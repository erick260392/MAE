import argparse
import sys

try:
    import fitz
except ImportError:
    print('Error: Falta la librería PyMuPDF. Instálala con: pip install pymupdf', file=sys.stderr)
    sys.exit(1)

parser = argparse.ArgumentParser(description='Extrae texto de un PDF y lo imprime en consola.')
parser.add_argument('pdf_path', nargs='?', default='storage/app/public/products/catalogo.pdf', help='Ruta al archivo PDF')
parser.add_argument('--max-chars', type=int, default=800, help='Cantidad máxima de caracteres por página a mostrar')
args = parser.parse_args()

try:
    doc = fitz.open(args.pdf_path)
except Exception as exception:
    print(f'Error al abrir el PDF: {exception}', file=sys.stderr)
    sys.exit(1)

for page_num in range(len(doc)):
    page = doc[page_num]
    text = page.get_text().strip()
    if text:
        print(f"\n{'='*60}")
        print(f"PÁGINA {page_num + 1}")
        print('='*60)
        print(text[: args.max_chars])

if len(doc) == 0:
    print('El PDF no contiene texto extraíble.', file=sys.stderr)

doc.close()
