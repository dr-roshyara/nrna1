# IR-R1-02 — Independent Review: Strategic Baseline and Release-1 Tactical Designs

| | |
|---|---|
| **Reviewer** | `deepseek-chat` (DeepSeek model), running inside the **Claude Code** CLI |
| **Status of this review** | **NOT INDEPENDENT under the commission's rule.** An advance check, written at the Principal's instruction; it does not satisfy IR-R1-02 |
| **Commission** | `2026-11-11-ir-r1-02-independent-review-commission.md` (file 01), issued by the Principal (D-94) on 2026-11-11 |
| **Package** | 19 files; `SHA256SUMS` and `upload-files/MANIFEST.sha256` verified (see §0.3) |
| **Prior involvement** | Author of IR-R1-01 (file 19), the non-independent review whose findings S1 §17 and S2 §14 apply |
| **Date** | 2026-11-11 |
| **Decides / modifies / accepts** | **Nothing.** No decision is taken for the Principal, no file is changed, no acceptance is claimed or implied |

---

## 0. Reviewer identity, prior involvement, integrity

### 0.1 Identity

**`deepseek-chat`, a DeepSeek model, inside the Claude Code CLI.** The commission (file 01, line 7) requires a reviewer that is "not Claude, **not DeepSeek**, **not run inside Claude Code**". This reviewer is the excluded model family *and* the excluded interface. It is not a human architect and not another model family in its own interface.

### 0.2 Prior involvement and the effect on independence

- **I wrote IR-R1-01** — the report delivered as file 19 — in this same model and interface.
- **S1 §17 and S2 §14 are the corrections to that report's findings** (H-1, H-2, M-1 … M-3; M-4 … M-7). I have therefore reviewed **the response to my own review**, applied by another author. That is the precise conflict the commission's rule exists to prevent: I cannot be a disinterested judge of whether my earlier findings were correctly and completely applied.
- **Consequence.** This report is an **advance, adversarial check**, written because the Principal instructed that it must be written ("I dont have independent reviewer. So your review must work"). It is **not the independent review** the commission requires, and it must not be recorded as one. The planned review by a different model family in its own interface (ChatGPT) remains necessary. If that reviewer is given this report, it should be given only as a list of points already raised, **not** as a conclusion to endorse.

### 0.3 Integrity method — hashes computed, not quoted

I computed the hashes; I did not take them from the package's own text.

| Check | Result |
|---|---|
| `sha256sum -c SHA256SUMS` (package root) | **pass** |
| `sha256sum -c upload-files/MANIFEST.sha256` | **19 / 19 pass** |
| Decision record (02), direct | `ec836c6e1eeba073a219001b1f410ebedc9680a201b9ddabe6b3df1cf8981da7` — **matches** the reviewer prompt |
| S1 (07), direct | `4abf0a1fd0b261e99e801d47096e3f2a16f19fdb401a0043f379521f75cf0152` — **matches** |
| S2 (08), direct | `7921848b7d5b77fe5d2145ec011ffc85a06beabf81c2cd26cdc585f9c3a596dc` — **matches** |
| ZIP vs extracted folder | byte-identical (`diff -r`); ZIP SHA-256 `b096066bf327cf2bd8be95ac2263d9fcfda5790e682024addd3514f93c8181bf`, 26 entries |
| Package vs repository | all 19 files byte-identical to the working tree at review start |

**Two integrity observations about the record, not the package** (finding F-05):

- **D-156** cites the model document (§49–§51) at SHA-256 `2bc6b65c…`; the file in the package (18) hashes to `1e9eaf31…`.
- **D-157** cites the thin slice at `0f89507f…`; the file in the package (15) hashes to `d463a20d…`.
- **D-162** cites the C1/C2 specification at `b7c9bb43…`; the file in the package (13) hashes to `5ea99458…`.

All three files carry later correction notes (13 lines 189–193; 15 lines 200–229), which explains a difference but not the absence of a recorded amendment act. **F-05.**

**File 18 locator.** The commission's file table (01 line 32) gives `docs/publicdigit/architecture/reviews/2026-11-08-…`; the file's actual repository path is `docs/publicdigit/reviews/2026-11-08-…`. The content matches the manifest. **F-10**, no effect on the review.

**Freeze.** Files 02, 04, 07 and 08 were not modified. Their hashes were recomputed after the review and are unchanged.

### 0.4 Scope limits

