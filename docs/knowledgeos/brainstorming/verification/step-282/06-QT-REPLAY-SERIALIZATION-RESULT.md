# 06 — `Q_t` Replay and Serialization Result
**`exec/f17_f18_qt.py` → `OUT-F17-F18.txt`** — **10/10 PASS**

| Test | Property | Observed |
|---|---|---|
| F17.1 | replay from genesis | `\|Q\|=3` |
| F17.2 | `Q_t = Replay_Q(Q₀,H_t)` | deterministic |
| F17.3 | repeated replay idempotent | set semantics |
| F17.4 | temporal reconstruction | `Q_at_step2 = 2` |
| F17.5 | duplicate `ask` idempotent | ✓ |
| F17.6 | replay after supersession | supersession lives in `ℛ`, `Q` unaffected |
| F17.7 | `unask` recoverable from History | History retains both events |
| F18.1 | `Deserialize(Serialize(Q))=Q` | 86 bytes, exact |
| F18.2 | serialization deterministic | sorted → order-independent |
| F18.3 | equality after round-trip | stable |

## Classification — the question Step 282 asks
| Candidate | Verdict |
|---|---|
| (1) part of theoretical state | **NO** — `K` is still `(𝒜,ℛ)`; `Q` is not a component |
| (3) externally maintained register | **NO** — it is reconstructed from History |
| **(2) event-derived operational state** | **YES** |

> **`Q_t` is a PROJECTION of History — the same shape as `Σ` being a projection of `e`.**
> It is not a new *kind* of object, which is why Step 281's invariant proof came out 8/8.

## Residual — normative, not formal
`ask(p); unask(p) → Q = ∅`. *"p was once asked"* is unrecoverable **from `Q` alone**, but History retains
both events. **Nothing is lost from the system.** Whether `unask` should exist at all is a **normative**
choice. `Primary: N.`
