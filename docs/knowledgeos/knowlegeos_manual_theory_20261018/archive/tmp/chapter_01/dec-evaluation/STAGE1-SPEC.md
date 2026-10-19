# Stage 1 clean-record decide-or-escalate core: specification (proposed; not implemented)

**Authorization:** Principal, 2026-10-18: *"Authorize Stage 1 clean-record DEC; keep C1 open."*
- Specification only. **No implementation until a separate authorization.**

**Tags:**
- [Src] corpus;
- [Repo] repository;
- [RV] reviewer-verified (review 2);
- [Ded] deduction or proof here;
- [Asm] assumption;
- [Pol·ID] open governance policy;
- [Des] proposed design choice;
- [Obs] store observation (census 2026-10-18).

---

## 1. Inputs and provenance

| Input | Content | Provenance |
|---|---|---|
| **Record prefix `E_k`** | entries `(pos, ê)`; ê is the act as recorded. Each model field is a value or **⊥** | the KnowledgeOS authority store (BC-7 workflow records) plus committed act texts [Src S0071] |
| **Open policy set Π** | the reserved parameters **not yet decided by a recorded ARB act**: C1 ∈ {b, a_tau, a_rho}; RD 1 (claim kind × ending kind); δ; RD 6 υ; C7 π; C10; C6; C14; C15; BAR; BASIS; ιc | register + TODO [Pol] |
| **ARB acts** | recorded acts that fix a parameter. They remove values from Π | G-2/R5b; ES-001.2 [Src/Repo] |
| **Question φ** | `Valid(e)` · `Current(c, t)` · `Elig(e, p, w)`. **Not** "not authorized"; negatives are out of scope | Stage 1 scope [Des] |

## 2. Model-required fields and completeness

**Required fields** `Req(x, φ)`: the fields of record x that the semantics of φ reads (ALM-R2-SPEC S-4 definitions).

| Kind | Required for Valid / Current | Additionally for Elig |
|---|---|---|
| any act | kind, holder, τ | — |
| any act, under C10 = given | ord, **if another relevant act has the same τ** | — |
| any act, under C1 = a_* | ρ (record time) | — |
| GRANT | grantee, period (from and until), scope as **atoms**, proviso, **issuer = holder** | bars |
| REVOKE, CONFIRM | target | — |
| ADOPT | subject, ckind, cperiod | — |
| END | target | — |
| PERFORM | activity, work | performer (of e and of every implementer of w) |
| ACCEPT | work | — |

**[Des]** A field the store schema does not contain counts as **⊥ for every record**. **A record is never complete because the store lacks a field.** This is the Principal's check (b).

**Relevant records** `Rel(φ, E_k)`: the least set that contains the subject act of φ and is closed under "could affect", where **⊥ matches anything** (the wildcard rule). Rel includes:

| Category | Members |
|---|---|
| potential bases | every GRANT whose grantee is ⊥ or equals the holder of a member act |
| the issuer chain of those grants | their GRANT acts' holders, recursively |
| endings and confirmations | every REVOKE or CONFIRM whose target is ⊥ or a member grant |
| claim endings | every END whose target is ⊥ or the claim in φ |
| IND-1 / IND-2 implementers | every PERFORM implement whose work is ⊥ or equals the work in question (IND-1 for ACCEPT, IND-2 for Elig) |
| C6 rivals | every ADOPT whose subject or ckind is ⊥ or equal |

**Clean(φ, E_k)** holds iff, for every `x ∈ Rel(φ, E_k)`:
1. all of `Req(x, φ)` are non-⊥;
2. x has no contradictory duplicate entries;
3. no two members of Rel have the same τ with ⊥ ord (C10);
4. **every member of kind REVOKE, END or CONFIRM has Auth = T on E_k** (its authority is on record).

## 3. The decision rule

```
Decide(φ, E_k)  ⟺  Clean(φ, E_k)  ∧  ∀ θ ∈ Π :  φ(H_k ; θ) = T
```

Here `H_k` is the **single** closed-world history formed by the records in `E_k`. Clean guarantees exactly one world. φ is evaluated with ALM-R2-SPEC S-4 (V layer) under θ. **Otherwise: escalate** with reasons (§4).

**Why each condition is there:**

| Condition | Purpose | Source |
|---|---|---|
| (1)–(3) | no guessing about unknown facts | removes the review-2 false positives N1, N2, R2-04, R2-14 [RV/AR] |
| (4) | under an open RD 6, an ending whose authority is not on record must not be silently ignored | N3 [AR]; RD 6 open |
| `∀θ` | **non-usurpation** | Principal requirement |

**On necessity [Ded]:** the conditions are **sufficient** (§6, Th-S1) and **deliberately not necessary**. Some cases with ⊥ fields are in fact determined, and Stage 1 escalates them anyway. *The rate of such unnecessary escalations is unmeasurable on the current store,* because every case there is unclean (§8).

## 4. Escalation reasons

A result may carry several reason codes. Each code cites entry positions and a spec clause.

