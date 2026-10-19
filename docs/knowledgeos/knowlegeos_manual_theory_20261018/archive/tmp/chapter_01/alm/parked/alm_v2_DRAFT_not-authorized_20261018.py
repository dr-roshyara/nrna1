"""ALM v2: reference implementation of the layered Authority-Lifecycle Model.

Mirrors ALM-FORMAL-MODEL.md (v2) definition by definition. It is a research candidate:
not validated, not adopted. v1 is preserved as alm_v1.py.

Layers (kept separate on purpose):
  A  authority     Auth(e)            - did the actor hold a covering grant in force?
  F  form          Form(e)            - is it an act of a recognised kind, well-formed?
  V  validity      Valid(e)=Auth&Form
  G  eligibility   Elig(e,purpose)    - may this valid act serve this purpose?
  E  evidence      record items, quality, contradictions
  R  recognition   Rec_k(question)    - three-valued, with the basis of the verdict
  O  operational   Effective_k(e), UsableFrom(e)
"""
from __future__ import annotations
from dataclasses import dataclass, field, replace
from itertools import product

INF = float("inf")
T, F, U = "T", "F", "U"          # Kleene truth values

ACT_KINDS = {"GRANT", "REVOKE", "CONFIRM", "ADOPT", "END_CLAIM", "PERFORM"}


# ------------------------------------------------------------------ Kleene logic
def k_not(a): return {T: F, F: T, U: U}[a]
def k_and(*xs):
    return F if F in xs else (U if U in xs else T)
def k_or(*xs):
    return T if T in xs else (U if U in xs else F)
def b(x: bool): return T if x else F


@dataclass(frozen=True)
class Act:
    id: str
    actor: str
    kind: str                      # one of ACT_KINDS, or a non-act such as NOTE
    tau: int                       # act time
    seq: int = 0                   # A0 tie-break within equal tau (history-supplied)
    # GRANT
    holder: str | None = None
    scope: frozenset = frozenset()          # {(kind, object)}; object '*' = any
    period: tuple = (0, INF)
    proviso: bool = False
    bars: frozenset = frozenset()           # {(purpose, actor)}
    max_uses: float = INF                   # single-use = 1
    human_act_ref: str | None = "h"         # R5b form requirement for grants
    # targets / objects
    target: str | None = None               # REVOKE/CONFIRM: grant id; END_CLAIM: claim id
    subject: str | None = None              # ADOPT subject; PERFORM work item
    activity: str | None = None             # PERFORM: implement|verify|review|complete|accept
    claim_period: tuple = (0, INF)
    disclosure: bool = False                # A-5.2 D-a..D-d present (eng->gov review)


@dataclass
class Params:
    root: frozenset = frozenset({("R", "*")})   # (actor, kind|'*')      [Asm A1]
    iota: str = "closed"                          # interval convention    [Pol]
    kappa: str = "persist"                        # carry-over of endings  [Pol]
    consume: str = "valid_uses"                   # what consumes a use    [Pol]
    general_bars: bool = True                     # B0 (IND-2)             [Src/Repo]
    ind6_gate_rule: bool = False                  # IND-6 (PROPOSED only)  [Pol]
    lam_neg: float = INF                          # horizon for negatives from absence [Pol]
    lam_pos: float = 0                            # horizon for positives   [Pol]
    # switches used ONLY to demonstrate invariant violations (never policy):
    allow_backdated_period: bool = False          # breaks INV-5
    retroactive_revocation: bool = False          # breaks INV-4 (revocation effective from grant start)


