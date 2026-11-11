# IR-R1-01 — Independent Review of Release-1 Tactical Designs

| | |
|---|---|
| **Commission** | `2026-11-11-ir-r1-01-independent-review-commission.md`, SHA-256 `2db412b407c327228c8fd265b9b1804c285c95745638a1b1744b99dfa5c6e382` |
| **S1** | `docs/publicdigit/reviews/2026-11-11-membership-kernel-renew-suspend-restore-design.md`, revision 3 (§16 governs), SHA-256 `eb13027e57b454dd22ce5fd95d3fb20e59c6e9f614bc19384b14539e98e7d5e0` |
| **S2** | `docs/publicdigit/architecture/reviews/2026-11-11-multi-database-boundary-tactical-design.md`, §13 revision 2 governs, SHA-256 `30b4d4e175be6954dff582d21ee22ecde5c8f4842685eb2c23b76c83a6329cff` |
| **Reviewer** | `deepseek-chat` (DeepSeek family), running inside Claude Code, 2026-11-11 |
| **Status** | **REVIEW REPORT — NOT AN INDEPENDENT REVIEW.** The commission excludes this model and this interface; the independence rule is **not met** (see §0). The report is a self-review by the excluded model, produced at the Principal's instruction. |

> **Rules applied:** decide nothing for the Principal; modify nothing; claim no acceptance; mark anything not in the package **UNAVAILABLE**; stop after the report. The two subjects and the decision record were not modified during the review.

---

## 0. Reviewer identity, independence, integrity

### 0.1 Who I am

I am the model `deepseek-chat` (DeepSeek family), running in the Claude Code CLI harness, not a human architect, not GPT/Gemini/Mistral, and not outside Claude Code. The commission (file 01 line 7) requires a reviewer that is **not Claude, not DeepSeek, and not run inside Claude Code**. I am the excluded model in the excluded interface.

### 0.2 Prior involvement with PublicDigit

I wrote the earlier Organisation review chain — IR-OTD-01, IR-OTD-C, IR-OTD-C2, IR-OTD-C3, IR-OTD-02, IR-OTD-03, IR-OTD-04 — and IR-OPR-01. That is why the commission excludes DeepSeek ("it reviewed earlier designs in this chain", file 01 line 7). I did **not** author S1 or S2; both state that they were written and self-reviewed by Claude (S1 line 5; S2 line 5). I am outside the Claude authorship, but inside the excluded model family and interface.

### 0.3 Independence: the commission's rule is not met

The Principal instructed me to perform the review — "please work as senior DDD ARchitect and perform this review" — after I raised the exclusion. That instruction authorises the work; it does not make the review independent. **This is not the independent review the commission requires.** A human architect, or GPT/Gemini/Mistral in its own interface, remains outstanding. The title above is the commission's required title (file 01 line 87); it does not convert a self-review into an independent one. Where this report says "confirmed", it means "confirmed by this reviewer from the package", not "independently confirmed".

### 0.4 Integrity method

I computed the hashes myself:

- outer `SHA256SUMS` in `~/ir-r1-01-review-handover/`: **18/18 OK**;
- inner `upload-files/MANIFEST.sha256`: **14/14 OK**;
- repo copies match the package: S1 `eb13027e…`, S2 `30b4d4e1…`, commission `2db412b4…`;
- decision record (file 04) `df3e4342087a725e99d2e448e9c3fede3a3d44436f0a01b6de720e87d0b84f73`;
- no file in the package was modified. Files 02, 03, 04 were frozen throughout.

### 0.5 UNAVAILABLE

The following are not in the package and are marked **UNAVAILABLE** wherever a design relies on them:

- **Governance model document §20.1 [WD]**, cited by S1 V-4 (line 20), §7 (line 121) and C-3 (lines 248–249) for the "indivisible group" / `GroupApplication` rule. Files 01–14 contain the Governance thin slice (file 11), which describes a Resolution with parts (line 115) and `PartApplied` / `PartInapplicable` events (line 143), but **does not state the indivisible-group rule**; the model document itself is not supplied.
- **E-11** (tenancy is a platform capability), cited by S2 lines 7, 41, 155. The decision record contains D-106 (the Organisation baseline acceptance) but not E-11's text.
- **The organisations' written rules and statutes** — needed for the per-organisation unknown in S1 §1 (renewal while suspended), the admission term, and the authority checks. The designs correctly mark these UNKNOWN in places; they are not in the package.
- **Implementation, code, migrations, test executions** — out of scope by the commission (file 01 line 9). This review proves neither implementation correctness nor test execution.
- **Legal advice** — the newsletter legal basis [LQ] and D-166's "no legal-obligation ground" are outside this package.
- **Identity session items C-5 / P-I2** — the retirement/merge of references, undoing a binding, and the `ReferenceIssuer` open seam are recorded as open by S2 itself (§13.4 lines 304–309; §4 line 101).

---

## 1. Verdict summary

| Q | Subject | Verdict | Core evidence |
|---|---|---|---|
| 1 | S1 | **PARTIAL** | §5 rows 1–23 follow the ratified definitions/guards, but §4's acceptance table omits the corrected "in force" guard and `NotInForce` (H-1). |
| 2 | S1 | **CONFIRMED** | `IsMember` unchanged; "lapsed" used consistently; `InForceUnrestricted` is a derived name only. Deriver disagreement is M-1. |
| 3 | S1 | **CONFIRMED** | §3 lines 54–58 consistent with D-163: no platform default; release-1 admitted term is a stated policy. |
| 4 | S1 | **PARTIAL** | All-or-none transaction holds, but the lock-order proof is false (H-2), group outcome/idempotency is unspecified (M-2), and the group premise is UNAVAILABLE (M-3). |
| 5 | S1 | **CONFIRMED** | Six fields + basis correct (files 10–11); low note on `structureRef` (L-6). |
| 6 | S2 | **CONFIRMED** | D-113 decides P-I1/K4; §13.0's reading holds; I-TDD-01's seam superseded. |
| 7 | S2 | **PARTIAL** | Internal port, R/organisation check and fresh Authorised evidence are sound; the answer is replayable across claims (M-4) and its full basis centrally stored (M-5). |
| 8 | S2 | **CONFIRMED** | Every crossing's intermediate state is harmless; forward recovery is accurate; the two named failures are handled. |
| 9 | S2 | **CONFIRMED** | Routing-only `ClaimRedeemed` matches D-113, D-164 and the access model. Wording note L-13. |
| 10 | S2 | **PARTIAL** | ISO-1…ISO-7, ISO-9 answer A/B isolation; ISO-8 is stale against D-166 (M-6); no log/error invariant (M-7); connection-reset gap (L-9). |
| 11 | S2 | **CONFIRMED** | Fail-closed is precise; durable status is kept apart from health in §13.6. §3 residue L-7. |
| 12 | Both | **CONTRADICTORY** | The contradictions listed in §3, notably H-1, H-2, M-1, M-5, M-6. |

