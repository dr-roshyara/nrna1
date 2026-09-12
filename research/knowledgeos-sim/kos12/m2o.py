"""KR-M2O-2026-09-02 — Candidate Multiplicity, Selection, Validation, Determination. [EXP]

Adversarial. Does NOT try to confirm the Linga–Yoni metaphor.
Ground truth is EVALUATOR-ONLY; the agent never reads it (statically audited).
Baseline KnowledgeOS Theory v1.2, unmodified. No v1.3.
"""
import random, math, statistics as st
from dataclasses import dataclass, field

# ---------------------------------------------------------------- worlds (truth = evaluator only)
@dataclass
class World:
    truth: str                      # EVALUATOR ONLY
    kind: str
    n_signal: int = 40              # observations available to the agent
    noise: float = 1.0
    def sample(self, h, rng, n=None):
        """Agent-visible score of h: the MEAN of n observations.
        The mean of n iid N(mu, s) is exactly N(mu, s/sqrt(n)), so draw it directly —
        identical distribution, 40x cheaper."""
        n = n or self.n_signal
        return rng.gauss(self.signal(h), self.noise/math.sqrt(n))
    def signal(self, h):
        """Declared, world-specific signal. For NULL worlds every h has mu = 0."""
        if self.kind == "null":                      return 0.0
        if self.kind == "unique_true":               return 0.6 if h == self.truth else 0.0
        if self.kind == "observationally_equivalent":return 0.6 if h in (self.truth,"H_twin") else 0.0
        if self.kind == "none_admissible":           return -0.5
        if self.kind == "false_high_fit":            return 0.9 if h == "H_decoy" else (0.3 if h==self.truth else 0.0)
        if self.kind == "true_weak_evidence":        return 0.15 if h == self.truth else 0.0
        return 0.0

WORLDS = {
 "W1 unique true":            World("H_true","unique_true"),
 "W2 observationally equiv":  World("H_true","observationally_equivalent"),
 "W3 none admissible":        World("H_true","none_admissible"),
 "W4 false but high fit":     World("H_true","false_high_fit"),
 "W6 true, weak evidence":    World("H_true","true_weak_evidence"),
 "NULL no real advantage":    World("H_true","null"),
}

# ---------------------------------------------------------------- generators G1..G7
def gen(kind, n, rng, truth="H_true"):
    """Returns (candidates, dependence_groups). The FALSE PROPORTION is measured, never assumed."""
    if kind=="G1_random":      C=[f"R{i}" for i in range(n)]; grp=list(range(n))
    elif kind=="G2_diverse":   C=[f"D{i}" for i in range(n)]; grp=list(range(n))
    elif kind=="G3_correlated":
        C=[f"C{i//5}_{i}" for i in range(n)]; grp=[i//5 for i in range(n)]
    elif kind=="G4_near_dup":
        C=[f"N{i//20}_{i}" for i in range(n)]; grp=[i//20 for i in range(n)]
    elif kind=="G5_adversarial":
        C=(["H_decoy"]*max(1,n//10))+[f"A{i}" for i in range(n-max(1,n//10))]; grp=list(range(n))
    elif kind=="G6_mostly_false":
        C=[f"F{i}" for i in range(n)]; grp=list(range(n))
        if n>0 and rng.random()<0.02: C[0]=truth
    elif kind=="G7_mostly_true":
        k=max(1,int(n*0.8)); C=[truth]*k+[f"F{i}" for i in range(n-k)]; grp=list(range(n))
    else: raise ValueError(kind)
    return C[:n], grp[:n]

def false_proportion(C, truth): return sum(1 for c in C if c!=truth)/len(C) if C else None
def diversity(grp): return len(set(grp))/len(grp) if grp else None

# ---------------------------------------------------------------- multi-criteria assessment
CRITERIA = ["evidence","consistency","explanatory","predictive","robustness","assumption_cost"]

def assess(h, w, rng, split="train"):
    e = w.sample(h, rng)
    return dict(evidence=e,
                consistency=rng.uniform(0,1),
                explanatory=rng.uniform(0,1),
                predictive=w.sample(h, rng),          # independent draw = held-out
                robustness=rng.uniform(0,1),
                assumption_cost=-rng.uniform(0,1))

# ---------------------------------------------------------------- selection regimes
def sel_scalar(V, w1=1.0):
    """§14 secondary arm. argmax of a weighted sum — forces uniqueness by construction."""
    best=max(V, key=lambda h: V[h]["evidence"]*w1 + V[h]["consistency"])
    return [best]

def sel_threshold(V, tau):
    return [h for h,v in V.items() if v["evidence"] >= tau]

def sel_pareto(V, dims=("evidence","consistency","explanatory")):
    """Admissibility: keep the non-dominated set. PRESERVES incomparability."""
    def dom(a,b): return all(V[a][d] >= V[b][d] for d in dims) and any(V[a][d] > V[b][d] for d in dims)
    return [h for h in V if not any(dom(g,h) for g in V if g!=h)]

# ---------------------------------------------------------------- §10 winner's curse
def winners_curse(ns, trials=200, seed=7):
    """NULL world: no candidate has real predictive advantage. Select on training,
    evaluate on independent held-out. Measures SELECTION OPTIMISM vs n."""
    rng=random.Random(seed); w=WORLDS["NULL no real advantage"]; out={}
    for n in ns:
        tr_sel, ho_sel, ho_mean = [], [], []
        for _ in range(trials):
            C,_=gen("G1_random", n, rng)
            train={h: w.sample(h,rng) for h in C}
            best=max(train, key=train.get)
            held ={h: w.sample(h,rng) for h in C}       # independent evaluation
            tr_sel.append(train[best]); ho_sel.append(held[best])
            ho_mean.append(sum(held.values())/len(held))
        out[n]=dict(n=n, trials=trials,
                    max_training=round(st.mean(tr_sel),4),
                    heldout_of_selected=round(st.mean(ho_sel),4),
                    heldout_mean_all=round(st.mean(ho_mean),4),
                    selection_optimism=round(st.mean(tr_sel)-st.mean(ho_sel),4),
                    sd_heldout_selected=round(st.pstdev(ho_sel),4),
                    theory_sqrt2lnn=round(math.sqrt(2*math.log(n))/math.sqrt(w.n_signal),4) if n>1 else 0.0)
    return out
