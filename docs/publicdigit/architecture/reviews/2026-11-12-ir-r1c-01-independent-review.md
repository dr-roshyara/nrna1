# IR-R1C-01 — Independent Review: Strategic Baseline v1.1 rev 4 and Release-1 Contract Additions

**Reviewer:** GPT-6 in ChatGPT  
**Commission:** IR-R1C-01, issued by the Principal (D-94), 2026-11-12  
**Prior involvement:** GPT-6 reviewed the binding Governance completion, ledger-clock and M-TDD-01 source designs in IR-GM-01 and IR-GM-01-R1. GPT-6 had not previously reviewed these two frozen subjects.  
**Review boundary:** S1 and S2 only, within the exact scope in commission file 01. No decisions made, no files modified, no implementation authorised, no tests executed.

---

## 1. Integrity and source control

### 1.1 Package integrity

- Uploaded ZIP: `ir-r1c-01-review-handover.zip`
- ZIP SHA-256: `0f600c1fc5dc987ec115d0c332f997e270f63aa9b1db3db76b724dcea2574065`
- `MANIFEST.sha256`: **41/41 entries verified**
- Outer `SHA256SUMS`: **43/43 entries verified**
- Both checksum sets were checked against the extracted package.

### 1.2 Frozen subject fingerprints

| Subject | Revision | Recalculated SHA-256 | Result |
|---|---:|---|---|
| S1 — `publicdigit-strategic-ddd-baseline.md` | 4 | `8e74bb6dc45964b6090834cb09c03ac4f71d6b0988ff2fa189310251142e8080` | MATCH |
| S2 — release-1 tactical completion and implementation blueprint | 2 | `ca5fad2ba1a24b44ab3a38ffeeed738baf2d4a69f9aad963a74d1bc4c5302217` | MATCH |

The supplied revision 3 / revision 1 documents and their diffs were treated as historical comparison material. Deleted diff text was not treated as current requirements. File 08 was treated as the binding decision record, as commissioned.

**Integrity verdict: PASS.** The reviewed bytes are the exact commissioned subjects.

---

## 2. Executive verdict

| Subject / contract | Verdict | Reason |
|---|---|---|
| **S1 — strategic DDD baseline v1.1 rev 4** | **READY FOR RATIFICATION** | Faithful consolidation of D-173 through D-213; revision 3 omissions and formatting defects are corrected. E-1 needs explicit attribution in the ratifying act, not a new business decision. |
| **S2 — release-1 blueprint rev 2** | **READY AFTER SPECIFIC CORRECTIONS** | The business ownership model and flows are substantially sound. T-1 needs a defined Governance knowledge boundary and unambiguous result semantics. T-4 conflicts with the accepted contact-at-delivery rule and lacks a defined `NoContact` reason. §6 also overstates the source authority for the proposed one-minute scheduler. |
| **T-1 — `OfficeCoverageAsAt`** | **AFTER SPECIFIC CORRECTIONS** | The business question and separation from `AuthorityCheck` are correct; the DecisionLog boundary and returned-tenure semantics are not sufficiently defined. |
| **T-2 — `CapacityBelonging…`** | **READY AFTER ONE SPECIFIC CLARIFICATION** | It preserves K-4, avoids Membership, and correctly implements D-213. Bind its valid-time date to the same business-time date used by K-4 in release-1 flows. |
| **T-4 — `DeliveryContactsAsAt`** | **AFTER SPECIFIC CORRECTIONS** | Its ownership is correct, but its timing conflicts with D-210 §7 and its payload does not specify the reason required for `NoContact`. |
| **K-C1 — preserve V-5 by refusal, not timestamp adjustment** | **READY FOR ARB ACCEPTANCE** | This is compatible with D-209 and preserves the already approved COR-4 V-5 requirement. Add the complementary proof that equal timestamps remain permitted across different Membership streams. |

**No new business decision is identified.** The remaining actions are ratification/ARB acceptance and specific contract corrections. The suspended-member-plus-staff newsletter consequence is already determined by D-213 and is not reopened.

---

## 3. S1 — strategic baseline v1.1 revision 4

### Question 1 — Faithfulness to D-173 … D-213

**Result: PASS. [E]**

S1 is a consolidation, not a new strategic model. Its principal statements are supported by the binding sources:

