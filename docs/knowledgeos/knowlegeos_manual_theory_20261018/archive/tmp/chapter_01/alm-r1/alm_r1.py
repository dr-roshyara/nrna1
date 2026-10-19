"""ALM-r1: implementation of ALM-R1-SPEC.md (S-0 .. S-8). Research candidate, not validated.

Design rule: Theta has NO defaults. Every reserved policy parameter must be stated by the caller
(see S-11). Register IDs are used for parameter names wherever an ID exists.
"""
from __future__ import annotations
from dataclasses import dataclass, replace
from itertools import product

INF = float("inf")
ROOT = "ROOT"
KINDS = {"GRANT", "REVOKE", "CONFIRM", "ADOPT", "END", "PERFORM", "ACCEPT"}


@dataclass(frozen=True)
class Act:
    id: str
    kind: str
    holder: str
    performer: str | None = None
    tau: int | None = None
    ord: int | None = None
    # GRANT
    grantee: str | None = None
    scope: frozenset = frozenset()
    period: tuple | None = None            # None = [tau, inf): no stated period runs from the act
    proviso: bool = False
    bars: frozenset = frozenset()          # {(purpose, performer, work|'*')}
    # REVOKE / CONFIRM / END
    target: str | None = None
    # ADOPT
    subject: str | None = None
    ckind: str = "decision"
    cperiod: tuple = (0, INF)
    # PERFORM / ACCEPT
    activity: str | None = None
    work: str | None = None
    # evidence attributes (not authority inputs)
    attested: bool = False
    disclosure: bool = False


@dataclass(frozen=True)
class Theta:
    """Every field is a reserved policy parameter or a stated assumption (S-11). No defaults."""
    C1: str            # 'b' (act establishes; records evidence) | 'a' (recorded act)
    RD1: dict          # claim kind -> 'persist' | 'cascade'
    delta: str         # 'independent' | 'dependent'          (RD 1, delegation)
    BASIS: str         # 'any' | 'all'                        [Cand]
    C10: str           # 'given' | 'no_same_instant'
    iota_g: str        # 'closed' | 'half_open'               (C14, grants)
    iota_c: str        # 'closed' | 'half_open'               [Cand, claim periods]
    C6: str            # 'off' | 'later_prevails'
    BAR: str           # 'any' | 'all'                        [Cand]
    root: frozenset    # {(holder, atom)} static root          (C12: static only)

    def __hash__(self):
        return hash((self.C1, tuple(sorted(self.RD1.items())), self.delta, self.BASIS, self.C10,
                     self.iota_g, self.iota_c, self.C6, self.BAR, self.root))


def in_period(conv, t, period):
    lo, hi = period
    return lo <= t <= hi if conv == "closed" else lo <= t < hi


def gperiod(g):
    """Effective grant period (spec S-2): a grant with no stated period runs [tau, inf)."""
    return g.period if g.period is not None else (g.tau, INF)


def covers(S, atom):
    return atom in S or (atom[0], "*") in S or ("*", "*") in S


