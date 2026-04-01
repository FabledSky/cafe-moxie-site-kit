#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
PLUGIN_DIR="$ROOT_DIR/plugin"
DIST_DIR="$ROOT_DIR/dist"
PLUGIN_SLUG="cafe-moxie-site-kit-enterprise-ready-fixed"

mkdir -p "$DIST_DIR"
rm -f "$DIST_DIR/${PLUGIN_SLUG}.zip"

TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

mkdir -p "$TMP_DIR/$PLUGIN_SLUG"
cp -R "$PLUGIN_DIR"/. "$TMP_DIR/$PLUGIN_SLUG/"

(
  cd "$TMP_DIR"
  zip -rq "$DIST_DIR/${PLUGIN_SLUG}.zip" "$PLUGIN_SLUG"
)

echo "Created: $DIST_DIR/${PLUGIN_SLUG}.zip"