class ALM:
    def __init__(self, params: Params | None = None):
        self.p = params or Params()

    # ------------------------------------------------------------ helpers
    def in_period(self, t, period):
        lo, hi = period
        return lo <= t <= hi if self.p.iota == "closed" else lo <= t < hi

    def is_root(self, e: Act):
        return any(a == e.actor and (k == "*" or k == e.kind) for a, k in self.p.root)

    @staticmethod
    def key(e: Act):                              # A0: strict total order
        return (e.tau, e.seq, e.id)

    @staticmethod
    def need(e: Act, by_id):
        """(kind, object) the act needs to be covered by a grant scope."""
        if e.kind == "GRANT":
            return ("GRANT", "*")
        if e.kind in ("REVOKE", "CONFIRM"):
            return (e.kind, "*")
        if e.kind == "END_CLAIM":
            c = by_id.get(e.target)
            return ("END_CLAIM", c.subject if c else None)
        if e.kind == "ADOPT":
            return ("ADOPT", e.subject)
        if e.kind == "PERFORM":
            return (e.activity, e.subject)
        return (e.kind, None)

    @staticmethod
    def covers(g: Act, need):
        k, o = need
        return (k, o) in g.scope or (k, "*") in g.scope

    def form(self, e: Act) -> bool:
        """F: recognised act kind; grants carry a human-act reference (R5b).
        A NOTE (recording note, observation) is never an act (R-60, R-34)."""
        if e.kind not in ACT_KINDS:
            return False
        if e.kind == "GRANT" and not e.human_act_ref and not self.is_root(e):
            return False
        return True

    # ------------------------------------------------------------ A / V layers
    def evaluate(self, H):
        """Returns dict id -> {'auth','valid','grant'} computed by recursion on A0 order.
        'grant' = the grant used (for consumption and cascade), or 'ROOT'."""
        acts = sorted(H, key=self.key)
        by_id = {a.id: a for a in acts}
        res: dict[str, dict] = {}
        uses: dict[str, int] = {}
        for e in acts:
            auth, used = False, None
            if self.is_root(e):
                auth, used = True, "ROOT"
            else:
                for g in self.grants_in_force_for(e, acts, res, by_id, uses):
                    auth, used = True, g.id
                    break                          # [Pol] earliest covering grant is used
            valid = auth and self.form(e)
            res[e.id] = {"auth": auth, "valid": valid, "grant": used if valid else None}
            if valid and used not in (None, "ROOT") and e.kind in ("PERFORM", "ADOPT"):
                uses[used] = uses.get(used, 0) + 1
            elif self.p.consume == "attempted_uses" and e.kind in ("PERFORM", "ADOPT"):
                for g in acts:
                    if g.kind == "GRANT" and g.holder == e.actor and self.covers(g, self.need(e, by_id)) \
                            and self.key(g) < self.key(e):
                        uses[g.id] = uses.get(g.id, 0) + 1
                        break
        return res

    def grants_in_force_for(self, e, acts, res, by_id, uses, at_time=None, strict_key=None):
        t = e.tau if at_time is None else at_time
        ek = self.key(e) if strict_key is None else strict_key
        out = []
        for g in acts:
            if g.kind != "GRANT" or g.holder != e.actor or self.key(g) >= ek:
                continue
            if not res.get(g.id, {}).get("valid"):
                continue
            if not self.covers(g, self.need(e, by_id)):
                continue
            if not self.in_period(t, g.period):
                continue
            if g.period[0] < g.tau and not self.p.allow_backdated_period:
                continue                            # INV-5: never in force before given
            if self.p.retroactive_revocation:
                # violation switch: a revocation anywhere in H voids the grant from its start;
                # requires validity of r, which may itself be later -> uses current res only
                if any(r.kind == "REVOKE" and r.target == g.id and res.get(r.id, {}).get("valid", True)
                       for r in acts):
                    continue
            elif any(r.kind == "REVOKE" and r.target == g.id and self.key(r) < ek
                     and res.get(r.id, {}).get("valid") for r in acts):
                continue
            if g.proviso and not any(c.kind == "CONFIRM" and c.target == g.id and self.key(c) < ek
                                     and res.get(c.id, {}).get("valid") for c in acts):
                continue
            if e.kind in ("PERFORM", "ADOPT") and uses.get(g.id, 0) >= g.max_uses:
                continue                            # consumed
            out.append(g)
        return out

    def valid(self, H):
        return {i: r["valid"] for i, r in self.evaluate(H).items()}

    def current(self, H, cid, t) -> bool:
        """V3: claim adopted by act cid is current at evaluation time t."""
        acts = sorted(H, key=self.key)
        by_id = {a.id: a for a in acts}
        res = self.evaluate(H)
        a = by_id[cid]
        if not (res[cid]["valid"] and a.tau <= t and self.in_period(t, a.claim_period)):
            return False
        if any(x.kind == "END_CLAIM" and x.target == cid and res[x.id]["valid"] and x.tau <= t
               for x in acts):
            return False
        if self.p.kappa == "cascade" and res[cid]["grant"] not in (None, "ROOT"):
            g = by_id[res[cid]["grant"]]
            later_key = (t, INF, "~")                # a hypothetical act at t, after all at t
            still = (self.in_period(t, g.period) and not any(
                r.kind == "REVOKE" and r.target == g.id and self.key(r) < later_key
                and res[r.id]["valid"] for r in acts))
            if not still:
                return False
        return True

    # ------------------------------------------------------------ G layer
    def implementers(self, H, work):
        res = self.evaluate(H)
        return {e.actor for e in H if e.kind == "PERFORM" and e.activity == "implement"
                and e.subject == work and res[e.id]["valid"]}

    def eligible(self, H, eid, purpose, attested_identity=False):
        """Returns (verdict, flags). verdict in
        ELIGIBLE_ATTESTED | ELIGIBLE_ASSERTED | INELIGIBLE | NOT_VALID."""
        by_id = {a.id: a for a in H}
        e = by_id[eid]
        res = self.evaluate(H)
        flags = []
        if not res[eid]["valid"]:
            return "NOT_VALID", flags
        g = by_id.get(res[eid]["grant"])
        if g is not None and (purpose, e.actor) in g.bars:
            return "INELIGIBLE", ["IND-5 grant bar"]
        if self.p.general_bars and purpose == "independent_verification" \
                and e.actor in self.implementers(H, e.subject):
            return "INELIGIBLE", ["IND-2 implementer may not independently verify"]
        if self.p.ind6_gate_rule and purpose == "gate_verdict" and e.actor in self._gated_parties(H, e.subject):
            return "INELIGIBLE", ["IND-6 (proposed) gated party issues verdict"]
        if purpose == "governance_review" and any(
                x.actor == e.actor and x.kind == "PERFORM" and x.subject == e.subject
                and x.activity in ("implement", "architecture") for x in H):
            if not e.disclosure:
                flags.append("IND-3 governance defect: disclosure D-a..D-d missing")
        return ("ELIGIBLE_ATTESTED" if attested_identity else "ELIGIBLE_ASSERTED"), flags  # IND-7

    @staticmethod
    def _gated_parties(H, work):
        return {e.actor for e in H if e.kind == "PERFORM" and e.subject == work
                and e.activity == "implement"}

    # ------------------------------------------------------------ E + R layers
    @staticmethod
    def prefix(records, k):
        """records: list of (pos, Act-as-recorded). Returns acts known at k, and
        the ids whose records contradict each other (E layer)."""
        seen: dict[str, Act] = {}
        contradicted = set()
        for pos, a in sorted(records, key=lambda r: r[0]):
            if pos > k:
                break
            if a.id in seen and seen[a.id] != a:
                contradicted.add(a.id)
            seen.setdefault(a.id, a)
        return seen, contradicted

    def recognize_valid(self, records, k, eid, now):
        """R: three-valued recognition of Valid(eid) on the records as they stood at k.
        Returns (verdict, basis). verdict in AUTHORIZED | NOT_AUTHORIZED | UNDETERMINED."""
        known, contra = self.prefix(records, k)
        if eid not in known:
            return "UNDETERMINED", "act not on record"
        if contra:
            # Kleene: evaluate under every resolution of each contradiction
            vals = set()
            for choice in self._resolutions(records, k, contra):
                vals.add(self.valid(list(choice.values()))[eid])
            if len(vals) > 1:
                return "UNDETERMINED", "contradictory records"
        acts = list(known.values())
        v = self.valid(acts)[eid]
        t = known[eid].tau
        if v:
            if t > now - self.p.lam_pos:
                return "UNDETERMINED", "positive presumption window open"
            return "AUTHORIZED", "records establish it"
        # negative: from positive evidence or from absence?
        basis = self._negative_basis(acts, eid)
        if basis == "absence" and t > now - self.p.lam_neg:
            return "UNDETERMINED", "no covering grant on record (absence; lam_neg window open)"
        return "NOT_AUTHORIZED", f"records establish it ({basis})"

    def _resolutions(self, records, k, contra):
        alts: dict[str, list[Act]] = {}
        base: dict[str, Act] = {}
        for pos, a in records:
            if pos > k:
                continue
            if a.id in contra:
                alts.setdefault(a.id, [])
                if a not in alts[a.id]:
                    alts[a.id].append(a)
            else:
                base.setdefault(a.id, a)
        ids = list(alts)
        for combo in product(*[alts[i] for i in ids]):
            yield {**base, **dict(zip(ids, combo))}

    def _negative_basis(self, acts, eid):
        """'form' if the act is not a well-formed act; 'evidence' if a covering grant of the
        actor is on record but shown revoked/expired/unconfirmed/consumed/not-yet;
        otherwise 'absence'."""
        by_id = {a.id: a for a in acts}
        e = by_id[eid]
        if not self.form(e):
            return "form"
        covering = [g for g in acts if g.kind == "GRANT" and g.holder == e.actor
                    and self.covers(g, self.need(e, by_id))]
        return "evidence" if covering else "absence"

    def recognize_current(self, records, k, cid, t, now):
        known, contra = self.prefix(records, k)
        if cid not in known:
            return "UNDETERMINED", "adoption not on record"
        acts = list(known.values())
        if self.current(acts, cid, t):
            if t > now - self.p.lam_pos:
                return "UNDETERMINED", "positive presumption window open"
            return "CURRENT", "records establish it"
        ended = any(x.kind == "END_CLAIM" and x.target == cid and x.tau <= t for x in acts)
        if ended or not self.valid(acts)[cid] and self._negative_basis(acts, cid) != "absence":
            return "NOT_CURRENT", "records establish an ending or defect"
        if t > now - self.p.lam_neg:
            return "UNDETERMINED", "not established (absence; lam_neg window open)"
        return "NOT_CURRENT", "records establish it (absence presumed complete)"

    # ------------------------------------------------------------ O layer
    def effective(self, records, k, eid, now, procedural_ok=True):
        known, _ = self.prefix(records, k)
        return (eid in known and procedural_ok
                and self.recognize_valid(records, k, eid, now)[0] == "AUTHORIZED")

    def usable_from(self, records, eid, now):
        """Smallest record position at which the act becomes operationally effective."""
        kmax = max((p for p, _ in records), default=0)
        for k in range(0, kmax + 1):
            if self.effective(records, k, eid, now):
                return k
        return None

    # ------------------------------------------------------------ historical (date) questions
    def answer_at_date(self, records, d, witnesses, question):
        """Date-indexed recognition WITHOUT a clock in the store.
        witnesses: set of (date, pos) pairs from independent evidence ("position pos was on
        record by date"). question: callable k -> verdict. The answer is determined iff the
        verdict is the same for every position consistent with the witnesses."""
        kmax = max((p for p, _ in records), default=0)
        lo = max([p for (dt, p) in witnesses if dt <= d], default=0)
        hi_c = [p for (dt, p) in witnesses if dt > d]
        hi = min(hi_c) - 1 if hi_c else kmax          # position at d is in [lo, hi]
        verdicts = {question(k) for k in range(lo, max(lo, hi) + 1)}
        if len(verdicts) == 1:
            return verdicts.pop(), (lo, hi)
        return "UNDETERMINED", (lo, hi)

    # ------------------------------------------------------------ alternative semantics
    def stable_same_instant(self, H):
        """Non-strict semantics (acts may depend on same-tau acts): all fixed points."""
        acts = list(H)
        by_id = {a.id: a for a in acts}
        sols = []
        for bits in product([False, True], repeat=len(acts)):
            val = {a.id: x for a, x in zip(acts, bits)}
            ok = True
            for e in acts:
                if self.is_root(e):
                    want = True
                else:
                    want = any(
                        g is not e and g.kind == "GRANT" and g.holder == e.actor and val[g.id]
                        and g.tau <= e.tau and self.covers(g, self.need(e, by_id))
                        and self.in_period(e.tau, g.period)
                        and not any(r is not e and r.kind == "REVOKE" and r.target == g.id
                                    and r.tau <= e.tau and val[r.id] for r in acts)
                        for g in acts)
                    want = want and self.form(e)
                if want != val[e.id]:
                    ok = False
                    break
            if ok:
                sols.append(val)
        return sols
