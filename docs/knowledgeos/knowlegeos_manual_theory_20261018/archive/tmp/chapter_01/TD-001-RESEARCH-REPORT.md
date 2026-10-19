# TD-001 "Authority-Determined Decision Validity": research report (2026-10-18)

**Placement:** Chapter 1 material (§1.13, §1.14.1), so under the chapter-folder rule it lives in the scratchpad mirror of `chapter_01/`.

**Status of TD-001: PROPOSED (temporary working definition; not canonical).**
- **Provenance:** drafted by the Principal (via ChatGPT) from the author's business explanation of M2, **not derived from the corpus.** That is recorded as required.

**Labels:**
- **[Src]** source statement with location;
- **[Ind]** verified by me against the source text or platform files;
- **[Ded]** my deduction;
- **[Hyp]**;
- **[Open]**.

## 1. Where the corpus already speaks about this concept

**Search method:**
- **Authorized sources read in full:** the 08-16 background set; S0001–S0020.
- **Index metadata (summaries) for the rest:** 49 sources touch the authority lifecycle, 159 revocation or supersession, 40 delegation, 22 expiry, 74 decision or claim validity. The first of these later in the sequence after S0018 is S0058. They will be compared as the sequence reaches them.
- **No out-of-sequence reading.**

| Existing statement | Location | Kind |
|---|---|---|
| "Approved ≠ Currently authoritative"; a rule "may be Active … and not currently authoritative — because its authority lapsed, was revoked, or was superseded. A single status field cannot say that." | V3 L313–319 | [Src][Ind] (re-read today) |
| "Every authoritative state is entered by a **recorded human act**, never by the passage of a mechanism." | V3 §10.2 L321–323 | [Src][Ind] |
| "Authority is a recorded permission for a named activity within a stated scope, whose legitimacy derives entirely from a human act … It exists only as a grant, only for a scope, and only by reference to an act." | AMS L49–51 | [Src][Ind] |
| The evaluator answers only "does a recorded grant exist that covers this principal, capability, scope and instant?" (FOUND / NOT_FOUND) and **never concludes authorized or unauthorized**: "NOT_FOUND and unauthorized diverge whenever a grant exists but was not recorded … converting a gap in the record into a judgement about a person". | IA L89–101 | [Src][Ind] |
| "Validity cannot exceed the approver's authority" makes authority **temporal**: "was the authority valid **at the time of approval**?" "Revocation must be distinguishable from expiration." | AB L100–107 | [Src][Ind] |
| Expired exceptions: validity evaluated "against the asking instant, never cached as a boolean"; on failure "MARK_UNKNOWN → never a pass" | IM L211–219 | [Src][Ind] (earlier today) |
| The `authority` field = immutable **provenance** × act-changed **standing**; "Attestation changes standing. Nothing changes provenance." | S0018 L72–96 (`authorities.yaml` header [Ind]) | [Src] |
| Standing changes only by governance **acts**, recorded as act-logs (rulings, dispositions) | S0018 L87; S0012 L152 | [Src] |
| Authority **decisions**: "the AI evaluates and recommends — authority remains with governance"; "for authority decisions the platform's product is DECISION-READINESS, not decisions" | S0006 L36; S0017 L22, L92 | [Src] |
| "Refuse to approve its own work" (R-8) and "its own approval" as a non-responsibility (N-5) | S0019 L73, L85 | [Src] |
| **Three kinds of authority over different subjects:** policy (DA/ARB) · method and implementation (sponsor + ARB; an individual for the machinery) · domain knowledge (product team) | S0019 L98–107 | [Src] |
| Authorizations exist **outside** the authorization record (commissions with no minted ruling) | S0014 C-2; S0015 D-3 | [Src] |
| **FREEZE** decisions exercised n ≥ 3, "each time by declaration, under no named authority, procedure, or reversal condition" | S0017 L55 | [Src] |
| The book's own model: Ch. 1 §1.6 (act × scope × time), §1.13, §1.14.1 (v1 and candidate M2) | [BOOK] | — |