| Code | Class | Condition (precise) | Justification shown |
|---|---|---|---|
| `FIELD_UNKNOWN(x, f)` | **evidence gap** | `x ∈ Rel`, `f ∈ Req(x, φ)`, `ê(x).f = ⊥` | the entry `pos(x)`; "the field f is required by clause …" |
| `ORDER_UNKNOWN(x, y)` | evidence gap | condition 3 | the entries for x and y |
| `CONTRADICTION(x)` | evidence gap | condition 2 | both entries |
| `ENDING_AUTHORITY_NOT_ON_RECORD(x)` | evidence gap **and** policy (RD 6) | condition 4 | x and its missing basis. "Whether this counts is reserved decision RD 6" |
| `POLICY_DEPENDENT(P, sat, unsat)` | **policy dependence** | **only computed when Clean.** There exist θ₁, θ₂ ∈ Π with φ = T under θ₁ and φ ≠ T under θ₂. **P** = the minimal set of parameters whose values separate them; **sat** / **unsat** = their values | "Under ARB choice … KnowledgeOS could decide; under … it could not" |
| `NO_AUTHORITY_ON_RECORD` | **not established** | Clean ∧ ∀θ: φ = F on `H_k` | "**The records do not establish authority. This is not a finding that the act was unauthorized**: a grant may exist unrecorded" [RV: no sound negatives under an open world] |
| `OPEN_WORLD` | caveat | always attached to every result, positive or not, unless completeness is presumed (§7) | "Conclusions are as of record position k" |

**Rules:**
- Evidence-gap codes are always computed.
- `POLICY_DEPENDENT` and `NO_AUTHORITY_ON_RECORD` are computed **only for clean cases**. For unclean cases the output says "policy dependence not assessed: evidence incomplete". **No claim is made.**
- **AI's role:**
  - for evidence-gap codes, AI may **search for the missing records** and propose where they could be found. Any evidence it finds enters only as **new recorded entries** through the governed path, **never** as an assumption;
  - for `POLICY_DEPENDENT`, AI explains the **named** reserved decision and its consequences, and recommends. **The recommendation is recorded as a recommendation** (H-1).

## 5. Human-authority invariants

| Id | Invariant | Source |
|---|---|---|
| H-1 | an AI recommendation is recorded with `authority: generated` and never satisfies any condition of §3 or any ARB-act requirement | ES-001.2; R-34; INV-ATTR-1 [Src/Repo] |
| H-2 | a value is removed from Π only by a recorded ARB act, referenced by id | G-2/R5b; ARCH-INV-1 [Src/Repo] |
| H-3 | the code has no default value for any member of Π. (`Decide` quantifies over Π) | Rev R2-17; Principal [Des] |
| H-4 | a decision record is append-only. Re-examination writes new events and never edits or back-dates | AX-NoBackdate (S0079 and others) [Src] |

## 6. Claims to prove (proof obligations)

**Th-S1 (safety).**
- **Premises:**
  - AX-Truth;
  - **Comp(k, t_φ)**: every act with τ ≤ t_φ is in `E_k`;
  - under C1 = a_*: every act with ρ ≤ t_φ is in `E_k`;
  - the V-layer semantics are as specified (ALM-R2-SPEC S-4).
- **Claim:** `Decide(φ, E_k) ⇒ ∀θ ∈ Π: φ(H; θ) = T` in the true history H.
- **Proof sketch [Ded]:**
  1. Clean makes every relevant record complete and true (AX-Truth), with a known order.
  2. **Lemma R:** every act of H that can affect φ is in `Rel(φ, E_k)`. By Comp, every such act with τ ≤ t_φ is recorded. Acts with τ > t_φ are inert by locality (Th-2 [RV for C1 = b]). The wildcard rule makes Rel over-approximate on ⊥ fields.
  3. So `H_k` and H agree on every act that can affect φ, and `φ(H_k; θ) = φ(H; θ)` for every θ.
- **Status:** **sketch**. Lemma R and the C1 = a_* locality must be **proved formally** in the implementation round. For C1 = b the dependencies are reviewer-verified; for a_* they are unreviewed.

**Th-S2 (non-usurpation).**
- **Claim:** (i) Decide does not depend on any single θ; (ii) if `Π′ ⊆ Π` (the ARB decides parameters), then `Decide_Π ⇒ Decide_Π′`.
- **Proof:** (ii) holds because ∀ over Π implies ∀ over any subset. (i) holds by definition. ∎ **[Ded], complete.**

**Th-S3 (reason soundness).**
- Each code's condition, as stated in §4, implies the stated explanation. `NO_AUTHORITY_ON_RECORD` implies "no basis on record", and **does not** imply "no authority" [RV: Th-5 existence].
- **Status:** to prove per code. Straightforward from the definitions.

**Th-S4 (re-examination is complete).**
- **Claim:** if a new entry changes `Decide(φ)`, it is within the wildcard relevance of φ's recorded basis, so re-examining only matching decisions misses no change.
- **Status:** to prove. Follows from Lemma R.

