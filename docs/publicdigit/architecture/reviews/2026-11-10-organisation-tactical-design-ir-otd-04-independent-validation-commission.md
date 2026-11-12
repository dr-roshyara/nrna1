# IR-OTD-04: independent validation of the Organisation tactical design (§49–§51)

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-10 |
| **Status** | **COMMISSION, READY TO HAND OVER.** Supersedes the IR-OTD-02 commission for the next review (that commission and its reports stand as history) |
| **Reviewer** | **Outside the previous review chain:** not Claude; **not `deepseek-chat`** (author of IR-OTD-01, -C, -C2, -C3, -02 and -03); **not run inside Claude Code**. A human architect, or another model family (GPT, Gemini, Mistral) in its own interface |
| **Why** | Six reviews by one model found real defects, each corrected. **None was independent** (each disclosed this). This is the first independent validation |
| **Purpose** | Validate the **complete normative §49–§51**, not only the last corrections |
| **Not in scope** | Redesign; other contexts; the people register; implementation. **A review of the written design proves neither implementation correctness nor test execution** |

## 1. Package (SHA-256, 2026-11-10)

| # | File | Role | SHA-256 |
|---|---|---|---|
| 01 | this commission | start here | see `MANIFEST.sha256` |
| 02 | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` | **subject**: §49–§51 (base §9–§12, §38, §45) | `71e94de9e5dfb29b25da6f0647bd95ac35cd3f0eef45e0da1256fda146b7b379` |
| 03 | `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` | decision record | `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3` |
| 04 | `docs/publicdigit/architecture/reviews/2026-11-10-organisation-strategic-baseline-v1.0.md` | **accepted baseline D-106**; §6 is the source of A-8 | `22d763aa25638fe82b211a2a7fd211c8401f498aa23ba4b944fa158e8a3308bd` |
| 05 | `docs/publicdigit/architecture/reviews/2026-11-09-reference-geography-tactical-design-baseline-v3.11.md` | **ratified Geography design (D-93)**; containment slot, `Belief(slot, t, p)`, `positionAt(τ)` | `8b7300d94616432b0efff4f3f11cfec14e68a2884da53bdd678c286d5dde6983` |
| 06 | `…/2026-11-10-ir-otd-02-fresh-confirmation.md` | previous findings C-1 … C-3 | `6530eeefb8d5774c29eddcd15320603f1fa5b3ddffbe25625ab0842f5a83c1e0` |
| 07 | `…/2026-11-10-ir-otd-03-reconfirmation.md` | previous finding R-1 and low notes | `7274075658aae7c6c5ccc65815c8e34292052d6805644157d621a11e62e8e4ed` |

**Normative text:**
- invariant tables §49.4, §50.6, §51.4;
- §49.7, §49.8;
- §50.15;
- the descent algorithm §51.3;
- lifecycle tables §50.5, §51.5;
- scenarios §49.10, §50.11, §50.15, §51.6.

**Not normative:** §51.11–§51.15 and every "Update" paragraph. **§51.15 is the author's own test; re-run it, do not trust it.**

## 2. Decisions to evaluate (read them in file 03, not in summaries)

- **D-114 … D-122** (§11.43–§11.47; D-115 is a working direction only);
- **D-136** (§11.49);
- **D-140** (§11.51);
- **D-106 §6** (file 04): "A change in published Geography never changes belonging by itself".

D-123 … D-135 and D-137 … D-139 belong to other topics, except D-125/D-126 (electoral divisions), referred to in §51.2.

## 3. Previous findings and their disposition (verify each in the text)

| Finding | Disposition claimed |
|---|---|
| IR-OTD-02 C-1 (D-140 in the Higher-body row) | Higher-body row states resumption |
| IR-OTD-02 C-2 (B-8 not normative) | B-8 in §50.6 |
| IR-OTD-02 C-3 (who confirms a scheme's Geography position) | A-8 names the competent authority; scenario 33 |
| **IR-OTD-03 R-1** (Higher-body row lacked the written-rules exception) | **Exception added, agreeing with A-7, the Adopt row and scenario 29** |
| deepseek-chat check of A-8 vs one shared τ (not independent) | A-8 states that a confirmation names an already-published position and is used only when known at τ; the Geography dependency is recorded |
| IR-OTD-03 §4 low notes | A-1 "*effective* destination" and A-8's "could alter" trigger; Change row states D-140 resumption; Binding list names D-116 … D-122, D-136, D-140; step 4 "placement act". **D-106 §6 now supplied** (file 04) |

## 4. Review questions

1. **Authority:** do D-119/D-136's hierarchy **and** the written-rules exception (identifiable provision; unknown competence refused) hold in every normative place (A-1, A-3, A-7, step 4, Adopt / Change / Remove / Higher-body rows, scenarios 25–31)?
2. **Allocation lifecycle (D-140):** is "overridden, not ended; resumes with `PlacementReviewNeeded`; refused never applies" consistent across A-7, the Change, Remove and Higher-body rows, the closure row and scenario 31?
3. **Geography contract:**
   - Does A-8 use only what the ratified Geography design provides: the containment slot `(CONTAINMENT, unit, scheme)`, `Belief(slot, t, p)` and `resolve`?
   - **Is reading a scheme at its *confirmed* position consistent with D-106 §6 *and* with "consumes read-only, at one shared τ (D-73)"?** The author's reading is that the confirmed position is a fact recorded as known at τ and is ≤ `positionAt(τ)`, so one τ still fixes the answer. Test it.
   - **Dependency:** A-8 assumes published content at a position never changes. Geography v3.11 leaves open ([OQ]) the effect of a defective approval on published facts. **Is A-8's dependency stated adequately, and is anything else in §49–§51 exposed to it?**
4. **Membership coverage and τ:** do B-2, B-7, S-9 and C-2 use CR-01 C1/C2 at the same τ as the Organisation records?
5. **Invariants, algorithms, scenarios:** for the 15 cases in §51.15 and scenarios 12, 13, 28–33, do the invariants, lifecycle rows and descent algorithm give the same answer?
6. **Governance–Organisation separation:** does Governance decide and Organisation record and derive, everywhere (no rule in which Organisation decides authority)?
7. **Internal consistency:** does any normative sentence, read on its own, give an outcome another normative sentence denies? **A cross-reference does not cure a contradiction.**

## 5. Report

Title: **"IR-OTD-04 — Independent Validation: Organisation Tactical Design (§49–§51)"**.
1. **Per question and per previous finding:** CONFIRMED · PARTIAL · MISSING · CONTRADICTORY · NOT ASSESSABLE, with the **exact section and line** supporting each confirmation.
2. **Findings** (high, medium, low), each with:
   - evidence;
   - whether it is a fact or an inference;
   - its impact;
   - whether a Principal decision is needed.
3. **Verdict:** VALIDATED (ready for the Principal's acceptance decision) · NOT VALIDATED (what remains).
4. **Reviewer identity** (human, or model, version and interface), prior involvement with PublicDigit, and integrity method (hashes computed or not).

**Rules:**
- Decide nothing for the Principal.
- Modify nothing.
- Claim no acceptance.
- Mark anything not in the package UNAVAILABLE.
- **Stop after the report.**
