#!/usr/bin/env python3
"""
EXPERIMENTS 19-20 — Expressiveness of the terminal proposition/assertion type.

Terminal type (Step 262 verdict):   P = (Entity, Dimension, Value)
Terminal assertion (Step 267):      A = (id, P, e, c, t, Pi)
Relations live OUTSIDE P, in R subset A x A x RelType (Step 262).

TEST: take propositions the corpus itself needs, and try to express each one.
A proposition is EXPRESSIBLE iff it can be written as one triple (or, where the
corpus allows, as a triple plus an R-edge) WITHOUT losing information that a
mandatory operation would need.
"""
import sys, os; sys.path.insert(0, os.path.dirname(__file__))
from kos_kernel import Proposition
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

hr("EXP-19  Expressiveness of P = (Entity, Dimension, Value)")

CASES = [
 # (source in corpus, natural-language proposition, verdict, why)
 ("Closure-04 running example", "Nexus.version = 3.69",
  "EXPRESSIBLE", "(Nexus, version, 3.69) -- the paradigm case"),

 ("267.6 field c", "Nexus.version = 3.69 IN THE PRODUCTION CONTEXT",
  "EXPRESSIBLE-VIA-A", "context is a field of A, not of P; so two propositions "
  "with the same triple in different contexts are ONE proposition and TWO assertions"),

 ("Step 187.13 worked example", "Alice is Architecture Board chair from 2026-01-01 to 2026-12-31",
  "LOSSY", "(Alice, role, chair) drops WHICH BOARD and drops the VALIDITY INTERVAL. "
  "t in A is assertion time, not valid time; Step 185 distinguishes T_valid != T_known "
  "but A carries only one t"),

 ("Step 262 own open question", "assertion a1 CONTRADICTS assertion a2",
  "NOT-A-PROPOSITION", "expressed as an R-edge instead. So the theory has TWO "
  "mechanisms for propositional content (P and R) with no stated relationship"),

 ("Step 199 (Unknown)", "It is NOT the case that Nexus.version = 3.69",
  "INEXPRESSIBLE", "no negation. (Nexus, version, NOT-3.69) requires V_D to contain "
  "negative values, which Step 264's value-space definition does not provide"),

 ("Step 025t (inference rules)", "IF Nexus.version >= 3.60 THEN Nexus is patch-current",
  "INEXPRESSIBLE", "no conditional. This is the CONDITIONAL DETERMINATION problem the "
  "corpus opened with on 2026-08-25 (files 235754, 235804, 235855, 000209)"),

 ("Step 087 (collective choice)", "EVERY committee has at least 3 members",
  "INEXPRESSIBLE", "no quantification and no arithmetic comparison over a population"),

 ("20260826-000501 business example", "Nexus is ready to be migrated",
  "EXPRESSIBLE-BUT-EMPTY", "(Nexus, migration_readiness, ready) holds the CONCLUSION "
  "but the theory has no way to express the RULE that produces it -- the rule lives in "
  "Policy, which Step 266 classifies as having no decision procedure"),

 ("Step 264.14 (units)", "Nexus response time = 250 ms",
  "LOSSY", "(Nexus, response_time, 250) loses the UNIT. Step 264.14 identifies units as "
  "'another layer' and does not put them in V"),

 ("Step 199", "Nexus.version is UNKNOWN",
  "AMBIGUOUS", "either (Nexus, version, UNKNOWN) -- putting an epistemic marker into "
  "the VALUE SPACE, a category error Step 264 warns against -- or the absence of any "
  "assertion, which is indistinguishable from never having asked"),
]
w = max(len(c[2]) for c in CASES)
counts = {}
for src, nl, verdict, why in CASES:
    counts[verdict] = counts.get(verdict,0)+1
    print(f"\n  [{verdict:<{w}}]  {nl}")
    print(f"     source: {src}")
    print(f"     {why}")
print("\n  " + "-"*70)
for k,v in sorted(counts.items(), key=lambda x:-x[1]): print(f"  {k:<22} {v}")
n_ok = counts.get("EXPRESSIBLE",0) + counts.get("EXPRESSIBLE-VIA-A",0)
print(f"""
  => Of {len(CASES)} propositions the corpus itself requires, {n_ok} are cleanly expressible.
     THREE are inexpressible (negation, conditional, quantification), TWO are
     lossy (validity interval, unit), ONE is not a proposition at all in this
     type system, and ONE is ambiguous between a value and an epistemic marker.""")

hr("EXP-20  The founding problem, checked against the terminal type")
print("""  The corpus OPENED (2026-08-25 23:57 - 2026-08-26 00:03) on:

     'What substrate must be preserved so that multiple determination regimes
      can independently reconstruct the same phenomenon?'   (235804)
     'Determination is the missing mathematical object.'    (235855)
     'Conditional evidence and reasoning combine.'          (000209, 000339)

  The corpus CLOSED (2026-08-30) on:

     P = (Entity, Dimension, Value)      [Step 262 verdict]
     K = (A, R)                          [Steps 262-269]

  TEST: can the terminal type express a conditional determination?""")
print(f"""
     conditional 'IF v >= 3.60 THEN patch-current':  INEXPRESSIBLE (EXP-19)
     the rule that derives it:                       lives in Policy
     Policy computability (Step 266 s266.22):        class C, no decision procedure
     Assessment computability (s266.23):             class C
     'Determination' as an object:                   ABSENT from the terminal model
                                                     (grep: no Determination in
                                                      Steps 258-270's K or A)
  => The object the corpus named as MISSING on day 1 -- Determination -- is still
     missing on day 6, and the terminal proposition type cannot express the
     conditional structure that Determination was introduced to carry.
     The theory closed around the part of the problem it could formalize.""")
