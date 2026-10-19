# PROPOSED / NOT RATIFIED; materials only; not executed.
# KC-1 external replication: freeze helper (PROTOCOL.md section 8.1). It is a HUMAN step.
# Without --write it only prints the hashes (safe to run at any time). With --write the human writes
# FREEZE-MANIFEST.sha256 and then commits the whole folder BEFORE any encoding; the commit id is then
# entered in every coordinator record (answer_sheet_template.md section A).
# Run from external-protocol/:   python3 -B tools/freeze.py [--write] [--key-outside]
#   --key-outside : key_PRIVATE.md is kept outside the repository; its hash is still recorded here.
import sys, os, hashlib, glob

MATERIALS = ['PROTOCOL.md', 'analysis-plan.md', 'adjudication.md', 'answer_sheet_template.md', 'score.py',
             'packet_T3.md', 'packet_SH.md', 'packet_PARSE.md', 'packet_PARSE_B.md', 'packet_GEN.md',
             'key_PRIVATE.md', 'tools/build_packets.py', 'tools/leak_check.py', 'tools/freeze.py', 'tools/source_data.json',
             'tools/leak_check.out']
h = lambda b: hashlib.sha256(b).hexdigest()
lines = ['# PROPOSED / NOT RATIFIED until committed by the human; sha256 of the frozen materials (PROTOCOL.md 8.1)']
for f in MATERIALS:
    if not os.path.exists(f): lines.append(f'MISSING  {f}'); continue
    b = open(f, 'rb').read(); lines.append(f'{h(b)}  {f}')
    if f.startswith('packet_'):
        t = b.decode('utf-8'); i = t.index('<!-- BEGIN PACKET'); i = t.index('\n', i) + 1; j = t.index('<!-- END PACKET -->')
        lines.append(f'{h(t[i:j].encode("utf-8"))}  {f}#sendable-body')
print('\n'.join(lines))
if '--write' in sys.argv:
    open('FREEZE-MANIFEST.sha256', 'w', encoding='utf-8').write('\n'.join(lines) + '\n')
    print('written: FREEZE-MANIFEST.sha256  -> now commit the folder (human), before any encoding')
