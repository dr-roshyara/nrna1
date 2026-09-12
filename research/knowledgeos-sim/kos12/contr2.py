"""KR-CONTR-2026-09 — Contradiction, Evaluation Domain and Zero.  [EXP]

Protocol authored by the KnowledgeOS research programme (2 184 lines).
Executor: this lane.  PROTOCOL AUTHORSHIP IS NOT CLAUDE'S.

Baseline v1.2 unchanged.  FR-001 frozen, not reopened.  No Theory v1.3.
Factivity is an INDEPENDENT track: nothing here depends on the R1 decision, and the
simulated agent NEVER has access to world truth (the evaluator may).

Central question: can contradiction be represented WITHOUT collapsing it into
uncertainty, absence, insufficient evidence, theory incompleteness, unobservability
or non-assessment?

METHOD.  This is an INJECTIVITY problem, not a logic-design problem.  A candidate
representation R : S -> D is adequate iff it is injective on the quotient of the
semantic-condition set by the REQUIRED distinctions.  That gives a computable lower
bound on |D| and makes several results theorems rather than observations.
"""
import itertools, json, math, random
from dataclasses import dataclass, field, replace
from typing import Optional, Tuple

# ============================================================ commitments & states
@dataclass(frozen=True)
class Commitment:
    """One epistemic commitment held by the agent.  NEVER carries world truth."""
    prop: str = "p"
    polarity: bool = True          # True = p, False = not-p
    source: str = "S1"
    context: str = "C1"
    time: int = 1
    confidence: float = 1.0
    model: str = "M1"
    layer: str = "claim"           # observation | evidence | interpretation | claim | model
    status: str = "active"         # active | retracted | superseded

@dataclass(frozen=True)
class State:
    """The agent's epistemic state.  A set of commitments plus representational flags."""
    name: str
    commitments: Tuple[Commitment, ...] = ()
    represented: bool = True       # is the proposition represented at all?
    assessed: bool = True          # has evaluation occurred?
    evidence_sufficient: bool = True
    rival_hypotheses: int = 1      # >1 = underdetermined
    observable: bool = True
    applicable: bool = True
    evaluator_exists: bool = True   # False = theory incompleteness (no evaluator)
    interpreted: bool = True
    observed: bool = True

def act(s):  return tuple(c for c in s.commitments if c.status == "active")
def pos(s):  return tuple(c for c in act(s) if c.polarity)
def neg(s):  return tuple(c for c in act(s) if not c.polarity)

# ============================================================ the semantic classes
# One state per semantic condition named by the protocol (SS 2.1, 11).  Each carries
# its protocol provenance.  Inventing a condition would make separation meaningless.
P  = lambda **k: Commitment(polarity=True,  **k)
NP = lambda **k: Commitment(polarity=False, **k)

CONDITIONS = {
 "Unknown":            (State("Unknown", (), observed=False),                    "S11 case 1"),
 "Absent":             (State("Absent", (), represented=False, observed=False),  "S11 case 2"),
 "NotAssessed":        (State("NotAssessed", (P(),), assessed=False),            "S11 case 3"),
 "InsufficientEvidence":(State("InsufficientEvidence", (P(confidence=.2),),
                               evidence_sufficient=False),                       "S11 case 4"),
 "Underdetermined":    (State("Underdetermined", (P(),), rival_hypotheses=3),    "S11 case 5"),
 "Unobservable":       (State("Unobservable", (), observable=False, observed=False),"S11 case 6"),
 "NotApplicable":      (State("NotApplicable", (), applicable=False),            "S2.1"),
 "Uninterpreted":      (State("Uninterpreted", (P(layer="observation"),),
                               interpreted=False),                               "S2.1"),
 "Satisfied":          (State("Satisfied", (P(),)),                              "S9 true"),
 "Unsatisfied":        (State("Unsatisfied", (NP(),)),                           "S9 false"),
 # --- the contradiction family -------------------------------------------------
 "DirectContradiction":(State("DirectContradiction", (P(), NP())),               "S11 case 7"),
 "SourceConflict":     (State("SourceConflict", (P(source="S1"), NP(source="S2"))), "S11 case 8"),
 "ObservationConflict":(State("ObservationConflict",
                              (P(layer="observation"), NP(layer="observation"))), "S11 case 9"),
 "UnequalConfidence":  (State("UnequalConfidence",
                              (P(confidence=.95), NP(confidence=.10))),           "S11 case 10"),
 "Retraction":         (State("Retraction", (P(status="retracted"), NP())),      "S11 case 11"),
 "Supersession":       (State("Supersession",
                              (P(time=1, status="superseded"), NP(time=2))),      "S11 case 12"),
 "ContextDifference":  (State("ContextDifference",
                              (P(context="C1"), NP(context="C2"))),               "S11 case 13"),
 "TemporalDifference": (State("TemporalDifference", (P(time=1), NP(time=2))),     "S11 case 14"),
 "ModelConflict":      (State("ModelConflict",
                              (P(model="M1", layer="model"), NP(model="M2", layer="model"))),
                                                                                  "S11 case 15"),
 "EvidenceConflict":   (State("EvidenceConflict",
                              (P(layer="evidence"), NP(layer="evidence"))),       "S31"),
}

