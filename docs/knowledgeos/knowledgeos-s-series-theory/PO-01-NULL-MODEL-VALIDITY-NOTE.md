# PO-1 Note: Validity of the Model-0 Null Generators in the Frozen Analysis Instrument

| | |
|---|---|
| **Kind** | A mathematical and computational note by Session B (Professor), written for the Researcher and for independent review. **It decides nothing and changes no frozen artifact.** |
| **Status** | DERIVED, **unreviewed**: a same-model analysis. The synthetic computations are development evidence about the instrument, **not evidence about the corpus**. |
| **Date** | 2026-10-17 |
| **Addresses** | Proof obligation PO-1 of the manuscript (Ch. 8.5, 8.10), and the declared limitation "exact validity of the H3 null chain is unproven" (GL-0129 m2; `prereg_v2.py` l.265) |
| **Sources read** | `research/prereg_v1.py` sha256 `eb6291abfe0653ea0a5dcaee8c0dcaf04ab6d47ecf40b3cb960dda103ac1a1f5` (FROZEN, GL-0094; the null generators, l.33–124); `research/prereg_v2.py` sha256 `827ce461634201fa4da3d69bb87890a5bae0f3bd4ac993042a1eb9cf43b1d87d` (FROZEN, GL-0137; how they are called). W branch `s-series/s5-r7-v29-repair`. **No corpus, S5, ledger or hold-out material was read.** |
| **Placement** | A temporary root-level file. If the architecture proposal is approved, it moves to `appendices/app-b-proofs-and-obligations.md` and its scripts to `tools/studies/`. |

---

## 1. Question

Every pre-registered test reports a Monte-Carlo p-value p = (1 + K)/(B + 1), where K = #{b : T_b\* ≥ T} (`prereg_v1.py` l.33–34). The manuscript's Lemma 8.1 shows this p is valid (P(p ≤ u) ≤ u) whenever the observed statistic and the B null statistics are **exchangeable** under H₀. PO-1 asks whether each frozen null generator delivers that exchangeability.

## 2. How the frozen instrument draws its nulls (FACT)

Every null draw is generated **independently from the observed data x₀**: `[t(m0(x0, rng)) for _ in range(B)]` (`prereg_v2.py` l.263, l.314, l.320, l.325; for H5, l.205).

| H | Family (GL-0128…0131) | Null generator (`prereg_v1.py`) | Mechanism |
|---|---|---|---|
| H1 | confirmatory | `m0_h1` l.42–48: uniform shuffle of each sequence's positions ≥ 2 | a uniform random element of a finite permutation group, applied to x₀ |
| H2 | confirmatory | `m0_h2` l.123–124: i.i.d. fair orientation of each relation | a uniform random element of the sign-flip group {0,1}^m (independent of x₀ except through m) |
| H4 | confirmatory | `m0_h4` l.86–92: uniform shuffle of dated positions across kinds, within each object | a uniform random element of a product of permutation groups |
| H3 | exploratory | `m0_h3` l.62–73: 10·\|E\| attempted directed double-edge swaps, **starting at x₀** | a Markov chain run from x₀ |
| H5 | exploratory | `m0_h5` l.105–115: 5·\|R\| curveball trades, **starting at x₀** | a Markov chain run from x₀ |

## 3. Results

### 3.1 H1, H2 and H4 are exact (PO-1 discharged for the whole confirmatory family)

