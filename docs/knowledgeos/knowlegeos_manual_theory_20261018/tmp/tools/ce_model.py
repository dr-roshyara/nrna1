#!/usr/bin/env python3
"""A finite model of the evaluator of current authority, CE* (Ch. 9 §9.13, theory turn 4). A toy, not KnowledgeOS:
it tests the FORM of the repaired definition, not its content. Version 14 (C21 settlement):
every recorded use of a single-use grant counts, whatever its value now, and a use is accepted only if T (W-3); "above
the issuer's scope" read against the scope of the basis the issuance rests on (W-2); a mark for a second use present
without the guard (W-4); check 24l extended (annulments of uses and of annulments; the issuer's grantee), check 26.
Version 13 (the Principal after C20):\na grant may be marked single_use; every act under a single-use grant is a use, owner-checked, at most one not F, and names\nthe previous use of the grant (prev_use, a chain); grants not single-use get no owner check; a transfer only by the\ngrant's issuer or by an authority above the issuer's scope, never by the holder, and it carries the last use of the grant\n(last_use); every act carries its recording application; checks 24d, 24h, 24l rewritten. Version 12 (C20 settlement, window review W): owner_at
takes the last transfer that is not F (a later authorized transfer ends a U one's undetermined owner; W-1); a transfer's
scope must be its grant's scope (W-2); the owner check reads CE* values of the transfers (W-4); the owner mark reads the
owner at the use's cut (W-5); the guard trusts the declared application, a located assumption (§9.15 L10; W-3); checks
24h-24l, 24l random append sequences with transfers. Version 11 (the Principal, 2026-10-21, F22 = (C + O)):
a grant carries its owner application, fixed at issuance (field 'owner'); a consumption carries the application that
recorded it ('app'); a change of owner is kind 'transfer', appended only at the kernel and only if its authorization is
T; the guarded append (append_guard, append) refuses a use (the Professor's reading: a consumption) not recorded by the
owner the contract names when it is appended, and refuses while that owner is undetermined; a use present without the
guard (e.g. by a merge) is marked "use not recorded by its owner"; Phi does not read owners or applications (24g);
check 24. Transfers are not in the random generator. Version 10 (C19 settlement, commission T): a consumption's
only basis is the grant it consumes, which it must cite (bases_of; T2-02); a consumption does not end the currency of
adoptions made before it (§1.14.1-4; T1-04); the append guard counts only consumptions that are not F (T1-02); an
unauthorized removal is refused, since lost content cannot be restored (T1-05); removed_by is a set, and the "removal not
authorized" mark needs every removal act of the entry to be non-T (T1-06); a cut is evaluated with D checked on the whole
record (ce_star_cut; T1-01); signed-as-other with recorder = actor is marked, not refused (T1-15); checks 18e, 21, 22,
22b, 23. Version 9 (the Principal's decisions after turn 7): acts at
the same act time are unordered ("ordered before" is U; Record.before; an atom o <- not o per tied pair in the program
reading; reasons name the pair; check 10b); consumption admitted (kind 'consume', ending its grant; append_guard prevents a
second consumption; check 21); a removal is an authorized, recorded act that the tombstone cites (kind 'remove',
removed_by; marks say when the removal is not authorized; check 22); the Professor's choices under the Principal's
standing instruction: signed-as-other requires recorder != actor, and marks for an act's authorization (marks_act; 22b);
the random generator includes consumptions.
Version 8 (turn-7 settlement): the support is a closure
under citations, annulments and endings, independent of live rules (check 19); tombstones keep position and integrity
result (17b); merge() joins copies by identity (18b, 18d); crowded act times (18c); random recorder and auth (20); the
bound ratios use n + 1 with n the non-root acts. Version 7 (turn 7: check 18, records of several applications merged
without a shared serialization point; F22/K4, §9.16). Version 6: the Principal's turn-6 decisions, applied in turn 7.

Record: a list of entries in record order (positions 0..n-1). Entry kinds admitted in D (§9.13.2):
  root(id, actor, scope, t)                                    a root act: authorized by stipulation
  grant(id, actor, cites, holder, scope, t, start, end)        a grant is the product of an act (issuance)
  adopt(id, actor, cites, item, scope, t, vfrom, vto)          an adoption of item, valid on [vfrom, vto)
  revoke(id, actor, cites, item, scope, t)                     revokes the item's standing from t
  annul(id, actor, cites, target, scope, t)                    annuls an act (void from the start, at any position)
  endg(id, actor, cites, grant, scope, t)                      ends a grant (its window closes)
Every entry also carries 'ok' (the integrity check passed; default True). A record with an entry that fails it, a
missing field, or another kind (e.g. supersession) is outside D: CE* refuses ('undefined', with the reason).
Order of acts: the key (t, position); an ending counts for act x only if its key is below x's key (ties by position).
Three values T, F, U; strong Kleene connectives. Information order: U below T and below F.
covers(gscope, xscope): a given relation (equal, or gscope a prefix of xscope); pairs outside its domain give U.
The activity is not modelled (one activity). υ: 'U' (an act of undetermined authorization counts as undetermined) or
'notcount'. C1 = constitute: no adoption on record gives F.

Two independent routes: Phi on the record (lfp = KK; wfs = the alternating fixpoint), and the record translated into
a ground normal program (program(); KK by Fitting's operator, WF by unfounded sets, Van Gelder-Ross-Schlipf).
Checks (1)-(23), (2b), (3b), (9b), (10b), (11b), (15b)-(15d), (17b), (18b)-(18e), (22b): see the printed lines. Version 6: recorder and auth fields
required (defaults filled for the earlier checks), Q1's marks, removal by tombstone (17). Version 5 (turn-6 settlement): dangling citations
refused (D); refusal reasons read only U alternatives and U bodies, components named, self-annulling acts named, a soundness
check (15c), and decision 3's 'when U' cases (15d). Version 4 (turn-5 settlement): general refusal
reasons (u_reasons over the dependency graph), the tightness check (14), the reviewers' cases (15), Θ_f's fixed rules (16).
Version 3: D admits citations of earlier entries only (the
Principal, 2026-10-20); U carries its reasons (ce_star_explained). Checks 5, 7 and 8 use records outside D, to show what
the restriction excludes. Usage: python3 -I ce_model.py
"""
import random
from itertools import product

T, F, U = 'T', 'F', 'U'
FIELDS = {'root': {'actor', 'scope', 't'},
          'grant': {'actor', 'cites', 'holder', 'scope', 't', 'start', 'end'},
          'adopt': {'actor', 'cites', 'item', 'scope', 't', 'vfrom', 'vto'},
          'revoke': {'actor', 'cites', 'item', 'scope', 't'},
          'annul': {'actor', 'cites', 'target', 'scope', 't'},
          'endg': {'actor', 'cites', 'grant', 'scope', 't'},
          # v9 (the Principal after turn 7): consumption admitted; a removal is an authorized, recorded act
          'consume': {'actor', 'cites', 'grant', 'scope', 't'},     # the holder uses up the grant: it ends it after
          'remove': {'actor', 'cites', 'target', 'scope', 't'},     # the act a tombstone cites (removed_by)
          # v11 (the Principal, F22 = (C + O)): a change of a grant's owner application, recorded at the kernel
          'transfer': {'actor', 'cites', 'grant', 'new_owner', 'scope', 't', 'last_use'}}   # v13: carries the uses
FIELDS['grant'] = FIELDS['grant'] | {'owner'}                       # v11: the owner application, fixed at issuance
ENDING_KINDS = ('endg', 'consume')
KERNEL = 'kernel'                                                    # v11: the application that holds the contract
APP_KINDS = ('consume', 'transfer')                                  # v11: entries that carry their recording app


def bases_of(x):
    """v10 (C19, T2-02): a consumption's only basis is the grant it consumes; every other act's are its citations."""
    return [x['grant']] if x.get('kind') == 'consume' else x.get('cites', [])
# v6 (the Principal, after turn 6): every entry also carries its recorder and authentication status, apart from the actor
for _k in FIELDS:
    FIELDS[_k] = FIELDS[_k] | {'recorder', 'auth'}
for _k in APP_KINDS:
    FIELDS[_k] = FIELDS[_k] | {'app'}
AUTH = {'signed-as-actor', 'signed-as-other', 'unsigned'}


def k_not(a):
    return {T: F, F: T, U: U}[a]


def k_and(*xs):
    if F in xs:
        return F
    if U in xs:
        return U
    return T


def k_or(*xs):
    if T in xs:
        return T
    if U in xs:
        return U
    return F


def leq(a, b):  # information order on assignments
    return all(a[k] == U or a[k] == b[k] for k in a)


def covers(gs, xs, domain):
    if (gs, xs) not in domain:
        return U
    return T if xs == gs or xs.startswith(gs + '/') else F


class Record:
    def __init__(self, entries, cov_domain, defaults=True, tie='unordered'):
        """defaults=True fills a missing recorder (the actor) and auth ('signed-as-actor'), so that the earlier checks keep
        their records; defaults=False keeps the entries as given (used to test D's field rule).
        tie='unordered' (v9, the Principal after turn 7; the default): acts at the same act time are unordered, and
        "ordered before" between them is U (F26 (b) at ties).
        tie='pos': equal act times are ordered by record position (F26 (a) on ties; the default of v1-v8).
        tie='id': by the act's identity, standing in for a content-derived identity (K2), so the order of acts needs
        no record position (F26 (c) with a tie rule; v7, check 18). D's citation rule always reads positions."""
        self.tie = tie
        if defaults:
            for x in entries:
                x.setdefault('recorder', x.get('actor'))
                x.setdefault('auth', 'signed-as-actor')
                x.setdefault('app', 'A0' if x.get('kind') != 'transfer' else KERNEL)   # v13: every act carries it
                if x.get('kind') == 'grant':        # v11: one owner application, 'A0' unless given
                    x.setdefault('owner', 'A0')
                    x.setdefault('single_use', False)   # v13 (the Principal after C20): uses owner-checked only if True
                elif x.get('kind') == 'transfer':
                    x.setdefault('last_use', None)
                elif x.get('kind') == 'consume':
                    x.setdefault('app', 'A0')
                elif x.get('kind') == 'transfer':
                    x.setdefault('app', KERNEL)
        self.e = entries
        self.by = {x['id']: x for x in entries}
        self.pos = {x['id']: i for i, x in enumerate(entries)}
        self.dom = cov_domain

    def acts(self):
        return [x['id'] for x in self.e]

    def key(self, i):
        return (self.by[i]['t'], self.pos[i] if self.tie == 'pos' else i)

    def before(self, e, x):
        """'e is ordered before x', three-valued: by act time; at equal act times by the tie rule (U if unordered)."""
        te, tx = self.by[e]['t'], self.by[x]['t']
        if te != tx:
            return T if te < tx else F
        if self.tie == 'unordered':
            return U
        return T if self.key(e) < self.key(x) else F


def in_D(rec):
    """Membership of D, decidable: unique ids, admitted kinds, required fields, integrity."""
    if len(rec.by) != len(rec.e):
        return False, 'identifiers not unique'
    for x in rec.e:
        if x.get('kind') not in FIELDS:
            return False, f"kind {x.get('kind')} not admitted ({x['id']})"
        if not FIELDS[x['kind']] <= set(x):
            return False, f"missing field in {x['id']}: {sorted(FIELDS[x['kind']] - set(x))}"
        if x['auth'] not in AUTH:
            return False, f"auth of {x['id']} is {x['auth']!r}, not one of {sorted(AUTH)}"
        if x['kind'] == 'consume' and x['grant'] not in x['cites']:
            return False, f"consumption {x['id']} does not cite the grant it consumes (v10, C19 T2-02)"
        for g_, u_ in list((x.get('prev_use') or {}).items()) + ([(x['grant'], x['last_use'])] if x['kind'] == 'transfer' else []):
            if u_ is None:
                continue                            # v13: a use-pointer names an earlier use of the same grant
            if u_ not in rec.pos or rec.pos[u_] >= rec.pos[x['id']] or g_ not in bases_of(rec.by[u_]) or rec.by[u_]['kind'] == 'transfer':
                return False, f"{x['id']} names {u_} as the last use of {g_}, which is not an earlier use of it (v13)"
        if not x.get('ok', True):
            return False, f"integrity check failed for {x['id']}"
        if x.get('removed'):
            rs = [rec.by.get(r) for r in x.get('removed_by', [])]
            if not rs or any(r is None or r['kind'] != 'remove' or r['target'] != x['id'] for r in rs):
                return False, f"tombstone {x['id']} cites no removal act of it (v9: a removal is an authorized, recorded act that the tombstone cites)"
        for g in x.get('cites', []):
            if g not in rec.pos:
                return False, f"{x['id']} cites {g}, which is not in the record (D: citations of earlier entries only; v5: a dangling citation is refused)"
            if rec.pos[g] >= rec.pos[x['id']]:
                return False, f"{x['id']} cites {g}, which is not an earlier entry (D: citations of earlier entries only)"
    return True, None


def effective(a, eid, upsilon):
    v = a[eid]
    if upsilon == 'notcount':
        return T if v == T else F
    return v


