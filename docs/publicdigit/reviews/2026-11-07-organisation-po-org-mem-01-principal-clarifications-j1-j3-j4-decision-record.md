# PO-ORG-MEM-01 addendum: Principal decisions J-1, J-3 and J-4, decision record

**Status:** **DECIDED by the Principal, 7 November 2026.** This record is a linked addendum to the PO-ORG-MEM-01 decision record. It does not rewrite that record: Decision A and Decision B stand exactly as recorded there. This record authorises no implementation, test, production check, new PO or ARB decision, or ODA-01 edit (§5).

**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/*-decision-record.md` convention.
**Baseline:** branch `election-flow-updated`, HEAD `e59ab47b7`.
**Recorded by:** Claude (Architecture Session B: Membership and Committee), on the Principal's instruction "Principal Instruction — J-1, J-3, J-4 and ODA-01 v1.3 Preparation" (7 November 2026). The recorder records the acts; it does not perform them.
**Linked record:** `docs/publicdigit/reviews/2026-11-03-organisation-po-org-mem-01-membership-ownership-decision-record.md` (PO-ORG-MEM-01, "POMEM").
**Questions raised by:** `IR-ODA-01-v1.2-review-fresh-claude-session.md` §C.1 (J-1, J-3, J-4). That review is a Claude-family review. It does not satisfy the human / non-Claude independent-review requirement.

---

## 1. Four kinds of statement, kept separate

| Kind | Where | Changed by this record? |
|---|---|---|
| Original approved decisions | POMEM §1 (Decision A, Decision B, Constraints, final status line), 3 November 2026 | **No** |
| Principal clarifications and confirmations | this record, §2 | new |
| Factual verification by a Claude session | this record, §3 | new (records an earlier check) |
| Documentary correction | constitutional-authority preparation document: the §7.3 D-4 row under J-4; then, on later Principal approvals of 7 November 2026, five further D-4 attribution references, the §2 "Decisions relied on" row, and the formatting of the corrupted header Status cell (§4) | authority attribution and formatting only, with a dated correction note. D-4's wording, scope, conditions and interim status are unchanged |

## 2. Principal decisions

### PO-ORG-MEM-01-J1: scope of Decision A

| | |
|---|---|
| **Decision (Principal)** | **Membership decisions only.** The sentence "A competent organisational organ or authorised office holder makes Membership decisions within its constitutional competence or valid delegation" applies specifically to Membership decisions. It does not establish a general rule for all decisions concerning the Organisation's internal affairs. |
| **Date / authority** | 7 November 2026 · Principal |
| **Nature** | a Principal clarification of the intended scope of Decision A. The original wording is unchanged. |
| **Affected record** | POMEM §1, Decision A (third paragraph) |
| **Consequence** | 1. The exact scope of Decision A is preserved. 2. It is not widened into a general organisational decision-making principle. 3. It is not narrowed to organs: both competent organisational organs **and** authorised office holders remain covered. 4. Nothing here means that the Organisation bounded context owns the Constitution. 5. Decision A does not settle the interpretation of ACT §11. 6. Other organisational decision-making authority remains subject to its own constitutional evidence, valid delegation and authorised decisions. |
| **Scope boundary** | settles the scope of Decision A only. Constitution ownership and representation stay with the ARB (POMEM §1 "Interpretation boundary", §3). |
| **Related finding** | IR12-F1 (fresh-session review), J-1 |

### PO-ORG-MEM-01-J3: confirmation of Decision B and its Constraints

| | |
|---|---|
| **Decision (Principal)** | **CONFIRMED.** The original Decision B message and its Constraints are authentic. The committed POMEM record (commit `c632fe45d`) reproduces Decision B and its Constraints accurately. The original final status line ("PO-ORG-MEM-01 APPROVED; DR-07a M-1–M-5 AUTHORISED") is also confirmed. |
| **Date / authority** | 7 November 2026 · Principal |
| **Affected record** | POMEM §1 (Decision B, Constraints, final status line) and its 2026-11-06 fidelity note |
| **Consequence** | The fidelity note's limit ("cannot be checked against a committed original") is answered by the Principal's confirmation of authenticity, not by the textual comparison (§3). Decision A and Decision B are unchanged. |
| **Scope boundary** | The DR-07a M-1 to M-5 authorisation is not broadened. This confirmation does not authorise DR-07a implementation, code changes, tests, production checks or further architectural decisions. The Decision B requirement of independent review before DR-07a findings are used still applies. |
| **Related finding** | IR12-F4 (fresh-session review), J-3; ODA-M-1 |

### PO-ORG-MEM-01-J4: PO / Human ARB capacities and the D-4 attribution

| | |
|---|---|
| **Decision (Principal)** | **PO and Human ARB were the same person acting in two different capacities.** D-4 was decided in the capacity of **Human ARB, as or on behalf of the Governance owner**. It was not a PO decision merely because the same individual also held the PO capacity. |
| **Date / authority** | 7 November 2026 · Principal |
| **Authoritative source for D-4** | `docs/publicdigit/reviews/2026-10-09-membership-slice1b-acceptance-review-and-slice2-decision-closure.md:293`: "**DECIDED** (by the Human ARB as, or on behalf of, the Governance owner)". Section heading `:284`: "Decision record: D-1 … D-5 (Product Owner / Human ARB, 2026-10-11)". |
| **Consequence** | The distinction between the two capacities is preserved. The "PO DECIDED (interim)" attribution in the preparation document §7.3 is corrected (§4). |
| **Scope boundary** | a correction of who decided D-4 and in which capacity. **Not a new D-4 decision.** D-4's wording, scope, conditions and consequences are unchanged. D-4 is not extended to committee-association approval or any other authority. (The interim owner / admin restriction for committee-association reviews is a separate interim decision, SEC-DEC-01 P3-R2, explicitly not an extension of D-4.) |
| **Related finding** | IR12-F14, IR12-F13 (fresh-session review), J-4; ODA-M-4a |

## 3. Textual comparison (factual verification, not independent)

- **Who and when:** Claude, Architecture Session B, 7 November 2026. *Correction to the instruction's wording:* the instruction attributes this comparison to Session A. Session A's earlier check (session log 2026-11-06) compared the header and Decision A only, against the first version of the record. The full comparison was made by Session B, the session that wrote POMEM.
- **Method:** the Principal's original message (this session's transcript, 2026-11-03 08:19:24 UTC) was compared word by word with the quoted §1 of POMEM at `c632fe45d`, after Markdown markup and bullet characters were removed.
- **Result:** 287 source words, 283 record words. The only difference is the four words of the pasted-text wrapper tags around the message.
- **Limit:** the comparison was made by the author of the record, from a transcript that is not committed. It is **not** independent verification. Authenticity rests on the Principal's confirmation (J-3), not on this comparison.

## 4. Documentary correction made under J-4

| Record | Location | Before | After |
|---|---|---|---|
| `docs/publicdigit/architecture/reviews/2026-11-03-organisation-membership-constitutional-authority-discovery-preparation.md` | §7.3 table, D-4 row (line 299) | `PO DECIDED (interim)` | `Human ARB DECIDED (interim), as or on behalf of the Governance owner` |

A dated correction note follows the table. Further occurrences were first reported, not changed (§6). On the Principal's later approval of 7 November 2026 ("Complete D-4 Attribution Corrections and Documentation Repair"), the five occurrences in the preparation document were corrected in the same way; the POMEM line was left unchanged. On a further Principal approval of 7 November 2026, the preparation document's §2 "Decisions relied on" row (line 39) was also corrected: it separates D-4 from the PO decisions and attributes D-4 to the Human ARB, as or on behalf of the Governance owner. This is a documentary correction, not a new decision.

## 5. Not authorised by this record

Editing ODA-01 v1.0, v1.1 or v1.2; creating ODA-01 v1.3; new PO or ARB decisions; any substantive change to D-4; reopening the ratified Membership architecture; code, schema, test or implementation changes; commits or pushes.

## 6. Further occurrences reported (preparation-document rows since corrected on approval, §4)

Line numbers are those at the time of the report.

| Record | Line | Text | Kind |
|---|---|---|---|
| preparation document | `:24` | "The PO has already fixed … (D-1, D-2, D-3, D-4 interim, …)" | same wrong attribution |
| preparation document | `:92` | "the PO-decided interim adapter D-4" | same wrong attribution |
| preparation document | `:104` | "Does the principle change any existing PO platform rule (D-1…D-5, …)?" | collective label that includes D-4 (relates to IR12-F11 / ODA-M-18) |
| preparation document | `:449` | OQ-7 "Conflicts between statutes and D-1…D-5 …", authority "PO" | conflict authority for D-4 (relates to IR12-F11) |
| preparation document | `:538` | "PO decisions: D-1…D-5 (…:290-296)" | collective label that includes D-4 |
| POMEM | `:77` | "conflicts with D-1…D-5, E-1…E-5, GOV-END B1" | no authority attribution; listed because IR12-F14 cites it |

## 7. Traceability

POMEM §1–§3; `IR-ODA-01-v1.2-review-fresh-claude-session.md` §C (IR12-F1, F4, F13, F14), §C.1 (J-1, J-3, J-4), §E (ODA-M-1, ODA-M-4a); slice-2 decision closure `:284`, `:293`; SEC-DEC-01 decision record §1 (P3-R2).
