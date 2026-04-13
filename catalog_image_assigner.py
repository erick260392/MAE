import csv
import math
import re
from pathlib import Path

import fitz

PDF_PATH = Path('storage/app/public/products/ASAI-Internacional-CATALOGO-GENERAL-11-11.pdf')
CSV_PATH = Path('storage/app/public/products/catalogo.csv')
OUTPUT_CSV = Path('storage/app/public/products/catalogo-with-images.csv')
IMAGE_DIR = Path('storage/app/public/products/catalog_images_assigned')

MIN_IMG_AREA = 80 * 80
MAX_IMG_WIDTH = 1000
MAX_IMG_HEIGHT = 1000


def parse_csv():
    if not CSV_PATH.exists():
        raise FileNotFoundError(f'CSV not found: {CSV_PATH}')

    rows = []
    with CSV_PATH.open('r', encoding='utf-8', newline='') as file:
        reader = csv.DictReader(file)
        for row in reader:
            row['page'] = int(row['page']) if row.get('page') and row['page'].isdigit() else None
            row['image'] = row.get('image', '')
            rows.append(row)
    return rows


def is_code_candidate(value):
    if not value:
        return False

    value = value.strip().rstrip(':')

    if value.isdigit():
        return False

    return bool(re.search(r'\d', value) and re.search(r'[A-Z]', value, re.IGNORECASE))


def is_quantity_line(line):
    return any(unit in line.upper() for unit in ['ML', 'M', 'CM', 'PZ', 'FT', 'IN', 'MM', 'PZA', 'MX'])


def get_image_blocks(page):
    blocks = page.get_text('dict')['blocks']
    image_blocks = []
    for b in blocks:
        if b['type'] != 1:
            continue
        width = b['width']
        height = b['height']
        if width < 50 or height < 50:
            continue
        if width > MAX_IMG_WIDTH or height > MAX_IMG_HEIGHT:
            continue
        if width * height < MIN_IMG_AREA:
            continue

        image_blocks.append({
            'bbox': b['bbox'],
            'width': width,
            'height': height,
            'bytes': b['image'],
            'ext': b['ext'],
            'page': page.number + 1,
            'center_y': (b['bbox'][1] + b['bbox'][3]) / 2,
            'center_x': (b['bbox'][0] + b['bbox'][2]) / 2,
        })
    return image_blocks


def parse_product_items(text):
    lines = [line.strip() for line in text.splitlines() if line.strip()]
    items = []
    current = {}

    for line in lines:
        if is_code_candidate(line.split()[0]):
            if current.get('code') and current.get('name'):
                items.append(current)
                current = {}
            parts = line.split(maxsplit=1)
            current['code'] = parts[0].strip()
            current['name'] = parts[1].strip() if len(parts) > 1 else ''
            current['unit'] = ''
            continue

        if current and not current['name']:
            current['name'] = line
            continue

        if current and not current['unit'] and is_quantity_line(line):
            current['unit'] = line
            continue

        if current and current['name'] and line and not current['unit']:
            if line.startswith('MANGUERA') or line.startswith('REGULADOR') or line.startswith('CONECTOR'):
                current['name'] += ' ' + line
            elif is_quantity_line(line):
                current['unit'] = line
            else:
                current['name'] += ' ' + line

    if current.get('code') and current.get('name'):
        items.append(current)

    return items


def parse_product_blocks(page):
    blocks = page.get_text('dict')['blocks']
    groups = []
    for b in blocks:
        if b['type'] != 0:
            continue
        text = ''.join(span['text'] for line in b['lines'] for span in line['spans']).strip()
        if not text:
            continue
        items = parse_product_items(text)
        if not items:
            continue
        groups.append({
            'bbox': b['bbox'],
            'center_y': (b['bbox'][1] + b['bbox'][3]) / 2,
            'center_x': (b['bbox'][0] + b['bbox'][2]) / 2,
            'items': items,
        })
    return groups


def save_image(image_block, index):
    IMAGE_DIR.mkdir(parents=True, exist_ok=True)
    filename = f'page{image_block["page"]:02d}_{index:02d}.{image_block["ext"]}'
    path = IMAGE_DIR / filename
    path.write_bytes(image_block['bytes'])
    return path.relative_to('storage/app/public')


def assign_block_images(groups, image_blocks):
    if not image_blocks or not groups:
        return {}

    assigned = {}
    available_images = list(image_blocks)

    for group in sorted(groups, key=lambda g: (g['center_y'], g['center_x'])):
        if available_images:
            nearest = min(
                available_images,
                key=lambda img: math.hypot(img['center_x'] - group['center_x'], img['center_y'] - group['center_y']),
            )
            available_images.remove(nearest)
        else:
            nearest = min(
                image_blocks,
                key=lambda img: math.hypot(img['center_x'] - group['center_x'], img['center_y'] - group['center_y']),
            )

        for item in group['items']:
            assigned[item['code']] = str(nearest['path'])

    return assigned


def write_output(rows):
    with OUTPUT_CSV.open('w', encoding='utf-8', newline='') as file:
        writer = csv.DictWriter(file, fieldnames=['code', 'name', 'unit', 'category', 'image', 'page'])
        writer.writeheader()
        for row in rows:
            writer.writerow(row)


def main():
    if not PDF_PATH.exists():
        raise FileNotFoundError(f'PDF not found: {PDF_PATH}')

    rows = parse_csv()
    doc = fitz.open(PDF_PATH)

    images_by_page = {}
    for page_no in range(len(doc)):
        page = doc[page_no]
        image_blocks = get_image_blocks(page)
        if not image_blocks:
            continue
        saved = []
        for index, img in enumerate(sorted(image_blocks, key=lambda x: (x['center_y'], x['center_x'])), start=1):
            path = save_image(img, index)
            saved.append({**img, 'path': str(path)})
        images_by_page[page_no + 1] = saved

    for page_no in range(len(doc)):
        page = doc[page_no]
        page_number = page_no + 1
        groups = parse_product_blocks(page)
        page_images = images_by_page.get(page_number, [])
        assigned = assign_block_images(groups, page_images)

        for row in rows:
            if row.get('page') == page_number and row.get('code') in assigned:
                row['image'] = assigned[row['code']]

    # fallback: page-level first image
    for row in rows:
        if row['image']:
            continue
        page_images = images_by_page.get(row.get('page'), [])
        if page_images:
            row['image'] = str(page_images[0]['path'])

    write_output(rows)
    print(f'Written {len(rows)} products with image assignments to {OUTPUT_CSV}')


if __name__ == '__main__':
    main()
