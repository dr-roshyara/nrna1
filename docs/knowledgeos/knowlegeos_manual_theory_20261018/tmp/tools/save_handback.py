"""Save a subagent's hand-back verbatim: the longest string in its task JSONL that STARTS with the marker
(after optional whitespace). save_hb.py took the longest string merely CONTAINING it, which picked tool outputs
that quoted the commission (consolidation S0201-S0225, reports A and C). Usage as save_hb.py."""
import json, sys, hashlib
src, marker, out, sha = sys.argv[1:5]
cands = []
def walk(o):
    if isinstance(o, dict):
        for v in o.values(): walk(v)
    elif isinstance(o, list):
        for v in o: walk(v)
    elif isinstance(o, str) and o.lstrip().startswith(marker):
        cands.append(o.lstrip())
for line in open(src, encoding='utf-8'):
    try: walk(json.loads(line))
    except json.JSONDecodeError: pass
assert cands, 'no string starts with the marker'
text = max(cands, key=len)
if not text.endswith('\n'): text += '\n'
open(out, 'w', encoding='utf-8').write(text)
h = hashlib.sha256(text.encode('utf-8')).hexdigest()
open(sha, 'w').write(f'{h}  {out.split("/")[-1]}\n')
print(len(cands), 'candidates;', len(text), 'chars;', h)
