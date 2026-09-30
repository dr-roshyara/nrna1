#!/usr/bin/env python3
"""
KSME-15 -- Behavioral Semantics Engine (BSE).

Semantic-neutral mathematical machinery for computing behavioral equivalence,
congruence, partitions, counterexample certificates, and three distinct
notions of minimality over a finite bounded executable regime.

R = (E, T, O, C, H):
    E : finite state space (any hashable Python objects)
    T : dict name -> (fn: (state, ctx) -> state, list of admissible contexts)
    O : dict name -> fn: state -> value (observations)
    C : optional constraints/invariants (documentation only in this engine;
        enforcement is the caller's responsibility -- BSE does not invent them)
    H : horizon. Two DISTINCT semantics, not one "None means whatever":
        - H = a finite integer  -> FINITE-HORIZON behavioral equivalence,
          x ~_{B,H} y, quantifying only over sequences sigma with |sigma|<=H.
          This is always well-defined, for any E/T/O, no extra assumptions.
        - H = None              -> UNBOUNDED behavioral equivalence, x ~_B y,
          quantifying over ALL sigma in T*. behavioral_partition() computes
          this via fixed-point partition refinement, which is *exact* for
          unbounded ~_B *only* under these assumptions (all held by every
          regime validated in KSME-15; not automatically true in general):
            1. E is finite;
            2. every T_i is a deterministic total function on E (T:ExC->E,
               not T:ExC->P(E) and not a relation R subseteq ExCxE);
            3. the operation+context alphabet {(T_i,c): c in contexts_i} is
               finite;
            4. every O_j is a deterministic function of state (no hidden
               randomness, no dependence on anything outside E).
          If any of these fail, `None` must NOT be read as "unbounded ~_B" --
          it would silently compute something else. This engine does not
          check these assumptions for the caller; they must be disclosed in
          the regime's own provenance (see RegimeManifest below).

BSE v1 is DETERMINISTIC-ONLY, BY DESIGN, NOT BY OVERSIGHT. Every T_i above
must be a genuine function E×C_i->E. Nondeterministic transitions
(T:E×C->P(E)) or relational transitions (R subseteq ExCxE) are an
explicitly DEFERRED v2 extension -- this investigation's own Track-A
research (KSME-10/11/12/13A) found real, live candidates for relational/
partial transition semantics, and BSE v1 deliberately does not attempt to
support them yet, rather than support them badly. Do not pass a
one-to-many "operation" into T and expect correct results; it will silently
be treated as if it always returns whatever its Python code happens to
compute, with no relational semantics.

This module is semantic-neutral: it does not know or care whether E's
elements are elections, evidence records, or synthetic integers. It must
never be used to assert that a state from one semantic lane (Track-A,
Track-B, Lane-B) "is" a state from another -- that identity question is
answered elsewhere, by disclosed provenance, never by this engine.

~B is computed via fixed-point partition refinement (the same
known-correct primitive already used in KSME-04/05/08), formalized here as
a single reusable Regime.behavioral_partition() method instead of being
re-derived ad hoc in every script.

ARCHITECTURAL NOTE (corrected per KSME-15 audit): the source corpus comes
FIRST, BSE is execution/analysis infrastructure SECOND. The chain is
    Source Corpus -> R=(E,T,O,C,H) -> BSE -> ~_{B,H} -> Pi_{B,H} -> K_{B,H} -> K_min
never "BSE -> (E,T,O) -> ...", which would wrongly suggest BSE generates or
supplies semantics. BSE never does; a regime's E/T/O/C/H must always be
supplied, with disclosed provenance, by the caller.
"""
from __future__ import annotations
import itertools
from dataclasses import dataclass, field
from typing import Any, Callable, Hashable