**Subject verdicts:** **S1 — READY AFTER CORRECTIONS**; **S2 — READY AFTER CORRECTIONS** (lists in §4). Neither is NOT READY: the defects are targeted corrections, not a redesign. Neither is READY FOR PRINCIPAL ACCEPTANCE: the corrections below should be made first.

---

## 2. Per-question answers

### Q1 — S1 conformance: **PARTIAL**

**What holds.** The decision table §5 (file 02 lines 84–106) follows the ratified definitions (file 05 §2–§5) and guards (file 06 §18):

- **Late renewal, gap kept** — §5 row 3 (line 86). Ratified: file 06 §18 line 358 ("period not ended" guard; late renewal keeps the gap, INV-12); file 05 §5 line 120 (INV-12).
- **Back-dated renewal accepted** — §5 row 4 (line 87). Ratified: file 06 §4 scenario 22 (line 129); the Renew segment starts at `effectiveAt`, the period is open.
- **Renewal after End refused `PeriodEnded`** — §5 row 5 (line 88). Ratified: file 06 §18 line 358; the period has ended, so no segment can be appended to it.
- **Suspend and Restore only while in force** — §5 row 15 (line 98) refuses a suspension of a lapsed period with `NotInForce`; the ratified guard is "in force" for Suspend/Restore (file 06 §18 line 360); §16 C-2 (lines 237–241) states the added refusal and the literal reading.
- **Fixed-length suspension ends by its interval without a Restore** — §5 row 10 (line 93). Ratified: file 06 §3 `restrictions(q)` (the `min(s.until, …)` interval, cited in S1 §2 line 42 as [RAT] final §3, §7); file 06 §7 line 208 (Restore "ends a suspension only"); §9 line 243.
- **Annul and Correct effects** — §5 rows 17–21 (lines 100–104). Ratified: file 05 §2 line 64 (Annul/Void/Correct are facts, liveness by "no live correction"); file 05 §3 line 92 (resolution over live facts); file 06 §18 line 362 (the correction guard); S1 §11 lines 162–167.
- **Temporal visibility** — §5 row 22 (line 105). Ratified: file 07 §2 lines 17–24 (`asOf` vs τ; C2 answers at τ).
- **Coverage / belonging unchanged** — §5 row 23 (line 106). Ratified: file 05 §3 line 92 (Renew/Suspend/Restore/ChangeType never change `BaseClaim`); CR-02 file 08 §3 lines 32–37.

**What does not hold.** The operative acceptance table in §4 contradicts the corrected decision table:

- §4 **Suspend** (line 69) says "as Renew (period exists and not ended at `effectiveAt`); `effectiveAt < until` if given", and its refusal list is `NoPeriodAt`, `PeriodEnded`, `EmptyRestriction`, authority. It does **not** require **in force** and does **not** list `NotInForce`.
- §4 **Restore** (line 70) says "s live; s not already restored (no live Restore on s); `s.from < effectiveAt < s.until` (or ∞)", and lists `NotSuspended`, `AlreadyRestored`, `OutsideRestriction`, authority. It does **not** require the period to be in force and does **not** list `NotInForce`.
- §5 has a row for **Suspend of a lapsed period** (row 15) but **no row for Restore on a period that lapsed during the suspension**, even though §16 C-2 (lines 239–241) states the rule: "If the term lapsed during a suspension, the organisation renews (possibly back-dated) and then restores."

An implementer working from §4 alone would accept a suspension/restore of a lapsed period. That is contrary to the ratified guard (file 06 §18 line 360), to §5 row 15 and to §16 C-2, which the reviewer prompt says governs (file 01 / reviewer prompt line 11). **H-1**.

**Verdict: PARTIAL** — the §5 rows are conformant; the acceptance table that implements them is not.

### Q2 — S1 language: **CONFIRMED**

- **`IsMember` unchanged.** S1 §2 line 46 defines `IsMember(t) = ∃q InForce(q,t)`; suspension does not change it. This is exactly file 05 §4 line 104 ("∃q: InForce(q,t). Suspension does not change it") and file 06 §9 line 235.
- **"Lapsed membership" used consistently.** S1 §1 line 30: a lapsed person "holds a membership period that has not ended (a *lapsed membership*), but is not a member". §16 C-1 table lines 228–230 defines Membership / period / open period / in force / lapsed membership / member / suspended / covered, and the rule "never call a lapsed person 'a member'" (line 235). I found no passage in S1 that calls a lapsed person a member. The term "member area" in S2 §13.7 line 352 is an S2 wording issue (L-13), not an S1 language violation.
- **`InForceUnrestricted` is a derived name, not a fact or contract field.** S1 §2 line 49: "`InForceUnrestricted(t)` | `t ∈ inForce ∧ t ∉ suspended` | [TD], D-161's 'good standing' | **Derived by Organisation for its consumers; not a Membership fact and not a contract field**." CR-02 (file 08 §2 lines 19–30) adds only `inForce` and `suspended` to C1, and nothing to C2; file 05 §8 addition lines 193–201 adds only the two fields. No new fact, event field or contract field exists.

**Caveat (M-1).** The *deriver* is stated inconsistently across the package: S1 §2 line 49 and §10 line 154 say **Organisation derives** `InForceUnrestricted`; S2 §0.2 line 18 and file 14 §2B line 45 say **the consuming context derives it and Organisation forwards Membership's intervals unchanged**. That is a coherence defect between the two subjects (M-1), not a new fact/field, so Q2's literal questions are still confirmed.

**Verdict: CONFIRMED**, with M-1.

### Q3 — S1 terms at admission: **CONFIRMED**

