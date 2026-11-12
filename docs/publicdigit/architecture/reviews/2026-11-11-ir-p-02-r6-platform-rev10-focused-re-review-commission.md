# IR-P-02-R6: focused independent re-review of the Platform (tenancy) design, revision 10

| | |
|---|---|
| **Commissioning authority** | The Principal (D-94), 2026-11-11. **This commission carries the Principal's commission text "IR-P-02-R6 — Revision 10 Handover Preparation and Focused Independent Re-Review Commission" (§§4–8), which governs.** This file adds the file list, source statuses and packager's notes |
| **Reviewer** | **GPT-6 in ChatGPT.** Not Claude, not DeepSeek, not in Claude Code. **Prior involvement:** author of IR-P-02 and IR-P-02-R … R5. Not blind |
| **Subject** | `docs/publicdigit/architecture/reviews/2026-11-11-platform-tenancy-tactical-design.md`, **revision 10**, SHA-256 **`30ecb53eb9d2acd526ef55ce743cbbd320e89d1071ab691ce88c98c179f54aeb`**. Recalculated before packaging; frozen; not modified |
| **Proof register** | `2026-11-11-platform-proof-obligation-register.md`, SHA-256 `6711114e57a0bdb17fe8090380c5556799aacedc773176454f1c9366ad0161ea`. It cites the subject fingerprint. **47 obligations (A 14, B 10, C 9, D 6, E 8), all NOT EXECUTED; no duplicate ids** |
| **Status** | **Proposed, not accepted. No implementation authorised.** Do not predetermine anything from the author's notes |

## 1. Files and source status (`MANIFEST.sha256` authoritative)

| # | File | Source status |
|---|---|---|
| 01 | this commission | — |
| 02 | **subject**, revision 10 | **proposed** |
| 03 | proof register (A–E) | author's register; all NOT EXECUTED |
| 04 | change trace R5 → revision 10 | **author's packaging aid; not evidence** |
| 05 | revision 9 snapshot (`4e131bfb…`) | historical (the R5 subject) |
| 06 | revision 8 snapshot (`28cfecdf…`) | historical (the R4 subject) |
| 07 | IR-P-02-R5 (formal report + chat summary appendix) | historical, verbatim |
| 08 | IR-P-02-R4 (recorder note on its misprinted fingerprint) | historical, verbatim |
| 09 | IR-P-02-R3 | historical, verbatim |
| 10 | IR-P-02-R2 | historical, verbatim |
| 11 | IR-P-02-R | historical, verbatim |
| 12 | IR-P-02 | historical, verbatim |
| 13 | IR-P-01 findings extract (full report never saved) | historical, extract |
| 14 | **decision record** | **binding**, see §2 |
| 15 | multi-database design | **accepted (D-178)**: ISO-1 … ISO-11, INV-ACCESS-1 (§16 M-6), backup / recovery / grant constraints |
| 16 | strategic baseline acceptance act | **accepted (D-173)**: ownership |
| 17 | H-1 analysis | strategic-baseline input (D-173): INV-ACCESS-1 §H, D-166 controls §D |
| 18 | Governance thin slice | **accepted (D-157)**: §6 Resolution / DecisionLog; AuthorityCheck correction notes 1–4 (**note 4 = `requestRef`**); additions K-1 and **D-163 (precedent)**. **Does NOT contain the D-190 note** |
| 19 | AuthorityCheck contract review | review; adopted into file 18 as correction notes |
| 20 | strategic context map and governance tactical model | **PROPOSED (working baseline, not ratified):** GD-1, GD-11, GD-12 detail |
| 21 | XD-3 Identity ARB act | **ACCEPTED (D-75)** |
| 22 | XD-3a record | **accepted** via file 21 (append-only note; **the header still says PROPOSED**) |
| 23 | XD-3b record (Person vs Account) | **accepted**, as file 22 |
| 24 | XD-3c record (Account lifecycle) | **accepted**, as file 22 |
| 25 | I-TDD-01 Identity design | **PROPOSED** (superseded where D-113 applies) |
| 26 | I-TDD-02 scope | **scope proposal only; I-TDD-02 not written** |

## 2. Decisions