@dataclass
class RegimeManifest:
    """Provenance-completeness gate for a Regime. No Regime built from any
    real (non-purely-synthetic) source material should be treated as an
    admissible KSME result without one of these, filled in honestly --
    partial disclosure is fine, SILENT disclosure is not.

    semantic_status: one of "SYNTHETIC" (validation/test system, no real-
        world claim), "SOURCE_GROUNDED" (every element traces to a real,
        cited source), "LANE_B" / "TRACK_B" (real but explicitly non-Track-A
        lanes, tagged, never promoted), "MIXED" (disclose exactly which
        parts are which -- never leave this implicit).
    """
    regime_id: str
    source_track: str  # e.g. "TRACK-A", "LANE-B", "TRACK-B", "SYNTHETIC"
    semantic_status: str
    state_sources: dict = field(default_factory=dict)       # component -> citation
    operation_sources: dict = field(default_factory=dict)   # op name -> citation
    observation_sources: dict = field(default_factory=dict) # obs name -> citation
    constraint_sources: dict = field(default_factory=dict)  # constraint -> citation
    horizon: Any = None
    finite_state: bool = True
    determinism: str = "deterministic"  # BSE v1 supports only this value
    provenance_complete: bool = False

    def validate(self, regime: "Regime") -> tuple[bool, list[str]]:
        """Checks the manifest actually covers every element the Regime
        declares -- does not check the CITATIONS are true (that is a human/
        source-verification job BSE cannot do), only that none are silently
        missing."""
        problems = []
        for name in regime.T:
            if name not in self.operation_sources:
                problems.append(f"operation '{name}' has no declared source")
        for name in regime.O:
            if name not in self.observation_sources:
                problems.append(f"observation '{name}' has no declared source")
        if self.determinism != "deterministic":
            problems.append(
                f"determinism={self.determinism!r} -- BSE v1 supports only "
                f"'deterministic'; results for any other value are undefined")
        if not self.semantic_status:
            problems.append("semantic_status is required, never left blank")
        ok = len(problems) == 0
        self.provenance_complete = ok
        return ok, problems


@dataclass
class CounterexampleCertificate:
    abstraction: str
    state_x: Any
    state_y: Any
    abstraction_x: Any
    abstraction_y: Any
    operation_sequence: tuple
    observation: str
    output_x: Any
    output_y: Any
    violated_property: str

    def to_dict(self):
        return {
            "abstraction": self.abstraction,
            "state_x": repr(self.state_x),
            "state_y": repr(self.state_y),
            "abstraction_x": repr(self.abstraction_x),
            "abstraction_y": repr(self.abstraction_y),
            "operation_sequence": [str(s) for s in self.operation_sequence],
            "observation": self.observation,
            "output_x": repr(self.output_x),
            "output_y": repr(self.output_y),
            "violated_property": self.violated_property,
        }


