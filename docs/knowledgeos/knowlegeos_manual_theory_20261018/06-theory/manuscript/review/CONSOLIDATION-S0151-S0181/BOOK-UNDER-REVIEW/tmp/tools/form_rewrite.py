#!/usr/bin/env python3
"""Form-only rewrite of a Ch. 0–2 section file (the Principal's instruction, 2026-10-19: "one section of Ch. 0–2
rewritten per reading window … form only, no new claims; reviewed in that window").

What it does, and nothing else:
  1. numbers the claim heads (lines starting with **Capitalised…**) per subsection as "<sub>-<k>." (e.g. 1.13.2-3),
     except explanatory blocks named in SKIP; claims outside any ### subsection are numbered "<sec>-<k>";
  2. inserts, after the H2 title, a status/lineage header, a plain-language summary and a claims index;
  3. appends open questions, "what does not hold" and a references table (S-IDs and source abbreviations found in the text);
  4. checks: with the inserted labels removed, the original body is byte-identical.

The prose of blocks 2–3 is supplied by the Professor in a small text file (sections separated by lines "@@ NAME"):
  @@ LINEAGE, @@ PLAIN, @@ OPEN, @@ WDNH, @@ ABBREV (lines "ID | description | path | used in").
They must only point to text already in the section; the reviewer checks that.

Usage: python3 -I form_rewrite.py <section.md> <window> <blocks.txt> [--dry]
"""
import json, pathlib, re, sys

SKIP = ('**Classification', '**Source added', '**In plain words', '**In business terms', '**Notation',
        '**Acts and their records', '**Two time axes', '**Open parameters', '**Five things', '**Normative effects',
        '**Definitions (Ded', '**The answer: a headline')
W = pathlib.Path(__file__).resolve().parents[2]


def blocks(path):
    out, cur = {}, None
    for l in pathlib.Path(path).read_text(encoding='utf-8').split('\n'):
        if l.startswith('@@ '):
            cur = l[3:].strip(); out[cur] = []
        elif cur:
            out[cur].append(l)
    return {k: '\n'.join(v).strip() for k, v in out.items()}


def main():
    src, window, bfile = sys.argv[1], sys.argv[2], sys.argv[3]
    dry = '--dry' in sys.argv
    p = pathlib.Path(src); orig = p.read_text(encoding='utf-8')
    L = orig.split('\n')
    assert L[0].startswith('## '), 'first line must be the H2 title'
    sec = re.match(r'## (\d+\.\d+)', L[0]).group(1)
    sub, n, out, index = sec, 0, [], []
    for l in L[1:]:
        m = re.match(r'### (\d+\.\d+\.\d+[a-z]?) ', l)
        if m:
            sub, n = m.group(1), 0; out.append(l); continue
        if re.match(r'\*\*[A-Z]', l) and not l.startswith(SKIP):
            n += 1
            head = re.match(r'\*\*(.+?)\*\*', l).group(1).rstrip('.').rstrip(':')
            index.append((sub, f'{sub}-{n}', head))
            l = f'**{sub}-{n}. ' + l[2:]
        out.append(l)
    body = '\n'.join(out)
    B = blocks(bfile)
    files = {json.loads(x)['source_id']: json.loads(x)['path'] for x in open(W / '02-FILES.jsonl', encoding='utf-8')}
    sids = sorted(set(re.findall(r'\bS\d{4}\b', orig)))
    head = (f"{L[0]}\n\n*Rewritten in **form only** in window {window} (2026-10-19), under the Principal's instruction \"one section of Ch. 0–2 rewritten per reading window … form only, no new claims\". **No claim was added, removed or changed in the body.** Claim heads are labelled {sec}.k-j (or {sec}-j outside subsections); a plain-language summary, a claims index, open questions, \"what does not hold\" and references are added, each pointing only to text already in the section. Earlier status, reviews and corrections stand as recorded in the chapter's change log and the review folders. **Status of the rewrite: provisional, before review in window {window}.***\n\n"
            f"*Lineage of the main sources (Int, added at the rewrite):* {B.get('LINEAGE','')}\n\n"
            f"### {sec}.0 In plain language\n\n{B.get('PLAIN','')}\n\n### Claims index\n\n"
            "*The index covers the numbered heads only; claims inside unnumbered blocks are not indexed.*\n\n"
            "| § | No. | Claim (its head, as written) |\n|---|---|---|\n"
            + '\n'.join(f'| {s} | {k} | {h.replace("|", "/")} |' for s, k, h in index) + '\n\n')
    refs = "### References\n\n| Source | ID | Path | Used in |\n|---|---|---|---|\n"
    for line in B.get('ABBREV', '').split('\n'):
        if line.strip():
            refs += '| ' + line.strip() + ' |\n'
    refs += ''.join(f'| corpus source | {s} | `{files.get(s, "?")}` | as cited |\n' for s in sids)
    tail = (f"\n### {sec}.98 Open questions (each stated above; collected here)\n\n{B.get('OPEN','')}\n\n"
            f"### {sec}.99 What does not hold (each stated above; collected here)\n\n{B.get('WDNH','')}\n\n" + refs)
    new = head + body.rstrip() + '\n' + tail
    stripped = re.sub(r'\*\*\d+\.\d+(?:\.\d+[a-z]?)?-\d+\. ', '**', new)
    a = stripped.index(L[1] if L[1].strip() else L[2]) if False else None
    core_new = re.sub(r'\*\*\d+\.\d+(?:\.\d+[a-z]?)?-\d+\. ', '**', body)
    ok = core_new.rstrip() == '\n'.join(L[1:]).rstrip()
    print(f'{p.name}: {len(index)} claims numbered; body identical after stripping labels: {ok}')
    assert ok
    if not dry:
        p.write_text(new, encoding='utf-8')


if __name__ == '__main__':
    main()
