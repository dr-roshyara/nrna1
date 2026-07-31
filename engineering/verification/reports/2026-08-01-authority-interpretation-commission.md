# Authority Interpretation Commission

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** is every architectural conclusion derived from the **actual governing authority**, or from summaries and downstream documents? Interpretation discipline only — no implementation review, no redesign.
**Repository Integrity Gate:** ✅ PASSED.

> ## THE COMMISSION'S CENTRAL RESULT
>
> **ADR-T11 was not an isolated slip. Tracing citations to their sources revealed a pattern — and reading one primary text surfaced a BINDING authority that no previous commission had consulted at all.**
>
> That authority (**Constitutional Policy 3**) **reframes C-1, dissolves part of the earlier concern, and simultaneously converts C-6 from *"inaccurate"* into *"contradicts a binding rule."*** Both movements came from reading a document I had been citing second-hand for the entire session.

---

## 1. Authority inventory — primary vs secondary, as actually used

| Authority | Primary location | How I actually sourced it | Verdict |
|---|---|---|---|
| **ADR-T11** (anonymity) | `docs/adr/ADR-T-LOG-Tactical-Implementation.md:18` | ❌ charter paraphrase, until corrected | **was SECONDARY** — now read |
| **Constitutional Policies 1–4** | **`EPIC-003_Tactical_DDD_Entry_Assessment.md` §THE FOUR DECISIONS** | ❌ **`.claude/MEMORY.md:57`**, which itself says *"full text: EPIC-003…"* | **was SECONDARY for the whole session** |
| **Policy 3 — Custodial Constraint (CL-1/CL-2/CL-3)** | same, §180–183 | ❌ **never consulted at all** | **UNCONSULTED — the finding** |
| EPIC-004K §197 · §142 · §81 · §57 | `EPIC-004K…md` | ✅ read directly by line | **PRIMARY** |
| **EPIC-004K §11** (*"the process is orchestration, not an aggregate"*) | `EPIC-004K…md` | ⚠️ via `AdjudicationProcessStore`'s **docblock**, which cites §11 | **SECONDARY** — and it justified widening AT-EVT-001 |
| **PB-006** (*Registration ≠ Delivery*) | PB-006 record | ⚠️ via `InboxHandler` docblock + MEMORY | **SECONDARY** |
| **ADR-T3 / T4** (at-least-once ⇒ dedupe) | ADR-T log | ⚠️ via docblocks + MEMORY | **SECONDARY** |
| ADR-T16 · T5 · T21 · T22 | `ADR-T-LOG…md` | ✅ read directly (line 18–30) | **PRIMARY** |
| Roadmap §WP-6/§WP-7 | roadmap | ✅ read directly | **PRIMARY** |

**Six load-bearing authorities were sourced from docblocks, MEMORY, or my own earlier plans.** In each case a *downstream document that cites the authority* stood in for the authority.

## 2. Interpretation traceability matrix

| Conclusion I produced | Evidence | Governing authority | Sourced from | Classification |
|---|---|---|---|---|
| "The voter trail violates the anonymity invariant" | the 3-line log file | ADR-T11 | ❌ charter paraphrase | **UNSUPPORTED — withdrawn** |
| "Expiry concludes nothing" (Policy 4; 3 keystones encode it) | `expire()` sets no verdict | **Policy 4 Integrity Invariant** | ❌ MEMORY + my WP-2 plan | ⚠️ **supported, but was traced only now.** Primary text: *"automated integrity verification MAY detect anomalies; it SHALL NOT determine their significance"* — **the conclusion survives verification unchanged** |
| "EPW = CW + MAD + LSM" | §142 | **Policy 2** + §142 | ⚠️ §142 primary; Policy 2 via MEMORY | ✅ **confirmed by primary** — and see §3 for what the primary adds |
| "AT-EVT-001 may be widened because the process is not an aggregate" | guard failure | EPIC-004K §11 | ⚠️ a **code docblock** citing §11 | ⚠️ **AMBIGUOUS — the reasoning may be right, the sourcing is second-hand** |
| "Registration ≠ Delivery justifies consumer-side registration" | provider code | PB-006 | ⚠️ docblock + MEMORY | ⚠️ ambiguous sourcing; conclusion consistent with observed code |
| "Late decision ≠ redelivery" | §197 quoted | §197 | ✅ primary | ✅ **directly supported** |
| "Q-2 owns durations; the APM enforces" | §81 quoted | §81 | ✅ primary | ✅ **directly supported** |
| "The charter's *(anonymous)* annotation is inaccurate" | the log file | ⚠️ I cited no authority | ❌ my own reading | **NOW UPGRADED — see §3** |

## 3. Authority scope assessment — what the primary text actually says