> **Proposition 1** [DERIVED] Let G be a finite group acting on the data space, let H₀ assert that the law of x₀ is G-invariant, and let g₁, …, g_B be i.i.d. uniform on G, independent of x₀. Then (T(x₀), T(g₁x₀), …, T(g_Bx₀)) is exchangeable, and p is valid.
>
> *Proof.*
> 1. *An independent uniform element.* Let g₀ be uniform on G, independent of x₀ and of g₁, …, g_B, and put y := g₀x₀. For each fixed g, gx₀ has the law of x₀ (invariance). So the conditional law of y given g₀ = g does not depend on g. Hence y is independent of g₀, and y has the law of x₀.
> 2. *Rewriting the vector.* Since x₀ = g₀⁻¹y, the vector (x₀, g₁x₀, …, g_Bx₀) equals (h₀y, h₁y, …, h_By), where h₀ := g₀⁻¹ and h_b := g_b g₀⁻¹.
> 3. *The h's are i.i.d. uniform and independent of y.* h₀ is uniform. Given g₀, the h_b (b ≥ 1) are i.i.d. uniform (a right translate of a uniform element is uniform) and independent of h₀. All of them are functions of (g₀, g₁, …, g_B), which are independent of y.
> 4. *Conclusion.* The vector is therefore a coordinatewise function of y and an i.i.d. sequence independent of y, so it is exchangeable. Lemma 8.1 then gives validity. ∎ (This is the standard randomization-test argument, e.g. Hemerik & Goeman, *J. R. Stat. Soc. B*, 2018.)

- **H1**: G = the product over objects of Sym(positions ≥ 2). H₀ is within-object exchangeability of change classes after the first.
- **H4**: G = the product over objects of Sym(dated kinds). H₀ is within-object exchangeability of the dated birth positions.
- **H2**: G = {0,1}^m acting by orientation flips. H₀ is i.i.d. fair orientation, which is G-invariant.

**[DERIVED]** All three confirmatory tests are therefore exactly valid, conditional on the stated null. What each null *means* scientifically (the Model-0 content) is a design question that Freeze 1 settled (S-B1 §3). It is not re-opened here.

### 3.2 H3 and H5 use a "star" MCMC scheme, which is not valid in general

For a chain K that is reversible with respect to π, there are two well-known exact constructions (Besag & Clifford, *Biometrika* 1989):
- the **parallel** method: run n steps from x₀ to x\*, then B independent n-step runs from x\*;
- the **serial** method.

The frozen instrument uses neither. It runs B independent n-step chains **from x₀ itself**, which this note calls the "star" scheme. Under stationarity each pair (x₀, X_b) is exchangeable, but the full vector (x₀, X₁, …, X_B) is not: x₀ is the common ancestor of all the draws.

> **Proposition 2** [DERIVED; computed] The star scheme does not, in general, yield a valid p-value at finite n.
>
> *Counterexample.* Take a symmetric (hence uniform-stationary), irreducible, aperiodic 3-state chain on {a, b, c} with T = (0, 1, 2) and δ = 0.01:
> - from a: stay with probability 1−δ/2, move to b with probability δ/2;
> - from b: move to a with probability δ/2, stay with probability δ/2, move to c with probability 1−δ;
> - from c: move to b with probability 1−δ, stay with probability δ.
>
> Let x₀ be uniform, n = 1 and B = 99. The exact size is P(p ≤ 0.01) = **0.123** and P(p ≤ 0.05) = **0.332** (script `star.py`, §5). The mechanism: from the state with maximal T, one step almost surely lowers T, so K is almost always 0 and p sits at its minimum. ∎

**[EDITORIAL]** Proposition 2 concerns the *scheme*. It shows that validity of the H3 and H5 tests cannot be inferred from the stationarity of their chains alone. It depends on how fast the chains mix relative to n = 10·|E| (H3) or 5·|R| (H5).

### 3.3 The H3 swap chain is reducible, but the T_h3 null is unaffected on small graphs

- **[DERIVED; computed, exhaustive]** Under the frozen move of `m0_h3`, I enumerated every simple digraph on n = 3 and n = 4 nodes and grouped them by (out-degree, in-degree) sequence:
  - n = 3: 1 of 63 degree classes is **disconnected** (the directed 3-cycle and its reversal: every swap needs 4 distinct nodes);
  - n = 4: 16 of 2,656 classes are disconnected.

  This agrees with the known result that directed double-edge swaps need an additional triangle-reorientation move to connect the space (Rao, Jana & Bandyopadhyay, *Sankhyā A*, 1996).
  - Consequence: the H3 null is at best uniform on the **component reachable from x₀**, not on the full degree class named by the competing model ("a random graph with the same degrees", S-B1 §3).
