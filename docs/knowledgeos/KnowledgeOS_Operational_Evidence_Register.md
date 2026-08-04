# KnowledgeOS — Operational Evidence Register

| | |
|---|---|
| **Kind** | ⭐ **OPERATIONAL EVIDENCE REGISTER** — the Operational Evidence stream's record. ⛔ ***Append-only · entries are never edited · behavioural observations with provenance, never conclusions*** |
| **Authority** | ⚠️ **Generated — evidence for the Decision Authority; the DA accepts or rejects, never this register** |
| **Rule** | every entry: what was OBSERVED · which architectural claim it bears on · first-hand provenance · what it does NOT show. ⭐ *The strongest statement never exceeds the evidence* |

---

## OE-KOS-1 · WP-4B delivery planning under the adopted architecture *(2026-08-03)*

| | |
|---|---|
| **Event** | a real delivery-planning activity (WP-4B, conclude-to-issue seam) was executed **inside** the adopted architecture and the governance model — the platform's first observed real-work exercise since Phase V opened |
| **Provenance, verified first-hand** | `docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md` — type line read verbatim: *"delivery artefact. **Not a governance document · no architecture proposed · no ADR reinterpreted · no Board decision improved**"* · §8 heading read verbatim: *"WP-4C and WP-4D — **deliberately no plan produced**"* · six WP-4B verification reports in `engineering/verification/reports/` (2026-08-02..03) |
| **External review** | ARB-persona review 2026-08-03 — verdict: *"the first operational validation of the KnowledgeOS governance model in a real delivery planning activity"* |

### Observed behaviours *(the review's table, carried with its own grades)*

| Observation | Evidence |
|---|---|
| Architecture constrained delivery planning | ✅ demonstrated *(gap identified against existing architecture; no redesign)* |
| Governance prevented unauthorized scope expansion | ✅ demonstrated — ⭐ **WP-4C/4D plans REFUSED because authorization was absent** *(§8, verified)* |
| Implementation questions stayed implementation questions | ✅ demonstrated *(three missing producers classified "not green-able ≠ architecture problem")* |
| Architecture preserved during planning | ✅ demonstrated *(timestamp-marker choice: smallest implementation preserving the existing model, no new architectural state)* |
| Implementation completed | ❌ **not yet** |
| Runtime behavior validated | ❌ **not yet** |

### Which architectural claims this bears on

| Claim | Effect of OE-KOS-1 |
|---|---|
| ⭐⭐ **R-6 composite, edge `guides PRODUCT ENGINEERING`** — previously *"PARTIAL: protocol used; effect unmeasured"* | ⭐ **first RECORDED behavioural observation of the guides-edge working** — four constraint-obeying behaviours with provenance. ⚠️ *Still no unguided baseline; the edge is evidenced, not measured* |
| **Dashboard watch signal** *"a decision resolved against the catalog"* | ⭐ **the inverse observed: decisions resolved WITH the constraints** — the signal machinery works in both directions |
| **The back-edge** *(evidence → platform change)* | ⛔ **NOT traversed by this entry** — no platform change occurred. *This entry is the guides-edge, not the improvement-edge; conflating them would repeat the level-mixing class* |

### ⚠️ Candidate methodology observation *(n=1 — recorded, NOT promoted)*

The review proposes a standing delivery gate: *implementation question → already answered by architecture? → implement, else escalate.* ⛔ **The methodology is FROZEN and ES-006.1 forbids promotion from a single occurrence.** Recorded here as **MO-1** with its trigger: *if the gate-shaped judgment recurs in WP-4C/4D or later slices, the recurrence — not this entry — reopens the question through the ladder.* ⭐ *Checked before recording: the EDM's stopping rule ("which existing decision does this extend?") is the same move at the decision level; a delivery gate may prove to be its delivery-facing corollary rather than a new rule.*

### Also recorded *(interpretation, not adopted)*

The review's four-capability reading *(Strategic Discovery · Architecture Governance · Delivery Governance · Implementation Guidance)* — converges with the three-capability workflow observation, adding a delivery face. **n=1 each; vocabulary unadopted.**

---

> **⛔ Register is append-only. This entry is evidence, not a conclusion. Acceptance belongs to the Decision Authority.**

---

## OE-KOS-2 · WP-4B RED-ratchet implementation slice *(2026-08-03 — first entry under the five-section template)*

