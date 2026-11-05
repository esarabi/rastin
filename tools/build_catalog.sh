#!/usr/bin/env bash
# Build the Rastin catalog: digital PDF, print PDF (3 mm bleed) and PNG page previews.
# Needs: php, node + playwright (Chromium).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
OUT="$ROOT/catalog/build"
mkdir -p "$OUT"
php "$ROOT/catalog/template.php" 0 > "$OUT/catalog.html"
php "$ROOT/catalog/template.php" 3 > "$OUT/catalog-print.html"
node "$ROOT/tools/catalog_pdf.js" "$OUT"
ls -la "$OUT"
