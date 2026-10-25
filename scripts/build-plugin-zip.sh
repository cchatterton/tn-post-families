#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLUGIN_SLUG="tn-post-families"
DIST_DIR="$ROOT_DIR/dist"

rm -rf "$DIST_DIR/$PLUGIN_SLUG"
rm -f "$DIST_DIR/$PLUGIN_SLUG.zip" "$ROOT_DIR/$PLUGIN_SLUG.zip"
mkdir -p "$DIST_DIR"
cp -R "$ROOT_DIR/$PLUGIN_SLUG" "$DIST_DIR/$PLUGIN_SLUG"
find "$DIST_DIR/$PLUGIN_SLUG" -name '.DS_Store' -delete

(
    cd "$DIST_DIR"
    zip -qr "$PLUGIN_SLUG.zip" "$PLUGIN_SLUG"
)
cp "$DIST_DIR/$PLUGIN_SLUG.zip" "$ROOT_DIR/$PLUGIN_SLUG.zip"
