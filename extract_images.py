import fitz
import os

pdf_path = "storage/app/public/products/ASAI-Internacional-CATALOGO-GENERAL-11-11.pdf"
output_dir = "storage/app/public/products/asai"

os.makedirs(output_dir, exist_ok=True)

doc = fitz.open(pdf_path)
print(f"Total páginas: {len(doc)}")

img_count = 0
for page_num in range(len(doc)):
    page = doc[page_num]
    images = page.get_images(full=True)

    for img_index, img in enumerate(images):
        xref = img[0]
        base_image = doc.extract_image(xref)
        img_bytes = base_image["image"]
        img_ext = base_image["ext"]
        width = base_image["width"]
        height = base_image["height"]

        # Solo guardar imágenes de tamaño razonable (no iconos ni fondos)
        if width > 100 and height > 100 and len(img_bytes) > 5000:
            filename = f"{output_dir}/p{page_num+1:03d}_img{img_index+1:02d}.{img_ext}"
            with open(filename, "wb") as f:
                f.write(img_bytes)
            img_count += 1
            print(f"  Página {page_num+1} img{img_index+1}: {width}x{height} {img_ext} ({len(img_bytes)//1024}KB) → {filename}")

print(f"\nTotal imágenes extraídas: {img_count}")
doc.close()