- **UNAVAILABLE in the package** (marked wherever relevant): the accepted Newsletter/Forum design of D-158 (`2026-11-10-newsletter-and-forum-design-minimum-package.md`, hash `8b2be45d…`); the acceptance act behind D-156 … D-158; the Organisation strategic baseline v1.0 rev. 2c (E-11's record text); the cutover gates C-1 … C-10 of D-160; I-TDD-02 (not written); Addendum A-8 (quoted only, at 15 line 198); counsel's opinion on D-151.
- **Out of scope by commission:** implementation, code, schema, tests. A design review proves neither implementation correctness nor test execution.

---

## 1. Verdicts

| Part | Verdict |
|---|---|
| **A — strategic baseline through D-168** | **COHERENT AFTER CORRECTIONS** |
| **B — S1 Membership renew / suspend / restore, revision 4** | **READY AFTER CORRECTIONS** |
| **C — S2 multi-database boundary, revision 3** | **READY AFTER CORRECTIONS** |

**Part A.** No decision-level contradiction was found across D-1 … D-168; the three kinds of ownership, the D-167 settlement and the D-164 separation hold together. Six corrections are needed (F-01 … F-06), none of which reopens a decision: the D-168 override has no path into the release-1 access decision (F-02), D-167's labelling duty is carried by no design (F-03), the D-166 controls have one operational hole (F-06), and the acceptance hashes of three accepted artefacts no longer match their files (F-05).

**S1.** The IR-R1-01 high findings are genuinely fixed: the in-force guard is in the command rules (§4) and the lapse-during-suspension case exists (§17 row 15b); one lock order now governs single and grouped appends (§17 H-2), matching the correction note in the accepted specification (13 lines 189–193). What remains is a proof row that still encodes the superseded derivation (F-01), a glossary line that contradicts the ratified predicate (F-04), and the unbuilt D-168 override (F-02).

**S2.** `requestRef`, the minimal central provenance, the corrected recovery claims and ISO-9/ISO-10 are sound. The one substantive correction is the case-grant record (F-06), which is ISO-8's own mechanism; the rest are low (F-11, F-16).

---

## 2. Part A — the strategic baseline (questions 1–8)

### Q1. Ownership — exactly one owner, three kinds kept apart

**Verdict: CONFIRMED.**

- **Three kinds are separated and named:** domain owner (context) / information owner / storage — 08 §2, lines 33–54; reaffirmed as "co-location ≠ merger" (08 line 52) and by the architecture-test rule (05 line 216; 06 line 276).
- **Completeness is demonstrated, not asserted:** 08 line 54 and 06 §2F, lines 127–142 ("every fact and decision has exactly one owner"), with the database column filled for each.
- **Domain ownership:** 06 §3 lines 146–154; 05 §3 lines 52–65; 03 §3 lines 53–69.
- **The central items are exactly Identity's and the platform's:** 08 §2 lines 37–42; nothing organisational (lines 43–50).
- **No double owner of a business fact** was found. **One owner is a capability, not a context:** the organisation registry, held by "Platform / tenancy capability (E-11; context-owner naming HD-8 open, no business effect)" — 08 line 41; 03 §11.2 line 226. Disclosed, and claimed to have no business effect. **F-12 (low).**

### Q2. Authority after D-167 — no remaining default authority; anything H-1 missed

**Verdict: PARTIAL.**

- **D-167 is correctly applied to the four rulings:** D-145 (second sentence no longer applies), D-53 (tribunal default no longer applies), D-64 (messaging off until designated), D-115 (withdrawn as a default) — 02 line 1124; analysis 04 §B lines 41–61; the thin slice carries the correcting note (15 line 231).
- **The "nobody until designated" family is unaffected and correct:** D-58, D-149, D-150, D-154(1), D-163 — 04 lines 43–44; 02 lines 1010–1011, 1034, 1090.
- **D-155 is correctly classified as safety, not authority:** it requires the acts of bodies that already hold authority — 04 line 49.
- **No remaining place where PublicDigit assigns authority by default was found** across D-1 … D-168.
- **One silence rule outside the H-1 taxonomy was missed:** the Governance thin slice sets **"Defaults when silent: editor exclusive, moderator non-exclusive"** (15 line 198, correction note 1 citing Addendum A-8). That is a platform rule deciding a constitutional question — how many holders an office may have — while the organisation's rules are silent. It assigns no authority, so it is not kind A; but it is not in 04 §B's table and not in D-167's closed list of product defaults (02 line 1124). **F-07 (low).** This is why the answer is PARTIAL, not CONFIRMED.

### Q3. Product defaults — authority-free, overridable, labelled

**Verdict: PARTIAL.**

- **Authority-free and overridable in the decisions:** D-167 lists D-52, D-55, D-56, D-114, D-140, D-152, D-153, D-161 (02 line 1124); D-168 makes D-161 overridable (02 line 1125); 04 §B classifies each (lines 50–56).
- **Labelled — not carried into any design.** D-167 requires the label "platform default (D-nn)" "wherever they affect a record or decision". A search of files 05–08, 13, 15 and 16 finds **no** implementation point for that label. The access decision (05 flow D step 5, line 129; 06 §2B line 47), the newsletter audience (05 flow E line 139; 06 line 48) and the forum post rule (07 §10 lines 155–156) are all records or decisions the rule affects. **F-03 (medium).**
- **Overridable — no mechanism.** `InForceUnrestricted` is applied unconditionally by the consumers; nothing tells them whether the organisation's rules displace D-161. **F-02 (medium).** Both are why the answer is PARTIAL.

### Q4. Identity — D-113 applied consistently; I-TDD-01's stale wording

**Verdict: CONFIRMED.**

- **D-113's substance:** reference at import without a Person; Person at registration/valid claim; code tied to `PersonRef`; binding is not a merge; binding is routing only — 02 line 878.
- **Applied consistently:** S2 §4 lines 85–97 (steps 2–7); §13.0 lines 224–238 (P-I1/K4 decided by D-113); §13.1 lines 240–246 (`ClaimRedeemed` = routing only); 06 §2D lines 81–91 (association = routing); 05 flow A lines 88–98.
- **I-TDD-01's stale wording is correctly displaced:** file 16 line 93 still says "that person now has **access**", and lines 65/169 still present K4 as open; S2 §13.1 replaces the meaning and §13.0 closes K4, and the commission flags file 16 as "P; **predates D-113**" (01 line 30). The displacement is in the governing design, not in the file. **F-13 (low).**
- **One gap in the flow:** D-113 requires a second binding "refused **and reported to that organisation**". S2 §4 step 6 says "refused neutrally" and states the one-reference rule, but neither §4 nor §13.3 gives the report a path. **F-14 (low).** Neutral to the person and reported to the organisation are compatible; the report is simply absent.

### Q5. Multi-database — D-164 reflected; nothing organisational central; binding never authority

**Verdict: CONFIRMED.**

- **D-164's separation:** 02 line 1102; 08 §1 lines 28–31; §2 lines 33–54; 06 §3 lines 146–154.
- **Nothing organisational is stored centrally:** organisation records, units, membership, governance, forums, newsletters all at 08 §2 lines 43–50; the claim issuance record is on the organisation side (line 50).
- **The binding is never authority:** 08 §13.1 line 244; ISO-7 line 321; 06 §M-1 lines 234–241; 06 §2D table; architecture test at 06 line 260.
- **One textual doubt, not a breach:** D-164 says "Central holds **only**" and enumerates five items; the central Claim and issued-`PersonRef` state (08 §2 lines 38–40; §4 steps 2 and 4) are not named in that enumeration. They are Identity's authentication/association machinery and carry no personal data (08 line 38), so they fit the spirit; a note reading them into "authentication" and "the association" would remove the doubt. **F-11 (low).**

### Q6. INV-ACCESS-1 / D-166 — cross-cutting rule, §D controls, honest limit

**Verdict: PARTIAL.**

- **The invariant is stated coherently and binds every context and procedure:** 04 §H lines 154–165; its two permissions (joining grant, case grant) and its four non-substitutes (binding, admin role, support request, backup possession).
- **§D's controls cover every operational procedure:** roles (no standing human credentials), migrations (runner only), backups (encrypted, restore into that organisation's own database; opening one = content access), troubleshooting (content-free logs), temporary copies, privileged support — 04 §D lines 86–93.
- **The honest limit is stated correctly and unusually plainly:** whoever controls server and keys can technically read data; release 1's guarantee is **procedural and auditable, not cryptographic**; organisation-held keys are a later option; the service terms must state this truthfully — 04 lines 95–99.
- **One operational hole:** the case grant is "recorded in the organisation's own database" (08 ISO-8 line 322; §13.9 line 386). When that database is unavailable, corrupt, or the object of a failed migration — the canonical reasons support is needed — the grant cannot be recorded there, and 04 §D lines 90–91 makes opening a backup for inspection a content access requiring one. No fallback is stated. **F-06 (medium).** This is why the answer is PARTIAL.

