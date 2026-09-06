# `KR-ZOOM-02` — **Inquiry-Zoom** (the Nexus formulation)

**Status:** research experiment · experimental / **non-adjudicated**
**Theory v1.2 FROZEN · kernel NOT SELECTED · no carrier declared**

Supersedes the operator in `KR-ZOOM-01`, which was **restriction-zoom (descent)** — see that
experiment's `RESULTS.md` §9 amendment.

## The principle under test

$$\boxed{\text{Inquiry focus} \;\neq\; \text{Knowledge boundary}}$$
$$Zoom(K_t, O_i) = (K_t,\ \mathrm{Focus}=O_i) \qquad\text{— a VIEW, not a deletion}$$

> **Attention is selective; investigation is not necessarily restrictive.**
> *When I focus on a problem, I narrow the question — not the evidence available to solve it.*

Three operations kept separate: **Observe** → **Focus** → **Investigate**, with the invariants
*Focus does not destroy $K_t$* and *Investigation may discover dimensions not present in the
initial focus.*

## The domain

The owner's Nexus example, generated and auditable. 14 dimensions; anomaly `egress_spike` observed
on `network`; ground-truth causal chains that **cross dimensions**
(`network → repositories → ci_cd → config`), plus same-shaped decoys.

## Paired comparison

Both operators see the **identical case** and the **identical exploration RNG**, so any difference
is the operator, not the sample.

| | boundary | on discovering evidence in another dimension |
|---|---|---|
| `RESTRICTION` (KR-ZOOM-01's operator) | the anchor dimension | **refuses to follow** |
| `INQUIRY` (this experiment) | none — $K_t$ preserved | **expands the working set** |

## Reproduce

```bash
python3 code/run02.py     # paired run + Z1..Z5      -> data/ledger02.jsonl, summary02.json
python3 code/run02b.py    # budget sweep + repairs   -> data/sweep02.json
```

Seeds `20260904` / `88020260904`, n = 4000 per split. Python 3 stdlib only.

**Read `RESULTS.md` — five metrics in this experiment are definitional and are labelled there.**
