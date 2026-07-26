# EPIC-004 Q-2 Resolution Package — Business Policy for the Contestation Window Family

**Kind:** business-policy resolution package — the last governance gate before implementation authorization (per the recorded standing sequence: architecture ✓ → successor ADRs ✓ → **Q-2 values** → refinement/implementation). Not implementation; not architecture redesign.
**Status:** PROPOSED — every interim value below is a **draft for ARB ratification**, marked INTERIM, with a mandatory stakeholder review requirement. Nothing here is a permanent business rule; the package deliberately does not pretend the business numbers are settled.
**Immutable inputs:** the frozen chain (EPIC-004B..J) · EPIC-004K (APPROVED, agenda resolved) · ADR-T21/T22/T23 (issued) · the four Constitutional Policies (EPIC-003) · the ruled horizon-expiry policy and the armed `DeterminationFinalized` reversal condition.
**Arithmetic note (deliberate correction, recorded):** the commissioning text rendered the derivation with "×"; the approved form (EPIC-004K §Q-2; Constitutional Policy 2) is **additive** — durations compose by sum, and this package derives accordingly. A multiplicative composition of durations would be dimensionally meaningless; the correction is noted so it cannot read as silent deviation.

---

## 1. Contestation Window (CW)

- **Configurable parameter:** `contestation_window` — per election type, per target type (election result · determination), attached to the election instance at creation (Policy 2's future-proofing: policy varies; architecture doesn't).
- **Interim defaults (real elections):** **30 days** from result publication (target: election result) · **30 days** from issuance (target: determination).
- **Rationale (evidence-grounded, honestly thin):** 30 days is the **only dispute-relevant duration the existing system ever operationalized** (the legacy per-voter audit retention). Adopting it as the *window* aligns the interim policy with the one number that has operational history — while the EPW arithmetic (§4) now extends actual evidence retention far beyond it, resolving the old contradiction in the direction the ARB ruled (retention serves the window, not vice versa). The determination-window symmetry (same 30 days) carries no independent evidence; symmetry is the least-assumptive interim choice.
- **Demo elections:** **exempt** — consistent with the implemented constitutional-enforcement exclusion for demo elections; no window, no preservation obligation beyond existing demo-reset practice.
- **Stakeholder review requirement:** REQUIRED before any live constitutional use — election-type differentiation (board vs. referendum-class vs. emergency) is expected to replace the flat default; the illustrative differentiation recorded at Policy-2's ruling (90/365/30-day classes) is the natural starting agenda.

## 2. Maximum Adjudication Duration (MAD)

- **Configurable parameter:** `max_adjudication_duration` — per election type; consumed as the APM's adjudication-horizon timer (EPIC-004K §7, PM-8).
- **Interim default:** **60 days** from process opening.
- **Rationale — flagged as this package's weakest number:** **no implemented business evidence exists for MAD anywhere in the record** (the transport park-deadline is 60 *minutes* and is not business policy). The interim value is a deliberately conservative-long placeholder: premature expiry of a legitimate constitutional deliberation is the worse failure mode than a long horizon, and K1 forbids automating our way out of a too-short one. **Priority item for stakeholder ratification.**
- **Operational consequences:** *PM horizon* — a process not concluded within 60 days transitions to Expired (ruled policy, §6). *Evidence retention* — MAD is the middle term of the EPW sum; lengthening MAD lengthens preservation for every election of that type. *Finality calculation* — MAD does **not** enter finality directly (finality follows the determination's own CW); it bounds how late within the preservation window a determination can still be produced, which is exactly why it appears in the EPW sum (§4).

## 3. Legal Safety Margin (LSM)

- **Configurable parameter:** `legal_safety_margin` — per election type (expected org-invariant in practice).
- **Interim default:** **30 days.**
- **Rationale — why the term exists at all:** the window and the horizon measure *process* time; the margin absorbs *world* time — challenges filed at the boundary but in transit, notice and service lags, jurisdictional review of whether a filing was timely, and the gap between "no challenge can be initiated" and "we are legally certain none was." Deleting the term (removal test) would make evidence destruction lawful at the exact instant a boundary dispute about timeliness is most plausible — the margin is insurance priced in days.

## 4. Retention arithmetic — formal derivation (additive, per the approved form)

**Evidence Preservation Window (per election instance):**

```
EPW(election) = CW(election-result) + MAD + LSM
             = 30 + 60 + 30           (interim, real elections)
             = 120 days from result publication
```

**Demonstration that each term is load-bearing (the derivation, not just the formula):**
1. A challenge may be filed on the window's **last day** (day 30) — timeliness is governed by filing time, so a timely-filed challenge is adjudicated even though the window closes mid-proceeding. *(This is not a new rule; it is what the +MAD term means — the arithmetic exists precisely so evidence survives adjudications that begin at the window's edge.)*
2. That adjudication may lawfully run to the horizon — +60 days (MAD). Its evidence must exist throughout (Retention Invariant: evidence for a permissible challenge never expires while the challenge can be initiated **or resolved**).
3. Legal certainty that no further timely filing exists (boundary/transit/timeliness disputes) — +30 days (LSM).
4. **Derived determination-finality term:** finality(determination) = issued-at + CW(determination) = issued-at + 30 days, **provided no challenge against the determination is open at closure** — a challenged determination's finality waits on that adjudication's own conclusion (recursion bounded by the same arithmetic applied to the new proceeding).
5. **Consistency check against the old contradiction:** legacy per-voter records at 30 days vs. security events at 730 — the interim EPW (120 days) supersedes the 30-day deletion for everything the Retention Invariant covers; the 730-day security-event retention is untouched (its own concern, longer than EPW, no conflict).

