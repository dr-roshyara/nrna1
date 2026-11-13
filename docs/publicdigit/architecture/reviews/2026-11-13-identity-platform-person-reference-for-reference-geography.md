# Identity → Reference Geography: the platform-level person reference (minimal contract, IG) — PROPOSED

| | |
|---|---|
| **Status** | **ACCEPTED by the Principal, 2026-11-13 (D-225)**, with **Q-IG-1 = (a)** (one `ContextPersonRef` per Person and context) and **Q-IG-2 = yes** (stewards, approvers and matter reviewers act through their own Identity Account). Accepted text: SHA-256 `15d31dc79bc29c942f51b1d281bbef6680c9c4def37d93c6dd1c62d57db26f72` (this status line is the only later change). No implementation authorised by this document alone |
| **Commission** | Principal DDD review of 2026-11-13 and D-224 (1): "the minimum Identity person-reference contract that Geography's approval roster needs. Not an Identity redesign" |
| **Closes, if accepted** | finding G-1 of the reconciliation; baseline open item **U-2**; the **[TU]** of v3.11 §8 ("which reference a platform-level context receives … belongs to Identity's tactical design") |
| **Consumes, unchanged** | **D-75** (XD-3: one canonical Person owned by Identity; a different opaque reference per organisation, resolvable only by Identity; Person ≠ Account); XD-3b C-5 (merges only by an explicit Identity act); **I-TDD-02** (D-200: Person = `PersonId` + state `active` / `mergedInto`; merge "deferred, rule only"; §16 minimisation); **D-84, D-84a, D-88, D-89** (stewardship, independence by canonical person); **v3.11 §8** (roster, `excluded(r)`, merge cases A–E) |
| **Changes** | **adds** one reference kind and two notices to Identity. **Changes no** Identity aggregate, invariant or accepted flow, and no Geography rule |

## 0. The finding

**Geography needs to tell, for its approval rule, whether two of its actors are the same person** (D-88: the second approver must be "a distinct canonical person" from everyone who prepared, assessed or submitted the revision).

**Identity today gives a consumer no reference that can answer that at platform level:**
- organisations get a per-organisation `PersonRef` (D-75); Geography is no organisation;
- Platform gets **Account** references (R-2). But an Account is not a person: one person may hold two Accounts, and an Account can be deleted;
- the canonical `PersonId` is Identity's own and is **never handed out** (R-2 output: "Never a `PersonId`", I-TDD-02 §8).

**So the gap is one missing reference kind, not a design flaw.**

## 1. Business meaning

**A map change that matters (a structural revision) needs two different people:** the one who prepared or assessed it, and an independent second person. **To check "different", PublicDigit must know which actions came from the same person**, even if that person logged in with two accounts or Identity later finds two registrations to be one person.

**The contract gives Geography exactly that and nothing more:**
- an opaque number per person, **for Geography only**;
- a notice when Identity decides two numbers are one person, or reverses that.

**Geography learns no name, no e-mail, no organisation and no membership.**

## 2. What Geography needs (v3.11 §8, exact)

1. **one reference per canonical person** for platform-level use, stable and never reused;
2. **reference equality ⇔ the same canonical person**, as far as Identity knows;
3. **a merge notice** when Identity merges persons, naming the affected references and the time of Identity's act, **and nothing about organisations**;
4. the same for a **reversal** of a mistaken merge;

plus, implicitly, **5.** a way to obtain the reference of **the person acting now** (the signed-in steward or approver).

Geography's use: **accountability and the `excluded(r)` comparison only.** It never resolves a reference to an organisation's `PersonRef` and never queries affiliations (v3.11 §8; XD-3 §2 item 3, C-1).

## 3. Proposed contract

| # | Element | Definition | Invariant |
|---|---|---|---|
| **IG-1** | **`ContextPersonRef`** | an opaque reference to one Identity Person, **scoped to one platform-level context** (here `reference-geography`) | **one per (Person, context)**, issued on first use, **never reused**, never changed; resolvable **only by Identity**; carries no data |
| **IG-2** | **`ActingContextRef(context)`** (query) | signed-in Account → its Person → that Person's `ContextPersonRef` for the context | refused if the Account is not `active`, or Identity is unavailable. **A merged Person answers with the survivor's reference** |
| **IG-3** | **equality** | two `ContextPersonRef`s of one context are the same person **iff** they are identical **or** linked by a merge Identity has recorded and not reversed | Geography computes it from IG-4 / IG-5; **no reverse lookup** is offered |
| **IG-4** | **`PersonsMerged`** (event) | `{mergeId, context, refs: [r₁ … rₙ], survivorRef, identityActAt}`; **one event per context** that holds any of the refs | names **only that context's refs**; nothing about organisations, Accounts or `PersonId`s. Emitted only by Identity's explicit merge act (C-5) |
| **IG-5** | **`PersonMergeReversed`** (event) | `{mergeId, context, identityActAt}` | reverses exactly that merge |
| **IG-6** | **delivery** | Identity's central outbox → Geography's central inbox; at least once; idempotent by `(mergeId, kind)` | **order-insensitive:** equivalence at a roster version = the merges recorded up to it, minus those whose reversal is recorded up to it. A reversal that arrives first makes the later merge a no-op |

**Geography's side (already in v3.11, unchanged):**
- it records each IG-4 / IG-5 as **a roster write under its publication lock**, with a roster version;
- from that version, `excluded(r)` uses the new equivalence;
- approvals given earlier are judged by cases A–E of v3.11 §8.