### Constitutional Policy 3 — the unconsulted authority that reframes everything

Primary text (`EPIC-003…md:180–184`):

> **"the current code-to-vote linkage is an intentional design trade-off that supports custodial investigation and dispute resolution … it is NOT an architectural defect at this stage."**
> **CL-1:** *"the linkage SHALL exist solely for custodial investigation; it SHALL never be used during normal election processing."*
> **CL-2:** *"access SHALL require privileged administrative authority and be fully auditable — **every lookup must itself become evidence.**"*
> **CL-3:** reviewed when Self-Verifying Integrity becomes operational.
> **Vocabulary correction (binding):** *"the architecture **SHALL NOT** state 'the system is anonymous.' … administrative re-identification is possible through controlled custodial mechanisms."*

**Three consequences, and they pull in different directions:**

1. **A linkage is constitutionally ACCEPTED at Phase 1** — explicitly *"not an architectural defect at this stage."* My earlier alarm was aimed at a property the constitution had already ruled on. **This weakens the violation reading further than the last correction did.**
2. **But acceptance is BOUNDED by CL-1 and CL-2, and those are far more specific to this artifact than ADR-T11's three enumerated kinds.** The sharper questions become: is the trail written **during normal election processing** (CL-1 forbids the linkage being *used* then)? Is access **privileged and audited**, with **every lookup becoming evidence** (CL-2)? A world-readable file under `storage/logs` with no access-logging mechanism engages CL-2 directly.
3. **C-6 is upgraded from "inaccurate" to "contradicts a binding rule."** The charter states *"Candidate selections **(anonymous)**"* and, elsewhere, *"the system is anonymous"* in substance. Policy 3's vocabulary correction is **binding** and says the architecture **SHALL NOT** state that. **This is now supported by primary authority, not by my reading.**

### Constitutional Policy 2 — primary text adds a requirement I had treated as a config gap

> **Retention Invariant (binding):** *"evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved."*
> **"Consequence accepted with the ruling: the Contestation Window itself must be explicitly defined."**

**WP-7 readiness finding R-2 is therefore stronger than reported.** I classified the missing Contestation Window as a configuration gap; the primary text makes its explicit definition **a consequence accepted with a binding ARB ruling.** Same fact, higher authority.

### ADR-T11 — scope unchanged from the last commission

*"any aggregate, event payload, or projection"* · enforcement *"no `user_id` reconstruction"*. **Undefined with respect to log files** — and now visibly **narrower in relevance** than Policy 3's CL-1/CL-2, which speak directly to linkage use and access control.

## 4. Evidence independence review

| Observed fact | Survives interpretation change? |
|---|---|
| The file contains `user_id 10` → `candidate_id 5` → `vote_id 1` | ✅ **Yes** — a fact, independent of every ruling |
| Writers autoloaded with zero call sites | ✅ Yes |
| One file, demo tenant, 2026-02-19 | ✅ Yes |
| AT-Q7-001 scans contexts + messaging only | ✅ Yes |
| `votes` has no `user_id` | ✅ Yes |
| **"This violates the anonymity invariant"** | ❌ **No — an interpretation, withdrawn** |
| **"The `(anonymous)` annotation is merely inaccurate"** | ❌ **No — superseded upward by Policy 3** |

**Every observed fact survived two interpretation reversals unchanged.** *The conclusion is withdrawn; the evidence stands* — and the evidence has now supported three different readings without itself needing revision. **That is what makes evidence worth separating from interpretation.**

## 5. Interpretation classification

| Conclusion | Classification |
|---|---|
| Late ≠ redelivered (§197) · Q-2 owns durations (§81) · EPW formula (§142) | **Directly supported by authority** |
| Policy 4 forbids a timer→conclusion path | **Directly supported** — verified against primary text this commission |
| Charter must not claim anonymity | **Directly supported** — Policy 3's binding vocabulary correction |
| Contestation Window must be explicitly defined | **Supported through explicit inference** — a stated consequence of Policy 2's ruling |
| AT-EVT-001's widening rationale | **Ambiguous sourcing** — §11 read through a docblock |
| Whether the trail engages CL-1 / CL-2 | **Requires ARB interpretation** |
| Whether ADR-T11's scope reaches a log file | **Requires ARB interpretation** |
| "The trail violates anonymity" | **Unsupported — withdrawn** |

**No ambiguous finding has been forced into a supported conclusion.**

## 6. ARB decision packages

### Package C-1 (REVISED — the governing authority has changed)

