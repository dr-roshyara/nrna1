# IR-P-01 — Independent Review: Platform (Tenancy) Tactical Design

| | |
|---|---|
| **Reviewer** | `deepseek-chat` (DeepSeek), run through Claude Code. **This is not the independent review the commission requires.** The commission excludes Claude, DeepSeek and Claude Code (`01:7`); I am the excluded model and the excluded interface. The Principal instructed this review because no independent reviewer is available. It is a senior-architect review, offered as evidence — not the IR-P-01 the commission defines. |
| **Date** | 2026-11-11 |
| **Commission** | `01-2026-11-11-ir-p-01-platform-independent-review-commission.md`; the Principal (D-94); acceptance deferred until review (D-178, `03:1173`). |
| **Subject** | Two texts of the same file: the package text (revision 2, 342 lines, SHA-256 `2dd9cec38dd104894dcc12792540fed67212df8a2526737d4c16ec35651ef6b1`) and the live repository text (revision 3, 406 lines, SHA-256 `ab7e53bcae99a28a264fd66d875b25cbb83bf89c4dfe004cdd63fed24c6a6049`). **Revision 3 governs where it differs** (`02[L]:348`). |
| **Status** | **Review.** Records no ruling; modifies nothing; claims no acceptance; stops after this report. |

---

## 0. Integrity, identity and prior involvement

### 0.1 Hashes and checks I computed

I computed these myself; none is copied from the package.

| Check | Result |
|---|---|
| Outer `SHA256SUMS` (handover root) | **14/14 OK** |
| Inner `upload-files/MANIFEST.sha256` | **10/10 OK** |
| Package subject `02` | `2dd9cec3…` — **matches** the commission (`01:8`) |
| Decision record `03` | `15b8d477…` — **matches** the commission (`01:8`) and the repository copy at `docs/publicdigit/reviews/…` |
| Fingerprint claim (package `02`, first 340 lines) | `6908c15d…` — **verified exactly**. Lines 341 (blank) and 342 (status note) are the only addition (`02[P]:341–342`). |
| Live repository subject | `ab7e53bc…`, 406 lines — **does not match the package**; it is the package text plus an appended revision 3 (`02[L]:346–406`) |
| GPT-6 IR-P-01 report | **UNAVAILABLE** — not in the package and not at the commissioned report path |

**Integrity fact.** The package is internally sound. The repository text is not the text the package froze. Revision 3 was appended after a review it describes as “IR-P-01 (GPT-6, independent; verdict NOT READY)” (`02[L]:346`). The commission says revision 2 governs (`01:8`); the repository now governs by revision 3 (`02[L]:348`). The start sheet's freeze (`0-START-HERE.md:8`) was not observed in the repository after the package was built.

**Which text I review.** I review **revision 3, the live text**, because it is the text a Principal acceptance would attach to. Where revision 3 does not change a revision-2 passage, I cite the package line. I also state the verdict revision 2 alone would receive (see §4.2).

### 0.2 Prior involvement (disclosure)

- I wrote the **IR-R1-01** report (`docs/publicdigit/architecture/reviews/2026-11-11-ir-r1-01-independent-review.md`, committed as `f79189616`, branch `election-flow-updated`).
- I wrote an **earlier IR-R1-02 draft/check**. The decision record attributes the independent IR-R1-02 to **GPT-6** (`04:10`; `03:1149`), and the multi-database design records revisions 3 and 4 as corrections “after IR-R1-01 / IR-R1-02; deepseek-chat, not independent” (`05:392`, `05:416`). My earlier text was therefore a check, not the independent review the record cites.
- I have reviewed the strategic baseline and the two tactical designs at earlier revisions. **I did not author files 02, 03, 05 or 10.** I am not independent of the package's correction history, but I am not its author.

### 0.3 UNAVAILABLE material

- **The GPT-6 IR-P-01 report itself.** Revision 3 says it exists and returned NOT READY; its text is not in the package or the repository. I therefore **cannot verify that F-01 … F-04 are the complete set of GPT-6's findings**, nor whether revision 3 implements each faithfully.
- **E-11's record text** — `05:407` says it is not in the package: **UNAVAILABLE there**.
- **The brainstorming folder** cited as read-only, non-authoritative input (`02[P]:8`): not in the package.
- **Any implementation, test, proof or operational evidence.** A design review proves neither implementation correctness nor test execution (`01:9`). All proof obligations remain untested.

**Method.** I read files 01–10 in full or in the sections relevant to the questions; searched the decision record for the cited decisions (D-1a `03:36`, D-1b `03:37`, D-73 `03:658`, D-104 `03:844`, D-110 `03:860`, D-113 `03:878`, D-148 `03:1002`, D-149 `03:1010`, D-150 `03:1011`, D-154 `03:1034`, D-159 `03:1060`, D-160 `03:1068`, D-164 `03:1102`, D-166 `03:1116`, D-167 `03:1124`, D-170 `03:1134`, D-173 `03:1149`, D-174 `03:1157`, D-175 `03:1158`, D-176 `03:1166`, D-177 `03:1172`, D-178 `03:1173`); compared the package subject byte-for-byte with the live repository text; computed every hash above. I did not execute code, run tests, or modify files 02 or 03.

