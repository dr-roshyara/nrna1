# Election-Only ticket authority matrix — `PBDIGIT-48` … `PBDIGIT-68`

**Type:** Governance / authority classification · **Date:** 2026-08-13 · **Programme:** IERVP (Session 2)
**⛔ Analysis only. No production code, test, `ElectionConstitution`, lifecycle, domain-object or fixture change. No implementation authorized. No open business decision resolved. No settled decision reopened. Tickets are treated as governance EVIDENCE, not as automatic requirements.**

**Authority ladder applied** (higher never inferred from lower): PO/ARB decision → adopted rule (Manifesto, where ratified: *unratified — cited as adopted-decision record*) → `ElectionConstitution` / accepted ADR → implementation authorization → implementation evidence → tests → ticket text. **Test failure ≠ business rule · implementation ≠ business decision · ticket text ≠ authorization · implemented ≠ authorized.**

---

## 1 · The matrix

**Identifiers 52–57 were never issued** — verified against the backlog filesystem; one row each, so the range is auditable.

| Ticket | Subject | Election-Only? | Status | Authority | Implemented? | Authorized? | Blocker? | Dependency | Decision Needed | Next Action |
|---|---|---|---|---|---|---|---|---|---|---|
| **48** | Election state had four representations; login routing reads a deprecated field | YES *(routing affects EO voters)* | **H — SUPERSEDED** *(discovery complete; work carried by `PBDIGIT-58`)* | its own discovery, PO-accepted; Option B approved | n/a *(carrier)* | n/a | NON-BLOCKER *(as a ticket)* | → 58 | none | **retire to traceability** — historical source for 58 |
| **49** | Voter eligibility has two homes (`election_memberships` 3 · `voters` 0) | YES *(voter model)* | **E — TECHNICAL/ARCHITECTURAL SUPPORT** | ticket + measurements only | MIXED — duplication exists | NO | NON-BLOCKER | overlaps `AD-2`/`Q3` *(authoritative-engine question)* | none new — folded into `AD-2` | **verify** it stays aligned when `Q3` is decided |
| **50** | Times not converted to viewer's timezone | YES *(EO voters read deadlines)* | **D — BLOCKED BY BUSINESS DECISION** | PO steer on record *(device timezone)* — **ticket itself `OPEN — not authorised`**, fallback undecided | MISSING | NO | NON-BLOCKER *(no adopted rule requires it — a product gap, not a correctness failure)* | `EM-OPEN-018` · interacts with 67 | ✅ `EM-OPEN-018` | **clarify** — PO completes the display decision |
| 51 | Error pages cannot render (`symfony/config` absent) | CROSS-CUTTING | **E — TECHNICAL SUPPORT** | ticket + reproduced evidence | MISSING | NO | NON-BLOCKER *(diagnostic cost only)* | none | none | **implement when convenient** *(separate authorization)* |
| 52–57 | — | — | **NEVER ISSUED** *(no ticket exists — filesystem-verified)* | — | — | — | — | — | — | none |
| **58** | Complete the legacy election-state migration (7 capabilities; `58A` observes first) | YES *(voter login-routing to a live election)* | **C — PARTIAL** | Option B **approved**; implementation **awaiting authorisation** | PARTIAL | **NO** *(direction approved ≠ slice authorized)* | **CONDITIONAL** — becomes a blocker **iff** the voting-period login-redirect rule is adopted *(a PO statement on record, 2026-08-09; flagged in the Manifesto coverage note as an UNCONFIRMED candidate — deliberately never migrated from conversation)* | 59 sequences `58B` | 🟡 **confirm/adopt the login-redirect rule** | **clarify, then authorize** |
| **59** | Which timestamps are constitutional? (`voting_*` vs `start/end_date` — disagree for 4 of 4) | **YES — the clock drives the computed path** | **D — BLOCKED BY BUSINESS DECISION** | ticket + measurement; **DECISION REQUIRED** on its face | n/a *(the ambiguity IS the state)* | NO | **CONDITIONAL — strong.** The EO voting window's *authority* is ambiguous; `EM-VOT-002`'s computed-path enforcement reads this clock | 67 · 58B | ✅ **the timestamp-authority decision** | **clarify — decide before any window-semantics work** |
| **60** | Result Visibility never became a capability *(deputy can fabricate `results_published_at`; results hidden with no way back)* | YES *(EO results phase)* | **C — PARTIAL** | 🔑 **PO domain ruling ON RECORD (2026-08-06): publication = immutable constitutional fact · visibility = toggleable operational control (`hide/show`, never "unpublish")** | PARTIAL — capability missing; `D-2` integrity defect present | `D-2` marked *"fixable now"* — **treated as NOT slice-authorized until the PO re-confirms** *(a ticket phrase is not an authorization)* | **CONDITIONAL** — `V-1` (results exposable during `voting_active`) is integrity-relevant for EO | — | 🔑 **`EM-OPEN-013` (G-PUB) is substantially ANSWERED by this ruling** — see §4. PO to confirm disposal | **verify authority, then authorize `D-2`** |
| 61 | Newsletter suite failed at HEAD; dispatch never worked | **NO** *(Member Communication)* | **G — OUTSIDE ELECTION-ONLY** | resolved record | ✅ RESOLVED 2026-08-07 | YES (was) | NON-BLOCKER | none | none | none — closed |
| **62** | "Already voted" exclusion never fired at login *(tenant scope defeated it)* | YES *(EO post-vote routing)* | **B — ALREADY SATISFIED** | PO-authorized bounded fix · RED→GREEN proven | ✅ REPAIRED 2026-08-07 | YES | NON-BLOCKER | sibling of 65/69 *(same `BelongsToTenant` mechanism)* | none | **verify** — Session 1's estate should hold it |
| 63 | Verify the organisation-creation journey + estate *(IERVP carrier)* | CROSS-CUTTING *(verification programme, not a feature)* | **E — SUPPORT** | its own reviews | n/a | n/a | NON-BLOCKER | residue: IERVP test data · working-org restore *(PO-owned disposal)* | none | **retire to traceability**; dispose residue when PO chooses |
| **64** | Election opens voting without any candidate | **YES — core** | **A — REQUIRED** | ✅ **adopted `EM-VOT-001`/`EM-VOT-002` + explicit implementation grant** | 🟡 **IN PROGRESS** *(Session 3, in flight, within grant — Watch 02)* | ✅ **YES** | **BLOCKER — being closed** | Session 1 verification follows | none — decided | **Session 3 completes; Session 1 verifies** |
| **65** | Voter eligibility decided by browser tenant context | **YES — core** | **A — REQUIRED** | **adopted entitlement rules** (`EM-ENT-001`, `EM-EO-*`: the entitlement is election-specific) **+ controlled A/B measurement** of denial | MISSING *(defect present)* | **NO** | 🔴 **BLOCKER — the one authoritative unaddressed blocker in the range.** A voter holding a valid election-specific entitlement is denied by ambient context — that contradicts adopted rules, with measured evidence | `PBDIGIT-69` *(outside range — the 300s cache that makes 65 persist; resolve together or 69 first)* | `Q-D1`-adjacent; **repair needs its own authorization** | **authorize the repair slice** |
| **66** | Establish Election-Only mode constitutionally | YES | **C — PARTIAL** | premise materially corrected *(authority DID exist)*; **its entitlement half is delivered** — the `D-ENT` chain + Manifesto EO rules | PARTIAL *(rules adopted; constitutional vocabulary pending)* | vocabulary → **Phase 4 review** *(settled routing — not reopened)* | NON-BLOCKER | Phase 4 governance-language review | none new | **hold for Phase 4**; supersede-check then |
| **67** | Schedule input interprets browser-local time as UTC *(elections open 1–2 h late, DST-variable)* | **YES** *(EO windows measurably wrong)* | **D — BLOCKED BY BUSINESS DECISION** | measured defect; **`D-1`…`D-4` explicitly reserved for the PO** | MISSING | NO | **CONDITIONAL — strong.** No *adopted* rule states input-timezone semantics, so per the blocker test it cannot be called authoritative — **but the voting window is factually wrong on live data** | 59 · `EM-OPEN-018` | ✅ **`D-1`…`D-4`** | **clarify — decide `D-1`…`D-4` with 59** |
| **68** | ElectionMembership is a durable election entitlement *(the `D-ENT` carrier)* | YES *(admission/governance)* | **C — PARTIAL** | ✅ adopted chain (`D-ENT-1`/`2`, `Q-A0`, `Q-B1`, 27 Manifesto rules) · implementation gaps `IG-*` **not** authorized | PARTIAL | admission slice **blocked by `BR-1.12`**; suspension by `BR-1.13`/`Q3` | **CONDITIONAL** — `BR-1.12` blocks the admission slice *(already on the PO's desk)* | `BR-1.12` · `Q3` · `BR-1.x` | ✅ `BR-1.12` first | **PO decides `BR-1.12`; then authorize admission** |

## 2 · Election-Only delivery view *(the Product Owner's view)*

| Group | Tickets |
|---|---|
| **1 · ALREADY SATISFIED** | **62** *(already-voted exclusion — repaired, proven)* |
| **2 · IMPLEMENTED BUT NEEDS VERIFICATION** | *(none in range — 62's estate coverage is Session 1's to confirm)* |
| **3 · IMPLEMENTATION IN PROGRESS** | **64** *(`EM-VOT-002` — Session 3, within grant)* |
| **4 · AUTHORIZED BUT NOT IMPLEMENTED** | *(none — 64 moved to group 3; nothing else holds a grant)* |
| **5 · BUSINESS DECISION REQUIRED** | **50** (`EM-OPEN-018`) · **59** (timestamp authority) · **67** (`D-1`…`D-4`) · **68** (`BR-1.12`) · **58** *(conditional — confirm the login-redirect rule candidate)* · **60** *(confirm `D-2` authorization + `EM-OPEN-013` disposal)* |
| **6 · NOT ELECTION-ONLY** | **61** *(Member Communication — resolved anyway)* |
| **7 · FULL MEMBERSHIP / DEFERRED** | *(no ticket in range is primarily FM — the FM register lives inside 68 and stays frozen)* |
| **8 · OBSOLETE / RETIREMENT CANDIDATES** | **48** *(superseded by 58 — retire to traceability)* · **63** *(programme carrier — retire to traceability; residue disposal remains)* |
| **Unaddressed authoritative blocker** | 🔴 **65** *(+ its persistence mechanism `PBDIGIT-69`, outside range)* |

## 3 · Critical path to "Election-Only READY"

**Only authoritative blockers — unfinished ≠ critical.**

```
ADOPTED RULES (27, incl. EM-VOT-002)
        │
        ├── 64  EM-VOT-002 enforcement        → Session 3 completes → Session 1 verifies   [in progress]
        │
        ├── 65  entitlement recognised regardless of ambient context                        [🔴 needs repair authorization]
        │        └── with 69 (cache persistence) — together, or 69 first
        │
        ├── BR-1.12  admission state          → PO decides → admission slice authorized     [on the PO's desk]
        │
        └── TIME SEMANTICS (conditional pair) → 59 timestamp authority + 67 D-1…D-4         [PO decisions]
                 └── without them the EO voting window's meaning rests on disputed clocks
        │
        ▼
   ELECTION-ONLY READY  (verification by Session 1 throughout)
```

**Explicitly NOT on the critical path:** 48 · 49 · 50 · 51 · 58 *(unless the redirect rule is adopted)* · 60 *(integrity-relevant but results-phase; sequence after voting correctness unless the PO prioritises `V-1`)* · 61 · 62 · 63 · 66 · the FM register.

## 4 · 🔑 Governance find of this analysis — `EM-OPEN-013` appears already answered

**`PBDIGIT-60` carries a Product Owner domain ruling (2026-08-06)** that predates and substantially answers the `G-PUB`/`EM-OPEN-013` question *("may a published result be unpublished?")*:

> **Publication is an immutable constitutional fact** (`publish_results`, once) · **visibility is a separate, toggleable operational control** (*hide/show results — never "unpublish"*).

**When I raised `G-PUB` from the Officer Guide's "unpublish as many times as needed", I missed this recorded authority.** The guide's language conflicts with the ruling; the *console command's* existence is implementation, not authority. **Registered as: `EM-OPEN-013` — ANSWERED-BY-EXISTING-AUTHORITY CANDIDATE, awaiting one-line PO confirmation that the 2026-08-06 ruling disposes of it.** *(Not closed by me — cross-referencing is Session 2's job; closing is the PO's.)*

## 5 · Final report

| | |
|---|---|
| **A · Analysed** | 21 identifiers → **15 tickets exist**, 6 never issued (52–57, filesystem-verified) |
| **B · Election-Only REQUIRED (A)** | **2** — 64 *(in progress)* · **65** *(unaddressed)* |
| **C · Already satisfied (B)** | **1** — 62 |
| **D · Partial (C)** | **4** — 58 · 60 · 66 · 68 |
| **E · Blocked by business decision (D)** | **3** — 50 · 59 · 67 |
| **F · Full Membership / deferred (F)** | **0 tickets** *(the FM register lives inside 68, frozen)* |
| **G · Outside Election-Only (G)** | **1** — 61 |
| **H · Obsolete/superseded candidates (H)** | **2** — 48 · 63 *(both: retire to traceability — a PO act, proposed not performed)* |
| **I · Authority gaps / unclassifiable** | **0 tickets unclassifiable.** Two **rule-candidates** exist only in conversation: the **voting-period login-redirect** (affects 58's blocker status) and the **auto-approval threshold** (`EM-OPEN-019`, 30 vs 40) |
| **J · Current EO blockers** | 🔴 **65** *(authoritative, unaddressed — with 69)* · 64 *(being closed)* · `BR-1.12` *(decision)* · conditional: 59 + 67 *(time semantics)* |
| **K · NOT blockers** | 48 · 49 · 50 · 51 · 58 *(today)* · 60 · 61 · 62 · 63 · 66 |
| **L · Decisions required from the PO** | ① `BR-1.12` · ② 59 timestamp authority · ③ 67 `D-1`…`D-4` (+ `EM-OPEN-018`) · ④ confirm/reject the **login-redirect rule** candidate *(flips 58)* · ⑤ confirm **`EM-OPEN-013` disposal** via the 60 ruling · ⑥ authorize the **65(+69) repair slice** · *(standing: `EM-OPEN-019`, `EM-OPEN-017`, `EM-OPEN-021`)* |
| **M · Recommended next governance actions** | **1.** Authorize the **65+69 repair** — the only authoritative unaddressed blocker. **2.** Decide **`BR-1.12`** — cheapest open decision, unblocks admission. **3.** Take **59+67 together** — one time-semantics decision set, not two. **4.** Confirm `EM-OPEN-013` disposal *(one line)*. **5.** Retire 48/63 to traceability. **Everything else can wait without endangering Election-Only.** |

> **The answer to the question as posed:** of everything unfinished in `PBDIGIT-48`…`68`, **only 65 (+69), the completion of 64, and the `BR-1.12` decision are necessary for Election-Only to be *correct* against its adopted rules** — plus the conditional time-semantics pair (59+67) if "the window opens when scheduled" is to mean anything definite. **The rest is real work, but not Election-Only-critical.**

---

**Boundaries:** tickets read as evidence; no ticket text promoted to authority; `IMPLEMENTED` never conflated with `AUTHORIZED` *(60's "fixable now" explicitly not treated as a grant)*; settled decisions not reopened (`EM-VOT-001`/`002`, `SD-14`, the Constitution's home status, the FM freeze, the no-second-registry rule); no duplication created — 48/63 marked as traceability sources, not re-required; Session 1's Master Matrix not consumed *(62's estate status left to Session 1)*; Session 3's in-flight work untouched. **Where authority was absent I did not guess** — the two conversation-only rule candidates are named as candidates, not classified as rules.