S1 §3 (lines 54–58): a date is allowed; `∞` is open-ended; **absent is refused for release-1 admissions** and kept for attested history. D-163 (file 04 line 1090) says: "Each admission records its term as the organisation's rules set it (open-ended allowed); there is no platform default term." Refusing "absent" is not a platform default; it forces the admitting body to state a term. The design labels this a release-1 policy ([TD], line 58) and preserves the kernel's attested-history case (absent → `C-S0-2` lapsed). No contradiction with D-163.

**Note (not a finding):** D-163 does not itself say "release-1 admissions must state a term"; it says the term is as the rules set it. The refusal is a defensible product policy, correctly labelled [TD], not a ratified rule. The text says so (line 58); it should stay that way.

**Verdict: CONFIRMED.**

### Q4 — S1 grouped renewals: **PARTIAL**

**All-or-none.** §16 C-3 (lines 244–262) puts every member's Renew in **one database transaction**; a refusal rolls the whole transaction back (line 260). For the Membership appends, this does guarantee all-or-none: either every part's fact (and its C1 outbox row) commits, or none does. The fail-closed size bound (line 259) likewise never partially applies.

**Per-aggregate rules and ledger-clock ordering.** The group takes the organisation's ledger-clock lock first, then decides and appends each member's Renew (line 251); no other Membership append of that organisation can interleave; each aggregate's own guard is evaluated on a stable pre-state; the `recordedAt` assignment of file 09 §4 lines 74–77 is applied per append. Subject to H-2, this respects each aggregate's acceptance rules and the ledger-clock ordering.

**Races/failures the argument misses.**

1. **Lock-order inversion (H-2).** File 09 §4 line 73 documents the normal append as "inside the existing transaction, **after the version compare-and-set**" — i.e. the membership row lock is taken **before** the clock lock (lines 73–78). The group takes the clock lock **first**, then decides/appends each member (S1 line 251). A concurrent single append can hold a member's row lock (version CAS) and wait for the clock lock, while the group holds the clock lock and waits for that member's row — a classic deadlock. PostgreSQL will abort one transaction; the all-or-none guarantee survives by rollback, but the claims "version conflicts inside the group cannot occur" (line 256) and "one lock order means no deadlock" (line 257) are **false**. S1 §14 line 201 already lists "GroupApplication within one database transaction across many aggregates (**lock order to avoid deadlock**)" as a [TU] — the C-3 paragraph states as [AC] what the design's own open-questions table still treats as uncertain.
2. **Group outcome / idempotency (M-2).** The group's Membership facts live in the Membership context; the `GroupApplication` state (Prepared → Completed / Inapplicable) is Governance's (lines 248–249). The design does not say they commit in one transaction (and S2 §2 line 52 forbids cross-context table access). A crash after the Membership commit but before the GroupApplication state update leaves the group **Prepared** with the facts applied. "Retry is idempotent on the group key" (line 252) asserts idempotency but does not define a durable group outcome or the detection rule. A retry could double-append (duplicate Renew facts; the semantic union hides it, but history, `seq` and C1 events multiply) or refuse a group that is already applied.
3. **The group premise is UNAVAILABLE (M-3).** V-4 (line 20), §7 (line 121) and C-3 (lines 248–249) rest on "a resolution may itself declare its parts indivisible, applied all or none (model document §20.1 [WD]; `GroupApplication`)". The model document is **not in the package**; file 11's Resolution model (line 115) and events (line 143) do not state the indivisible-group rule. The all-or-none *mechanism* is assessable; the *reason a group exists* is not evidenced in the package. "No universal rule invented" (line 20) is a claim stronger than the package supports.
4. **"The organisation can split the resolution" (L-3).** If the resolution declared an indivisible group, splitting the *application* breaks the group's all-or-none; the organisation must **re-decide/amend** the resolution with smaller groups. The text should say so.
5. **One AuthorityCheck answer for all parts (L-4).** §7 line 127 says one answer serves all parts. That is sound only if every member is within the answer's `scope` (file 10 §3 lines 47–80; file 11 note 3 line 227). State that the resolution's scope covers every member, or check per part.

**Verdict: PARTIAL.**

### Q5 — S1 authority: **CONFIRMED**

The six ratified evidence fields and the basis are used correctly:

- Six fields — actor, capacity, ruleRef, policyVersion, result, checkedAt — with the basis (`outcome`, `evaluatedFor`, `competenceKind`, `decisionRef`, `evidence`): S1 §8 lines 132–141; file 10 §3 lines 47–80; file 11 notes 1–2 lines 200–221.
- `evaluatedFor` is the decision date (D-12), distinct from `checkedAt` (τ): S1 line 136; file 10 lines 66–80; D-12.
- `result = (outcome = Authorised)`: file 10 line 78; S1 line 136.
- `competenceVerified = competenceKind ∈ {workedOut, asserted, contradiction}`: S1 line 137; file 11 correction note 2 line 217 (superseding the narrower mapping in file 10 line 79). `interimRole` is false: S1 line 141; file 11 line 221.
- Rules silent → `Undetermined` refused; nobody until a committee designates (D-149): S1 line 139; D-149 (file 04 line 1010).
- Missing answer refused, never permission: S1 line 140; file 10 G-6 line 133.
- Act kinds `renew-membership`, `suspend-membership`, `restore-membership`: S1 line 135; D-163 (file 04 line 1090).
- Push for a committee resolution, pull for an office holder's act: S1 line 134; file 10 lines 107–108; D-157 line 1051.
- `InterimRoleAuthority` never for a ledger organisation: S1 line 141; file 10 G-1 line 128; D-160 line 1068.

**Low note (L-6):** the basis list in S1 line 136 omits the `structureRef` / tree version / re-ask required by file 11 correction note 3 line 227 ("ancestors come from Organisation's own Tree at (d, τ), with the tree version, recorded in the basis as `structureRef`. Organisation re-asks if the tree version changed"). S1 line 138 supplies the ancestor chain but not the version. Add it, or state that a `renew-membership` act's scope does not use the tree.

**Verdict: CONFIRMED.**

### Q6 — S2 decisions: **CONFIRMED**

S2 §13.0 (lines 224–238) reads D-113 correctly:

