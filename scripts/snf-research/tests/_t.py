"""Test bootstrap: put the research package dir on sys.path."""
import os
import sys

_here = os.path.dirname(os.path.abspath(__file__))
_root = os.path.dirname(_here)
for p in (_root, _here):
    if p not in sys.path:
        sys.path.insert(0, p)
