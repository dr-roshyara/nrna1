# MD-059 §06 — Adversarial Hypotheses (§11 of the authorizing prompt)

| # | Hypothesis | Verdict | Evidence |
|---|---|---|---|
| H1 | F4 semantics secretly depend on representation. | **PARTIALLY CONFIRMED, in a specific sense** | `Obs_F4`/`Sat_F4`'s *definition* is representation-opaque (`03`, tests 1/2/3/8 pass); but 4/5/6 are `UNDECIDABLE FROM CURRENT CORPUS` because no two agreed encodings of `K_t` exist to test against — so representation-independence is *unverifiable*, not confirmed false, but also not cleanly confirmed true across the full test battery. |
| H2 | F4 semantics depend on arbitrary decomposition. | **REFUTED for `Obs_F4`/`Sat_F4`'s own definition** (opaque, no decomposition used); **CONFIRMED as the exact reason `Beh_F4`/`Trace_F4` are UNAVAILABLE** (would require choosing among unresolved composition-rule candidates, `02`). | `01`/`02` |
| H3 | Observational equivalence is too weak to establish kernel identity. | **NOT TESTED HERE** — `Obs_F4` is not even computable (`Sat`'s body is missing, M0132), so its strength relative to a "true" F4 identity cannot yet be assessed. | `02` |
| H4 | Behavioural equivalence is too strong. | **NOT TESTABLE** — `Beh_F4` does not exist. | `02` |
| H5 | Trace boundaries are arbitrary. | **NOT TESTABLE** — `Trace_F4` does not exist. | `02` |
| H6 | Satisfaction semantics merely relocate unresolved requirements. | **CONFIRMED, precisely, and now source-stated rather than inferred** | `Sat_F4` is genuinely corpus-native and frozen as a shape (a real gain over MD-058's general R8 finding) — but M0132 itself relocates the open question from "is `⊨`/`C_KOS` specified" to "what is `Sat`'s own body for each requirement class," the same shape of gap one level down, not resolved by the relocation. |
| H7 | Different semantic relations satisfy the same requirements. | **NOT DIRECTLY RE-TESTED HERE** — this phase did not derive a new relation, it instantiated MD-058's own; MD-058's own H7 finding (a family, not one relation) stands, unextended by this phase. | MD-058 §05 |
| H8 | F3/F4 can be made equivalent only by adding an ungrounded modelling choice. | **CONFIRMED** | `04` — any bridge between F3's atom-space and F4's requirement-space would have to be invented; none is corpus-given. |
| H9 | F4 cannot be instantiated from existing corpus evidence. | **PARTIALLY REFUTED** | `Obs_F4`/`Sat_F4` ARE instantiable in form (a genuine partial success, distinguishing F4 from F1/F5/K0); `Beh_F4`/`Trace_F4` are not. |
| H10 | Governance must intervene before semantic comparison becomes well-defined. | **PARTIALLY CONFIRMED — one of the two blockers is research-shaped, not governance-shaped** | The composition-rule reconciliation (`P-12`/`P-13`) is a genuine decision/governance act. `Sat`'s own missing body, by contrast, is a **research obstruction** — reconciling `K_t`'s 9+ unresolved variants (UE-1/UE-2) enough to type `Sat`'s inputs — not something a governance act alone could close without that research being done first; this corrects an earlier over-broad framing of both blockers as governance-shaped. |

No hypothesis was forced; H3/H4/H5/H7 are explicitly left untested/unre-tested rather than answered
by inference.