---

## 1. Verdict summary

| # | Question | Verdict | Anchor |
|---|---|---|---|
| 1 | Boundaries — only what D-173 … D-176 give it; no takeover of Identity, Organisation or Governance; no authority | **PARTIAL** | `02[L]:350–365`; `02[P]:51–56`; D-164 `03:1102`; D-170 `03:1134` |
| 2 | Lifecycle — status / `schemaState` / health apart; `closed` never delete; joining ordered and gated | **PARTIAL** | `02[L]:367–384`, `402–404`; `02[P]:79–104`; `05:56–80`, `329–348` |
| 3 | Aggregates — one `ServedOrganisation`; `PendingCaseGrant` sufficient | **PARTIAL** | `02[P]:145–151`, `289–296`; `02[L]:392–400` |
| 4 | D-174 / D-176 emergency route — stale contact, ordered updates, direct registration, reporting, withdrawal, forgery | **PARTIAL** | `02[L]:350–365`, `392–400`; `02[P]:236–252`, `333–338`; D-176 `03:1166` |
| 5 | Initial contacts by Identity Account before the organisation database exists | **PARTIAL** | `02[P]:254–266`; `02[L]:390`; D-113 `03:878`; D-110 `03:860`; `08:64` |
| 6 | Counterparty — identity, link, commercial acceptance, constitutional authority apart; no organisational power | **PARTIAL** | `02[P]:268–281`; `02[L]:352`; D-175 `03:1158`; D-176 `03:1166` |
| 7 | INV-ACCESS-1 — no content path without a case grant (emergency, backups, restores) | **PARTIAL** | `02[P]:106–130`; `06:80–101`, `154–165`; `05:317`, `322`, `442` |
| 8 | Routing and failure — fail closed; 12 scenarios; no cross-database transaction | **PARTIAL** | `02[P]:151`, `298–313`; `02[L]:392–400` |
| 9 | Contradictions with accepted decisions or designs; invented rules; claims stronger than evidence | **CONTRADICTORY** | see §2.9 |

**Overall verdict: NOT READY** (see §4). Revision 3 closes four real findings; it introduces a central-content boundary breach in the very mechanism meant to protect the boundary, and it leaves the accepted ISO-3 conflict, the reporting-channel gap and the contact-list capture path unresolved.

---

## 2. Answers to the commission's questions

### Q1. Boundaries — **PARTIAL**

**What is confirmed.** Platform owns the registry, locator, platform status and `schemaState`, the service-operation grant record and the D-170 pending grants (`02[L]:51–53`; `02[P]:53`; D-173 `03:1149`); D-174 adds platform contacts (`03:1157`); D-175 adds the counterparty verification (`03:1158`). It does not own Person, Account, login, sessions, claims or bindings — Identity does (`09:18–25`; D-110 `03:860`; `02[P]:54`). It does not own Organisation business facts; the pending grant is metadata, and the grant remains the organisation's decision (`02[P]:55`; `04:26`; D-170 `03:1134`). Governance keeps the authority question (`02[P]:56`; `10:121`). Nothing in the design grants authority: the recurring statements are correct (`02[L]:352`, `359`; D-1a `03:36`; D-167 `03:1124`).

**Where it is partial.**

1. **Revision 3 pulls organisation Governance content into the platform record.** F-01 requires, with every direct contact registration, “the decision (text or extract)”, “the provision of the organisation's rules on file”, and “the sitting minutes or signed extract (fingerprinted)” (`02[L]:353–357`), and says the registration and its evidence are “copied into the organisation's database” on recovery (`02[L]:362`). The design does not say where that evidence is held while the database is down. If it is held centrally, it is an organisation's committee decision, rules provision and minutes — Governance content — in the central database. D-164 permits central only person registration and Account, authentication and sessions, the registry, the Account–organisation association, and shared reference data (`03:1102`); D-170 permits a pending grant record “(metadata only, no business content)” (`03:1134`). **Finding H-2.**
2. **`PlatformContacts` stores more than D-174 allows.** D-174 says Platform keeps “only their account / `PersonRef` references centrally (no other data)” (`03:1157`). Revision 2 R-E stores “Account references + designating decision reference, date, **position**” (`02[P]:293`). “Position” may be an organisational office — a Governance fact that D-164 keeps in the organisation database — and the decision reference and date are data beyond the literal “no other data”. D-176's direct registration needs the decision reference, so the design should state the reconciliation rather than leave the conflict. **Finding M-6.**
3. **The counterparty record's content is not bounded.** D-175 requires verifying “the identity of the person accepting the service terms and their stated link” (`03:1158`). The design's record holds “the method, evidence fingerprints, date and verifier” (`02[P]:281`), but does not say where the signer's identity, the signed service agreement or the link evidence live. D-164's central list does not include commercial contract records. **Finding M-7.**

**Does it take over anything?** Not Identity, not Organisation's business facts, not Governance's authority — with the two caveats above: F-01's central evidence is a *de facto* central copy of Governance content, and the direct-registration mechanism can change who may authorise content access (H-5).

