#!/usr/bin/env python3
"""Regenerate the auto TOC block in agents.md based on markdown headings."""

from __future__ import annotations

import pathlib
import re
import sys
from collections import defaultdict

REPO_ROOT = pathlib.Path(__file__).resolve().parents[1]
AGENTS_PATH = REPO_ROOT / "agents.md"
BASE_LINK = "https://github.com/FabledSky/cafe-moxie-site-kit/blob/main/agents.md#"
BEGIN = "<!-- BEGIN AUTO TOC -->"
END = "<!-- END AUTO TOC -->"
HEADER_RE = re.compile(r"^(#{2,4})\s+(.+?)\s*$")
FENCE_RE = re.compile(r"^```")


def github_anchor(text: str) -> str:
    text = text.strip().lower()
    text = re.sub(r"[`*_~\[\]()]", "", text)
    text = re.sub(r"[^\w\s\-.]", "", text)
    text = text.replace(".", "")
    text = re.sub(r"\s", "-", text)
    return text.strip("-")


def collect_headings(lines: list[str]) -> list[tuple[int, str, str]]:
    in_fence = False
    anchors_seen: defaultdict[str, int] = defaultdict(int)
    headings: list[tuple[int, str, str]] = []

    for line in lines:
        if FENCE_RE.match(line):
            in_fence = not in_fence
            continue
        if in_fence:
            continue

        m = HEADER_RE.match(line)
        if not m:
            continue

        level = len(m.group(1))
        title = m.group(2).strip()
        if title.lower() == "table of contents":
            continue

        anchor = github_anchor(title)
        count = anchors_seen[anchor]
        anchors_seen[anchor] += 1
        if count:
            anchor = f"{anchor}-{count}"

        headings.append((level, title, anchor))

    return headings


def build_toc(headings: list[tuple[int, str, str]]) -> str:
    lines = [BEGIN]
    for level, title, anchor in headings:
        indent = "  " * (level - 2)
        lines.append(f"{indent}* [{title}]({BASE_LINK}{anchor})")
    lines.append(END)
    return "\n".join(lines)


def replace_toc(content: str, toc_block: str) -> str:
    marker_pattern = re.compile(rf"^\s*{re.escape(BEGIN)}\s*$[\s\S]*?^\s*{re.escape(END)}\s*$", re.MULTILINE)
    if marker_pattern.search(content):
        return marker_pattern.sub(toc_block, content, count=1)

    heading_pattern = re.compile(r"^##\s+Table of Contents\s*$", re.MULTILINE)
    match = heading_pattern.search(content)
    if not match:
        raise RuntimeError("Could not find '## Table of Contents' heading and no auto TOC markers exist.")

    insert_at = match.end()
    return content[:insert_at] + "\n\n" + toc_block + content[insert_at:]


def main() -> int:
    content = AGENTS_PATH.read_text(encoding="utf-8")
    lines = content.splitlines()
    headings = collect_headings(lines)
    toc_block = build_toc(headings)
    updated = replace_toc(content, toc_block)

    if updated != content:
        AGENTS_PATH.write_text(updated, encoding="utf-8")
        print("Updated agents.md TOC block.")
    else:
        print("agents.md TOC already up to date.")

    return 0


if __name__ == "__main__":
    sys.exit(main())
