from __future__ import annotations

import json
import os
import shutil
from dataclasses import dataclass
from pathlib import Path

from PIL import Image, features

ROOT = Path(__file__).resolve().parents[1]
PUBLIC_DOOMSDAY = ROOT / "public" / "images" / "doomsday"
RESPONSIVE_DIR = PUBLIC_DOOMSDAY / "responsive"
ICONS_DIR = ROOT / "public" / "icons"
FAVICON_SOURCE = ROOT / "z-docs" / "images" / "logo_without_text.png"

WEBP_QUALITY = 82
AVIF_QUALITY = 55
FAVICON_CONTENT_RATIO = 0.86


@dataclass(frozen=True)
class AssetSpec:
    source: str
    public_name: str
    expected_size: tuple[int, int]
    widths: tuple[int, ...]
    copy_png_fallback: bool = False


ASSETS: tuple[AssetSpec, ...] = (
    AssetSpec(
        "resources/js/assets/doomsday/doomsday_hero_background_desktop.png",
        "doomsday_hero_background_desktop",
        (1536, 1024),
        (640, 960, 1280, 1536),
        True,
    ),
    AssetSpec(
        "resources/js/assets/doomsday/doomsday_hero_background_mobile.png",
        "doomsday_hero_background_mobile",
        (1440, 2560),
        (480, 720, 1080, 1440),
        True,
    ),
    AssetSpec("public/images/doomsday/ai_job_apocalypse.png", "ai_job_apocalypse", (1672, 941), (480, 768, 1200, 1672)),
    AssetSpec("public/images/doomsday/antibiotic_apocalypse.png", "antibiotic_apocalypse", (1535, 1024), (480, 768, 1200, 1535)),
    AssetSpec("public/images/doomsday/europe_war_countdown.png", "europe_war_countdown", (1672, 941), (480, 768, 1200, 1672)),
    AssetSpec("public/images/doomsday/extreme_heat_breakpoint_separate.png", "extreme_heat_breakpoint_separate", (1671, 941), (480, 768, 1200, 1671)),
    AssetSpec("public/images/doomsday/taiwan_invasion.png", "taiwan_invasion", (1672, 941), (480, 768, 1200, 1672)),
    AssetSpec("public/images/doomsday/uninhabitable_earth_separate.png", "uninhabitable_earth_separate", (1536, 1024), (480, 768, 1200, 1536)),
    AssetSpec("public/images/doomsday/society_collapse_separate.png", "society_collapse_separate", (536, 473), (320, 536)),
)


def ensure_encoder_support() -> None:
    Image.init()
    missing: list[str] = []
    if not features.check("webp"):
        missing.append("WebP")
    if not features.check("avif"):
        missing.append("AVIF")
    if "ICO" not in Image.SAVE:
        missing.append("ICO")

    if missing:
        raise RuntimeError(f"Pillow encoder support missing: {', '.join(missing)}")


def verify_source(path: Path, expected_size: tuple[int, int]) -> Image.Image:
    if not path.is_file():
        raise FileNotFoundError(f"Missing approved source: {path.relative_to(ROOT)}")

    image = Image.open(path)
    image.load()
    if image.format != "PNG":
        raise RuntimeError(f"Expected PNG source for {path.relative_to(ROOT)}, got {image.format}")
    if image.size != expected_size:
        raise RuntimeError(
            f"Source dimensions changed for {path.relative_to(ROOT)}: expected {expected_size}, got {image.size}"
        )

    return image.convert("RGBA" if "A" in image.getbands() else "RGB")


def atomic_save(image: Image.Image, target: Path, image_format: str, **options: object) -> None:
    target.parent.mkdir(parents=True, exist_ok=True)
    temporary = target.with_name(f".{target.stem}.tmp{target.suffix}")
    image.save(temporary, format=image_format, **options)
    os.replace(temporary, target)