- **[DERIVED; computed, exhaustive]** In every disconnected class at n ≤ 4, the components have **identical distributions** of T_h3 (2-cycles + 3-cycles). Reversing a directed 3-cycle preserves both counts in these cases.
- **[OPEN]** Whether this holds for all n. A proof would need to show that triangle reorientation preserves the number of 2- and 3-cycles together, which is plausible but not shown here. Until then, reducibility is a recorded caveat: the conditional (component) null is what H3 actually tests.

### 3.4 Synthetic size of the frozen H3 and H5 tests

**[DERIVED; computed, synthetic]** I called the frozen functions themselves (`prereg_v1.py`, loaded by path) with B = 99 and R = 300 replications. Under H₀, x₀ was drawn from the chain's own stationary law by a long burn-in (200·|E| swaps or 200·|R| trades) from a random start. Values are the estimated P(p ≤ α) ± 1.96·SE:

| Test | Regime | α = 0.01 | α = 0.05 | α = 0.10 |
|---|---|---|---|---|
| H3 | sparse random digraph, 30 nodes, 45 arcs | 0.003 (±0.007) | 0.030 (±0.019) | 0.080 (±0.031) |
| H3 | hub-heavy digraph, 30 nodes, 45 arcs | 0.007 (±0.009) | 0.027 (±0.018) | 0.063 (±0.028) |
| H5 (exact implications) | 40 rows × 12 dimensions, column densities 0.9…0.05 | 0.003 (±0.007) | 0.017 (±0.014) | 0.060 (±0.027) |

**Reading.** In these regimes the frozen tests are **at or below nominal size (conservative)**. There is no sign of the anti-conservatism that Proposition 2 makes possible.

**Limits of this evidence:**
1. the regimes are mine, not the corpus's (the real graph and matrix sizes are unknown before S5);
2. R = 300 gives wide intervals;
3. burn-in only approximates stationarity;
4. H5's Loevinger statistic and the v2 min-p combination were not simulated;
5. a reviewer of a different model family has not checked this.

## 4. Conclusions for PO-1

| Item | Conclusion | Standing |
|---|---|---|
| H1, H2, H4 (the whole confirmatory family) | **exactly valid** under their stated nulls (Prop. 1) | DERIVED; needs independent check |
| H3, H5 (exploratory) | not exact by construction (Prop. 2); conservative in three synthetic regimes (§3.4) | DERIVED + synthetic evidence; general validity remains a **declared limitation** |
| H3 null support | the reachable component, not the full degree class (§3.3); no effect on the T_h3 distribution for n ≤ 4 | DERIVED; general n OPEN |

**Consequence for the error-rate claims.** Prop. 8.3 of the manuscript (Holm under R3) needs valid confirmatory p-values. Given Proposition 1, its premise holds for the frozen confirmatory family. The exploratory BH results inherit the H3/H5 caveat, and they are never confirmatory (S-B1 §5).

## 5. What this note recommends (to the Researcher; decides nothing)

1. **Record, don't patch.** prereg_v2 is frozen. "Any change … needs a new version, independent review and a new human act, before any S5 data" (GL-0137). The note proposes no change to the frozen instrument.
2. **If a new version is ever opened for other reasons**, the Besag–Clifford parallel scheme would make H3 and H5 exact without new assumptions. Both chains are symmetric proposals, so a backward run equals a forward run. The cost is one extra n-step run per test.
3. **Optional pre-data study** (synthetic, needs no corpus, but needs an authorization like the V-5/V-8/V-9 studies): repeat §3.4 at sizes matched to the frame's expected scale, with R ≥ 2,000. Include the H5 Loevinger and min-p statistics, and graph regimes with many 2- and 3-cycles.
4. **Update the declared limitation text.** At the next documented revision of the limitations, "exact validity of the H3 null chain is unproven" could read: "H3/H5 nulls use a star MCMC scheme, not exact in general (PO-1 note Prop. 2); conservative in synthetic checks; H3 null restricted to the swap-reachable component."