- The business purpose and legitimacy differentiator follow D-100, D-1a, D-167 and D-211 (S1 §1, lines 29–59).
- The core / supporting / generic classification matches D-211 (S1 §2, lines 61–75; decision record file 08, D-211).
- The bounded-context ownership table is consistent with the accepted D-173 context table and the later accepted designs (S1 §3, lines 77–95).
- Governance authority, Membership truth, Organisation belonging, Identity and Platform responsibilities remain separate (S1 §4, lines 97–123).
- D-212 is represented as strategic responsibility accepted but not yet available; the required Adjudication and Governance changes remain explicit gates (S1 §8, lines 207–222; decision record D-212).
- D-213 is correctly included in the newsletter audience rule: members are filtered by the participation rule; other capacities named by the creating decision qualify by their own valid belonging (S1 §4, line 109; §5, line 177; §6, lines 180–190; decision record D-213).

No new business rule was identified in S1. Its ownership statements and scope distinctions are consolidations of accepted decisions and designs.

### Question 2 — Internal consistency and revision 3 corrections

**Result: PASS. [E]**

The three reported revision 3 defects are corrected:

1. **Decision coverage:** revision 4 reflects D-213 rather than stopping at D-212.
2. **§7 table:** the temporal-rule text is now within the table; the previous detached bullet lines no longer break the table structure.
3. **Geography relationship:** new §4a explains the relationship between official reference units, organisation units, coverage references, addresses and belonging (S1 §4a, lines 125–144).

The unresolved list is also appropriately cautious. U-5 and U-9 identify the proposed T-1 and T-2 closures as not yet accepted; they are not represented as already settled. U-1, U-2, U-3, U-6, U-8 and U-10 remain explicitly unresolved or deferred where appropriate (S1 §9, lines 224–237).

The broad Identity → every context relationship in §5 (line 150) does not silently settle the Geography steward identity issue: U-2 remains explicitly open, and S2 §8 correctly says not to borrow an organisation `PersonRef` for that purpose. No material contradiction is established.

### Question 3 — §4a: Geography, units, addresses and belonging

**Result: PASS. [E]**

Each row is supported by the cited accepted material:

- **Official reference unit:** Reference Geography owns the shared official map and stable `ReferenceUnitId`; it does not decide organisation belonging (D-31 and ratified D-93).
- **Organisation unit:** the organisation defines its own hierarchy and parent relationships independently of official Geography parents (Organisation §49.5, especially lines 942–952 in file 21).
- **Coverage reference:** Organisation stores the official reference relied on, the Geography knowledge position, its own valid period and basis (Organisation §49.6, lines 964–973).
- **Address:** an address is organisation-local profile information; it does not itself place a person in a unit. It may at most support a suggestion (D-80, D-79, D-101).
- **Belonging:** it is an Organisation fact based on an authorised act, or on a residence/area rule expressly adopted by the organisation's written rules; it does not arise automatically from an address or map change (D-79, D-114, Organisation §50 and §51).
- **Map change:** it causes review flags where required; coverage, allocation and belonging remain as recorded until the organisation decides. Units, members and committees are not moved automatically (Organisation §49.6, lines 975–985).

S1 §4a does not create a universal street register or make official geography the owner of organisation units. It correctly preserves the D-93 release boundary: the Geography design is ratified, but its use/integration is later release work (S1 §4a, lines 137–144).

### Question 4 — E-1: multi-database conformance note

**Result: SUBSTANTIVELY RESOLVED. [E]**

The four statements in the note are already supported by accepted decisions/designs, although the note's original attribution was incomplete:

| Note item | Correct establishing source |
|---|---|
| ISO-11 case-grant session / separate operations channel | Platform rev 11 H-3 and gateway, accepted by D-193 |
| `schemaState = migrating` is unroutable | Platform rev 11 M-8, accepted by D-193 |
| `pending` precedes `provisioning`; no technical operation before verification and service grant | D-185, with Platform rev 11 consistency under D-193 |
| Registrar's first claim is the bootstrap exception | Platform rev 11 M-3 / D-193 and Identity I-TDD-02 / D-200 |

S1 Appendix E, lines 305–332, accurately distinguishes the note's original cited sources from the later decisions that accepted its substance.

