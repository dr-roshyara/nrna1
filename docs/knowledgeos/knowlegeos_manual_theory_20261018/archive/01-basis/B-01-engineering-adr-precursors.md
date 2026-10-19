# B-01 — Engineering ADRs as a candidate precursor of the theory's basis

| Field | Value |
|---|---|
| Read | `engineering/architecture/adr/` — all 4 files, **full** (logged in `00-access/READ-LOG.tsv`) |
| Standing of these files | **not** KnowledgeOS corpus files (outside the 2,779-record registry). They are the governance record of the PublicDigit (AI) Engineering Platform, 2026-07-08 … 2026-08-04 |
| Status of this note | extraction **[SRC-ADR]** + interpretation **[I]** + one hypothesis **[H]**. Nothing here is yet a reconstructed KnowledgeOS claim |

## 1. What the files are

| File | Content | Date |
|---|---|---|
| ADR-AIP-01 | Engineering Platform Baseline v1.0; mission *"evidence, never authority"* (l.19); "Baseline" = stable → validated → may evolve by amendment; frozen artifacts change only by supersession (l.42) | 2026-07-08 |
| ADR-AIP-02 | AIP-14 Product Primacy: the platform exists only to deliver the product; platform-only iterations need ARB approval | 2026-07-08 |
| ADR-AIP-03 | BC-7 Governed Session Orchestration recognized; invariants I-1 single mutation owner, I-2 handoff + human START, I-3 sticky STOPPED, I-10 *record outranks prose*; I-4 *registry ≠ authority*; "one capability identifier = one semantic owner" | 2026-08-17 |
| ADR-AIP-LOG | rulings R-30 … R-100 (R-1…R-29 historical in Phase-02.5 §6) | 2026-07-08 … 2026-08-04 |

## 2. Principles stated explicitly in the ADRs (exact anchors)

| # | Principle (paraphrase; quotes marked) | Anchor |
|---|---|---|
| E-1 | AI drafts carry *authority: generated*; only a **Human Decision Event** moves an artifact beyond draft | AIP-01 header, l.49 |
| E-2 | Mission: *"evidence, never authority"* | AIP-01 l.19 |
| E-3 | Three classes of review information — **observations · decisions · implemented changes**; suggestions are not rulings; default class is *observation*; **silence is not adoption** | R-34; R-47 |
| E-4 | Epistemic labels **Observed · Measured · Derived · Interpreted · Recommended** on all architectural statements | R-36 |
| E-5 | *Implementation evidence outweighs unverified theory*; stop at uncertainty, never silently invent | R-36 |
| E-6 | Burden of proof reversed: expansion needs **demonstrated insufficiency**; new terms need **demonstrated, not anticipated, ambiguity** | R-37; R-80 |
| E-7 | **Append-only history**: decision text never rewritten; corrections are annotations; withdrawn rows retained; retired numbers never reused | R-41; R-53; R-62; R-90 |
| E-8 | One **authoritative lifecycle state** per artifact at any moment; plans hold the present, logs hold the sequence | R-41 |
| E-9 | **Supersession only by explicit act, never inference**; "not shown incompatible" ≠ "compatible" | R-77 |
| E-10 | **Prepared ≠ adopted**; no self-adoption; no standing delegation | R-81…R-86; R-88 |
| E-11 | **Permission ≠ authorization ≠ commissioning ≠ execution** | R-79; R-80 |
| E-12 | A recorded marker means exactly what was observed: *"a request was made"* ≠ *"issuance confirmed"* | R-76; R-82 |
| E-13 | Evidence producer never accepts its own work; evidence submission is separate from the governance outcome | R-71; R-91 (held) |
| E-14 | **Undetermined is a legitimate result**: "FALSE or UNSUPPORTED remains UNDETERMINED"; "reproduction impossible" is a finding | R-50; R-53 |
| E-15 | **Registry ≠ authority**; **recorded state overrides narrative** | AIP-03 I-4, I-10 |
| E-16 | Missing home is **named and returned**, nothing invented | R-96 |
| E-17 | A count is operational inventory, not scope | R-99 |

## 3. Corpus link (mechanical pattern counts over the 2,734 frame files; logged; no content displayed)

| Pattern | Files | Earliest by source ID |
|---|---|---|
| `ADR-AIP` | 52 | S0011 (2026-06-25), S0013 (2026-07-11) |
| ruling ids `R-30…R-100` (regex; may over-match) | 172 | — |
| `EKS` | 251 | S0048 (2026-08-16) |
| "record outranks / recorded state overrides" | 9 | S0067 (2026-08-17) |
| "evidence, never/not authority" | 11 | S0130 (2026-08-19) |

**[FACT, mechanical]** The corpus cites the engineering ADRs from its earliest files onward.

## 4. Interpretation and hypothesis (to be tested, not adopted)

- **[I]** Several E-principles have the same shape as reported KnowledgeOS theory constructs (all second-hand until the primaries are read): E-2/E-3/E-13 ~ AX-1 *Proposal ≠ Decision, Evidence ≠ Knowledge*; E-7/E-9 ~ AX-4 non-monotonicity with preserved history and AX-5 provenance; E-14 ~ UNKNOWN preservation (H-ZERO-001); E-12/E-15 ~ FalseZero (*claims are auditable assertions, not status fields*).
- **[H-B1]** *KnowledgeOS theory began as a generalization of the governance discipline practised in the Engineering Platform (via EKS, the Engineering Knowledge System), and only later acquired its mathematical and philosophical layers.*
  - **Supports it:** the citation counts above; the corpus's earliest dated files (June–August 2026) are engineering-knowledge documents.
  - **Would refute it:** early corpus files defining the core distinctions independently of, or before, the platform; or theory-layer documents that never connect back to EKS/platform concepts.
  - **Test:** read the earliest root and EKS documents (S0011, S0013, S0048, S0188 *what-eks-is-today*, S0210/S0211 Conceptual Foundation) and trace each E-principle's first appearance.
