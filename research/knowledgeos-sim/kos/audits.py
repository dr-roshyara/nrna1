"""§23 NO-SMUGGLING · §24 ORACLE INDEPENDENCE · §26 CIRCULARITY · §25 TYPE CHECK."""
import inspect, re
from . import transitions as T, state as ST, scenarios as SC, world as W
from .types import COLLISIONS, TYPE_TABLE

# ------------------------------------------------------- §23 no-smuggling
AGENT_MODULES = [T, ST]          # code the AGENT is allowed to run
FORBIDDEN = r"\.truth\b"         # reading latent world truth

def smuggling_audit():
    findings = []
    for mod in AGENT_MODULES:
        src = inspect.getsource(mod)
        for i, line in enumerate(src.split("\n"), 1):
            if re.search(FORBIDDEN, line) and not line.strip().startswith("#"):
                findings.append(dict(module=mod.__name__, line=i, text=line.strip(),
                                     issue="agent code reads latent world truth"))
    # every semantic capability must be introduced by exactly one transition
    introducers = {
        "world-contact":        ["Acquire"],
        "evidential-admission": ["Qualify"],
        "meaning-assignment":   ["Interpret"],
        "hypothesis-space":     ["OpenHypothesisSpace"],
        "target-relative-weight":["Assess"],
        "set-valued-selection": ["Determine"],
        "attribution":          ["knowledge_attribution"],
        "elimination":          ["Reject"],
        "state-commit":         ["Revise", "Supersede"],
    }
    helper_names = [n for n, o in vars(SC).items()
                    if callable(o) and not n.startswith("scenario") and not n.startswith("_")]
    return dict(forbidden_reads=findings,
                capability_introducers=introducers,
                scenario_helpers=helper_names,
                helper_introduces_capability=[],   # verified below
                note="Only kos/transitions.py mutates E; scenarios orchestrate, "
                     "the lexicon and admission policy are DECLARED inputs, not capabilities.")

def helper_audit():
    """Does any non-transition function write to the epistemic state?"""
    offenders = []
    for mod in (SC,):
        for name, fn in vars(mod).items():
            if not callable(fn) or not hasattr(fn, "__code__"): continue
            try: src = inspect.getsource(fn)
            except Exception: continue
            # ASSIGNMENT only - a read is legitimate orchestration
            for m in re.finditer(r"E\.(observations|determinations|attributions|evidence|"
                                 r"interpretations|rejections)\s*\[[^\]]+\](\[[^\]]+\])?\s*=", src):
                offenders.append(dict(fn=f"{mod.__name__}.{name}",
                                      stmt=m.group(0).strip(),
                                      issue="non-transition WRITES epistemic state"))
    return offenders

# ------------------------------------------------------- §26 circularity
CIRCULARITY = [
 dict(id="CIRC-1", a="Knowledge defined via Truth (DEF-1 factivity)",
      b="Truth inferred from Knowledge",
      circular=False,
      finding="NOT circular in this implementation: truth exists ONLY in World and no "
              "agent transition reads it. This is precisely WHY factivity fails — the "
              "non-circularity and the impossibility are the same fact.",
      grounding="World.truth is exogenous; the evaluator alone reads it."),
 dict(id="CIRC-2", a="Zero defined via Gap (DEF-22)", b="Gap defined via Zero",
      circular=False,
      finding="One-directional: Gap is primitive (unsatisfied requirements), "
              "Zero := (Δ = ∅).",
      grounding="Sat(K,r) is defined per requirement kind, independently of Zero."),
 dict(id="CIRC-3", a="Adequacy defined via Satisfaction (DEF-20)",
      b="Satisfaction defined via Adequacy",
      circular=False,
      finding="Not circular, but Adequate and Zero are EXTENSIONALLY IDENTICAL in "
              "v1.0/v1.1: both reduce to Δ = ∅. One of the two definitions does no "
              "independent work. REDUNDANCY, not circularity.",
      grounding="DEF-20 and DEF-22 collapse; recommend one be derived from the other."),
 dict(id="CIRC-4", a="Determination defined via Knowledge",
      b="Knowledge defined via Determination (Γ)",
      circular=False,
      finding="One-directional: Determine reads assessments and the standard only; "
              "Γ reads determinations. No back-edge.",
      grounding="Determine() never consults E.attributions."),
 dict(id="CIRC-5", a="Semantic equivalence defined via behaviour",
      b="Behaviour defined via semantic equivalence",
      circular=True,
      finding="PARTLY CIRCULAR AND UNRESOLVED. P7 compares behaviour under a semantic "
              "projection chosen by the experimenter. A different projection gives a "
              "different equivalence. The theory supplies no independent grounding for "
              "which projection is THE semantic one.",
      grounding="NONE AVAILABLE — this is the open problem the previous experiment "
                "reached as 'what is the correct semantic granularity'."),
]

# ------------------------------------------------------- §25 type check
def type_audit():
    return dict(n_types=len(TYPE_TABLE), notation_collisions=COLLISIONS,
                worst=max(COLLISIONS.items(), key=lambda kv: len(kv[1])))

# ------------------------------------------------------- §8 information conservation
def information_audit(E):
    unexplained = []
    for iid, it in E.interpretations.items():
        if it["of"] not in E.evidence: unexplained.append((iid, "orphan interpretation"))
        if not it["prov"].assumptions: unexplained.append((iid, "no declared assumption"))
    for eid, ev in E.evidence.items():
        if ev["of"] not in E.observations: unexplained.append((eid, "orphan evidence"))
    for p, d in E.determinations.items():
        if d["A"] and not d["provenance"]: unexplained.append((p, "unsupported determination"))
    return unexplained
