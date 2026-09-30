# F-SERIES — GOVERNANCE DECISION DEPENDENCY AND SEQUENCING

**Status:** evidence and decision-structure document only.
- No decision is made.
- No option is recommended.
- Nothing is redesigned, implemented or executed.

**Commission:** the human, 2026-09-25: *"What must the human/governance lane decide, in what order, and which later decisions depend on those decisions?"*

**Starting point:** `prompts/F-SERIES-v1.3-R-AUTHORITY-OWNERSHIP-CONFORMANCE-REVIEW.md` §12 (commit `73484b055`), "the Review".

**Tags:** **[F]** explicit source dependency · **[D]** derived from documented architecture · **[O]** suspected or uncertain; needs human clarification.

**Paths** are under `docs/knowledgeos/knowledgeos_theory_chronological_extraction/` unless stated. Short names:

| Short | Document |
|---|---|
| L0R | `governance/L0-DECISION-RECORD-01.md` |
| GIA | `governance/GIA-DECISION-PACKAGE-01.md` |
| RCA | `architecture/research-control-architecture.md` (**PROPOSED**) |
| MP | `prompts/knowledge_os_protocoll.md` |
| ARCH | `prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` |

> ⚠ **Identifier collision (recorded, not resolved).**
> - The RCA uses "H-1" and "H-10" for items of the *governance assessment*. RCA L94: *"Decision Authority, unmapped, see H-1 in the assessment"*. RCA L362: increment 3 precondition *"H-1, H-10 of the assessment"*.
> - These are **not** the F-Series H-1 (F-Series role) and H-10 (write zone).
> - In this document, **H-n = F-Series decision** and **aH-n = assessment item**.
> - This is the namespace problem GIA-6 / NR-1 addresses (GIA L68).

---

## 1. Open items and their authority level

**Authority** is who the evidence says decides the item. "L0" is not assumed.

**L0-DEC-27 [F] (L0R L395):**
- *"L0 decides only: release, scope changes, exceptions, phase boundaries, and R1 defects."*
- *"Technical choices inside an approved scope are not L0 decisions."*

**Authority assignment precedent [F]:** GIA-3 → L0-DEC-15 (who may modify the runner) was decided by L0. GIA §9 lists "authority assignment" under L0, in a governance-prepared boundary table.

