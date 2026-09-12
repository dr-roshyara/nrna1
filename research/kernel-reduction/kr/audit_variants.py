"""Variants V8-V12: the reduction hypotheses proposed by the external audit
(2026-09-01, 'Yes. The report is sufficient to make a').

Each grants an audit hypothesis AS A MODEL and measures what collapses.
Granting a hypothesis is not endorsing it - it is testing its consequences.
"""
from . import carriers as CA
from .atoms import *
from .variants import variant, VARIANTS, use, ops_for, base_spec

# V8 (audit S8): Challenge = Hypothesize a rival + Discriminate against the claim.
def v8_ops(s): s.pop("Challenge", None); return s
def v8_rules(r):
    return r + [({CA.CLAIM, CA.HYPOTHESIS}, {A_DIFF_DECISION}, CA.DEFEATER)]
variant("V8", "AUDIT S8: a Defeater is a rival Hypothesis discriminated against a Claim "
              "(Challenge removed from the model, not absorbed)", v8_ops, v8_rules)

# V9 (audit S26): alternative-generation reconstructed from Represent + Relate.
def v9_ops(s): s.pop("Hypothesize", None); return s
def v9_rules(r):
    return r + [({CA.REPRESENTATION, CA.RELATION}, {A_LINKING}, CA.HYPOTHESIS)]
variant("V9", "AUDIT S26: a Hypothesis is a Representation related to an existing one "
              "(Hypothesize removed from the model)", v9_ops, v9_rules)

# V11 (audit S24): C3 does not name the carrier Representation; structure is generic.
def v11_rules(r):
    return r + [({CA.SEMANTIC}, {A_LINKING}, CA.REPRESENTATION),
                ({CA.SEMANTIC}, {A_GENERATION}, CA.REPRESENTATION)]
variant("V11", "AUDIT S24: `Representation` is not the private product of one operator - "
               "any structuring power yields it", None, v11_rules)

# V12: grant EVERY audit reduction hypothesis simultaneously (V1+V3+V5+V8+V9+V11).
def v12_ops(s):
    s["Interpret"] = {"semantic-encoding"}; s["Represent"] = {"semantic-encoding"}
    s["Determine"] = {A_NORM_COMPARISON, A_DIFF_DECISION}
    s["Select"] = {A_DIFF_DECISION}
    s.pop("Challenge", None); s.pop("Hypothesize", None)
    return s
def v12_rules(r):
    out = []
    for i, a, k in r:
        a = {("semantic-encoding" if x in (A_MEANING, A_ENCODING) else x) for x in a}
        out.append((i, a, k))
    out = [x for x in out if x[2] not in (CA.DETERMINATION, CA.DECISION)]
    out += [({CA.OBSERVATION, CA.CONTEXT}, {"semantic-encoding"}, CA.REPRESENTATION),
            ({CA.NORM_DELTA, CA.INQUIRY}, {A_DIFF_DECISION}, CA.DETERMINATION),
            ({CA.ACTION_SET, CA.OBJECTIVE}, {A_DIFF_DECISION}, CA.DECISION),
            ({CA.CLAIM, CA.HYPOTHESIS}, {A_DIFF_DECISION}, CA.DEFEATER),
            ({CA.REPRESENTATION, CA.RELATION}, {A_LINKING}, CA.HYPOTHESIS),
            ({CA.SEMANTIC}, {A_LINKING}, CA.REPRESENTATION)]
    return out
variant("V12", "AUDIT MAXIMAL: every audit reduction hypothesis granted at once "
               "(V1 + V3 + V5 + V8 + V9 + V11)", v12_ops, v12_rules)

AUDIT_VARIANTS = ["V8", "V9", "V11", "V12"]
