"""ALM: reference implementation of the layered Authority-Lifecycle Model.

Mirrors ALM-FORMAL-MODEL.md definition by definition (V1-V4, R1-R2, O1).
Research artefact: a candidate model, not adopted policy.
"""
from __future__ import annotations
from dataclasses import dataclass, field
from itertools import product

INF = float("inf")

AUTHORIZED, NOT_AUTHORIZED, UNDETERMINED = "AUTHORIZED", "NOT_AUTHORIZED", "UNDETERMINED"
CURRENT, NOT_CURRENT = "CURRENT", "NOT_CURRENT"


@dataclass(frozen=True)
class Act:
    id: str
    actor: str
    kind: str                 # GRANT | REVOKE | CONFIRM | ADOPT | END_CLAIM | PERFORM
    tau: int                  # act time
    # GRANT
    holder: str | None = None
    scope: frozenset = frozenset()          # {(kind, subject)}
    period: tuple = (0, INF)
    proviso: bool = False                   # True = pending until a valid CONFIRM
    bars: frozenset = frozenset()           # {(purpose, actor)}
    # REVOKE / CONFIRM / END_CLAIM target; PERFORM/ADOPT subject
    target: str | None = None
    subject: str | None = None
    claim_period: tuple = (0, INF)


@dataclass
class Model:
    """Parameters: root, iota (closed|half_open), kappa (persist|cascade)."""
    root: frozenset = frozenset({("R", "*")})   # (actor, kind or '*')
    iota: str = "closed"
    kappa: str = "persist"

    # ---------- helpers ----------
    def in_period(self, t, period):
        lo, hi = period
        return lo <= t <= hi if self.iota == "closed" else lo <= t < hi

    def is_root(self, act: Act) -> bool:
        return any(a == act.actor and (k == "*" or k == act.kind) for a, k in self.root)

    @staticmethod
    def required_pair(act: Act, by_id: dict):
        if act.kind == "GRANT":
            return ("GRANT", "*")
        if act.kind in ("REVOKE", "CONFIRM"):
            return (act.kind, "*")
        if act.kind == "END_CLAIM":
            tgt = by_id.get(act.target)
            return ("END_CLAIM", tgt.subject if tgt else None)
        return (act.kind, act.subject)        # ADOPT, PERFORM

    @staticmethod
    def covers(g: Act, pair) -> bool:
        k, s = pair
        return (k, s) in g.scope or (k, "*") in g.scope

    # ---------- V layer (well-founded recursion on the strict order) ----------
    def order(self, H):
        """A0: strict total order = (tau, position in the given history list)."""
        return sorted(enumerate(H), key=lambda ix: (ix[1].tau, ix[0]))

    def validity(self, H):
        """Returns {act.id: bool}. Theorem 1: unique by recursion on the order."""
        ordered = [a for _, a in self.order(H)]
        by_id = {a.id: a for a in H}
        valid: dict[str, bool] = {}
        rank = {a.id: i for i, a in enumerate(ordered)}
        for e in ordered:
            valid[e.id] = self._valid_given(e, ordered, rank, valid, by_id)
        return valid

    def _in_force_for(self, g, e_rank, t, ordered, rank, valid, by_id, strict=True):
        """V1 with 'before' meaning rank < e_rank (strict)."""
        before = (lambda x: rank[x.id] < e_rank) if strict else (lambda x: rank[x.id] <= e_rank)
        if not (valid.get(g.id) and before(g)):
            return False
        if not self.in_period(t, g.period):
            return False
        for r in ordered:
            if r.kind == "REVOKE" and r.target == g.id and before(r) and valid.get(r.id):
                return False
        if g.proviso:
            if not any(c.kind == "CONFIRM" and c.target == g.id and before(c) and valid.get(c.id)
                       for c in ordered):
                return False
        return True

    def authorizing_grants(self, e, ordered, rank, valid, by_id):
        pair = self.required_pair(e, by_id)
        return [g for g in ordered if g.kind == "GRANT" and g.holder == e.actor
                and self.covers(g, pair)
                and self._in_force_for(g, rank[e.id], e.tau, ordered, rank, valid, by_id)]

    def _valid_given(self, e, ordered, rank, valid, by_id):
        if self.is_root(e):
            return True
        return bool(self.authorizing_grants(e, ordered, rank, valid, by_id))

    def current(self, H, cid: str, t: int) -> bool:
        """V3: is claim adopted by ADOPT act `cid` current at evaluation time t."""
        ordered = [a for _, a in self.order(H)]
        rank = {a.id: i for i, a in enumerate(ordered)}
        by_id = {a.id: a for a in H}
        valid = self.validity(H)
        a = by_id[cid]
        if not (valid[a.id] and a.tau <= t):
            return False
        if not self.in_period(t, a.claim_period):
            return False
        if any(x.kind == "END_CLAIM" and x.target == cid and valid[x.id] and x.tau <= t for x in H):
            return False
        if self.kappa == "cascade" and not self.is_root(a):
            # authority that made the claim must still be in force at t
            # (hypothetical act at t, ordered after every act with tau <= t)
            hyp_rank = max([rank[x.id] for x in ordered if x.tau <= t], default=-1) + 0.5
            ok = any(g.kind == "GRANT" and g.holder == a.actor
                     and self.covers(g, self.required_pair(a, by_id))
                     and self._in_force_for(g, hyp_rank, t, ordered,
                                            {**rank}, valid, by_id)
                     for g in ordered)
            if not ok:
                return False
        return True

    def eligible(self, H, eid: str, purpose: str, general_bars=frozenset()) -> bool:
        """V4: valid AND not barred (general bars B0 or bars of an authorizing grant)."""
        ordered = [a for _, a in self.order(H)]
        rank = {a.id: i for i, a in enumerate(ordered)}
        by_id = {a.id: a for a in H}
        valid = self.validity(H)
        e = by_id[eid]
        if not valid[eid]:
            return False
        if (purpose, e.actor) in general_bars:
            return False
        grants = self.authorizing_grants(e, ordered, rank, valid, by_id)
        if grants and all((purpose, e.actor) in g.bars for g in grants):
            return False   # every authorizing grant bars this use
        return True

    # ---------- R layer ----------
    def recognize_valid(self, H, pos: dict, k: int, eid: str, now: int, lam: float):
        """R2 for 'is act eid valid'. pos: act.id -> record position (absent = unrecorded)."""
        Hk = [a for a in H if a.id in pos and pos[a.id] <= k]
        ids = {a.id for a in Hk}
        if eid not in ids:
            return UNDETERMINED                     # the act itself is not on record
        t = next(a.tau for a in Hk if a.id == eid)
        if t > now - lam:
            return UNDETERMINED
        return AUTHORIZED if self.validity(Hk)[eid] else NOT_AUTHORIZED

    def recognize_current(self, H, pos, k, cid, t, now, lam):
        Hk = [a for a in H if a.id in pos and pos[a.id] <= k]
        if t > now - lam:
            return UNDETERMINED
        if cid not in {a.id for a in Hk}:
            return UNDETERMINED                     # r1.1: records do not establish it
        return CURRENT if self.current(Hk, cid, t) else NOT_CURRENT

    # ---------- O layer ----------
    def effective(self, H, pos, k, eid, now, lam, procedural_ok=True):
        recorded = eid in pos and pos[eid] <= k
        return (recorded and procedural_ok
                and self.recognize_valid(H, pos, k, eid, now, lam) == AUTHORIZED)

    # ---------- alternative semantics, for the uniqueness counterexample ----------
    def stable_assignments_same_instant(self, H):
        """Non-strict semantics: an act may depend on acts with tau <= its own tau
        (excluding itself). Returns all assignments that are fixed points."""
        acts = list(H)
        by_id = {a.id: a for a in acts}
        sols = []
        for bits in product([False, True], repeat=len(acts)):
            valid = {a.id: b for a, b in zip(acts, bits)}
            ok = True
            for e in acts:
                if self.is_root(e):
                    want = True
                else:
                    want = False
                    pair = self.required_pair(e, by_id)
                    for g in acts:
                        if g is e or g.kind != "GRANT" or g.holder != e.actor:
                            continue
                        if not (valid[g.id] and g.tau <= e.tau and self.covers(g, pair)
                                and self.in_period(e.tau, g.period)):
                            continue
                        revoked = any(r is not e and r.kind == "REVOKE" and r.target == g.id
                                      and r.tau <= e.tau and valid[r.id] for r in acts)
                        if not revoked:
                            want = True
                            break
                if want != valid[e.id]:
                    ok = False
                    break
            if ok:
                sols.append(valid)
        return sols