### Q7. Release-1 scope — D-146, D-147, D-160, D-163 against the designs

**Verdict: CONFIRMED.**

- **D-146** (Membership, Newsletter, Membership Forum) — 02 line 988; the six flows of 05 §5 and the matrix of 06 §2F stay inside it.
- **D-147** (units and placement by act; no area rules or Geography) — 02 line 994; 05 §9 lines 196–205; flow F line 154; the map is dormant (05 line 78, 06 line 139).
- **D-160** (ledger organisations only) — 02 line 1068; 05 §9 line 205; cutover gates **UNAVAILABLE** (not in the package), so the gate's content was not assessed.
- **D-163** (Renew, Suspend/Restore in release 1; no platform default term; payment outside) — 02 line 1090; 07 §3 lines 54–62, §13 lines 186–193; 08 §11 lines 195–203.
- **No scope contradiction found.** The Newsletter/Forum baseline accepted by D-158 is **UNAVAILABLE**; its integration in 05 flows D–E was reviewed, not the accepted text.

### Q8. Contradictions, and claims stronger than evidence

**Verdict: PARTIAL.**

- **Between decisions: none found.** D-167 (02 line 1124) and D-168 (line 1125) sit consistently with D-1a/D-1b (02 lines 36–37); D-164 does not contradict D-31/D-93, D-104, D-107/D-110/D-111 or D-123/D-138 (06 §M-9 lines 321–326; 03 §11.5).
- **Claims stronger than evidence, or stale statements, were found in five places:**
  - **accepted-artefact hashes no longer match their files** — **F-05 (medium)**;
  - **04 §0.3 line 22 still says the package "is therefore stale and must be rebuilt"**, while the delivered package *is* the rebuilt one (S1 rev 4, S2 rev 3) — **F-09 (low)**;
  - **05 line 59 still says Organisation derives `InForceUnrestricted`**, superseded by 06 §2B lines 43–55 and 07 §2 line 49, and 05's header declares the revision — **F-17 (low)**;
  - **08 §7 line 189 still marks failed migrations `unavailable`**, a status withdrawn by §13.6 line 335 and L-7 line 400 — **F-16 (low)**;
  - **03 §11.1 line 206's record-integrity claim covers D-1 … D-164**, while the baseline under review runs to D-168 (the later entries are present exactly once at 02 §11.70–§11.72) — **F-18 (low)**.
