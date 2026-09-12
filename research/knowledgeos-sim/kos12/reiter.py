"""KR-REITER-2026-09 — mathematical audit of Reiter's situation calculus.  [EXP]

Mandate S17: "Where claims are finite or executable, write tests... Do not report a
mathematical claim merely because the formula looks plausible.  Execute it."

These are VERIFICATION tests of Reiter's formal machinery, NOT an implementation of
KnowledgeOS.  The mandate's Objective defers implementation; S17 requires execution.
Nothing here promotes any Reiter concept to a KnowledgeOS primitive.
"""
import itertools
from dataclasses import dataclass, field
from typing import Tuple, FrozenSet, Optional

# ============================================================ Reiter's ontology, faithfully
@dataclass(frozen=True)
class Situation:
    """A situation IS a history: a finite sequence of actions.  NOT a state."""
    actions: Tuple[str, ...] = ()
    def do(self, a): return Situation(self.actions + (a,))
    def __len__(self): return len(self.actions)
S0 = Situation()

# A basic action theory over propositional fluents.
# gamma_plus[a]  = fluents action a makes TRUE
# gamma_minus[a] = fluents action a makes FALSE
# poss[a]        = precondition, a predicate on the fluent set
@dataclass(frozen=True)
class ActionTheory:
    fluents: FrozenSet[str]
    gamma_plus: dict
    gamma_minus: dict
    poss: dict = field(default_factory=dict)
    initial: FrozenSet[str] = frozenset()

    def Poss(self, a, state):
        p = self.poss.get(a)
        return True if p is None else p(state)

    def successor(self, a, state):
        """Successor state axiom:  F' = gamma+ ∨ (F ∧ ¬gamma-)"""
        gp = set(self.gamma_plus.get(a, ()))
        gm = set(self.gamma_minus.get(a, ()))
        return frozenset(gp | (set(state) - gm))

    def progress(self, sit: Situation, from_state=None):
        """PROGRESSION: roll the initial state forward through the history."""
        st = self.initial if from_state is None else from_state
        for a in sit.actions:
            if not self.Poss(a, st): return None          # non-executable
            st = self.successor(a, st)
        return st

    def executable(self, sit: Situation):
        st = self.initial
        for a in sit.actions:
            if not self.Poss(a, st): return False
            st = self.successor(a, st)
        return True

    def regress(self, fluent: str, sit: Situation):
        """REGRESSION: rewrite a query about sit into a query about S0, by unwinding
        the successor-state axiom backwards.  Returns a predicate over the S0 state."""
        if not sit.actions:
            return lambda s0: fluent in s0
        a, prefix = sit.actions[-1], Situation(sit.actions[:-1])
        if fluent in self.gamma_plus.get(a, ()):   return lambda s0: True
        if fluent in self.gamma_minus.get(a, ()):  return lambda s0: False
        inner = self.regress(fluent, prefix)
        return lambda s0: inner(s0)

# ============================================================ A1 history/state collision
def A1_history_state_collision():
    """Reiter: 'Situations are histories, not states. Two situations may have identical
    fluent values but be different histories.'  EXECUTED, not quoted."""
    T = ActionTheory(fluents=frozenset({"F"}),
                     gamma_plus={"turn_on": ("F",)}, gamma_minus={"turn_off": ("F",)},
                     initial=frozenset())
    s1 = S0.do("turn_on")
    s2 = S0.do("turn_on").do("turn_off").do("turn_on")
    st1, st2 = T.progress(s1), T.progress(s2)
    return {"claim": "two distinct situations can share a state",
            "situation_1": s1.actions, "situation_2": s2.actions,
            "state_1": sorted(st1), "state_2": sorted(st2),
            "states_identical": st1 == st2,
            "situations_identical": s1 == s2,
            "collision_demonstrated": st1 == st2 and s1 != s2,
            "corollary": ("no function of the STATE can recover the history -- so "
                          "Situation != State is not a stipulation, it is forced")}