### Q2. Lifecycle — **PARTIAL**

**Confirmed.** Platform status, `schemaState` and health are kept apart: `provisioning · operational · suspended (later) · closed (later)`, plus `schemaState` (`current · migration-failed`) and health as an observation (`02[P]:88–90`; `05:329–348`). Health never changes status (`02[P]:103`). `closed` never means “delete the database” (`02[P]:104`; `05:66`). The fail-closed resolution covers unknown, not operational, wrong schema and not bound (`02[P]:151`).

**The joining order: fixed in revision 3, with new seams.**

- Revision 2 was internally contradictory: the sequence created the database at step 4 and recorded the service-operation grant at step 5 (`02[P]:96–97`), while §3.3 said “Without the grant: no technical operation (provisioning cannot start)” (`02[P]:112`) and the aggregate said “a service-operation grant must exist before provisioning” (`02[P]:147`). Revision 3 corrects the order: `pending` → counterparty verified → service terms accepted and grant recorded → `provisioning` (database created and migrated) → founding records and claim → `operational` (`02[L]:369–384`). **This closes the contradiction.**
- **`pending` is a new durable status** (`02[L]:371`, `404`). It is not in the accepted multi-database design's durable status list (`05:332`) or its creation flow (`05:74–80`). Adding it is an operational design choice (`05:200` says lifecycle statuses are operational), but the accepted design is not noted as refined, and D-175's literal wording says registration “stays `provisioning` until verified” (`03:1158`), whereas revision 3 verifies at `pending`. The intent is preserved; the wording is not reconciled. **Finding M-2.**
- **The bootstrap claim has no officer and no AuthorityCheck.** Revision 3 step 6 has provisioning write the registrar's and initial contacts' local person entries, Identity issue their `PersonRef`s, and “the registrar's claim is issued”, with the founding decision as the asserted basis (`02[L]:375–378`). The accepted multi-database design's §4 step 4 says an **officer** issues a claim, with **AuthorityCheck in the organisation** (`05:89`). At bootstrap there is no officer and no Governance record. The design's asserted-evidence basis is defensible under D-148/D-159, but it is an **exception to an accepted design** and is not stated as one. **Finding M-3.**
- **No migration-in-progress state.** `schemaState` is only `current` or `migration-failed` (`02[P]:89`; `05:333`). A migration that is running but not yet failed has no state, and the design does not say whether routing continues during it. **Finding M-8.**
- **The contact precondition can fail.** The invariant is `provisioning → operational` only with schema current and **at least one contact** (`02[L]:384`), but a contact named in the founding decision may have no Account, and the design provides no invitation path; a contact whose Account is already bound to a different Person is refused by INV-ID-6 (`08:64`). **Finding M-4.**

### Q3. Aggregates — **PARTIAL**

**One `ServedOrganisation` is defensible.** The routability invariant genuinely spans status, `schemaState`, the service-operation grant, the counterparty verification and at least one contact (`02[P]:147`; `02[L]:293`, `383–384`). Contact and status changes are rare, so contention is not a reason to split (`02[P]:293`). I would not require a second aggregate.

**But the stated justification is partly wrong.** “Hostnames unique across the registry” (`02[P]:147`) is a **cross-aggregate** uniqueness constraint; it needs a unique index or a domain service, not an aggregate invariant. “Locator written only by provisioning, audited, never from a request” is a command-authorisation rule, not an aggregate invariant. The aggregate can still be right; the justification should be restated. **Finding L-4.**

**`PendingCaseGrant` is not sufficient as written.**

- **Two state machines remain in the live text.** §3.4 says `pending → used → reported → copied`, or `expired` / `withdrawn` (`02[P]:116`); the revised model R-E says `given → active → closed`, with an orthogonal `copiedToOrganisation` flag (`02[P]:294`). Part 4 line 149 still says “states per §3.4” (`02[P]:149`). The commission says revision 2 governs where it differs (`01:8`), so R-E should win — but §3.4 and Part 4 are not marked superseded, and revision 3 does not reconcile them. **Finding M-1.**
- **The revision-2 state model lost two distinctions.** `expired` and `withdrawn` collapse into `closed` (`02[P]:294`), although they have different audit meanings and different reporting duties. The durable `reported` state disappeared; “given (reported immediately)” is asserted, but no flag or state records whether the report to contacts or to the organisation actually succeeded. The `CaseGrantReported` event (`02[P]:167`) has no state to report against. **Finding M-1.**
- **Grantor evidence uses two different versions.** R-E says “the designation record version used” (`02[P]:294`); F-04 uses “the current contact list at that version” and the `ServedOrganisation` row version (`02[L]:396`). These are not obviously the same thing. **Finding L-5.**
- **F-04 linearises removal against grant, but not withdrawal against use.** A `WithdrawCaseGrant` can race a `UseCaseGrant`; the design does not state the linearisation point for that pair. **Finding M-11.**
- **F-04 spans two aggregates in one central transaction** (`ServedOrganisation` and `PendingCaseGrant`, `02[L]:394–396`). That is acceptable in one database, but it means the consistency boundary is the transaction, not one aggregate; the design should say so. **Finding L-4.**