- These are documentation residues, not decision conflicts; hence PARTIAL rather than CONTRADICTORY.

---

## 3. Part B — S1 Membership renew / suspend / restore, revision 4 (questions 9–11)

### Q9. Decision table against the ratified definitions and guards

**Verdict: CONFIRMED.**

- **Ratified predicates:** 09 §4 lines 94–111; **`Suspended` has no in-force condition** (10 line 92).
- **Ratified guard:** 10 §18 line 360 — Suspend / Restore require **in force**.
- **Suspend:** "period exists and **is in force at `effectiveAt`**", refusal `NotInForce` — 07 §4 line 69.
- **Restore:** `s` live, **period in force**, not already restored, inside the restriction — 07 §4 line 70.
- **Row 15** (suspension of a lapsed period) → refused `NotInForce` — 07 §5 line 98; rationale §16 C-2 lines 237–242.
- **Lapse during a suspension:** row **15b** in §17 line 275 — Restore refused `NotInForce`; renew (possibly back-dated), then restore; the restriction otherwise ends at its own `until`. The temporal worked case agrees (06 §2E case 5, line 108).
- **Renewal while suspended remains accepted and the term runs** — 07 §5 row 8 line 91; 06 §2E case 6 line 109.
- **`Restore` "ends a suspension only"** — 10 line 209; 07 §4 line 70 and §5 rows 11–14.
- **One placement defect only:** the new case lives in §17, not in §5's table (which still ends at row 23, 07 lines 82–106). Since §17 governs, this is a reading hazard, not a wrong rule — **F-15 (low).**

### Q10. Grouped renewals — one lock order; marker and event

**Verdict: CONFIRMED.**