## 6. Reproduction

The scripts were run with `python3 -I` in Session B's scratchpad on 2026-10-17. They read **only** the frozen `prereg_v1.py` (passed as an argument) and generate synthetic data. They are reproduced below exactly as run; SHA-256 of the files as run:

| Script | sha256 | Output (verbatim) |
|---|---|---|
| `exhaust3.py` (reachability, distribution check) | `9c81968e8a5fca9d583fcf7512eaa71ec67345ee55b1de1762c56a5d9c75b1b3` | `n=3: degree classes 63, disconnected under swap 1, of which components differ in the T_h3 DISTRIBUTION 0` · `n=4: degree classes 2656, disconnected under swap 16, of which components differ in the T_h3 DISTRIBUTION 0` |
| `size.py` (synthetic size; args: path to `prereg_v1.py`, R = 300) | `37c3c3ea48df0ede532bf561296444925b861725a184b05df476af92121c2043` | the table in §3.4 |
| `star.py` (counterexample) | `55274339713e592a79e404e3efa484eb0e0c8a3409eb388bba3b4afa0dc22beb` | `n=1 steps: P(p<=0.01) = 0.123` · `n=1 steps: P(p<=0.05) = 0.332` |

`exhaust3.py` is `exhaust2.py` (sha `e67d3d08…`) with the per-component comparison changed from the *set* of T values to the *distribution* of T values. Both versions report 0 differing components.

<details><summary>exhaust3.py</summary>

```python
import collections
import itertools, random, sys
def move_neighbours(E):
    E=frozenset(E); out=set()
    for (a,b),(c,d) in itertools.permutations(E,2):
        if len({a,b,c,d})<4 or (a,d) in E or (c,b) in E: continue
        out.add(frozenset(E-{(a,b),(c,d)}|{(a,d),(c,b)}))
    return out
def t_h3(E):
    E=set(E); two=sum(1 for a,b in E if (b,a) in E)//2
    three=sum(1 for a,b in E for c in [y for x,y in E if x==b] if (c,a) in E)//3
    return two+three
for n in (3,4):
    pairs=[(a,b) for a in range(n) for b in range(n) if a!=b]
    classes=collections.defaultdict(list)
    for mask in range(1<<len(pairs)):
        E=frozenset(p for i,p in enumerate(pairs) if mask>>i&1)
        key=(tuple(sum(1 for a,b in E if a==v) for v in range(n)),tuple(sum(1 for a,b in E if b==v) for v in range(n)))
        classes[key].append(E)
    disc=0; tdiff=0; ex=None
    for key,members in classes.items():
        ms=set(members); seen=set(); comps=[]
        for x in members:
            if x in seen: continue
            comp={x}; fr=[x]
            while fr:
                y=fr.pop()
                for z in move_neighbours(y):
                    if z not in comp: comp.add(z); fr.append(z)
            seen|=comp; comps.append(comp)
        if len(comps)>1:
            disc+=1
            Ts=[tuple(sorted(collections.Counter(t_h3(z) for z in c).items(), key=lambda kv: kv[0])) for c in comps]; Ts=[tuple((k, v/sum(x for _,x in t)) for k,v in t) for t in Ts]
            if len({tuple(t) for t in Ts})>1:
                tdiff+=1; ex=ex or (key,[sorted(c,key=sorted)[0] for c in comps],Ts)
    print(f"n={n}: degree classes {len(classes)}, disconnected under swap {disc}, of which components differ in the T_h3 DISTRIBUTION {tdiff}")
    if ex: print("  example", ex[0], [sorted(e) for e in ex[1]], ex[2])
```
</details>