- D-113 (file 04 line 878) says verbatim: "no Person is created at import; the reference is still issued at import; the claim code is tied to the organisation's `PersonRef`, not to a `PersonId`. … Answers K4 and P-I1."
- S2 §13.0 line 226 quotes it; lines 228–232 apply it to `ReferenceIssuer`; §4 steps 1–3 (lines 87–89) issue R at import without a Person; step 4 (line 89) ties the claim to R; step 6 (line 91) binds R to the registered Person.
- I-TDD-01 predates D-113: file 12 §11 P-I1 line 169 leaves K4 open; §2.3 line 65 says "the invariants hold either way"; §5 line 103 (`CreateClaim`) is the pre-D-113 shape. S2 §13.0 line 238 correctly says I-TDD-01 is not edited here and its open K4 seam is superseded. The `ClaimRedeemed` wording is corrected separately in §13.1 (see Q9).

**Verdict: CONFIRMED.**

### Q7 — S2 claim authority: **PARTIAL**

**What holds.**

- The internal port only (no public endpoint): S2 §13.2 line 252; ISO-3/ISO-9.
- The reference-belongs-to-organisation check: line 253–255 — R was issued for the same organisation; the context's organisation = R's organisation; a PersonRef from another organisation is refused.
- Fresh `Authorised` evidence: lines 257–259 — the six fields + basis, `outcome = Authorised`, same organisation, `checkedAt` within a short freshness window [TU]; Identity refuses a missing, foreign, stale or non-Authorised answer.
- Later authority change does not void issued claims (the act was authorised on its decision date; the organisation may revoke): line 260; D-12.
- Revocation/redemption race on the one central Claim row, versioned, first commit wins; revoking a redeemed claim refused `AlreadyRedeemed`; undoing a binding is not revocation (C-5): lines 262–265.

**What is still replayable (M-4).** The AuthorityCheck answer is **not bound to the specific claim subject or issuance id**. The six fields + basis carry `outcome`, `evaluatedFor` (the business date), `competenceKind`, `decisionRef` and `evidence`; they do not name R or the issuance id. Identity checks the answer's fields, the organisation and the freshness window, but not that the answer was evaluated **for this claim**. Within the same organisation and the freshness window, a valid `Authorised` answer for one `IssueClaim` can be replayed for another `IssueClaim` or `RevokeClaim` — by a buggy or compromised Organisation service, or by any caller that can reach the internal port. The R check limits the blast radius to the same organisation, not to the same subject. "Identity verifies provenance" (line 258) verifies fields, not that the issuer evaluated this act. **M-4.**

**What is over-centralised (M-5).** §13.2 step 3 (line 258) says "Identity stores it on the Claim as provenance" — the **full** six fields + basis, including actor `PersonRef`, capacity/tenure reference, `ruleRef`, `policyVersion`, `decisionRef` and evidence fingerprints. That is organisation governance evidence. D-164 (file 04 line 1102) says central holds **only** registration/Account, auth/sessions, the registry, the Account–organisation association and shared reference data; each organisation's database holds governance, outbox/inbox and audit. D-111 (line 866) says each organisation's business data belongs to it. The Claim row itself (code hash, R, window, state) is central under D-113/I-TDD-01; the **full authority answer** is not obviously central. Either this is a D-164 exception (Principal decision) or the central provenance should be minimised (organisation id, issuance id, actor ref, outcome, checkedAt) with the full basis kept in the organisation's `ClaimIssuance` record. **M-5.**

**Low note (L-11).** Step 2 (lines 253–255) checks that R belongs to the organisation, but not that the claim's local entry E is the E for which R was issued. `ReferenceIssuer` is keyed by (organisation, E), so Identity could verify (organisation, E, R) if E is sent; otherwise a buggy Organisation could issue a claim for R against the wrong local entry. Low; organisation-side check.

**Verdict: PARTIAL.**

### Q8 — S2 recovery: **CONFIRMED**

I tested each crossing in §13.3 (lines 267–300):

| Crossing | Intermediate state | Harmless / recoverable? |
|---|---|---|
| 1. Local entry E created (org) | E without R | Harmless; entries without R are re-requested (reconciliation, line 298). |
| 2. Request R (Identity) | R issued, unbound, idempotent on (org, E) | Harmless; retry returns the same R; an unbound R proves nothing (D-113). |
| 3. Store R on E (org) | E.R | Harmless; a missing R is reconciled. |
| 4. Intent to issue (org) | `ClaimIssuance(id, E, pending)` | Harmless; it is the durable key that lets a retry/revoke identify the claim. |
| 5. `IssueClaim` (Identity atomic) | Claim + outbox `ClaimIssued` | Harmless; retry answers "already issued"; the code hash is stored, the plain text is not. |
| 6. Record result (org) | `ClaimIssuance(id, issued)` | Recoverable from `ClaimIssued` (inbox) or reconciliation. |
| 7. Redeem (Identity atomic) | binding + claim consumed + outbox `ClaimRedeemed` | Harmless; single use; a second redemption refused; the binding is routing only. |
| 8. Receive `ClaimRedeemed` (org) | inbox + E claimed | At-least-once; dedup by event id; idempotent by claim id; retried under the event's organisation context. |

**Lost reply at claim issue (lines 280–283).** Identity committed the claim; the organisation did not receive the code. Retry says "already issued"; the organisation revokes (it has the issuance id from step 4) and issues anew (new issuance id); the first code expires. Forward recovery, not compensation: no committed fact is undone. Correct.

**Redemption committed while the organisation database is down (lines 287–291).** The centre has the binding and the consumed claim atomically; the organisation's entry shows unclaimed; the person can be routed; "sees nothing beyond what the organisation's own rules allow"; the event retries; the inbox dedups; processing is idempotent by claim id. Harmless and recoverable. Correct. **L-14** notes one overstatement: "the first code is never usable without the person receiving it" (line 283) is too strong — an intercepted code is usable; the window and revoke/reissue are the controls. **L-15** notes "not yet marked claimed changes no right" (line 291) holds only if the organisation's rules do not use the mark as an access condition; state that the mark is administrative, or accept the temporary denial.

**Verdict: CONFIRMED.**

### Q9 — S2 `ClaimRedeemed`: **CONFIRMED**

S2 §13.1 (lines 240–246) corrects I-TDD-01's stale "that person now has access to that organisation" (file 12 line 93) to: R is now bound to a registered Person; requests by that person's Account may be **routed** to the organisation; it grants nothing else — no membership, voting, office, authority, forum or newsletter right, no workspace access; the organisation may only mark its entry "claimed", show the person their own record, and let its own rules decide everything else.