### 1 · Observation
A real implementation iteration (WP-4B, conclude-to-issue seam) was executed in RED-ratchet form: **K2 isolated as the slice's single behavioural invariant** *(concluded-but-unissued → redrive ⇒ exactly one determination)* with K1/K3/K4 reclassified as its supports · the **"no producer logic in the seam"** constraint named explicitly **where the current tests cannot detect its violation** · **implementation cost recorded BEFORE being paid** *(an 11-parameter private constructor rebuilt at ~8 sites means "add two fields" ≠ small)* · **an honest stop at a clean boundary** *(the sixth constructor argument is silently ignored by PHP — GREEN's first step must add the parameter, so the slice was left RED rather than falsely green)* · the dev-guide reminder honored as part of done.

### 2 · Provenance *(verified first-hand this pass)*
`docs/plans/20260803-1600-wp4b-conclude-to-issue-seam-delivery-plan.md` — read verbatim: *"K2 is not one keystone among four — it is the behavioural invariant of the slice"* · *"⛔ No producer logic in the seam"* · the 11-parameter constructor across `admitEvidence`/`submitToAuthority`/…/`open()`/`reconstitute()` · *"the issuance spy passed as a sixth is silently ignored by PHP"* — and git commit **`51fdbfb8b` `test(adjudication): WP-4B RED ratchet — port introduced, harness corrected, next absence reached`** *(today)*.

### 3 · Architectural claims affected
⭐ **R-6 `guides product engineering`: SECOND formal behavioural record — n=2** *(planning obeyed constraints in OE-KOS-1; implementation now obeys them under RED discipline)*. ⚠️ **OE-KOS-1's two ❌ rows remain ❌** — the slice is deliberately RED; implementation is NOT complete and runtime is NOT validated. ⛔ **Still not the back-edge** — no platform change occurred.

### 4 · Interpretation *(marked — candidate explanations only)*
The review reads this as delivery-mode maturity and proposes a possible reusable kind, **"Implementation Finding"** *(constructor fan-out · test blind spots · deferred refactoring — neither architecture nor operational evidence)* — **n=1, watch only**. Its four-kinds table *(architecture / implementation constraint / operational finding / delivery plan)* recorded as interpretation, unadopted.

### 5 · Promotion decision
⛔ **Left to the Decision Authority.**

---

## OE-KOS-3 · Developer-guide obligation skipped; safety-net blind spot found and fixed *(2026-08-04)*

### 1 · Observation
The Developer-Guide Definition of Done was **skipped across every engineering-tooling step** (metrics tool v1–v3 · two collectors · conformance suite — zero guides written), and the Stop-hook safety net stayed silent for a **structural** reason: its area derivation matched only `app/Contexts/`, `app/`, `database/migrations/` — **`scripts/**` matched no pattern, so the changed files produced zero code areas.** ⭐ **The gap was discovered by USER AUDIT, not by the platform.** Remediation same-turn: 4 guides written (`developer_guide/engineering_observations/00–03`) · hook extended (`scripts/<seg>/` pattern + aliases) · fix verified twice (real log: adjudication nudged, engineering_observations matched-silent · synthetic log: `NUDGE: engineering_observations`).