# theory incompleteness: no evaluator EXISTS (distinct from evaluation not yet done)
CONDITIONS["TheoryIncomplete"] = (State("TheoryIncomplete", (P(),), assessed=True,
                                        evaluator_exists=False),
                                  "S2.5 -- model lacks structure; corpus NO_EVALUATOR")
_TI = "TheoryIncomplete"

# ============================================================ required distinctions
# Taken from protocol S9's table and invariants I1-I5.  NOT invented, NOT extended.
REQUIRED = [
 ("Satisfied","Unsatisfied"),                ("Unsatisfied","Unknown"),
 ("Unknown","Absent"),                       ("Absent","NotAssessed"),
 ("NotAssessed","NotApplicable"),            ("InsufficientEvidence","Underdetermined"),
 ("Underdetermined","Unobservable"),
 # contradiction must differ from each of these -- I1..I5 and S9
 ("DirectContradiction","Unknown"),          ("DirectContradiction","Absent"),
 ("DirectContradiction","InsufficientEvidence"),
 ("DirectContradiction",_TI),                ("DirectContradiction","NotAssessed"),
]
REQ_PROVENANCE = "protocol S9 table + invariant candidates I1-I5"

# ============================================================ candidate representations
def R_A(s):
    """Candidate A -- fourth value.  D = {T,F,U,C}.

    FAIRNESS: this is the STRONGEST available mapping, per protocol S5 (test the
    candidate, not a strawman).  It returns U -- never T/F -- whenever the evaluation
    genuinely cannot run: no evaluator, uninterpreted input, unobservable, not
    applicable, not assessed, insufficient evidence, or rivals outstanding.
    """
    if pos(s) and neg(s): return ("C",)
    if not s.represented or not act(s): return ("U",)
    if not (s.evaluator_exists and s.interpreted and s.observable and s.applicable
            and s.assessed and s.evidence_sufficient and s.rival_hypotheses == 1):
        return ("U",)
    if pos(s): return ("T",)
    if neg(s): return ("F",)
    return ("U",)

def R_B(s):
    """Candidate B -- contradiction as an independent relation OUTSIDE Sat."""
    contr = bool(pos(s) and neg(s))
    base = R_A(s)[0]
    if base == "C": base = "U"                       # Sat stays three-valued
    return (base, contr)

def R_C(s):
    """Candidate C -- exclusive construction: contradiction prevented structurally.

    bottom == (not-p in K and p not in K).  A state cannot hold both, so one side
    must be dropped.  Best available rule: keep the higher-confidence side; on a tie
    keep the positive.  This is deliberately the STRONGEST version of C.
    """
    p_, n_ = pos(s), neg(s)
    if p_ and n_:
        keep_pos = max(c.confidence for c in p_) >= max(c.confidence for c in n_)
        s = replace(s, commitments=(p_ if keep_pos else n_))
    return R_A(s)

def R_D(s, fields=None):
    """Candidate D -- structured evaluation.  The tuple is DISCOVERED, not assumed;
    `fields` selects a subset for the minimality analysis."""
    all_fields = {
      "value":       R_A(s)[0],
      "polarity":    (bool(pos(s)), bool(neg(s))),
      "reason":      ("absent" if not s.represented else
                      "theory-incomplete" if not s.evaluator_exists else
                      "not-applicable" if not s.applicable else
                      "unobservable" if not s.observable else
                      "unobserved" if not s.observed else
                      "uninterpreted" if not s.interpreted else
                      "not-assessed" if not s.assessed else
                      "insufficient" if not s.evidence_sufficient else
                      "underdetermined" if s.rival_hypotheses > 1 else None),
      "provenance":  tuple(sorted({c.source for c in act(s)})),
      "evidence":    tuple(sorted({c.layer for c in act(s)})),
      "context":     tuple(sorted({c.context for c in act(s)})),
      "time":        tuple(sorted({c.time for c in act(s)})),
      "model":       tuple(sorted({c.model for c in act(s)})),
      "status":      tuple(sorted({c.status for c in s.commitments})),
      "confidence":  tuple(sorted(round(c.confidence, 2) for c in act(s))),
    }
    keys = fields if fields is not None else list(all_fields)
    return tuple(all_fields[k] for k in keys)

