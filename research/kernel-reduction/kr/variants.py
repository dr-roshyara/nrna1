"""Robustness variants (Part XXIII).

Each variant is an ALTERNATIVE REASONABLE MODEL of the same domain.  A
conclusion that survives every variant is [EXP] robust; one that flips is
an artifact of a modelling choice and must be reported as such.
"""
import copy
from . import carriers as CA
from .operators import Op, C0_PLUS
from .atoms import *

def _ops(spec):
    return {n: Op(n, set(a), C0_PLUS[n].corpus if n in C0_PLUS else "-", "")
            for n, a in spec.items()}

def base_spec():
    return {n: set(o.atoms) for n, o in C0_PLUS.items()}

VARIANTS = {}
def variant(vid, desc, ops_mut=None, rules_mut=None, drop=()):
    VARIANTS[vid] = dict(id=vid, desc=desc, ops_mut=ops_mut,
                         rules_mut=rules_mut, drop=list(drop))

# V0 baseline
variant("V0", "baseline model as declared in 04-operator-contracts")

# V1 - Interpret/Represent fused: one atom 'semantic-encoding'
def v1_ops(s):
    s["Interpret"] = {"semantic-encoding"}; s["Represent"] = {"semantic-encoding"}; return s
def v1_rules(r):
    out = []
    for i, a, k in r:
        a = {("semantic-encoding" if x in (A_MEANING, A_ENCODING) else x) for x in a}
        out.append((i, a, k))
    out.append(({CA.OBSERVATION, CA.CONTEXT}, {"semantic-encoding"}, CA.REPRESENTATION))
    return out
variant("V1", "meaning-assignment and symbolic-encoding are ONE atom", v1_ops, v1_rules)

# V2 - inference may operate directly on semantic content
def v2_rules(r):
    return r + [({CA.SEMANTIC, CA.RULE}, {A_ENTAILMENT}, CA.CLAIM)]
variant("V2", "Infer may act on SemanticContent, not only Representation", None, v2_rules)

# V3 - closure is a difference-decision over the norm delta
def v3_ops(s):
    s["Determine"] = {A_NORM_COMPARISON, A_DIFF_DECISION}; return s
def v3_rules(r):
    return [x for x in r if x[2] != CA.DETERMINATION] + \
           [({CA.NORM_DELTA, CA.INQUIRY}, {A_DIFF_DECISION}, CA.DETERMINATION)]
variant("V3", "closure-judgment is NOT primitive: adequacy = difference-decision over NormDelta",
        v3_ops, v3_rules)

# V4 - DetectGap's discrimination is scoped to the norm delta only
def v4_ops(s):
    s["DetectGap"] = {A_NORM_COMPARISON, "gap-difference-decision"}; return s
def v4_rules(r):
    return [x for x in r if not (x[2] == CA.GAP)] + \
           [({CA.NORM_DELTA}, {"gap-difference-decision"}, CA.GAP),
            ({CA.NORM_DELTA}, {A_DIFF_DECISION}, CA.GAP)]
variant("V4", "DetectGap's difference-decision is SCOPED to NormDelta (not a general power)",
        v4_ops, v4_rules)

# V5 - action selection is difference-decision applied to an ActionSet
def v5_ops(s):
    s["Select"] = {A_DIFF_DECISION}; return s
def v5_rules(r):
    return [x for x in r if x[2] != CA.DECISION] + \
           [({CA.ACTION_SET, CA.OBJECTIVE}, {A_DIFF_DECISION}, CA.DECISION)]
variant("V5", "preference-over-actions is NOT primitive: choice = difference-decision + Objective",
        v5_ops, v5_rules)

# V6 - warrant splits into evidential support and defeater survival
def v6_ops(s):
    s["Validate"] = {"evidential-support"}; s["Challenge"] = {A_NEGATION}; return s
def v6_rules(r):
    out = [x for x in r if x[2] != CA.VERDICT]
    out += [({CA.CLAIM, CA.EVIDENCE, CA.DEFEATER}, {"evidential-support"}, CA.VERDICT),
            ({CA.HYPOTHESIS, CA.EVIDENCE, CA.DEFEATER}, {"evidential-support"}, CA.VERDICT)]
    return out
variant("V6", "a Verdict REQUIRES a surviving-defeater step (validation presupposes challenge)",
        v6_ops, v6_rules)

# V7 - the raw 13-operator C0, no Qualify
variant("V7", "raw C0: no evidential-qualification operator at all", drop=["Qualify"])

class use:
    """context manager applying a variant's derivation-rule mutation"""
    def __init__(self, v): self.v = v
    def __enter__(self):
        self.saved = list(CA.DERIVATION_RULES)
        if self.v["rules_mut"]:
            CA.DERIVATION_RULES[:] = self.v["rules_mut"](list(self.saved))
        return self
    def __exit__(self, *a): CA.DERIVATION_RULES[:] = self.saved

def ops_for(v):
    s = base_spec()
    if v["ops_mut"]: s = v["ops_mut"](s)
    for d in v["drop"]: s.pop(d, None)
    return _ops(s)
