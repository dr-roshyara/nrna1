# PROTOCOL — workerC, O-10i2 / O-10i3 / O-10g1 / O-10g2 finite checks

Status: frozen before any code is written. Synthetic only. No repository data.
All checks guard hand proofs; they are not independent evidence for the proofs
(same author). Any failure is reported as found; this file is never edited after
results; a corrected protocol would be a new file PROTOCOL-v2.md.

## Common model
- L = Sigma^{<=n} (all strings), Sigma = {0..s-1}. h_u = length-u prefix.
- Bitemporal decision D(h_u, t), t <= u, values in V = {0..m-1}.
- Full bitemporal entry at u: vec(h_u) = (D(h_u,t))_{t<=u}. Full kernel K_full:
  h ~ h' iff |h|=|h'| and vec(h_u)=vec(h'_u) for all u<=|h|.
- Horizon k (property): D(h_u,t) = D(h_{t+k},t) whenever u > t+k.
  Horizon policy: D^k(h_u,t) := D(h_{min(u,t+k)}, t).
- Window entry W_k(h_u) = (D(h_u,t))_{max(0,u-k)<=t<=u}; window kernel K_win.
- A PC kernel is represented by a record function rec(h) = tuple of entries
  (class-log). Class of h = rec(h). Child classes of a class C at level t =
  { rec(h+a) : rec(h)=C, a in Sigma }.
- Encoding schemes (named; never mixed):
  * NODE: per-node integer cost; cost(h) = sum_{t<|h|} ceil(log2 c([h_t])),
    c = number of child classes.
  * GW: one global fixed width W = ceil(log2 max_C c(C)) over all nodes at levels < n.
  * FL-plain: full bitemporal entry at u, (u+1)*b bits, b = ceil(log2 m).
  * FW-cf-full / FW-cf-diff: context-free fixed width per position u,
    = ceil(log2 #possible entries); full: m^(u+1); diff: m*(m+1)^u
    (changed set C subset {0..u-1}, a value in V for each changed t, plus new value).
  * VL-list: diff entry = count field ceil(log2(u+1)) bits + per changed t:
    absolute index ceil(log2 u) bits + value b bits, + new value b bits.
    (ceil(log2 1)=0.)

## Checks, expected results, failure criteria
K1 (guards Thm H1). s=2, m=2, n=4; for k in {0,1,2}: 300 random D satisfying
  horizon k (random values where u-t<=k, frozen otherwise). Expect K_win == K_full
  in 300/300 per k. Power control: truncated window of size k (drop t=u-k)
  for k>=1: expect at least one mismatch per k (report count). Failure: any
  K_win != K_full.
K2 (guards Prop H3). s=2, m=2, n=4; 300 random unrestricted D. For 0<=k<k'<=4:
  K_full(D^{k'}) subset K_full(D^k). Also K_full(D^4) == K_full(D) (n=4).
  Expect 0 violations. Failure: any violation.
K3 (EXPLORATORY, no expected value). Same population as K2: count pairs (k,k+1)
  where reducing horizon k+1 -> k increases (a) GW width, (b) max over histories
  of NODE cost, (c) NODE cost for some history; and where it increases
  #classes at length n (expected 0, since coarsening; failure if >0).
K4 (guards Thm G2-lower constructions). For s=2, K = strings without factor "11";
  for s=3, K = strings over {0,2} (no letter 1); n=1..9 (s=2), n=1..6 (s=3).
  pi'_K: at each level t, K_t is one class, every other string a singleton.
  Brute-force NODE cost along h=0^n must equal
  sum_{t<n} ceil(log2(s|K_t| - |K_{t+1}| + 1)); identity NODE cost = n*ceil(log2 s).
  GW example: pi' total on levels <n, identity on level n: GW = ceil(n log2 s),
  identity GW = ceil(log2 s). Failure: any mismatch.
K5 (guards upper bounds). s=2, n=4 (and s=3, n=3): 300 random pairs pi subset pi'
  built as kernels of online records: pi' = kernel of record with random entries
  rho'(h,a) in {0..r-1} (r random in {1,2,3}); pi = kernel of joint (rho', rho'').
  Check for all h of length n: (i) Prop0 sanity pi subset pi'; (ii)
  NODE_pi'(h) <= sum_{t<n} ceil((t+1) log2 s); (iii) sum_t c'_t(h) <= sum_{t=1..n} N_t(pi');
  (iv) sum_t (c'_t(h)-1) + 1 <= N_n(pi') (L has no dead ends);
  (v) GW(pi') <= ceil(log2 max_{t<n} N_{t+1}(pi)); (vi) c'(C') <= sum of c(C) over
  pi-classes C inside C'. Expect 0 violations. Failure: any violation.
K6 (guards O-10g1 formulas). (a) all-flip D(h_u,t) = (u+t) mod 2, n=1..8:
  VL-list entry at u (u>=1) = ceil(log2(u+1)) + u(ceil(log2 u)+1) + 1 > FL-plain u+1.
  (b) enumerate context-free diff entry sets for m in {2,3}, u in {0..4}: count
  == m(m+1)^u; FW-cf-diff >= FW-cf-full always; report positions where equal.
  (c) 300 random D (s=2,m=2,n=5): VL-list per entry within
  [ceil(log2(u+1))+b, ceil(log2(u+1))+u(ceil(log2 u)+b)+b]; report how many entries
  have VL-list > FL-plain and how many <. (d) node-level bijection: for random D,
  the number of distinct full entries and of distinct diff entries following each
  node of K_full are equal. Expect 0 violations. Failure: any violation.

Seeds: random.Random(20261018 + check index). Python 3, run with `python3 -I -B`.