This is consistent with D-113 (file 04 line 878: binding is not a merge; at most one reference per organisation per Person), D-164 (line 1102: the central association never establishes membership, voter status, office, access or authority), D-104 (line 844: workspace access is a distinct fact), D-161 (line 1076) / D-152 (line 1020) (forum posting requires belonging and standing) and file 14 M-1 (lines 234–241). **CONFIRMED.**

**Low note (L-13):** §13.7 line 352 says "A redeemed claim lets a person reach the member area" — colloquially readable as access. The next sentence qualifies it, but reword to "lets the person's requests be routed to the organisation's member area; the organisation's rules decide what is shown". **Low note (L-16):** file 12's line 93 remains stale; S2 corrects it for the Identity session (line 242), which is the right treatment, but the source text should carry the correction when that session is next edited.

### Q10 — S2 isolation: **PARTIAL**

**The A/B isolation question.** ISO-1…ISO-7 and ISO-9 (lines 315–323) answer it: one organisation context per operation (ISO-1); the connection comes only from the registry (ISO-2); an authenticated request's organisation must be one the account is bound to, and a conflicting organisation id is refused (ISO-3); every PersonRef is checked to belong to the organisation (ISO-4); no second organisation connection (ISO-5); jobs carry the context and the connection is reset between jobs (ISO-6); the binding is never read as membership, access or authority (ISO-7); an inbox event is processed only in the organisation named in it and only by the rightful recipient (ISO-9). The tests at line 325 cover concurrent A/B requests, a PersonRef from B, the query bypass, the claimed entry, the binding-as-authority case and a cross-organisation job. Public endpoints (line 324) require an organisation context for organisation-scoped public forms; delayed `ClaimRedeemed` (line 326) is a late event, not a leak.

**ISO-8 is stale against D-166 (M-6).** The ISO-8 row (line 322) says privileged/platform operations "run per organisation with explicit context and are recorded; they never read business content as part of their function". It does **not** require the organisation's **joining grant** for technical operations, the **case grant** for content access recorded in the organisation's own database, or the record/report to the organisation. D-166 (file 04 line 1116) does: platform staff access only where the organisation provides the access; technical operations granted once at joining; content access a case-by-case grant recorded in the organisation's own database, owned by Organisation; without a grant refused; no legal-obligation ground. §13.9 (lines 384–388) states D-166 correctly, but the **invariant row** — the operative list the question tests — is the pre-D-166 wording. An implementation from the table alone would not implement D-166. **M-6.**

**A missing invariant (M-7).** Nothing in ISO-1…ISO-9 covers **logs, errors, traces, metrics or telemetry**. D-75 §5 (file 04 line 40) names "disclosure through logs, events, APIs or error messages" as a threat. I-TDD-01 TH-3 (file 12 line 151) answers it with "neutral failure messages; events only to the issuing organisation", but that is a threat answer in another design, not an S2 invariant and not in the architecture test. Add ISO-10: every log/error/trace/metric carries the organisation context and never includes another organisation's data; errors are neutral; test with a cross-organisation error/log case. **M-7.**

**Connection reuse (L-9).** ISO-6 (line 320) says the connection "is reset between jobs (must)", and the test alternates A/B in one worker. "Reset" is not defined: `search_path`, temporary tables, `SET`/GUCs, prepared statements and session-level state can survive a pooled connection. Define reset to include session state, or use a fresh (or per-organisation) connection for each operation. **Low.**

**ISO-5 scope (L-10).** Line 313 says "cross-organisation questions exist only inside Identity (none in release 1)". If the organisation picker (`AffiliationDiscovery`, file 12 §5/A-2) is in release 1, Identity does have one — inside Identity, returning only the signed-in person's own list. Clarify "none in release 1" or name the picker. **Low.**

**Registry integrity (L-12).** ISO-2 derives the connection only from the registry entry; the entry's database locator is therefore a trust anchor. The design does not state that the locator is written only by provisioning and audited, and that no request can influence it. Add a sentence or an invariant. **Low.**

**Verdict: PARTIAL** — the A/B isolation model is sound; ISO-8 and the observability gap are real holes.

### Q11 — S2 availability: **CONFIRMED**

§13.6 (lines 329–348) states fail-closed precisely:

- Durable registry statuses: `provisioning · operational · suspended (later) · closed (later)`, plus a durable `schemaState` (`current` / `migration-failed`) that blocks routing until repaired. **Reachability is a health observation, not a status**; §3's `unavailable` is withdrawn (line 335).
- The centre is needed for session validity, binding (ISO-3) and routing (ISO-2): new login, existing session, selection, routing — yes; identity → organisation event delivery — yes (source); organisation → identity calls — yes (target); an already-running request — no new central read, it completes on the context resolved at entry; after recovery — resume, nothing proceeded on stale routing; organisation-internal jobs — yes, for routing at job start (the Addendum M exception is withdrawn, §0.3 line 23; one fail-closed rule).
- The central database is a single point of failure (line 143); "if the centre is down, everything waits" (line 335). A later design may let organisation-internal jobs run on a signed routing snapshot [TU; later], if central availability proves insufficient (line 348) — a future option, not a release-1 exception.

**Low note (L-7):** §3 (lines 60–66) still lists `unavailable` as a durable status in the normative lifecycle table, while §13.6 withdraws it. The "§13 governs" rule makes §13.6 operative, but the stale row should be marked superseded or removed; a reader who starts at §3 would implement a health state as a status.

**Verdict: CONFIRMED.**

### Q12 — Both: **CONTRADICTORY**

The contradictions and over-claims are H-1, H-2, M-1, M-2, M-3, M-4, M-5, M-6, M-7, plus the lows in §3. I found **no invented business rule presented as ratified**; the candidates are:

- S1 §3's "release-1 admissions must state a term" — a [TD] product policy, not presented as business law (line 58);
- S1 §7's "parts independent unless the resolution declares an indivisible group" — a [WD] premise whose source is **UNAVAILABLE** in the package (M-3), not an invented rule;
- S1 §16 C-2's refusal of a restriction on a lapsed period — the ratified guard, correctly read;
- S2 §13.6's one fail-closed rule — a design choice, not a business rule; it withdraws Addendum M's exception (§0.3 line 23);
- S2 §13.2's "a later authority change does not void issued claims" — consistent with D-12's decision-time authority, not invented.

The **claims stronger than their evidence** are:

- S1 line 256 "version conflicts inside the group cannot occur" and line 257 "one lock order means no deadlock" — H-2;
- S1 line 252 "retry is idempotent on the group key" — M-2 (no mechanism);
- S1 V-4 line 20 "No universal rule invented" — M-3 (source unavailable);
- S2 line 258 "Identity verifies provenance" — it verifies fields, not the issuer/subject — M-4;
- S2 line 283 "the first code is never usable without the person receiving it" — L-14;
- S2 line 291 "not yet marked claimed changes no right" — L-15;
- S2 line 335 "§3's `unavailable` is withdrawn" — stated, but not edited into §3 — L-7;
- S2 V-7 line 155 "Confirmed against … E-11" — E-11 is not in the package — L-17;
- S1 §15 line 207 "READY FOR PRINCIPAL TACTICAL ACCEPTANCE" — stale — L-2.

**Verdict: CONTRADICTORY.**

---

## 3. Findings

### High

#### H-1 — S1: §4's acceptance rules omit the corrected "in force" guard for Suspend/Restore

- **Evidence.** S1 §4 lines 69–70 (Suspend "as Renew (period exists and not ended)"; Restore "s live; not already restored; `s.from < effectiveAt < s.until`"; refusal lists without `NotInForce`); S1 §5 row 15 line 98 (refused `NotInForce`; ratified guard "in force", final §18, REQ-MEM-SUSPENSION); S1 §16 C-2 lines 237–241 ("Added refusal: `NotInForce` for Suspend and Restore"); file 06 §18 line 360 (guard "in force"); reviewer prompt line 11 (§16 governs).
- **Fact or inference.** Fact.
- **Impact.** An implementation of §4 alone accepts a suspension or restore of a lapsed period. That is the very case the ratified guard forbids: a restriction placed while lapsed would bite on a later renewal without a new decision, contrary to C-2's remedy ("renews first and suspends in the same decision"). The refusal outcome `NotInForce` is absent from the command table, and §5 has no row for Restore-on-a-period-that-lapsed-during-suspension.
- **Principal decision needed.** No — correct the table. Add "in force" to the Suspend and Restore acceptance conditions, add `NotInForce` to both refusal sets, add the missing Restore-of-lapsed row, or mark §4 superseded by §16.

#### H-2 — S1: the grouped-renewal lock-order argument is false against the accepted C1/C2 append procedure