**Ratifying-act instruction:** confirm that the note is a conformance note implementing D-185 and D-193 (including H-3, M-3 and M-8), and through them D-166, D-170 and D-175. Correct the attribution in the ratifying act; do not edit the historical note as though its original authorship or timing had changed. This is traceability confirmation, not a new business decision.

### Question 5 — S1 ratification readiness

**Verdict: READY FOR RATIFICATION.**

The baseline faithfully represents the accepted strategic model and its intended release boundaries. It does not claim that every later-release context is implemented or that every tactical contract is complete.

The ratifying act should:

1. identify the exact revision 4 fingerprint in §1.2;
2. make the E-1 attribution confirmation described above;
3. re-record the accepted-source fingerprints under D-173 §2;
4. apply the two supersession/status banners listed in S1 Appendix B only after ratification;
5. if T-1 and T-2 are accepted in the same act, record their acceptance and close U-5 / U-9 in the decision record; otherwise leave those items open.

No change to the strategic domain boundaries is required.

---

## 4. S2 — release-1 blueprint revision 2

### Question 6 — T-1 `OfficeCoverageAsAt`

**Business meaning: PASS. Contract completeness: NOT YET.**

The distinction is correct:

- `AuthorityCheck` answers whether a particular actor may perform an act.
- `OfficeCoverageAsAt` answers whether any valid moderator tenure covers a forum at a business date, as Governance knew it at a knowledge time.

The moderator-only release-1 scope is justified. Newsletter sending already checks the particular editor's authority through `AuthorityCheck(send-newsletter(series))` (S2 §4 T-1, lines 71–94; D-58 and D-60). T-1 does not need an editor-coverage query for release 1.

The following semantics are also correct:

- Governance alone evaluates tenure validity.
- The tenure must be in term on `d`, have a basis, and belong to an organ that has not ended.
- A containing-forum moderator counts only where the organisation's rules allow it.
- Overlapping tenures do not create exclusivity; one valid tenure is sufficient (D-171).
- Incomplete knowledge or unresolved scope produces `Refused`, not `NotCovered`.
- `NotCovered` makes the forum read-only; `Refused` denies posting but does not deny reading (D-150; D-210 §8).

**[A] A-01 — `decisionLogClosedThrough` is undefined by the accepted Governance design.**

- **Location:** S2 §4 T-1, lines 78–82 and 91.
- **Binding source:** Governance completion rev 5 §3.1–§3.2 (file 18, lines 143–161).
- **Defect:** the accepted DecisionLog defines contiguous positions (GD-11), a per-organisation `lastPosition` / `lastRecordTime`, and monotonic database-clock record time (GD-12). It does not define a `closedThrough` watermark or the guarantee represented by `decisionLogClosedThrough`.
- **Failure scenario:** if a caller cannot establish that the DecisionLog is complete through requested `τ`, it must refuse. Without a defined boundary and its atomicity/serialization rule, the producer has no specified basis for choosing `NotCovered` instead of `Refused`.
- **Minimum correction:** define the Governance-specific knowledge watermark and how it is read consistently with the log head and in-flight writes. Do not silently reuse Membership's `closedThrough` semantics; Governance has its own accepted log mechanism. Alternatively, remove the returned field and specify an equally precise completeness/refusal contract.

**[A] A-02 — `Covered.tenures` does not say whether it contains only valid tenures.**

- **Location:** S2 §4 T-1, lines 78–90.
- **Binding source:** D-150, D-171, Governance completion rev 5 §3.1–§3.3.
- **Defect:** the table says “all listed; covered if at least one is valid”, while the `Covered` payload has no per-tenure validity indicator. A consumer cannot tell whether an invalid, annulled or voided appointment is included in the returned list.
- **Failure scenario:** a caller receives `Covered` with a list containing a tenure whose basis is not valid as known at `τ`, and treats the whole list as the set of valid coverage evidence.
- **Minimum correction:** return only the tenures that Governance has evaluated as valid for `(forumId, d, τ)`—for example, `validTenures`—or include an explicit validity result for every returned tenure. The simplest contract is to return only valid tenures.

**[B] Proof obligations for T-1:**

