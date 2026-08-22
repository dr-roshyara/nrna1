#!/usr/bin/env python3
"""Run the KOS-SNF research test suite (stdlib unittest only)."""
import os
import sys
import unittest

HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, HERE)
sys.path.insert(0, os.path.join(HERE, "tests"))

if __name__ == "__main__":
    suite = unittest.defaultTestLoader.discover(
        os.path.join(HERE, "tests"), pattern="test_*.py")
    res = unittest.TextTestRunner(verbosity=2).run(suite)
    sys.exit(0 if res.wasSuccessful() else 1)
