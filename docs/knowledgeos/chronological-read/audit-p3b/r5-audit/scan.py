#!/usr/bin/env python3
"""scan_level bypass probes (synthetic strings; nothing executed). Run: cd <CR>/scripts && python3 -B /tmp/r5-audit/scan.py"""
import importlib.util, os, sys
sys.dont_write_bytecode = True
sp = importlib.util.spec_from_file_location("r5", os.path.join(os.getcwd(), "p3b_s5_r5.py"))
r5 = importlib.util.module_from_spec(sp); sp.loader.exec_module(r5)
RUN, B = "OB9501-R5-L02S", "OB9501"
cases = {
  "second reader call foreign run": "python3 p3b_read_source.py --run OB9501-R5-L02S --batch OB9501 --label x --step 1 --page 1 S9501 && python3 p3b_read_source.py --run OB9501-R5-L02U01 --batch OB9501 --label x --step 1 --page 1 --mode bytes S9511",
  "synthesis using a unit run id": "python3 p3b_read_source.py --run OB9501-R5-L02U01 --batch OB9501 --label x --step 1 --page 1 --mode bytes S9511",
  "python open() of a corpus path (no glob, token list empty)": "python3 -c \"print(open('docs/knowledgeos/x/file.md').read())\"",
  "cat of a relative path": "cd docs/knowledgeos && cat some/file.md",
  "git show via variable": "G=git; $G show HEAD:docs/x.md",
  "importlib of discovery io": "python3 -c \"import importlib; importlib.import_module('p3b_' + 'discovery_io')\"",
}
for k, cmd in cases.items():
    print(f"{k:58s} -> {r5.scan_level({'input': {'command': cmd}}, RUN, B, set(), set())}")
