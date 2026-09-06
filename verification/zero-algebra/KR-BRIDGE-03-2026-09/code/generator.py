r"""KR-BRIDGE-03 — PARAMETRIZED GENERATOR with a controllable REDUNDANCY DISTRIBUTION.

WHY. KR-BRIDGE-02 searched only 31 of 112 cells. The cause was structural, not accidental:

    redundancy r = |D| - |{v}|,  n_rec = 4, 4 uniform values
      r = 0 (9.4%)   all values distinct  -> Zero NEVER fires under dedup   -> (a+b) = 0
      r = 1 (56.2%)  informative
      r = 2 (32.8%)  informative
      r = 3 (1.6%)   all values equal     -> Zero ALWAYS fires              -> (c+d) = 0

    Only TWO strata could contribute. A stratum is informative iff BOTH Zero and
    non-Zero occur in it; the extremes are deterministic and contribute nothing.

TWO INDEPENDENT LEVERS, and they are different things:
  (L1) the REDUNDANCY DISTRIBUTION  -- how case mass is spread over r.
       Flattening it moves mass INTO the informative middle. This is the lever the
       user asked for.
  (L2) the STRATIFICATION VARIABLE  -- redundancy COUNT is coarse. The multiplicity
       PARTITION (e.g. r=2 is either 2+2 or 3+1) is finer, and (partition, |S|) finer
       still. A finer stratum is BOTH a better confounder control AND, because Zero
       varies with WHICH record is eliminated, still informative.

STATISTICAL WARRANT for L1. Redundancy-balanced sampling is a STRATIFIED (case-control
style) design. Within-stratum risk differences are unbiased under it; only MARGINAL
quantities change meaning. Since KR-BRIDGE-02 established that adjudication must be
WITHIN-STRATUM anyway, re-weighting r is legitimate -- but the MARGINAL RD from this
generator is NOT comparable to KR-BRIDGE-01/02 and is not reported as if it were.
"""
import random, collections
from bridge import Case, Rec

def multiplicity_partition(D):
    """The multiplicity SHAPE, e.g. (2,1,1). Finer than the redundancy count."""
    c = collections.Counter(r.v for r in D.recs)
    return tuple(sorted(c.values(), reverse=True))

def redundancy(D):
    return len(D.recs) - len({r.v for r in D.recs})

# ------------------------------------------------------------------ regimes
REGIMES = {
 # name:            n_rec, n_values, n_src, n_tag, mode
 "R0_baseline":       dict(n_rec=4, n_values=4, mode="uniform"),   # == KR-BRIDGE-01/02
 "R1_wider_records":  dict(n_rec=6, n_values=4, mode="uniform"),
 "R2_6x6":            dict(n_rec=6, n_values=6, mode="uniform"),
 "R3_zipf":           dict(n_rec=6, n_values=6, mode="zipf", s=1.0),
 "R4_zipf_wide":      dict(n_rec=6, n_values=8, mode="zipf", s=1.2),
 "R5_balanced_n4":    dict(n_rec=4, n_values=4, mode="redundancy_balanced"),
 "R6_balanced_n5":    dict(n_rec=5, n_values=5, mode="redundancy_balanced"),
 "R7_balanced_n6":    dict(n_rec=6, n_values=6, mode="redundancy_balanced"),
 "R8_balanced_n6v4":  dict(n_rec=6, n_values=4, mode="redundancy_balanced"),
}
SOURCES = ("A", "B", "C"); TAGS = ("p", "q"); TIMES = (0, 1, 2)

def _values(n_values):
    return tuple(10 * (i + 1) for i in range(n_values))

def _zipf_weights(n, s):
    w = [1.0 / ((i + 1) ** s) for i in range(n)]
    tot = sum(w); return [x / tot for x in w]

def make_generator(regime):
    cfg = REGIMES[regime]; n_rec = cfg["n_rec"]; VALS = _values(cfg["n_values"])
    mode = cfg["mode"]

    def one(rng):
        if mode == "uniform":
            vs = [rng.choice(VALS) for _ in range(n_rec)]
        elif mode == "zipf":
            w = _zipf_weights(len(VALS), cfg["s"])
            vs = rng.choices(VALS, weights=w, k=n_rec)
        elif mode == "redundancy_balanced":
            # (1) draw the number of DISTINCT values uniformly over its feasible range
            # (2) draw a case UNIFORMLY CONDITIONAL on that count, by rejection.
            # This flattens the redundancy distribution WITHOUT distorting the
            # within-stratum conditional law -- which is what the analysis uses.
            k = rng.randint(1, min(n_rec, len(VALS)))
            while True:
                vs = [rng.choice(VALS) for _ in range(n_rec)]
                if len({*vs}) == k: break
        else:
            raise ValueError(mode)
        return Case(tuple(Rec(v=v, src=rng.choice(SOURCES), tag=rng.choice(TAGS),
                              t=rng.choice(TIMES)) for v in vs))

    def gen(n, seed):
        rng = random.Random(seed)
        return [one(rng) for _ in range(n)]
    gen.n_rec = n_rec; gen.regime = regime; gen.values = VALS
    return gen