def basis(rec, a, x, g, upsilon):
    """The value of 'x is authorized through basis g' (one cited grant or root)."""
    i = x['id']
    if g not in rec.by:
        return F                                   # a citation of an entry not in the record is not a basis
    gr = rec.by[g]
    cov = covers(gr['scope'], x['scope'], rec.dom)
    if gr['kind'] == 'root':                       # a root as a basis: its actor holds it; no window, no endings
        return k_and(a[g], T if gr['actor'] == x['actor'] else F, cov)
    if gr['kind'] != 'grant':
        return F
    in_window = T if gr['start'] <= x['t'] < gr['end'] else F
    ended = [k_and(effective(a, e['id'], upsilon), rec.before(e['id'], i)) for e in rec.e
             if e['kind'] in ENDING_KINDS and e['grant'] == g and e['id'] != i]
    annulled_g = [effective(a, e['id'], upsilon) for e in rec.e
                  if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
    return k_and(a[g], T if gr['holder'] == x['actor'] else F, in_window,
                 k_not(k_or(F, *ended)), k_not(k_or(F, *annulled_g)), cov)


def phi(rec, a, upsilon):
    new = {}
    for x in rec.e:
        i = x['id']
        if x['kind'] == 'root':
            new[i] = T
            continue
        if not x['cites']:
            new[i] = F
            continue
        alts = [basis(rec, a, x, g, upsilon) for g in bases_of(x)]
        own_annul = [effective(a, e['id'], upsilon) for e in rec.e if e['kind'] == 'annul' and e['target'] == i]
        new[i] = k_and(k_or(*alts), k_not(k_or(F, *own_annul)))
    return new


def bottom(rec):
    return {x['id']: (T if x['kind'] == 'root' else U) for x in rec.e}


def lfp(rec, upsilon, start=None, limit=None):
    a = start or bottom(rec)
    n = len(rec.e)
    for k in range((limit or n + 2) + 1):
        b = phi(rec, a, upsilon)
        if b == a:
            return a, k
        a = b
    return None, None


def wfs(rec, count=None):
    """Well-founded model by the alternating fixpoint (Van Gelder 1989/1993), υ = 'counts as undetermined', read
    directly off the record. Positive dependence: authorization through a cited grant or root. Negative: endings and
    annulments. A coverage pair outside covers' domain is false in the lower pass and true in the upper one (the
    constant U as an atom u <- not u)."""
    passes = [0, 0]

    def least(J, hi):
        S = {x['id'] for x in rec.e if x['kind'] == 'root'}
        changed = True
        while changed:
            changed = False
            passes[1] += 1
            for x in rec.e:
                i = x['id']
                if i in S or x['kind'] == 'root' or not x['cites']:
                    continue
                if any(e['kind'] == 'annul' and e['target'] == i and e['id'] in J for e in rec.e):
                    continue
                ok = False
                for g in bases_of(x):
                    if g not in rec.by or g not in S:
                        continue
                    gr = rec.by[g]
                    c = covers(gr['scope'], x['scope'], rec.dom)
                    if c == F or (c == U and not hi):
                        continue
                    if gr['kind'] == 'root':
                        ok = ok or gr['actor'] == x['actor']
                        continue
                    if gr['kind'] != 'grant' or gr['holder'] != x['actor'] or not (gr['start'] <= x['t'] < gr['end']):
                        continue
                    if any(e['kind'] in ENDING_KINDS and e['grant'] == g and e['id'] != i and e['id'] in J
                           and (rec.before(e['id'], i) == T or (rec.before(e['id'], i) == U and not hi))
                           for e in rec.e):
                        continue                   # an ordering U blocks in the lower pass only (as covers U)
                    if any(e['kind'] == 'annul' and e['target'] == g and e['id'] != i and e['id'] in J for e in rec.e):
                        continue
                    ok = True
                if ok:
                    S.add(i)
                    changed = True
        return S
    K = set()
    while True:
        passes[0] += 1
        Up = least(K, hi=True)
        K2 = least(Up, hi=False)
        if K2 == K:
            break
        K = K2
    if count is not None:
        count.extend(passes)
    return {x['id']: (T if x['id'] in K else (U if x['id'] in Up else F)) for x in rec.e}


# ---- the second route: the record as a ground normal program --------------------------------------------------
def program(rec):
    """Rules (head, positive body, negative body). Roots are facts. For each non-root act x and cited basis g that
    passes the record constants (holder, window, covers not F): x <- g, [u_pair], not e (each prior ending of g),
    not e (each annulment of g), not e (each annulment of x). covers = U adds an atom u_pair with u_pair <- not u_pair."""
    rules = []
    upairs = set()
    for x in rec.e:
        i = x['id']
        if x['kind'] == 'root':
            rules.append((i, (), ()))
            continue
        own = tuple(e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == i)
        for g in bases_of(x):
            if g not in rec.by:
                continue
            gr = rec.by[g]
            c = covers(gr['scope'], x['scope'], rec.dom)
            if c == F:
                continue
            pos = [g]
            if c == U:
                u = ('u', gr['scope'], x['scope'])
                upairs.add(u)
                pos.append(u)
            if gr['kind'] == 'root':
                if gr['actor'] != x['actor']:
                    continue
                rules.append((i, tuple(pos), own))
                continue
            if gr['kind'] != 'grant' or gr['holder'] != x['actor'] or not (gr['start'] <= x['t'] < gr['end']):
                continue
            neg = [e['id'] for e in rec.e if e['kind'] in ENDING_KINDS and e['grant'] == g and e['id'] != i
                   and rec.before(e['id'], i) == T]
            neg += [e['id'] for e in rec.e if e['kind'] == 'annul' and e['target'] == g and e['id'] != i]
            # v9: an ending whose order is U contributes not(e and o), o <- not o; split as (not e) or (not o)
            und = [e['id'] for e in rec.e if e['kind'] in ENDING_KINDS and e['grant'] == g and e['id'] != i
                   and rec.before(e['id'], i) == U]
            for choice in product(*[(('e', e), ('o', e)) for e in und]):
                extra = []
                for kind_, e in choice:
                    if kind_ == 'e':
                        extra.append(e)
                    else:
                        o = ('o', e, i)
                        upairs.add(o)
                        extra.append(o)
                rules.append((i, tuple(pos), tuple(neg) + tuple(extra) + own))
    for u in upairs:
        rules.append((u, (), (u,)))
    atoms = set(rec.acts()) | upairs
    return atoms, rules


def fitting_kk(atoms, rules):
    """Fitting's three-valued immediate-consequence operator, iterated from all-U."""
    val = {p: U for p in atoms}
    while True:
        new = {}
        for p in atoms:
            bodies = [k_and(*[val[q] for q in pos], *[k_not(val[q]) for q in neg]) for h, pos, neg in rules if h == p]
            new[p] = k_or(F, *bodies)
        if new == val:
            return val
        val = new


def wf_unfounded(atoms, rules):
    """WF by Van Gelder-Ross-Schlipf 1991: iterate W(I) = (immediate consequences, greatest unfounded set)."""
    Tset, Fset = set(), set()

    def lit_false(pos, neg):
        return any(q in Fset for q in pos) or any(q in Tset for q in neg)
    while True:
        newT = {h for h, pos, neg in rules if all(q in Tset for q in pos) and all(q in Fset for q in neg)}
        A = set(atoms)
        changed = True
        while changed:                               # greatest unfounded set: remove atoms with a usable rule
            changed = False
            for p in list(A):
                if any(h == p and not lit_false(pos, neg) and not any(q in A for q in pos) for h, pos, neg in rules):
                    A.discard(p)
                    changed = True
        if newT == Tset and A == Fset:
            break
        Tset, Fset = newT, A
    return {p: (T if p in Tset else F if p in Fset else U) for p in atoms}


def current(rec, a, item, t, upsilon, qscope=None):
    """current-authority(c, x, t): adoptions of c with t_x <= t (and, for a queried scope, covers(scope, x));
    whole-or-nothing scope; an earlier adoption stays current unless revoked (C6 fixed)."""
    adopts = [x for x in rec.e if x['kind'] == 'adopt' and x['item'] == item and x['t'] <= t]
    if not adopts:
        return F                                  # C1 = constitute: no adoption on record
    alts = []
    for x in adopts:
        valid = T if x['vfrom'] <= t < x['vto'] else F
        scope_ok = T if qscope is None else covers(x['scope'], qscope, rec.dom)
        revs = [k_and(effective(a, y['id'], upsilon), rec.before(x['id'], y['id']), T if y['t'] <= t else F)
                for y in rec.e if y['kind'] == 'revoke' and y['item'] == item]
        intact = []
        for g in x['cites']:
            if g in rec.by and rec.by[g]['kind'] == 'grant':
                gr = rec.by[g]
                closes = T if gr['end'] <= t else F
                ends = [k_and(effective(a, e['id'], upsilon), rec.before(x['id'], e['id']), T if e['t'] <= t else F)
                        for e in rec.e if e['kind'] == 'endg' and e['grant'] == g]   # v10 (T1-04): not consumptions
                intact.append(k_not(k_or(closes, *ends)))
        alts.append(k_and(a[x['id']], valid, scope_ok, k_not(k_or(F, *revs)), *intact))
    return k_or(*alts)


def ce_star(rec, item, t, qscope=None):
    ok, why = in_D(rec)
    if not ok:
        return ('undefined', why)
    return current(rec, wfs(rec), item, t, 'U', qscope)


def dep_graph(rec):
    """The program reading's dependency edges of live rules: head -> (body atom, 'pos' | 'neg'). A covers pair with
    value U is an atom ('u', gs, xs) with the rule u <- not u (a negative self-loop)."""
    atoms, rules = program(rec)
    edges = {}
    for h, pos, neg in rules:
        for q in pos:
            edges.setdefault(h, set()).add((q, 'pos'))
        for q in neg:
            edges.setdefault(h, set()).add((q, 'neg'))
    return atoms, edges


def value(a, q):
    return U if isinstance(q, tuple) else a[q]


def body_value(a, pos, neg):
    return k_and(*[value(a, q) for q in pos], *[k_not(value(a, q)) for q in neg])


def u_reasons(rec, a, start):
    """Refusal reasons for U (§9.13.3, v5): from the U atoms the answer rests on, follow only the U literals of rule
    bodies that are themselves U (a body that is F supports nothing); name every strongly connected component of U atoms
    that has a cycle (on D each contains a negative edge, R2), and every undefined covers pair met."""
    atoms, rules = program(rec)
    rules_of = {}
    for h, pos, neg in rules:
        rules_of.setdefault(h, []).append((pos, neg))
    edges = {}
    seen, stack = set(), [q for q in start if value(a, q) == U]
    while stack:
        q = stack.pop()
        if q in seen:
            continue
        seen.add(q)
        for pos, neg in rules_of.get(q, ()):
            if body_value(a, pos, neg) != U:
                continue
            for r in pos:
                if value(a, r) == U:
                    edges.setdefault(q, set()).add((r, 'pos')); stack.append(r)
            for r in neg:
                if value(a, r) == U:
                    edges.setdefault(q, set()).add((r, 'neg')); stack.append(r)
    def reach(q):
        out, st = set(), [r for r, _ in edges.get(q, ())]
        while st:
            r = st.pop()
            if r not in out:
                out.add(r)
                st += [s for s, _ in edges.get(r, ())]
        return out
    R = {q: reach(q) for q in seen}
    sccs, done = [], set()
    for q in seen:
        if q in done or q not in R[q]:
            continue
        comp = {q} | {r for r in R[q] if q in R[r]}
        done |= comp
        sccs.append(comp)
    reasons = []
    for comp in sccs:
        if any(isinstance(q, tuple) for q in comp):
            (q0,) = [q for q in comp if isinstance(q, tuple)]
            if q0[0] == 'o':                       # v9: ties unordered
                reasons.append(f"same act time: the order of {q0[1]} and {q0[2]} is undetermined (ties unordered)")
                continue
            (_, gs, xs) = q0
            users = sorted(h for h in seen if not isinstance(h, tuple) and (('u', gs, xs), 'pos') in edges.get(h, ()))
            reasons.append(f"covers({gs}, {xs}) undefined (used by {', '.join(users)})")
            continue
        acts = sorted(comp)
        named = []
        for e in acts:
            x = rec.by[e]
            if x['kind'] != 'annul' or x['target'] not in comp:
                continue
            if x['target'] == e:
                named.append(f"self-annulling act {e}: it annuls itself")
                continue
            st, vis = [r for r, k in edges.get(e, ()) if k == 'pos' and r in comp], set()
            while st:
                r = st.pop()
                if r not in vis:
                    vis.add(r)
                    st += [s for s, k in edges.get(r, ()) if k == 'pos' and s in comp]
            if x['target'] in vis:
                how = 'directly' if x['target'] in x['cites'] else 'through the grants it acts under'
                named.append(f"self-cancelling act {e}: it annuls {x['target']}, a link of its own authorization chain ({how})")
        if named:
            reasons += named
        elif all(rec.by[e]['kind'] == 'annul' and rec.by[e]['target'] in comp for e in acts):
            reasons.append(f"mutual annulment of {' and '.join(acts)}" if len(acts) == 2
                           else f"annulment ring of {', '.join(acts)}")
        else:
            reasons.append(f"negative cycle (strongly connected component) through {', '.join(acts)}")
    return reasons or ['undetermined (no cycle or coverage reason found)']


def alternatives(rec, a, item, t, upsilon='U', qscope=None):
    """current-authority's alternatives, one per adoption y with t_y <= t: (value, the U atoms it rests on, whether the
    queried-scope covers pair is U, y)."""
    out = []
    for y in rec.e:
        if not (y['kind'] == 'adopt' and y['item'] == item and y['t'] <= t):
            continue
        valid = T if y['vfrom'] <= t < y['vto'] else F
        scope_ok = T if qscope is None else covers(y['scope'], qscope, rec.dom)
        parts, uatoms = [a[y['id']], valid, scope_ok], ([y['id']] if a[y['id']] == U else [])
        ordu = []                                   # v9: pairs whose order is U (same act time, ties unordered)
        for v in rec.e:
            if v['kind'] == 'revoke' and v['item'] == item and rec.before(y['id'], v['id']) != F and v['t'] <= t:
                parts.append(k_not(k_and(effective(a, v['id'], upsilon), rec.before(y['id'], v['id']))))
                if a[v['id']] == U:
                    uatoms.append(v['id'])
                if rec.before(y['id'], v['id']) == U and effective(a, v['id'], upsilon) != F:
                    ordu.append((y['id'], v['id']))
        for g in y['cites']:
            if g in rec.by and rec.by[g]['kind'] == 'grant':
                gr = rec.by[g]
                closes = T if gr['end'] <= t else F
                ends = [e for e in rec.e if e['kind'] == 'endg' and e['grant'] == g
                        and rec.before(y['id'], e['id']) != F and e['t'] <= t]
                parts.append(k_not(k_or(closes, *[k_and(effective(a, e['id'], upsilon), rec.before(y['id'], e['id']))
                                                  for e in ends])))
                uatoms += [e['id'] for e in ends if a[e['id']] == U]
                ordu += [(y['id'], e['id']) for e in ends if rec.before(y['id'], e['id']) == U
                         and effective(a, e['id'], upsilon) != F]
        out.append((k_and(*parts), uatoms, scope_ok == U, y['id'], ordu))
    return out


def ce_star_explained(rec, item, t, qscope=None):
    """CE* with its refusal reason for U (v5): the reason is computed only from alternatives whose value is U, and within
    them from U literals of U rule bodies (§9.13.3)."""
    v = ce_star(rec, item, t, qscope)
    if v != U:
        return v, None
    a = wfs(rec)
    start, extra = [], []
    for val, uatoms, cov_u, y, ordu in alternatives(rec, a, item, t, 'U', qscope):
        if val != U:
            continue
        start += uatoms
        if cov_u:
            extra.append(f"covers({rec.by[y]['scope']}, {qscope}) undefined for the queried scope (adoption {y})")
        for p_, q_ in ordu:
            extra.append(f"same act time: the order of {p_} and {q_} is undetermined (ties unordered)")
    rs = u_reasons(rec, a, start) if start else []
    rs = [r for r in rs if not r.startswith('undetermined')] + extra
    return U, rs or ['undetermined (no cycle or coverage reason found)']


def tombstone(rec, i):
    """A recorded removal by tombstone (the Principal, after turn 6): the entry keeps its identity, kind and the fields
    citations rely on (the Professor's reading: every field D requires), keeps its record position and its integrity
    result ('ok'), drops its content, and is marked removed. The record stays in D only if the entry passed the
    integrity check before (v8, A1-04/A2-02: tombstoning a failing entry does not restore D)."""
    x = rec.by[i]
    x.pop('content', None)
    x['removed'] = True


def remove(rec, i, rid, actor, cites, t, scope='s', **kw):
    """v9 (the Principal after turn 7): a removal is an authorized, recorded act that the tombstone cites. Appends the
    removal act rid (kind 'remove', target i) and tombstones i with removed_by = rid. The removal's own authorization is
    evaluated like any act; if it is not T, the marks say so."""
    guard = kw.pop('guard', True)
    r = dict({'id': rid, 'kind': 'remove', 'actor': actor, 'cites': list(cites), 'target': i, 'scope': scope, 't': t,
              'recorder': actor, 'auth': 'signed-as-actor'}, **kw)
    rec.e.append(r)
    rec.by[rid] = r
    rec.pos[rid] = len(rec.e) - 1
    if guard:                                       # v10 (C19, T1-05): content loss cannot be undone, so prevent
        v = wfs(rec)[rid]
        if v != T:
            rec.e.pop()
            del rec.by[rid], rec.pos[rid]
            return False, f"removal {rid} refused: its authorization is {v}, not T (the content would be lost)"
    tombstone(rec, i)
    rec.by[i]['removed_by'] = sorted(set(rec.by[i].get('removed_by', [])) | {rid})
    return True, None


def support(rec, item, t, start=None):
    """The entries an answer for (item, t) may rest on, for its marks (v8, turn-7 settlement, A1-01/A2-01): the closure,
    from the adoptions and revocations of the item with act time <= t, under 'reads': an entry reads the entries it
    cites, the annulments that target it and the endings of it (a grant). The closure does not depend on which rules
    are live, so it contains every entry that the evaluation of a reached atom reads. A conservative over-approximation:
    it may contain entries the value does not need (§9.13.3, marks; check 19 tests that nothing outside it matters)."""
    targets = {}
    for e in rec.e:
        if e['kind'] == 'annul':
            targets.setdefault(e['target'], set()).add(e['id'])
        elif e['kind'] in ENDING_KINDS or e['kind'] == 'transfer':   # v11: a grant's transfers, for the owner mark
            targets.setdefault(e['grant'], set()).add(e['id'])
        elif e['kind'] == 'remove':
            targets.setdefault(e['target'], set()).add(e['id'])   # the removal act a tombstone rests on
    if start is None:
        start = {y['id'] for y in rec.e if y['kind'] in ('adopt', 'revoke') and y['item'] == item and y['t'] <= t}
    seen, st = set(), list(start)
    while st:
        q = st.pop()
        if q in seen or q not in rec.by:
            continue
        seen.add(q)
        st += list(rec.by[q].get('cites', [])) + list(targets.get(q, ()))
        st += [u for u in (rec.by[q].get('prev_use') or {}).values() if u] + ([rec.by[q]['last_use']] if rec.by[q].get('last_use') else [])
    return seen


def marks_act(rec, i):
    """v9: marks for one act's authorization, by the same closure started from the act."""
    return marks(rec, None, None, start={i})


def marks(rec, item, t, start=None):
    """Q1's marks (the Principal, after turn 6): the answer is marked, not refused, when it rests on transcribed or
    unsigned entries, or on removed content."""
    s = support(rec, item, t, start)
    out = []
    tr = sorted(i for i in s if rec.by[i]['auth'] == 'signed-as-other')
    un = sorted(i for i in s if rec.by[i]['auth'] == 'unsigned')
    rm = sorted(i for i in s if rec.by[i].get('removed'))
    if tr:
        out.append(f"rests on transcribed entries {', '.join(tr)}")
    if un:
        out.append(f"rests on unsigned entries {', '.join(un)}")
    if rm:
        out.append(f"rests on removed content {', '.join(rm)}")
        a = wfs(rec)
        bad = sorted(f"{i} ({', '.join(f'{r}={a.get(r)}' for r in rec.by[i].get('removed_by', []))})" for i in rm
                     if not any(a.get(r) == T for r in rec.by[i].get('removed_by', [])))
        if bad:                                     # v10 (T1-06): marked only if no removal act of it is T
            out.append(f"removal not, or no longer, authorized: {', '.join(bad)}")
    od = sorted(i for i in s if rec.by[i]['auth'] == 'signed-as-other' and rec.by[i].get('recorder') == rec.by[i].get('actor'))
    if od:                                          # v10 (T1-15, T2-14): a mark, not a refusal (decision 2)
        out.append(f"inconsistent recording status (signed-as-other, recorder = actor) {', '.join(od)}")
    cs = [(i, g) for i in sorted(s) for g in single_use_grants(rec, rec.by[i])]
    if cs:                                          # v11: detect a use that did not pass the kernel's guard
        nb = []                                     # v13: uses of single-use grants only (the Principal after C20)
        for i, g in cs:                             # v12 (C20, W-5): the owner as the guard saw it, at the use's cut
            pre = Record([dict(x) for x in rec.e[:rec.pos[i]]], rec.dom, tie=rec.tie)
            ow, _ = owner_at(pre, g, len(pre.e)) if g in pre.by else (None, None)
            if ow is None or rec.by[i]['app'] != ow:
                nb.append(f"{i} (by {rec.by[i]['app']}, owner {ow})")
        if nb:
            out.append(f"use not recorded by its owner: {', '.join(nb)}")
        multi = sorted({g for _, g in cs if len(uses_of(rec, g, len(rec.e))) > 1})
        if multi:                                   # v14 (C21, W-4): a second use present without the guard
            out.append(f"second use of a single-use grant: {', '.join(f'{g} ({len(uses_of(rec, g, len(rec.e)))} uses)' for g in multi)}")
    return out


def act_explained(rec, i):
    """authorized(i) with its refusal reason for U (the Q3-style question about one recorded act)."""
    ok, why = in_D(rec)
    if not ok:
        return ('undefined', why)
    a = wfs(rec)
    return (a[i], None) if a[i] != U else (U, u_reasons(rec, a, [i]))


def citation_acyclic(rec):
    """True iff the citation graph (x -> each cited entry in the record) has no cycle (a tight record)."""
    g = {x['id']: [c for c in x.get('cites', []) if c in rec.by] for x in rec.e}
    state = {}
    def dfs(v):
        state[v] = 1
        for w in g[v]:
            if state.get(w) == 1 or (w not in state and not dfs(w)):
                return False
        state[v] = 2
        return True
    return all(dfs(v) for v in g if v not in state)


def random_record(rng, n_acts=6, forward=False, targets_any=False):
    """forward=False: every citation and target is an earlier entry. forward=True: any other entry (forward citation).
    targets_any=True (with forward=False): citations earlier only, as D requires; annulment targets and ended grants
    may be any other entry (later ones included)."""
    scopes = ['s', 's/a', 's/b']
    dom = {(g, x) for g in scopes for x in scopes}
    if rng.random() < 0.3:
        dom.discard((rng.choice(scopes), rng.choice(scopes)))   # coverage sometimes undefined
    kinds = [rng.choice(['grant', 'grant', 'adopt', 'adopt', 'revoke', 'annul', 'endg', 'consume']) for _ in range(n_acts)]
    ids = ['r'] + [f'{k[0]}{j}' for j, k in enumerate(kinds)]
    ents = [{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}]
    actors = ['P', 'A', 'B']
    for j, kind in enumerate(kinds):
        i = ids[j + 1]
        pool = [y for y in ids if y != i] if forward else ids[:j + 1]
        cites = rng.sample(pool, k=min(len(pool), rng.choice([1, 1, 2])))
        e = {'id': i, 'kind': kind, 'actor': rng.choice(actors), 'cites': cites, 'scope': rng.choice(scopes),
             't': rng.randint(0, 5)}
        if kind == 'grant':
            e.update(holder=rng.choice(actors), start=rng.randint(0, 3), end=rng.randint(3, 7))
        elif kind == 'adopt':
            e.update(item='c', vfrom=0, vto=rng.randint(3, 8))
        elif kind == 'revoke':
            e.update(item='c')
        elif kind == 'annul':
            e.update(target=rng.choice([y for y in ids if y != i] if targets_any else pool))
        elif kind == 'endg':
            gs = [y for y in (ids if targets_any else pool) if y.startswith('g') and y != i] or ['r']
            e.update(grant=rng.choice(gs))
        elif kind == 'consume':                    # v9: the holder uses up a grant it cites
            gs = [y for y in cites if y.startswith('g')] or [y for y in pool if y.startswith('g')] or ['r']
            g = rng.choice(gs)
            if g not in cites:
                e['cites'] = cites + [g] if g in pool else cites
            e.update(grant=g)
        ents.append(e)
    return Record(ents, dom)


def loop_record(rng, n_acts=5):
    """Loop-rich records: one scope, wide windows, two actors; grants cite other grants or the root, so positive and
    negative cycles are frequent."""
    kinds = [rng.choice(['grant', 'grant', 'grant', 'adopt', 'annul', 'endg']) for _ in range(n_acts)]
    ids = ['r'] + [f'{k[0]}{j}' for j, k in enumerate(kinds)]
    ents = [{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}]
    for j, kind in enumerate(kinds):
        i = ids[j + 1]
        pool = [y for y in ids if y != i]
        bases = [y for y in pool if y == 'r' or kinds[ids.index(y) - 1] == 'grant'] or ['r']
        e = {'id': i, 'kind': kind, 'actor': rng.choice(['A', 'B']), 'cites': [rng.choice(bases)], 'scope': 's',
             't': rng.randint(1, 3)}
        if kind == 'grant':
            e.update(holder=rng.choice(['A', 'B']), start=0, end=9)
        elif kind == 'adopt':
            e.update(item='c', vfrom=0, vto=9)
        elif kind == 'annul':
            e.update(target=rng.choice(pool))
        elif kind == 'endg':
            e.update(grant=rng.choice([y for y in bases if y != 'r'] or ['r']))
        ents.append(e)
    return Record(ents, {('s', 's')})


def linear_extension(rng, entries):
    """A random order of the entries in which every citation points to an earlier entry (a topological sort of the
    citation graph): one possible merged order of several applications' records (v7, check 18)."""
    left, out, have = list(entries), [], set()
    while left:
        ready = [e for e in left if all(g in have for g in e.get('cites', []))]
        e = rng.choice(ready)
        left.remove(e)
        out.append(e)
        have.add(e['id'])
    return out


READ_FIELDS = {k: v - {'recorder', 'auth'} for k, v in FIELDS.items()}
AUTH_RANK = {'unsigned': 0, 'signed-as-other': 1, 'signed-as-actor': 2}


def merge(records, rng=None):
    """v8 (turn-7 settlement, D1-02/D2-02): the union of several applications' records. The identity of an act is
    derived from the fields CE* reads (kind and READ_FIELDS); recorder and auth are not part of it. Copies with one
    identity are joined: the fields CE* reads must agree, or the merge is refused (conflicting copies); a tombstone
    wins over a full copy (removed, no content); the auth status is the weakest of the copies (unsigned < other <
    actor), so the marks never get weaker by merging; the recorder is the set of recorders. Returns (entries, None) in
    a linear extension of the citation order (random if rng is given, else the first record's order then the rest),
    or (None, reason)."""
    by, order = {}, []
    for R in records:
        for x in R:
            i = x['id']
            if i not in by:
                by[i] = dict(x)
                by[i]['recorder'] = frozenset([x.get('recorder')]) if not isinstance(x.get('recorder'), frozenset) else x['recorder']
                order.append(i)
                continue
            y = by[i]
            if any(x.get(f) != y.get(f) for f in READ_FIELDS[x['kind']] | {'kind'}):
                return None, f'conflicting copies of {i}'
            if x.get('removed') or y.get('removed'):
                y['removed_by'] = sorted(set(y.get('removed_by') or []) | set(x.get('removed_by') or []))   # v10 (T1-06)
                y['removed'] = True
                y.pop('content', None)
            y['ok'] = y.get('ok', True) and x.get('ok', True)
            if AUTH_RANK[x['auth']] < AUTH_RANK[y['auth']]:
                y['auth'] = x['auth']
            y['recorder'] = y['recorder'] | (x['recorder'] if isinstance(x.get('recorder'), frozenset) else frozenset([x.get('recorder')]))
    ents = [by[i] for i in order]
    for e in ents:
        e['recorder'] = ','.join(sorted(str(r) for r in e['recorder']))
    return (linear_extension(rng, ents) if rng else ents), None


def owner_at(rec, g, upto, a=None):
    """v11 (F22 = (C + O)): the owner application of grant g named by the contract as recorded in the first `upto`
    entries: the owner fixed at issuance, replaced by each transfer of g, taken in record order (the kernel is the only
    writer of the contract, so its transfers are serialized by its record; S0137 L172: authority moves at the writer
    switch). v12 (C20, W-1): the LAST transfer of g in record order that is not F decides: T gives its new owner, U
    leaves the owner undetermined (None, with the reason); F counts as no transfer; with none, the owner at issuance.
    So a later authorized transfer restores a determinate owner after a U one (v11 froze the grant at the first U).
    The values read are CE*'s (v12, W-4): the owner check evaluates CE* for the grant's transfers. Returns (owner, reason)."""
    if g not in rec.by or rec.by[g]['kind'] != 'grant':
        return None, f'{g} is not a grant on record'
    a = a or wfs(rec)
    for x in reversed(rec.e[:upto]):
        if x['kind'] == 'transfer' and x['grant'] == g and a[x['id']] != F:
            if a[x['id']] == U:
                return None, f'the transfer {x["id"]} of {g} is U, so its owner is undetermined'
            return x['new_owner'], None
    return rec.by[g]['owner'], None


def single_use_grants(rec, x):
    """v13: the single-use grants an act is under (its bases that are grants marked single_use). A transfer is not a use."""
    if x.get('kind') == 'transfer':
        return []
    return [g for g in bases_of(x) if g in rec.by and rec.by[g]['kind'] == 'grant' and rec.by[g].get('single_use')]


def uses_of(rec, g, upto):
    """v13: the uses of grant g among the first `upto` entries: every act under g (the Principal after C20)."""
    return [x['id'] for x in rec.e[:upto] if x['kind'] != 'transfer' and g in bases_of(x)]


def append_guard(rec, e):
    """v9 (the Principal after turn 7: prevent single use): the kernel refuses to append a consumption of a grant that
    the record already shows consumed (any grant; within one record).
    v11 (the Principal, F22 = (C + O)): "The kernel refuses a use of a grant not recorded by its owner." The owner is
    the one the contract names when the use is appended (owner_at); an undetermined owner is refused. The guard and the
    append are one step (§9.15 K4; S0137 L52).
    v13 (the Principal after C20): (1) "every act under a grant marked single-use is a use and is owner-checked; grants
    that are not single-use need no owner check". So for each single-use grant the act is under: owner check; at most
    one use not F (single use prevented); and the use names the previous use of the grant (prev_use, a chain), filled
    if absent and refused if wrong. (2) "Ownership transfer: only the grant's issuer, or an authority above the issuer's
    scope (to replace an absent issuer); never the holder. A transfer must carry the record of earlier uses to the new
    owner": the transfer names the last use of the grant (last_use), checked against the uses the kernel has accepted;
    with the chain, the new owner's record reaches every earlier use by citation closure. Returns (ok, reason)."""
    e = dict(e)
    Record([e], rec.dom)                            # fills the defaults of this one entry
    tmp = Record([dict(x) for x in rec.e] + [dict(e)], rec.dom, tie=rec.tie)
    a_new = wfs(tmp)
    a = wfs(rec)
    su = single_use_grants(rec, e)
    if su:
        prev = dict(e.get('prev_use') or {})
        for g in su:
            owner, why = owner_at(rec, g, len(rec.e), a)
            if owner is None:
                return False, f"use of {g} refused: {why}"
            if e['app'] != owner:
                return False, f"use of {g} refused: recorded by {e['app']}, not by its owner {owner}"
            earlier = uses_of(rec, g, len(rec.e))
            if earlier:                             # v14 (C21, W-3): every recorded use counts, whatever its value now:
                return False, f"{g} is single-use and already used by {', '.join(earlier)} (prevented)"   # no reopening by annulment
            if a_new[e['id']] != T:                 # v14: so a use is accepted only if it is T at append
                return False, f"use of {g} refused: its authorization is {a_new[e['id']]}, not T"
            last = earlier[-1] if earlier else None
            if g in prev and prev[g] != last:
                return False, f"use of {g} refused: it names {prev[g]} as the previous use, the last is {last}"
            prev[g] = last
        e['prev_use'] = prev
    elif e['kind'] == 'consume':                   # v9-v10, any grant, within one record; no owner check (v13)
        live = [x['id'] for x in rec.e if x['kind'] == 'consume' and x['grant'] == e['grant'] and a[x['id']] != F]
        if live:
            return False, f"{e['grant']} is already consumed by {', '.join(live)} (single use, prevented)"
    if e['kind'] == 'transfer':
        g = e['grant']
        if g not in rec.by or rec.by[g]['kind'] != 'grant':
            return False, f"transfer {e['id']} refused: {g} is not a grant on record"
        gr = rec.by[g]
        if e['app'] != KERNEL:
            return False, f"transfer {e['id']} refused: recorded by {e['app']}, not at the kernel, which holds the contract"
        if e['scope'] != gr['scope']:               # v12 (C20, W-2)
            return False, f"transfer {e['id']} refused: its scope {e['scope']} is not the scope {gr['scope']} of {g}"
        if a_new[e['id']] != T:
            return False, f"transfer {e['id']} refused: its authorization is {a_new[e['id']]}, not T"
        if e['actor'] == gr['holder']:              # v13: never the holder
            return False, f"transfer {e['id']} refused: {e['actor']} is the holder of {g}"
        if e['actor'] != gr['actor']:               # v13: else an authority above the issuer's scope
            # v14 (C21, W-2): the issuer's scope is the scope of the basis the issuance rests on (a T basis it cites),
            # not the grant's scope; "above" = a T basis of the transfer that strictly covers it, or the root when the
            # issuer acted under a grant. A grantee of the issuer cannot strictly cover the issuer's scope.
            iss = [tmp.by[c] for c in gr['cites'] if c in tmp.by and basis(tmp, a_new, gr, c, 'U') == T]
            above = []
            for b in e['cites']:
                if b not in tmp.by or basis(tmp, a_new, tmp.by[e['id']], b, 'U') != T:
                    continue
                bb = tmp.by[b]
                for ib in iss:
                    if ib['kind'] == 'root':
                        continue                    # nothing is above the root
                    if bb['kind'] == 'root' or (covers(bb['scope'], ib['scope'], rec.dom) == T and bb['scope'] != ib['scope']):
                        above.append(b)
            if not above:
                return False, f"transfer {e['id']} refused: {e['actor']} is not the issuer {gr['actor']} and holds no authority above the issuer's scope {', '.join(ib['scope'] for ib in iss) or 'none'}"
        earlier = uses_of(rec, g, len(rec.e))
        last = earlier[-1] if earlier else None
        if e['last_use'] != last:
            return False, f"transfer {e['id']} refused: it carries {e['last_use']} as the last use of {g}, the last is {last}"
    return True, None


def complete(rec, e):
    """v13: the entry as the guard would append it: defaults filled, prev_use filled for a use, last_use for a transfer
    when not given (the recording party supplies them; the guard checks given ones)."""
    e = dict(e)
    given_last = 'last_use' in e
    Record([e], rec.dom)
    su = single_use_grants(rec, e)
    if su:
        prev = dict(e.get('prev_use') or {})
        for g in su:
            us = uses_of(rec, g, len(rec.e))
            prev.setdefault(g, us[-1] if us else None)
        e['prev_use'] = prev
    if e['kind'] == 'transfer' and not given_last and e['grant'] in rec.by:
        us = uses_of(rec, e['grant'], len(rec.e))
        e['last_use'] = us[-1] if us else None
    return e


def append(rec, e):
    """v11: the guarded append, one step: the entry is added only if append_guard accepts it. Returns (ok, reason)."""
    e = complete(rec, e)
    ok, why = append_guard(rec, e)
    if ok:
        rec.e.append(e)
        rec.by[e['id']] = e
        rec.pos[e['id']] = len(rec.e) - 1
    return ok, why


def ce_star_cut(rec, s, item, t, qscope=None):
    """v10 (C19, T1-01): CE* at the cut of the first s entries of a record. Membership of D is a property of the
    record, checked on the whole record; the cut is evaluated on its entries. So a tombstone whose removal act lies
    after the cut does not put the cut outside D."""
    ok, why = in_D(rec)
    if not ok:
        return ('undefined', why)
    pre = Record([dict(x) for x in rec.e[:s]], rec.dom, tie=rec.tie)
    return current(pre, wfs(pre), item, t, 'U', qscope)


def all_fixed_points(rec, upsilon):
    ids = [i for i in rec.acts() if rec.by[i]['kind'] != 'root']
    out = []
    for vals in product([T, F, U], repeat=len(ids)):
        a = {i: T for i in rec.acts() if rec.by[i]['kind'] == 'root'}
        a.update(zip(ids, vals))
        if phi(rec, a, upsilon) == a:
            out.append(a)
    return out


def G(i, actor, cites, holder, t=1, start=0, end=9, scope='s'):
    return {'id': i, 'kind': 'grant', 'actor': actor, 'cites': cites, 'holder': holder, 'scope': scope, 't': t,
            'start': start, 'end': end}


ROOT = {'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}

if __name__ == '__main__':
    rng = random.Random(20261020)
    gens = [('backward', False), ('forward', True)]
    # (1) monotonicity of Phi under υ='U'
    for name, fw in gens:
        bad = 0
        for _ in range(400):
            rec = random_record(rng, forward=fw)
            ids = rec.acts()
            b = {i: (T if rec.by[i]['kind'] == 'root' else rng.choice([T, F, U])) for i in ids}
            a = {i: (b[i] if rng.random() < 0.5 or rec.by[i]['kind'] == 'root' else U) for i in ids}
            bad += not leq(phi(rec, a, 'U'), phi(rec, b, 'U'))
        print(f'(1) Phi monotone under υ=U, {name} citations: violations {bad} of 400')
        assert bad == 0
    # (2) KK: iteration from the bottom stops within n+1 rounds; a chain attains the bound
    worst = 0
    for name, fw in gens:
        for _ in range(2000):
            rec = random_record(rng, n_acts=rng.randint(1, 9), forward=fw)
            a, k = lfp(rec, 'U')
            assert a is not None
            worst = max(worst, k / len(rec.e))       # n non-root acts and one root: n + 1 = len(rec.e) (v8, A1-18)
    n = 8
    chain = Record([ROOT] + [G(f'g{j}', 'P', [f'g{j + 1}'] if j < n - 1 else ['r'], 'P') for j in range(n)], {('s', 's')})
    _, kc = lfp(chain, 'U')
    chain_d = Record([ROOT] + [G(f'g{j}', 'P', [f'g{j - 1}'] if j > 0 else ['r'], 'P') for j in range(n)], {('s', 's')})
    _, kd = lfp(chain_d, 'U')
    print('(2) KK reached on 4000 random records; worst rounds/(n+1) =', round(worst, 2),
          f'; a chain of {n} grants recorded leaf first (outside D): {kc} rounds; recorded root first (in D:',
          f'{in_D(chain_d)[0]}): {kd} rounds; n+1 = {len(chain.e)}')
    assert in_D(chain_d)[0] and kd == n
    assert worst <= 1.0 and kc <= len(chain.e)
    # (2b) WF: alternating fixpoint, outer rounds <= n+1, inner passes <= 2(n+1)^2
    wo = wi = 0
    for _ in range(2000):
        rec = random_record(rng, n_acts=rng.randint(1, 9), forward=True)
        c = []
        wfs(rec, c)
        m = len(rec.e)                             # n + 1, n the non-root acts (v8, A1-18)
        wo, wi = max(wo, c[0] / m), max(wi, c[1] / (2 * m * m))
    print('(2b) WF on 2000 forward records: worst outer rounds/(n+1) =', round(wo, 2),
          '; worst inner passes/(2(n+1)^2) =', round(wi, 3))
    assert wo <= 1.0 and wi <= 1.0
    # (3) υ='notcount': mutual annulment oscillates; it has two total fixed points and none is least
    mut = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'P', ['r'], 'B', t=0),
                  {'id': 'x', 'kind': 'annul', 'actor': 'A', 'cites': ['g1'], 'target': 'y', 'scope': 's', 't': 1},
                  {'id': 'y', 'kind': 'annul', 'actor': 'B', 'cites': ['g2'], 'target': 'x', 'scope': 's', 't': 1}],
                 {('s', 's')})
    a_u, _ = lfp(mut, 'U')
    a_n, _ = lfp(mut, 'notcount', limit=50)
    fpn = all_fixed_points(mut, 'notcount')
    least = [p for p in fpn if all(leq(p, q) for q in fpn)]
    print('(3) mutual annulment: υ=U KK x, y =', a_u['x'], a_u['y'], '; υ=notcount: iteration reaches a fixed point:',
          a_n is not None, '; fixed points (x, y):', sorted((p['x'], p['y']) for p in fpn), '; least:', len(least))
    assert a_u['x'] == U and a_u['y'] == U and a_n is None and len(fpn) == 2 and not least
    fpu = sorted((p['x'], p['y']) for p in all_fixed_points(mut, 'U'))
    print('(3b) mutual annulment under υ=U: fixed points (x, y):', fpu)
    assert fpu == [('F', 'T'), ('T', 'F'), ('U', 'U')]
    # (4) CE* (read off WF) answers T, F and U on some records: constant evaluators fail
    seen = set()
    for _ in range(3000):
        rec = random_record(rng, targets_any=rng.random() < 0.5)          # records in D
        for t in range(0, 7):
            seen.add(ce_star(rec, 'c', t))
    print('(4) answers of CE*(c, t) on WF, 3000 random records in D:', sorted(seen))
    assert {T, F, U} <= seen
    # (5) exhaustive: every fixed point lies above KK; WF's extra values are not forced
    for name, gen in [('random forward', lambda: random_record(rng, n_acts=rng.randint(2, 6), forward=True)),
                      ('loop-rich', lambda: loop_record(rng, n_acts=rng.randint(2, 6)))]:
        recs = fps = multi = not_kk = not_above_wf = wf_not_fp = both = 0
        distinct = set()
        for _ in range(300):
            rec = gen()
            kk, _ = lfp(rec, 'U')
            wf = wfs(rec)
            fp = all_fixed_points(rec, 'U')
            recs += 1
            fps += len(fp)
            multi += len(fp) > 1
            wf_not_fp += wf not in fp
            for p in fp:
                assert leq(kk, p)
                not_kk += p != kk
                not_above_wf += not leq(wf, p)
                both += p != kk and not leq(wf, p)
        print(f'(5) exhaustive over 3^k assignments, {recs} {name} records: {fps} fixed points; records with more '
              f'than one: {multi}; fixed points other than KK: {not_kk} (all above KK); fixed points not above WF: '
              f'{not_above_wf}; of these, other than KK: {both}; records where WF is not a fixed point of Phi: '
              f'{wf_not_fp}')
    assert fps > recs and not_kk > 0 and not_above_wf > 0
    # (6) the two routes agree: KK(Phi) = Fitting KK of the program; alternating WF = unfounded-set WF;
    #     with backward citations only WF = KK
    for name, fw in gens + [('loop-rich', None), ('backward citations, any targets (D)', 'D')]:
        diff = 0
        for _ in range(3000):
            rec = (loop_record(rng, n_acts=rng.randint(1, 9)) if fw is None else
                   random_record(rng, n_acts=rng.randint(1, 9), targets_any=True) if fw == 'D' else
                   random_record(rng, n_acts=rng.randint(1, 9), forward=fw))
            if fw == 'D':
                assert in_D(rec)[0]
            kk, _ = lfp(rec, 'U')
            wf = wfs(rec)
            atoms, rules = program(rec)
            fk, fw2 = fitting_kk(atoms, rules), wf_unfounded(atoms, rules)
            assert all(fk[i] == kk[i] for i in rec.acts()), 'KK routes differ'
            assert all(fw2[i] == wf[i] for i in rec.acts()), 'WF routes differ'
            assert leq(kk, wf)
            diff += kk != wf
        print(f'(6) {name}, 3000 records: KK routes agree, WF routes agree, KK <= WF; WF decides more on', diff)
        assert diff == 0 or fw not in (False, 'D')      # v9: forward and loop-rich counts are reported, not asserted
    # (7) circular self-authorization: KK U, WF F; the all-T assignment is a total fixed point (F is grounded, not forced)
    circ = Record([ROOT, G('g1', 'A', ['g2'], 'B'), G('g2', 'B', ['g1'], 'A'),
                   {'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g2'], 'item': 'c', 'scope': 's', 't': 2,
                    'vfrom': 0, 'vto': 9}], {('s', 's')})
    kk, _ = lfp(circ, 'U')
    wf = wfs(circ)
    allT = {i: T for i in circ.acts()}
    print('(7) circular grants: KK g1, g2, x =', kk['g1'], kk['g2'], kk['x'], '; WF =', wf['g1'], wf['g2'], wf['x'],
          '; all-T is a fixed point:', phi(circ, allT, 'U') == allT)
    assert kk['x'] == U and wf['x'] == F and phi(circ, allT, 'U') == allT
    # (8) a grounded T that is not forced: x adopted under the root, annulled by an act under a self-supporting loop
    cm2 = Record([ROOT, {'id': 'x', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1,
                         'vfrom': 0, 'vto': 9},
                  G('g1', 'A', ['g2'], 'B'), G('g2', 'B', ['g1'], 'A'),
                  {'id': 'n', 'kind': 'annul', 'actor': 'A', 'cites': ['g2'], 'target': 'x', 'scope': 's', 't': 2}],
                 {('s', 's')})
    kk, _ = lfp(cm2, 'U')
    wf = wfs(cm2)
    alt = {'r': T, 'x': F, 'g1': T, 'g2': T, 'n': T}
    print('(8) loop annulling a rooted adoption: KK x =', kk['x'], '; WF x =', wf['x'], '; current-authority(c, 3) off WF =',
          current(cm2, wf, 'c', 3, 'U'), '; {loop T, x F} is a fixed point:', phi(cm2, alt, 'U') == alt,
          '; CE* on this record (outside D):', ce_star(cm2, 'c', 3)[0])
    assert kk['x'] == U and wf['x'] == T and phi(cm2, alt, 'U') == alt
    # (9) mutual annulment stays undetermined under WF
    wm = wfs(mut)
    print('(9) mutual annulment, WF: x, y =', wm['x'], wm['y'])
    assert wm['x'] == U and wm['y'] == U
    mx = act_explained(mut, 'x')
    print('(9b) authorized(x) =', mx[0], 'with reason', mx[1])
    assert mx[0] == U and any('mutual annulment of x and y' in r for r in mx[1])
    # (10) a same-time ending: counts only if recorded before the act (key (t, position))
    def tie(first, rule='pos'):
        x = {'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g'], 'item': 'c', 'scope': 's', 't': 3, 'vfrom': 0, 'vto': 9}
        e = {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g', 'scope': 's', 't': 3}
        return Record([ROOT, G('g', 'P', ['r'], 'A', t=0)] + ([e, x] if first else [x, e]), {('s', 's')}, tie=rule)
    after, before = wfs(tie(False))['x'], wfs(tie(True))['x']
    print('(10) ties by position (v1-v8): ending with the act time of x: recorded after x -> x', after,
          '; recorded before x -> x', before)
    assert after == T and before == F
    ua, ub = wfs(tie(False, 'unordered'))['x'], wfs(tie(True, 'unordered'))['x']
    why = act_explained(tie(True, 'unordered'), 'x')
    print('(10b) v9, ties unordered (the Principal after turn 7): x =', ua, ub, 'in both record orders, with reason', why[1])
    assert ua == ub == U and any('same act time' in r for r in why[1])
    # (11) a holder annulling the grant it acts under: a negative cycle, U in KK and WF (defect 6 stays open)
    selfa = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0),
                    {'id': 'e', 'kind': 'annul', 'actor': 'A', 'cites': ['g1'], 'target': 'g1', 'scope': 's', 't': 1}],
                   {('s', 's')})
    kk, _ = lfp(selfa, 'U')
    wf = wfs(selfa)
    print('(11) self-annulment cycle: KK g1, e =', kk['g1'], kk['e'], '; WF =', wf['g1'], wf['e'])
    assert kk['e'] == U and wf['e'] == U
    selfx = Record(selfa.e + [{'id': 'x', 'kind': 'adopt', 'actor': 'A', 'cites': ['g1'], 'item': 'c', 'scope': 's',
                               't': 2, 'vfrom': 0, 'vto': 9}], {('s', 's')})
    ans = ce_star_explained(selfx, 'c', 3)
    print('(11b) an adoption under that grant: CE*(c, 3) =', ans[0], 'with reason', ans[1])
    assert ans[0] == U and any('self-cancelling' in r for r in ans[1])
    # (12) a parent grant's ending does not reach the child grant's acts (defect 2, open half)
    par = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'A', ['g1'], 'B', t=1),
                  {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g1', 'scope': 's', 't': 2},
                  {'id': 'x', 'kind': 'adopt', 'actor': 'B', 'cites': ['g2'], 'item': 'c', 'scope': 's', 't': 4,
                   'vfrom': 0, 'vto': 9}], {('s', 's')})
    print('(12) parent g1 ended at 2, act under child g2 at 4: x =', wfs(par)['x'], '; CE*(c, 5) =', ce_star(par, 'c', 5))
    assert wfs(par)['x'] == T
    # (13) outside D: CE* refuses with the reason (fail-closed against a dropped or failing negative entry)
    bad_int = Record([ROOT, {'id': 'x', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1,
                             'vfrom': 0, 'vto': 9},
                      {'id': 'n', 'kind': 'annul', 'actor': 'P', 'cites': ['r'], 'target': 'x', 'scope': 's', 't': 2,
                       'ok': False}], {('s', 's')})
    nohold = Record([ROOT, {'id': 'g', 'kind': 'grant', 'actor': 'P', 'cites': ['r'], 'scope': 's', 't': 0,
                            'start': 0, 'end': 9}], {('s', 's')})
    sup = Record([ROOT, {'id': 'y', 'kind': 'supersede', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1}],
                 {('s', 's')})
    dropped = Record(bad_int.e[:2], {('s', 's')})
    fwd = Record([ROOT, G('g1', 'P', ['g2'], 'A'), G('g2', 'P', ['r'], 'P')], {('s', 's')})
    dang = Record([ROOT, {'id': 'x', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'c', 'scope': 's', 't': 1,
                          'vfrom': 0, 'vto': 9},
                   {'id': 'n', 'kind': 'annul', 'actor': 'A', 'cites': ['g'], 'target': 'x', 'scope': 's', 't': 2}],
                  {('s', 's')})
    outs = [ce_star(r, 'c', 3) for r in (bad_int, nohold, sup, fwd, dang)]
    print('(13) outside D:', [o[1] for o in outs], '; the same record with the failing annulment dropped would give',
          ce_star(dropped, 'c', 3))
    assert all(o[0] == 'undefined' for o in outs) and ce_star(dropped, 'c', 3) == T
    # (14) the lemma's real hypothesis: no citation cycle (tight) suffices; earlier-only citations are one case
    tight_out = tight = nontight = nt_diff = 0
    for _ in range(6000):
        rec = (random_record(rng, n_acts=rng.randint(1, 9), forward=True) if rng.random() < 0.5
               else loop_record(rng, n_acts=rng.randint(1, 9)))
        kk, _ = lfp(rec, 'U')
        wf = wfs(rec)
        if citation_acyclic(rec):
            tight += 1
            tight_out += not in_D(rec)[0]
            assert kk == wf, 'tight record with WF != KK'
        else:
            nontight += 1
            nt_diff += kk != wf
    print(f'(14) 6000 records with forward citations: {tight} without a citation cycle ({tight_out} of them outside D):',
          f'WF = KK on all; {nontight} with a cycle, WF != KK on {nt_diff}')
    # (15) refusal reasons in the cases the turn-5 reviews named, all in D
    def adopt(i, actor, cites, t=3, scope='s'):
        return {'id': i, 'kind': 'adopt', 'actor': actor, 'cites': cites, 'item': 'c', 'scope': scope, 't': t,
                'vfrom': 0, 'vto': 9}
    def annul(i, actor, cites, target, t=2):
        return {'id': i, 'kind': 'annul', 'actor': actor, 'cites': cites, 'target': target, 'scope': 's', 't': t}
    S1 = {('s', 's')}
    cases = {
        'indirect self-cancelling (annuls the parent grant)': (Record([ROOT, G('g1', 'P', ['r'], 'A', t=0),
            G('g2', 'A', ['g1'], 'B', t=1), annul('e', 'B', ['g2'], 'g1'), adopt('x', 'B', ['g2'])], S1), 'self-cancelling'),
        'annulment ring of three': (Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'P', ['r'], 'B', t=0),
            G('g3', 'P', ['r'], 'C', t=0), annul('a1', 'A', ['g1'], 'a2'), annul('a2', 'B', ['g2'], 'a3'),
            annul('a3', 'C', ['g3'], 'a1'), adopt('x', 'P', ['r']), annul('n', 'P', ['r'], 'x', t=4)], S1), None),
        'ending cycle (position/key inversion)': (Record([ROOT, G('g', 'P', ['r'], 'A', t=0),
            G('h', 'A', ['g'], 'B', t=5), {'id': 'e', 'kind': 'endg', 'actor': 'B', 'cites': ['h'], 'grant': 'g',
            'scope': 's', 't': 4}, adopt('x', 'A', ['g'], t=6)], S1), 'negative cycle'),
        'queried scope with covers undefined': (Record([ROOT, adopt('x', 'P', ['r'], scope='s/a')],
            {('s', 's'), ('s', 's/a')}), 'queried scope'),
        'revocation of undetermined authorization': (Record([ROOT, G('g1', 'P', ['r'], 'A', t=0),
            annul('e', 'A', ['g1'], 'g1', t=1), adopt('x', 'P', ['r'], t=2),
            {'id': 'v', 'kind': 'revoke', 'actor': 'A', 'cites': ['g1'], 'item': 'c', 'scope': 's', 't': 3}], S1),
            'self-cancelling'),
    }
    for name, (rec, want) in cases.items():
        assert in_D(rec)[0], name
        q = 's/a' if 'queried' in name else None
        v, rs = ce_star_explained(rec, 'c', 5 if 'revocation' in name else 7, q)
        if name.startswith('annulment ring'):
            v, rs = act_explained(rec, 'a1')
            want = 'annulment ring'
        print(f'(15) {name}: {v}, reason {rs}')
        assert v == U and any(want in r for r in rs) and not any(r.startswith('undetermined') for r in rs)
    nores = asked = 0
    for _ in range(3000):
        rec = random_record(rng, n_acts=rng.randint(2, 9), targets_any=True)
        for tt in range(0, 7):
            v = ce_star(rec, 'c', tt)
            if v == U:
                asked += 1
                nores += ce_star_explained(rec, 'c', tt)[1][0].startswith('undetermined')
        a = wfs(rec)
        for i in rec.acts():
            if a[i] == U:
                asked += 1
                nores += act_explained(rec, i)[1][0].startswith('undetermined')
    print(f'(15b) 3000 random records in D: {asked} U answers (items and acts); without a named reason: {nores}')
    assert nores == 0
    # (15c) soundness of reasons: every act named in a reason is U; with a queried scope as well
    import re as _re
    bad = named_n = 0
    def d_loop_record(n):
        """In D, annulment-rich: citations of earlier grants or the root only; annulment targets any other entry."""
        ents, ids = [dict(ROOT)], ['r']
        for j in range(n):
            kind = rng.choice(['grant', 'grant', 'annul', 'annul', 'adopt'])
            i = f'{kind[0]}{j}'
            bases = [y for y in ids if y == 'r' or y.startswith('g')]
            e = {'id': i, 'kind': kind, 'actor': rng.choice(['P', 'A', 'B']), 'cites': [rng.choice(bases)], 'scope': 's',
                 't': rng.randint(0, 3)}
            if kind == 'grant':
                e.update(holder=rng.choice(['P', 'A', 'B']), start=0, end=9)
            elif kind == 'adopt':
                e.update(item='c', vfrom=0, vto=9)
            else:
                e.update(target=None)
            ents.append(e); ids.append(i)
        for e in ents:
            if e.get('target', 0) is None:
                e['target'] = rng.choice([y for y in ids if y != 'r'])
        return Record(ents, {('s', 's')})
    for _ in range(2000):
        rec = d_loop_record(rng.randint(3, 9)) if rng.random() < 0.7 else random_record(rng, n_acts=rng.randint(2, 9), targets_any=True)
        a = wfs(rec)
        for tt in range(0, 7):
            for q in (None, 's/a'):
                v, rs = ce_star_explained(rec, 'c', tt, q)
                if v != U:
                    continue
                for r in rs:
                    if r.startswith('covers(') or r.startswith('same act time'):
                        continue                   # these name a pair, not U acts (v9: order reasons name the two acts)
                    for tok in _re.findall(r'[a-z]\d+|\br\b', r):
                        if tok in a:
                            named_n += 1
                            bad += a[tok] != U
    print(f'(15c) 2000 random records in D, with and without a queried scope: {named_n} acts named in reasons; not U: {bad}')
    assert bad == 0 and named_n > 0
    # (15d) decision 3 'when U': an act annulling a link of its own chain is U only when nothing else decides it
    red = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'P', ['r'], 'A', t=0),
                  annul('e', 'A', ['g1', 'g2'], 'g1', t=1)], S1)
    dead = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0),
                   {'id': 'n', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g1', 'scope': 's', 't': 1},
                   annul('e', 'A', ['g1'], 'g1', t=2)], S1)
    selfann = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), annul('e', 'A', ['g1'], 'e', t=1)], S1)
    vr, vd = wfs(red)['e'], wfs(dead)['e']
    sa = act_explained(selfann, 'e')
    print(f'(15d) self-cancelling with a second intact basis: e = {vr}; with the link already ended: e = {vd};',
          f'an act annulling itself: {sa}')
    assert vr == T and vd == F and sa[0] == U and 'self-annulling' in sa[1][0]
    # (16) two fixed rules of Θ_f made visible: P is conjunctive over cited grants; a root cannot be annulled
    conj = Record([ROOT, G('g1', 'P', ['r'], 'A', t=0), G('g2', 'P', ['r'], 'Z', t=0), adopt('y', 'A', ['g1', 'g2'], t=1),
                   {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g2', 'scope': 's', 't': 2}], S1)
    ra = Record([ROOT, annul('n', 'P', ['r'], 'r', t=1), adopt('y', 'P', ['r'], t=2)], S1)
    print('(16) adoption authorized through g1, unused g2 ended: authorized(y) =', wfs(conj)['y'], '; CE*(c, 3) =',
          ce_star(conj, 'c', 3), '| annulment of the root: root', wfs(ra)['r'], ', adoption under it', wfs(ra)['y'])
    assert wfs(conj)['y'] == T and ce_star(conj, 'c', 3) == F and wfs(ra)['r'] == T and wfs(ra)['y'] == T
    # (17) v6: recorder and auth fields; marks; tombstones
    nof = Record([{'id': 'r', 'kind': 'root', 'actor': 'P', 'scope': 's', 't': 0}], S1, defaults=False)
    badauth = Record([dict(ROOT, auth='attested')], S1)
    base = [dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), recorder='Gov', auth='signed-as-other'),
            dict(adopt('x', 'A', ['g'], t=1), auth='unsigned'),
            {'id': 'z', 'kind': 'adopt', 'actor': 'P', 'cites': ['r'], 'item': 'd', 'scope': 's', 't': 1, 'vfrom': 0,
             'vto': 9, 'content': 'text'}]
    mk = Record([dict(e) for e in base], S1)
    v_before = ce_star(mk, 'c', 3)
    m_c, m_d = marks(mk, 'c', 3), marks(mk, 'd', 3)
    tomb = Record([dict(e) for e in base], S1)
    remove(tomb, 'g', 'rm', 'P', ['r'], 5)
    v_after = ce_star(tomb, 'c', 3)
    print('(17) entries without recorder and auth:', in_D(nof)[1], '| auth outside the allowed values:', in_D(badauth)[1])
    print('     marks for c (rests on a transcribed grant and an unsigned adoption):', ce_star(mk, 'c', 3), m_c,
          '| for d (signed-as-actor throughout):', ce_star(mk, 'd', 3), m_d)
    print('     after a tombstone of g: in D:', in_D(tomb)[0], '; CE*(c, 3) =', v_after, '(before:', v_before, ');',
          'marks:', marks(tomb, 'c', 3))
    assert not in_D(nof)[0] and not in_D(badauth)[0]
    assert v_before == T and any('transcribed entries g' in m for m in m_c) and any('unsigned entries x' in m for m in m_c)
    assert m_d == [] and in_D(tomb)[0] and v_after == v_before and any('removed content g' in m for m in marks(tomb, 'c', 3))
    # (18) v7 (turn 7, F22/K4): several applications' records merged with no shared serialization point. A causal history
    # (every citation of an entry already made) is split between two applications; the merge is any order in which
    # citations point backward. With ties by identity (F26 (c)), every such order gives the same answers; with ties by
    # position (F26 (a)), the answer can depend on the merge order. A cut across records (one prefix per application) is
    # in D iff it is citation-closed (a consistent cut).
    same_id = diff_pos = n_hist = cut_ok = cut_bad = 0
    for _ in range(600):
        hist = random_record(rng, n_acts=rng.randint(3, 9), targets_any=True)
        app = {x['id']: (0 if x['kind'] == 'root' else rng.choice([1, 2])) for x in hist.e}
        n_hist += 1
        answers = {'id': set(), 'pos': set()}
        for _k in range(6):
            ext = linear_extension(rng, [dict(x) for x in hist.e])
            for tie_rule in ('id', 'pos'):
                m = Record([dict(x) for x in ext], hist.dom, tie=tie_rule)
                assert in_D(m)[0]
                answers[tie_rule].add(tuple(ce_star(m, 'c', tt, q) for tt in range(7) for q in (None, 's/a')))
        same_id += len(answers['id']) == 1
        diff_pos += len(answers['pos']) > 1
        cut_len = {1: rng.randint(0, 9), 2: rng.randint(0, 9)}
        seen = {1: 0, 2: 0}
        cut = []
        for x in hist.e:
            a_ = app[x['id']]
            if a_ == 0:
                cut.append(dict(x))
                continue
            seen[a_] += 1
            if seen[a_] <= cut_len[a_]:
                cut.append(dict(x))
        ids_in = {x['id'] for x in cut}
        closed = all(g in ids_in for x in cut for g in x.get('cites', []))
        inD = in_D(Record(cut, hist.dom, tie='id'))[0]
        assert closed == inD
        cut_ok += closed
        cut_bad += not closed
    # the race: application 1 records A's grant x to B at act time 3; application 2 records P's ending of A's grant g at
    # act time 3; B later adopts c under x. Neither of x, e cites the other, so both merge orders keep D.
    y = {'id': 'y', 'kind': 'adopt', 'actor': 'B', 'cites': ['x'], 'item': 'c', 'scope': 's', 't': 5, 'vfrom': 0, 'vto': 9}
    race = [ROOT, G('g', 'P', ['r'], 'A', t=0), G('x', 'A', ['g'], 'B', t=3),
            {'id': 'e', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'g', 'scope': 's', 't': 3}, y]
    order1, order2 = race, race[:2] + [race[3], race[2], y]
    p1, p2 = (ce_star(Record([dict(x) for x in o], S1, tie='pos'), 'c', 6) for o in (order1, order2))
    un1, un2 = (ce_star_explained(Record([dict(x) for x in o], S1), 'c', 6) for o in (order1, order2))
    i1, i2 = (ce_star(Record([dict(x) for x in o], S1, tie='id'), 'c', 6) for o in (order1, order2))
    print(f'(18) {n_hist} causal histories split between two applications as FRAGMENTS (an application holds only its own entries,',
          'so its record is in general not in D), 6 merge orders each: same answers under ties by',
          f'identity in {same_id}; answers depend on the merge order under ties by position in {diff_pos}')
    print(f'     cuts across records: in D exactly when citation-closed ({cut_ok} closed, {cut_bad} not, all refused)')
    print(f'     the race (grant x and the ending e of its basis at the same act time, in two applications; B adopts c under x',
          f'later): ties by position give CE*(c, 6) = {p1} or {p2} by merge order; ties by identity give {i1} and {i2};',
          f'v9, ties unordered (the Principal after turn 7): {un1[0]} and {un2[0]}, reason {un1[1]}')
    assert same_id == n_hist and p1 != p2 and i1 == i2 and cut_bad > 0
    assert un1[0] == un2[0] == U and any('same act time' in r for r in un1[1])
    # (18b) v8 (D1-01/D2-01): each application's record in D: it holds its own entries and copies of what they cite.
    # Then every union of records, and every union of per-record prefixes, is closed under citation: the cut condition is
    # automatic. With ties by identity, merge orders give the same values AND the same marks.
    n_rec_in_D = vals_same = marks_same = cuts_in_D = cuts = 0
    for _ in range(400):
        hist = random_record(rng, n_acts=rng.randint(3, 9), targets_any=True)
        app = {x['id']: rng.choice([1, 2]) for x in hist.e if x['kind'] != 'root'}
        recs = []
        for a_ in (1, 2):
            need = set(['r'] + [i for i, v in app.items() if v == a_])
            st = list(need)
            while st:
                q = st.pop()
                for g in hist.by[q].get('cites', []):
                    if g not in need:
                        need.add(g)
                        st.append(g)
            own = [dict(x, recorder=f'app{a_}', auth=(x.get('auth') if app.get(x['id']) == a_ or x['id'] == 'r'
                                                       else rng.choice(sorted(AUTH)))) for x in hist.e if x['id'] in need]
            recs.append(own)
        n_rec_in_D += all(in_D(Record([dict(x) for x in R], hist.dom))[0] for R in recs)
        outs = set()
        for _k in range(4):
            ents, why = merge([[dict(x) for x in R] for R in recs], rng)
            assert why is None
            m = Record(ents, hist.dom)                     # v9: ties unordered (the rule now)
            assert in_D(m)[0]
            outs.add(tuple((ce_star(m, 'c', tt), tuple(marks(m, 'c', tt))) for tt in range(7)))
        vals_same += len({tuple(v for v, _m in o) for o in outs}) == 1
        marks_same += len(outs) == 1
        for _k in range(3):
            pre = [R[:rng.randint(1, len(R))] for R in recs]
            ents, _w = merge([[dict(x) for x in R] for R in pre])
            cuts += 1
            cuts_in_D += in_D(Record(ents, hist.dom))[0]
    print(f'(18b) 400 histories, each application holding copies of what it cites: both records in D in {n_rec_in_D};',
          f'merge orders give the same values in {vals_same} and the same values and marks in {marks_same};',
          f'cuts across records (one prefix each) in D: {cuts_in_D} of {cuts} (automatic)')
    assert n_rec_in_D == 400 and vals_same == 400 and marks_same == 400 and cuts_in_D == cuts
    # (18c) v8 (D1-09, D2): crowded act times (all in {1, 2}): ties by position do differ on random records; by identity not
    dpos = did = dun = 0
    for _ in range(400):
        hist = random_record(rng, n_acts=rng.randint(4, 9), targets_any=True)
        for x in hist.e:
            if x['kind'] != 'root':
                x['t'] = rng.choice([1, 2])
        res = {'id': set(), 'pos': set(), 'unordered': set()}
        for _k in range(6):
            ext = linear_extension(rng, [dict(x) for x in hist.e])
            for tr in res:
                res[tr].add(tuple(ce_star(Record([dict(x) for x in ext], hist.dom, tie=tr), 'c', tt) for tt in range(4)))
        dpos += len(res['pos']) > 1
        did += len(res['id']) > 1
        dun += len(res['unordered']) > 1
    print(f'(18c) 400 histories with act times in {{1, 2}}, 6 merge orders: answers differ by merge order under ties by',
          f'position in {dpos}, by identity in {did}, unordered (v9) in {dun}')
    assert did == 0 and dpos > 0 and dun == 0
    # (18d) v8 (D1-02/D2-02): copies of one act with different recorder and auth, and a tombstone with its original
    g_full = dict(G('g', 'P', ['r'], 'A', t=0), content='text', auth='signed-as-actor', recorder='app1')
    g_copy = dict(G('g', 'P', ['r'], 'A', t=0), content='text', auth='signed-as-other', recorder='app2')
    xa = dict(adopt('x', 'A', ['g'], t=1), recorder='app1')
    r1, r2 = [dict(ROOT), g_full, xa], [dict(ROOT), g_copy]
    t2 = [dict(ROOT), dict(g_copy, removed=True, removed_by=['rm']),
          {'id': 'rm', 'kind': 'remove', 'actor': 'P', 'cites': ['r'], 'target': 'g', 'scope': 's', 't': 5,
           'recorder': 'app2', 'auth': 'signed-as-actor'}]
    t2[1].pop('content')
    res = []
    for rs in ([r1, r2], [r2, r1], [r1, t2], [t2, r1]):
        ents, why = merge([[dict(x) for x in R] for R in rs])
        m = Record(ents, S1)
        res.append((in_D(m)[0], ce_star(m, 'c', 3), tuple(marks(m, 'c', 3))))
    conflict = merge([[dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0))], [dict(ROOT), dict(G('g', 'P', ['r'], 'B', t=0))]])
    print('(18d) one act copied by two applications (signed-as-actor, signed-as-other), both merge orders:', res[0], res[1],
          '| a tombstone in one, the original in the other, both orders:', res[2], res[3],
          '| copies that disagree on a field CE* reads:', conflict[1])
    assert res[0] == res[1] and res[2] == res[3] and all(r[0] and r[1] == T for r in res)
    assert any('transcribed' in m for m in res[0][2]) and any('removed' in m for m in res[2][2]) and conflict[0] is None
    # (17b) v8 (A1-05, D2-06, A1-04/A2-02): a tombstone of an entry with content; a tombstone of a failing entry
    tb = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), content='the grant text'), dict(adopt('x', 'A', ['g'], t=1))], S1)
    before = ce_star(tb, 'c', 3)
    remove(tb, 'g', 'rm', 'P', ['r'], 5)
    fail = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), content='text', ok=False), dict(adopt('x', 'A', ['g'], t=1))], S1)
    f_before = in_D(fail)[0]
    remove(fail, 'g', 'rm', 'P', ['r'], 5)
    print('(17b) tombstone of a grant with content: content dropped:', 'content' not in tb.by['g'], '; CE*(c, 3) before/after:',
          before, ce_star(tb, 'c', 3), '; marks:', marks(tb, 'c', 3),
          '| tombstone of an entry that failed the integrity check: in D before', f_before, ', after', in_D(fail)[0])
    assert 'content' not in tb.by['g'] and before == ce_star(tb, 'c', 3) == T and not f_before and not in_D(fail)[0]
    # (19) v8 (A1-01/A2-01): the support contains every entry the answer rests on. For entries outside it: removing the
    # entry (with what cites it) or changing a grant's holder never changes the answer. Also the reviewers' two cases.
    outside = changed = 0
    for _ in range(1500):
        rec = random_record(rng, n_acts=rng.randint(2, 9), targets_any=True)
        for tt in (2, 4, 6):
            v0 = ce_star(rec, 'c', tt)
            sp = support(rec, 'c', tt)
            for e in rec.e:
                if e['id'] in sp or e['kind'] == 'root':
                    continue
                outside += 1
                drop = {e['id']}
                grow = True
                while grow:
                    grow = False
                    for y in rec.e:
                        if y['id'] not in drop and set(y.get('cites', [])) & drop:
                            drop.add(y['id'])
                            grow = True
                r2_ = Record([dict(y) for y in rec.e if y['id'] not in drop], rec.dom)
                if in_D(r2_)[0]:
                    changed += ce_star(r2_, 'c', tt) != v0
                if e['kind'] == 'grant':
                    r3_ = Record([dict(y, holder=('Z' if y['holder'] != 'Z' else 'A')) if y['id'] == e['id'] else dict(y)
                                  for y in rec.e], rec.dom)
                    changed += ce_star(r3_, 'c', tt) != v0
    p1 = Record([dict(ROOT), G('g', 'P', ['r'], 'A', t=0), adopt('y', 'A', ['g'], t=1),
                 dict(G('h', 'P', ['r'], 'Z', t=0), auth='unsigned'),
                 {'id': 'v', 'kind': 'revoke', 'actor': 'B', 'cites': ['h'], 'item': 'c', 'scope': 's', 't': 2}], S1)
    p2 = Record([dict(ROOT), dict(G('g0', 'P', ['r'], 'Z', t=0), auth='signed-as-other', recorder='Gov'), G('g', 'A', ['g0'], 'A', t=0),
                 adopt('y', 'A', ['g'], t=1)], S1)
    print(f'(19) 1500 random records in D, times 2, 4, 6: {outside} entries outside the support; removing one or changing',
          f'its holder changed the answer {changed} times | case P1: CE*(c, 5) =', ce_star(p1, 'c', 5), marks(p1, 'c', 5),
          '| case P2:', ce_star(p2, 'c', 5), marks(p2, 'c', 5))
    assert outside > 0 and changed == 0 and marks(p1, 'c', 5) and marks(p2, 'c', 5)
    # (20) v8 (A2-10): random recorder and auth fields change no value
    diff = 0
    for _ in range(1500):
        rec = random_record(rng, n_acts=rng.randint(2, 9), targets_any=True)
        v = [ce_star(rec, 'c', tt) for tt in range(7)]
        def rnd(y):
            au = rng.choice(sorted(AUTH))
            rc = rng.choice([w for w in ['P', 'A', 'B', 'Gov'] if not (au == 'signed-as-other' and w == y.get('actor'))])
            return dict(y, recorder=rc, auth=au)
        r2_ = Record([rnd(y) for y in rec.e], rec.dom, defaults=False)
        diff += v != [ce_star(r2_, 'c', tt) for tt in range(7)]
    print(f'(20) 1500 random records in D with random recorder and auth: values changed in {diff}')
    assert diff == 0
    # (21) v9-v10: consumption admitted to D. The holder consumes g: acts under g ordered after the consumption are F; an
    # adoption made before stays current (v10, T1-04, §1.14.1-4); a consumption by a non-holder, or by the holder of
    # another grant, is F and ends nothing (v10, T2-02); a second consumption is F after the first, U at the same act
    # time; the append guard refuses a consumption while another consumption of the grant is not F (v10, T1-02).
    def cons(i, actor, t, g='g', cites=None):
        return {'id': i, 'kind': 'consume', 'actor': actor, 'cites': cites or [g], 'grant': g, 'scope': 's', 't': t}
    base21 = [dict(ROOT), G('g', 'P', ['r'], 'A', t=0)]
    r21 = Record([dict(x) for x in base21] + [cons('k1', 'A', 2), adopt('x1', 'A', ['g'], t=1), adopt('x3', 'A', ['g'], t=3)], S1)
    w21 = wfs(r21)
    cur_x1 = current(Record([dict(x) for x in base21] + [adopt('x1', 'A', ['g'], t=1), cons('k1', 'A', 2)], S1),
                     wfs(Record([dict(x) for x in base21] + [adopt('x1', 'A', ['g'], t=1), cons('k1', 'A', 2)], S1)), 'c', 4, 'U')
    r21b = Record([dict(x) for x in base21] + [cons('k1', 'B', 2), adopt('x3', 'A', ['g'], t=3)], S1)
    w21b = wfs(r21b)
    r21o = Record([dict(x) for x in base21] + [G('gB', 'P', ['r'], 'B', t=0), cons('kB', 'B', 2, g='g', cites=['gB', 'g']),
                                               adopt('x3', 'A', ['g'], t=3)], S1)
    w21o = wfs(r21o)
    r21c = Record([dict(x) for x in base21] + [cons('k1', 'A', 2), cons('k2', 'A', 4)], S1)
    r21d = Record([dict(x) for x in base21] + [cons('k1', 'A', 2), cons('k2', 'A', 2)], S1)
    g_ok, g_why = append_guard(r21, cons('k2', 'A', 4))
    g_ok_b, _ = append_guard(r21b, cons('k2', 'A', 4))          # the earlier consumption is F: not blocking
    r21a = Record([dict(x) for x in base21] + [cons('k1', 'A', 2), annul('n', 'P', ['r'], 'k1', t=3)], S1)
    g_ok_a, _ = append_guard(r21a, cons('k2', 'A', 4))          # the earlier consumption is annulled (F): not blocking
    bad_cite = Record([dict(x) for x in base21] + [G('gB', 'P', ['r'], 'B', t=0), cons('kB', 'B', 2, g='g', cites=['gB'])], S1)
    print('(21) consumption: k1 by the holder at 2: k1 =', w21['k1'], ', act at 1 =', w21['x1'], ', act at 3 =', w21['x3'],
          '| an adoption at 1, consumed at 2: current at 4 =', cur_x1,
          '| by a non-holder: k1 =', w21b['k1'], ', act at 3 =', w21b['x3'],
          '| by the holder of another grant, citing both: kB =', w21o['kB'], ', act at 3 =', w21o['x3'],
          '| not citing the consumed grant:', in_D(bad_cite)[1])
    print('     a second consumption later: k2 =', wfs(r21c)['k2'], '; at the same act time: k1, k2 =', wfs(r21d)['k1'],
          wfs(r21d)['k2'], '| append guard after an effective consumption:', g_ok, g_why,
          '| after an ineffective one:', g_ok_b, '| after an annulled one:', g_ok_a, '| in D:', in_D(r21)[0])
    assert w21['k1'] == T and w21['x1'] == T and w21['x3'] == F and cur_x1 == T and w21b['k1'] == F and w21b['x3'] == T
    assert w21o['kB'] == F and w21o['x3'] == T and not in_D(bad_cite)[0]
    assert wfs(r21c)['k2'] == F and wfs(r21d)['k1'] == U and wfs(r21d)['k2'] == U and not g_ok and g_ok_b and g_ok_a
    assert in_D(r21)[0]
    # (22) v9-v10: a removal is an authorized, recorded act that the tombstone cites; an unauthorized removal is refused
    # (v10, T1-05: content loss cannot be undone, so it is prevented); a removal that later stops being T is marked
    r22 = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), content='text'), adopt('x', 'A', ['g'], t=1)], S1)
    v22 = ce_star(r22, 'c', 3)
    ok22, _ = remove(r22, 'g', 'rm', 'P', ['r'], 5)
    r22b = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), content='text'), adopt('x', 'A', ['g'], t=1)], S1)
    ok22b, why22b = remove(r22b, 'g', 'rm', 'Z', ['r'], 5)      # Z is not the root's actor: refused
    r22f = Record([dict(ROOT), G('gR', 'P', ['r'], 'R', t=0), dict(G('g', 'P', ['r'], 'A', t=0), content='text'),
                   adopt('x', 'A', ['g'], t=1)], S1)
    remove(r22f, 'g', 'rm', 'R', ['gR'], 5)
    r22f.e.append(dict(annul('n', 'P', ['r'], 'rm', t=6), recorder='P', auth='signed-as-actor'))
    r22f = Record(r22f.e, S1)
    r22c = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), removed=True), adopt('x', 'A', ['g'], t=1)], S1)
    print('(22) removal by an authorized act:', ok22, 'CE* =', ce_star(r22, 'c', 3), '(before', v22, '), marks', marks(r22, 'c', 3),
          '| by an unauthorized act: refused:', not ok22b, why22b, '; content kept:', 'content' in r22b.by['g'],
          '| a removal later annulled: CE* =', ce_star(r22f, 'c', 3), marks(r22f, 'c', 3),
          '| a tombstone citing no removal act:', in_D(r22c)[1])
    assert ok22 and ce_star(r22, 'c', 3) == v22 == T and not any('authorized' in m for m in marks(r22, 'c', 3))
    assert not ok22b and 'content' in r22b.by['g'] and ce_star(r22f, 'c', 3) == T
    assert any('no longer, authorized' in m for m in marks(r22f, 'c', 3)) and not in_D(r22c)[0]
    # (22b) v9-v10: marks for one act's authorization; signed-as-other with recorder = actor is marked, not refused
    r22d = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), auth='signed-as-other', recorder='Gov'),
                   adopt('x', 'A', ['g'], t=1)], S1)
    r22e = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), auth='signed-as-other'), adopt('x', 'A', ['g'], t=1)], S1)
    print('(22b) marks for authorized(x):', marks_act(r22d, 'x'), '| signed-as-other with recorder = actor: in D',
          in_D(r22e)[0], ', marks', marks(r22e, 'c', 3))
    assert any('transcribed entries g' in m for m in marks_act(r22d, 'x')) and in_D(r22e)[0]
    assert any('inconsistent recording status' in m for m in marks(r22e, 'c', 3))
    # (23) v10 (T1-01): every cut of a record with removals is evaluable, and its values do not change by the removal
    bad23 = n23 = 0
    for _ in range(300):
        rec = random_record(rng, n_acts=rng.randint(3, 8), targets_any=True)
        if not in_D(rec)[0]:
            continue
        before = [ce_star_cut(rec, s_, 'c', tt) for s_ in range(1, len(rec.e) + 1) for tt in (2, 4, 6)]
        victims = [x['id'] for x in rec.e if x['kind'] in ('grant', 'adopt')]
        if not victims:
            continue
        r2_ = Record([dict(x) for x in rec.e], rec.dom)
        if not remove(r2_, rng.choice(victims), 'rmX', 'P', ['r'], 9)[0]:
            continue
        n23 += 1
        after = [ce_star_cut(r2_, s_, 'c', tt) for s_ in range(1, len(rec.e) + 1) for tt in (2, 4, 6)]
        bad23 += before != after
    print(f'(23) {n23} records with a removal: every cut evaluated; values changed by the removal at some cut in {bad23}')
    assert n23 > 0 and bad23 == 0
    # (18e) v10 (T1-06, T2-04): two removals of one entry, in two applications, one authorized and one not
    gq = dict(G('g', 'P', ['r'], 'A', t=0), content='text')
    xq = adopt('x', 'A', ['g'], t=1)
    q1 = Record([dict(ROOT), dict(gq), dict(xq)], S1)
    remove(q1, 'g', 'rm1', 'P', ['r'], 5)
    q2 = Record([dict(ROOT), G('gZ', 'P', ['r'], 'Z', t=0), dict(gq), dict(xq)], S1)
    remove(q2, 'g', 'rm2', 'Z', ['gZ'], 5)
    q2.e.append(dict(annul('n2', 'P', ['r'], 'rm2', t=6), recorder='P', auth='signed-as-actor'))
    outs = []
    for rs in ([q1.e, q2.e], [q2.e, q1.e]):
        ents, why = merge([[dict(x) for x in R] for R in rs])
        m = Record(ents, S1)
        outs.append((in_D(m)[0], ce_star(m, 'c', 3), tuple(marks(m, 'c', 3))))
    print('(18e) two removals of one entry, one later annulled, both merge orders:', outs[0], outs[1])
    assert outs[0] == outs[1] and outs[0][0] and not any('authorized' in m for m in outs[0][2])
    # (24) v11 (the Principal, F22 = (C + O)): one owner application per grant, fixed at issuance; a change of owner is a
    # recorded, authorized act (at the kernel); the kernel refuses a use of a grant not recorded by its owner.
    def use(i, app, t, g='g', actor='A'):
        return {'id': i, 'kind': 'consume', 'actor': actor, 'cites': [g], 'grant': g, 'scope': 's', 't': t, 'app': app}

    def xfer(i, new_owner, t, actor='P', cites=('r',), app=KERNEL, g='g'):
        return {'id': i, 'kind': 'transfer', 'actor': actor, 'cites': list(cites), 'grant': g, 'new_owner': new_owner,
                'scope': 's', 't': t, 'app': app}
    base24 = [dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A1', single_use=True)]   # v13: single-use
    r24 = Record([dict(x) for x in base24], S1)
    ok_own, _ = append_guard(r24, use('k1', 'A1', 2))
    ok_oth, why_oth = append_guard(r24, use('k1', 'A2', 2))
    # (24b) the cost: the owner A1 is unavailable, so nobody else can use g; moving ownership is the only way
    r24b = Record([dict(x) for x in base24], S1)
    before_move = append(r24b, use('k2', 'A2', 3))
    move = append(r24b, xfer('m1', 'A2', 4))
    after_move = append(r24b, use('k2', 'A2', 5))
    r24s = Record([dict(x) for x in base24], S1)
    append(r24s, xfer('m1', 'A2', 4))
    stale = append(r24s, use('k1', 'A1', 3))                    # the old owner, not yet aware of the move
    print('(24) use by the owner A1:', ok_own, '| by A2:', ok_oth, why_oth)
    print('(24b) owner unavailable: use by A2 before the move:', before_move, '| the move to A2 at the kernel:', move,
          '| use by A2 after it:', after_move, '| the old owner after the move (act time 3, appended later):', stale)
    assert ok_own and not ok_oth and not before_move[0] and move[0] and after_move[0] and not stale[0]
    # (24c) a transfer not at the kernel, an unauthorized transfer, a transfer at a tie with the end of its basis, and a
    # record holding a U transfer (owner undetermined: a use is refused)
    r24c = Record([dict(x) for x in base24] + [G('gQ', 'P', ['r'], 'Q', t=0), {'id': 'eQ', 'kind': 'endg', 'actor': 'P',
                   'cites': ['r'], 'grant': 'gQ', 'scope': 's', 't': 5}], S1)
    not_kernel = append_guard(r24c, xfer('m1', 'A2', 4, app='A1'))
    unauth = append_guard(r24c, xfer('m1', 'A2', 4, actor='Z'))
    tie_u = append_guard(r24c, xfer('m1', 'A2', 5, actor='Q', cites=('gQ',)))
    r24u = Record([dict(x) for x in r24c.e] + [xfer('m1', 'A2', 5, actor='Q', cites=('gQ',))], S1)
    use_u = append_guard(r24u, use('k1', 'A1', 6))
    print('(24c) transfer recorded by A1:', not_kernel, '| by an actor without basis:', unauth, '| at a tie with its basis\'s end:',
          tie_u, '| a use while a transfer is U:', use_u)
    assert not not_kernel[0] and not unauth[0] and not tie_u[0] and 'U' in tie_u[1] and not use_u[0] and 'undetermined' in use_u[1]
    # (24d) v13 (the Principal after C20): every act under a single-use grant is a use and is owner-checked; a grant that
    # is not single-use needs no owner check
    ad_other = append_guard(r24, dict(adopt('x', 'A', ['g'], t=1), app='A2'))
    ad_owner = append_guard(r24, dict(adopt('x', 'A', ['g'], t=1), app='A1'))
    r24n = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A1')], S1)
    ad_ns = append_guard(r24n, dict(adopt('x', 'A', ['g'], t=1), app='A2'))
    print('(24d) an adoption under a single-use g by A2:', ad_other, '| by the owner A1:', ad_owner,
          '| under a grant not single-use, by A2:', ad_ns)
    assert not ad_other[0] and ad_owner[0] and ad_ns[0]
    # (24e) detection of a bypass: a consumption by a non-owner present in a record (e.g. arriving by a merge, not through
    # the guard) is marked; values do not read the recording application
    r24e = Record([dict(x) for x in base24] + [use('k1', 'A2', 2), dict(adopt('x3', 'A', ['g'], t=3), app='A1')], S1)
    r24f = Record([dict(x) for x in base24] + [use('k1', 'A1', 2), dict(adopt('x3', 'A', ['g'], t=3), app='A1')], S1)
    print('(24e) a bypassing use: values', wfs(r24e) == wfs(r24f), ', marks', marks_act(r24e, 'x3'), '| by the owner: marks',
          marks_act(r24f, 'x3'))
    assert wfs(r24e) == wfs(r24f) and any('not recorded by its owner' in m for m in marks_act(r24e, 'x3'))
    assert not any('owner' in m for m in marks_act(r24f, 'x3'))
    # (24f) the owner is a read field: two copies of a grant naming different owners do not merge
    c1 = [dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A1')]
    c2 = [dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A2')]
    m24, why24 = merge([Record(c1, S1).e, Record(c2, S1).e])
    print('(24f) copies naming two owners:', why24)
    assert m24 is None
    # (24g) the owner and the recording application change no value: 500 random records, apps and owners randomized
    diff24 = 0
    for _ in range(500):
        rec = random_record(rng, n_acts=rng.randint(3, 8))
        if not in_D(rec)[0]:
            continue
        v = [ce_star(rec, 'c', tt) for tt in range(7)]
        r2_ = Record([dict(y, **({'owner': rng.choice(['A1', 'A2'])} if y['kind'] == 'grant' else
                                 {'app': rng.choice(['A1', 'A2'])} if y['kind'] == 'consume' else {})) for y in rec.e], rec.dom)
        diff24 += v != [ce_star(r2_, 'c', tt) for tt in range(7)]
    print(f'(24g) 500 random records with random owners and recording applications: values changed in {diff24}')
    assert diff24 == 0
    # (24h) v12 (C20, W-1): a transfer T at append becomes U when its basis is ended at the same act time; a later
    # authorized transfer at the kernel restores a determinate owner (v11 froze the grant)
    D24 = {('s', 's'), ('s', 's/a'), ('s', 's/b'), ('s/b', 's/b'), ('s/a', 's/b'), ('s/b', 's/a'), ('s/a', 's/a')}
    # v13: Q holds authority above the issuer's scope (gQ at s over g at s/a), so Q may move g
    r24h = Record([dict(ROOT), G('gP', 'P', ['r'], 'P2', t=0, scope='s/a'),          # v14: issuer P2 acts at s/a
                   dict(G('g', 'P2', ['gP'], 'A', t=0, scope='s/a'), owner='A1', single_use=True),
                   G('gQ', 'P', ['r'], 'Q', t=0)], D24)
    h1 = append(r24h, dict(xfer('m1', 'A2', 5, actor='Q', cites=('gQ',)), scope='s/a'))
    r24h.e.append({'id': 'eQ', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'gQ', 'scope': 's', 't': 5,
                   'recorder': 'P', 'auth': 'signed-as-actor'})
    r24h = Record(r24h.e, D24)
    u_now = append_guard(r24h, dict(use('k0', 'A2', 6), scope='s/a'))
    h2 = append(r24h, dict(xfer('m2', 'A3', 6), scope='s/a'))
    h3 = append_guard(r24h, dict(use('k', 'A3', 7), scope='s/a'))
    print('(24h) transfer m1 T at append:', h1[0], ', then U:', wfs(r24h)['m1'], '| a use meanwhile:', u_now,
          '| a later move to A3:', h2[0], '| use by A3:', h3)
    assert h1[0] and wfs(r24h)['m1'] == U and not u_now[0] and h2[0] and h3[0]
    # (24i) v12 (C20, W-2): a transfer's scope is the grant's scope, so authority on another scope cannot move it
    r24i = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0, scope='s/a'), owner='A1'),
                   G('gz', 'P', ['r'], 'Z', t=0, scope='s/b')], D24)
    i1 = append_guard(r24i, dict(xfer('m', 'A9', 3, actor='Z', cites=('gz',)), scope='s/b'))
    i2 = append_guard(r24i, dict(xfer('m', 'A9', 3), scope='s/a'))
    print('(24i) Z, with authority on s/b, moves g (s/a):', i1, '| P from the root, scope s/a:', i2)
    assert not i1[0] and i2[0]
    # (24j) v12 (C20, W-5): the owner mark reads the owner at the use's cut: a use that passed the guard is not marked
    # when the transfer it relied on is annulled later
    r24j = Record([dict(x) for x in base24], S1)
    append(r24j, xfer('m', 'A2', 3))
    append(r24j, use('k', 'A2', 4))
    r24j.e.append(dict(annul('n', 'P', ['r'], 'm', t=5), recorder='P', auth='signed-as-actor'))
    r24j = Record(r24j.e, S1)
    print('(24j) the transfer annulled after the use: m =', wfs(r24j)['m'], ', marks of k:', marks_act(r24j, 'k'))
    assert wfs(r24j)['m'] == F and not any('owner' in m for m in marks_act(r24j, 'k'))
    # (24k) v12 (C20, W-3): the guard trusts the declared application field (a located assumption, §9.15 L10)
    k1 = append_guard(Record([dict(x) for x in base24], S1), use('k', 'A1', 2, actor='A'))
    print('(24k) a use declared app=A1 by whoever submits it: accepted', k1[0], '(L10: the kernel authenticates the submitting application)')
    assert k1[0]
    # (24l) v13-v14 (the Principal after C20; C21 W-2, W-3): random append sequences through the guard. The issuer P2 acts
    # under gP at s/a and issues g at s/a/b (single-use, holder A, owner A1). Q holds gQ at s, strictly above the issuer's
    # scope; S is the issuer's own grantee at s/a (not above); Z holds gz at s/b. Events: uses (consumptions and adoptions
    # under g, random applications, some by a non-holder), transfers by the issuer, Q, S, the holder A and Z (some with a
    # wrong last use), endings of gQ, annulments of uses, of transfers and of annulments. Invariants: every accepted use
    # was T, by the owner at its cut, the first use on record, and is unmarked; at most one use of g on record; every
    # accepted transfer is by the issuer or Q, never by A or S; it carries the last use at its cut, and its citation
    # closure reaches every earlier use; the record stays in D.
    D25 = {(x, y) for x in ('s', 's/a', 's/a/b', 's/b') for y in ('s', 's/a', 's/a/b', 's/b')}
    acc = bad24 = 0
    cnt = {'use': 0, 'issuer': 0, 'above': 0, 'holder_refused': 0, 'holder_accepted': 0, 'grantee_refused': 0,
           'grantee_accepted': 0, 'wrong_last_refused': 0, 'reuse_after_annul_refused': 0}
    for _ in range(400):
        r_ = Record([dict(ROOT), G('gP', 'P', ['r'], 'P2', t=0, scope='s/a'),
                     dict(G('g', 'P2', ['gP'], 'A', t=0, scope='s/a/b'), owner='A1', single_use=True),
                     G('gQ', 'P', ['r'], 'Q', t=0), G('gS', 'P2', ['gP'], 'S', t=0, scope='s/a'),
                     G('gz', 'P', ['r'], 'Z', t=0, scope='s/b')], D25)
        for j in range(rng.randint(4, 12)):
            t_ = rng.randint(1, 6)
            c = rng.random()
            if c < 0.35:
                actor_ = rng.choice(['A', 'A', 'B'])
                if rng.random() < 0.5:
                    base_ = dict(use(f'k{j}', rng.choice(['A1', 'A2', 'A3']), t_), actor=actor_)
                else:
                    base_ = dict(adopt(f'x{j}', actor_, ['g'], t=t_), app=rng.choice(['A1', 'A2', 'A3']))
                e = dict(base_, scope='s/a/b')
            elif c < 0.65:
                who = rng.choice([{'actor': 'P2', 'cites': ('gP',)}, {'actor': 'Q', 'cites': ('gQ',)},
                                  {'actor': 'S', 'cites': ('gS',)}, {'actor': 'A', 'cites': ('g',)}, {'actor': 'Z', 'cites': ('gz',)}])
                e = dict(xfer(f'm{j}', rng.choice(['A1', 'A2', 'A3']), t_, **who), scope='s/a/b')
                if rng.random() < 0.2:
                    e['last_use'] = rng.choice([x['id'] for x in r_.e if 'g' in bases_of(x) and x['kind'] != 'transfer'] or ['gQ'])
            elif c < 0.72:
                e = {'id': f'e{j}', 'kind': 'endg', 'actor': 'P', 'cites': ['r'], 'grant': 'gQ', 'scope': 's', 't': t_}
            else:
                tg = [x['id'] for x in r_.e if x['kind'] in ('transfer', 'annul', 'consume', 'adopt')]
                e = annul(f'n{j}', 'P', ['r'], rng.choice(tg or ['gQ']), t=t_)
            n0 = len(r_.e)
            pre = Record([dict(x) for x in r_.e], D25)
            had_use = bool(uses_of(pre, 'g', n0))
            ok_, why_ = append(r_, e)
            if e['kind'] == 'transfer' and e['actor'] in ('A', 'S'):
                key = 'holder' if e['actor'] == 'A' else 'grantee'
                cnt[key + ('_accepted' if ok_ else '_refused')] += 1
            if not ok_ and why_ and 'carries' in why_:
                cnt['wrong_last_refused'] += 1
            if not ok_ and had_use and 'g' in single_use_grants(pre, e):
                a_pre = wfs(pre)
                if any(a_pre[u] == F for u in uses_of(pre, 'g', n0)):
                    cnt['reuse_after_annul_refused'] += 1
            if not ok_:
                continue
            x = r_.e[-1]
            bad24 += not in_D(r_)[0]
            bad24 += len(uses_of(r_, 'g', len(r_.e))) > 1
            if 'g' in single_use_grants(r_, x):
                acc += 1
                cnt['use'] += 1
                ow, _ = owner_at(pre, 'g', n0)
                bad24 += ow != x['app'] or had_use or wfs(r_)[x['id']] != T
                bad24 += any('owner' in m or 'second use' in m for m in marks_act(Record(r_.e, D25), x['id']))
            if x['kind'] == 'transfer':
                cnt['issuer' if x['actor'] == 'P2' else 'above'] += 1
                bad24 += x['actor'] not in ('P2', 'Q')
                us = uses_of(pre, 'g', n0)
                bad24 += x['last_use'] != (us[-1] if us else None)
                bad24 += not set(us) <= support(Record(r_.e, D25), None, None, start={x['id']})
    print(f'(24l) 400 random append sequences: {acc} uses accepted; transfers accepted by the issuer {cnt["issuer"]}, by an '
          f'authority above {cnt["above"]}; holder refused {cnt["holder_refused"]} / accepted {cnt["holder_accepted"]}; '
          f'the issuer grantee refused {cnt["grantee_refused"]} / accepted {cnt["grantee_accepted"]}; wrong last use refused '
          f'{cnt["wrong_last_refused"]}; a use after an annulled use refused {cnt["reuse_after_annul_refused"]}; violations {bad24}')
    assert acc > 0 and cnt['above'] > 0 and cnt['issuer'] > 0 and cnt['holder_accepted'] == 0 and cnt['grantee_accepted'] == 0
    assert cnt['wrong_last_refused'] > 0 and cnt['reuse_after_annul_refused'] > 0 and bad24 == 0
    # (26) v14 (C21): the reviewer's cases. A1 resurrection by annulling an annulment; A3 two unguarded uses by the owner
    # (marked); A4 a transfer by the issuer's own grantee at the issuer's scope (refused)
    r26 = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A1', single_use=True)], S1)
    o1 = append(r26, dict(adopt('u1', 'A', ['g'], t=1), app='A1'))
    append(r26, annul('n1', 'P', ['r'], 'u1', t=2))
    o2 = append(r26, dict(adopt('u2', 'A', ['g'], t=3), app='A1'))
    r26c = Record([dict(ROOT), dict(G('g', 'P', ['r'], 'A', t=0), owner='A1', single_use=True),
                   dict(adopt('u1', 'A', ['g'], t=1), app='A1'), dict(adopt('u2', 'A', ['g'], t=2), app='A1')], S1)
    D26 = {('s', 's'), ('s', 's/a'), ('s', 's/a/b'), ('s/a', 's/a'), ('s/a', 's/a/b'), ('s/a/b', 's/a/b')}
    r26d = Record([dict(ROOT), G('gP', 'P', ['r'], 'P2', t=0, scope='s/a'),
                   dict(G('g', 'P2', ['gP'], 'A', t=0, scope='s/a/b'), owner='A1', single_use=True),
                   G('gS', 'P2', ['gP'], 'S', t=0, scope='s/a')], D26)
    o4 = append(r26d, {'id': 'm', 'kind': 'transfer', 'actor': 'S', 'cites': ['gS'], 'grant': 'g', 'new_owner': 'A9',
                       'scope': 's/a/b', 't': 1, 'app': KERNEL})
    print('(26) A1: first use', o1[0], '; after it is annulled, a second use:', o2, '| A3 marks:', marks_act(r26c, 'u2'),
          '| A4 the issuer grantee at the issuer scope:', o4)
    assert o1[0] and not o2[0] and any('second use' in m for m in marks_act(r26c, 'u2')) and not o4[0]
    print('all assertions passed')
