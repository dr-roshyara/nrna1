# `EKS-06` — Reference resolution is bound to a FIXED TOKEN LIST, not to the document's declared identifier families

**Status:** BACKLOG · **Class:** assurance-coverage problem · **Raised:** 2026-08-21
**Owner for disposition:** Governance / ARB *(the same owner as `OQ-1`)*
> ⛔ **A backlog item records a problem and a candidate requirement; it commissions nothing.** ⛔ **This item adopts nothing, changes no checker, and proposes no gate.**

---

## 1 · The problem, as observed

**Track 2's `S2` slice — *intra-document reference resolution* (`CAP-004`/`DP-4`) — resolves two token shapes:**

```
§x.y      → must resolve to exactly one heading
step N    → must resolve to a normative enumeration that defines N
```

⭐ **The independent AMD6 Architecture review found a dangling reference of exactly `S2`'s class that `S2` does not see:**

| | |
|---|---|
| **The defect** (`DV-7`) | `…-MIGRATION-PLAN.md` §0.6.5 row 6 cites the operator object **`P5·ALL`**. §4.7 defines `P5·1 · P5·1b · P5·2 · P5·3 · P5·4 · P5·5 · P7·1 · P7·2 · P7·3`. **`P5·ALL` occurs exactly once in the artifact — in that citing cell — and is defined nowhere.** It propagates to `…-AMD6-SUMMARY.md` line 198 |
| **Why `S2` is silent** | **`P5·<id>` is not `§x.y` and not `step N`.** The reference is dangling in precisely `S2`'s sense — *a citation to an identifier its defining section does not define* — but the token shape is outside the implemented register |
| **Consequence for the back-test record** | ⭐ **The Phase-0 report's `AMD6 → quiet` row for `S2` is CORRECT AS SCOPED and remains so.** ⛔ **But the artifact is NOT free of `S2`'s defect class**, and that distinction is invisible in a report that says *"quiet"* |

⚠️ **The same shape is what `DI-5` was:** *"Phase 5 step 5"* cited against a block defining `1 · 2 · 3 · 4`. **`S2` was built from that instance and generalized to the TOKEN, not to the CLASS.**

## 2 · Candidate requirement — ⛔ **a candidate, not a decision**

> **The reference register should be derived from the identifier families the document DECLARES, rather than from a fixed list of token shapes.**

**A document that defines an identifier family — `§x.y`, `step N`, `P5·<id>`, `RC-n`, `CL-n`, `criterion N`, `OPEN-Mn` — should have every citation of that family resolved against that family's declaring section.** ⭐ **`S3` already does something adjacent for vocabulary: it takes a *document-level declaration* (the backticked `DI-7` label) as its input rather than a hard-coded term list.** ⇒ **the pattern for declaration-driven registers exists in the realized code; `S2` does not use it.**

⚠️ **Bound, stated so this item is not read as more than it is:** this is **ONE occurrence**. Under `ES-006.1` a single occurrence promotes nothing — it is a **problem plus a candidate requirement**, ⛔ **not an engineering standard, not a capability realization, and not a checker change.** ⭐ **A second independent instance would be the evidence that matters.**

## 3 · What this item explicitly does NOT claim

⛔ **It does not claim `S2` is defective** — `S2` does what it was specified to do, and its back-test rows are true.
⛔ **It does not claim the Phase-0 report is wrong** — the report's `NOT-CHECKED` discipline and its `D-4` verbatim limitation statement are precisely what let this gap be named instead of hidden. ⭐ **This item is evidence that that discipline works.**
⛔ **It does not propose a gate, a hook, a CI wiring, or a severity.** ⛔ **It does not touch `OQ-1`…`OQ-4`, and it answers none of them.**
⛔ **It does not affect the AMD6 chain's routing** — `DV-7` is disposed by the migration-plan correction commission, not by this item.

## 4 · Relationship to the frozen methodology

⭐ **This is recorded as an OBSERVATION with a named owner, not as a protocol refinement.** The methodology freeze (2026-08-01) permits recording a deficiency that implementation exposed; ⛔ **it does not permit acting on it here.** **Disposition — including whether to dispose of it at all — is Governance/ARB's.**

**Traceability:** the independent AMD6 Architecture review by `claude-code-session:dd639043` §6.2 (`DV-7`) · `docs/knowledgeos/reviews/2026-08-21-track2-phase0-historical-back-test-report.md` *(`S2` scope, the `AMD6 → quiet` row, the `NOT-CHECKED` statement)* · `developer_guide/knowledgeos/02_track2_structural_profile.md` *(`S2`/`S3` profile; `S3`'s declaration-driven input)* · `PKS_Phase_III_Capability_Catalog.md` *(`CAP-003` REALIZED · `CAP-004` `--anchors`)* · `DI-5` *(the instance `S2` generalized from)* · `ES-006.1` *(one occurrence promotes nothing)* · `OQ-1` *(same owner; `S8`/`DI-3`-`DI-6` class)*.
