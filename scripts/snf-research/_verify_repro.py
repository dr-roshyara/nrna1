"""Verify byte-for-byte reproducibility: run the pilot twice as a subprocess
and compare SHA-256 of the two result/metrics files."""
import hashlib
import os
import subprocess
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
RUNNER = os.path.join(HERE, "run-pilot.py")
OUT = os.path.normpath(os.path.join(HERE, "..", "..",
                                    "docs", "knowledgeos", "brainstorming"))
NAMES = ["KOS-SNF-pilot-results.json", "KOS-SNF-pilot-metrics.json"]


def _hash(name: str) -> str:
    with open(os.path.join(OUT, name), "rb") as f:
        return hashlib.sha256(f.read()).hexdigest()


def main() -> None:
    subprocess.run([sys.executable, RUNNER], check=True,
                   stdout=subprocess.DEVNULL)
    first = {n: _hash(n) for n in NAMES}
    subprocess.run([sys.executable, RUNNER], check=True,
                   stdout=subprocess.DEVNULL)
    second = {n: _hash(n) for n in NAMES}
    ok = True
    for n in NAMES:
        match = first[n] == second[n]
        ok = ok and match
        print(f"{n}: run1={first[n][:12]} run2={second[n][:12]} "
              f"{'IDENTICAL' if match else 'DIFFERS'}")
    print("REPRODUCIBLE: byte-identical across runs" if ok
          else "NOT reproducible")


if __name__ == "__main__":
    main()