### Q4. D-174 / D-176 emergency route — **PARTIAL**

**What revision 3 improves.** Revision 2 identified the stale-contact gap (`02[P]:242–247`) and D-176 decided that the platform's registered list governs, with direct registration, immediate reporting and withdrawal (`03:1166`). Revision 3 adds four-element evidence, marks every registration “asserted, not machine-checked”, reports and makes it challengeable, and linearises contact removal against grant (`02[L]:350–365`, `392–400`). That is a real improvement on revision 2's bare “citing the organisation's decision” (`02[P]:335`).

**What remains.**

1. **The evidence is asserted, not authenticated.** Platform checks only “that all four elements are present and well-formed” (`02[L]:358`). A current contact, or the verified counterparty, can supply a fabricated decision text, a fabricated rule provision and fabricated minutes; “well-formed” is not “genuine”. The recovery comparison (`02[L]:362–364`) happens after the inspection and depends on the organisation noticing. **Finding H-5.**
2. **A sender can capture the list.** Every registration is reported “at once to all current contacts and to the counterparty” (`02[L]:361`), and “additions are additionally reported to *all* contacts (including the one removed or added)” (`02[L]:365`). **Removals are reported only to current contacts.** A single current contact can therefore register the removal of every other contact (the report goes only to the sender), then add an accomplice; the removed contacts are not told of their removal. The counterparty is a recognised channel even when it is not a contact (`02[L]:352`, `357`), so it can do the same. This is not “beyond D-176” — D-176 authorises the route — but it is a residual risk the design does not state, and it contradicts R-C's “commercial matters only” (`02[P]:278`). **Finding H-5.**
3. **There is no reporting channel.** Platform holds only Account references for contacts (`02[P]:285`; D-174 `03:1157`). The organisation's contact details are in its database — the database that is down. Identity holds the login address but its boundary is authentication and routing (`09:18–25`; `08:64`; `05:91`). “Reported at once” (`02[L]:361`) is a claim stronger than the model supports unless a notification contract and a lawful basis for using the login address are defined. **Finding H-4.**
4. **The ordering key is ambiguous.** “Ordered by the designating decision's date and log position” (`02[P]:250`) and “newest decision wins” (`02[L]:335`) have no tiebreak rule and no rule for a backdated or future-dated decision. A fabricated future-dated decision always wins. **Finding M-12.**
5. **Withdrawal is available to “any current contact”** (`02[P]:295`). That is safe-side (it refuses access) but allows one contact to withdraw another's legitimate grant; the design should say whether that is intended. **Finding L-3.**

**Forgery answer, directly.** Yes, one thing is still forgeable: the **organisation decision reference and its supporting evidence** on a direct registration. The sender cannot forge the sender identity (it is an authenticated Account) and cannot forge a grant by a contact removed in an earlier-committed version (F-04), but it can forge the *decision* the registration cites. D-176 accepts “citing the organisation's decision” as the basis; it does not say the citation is verified, and it cannot be verified while the database is down. The design should state this as the residual risk and name the controls (reporting, challenge, recovery comparison) — or propose dual control — rather than treating the four-element check as authentication.

### Q5. Initial contacts (R-B) — **PARTIAL**

**Confirmed in principle.** Referencing initial contacts by **Identity Account** before the organisation database exists is consistent with D-113, D-110 and XD-3: a `PersonRef` requires the organisation's own record (`02[P]:260`; D-113 `03:878`), while the Account is central (`03:1102`) and one Account can serve several organisations (`03:860`). The transition — registrar records the founding decision, local entries are created, claims bind the `PersonRef`s to the same Persons, and the organisation's own decisions maintain the list (`02[P]:262–266`; `02[L]:390`) — is sound, and INV-ID-6 prevents a claim from re-binding a Person who already has a different Account (`08:64`).

**Where it is partial.**

1. **A named contact may have no Account.** The design says contacts are referenced by their Account because “they need one anyway to log in and give a grant” (`02[P]:259`), but it does not say what happens when a founding decision names someone who has not registered. There is no invitation path, and the `provisioning → operational` invariant requires at least one contact (`02[L]:384`). **Finding M-4.**
2. **A contact whose Account is already bound to a different Person cannot be activated.** INV-ID-6 refuses the claim (`08:64`). If that person is the only named contact, the invariant fails; the design does not say how the organisation replaces them before `operational`. **Finding M-4.**
3. **The two lists use different keys.** The platform list is Account-keyed (`02[P]:293`; `02[L]:390`); the organisation's designation is PersonRef-keyed (its own Governance records). A contact update arriving from the organisation database identifies a PersonRef; Platform cannot map PersonRef to Account without Identity, and the design does not state that resolution contract. **Finding M-5.**

### Q6. Counterparty (D-175, R-C) — **PARTIAL**

**The four-way separation is correct.** R-C keeps apart the signer's identity, the signer's link, PublicDigit's commercial acceptance and the organisation's constitutional authority (`02[P]:270–275`). The signer is the service counterparty; the signer is a platform contact only if the founding decision names them (`02[P]:278–279`); D-175 creates no constitutional authority (`03:1158`; D-1a `03:36`). That is exactly the right distinction.

