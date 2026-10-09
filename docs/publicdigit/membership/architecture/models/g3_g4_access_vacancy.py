"""G-3 / G-4 bounded model: service access and vacancy over a tenure with a membership
eligibility rule, under Membership corrections.

Domain: months t in [0,T), T=6. Membership: Admit@0, optional End@e, optional later
annulment of that End recorded at r>e. One position s with eps(s) = "holder must be a
member". One tenure of person p in s: [x, y) with y=None open, opened by appointment.
Business-rule variants for G-4 (BQ-P3, open): LAPSE (ineligibility ends the tenure
legally) vs SUSPEND (tenure held, ineffective while ineligible). Both are DERIVED here.

Compared, at final knowledge, for every month t:
  DERIVED access(t)  = Held(t) and eps(t)            (projection; recomputable)
  STORED ACL(t)      = grant written at tenure start if member-as-known, revoked on tenure
                       end and on a membership End event as known; no handler for annulment
                       (the event-handler shape of a forum ACL).
  vacancy: legal(t)   = not Held(t);  functional(t) = not (Held(t) and eps(t)).
"""
T = 6
def member_final(e, annulled):
    return lambda t: (e is None or annulled or t < e)

stats = dict(cases=0, acl_diverges=0, acl_months_wrong=0, legal_ne_functional_cases=0,
             lapse_vs_suspend_differ=0)
for x in range(T):
    for y in [None] + list(range(x + 1, T + 1)):
        held = lambda t, x=x, y=y: x <= t and (y is None or t < y)
        for e in [None] + list(range(1, T)):
            for r in ([None] + list(range(e + 1, T)) if e is not None else [None]):
                stats['cases'] += 1
                m = member_final(e, r is not None)
                derived = [held(t) and m(t) for t in range(T)]
                # STORED ACL as a replay of events in time order
                acl = []; on = False
                for t in range(T):
                    known_member = (e is None or t < e or (r is not None and r <= t))
                    if t == x and known_member: on = True          # grant at tenure start
                    if y is not None and t == y: on = False          # tenure end revokes
                    if e is not None and t == e: on = False          # membership End revokes
                    # annulment recorded at r: no handler -> nothing re-granted
                    acl.append(on and held(t))
                wrong = sum(1 for a, d in zip(acl, derived) if a != d)
                if wrong:
                    stats['acl_diverges'] += 1; stats['acl_months_wrong'] += wrong
                legal = [not held(t) for t in range(T)]
                functional = [not d for d in derived]
                if legal != functional: stats['legal_ne_functional_cases'] += 1
                # LAPSE: tenure legally ends at first ineligible month; it never revives
                lapse = []; ended = False
                for t in range(T):
                    if held(t) and not m(t): ended = True
                    lapse.append(held(t) and not ended and m(t))
                if lapse != derived: stats['lapse_vs_suspend_differ'] += 1
print(stats)
