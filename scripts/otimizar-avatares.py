"""
Extrai o PNG embedado de cada SVG dos 3dicons, redimensiona para 128x128
e salva como WebP em assets/icon/avatars/.

Uso: python3 scripts/otimizar-avatares.py
"""

import base64
import re
import sys
from io import BytesIO
from pathlib import Path

try:
    from PIL import Image
except ImportError:
    sys.exit("Pillow nao instalado. Rode: pip install Pillow")

SRC_DIR  = Path(__file__).parent.parent / "assets/icon/3dicons - Open source 3D icon library (Community)"
DEST_DIR = Path(__file__).parent.parent / "assets/icon/avatars"
SIZE     = (128, 128)
QUALITY  = 85

PATTERN = re.compile(r'data:image/\w+;base64,([A-Za-z0-9+/=]+)')

def process(svg_path: Path) -> tuple[str, int, int]:
    raw = svg_path.read_text(encoding="utf-8")
    m = PATTERN.search(raw)
    if not m:
        return (svg_path.name, 0, 0)

    img_data = base64.b64decode(m.group(1))
    img = Image.open(BytesIO(img_data)).convert("RGBA")
    img = img.resize(SIZE, Image.LANCZOS)

    stem = svg_path.stem.replace("-dynamic-color", "")
    out_path = DEST_DIR / f"{stem}.webp"
    img.save(out_path, "WEBP", quality=QUALITY, method=6)

    return (stem, len(img_data), out_path.stat().st_size)

def main():
    DEST_DIR.mkdir(parents=True, exist_ok=True)

    svgs = sorted(SRC_DIR.glob("*-dynamic-color.svg"))
    if not svgs:
        sys.exit(f"Nenhum SVG encontrado em: {SRC_DIR}")

    print(f"Processando {len(svgs)} icones -> {DEST_DIR}\n")

    total_before = total_after = 0
    errors = []

    for svg in svgs:
        try:
            name, before, after = process(svg)
            total_before += before
            total_after  += after
            ratio = (1 - after / before) * 100 if before else 0
            print(f"  {name:<40} {before//1024:>4}KB -> {after//1024:>2}KB  (-{ratio:.0f}%)")
        except Exception as e:
            errors.append((svg.name, str(e)))
            print(f"  ERRO {svg.name}: {e}")

    print(f"\nTotal: {total_before//1024//1024}MB -> {total_after//1024}KB  "
          f"({(1 - total_after/total_before)*100:.1f}% reducao)")
    if errors:
        print(f"\n{len(errors)} erros:")
        for name, msg in errors:
            print(f"  {name}: {msg}")

if __name__ == "__main__":
    main()
