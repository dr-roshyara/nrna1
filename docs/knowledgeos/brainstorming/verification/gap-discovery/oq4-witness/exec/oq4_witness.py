r"""OQ-4 — the existential witness for  Adequacy != Realization.

TARGET (Theory 13 OQ-4, Theory 05 §3 [REC]):  exhibit a level with
        Hhat(Q|R) = 0   AND   F < 1
"Requires either a decoder deliberately weaker than the information, or a
 constraint that bites at an adequate level.  Neither is hard; neither was done."

Both routes are DECLARED HERE, BEFORE the run, and both are executed.  Nothing is
tuned after seeing a result.  This decides nothing about the carrier question.

REUSE: carrier.py / pipeline.py / metrics.py of KR-REP-REDUCTION-2026-09, imported
unmodified.  Same generator, same seeds, same N_CASES as the published run.
"""
import os, sys, json, random, collections

CODE = "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/verification/zero-algebra/KR-REP-REDUCTION-2026-09/code"
sys.path.insert(0, CODE)
from carrier import Case, Rec, Q, V, SOURCES, TIMES, N_REC, decile   # noqa: E402
from pipeline import build_chain, C, O, LEVEL_FIELDS                  # noqa: E402
from metrics import H_hat_cond, N_viol, fiber_stats                   # noqa: E402

N_CASES    = 40000
SEED_TRAIN = 20260903          # identical to run.py
CONV       = "ascending"       # the published primary convention
LEVEL      = "R5"              # the ONLY adequate level in the published run
K_ANON     = 2                 # declared before the run

def gen(n, seed):
    rng = random.Random(seed)
    return [Case(tuple(Rec(v=rng.choice(V), s=rng.choice(SOURCES), t=rng.choice(TIMES))
                       for _ in range(N_REC))) for _ in range(n)]

def rep_key(case):                       # R5 schema: {v, s}
    return tuple((r.v, r.s) for r in case.recs)

# ---------------------------------------------------------------- ROUTE 1 decoder
def O_weak(R):
    """DECLARED, FIXED IN ADVANCE, deliberately weaker than the information.

    The published O takes an ARGMAX over the numeric field.  O_weak instead reads the
    FIRST record's source.  It reads only R, is fixed before any result is seen, and
    is strictly weaker: it cannot see which record is largest.  Its decile component
    is unchanged, so the two decoders differ on EXACTLY the argmax component."""
    nums = R.numeric()
    return (R.recs[0].s, decile(sum(nums)))

# ================================================================ run
print("=" * 78); print("OQ-4 WITNESS SEARCH   Adequacy != Realization"); print("=" * 78)
print(f"  carrier          Rec(v, s, t, rank) / Case(recs)  -- KR-REP-REDUCTION-2026-09")
print(f"  representation   {LEVEL}   fields {sorted(LEVEL_FIELDS[LEVEL])}")
print(f"  chain            D -> R5=T5(D) -> R4 -> R3 -> R2   (rank_convention={CONV})")
print(f"  inquiry Q        (argmax_source(D), decile(sum v))")
print(f"  N_CASES          {N_CASES}      seed {SEED_TRAIN}")
print(f"  k-anonymity      k = {K_ANON}   (declared before the run)")

cases  = gen(N_CASES, SEED_TRAIN)
chains = [build_chain(D, CONV) for D in cases]
qs     = [Q(D) for D in cases]
reps   = [rep_key(ch[LEVEL]) for ch in chains]
srcs   = [{r.s for r in D.recs} for D in cases]

# ---- adequacy at the level (independent of O and of C) -------------------------
hq = H_hat_cond(qs, reps); nv = N_viol(reps, qs); fs = fiber_stats(reps, qs)
adequate = (nv == 0)
print(); print("-" * 78); print(f"1. ADEQUACY at {LEVEL}  (a property of (T, Q, population) -- not of O, not of C)")
print("-" * 78)
print(f"  Hhat(Q|R)   = {hq:.5f}")
print(f"  N_viol      = {nv}          <- exact, bias-free; authoritative for 'exactly zero'")
print(f"  adequate    = {adequate}")
print(f"  fibers      = {fs['n_fibers']}   singleton {fs['singleton_fibers']}   "
      f"max {fs['max_fiber']}   conflicted {fs['conflicted_fibers']}")

# ---- control: the published decoder -------------------------------------------
dec_pub = [O(ch[LEVEL]) for ch in chains]
adm_pub = [C(ch[LEVEL], LEVEL, s) for ch, s in zip(chains, srcs)]
F_pub = sum(1 for a, d, q in zip(adm_pub, dec_pub, qs) if a and d == q) / N_CASES
print(); print("-" * 78); print("2. CONTROL -- the published frame  (Q, C, O)"); print("-" * 78)
print(f"  A_n = {sum(adm_pub)/N_CASES:.5f}      F_n = {F_pub:.5f}")
print(f"  published run reported F_n = 1.0 at R5  ->  "
      f"{'REPRODUCED' if abs(F_pub-1.0) < 1e-9 else '*** MISMATCH ***'}")
