# Settlement of window C20 (S0106–S0110) and of the F22 = (C + O) application

*2026-10-21. By the Professor. One window reviewer, W (Opus), in the normal window form: a blind inventory first, then the comparison and the validity review. Its object was §8.13 (new), the application of F22 = (C + O) as changed theory (the Principal, 2026-10-21), and the C19 settlement's rows (Rulebook §2 step 9).*

## Reports, saved verbatim, with their sha256 at hand-back

| Report | File | sha256 at receipt |
|---|---|---|
| W, step 1 (blind inventory) | `STEP1-INVENTORY.md` | `68c840b64c127f7bd0efc70e2457a2d76fc1f49bac8468fb009e34666b632ad8` |
| W, steps 2–3 | `REVIEW-REPORT.md` | `926766c93463788016949612a4fbbf2f74b2644dbc22803356de6940b4ba777a` |

The reviewer re-read its saved inventory and recomputed its hash, which matched.

## 0. Deliverable status

**Met.**
- S0106–S0110 were read in order, all at depth full, each started and closed in turn.
- §8.13 is settled as v2:
  - CON-0202, 0203, 0204 and 0206 are provisional, restated where the review required;
  - CON-0205 is INVALID as stated and is restated as CON-0207.
- F22 = (C + O) is applied and repaired: §9.16 v6 (R4a), §9.13 v11, §9.15 v8, model `ce_model` v12.
- **Two questions go to the Principal** (§4).

No critical finding. The reviewer's 14 findings break down as:
- **7 high:**
  - four on the F22 application (W-1 to W-4);
  - three on §8.13's links to the kernel (W-6, W-7, and W-8 by its content).
- **7 normal.**

## 1. Measures (per source)

| Source | Lines | Time (start → stop) | Depth (Professor / reviewer, blind) | Professor's findings | Review findings bearing on it | New concepts |
|---|---|---|---|---|---|---|
| S0106 | 69 | 12 s | full / full (borderline in both) | 2 | coverage (L45, L59) | — |
| S0107 | 118 | 13 s | full / full | 5 | W-3 via S0071, coverage (L16, L53, L67–68, L85) | — |
| S0108 | 53 | 11 s | full / full | 3 | W-6, coverage (L8, L24) | CON-0207 (with S0109) |
| S0109 | 137 | 11 s | full / full | 4 | W-6, W-7, W-8, W-10, coverage (L84, L114, L125) | CON-0202, CON-0203, CON-0204 |
| S0110 | 48 | 14 s | full / full | 3 | W-8, W-9 | CON-0206 |

- **Time.** The time is stop minus start from the READ-LOG. It measures one whole-file read plus the close by this reader, not reading effort.
- **Reviewer time** (harness): step 1, 75 s; steps 2–3, 345 s.
- **Correction (the Principal, 2026-10-21, after C21; additive):** the times above are **not** a measurement of time per source. They are the gap between the start call and the close call in the tool. From C22, a source is started when opened and closed after its theory is written into the book.

- **Skim.** None was skimmed, and the two blind judgements agree on all five sources. S0106 is borderline in both. There is still no skimmed source in 109, so the §3 safeguard (read one skimmed source blind) could not be applied. **The C19 settlement's plan, to take one from an earlier window, could not be met either**: there is none. In its place the reviewer judged every source's depth blind. *A deviation from §3, recorded.*

## 2. Findings and their disposition

| ID | Finding | Sev. | Decision and correction | Resolved when | Holds? |
|---|---|---|---|---|---|
| W-1 | one U transfer freezes the grant for good; the remedy "unless ownership is moved" fails | high | Accepted. **The Professor's choice:** the owner is set by the **last** transfer that is not F (T: its new owner; U: undetermined; none: the owner at issuance). A later authorized transfer ends the undetermined state | check 24h (the reviewer's W2b: T at append, U after its basis ends at the same act time, then a move restores use) | **yes** |
| W-2 | a transfer's authority is not tied to the grant it moves; who may transfer is normative | high | Accepted in two parts. **Technical:** a transfer's scope must equal its grant's scope (guard), check 24i. **Normative, returned to the Principal (§4):** who may transfer | check 24i; §4 item 1 | **yes**; decision with the Principal |
| W-3 | the declared application field is trusted with no assumption | high | Accepted. §9.15 **L10** (authenticated recording application), of L2's type; G4 cites L9 and L10 | check 24k shows the dependence; §9.15 L10 | **yes** |
| W-4 | "not an evaluation of CE*" is false | high | Accepted. Restated: the owner check evaluates CE* for the grant's transfers. What is smaller than (C + K) is said precisely | R4a, K4-k, CON-0194 | **yes** |
| W-5 | the owner mark judges a past use by present values | normal | Accepted. The mark reads the owner at the use's cut | check 24j | **yes** |
| W-6 | CON-0205 contradicted in the window: S0109 L28 writes the selection without deciding | high | Accepted. CON-0205 INVALID as stated; **CON-0207**: the origin of a selection, not the writing of it, makes the decision; a transcription case, not P-perf | §8.13 C4, register | **yes** |
| W-7 | C2's kernel Ded is false on D as it stands, and tests the wrong property | high | Accepted. The Ded is withdrawn. Restated: D has referent gaps by design (forward targets; application names); the source's gap is a performer or owner gap | §8.13 C2, CON-0203 | **yes** |
| W-8 | C1: a citation does assert something, namely existence and order | normal | Accepted. "A reference asserts existence, not correctness" | §8.13 C1, CON-0202 | **yes** |
| W-9 | C5's "CE* agrees" overstated | normal | Accepted. Scoped: no later-wins clause for adoptions; no conflict state; a revocation is item-wide | §8.13 C5, CON-0206 | **yes** |
| W-10 | CON-0202 merges claim-scoping (CON-0154) | normal | Accepted. Pointed to CON-0154 | register | **yes** |
| W-11 | "read field" has two senses | normal | Accepted. Qualified in R4a: identity fields vs fields Φ reads | R4a | **yes** |
| W-12 | "use" = consumption rests on R4's option text; its consequence should be confirmed | normal | Accepted. Source corrected (R4's option). **Confirmation returned to the Principal (§4)**, since the word is the Principal's | R4a; §4 item 2 | **yes**; confirmation with the Principal |
| W-13 | the second cost needs L9 | normal | Accepted | R4a | **yes** |
| W-14 | lineage count; S0071 is adverse on the transfer point; S0137 L172 is Int | normal | Accepted. Two lines of support; adverse prior stated; Int labelled; the record-order asymmetry with endings stated | R4a | **yes** |