| Id | Item | Authority (evidence) |
|---|---|---|
| **H-1** | F-Series role (R-A / R-B / R-C) | L0 [D]: a scope and methodology change. Release Check question 1, *"Is the approved methodology unchanged since the last release?"* (L0R L385–391), would register it |
| **H-14a** | DEFINE / CHANGE / APPROVE / EXECUTE of execution-assurance machinery | L0 [F: precedent L0-DEC-15; GIA §9 "authority assignment" (governance-prepared)] |
| **GI-1** | no recorded L0 approval of ARCH v1.0 / v1.1 / v1.2 Addendum B | L0 [F: L0R L218, listed "Open for L0" L227] |
| **GIA-9 (= H-14b)** | ADOPT / ADAPT / REJECT of the research-produced evidence binding | L0 [F: GIA §8 *"then the L0 decision under GIA-9"*; GIA §11] |
| **GI-3** | §5A mechanics m1/m2 | **L0, or delegated to governance** [F: L0R L250–253] |
| **RC-H-05** | adopt or reject the RCA as an ARCH addendum | L0 [F: RCA §13 "Decisions required from the human (L0)"]. ⚠ Its wording names a slot already used (GI-2) [F: L0R L219] |
| **1b acceptance** | acceptance of increment 1b | L0 [F: L0-DEC-05 C4-b *"accepted by L0"*, L0R L137] |
| **H-15** | transcribing F-Series rulings into L0R | [F] precedent: research-side L0 acts were **transcribed by the governance session** at the record's request (L0-DEC-08…10, L0R L203–205). Whether the human must re-confirm them: [O] |
| **H-16** | coverage granularity + operational items 9, 10, 12, 16, 27 | [O]. RCA says the granularity must be "decided" before increment 2 (L361) without naming a decider. Methodology suggests L0 [D] |
| **H-17** | a TDI human-labelled reference set | [O]. Nothing names a decider |
| **H-18** | Phase-1 → Phase-2 translation ownership | L0 [D]: a phase-boundary item (L0-DEC-27 "phase boundaries") |
| **H-4** | look-ahead policy (F-Series) | L0 [D]: methodology. The F rule was approved research-side only (F-LOG-0002; standing = H-15) |
| **H-10** | F-Series write zone / lane | L0 [D]: scope |
| **RC-H-01** | source admission design (per source vs at boundaries) | L0 [F: RCA §13] |
| **RC-H-02** | manifest generation and baseline commit | L0 [F: RCA §13; RCA §5 *"a human approves the baseline"*] |
| **RC-H-06** | Q18 vs Q61 (STATE writer conflict, E-7) | L0 [F: RCA §13] |
| **MP §49 item 4** | who writes `RECONSTRUCTION-STATE.json` | MP classifies it as `[OPERATIONAL]`, *"infrastructure/tooling decision, not a schema gap"* (MP L4700, L4705) [F]. Who decides it is [O]. It may be an in-scope technical choice under L0-DEC-27 [D] |
| *carried* | H-2, H-3, H-5…H-8, H-11, H-12, H-13, HDR-1, HDR-6 | L0 [D] (scope / exceptions), as in the Review |
| *related L0 items* | GIA-6 (NR-1), **GIA-8** (the research session must not commit governance or governance-architecture artifacts), GIA-10 (18 paths), SQ-1/SQ-2, C-5…C-7, B-15 | L0 [F: GIA §11 L154–160; L0R L158–170] |

---

## 2. Dependency graph

**Columns:**
- **Depends on** — what must be settled first.
- **Blocks** — what cannot proceed until this is settled.
- **Indep.?** — Y (no documented prerequisite) · N.

