import re, json, random, sys
R7 = sys.argv[1]
out = sys.argv[2]
t2 = open(R7 + '/T2-RULE-CANDIDATES.md', encoding='utf-8').read().split('\n')
t1 = open(R7 + '/T1-CONCEPT-CANDIDATES.md', encoding='utf-8').read().split('\n')
def clean(s):
    s = s.replace('**', '')
    s = re.sub(r'\[r1(\.\d+)?( sync)?\]\s*', '', s)
    return re.sub(r'\s+', ' ', s).strip()
rows = ['R1', 'R6', 'R9', 'R11', 'R14', 'R15', 'R16', 'R17', 'R21', 'R79', 'R80']
texts, src = {}, {}
for i, line in enumerate(t2, 1):
    m = re.match(r'^\| *(R\d+) *\|', line)
    if m and m.group(1) in rows and m.group(1) not in texts:
        cells = line.split('|')
        texts[m.group(1)] = clean(cells[2]); src[m.group(1)] = i
concepts, csrc = [], {}
for i, line in enumerate(t1, 1):
    m = re.match(r'^\| *(C\d+) *\|', line)
    if m:
        cells = line.split('|')
        concepts.append((m.group(1), clean(cells[2]))); csrc[m.group(1)] = i
random.seed(20261017)
order = list(rows)
random.shuffle(order)
json.dump({'texts': texts, 'src': src, 'concepts': concepts, 'csrc': csrc, 'order': order, 'initial_order': rows},
          open(out, 'w', encoding='utf-8'), ensure_ascii=False, indent=1)
print(order)
for r in order: print(r, '::', texts[r])
for c, d in concepts: print(c, '::', d)