**Coverage gaps named by W, now in §8.13:**
- taint without retraction (S0107 L53, S0106 L59);
- disclosure is not cure (S0107 L16);
- a required property with open content (S0109 L125);
- a decision-authority boundary, not a refusal (S0108 L8);
- a refusal is evidence and not clearance (S0106 L45, S0107 L68);
- NOT ATTESTED, never PASS (pointer to §8.12 C1);
- the within-source supersession with its diff (S0109 L114).

**Not added, low:**
- necessary is not sufficient for a bounded context, and schema follows semantics (S0109 L84, L67): not kernel theory;
- "selected" folded into "delivered" (S0108 L24): the distinction chain is already CON-0154's neighbourhood.

**Source slips** (listed separately, not relied on):
- S0106 L15 says "fifteen deliverables" against a table of 13 rows;
- the two "four times" counters of S0109 L28 and S0110 L38 count different things.

**Appendix items:**
- the §9.13 tool pointer is corrected (v12);
- the EXT-090/091 homes now read "R4a";
- the owner default 'A0' in the model is stated as a limit.

**C19 settlement rows:** the reviewer spot-checked eight rows (W-3, W-4, W-9, W-10, W-13, T1-03, T1-08, T2-13), and all are met where the settlement says. The other rows were not re-checked; that limit is recorded.

**Verdicts:**

| Concept | Verdict |
|---|---|
| CON-0202, CON-0203, CON-0204, CON-0206 | PROVISIONAL (restated where noted) |
| CON-0205 | INVALID as stated |
| CON-0207 | new, PROVISIONAL |
| CON-0191, CON-0193, CON-0194 | PROVISIONAL, as repaired |

## 3. Checks run on the corrections

- **Model** `ce_model.py` v12: all assertions passed (`ce_model-run-20261021-v12.txt`). New checks:
  - 24h (the fold past a U transfer);
  - 24i (the transfer's scope);
  - 24j (the mark at the cut);
  - 24k (the dependence on L10);
  - 24l (300 random append sequences with transfers: 157 uses accepted, none recorded by another than the owner at its cut, none marked).
- **Quote check**, 0 not found in each changed section:
  - §8.13: 30 found;
  - §9.13: 10, §9.15: 13, §9.16: 21.

  Whole book: 14 NOT FOUND lines, equal to the baseline.
- **R1:** 183 rows with a home; 1 inherited flag (CON-0133). CON-0205 carries its invalid tag in §8.13.
- **R2:** no new candidates in the changed sections.

## 4. Returned to the Principal (normative)

1. **Who may transfer a grant's ownership?** The decision says "a recorded, authorized act" and does not name the authority. Under the present technical rule, any authority covering the grant's scope may move it, including the grant's holder citing the grant itself. Options:
   - **(i)** the issuer of the grant;
   - **(ii)** the holder;
   - **(iii)** the current owner (S0071 DP-3; it cannot replace an absent owner, against the stated cost);
   - **(iv)** any authority over the grant's scope (the present rule);
   - **(v)** the root only.
2. **The scope of "a use".** Is it a **consumption** (the model; R4 v4's option text said "for its consumptions")? Then the owner controls one event per grant, and adoptions under the grant are not checked. Or is it **any act under the grant**?

*Decided by the Professor and marked for review (the standing instruction):*
- the fold past a U transfer;
- the transfer's scope;
- the mark at the use's cut;
- L10;
- "recorded" = at the kernel;
- kernel record order.

## 5. Open items

| Item | Role | Next action |
|---|---|---|
| who may transfer; the scope of "use" | **Principal** | decide (§4) |
| P (conjunctive or disjunctive) | Principal (reserved) | unchanged |
| removal tied to a removal activity over the target's scope | Professor | next model revision |
| a soundness check for act marks (T1-16); the tighter marks rule (§8.13 C3) | Professor | next model revision |
| a correction kind for attribution in D (§8.13 C7) | Professor | when a later source bears on it |
| how Q1 answers for a reference with no referent (§8.13 C2) | Professor | open question |
| the inheritance-channel fork | Professor / Principal | when a later source bears on it |
| §9.15: a gate's bypass and the timing of evaluation | Professor | next §9.15 review |
| the skim safeguard cannot run while nothing is skimmed | Professor | report to the Principal; continue blind depth judgements |
| reading | Professor | C21 (S0111–S0115) |
