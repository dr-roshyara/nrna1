import json, re, sys, hashlib, os
SP = os.path.dirname(os.path.abspath(__file__))
CUR = sys.argv[1]
TARGET = '4d65261d18f6fdb1af007732ca46e443d8c1a057fe1f3e0746a6fc852f391367'
ops = []  # forward-order list of (step, kind, a, b) for Chapter 1 only

class Rec(str):
    def __new__(cls, path): o = str.__new__(cls, ''); o.path = path; return o
    def count(self, *a): return 1
    def replace(self, a, b, *k):
        if 'CH-01' in self.path: ops.append((STEP, 'replace', a, b))
        return self
    def __add__(self, o): return self
class F:
    def __init__(self, path): self.path = path
    def read(self): return Rec(self.path)
    def write(self, s): return 0
    def __enter__(self): return self
    def __exit__(self, *a): pass
def fake_open(p, mode='r', *a, **k): return F(str(p))
def after_line(p, startswith, add):
    if 'CH-01' in p: ops.append((STEP, 'after_line', startswith, add))

def heredocs(cmd):
    return re.findall(r"python3(?: -I)? - [^\n]*<<'EOF'\n(.*?)\nEOF", cmd, re.S)

steps = sorted(os.listdir(f'{SP}/cmds'))
for fn in steps:
    STEP = fn
    d = json.load(open(f'{SP}/cmds/{fn}')); inp = d['input']
    if fn.endswith('_Edit.json'):
        if 'CH-01' in inp['file_path']: ops.append((fn, 'replace', inp['old_string'], inp['new_string']))
        continue
    cmd = inp.get('command', '')
    n = int(fn[:5])
    if n in (18015, 18026) or n > 17941: continue  # this reconstruction's own read-only extraction
    for src in heredocs(cmd):
        src = re.sub(r"def after_line\(.*?\n(?=\S)", "", src, flags=re.S)
        if 'CH-01' not in src and 'chapter_01' not in src and 'C=' not in src: continue
        g = {'open': fake_open, 'after_line': after_line, 'print': lambda *a, **k: None, '__name__': 'x'}
        try: exec(src, g)
        except Exception as e: ops.append((fn, 'ERROR', repr(e), src[:200]))
    # shell appends to Chapter 1
    for m in re.finditer(r"(?:echo|printf '%s\\n') '((?:[^']|'\\'')*)' >> (?:manuscript/)?chapter_01/CH-01-knowledge-authority-execution\.md", cmd):
        ops.append((fn, 'append', m.group(1).replace("'\\''", "'") + '\n', None))

json.dump(ops, open(f'{SP}/ops.json', 'w'), ensure_ascii=False, indent=1)
for o in ops: print(o[0], o[1], repr(o[2][:70]))

s = open(CUR).read()
for step, kind, a, b in reversed(ops):
    if kind == 'replace':
        c = s.count(b)
        if b == '' or c != 1: print('!! reverse-replace count', c, step, repr(b[:80])); 
        if c >= 1: s = s.replace(b, a, 1)
    elif kind == 'after_line':
        L = s.split('\n'); hits = [k for k, l in enumerate(L) if l.startswith(a)]
        ins = add_lines = ('\n' + b).split('\n')
        k = hits[0] if len(hits) == 1 else None
        if k is None or L[k+1:k+1+len(ins)] != ins: print('!! after_line mismatch', step, len(hits))
        else: del L[k+1:k+1+len(ins)]
        s = '\n'.join(L)
    elif kind == 'append':
        if not s.endswith(a): print('!! append not at end', step)
        else: s = s[:-len(a)]
    else: print('!!', step, kind, a)
open(f'{SP}/CH-01-v1-reconstructed.md', 'w').write(s)
h = hashlib.sha256(s.encode()).hexdigest()
print('reconstructed sha256', h, 'MATCH' if h == TARGET else 'NO MATCH')