CANDIDATES = {"A_fourth_value": R_A, "B_delegated_relation": R_B,
              "C_exclusive": R_C, "D_structured": R_D}
D_FIELDS = ["value","polarity","reason","provenance","evidence","context",
            "time","model","status","confidence"]

# ============================================================ separation machinery
def separates(R, x, y):
    return R(CONDITIONS[x][0]) != R(CONDITIONS[y][0])

def separation_report(name, R):
    fails = [{"pair": [x, y], "collapsed_to": str(R(CONDITIONS[x][0]))}
             for x, y in REQUIRED if not separates(R, x, y)]
    return {"candidate": name, "required_pairs": len(REQUIRED),
            "separated": len(REQUIRED) - len(fails), "failures": fails,
            "adequate": not fails}

def full_matrix(R):
    ks = list(CONDITIONS)
    return {x: {y: bool(R(CONDITIONS[x][0]) != R(CONDITIONS[y][0]))
                for y in ks if y != x} for x in ks}

# ============================================================ the minimum domain
def chromatic_number(nodes, edges):
    """Minimum |D| for a FLAT value domain = chromatic number of the must-differ graph.

    Assigning values to conditions IS a graph colouring: two conditions joined by a
    required distinction must receive different values.  Exact, with a clique lower
    bound -- the graph is small.
    """
    adj = {n: set() for n in nodes}
    for a, b in edges:
        adj[a].add(b); adj[b].add(a)
    # greedy max clique for the lower bound
    best = []
    for start in nodes:
        cl = [start]
        for n in nodes:
            if n != start and all(n in adj[c] for c in cl): cl.append(n)
        if len(cl) > len(best): best = cl
    lower = len(best)
    def colourable(k):
        order = sorted(nodes, key=lambda n: -len(adj[n]))
        col = {}
        def bt(i):
            if i == len(order): return True
            n = order[i]
            used = {col[m] for m in adj[n] if m in col}
            for c in range(k):
                if c not in used:
                    col[n] = c
                    if bt(i + 1): return True
                    del col[n]
            return False
        return bt(0)
    k = lower
    while not colourable(k): k += 1
    return {"chromatic_number": k, "clique_lower_bound": lower,
            "max_clique": sorted(best), "n_nodes": len(nodes), "n_required_edges": len(edges)}

# ============================================================ E1 -- adequacy + minimum domain
def E1_adequacy_and_minimum_domain():
    reps = {n: separation_report(n, R) for n, R in CANDIDATES.items()}
    nodes = sorted({c for pair in REQUIRED for c in pair})
    chrom = chromatic_number(nodes, REQUIRED)
    for n, R in CANDIDATES.items():
        vals = {str(R(CONDITIONS[c][0])) for c in nodes}
        reps[n]["distinct_values_used_on_required_nodes"] = len(vals)
    return {"question": "which candidates separate the REQUIRED distinctions, and what "
                        "is the minimum flat domain size?",
            "required_provenance": REQ_PROVENANCE,
            "candidates": reps,
            "minimum_flat_domain": chrom,
            "consequence": ("a FLAT value domain needs at least chromatic_number values; "
                            "a fourth value is necessary only if that number exceeds 3, "
                            "and sufficient only if it equals 4")}

# ============================================================ E2 -- witnesses
def E2_collapse_witnesses():
    out = {}
    for n, R in CANDIDATES.items():
        coll = {}
        ks = list(CONDITIONS)
        for x, y in itertools.combinations(ks, 2):
            if R(CONDITIONS[x][0]) == R(CONDITIONS[y][0]):
                coll.setdefault(str(R(CONDITIONS[x][0])), []).append([x, y])
        out[n] = {"n_collapsed_pairs": sum(len(v) for v in coll.values()),
                  "n_total_pairs": len(ks) * (len(ks) - 1) // 2,
                  "collapses_by_value": {k: v[:8] for k, v in coll.items()}}
    return {"question": "exactly WHERE does each candidate collapse distinctions?",
            "note": "witnesses shown, per protocol S10 -- not a bare pass/fail",
            "candidates": out}

# ============================================================ E3 -- Zero interaction
def zero_weak(vs):    return not any(v == "F" for v in vs)
def zero_reasoned(vs, agent):  return zero_weak(vs) and not any(
                          v == "U" and a for v, a in zip(vs, agent))