**Th-S5 (honest limit).**
- Without Comp, no record-based rule guarantees safety: a late revocation flips T [RV: Th-4].
- Stage 1's guarantee is therefore **conditional on Comp**. Comp is **not checkable from records**; it is an operational presumption (§7).

## 7. Evidence basis, events and re-examination

| Record or event | Content |
|---|---|
| `DecisionRecord` (append-only) | decision id · φ · record position k · a hash of `E_k` · the snapshot of Π (open policies) · the verdict (DECIDED / ESCALATED) · reason codes with entry positions · the time of decision |
| `RecognitionChange` (new event) | when an entry at `k′ > k` matches the wildcard relevance of an existing decision, re-evaluate. If the verdict or reasons change: decision id, k, k′, the old and new verdict, and the causing entries. **The old record is untouched** |
| `PolicyNarrowed` | an ARB act removes values from Π. Escalated decisions are re-evaluated (Th-S2: only *more* decisions become possible) |
| `Recommendation` | AI output: the problem, the evidence (each statement typed RECORDED / DERIVED / HYPOTHETICAL / POLICY), the options, a recommendation, and **what needs the ARB**. Its authority is `generated` |

**What happens to actions already taken when a RecognitionChange turns DECIDED into ESCALATED** is a **governance decision** (`RECORDING-DEADLINE-BRIEF.md`).

## 8. Limitations and what Stage 1 can support on the current store

**[Obs] Census of 2026-10-18** (`.claude/runtime/workflow/*.json`; 22 records):

| Store fact | Consequence |
|---|---|
| 144 grants, with fields `{grantId, humanActRef, authority, status, scope, registeredBy}` | every grant lacks **grantee, period, act time and record time**. **Scope is prose**, not atoms. The issuer appears only as prose in `authority` (e.g. "PO/ARB (delivered commission, registered verbatim)") |
| 274 transitions, with **2 dates** (both "2026-08-19") and no record times; 80 carry `humanAct` prose | the act time is ⊥ for nearly every act, and so is ρ |
| statuses: 142 AUTHORIZED, **1 REVOKED, 1 CONSUMED** | governance-side revocation **is now observed (n = 1)**. This updates review-1 E-8 and OQ-6 |

**[Ded] Consequence: on the current store, `Clean(φ)` fails for every grant-dependent question.**
- Every grant has ⊥ grantee, period and τ, and its scope cannot be read as atoms.
- Even the validity of a recorded root act needs a structured holder and τ (and ρ under C1 = a_*), and these exist only as prose.
- **Stage 1 would therefore decide nothing autonomously today. It would escalate every case, with exact reason codes.**
- That is **safe and well explained, but delivers no autonomous-decision value until the data prerequisites (§9) are met.**

## 9. Data prerequisites (for the architecture; not decided here)

| Id | Prerequisite | Why |
|---|---|---|
| R-Q1 | a record time per entry | C1 = a_* is undeterminable without it (`probe_c1_without_record_times.out`) |
| R-Q2 | an act time per act, plus a tie order for same-time acts | Req; C10 |
| R-Q3 | a structured grantee, and the issuer as a structured holder | Req; Basis |
| R-Q4 | the grant period, explicitly stated | Req; C7 |
| R-Q5 | scope as permission atoms, **or** a governed mapping from scope prose to atoms | Covers. Today the scope is an opaque string ([Src] S0071 §5.3) |

**Changing the authority store is an architecture act.** It is gated by `R-37` (authority deliberately not automated) and is the open ADR-C6 question ([Src] S0062 OQ-8). **It needs an ARB act.**

## 10. Counterexamples to test (hand-derived expectations; to be executed in the implementation round)

| Scenario | Expected Stage 1 result |
|---|---|
| N1: unrecorded tie order (R2-01) | ESCALATE: `ORDER_UNKNOWN(r, e)` |
| N2: REVOKE with ⊥ holder (R2-02) | ESCALATE: `FIELD_UNKNOWN(r, holder)` (r is relevant: its target is the basis grant) |
| N2b: END with ⊥ target | ESCALATE: `FIELD_UNKNOWN(x, target)` (relevant by the wildcard rule) |
| N3: REVOKE by a holder without recorded authority (R2-03) | ESCALATE: `ENDING_AUTHORITY_NOT_ON_RECORD(r)`, with RD 6 named |
| R2-04: REVOKE with ⊥ τ | ESCALATE: `FIELD_UNKNOWN(r, tau)` |
| N9 / R2-14: implementer with ⊥ performer, question Elig | ESCALATE: `FIELD_UNKNOWN(i, performer)` |
| A clean root grant → act, all fields known, with **C1 open and ρ unknown** | ESCALATE: `FIELD_UNKNOWN(·, rho)`, with C1 named |
| The same, with ρ known | DECIDE, plus the `OPEN_WORLD` caveat |
| A late revocation entry after a DECIDE | `RecognitionChange` (Th-4 [RV]) |
| An ARB act fixing C1 = b | `PolicyNarrowed`. Earlier ρ-escalations may become decisions (Th-S2) |
