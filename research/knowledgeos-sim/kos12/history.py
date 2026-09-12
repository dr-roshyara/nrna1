"""KR-HISTORY-2026-09-02 — Closure Event: Kernel vs Domain History.  [EXP]

Three hypotheses, none assumed:
    A  ClosureEvent ∈ Audit/History      (the kernel need not know closure happened)
    B  ClosureEvent ∈ DomainHistory      (later DOMAIN behaviour depends on it)
    C  ClosureEvent ∈ 𝒦                  (the kernel itself needs event semantics)

Decisive test:
    K_t , K_{t+1} identical   ∧   History_1 ≠ History_2
    → if every kernel operation behaves identically, ClosureEvent is not kernel capability.
"""
import inspect, re, copy
from kos.state import EpistemicState
from kos import transitions as T
from kos12.closure import State, Arg, closed, determined, known, reconciled

# ---------------------------------------------------------------- the two histories
def build_pair():
    """Two epistemic states with IDENTICAL current content, differing only in whether a
    ClosureEvent occurred in their past."""
    def mk(with_closure):
        E = EpistemicState()
        E.hypotheses["p"] = ["H1", "H2"]
        E.rejections["p"] = set()
        # --- shared current content -------------------------------------------------
        E.determinations["p"] = dict(prop="p", A=["H1"], status="unique", weight=1.0,
                                     weights={"H1":1.0}, dropped_dependent=0,
                                     independent_sources=1, standard_met=True,
                                     causal_status="not-identified",
                                     provenance=("a1",), assumptions=())
        E.evidence["e1"]  = dict(id="e1", of="o1", prop="p", token="H1", source="s1",
                                 reliability=1.0, derived_from=None, prov=None)
        # --- histories DIFFER ---------------------------------------------------------
        if with_closure:
            E.history = [dict(t=0, transition="Determine", produced="p", inputs=(), assumptions=()),
                         dict(t=1, transition="ClosureEvent", produced="p", inputs=(),
                              assumptions=(), closed=True),
                         dict(t=2, transition="Revise", produced="p", inputs=(), assumptions=(),
                              superseded=dict(prop="p", A=["H1"], status="unique")),
                         dict(t=3, transition="Determine", produced="p", inputs=(), assumptions=())]
        else:
            E.history = [dict(t=0, transition="Acquire", produced="o1", inputs=(), assumptions=()),
                         dict(t=1, transition="Determine", produced="p", inputs=(), assumptions=())]
        E.t = 4
        return E
    return mk(True), mk(False)

def current_content(E):
    """Everything that is NOT history."""
    return dict(determinations={k: {kk: (sorted(vv) if isinstance(vv, list) else vv)
                                    for kk, vv in v.items()}
                                for k, v in E.determinations.items()},
                evidence=sorted(E.evidence), hypotheses={k: sorted(v) for k, v in E.hypotheses.items()},
                rejections={k: sorted(v) for k, v in E.rejections.items()},
                observations=sorted(E.observations), interpretations=sorted(E.interpretations),
                assessments=[a["id"] for a in E.assessments])

# ---------------------------------------------------------------- T1/T6 behavioural test
KERNEL_OPS = ["Acquire","Qualify","Interpret","OpenHypothesisSpace","Assess",
              "Determine","Reject","Accept","Revise","Supersede"]

def op_outputs(E):
    """Run every kernel operation that can be run without external input, and record
    the resulting CURRENT CONTENT. History is deliberately excluded from the comparison."""
    out = {}
    from kos.scenarios import STRICT, LENIENT
    for op in ("Determine","Revise"):
        E2 = copy.deepcopy(E)
        try:
            getattr(T, op)(E2, "p", LENIENT)
            out[op] = current_content(E2)
        except Exception as ex:
            out[op] = f"ERROR {ex!r}"
    E3 = copy.deepcopy(E); T.Reject(E3, "p", "H2", "test"); out["Reject"] = current_content(E3)
    E4 = copy.deepcopy(E); T.OpenHypothesisSpace(E4, "q", ["A","B"]); out["OpenHypothesisSpace"]=current_content(E4)
    return out

# ---------------------------------------------------------------- T3 static test
def static_history_reads():
    """Does ANY kernel transition read E.history?  A static check over the source."""
    src = inspect.getsource(T)
    hits = []
    fn = None
    for i, line in enumerate(src.split("\n"), 1):
        m = re.match(r"^def (\w+)", line)
        if m: fn = m.group(1)
        if re.search(r"E\.history", line) and not line.strip().startswith("#"):
            kind = "WRITE" if re.search(r"E\.history\.append", line) else "READ"
            hits.append(dict(fn=fn, line=i, kind=kind, text=line.strip()[:90]))
    return hits

# ---------------------------------------------------------------- T2 reconstructibility
def reconstructible_from_state(E):
    """Can 'was this ever closed?' be recovered from current content alone?"""
    cc = current_content(E)
    return dict(current_content_identical_across_pair=None,   # filled by the runner
                any_field_encoding_past_closure=[k for k, v in cc.items()
                                                 if "clos" in str(v).lower()])

# ---------------------------------------------------------------- T4/T5 domain probes
def domain_probes(E_closed, E_never):
    """Operations that a DOMAIN might legitimately want. NONE is invented as a kernel rule:
    each is checked against whether the theory actually contains it."""
    def ever_closed(E): return any(h.get("transition")=="ClosureEvent" for h in E.history)
    def reopen_count(E): return sum(1 for h in E.history if h.get("transition")=="Revise")
    probes = {
      "re-closure under a stronger standard":
        dict(distinguishes=ever_closed(E_closed)!=ever_closed(E_never),
             in_theory=False,
             note="a governance rule of the form 'a reopened proposition needs a stronger "
                  "standard' would read history. THE THEORY CONTAINS NO SUCH RULE."),
      "stability / churn metric":
        dict(distinguishes=reopen_count(E_closed)!=reopen_count(E_never),
             in_theory=False,
             note="an analytics concern; no theory object consumes it"),
      "explanation / why-do-we-believe-X":
        dict(distinguishes=True, in_theory=True,
             note="AX-5 provenance preservation and I9 require the trail to SURVIVE; "
                  "they do not require any OPERATION to read it"),
      "supersession traceability (§17 / CE-3)":
        dict(distinguishes=True, in_theory=True,
             note="the theory requires a superseded claim to remain traceable — again a "
                  "PRESERVATION obligation, not an operation input"),
    }
    return probes
