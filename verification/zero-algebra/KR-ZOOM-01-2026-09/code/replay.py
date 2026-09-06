#!/usr/bin/env python3
"""Deterministic replay: regenerate the ledger and assert byte-identity."""
import hashlib, os, subprocess, sys
ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
L = f"{ROOT}/data/ledger.jsonl"
before = hashlib.md5(open(L, "rb").read()).hexdigest()
subprocess.run([sys.executable, f"{ROOT}/code/run_zoom01.py"], check=True, capture_output=True)
after = hashlib.md5(open(L, "rb").read()).hexdigest()
print(f"before={before}\nafter ={after}\n{'REPLAY OK' if before==after else 'REPLAY FAILED'}")
sys.exit(0 if before == after else 1)
