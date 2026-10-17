# Recovers each blind encoder's final reply verbatim from its agent transcript (JSONL), and checks
# the reply's JSON against the saved <id>.json. Run once by the orchestrating analyst on 2026-10-17.
# The transcripts are session-local files (Claude Code task outputs) and are NOT archived here; the
# extracted replies are in blind/results/verbatim/ and blind2/results/verbatim/.
# Usage: python3 -B extract_verbatim.py <tasks_dir> <KC-1-READINESS-scripts dir>
import json, sys, os
T, S = sys.argv[1], sys.argv[2]
M = {'blind2': {'A1': 'a6f6e40f7ac722c5e', 'A2': 'a132d179eb8a3448d', 'A3': 'ae7c44be9520d211d',
                'B1': 'abdced180f6408f19', 'B2': 'ad6bd5f901e38fd46', 'B3': 'a60f8184b3782b53a'},
     'blind': {'A1': 'a5d1b1175761f79a7', 'A2': 'a58e1ea688a9c1a9f', 'B1': 'a72e4985b3f1c8158', 'B2': 'a784fe0e74ba8651f'}}
for d, m in M.items():
    out = os.path.join(S, d, 'results', 'verbatim'); os.makedirs(out, exist_ok=True)
    for n, tid in m.items():
        last = None
        for line in open(os.path.join(T, tid + '.output')):
            try: o = json.loads(line)
            except ValueError: continue
            if o.get('type') == 'assistant':
                for c in o.get('message', {}).get('content', []):
                    if c.get('type') == 'text' and c['text'].strip(): last = c['text']
                    if c.get('type') == 'tool_use' and c.get('name') == 'SubagentHandback':
                        last = c.get('input', {}).get('message', json.dumps(c.get('input', {}), ensure_ascii=False))
        open(os.path.join(out, n + '.reply.txt'), 'w').write(last)
        j = json.load(open(os.path.join(S, d, 'results', n + '.json')))
        i = last.find('{'); k = last.find('}', i); r = json.loads(last[i:k + 1])
        print(d, n, 'saved JSON equals reply JSON:', {a: sorted(b) for a, b in r.items()} == {a: sorted(b) for a, b in j.items()})