- **One lock order for every append, single or grouped:** (1) AuthorityCheck answer, **no locks**; (2) organisation's ledger-clock lock; (3) per membership version compare-and-set, decide, append, outbox — 07 §17 H-2 line 276.
- **It matches the accepted specification's correction note:** "Every Membership append takes the organisation's ledger-clock lock first, then the membership's version compare-and-set (previously the reverse)"; "the AuthorityCheck answer is obtained before any lock" — 13 lines 189–193.
- **Why the deadlock is removed:** every append path takes the same first lock, so a group and a single append wait for each other but cannot form a cycle; a deadlock or serialisation failure is still rolled back and retried (07 line 276). The group's cost is disclosed: it holds the organisation's membership writes for its duration, with a fail-closed size bound (07 §16 C-3 lines 259–262).
- **Retries are safe:** group key = (resolution id, group id); marker (its own table) **and** outbox `RenewalGroupApplied(key, outcome)` are written **in the same Membership transaction**; a retry checks the marker and answers the recorded outcome without appending; Governance moves Completed/Inapplicable **from the event**, idempotently, with no cross-context table access — 07 §17 M-2 line 278.
- **The source for "indivisible group" is quoted accurately:** model document §20.1 at 18 lines 310–311, status [WD] D-10 — I verified the quotation against the file.
- **One leftover:** §7 line 127 still says unconditionally that one AuthorityCheck answer "serves all its parts", while L-4 (line 281) adds the scope condition. §17 governs — **F-08 (low).**

### Q11. Terminology — `IsMember`, lapsed, `InForceUnrestricted`, D-168

**Verdict: PARTIAL.**

- **`IsMember` is untouched:** true only while some period is in force; suspension does not change it — 07 §2 line 46; §16 C-1 lines 220–235.
- **A lapsed person holds a lapsed membership** (covered, not a member) — 07 §16 C-1 lines 226–235; 05 §2 line 41.
- **`InForceUnrestricted` is not a Membership fact and not a contract field:** `t ∈ inForce ∧ t ∉ suspended`, derived by the **consuming** context from intervals Organisation forwards unchanged — 07 §2 line 49, §10 line 154; 06 §2B lines 43–55; D-161 terminology note 2 (02 line 1094).
- **Two defects:**
  - **The proof row still says Organisation computes it** — 07 §12 line 184 ("Organisation computes `InForceUnrestricted` from Published Language only"). §17 M-1 corrected §2 and §10 but not §12. An implementer writing tests from §12 would reintroduce the exact ownership error IR-R1-01 M-1 identified. **F-01 (medium).**
  - **The glossary row for `Suspended` contradicts the ratified predicate** — 07 §16 C-1 line 230 says "rights restricted (only while in force, C-2)", while the formal predicate (07 line 45; 10 line 92) has **no** in-force condition, and 06 §2E case 5 (line 108) relies on a person being "lapsed **and** suspended from 1 Apr". If read as a state definition, the row would drop the restriction when the term lapses, and a member renewed during the suspension could post without a Restore — the opposite of 06 §2E case 6. The parenthetical is defensible only as "the **act** is possible only while in force". **F-04 (medium).**
- **D-168's overridability is stated but not designed:** the rule is applied unconditionally (07 §10 lines 155–156; 06 §2B line 47), with no channel by which an organisation's contrary rules reach the decision. **F-02 (medium).** Hence PARTIAL.

---

## 4. Part C — S2 multi-database boundary, revision 3 (questions 12–16)

### Q12. Claim authority — answer bound to its request

**Verdict: CONFIRMED.**

- **`requestRef` = (R, local entry E, issuance id)** is carried in the answer's basis; Identity accepts the answer **only if** it matches this command, R was issued for (organisation, E), and the answer is fresh; one answer serves one issuance — 08 §14 M-4 line 396.
- **The AuthorityCheck contract carries `requestRef` for subject-specific acts**, and a consumer accepts it only for that request — 15 line 229 (correction note 4).
- **The rest of the contract holds:** internal port only, inside an `OrganisationContext` with no public endpoint; Identity verifies R's provenance from its own records; missing, foreign, stale or non-Authorised answers are refused; authority is never inferred — 08 §13.2 lines 250–260; ISO-4 line 318.
- **Nothing forgeable was found.** A foreign R is refused (§13.2(2)); a replay against another issuance fails `requestRef`; a replay of the same issuance is idempotent. The freshness window across two databases is correctly marked **[TU]**.

### Q13. Minimal central provenance — full evidence organisation-side, digest centrally

**Verdict: CONFIRMED.**

- **No D-164 exception is claimed:** central provenance is minimal — organisation id, issuance id, actor `PersonRef`, outcome, `checkedAt`, and a **digest** of the full answer; the full six fields + basis stay in the organisation's `ClaimIssuance` record — 08 §14 M-5 line 397.
- **This satisfies both D-164 and D-111:** the organisation owns the evidence, PublicDigit holds only what routing and verification need (08 §2 lines 38–40, 50).
- **The digest's canonicalisation and algorithm are [TU]**, correctly flagged; an auditor can match the two records. No finding.

