# B1 — `apply_candidacy`: the one participant-performed capability, classified

**Commission:** Principal Architect · Election Verification Slice 1, batch **B1** · **Date:** 2026-08-08 · **Checkpoint:** `caf42ef7`
**Status:** investigation only — **no production code, test, fixture, migration or configuration changed**
**Scope:** `apply_candidacy` only. B2–B7 not started.

---

## 1 · Capability definition — read from the Constitution, not interpreted

```php
'apply_candidacy' => [
    'allowed_states'  => ['setup_nomination'],
    'allowed_roles'   => ['voter', 'member'],
    'preconditions'   => [],
    'target_state'    => 'setup_nomination',  // no state change; capability check only
    'description'     => 'Apply for candidacy during the nomination phase',
],
```

**OBSERVED FACTS:** the only constitutional action whose roles are **participants** (`voter`, `member`) rather than officers or `system` · permitted **only** in `setup_nomination` · **no declared preconditions** · **no state change** — and the self-transition carries an **explicit inline comment** saying so.

⚠️ **This corrects my own calibration.** That report implied only `resume` documented its self-transition and singled out `complete_nomination` as undocumented. **`apply_candidacy` documents it too.** So **two of three** self-transitions are documented, and `complete_nomination` remains the only undocumented one — a narrower and more accurate statement than the one I filed.

## 2 · The actual B1 test population — and it is the finding

**Three files reference `apply_candidacy`:**

| File | In the adopted universe? | What it is |
|---|---|---|
| `tests/Feature/Election/ElectionDashboardCapabilitiesTest.php` | ✅ **yes** | see §3 |
| `tests/Feature/ElectionStateMachineTest.php` | 🔴 **no** — `tests/Feature/`, not `tests/Feature/Election/` | out of scope |
| `tests/Support/ElectionScenarioFactory.php` | n/a | a **support helper**, not a test |

**And the population that actually concerns candidacy application:**

| File | Tests | In the adopted universe? |
|---|---:|---|
| `tests/Feature/CandidacyApplicationTest.php` | **12** | 🔴 **no** |
| `tests/Feature/ElectionCandidacyRelationshipTest.php` | 9 | 🔴 no |
| `tests/Feature/ElectionCandidacyApplyPageTest.php` | 6 | 🔴 no |
| `tests/Feature/CandidacyApplicationMigrationTest.php` | 1 | 🔴 no |
| `tests/Feature/DebugCandidacyTest.php` | 1 | 🔴 no |
| **Total** | **29** | **all outside** |

**OBSERVED FACT.** **Zero of the 1,376 rows belong to a class with `Candidac` in its name.** *(Counted with `phpunit --list-tests`, per the binding rule.)*

> 🔴 **The entire candidacy-application test population — 29 tests — sits OUTSIDE the universe `SD-1` adopted.** The adopted universe **cannot answer** whether a participant can apply for candidacy.

**This supersedes the calibration's "1 referencing file" signal.** The true measurement is **0 verifying tests in scope, 29 candidate tests out of scope.** *"Textual reference ≠ coverage"* was the right caution, and the gap it concealed was larger than expected.

## 3 · What the one in-universe test actually verifies

`ElectionDashboardCapabilitiesTest::test_capabilities_map_has_all_required_actions` — **PASSED**.

```php
$reflection = new \ReflectionMethod($controller, 'getStateMachineData');
$reflection->setAccessible(true);
$stateMachine = $reflection->invoke($controller, $election);
…
$this->assertArrayHasKey($action, $stateMachine['capabilities']);
```

| Dimension | Finding |
|---|---|
| **Business intent** | that the dashboard's capabilities map **contains a key** for each of the 15 constitutional actions |
| **Business invariant** | **None for `apply_candidacy`.** It asserts the *presence of a key*, never that a participant may or may not apply |
| Lifecycle state | 🔴 **not established** — the fixture is `Election::factory()->create(['type' => 'real'])`; **`setup_nomination` is never established** |
| Actor | 🔴 **none** — no user is authenticated; roles `voter`/`member` are never exercised |
| Authorization | 🔴 **not exercised** |
| Entry point | 🔴 **a private controller method invoked by reflection** — not an HTTP route |
| Authoritative source consulted | `ElectionManagementController::getStateMachineData()` — **a projection**, not the constitution |
| **Business Decision Ownership** | **Interface/Projection** |
| **Verification strength** | **Structural / projection verification** — **not business verification** |

> **A green test, and it proves the map has a key.** It cannot pass or fail on whether candidacy application works.

## 4 · Business-case coverage, within the adopted universe