def copy_fallback(source: Path, target: Path) -> None:
    target.parent.mkdir(parents=True, exist_ok=True)
    if target.is_file() and source.read_bytes() == target.read_bytes():
        return

    temporary = target.with_name(f".{target.name}.tmp")
    shutil.copyfile(source, temporary)
    os.replace(temporary, target)


def resized(image: Image.Image, width: int) -> Image.Image:
    height = round(image.height * width / image.width)
    return image.resize((width, height), Image.Resampling.LANCZOS)


def generate_responsive_assets() -> list[Path]:
    generated: list[Path] = []
    for spec in ASSETS:
        source = ROOT / spec.source
        image = verify_source(source, spec.expected_size)

        if spec.copy_png_fallback:
            fallback = PUBLIC_DOOMSDAY / f"{spec.public_name}.png"
            copy_fallback(source, fallback)
            generated.append(fallback)

        for width in spec.widths:
            variant = resized(image, width)
            webp = RESPONSIVE_DIR / f"{spec.public_name}-{width}.webp"
            avif = RESPONSIVE_DIR / f"{spec.public_name}-{width}.avif"
            atomic_save(variant, webp, "WEBP", quality=WEBP_QUALITY, method=6)
            atomic_save(variant, avif, "AVIF", quality=AVIF_QUALITY, speed=6)
            generated.extend((webp, avif))

    return generated


def favicon_master() -> Image.Image:
    source = verify_source(FAVICON_SOURCE, (174, 187))
    master_size = 1024
    content_size = round(master_size * FAVICON_CONTENT_RATIO)
    scale = min(content_size / source.width, content_size / source.height)
    resized_source = source.resize(
        (round(source.width * scale), round(source.height * scale)),
        Image.Resampling.LANCZOS,
    )
    master = Image.new("RGBA", (master_size, master_size), (0, 0, 0, 0))
    offset = ((master_size - resized_source.width) // 2, (master_size - resized_source.height) // 2)
    master.alpha_composite(resized_source, offset)
    return master


def generate_favicon_family() -> list[Path]:
    master = favicon_master()
    public = ROOT / "public"
    generated: list[Path] = []

    png_targets = {
        public / "favicon-16x16.png": 16,
        public / "favicon-32x32.png": 32,
        public / "apple-touch-icon.png": 180,
        ICONS_DIR / "icon-192.png": 192,
        ICONS_DIR / "icon-512.png": 512,
    }
    for target, size in png_targets.items():
        icon = master.resize((size, size), Image.Resampling.LANCZOS)
        atomic_save(icon, target, "PNG", optimize=True)
        generated.append(target)

    ico = public / "favicon.ico"
    atomic_save(master, ico, "ICO", sizes=[(16, 16), (32, 32), (48, 48)])
    generated.append(ico)

    manifest = {
        "name": "Doomsday Clock",
        "short_name": "Doomsday",
        "start_url": "/",
        "scope": "/",
        "display": "standalone",
        "background_color": "#050505",
        "theme_color": "#050505",
        "icons": [
            {"src": "/icons/icon-192.png", "sizes": "192x192", "type": "image/png", "purpose": "any"},
            {"src": "/icons/icon-512.png", "sizes": "512x512", "type": "image/png", "purpose": "any"},
        ],
    }
    manifest_path = public / "site.webmanifest"
    manifest_path.write_bytes((json.dumps(manifest, indent=2, ensure_ascii=False) + "\n").encode("utf-8"))
    generated.append(manifest_path)

    return generated


def main() -> None:
    ensure_encoder_support()
    generated = generate_favicon_family() + generate_responsive_assets()

    print(f"Pillow {Image.__version__}: WebP={features.check('webp')} AVIF={features.check('avif')} ICO={'ICO' in Image.SAVE}")
    print("[DA VERIFICARE] favicon.svg skipped: approved source is raster and no non-tracing vector source exists.")
    for path in sorted(generated):
        print(f"{path.relative_to(ROOT).as_posix()}|{path.stat().st_size}")


if __name__ == "__main__":
    main()
