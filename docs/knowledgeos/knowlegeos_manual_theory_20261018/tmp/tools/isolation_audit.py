#!/usr/bin/env python3
"""Isolation audit of a reviewer's transcript (whole-book review S0100).

Reads a subagent transcript (JSONL), extracts every tool call's input, and classifies each against the review's
isolation rules: B0 none · B1 unlisted allowed file or unscoped search with no prohibited hit · B2 working book, OLD
texts, manuscript git history · B3 the key, the manifest, the access record or the scoring.

Usage: python3 -I isolation_audit.py LABEL TRANSCRIPT.jsonl [--grant]     (--grant: P1/P2, the twelve granted files)
Prints a markdown block. Reads only the transcript and the allowed list; writes nothing.
"""
import csv, json, pathlib, re, sys

LANE = 'docs/knowledgeos/knowlegeos_manual_theory_20261018/'
COPY = 'review/WHOLE-BOOK-S0100/BOOK-UNDER-REVIEW'
REPO = pathlib.Path(__file__).resolve().parents[5]
ALLOWED = {r[2] for r in csv.reader(open(REPO / 'docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv',
                                         encoding='utf-8'), delimiter='\t') if len(r) > 2}
GRANT = {'docs/implementation/' + f for f in (
    'KnowledgeOS_Product_Discovery_Charter.md', 'KnowledgeOS_Development_Constitution.md',
    'KnowledgeOS_Governance_Execution_Plan.md', 'Project_Knowledge_Architecture_Charter.md',
    'Project_Knowledge_Strategic_Model.md', 'RQ-002_Architectural_Synthesis.md',
    'RQ-002_General_Knowledge_Architecture_Constitutional_Model.md', 'RQ-002_Knowledge_Meta_Model.md',
    'RQ-002_Knowledge_Taxonomy.md', 'RQ-002_Project_Knowledge_Domain.md', 'RQ-002_Research_Findings_Raw.md',
    'RQ-002_Strategic_Discovery_Report.md')}

B3 = [r'answer.?key', r'MANIFEST', r'00-access', r'ACCESS-RECORD', r'SCOR(E|ING)', r'PROFESSOR', r'README-PROFESSOR']
HARD = [r'\.env', r'_batch_input', r'HOLDOUT', r'hold-out', r'\bseal', r'quarantine']
B2 = [r'-OLD-', r'HEADS-OLD-NEW', r'BASELINE-REVISIONS', r'03-methodology', r'/archive/']


def rel(p):
    p = str(p)
    i = p.find('docs/')
    return p[i:] if i >= 0 else p


def classify(text, grant):
    """Return (class, reason) for one tool input."""
    t = text
    for pat in HARD:
        if re.search(pat, t, re.I):
            return 'B3', f'hard-blocked pattern {pat}'
    for pat in B3:
        if re.search(pat, t):
            return 'B3', f'pattern {pat}'
    for pat in B2:
        if re.search(pat, t):
            return 'B2', f'pattern {pat}'
    # working book: 06-theory paths not inside the copy
    for m in re.finditer(r'06-theory/(manuscript/chapter_\d\d|THEORY-MAP\.md|CONCEPT-REGISTER\.tsv|BOOK-OUTLINE\.md|CHAPTER-PLAN\.md)', t):
        before = t[:m.start()]  # a `cd …/BOOK-UNDER-REVIEW` earlier in the command makes relative paths the copy's
        if 'BOOK-UNDER-REVIEW' not in before:
            return 'B2', f'working book: {m.group(0)}'
    if re.search(r'\bgit\b', t) and not re.search(r'git log --format', t):
        return 'B2', 'git command other than log --format'
    if re.search(r'(?<!BOOK-UNDER-REVIEW/)tmp/tools/book_search\.py', t) and 'BOOK-UNDER-REVIEW' not in t:  # the copy's tool may be reached via a shell variable holding the copy path
        return 'B2', 'lane-root book_search.py'
    if re.search(r'review/WHOLE-BOOK-S0100/(?!BOOK-UNDER-REVIEW|REVIEWER-NOTE|P[1-5]/BRIEF|P[1-5]/STEP2-COMMISSION|P[1-5]\b(?!/))', t):
        return 'B3', 'organisers\' files under the review folder'
    return None


def audit(label, path, grant):
    calls, out, seen = 0, [], set()
    for line in open(path, encoding='utf-8'):
        try:
            rec = json.loads(line)
        except ValueError:
            continue
        msg = rec.get('message') or {}
        if msg.get('role') != 'assistant':
            continue
        for b in msg.get('content') or []:
            if not isinstance(b, dict) or b.get('type') != 'tool_use':
                continue
            if 'andback' in (b.get('name') or ''):
                continue  # the hand-back report itself, not an access
            if b.get('id') in seen:
                continue
            seen.add(b.get('id'))
            calls += 1
            inp = b.get('input') or {}
            text = json.dumps(inp, ensure_ascii=False)
            c = classify(text, grant)
            if c:
                out.append((c[0], b.get('name'), c[1], text[:220]))
                continue
            fp = inp.get('file_path') or ''
            if fp:
                r = rel(fp)
                if r.startswith('docs/') and LANE not in r:
                    ok = r in ALLOWED or (grant and r in GRANT)
                    if not ok:
                        out.append(('B3' if 'knowledge_tranfer' in r or r.startswith('docs/implementation') else 'B1',
                                    b.get('name'), 'file not on the allowed list' + (' or grant' if grant else ''), r))
            if b.get('name') in ('Grep', 'Glob'):
                p = inp.get('path') or ''
                if not p or (COPY not in p and rel(p) not in ALLOWED and not (grant and rel(p) in GRANT)):
                    out.append(('B1', b.get('name'), 'search not scoped to the copy or a named source', text[:220]))
            if b.get('name') == 'Bash':
                cmd = inp.get('command', '')
                if re.search(r'\b(grep|rg|find)\b', cmd) and COPY not in cmd and 'CORPUS-ALLOWED.tsv' not in cmd and 'BRIEF.md' not in cmd and not any(a in cmd for a in ALLOWED if a):
                    out.append(('B1', 'Bash', 'search command with no copy or named-source scope', cmd[:220]))
    worst = max([o[0] for o in out], default='B0')
    print(f'### {label}\n- tool calls: {calls}; flagged: {len(out)}; **class: {worst}**')
    for o in out:
        print(f'  - {o[0]} · {o[1]} · {o[2]} · `{o[3]}`')
    return worst


if __name__ == '__main__':
    a = sys.argv[1:]
    grant = '--grant' in a
    a = [x for x in a if x != '--grant']
    audit(a[0], a[1], grant)