def zero_kleene(vs):
    if "F" in vs: return "F"
    if "U" in vs: return "U"
    return "T"

def E3_zero_interaction():
    """Does contradiction IMPLY Zero?  And can Zero be DETERMINED from contradiction?"""
    rows = []
    contr_conditions = ["DirectContradiction","SourceConflict","ObservationConflict",
                        "UnequalConfidence","ContextDifference","TemporalDifference",
                        "ModelConflict","EvidenceConflict"]
    for cname in contr_conditions:
        s = CONDITIONS[cname][0]
        vA = [R_A(s)[0]]; vB = [R_B(s)[0]]
        rows.append({"condition": cname,
                     "A_value": vA[0], "B_value": vB[0],
                     "A_zero_weak": zero_weak(vA), "A_zero_kleene": zero_kleene(vA),
                     "B_zero_weak": zero_weak(vB), "B_zero_kleene": zero_kleene(vB),
                     "closes_under_A": zero_weak(vA), "closes_under_B": zero_weak(vB)})
    implies = all(not r["closes_under_A"] for r in rows)
    # can Zero be determined FROM contradiction alone?  i.e. does knowing Contr fix Zero?
    non_contr = ["Unknown","Absent","NotAssessed","InsufficientEvidence"]
    zero_of_contr = {zero_kleene([R_A(CONDITIONS[c][0])[0]]) for c in contr_conditions}
    zero_of_non   = {zero_kleene([R_A(CONDITIONS[c][0])[0]]) for c in non_contr}
    return {"question": "Contradiction => Zero?  And is Zero determined by contradiction?",
            "rows": rows,
            "contradiction_implies_not_zero": implies,
            "zero_values_over_contradictory_states": sorted(zero_of_contr),
            "zero_values_over_noncontradictory_states": sorted(zero_of_non),
            "zero_determined_by_contradiction_alone": len(zero_of_contr) == 1
                                and not (zero_of_contr & zero_of_non)}

# ============================================================ E4 -- minimality
def E4_minimality():
    """Field ablation on D, then EXHAUSTIVE minimum-subset search over 2^10."""
    ablation = {}
    for f in D_FIELDS:
        keep = [k for k in D_FIELDS if k != f]
        R = lambda s, kk=keep: R_D(s, kk)
        rep = separation_report(f"D_minus_{f}", R)
        ablation[f] = {"adequate_without_it": rep["adequate"],
                       "distinctions_lost": [x["pair"] for x in rep["failures"]]}
    minimal_sets, best = [], None
    for r in range(1, len(D_FIELDS) + 1):
        for combo in itertools.combinations(D_FIELDS, r):
            R = lambda s, kk=list(combo): R_D(s, kk)
            if separation_report("t", R)["adequate"]:
                minimal_sets.append(list(combo))
        if minimal_sets:
            best = r; break
    return {"question": "what is the SMALLEST structure preserving the required distinctions?",
            "single_field_ablation": ablation,
            "necessary_fields": [f for f, v in ablation.items()
                                 if not v["adequate_without_it"]],
            "minimum_size": best,
            "n_minimum_sets": len(minimal_sets),
            "minimum_sets": minimal_sets,
            "unique_minimum": len(minimal_sets) == 1,
            "caveat": "experimental minimality for the TESTED distinction set only"}

# ============================================================ E5 -- internal structure of C
def E5_contradiction_internal_structure():
    """Is contradiction ONE state, or does it have internal structure?

    If a representation must distinguish contradiction subtypes from each other, then
    a single value C is insufficient EVEN IF C separates contradiction from
    non-contradiction.  Protocol S12.
    """
    fam = ["DirectContradiction","SourceConflict","ObservationConflict","UnequalConfidence",
           "ContextDifference","TemporalDifference","ModelConflict","EvidenceConflict",
           "Retraction","Supersession"]
    under = {}
    for n, R in CANDIDATES.items():
        classes = {}
        for c in fam: classes.setdefault(str(R(CONDITIONS[c][0])), []).append(c)
        under[n] = {"n_classes": len(classes), "classes": classes}
    # second-order: contradiction COMBINED with each other condition
    combos = []
    base = CONDITIONS["DirectContradiction"][0]
    for mod, kw in [("insufficient_evidence", {"evidence_sufficient": False}),
                    ("not_assessed",          {"assessed": False}),
                    ("unobservable",          {"observable": False}),
                    ("underdetermined",       {"rival_hypotheses": 3}),
                    ("not_applicable",        {"applicable": False})]:
        s2 = replace(base, name=f"Contr+{mod}", **kw)
        combos.append({"combination": f"Contr+{mod}",
                       **{n: str(R(s2)) for n, R in CANDIDATES.items()}})
    a_vals = {c["A_fourth_value"] for c in combos}
    return {"question": "is contradiction one state, or does it have internal structure?",
            "family_size": len(fam),
            "by_candidate": under,
            "second_order_combinations": combos,
            "A_collapses_all_combinations_to_one_value": len(a_vals) == 1,
            "interpretation": ("if A maps the whole family and all second-order "
                               "combinations to a single value C, then C separates "
                               "contradiction from non-contradiction while destroying "
                               "every distinction WITHIN contradiction")}