class Regime:
    def __init__(self, E, T: dict, O: dict, H: int | None = None, name: str = "R"):
        self.E = list(E)
        self.T = T  # name -> (fn, contexts)
        self.O = O  # name -> fn
        self.H = H
        self.name = name

    # ---------------------------------------------------------- observational partition
    def observational_partition(self):
        parts = {}
        for e in self.E:
            key = tuple(sorted((o, self._safe(fn, e)) for o, fn in self.O.items()))
            parts.setdefault(key, []).append(e)
        return [frozenset(v) for v in parts.values()]

    @staticmethod
    def _safe(fn, e):
        try:
            v = fn(e)
        except Exception as ex:
            v = ("__error__", str(ex))
        try:
            hash(v)
            return v
        except TypeError:
            return repr(v)

    # ---------------------------------------------------------- behavioral partition (fixed point)
    def behavioral_partition(self, record_steps: bool = False):
        classes = self.observational_partition()
        steps = [self._describe(classes)] if record_steps else None
        rounds = 0
        while True:
            rounds += 1
            e2c = {}
            for cid, cls in enumerate(classes):
                for e in cls:
                    e2c[e] = cid
            new_classes = []
            changed = False
            for cls in classes:
                buckets = {}
                for e in cls:
                    sig = []
                    for opname, (fn, ctxs) in sorted(self.T.items()):
                        for c in ctxs:
                            try:
                                nxt = fn(e, c)
                            except Exception as ex:
                                nxt = ("__error__", str(ex))
                            target = e2c.get(nxt, ("__unreached__", repr(nxt)))
                            sig.append((opname, repr(c), target))
                    sig = tuple(sig)
                    buckets.setdefault(sig, []).append(e)
                if len(buckets) > 1:
                    changed = True
                new_classes.extend(frozenset(v) for v in buckets.values())
            classes = new_classes
            if record_steps:
                steps.append(self._describe(classes))
            if not changed or (self.H is not None and rounds >= self.H):
                break
        result = {"rounds": rounds, "n_classes": len(classes), "classes": classes}
        if record_steps:
            result["steps"] = steps
        return result

    @staticmethod
    def _describe(classes):
        return {"n_classes": len(classes), "sizes": sorted(len(c) for c in classes)}

    def class_of(self, classes, state):
        for c in classes:
            if state in c:
                return c
        return None

    # ---------------------------------------------------------- exhaustive x~By (small |E| only)
    def behaviorally_equivalent(self, x, y, max_len: int | None = None):
        """Exhaustive check: does any operation sequence up to max_len distinguish x,y
        under any observation? Returns (equivalent: bool, certificate or None)."""
        max_len = max_len if max_len is not None else (self.H or 3)
        op_items = [(name, fn, c) for name, (fn, ctxs) in self.T.items() for c in ctxs]
        for length in range(0, max_len + 1):
            for seq in itertools.product(op_items, repeat=length):
                sx, sy = x, y
                for (name, fn, c) in seq:
                    sx = fn(sx, c)
                    sy = fn(sy, c)
                for oname, ofn in self.O.items():
                    ox, oy = self._safe(ofn, sx), self._safe(ofn, sy)
                    if ox != oy:
                        cert = CounterexampleCertificate(
                            abstraction="(identity -- raw state comparison)",
                            state_x=x, state_y=y, abstraction_x=x, abstraction_y=y,
                            operation_sequence=tuple(f"{n}({c!r})" for n, _, c in seq),
                            observation=oname, output_x=ox, output_y=oy,
                            violated_property="behavioral equivalence",
                        )
                        return False, cert
        return True, None

    # ---------------------------------------------------------- congruence test for F
    def congruence_test(self, F: Callable[[Any], Hashable], name: str = "F"):
        """F(x)=F(y) => F(T(x,c))=F(T(y,c)) for every T,c. Returns (bool, certificate|None)."""
        by_image = {}
        for e in self.E:
            by_image.setdefault(self._safe(F, e), []).append(e)
        for img, members in by_image.items():
            for x, y in itertools.combinations(members, 2):
                for opname, (fn, ctxs) in self.T.items():
                    for c in ctxs:
                        try:
                            tx, ty = fn(x, c), fn(y, c)
                        except Exception:
                            continue
                        fx, fy = self._safe(F, tx), self._safe(F, ty)
                        if fx != fy:
                            cert = CounterexampleCertificate(
                                abstraction=name, state_x=x, state_y=y,
                                abstraction_x=img, abstraction_y=img,
                                operation_sequence=(f"{opname}({c!r})",),
                                observation="F(post-state)", output_x=fx, output_y=fy,
                                violated_property="congruence",
                            )
                            return False, cert
        return True, None

    # ---------------------------------------------------------- behavioral sufficiency of F
    def sufficiency_test(self, F: Callable[[Any], Hashable], name: str = "F"):
        """F(x)=F(y) => x ~B y. The 'dangerous direction' (KSME-14's Phase I).
        Returns (bool, certificate|None)."""
        bp = self.behavioral_partition()
        classes = bp["classes"]
        state_to_class = {}
        for cid, cls in enumerate(classes):
            for e in cls:
                state_to_class[e] = cid
        by_image = {}
        for e in self.E:
            by_image.setdefault(self._safe(F, e), []).append(e)
        for img, members in by_image.items():
            for x, y in itertools.combinations(members, 2):
                if state_to_class.get(x) != state_to_class.get(y):
                    eq, cert = self.behaviorally_equivalent(x, y)
                    if cert is not None:
                        cert.abstraction = name
                        cert.abstraction_x = img
                        cert.abstraction_y = img
                        cert.violated_property = "behavioral sufficiency (F(x)=F(y) does not imply x~By)"
                    return False, cert
        return True, None

    def necessity_test(self, F: Callable[[Any], Hashable], name: str = "F"):
        """x ~B y => F(x)=F(y). The 'safe direction' -- does F collapse only what
        behavior already collapses (no over-distinguishing)?"""
        bp = self.behavioral_partition()
        for cls in bp["classes"]:
            imgs = {self._safe(F, e) for e in cls}
            if len(imgs) > 1:
                members = list(cls)
                x, y = members[0], members[1]
                cert = CounterexampleCertificate(
                    abstraction=name, state_x=x, state_y=y,
                    abstraction_x=self._safe(F, x), abstraction_y=self._safe(F, y),
                    operation_sequence=(), observation="F", output_x=self._safe(F, x),
                    output_y=self._safe(F, y),
                    violated_property="necessity (x~By but F(x)!=F(y): F over-distinguishes)",
                )
                return False, cert
        return True, None

    # ---------------------------------------------------------- complexity report
    def complexity_report(self):
        n_ops = sum(len(ctxs) for _, ctxs in self.T.values())
        return {
            "|E|": len(self.E), "|T|": len(self.T), "n_op_context_pairs": n_ops,
            "|O|": len(self.O), "horizon": self.H,
        }