class History:
    """V layer on an act history H (S-4). rho: optional record times, used only when C1='a'."""

    def __init__(self, acts, theta: Theta, rho: dict | None = None):
        self.acts = list(acts)
        self.by_id = {a.id: a for a in self.acts}
        if len(self.by_id) != len(self.acts):
            raise ValueError("duplicate act ids")
        self.th = theta
        self.rho = rho or {}
        if theta.C10 == "given":
            keys = [(a.tau, a.ord) for a in self.acts]
            if any(t is None or o is None for t, o in keys) or len(set(keys)) != len(keys):
                raise ValueError("C10='given' requires distinct, defined (tau, ord) for every act")
        elif any(a.tau is None for a in self.acts):
            raise ValueError("every act needs tau")
        self._valid, self._basis = {}, {}
        for e in sorted(self.acts, key=lambda a: (a.tau, a.ord if a.ord is not None else 0, a.id)):
            self._eval(e)

    # ---- order and availability (S-3, S-4.7)
    def prec(self, f, e):
        if self.th.C10 == "given":
            return (f.tau, f.ord) < (e.tau, e.ord)
        return f.tau < e.tau

    def _recorded_by(self, f, s):
        r = self.rho.get(f.id)
        return r is not None and r <= s

    def avail(self, f, point):
        """f is usable at point (('act', e) or ('time', t))."""
        kind, x = point
        if kind == "act":
            ok = self.prec(f, x)
            s = x.tau
        else:
            ok = f.tau <= x                              # C16 inclusive
            s = x
        if ok and self.th.C1 == "a":
            ok = self._recorded_by(f, s)
        return ok

    # ---- S-4.1 .. S-4.5
    def form(self, e):
        if e.kind not in KINDS:
            return False
        if e.kind == "GRANT":
            return gperiod(e)[0] >= e.tau
        if e.kind in ("REVOKE", "CONFIRM"):
            g = self.by_id.get(e.target)
            return g is not None and g.kind == "GRANT" and self.avail(g, ("act", e))
        if e.kind == "END":
            a = self.by_id.get(e.target)
            return a is not None and a.kind == "ADOPT" and self.avail(a, ("act", e))
        return True

    def need(self, e):
        if e.kind == "GRANT":
            return {("GRANT", atom) for atom in e.scope}
        if e.kind in ("REVOKE", "CONFIRM"):
            return {(e.kind, e.target)}
        if e.kind == "ADOPT":
            return {("ADOPT", e.subject)}
        if e.kind == "END":
            return {("END", self.by_id[e.target].subject)}
        if e.kind == "PERFORM":
            return {("PERFORM", (e.activity, e.work))}
        if e.kind == "ACCEPT":
            return {("ACCEPT", e.work)}
        return {("?", None)}

    def alive(self, g, point):
        if not (self._valid.get(g.id) and self.avail(g, point)):
            return False
        t = point[1].tau if point[0] == "act" else point[1]
        if not in_period(self.th.iota_g, t, gperiod(g)):
            return False
        for r in self.acts:
            if r.kind == "REVOKE" and r.target == g.id and self.avail(r, point) and self._valid.get(r.id):
                return False
        if g.proviso and not any(c.kind == "CONFIRM" and c.target == g.id and self.avail(c, point)
                                 and self._valid.get(c.id) for c in self.acts):
            return False
        if self.th.delta == "dependent":
            b = self._basis.get(g.id, set())
            if ROOT not in b and not any(self.alive(self.by_id[p], point) for p in b):
                return False
        return True

    def _root_covers(self, e, needs):
        rs = {atom for (h, atom) in self.th.root if h == e.holder}
        return bool(needs) and all(covers(rs, n) for n in needs)

    def restr(self, e):                              # IND-1 as an authority restriction
        if e.kind != "ACCEPT":
            return False
        return any(f.kind == "PERFORM" and f.activity == "implement" and f.work == e.work
                   and self._valid.get(f.id) and self.avail(f, ("act", e)) and f.holder == e.holder
                   for f in self.acts)

    def _eval(self, e):
        if self.th.C1 == "a" and self.rho.get(e.id) is None:
            self._valid[e.id], self._basis[e.id] = False, set()
            return
        if not self.form(e):
            self._valid[e.id], self._basis[e.id] = False, set()
            return
        needs = self.need(e)
        basis = set()
        if self._root_covers(e, needs):
            basis.add(ROOT)
        for g in self.acts:
            if g.kind == "GRANT" and g.grantee == e.holder and g.id != e.id \
                    and all(covers(g.scope, n) for n in needs) and self.alive(g, ("act", e)):
                basis.add(g.id)
        self._basis[e.id] = basis
        self._valid[e.id] = bool(basis) and not self.restr(e)

    # ---- public V-layer queries
    def valid(self, eid):
        return self._valid[eid]

    def auth(self, eid):
        e = self.by_id[eid]
        return bool(self._basis[eid]) and not self.restr(e)

    def basis(self, eid):
        return set(self._basis[eid])

    def current(self, cid, t):
        a = self.by_id[cid]
        if a.kind != "ADOPT" or not self._valid[cid] or a.tau > t:
            return False
        if not in_period(self.th.iota_c, t, a.cperiod):
            return False
        pt = ("time", t)
        if any(x.kind == "END" and x.target == cid and self._valid[x.id] and self.avail(x, pt)
               for x in self.acts):
            return False
        if self.th.RD1[a.ckind] == "cascade":
            b = self._basis[cid]
            if ROOT not in b:
                alive = [self.alive(self.by_id[g], pt) for g in b]
                q = any if self.th.BASIS == "any" else all
                if not (alive and q(alive)):
                    return False
        if self.th.C6 == "later_prevails":
            if any(x.kind == "ADOPT" and x.subject == a.subject and x.id != cid and self._valid[x.id]
                   and self.prec(a, x) and self.avail(x, pt) for x in self.acts):
                return False
        return True

    def elig(self, eid, purpose, work):
        e = self.by_id[eid]
        if not self._valid[eid]:
            return "NOT_VALID", []
        if e.performer is None:
            return "UNDETERMINED", []
        if purpose == "independent_verification" and any(
                f.kind == "PERFORM" and f.activity == "implement" and f.work == work
                and f.performer == e.performer and self.avail(f, ("act", e)) for f in self.acts):
            return "INELIGIBLE", ["IND-2"]
        grants = [self.by_id[g] for g in self._basis[eid] if g != ROOT]
        barred = [any(b[0] == purpose and b[1] == e.performer and b[2] in (work, "*") for b in g.bars)
                  for g in grants]
        if grants and ((self.th.BAR == "any" and any(barred)) or
                       (self.th.BAR == "all" and all(barred) and ROOT not in self._basis[eid])):
            return "INELIGIBLE", ["IND-5"]
        flags = []
        if purpose == "governance_review" and any(
                f.kind == "PERFORM" and f.activity == "implement" and f.work == work
                and f.performer == e.performer for f in self.acts) and not e.disclosure:
            flags.append("IND-3 DISCLOSURE_REQUIRED (governance defect)")
        return ("ELIGIBLE_ATTESTED" if e.attested else "ELIGIBLE_ASSERTED"), flags


