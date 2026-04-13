import fitz
from pathlib import Path

pdf_path = Path('storage/app/public/products/ASAI-Internacional-CATALOGO-GENERAL-11-11.pdf')
out_dir = Path('storage/app/public/products/catalog_images')
out_dir.mkdir(parents=True, exist_ok=True)

doc = fitz.open(pdf_path)

for page_number, page in enumerate(doc, start=1):
    for img_index, img in enumerate(page.get_images(full=True), start=1):
        xref = img[0]
        image_info = doc.extract_image(xref)
        img_bytes = image_info['image']
        ext = image_info.get('ext', 'png')
        if img_bytes is None:
            continue

        out_path = out_dir / f'page{page_number:02d}_{img_index:02d}_{xref}.{ext}'
        out_path.write_bytes(img_bytes)
        print(f'Extracted {out_path} ({image_info["width"]}x{image_info["height"]})')

doc.close()
print('Done')