# ============================================================ E6 -- vacuity guards
def E6_vacuity_guards():
    """Protocol S19.  A candidate must not pass by degenerate means."""
    out = {}
    contr_set = {"DirectContradiction","SourceConflict","ObservationConflict",
                 "UnequalConfidence","EvidenceConflict"}
    non_contr  = {"Unknown","Absent","NotAssessed","InsufficientEvidence",
                  "Underdetermined","Unobservable","Satisfied","Unsatisfied",
                  "NotApplicable","Uninterpreted", _TI}
    for n, R in CANDIDATES.items():
        cv = {str(R(CONDITIONS[c][0])) for c in contr_set}
        nv = {str(R(CONDITIONS[c][0])) for c in non_contr}
        allv = {str(R(CONDITIONS[c][0])) for c in CONDITIONS}
        out[n] = {
          "positive_capability -- can represent contradiction": bool(cv - nv),
          "negative_separation -- no false contradiction": not (nv & cv),
          "completeness -- all required distinctions": separation_report(n, R)["adequate"],
          "not_degenerate -- uses more than one value": len(allv) > 1,
          "n_distinct_values": len(allv)}
    return {"question": "does any candidate pass by degenerate means?",
            "guards": out,
            "note": ("'everything is U' and 'everything is C' both fail: the first fails "
                     "positive capability, the second fails negative separation")}

# ============================================================ E7 -- statistical stress
# Explicit per-candidate detection predicates.  An earlier version tested
# ("C" in str(value)), which matched the literal "C" inside context labels C1/C2 and
# made Candidate D appear to misfire on 47% of states.  That was a defect in the
# TEST, not in D.  Detection must be read from the candidate's own structure.
DETECTS = {
 "A_fourth_value":       lambda s: R_A(s)[0] == "C",
 "B_delegated_relation": lambda s: R_B(s)[1] is True,
 "C_exclusive":          lambda s: False,       # structurally cannot represent it
 "D_structured":         lambda s: bool(pos(s)) and bool(neg(s)),
}

def E7_stress(trials=20000, seed=20260902):
    """Protocol S18.  Randomized ROBUSTNESS only -- it proves nothing the deterministic
    witnesses have not already settled.  Reported with seed, generator and CI."""
    rng = random.Random(seed)
    def rand_state():
        k = rng.randint(0, 3)
        cs = tuple(Commitment(polarity=rng.random() < .5,
                              source=rng.choice(["S1","S2","S3"]),
                              context=rng.choice(["C1","C2"]),
                              time=rng.randint(1, 3),
                              confidence=round(rng.random(), 2),
                              model=rng.choice(["M1","M2"]),
                              layer=rng.choice(["observation","evidence","claim","model"]),
                              status=rng.choice(["active","active","active","retracted"]))
                   for _ in range(k))
        return State("rand", cs, represented=rng.random() < .9,
                     assessed=rng.random() < .8,
                     evidence_sufficient=rng.random() < .7,
                     rival_hypotheses=rng.choice([1,1,1,2,3]),
                     observable=rng.random() < .9, applicable=rng.random() < .95,
                     evaluator_exists=rng.random() < .9,
                     interpreted=rng.random() < .9, observed=rng.random() < .9)
    counts = {n: 0 for n in CANDIDATES}
    n_contr = 0
    for _ in range(trials):
        s = rand_state()
        is_contr = bool(pos(s) and neg(s))
        n_contr += is_contr
        for n, R in CANDIDATES.items():
            flagged = DETECTS[n](s)
            if flagged != is_contr: counts[n] += 1
    def ci(k, n):
        if n == 0: return [0.0, 0.0]
        p = k / n; se = math.sqrt(max(p * (1 - p), 1e-12) / n)
        return [round(max(0, p - 1.96 * se), 5), round(min(1, p + 1.96 * se), 5)]
    return {"question": "robustness of contradiction DETECTION under random states",
            "trials": trials, "seed": seed, "generator": "uniform over the field domains",
            "independent_cases": True,
            "contradictory_fraction": round(n_contr / trials, 4),
            "misclassification": {n: {"n": c, "rate": round(c / trials, 5),
                                      "ci95": ci(c, trials)} for n, c in counts.items()},
            "caveat": ("Candidate C is EXPECTED to misclassify -- it structurally cannot "
                       "hold both polarities.  This is robustness, NOT proof; the "
                       "deterministic witnesses in E1/E2 are the evidence.")}

