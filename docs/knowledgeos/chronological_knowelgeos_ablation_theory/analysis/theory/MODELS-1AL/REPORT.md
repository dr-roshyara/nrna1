# 1al: competing formal models on the strict core — per-operation guard signatures, bisimulation, distinguishing observations

| | |
|---|---|
| Status | research record. **FORMAL results over strict EMPIRICAL observations** (SEMOBS r4, 36 events); not theory; no corpus read |
| Instrument | `models_1al.py` committed before its run (`8b1f54c1e`); a loader defect (the split marker occurred inside semobs_r4's source) fixed before the re-run (`1e1627c63`); `RESULT.json` (`cd2534bc…`) |
| Secondary (labelled post-hoc) | `models_1al_secondary.py` → `RESULT-secondary.json` (bisimulation coordinates) · `models_1al_secondary_r2.py` → `RESULT-secondary-r2.json` |
| Corrections in the secondary pass | (1) a pair whose separating variables are UNK is **undetermined**, not a failure; (2) an identity token `same:<obj>` is known only for equality, and is UNK against a different object's value |
| Log | F-LOG-0144 |

## 1. Guard signatures are OPERATION-SPECIFIC (FORMAL)
A signature is the smallest variable set over which an operation's rule-grounded outcomes are a deterministic function.

| Operation | Surviving minimal signature(s) | Determination |
|---|---|---|
| START | **{state}** (target-indexed) | PARTIAL: 24 pairs separated, 6 undetermined (WP-4B at 16:10: state UNK) |
| OPEN-WORK | **{kind}** | DETERMINED |
| REGISTER | **{kind}** | DETERMINED |
| AUTHORIZE-IMPL | **{authority}** | DETERMINED |
| ASSIGN-ID | **{history}** | DETERMINED (1 pair); {route} VACUOUS |
| **RAISE** | **{authority, evidence} ≡ {evidence, target}** | both DETERMINED (20 separations each). {evidence, kind} and {evidence, exception} are only PARTIAL. **Evidence alone is refuted** by P1 (promoted on 1 instance by PA instruction) against L493-B (refused at 1) |
| **SUPERSEDE** | **{kind} ≡ {route}** | both DETERMINED. {authority}, {evidence} and {exception} are VACUOUS. **The first formal appearance of route**, observationally equivalent to kind (ADR-MP vs R-77/R-83) |
| ADOPT | authority + one of {conformance, kind, target} | all PARTIAL: R-86 vs R-91 is **undetermined**. The data show *that* something besides authority matters, not *what* |
| ANNOTATE | unconstrained | only one outcome observed |

**Consequence (candidate theory shape; HYPOTHESIS):** the legality layer is **a family of per-operation guards over different small variable sets**, not a single global variable vector. The "which variables matter?" question is ill-posed globally. It is well posed **per operation**.

## 2. Bisimulation of the ruling-status transition system (M3 sub-model B; FORMAL)
- Reachable states 250 → **80 bisimulation classes**.
- **Behaviour-relevant coordinates:** status, supersession. Annotation count is relevant only through the model's artificial cap (a modelling artefact).
- **Never varying independently:** registry, issuer, delegation, text. In particular, **the registry is a function of the five-valued status**.
- So within this model **history is absorbed into state**. The earlier "history needed" (the R-90 witness) is **representable by status alone** once WITHDRAWN is a status value.
- This is the second absorption result after scope-as-index. It matches the hypothesis *"state may already encode history"*.

## 3. Observationally equivalent models, and the shortest distinguishing observations

| Equivalence (current data) | Discriminating observation (smallest) | Model predictions |
|---|---|---|
| RAISE: {a, e} vs {e, t} | a RAISE at **1 instance** by the **same authority as P1 (PA)** on a **methodology-type target**, OR by the **ARB** on a **documentation-standard target** | {a, e}: follows the authority; {e, t}: follows the target type |
| SUPERSEDE: {k} vs {r} | a supersession via **ADR acceptance** of an **ADR-kind** object, OR via **ruling** of a **decision-log entry** | {k}: kind decides; {r}: route decides. **This is also the first corpus-feasible route-vs-kind test** |
| ADOPT: a + {c ∨ k ∨ t} | a Decision-Authority adoption refusal or approval where kind and target class equal those of an opposite-outcome adoption, with conformance stated | only c survives if kind and target are equal |
| START: state (6 undetermined) | resolve WP-4B's state at 16:10 (the question F-LOG-0129 left open) | — |

## 4. Summary by category
- **FORMAL RESULT:**
  - per-operation signatures;
  - evidence alone refuted for RAISE;
  - two equivalence classes (RAISE, SUPERSEDE);
  - history absorbed into status (bisimulation);
  - scope absorbed into state (1ak).
- **EMPIRICAL RESULT:** unchanged. Authority, kind and state are supported; evidence is weak.
- **HYPOTHESIS:** per-operation guard families; route matters for SUPERSEDE (tied with kind).
- **FALSIFIER:** each row of §3 decides one equivalence.
- **UNKNOWN:** WP-4B's 16:10 state; the conformance of R-86's rulings.
- **GOVERNANCE DECISION:** none. Gate 1 proper is external and pending.

## 1am-1: SUPERSEDE {kind} ≡ {route}, resolved (spec `SPEC-1AM-SUPERSEDE.json`, frozen at `217e8e63d`; result `RESULT-1AM-SUPERSEDE.json`; no new corpus read)

**SOURCE FACTS** (rows already read):
- R-57: "The approved plan's 7B Objective and Acceptance rows carried a mechanism **R-44 superseded**".
- R-44: "'consume Adjudication's existing port' was a **mechanism, never an architectural decision**. The invariant is binding; **the mechanism is substitutable**."

**SEMANTIC OBSERVATION:** SUPERSEDE of a mechanism, via route RULING, by the ARB, PERFORMED.

**FORMAL RESULT** (the frozen consistency test over the SUPERSEDE events plus this event):

| Model | Before | After | Conflict |
|---|---|---|---|
| **{route}** | consistent | **FALSIFIED** | R-77 (RULING, refused) vs R-44 (RULING, performed) |
| {authority} | consistent | FALSIFIED | the same pair (ARB in both) |
| **{kind}** | consistent | **survives** | — |
| {evidence}, {exception} | consistent | consistent, **vacuously** (UNK) | — |

**Reading:**
- The supersession guard follows the **kind of the superseded object**.
- Mechanisms are supersedable by an ordinary ruling. ADRs and design rules (R-77, R-83) are not, absent sufficient evidence. The source states this distinction itself: "a mechanism, never an architectural decision"; "the invariant is binding; the mechanism is substitutable".
- **Route is not required for SUPERSEDE on current data.** Its apparent role came from its coincidence with kind in ADR-MP.

**Caveat:** a single discriminating event, reported by R-57, with R-44 as its performed source. The kind categories (mechanism vs decision/ADR) are source-stated, not invented. **Evidence remains unresolved as a second SUPERSEDE variable**, because it is UNK on the performed events.