- prove that `τ` beyond the defined Governance watermark returns `Refused`, never `NotCovered`;
- prove that overlapping tenures return `Covered` while at least one valid tenure remains;
- prove that an annulled/voided basis no longer covers at the knowledge position where the effect is known, but does not alter earlier `τ` answers;
- prove that a pending challenge does not change coverage (D-71);
- prove direct and containing-forum scope handling, including a rule that does not permit containing-forum coverage;
- prove holder identities are not returned;
- prove clock regression / concurrent DecisionLog writes cannot produce a false `NotCovered` answer.

None of these proofs was executed in this review.

### Question 7 — T-2 `CapacityBelonging…`

**Business meaning: PASS. One temporal clarification required.**

T-2 preserves the accepted ownership model:

- Organisation owns belonging and non-member capacities.
- Membership remains authoritative for membership truth.
- T-2 refuses a request for `Member` and never calls Membership.
- K-4 is unchanged.
- An empty T-2 answer means no qualifying non-member belonging was found; it does not mean “not a member”.
- A person qualifying through both the member and a named non-member capacity appears once in the newsletter audience, with the qualifying bases recorded.
- D-213 is read correctly: a suspended member who also has a valid staff belonging receives the issue as staff when the series decision names staff. The standing filter applies to the member route, not to the independent staff route.
- Reading does not imply posting. The default posting rule remains member in good standing; a non-member capacity can post only where the organisation's written rule expressly allows it (D-153, D-161, D-168).

**[A] A-03 — T-2's valid-time date is not bound to the unchanged K-4 query.**

- **Location:** S2 §4 T-2, lines 101–114; compare S2 §5.1, lines 138–142, and D-210 §4 / K-4 (file 17, lines 56–77).
- **Binding source:** D-210 K-4 and D-73's single-τ cross-context operation.
- **Defect:** T-2 accepts both `at d` and `asKnownAt τ`; unchanged K-4 is expressed with `τ` only. The send flow says to use one `τ` with K-4, but does not explicitly bind T-2's `d` to the same business-time date represented by that operation.
- **Failure scenario:** T-2 could establish a worker/guest belonging at one valid-time date while K-4 establishes member belonging at another, then Communication or Deliberation combines them as one audience/access decision.
- **Minimum correction:** state that in release-1 flows combined with K-4, T-2 uses the same business-time date as K-4 (for current operations, the organisation-local date represented by the shared `τ`) and the same knowledge boundary `τ`. Do not change K-4.

This is a contract clarification, not a new business rule.

**[B] Proof obligations for T-2:**

- `Member` in `capacities[]` is refused and no Membership read/import occurs;
- an empty answer is not converted into “not a member”;
- a person with both routes is returned once, with every **qualifying** basis;
- a suspended member who is also staff is included only through the named staff route; a good-standing member who is also staff can carry both qualifying bases;
- worker/guest reading remains possible through T-2 when the Membership-dependent K-4 answer is refused, without treating the K-4 refusal as a negative membership answer;
- posting remains denied by default for worker/guest and is allowed only when the written rule names the capacity;
- T-2 and K-4 use the same `τ` and compatible business-time date.

### Question 8 — T-4 `DeliveryContactsAsAt`

**Ownership: PASS. Current contract and flow: NOT READY.**

Organisation is the correct owner of organisation-local contact details. Identity's login address must not be substituted. This follows D-101 and INV-ID-3.

**[A] A-04 — T-4 resolves contact at the wrong point in the newsletter lifecycle.**

- **Location:** S2 §4 T-4, lines 120–128; S2 §5.1, lines 138–146.
- **Binding source:** D-210 §7, especially delivery protocol items 3 and 9 (file 17, lines 178–193).
- **Defect:** D-210 requires the audience snapshot to store a **contact reference**, and says the contact is **read at delivery from the person profile**. S2 instead calls T-4 before the snapshot transaction, with `asKnownAt τ` tied to the send's audience `τ`, and describes the returned value simply as `contact`.
- **Failure scenario:** a person's organisation-local contact changes after the audience snapshot but before the outbox delivery. The proposed flow can send to a stale endpoint, or persist the raw endpoint in the snapshot, contrary to the accepted delivery protocol.
- **Minimum correction:** separate the two moments:
  1. at the audience snapshot, store the organisation-owned **contact reference** required by D-210;
  2. at actual delivery, resolve that reference to the current organisation-local endpoint through Organisation, at delivery time; use it transiently for the mail attempt and never substitute Identity's login address.

  The flow must move endpoint resolution to delivery time. The audience's eligibility snapshot remains fixed at the original send `τ`; this does not require recalculating the audience.