### Q14. Recovery — every intermediate state harmless and recoverable

**Verdict: CONFIRMED.**

- **Intent-first, atomic steps, idempotency keys, reconciliation:** 08 §13.3 lines 267–300 (eight steps, each with durable state, transaction boundary, key and recovery).
- **Lost reply at issuance:** the code's plain text is shown once; retry answers "already issued"; the organisation revokes and issues anew with a new issuance id — §13.3 lines 280–285.
- **Redemption while the organisation's database is down:** the binding, the consumed claim and `ClaimRedeemed` are already true; the organisation's "claimed" mark is delivered later at-least-once, deduplicated by event id and idempotent by claim id — §13.3 lines 287–291.
- **The earlier overstatements are corrected:** "the first code is never usable" → **an intercepted code is usable within its window; the controls are single use, a short window, throttling, and revoke-and-reissue**; "not yet marked claimed changes no right" → **true when the mark is administrative; if an organisation's rules use it as an access condition, access waits** — §14 line 405.
- **The model is forward recovery, not compensation:** no committed fact is undone — §13.3 line 285.

### Q15. Isolation — ISO-1 … ISO-10, connections, logs, privileged operations, locator

**Verdict: PARTIAL.**

- **The invariant set is complete and tested:** ISO-1 … ISO-7 and ISO-9 at 08 §13.5 lines 311–327; **ISO-10** (logs, errors, traces, metrics carry the operation's organisation context, never another organisation's data; central logs hold no business content; neutral errors; test) at §14 line 399.
- **Connection handling is defined:** a fresh connection per operation or per organisation; `search_path`, temp tables, settings and prepared statements never carried across organisations — §14 L-9 line 402; worker middleware and the alternating A/B test at ISO-6 line 320.
- **The organisation picker is the only cross-organisation question and lives inside Identity** — §14 L-10 line 403.
- **The registry locator is a trust anchor:** written only by provisioning, audited, never request-influenced — §14 L-12 line 404; ISO-2 line 316.
- **ISO-8 is the gap:** its content-access limb requires the case grant to be recorded in the organisation's own database (line 322), which fails when that database is the thing under repair — **F-06 (medium)**, carried from Q6. Hence PARTIAL.
- **A stale reference:** §7 line 189 still uses the withdrawn `unavailable` status — **F-16 (low)**.

### Q16. Availability — fail-closed stated precisely

**Verdict: CONFIRMED.**

- **One rule:** every interactive request and every job needs the centre for session validity, binding and routing; if the centre is down, everything waits — 08 §6 lines 126–145.
- **The boundary cases are stated:** a running request completes only if its context was resolved before the outage and makes no new central read; background jobs pause and retry, and none runs without a fresh registry lookup; recovery reconciles nothing because nothing proceeded on stale routing — §6 lines 135–137; §13.6 line 346.
- **Status versus health is correct:** durable statuses `provisioning · operational · suspended (later) · closed (later)` plus `schemaState`; reachability is a health observation, and the earlier `unavailable` status is withdrawn — §13.6 lines 331–335; §3 line 64; L-7 line 400.
- **The single point of failure is accepted and stated**, and the later option (signed routing snapshot) is marked later/[TU] — §6 lines 141–143; §13.6 line 348.
- **The superseded Addendum M statement is correctly withdrawn** — 08 §0.3 line 23; 06 line 363; §14 L-15 line 408.

---

## 5. Findings

Severity is about the design's fitness to be accepted and built, not about blame. "Principal" states whether a Principal decision is needed; where the answer is "no", the correction is the author's under existing decisions.

### Medium

**F-01 — S1's proof row still assigns `InForceUnrestricted` to Organisation.**
- **Evidence:** 07 §12 line 184 ("Organisation computes `InForceUnrestricted` from Published Language only") against 07 §2 line 49, §10 line 154 and §17 M-1 line 277 ("derived by the consuming context; Organisation forwards").
- **Fact or inference:** fact (textual conflict).
- **Impact:** the acceptance-test list would encode the ownership error that IR-R1-01 M-1 identified; a test suite written from §12 would enforce the wrong derivation.
- **Principal decision:** no. Correct §12 line 184.