- **Evidence.** S1 §16 C-3 line 251 ("first takes the organisation's ledger-clock lock, then decides and appends every member's Renew"), line 256 ("version conflicts inside the group cannot occur"), line 257 ("one lock order means no deadlock"); file 09 §4 line 73 ("Append (inside the existing transaction, **after the version compare-and-set**)") and lines 74–77 (clock lock, `recordedAt`, update, insert, outbox, commit); S1 §14 line 201 (lock order listed as [TU]).
- **Fact or inference.** The two orders are fact. The deadlock interleaving is inference: a concurrent single append that has taken a member row lock via the version compare-and-set and then waits for the clock lock, while the group holds the clock lock and waits for that member's row.
- **Impact.** PostgreSQL detects the deadlock and aborts one transaction. The group's all-or-none guarantee survives (the aborted transaction rolls back), so this is not a data-corruption defect; but the design's central proof is wrong, the group can deadlock with ordinary renewals, and under contention the group (holding the organisation's clock lock) can stall and retry repeatedly. The design's own §14 already treats the lock order as a [TU], so C-3's [AC] is stronger than its evidence.
- **Principal decision needed.** No — establish one lock order for **all** Membership appends (take the clock lock before the version compare-and-set everywhere), or make the group use the same order as single appends; obtain the one AuthorityCheck answer **before** taking the lock; keep the lock only for the decisions/appends; document the retry.

### Medium

#### M-1 — S1 vs S2/file 14: the `InForceUnrestricted` deriver is stated in two different layers

- **Evidence.** S1 §2 line 49 ("Derived by **Organisation** for its consumers"); S1 §10 line 154 ("Organisation | derives `InForceUnrestricted` per person at the operation's τ"); S2 §0.2 line 18 ("§2B: **consumers derive** `InForceUnrestricted`; Organisation forwards Membership's intervals"); file 14 §2B line 45 ("**Organisation does not derive `InForceUnrestricted`.** It forwards Membership's intervals unchanged and attributed to Membership … The consuming context makes the final access decision") and lines 47–48; D-161 (file 04 line 1076); CR-02 (file 08 §2 lines 19–30).
- **Fact or inference.** Fact.
- **Impact.** Two release-1 designs disagree on where the access predicate is computed. If Organisation derives at the operation's τ and a consumer derives at a different τ, the two can disagree; the "consumer-derived" name is still not a fact or contract field, so CR-02 is unchanged, but the layer is unresolved.
- **Principal decision needed.** No if aligned to S2/file 14 (Organisation forwards intervals; the consuming context applies the rule). If the Principal prefers Organisation to derive, record it and align S2/file 14; either way, one statement in both subjects.

#### M-2 — S1: the grouped-renewal outcome and idempotency are asserted, not designed

- **Evidence.** S1 §16 C-3 line 252 ("retry is idempotent on the group key"); lines 248–249 (`GroupApplication` Prepared → Completed / Inapplicable); line 260 (rollback on refusal); §7 line 126 (each member's Renew is a part); S2 §2 line 52 (one deployable per context; no cross-context table access); S1 §14 line 201.
- **Fact or inference.** The missing transaction boundary is fact from the text; the crash-after-commit window is inference.
- **Impact.** A crash after the Membership commit but before the `GroupApplication` state update leaves the group Prepared with the facts applied. Without a durable group outcome or a detection rule, a retry can double-append (duplicate Renew facts; the semantic union hides it, but history, `seq` and C1 events multiply) or refuse a group that is already applied. The all-or-none of the committed transaction holds; the **outcome state** can be wrong.
- **Principal decision needed.** No — define the group key and its durable outcome, and the retry check (e.g. all parts for the group carry the same decisionRef and are present → Completed). If the group state and the Membership facts are in the same organisation database, decide how to update the Governance state without cross-context table access (an idempotent outcome event, or a Membership-side applied-group marker reconciled by Governance).

#### M-3 — S1: the indivisible-group / `GroupApplication` premise is **UNAVAILABLE**

- **Evidence.** S1 V-4 line 20, §7 line 121, §16 C-3 lines 248–249, all citing "model document §20.1 [WD]" for a Resolution whose parts may form an indivisible group applied all or none. The model document is not in files 01–14; file 11 line 115 describes a Resolution "with parts … and competence evaluation per part", and line 143 lists `PartApplied` / `PartInapplicable`, but does **not** state the indivisible-group rule. No model document is present in the package.
- **Fact or inference.** The absence is fact; whether the rule exists outside the package is inference.
- **Impact.** The reason a grouped renewal is all-or-none rests on an unavailable source. The single-transaction mechanism is assessable on its own; the *premise* — that a resolution can declare its parts indivisible — is not evidenced in the package, so "No universal rule invented" (line 20) is a claim stronger than the package supports.
- **Principal decision needed.** No — supply or quote model §20.1, or mark the dependency **UNAVAILABLE** and have the Governance session confirm it. (The commission's rule is to mark it UNAVAILABLE, not to fill it in.)

#### M-4 — S2: the AuthorityCheck answer is not bound to the claim subject; it can be replayed within the freshness window

- **Evidence.** S2 §13.2 lines 257–259 (the answer carries the six fields + basis for `issue-claim`/`revoke-claim`, `outcome = Authorised`, same organisation, `checkedAt` fresh; Identity stores it as provenance; refuses missing/foreign/stale/non-Authorised); file 10 §3 lines 47–80 (the answer's fields; `evaluatedFor` is the business date; no subject/issuance id); file 11 §6.2 lines 119–134.
- **Fact or inference.** The text is fact; the replay path is inference.
- **Impact.** Within the same organisation and the freshness window, a valid `Authorised` answer for one claim issuance can be replayed for a different `IssueClaim`/`RevokeClaim`. Identity verifies the answer's fields, not that the issuer evaluated **this** R and issuance id. The R check limits the blast radius to the same organisation, not to the same person or entry; a buggy or compromised Organisation service (or a caller reaching the internal port) can issue a claim without a claim-specific authority check.
- **Principal decision needed.** No — bind the answer to R and the issuance id (include them in the AuthorityCheck `scope`, or in a request digest the answer signs/carries), and have Identity verify the binding; alternatively make the answer a one-time token tied to the issuance.

#### M-5 — S2: storing the full AuthorityCheck answer centrally conflicts with D-164 / D-111

- **Evidence.** S2 §13.2 line 258 ("Identity stores it on the Claim as provenance"); the answer includes actor `PersonRef`, capacity/tenure ref, `ruleRef`, `policyVersion`, `decisionRef`, evidence fingerprints (file 10 §3 lines 47–80). D-164 (file 04 line 1102) says central holds **only** registration/Account, auth/sessions, the registry, the Account–organisation association and shared reference data; each organisation's database holds governance, outbox/inbox and audit. D-111 (file 04 line 866) says each organisation's business data belongs to it. S2 §2 (line 52) is the ownership matrix.
- **Fact or inference.** The text is fact; the classification of the full answer as organisation governance data is inference (strong: `ruleRef`, capacity and `decisionRef` are Governance's).
- **Impact.** Organisation governance evidence sits in the central database outside D-164's "only" list and D-111's ownership. The Claim row itself is central under D-113/I-TDD-01; the **full authority basis** is not obviously platform infrastructure. This is a data-minimisation/privacy issue and a decision-conformance issue.
- **Principal decision needed.** **Yes**, if the Principal wants the full evidence kept centrally — that is a D-164 exception and should be recorded. Otherwise no: store the minimal provenance centrally (organisation id, issuance id, actor ref, outcome, checkedAt) and keep the full answer in the organisation's `ClaimIssuance` record; or store a hash/reference centrally and the evidence in the organisation database.

#### M-6 — S2: ISO-8 is stale against D-166

- **Evidence.** S2 §13.5 line 322 (ISO-8: privileged/platform operations run per organisation with explicit context and are recorded; never read business content as part of their function); S2 §13.9 lines 384–388 (D-166: technical operations need the organisation's joining grant; content access needs a case grant recorded in the organisation's own database, owned by Organisation; without a grant refused); D-166 (file 04 line 1116); D-165 (file 04 line 1110).
- **Fact or inference.** Fact.
- **Impact.** The invariant row — the list a reader implements — does not require the joining grant or the case grant; only the §13.9 note does. An implementation from the table could allow staff operations without the organisation's grant, contrary to a binding decision. Q10 explicitly tests D-166.
- **Principal decision needed.** No — update ISO-8 to the D-166 wording: technical operations need the joining grant (a registry fact) and explicit context; content access needs a case grant recorded in the organisation's own database, owned by Organisation; every access is recorded and reported; without a grant, refused; add the test.

#### M-7 — S2: no isolation invariant covers logs, errors, traces or metrics

- **Evidence.** S2 §13.5 lines 315–323 (ISO-1…ISO-9 cover data access, connection, binding, PersonRef, jobs and the inbox; none covers observability); D-75 §5 (file 04 line 40: disclosure through logs, events, APIs or error messages); I-TDD-01 TH-3 (file 12 line 151: neutral failure messages; events only to the issuing organisation) — a threat answer in another design, not an S2 invariant.
- **Fact or inference.** The absence is fact; the leak path is inference.
- **Impact.** A log line, error, trace or metric from an A request can carry B's identifiers or data; the ISO list and the architecture test do not cover it. D-75 §5 names it as a disclosure route.
- **Principal decision needed.** No — add ISO-10: every log/error/trace/metric carries the organisation context and never includes another organisation's data; errors are neutral; test a cross-organisation error/log case; keep central logs free of organisation business content.

### Low

- **L-1 (S1) — document control.** S1 line 1 says "PROPOSED, revision 2"; line 5 says "PROPOSED, revision 3 (corrections in §16)". The commission treats it as revision 3. Fix the title.
- **L-2 (S1) — stale assessment.** S1 §15 line 207 says "READY FOR PRINCIPAL TACTICAL ACCEPTANCE"; §16 line 269 says "ready for an independent check, then Principal acceptance"; the document is PROPOSED. The §15 verdict predates the corrections.
- **L-3 (S1) — split remedy.** §16 C-3 line 261 says "The organisation can split the resolution". If the resolution declared an indivisible group, splitting the *application* breaks the guarantee; require the organisation to **re-decide/amend** with smaller groups.
- **L-4 (S1) — one answer, all parts.** §7 line 127's "one AuthorityCheck answer … serves all its parts" is sound only if every member is within the answer's scope; state that the resolution's scope covers all parts, or check per part.
- **L-5 (S1) — `restrictions(q)` formula.** §2 line 42's `min(s.until, r.from)` is undefined when there is no live Restore; state the no-Restore case (`[s.from, s.until)`, `∞` open).
- **L-6 (S1) — `structureRef`.** §8 line 136's basis omits the `structureRef` / tree version / re-ask required by file 11 correction note 3 line 227; line 138 supplies the ancestor chain but not the version. Add it or state it is not applicable to `renew-membership`.
- **L-7 (S2) — `unavailable` residue.** §3 lines 60–66 still lists `unavailable` as a durable status; §13.6 line 335 withdraws it as a status (reachability is health). Mark §3's row superseded or remove it.
- **L-8 (S2) — V-6 classification.** §7 line 154 says the Suspend payload's `until` is [TD], "not ratified wording"; the ratified final §3's `restrictions(q)` uses `s.until` (S1 §2 line 42, [RAT] final §3, §7). Clarify that the fact's interval end is ratified and only the command field/encoding is [TD].
- **L-9 (S2) — connection reset.** ISO-6 line 320 says the connection "is reset between jobs"; define reset to include session state (`search_path`, temp tables, `SET`/GUCs, prepared statements) or use a fresh/per-organisation connection.
- **L-10 (S2) — ISO-5 "none in release 1".** Line 313 says there are no cross-organisation questions except inside Identity "none in release 1"; if the organisation picker (`AffiliationDiscovery`, file 12 A-2) is in release 1, Identity has one (inside Identity, own list only). Clarify the scope.
- **L-11 (S2) — E/R consistency.** §13.2 step 2 checks R's organisation but not that the claim's local E is the E R was issued for; Identity is keyed by (organisation, E) and can verify if E is sent. Low, organisation-side check.
- **L-12 (S2) — overstatements in §13.3.** Line 283's "the first code is never usable without the person receiving it" is too strong (an intercepted code is usable; the window and revoke/reissue are the controls); line 291's "not yet marked claimed changes no right" holds only if the organisation's rules do not use the mark. Rephrase.
- **L-13 (S2) — "reach the member area".** §13.7 line 352's "lets a person reach the member area" is colloquially readable as access; §13.1's corrected meaning is routing only. Reword to routing.
- **L-14 (S2) — E-11 UNAVAILABLE.** S2 line 7, line 41 and V-7 line 155 cite E-11 (tenancy is a platform capability); E-11 is not in the package (D-106 is). Mark E-11 UNAVAILABLE or cite D-106's text.
- **L-15 (S2) — Addendum M residue.** File 14 Addendum M §M-7 still allows organisation-only jobs to continue on a signed routing snapshot; S2 §0.3 line 23 and §6 withdraw it. S2 governs for S2; mark M-7 superseded in file 14 when that document is next edited.

---

## 4. Verdict per subject

### S1 — READY AFTER CORRECTIONS

The decision table, the ratified definitions and guards, the authority contract and the aggregate rules are substantively sound. The corrections are targeted:

1. **H-1** — add the "in force" guard and `NotInForce` to §4 Suspend/Restore; add the Restore-of-a-period-that-lapsed-during-suspension row; or mark §4 superseded by §16.
2. **H-2** — establish one lock order for all Membership appends (clock lock before the version compare-and-set), or align the group with the normal append; obtain the AuthorityCheck answer before the lock; document the retry.
3. **M-1** — align the `InForceUnrestricted` deriver with S2 §0.2 / file 14 §2B (Organisation forwards intervals; the consuming context derives), or record the Principal's different choice in both subjects.
4. **M-2** — define the group key, its durable outcome and the retry-detection rule.
5. **M-3** — supply/quote model §20.1 or mark the indivisible-group premise UNAVAILABLE.
6. **L-1…L-6** — title revision, stale §15, split remedy, one-answer scope, `restrictions(q)` no-Restore case, `structureRef`.

No correction requires a business decision from the Principal; M-1 is a design alignment unless the Principal wants Organisation to be the deriver.

### S2 — READY AFTER CORRECTIONS

The strategic reading of D-164/D-113, the claim flow, the recovery model and the A/B isolation model are substantively sound. The corrections are targeted:

1. **M-4** — bind the AuthorityCheck answer to R and the issuance id; have Identity verify the binding (or make the answer one-time).
2. **M-5** — minimise the central provenance (organisation id, issuance id, actor ref, outcome, checkedAt), keeping the full answer in the organisation's `ClaimIssuance` record; **or** obtain a Principal decision recording a D-164 exception for the full evidence.
3. **M-6** — update ISO-8 to D-166: joining grant for technical operations; case grant for content access recorded in the organisation's database, owned by Organisation; recorded and reported; without a grant refused.
4. **M-7** — add ISO-10 for logs/errors/traces/metrics and a cross-organisation error/log test.
5. **L-7…L-15** — §3 `unavailable` residue, V-6 `until` classification, connection reset, ISO-5 scope, E/R check, §13.3 overstatements, §13.7 wording, E-11 UNAVAILABLE, Addendum M M-7 residue.

Only M-5 may need a Principal decision (whether the full authority evidence may be central). The rest are corrections.

**Neither subject is NOT READY**: the defects are in acceptance tables, proof wording, one lock order, one binding field, one invariant and one data-placement choice — not in the domain model or the multi-database strategy. **Neither subject is READY FOR PRINCIPAL ACCEPTANCE** until the high and medium findings are corrected; the Principal may, of course, decide otherwise, which is not this reviewer's decision to make.

---

## 5. What this review does not establish

- It does not establish implementation correctness, test execution, migration safety or runtime behaviour; the commission excludes implementation (file 01 line 9).
- It does not establish the organisations' written rules, the per-organisation renewal-while-suspended statute, the admission term or the authority of any actual office holder; those are outside the package.
- It does not establish that the Governance model's §20.1 indivisible-group rule exists as S1 describes; that source is **UNAVAILABLE** (M-3).
- It does not establish independence. I am `deepseek-chat` in Claude Code, the excluded model and interface. The independent review the commission requires — a human architect or GPT/Gemini/Mistral in its own interface — remains outstanding.
- It does not accept either design, decide any open question for the Principal, or modify any supplied document.

*End of report. Stop after the report.*
