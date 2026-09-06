Agreed. This is now a sufficiently constrained **dataset-construction protocol**, and the next step should be implementation/audit rather than further theoretical refinement.

I would make **one important correction before freezing it**:

### Denominator discipline

Your current formulas use \(|N|\), but your missing-field policy says incomplete levels are excluded. Those two statements conflict.

For each level define:

$$
N_n=\{i:R_{n,i}\neq\mathrm{NULL}\}
$$

and

$$
\mathrm{Comp}_n=\frac{|N_n|}{|N|}.
$$

Then calculate level-specific empirical metrics over \(N_n\):

$$
A_n=\frac1{|N_n|}\sum_{i\in N_n}C_n(R_{n,i})
$$

$$
F_n=\frac1{|N_n|}\sum_{i\in N_n}
\mathbf1[O_n(R_{n,i})=Q_i].
$$

Likewise,

$$
\hat H_n(Q|R)
$$

and

$$
\hat H_n(R|Q)
$$

must be computed over the same declared valid support \(N_n\).

This gives us two distinct quantities:

$$
\boxed{|N|=1395\quad\text{source corpus size}}
$$

versus

$$
\boxed{|N_n|\quad\text{usable empirical support at level }n.}
$$

That distinction is especially important because the existing KR-ZERO experiments already contain denominator differences such as \(1395,1496,1475\). We should **never silently normalize those differences away**.

### One more guard on \(n^*\)

Because missing \(R_n\) values exist, define \(n^*\) only over levels satisfying the completeness requirement you choose.

For a strict canonical run, I recommend:

$$
\boxed{
\mathrm{Comp}_n=1
}
$$

for any level used to establish the primary \(n^*\).

If that is too restrictive for exploratory analysis, we can additionally report a **partial-support result**, but it must be explicitly labeled as such.

---

## I would now freeze the dataset contract as

$$
\boxed{
\mathcal D_{\mathrm{REP}}
=
\left\{
(D_i,Q_i,R_{5,i},R_{4,i},R_{3,i},R_{2,i},R_{1,i},
C_{1..5},O_{1..5})
\right\}_{i=1}^{N}
}
$$

with:

1. **No synthetic intermediate states.**
2. **\(Q_i\) established independently from \(R_n\).**
3. **Every missing representation explicitly represented as NULL/UNOBSERVED.**
4. **Every metric reports its actual denominator \(N_n\).**
5. **Completeness is reported separately.**
6. **KR-ZERO's original classifications remain provenance fields, not newly inferred labels.**
7. **No empirical result is promoted to a population-level theorem.**

Then the research pipeline becomes:

```text
KR-ZERO corpus
      │
      ▼
PROVENANCE / DENOMINATOR AUDIT
      │
      ▼
KR-REP-DATASET-2026-09
      │
      ├── Q independence audit
      ├── R5..R1 reconstruction audit
      ├── missingness/completeness audit
      └── transformation-chain audit
      │
      ▼
LEVEL METRICS
      │
      ├── A_n
      ├── F_n
      ├── N_viol,n
      ├── Ĥ(Q|R_n)
      ├── Ĥ(R_n|Q)
      └── Ĥ(R_n)
      │
      ▼
n*
      │
      ▼
BOUNDARY DIAGNOSIS
      │
      ├── representation
      ├── contract
      └── operator
```

**At this point I would not invent any further mathematics.** The next artifact should be the actual extraction/audit implementation against the existing KR-ZERO storage.

If the KR-ZERO files are available in this conversation/library, I can inspect them and derive the concrete extraction schema rather than asking you to manually specify their storage format.