**F-02 — D-168's override has no path into the release-1 access decision.**
- **Evidence:** D-168 (02 line 1125) makes D-161 an overridable default; 06 §2B lines 43–55, 05 flow D step 5 (line 129) and flow E (line 139), and 07 §10 lines 155–156 apply `InForceUnrestricted` unconditionally; no contract supplies an organisation's contrary rule.
- **Fact or inference:** fact (no mechanism exists in the designs); inference (an organisation whose rules allow lapsed members to post cannot be honoured in release 1).
- **Impact:** D-168 is unimplementable as designed; the platform would silently impose D-161, the outcome D-167/D-168 were decided to prevent.
- **Principal decision:** no. The mechanism (a Governance-supplied per-organisation policy read by the consumers, or a labelled default used when none is recorded) is a tactical correction; the Principal decides nothing new.

**F-03 — D-167's labelling duty is carried by no design.**
- **Evidence:** D-167 (02 line 1124) requires "platform default (D-nn)" "wherever they affect a record or decision"; no occurrence in files 05–08, 13, 15 or 16; the affected records/decisions are 05 flow D step 5 (line 129), flow E (line 139), 06 §2B lines 47–48, 07 §10 lines 155–156.
- **Fact or inference:** fact (absence).
- **Impact:** an auditor cannot tell whether an access or audience decision rested on the organisation's rule or the platform's; D-1a's central promise becomes unverifiable at the record level.
- **Principal decision:** no. Add the label at the specified points; if the Principal intends it only on some records, that is a one-line clarification.

**F-04 — S1's `Suspended` glossary row contradicts the ratified predicate.**
- **Evidence:** 07 §16 C-1 line 230 ("only while in force") against 07 §2 line 45 and 10 line 92 (no in-force condition), and 06 §2E case 5 line 108 ("lapsed **and** suspended from 1 Apr").
- **Fact or inference:** fact (textual conflict); inference (an implementer could end `suspended` when the term lapses, so a member renewed during an open suspension could post without a Restore).
- **Impact:** a wrong access decision in exactly the case 06 §2E case 6 was written to protect.
- **Principal decision:** no. Reword as "the act is possible only while in force; the restriction continues after a lapse".

**F-05 — Accepted-artefact hashes no longer match the files in the package.**
- **Evidence:** D-156 cites `2bc6b65c…` (02 line 1050), file 18 hashes to `1e9eaf31…`; D-157 cites `0f89507f…` (line 1051), file 15 to `d463a20d…`; D-162 cites `b7c9bb43…` (line 1082), file 13 to `5ea99458…` (computed). The changes are correction notes (13 lines 189–193; 15 lines 200–229); file 18 carries no corresponding note.
- **Fact or inference:** fact (hash mismatch); inference (content changed after acceptance; for 18 the cause is not stated anywhere in the package).
- **Impact:** the accepted tactical baselines cannot be verified against the record; the release-1 designs (05, 06, 15) rest on §49–§51 in file 18, whose accepted content is therefore unidentified. This is a traceability defect, not a business contradiction.
- **Principal decision:** **recommended** — a short act or record note re-accepting the current hashes of D-156/D-157/D-162 (or stating which amendment act covers each change). Without it, "accepted" and "in the package" refer to different texts.

