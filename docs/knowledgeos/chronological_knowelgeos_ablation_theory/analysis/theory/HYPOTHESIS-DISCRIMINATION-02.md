# Hypothesis discrimination 02 — instrument r2 and the L216 experiment

| | |
|---|---|
| Status | research record; not canonical; authority none. Supersedes the *use* of note 01 / r1; note 01 stays as record (not rewritten) |
| Instrument | `model_discrimination_r2.py` (`d14d79af…`), selftest 7/7; frozen with `L216-CASE/CASE-SPEC.json` (`1c1aa887…`) at `181d78e7e` **before** the read |
| Case | `.claude/sessions/2026-08-04.md` L216–L224 (`6309c2e3…`); observation `L216-CASE/OBSERVATION.json` (`a7ade762…`); result `L216-CASE/RESULT.json` (`db87d142…`) |
| Log | F-LOG-0114 |

## 1. Instrument repair (review 2026-09-27)
- Ev / Qual / Val are separate requirements. The high-rung prerequisite is a **declared norm reading** ∈ {QUAL, VAL, QUAL∧VAL, QUAL∨VAL} (MODEL-ASSUMPTION; r3 encoded ES-006.1 as QUAL∨VAL).
- Observation = component vector (EV, QUAL, VAL ∈ MET/DEVIATES/UNKNOWN/NOT-RECORDED/OUT-OF-SCOPE; OBL, EXC, AUTH ∈ PRESENT/ABSENT/UNKNOWN/NOT-RECORDED; SEM ∈ EPISTEMIC/OPERATIONAL/UNKNOWN/NOT-RECORDED). Wildcards are open-world.
- H3 split: **H3a** kind-dependent ordering · **H3b** kind-dependent *meaning* of adoption (work adoption = operational authorization; epistemic requirements out of its scope).
- r1 "EIG" renamed **MDS-U** (artificial uniform-outcome score). Primary measure is **logical separability** (pairs separable by ≥ 1 outcome).

## 2. Correction to note 01 / F-LOG-0113 (MODEL-DERIVED)
**P1 refutes H1 only under the VAL or QUAL∧VAL norm readings.** Under QUAL or QUAL∨VAL (the r3 encoding), P1's qualification is NOT-RECORDED (F-LOG-0102: E2 UNKNOWN), so H1 stays consistent. r1's "P1 excludes H1" was an artefact of merging Qual and Val into one `dVal` outcome, which implicitly closed the world. H3's exclusion likewise depended on the r1 kind assumption; P1's kind is now UNKNOWN and H3a/H3b both survive.

## 3. L216 result
- **SOURCE-FACT:** L218 "Architecture Discovery Freeze v1.0 adopted" — passive, no adopter named; no evidence, qualification, validation, obligation or exception recorded for the act.
- **Coding:** every ordering component NOT-RECORDED; AUTH NOT-RECORDED; SEM UNKNOWN (borderline: restricts discovery, and precedes "Phase 3 begins: execute the loop"; the section describes the adoption as neither); kind / high UNKNOWN; force AMB.
- **EMPIRICAL-RESULT (for this case): SILENT.** No hypothesis eliminated under any norm reading. Claims: C3 NOT-TRIGGERED-as-undeterminable (no deviation observable); C4 UNDETERMINABLE (no authority named).
- Surviving: H2 H3a H3b H4 H5 under VAL / QUAL∧VAL; all six under QUAL / QUAL∨VAL. Under the QUAL reading H1 ≡ H2 remains logically inseparable for any case of these attributes.

## 4. What L216 does show (SOURCE-FACT outside the frozen alphabet; each is one occurrence → observation, not theory)
| Observation | Structural distinction it bears on |
|---|---|
| a **reopening criterion**: discovery only on "evidence the existing model CANNOT EXPLAIN" | persistence / reversibility: exit from an adopted state is evidence-gated. **HYPOTHESIS H-asym:** entry and exit burdens are asymmetric (here: entry evidence not recorded, exit evidence demanding) |
| "refinements/exceptions of existing principles are expected and **are not discovery**" | exception vs structural transition: exceptions are ordinary, within-model transitions |
| "stronger than the meta-principle freeze" | freezes are **ordered by strength**; a third sense of "freeze" (a moratorium rule on a process) beside author-freeze and governance-freeze |
| empty cells "each naming its filling source"; "verdict-free pinned by test" | the corpus itself practises NOT-RECORDED ≠ FALSE (corpus-internal support for the open-world rule) |
| "Phase 3 begins: execute the loop … don't redesign it" | an adoption functioning as a **phase transition of the process**, not a promotion of an object — a candidate third object kind (regime/phase), neither knowledge item nor governed work |

## 5. Method lesson (MODEL-DERIVED, two occurrences: R-100 and L216)
Heading-level scores predict *which hypotheses a case could separate*, not *whether the source records ordering at all*. Session-log bullets summarise outcomes and have been silent on ordering twice. The dominant unknown is the per-source **silence probability u**, which r1/r2 treated as a constant. Next selections should weight **observability**: prefer sources whose heading or structure signals an evidence component (e.g. an instance count) or whose order is mechanically recorded (register rows; git commit timestamps as SOURCE-FACT ordering).

## 6. Completion against the review checklist (added 2026-09-28; no new corpus read)
- **Realized score:** L216's observation is all-wildcard, so every alive hypothesis permits it with the same probability. The realized MDS-U gain is **0 bits** under every norm reading (pre-read MDS-U was 0.48–0.54; an artificial uniform-model score, not an empirical probability).
- **Jointly falsified:** none. **Impossible with this case:** every distinction between the ordering hypotheses (no ordering component recorded).
- **The nine distinctions, as bounded by L216 alone:**

| Distinction | L216 bears on it? |
|---|---|
| knowledge vs governed work | no: kind unstated; a candidate third kind (regime/phase) observed |
| promotion vs authorization | no: no authority named |
| promotion vs validation | no: validation not mentioned |
| freeze vs adoption | **yes (SOURCE):** a freeze is itself the *object* of an adoption, and freezes are ordered by strength. Consistent with frozen ≠ adopted (a freeze-state is not an adoption; a freeze-policy can be adopted) |
| evidence vs qualification | partly: evidence appears only as the **reopening** condition, never as an entry condition |
| rule force vs document status | no |
| event vs state | **yes (SOURCE):** the adoption is recorded as an event that opens a phase ("Phase 3 begins") — a state entered by an event, left only by an evidence-gated event (H-asym) |
| authority inheritance | no |
| exception vs ordinary transition | **yes (SOURCE):** "refinements/exceptions … are not discovery" — exceptions classified as ordinary transitions |
