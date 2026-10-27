#!/usr/bin/env python3
"""Split a single-file chapter into section files along its `## ` headings (Rulebook §4, METHOD-VERSIONS entry 44).

- The text before the first `## ` heading (the chapter's preamble) goes, unchanged, to the top of NN.00-index.md.
  A marker line follows it, then a generated table of the section files. Only the marker and the table are new.
- Each `## ` heading starts a section file NN.MM-<short-name>.md holding that heading and everything up to the
  next `## ` heading, byte for byte. Section numbers are taken from the headings ("## 1.14 …" → 01.14);
  a "## 2.0 …" section becomes 02.00a (so it sorts after the index); an unnumbered heading ("## Change log")
  becomes NN.99-<name>.
- Check, before anything is written: preamble + the concatenated section texts == the original, byte for byte.
  After writing, the same check is repeated on the files on disk. The original file is not deleted by this script.

Usage: python3 -I split_chapter.py <chapter_dir>/<CH-file.md> [--dry]
"""
import pathlib, re, sys, hashlib

MARK = '<!-- split_chapter.py: everything above this line is the original chapter preamble, byte for byte; the table below is generated -->\n'


def slug(title, words=5):
    t = re.sub(r'[^\w\s-]', ' ', title.lower())
    return '-'.join(t.split()[:words])


def plan(src):
    text = src.read_text(encoding='utf-8')
    lines = text.splitlines(keepends=True)
    starts = [i for i, l in enumerate(lines) if l.startswith('## ')]
    assert starts, 'no ## headings'
    preamble = ''.join(lines[:starts[0]])
    chap = re.match(r'CH-(\d\d)', src.name).group(1)
    parts = []
    for k, i in enumerate(starts):
        j = starts[k + 1] if k + 1 < len(starts) else len(lines)
        head = lines[i][3:].strip()
        m = re.match(r'(\d+)\.(\d+)\s+(.*)', head)
        if m:
            num = f'{int(m.group(1)):02d}.{int(m.group(2)):02d}'
            if m.group(2) == '0':
                num += 'a'
            name = f'{num}-{slug(m.group(3))}.md'
        else:
            name = f'{chap}.99-{slug(head)}.md'
        parts.append((name, head, ''.join(lines[i:j])))
    assert preamble + ''.join(p[2] for p in parts) == text, 'join check failed before writing'
    assert len({p[0] for p in parts}) == len(parts), 'duplicate section file names'
    return text, preamble, parts, chap


def index_text(preamble, parts):
    rows = '\n'.join(f'| {p[1].split(" ", 1)[0] if re.match(r"\d", p[1]) else "—"} | `{p[0]}` | {p[1].split(" ", 1)[1] if re.match(r"\d", p[1]) else p[1]} |' for p in parts)
    return preamble + MARK + '\n## Sections\n\n| § | File | Subject |\n|---|---|---|\n' + rows + '\n'


def main():
    src = pathlib.Path(sys.argv[1]).resolve()
    dry = '--dry' in sys.argv
    text, preamble, parts, chap = plan(src)
    idx = src.parent / f'{chap}.00-index.md'
    print(f'{src.name}: {len(text.encode())} bytes, sha256 {hashlib.sha256(text.encode()).hexdigest()[:16]}…, {len(parts)} sections')
    for p in parts:
        print(f'  {p[0]:<58} {len(p[2].encode()):>7} bytes')
    if dry:
        return
    assert not idx.exists(), f'{idx.name} exists already'
    for p in parts:
        assert not (src.parent / p[0]).exists(), f'{p[0]} exists already'
    idx.write_text(index_text(preamble, parts), encoding='utf-8')
    for p in parts:
        (src.parent / p[0]).write_text(p[2], encoding='utf-8')
    # re-check from disk
    head = idx.read_text(encoding='utf-8').split(MARK)[0]
    joined = head + ''.join((src.parent / p[0]).read_text(encoding='utf-8') for p in parts)
    assert joined == text, 'join check failed after writing'
    print(f'  written; join check on disk: identical ({hashlib.sha256(joined.encode()).hexdigest()[:16]}…)')


if __name__ == '__main__':
    main()