**But the design does not disclose the power D-176 gives the counterparty.** D-176 says the organisation may register additions and removals “through another current platform contact or its verified service counterparty” (`03:1166`). Revision 3 reframes the counterparty as a “recognised channel” and a “messenger”, not a decider (`02[L]:352`). The reframing is honest, but the *effect* is that a commercial counterparty can change who is allowed to authorise a staff inspection — an organisational-access power, not a commercial one. R-C's “commercial matters only” (`02[P]:278`) is not true in effect, and the design neither reconciles the two statements nor asks whether the Principal intended the counterparty to have that channel. **Finding H-5.** The counterparty record's content is also unbounded (M-7).

### Q7. INV-ACCESS-1 — **PARTIAL**

**Confirmed.** The service-operation grant permits provisioning, migrations, backups, restores (automated) and health checks, and excludes any human reading of content (`02[P]:109–110`). The case-grant path is stated as the only content path (`02[P]:47`, `110`; D-166 `03:1116`; `06:156–165`). Backups contain content, and opening a backup for inspection is content access requiring a case grant (`06:90`). The release-1 criteria are listed in the accepted design (`05:442`).

**Where it is partial.**

1. **Support staff content access conflicts with ISO-3.** ISO-3 refuses any authenticated request whose context organisation is not one the account is bound to, except public endpoints (`05:317`; `05:111`). A support staff member's Account is not bound to the organisation; a case grant is not a binding, and ISO-7 says the binding grants routing only (`05:115`). The Platform design lists “support staff: content only by case grant” (`02[P]:76`) but does not define the channel by which that request passes ISO-3, nor state an exception to the accepted design. Without a separate operations channel authorised by the case grant, the only lawful content path may be technically blocked; with an ordinary binding, the person would be routable as if they belonged. **Finding H-3.**
2. **Backup inspection is named by D-170 but not modelled.** D-170's trigger list includes “backup inspection” (`03:1134`). The `PendingCaseGrant` scope is “one case: purpose, data area, time window, staff member” (`02[P]:121`), but a backup copy is not inside the organisation's database; the design does not define the case type, the location, or how the grant is copied and reported for a backup inspection. **Finding M-9.**
3. **Central evidence is readable without a case grant.** F-01's decision text, rule provision and minutes are, if held centrally, readable by whoever operates the central database without a case grant — the opposite of INV-ACCESS-1's purpose. **Finding H-2.**
4. **No reporting channel** (H-4), and **no operator identity model**: the design names “support staff” as an actor but models no `PlatformStaff` identity, no operator role, and no check that the staff member using a grant is the one named in it. `05:442` says “operator roles non-interactive” and “no human standing credentials”, but support staff are human; the platform design should say how their identity is established and checked. **Finding H-3** (same root).

### Q8. Routing and failure — **PARTIAL**

**Confirmed.** Resolution fails closed for unknown hostname/organisation, not operational, wrong schema and not bound (`02[P]:151`). The 12 scenarios cover counterparty not verified, no grant, out-of-order updates, stale contact, disabled account, duplicate grant, out-of-scope use, recovery, locator tamper, migration failure, central/Identity outage and cross-organisation request (`02[P]:300–313`). No cross-database transaction is assumed: each aggregate change is central, and organisation-database work is a separate idempotent step (`02[P]:169–171`). F-04's transaction is central only (`02[L]:394–396`). That is correct.

**Where it is partial.**

1. **Migration in progress** is not a state and has no routing rule (M-8).
2. **Withdrawal versus use** is not linearised (M-11).
3. **Scenario 4 is stale**: it says “per Q-P3” (`02[P]:305`) although Q-P3 is decided by D-176 (`03:1166`). The D-176 note below the table gives the rule, but the table itself is not updated. **Finding L-2.**
4. **Scenario 12 overstates**: “impossible by ISO-1/2/5” (`02[P]:313`). Those are design invariants, not proved properties; the scenario should say “refused; a breach is a defect”, not “impossible”. **Finding L-2.**
5. **The staff-access path** is absent from the routing model (H-3), and the **contact-list capture** case (removal of all other contacts, then addition) is not a scenario (H-5).

### Q9. Contradictions, invented rules, claims stronger than evidence — **CONTRADICTORY**

The live text contains these unresolved contradictions:

| # | Contradiction | Evidence |
|---|---|---|
| 1 | **`PendingCaseGrant` has two state machines.** §3.4 `pending → used → reported → copied` vs R-E `given → active → closed` + copied flag; Part 4 still points to §3.4. Revision 3 does not reconcile them. | `02[P]:116`, `149`, `294`; `02[L]:392–400` |
| 2 | **R-C says the counterparty is “commercial matters only”** and “grants nothing inside the organisation”, while D-176 makes it a channel that can change the contact list. Revision 3 reframes it as a messenger but does not reconcile the two statements. | `02[P]:278–279`; `02[L]:352`; D-176 `03:1166` |
| 3 | **`pending` is a new durable status**, absent from the accepted design's list and creation flow, and D-175 says registration “stays `provisioning` until verified”. | `02[L]:371`, `404`; `05:74–80`, `332`; D-175 `03:1158` |
| 4 | **The bootstrap claim is issued by provisioning without an officer or AuthorityCheck**, while the accepted design's §4 step 4 requires both. | `02[L]:375–378`; `05:89` |
| 5 | **`PlatformContacts` stores decision reference, date and “position”**, while D-174 says Platform keeps “only their account / `PersonRef` references … (no other data)”; “position” may be an organisational office. | `02[P]:293`; D-174 `03:1157`; D-164 `03:1102` |
| 6 | **F-01's evidence is organisation Governance content held centrally**, while D-164 limits central data and D-170 says “metadata only, no business content”. | `02[L]:353–362`; `03:1102`, `1134` |
| 7 | **No AuthorityCheck act kind exists** for platform-contact designation or an emergency case grant in the accepted Governance thin slice, while the design says Governance owns who may grant. | `02[P]:56`; `10:121–135`, `192–194`, `223` |
| 8 | **D-73's one-τ rule is not addressed** for the cross-context grant flows (give → use → report → copy), although D-73 is in the design's binding list. | `02[P]:7`, `169–171`; D-73 `03:658` |
| 9 | **Claims stronger than evidence:** “reported at once” with no channel (H-4); scenario 12 “impossible” (L-2); the live text still contains “READY FOR PRINCIPAL ACCEPTANCE” at `02[P]:340` even though revision 3's own verdict is “READY FOR FOCUSED INDEPENDENT RE-REVIEW” (`02[L]:406`). | `02[P]:340`; `02[L]:361`, `406` |

The decision record and the accepted designs are otherwise reflected faithfully: D-164's central list (`03:1102`), D-166's two permissions (`03:1116`), D-167's no-default-authority (`03:1124`), D-170's pending mechanism (`03:1134`), D-173's ownership (`03:1149`), D-174's contacts (`03:1157`), D-175's counterparty (`03:1158`), D-176's platform-list rule (`03:1166`). The contradictions are in the tactical text's own reconciliation, not in a misreading of the decisions.

---

## 3. Findings register

**Severity:** high = blocks acceptance or breaks an accepted invariant/decision; medium = must be corrected before implementation or leaves a material gap; low = clarity, consistency or documentation.

### 3.1 High

| ID | Finding | Evidence | Fact / inference | Impact | Principal? |
|---|---|---|---|---|---|
| **H-1** | **The reviewed text is not the package text.** The package freezes revision 2 (`2dd9cec3…`); the repository carries revision 3 (`ab7e53bc…`, 406 lines), appended after a GPT-6 IR-P-01 review. The GPT-6 report is unavailable; the package's freeze and subject hash no longer describe the live text. | `01:8`; `0-START-HERE.md:8`; `02[L]:346–406`; hashes in §0.1 | fact | A Principal acceptance could attach to a different fingerprint from the one the package commissioned; a re-review cannot verify that F-01 … F-04 are the complete GPT-6 finding set. | **Yes** — decide which text is the subject, rebuild/re-freeze the package, and record the GPT-6 report or its absence. |
| **H-2** | **F-01 holds organisation Governance content centrally.** The direct registration requires the decision text/extract, the rules provision and the minutes, and copies them into the organisation database on recovery; the design does not say where they are held meanwhile. If central, it breaches D-164 and D-170's “metadata only, no business content”, and makes that content readable centrally without a case grant. | `02[L]:353–362`; D-164 `03:1102`; D-170 `03:1134`; `06:156–165` | fact (evidence required); inference (central holding) | The mechanism meant to protect the boundary imports the content it is meant to keep out; INV-ACCESS-1 is weakened at the exact moment the organisation's database is down. | **Yes if central holding is intended** (a new D-164/D-170 exception); otherwise a design correction. |
| **H-3** | **Support staff content access conflicts with ISO-3.** ISO-3 refuses an authenticated request whose organisation is not in the account's bindings; a case grant is not a binding. The design names support staff as an actor but defines no operations channel and no operator identity, and does not state an exception to the accepted design. | `02[P]:76`, `151`; `05:111`, `317`; ISO-7 `05:115`; `05:442` | fact | The only lawful content path may be technically blocked; or staff would need a binding that ISO-7 says is routing only. Either way INV-ACCESS-1's case-grant path is unproved. | **No** for the channel; **Yes** if ISO-3 is to be amended. |
| **H-4** | **Immediate reporting has no channel or data.** D-176 and R-E require every grant and every contact change to be reported “at once” to all current contacts; Platform holds only Account references, the organisation's contact details are in the database that is down, and Identity's login address is held for authentication. No notification contract is defined. | `02[L]:361`; `02[P]:285`, `294`; D-174 `03:1157`; XD-3 `09:18–25`; `08:64` | fact (no channel); inference (unimplementable as stated) | D-176's central control — immediate reporting — may not be executable; the claim is stronger than the model. | **Yes if** Identity's login address is to be used for operational notices (a new use and lawful basis); otherwise a design correction. |
| **H-5** | **The contact list can be captured; the cited decision is not authenticated.** Platform checks only presence and well-formedness; a current contact or the counterparty can register a fabricated decision, remove all other contacts (removals are reported only to current contacts), then add an accomplice. The recovery comparison is after the inspection. This contradicts R-C's “commercial matters only” and is not stated as a residual risk. | `02[L]:352–365`; `02[P]:278–279`, `335`; D-176 `03:1166` | fact (mechanism); inference (impact) | A commercial counterparty or one contact can, during an outage, replace the list that authorises content access; D-176's controls detect but do not prevent it. | **Yes** — accept the residual risk or require dual control / notice to removed contacts / a cooling-off period before a new contact can give a grant. |

