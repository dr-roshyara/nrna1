# M0 report: the factored transition model attacked with development data

| | |
|---|---|
| Status | research record. Not canonical. Authority: none. **Every result is MODEL-DERIVED from development data. None is an EMPIRICAL-RESULT** |
| Pre-registration | `prompts/KNOWLEDGEOS-M0-TRANSITION-MODEL-PREREGISTRATION.md` (`fde7995f…`), frozen at `1760c863e` **before** the verifier existed |
| Verifier | `m0_check.py` (`42cfef42…`): deterministic breadth-first search over all states (288 reachable per guard model); self-test 6/6, including a mutation test showing the frame check detects violations |
| Output | `M0-RESULT.json` (`25c31558…`) |
| Log | F-LOG-0117 |

## 1. The model (MODEL-ASSUMPTION)
- **Model:** M = (O, K, R, F, A, S, E, δ).
- **State S:** a product of eight *candidate* coordinates. The set is open, and the planes named by different sources are not unified.
- **Frames:** each operation carries a frame, the set of coordinates it may change. Frame axiom: `d ∉ Frame(o) ⇒ S'_d = S_d`.
- **Guards:** four competing variants for when the evidence bar applies (the bar is met by evidence 2+ or an exception):
  - G-R: on route (bar on PROMOTION);
  - G-K: on kind (bar when identity is observation);
  - G-O: on operation (bar on RAISE);
  - G-E: on effect (bar when the effect is epistemic).
- **Force:** each variant runs force-insensitive or force-sensitive. Force-sensitive is the H4 form: the bar applies only under IN force.
- This gives 8 guard models.

## 2. Assumptions carried
- The coordinate domains.
- The bar threshold (2 instances).
- REOPEN requires counter-evidence (L216).
- RAISE is blocked while frozen (L205).
- Common authority guard (C1).
- The development acts are encoded with every reading alternative left open.

## 3. Invariants and properties

| Property | Result | Basis |
|---|---|---|
| P-frame | holds on every transition of all 8 models. By construction; the mutation test shows the check has teeth | — |
| **P-frame-fit, FREEZE** | a frame of {standing} **cannot express** L205-A1 ("existing set" unchanged, yet "discovery … stops"). A frame of {regime} can | L205-A1 → **the freeze acts on a process-level coordinate, not on object standing** |
| P-planes | `governance closed ∧ execution open` is reachable in one step. `execution closed` is unreachable for an item with no close transition | L493 (AST-015) |
| P-reach | `adopted ∧ ¬validated` is reachable in every model | P1 witnesses it (development) |
| P-rev | LAPSE is enabled from reachable adopted states, so standing is reversible. REOPEN is never enabled without counter-evidence. **Two exits, which do not conflict:** lapse lowers *standing*; counter-evidence reopens the *regime* | L205-A4, L216 |
| **P-gate-invariance** | only **G-K** lets RECLASSIFY change whether a RAISE is enabled. G-R, G-O and G-E are gate-invariant | L205-A3 ("same gate") can falsify **only G-K**, and only if the two kinds are differently gated (unstated) |
| **P-markov** | **not Markov on object state.** Witness: `[]` and `[ASSIGN, WITHDRAW]` both end with the identifier free; ASSIGN is enabled after the first and disabled after the second. Adding a 3-valued registry coordinate {unused, used, retired} **restores the Markov property** | R-90, "RETIRED, not recycled" (F-LOG-0113) |

**P-markov consequence:** the history that matters enters as a **finite tombstone summary**, not as full event sourcing. B4 (event-sourced state) is *not required* by this witness. Whether some other rule needs unbounded history remains untested.

## 4. Development consistency (open world: an unrecorded field may take any value)
- **All four force-insensitive models are consistent** with every coded act.
- **All four force-sensitive models are REASON-INCONSISTENT with L493-B.**
  - The refusal states the bar as its reason ("ES-006.1 forbids"), under a text whose force is AMB.
  - A force-sensitive guard lifts the bar at AMB, so it cannot be the reason given.
  - Reading: practice enforced the bar as if it were in force. This is *practiced* force ≠ document force, consistent with Force ≠ Status.
  - This is reason-level evidence, not an elimination: refusals are always permitted.

## 5. The P1 conditional (sensitivity: P1's unrecorded exception closed to "none")

| P1 reading | Models refuted by P1 |
|---|---|
| RAISE · observation · normative | G-K, G-O (insensitive) |
| RAISE · observation · epistemic | G-E, G-K, G-O (insensitive) |
| RAISE · rule · normative | G-O (insensitive) |
| RAISE · rule · epistemic | G-E, G-O (insensitive) |
| CREATE-NORM · observation · normative | G-K (insensitive) |
| CREATE-NORM · observation · epistemic | G-E, G-K (insensitive) |
| CREATE-NORM · rule · normative | none |
| CREATE-NORM · rule · epistemic | G-E (insensitive) |

- **G-R survives every reading.** P1 was a RULING ("by explicit PA instruction").
- **Conjecture (MODEL-DERIVED; development only; conditional on closing P1's exception):** the only guard model consistent with P1 under *every* reading **and** with L493-B's stated reason is **G-R / force-insensitive**. That is: the evidence bar attaches to the *promotion route* and is enforced regardless of the text's document status.
- It is a conjecture to test, not a result.

## 6. Surviving equivalences and shortest distinguishing traces (P-dist)
- Every model pair is separable by **one invocation from the initial state**. The model is not the bottleneck; observability is.
- Required observations, per pair:
  - **G-R vs G-O:** a RULING that performs RAISE (raises standing) with evidence < 2 and **no exception**.
  - **G-K vs G-O:** a CREATE-NORM on an *observation*-identity object with no evidence.
  - **G-E vs the others:** an epistemic-effect RULING with evidence < 2.
  - **Force-sensitive vs force-insensitive:** any barred act at AMB force with evidence < 2 and no exception.

## 7. Strongest next observation (model-directed)
- **G-R vs G-O is decided by one field on an already-read case:** whether **P1 (R-41) positively had no exception**.
  - The rulings register row records exceptions when they exist (R-39's row does); R-41's row records none.
  - Under our rule this is still NOT-RECORDED.
- It becomes ABSENT legitimately **only if the register itself declares a completeness convention**, i.e. that exceptions are recorded in the row.
- **Recommended L0-REL-27:**
  - a locate-only search of the rulings register's header or preamble (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, `7795c14b…`) for a completeness convention on exceptions;
  - then, only if one is found, a bounded read of that paragraph.
- If there is a convention: P1 becomes a real discriminator between G-R and G-O (with P1's operation reading fixed by its "generalizes the WP-1 correction" source text).
- If there is none: move to TODO 1n, a fresh RULING that raises standing, from the register.

## 8. What this does not show
- No population claim; the acts were selected.
- No choice of dimensions.
- No canonical frame table.
- Nothing about ML.
