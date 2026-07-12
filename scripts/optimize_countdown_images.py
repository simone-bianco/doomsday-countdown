#!/usr/bin/env python3
"""Create losslessly optimized public countdown images from preserved originals.

The source files are never modified. Output PNGs keep the original pixel
resolution and color data while stripping ancillary metadata and using maximum
PNG compression. If recompression is larger than the source, the original byte
stream is copied instead.
"""

from __future__ import annotations

import argparse
import hashlib
import shutil
import sys
import tempfile
from dataclasses import dataclass
from pathlib import Path

from PIL import Image, ImageOps


DEFAULT_MAPPING: dict[str, str] = {
    "russia_invade_europa.png": "fall_of_europe_separate.png",
    "caldo_malsano.png": "extreme_heat_breakpoint_separate.png",
    "biodiversita.png": "uninhabitable_earth_separate.png",
}


@dataclass(frozen=True)
class Result:
    source: Path
    target: Path
    width: int
    height: int
    source_bytes: int
    target_bytes: int
    source_sha256: str
    target_sha256: str

    @property
    def saved_bytes(self) -> int:
        return self.source_bytes - self.target_bytes

    @property
    def saved_percent(self) -> float:
        if self.source_bytes == 0:
            return 0.0
        return self.saved_bytes / self.source_bytes * 100


def sha256(path: Path) -> str:
    digest = hashlib.sha256()
    with path.open("rb") as stream:
        for chunk in iter(lambda: stream.read(1024 * 1024), b""):
            digest.update(chunk)
    return digest.hexdigest()


def parse_mapping(values: list[str] | None) -> dict[str, str]:
    if not values:
        return DEFAULT_MAPPING.copy()

    mapping: dict[str, str] = {}
    for value in values:
        source, separator, target = value.partition("=")
        if not separator or not source.strip() or not target.strip():
            raise ValueError(
                f"Invalid mapping {value!r}; expected SOURCE.png=TARGET.png"
            )
        mapping[source.strip()] = target.strip()
    return mapping


def optimize_png(source: Path, target: Path) -> Result:
    if not source.is_file():
        raise FileNotFoundError(f"Source image not found: {source}")
    if source.suffix.lower() != ".png" or target.suffix.lower() != ".png":
        raise ValueError("Lossless optimizer currently accepts PNG -> PNG only")

    target.parent.mkdir(parents=True, exist_ok=True)
    source_size = source.stat().st_size
    source_digest = sha256(source)

    with Image.open(source) as opened:
        image = ImageOps.exif_transpose(opened)
        image.load()
        width, height = image.size

        # Copy pixels into a fresh image to omit EXIF/text/ICC ancillary chunks.
        clean = Image.new(image.mode, image.size)
        clean.paste(image)

        with tempfile.NamedTemporaryFile(
            dir=target.parent,
            prefix=f".{target.stem}.",
            suffix=".tmp.png",
            delete=False,
        ) as temporary:
            temporary_path = Path(temporary.name)

        try:
            clean.save(
                temporary_path,
                format="PNG",
                optimize=True,
                compress_level=9,
            )

            # Maximum compression may occasionally be larger than an already
            # optimized source. Preserve the smaller lossless representation.
            if temporary_path.stat().st_size >= source_size:
                shutil.copyfile(source, temporary_path)

            temporary_path.replace(target)
        finally:
            temporary_path.unlink(missing_ok=True)

    # Guard against accidental source mutation.
    if sha256(source) != source_digest:
        raise RuntimeError(f"Source changed during optimization: {source}")

    return Result(
        source=source,
        target=target,
        width=width,
        height=height,
        source_bytes=source_size,
        target_bytes=target.stat().st_size,
        source_sha256=source_digest,
        target_sha256=sha256(target),
    )


def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "--source-dir",
        type=Path,
        default=Path("z-docs/images"),
        help="Directory containing untouched source PNG files",
    )
    parser.add_argument(
        "--target-dir",
        type=Path,
        default=Path("public/images/doomsday"),
        help="Directory receiving optimized public PNG files",
    )
    parser.add_argument(
        "--map",
        action="append",
        metavar="SOURCE=TARGET",
        help="Override default filename mapping; repeat for multiple files",
    )
    return parser


def main() -> int:
    args = build_parser().parse_args()

    try:
        mapping = parse_mapping(args.map)
        results = [
            optimize_png(
                args.source_dir / source_name,
                args.target_dir / target_name,
            )
            for source_name, target_name in mapping.items()
        ]
    except (FileNotFoundError, OSError, RuntimeError, ValueError) as error:
        print(f"error: {error}", file=sys.stderr)
        return 1

    total_source = sum(result.source_bytes for result in results)
    total_target = sum(result.target_bytes for result in results)

    for result in results:
        print(
            f"{result.source.name} -> {result.target.name} | "
            f"{result.width}x{result.height} | "
            f"{result.source_bytes:,} -> {result.target_bytes:,} bytes | "
            f"saved {result.saved_percent:.2f}% | "
            f"sha256 {result.target_sha256[:12]}"
        )

    saved = total_source - total_target
    percent = saved / total_source * 100 if total_source else 0.0
    print(
        f"TOTAL | {total_source:,} -> {total_target:,} bytes | "
        f"saved {saved:,} bytes ({percent:.2f}%)"
    )
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