**Why one reference per context rather than the bare `PersonId`:**
- it follows D-75's own pattern (each consumer its own opaque reference, resolvable only by Identity) and I-TDD-02 §16 minimisation;
- **Geography's accountability records cannot be joined to any other context's records by a shared key.**

The cost is one mapping table in Identity. The alternative is Q-IG-1 (§7).

## 4. Keeping the three things apart

| | Account | `ContextPersonRef` | Steward appointment |
|---|---|---|---|
| **Owner** | Identity | Identity (issues), Geography (holds) | **Geography's roster** (D-84: the platform owner appoints) |
| **Means** | a login that authenticates | "this is the same person as before, as far as Identity knows" | **authority** to assess, submit or approve |
| **Grants authority** | no | **no** | yes, within D-84 … D-89 |
| **After Account deletion** | gone | **kept**: the accountability record stays truthful | unaffected record; acting needs a new active Account |

- **A reference proves no qualification and creates no appointment.**
- **A Platform operator identity** (`PlatformOperator`, D-193 H-3) **is not a steward reference.** A staff member who is a steward acts through their own Identity Account (Q-IG-2).

## 5. Exceptional cases

| Case | Behaviour |
|---|---|
| **unknown reference** (not issued by Identity for this context) | Geography refuses the roster or approval write (fail closed) |
| **acting Account not `active`** | IG-2 refused; no act |
| **Identity unavailable** | IG-2 refused, so Geography refuses to record the act. **Publication re-checks need no Identity call:** they use recorded notices only |
| **merge recorded** | from that roster version, the merged references count as one person in `excluded(r)` (v3.11 cases A–E) |
| **merge reversed** | from the reversal's roster version they count as two again. **Earlier findings stay recorded** (append-only) |
| **notice delayed** | equivalence is "as far as recorded"; v3.11's publication-time re-check and its after-publication cases cover the delay |
| **two Accounts of one person, never merged** | **not detected.** As I-TDD-02 §0 item 5 states, D-88's guarantee is "as far as Identity knows". **Recorded as a residual risk, not solved here** |
| **erasure request** | the reference is pseudonymous personal data kept for accountability ([LQ] retention, as I-TDD-02 D-9) |

**Merges themselves stay deferred:**
- Identity's merge procedure (P-I3) is not designed;
- **until it exists, IG-4 / IG-5 are never emitted**, and the contract is still met;
- **Geography can be built and released with IG-1 … IG-3 alone**, and must already consume IG-4 / IG-5 correctly (tested with fixtures).

## 6. Proof obligations (NOT EXECUTED)

| # | Obligation |
|---|---|
| IG-P1 | one `ContextPersonRef` per (Person, context); the same Person asking twice gets the same reference; two Persons get different ones; a reference is never reissued |
| IG-P2 | IG-2 refuses a non-active Account and an unavailable Identity; a merged Person answers with the survivor's reference |
| IG-P3 | no Identity operation returns `PersonId`, Account, login identifier or any organisation reference for a `ContextPersonRef` (no reverse lookup); architecture test |
| IG-P4 | the reference for `reference-geography` differs from the same Person's reference for any other context, and from every organisation `PersonRef` |
| IG-P5 | `PersonsMerged` names only that context's references; idempotent; carries Identity's act time |
| IG-P6 | equivalence is order-insensitive: merge-then-reversal and reversal-then-merge give the same result at the same roster version |
| IG-P7 | Geography: a merged pair is one person in `excluded(r)` from the merge's roster version; distinct again from the reversal's |
| IG-P8 | Geography imports no Identity internals; it holds references only (architecture test) |

## 7. Decisions for the Principal

| # | Question | Options | Recommendation |
|---|---|---|---|
| **Q-IG-1** | Which reference does a platform-level context receive? | **(a)** one `ContextPersonRef` per (Person, context) · **(b)** the bare `PersonId` | **(a).** It is consistent with D-75's per-consumer pattern and minimisation; one small table. (b) is simpler but makes the canonical id a shared join key across platform contexts |
| **Q-IG-2** | Do stewards, approvers and matter reviewers act through their own Identity Account (not a `PlatformOperator` identity)? | yes · no | **Yes.** D-88 compares canonical Persons, and those are Identity's. A staff member with both roles uses their own Account for Geography acts |

**Not a decision now:** Identity's merge procedure (P-I3). The contract only fixes what Geography receives when a merge or reversal happens.

## 8. Impact on Reference Geography and the next step

- **Geography's design is unchanged.** v3.11 §8's four requirements are met by IG-1 … IG-6. Its [TU] is answered by Q-IG-1.
- **Standalone Geography then has no open external dependency** except its own PO-1 … PO-7 and the deferred items of D-93 (L-4c, Q-2, Japanese classification, naming, address ownership). **PO-8 matters only for integration** (D-147).
- **Recommended next step, after acceptance:** Reference Geography's standalone tactical completion (D-224 (2)). It maps v3.11 to slices, aggregates, ports and the central database, with PO-1 … PO-7 and IG-P7 / IG-P8 as test obligations. **The Identity part (IG-1 … IG-3) becomes the first, small implementation slice of it.**

**Traceability:** D-75, D-84, D-84a, D-88, D-89, D-93, D-147, D-193 (H-3), D-200, D-224 · XD-3, XD-3b C-5 · I-TDD-02 §0, §8, §16, §18, §19 · v3.11 §8 · reconciliation G-1 · baseline U-2.
