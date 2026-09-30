#!/usr/bin/env python3
"""R-3 (G-LOG-0108; canary finding F-2): the R7 addendum's status block must state the factual status. It must not
carry the stale "not activated / NOT AUTHORIZED" lines, which agents read and cited as an instruction during canary
attempt 1. The status line itself authorizes nothing: execution is governed only by the recorded human acts.
  cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r7_addendum_status
"""
import os
import re
import unittest

CR = os.path.dirname(os.path.dirname(os.path.dirname(os.path.abspath(__file__))))
ADDENDUM = os.path.join(CR, "prompts", "20260926_2400_p3b-agent-contract-r7-addendum.md")


def status_block():
    text = open(ADDENDUM, encoding="utf-8").read()
    return text.split("\n## 0.", 1)[0]          # everything before the first normative section


class AddendumStatus(unittest.TestCase):
    def test_no_stale_activation_or_authorization_markers(self):
        s = status_block()
        for pat in (r"NOT ACTIVATED", r"NOT AUTHORIZED", r"not activated", r"Not frozen, not implemented"):
            self.assertIsNone(re.search(pat, s), pat)

    def test_states_the_factual_status_and_authorizes_nothing(self):
        s = status_block()
        self.assertIn("Activated (G-LOG-0102); bound as v2.8 (G-LOG-0106)", s)
        self.assertIn("this status line authorizes nothing by itself", s)


if __name__ == "__main__":
    unittest.main()