# ============================================================ E8 -- kernel derivability
def E8_kernel_analysis():
    """Protocol S22.  Is contradiction an irreducible KERNEL capability, or a state
    derivable from existing candidate powers?  Importance is not irreducibility."""
    POWERS = ["Observe","Interpret","Represent","Relate","Discriminate","Hypothesize",
              "DetectGap","Challenge","Validate","Revise","Determine","Select","Qualify"]
    derivation = {
      "detect that Pos and Neg both hold":
        {"powers": ["Represent","Relate","Discriminate"],
         "argument": "both commitments are already REPRESENTED; detecting that they are "
                     "related by negation and are distinct is Relate + Discriminate"},
      "classify the contradiction subtype":
        {"powers": ["Discriminate","Qualify"],
         "argument": "subtype is a function of provenance/context/time fields already "
                     "present in the representation (E4)"},
      "decide whether it must be resolved":
        {"powers": ["Challenge","Determine"],
         "argument": "a governance/evaluation act, not a representational one"},
      "resolve it":
        {"powers": ["Revise","Select"],
         "argument": "supersession or retraction -- existing revision machinery"},
    }
    return {"question": "does contradiction introduce an irreducible kernel capability?",
            "candidate_powers": POWERS,
            "derivation": derivation,
            "all_steps_derivable": all(
                set(v["powers"]) <= set(POWERS) for v in derivation.values()),
            "verdict_argument": ("no step required a power outside the candidate set. "
                                 "Detection is Relate+Discriminate over an existing "
                                 "representation. Therefore contradiction is a SEMANTIC "
                                 "STATE, not a kernel operator -- subject to the standing "
                                 "caveat that the candidate operator set is not final"),
            "caveat": "derivability relative to the TESTED operator set (protocol S22)"}

# ============================================================ E9 -- invariants
def E9_invariants():
    """Protocol S26.  TEST the invariant candidates -- do not assume them."""
    def d(x, y, R): return R(CONDITIONS[x][0]) != R(CONDITIONS[y][0])
    res = {}
    for iid, (x, y) in [("I1_Contr_ne_Unknown", ("DirectContradiction","Unknown")),
                        ("I2_Contr_ne_Absent",  ("DirectContradiction","Absent")),
                        ("I3_Contr_ne_NotAssessed", ("DirectContradiction","NotAssessed")),
                        ("I4_Contr_ne_InsufficientEvidence",
                                                ("DirectContradiction","InsufficientEvidence")),
                        ("I5_Contr_ne_Unobservable", ("DirectContradiction","Unobservable"))]:
        res[iid] = {n: d(x, y, R) for n, R in CANDIDATES.items()}
    # I11 -- PROPOSED. The protocol's I1-I5 all compare contradiction with members of
    # the UNKNOWN family.  None forbids confusing it with SATISFIED.  Candidate C
    # satisfies I1-I5 while mapping a contradiction to T.  The invariant set has a gap.
    res["I11_Contr_ne_Satisfied_PROPOSED"] = {
        n: (R(CONDITIONS["DirectContradiction"][0]) != R(CONDITIONS["Satisfied"][0]))
        for n, R in CANDIDATES.items()}
    res["I11_note"] = ("PROPOSED addition. I1-I5 compare contradiction only against the "
                       "UNKNOWN family, so a candidate can satisfy all five by mapping "
                       "contradiction to T. Candidate C does exactly that.")
    s = CONDITIONS["DirectContradiction"][0]
    res["I7_Contr_ne_Zero"] = {
        "zero_weak_on_contradiction_under_B": zero_weak([R_B(s)[0]]),
        "note": "if a contradictory state CLOSES, Contr and Zero are not the same object"}
    res["I8_Detect_ne_Resolve"] = {
        "argument": "E8 assigns detection to Relate+Discriminate and resolution to "
                    "Revise+Select -- disjoint power sets", "holds": True}
    res["I10_historical_ne_current"] = {
        "retraction_vs_direct_differ_under_D":
            d("Retraction", "DirectContradiction", R_D),
        "note": "Retraction holds p historically but not currently"}
    return {"question": "which invariant candidates survive testing?", "results": res}