**F-06 — The case-grant record cannot exist when the organisation's database cannot record it.**
- **Evidence:** ISO-8 requires content access to need "a case grant recorded in the organisation's own database (owned by Organisation)" (08 line 322; §13.9 line 386); 04 §D lines 90–91 makes opening a backup for inspection a content access requiring a case grant. No fallback is stated for an unavailable or corrupt organisation database.
- **Fact or inference:** fact (the requirement's location); inference (the scenario — a failed migration or a restore inspection is exactly when content access may be wanted).
- **Impact:** either access is refused in that state (safe, but support may be unable to diagnose), or someone improvises a route outside D-166. Both outcomes should be chosen, not discovered.
- **Principal decision:** **possible, narrow** — whether, when an organisation's database cannot record a grant, content access is refused (fail closed) or granted by another recorded route. The second option changes D-166's mechanism and is the Principal's to allow.

### Low

**F-07 — An office-exclusivity silence default lies outside the H-1 taxonomy.** 15 line 198: "Defaults when silent: editor exclusive, moderator non-exclusive"; not in 04 §B's table (lines 41–61) nor in D-167's list (02 line 1124). It assigns no authority but decides a constitutional question (how many holders an office may have) while the rules are silent. **Principal:** a narrow confirmation whether it is a kind-D default under D-167 (label it) or must be withdrawn.

**F-08 — S1 §7 line 127 states the one-answer rule unconditionally**, while L-4 (line 281) limits it to an answer whose scope covers every part's member. §17 governs; the earlier sentence should carry the condition.

**F-09 — File 04 §0.3 line 22 still says the package is stale and must be rebuilt.** The delivered package is the rebuilt one (S1 rev 4, S2 rev 3). Documentation only; it could mislead a later reader into thinking the reviewed package is the old one.

**F-10 — The commission's locator for file 18 is wrong.** 01 line 32 says `docs/publicdigit/architecture/reviews/…`; the actual path is `docs/publicdigit/reviews/…`. Content is correct and manifest-verified. Correct the table in any reissue.

**F-11 — D-164's "Central holds only" enumeration does not name Identity's Claim and issued-`PersonRef` state.** 02 line 1102 against 08 §2 lines 38–40 and §4 steps 2/4. They are authentication/association machinery with no personal data (08 line 38); a note reading them into those categories removes the doubt. No Principal decision.

**F-12 — The organisation registry's owner is a capability, not a context.** 08 line 41: "Platform / tenancy capability (E-11; context-owner naming HD-8 open, no business effect)"; 03 §11.2 line 226. This is the one owner Q1 asks for that is not a named context. Disclosed; no business effect claimed.

**F-13 — File 16 still presents K4/P-I1 as open and `ClaimRedeemed` as "access".** 16 lines 65, 93, 169; displaced by 08 §13.0–§13.1 and flagged as predating D-113 (01 line 30). The file itself should carry a superseded note, as 07 §15 and 08 §12 do.

**F-14 — D-113's duplicate-binding report has no path.** D-113 (02 line 878) requires a second binding be "refused and reported to that organisation"; 08 §4 step 6 says "refused neutrally" and neither §4 nor §13.3 gives the report a route. Add the report (to the organisation, not to the person).

**F-15 — Row 15b exists only in S1 §17, not in §5's table** (07 lines 82–106; row added at line 275). §17 governs, so the rule is right; the table a reader will consult first is incomplete.

**F-16 — S2 §7 line 189 still uses the withdrawn `unavailable` status** for a failed migration, against §13.6 line 335 and L-7 line 400 (`schemaState = migration-failed`).

**F-17 — File 05 line 59 still says Organisation derives `InForceUnrestricted`.** Superseded by 06 §2B and 07 §2 line 49; 05's own header declares the revision, but the row reads as current.

**F-18 — File 03 §11.1 line 206's record-integrity claim stops at D-164**, while the baseline under review runs to D-168. The later entries are present exactly once (02 §11.70–§11.72); the claim should be restated for the new range.

---

## 6. What this review did not assess

- **Implementation, schema, code and tests** — outside the commission.
- **D-158's accepted Newsletter/Forum design** and the **D-156 … D-158 acceptance act** — **UNAVAILABLE** (not in the package).
- **The cutover gates C-1 … C-10** of D-160 — **UNAVAILABLE**.
- **E-11's record text** in the Organisation strategic baseline — **UNAVAILABLE** (so the tenancy capability's accepted definition was taken from 08 line 41 and 03 §3, cited there).
- **I-TDD-02** (Identity claim and binding) — not written; the claim's tactical design is P-I2/C-5, still open in the Identity session.
- **Addendum A-8** — **UNAVAILABLE**; quoted at 15 line 198, and F-07 is limited to that quotation.
- **Counsel's opinion on the newsletter legal basis (D-151)** — external and outstanding; it gates launch, not this review.

---

## 7. Compliance with the commission's rules

- **Decide nothing for the Principal:** no question is answered on the Principal's behalf. Where a Principal act is suggested (F-05; F-06; F-07), it is named as a question for the Principal, not resolved.
- **Modify nothing:** no package file, repository file, decision record or design was changed. The freeze on 02, 04, 07 and 08 is intact.
- **Claim no acceptance:** nothing here accepts, ratifies, closes or freezes any decision or design.
- **Mark missing sources UNAVAILABLE:** §0.4 and §6.
- **Stop after the report:** this report ends here.

**Final statement on independence.** Because the reviewer is `deepseek-chat` inside Claude Code — the excluded model *and* interface — and because it wrote the prior review whose findings these revisions apply, **this report is not the independent review IR-R1-02 requires**. It is an advance check for the Principal and for the reviewer who follows. The ChatGPT review the Principal plans is the one that can meet the commission; it should reach its own verdicts.

---

**Recorder's note (2026-11-11, appended after the report; not part of the review):**
- **H-1 decided by the Principal as D-172** (D-167 governs D-154(2)).
- **M-1 … M-11 applied:** S1 §19, S2 §16, H-1 analysis §I, I-TDD-01 supersession note.
- **M-3:** a Platform (tenancy) context is recommended, for confirmation in the baseline acceptance.
