# WP-7B-R1 — Final Authorization Readiness Review

**Date:** 2026-08-02 · **Prepared by:** Principal Architect
**Question:** **does sufficient evidence exist for the ARB to authorize implementation?**
**Status:** verification only. **No architecture redesigned · no Tactical DDD performed · no ADR modified · no code written · no work package created · no priority reordered.**

---

> ## DECISION — **Option A: WP-7B-R1 is READY FOR AUTHORIZATION**
>
> **With one thing the Board should read before agreeing: the open Q-2 decision is the *reason* for this work, not a blocker to it. §2 sets out why.**

---

## 1. Scope Verification

**The target is one private method**, `ResolvesEvidencePreservationWindow::anchorOf()`, whose docblock already declares itself interim:

> *"⚠️ **INTERIM.** Policy 2 defines the window's three durations but not its START, and the anchor is an open Q-2 decision. Until it is made, the first available candidate is used, ordered narrowest-to-broadest; **when Q-2 rules, this collapses to one named field and nothing else changes.**"*

| Check | Result |
|---|---|
| **Implementation scope explicit** | ✅ R-60: *"extract the interim anchor into an **`EvidenceAnchorResolver` port with a `TemporaryDefaultAnchorResolver`** implementation"* |
| **Implementation boundary explicit** | ✅ **one method, one service, one new port + one implementation, one container binding.** `EvidencePreservationWindow` (Domain) and `EvidencePreservationDurations` (port) are not touched |
| **Excluded scope explicit** | ✅ R-60: **"behaviour unchanged"** · *"separate from Slice 7B and **not a reopening of it**"*. **Deciding the anchor is Q-2's act and is excluded by construction** |
| **Acceptance boundary explicit** | ✅ **behaviour-preservation, and it is testable rather than aspirational** — the existing suites are the harness: `EvidencePreservationWindowResolutionTest` (2) · `EvidencePreservationDurationsTest` (8) · `EvidencePreservationWindowTest` (unit) · `AuditCleanupTest` (10). **Every one must stay green unmodified** |

## 2. Dependency Verification

| Dependency | Classification | Basis |
|---|---|---|
| The three ownership decisions (`Jurisdiction` · `EvidenceEnvelopeRef` · `ContestedOutcomeRef`) | **not applicable** | **different bounded contexts.** WP-7B-R1 touches **Election** and **Audit/Retention** only; the ownership questions live in **Adjudication** and **Contestation** |
| The integration-path decisions | **not applicable** | same reason — **no event, no crossing, no published language is involved** |
| WP-4B / 4C / 4D | **not applicable** | no shared file, no shared port, no shared concept |
| **Q-2 — the Evidence Preservation Window's anchor** | ⚠️ **UNRESOLVED — and it is the reason for the work** | **See below. This is the one dependency that could look like a blocker and is not** |
| **Constitutional Policy 2** (the three durations) | **accepted** | unchanged and untouched — the durations still arrive through `EvidencePreservationDurations` |
| **AP-1 (fail closed) · AP-2 (one home per parameter)** | **accepted** | preserved by *behaviour unchanged*; the *"no anchor ⇒ still open"* rule is not altered |
| **R-59 (Slice 7B) · R-65 / R-66 (Slice 7C)** | **accepted** | R-60 states this is *"separate from Slice 7B and not a reopening of it"* |
| Deptrac's approved layer model | **accepted** | `ElectionInfrastructure: [ElectionDomain, ElectionApplication, Shared]` already permits the intended shape |
| Any unresolved ADR | **none found** | no ADR governs the anchor's *location*; **Q-2 governs its *value*** |

> ### ⚠️ Why the open Q-2 decision does not block this authorization
>
> **R-60's stated purpose is precisely to survive Q-2's absence:** *"Q-2's eventual ruling then **swaps an implementation** rather than **editing service behaviour**, and the interim policy becomes a named, replaceable thing instead of a private method."*
>
> **The work does not require Q-2's answer. It makes Q-2's answer cheap to apply.** **Waiting for Q-2 would invert the ruling's own rationale** — the extraction is valuable *because* Q-2 is open, and loses its value the moment Q-2 rules.

## 3. Strategic DDD Verification

| Will implementation… | Answer | Basis |
|---|---|---|
| change bounded-context ownership | **No** | Election continues to own Evidence Preservation Window Resolution; Audit/Retention continues to own the deletion decision |
| change capability ownership | **No** | the capability is unchanged; only its internals are re-seated |
| introduce a strategic concept | **No** | **`EvidenceAnchorResolver` is a port over an existing private method** — it names something that already exists |
| modify the context map | **No** | no event, no crossing; **Adjudication still supplies MAD as configuration and is never called** |
| modify published language | **No** | nothing is published |
| invalidate an accepted ADR | **No** | none found that governs this |