| Decision | Depends on | Blocks | Indep.? | Evidence | Authority |
|---|---|---|---|---|---|
| **GI-1** | — | the L0 standing of ARCH, hence of H-1's option R-B (an ARCH §9 change) [D] and of RC-H-05 (an addendum to ARCH) [D]. Possibly H-18 [O] | **Y** | [F] L0R L218 | L0 |
| **GI-2** (slot collision) | — | the wording of RC-H-05 | **Y** | [F] L0R L219 | L0 |
| **RC-H-05** | GI-2 [F: "no longer names a free slot"]; GI-1 [O: the RCA is submitted as an ARCH addendum, and ARCH's own approval is unrecorded] | the **binding** status of every RCA allocation (RCA §5–§8, §11) [D] | N | [F] RCA L6, §13 | L0 |
| **Independent 1b review** (per artifact) | — | GIA-9 [F: GIA §8 *"1b starts with INDEPENDENT REVIEW → ADOPT/ADAPT/REJECT, not with IMPLEMENT"*] | Y | [F] GIA §8 L121–134 | governance-commissioned [F: GIA §8]; who performs it [O] |
| **GIA-9** | the independent 1b review [F] | 1b [F: GIA §11 "Before 1b: GIA-5 · GIA-9 (after the review) · GIA-10"]. H-14a for the `evidence/` artifacts [D] | N | [F] GIA L71, §8, §11 | L0 |
| **GIA-10** | — | 1b [F: GIA §11] | **Y** | [F] GIA L72, §11 | L0 |
| GIA-5 | — | 1b [F: GIA §11] | — | **decided** (L0-DEC-17) [F] | L0 |
| **RC-H-01** | — | 1b [F: RCA §11 L360, "Precondition RC-H-01, RC-H-02" — **proposed**] | Y | [F-proposed] | L0 |
| **RC-H-02** | — | 1b [F-proposed: RCA L360]; the manifest baseline [F-proposed: RCA §5] | Y | [F-proposed] | L0 |
| **1b implementation** | GIA-9, GIA-10 [F: GIA]; RC-H-01, RC-H-02 [F-proposed: RCA]; H-14a for *who implements* [D] | 1b acceptance | N | as cited | the implementer is H-14a [D]. The GIA's proposed boundary: *"governance session … implement controls after authorization"* (GIA §9) [F-proposed] |
| **1b acceptance** | 1b implementation + independent review, on the 1a pattern [D: L0-DEC-13 sequence 1a.1–1a.7] | **C-5** (the F0031–F0040 correction) [F: L0-DEC-05 C4-b]. Whether it also blocks *new* corpus reads is [O] (§5) | N | [F] L0R L137 | L0 |
| **SQ-1, SQ-2** | — | C-5 [F: L0R L197 "C-5 is blocked on P-1, P-2 and P-5 (SQ-1 and SQ-2 answered)"] | **Y** | [F] | L0 |
| **GI-3** (m1/m2) | — | any §5A revision [F: L0R L254 "Using §5A requires (m1) and (m2) to be fixed first"]; hence the §5A-represented parts of C-5 [F: L0R L230–257] and v1.3-R row 6 | **Y** | [F] | L0, or delegated |
| **C-5 → C-6 → C-7** | 1a accepted (done, L0-DEC-29) · 1b accepted · SQ-1/SQ-2 · GI-3 for its §5A parts | **"F0041+ progression"** [F: SAFE-RESEARCH-EXCEPTION-01 *"forbidden until C-7"*, L0R L179; meaning fixed by L0-DEC-09a] | N | [F] | research executes C-5; a separate session reviews C-6; L0 closes C-7 [F: L0-DEC-05] |
| **H-15** | — | the L0 standing of the F-Series rules underlying H-4 (no-look-ahead), H-11 (population/order) and H-3 (the IDs minted at the human's request) [D] | **Y** | [F] L0R L203–205 precedent | GOV-S transcribes; L0 re-confirmation [O] |
| **H-1** | GI-1, **for option R-B only** [D]. ⚠ No documented prerequisite for R-A or R-C [D] | H-14a's relevance [D: under R-C, F-Series has no machinery]; H-2, H-4, H-10, H-11, H-12, H-13, H-16's F-items [D] | Y, except as noted | [D] | L0 |
| **H-14a** | H-1 [D: moot under R-C]. RC-H-05: **ordering [O]** (§4). GIA-8 [D: an F-owned arrangement would need the research session to commit machinery that GIA-8, if confirmed, forbids] | H-10 [D]; 1b *implementer* [D]; H-16 decider [O]; H-17 owner [O] | N | [D] | L0 [F precedent] |
| **GIA-8** | — | H-14a's F-owned and split branches [D] | **Y** | [F] GIA L70, §11 "Standing" | L0 |
| **H-4** | H-15 [D]; H-1 ≠ R-C [D] | the F-Series traversal rule (Review row 28) | N | [D] | L0 |
| **H-10** | H-1, H-14a [D]; GIA-8 [D] | the Git-CAS commit scope (Review row 8); where MP records are written (MP L4284–4290) | N | [D] | L0 |
| **H-11** | H-1 [D]; H-15 [D] | whether "F3082 first" exists at all | N | [D] | L0 |
| **H-3** | — [D: the registry-membership question arises under every H-1 option] | F3082–F3108 execution; KOS-G-003 would otherwise report dangling IDs [F: L0-DEC-17] | **Y** | [F]/[D] | L0 |
| **H-12** | H-1 [D]; H-3 [D] | reuse of `ledger/F3082/*` | N | [D] | L0 |
| **H-16** | H-1 [D] for the F items. The granularity itself also gates RCA increment 2 [F-proposed: RCA L361] | increment 2 [F-proposed]; Review rows 9, 10, 12, 16, 27 | partly | [F-proposed]/[D] | [O] |
| **H-17** | — | TDI metrics beyond descriptive statistics (Review §7); KOS-G-013 is a REVIEW gate [F] | **Y** | [D] | [O] |
| **H-18** | GI-1 [O: the translation sits at ARCH's ACL] | the Phase-2 intake of an RRP with ORIGIN translation (Review §6) | Y [D] | [D] | L0 [D] |
| **RC-H-06** | — | STATE writes (Review row 20) | **Y** | [F] RCA §13, L317 | L0 |
| **MP §49 item 4** | [O]: possibly H-14a [D] | `RECONSTRUCTION-STATE.json` writes (Review row 21) | [O] | [F] MP L4705 | [O] |
| **New release for corpus reading** | the Research Release Check result [F: L0-DEC-27]; everything that check's five questions cover | **any** corpus read [F: L0-DEC-30 *"No corpus reading … Any further scope needs a new release"*] | N | [F] L0R L451–467 | L0 |
| H-2, H-5…H-8, H-13 | H-1, H-14a [D] | the v1.3-R design points listed in the Review | N | [D] | L0 |

---

## 3. H-1 and H-14a — what each documented option changes (no preference stated)

### 3.1 H-1 options

Documented in the DD review §11 and the Map §G.

| Aspect | **R-A** — F-Series becomes the execution-integrity layer of the existing Phase-1 protocol | **R-B** — F-Series stays a separate Phase-1 protocol | **R-C** — F-Series retires; its population joins the Phase-1 queue |
|---|---|---|---|
| Authority boundary | MP keeps all semantics; F holds none (Review §5) | F would hold Phase-1 semantics beside MP. Requires an **ARCH §9 architecture change** [F: ARCH L484–486] | none for F |
| Execution responsibility | F executes MP under rules others define (Review §5.5: DEFINE UNRESOLVED on 13 rows) | F executes its own protocol | the MP lane executes |
| RCA increments 1b/2/3 | **overlap** with most of the F machinery (Review K-3) | coexist in parallel; the overlap remains [D] | no F overlap; increments proceed on their own terms [D] |
| Governance session | owns binding machinery regardless (L0-DEC-15/18) | same, plus a second protocol to govern [D] | unchanged |
| L0 | decides H-14a and the dependent items | decides an ARCH §9 proposal, and GI-1 becomes material [D] | decides H-3 / H-11 for the 27 non-canonical files [D] |
| Mechanisms affected | all 31 Review rows | all 31, plus the F protocol, state and governance log (the Map §E duplicates) | none. F artifacts become KEEP-H [D] |
| Downstream decisions | H-14a, H-2, H-4, H-10, H-11, H-12, H-13, H-16 (F items) | GI-1, H-14a, H-2, H-4, H-10, H-11, H-12, H-13, H-16 | H-3, H-11 (as registry/queue questions); H-12 (only if the F3082 read is to be reused) [D] |

### 3.2 H-14a arrangements

Documented in the Review §10 and §12.

| Aspect | **Governance-owned** | **F-Series / research-owned** | **Split** |
|---|---|---|---|
| Relation to binding L0 decisions | consistent with L0-DEC-15/18 [F] | runner, gates, door and `admit.py` stay governance-owned **regardless** [F: L0-DEC-15/18]. "F-owned" can cover only the remaining machinery | the split line must respect L0-DEC-15/18 [F] |
| Relation to the RCA (proposed) | matches the RCA's proposed allocation (§5, §8) [D] | diverges from it [D] | partly matches it [D] |
| Relation to GIA-8 (open) | consistent [D] | conflicts if GIA-8 is confirmed [D] | depends on which side commits what [D] |
| Relation to B-15 | resolves the separation question in one direction [D] | [O]: B-15 asks exactly whether research may build this | [O] |
| Who implements 1b | the governance session [D] | the research lane [D] (`evidence/` via GIA-9) | the split line [D] |
| F-Series scope afterwards | the research-agent executor of MP (the Review's 15 F-YES rows at most) [D] | the 15 F-YES rows plus the currently UNRESOLVED-definer rows [D] | between the two [D] |
| Next decisions | RC-H-05, GIA-9, RC-H-01/02, H-10 | GIA-8, GIA-9, H-10, H-16, H-17 | the split specification itself, then as either column |

---

## 4. RCA adoption dependency

| Question | Answer | Tag |
|---|---|---|
| Decisions possible **without** RC-H-05 | GI-1, GI-2, GI-3, GIA-8, GIA-10, SQ-1/SQ-2, H-15, H-17, RC-H-06, H-3, H-1 | [D]: none depends on an RCA allocation |
| Decisions **materially different** if the RCA is adopted | H-14a (the RCA allocates machinery); RC-H-01/02 (they become RCA decisions rather than stand-alone ones); H-16 (increment 2's granularity precondition becomes binding); the audit owner (RCA §8 auditor role) | [D] |
| L0 decisions binding **regardless** | L0-DEC-05/06/07/09a (correction, SQ, safe exception); L0-DEC-15/16/17/18 (runner/gates/door; pins; canonical list; `admit.py` repair); L0-DEC-22 (protocol amendments); L0-DEC-27 (MVG; the Release Check); L0-DEC-29 (1a accepted); L0-DEC-30 (current release) | [F] |
| RCA mechanisms that are **proposals only** until RC-H-05 | the RCI-001…015 invariants; manifest-by-governance (§5); per-source admission (§6); the STOP protocol (§7); roles and write zones (§8); the increment plan (§11); the `research_traversal` field | [F] RCA L6 |
| Can H-14a be decided **before** RC-H-05? | **Not established.** No source orders them. L0-DEC-15 shows L0 can assign authority without adopting the RCA [F]. Whether an H-14a decision would itself adopt part of RCA §5/§8 is [O] | **[O]** |

---

## 5. The 1b dependency

| Layer | Status | Evidence |
|---|---|---|
| **1b exists as a proposal** | yes. RCA §11 increment 1b = *"manifest, hashes, source admission, read receipts, position and coverage in canonical IDs"* (RCI-001…006); GIA §8 gives the transition specification | [F] RCA L360; GIA L121–134 |
| **1b is adopted / authorized** | **no.** L0-DEC-12…16: *"These decisions do not authorize 1b"*. L0-DEC-29: *"authorizes neither 1b nor C-5"*. L0-DEC-30: 1b *"Also not authorized"* | [F] L0R L287, L447, L462 |
| **1b accepted** | no | [F] |
| **1b as a prerequisite for C-5** (the correction) | **yes, binding** | [F] L0-DEC-05 C4-b, L0R L137 |
| **1b as a prerequisite for chronological F0041+ progression** | **yes, transitively and binding**: SAFE-RESEARCH-EXCEPTION-01 forbids "F0041+ progression" until C-7, and C-5 requires 1b | [F] L0R L179 + L137 |
| **1b as a prerequisite for *any* new corpus read** | **not established.** SRE-3 is an *observation*, not a decision: *"no admission control exists yet (1b pending)"*. RCA increment 4 (research continuation) lists increments 1–2 as a precondition but says *"Whether research pauses until then is L0's call"* (proposed). L0-DEC-09a: out-of-sequence safe reads are *not prohibited by the exception* | **[O]** L0R L193, L211; RCA L363 |
| Relationship to **L0-DEC-30** | independent: L0-DEC-30 already forbids all corpus reading within the current release, whatever 1b's status | [F] |
| Relationship to the **safe-research exception** | the exception bars progression until C-7, which needs 1b; it does not bar out-of-sequence safe reads | [F] |
| Relationship to **H-14a** | H-14a decides who *implements* 1b [D]. GIA-9 decides what happens to the existing research-built `evidence/` artifacts [F] | [D]/[F] |

---

## 6. Decisions vs evidence gaps

### 6.1 Human decisions

These can be decided by an authorized process, each per §1.
- H-1, H-14a
- GI-1, GI-2, GI-3
- GIA-8, GIA-9 (after the review), GIA-10
- RC-H-01, RC-H-02, RC-H-05, RC-H-06
- 1b acceptance
- SQ-1, SQ-2
- H-15, H-4, H-10, H-18
- H-2, H-3, H-5…H-8, H-11, H-12, H-13, HDR-1, HDR-6
- a new Research Release (scope)

**Borderline — an established decision whose decider is a gap:** H-16, H-17, and MP §49 item 4. MP itself classifies item 4 as an operational decision; *who* decides it is unestablished.

### 6.2 Evidence gaps

The records do not establish the answer. Nothing here is converted into a proposal.

| # | Gap | What the records do say |
|---|---|---|
| **E-1** | who owns writing `RECONSTRUCTION-STATE.json` | MP L4705: the remaining gap is *"which store hosts this state, and which agent/session boundary owns writing it"*. RCA §8 (proposed) gives research the research state, but names `KNOWLEDGEOS-RESEARCH-STATE.md`, not the JSON |
| **E-2** | who owns an audit of **research output**, as distinct from governance-work verification | B-12 "INDEPENDENT AUDIT · NOT STARTED BY RESEARCH SESSION" (STATE L74). GIA §9: the governance session may not *"resolve B-12 on its own initiative"*. GIA §10: B-12 *"paused"*. RCA §8's auditor role is proposed. L0-DEC-25/28 cover governance verifications only |
| **E-3** | who performs the independent 1b review | GIA §8 requires it; no performer is named |
| **E-4** | who decides H-16 and H-17 | RCA L361 requires the granularity to be "decided"; no decider is named |
| **E-5** | whether an H-14a decision can precede RC-H-05 without implicitly adopting the RCA | §4 |
| **standing limitation** | every L0 record is *"Attestation: UNVERIFIED (AI transcription)"*, and the attested channel is unresolved | RCA §12 L374; every L0R entry |

---

## 7. Earliest safe sequences (derived; alternatives shown, none selected)

**Tier 0 — no documented prerequisites; any order, or in parallel:**

```text
GI-1 · GI-2 · GI-3 · GIA-8 · GIA-10 · SQ-1 · SQ-2 · H-15 · H-17 · RC-H-06 · H-3
+ commissioning the independent 1b review (GIA §8)
```

**Tier 1 — the central pair.** The evidence supports three orders equally ([O], §4):

```text
(α)  RC-H-05  ──▶  H-1 + H-14a
(β)  H-1 + H-14a  ──▶  RC-H-05
(γ)  H-1 + H-14a + RC-H-05 decided together
```

- R-B additionally needs GI-1 settled first [D].

**Tier 2 — dependent on Tier 1 (and on H-15 where marked):**

```text
H-2 · H-4 (needs H-15) · H-10 · H-11 (needs H-15) · H-12 (needs H-3) · H-13 · H-16 · H-5…H-8
```

**The 1b path.** It runs in parallel with Tiers 1–2; its *implementer* depends on H-14a [D]:

```text
independent 1b review ──▶ GIA-9 ─┐
GIA-10 ──────────────────────────┤
RC-H-01, RC-H-02 (per the proposed RCA) ─┴──▶ 1b implementation ──▶ 1b acceptance (L0)
                                                          │
                SQ-1, SQ-2 · GI-3 ────────────────────────┴──▶ C-5 ──▶ C-6 ──▶ C-7
                                                                         │
                                                  "F0041+ progression" permitted by the exception
```

**Then:**

```text
implementation by the party H-14a names ──▶ Research Release Check (L0-DEC-27) ──▶ L0 release with a
corpus-reading scope ──▶ execution
```

For chronological F0041+ execution, C-7 is additionally required [F]. For out-of-sequence safe reads, whether 1b is required is [O] (§5).

---

## 8. Branch consequences (documented alternatives only; not ranked)

| Branch | Who defines | Who implements | Governance machinery affected | F-Series scope | Next decisions | Corpus-execution status |
|---|---|---|---|---|---|---|
| **R-A + governance-owned** | MP (semantics); governance proposes the machinery specs; L0 approves [D] | the governance session (machinery); the research agent executes MP [D] | increments 1b/2/3 as proposed; L0-DEC-15/18 unchanged | executor of MP only [D] | RC-H-05, GIA-9, RC-H-01/02, H-10, H-16 | blocked until 1b and the release (and C-7 for chronological progression) |
| **R-A + F-owned** | MP (semantics); the F lane for the currently UNRESOLVED-definer rows; L0 approves [D] | the F lane, except the L0-DEC-15/18 machinery [F] | diverges from the RCA proposal; GIA-8 conflict if confirmed [D] | the 15 F-YES rows + the UNRESOLVED-definer rows [D] | GIA-8, GIA-9, H-10, H-16, H-17 | same blocks |
| **R-A + split** | as specified by the split [D] | as specified by the split [D] | partial overlap with the RCA [D] | between the two [D] | the split specification, then as above | same blocks |
| **R-B** | the F lane (its own protocol) + MP, after an ARCH §9 change [F: ARCH §9] | the F lane [D] | a second protocol beside GOV (the Map §E duplicates remain) [D] | full F protocol | GI-1, an ARCH §9 proposal, H-14a, H-2…H-13 | same blocks, plus the ARCH change |
| **R-C** | MP | the MP lane | none from F | none; F artifacts KEEP-H [D] | H-3, H-11 (as registry/queue questions) | governed by the MP lane's own blocks (L0-DEC-30, C-7) |

---

## 9. Control-plane boundary check

| Question | Finding | Evidence |
|---|---|---|
| Does an existing release mechanism exist? | **Yes:** the Research Release Check (five questions; GREEN / YELLOW / RED; L0 decides release), which **superseded** L0-DEC-19's seven-item condition | [F] L0-DEC-27, L0R L369–410 |
| Has it been used? | **Yes:** `audits/2026-09-23-RESEARCH-RELEASE-CHECK-01.md` (YELLOW) → L0-DEC-30 | [F] L0R L451–455 |
| Does any decision in §2 require a new gate, release mechanism, control plane, authoritative state machine or registry? | **No.** Every path above ends in the existing Release Check and an L0 release | [D] |
| Is the existing mechanism itself unresolved anywhere? | **Open issues only, no replacement designed:** (a) the attested L0 channel (UNVERIFIED); (b) R2 residuals carried by L0-DEC-27/29 (e.g. IR-A3, R-2 runner code unpinned); (c) GIA-6 namespace rule | [F] L0R L399–402; RCA §12 |

---

## 10. Execution status

| Activity | Current status | Why |
|---|---|---|
| F-Series implementation | **BLOCKED** | H-1 and H-14a unresolved; DEFINE UNRESOLVED on 13 rows (Review §5.5) |
| F0041 | **BLOCKED** | L0-DEC-30 (no corpus reading); chronological progression barred until C-7 (L0-DEC-07, L0-DEC-09a) |
| F3082 | **BLOCKED** | H-3 (non-canonical ID), H-11 (order), H-12 (read standing); plus everything blocking F0041 |
| Governance decisions | **NEXT** | this package sets out their dependencies |
| RCA implementation | **NOT AUTHORIZED** | RC-H-05 unresolved; 1b not authorized (L0-DEC-29, 30) |
| 1b | **NOT AUTHORIZED** | the independent review, GIA-9 and GIA-10 are prerequisites (GIA §11) |
| C-5 correction | **BLOCKED** | 1b acceptance, SQ-1/SQ-2, GI-3 (L0-DEC-05; L0R L197, L254) |
| Research Release for corpus execution | **NOT AVAILABLE** | the current release (L0-DEC-30) is Critical Attack Pass 01 only |

---

## 11. Decision agenda for human review

Decisions only, with dependencies, evidence needed, and what stays blocked. No answers.

| # | Decision | Depends on | Evidence needed | Blocked until resolved |
|---|---|---|---|---|
| 1 | **GI-1** — L0 standing of ARCH (v1.0 / v1.1 / v1.2 Addendum B) | — | L0R L218; ARCH header | R-B; RC-H-05's premise; H-18 [O] |
| 2 | **GI-2 + RC-H-05** — the RCA's addendum slot; adopt or reject the RCA | GI-2 → RC-H-05; GI-1 [O] | RCA; L0R L219 | the binding status of all RCA allocations |
| 3 | **H-1** — F-Series role | GI-1 (R-B only) | DD review §11; the Map §G; this document §3.1 | H-14a relevance; H-2, H-4, H-10…H-13, H-16 |
| 4 | **H-14a** — DEFINE / CHANGE / APPROVE / EXECUTE of execution-assurance machinery | H-1; ordering with RC-H-05 [O]; GIA-8 | the Review §5; §3.2, §4 here | the 1b implementer; H-10; F implementation |
| 5 | **GIA-8** — the research session must not commit governance artifacts? | — | GIA L70 | H-14a's F-owned and split branches |
| 6 | **Independent 1b review → GIA-9** (and **GIA-10**) | the review before GIA-9 | GIA §8, §11; `evidence/README.md` | 1b |
| 7 | **RC-H-01, RC-H-02** — admission design; manifest baseline | — | RCA §5–§6 | 1b (per the proposed RCA) |
| 8 | **1b acceptance** | items 6, 7; implementation per item 4 | a 1a-style review record | C-5 → C-7 → F0041+ progression |
| 9 | **SQ-1, SQ-2** · **GI-3** | — | L0R L164–165, L244–254 | C-5; every §5A revision |
| 10 | **H-15** — transcribe the F-LOG rulings into L0R | — | L0R L203–205 precedent; `F-GOVERNANCE-LOG.md` | H-4, H-11 (their standing) |
| 11 | **H-3** — F3082–F3108 registry membership | — | the Map §F; L0-DEC-17 | F3082–F3108 |
| 12 | **H-4, H-10, H-11, H-12, H-13, H-2, H-5…H-8** | H-1 / H-14a / H-15 / H-3 as in §2 | the Review; the Design | the corresponding F design points |
| 13 | **H-16, H-17** — coverage granularity; TDI reference set | their deciders are E-4 | Review §3, §7; RCA L361 | increment 2; TDI metrics |
| 14 | **H-18** — translation-contract ownership | GI-1 [O] | Review §6 | Phase-2 intake with ORIGIN translation |
| 15 | **RC-H-06**, and **MP §49 item 4** (decider: E-1) | — | RCA L317; MP L4705 | STATE and `RECONSTRUCTION-STATE.json` writes |
| 16 | **A new Research Release** with a corpus-reading scope | the Release Check (L0-DEC-27) over whatever items 1–15 settle | a Release Check record | any corpus read |

**Evidence gaps to clarify alongside (not decisions):**
- E-1: who writes `RECONSTRUCTION-STATE.json`;
- E-2: who owns an audit of research output;
- E-3: who performs the 1b review;
- E-4: who decides H-16 and H-17;
- E-5: the H-14a / RC-H-05 ordering;
- the L0 attestation channel.

*Stop. No decision, recommendation, redesign, implementation or corpus read follows from this document.*