**[Ded] No single source states a full definition of "currently valid / authoritative decision".** The corpus states its **components** separately:
- authority as permission (AMS);
- temporality at the time of approval (AB);
- lapse, revocation and supersession (V3);
- recorded human act (V3);
- report the gap, not a judgement (IA);
- unknown is never a pass (IM).

The book's §1.14.1 is the only place that composes them. So TD-001 is **a summary of an existing composition, not a new concept.**

## 2. Comparison with TD-001

### Agrees [Ded, from the sources above]

| TD-001 element | Agreeing source |
|---|---|
| validity needs the required authority | AMS L49; V3 §10.2 |
| "has not ceased to be valid": revoked, replaced | V3 L319 ("lapsed, revoked, superseded") |
| consider the time and subsequent acts | AB L104–107; V3 L319 |
| report uncertainty, do not invent an answer | IA L89–101; IM L211–219 (MARK_UNKNOWN); INV-A3 |
| not valid merely because a database says so | V3 L313 ("Approved ≠ currently authoritative"); S0018 (status ⟂ authority) |
| the five distinctions | all present in the book's §1.14.1 "Five things the answer keeps apart" [BOOK] |

### Differs or is missing (all [Ded])

1. **Which "authority".** TD-001 means a **permission to act** (AMS). The corpus uses the same word for a **document's trust and origin** (S0018; `authorities.yaml`), and S0019 adds "three kinds of authority over subjects". Without saying which, TD-001 inherits the term drift (TODO-01).
2. **"Currently valid" is a new term.** The corpus says "currently authoritative" (V3); the book says "current authority" and "effective" (Ch. 2 §2.11); S0018 says "standing". Adding a fourth term would create drift (TODO-03).
3. **"Relevant time" is ambiguous.** The sources fix the decisive time as **the time of the act** ("valid at the time of approval", AB L107). The book showed that evaluating the grant at the time of **use** is refuted (§1.14.1 correction note). TD-001 should say "at the time the decision was made", or it can be read back into the refuted form (TODO-05).
4. **The endings must themselves be authorized.** TD-001 says "revoked, replaced or otherwise brought to an end" but not **by whom**. V3 §10.2 (states change only by a recorded human act) and the book's safeguard 2 require the ending act to be authorized too. Otherwise anyone can end a decision.
5. **The authority's ending is a separate question from the decision's ending.** Whether the delegator or grant losing authority ends earlier decisions is the book's ending policy P (reserved decision 1). TD-001 folds it into "ceased to be valid under the applicable rules". That is acceptable at business level, but it should name the dependency.
6. **"Recognised as currently valid" merges two things.** One is what valid acts made of the decision (de jure); the other is what the records show (recognized). The book's alternative C3 (`CH-01-final-review.md` L70) keeps them apart. Under the corpus's own evidence that authorizations exist outside the record (S0014 C-2), they genuinely diverge.
7. **Subject.** TD-001 says "decision". The corpus applies the same logic to **rules** (V3 L319), **exceptions** (AB, IM) and **knowledge items** (S0018 standing). S0004 MM-4 asks whether state attaches to the artifact or to the rule it carries (TODO-04).

### Contradicts

**No source contradicts TD-001.**

### Tensions (stress tests, [Ded])

- **(a) Freezes.** Freezes were decided "under no named authority" (S0017 L55) and are treated as binding by the programme (S0004 freeze order). Read strictly, TD-001 would report them "cannot determine", or "not validly made" if records constitute authority. Either the freezes are outside TD-001's scope, or TD-001 conflicts with practice. **[Open], TODO-08.**
- **(b) Documents.** A document can be `authority: authoritative` (standing) with no decision-maker's permission involved at all (S0018). So TD-001's "authority" must not be read as the field.