# ======================= R layer (S-5, S-6) =======================

@dataclass(frozen=True)
class Entry:
    pos: int
    act: Act               # as recorded; fields may be None
    rho: int | None = None


def worlds(entries, k, theta, tau_dom, perf_dom, max_worlds=5000):
    """S-6 worlds: variant choice per contradicted id x assignment of unknown fields."""
    Ek = [en for en in entries if en.pos <= k]
    variants: dict[str, list] = {}
    for en in sorted(Ek, key=lambda x: x.pos):
        lst = variants.setdefault(en.act.id, [])
        if (en.act, en.rho) not in lst:
            lst.append((en.act, en.rho))
    ids = list(variants)
    out = []
    for choice in product(*[variants[i] for i in ids]):
        acts = [a for a, _ in choice]
        rho = {a.id: r for a, r in choice}
        slots = []                                   # (index, field, domain)
        for i, a in enumerate(acts):
            if a.tau is None:
                slots.append((i, "tau", tau_dom))
            if a.performer is None and a.kind in ("PERFORM", "ACCEPT"):
                slots.append((i, "performer", perf_dom))
            if theta.C10 == "given" and a.ord is None:
                slots.append((i, "ord", list(range(len(acts)))))
            if theta.C1 == "a" and rho[a.id] is None:
                slots.append((i, "rho", tau_dom))
        for vals in product(*[d for _, _, d in slots]):
            aa, rr = list(acts), dict(rho)
            for (i, f, _), v in zip(slots, vals):
                if f == "rho":
                    rr[aa[i].id] = v
                else:
                    aa[i] = replace(aa[i], **{f: v})
            if theta.C1 == "a" and any(rr.get(a.id) is not None and a.tau is not None and rr[a.id] < a.tau
                                       for a in aa):
                continue                             # AX-NoBackdate
            try:
                out.append(History(aa, theta, rr))
            except ValueError:
                continue                             # e.g. colliding (tau, ord): not a world
            if len(out) > max_worlds:
                raise RuntimeError("world bound exceeded")
    return out


def _eval_q(H: History, q):
    kind = q[0]
    if kind == "valid":
        return H.valid(q[1]), H.by_id[q[1]].tau
    if kind == "auth":
        return H.auth(q[1]), H.by_id[q[1]].tau
    if kind == "current":
        return H.current(q[1], q[2]), q[2]
    raise ValueError(q)


def rec(entries, k, q, theta, now, lam_pos, lam_neg, tau_dom=(1, 2, 3), perf_dom=("p1", "p2")):
    """S-6.1. q = ('valid', id) | ('auth', id) | ('current', cid, t). Returns 'T'|'F'|'U'."""
    if not any(en.act.id == q[1] and en.pos <= k for en in entries):
        return "U"
    W = worlds(entries, k, theta, tau_dom, perf_dom)
    if not W:
        return "U"
    vals = [_eval_q(H, q) for H in W]
    if all(t is not None and t <= now - lam_pos and v for v, t in vals):
        return "T"
    if all(t is not None and t <= now - lam_neg and not v for v, t in vals):
        return "F"
    return "U"


# ======================= O layer (S-7) and dated questions (S-8) =======================

def eff(entries, k, eid, theta, now, lam_pos, lam_neg, proc=lambda k, eid: True, **dom):
    known = any(en.act.id == eid and en.pos <= k for en in entries)
    return known and proc(k, eid) and rec(entries, k, ("valid", eid), theta, now, lam_pos, lam_neg, **dom) == "T"


def usable_from(entries, eid, theta, now, lam_pos, lam_neg, **dom):
    kmax = max((en.pos for en in entries), default=0)
    for k in range(kmax + 1):
        if eff(entries, k, eid, theta, now, lam_pos, lam_neg, **dom):
            return k
    return None


def rec_at_date(entries, d, witnesses, q, theta, now_of, lam_pos, lam_neg, **dom):
    """S-8: now_of(k) gives the reading instant for prefix k (normally d)."""
    kmax = max((en.pos for en in entries), default=0)
    lo = max([p for (dt, p) in witnesses if dt <= d], default=0)
    hi_c = [p for (dt, p) in witnesses if dt > d]
    hi = (min(hi_c) - 1) if hi_c else kmax
    vs = {rec(entries, k, q, theta, now_of(k), lam_pos, lam_neg, **dom) for k in range(lo, max(lo, hi) + 1)}
    return (vs.pop() if len(vs) == 1 else "U"), (lo, hi)
