#!/usr/bin/env python3
"""Save a reviewer's final hand-back text from its transcript (JSONL) to a file (whole-book review S0100).

Usage: python3 -I save_handback.py TRANSCRIPT.jsonl OUT.md "HEADER LINE"
The final hand-back is the text of the last assistant message that carries text, or the SubagentHandback tool input.
"""
import json, sys

src, out, header = sys.argv[1], sys.argv[2], sys.argv[3]
last = None
for line in open(src, encoding='utf-8'):
    try:
        rec = json.loads(line)
    except ValueError:
        continue
    msg = rec.get('message') or {}
    if msg.get('role') != 'assistant':
        continue
    for b in msg.get('content') or []:
        if not isinstance(b, dict):
            continue
        if b.get('type') == 'text' and b.get('text', '').strip():
            last = b['text']
        elif b.get('type') == 'tool_use' and 'andback' in (b.get('name') or ''):
            inp = b.get('input') or {}
            last = inp.get('report') or inp.get('text') or inp.get('message') or json.dumps(inp, ensure_ascii=False)
if not last:
    sys.exit('no hand-back text found')
open(out, 'w', encoding='utf-8').write(header + '\n\n' + last.strip() + '\n')
print(out, len(last.split()), 'words')