print("  => adequate AND F=1: the published design excluded its own target by construction.")

# ---- ROUTE 1: weaker decoder ---------------------------------------------------
dec_w = [O_weak(ch[LEVEL]) for ch in chains]
F_w   = sum(1 for a, d, q in zip(adm_pub, dec_w, qs) if a and d == q) / N_CASES
F_w_src = sum(1 for d, q in zip(dec_w, qs) if d[0] == q[0]) / N_CASES
F_w_dec = sum(1 for d, q in zip(dec_w, qs) if d[1] == q[1]) / N_CASES
r1 = adequate and F_w < 1.0
print(); print("-" * 78); print("3. ROUTE 1 -- frame (Q, C, O_weak):  a decoder weaker than the information")
print("-" * 78)
print(f"  F_n                = {F_w:.5f}")
print(f"  argmax component   = {F_w_src:.5f}     <- where O_weak is weaker")
print(f"  decile component   = {F_w_dec:.5f}     <- unchanged by construction")
print(f"  Hhat(Q|R) = {hq:.5f}  AND  F = {F_w:.5f} < 1   ->  "
      f"{'WITNESS FOUND' if r1 else 'no witness'}")

# ---- ROUTE 2: a constraint that bites at an adequate level ---------------------
fib = collections.Counter(reps)
adm_k = [1 if (a and fib[r] >= K_ANON) else 0 for a, r in zip(adm_pub, reps)]
F_k   = sum(1 for a, d, q in zip(adm_k, dec_pub, qs) if a and d == q) / N_CASES
blocked = N_CASES - sum(adm_k)
r2 = adequate and F_k < 1.0
print(); print("-" * 78); print(f"4. ROUTE 2 -- frame (Q, C and k-anonymity(k={K_ANON}), O):  a constraint that bites")
print("-" * 78)
print(f"  A_n (with k-anon)  = {sum(adm_k)/N_CASES:.5f}")
print(f"  cases blocked      = {blocked}   ({100*blocked/N_CASES:.2f} %)")
print(f"  F_n                = {F_k:.5f}")
print(f"  Hhat(Q|R) = {hq:.5f}  AND  F = {F_k:.5f} < 1   ->  "
      f"{'WITNESS FOUND' if r2 else 'no witness'}")
print()
print("  ** the tension, measured: a SINGLETON fiber is maximally adequate (its Q is")
print("     determined) and maximally re-identifying (k-anonymity forbids decoding it).")
print(f"     singleton fibers = {fs['singleton_fibers']} of {fs['n_fibers']}")

# ---- verdict -------------------------------------------------------------------
print(); print("=" * 78); print("VERDICT"); print("=" * 78)
for name, ok, F in (("route 1  weaker decoder", r1, F_w), ("route 2  binding constraint", r2, F_k)):
    print(f"  {name:30s} {'WITNESS' if ok else 'none':8s}  Hhat(Q|R)={hq:.5f}  F={F:.5f}")
print(f"\n  Adequacy != Realization  ->  "
      f"{'EXHIBITED' if (r1 or r2) else 'NOT EXHIBITED under the declared search space'}")
print("  SCOPE: this carrier, this chain, this Q, level R5, N=40000, seed 20260903,")
print("         rank_convention=ascending.  Nothing outside that scope is claimed.")

out = {"carrier": "Rec(v,s,t,rank) / Case(recs) -- KR-REP-REDUCTION-2026-09",
       "level": LEVEL, "level_fields": sorted(LEVEL_FIELDS[LEVEL]),
       "chain": "D->R5->R4->R3->R2", "rank_convention": CONV,
       "Q": "(argmax_source(D), decile(sum v))",
       "n_cases": N_CASES, "seed": SEED_TRAIN, "k_anon": K_ANON,
       "adequacy": {"H_hat_Q_given_R": round(hq, 5), "N_viol": nv, "adequate": adequate, **fs},
       "control_published_frame": {"F_n": round(F_pub, 5)},
       "route1_weak_decoder": {"O_weak": "first record's source; decile unchanged",
                               "F_n": round(F_w, 5), "F_argmax": round(F_w_src, 5),
                               "F_decile": round(F_w_dec, 5), "witness": r1},
       "route2_k_anonymity": {"k": K_ANON, "blocked_cases": blocked,
                              "F_n": round(F_k, 5), "witness": r2},
       "verdict": "EXHIBITED" if (r1 or r2) else "NOT EXHIBITED"}
here = os.path.dirname(os.path.abspath(__file__))
json.dump(out, open(os.path.join(here, "oq4_witness.json"), "w"), indent=2)
print(f"\n  wrote {os.path.join(here, 'oq4_witness.json')}")