<details><summary>size.py</summary>

```python
# Synthetic size study of the frozen H3 and H5 Monte-Carlo tests (star scheme: every null draw restarts from x0).
# H0 data: x0 drawn from the null chain's own stationary law by a long burn-in. No corpus data is used.
import importlib.util, random, math, sys, time
spec=importlib.util.spec_from_file_location("V1", sys.argv[1]); V1=importlib.util.module_from_spec(spec); spec.loader.exec_module(V1)
B=99; R=int(sys.argv[2])
def ci(k,n):
    p=k/n; h=1.96*math.sqrt(p*(1-p)/n) if 0<k<n else 3/n; return f"{p:.3f} (±{h:.3f})"
def study(name, make, stat, null, burn):
    rng=random.Random(20261017); ps=[]
    t0=time.time()
    for r in range(R):
        x0=burn(make(rng),rng)
        t=stat(x0); ps.append(V1.perm_p(t,[stat(null(x0,rng)) for _ in range(B)]))
    out=[f"P(p<={a})={ci(sum(p<=a+1e-12 for p in ps),R)}" for a in (0.01,0.05,0.10)]
    print(name, *out, f"[{time.time()-t0:.0f}s]")
# H3: random sparse digraph, and a hub-heavy digraph
def mk_sparse(rng,n=30,m=45):
    pairs=[(a,b) for a in range(n) for b in range(n) if a!=b]; return rng.sample(pairs,m)
def mk_hub(rng,n=30,m=45):
    E=set()
    while len(E)<m:
        a=rng.choice([0,0,0,1,1]+list(range(n))); b=rng.choice([2,2,3]+list(range(n)))
        if a!=b: E.add((a,b))
    return list(E)
h3burn=lambda E,rng: V1.m0_h3(E,rng,swaps=200*len(E))
for nm,mk in (("H3 sparse",mk_sparse),("H3 hub",mk_hub)):
    study(nm, mk, V1.t_h3, V1.m0_h3, h3burn)
# H5: 40 rows x 12 dims, heterogeneous column densities
D=[f"d{i}" for i in range(12)]
def mk_rows(rng,n=40):
    dens=[0.9,0.8,0.7,0.6,0.5,0.4,0.3,0.25,0.2,0.15,0.1,0.05]
    return [{d for d,q in zip(D,dens) if rng.random()<q} for _ in range(n)]
h5burn=lambda R_,rng: V1.m0_h5(R_,rng,steps=200*len(R_))
study("H5 exact-implication", mk_rows, lambda rows: len(V1.implications(rows,D)), V1.m0_h5, h5burn)
```
</details>

<details><summary>star.py</summary>

```python
# Exact size of a star-scheme MC test on a 3-state reversible lazy chain (constructed example, not the instrument).
from math import comb
d=0.01; B=99; n=1
K={'a':{'a':1-d/2,'b':d/2,'c':0},'b':{'a':d/2,'b':d/2,'c':1-d},'c':{'a':0,'b':1-d,'c':d}}
T={'a':0,'b':1,'c':2}; pi={s:1/3 for s in T}
assert all(abs(K[x][y]-K[y][x])<1e-15 for x in T for y in T)   # symmetric => uniform stationary
def step(dist):
    out={s:0.0 for s in T}
    for x,px in dist.items():
        for y,q in K[x].items(): out[y]+=px*q
    return out
for u in (0.01,0.05):
    tot=0
    for x0 in T:
        dist={s:float(s==x0) for s in T}
        for _ in range(n): dist=step(dist)
        g=sum(p for s,p in dist.items() if T[s]>=T[x0])
        kmax=int(u*(B+1)-1+1e-9)
        tot+=pi[x0]*sum(comb(B,k)*g**k*(1-g)**(B-k) for k in range(kmax+1))
    print(f"n={n} steps: P(p<={u}) = {tot:.3f}  (valid test needs <= {u})")
```
</details>