**[A] A-05 — `NoContact` has no specified reason payload.**

- **Location:** S2 §4 T-4, lines 120–127; S2 §5.1, line 144.
- **Binding source:** D-210 §7, step 5 and delivery protocol item 9 (file 17, lines 165–172 and 184–190).
- **Defect:** the flow says the exclusion reason is recorded, but the contract only returns `NoContact` with no reason field or defined reason value.
- **Failure scenario:** Communication cannot record the required reason for exclusion or distinguish missing, unusable or unavailable contact information.
- **Minimum correction:** define `NoContact { reason: … }` (or an equivalent explicit reason code) and ensure the delivery record records that reason. Do not invent legal or policy reasons that are not present in Organisation's profile.

**[B] Proof obligations for T-4:**

- a contact changed after snapshot but before delivery is resolved according to D-210's delivery-time rule;
- no Identity login address is ever used;
- `NoContact` excludes the recipient and persists the required reason;
- Communication stores the contact reference in the snapshot, not a second contact register or an unintended raw-contact copy;
- provider retries do not resend accepted deliveries.

### Question 9 — Flows §5.1–§5.2

**Result: PASS on ownership direction; T-4 flow needs the correction above.**

There is **no direct Communication → Membership or Deliberation → Membership dependency** in the proposed flows:

- Newsletter member standing comes through Organisation's K-4.
- Named non-member capacities come through Organisation's T-2.
- Participation rules come from Governance's K-7.
- Forum openness comes from Governance's T-1.
- Individual moderation and newsletter sending remain governed by AuthorityCheck.
- Contact ownership remains Organisation through T-4.

The refusal semantics are generally correct: an incomplete source does not become a negative membership answer; a newsletter is not sent to a partial audience; a forum post is denied on a required-source refusal; and T-1 `Refused` does not itself deny reading.

The exception is the T-4 timing/order in §5.1 steps 9–11. Correct the flow so the snapshot stores a contact reference and the endpoint is resolved at delivery, as described in A-04/A-05.

### Question 10 — §6 code conformance and K-C1

#### G-1a — record time source

**Result: CORRECT. [E]**

The supplied `PostgresMemberRepository.php` shows the application clock and per-Member `last + 1` adjustment at lines 216–220. Replacing this with `clock_timestamp()` inside the transaction, without adjustment, is consistent with D-209.

#### G-1b — lock order

**Result: CORRECT. [E]**

The repository's current append sequence is documented as key check → valid time → version compare-and-set → insert (`PostgresMemberRepository.php`, lines 41–43). D-209 requires the organisation clock-row lock first, followed by the Membership version compare-and-set. The proposed correction is accurate.

#### G-1c — per-organisation clock state

**Result: DIRECTION CORRECT; REPOSITORY ABSENCE NOT FULLY VERIFIED. [B]**

D-209 requires the organisation-database clock row with `last_recorded_at` and `closed_through`. The supplied repository excerpt does not use such a row. However, the package does not include the complete migration/schema and all repository wiring needed to prove absence across the full repository. Verify this with a full repository search and schema/migration inspection before marking the code conformance item closed.

#### G-1d — refusal and retry

**Result: CHANGE CORRECT; RETRY PATH NEEDS PROOF. [B]**

The repository comment says it never retries (file 36, lines 41–43), and its current code uses the application clock / per-Member adjustment. D-209 requires rollback/refusal for the specified clock conditions and a retry. The change is correct. The new clock-refusal result must be shown to reach the intended retry/re-decision path without duplicate acceptance, unbounded looping or conversion to a false business refusal.

#### G-2 — `closedThrough`, C1/C2 and scheduler

**Result: CORE MECHANISM CORRECT; ONE ATTRIBUTION CORRECTION REQUIRED. [A]**

D-162 as amended by D-209 requires the ledger-clock / `closedThrough` mechanism; D-210 §5.2 requires due scheduled resignations to be executed in the same `closedThrough` step before the boundary passes their effective date. The code gap is correctly identified.

However, **“a scheduler calls the same `closedThrough` step every minute per organisation” is not prescribed by D-162, D-209 or D-210**. It is a proposed operational cadence, not an accepted architectural requirement.

