import csv
import re
from pathlib import Path

INPUT = Path('storage/app/public/products/catalogo-texto.txt')
OUTPUT = Path('storage/app/public/products/catalogo.csv')

QUANTITY_PATTERN = re.compile(r'^(?:\d+\s*(?:mts|m|cm|mm|"|’|”)?|100\s*mts|50\s*mts|40\s*mts)$', re.IGNORECASE)
PAGE_PATTERN = re.compile(r'^(?:PÁGINA|PAGINA|ACTUALIZADO|CATÁLOGO|CATALOGO)$', re.IGNORECASE)


def clean_line(line: str) -> str:
    return line.strip().replace('\u201c', '"').replace('\u201d', '"').replace('\u2019', "'")


def is_code_candidate(token: str) -> bool:
    if not token or token.isdigit() or len(token) > 20:
        return False
    return bool(re.search(r'\d', token) and re.search(r'[A-Z]', token, re.IGNORECASE))


def is_code_line(line: str) -> bool:
    if not line:
        return False
    if PAGE_PATTERN.match(line):
        return False
    first_token = line.split()[0]
    return is_code_candidate(first_token)


def is_quantity_line(line: str) -> bool:
    return bool(QUANTITY_PATTERN.match(line.strip()))


def parse_lines(lines):
    items = []
    current = {}
    current_page = None

    for raw in lines:
        line = clean_line(raw)
        if not line or line.startswith('=') or line.isdigit():
            continue

        if line.upper().startswith('PÁGINA'):
            pieces = line.split()
            if len(pieces) > 1 and pieces[1].isdigit():
                current_page = int(pieces[1])
            continue

        if is_code_line(line):
            if current.get('code') and current.get('name'):
                current['page'] = current_page
                items.append(current)
                current = {}

            parts = line.split(maxsplit=1)
            code = parts[0].strip()
            current['code'] = code
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
        current['page'] = current_page
        items.append(current)

    return items


def main():
    if not INPUT.exists():
        raise FileNotFoundError(f'Input file not found: {INPUT}')

    text = INPUT.read_text(encoding='utf-8', errors='ignore')
    lines = text.splitlines()
    items = parse_lines(lines)

    with OUTPUT.open('w', newline='', encoding='utf-8') as file:
        writer = csv.DictWriter(file, fieldnames=['code', 'name', 'unit', 'category', 'image', 'page'])
        writer.writeheader()
        for item in items:
            writer.writerow({
                'code': item.get('code', ''),
                'name': item.get('name', ''),
                'unit': item.get('unit', ''),
                'category': '',
                'image': '',
                'page': item.get('page', ''),
            })

    print(f'Parsed {len(items)} products to {OUTPUT}')


if __name__ == '__main__':
    main()