## 4. Tactical Readiness

| Check | Result |
|---|---|
| Aggregate boundaries known | ✅ **none are involved.** The PM/window is a value object plus an application service; no aggregate is touched |
| **Ports defined** | ✅ R-60 names the port: **`EvidenceAnchorResolver`** |
| **Adapter strategy defined** | ✅ **by an in-context precedent one slice old:** `EvidencePreservationDurations` (port, `Election/Application/Port/`) → `ConfiguredEvidencePreservationDurations` (`Election/Infrastructure/Config/`) → bound in `ElectionServiceProvider`. **`TemporaryDefaultAnchorResolver` follows the same shape, and Deptrac's approved model already permits it** |
| Behaviour-preserving objective stated | ✅ **"behaviour unchanged"**, in the ruling's operative text |
| Implementation objective unambiguous | ✅ move the three-candidate ordering — `results_published_at` → `end_date` → `archived_at` — behind the port, unchanged |

**One observation, recorded and not a blocker:** **R-60 specifies *what*, not *where*.** **The placement is derivable rather than open** — the sibling precedent and the Deptrac layer rules determine it — so this is a detail for the implementation plan, not a missing prerequisite.

## 5. Governance Readiness

| Check | Result |
|---|---|
| **Authorization exists only as an ARB act** | ✅ **and it does not exist yet.** R-60 is **Planning Governance · Approval**, *"opened, not delivered"* — the classification was corrected by the issuing authority to make exactly this distinction |
| **Acceptance criteria exist** | ✅ behaviour-preservation, evidenced by the four named suites staying green unmodified |
| **Definition of Done exists** | ✅ the house DoD: RED → GREEN · `composer merge-gate` PASS · **triple qualification** · developer guide · **STOP for ARB slice acceptance** |
| **No additional architectural commission required** | ✅ **none.** R-60 already contains the design decision |

## 6. Risk Assessment

| Kind | Level | Why |
|---|---|---|
| **Architectural** | **very low** | a port over an existing private method; no boundary, no concept, no crossing |
| **Tactical** | **low** | one method relocated behind an interface, with a precedent to copy |
| **Integration** | **low** | one container binding. **The failure mode is loud** — an unbound port fails at resolution, not silently |
| **Governance** | **low** | one authorization; no ADR, no frozen artifact, no published language |
| ⚠️ **Verification** | **moderate, and worth naming** | **`composer merge-gate` does not execute `tests/Feature/Audit/`** (recorded at 7C's acceptance). **`AuditCleanupTest` is part of this slice's behaviour-preservation harness, so the gate's PASS will not cover it** — it must be run directly. **A property of the verification architecture, not of this slice** |

## 7. Decision

> # ✅ Option A — **WP-7B-R1 is READY FOR AUTHORIZATION**

**Evidence supporting the conclusion:**

1. **Scope, boundary, exclusions and acceptance boundary are all explicit** — three of the four in R-60's own operative text, the fourth in existing test suites.
2. **No unresolved dependency applies.** The three ownership questions and the two integration-path questions belong to other bounded contexts and share nothing with this work.
3. **The one unresolved item — Q-2 — is the work's rationale, not its blocker**, on the ruling's own reasoning.
4. **No strategic change is implied** on any of the six checks.
5. **Tactical work can begin immediately**: the port is named, and the adapter shape is fixed by a one-slice-old in-context precedent plus the approved Deptrac model.
6. **The only missing artifact is the authorization itself.**

**Recorded for the authorizing act, not as conditions this review imposes:** the **behaviour-unchanged constraint** should be named in the ruling as the acceptance boundary, and the verification note in §6 means *merge-gate PASS alone will not evidence behaviour preservation* — `AuditCleanupTest` must be run and reported directly.

---

**Traceability:** **R-60** (the opening ruling and its operative text; classification corrected to **Planning Governance**) · **R-59 · R-65 · R-66** · **Constitutional Policy 2** · **AP-1 · AP-2** · `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php` (`anchorOf()`, the extraction target) · `.../Application/Port/EvidencePreservationDurations.php` · `.../Infrastructure/Config/ConfiguredEvidencePreservationDurations.php` (the adapter precedent) · `.../Infrastructure/Providers/ElectionServiceProvider.php` · `deptrac.yaml` §ElectionInfrastructure · `tests/Feature/Contexts/Election/EvidencePreservationWindowResolutionTest.php` · `…/EvidencePreservationDurationsTest.php` · `tests/Unit/Contexts/Election/Domain/EvidencePreservationWindowTest.php` · `tests/Feature/Audit/AuditCleanupTest.php` · `engineering/verification/commissions/2026-08-02-programme-authorization-matrix.md`. **Verification only — no ruling issued.**