- **Location:** S2 §6, G-2, line 176.
- **Minimum correction:** label the one-minute cadence as a proposed implementation choice (or remove the frequency pending an operational requirement). Keep the accepted requirement: the closure step must run independently of user reads often enough to execute due declarations, and must fail closed as D-209/D-210 specify.
- No Principal business decision is needed for this source-attribution correction. A concrete cadence can be selected as an implementation/operational parameter.

#### G-3 — InterimRoleAuthority binding

**Result: DESIGN DIRECTION CORRECT; BINDING CLAIM NEEDS VERIFICATION. [B]**

The `InterimRoleAuthority` source comment says it was not bound in the container in Slice 2a (file 37, lines 14–23). The included package does not provide the complete service-container binding evidence needed to prove the stronger statement “not bound for any release-1 organisation”. Verify actual bindings and organisation configuration before closing G-3. The intended direction—Governance AuthorityCheck behind the consumer-side port—is consistent with D-1a and D-167.

#### G-4 — Membership lifecycle and scheduled resignation

**Result: CORRECT. [E]**

The supplied `Member.php` still rejects an effective date after the decision date in `decideEnd` (file 35, lines 195–218, especially 214–215). D-205 permits only the narrow scheduled-resignation execution exception. The proposed addition of Renew / Suspend / Restore, scheduled declaration / withdrawal, `ownAct`, and the narrowly bounded `End(resignation)` exception is consistent with D-163 and D-203–D-205.

#### K-C1 — retain V-5 by refusal

**Result: COMPATIBLE WITH D-209. [E]**

COR-4 Q-2 was approved as part of the accepted COR-4 work and requires strictly increasing `recordedAt` per Membership stream (file 33, §3 Q-2). `Member.php` currently checks only that `recordedAt` does not decrease (file 35, lines 25–33), while the repository achieves strict increase by adding one microsecond (file 36, lines 216–220).

K-C1 preserves the stricter per-Membership requirement without violating D-209:

- no timestamp is adjusted;
- if the database clock equals that Membership's previous `recordedAt`, the append is refused and retried;
- D-209's allowance for equal timestamps across different Membership streams remains intact.

**[B] Add a complementary proof:** equal database-clock readings for different Membership streams must remain permitted where the organisation-wide D-209 guard permits them. The existing proposed KC1-1 tests only the same-Membership refusal case; it should also protect against accidentally imposing global strict increase.

### Question 11 — New high-severity contradictions

**Result: no new high-severity contradiction in S1. S2 contains the specific contract defects already recorded above.**

- S1's Geography section is consistent with the ratified D-93 design and Organisation §§49–51.
- S1's D-213 treatment is exact.
- S1 preserves the D-212 tribunal exclusion as unavailable pending the required Adjudication and Governance changes.
- S2 does not create a direct Communication / Deliberation → Membership dependency.
- T-1's undefined completeness boundary and ambiguous tenure result must be corrected before acceptance.
- T-4's send-time resolution conflicts with D-210's delivery-time contact rule and must be corrected before acceptance.
- The G-2 one-minute scheduler is a source-attribution issue, not a new business rule.

---

## 5. Findings register