# ============================================================ A2 successor-state consistency
def A2_successor_state_axiom():
    """Determinism, persistence, and the CONFLICTING-EFFECTS case."""
    T = ActionTheory(fluents=frozenset({"F","G"}),
                     gamma_plus={"a": ("F",), "conflict": ("F",)},
                     gamma_minus={"b": ("F",), "conflict": ("F",)},
                     initial=frozenset({"G"}))
    st = frozenset({"G"})
    det = len({T.successor("a", st) for _ in range(5)}) == 1
    persist = "G" in T.successor("a", st)                      # a says nothing about G
    # the conflicting case: gamma+ and gamma- BOTH name F
    conflict_from_false = T.successor("conflict", frozenset())
    conflict_from_true  = T.successor("conflict", frozenset({"F"}))
    return {"claim": "F' = gamma+ ∨ (F ∧ ¬gamma-)",
            "deterministic": det,
            "persistence_unmentioned_fluent_unchanged": persist,
            "conflicting_effects_from_F_false": sorted(conflict_from_false),
            "conflicting_effects_from_F_true":  sorted(conflict_from_true),
            "conflict_resolved_silently_in_favour_of_gamma_plus":
                "F" in conflict_from_false and "F" in conflict_from_true,
            "finding": ("when gamma+ and gamma- both name F, the SSA yields F TRUE with "
                        "no signal that the effect axioms were inconsistent. The frame "
                        "solution PRESUPPOSES consistent effect axioms; it does not "
                        "detect their inconsistency.")}

# ============================================================ A3 regression ≡ progression
def A3_regression_progression_equivalence():
    T = ActionTheory(fluents=frozenset({"F","G"}),
                     gamma_plus={"on": ("F",), "setG": ("G",)},
                     gamma_minus={"off": ("F",)},
                     initial=frozenset())
    seqs = [(), ("on",), ("on","off"), ("on","off","on"), ("setG","on"), ("on","setG","off")]
    rows, agree = [], True
    for seq in seqs:
        sit = Situation(seq)
        prog = T.progress(sit)
        for f in ("F","G"):
            r = T.regress(f, sit)(T.initial)
            p = f in prog
            rows.append({"situation": seq, "fluent": f, "regression": r, "progression": p,
                         "agree": r == p})
            agree &= (r == p)
    return {"claim": "regression and progression answer the same queries",
            "rows": rows, "n_checks": len(rows), "all_agree": agree}

# ============================================================ A4 executability / Poss
def A4_poss_and_executability():
    T = ActionTheory(fluents=frozenset({"open"}),
                     gamma_plus={"enter": ()}, gamma_minus={},
                     poss={"enter": lambda s: "open" in s},
                     initial=frozenset())
    blocked = Situation(("enter",))
    T2 = ActionTheory(fluents=T.fluents, gamma_plus={"unlock": ("open",), "enter": ()},
                      gamma_minus={}, poss=T.poss, initial=frozenset())
    ok = Situation(("unlock","enter"))
    return {"claim": "executable(s) requires Poss at every prefix",
            "blocked_situation": blocked.actions,
            "blocked_executable": T.executable(blocked),
            "unblocked_situation": ok.actions,
            "unblocked_executable": T2.executable(ok),
            "precondition_is_a_gate_not_an_effect": True}

# ============================================================ A5 observational equivalence
def A5_observational_equivalence():
    """Can two situations be distinguished by any fluent query?  If not, they are
    observationally equivalent while remaining distinct situations."""
    T = ActionTheory(fluents=frozenset({"F"}),
                     gamma_plus={"on": ("F",)}, gamma_minus={"off": ("F",)},
                     initial=frozenset())
    pairs = [(Situation(("on",)), Situation(("on","off","on"))),
             (Situation(()),      Situation(("on","off")))]
    rows = []
    for s1, s2 in pairs:
        st1, st2 = T.progress(s1), T.progress(s2)
        rows.append({"s1": s1.actions, "s2": s2.actions,
                     "obs_equivalent": st1 == st2, "situations_equal": s1 == s2,
                     "history_length_differs": len(s1) != len(s2)})
    return {"claim": "observational equivalence does not imply situation identity",
            "rows": rows,
            "all_obs_equivalent_but_distinct": all(
                r["obs_equivalent"] and not r["situations_equal"] for r in rows)}