### 3.2 Medium

| ID | Finding | Evidence | Fact / inference | Impact | Principal? |
|---|---|---|---|---|---|
| **M-1** | **`PendingCaseGrant` state model is split and lossy.** §3.4 and R-E disagree; Part 4 points to §3.4; `expired`/`withdrawn` collapse into `closed`; the durable `reported` state disappears; `CaseGrantReported` has no state. | `02[P]:116`, `149`, `167`, `294`; `02[L]:392–400` | fact | Implementers may build the wrong states; report delivery is not tracked; expiry and withdrawal become indistinguishable in audit. | No — design correction. |
| **M-2** | **`pending` is not reconciled with the accepted design or D-175.** It is absent from `05`'s status list and creation flow; D-175 says registration “stays `provisioning` until verified”. | `02[L]:371`, `404`; `05:74–80`, `332`; D-175 `03:1158` | fact | Two lifecycle descriptions; a reader may treat `pending` as outside the accepted state machine. | No — note the refinement in `05`; no decision reopened. |
| **M-3** | **The bootstrap claim bypasses the accepted officer/AuthorityCheck path.** Provisioning issues the registrar's claim on the founding decision; `05` §4 step 4 requires an officer and AuthorityCheck. | `02[L]:375–378`; `05:89` | fact | An unstated exception to an accepted design; the platform performs the first access-granting act. | No — state the bootstrap exception and record it in `05`; Principal note only if the exception is to be accepted as a rule. |
| **M-4** | **An initial contact may have no Account, or may be blocked by INV-ID-6.** No invitation path; the “at least one contact” invariant can fail before `operational`. | `02[P]:259`; `02[L]:384`; `08:64` | fact | Joining can stall with no stated remedy. | No — design correction. |
| **M-5** | **Account and PersonRef keys are not reconciled.** Platform list is Account-keyed; the organisation's designation is PersonRef-keyed; no resolution contract. | `02[P]:293`; `02[L]:390`; D-113 `03:878` | fact | Contact updates from the organisation database cannot be matched to the platform list without Identity; risk of ISO-4/ISO-7 confusion. | No — design correction. |
| **M-6** | **`PlatformContacts` exceeds D-174's “no other data”.** It stores decision reference, date and “position”; `position` may be an organisational office (Governance fact). | `02[P]:293`; D-174 `03:1157`; D-164 `03:1102` | fact | Central data beyond the decision's text; possible centralisation of an organisation office. | **Yes** for the D-174/D-164 reading if `position` means an office; otherwise a design correction. |
| **M-7** | **Counterparty record content is unbounded.** D-175 requires the signer's identity and link; the design records method, fingerprints, date and verifier but not where the identity, agreement or link evidence live. | `02[P]:281`; D-175 `03:1158`; D-164 `03:1102` | fact | Personal/commercial data may be centralised without a stated boundary; [LQ] covers sufficiency, not storage. | **Yes** if the identity/agreement is to be stored centrally; otherwise a design correction. |
| **M-8** | **Migration in progress is not modelled.** `schemaState` has only `current` and `migration-failed`; no `migrating`/maintenance state or routing rule. | `02[P]:89`; `05:333`; `05:189` | fact | Requests may hit a half-migrated schema; the failure state catches only the end result. | No — design correction. |
| **M-9** | **Backup inspection is not modelled.** D-170 names it; the `PendingCaseGrant` scope and the design's case types do not cover a backup copy outside the organisation database. | D-170 `03:1134`; `02[P]:121`; `06:90` | fact | The one case D-170 explicitly names has no tactical path. | No — design correction. |
| **M-10** | **No AuthorityCheck act kind for platform-contact designation or an emergency case grant.** The accepted Governance contract has no such act kind; the design says Governance owns who may grant. | `02[P]:56`; `10:121–135`, `192–194`, `223` | fact | The “normal AuthorityCheck route” (`02[P]:221`) has no contract for these acts. | No — a Governance-session contract change; Principal only if the contract is to be extended by decision. |
| **M-11** | **Withdrawal vs use is not linearised.** F-04 covers removal vs grant only. | `02[L]:392–400`; `02[P]:295` | fact | A withdrawn grant may be used once more. | No — design correction. |
| **M-12** | **Ordering key is ambiguous.** “Decision date and log position” has no tiebreak; backdated and future-dated decisions are not handled; a fabricated future date wins. | `02[P]:250`; `02[L]:335` | fact | Out-of-order or forged updates may be applied as “newest”. | No — design correction (and part of H-5). |
| **M-13** | **D-73's one-τ rule is not addressed** for the give → use → report → copy flow. | `02[P]:7`, `169–171`; D-73 `03:658` | fact | The evaluation time for the grant's authority/scope is unstated. | No — design correction. |

