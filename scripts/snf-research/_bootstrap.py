"""Make the snf-research modules importable both directly and from the runner.

The directory name contains a hyphen, so it cannot be a normal Python package.
Every module in this directory starts with `import _bootstrap` to put its own
directory on sys.path, then uses plain absolute imports (``from ir import ...``).
"""
import os
import sys

_here = os.path.dirname(os.path.abspath(__file__))
if _here not in sys.path:
    sys.path.insert(0, _here)