### 2 · Provenance *(verified first-hand)*
Hook source read (pre-fix patterns) · `.claude/runtime/2026-08-04-files.log` contains the `scripts/` entries (the log worked; the derivation didn't) · `developer_guide/engineering_observations/` did not exist before 2026-08-04 · both verification runs' output captured.

### 3 · Architectural claims affected
⭐⭐ **The BACK-EDGE, formally traversed for the first time — at the INSTRUMENTATION level:** observed deficiency → recorded evidence → **platform-machinery change with records** (the hook is engineering-platform instrumentation). Graded honestly: **formal n=1, instrumentation-level** — not governance canon; the dashboard's informal n≈3 for canon-level changes stands separately. · **DG-1: first counted instance, and it is a FAILURE-MODE instance** (implementation shipped without guidance; the net had a hole; the obligation was also skipped while the net was silent — the rule binds regardless of reminders). · Consistent with Run 1's finding: observed governance is preventive — and preventive nets fail structurally at scope boundaries.

### 4 · Interpretation *(marked — candidate explanations only)*
Safety nets built for one area class (app/) go blind when work moves to a new one (scripts/) — candidate lesson: **when work enters a new area class, check instrument coverage.** Also: reminder-coverage must track obligation-scope, or the obligation silently narrows to what the net can see. n=1 each; watch material.

### 5 · Promotion decision
⛔ **Left to the Decision Authority.**

---

## Register rules — REV 2 *(review 2026-08-03; OE-KOS-1 untouched per append-only discipline)*

### ⭐ The normalized entry template *(binding from OE-KOS-2 onward)*

**The review's refinement, accepted: an entry mixes three epistemic kinds unless the sections are fixed. Every future entry uses exactly these five sections:**

| Section | Purpose |
|---|---|
| **1 · Observation** | what objectively happened — behaviour only |
| **2 · Provenance** | first-hand verified evidence *(artifact paths · verbatim lines · reports)* |
| **3 · Architectural claims affected** | which hypotheses/edges this bears on — *and which it does NOT (the level-mixing guard lives here)* |
| **4 · Interpretation** | candidate explanations — **clearly marked, never mixed into §1** |
| **5 · Promotion decision** | ⛔ **always the same text: left to the Decision Authority** |

*(OE-KOS-1 already carries all five concerns; they were not yet separated into fixed sections. It stands as written — the template normalizes forward, not backward.)*

### ⭐ The two streams — and the check-before finding

| Stream | Nature | Home |
|---|---|---|
| **A · Operational Evidence** *(what happened?)* | append-only, written per event | **this register** |
| **B · Learning Candidates** *(what recurring pattern appears?)* | ⛔ **generated PERIODICALLY from Stream A · reviewed by the DA · NEVER written directly** | ⭐ **check-before: the home ALREADY EXISTS — Pattern Cards (`EPC-nnn`) + the Pattern Evidence Register** *(reserved namespace `knowledge/evidence/`, trigger "PB-004 retrospective")*. **Stream B is that machinery finally receiving its input feed — nothing new is created; the register's growth is what arms the existing trigger** |

⭐ *The separation's purpose, in the review's words: it prevents this register from slowly becoming another architecture document. Patterns are extracted FROM entries by a distinct act, after several exist — never written INTO them.*

### The review's ARB verdict *(recorded for the docket; enacted by nobody here)*

**Approved:** the register · append-only · provenance-before-recording · observation ≠ authority · explicit "what this does not show" · the level-mixing guard. **Deferred:** no methodology from OE-KOS-1 *(MO-1 stands at n=1)* · "four capabilities" stays unadopted · **no governance rule extracted before multiple OE entries exist.**

### ⭐ The standing expectation

**No new architectural artifacts. The register grows: OE-KOS-2** *(next real delivery slice — WP-4B implementation is the natural candidate)* **· OE-KOS-3 · … Pattern extraction begins only when recurrence exists, through the EPC machinery, by the DA's review.** ⛔ *And entries are never created to fill the register — only real operational events qualify.*

---

## Register rules — REV 3 *(second review 2026-08-03; both reviews' verdicts recorded)*

### ⚠️ MO-1's lifecycle — the challenge accepted

**Methodology observations do not belong to this register permanently** — evidence answers *"what happened?"*; methodology answers *"what recurring behaviour has earned promotion?"* — different lifecycles. **Disposition: MO-1 is HELD here only as staging, and MIGRATES to the Pattern Evidence Register when that machinery is armed.** ⛔ *The register must never gradually become the methodology.*

### ⭐ Recurrence made explicit — a POINTER view, not new machinery

*(Per the refinement: recurrence should be visible, not implicit. No thresholds are policy today. This table STAGES for the EPC machinery and migrates with MO-1.)*

| Pattern candidate | Observations | Status |
|---|---:|---|
| **MO-1** *(delivery decision gate: answered-by-architecture? → implement/escalate)* | **1** *(OE-KOS-1)* | **Recorded** |
| **IF-1** *(“Implementation Finding” as a kind: constructor fan-out · test blind spots · cost-before-paying)* | **1** *(OE-KOS-2)* | **Recorded** |
| **DG-1** *(implementation accompanied by developer guidance — row joins per the pre-authorized plan, at this genuine entry)* | **1** *(OE-KOS-3 — a FAILURE-MODE instance: obligation skipped + net blind)* | **Recorded** |

### ⭐ Check-before finding on the proposed principle elevation

*"The strongest statement never exceeds the evidence"* — proposed for platform-wide elevation "later" — ⭐ **already exists as a standing rule in the runtime binding** (`.claude/CLAUDE.md`, the final rule). *Elevation to platform canon would be a governance act; recorded for the docket, nothing elevated here.*

### The two reviews' ARB verdicts *(recorded for the docket; enacted by nobody here)*

**Review 1 approved:** register · append-only · provenance-first · DA separation. **Deferred:** MO-1 promotion · four-capability reading · any rule before multiple entries. **New observation recorded (n=1, unadopted): two complementary feedback loops** — Discovery Loop *(creates architecture)* · Operational Learning Loop *(validates and improves it)* — *converges with SP-5 and the three-capability observation.*
**Review 2 verdict: "approved architectural maintenance, not architectural discovery"** — forward normalization · EPC reuse over duplication · clean OE/pattern separation · no new artifacts. ⭐ *Its caution adopted operationally: `CONTEXT.md` records state + pointers only — canonical content lives in canonical documents.*