- **Binding:** D-164 … D-191, plus D-12, D-110, D-111, D-113 (file 14).
- **Exact wording:**
  - **D-190: file 14 §11.89.** It **authorises** the act-kind extension. **The addition note is to be ratified together with the Platform acceptance; it is not ratified now.** R10-1 contains only a **draft**.
  - **D-191: file 14 §11.90.** An accepted decision; its scope is **exactly** the listed metadata.
- **Precedent:** D-163 (file 14 §11.68; addition in file 18).
- **Keep four things apart:**
  1. recorded decision;
  2. authorised but unratified Governance addition;
  3. proposed tactical implementation;
  4. unexecuted proof obligation.

## 3. Missing or unresolved sources (stated by the packager)

| # | Item | Status |
|---|---|---|
| S-1 | **Accepted Identity operation resolving an organisation `PersonRef` to the bound Account** | **Does not exist.**<br>- Ratified principles: file 21 and files 22–24; D-110, D-111, D-113.<br>- **Mechanism only in PROPOSED file 25.**<br>- **I-TDD-02 not written** (file 26).<br>- **No accepted Identity tactical contract exists** |
| S-2 | **D-190 Governance addition note** | **Draft only** (R10-1); not in file 18 |
| S-3 | **GD-11 / GD-12 detail; per-part persistence of `ruleRef` / `policyVersion`** | GD detail only in PROPOSED file 20. **The per-part sentence appears only in the R10-1 draft; whether D-190 authorises it is for the reviewer (Principal commission §4 R10-1)** |
| S-4 | Values of the R10-5 confirmation window and R10-7 abandonment window | **not stated** in the design (author's note, file 04) |

## 4. Review scope, classifications, verdict

**As in the Principal's commission (governing):**
- **§4 R10-1 … R10-9:** review scope.
- **§5:** R5-01 … R5-13 mapping.
- **§6:** architectural checks.
- **§7:** classes [a] [b] [c] [d] [TU] [UNKNOWN] **[EXT]**, each with section, line, evidence, failure scenario, consequence and minimum correction.
- **§8 verdicts:**
  - READY FOR PRINCIPAL CONSIDERATION;
  - NOT READY;
  - READY WITH EXPLICIT EXTERNAL CONDITIONS (only if they are genuinely separable and precisely stated).

**Keep four gates apart:**
1. tactical coherence;
2. Governance and Identity contract acceptance;
3. implementation authorisation;
4. proof execution and release.

**Do not ask the Principal anything D-190 or D-191 already answers. Do not solve another context's design inside Platform.**

**Report title:** "IR-P-02-R6 — Focused Re-Review: Platform Revision 10".
- **Integrity first:** recalculate the manifest and the subject fingerprint.
- **Rules:**
  - modify nothing;
  - decide nothing for the Principal;
  - reopen nothing;
  - claim no acceptance;
  - **stop after the report.**

## 5. Principal's commission text (governing; §§4–8 reproduced verbatim, headings demoted one level)

### 4. Revision 10 review scope

Commission GPT-6 to review the actual revision 10 source. The review must not rely solely on Session A's summary.

#### R10-1 — D-190 Governance addition note

Review the exact proposed note against D-190 and the accepted Governance contract.

Check:

1. `designate-platform-contacts` identifies one designation, including the cited decision, Resolution part and designated PersonRef.
2. `give-case-grant` applies only to a case grant given through the Organisation's normal route while its database works.
3. The two act kinds are separate and have the correct subjects, actors, business dates and competence requirements.
4. Governance answers from the Organisation's own rules.
5. Where the rules are silent, the outcome is `Undetermined` until a competent body is designated, consistently with D-167 and D-181.
6. Emergency grants under the outage route are not incorrectly evaluated using `give-case-grant`.
7. Platform remains a consumer of Governance authority and does not create authority.
8. The proposal does not introduce an unapproved second AuthorityCheck schema or unsupported fields.

**Specific issue: `ruleRef` and `policyVersion`.**

R10-1 states that these values are the citation and rule-document version under which the competence of a Resolution part or act was evaluated, and that they remain fixed.

Determine whether this rule is already established by accepted Governance sources or is an additional Governance rule that must be explicitly included in the D-190 addition note and ratification.

Do not assume that D-190 automatically authorised every related persistence rule.

Classify any unsupported semantics accurately.

#### R10-2 — D-191 central contact metadata

Verify that the exact central metadata model conforms to D-191.

Check:

- Account reference.
- Contact source.
- Cited decision reference and date.
- Document digests, not decision documents.
- Sender.
- Validation outcome.
- Evidence reference.

The actual permitted fields must be compared with the authoritative wording of D-191.

Check whether the design adds any other central data, including personal data, decision content, minutes, rules or unnecessary Organisation business records.

Verify the recovery replacement rule: Organisation records govern after recovery, and the central outage metadata does not become a permanent competing authority source.

D-191 resolves the earlier D-174 metadata conflict only for the specified list. Do not extend its allowance by implication.

#### R10-3 — Organisation inbox compare-and-replace

Review the atomic version application.

Check:

- Comparison and update occur within one atomic transaction.
- Concurrent deliveries cannot cause version regression.
- Duplicate, delayed, reordered and retried messages are safe.
- Terminal states cannot be reopened.
- Admission and inbox application are properly serialised.
- Lock order is consistent with all existing central and Organisation transactions.
- Missing-copy behaviour remains fail closed.

Examine actual database semantics, not merely the phrase "under a lock".

#### R10-4 — D-189 deadline and timer start

D-189 remains accepted and must not be reopened.

Verify:

- `m0` is defined precisely.
- The timer begins early enough that no time between the authoritative clock reading, admission, dispatch and execution is omitted.
- The deadline cannot be extended by gateway delay, queue delay, worker restart, failover or retry.
- The remaining duration is propagated conservatively.
- The central clock is authoritative.
- Admission is refused if the required time cannot be established.
- No result is delivered after the effective deadline.

Check the actual sequence and timing boundaries.

Do not accept a statement that a timer starts "before admission" without proving that the resulting deadline cannot exceed D-189.

#### R10-5 — Live database termination and containment

Review the complete live-operation termination path.

Check:

1. Cancellation and backend termination.
2. Positive confirmation of termination.
3. Database login or credential revocation.
4. Termination of existing sessions.
5. Network-path closure.
6. Grant containment and permanent refusal of further operations.
7. Operation state when termination is uncertain.
8. Incident reporting and audit evidence.
9. Recovery and re-authorisation rules.

Determine whether the proposed controls actually prevent continued access if the database backend ignores cancellation or termination.

Distinguish an intended containment procedure from a technically demonstrated guarantee.

The design must not claim that work has stopped merely because the gateway timer expired.

#### R10-6 — D-188 report record

Verify the full per-operation report record.

At minimum, check that each affected operation identifies:

- Operation ID.
- Grant ID.
- Operation status.
- Outcome.
- Whether access completed.
- Whether access may have occurred.
- Whether termination was confirmed.
- Audit-evidence references.
- Relationship to the failed designation and suspended grant.

Check durable report creation, completeness, idempotent delivery and recovery after interruption.

Ensure D-179 notifications remain limited to their permitted security-notification purpose. Do not send Organisation business content through the login address.

D-188 remains accepted.

#### R10-7 — Operation registry lifecycle

Review the complete registry-to-Organisation admission lifecycle.

Check every failure window:

- Registry transaction fails.
- Registry commits but Organisation admission does not.
- Organisation admission commits but acknowledgement is lost.
- Gateway crashes before `started`.
- Gateway crashes after `started`.
- Retry arrives with identical binding.
- Retry arrives with different binding.
- Admission window expires.
- An operation remains registered but never becomes admitted.
- A worker restarts or fails over.

Verify:

- One global operation identity.
- Immutable binding.
- Deterministic retry behaviour.
- No second execution.
- No accidental new deadline.
- Explicit abandonment rules.
- Correct handling of an operation whose access may already have occurred.

Do not claim exactly-once execution unless the actual design and proof establish it. Preserve the existing conservative uncertainty rule.

#### R10-8 — Proof register

Independently compare the entire revision 10 design with the entire proof register.

Verify:

- Sections A–D are preserved where applicable.
- Section E contains the eight revision 10 additions.
- The total count is correct.
- Every obligation has a unique identifier.
- Every obligation maps to the actual design.
- No previous applicable proof obligation has been silently removed.
- E-7 remains blocked until the accepted Identity operation exists.
- E-8 remains blocked until the D-190 Governance addition is ratified.
- Every unexecuted obligation remains `NOT EXECUTED [TU]`.

The review must not convert a design review into execution evidence.

#### R10-9 — External dependencies

Assess X-1, X-2 and X-3 separately.

**X-1 — Governance addition under D-190**

The proposed addition note must be checked for exact conformance with D-190 and the accepted Governance model. It remains a ratification dependency until the appropriate acceptance act.

**X-2 — Identity PersonRef-to-Account resolution**

Determine whether an accepted Identity contract exists and whether Platform consumes it correctly. If not, record it as an unresolved external dependency. The emergency route must remain fail closed.

**X-3 — GD-11 and GD-12**

Determine what the accepted Governance thin slice actually establishes and what remains only in the proposed Governance model.

Do not treat proposed GD-11/GD-12 semantics as accepted.

For each dependency, state:

- Owning bounded context.
- Authoritative source.
- Current acceptance status.
- Exact missing contract or decision.
- Platform behaviour while the dependency remains unresolved.
- Whether it is a design blocker, acceptance condition or implementation/build gate.

Do not resolve another bounded context's design inside Platform.

### 5. Recheck all IR-P-02-R5 findings

The reviewer must explicitly map each of the following to revision 10:

- R5-01 — Governance act-kind extension.
- R5-02 — Identity PersonRef-to-Account resolution.
- R5-03 — GD-11/GD-12 and related Governance semantics.
- R5-04 — atomic Organisation inbox update.
- R5-05 — timer-start gap.
- R5-06 — live-database termination and containment.
- R5-07 — per-operation D-188 report content.
- R5-08 — registry-only failure lifecycle.
- R5-09 — additional proof coverage.
- R5-10 — D-174/D-191 central metadata conflict.
- R5-11 — subject identifier and Identity integration.
- R5-12 — backend termination and isolation guarantees.
- R5-13 — earlier findings and proof obligations.

No finding may be marked resolved merely because Session A says it has been fixed.

### 6. Additional architectural review

Check that revision 10 continues to comply with:

- D-164: separate database per Organisation.
- D-166: service-operation grants do not permit content reading; case-specific grants remain necessary.
- D-167: Platform never appoints or infers Organisation authority.
- D-170: emergency grant metadata only, with the accepted recovery process.
- D-173: accepted strategic baseline; tactical acceptance and implementation authorisation remain separate.
- D-174 through D-189: all accepted contact, emergency grant, authority, recovery, withdrawal, reporting and deadline decisions.
- D-190: only the authorised Governance extension.
- D-191: only the specified additional central contact metadata.

Check the ownership boundaries:

- Governance owns organisational authority and decisions.
- Organisation owns its business records and organisational decisions.
- Platform owns the registry, locator, platform status, service-operation grant records and permitted pending case-grant metadata.
- Identity owns Person, Account, authentication and identity bindings.
- Membership owns membership facts.
- Finance owns Finance facts.

Check that Platform does not become a general Organisation administration or content-access context.

### 7. Required finding classifications

Use these classifications:

- **[a] Design defect:** the revision 10 design is incorrect, incomplete or inconsistent with an accepted decision or contract.
- **[b] Proof obligation:** the design may be adequate, but required implementation-level evidence is absent.
- **[c] Principal decision:** a genuine unresolved business, constitutional or policy decision.
- **[d] Resolved/accepted:** already settled by an accepted decision or authoritative contract.
- **[TU] Technical uncertainty:** technical behaviour or evidence is not established.
- **[UNKNOWN]:** supplied sources do not support a reliable conclusion.
- **[EXT] External dependency:** another bounded context or governance process must establish the required contract or evidence.

Do not ask the Principal questions that D-190 or D-191 have already answered.

For each finding, provide exact section and line references, evidence, failure scenario, consequence and minimum correction.

If an issue is external, identify its owner and do not silently solve it within Platform.

### 8. Review verdict

The independent reviewer must choose a verdict based on the evidence:

- READY FOR PRINCIPAL CONSIDERATION.
- NOT READY.
- READY WITH EXPLICIT EXTERNAL CONDITIONS, if and only if the remaining conditions are genuinely separable from the coherence of the Platform design and can be stated precisely.

The reviewer must distinguish:

1. Tactical design coherence.
2. Governance/Identity contract acceptance.
3. Implementation authorisation.
4. Proof execution and release readiness.

These are different gates.

The reviewer must not recommend implementation merely because the tactical design is accepted.
