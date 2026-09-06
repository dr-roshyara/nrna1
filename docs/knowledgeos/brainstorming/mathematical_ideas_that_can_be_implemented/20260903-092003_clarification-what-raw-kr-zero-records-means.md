By **“raw KR-ZERO records”** I mean the **actual case-level data produced by the KR-ZERO experiments**, not a theoretical dataset that we need to invent.

And there is an important distinction: **we have discussed the KR-ZERO results and structure, but in the material currently visible to me I do not have the actual case-level 1,395-record dataset itself.**

What I mean by a raw record is something approximately like:

```text
case_0001
    D              = original source/domain object
    S              = subset tested for elimination
    T              = transformation used
    Π              = preservation contract
    Zero(S; D)      = true/false
    k               = observed interaction order
    transformation = T2 / T4 / T8 / ...
    representation  = actual representation used
    context         = ...
```

The exact fields depend on what the KR-ZERO experiment actually persisted.

### What we currently know about the records

From the KR-ZERO results we have discussed, the corpus contains cases from the experiment asking essentially:

> Given Zero-status information for proper subsets up to order \(k\), can Zero-status of a larger subset be determined?

The reported population included:

| Result      |    Cases | Percentage |
| ----------- | -------: | ---------: |
| \(k=1\)     |     1252 |      89.7% |
| \(k=2\)     |       25 |       1.8% |
| \(k=3\)     |        3 |       0.2% |
| Irreducible |      115 |      8.24% |
| **Total**   | **1395** |   **100%** |

There were also separate populations/results such as the O2/O4/O6 analyses, which is why we have encountered \(1496\) and \(1475\) as well.

### What we **don't** yet know

We don't currently have evidence in the visible material that every one of those 1,395 cases contains:

```text
D
Q(D)
R5
R4
R3
R2
R1
C(Rn)
O(Rn)
```

In fact, the previous analysis strongly suggests that **they probably don't**.

That's why I used the phrase *raw KR-ZERO records*: I was referring to the underlying case-level experiment artifacts from which we would determine whether the new representation dataset can actually be constructed.

---

## This also exposes an important issue in our previous discussion

We should **not assume that such a raw dataset exists as a single CSV/JSON file**.

The KR-ZERO experiment may instead consist of several artifacts:

```text
KR-ZERO-ORDER-2026-09/
├── experiment definition
├── generated cases
├── Zero-status results
├── witnesses
├── transformation metadata
├── contract metadata
├── summary/results
└── README
```

If so, **those artifacts collectively constitute the raw corpus**.

And if the underlying cases were never persisted—only the aggregate results were saved—then we cannot reconstruct the 1,395 cases without fabrication.

That would itself be an important audit finding:

$$
\boxed{
\text{aggregate KR-ZERO results}
\neq
\text{case-level KR-REP dataset}
}
$$

---

### Therefore, I would correct our terminology

Instead of saying:

> “Give me the raw KR-ZERO records.”

we should say:

> **Locate and inspect the original case-generating artifacts and case-level outputs underlying `KR-ZERO-ORDER-2026-09`. Determine whether a reconstructible case-level corpus exists.**

Then we can answer the crucial question:

$$
\boxed{
\text{Can the existing KR-ZERO experiment actually support }
KR\text{-REP-DATASET-2026-09?
}
$$

If yes, we build the adapter.

If no, **we stop** and document exactly which information was never recorded. That is much better scientifically than pretending the 1,395 aggregate cases contain \(R_5\ldots R_1\).

If you have the KR-ZERO experiment files in the KnowledgeOS repository, **the next thing I would do is inspect those actual files and tell you exactly what the “records” are, rather than continuing to hypothesize about their schema.**