| | Case | Status |
|---|---|---|
| A | legitimate participant can apply | 🔴 **NOT VERIFIED** |
| B | wrong lifecycle state cannot apply | 🔴 **NOT VERIFIED** |
| C | unauthorised actor cannot apply | 🔴 **NOT VERIFIED** |
| D | non-member cannot apply | 🔴 **NOT VERIFIED** |
| E | inappropriate voter/member combination | **BUSINESS RULE NOT SPECIFIED** — the constitution lists both roles and does not distinguish them |
| F | duplicate candidacy | **BUSINESS RULE NOT SPECIFIED** — no declared precondition |
| G | candidate eligibility rules enforced | **MECHANISM NOT ESTABLISHED** |
| H | application persisted | 🔴 NOT VERIFIED |
| I | resulting business state correct | **n/a** — the action declares **no state change** |
| J | authorization is server-side | 🔴 **NOT VERIFIED** |
| K | invalid application cannot bypass the rule | 🔴 **NOT VERIFIED** |

**Nothing above is a defect claim.** **Not verified** means the adopted estate provides no evidence; the 29 out-of-scope tests may cover some of it, and **that has not been read.**

## 5 · Architectural observations

1. 🔴 **A constitutional capability with no in-scope behavioural test.** The constitution declares who may apply and when; the adopted estate verifies only that a **projection contains its name**.
2. **`apply_candidacy` is a capability check with no state change** — so `ConstitutionalTransitionGuard`-style transition tests would never cover it. **Its verification must be capability/authorization-shaped**, which is a different test shape from the other 14 actions. *(Recorded as a structural consequence, not a defect.)*
3. **The one in-scope test reaches its subject by reflection into a private method.** Whatever it protects, it is **not protecting an HTTP contract**.
4. 🔴 **`SD-1`'s adopted universe has a boundary defect for this capability.** `tests/Feature/Election/` was adopted; the candidacy tests live in `tests/Feature/`. **The scope was adopted from the assessment's five paths, and it excludes the population most relevant to the only participant-performed action.**

## 6 · Open questions — none resolved here

* **Do any of the 29 out-of-scope tests verify cases A–D, H, J or K?** **Not read.** Deciding to read them is an `SD-1` scope amendment, **not** engineering's call.
* Is the `voter` / `member` distinction in `allowed_roles` meaningful, or are both simply "participant"? **BUSINESS RULE NOT SPECIFIED.**
* Is duplicate candidacy permitted? **No declared precondition** — specification gap, not a code gap.
* **CROSS-STREAM QUESTION — NOT RESOLVED HERE:** Session 2 / IERVP holds runtime evidence about voter entitlement and suspension. **Not consulted, not used as specification.**

## 7 · Recommended follow-up — proposals only

| | Proposal |
|---|---|
| **`SD-4`** | **Amend `SD-1` to include the candidacy population** (or record deliberately that it stays out). **A scope decision, not engineering's.** |
| B1-a | Read the 29 out-of-scope tests **once scope permits** — the measurement, not a repair |
| B1-b | Record `complete_nomination`'s undocumented self-transition as a constitution question |

**No repair is proposed for the missing coverage, because whether it is missing depends on `SD-4`.**

## 8 · Self-audit

| Check | ✓ |
|---|---|
| Capability derived from the Constitution | ✅ quoted verbatim |
| Read the actual test, not just grep | ✅ §3 quotes the assertion and its fixture |
| Business intent established before result | ✅ intent first; the PASS is interpreted afterwards |
| Verified the actual lifecycle state | ✅ **`setup_nomination` is never established** |
| Actor identified | ✅ **none authenticated** |
| Authoritative decision source identified | ✅ a projection, not the constitution |
| Business Decision Ownership populated | ✅ Interface/Projection |
| Passing distinguished from verified | ✅ **the core finding** |
| Failures classified only after tracing | ✅ **no failures in B1** — the test passes |
| Session 2 evidence not used | ✅ recorded as a cross-stream question |
| No production/test/fixture change | ✅ |
| No constitutional rule invented | ✅ `BUSINESS RULE NOT SPECIFIED` used twice |
| Unknowns labelled | ✅ |
| Only B1 rows to be updated | ✅ 4 rows |
| Corrected my own prior report | ✅ §1 — the self-transition documentation claim |

---

**B1 COMPLETE — `APPLY_CANDIDACY` CLASSIFIED — AWAITING PRODUCT OWNER REVIEW**

**Traceability:** `app/Domain/Election/Constitution/ElectionConstitution.php` (`apply_candidacy`, verbatim) · `tests/Feature/Election/ElectionDashboardCapabilitiesTest.php:90-126` · `tests/Feature/{CandidacyApplicationTest,ElectionCandidacyRelationshipTest,ElectionCandidacyApplyPageTest,CandidacyApplicationMigrationTest,DebugCandidacyTest}.php` (29 tests, `--list-tests`) · matrix `2026-08-08-election-master-matrix.tsv` · calibration `2026-08-08-election-capability-calibration.md` (§1 correction)