# ================================================================== minimality notions
def component_minimality(E, components: list[str], extract: Callable[[Any, str], Hashable],
                          regime: Regime, max_subset_size: int | None = None):
    """M_component: smallest S subseteq components such that pi_S is behaviorally
    sufficient (pi_S(x)=pi_S(y) => x~By). Enumerates subsets smallest-first."""
    m = len(components)
    max_subset_size = max_subset_size if max_subset_size is not None else m
    results = []
    for size in range(0, max_subset_size + 1):
        for S in itertools.combinations(components, size):
            def F(e, _S=S):
                return tuple(extract(e, c) for c in _S)
            ok, cert = regime.sufficiency_test(F, name=f"pi_{S}")
            results.append({"S": S, "size": size, "sufficient": ok,
                            "counterexample": cert.to_dict() if cert else None})
            if ok:
                return {"minimal_S": S, "size": size, "all_tested": results}
    return {"minimal_S": None, "size": None, "all_tested": results,
            "note": "no subset up to max_subset_size was sufficient"}


def partition_minimality(regime: Regime):
    """M_partition: the coarsest behavior-preserving partition IS the behavioral
    partition itself, by construction of the fixed-point refinement."""
    bp = regime.behavioral_partition()
    return {"n_classes": bp["n_classes"], "rounds_to_fixed_point": bp["rounds"],
            "classes_sizes": sorted(len(c) for c in bp["classes"])}


def representation_minimality(candidates: dict, regime: Regime, cost_fn: Callable[[str], float]):
    """M_representation: among named candidate abstractions F, the min-cost one that
    is behaviorally sufficient. cost_fn(name) -> float, defined by the caller,
    disclosed explicitly (never invented silently)."""
    sufficient = []
    for name, F in candidates.items():
        ok, cert = regime.sufficiency_test(F, name=name)
        entry = {"name": name, "sufficient": ok, "cost": cost_fn(name) if ok else None,
                 "counterexample": cert.to_dict() if cert else None}
        sufficient.append(entry)
    feasible = [e for e in sufficient if e["sufficient"]]
    best = min(feasible, key=lambda e: e["cost"]) if feasible else None
    return {"all_candidates": sufficient, "best": best}