| ID | Class | Subject | Finding | Required treatment |
|---|---|---|---|---|
| A-01 | [A] | T-1 | `decisionLogClosedThrough` is not defined by the accepted Governance DecisionLog contract. | Define Governance-specific completeness/watermark and atomicity semantics, or remove the field and define an equivalent refusal contract. |
| A-02 | [A] | T-1 | `Covered.tenures` may contain invalid tenures; payload has no per-item validity. | Return only valid tenures or mark validity explicitly. |
| A-03 | [A] | T-2 | `at d` is not explicitly bound to K-4's valid-time semantics when both answers are combined. | Specify the same business-time date and `τ` for release-1 combined operations; keep K-4 unchanged. |
| A-04 | [A] | T-4 / flow | Contact is resolved at send snapshot time, while D-210 requires it to be read at delivery. | Snapshot a contact reference; resolve the endpoint at delivery time. |
| A-05 | [A] | T-4 | `NoContact` has no reason field although D-210 requires the exclusion reason to be recorded. | Define a reason-bearing `NoContact` result and persist the reason. |
| A-06 | [A] | S2 §6 G-2 | One-minute scheduler cadence is attributed to D-162/D-209 although those decisions do not prescribe that frequency. | Label cadence as a proposed implementation choice; retain the accepted closure-step requirement. |
| B-01 | [B] | T-1 | Completeness, concurrency, corrections, remedies and refusal behavior need proof. | Add and execute the specified T-1 proof cases. |
| B-02 | [B] | T-2 | Independence, deduplication, suspended-member/staff, posting and shared-time behavior need proof. | Add and execute T-2 proof cases. |
| B-03 | [B] | T-4 | Delivery-time contact resolution, no login-address use, reason recording and retry behavior need proof. | Add and execute T-4 proof cases. |
| B-04 | [B] | K-C1 | Same-Membership equality refusal is not enough to prove D-209 equality across different Membership streams remains allowed. | Add complementary cross-Membership equality test. |
| B-05 | [B] | G-1c / G-1d / G-3 | Supplied excerpts do not prove full clock-state absence, retry wiring or all container bindings. | Inspect full repository/schema and execute integration/architecture tests. |
| C-01 | [C] | Release 1 | Mail-provider idempotency and accepted-delivery behavior remain external guarantees (D-210 §7). | Verify provider behavior before release; do not claim proof from the contract alone. |
| C-02 | [C] | Launch gates | Newsletter legal basis and retention, resignation effective-time questions and other listed legal matters remain external/legal gates (S1 §9 U-3; D-210 §15). | Obtain the required counsel confirmation before launch. |
| D-01 | [D] | S1 / S2 | Ratification of S1 and ARB acceptance of corrected contracts remain decisions reserved to the Principal/ARB. | The reviewer makes no acceptance or ratification decision. |
| E-01 | [E] | S1 | D-211 classification and D-212/D-213 strategic consequences are represented faithfully. | No correction. |
| E-02 | [E] | S1 §4a | Geography / unit / address / belonging relationships match D-93 and Organisation §§49–51. | No correction. |
| E-03 | [E] | T-2 | D-213's independent qualification of named non-member capacities is correctly interpreted. | No reopening of D-213. |
| E-04 | [E] | S2 flows | No direct Communication or Deliberation dependency on Membership is introduced. | Preserve Organisation K-4 / T-2 mediation. |
| E-05 | [E] | K-C1 | Refusal rather than timestamp adjustment can preserve V-5 without contradicting D-209. | Add the complementary proof; no business-rule change. |

---

## 6. Principal decision guidance — business language

No new business policy choice is required by this review.

The decisions reserved to you are:

1. **Ratify S1:** this accepts the consolidated picture of what PublicDigit's business areas own and how they relate. The review recommends ratification. The ratifying act should confirm E-1's corrected attribution.
2. **Accept T-1 after correction:** this gives the Forum a reliable answer to “is there a valid moderator covering this forum?” It must not mistake missing Governance knowledge for “no moderator”.
3. **Accept T-2 after the date clarification:** this lets the platform recognise workers and guests without asking Membership whether they are members. It preserves the rule that reading does not automatically grant posting.
4. **Accept T-4 after correction:** this lets Newsletter obtain contact information from the organisation that owns it, at the correct point in delivery, without using a login email.
5. **Accept K-C1:** this preserves the already approved rule that one Membership's record times strictly increase, while avoiding artificial timestamp changes.

The reviewer does **not** recommend reopening D-213, D-209, D-210, D-93 or any other accepted decision.

---

## 7. Proof and implementation status

- No proof suite was executed during this review.
- The blueprint itself marks its proposed proofs as not executed.
- The review inspected only the supplied source excerpts and documents; it did not run the application, inspect an unprovided repository checkout, execute migrations or verify production/provider behavior.
- No implementation is authorised by this report.
- The report does not authorise a database migration, a production deployment, or a release.

## 8. Final verdict and stop

- **S1:** READY FOR RATIFICATION.
- **S2:** READY AFTER SPECIFIC CORRECTIONS.
- **T-1:** AFTER SPECIFIC CORRECTIONS.
- **T-2:** READY AFTER THE SPECIFIC TEMPORAL CLARIFICATION.
- **T-4:** AFTER SPECIFIC CORRECTIONS.
- **K-C1:** READY FOR ARB ACCEPTANCE, subject to its proof gate.

**No further review work is commissioned here. No accepted decision is reopened. No implementation is authorised. Stop.**