### 3.3 Low

| ID | Finding | Evidence | Fact / inference | Impact | Principal? |
|---|---|---|---|---|---|
| **L-1** | **Stale text in the live file.** §3.2's status table does not include `pending`; Part 4 says “states per §3.4”; the old verdicts at `02[P]:228` and `02[P]:340` are not marked superseded. | `02[P]:81–86`, `149`, `228`, `340`; `02[L]:402–406` | fact | A reader can rely on a superseded passage; the file carries three verdicts. | No — editorial. |
| **L-2** | **R-F scenarios 4 and 12 are stale or overstated.** Scenario 4 says “per Q-P3” though D-176 decided it; scenario 12 says “impossible by ISO-1/2/5”, which are unproved invariants. | `02[P]:305`, `313`; D-176 `03:1166` | fact | A reader may treat an unproved property as established; the decided rule is not in the table. | No — editorial. |
| **L-3** | **`WithdrawCaseGrant` by any current contact** allows one contact to withdraw another's legitimate grant. | `02[P]:295` | fact | Safe-side denial of access; intended or not is unstated. | No — state the intent. |
| **L-4** | **The one-aggregate justification is partly wrong.** Hostname uniqueness is cross-aggregate; the locator rule is a command rule; F-04 spans two aggregates in one transaction. | `02[P]:147`; `02[L]:394–396` | fact | The aggregate may still be right, but the stated consistency boundary is not. | No — restate. |
| **L-5** | **Version terminology differs** between R-E's “designation record version” and F-04's `ServedOrganisation` row version. | `02[P]:294`; `02[L]:396` | fact | Implementation ambiguity. | No — align the terms. |

---

## 4. Verdict

### 4.1 On the live text (revision 3): **NOT READY**

The design is materially better than revision 2. It closes the grant-before-provisioning contradiction, aligns the registrar bootstrap with the accepted multi-database creation flow, adds evidence and ordering to the emergency contact route, and linearises contact removal against grant. Those are real corrections, and revision 3 deserves credit for them.

It is not ready for acceptance because:

1. **H-2** — the correction imports organisation Governance content (decision text, rules provision, minutes) into the central record, against D-164 and D-170's “metadata only” rule, and weakens INV-ACCESS-1 at the moment the organisation database is down.
2. **H-3** — the staff content-access path is not reconciled with accepted ISO-3; the only lawful content path may be technically blocked, or may require a binding that ISO-7 forbids as authority.
3. **H-4** — “reported at once” has no channel or data; D-176's central control is asserted, not designed.
4. **H-5** — the contact list can be captured by a current contact or the counterparty; the cited decision is asserted, not authenticated; removed contacts are not told of their removal; the residual risk is not stated.
5. **M-1 / Q9** — the `PendingCaseGrant` state model is still split between §3.4 and R-E, and the live text still carries the old “READY FOR PRINCIPAL ACCEPTANCE” verdict alongside revision 3's “READY FOR FOCUSED INDEPENDENT RE-REVIEW”.

Three of these need the Principal's decision (H-2 if central evidence is intended; H-4 if Identity's login address is used for notices; H-5 whether the residual capture risk is accepted). I do not decide them. The rest are design corrections.

**Conditions for a focused re-review** (not a decision, and not an acceptance): H-2 resolved by keeping only digests/references centrally or by a recorded Principal exception; H-3 resolved by an explicit operations channel and operator identity, or an ISO-3 exception recorded in `05`; H-4 resolved by a named notification contract and lawful basis; H-5 resolved by dual control/notice or a recorded acceptance of the residual risk; M-1 resolved by one state model.

### 4.2 On the package text (revision 2) alone

The verdict would also be **NOT READY**, for the grant-order contradiction (`02[P]:92–99` vs `02[P]:112`, `147`), the state-model divergence (`02[P]:116` vs `02[P]:294`), the stale-contact/forgery and reporting gaps (`02[P]:242–252`, `333–338`), the ISO-3 staff-access conflict, and the same boundary issues. Revision 3 fixes the first and improves the second and third; the rest remain. The package's own “READY FOR PRINCIPAL ACCEPTANCE (self-review basis)” (`02[P]:340`) is not supported by this review.

### 4.3 What this review is not

- It is not the independent review the commission requires (`01:7`); the reviewer is the excluded model and interface. The Principal must decide what weight to give it.
- It is not a proof that the design is wrong in implementation; it is a design review. No code, test, migration, restore or access-control evidence was examined.
- It does not decide any Principal question, modify any file, or claim any acceptance.
- The GPT-6 IR-P-01 report is **UNAVAILABLE**; I cannot confirm that revision 3's F-01 … F-04 are the complete set of its findings.

**Stop — end of report.**
