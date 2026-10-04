# R6 validation matrix (proposal r1, after independent review; nothing runs before the human authorizes the scope)

**Design rule:** R6 must be able to **kill or materially change** the theory. Every experiment has:
- a sealed prediction;
- a **falsifier**;
- a **positive control**: a planted failure or separation that the procedure must detect.

**Layer note (review):** V1–V4 can change only the **formal** layer (R4). Only E-A5 below could change the **empirical** non-identifiability.

| # | Validation | What it attacks | Method | Prediction | Falsifier | Positive control | Cost |
|---|---|---|---|---|---|---|---|
| **V1a** | **Reading-monotonicity lemma** (formal) | R4's proofs cover frames, not alternative **clause readings** | Prove a lemma: R4's construction (σ ≡ p, f ≡ ∞, Ref unchanged) satisfies every reading R′ with property M (universal or vacuous on constant σ with no forced acts). So D1(i) holds for every R′ with M | Every R′ with M preserves D1(i). The listed readings are classified as M or not-M | a reading with M under which the construction fails (a lemma error) | — (a proof; reviewed) | low |
| **V1b** | **Enumeration of the non-M readings only** | the genuine break candidates: B-conf as "≥ 1 conforms" **with B-recW dropped** (which changes R_B itself); D-st⁺ (biconditional); D-chg without capability | U-sized enumeration (§7.1 extended per reading) | **sealed per reading before running** (a per-reading table, written and sealed separately) | the observed outcome differs from the sealed per-reading prediction | **planted failure:** CM-A5 / U₅ under each reading must show non-separation broken (detection check) | low |
| **V2** | **Scale via targeted sub-universes** (not full enumeration: two targets give U² ≈ 4×10¹¹) | §7.3 items: \|P\| ≥ 2, multiple targets, records, actors | Constructive witness checks on the R4 constructions, plus enumeration of small *slices* (one coordinate extended at a time); the target-tied B-chg reading is tested here | the R4 constructions remain valid; the K8.1 difference set as derived and sealed | a construction step fails in an extended slice | the planted countermodels must still separate | medium |
| **V5** | **Conj 5.5** (empirical) | the performative-ambiguity mechanism | **F-a and F-b** (where R4 places falsifiability) with a pre-fixed rule; F-c only with a blind S_mode | — | F-a / F-b as pre-fixed | **required:** e.g. a planted record set with known mode-explicit texts | medium |
| **E-A5** | **Empirical test of A5** (does any channel observe σ or f independently?) | the **empirical** non-identifiability (H5) | Run the **sealed Protocol C** (host-side: PR approvals, branch protection, CI, events) under its frozen info-gain review | per `LOOP-1BF/INFOGAIN-PREREG.md` | — | — | low run cost; **reopens H1 (human decision)** |
| **V6** | **Second corpus** | corpus specificity | — | — | replication failure | — | **human decision; decision brief only** |

**V5 caveats:**
- **Circularity:** Conj 5.5 was formed from the same 1bj disagreements, so a test on those data is not independent.
- **No power at this n.**
- **Recommendation:** record V5 as **NON-IDENTIFIABLE on existing data**. Run it only on **new blind data** (e.g. V6, or a fresh coding set with a pre-sealed S_mode).

**Dropped after review:**
- **V3 (adversarial frames).** Its prediction credited R4 with an "if and only if" it never claims: Cor 4.3 is only a *necessary* condition. What remains of it duplicates proven results (K8.5).
- **V4.** It re-tests proven sufficiency (K9) and adds little.

**Statistics and ML:** not required for V1, V2 or E-A5 (proof, enumeration, protocol). For V5, results are read descriptively only; the n is too small for inference.

## Highest-information-gain next experiment
**V1a (the reading-monotonicity lemma), then V1b (the non-M readings with planted controls).**
- **Most general:** a proof covers every M-reading at once.
- **Cheapest.**
- **Isolates the genuine break candidates:** existential B-conf with B-recW dropped, and D-st⁺. If V1b breaks D1 for a reasonable reading, R4 must be revised, and that comes to the human.

## Unresolved risks
1. **The R5 human spot-check is still pending.** V1b reuses the verify.py approach and should start only after R5 closes.
2. **Everything is Claude-family.** The strongest upgrade is a non-Claude reimplementation of R5/V1b from the stripped spec.
3. **Predictions are sealed by R4's own analyst:** sealed, but not independent.
4. **Finite checks never prove the generic statements.**
5. **The empirical non-identifiability can change only through E-A5 or V6.** Both are human decisions.
6. **Side issue:** R4 §8 cites the quarantined F09/F10/F12 for scope. Fix this at R4's next revision.

**Review (kos-theory-reviewer): PASS_WITH_LIMITATIONS on r0.** Its 3 blocking issues are fixed in this r1:
- V3's iff claim → V3 dropped;
- V5 had no positive control and no F-b → both added, plus the circularity note;
- V1's malformed prediction → split into V1a and V1b, with per-reading sealed predictions and the U-size dependence noted.