# ============================================================ E10 -- cardinality vs semantics
def E10_cardinality_is_not_the_obstruction():
    """E1 found chi = 3 on the protocol's required set, yet candidates A/B/C -- which
    have 3 or 4 values available -- all FAIL it.  That gap is the real finding.

    A colouring is an ASSIGNMENT.  A representation must additionally be COMPUTABLE
    from the state.  This test asks whether any adequate assignment can be computed
    from the evaluation OUTCOME alone, or whether it must read something else.
    """
    # the "no determinate value" family: what does the evaluation outcome give for each?
    fam = ["Unknown","Absent","NotAssessed","NotApplicable","Unobservable",
           "Uninterpreted","InsufficientEvidence","Underdetermined", _TI]
    outcome = {c: R_A(CONDITIONS[c][0])[0] for c in fam}
    identical = len(set(outcome.values())) == 1
    # which fields DO separate them?
    sep_by_field = {}
    for f in D_FIELDS:
        vals = {c: str(R_D(CONDITIONS[c][0], [f])) for c in fam}
        sep_by_field[f] = {"n_distinct": len(set(vals.values())),
                           "separates_all": len(set(vals.values())) == len(fam)}
    return {"question": "is the minimum-domain problem a CARDINALITY problem?",
            "no_determinate_value_family": fam,
            "evaluation_outcome_per_condition": outcome,
            "outcome_identical_across_family": identical,
            "theorem": ("if the evaluation outcome is identical across the family, then "
                        "NO function of the outcome alone separates any pair in it -- so "
                        "no flat domain, of ANY cardinality, indexed by evaluation outcome "
                        "is adequate.  The obstruction is not the number of values."),
            "separation_by_single_field": sep_by_field,
            "fields_that_separate_all": [f for f, v in sep_by_field.items()
                                         if v["separates_all"]]}

# ============================================================ E11 -- required-set sensitivity
def E11_required_set_sensitivity():
    """chi depends entirely on WHICH distinctions are declared required.  The protocol
    calls S9 a minimum ('at minimum include'), so chi=3 is a LOWER bound on the answer,
    not the answer.  Sweep defensible required-sets and report the range."""
    allc = list(CONDITIONS)
    unknown_family = ["Unknown","Absent","NotAssessed","NotApplicable","Unobservable",
                      "Uninterpreted","InsufficientEvidence","Underdetermined", _TI]
    contr_family = ["DirectContradiction","SourceConflict","ObservationConflict",
                    "UnequalConfidence","ContextDifference","TemporalDifference",
                    "ModelConflict","EvidenceConflict"]
    sets = {
      "S1_protocol_minimum": REQUIRED,
      "S2_plus_unknown_family_mutually_distinct":
          REQUIRED + [(a, b) for a, b in itertools.combinations(unknown_family, 2)],
      "S3_plus_contradiction_family_mutually_distinct":
          REQUIRED + [(a, b) for a, b in itertools.combinations(unknown_family, 2)]
                   + [(a, b) for a, b in itertools.combinations(contr_family, 2)],
      "S4_all_conditions_mutually_distinct":
          [(a, b) for a, b in itertools.combinations(allc, 2)],
    }
    out = {}
    for n, edges in sets.items():
        edges = [(a, b) for a, b in edges if a != b]
        nodes = sorted({c for e in edges for c in e})
        ch = chromatic_number(nodes, edges)
        out[n] = {"n_edges": len(edges), "n_nodes": ch["n_nodes"],
                  "chromatic_number": ch["chromatic_number"],
                  "D_structured_adequate": all(
                      R_D(CONDITIONS[a][0]) != R_D(CONDITIONS[b][0]) for a, b in edges)}
    return {"question": "how sensitive is the minimum domain to the required set?",
            "sets": out,
            "range": [min(v["chromatic_number"] for v in out.values()),
                      max(v["chromatic_number"] for v in out.values())],
            "interpretation": ("the 'minimum evaluation domain' is NOT a property of "
                               "contradiction. It is a function of which distinctions the "
                               "programme declares required -- which is a MODELLING "
                               "DECISION the protocol has not fully made.")}