## 5. Finality policy — confirmed, validated against the issued record

- **Trigger:** closure of the determination's contestation window with no open challenge (§4.4). **Realization:** temporal business policy — not a public command; no actor expresses finalization, time does. **Executor:** the platform's scheduled policy-evaluation pattern → invokes the aggregate's `finalize()` through its transactional boundary.
- **Consistency validation:** **ADR-T8** ✅ — the evaluator coordinates nothing across contexts; it is a scheduled policy over one aggregate's records, not loop coordination or compensation. **ADR-T21** ✅ — untouched; finality is downstream of the loop head. **ADR-T22** ✅ — finalize is a state transition only; the ruling-bearing event is never altered (Final emits nothing — the frozen silence stands). **ADR-T23** ✅ — finalization involves no legitimacy/sufficiency judgment; nothing decides, so nothing usurps the authority. **DMT** ✅ — the third branch stays closed: the dormant `finalize()` has its modeled intention (temporal), trigger (window closure), and executor (the policy evaluator); no new dormancy is created.

## 6. Horizon expiry — formalized (no redesign; the ruled policy, stated operationally)

1. A process reaching MAD without conclusion transitions to **Expired** — a recorded terminal fact, never a silent disappearance.
2. The challenge **returns to Contestation** for disposition under Contestation's own rules (its lapse/re-route vocabulary; the disposition is not Adjudication's to define).
3. **A late authority decision is never honored** — a decision arriving for an Expired process dead-letters as a conflict via the existing translation discipline (`PermanentFailure` family); no revival path exists (P3: no post-hoc resurrection of a closed proceeding).
4. Expiry within a still-open contestation window does not consume the window: a timely re-raise (if Contestation's rules permit) opens a **new** process with a fresh horizon — the EPW arithmetic already covers this (the re-raised adjudication is bounded by the same MAD inside the same preservation window only if re-filed within CW; a re-raise after CW closes is untimely by definition).

## 7. `DeterminationFinalized` — armed, not activated (confirmed)

The reversal condition **remains armed and is not activated by this package**. **Evidence required before activation (recorded):** a *designed* consumer that must observe finality — the concrete candidate being the retention machinery, when its design exists (a candidate is not a consumer; the frozen condition requires the consumer as evidence). Activation routes through ARB review with that design in hand, per №6's condition verbatim.

## Parameter table (the package's normative core — all INTERIM)

> **Standing qualification (ARB challenge, accepted and recorded before ratification):** **these values are implementation bootstrap defaults, not constitutional defaults.** They exist only to unblock implementation and carry no implication that they represent final governance policy. The earlier ruling ("structure now, values later") is honored in substance: what the ARB ratifies here is the *bootstrap*, and the constitutional values remain the stakeholder review's to set — the review requirement on every row is the mechanism, this sentence is its meaning.

| Parameter | Scope | Interim default | Evidence grade | Stakeholder review |
|---|---|---|---|---|
| `contestation_window` (election result) | per election type | 30 days from publication | Weak-but-real (the one operationalized legacy number) | REQUIRED — type differentiation expected |
| `contestation_window` (determination) | per election type | 30 days from issuance | Symmetry only | REQUIRED |
| `max_adjudication_duration` | per election type | 60 days from opening | **None — placeholder; weakest number** | REQUIRED — priority |
| `legal_safety_margin` | per election type | 30 days | Reasoned insurance, no operational history | REQUIRED |
| **EPW (derived, not set)** | per election instance | **120 days** | Follows from the above | Follows |
| Demo elections | — | exempt | Implemented exclusion precedent | Confirm at review |

## Traceability

Every section traces to: EPIC-004K §7/§Q-2/§15 (the three load-bearing consumers) · Constitutional Policy 2 and its Retention Invariant (EPIC-003 §THE FOUR DECISIONS) · the ruled horizon-expiry policy and armed reversal condition (EPIC-004K §DECISION AGENDA RESOLVED) · ADR-T8/T21/T22/T23 · the DMT closure (EPIC-004H → EPIC-004K). No frozen artifact or issued ADR is modified; no architecture is introduced; no code exists here.

## Self-review

Interim values proposed, never asserted as permanent — each with rationale, evidence grade, and a mandatory stakeholder review flag; the weakest number (MAD) is named as such rather than dressed up ✅ · the derivation is demonstrated term-by-term with each term's removal consequence, and the arithmetic correction (additive, not multiplicative) is recorded openly ✅ · finality validated against all five named authorities (T8/T21/T22/T23/DMT) ✅ · horizon expiry formalized without redesign; the one boundary case (expiry inside an open window) is resolved *by the existing arithmetic*, not by a new rule ✅ · the reversal condition stays armed with its activation evidence recorded ✅ · governance principles held: nothing dormant created (DMT), no silence undefended (ASP), every element derived from the issued record (ADP), no aggregate/repository surface touched (APP/RMSP), no VO invented (VODP), and the criteria discriminated — demo exemption, the non-entry of MAD into finality, and the multiplicative-form correction are all refusals the package made ✅.

---

## Implementation Authorization Recommendation (exactly one, as commissioned)

> **Implementation authorization recommended** — conditional on the ARB's ratification of this package's interim values.

**Evidence:** every architectural gate is closed (chain frozen · EPIC-004K approved · ADR-T21/T22/T23 issued · horizon-expiry ruled · reversal condition disposed); this package supplies the last missing inputs (the three parameters and their derivation) in ratifiable form; the remaining open items (§15 residue: Contestation's failure-declared disposition, the armed event, advisory computation) are all owned, none blocks refinement, and none is a business-policy gap. **No additional business decision is required before authorization beyond ratifying the interim table above** — and the table is designed so ratification is a single per-item act.

**Stop condition: STOP.** The Q-2 Resolution Package and the recommendation are complete. No refinement, no implementation planning, no code — await explicit ARB approval of the package (per-item ratification of the parameter table) and, upon it, the ARB's implementation authorization.

---

## RATIFIED · AUTHORIZED · TRANSITION RECOGNIZED (ARB, 2026-07-26 — three explicit per-item rulings)

1. **The bootstrap table is RATIFIED.** ARB interpretation, recorded verbatim in substance: *the ARB ratifies the interim parameter table solely as implementation bootstrap defaults. These values authorize implementation and testing but do not establish constitutional policy. Final constitutional values remain subject to stakeholder review and may replace the bootstrap defaults without requiring architectural redesign.* (Grounds recorded: the accepted qualification changed the ratification's meaning materially — the ARB is not ratifying permanent windows/durations/margins; no legislation, organizational policy, or constitutional document in evidence fixes different values, so no per-value change was warranted; withholding would delay implementation without governance value.) Mandatory stakeholder review stands on every row; MAD remains the flagged priority.
2. **Implementation authorization is GRANTED — Refinement Scope Only.** ARB wording, recorded verbatim in substance: *the ARB authorizes entry into the Architecture-to-Implementation Refinement phase. This authorization permits preparation of implementation artifacts — including the implementation roadmap, work-package decomposition, dependency analysis, ADR sequencing, acceptance criteria, conformance strategy, and test strategy. It does NOT authorize production code, infrastructure changes, or implementation work. Such work may begin only after the refinement artifacts have been completed, reviewed, and explicitly approved by the ARB.*
3. **The governance transition is FORMALLY RECOGNIZED:** architecture governance for this scope is **complete**; implementation governance is the next discipline. No further architecture documents on this track absent a triggered reversal or falsifiability condition; the next artifact family is implementation-governance artifacts. The ARB's recorded progression: Architecture Approved → ADR Successors Issued → Q-2 Bootstrap Policy Ratified → Implementation Authorization (Refinement Only) → Refinement Artifacts Approved → Implementation Begins.

**Q-2 is CLOSED** (structure ruled 2026-07-26 · bootstrap values ratified 2026-07-26 · constitutional values delegated to the standing stakeholder review). With it, every open gate named by the frozen chain and EPIC-004K is disposed.

---
*Inputs: EPIC-004K (APPROVED; §DECISION AGENDA RESOLVED) · ADR-T21/T22/T23 · EPIC-003 §THE FOUR DECISIONS (Policy 2 + Retention Invariant) · EPIC-004H (DMT) · legacy operational evidence (30-day audit practice; 730-day security events; demo exclusion precedent) — cited as evidence, nothing modified.*
