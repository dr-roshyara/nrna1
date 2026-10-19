from cases_m1 import iss, act, ev
# x adopts c at t=5 under single-use g. Later-recorded x2 adopts the SAME claim c at t=3 (earlier act time)
# with validity [3,4): x2 consumes g before x, so x is not validly made, and x2's own disjunct has expired by t=6.
base = [iss('g', 0, 1, single_use=True), act('x', 'adopt', 'c', 5, 2, cited=[['g']])]
plus = base + [act('x2', 'adopt', 'c', 3, 3, cited=[['g']], val=(3, 4))]
for rho in ('now', 'at-the-time'):
    print(f'rho={rho}: before adding x2 -> {ev(base, "c", 6, rho=rho)}; after adding a further adoption of c -> {ev(plus, "c", 6, rho=rho)}')