| Field | Content |
|---|---|
| **Evidence** | one log file linking `user_id` → `candidate_id` → `vote_id`; writers autoloaded, zero call sites; world-readable path; no access-logging mechanism |
| **Governing authority** | **Constitutional Policy 3 (CL-1, CL-2)** — *primary*; ADR-T11 — *secondary relevance* |
| **Exact ambiguity** | ① Does writing the trail constitute *using* the linkage **during normal election processing** (CL-1)? ② Does an unprivileged, unaudited file satisfy CL-2's *"privileged authority … every lookup must itself become evidence"*? ③ Does ADR-T11's *"projection"* reach a log file? |
| **If permitted** | the trail is a Phase-1 custodial artifact; CL-2 then implies **access controls and lookup-auditing that do not exist** |
| **If not permitted** | the artifact is retired; AT-Q7-001's scope gap becomes an executable-architecture question |
| **Decision required** | Interpretation of CL-1/CL-2 against this artifact |

### Package C-6 (UPGRADED — now authority-supported)

| Field | Content |
|---|---|
| **Evidence** | charter states *"Candidate selections **(anonymous)**"* and *"votes cannot be linked"*; Policy 3 states administrative re-identification **is** possible |
| **Governing authority** | **Policy 3 vocabulary correction — BINDING:** *"the architecture SHALL NOT state 'the system is anonymous'"* |
| **Ambiguity** | **None on the rule.** Only whether the charter counts as *"the architecture"* |
| **Consequence** | Correct the wording to *"operational ballot secrecy, not cryptographic unlinkability"* |
| **Decision required** | Confirm the correction; **no interpretation of intent needed** |

### Package C-7 (NEW)

| Field | Content |
|---|---|
| **Evidence** | AT-EVT-001's widening was justified via §11 read through a code docblock |
| **Authority** | EPIC-004K §11 |
| **Ambiguity** | whether §11's primary text supports the widening as the docblock's paraphrase implied |
| **Decision required** | Re-verify against §11, then accept or reverse the widening (already pending) |

**No recommendation is offered on any interpretation.**

## 7. Governance process lessons

| Where the assumption entered | Mechanism |
|---|---|
| ADR-T11 | the **charter** paraphrased it; I trusted the paraphrase |
| Policies 2–4 | **MEMORY** summarised them — *and named the primary source in the same line I quoted* |
| §11 · PB-006 · ADR-T3/T4 | **code docblocks** cited them; docblocks are written to *explain* code, not to state law |

**The common mechanism, stated once:** every secondary source was **accurate**. MEMORY's Policy 4 summary matched the primary. The charter's ADR-T11 gloss was directionally right. **The failure was never inaccuracy — it was LOSS OF SCOPE AND ADJACENCY.** A summary preserves the sentence and discards its neighbours: MEMORY's line 57 held Policy 4 correctly **and** compressed Policy 3's CL-1/CL-2/CL-3 into a clause I read past for an entire session.

**Where authority verification did occur, it worked immediately** — §197, §81 and §142 produced no reversals, because they were read where they live.

**Where conclusions changed:** twice on the same artifact, both times upon reading a primary text. **Neither reversal touched a single observed fact.**

## 8. Permanent method recommendation

**One step, added to the existing chain rather than replacing it:**

```
Observation → Evidence → ► AUTHORITY VERIFICATION ◄ → Interpretation → Recommendation → Authority Decision
```

**The rule, stated so it is checkable:**

> **A conclusion that constrains, forbids or permits must cite the authority's own text, read at the authority's own location.** A docblock, a charter summary, a MEMORY line or a previous plan may *point* to an authority; **none may stand in for one.**

**And the corollary this commission earned, which is the part that would have saved it:**

> **When reading a primary authority, read its NEIGHBOURS.** Constitutional rules arrive in sets, and a summary preserves the item while discarding the set. Policy 3 sat beside Policy 4 for the whole session, unread, while being the authority most specific to the artifact under investigation.

**Practical test before asserting a constraint:** *can I quote it, and do I know what stands next to it?* If either answer is no, the conclusion is not yet supportable.

**Recorded as method, deliberately NOT promoted to a standard** (R-38/R-39: refinement closed; promotion needs cross-slice evidence). Filed with the other un-promoted observations.

---

**Traceability:** **PRIMARY sources read for this commission:** `EPIC-003_Tactical_DDD_Entry_Assessment.md` §THE FOUR DECISIONS (Policies 1–4, CL-1/CL-2/CL-3, the binding vocabulary correction, the Retention Invariant) · `ADR-T-LOG-Tactical-Implementation.md:18` (ADR-T11) · `EPIC-004K` §57/§81/§142/§197 · roadmap. **Secondary sources identified as such:** `.claude/MEMORY.md:57` · `CLAUDE.md` charter · `AdjudicationProcessStore` and `InboxHandler` docblocks · prior WP plans. **No implementation reviewed; no code changed; no interpretation decided.**