### Unknown

- Whether a later source (S0058 onward) defines the concept formally.
- Whether the live records can support TD-001 at all: today they lack holder, period and issuer link (review 1 §6), so every case evaluates to "cannot determine". *That is a property of the data, not of the definition.*

## 3. Recommendation (AI, reasoned; not a decision)

**Retain TD-001 as PROPOSED, status NEEDS_REVISION.** It agrees with every source found and contradicts none. The differences are precision gaps, not errors.

**Candidate revision, TD-001 r1** (the Principal's original kept unchanged in the register):

> A governed claim (a decision, a rule or an exception) is **currently authoritative** at a given time when:
> 1. it was adopted by a recorded act of someone who, **at the time of that act**, held authority (a recorded permission for that kind of act within that scope) covering it;
> 2. its own validity period includes that time; and
> 3. no later act, **itself properly authorized**, has revoked, superseded or annulled it, and no ending of the authority under which it was adopted carries over to it under the organisation's rules.
>
> The organisation **recognises** it as current only when its records establish 1–3. Where they do not, the answer is **"cannot determine"**, never "not authorized".

**The r1 changes are mechanical alignments with existing sources**, each traceable to §2:
- the term is "currently authoritative" (V3);
- the time is the time of the act (AB);
- endings must be authorized (V3 §10.2);
- the carry-over rule is named (book reserved decision 1);
- recognition is kept apart from validity (C3);
- the subject is broadened to governed claims (V3, AB);
- the report-the-gap rule is stated (IA).

**None needs a human decision now.** The open choices it points to are already on your list: what "authority" is called, the ending policy, and how freezes are governed. Accepting r1 as the working text is yours to confirm, but **research continues either way**.

**Validation criteria for TD-001 (support · falsify):**
- **Support:** a later corpus definition with the same components, or the book's §1.14.1 formal model passing review.
- **Falsify:** a source defining current validity at the time of **use**, or treating authority as conferred by status; or a business case where the organisation correctly recognises a decision that fails 1–3.

---

## Addendum: comparison update after S0021–S0025 (2026-10-18)

| Finding | Effect on TD-001 | TODO |
|---|---|---|
| **External authority is inexpressible**: "who decides" vs "who must be obeyed" (S0022 F-3, L108–117) | **A gap in TD-001 and r1:** "the required authority" covers internal permission only. A decision can be properly authorized internally and still be invalid under a law or regulation. **Not a contradiction**; it may belong to "valid under the applicable rules" rather than to "authority" | T1-15 |
| **Retroactive minting of an authorization**, recommended with disclosure (S0023 Pkg 6) | r1's "at the time of that act held authority" is **consistent under C1-b** (the authority existed; the record came late) and **inconsistent under C1-a** (the record creates the authority, so it did not exist at the time) | T1-18 |
| **Genesis: the first decision** has no prior authority or evidence route (S0024 §10; S0025 F-2); the corpus proposes "first decision recorded Provisional/Proposed" | TD-001 needs a start of the chain. The book uses stipulated root acts. The corpus offers provisional standing instead. **Different mechanisms, not yet reconciled** | T1-17 |
| **Attestation is an act that gives a projection standing** (S0022 S-3, M-4) | **agrees** with TD-001's premise that standing comes from properly authorized acts | — |

**Status of TD-001: unchanged (PROPOSED, NEEDS_REVISION).** r1 stands as a candidate. A further precision (external obligations) is pending T1-15. No contradiction found in S0001–S0025.

---

## Addendum 2: statement-level provenance of TD-001 and r1 (2026-10-18)

**Method:**
- Every material statement in the original (O) and in r1 is traced to its source, with type (explicit or derived), reasoning, competing readings and status.
- **Statuses:** established (stated by a source and not contested in the sources read) · proposed · disputed · unresolved.
- **"Established" here is a research status, not approval.**

| # | Statement | In | Source | Explicit / derived | Reasoning | Counterexample / competing reading | Status |
|---|---|---|---|---|---|---|---|
| 1 | Validity requires the **required authority** | O, r1 | AMS L49–51; V3 §10.2 L321–323 | explicit | — | **external obligations** (law, regulator) are not covered (S0022 F-3) | established for internal authority; **unresolved** for external (T1-15) |
| 2 | The subject is a **decision** (O) / a **governed claim: decision, rule, exception** (r1) | O / r1 | V3 L319 (rules); AB L100–107 (exceptions); [BOOK] §1.14.1 (claims) | r1: derived | the same lapse / revoke / supersede logic is stated for rules and exceptions | does state attach to the artifact or the rule it carries? (S0004 MM-4) | **proposed** (T1-04) |
| 3 | Term **"currently valid"** (O) / **"currently authoritative"** (r1) | O / r1 | V3 L313 ("Currently authoritative") | explicit (r1 term) | r1 reuses the source's term instead of adding a fifth | "effective" (Ch. 2 §2.11) and "standing" (S0018) overlap | **proposed** (T1-02) |
| **4** | **adopted by a recorded act** | **r1** | V3 §10.2 ("a **recorded** human act"); AMS L51 ("only by reference to an act"; a grant record) | explicit in V3 | — | ⚠️ **IA L99–101: "a grant exists but was not recorded" is a real case.** Making "recorded" part of **validity** silently adopts C1-a ("the record constitutes"). **Under C1-b, recording belongs to recognition (r1 §recognition), not to validity** | **DISPUTED. r1 defect:** a policy choice (C1) entered the definition silently. **Correction candidate:** say "adopted by an act"; let "recognised" carry the record requirement; state C1 as an open alternative |
| **5** | authority = **a recorded permission** for that kind of act within that scope | r1 | AMS L49 | explicit | — | the same C1 issue as #4; and S0018's other sense of "authority" (document trust) | **DISPUTED (C1)**; the term itself is inconsistent (T1-01) |
| 6 | the authority held **at the time of that act** | r1 (O: "the relevant time") | AB L104–107 ("valid at the time of approval"); [BOOK] §1.14.1 correction note (time of use refuted by the CONSUMED case) | explicit (AB) + derived (book) | — | retroactive minting (S0023 Pkg 6) is compatible only under C1-b (T1-18) | **established** for the time of the act; its interaction with retroactive records is **unresolved** |
| 7 | **own validity period** includes the time | r1 | V3 L319 ("lapsed"); IM L211–219 (expiry at the asking instant); [BOOK] c2 | explicit | — | the boundary instant (inclusive or exclusive) is not decided by any source (C14) | established; the **boundary unresolved** |
| 8 | **no later act, itself properly authorized**, has revoked, superseded or annulled it | r1 (O: "revoked, replaced or otherwise brought to an end") | V3 L319; V3 §10.2; [BOOK] safeguard 2 | explicit (endings) + derived ("itself authorized" from §10.2: states change only by recorded human acts) | an ending is a state change, so the same rule applies to it | unverifiable endings: count or not? (reserved decision 6) | **established** (the requirement); the treatment of unverifiable endings is **unresolved** |
| 9 | an **ending of the authority carries over** only under the organisation's rules | r1 (O: "under the applicable rules") | [BOOK] ending policy P (reserved decision 1); AB L100 ("validity cannot exceed the approver's authority", for **exceptions**) | derived | AB states carry-over for exceptions; the book generalizes as a per-kind policy | AB's static vs dynamic reading (book §1.14.1) | **unresolved** (policy, reserved decision 1). **Correctly left open by r1** |
| 10 | the organisation **recognises** it only when its records establish 1–3 | r1 | V3 §10.2; IA L89–101; [BOOK] C3 (de jure vs recognized, `CH-01-final-review.md` L70) | derived | the split of validity and recognition makes #4 / #5 coherent under both C1 readings | **knowledge time:** "establish" as of when? Late-filed records (M2's bitemporal question) | **proposed**; **r1 lacks the knowledge-time qualifier** ("as the records stood at time *k*") |
| 11 | where not established: **"cannot determine", never "not authorized"** | O ("report uncertainty"), r1 | IA L89–101; IM L211–219 (MARK_UNKNOWN); INV-A3 | explicit | — | under C1-a a missing record **is** missing authority (AMS L51) | **established under C1-b; disputed under C1-a** |

**Aspects the Principal named that r1 does not address (by design or by gap):**

| Aspect | Treatment in r1 | Evidence / TODO |
|---|---|---|
| delegation | "held authority covering it" is silent on whether delegated authority survives the delegator | **gap, left to a policy:** reserved decision 1 (cascade); T1-14 |
| grant consumption | silent: a single-use grant used up before the act | **gap:** T1-12 (formal), alternative C13 |
| expiry and termination | covered by #6 (the act's time) and #9 (carry-over policy) | — |
| late-filed evidence | partly: #10 separates recognition, but **no knowledge time** | add a knowledge-time qualifier (r1.1 candidate) |
| rules and exceptions | covered by #2 | T1-04 |
| **freeze cases** | **counterexample:** binding in practice, no named authority (S0017 L55) | **unresolved** (T1-06). Either outside the scope of "governed claim", or a real conflict with practice |
| external obligations | not covered | T1-15 |
| genesis (the first decision) | not covered: no start of the chain | T1-17 |

**Candidate r1.1** (a further revision; **the Principal's original and r1 are kept unchanged**). Changes from r1 are bold.

> A governed claim (a decision, a rule or an exception) is **currently authoritative** at a given time when:
> 1. it was adopted by **an act** of someone who, at the time of that act, held authority (a permission for that kind of act within that scope) covering it;
> 2. its own validity period includes that time; and
> 3. no later act, itself properly authorized, has revoked, superseded or annulled it, and no ending of the authority under which it was adopted carries over to it under the organisation's rules.
>
> The organisation **recognises** it as current **on a given date** only when **its records, as they stood on that date,** establish 1–3. Where they do not, the answer is "cannot determine", never "not authorized".
>
> *Open alternative (C1, the Principal's): if records **constitute** authority, "an act" in 1 reads "a recorded act", and a missing record means no authority.*

**r1.1 status: PROPOSED.**
- It removes the silent C1 choice (#4, #5) and adds knowledge time (#10).
- **Open on it:** T1-06, T1-12, T1-14, T1-15, T1-17 and reserved decision 1.
- **TD-001 overall: PROPOSED, NEEDS_REVISION.** Not canonical; no approval implied.

---

## Addendum 3 (2026-10-18): S0031–S0104 and the repository-wide definition search

**TD-001 stays PROPOSED / NEEDS_REVISION. The original, r1 and r1.1 are preserved unchanged.** Evidence files:
- `S-BATCH-S0031-S0039.md`
- `S-BATCH-S0040-S0046.md`
- `S-BATCH-S0047-S0078.md`
- `S-BATCH-S0079-S0104.md`
- `REPO-WIDE-DEFINITION-SEARCH-20261018.md` (supplementary, outside the corpus)

### Statement-level verdict on r1.1

| r1.1 component | Corpus (S0001–S0104) | Supplementary repository evidence | Verdict |
|---|---|---|---|
| (1) adopted by an act of someone who **at the time of that act** held authority | **Supported.** S0058 L77 (a grant that postdates the work is an "anachronistic" defect); S0071 GP-1 (authority originates in a human act); S0073 L48 (the grant "as registered") | R-34 (adoption only by an explicit act); R-86 (effective on adoption); product analogue (decisions "outlast" the authority) | **SUPPORTED; precision gaps:** (a) authorizer vs executor/registrar (S0044 RD-6, S0078 OQ-10, n=2); (b) performer eligibility (S0104, S0102 #3; T1-19) |
| (1) "a permission for that kind of act within that scope" | sense (a) used throughout | **R-80 separates PERMISSION from AUTHORIZATION** | **Terminology gap.** The phrase merges two canonical terms (naming reserved, T1-01) |
| (2) its own validity period includes that time | **unevidenced:** grant records have **no** period field (S0071 §5.2) | product analogue: authorities have temporal boundaries | **UNEVIDENCED, not contradicted** |
| (3) no later properly authorized act revoked or superseded it | **supported for supersession:** S0067 L7, S0079 L144, S0053 L30, S0071 T-3 | R-77 ("explicit act, never inference"); R-41 | **SUPPORTED** |
| (3) "no ending of the authority carries over" | **open in the corpus:** S0071 OQ-6 ("whether authority can be withdrawn at all"); 47/47 grants AUTHORIZED, none ever changed | product analogue Stress Test 8 ("revocation stops future authority; prior … remains as historical fact"; effects differ by type) | **UNEVIDENCED in governance practice, not contradicted.** Delegating to "the organisation's rules" matches the analogue's finding that effects are type-dependent |
| recognition: "its records, **as they stood on that date**" | **cannot be operated** on the platform's authority store: no clock (S0067 L94, S0071 `Seq`), no history (S0062 C-3; V-E). Records are never back-dated (S0079 and others, n ≥ 6), so recognition follows recording time | no governance-side definition; product-side valid time only | **Structurally SUPPORTED** (act time ≠ recording time: S0103 L191). **Not operable** for past dates on this store, so the "cannot determine" fallback is the realistic output |
| "cannot determine", never "not authorized" | consistent: S0058 L49–51 ("does not make its content wrong … weighed at acceptance"); S0049/S0050 (default to weakest, not negative) | — | **SUPPORTED as practice, by analogy** |
| open alternative C1 | **leans to C1-b:** S0067 L51, S0092 L35, S0095 L141. **Counter:** S0071 I-2 (activation only when recorded); S0067 L52 (the act exists "as a committed artifact") | ES-001.2 "Documents record governance; they do not create it" (hosting standard still PROPOSED); R-86 | **Still open as a reserved decision.** The evidence now favours C1-b for **validity** and C1-a for **operational effect**. That split is what r1.1 already encodes (validity vs recognition) |
| subject "decision, rule or exception" | — | R-41: an **artifact** has one authoritative lifecycle state at any moment; S0053: one **current claim** per (act, dimension) | **Precision gap:** the subject differs by context (T1-04) |

**Strongest counter-evidence, in order:**
1. **S0071 I-2:** an act has no operational effect until recorded. This is fatal to r1.1 **only if** "currently authoritative" is meant to include operational effect.
2. **S0104:** a granted and STARTed performer is still barred. This is a counter-example to the **sufficiency** of r1.1's conditions **unless** the bars are read as part of the grant's scope.
3. **S0067 L52:** the act exists "as a committed artifact", which keeps C1-a alive in a weak form.

### Overall

**r1.1 is SUPPORTED in structure and NOT CONTRADICTED** by any source in the authorized sequence (S0001–S0104) or by the supplementary definitions found.
- It is **not validated**: proposed ≠ validated.
- **No authoritative definition of "current validity" of a governed claim was found** anywhere searched; the search scope and gaps are listed in the search file, §0 and §5.

Three precision gaps are now evidenced:
- **(G-A) eligibility:** T1-19;
- **(G-B) conditional acts:** S0052, where approval lands through a later act;
- **(G-C) recognition on an unversioned, clockless store.**

There is also one terminology gap: **(G-D) permission vs authorization**, per R-80.

**No r1.2 is drafted.** Drafting a successor is a revision step for the Principal to authorize. The gaps above are recorded for it.
