# CHANGELOG: 1bb → 1bb-r1

This file lists every change made in SPEC-r1 (`SPEC-r1.md` / `SPEC-r1.json`) relative to SPEC-1bb (`SPEC-1bb.md` / `SPEC-1bb.json`).

- **Types:** ADDED, CHANGED, REMOVED, RELABELLED, MOVED.
- **Defect** refers to REVIEW-1bb D1–D14.
- Items not listed here are **UNCHANGED**. They are summarized at the end.
- Nothing was collected, and no command was run.

## Shared rules (§0)

| ID | Type | Location | Defect | Before (1bb) | After (r1) |
|---|---|---|---|---|---|
| CH-01 | ADDED | §0.1 scope | D1 | Scope was defined by whether a prediction puts one hypothesis at risk. | Added: being in scope is necessary but not sufficient. Discrimination also depends on NIF availability (§1.7). |
| CH-02 | ADDED | §0.3 | D1 | — | The six distinctions from HUMAN-DECISIONS (N-EXISTS, N-FORCE, N-KNOWN, ACT-CONF, ACT-CONTRA, N-CHANGE-NOACT), each with what can and what can never establish it. |
| CH-03 | ADDED | §0.4 | D1 | — | NIF tiers: NIF-A (independent status record), NIF-B (third-party application, meaning N-FORCE[OPERATIONAL]), NIF-C (third-party citation, meaning N-KNOWN only). A list of what is not NIF. A graded fallback (maker's records, capped at FIRED-CONDITIONAL). Rule **G-D1** (DISJUNCTIVE grading). |
| CH-04 | CHANGED | §0.5 attestation, condition (i) and scope | D7 | Conditions (i)–(iii) were applied to every artefact, including the adoption record. | An act's record *is* the observation. It attests only its writer and its recording time, never its effective date or force. Conditions (i)–(iii) apply only to attesting fields of *other* observations. |
| CH-05 | CHANGED | §0.5 attestation, condition (ii) | D7 | Artefacts "derived from / citing" the record were excluded. | Citing artefacts are admissible as *observations* of uptake (NIF-B, NIF-C, ACT). Only copying self-statements to attest a field is excluded. |
| CH-06 | CHANGED | §0.5 attestation, condition (iii) | D11 | "CI start, server push" were assumed to exist. A committer date was "weaker". | Git author and committer dates are client-set, i.e. SOURCE_STATED. System times apply only "if such an artefact exists". |
| CH-07 | CHANGED | §0.5 entry into force | D5, D9 | Entry into force always came from an adoption record. | From an adoption form (AF-1/2/3) if the ruling is status-mediated. Otherwise "not earlier than its recording time". |
| CH-08 | CHANGED | §0.6 closed-world register → capability register v0 | D5(f) | "Fix in advance… before looking at the data". | Frozen before *acquisition*. Non-blindness toward R-86 and R-90 is disclosed. Operational-disposition capability is **UNKNOWN**, and R-90's status is UNKNOWN. |
| CH-09 | CHANGED | §0.6 single channel → declared channel set | D5(a) | One channel: a register row plus an adoption record. | DCS v0 = {K-REG, K-SESS, K-PROG}. DCS v1 = v0 plus Part C channels, closed before Stage 1. |
| CH-10 | ADDED | §0.6 OUT_OF_CHANNEL_ACT | D5(a) | — | An act found outside DCS v1 makes the "no act" premise UNDETERMINED. It is never scored as an act-less change. |
| CH-11 | ADDED | §0.6 adoption forms | D5(c) | "The DA's statement may itself be the adoption" (left open). | An exhaustive list AF-1, AF-2, AF-3. "R-89 already governs" = MAKER_FORCE_ASSERTION, not adoption. The sensitivity result is reported and never scored. |
| CH-12 | ADDED | §0.6 identity | D5(d) | Identity came from committer or author names. | AUTH_ID vs ACTOR_SET_ID. ACTOR_SET_ID caps the grade at FIRED-CONDITIONAL. |
| CH-13 | ADDED | §0.7 execution-channel register | D5(e) | No closed world for work. | EC v0 = repositories named by the human, plus CI records if they exist. An absence of work requires a complete EC. |
| CH-14 | ADDED | §0.8 absence and completeness | D2, D5 | "No act" = no entry in the channel's write history. | Every absence premise requires coverage, capture, retention and integrity. Otherwise it is NOT-EVIDENCED-IN-CAPTURE, and the falsifier is UNDETERMINED. |
| CH-15 | ADDED | §0.9 M-TIME | D4, D5(b), D11 | — | Order labels (RECORDING_ORDER, SOURCE_STATED_ORDER, INFERRED_ORDER, NOT_IDENTIFIABLE). The late-recording rule (NOT_IDENTIFIABLE). Falsifier grades (FIRED, FIRED-CONDITIONAL, DISJUNCTIVE, NOT-FIRED, UNDETERMINED, NOT_APPLICABLE). |
| CH-16 | ADDED | §0.11 artefact fallbacks | D11 | Existence of artefacts was assumed inside the fields. | A table giving preferred artefact / fallback / grade for CI time, push time, R-86, R-90, R-77/79/80 adoption, DA session logs, IMPLEMENTATION_PROGRESS versioning, register-in-git, and R11's 16:10. |

## P1

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-17 | CHANGED | P1 `norm_state_before` | D1 | The last execution or refusal attested the before-state. | N-FORCE at t1 by NIF-A or NIF-B. Execution is recorded as ACT only. |
| CH-18 | CHANGED | P1 `norm_state_after` | D1 | Queue, work plan, or first execution on the new state. | N-FORCE at t2 by NIF-A or NIF-B. The first execution is ACT only. |
| CH-19 | CHANGED | P1 `acts_in_closed_channel` | D5 | Closed-channel write history. | DCS v1, with writer grade, adoption form and late-recording flag. |
| CH-20 | ADDED | P1 `DCS_completeness` | D5 | — | New field. |
| CH-21 | ADDED | P1 `eligibility_criterion` | D8 | "Every eligible target" had no definition. | FR-D8 criterion, written per clause before Stage 1. |
| CH-22 | ADDED | P1 `response_or_legitimation` | D8 | — | New field. A legitimated case is scored LEGITIMATED, not N-CHANGE-NOACT. |
| CH-23 | CHANGED | P1 falsifier H-D | D1, D5 | The after-state was "acted on by engineering", with no closed-channel act. | NIF states at t1 and t2, a complete DCS v1, no late-recorded act, no OUT_OF_CHANNEL_ACT, and no legitimating act. |
| CH-24 | CHANGED | P1 min episodes and falsifier H-B | D10 | An existential census only, with an unlabelled reading and an unscoped conclusion. | Reading UNKNOWN. **1a:** census, with the conclusion "not instantiated in W". **1b:** one counter-instance. Both are scored. |
| CH-25 | RELABELLED | P1 R-86 "waiting" | D14 | Given as a SOURCE FACT candidate instance. | The quote is a SOURCE FACT. Reading it as behaviour is an INFERENCE. |
| CH-26 | ADDED | P1 NIF requirement and discrimination status | D1 | — | NIF is required. Without it, P1 is NON-DISCRIMINATING. |
| CH-27 | RELABELLED | P1 ig_per_effort | D14 | INFERENCE | PROPOSAL |

## P2

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-28 | CHANGED | P2 claim | D6 | "…if not, the system responds (sanction, remediation, waiver) before acceptance". | Conformance only: permitted at t_exec⁻. |
| CH-29 | MOVED | P2 `response` field | D6 | A P2 field with a window to acceptance. | Moved to P8. |
| CH-30 | CHANGED | P2 falsifier H-B | D6 | Non-permitted execution **and** no response before acceptance takes force. | Non-permitted execution with no waiver in force *ex ante*. The response is scored in P8. |
| CH-31 | CHANGED | P2 window | D6 | t_exec → acceptance takes force (R-87). | The norm-in-force interval up to t_exec. No response window. |
| CH-32 | CHANGED | P2 `norm_in_force` | D1 | From adoption records. | NIF-A or NIF-B, or the graded fallback (FIRED-CONDITIONAL). |
| CH-33 | ADDED | P2 `status_rule_dependence` | D1 | — | If non-permission relies on status mediation (for example R-81..R-85 via R-86), the result is DISJUNCTIVE. |
| CH-34 | ADDED | P2 `waiver_in_force_at_t_exec⁻` | D6 | — | New field. |
| CH-35 | CHANGED | P2 `execution_event` time and R11 16:10 | D11 | "System time (CI or push)". | Git dates are SOURCE_STATED. A CI record is used if it exists. |
| CH-36 | CHANGED | P2 `scope_determination` | D9 | No default. | FR-D9 default scope. |

## P4

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-37 | CHANGED | P4 making-act texts | D14 | R-87, R-88 and R-89 text all required. | R-89 required. R-87 needed for P7. R-88 **OPTIONAL**. |
| CH-38 | CHANGED | P4 `status_sequence` | D5, D7 | Adoption record write history. | AF-1/2/3 only. Each attests writer and recording time, never an effective date. Late-recording flags added. |
| CH-39 | CHANGED | P4 `force_indicator` | D1 | Work citing the ruling, or citation by a non-adopting actor. | NIF-A or NIF-B only. NIF-C is recorded. Work citing R-89 becomes a separate ACT field. |
| CH-40 | ADDED | P4 `maker_force_assertions` | D5(c) | — | "R-89 already governs" = MAKER_FORCE_ASSERTION. |
| CH-41 | CHANGED | P4 falsifier H-D | D1, D5 | No adoption entry, yet work executed under R-89 or a third party cites it. | A complete DCS v1 with no adoption form, no late-recorded adoption, and NIF-A or NIF-B showing R-89 in force. |
| CH-42 | CHANGED | P4 falsifier H-B note | D1 | "Paired with P7, the same data put H-B at risk". | Scored only through the §5 outcome table. |
| CH-43 | CHANGED | P4 window | D4 | From R-89's recording time. | From the earliest recorded trace of R-89 in any DCS channel. |
| CH-44 | ADDED | P4 NIF requirement | D1 | — | Without NIF, P4 is NON-DISCRIMINATING. |

## P5

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-45 | ADDED | P5 `capability_norm_in_force_at_t_R94` | D8 | Capability register (current). | The capability norm in force when R-94 was made. R-90 is UNKNOWN. |
| CH-46 | CHANGED | P5 capability source | D11 | "Adopted acts R-86, R-90". | Their text and status "if obtained". R-90 is UNKNOWN. |
| CH-47 | CHANGED | P5 `execution_under_R94` | D11 | Revision of IMPLEMENTATION_PROGRESS.md (assumed versioned). | "If versioned". Otherwise MENTION/REPORT, and P5 is UNDETERMINED. |
| CH-48 | ADDED | P5 `R94_effect_NIF` | D1 | — | New field. |
| CH-49 | CHANGED | P5 falsifiers | D1, D8 | H-D: no adoption plus execution. H-B: via P2. | Both need NIF. Execution alone gives DISJUNCTIVE. An unresolved capability gives NON-DISCRIMINATING. |
| CH-50 | CHANGED | P5 window | D4 | From R-94's recording time. | From the earliest recorded trace of R-94. |
| CH-51 | CHANGED | P5 actor identity | D5(d) | Register-row write history identity. | AUTH_ID or ACTOR_SET_ID. |

## P7

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-52 | CHANGED | P7 GREEN/VERIFY CI run | D14 | Part of the predecessor acceptance attestation. | **OPTIONAL**. It serves C2's evidence limb only. |
| CH-53 | ADDED | P7 `r72_gate_in_force_at_t_w` | D8 | — | New field. It is required for the H-B falsifier. |
| CH-54 | CHANGED | P7 `R89_status_and_text` | D2 | Status only. | Also whether R-89 waives or supersedes the gate. |
| CH-55 | CHANGED | P7 first successor work event | D2, D4 | First commit or CI run, with no window or protocol. | The Part B rank-0 output, with order labels. |
| CH-56 | ADDED | P7 `link_work_to_R89` | D2(a) | — | The CITES_R89 flag. |
| CH-57 | CHANGED | P7 waiver field | D2(b), D6 | Waiver or its absence. | Waiver *in force at t_w⁻*, from the full DCS v1 history. |
| CH-58 | ADDED | P7 third-party NIF-B / NIF-C | D2(d) | — | New field. |
| CH-59 | CHANGED | P7 falsifier H-D | D12 | "Treated as authorized because the predecessor was accepted". | NOT_APPLICABLE to the R-89 episode. Elsewhere it is an instance of P1-HD, scored only there. |
| CH-60 | CHANGED | P7 falsifier H-B | D6, D8 | Work before acceptance, gate in force, no waiver, **and no response before WP-4C-1 acceptance**. | At t_w⁻: acceptance not in force, gate in force, no waiver in force (including R-89 if it waives). The response clause is removed and moved to P8. |
| CH-61 | CHANGED | P7 window | D4 | R-89 recorded → WP-4C-1 acceptance or 2026-08-15. | [min(earliest trace of R-72, earliest WP-4C-1 ET), min(WP-4C-1 acceptance in force, 2026-08-15)]. |
| CH-62 | ADDED | P7 NIF requirement | D1 | — | DISJUNCTIVE if the result rests on the status rule. UNDETERMINED without a source. |

## P8

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-63 | CHANGED | P8 claim domain | D6 | Failures only. | Failures **and deviations**, including non-conformance (for example R11). |
| CH-64 | ADDED | P8 `active_target` | D8 | Undefined. | FR-D8 definition and attestation. |
| CH-65 | ADDED | P8 `references_to_R11_by_R91_R94`, `late_responses` | D6 | — | New fields. LATE_RESPONSE is recorded. |
| CH-66 | CHANGED | P8 `remediation_or_deferral_in_force` | D1 | Closed-channel entry with adoption record. | NIF, or the fallback in a complete DCS. |
| CH-67 | REMOVED | P8 tendency reading | D10 | "UNKNOWN… if a tendency, a census is needed" (no threshold). | Dropped. P8 is universal only. A tendency reading is NOT_TESTABLE_AS_SPECIFIED. |
| CH-68 | CHANGED | P8 falsifier H-D | D12 | "Remediation with no act (reduces to P1)". | Scored only under P1. |

## Cross-prediction sections

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-69 | ADDED | §1.7 NIF matrix | D1 | — | For each prediction and limb: the required NIF, and the "without it" status. |
| CH-70 | CHANGED | §1.8 input conflict | D14 | "UNKNOWN (conflict between the inputs)". | The conflict may be only apparent ("§WP-4 remains OPEN" may mean all of WP-4; C2's order is SOURCE_STATED_ORDER of drafting). |
| CH-71 | CHANGED | §1.9 feasibility | D11, D1, D5 | A list of candidates. | Each candidate applies "if it exists", with the fallbacks. Part C channels are added. |

## §2 C1 contested case

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-72 | CHANGED | C1 decision rule, default state | D9 | Proviso unmet with no covering block gave UNDETERMINED. | Default is unauthorized unless the proviso is shown met, which gives NON-CONFORMS. |
| CH-73 | ADDED | C1 default scope | D9 | — | "WP-4B REMAINS BLOCKED" covers all WP-4B work, including producer-agnostic RED, unless it is narrowed by a text in force before 16:10. |
| CH-74 | ADDED | C1 entry-into-force fallback | D9 | Adoption records only. | "Not earlier than recording time" for rulings that are not status-mediated. Stated dates are SOURCE_STATED. |
| CH-75 | CHANGED | C1 CONFORMS structure | D9 | The MD had P ∧ (removal ∨ scope). The JSON had (P ∧ removal) ∨ scope. | One structure, **P ∧ ¬B**, in both the MD and the JSON. |
| CH-76 | CHANGED | C1 UNDETERMINED | D9 | "Otherwise". | Only for an unobtainable text, or an entry into force relative to 16:10 that is NOT_IDENTIFIABLE. |
| CH-77 | CHANGED | C1 bearing | D6, D1 | Against H-B only if NON-CONFORMS **and** no response before R-87. | NON-CONFORMS bears against H-B conformance regardless of R-87. It is FIRED-CONDITIONAL unless NIF is present, and DISJUNCTIVE if it relies on status mediation. The response is scored in P8. |
| CH-78 | CHANGED | C1 acquire list: R-87 | D6 | Required. | OPTIONAL for C1. Needed for P8 only. |
| CH-79 | ADDED | C1 acquire list | D9, D1 | — | Whether any permitting text (R-81..R-85) was recorded before 16:10 in PREPARED status. |
| CH-80 | CHANGED | C1 R11 time | D11 | "CI or push time". | Git dates are SOURCE_STATED. A CI or server time is used if it exists. |

## §3 Status mediation

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-81 | ADDED | §3 item 0 | D13 | — | NIF-A for the status transition. |
| CH-82 | RELABELLED | §3 item 1 | D13 | "Separate identity and system time" (implied independent). | This is the maker's own act. Only its recording time and AUTH_ID are independent. |
| CH-83 | CHANGED | §3 item 2 | D1, D13 | Engineering commits or CI runs, or citations by non-adopting actors. | NIF-B only. NIF-C is recorded. Executor work is ACT. |
| CH-84 | CHANGED | §3 item 3 | D13 | Behavioural contrast with no eligibility condition. | An eligible contrast requires pending dependent work and a complete EC. Otherwise the contrast is VACUOUS. |
| CH-85 | ADDED | §3 FR-D13 | D13 | — | A decision rule for "independently observed". |

## §4 Ranking

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-86 | RELABELLED | §4 ranking | D14 | INFERENCE | PROPOSAL |
| CH-87 | ADDED | §4 rank 0 | D2 | — | Stage 0: Part B plus Part C. |
| CH-88 | CHANGED | §4 P4+P7 value | D2 | HIGH ("about 3 records"). | HIGH, MEDIUM or LOW, conditional on the trace and on NIF. |
| CH-89 | CHANGED | §4 P2 | D2 | HIGH. | HIGH. Moves to rank 1 if O0. |
| CH-90 | CHANGED | §4 P5 comparison | D3 | "Low effort and two-sided". | Compared on likely outcome (operational scope) and on NIF requirement. |

## §5 Smallest acquisition

| ID | Type | Location | Defect | Before | After |
|---|---|---|---|---|---|
| CH-91 | CHANGED | §5 structure | D2 | Three items. | Two stages: Stage 0 (S0-a Part B, S0-b Part C) and Stage 1 (S1-a … S1-g, run only if a trace exists). |
| CH-92 | CHANGED | §5 effort | D2 | "About 3 records". | The whole DCS v1 write history over the window, plus the NIF channel. |
| CH-93 | CHANGED | §5 outcome branch 1 | D1 | "R-89 not adopted, but work ran under it → H-D status clause falsified". | Outcome O4 = DISJUNCTIVE {H-D status clause, H-B conformance}. H-D is falsified only via O3 (NIF-A or NIF-B). |
| CH-94 | CHANGED | §5 outcome branch 2 | D1, D6, D8 | "R-89 in force, work before acceptance with no waiver → H-B falsified". | O2 or O2c. It requires the gate in force and no waiver in force (R-89 checked as a possible waiver). The response is excluded. |
| CH-95 | ADDED | §5 outcomes O0, O5, O6, O7, O8 | D2, D5 | Missing. | O0: no trace. O5: NIF-A shows R-89 not in force. O6: UNDETERMINED. O7: trace does not cite R-89. O8: variant or LOCAL_ONLY hits only. |
| CH-96 | REMOVED | §5 last line | D3 | "This is the only acquisition this small that puts **both** hypotheses at risk". | Deleted. |

## New parts

| ID | Type | Location | Defect / decision | Before | After |
|---|---|---|---|---|---|
| CH-97 | ADDED | Part A | TASK / HUMAN-DECISIONS | — | For each of D1–D14: the defect quoted, the correction, sufficiency, prediction change, and a frozen rule FR-Dn. |
| CH-98 | ADDED | Part B | HUMAN-DECISIONS Decision 2 | — | Frozen rank-0 protocol: channels; target PAT and VAR; execution-trace classes; timestamp table; absence and completeness (C1–C4); no lower window bound; three scripts with SHA-256 hashing and a canonical key; worker and reviewer procedure (Run A / Run B from a bundle; blind reclassification); output labels. |
| CH-99 | ADDED | Part C | HUMAN-DECISIONS Decision 3 | — | Frozen scoping protocol: rules of restraint and stop rule; 10 candidate channels; the `scope-nsc.sh` script (existence and metadata only, API projections); independence I1–I4 and completeness K1–K4; answer vocabularies for Q1–Q8; overall result and its frozen consequences. |
| CH-100 | ADDED | JSON structure | TASK | 1bb JSON keys: scope, specs, contested_case_resolution, status_mediation_upgrade, ranking, smallest_discriminating_acquisition. | `{meta, defects[14], spec{…}, rank0_protocol{…}, scoping_protocol{…}}`. |

## Kept UNCHANGED from 1bb (content preserved)
- §0.1 scope basis and exclusions (P3, P6).
- §0.5 self-report examples.
- SOURCE FACT: recording ≠ decision ≠ entry into force.
- "No act" never means that no unrecorded act happened anywhere.
- §0.10 independence of episodes (all five conditions).
- **P1:** target, claim, the UNKNOWN about which H-B reading is intended, min episodes for H-D (1), the prior-conditional-clauses field, and the information-gain value (LOW).
- **P2:** one-sidedness, the operation_class rule, min episodes (1), falsifier H-D (none).
- **P4:** target and claim, min episodes (1), falsifier H-B (none on P4).
- **P5:** target, the norm-type field, min episodes (1), the INFERENCE about operational scope.
- **P7:** claim (R-72 gate), min episodes (1), and the reason for dropping the draft's "2 pairs".
- **P8:** the failure-record field, the next-execution field, the substance of falsifier H-B, the window from failure to next execution, and the "no execution → not triggered" rule.
- **§2:** the acquire items R-73..R-76, R-78, R-80, and the scope text.
- **§3:** the SOURCE FACT of 2 self-reported episodes, and the statement "one contrary episode would falsify H-D's status clause; tests H-D only".
- **§4:** the relative values of P2 (HIGH), P5 (MEDIUM), P8 (LOW) and P1 (LOW).