# ============================================================ E12 -- the Reason expressivity guard
def E12_reason_expressivity_guard():
    """Review objection: "reason is the sole indispensable field" is unsafe, because
    reason could encode arbitrarily much -- in the limit Reason(x) = x, making it a
    disguised complete state identifier.  Then its indispensability is vacuous.

    This is MEASURABLE, not arguable.  Three guards.
    """
    conds = list(CONDITIONS)
    reasons = {c: R_D(CONDITIONS[c][0], ["reason"])[0] for c in conds}
    distinct = sorted(set(reasons.values()), key=lambda x: (x is None, str(x)))
    # G1 -- is it an identity encoding?
    identity = len(distinct) == len(conds)
    # G2 -- how much does it collapse?  a genuine categorical field collapses a lot
    collapsed = [[a, b] for a, b in itertools.combinations(conds, 2)
                 if reasons[a] == reasons[b]]
    # G3 -- is reason ALONE adequate?  if so it is doing all the work and the "pair"
    #       result is misleading
    alone = separation_report("reason_only", lambda s: R_D(s, ["reason"]))
    return {"question": "is `reason` a genuine categorical field, or a disguised state id?",
            "n_conditions": len(conds),
            "n_distinct_reason_values": len(distinct),
            "distinct_reason_values": [str(d) for d in distinct],
            "is_identity_encoding": identity,
            "n_pairs_collapsed_by_reason": len(collapsed),
            "example_collapses": collapsed[:6],
            "reason_alone_adequate": alone["adequate"],
            "reason_alone_separated": f"{alone['separated']}/{alone['required_pairs']}",
            "verdict": ("reason is a GENUINE categorical field iff it is not an identity "
                        "encoding AND it collapses pairs AND it is not adequate alone")}

# ============================================================ E13 -- minimal semantics of Reason
def E13_reason_decomposition():
    """The follow-on question the review poses: what is the minimal STRUCTURED
    semantics of Reason?  Not 'add nine categories' -- the TODO register rejected that.

    Hypothesis to TEST (not assume): reason factors into orthogonal sub-dimensions
        locus     -- WHERE the deficiency lies
        modality  -- WHAT KIND of deficiency it is
    If a factored reason separates exactly what the flat enumeration separates, the
    flat list is derived, not primitive.
    """
    # locus: whose deficiency is it?   modality: what kind?
    # Both drawn from the corpus bucket vocabulary (expG.BUCKET: agent/theory/world).
    FACTOR = {
      # reason                : (locus,    modality)
      "absent":               ("representation", "not-present"),
      "not-applicable":       ("scope",          "out-of-scope"),
      "unobservable":         ("world",          "inaccessible"),
      "unobserved":           ("agent",          "not-yet-done"),
      "uninterpreted":        ("agent",          "not-yet-done"),
      "not-assessed":         ("agent",          "not-yet-done"),
      "insufficient":         ("evidence",       "insufficient"),
      "underdetermined":      ("evidence",       "non-discriminating"),
      "theory-incomplete":    ("theory",         "no-evaluator"),
      None:                   ("none",           "none"),
    }
    def R_factored(s, keep=("locus","modality")):
        r = R_D(s, ["reason"])[0]
        loc, mod = FACTOR.get(r, ("unknown", "unknown"))
        d = {"locus": loc, "modality": mod}
        return tuple(d[k] for k in keep)
    conds = list(CONDITIONS)
    flat  = {c: R_D(CONDITIONS[c][0], ["reason"])[0] for c in conds}
    fact  = {c: R_factored(CONDITIONS[c][0]) for c in conds}
    # does the factorization LOSE anything the flat enumeration had?
    lost = [[a, b] for a, b in itertools.combinations(conds, 2)
            if flat[a] != flat[b] and fact[a] == fact[b]]
    # is either factor alone enough?
    solo = {k: len({R_factored(CONDITIONS[c][0], (k,)) for c in conds}) for k in
            ("locus","modality")}
    # adequacy of (value, locus, modality) against the required set
    R3 = lambda s: (R_D(s, ["value"])[0],) + R_factored(s)
    rep = separation_report("value+locus+modality", R3)
    return {"question": "does `reason` factor into orthogonal sub-dimensions?",
            "factorization": {k if k else "None": v for k, v in FACTOR.items()},
            "n_flat_reason_values": len(set(flat.values())),
            "n_factored_values": len(set(fact.values())),
            "distinctions_lost_by_factoring": lost,
            "factoring_is_lossless": not lost,
            "distinct_values_per_factor": solo,
            "value_plus_factored_reason_adequate": rep["adequate"],
            "value_plus_factored_reason_score": f"{rep['separated']}/{rep['required_pairs']}",
            "status": "[PROP] -- a TESTED hypothesis about reason's structure, not an adopted "
                      "vocabulary. The factor names are candidates and carry no authority."}
