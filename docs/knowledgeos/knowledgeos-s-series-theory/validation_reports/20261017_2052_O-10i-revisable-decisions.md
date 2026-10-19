# O-10i: decisions revised after recording (bitemporal model)

| Field | Value |
|---|---|
| Author / clock | Session B, 2026-10-17 20:52 |
| Protocol / code / results | as in the O-10f report (`o10fig/PROTOCOL.md` `24b93f08…83a7`; `o10fig_checks.py` `cb61e3b7…297f`; `RESULTS.txt`) |
| Review | INTERNALLY REVIEWED — SAME MODEL FAMILY (refute first); corrections integrated |

## 1. Model

**D(h_u, t)**, for t ≤ u, is the decision **for time t** as the system holds it **at time u**. It gives four distinct objects:

| Object | Definition |
|---|---|
| **The historical decision** | D(h_t, t): what was decided at the time |
| **A correction** at u | D(h_u, t) ≠ D(h_{u−1}, t), for some t < u |
| **The current authoritative decision** for t | D(h_\|h\|, t) |
| **A superseding decision** | a correction whose new value replaces the authority of the old one for t; the old value **stays** in the history |

Evidence and rule versions enter as input letters: late evidence, corrected data, rule changes.

**Requirement (P_bi):** the record at u determines D(h_u, t) for every t ≤ u.

## 2. Results

| ID | Statement | Status |
|---|---|---|
| **B1** | **The O-10a result extends.** h ≡\*_bi h′ ⇔ \|h\| = \|h′\| ∧ D(h_u, t) = D(h′_u, t) for all t ≤ u. This is the **greatest** append-only record preserving (P_bi). It is realized by a log whose entry at u holds (D(h_u, t))_{t≤u}. | MATHEMATICAL PROOF. It is Theorem 2 with a vector-valued decision. COMPUTATIONALLY CHECKED, I1 300 / 300, but **the same enumerator as O-10a, so not independent evidence** |
| **B2** | **A log of current decisions only is insufficient.** "1c" and "10" have the same current-decision log (1, 0), but the corrected decision for time 1 is 0 for one and 1 for the other. | COUNTEREXAMPLE (checked, I2) |
| **B3** | **The difference log is equivalent.** Its entry at u lists the times t < u whose value changed, with the new values, plus D(h_u, u). It determines B1's log and is determined by it, log for log, by induction. Both the **original** decision for t and the **current** one are recoverable; nothing is overwritten. | MATHEMATICAL PROOF (reviewed). Not pre-declared; not checked by code |
| **B4** | **Rule changes:** retroactive and non-retroactive re-evaluation are both well-defined bitemporal families. Which applies is a **STAKEHOLDER DECISION**. | ANALYTIC |
| **B5** *(corrected)* | **Concurrent corrections.** The record must fix the arrival order unless the merge is **commutative and associative**. Commutativity alone fails for 3 or more corrections: floor-average is commutative but order-dependent on 30 of 64 triples (reviewer). Even with an order-independent merge, D(h_u, t) depends on **which** corrections had arrived by u, so the record must still fix the arrival set at each u. | ANALYTIC + COUNTEREXAMPLE (reviewer) |

**Withdrawn as evidence:** the protocol's I3 computation was **vacuous**. It tested only that Python's `max` is commutative, and that two distinct values exist. B5 rests on the analysis and the reviewer's counterexample, not on I3.

**Size note:** B1's entry size grows as O(u). The difference log (B3) is the compact form.

## 3. Record semantics proposed (ARCHITECTURAL PROPOSAL; not technology-specific)

1. **Append-only.** No entry is edited or deleted.
2. **Every decision entry carries:** the subject and the time it concerns, the value, the rule version, and an evidence reference.
3. **A correction is a new entry** that references the entry it supersedes and states the reason (new evidence, corrected input, rule change).
4. **"Current authoritative"** is a **derived query**, the latest non-superseded entry for (subject, time). It is never a stored overwrite.
5. **Concurrent corrections** need either a declared total order, or a declared merge rule that is commutative **and** associative, plus a recorded arrival set.

**Which parts are mathematics and which are choices:**
- **Mathematics:** B1–B3, and the necessity of fixing order or arrival sets (B5).
- **Choices:** retroactivity (B4), which merge rule is used, and how far back corrections may reach. These are **stakeholder decisions**.

## 4. Open questions

| ID | Question |
|---|---|
| O-10i1 | Verifying a *superseded* decision: which evidence version applies (links to O-10f4) |
| O-10i2 | Bounded correction horizons: how much past may be revised, and the cost |
| O-10i3 | Bitemporal versions of the privacy result (P4), and of storage (O-10g) |
