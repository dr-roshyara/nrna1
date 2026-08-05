# Architecture Knowledge Transfer (AKT) — Part 9

**Immediate Next Steps**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 9 of 10 + Appendix** |
| **Part** | **Part 9 — Immediate Next Steps** |
| **As of** | **2026-08-01, end of day** · branch `feature/pb003` · HEAD `622c515d4` |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | the next AI session · Principal Engineers · Architecture Review Board |
| **Prerequisite** | **Part 8** (the full ledger). This Part is the action list derived from it |

---

## 0. The shortest true answer

> ### **Engineering is prepared and waiting on ONE thing: dispose C-1..C-4 and issue the Slice 7C authorization.**
>
> ### **Then: RED (4 new tests + the C-3-gated amendment) → GREEN → VERIFY (`composer merge-gate` + dev guide + operational record) → ACCEPT → close WP-7.**
>
> **Then, if WP-8 is to be the next work package, DEFINE IT BEFORE AUTHORIZING IT.**
>
> ### **Everything else stays open and blocks nothing.**

**⛔ And the boundary that governs your first move:** **there is currently NO authorized engineering activity on WP-7.** *No implementation has been performed, and none may begin until authorization issues.* **If you are asked to "continue WP-7," the correct response is to prepare the disposition, not to write code.**

---

## 1. What is authorized RIGHT NOW

**Read this table before doing anything. It is the difference between working and violating.**

| Activity | Authorized? | Authority |
|---|---|---|
| **Slice 7C implementation (RED or otherwise)** | ⛔ **NO** | authorization not issued |
| **WP-7B-R1** — extract the interim anchor into an `EvidenceAnchorResolver` port + `TemporaryDefaultAnchorResolver`, **behaviour unchanged** | ✅ **OPEN** | **R-60** |
| **WP-4 RED** — APM wiring keystones | ✅ **OPEN** (G-2 decided) | the WP-4 plan |
| **WP-3 RED** — `ChallengeRouted` published language + correlation-mint relocation | ✅ **AUTHORIZED**, assessment complete | ARB, on WP-2 acceptance |
| **WP-5 implementation** | ⛔ **NO** — **plan awaiting EP-01 approval** | — |
| **Governance PREPARATION** (packages, evidence, verification, findings) | ✅ **always** | the standing preparation role |
| **Applying the ES-005 amendment** | ⛔ **NO** — needs its own explicit authorization | R-37 operational terms |
| **Creating anything new under `engineering/`** | ⛔ effectively **NO** — bug/link/typo only | **R-37 · R-38** |
| **Moving documents into the domain roots (Phase 2)** | ⛔ **NO** — prerequisite unanswered | *does R-37 bind `docs/`?* |
| **Minting R-65..R-71, or picking 7C's ruling numbers** | ⛔ **NO** — an authority act | see §3.2 |

> **⚠️ If you find yourself reasoning toward "I'll just start the obvious next slice" — stop.** *Authorization is slice-granular by ruling (**R-47**: "Scope is slice-granular: 7A ONLY — 7B and 7C are NOT authorized"), and it has been granted one slice at a time all the way through.*

---

## 2. Priority 1 — the ARB commission that unblocks the product

### 2.1 What to prepare (this is preparation, not decision)

**Four dispositions in one session. Nothing else is required for the product to resume.**

| # | Decision | What the ARB must settle | Recommended framing *(prepared, NOT pre-filled)* |
|---|---|---|---|
| **C-1** | **Authorization wording** | the *"deletion mechanics"* phrasing would **forbid 7C's own approved test** | **constrain HOW DELETION IS PERFORMED, not what business decision is made** |
| **C-2** | **Release governance** | who owns the announcement | ⛔ **authorizing implementation is NOT authorizing release** — name an owner or defer release explicitly |
| **C-3** | **Amendment of accepted tests** | 7C's RED **includes modifying existing PASSING tests** | **authorize the amendment EXPLICITLY and LIMIT it to what the criterion requires** |
| **C-4** | **Identifier allocation** | which R-numbers 7C's authorization and acceptance take | ⚠️ **see §3.2 — the input is incomplete** |

**⛔ Preparation rules that apply to the artifact you produce:**

- It is a **PACKAGE**, not a **RECORD.** **Blank decision templates. Never write *"is accepted."* Never pre-fill an outcome — not even the recommended one.**
- **Name the authority per decision.** *Slice authorization is **Execution Governance · ARB**. If any item turns out to be plan approval, that is **Planning Governance · DECISION AUTHORITY** — a different act.*
- **One transition per vote.** *If a decision spans two governance categories, it IS two decisions (**DD-1**'s lesson).*
- **Provide a branch for every REJECTION.** *A decision whose rejection spawns an unlisted decision is **not fully prepared** (**DD-2**'s lesson).*
- **Close with *"no further governance PREPARATION is required"*** — never *"no further governance act needed"*, because the four decisions **are** governance acts.

### 2.2 Two items the ARB may settle in the same session at zero cost to the product

| Item | Why it is worth including |
|---|---|
| **Adopt the three corrected states** (migration NOT STARTED · link recovery PARTIAL · KnowledgeOS purpose UNDECIDED) | ⭐ ***Adopting them prevents two open gates from being retired by a status table rather than by a ruling.*** **Does not block 7C** |
| **Define WP-8, or record it as undefined** | *"Close WP-7, open WP-8" currently names something whose scope no accepted artifact defines.* **Does not block 7C** |

**⚠️ Claim discipline on WP-8, already narrowed once in the record:** *"WP-8 is undefined" **overreached**.* **The evidence supports only: *no accepted artifact defining WP-8 was found in the sources examined*.** ***Absence in searched artifacts is not non-existence.*** **The honest consequence: if WP-8 is to become the next authorized work package, it should be defined before authorization — it is not a blocker.**

---

## 3. Priority 2 — two things to raise BEFORE the ruling is drafted

### 3.1 Nothing in §3 is a decision. All of it is input the ARB is missing.

### 3.2 ⛔ The R-number collision must reach C-4

**C-4 exists to allocate 7C's identifiers. Its stated input is incomplete.**

| Fact | Evidence |
|---|---|
| The register **ends at R-64** (35 rows) | `grep -oE "^\| R-[0-9]+" engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md \| tail -5` |
| **R-65..R-71 are already spoken for** as *approved-in-substance, unminted* governance conclusions, cited across **≥8 documents** | R-65 repository-is-a-workspace · R-66 documentation-by-DOMAIN · R-67 `engineering/`-is-cross-product-SCOPE · R-68 ES-005-resolves-by-rule · R-69 R-39-is-the-precedent · **R-70 the Artifact Classification Model** · **R-71 Placement Is Derived** |
| The 7C recommendation proposes **R-65 = authorization, R-66 = acceptance** | 7C pre-authorization verification |
| The recorded warning was **narrower than the problem** | it said *"R-61..R-64 already issued; never reuse R-61"* — **it did not detect R-65..R-71** |

**⛔ Do NOT resolve this by choosing numbers.** *Whether the unminted set is minted first, whether 7C takes a later range, or whether a separate series opens is the ARB's decision.* ✅ **Do: state the collision as C-4's input, with the reproduction command.**

### 3.3 The `C-n` identifier collision

**`C-1..C-4` means the 7C governance decisions AND, elsewhere in the same work package, the WP-7 enforcement constraints** (*C-1 = never define/default/clamp a duration · C-2 = config-key uniqueness · C-4 = construction exclusivity*).

> **So *"C-1 is the one constraint whose manual enforcement is insufficient"* and *"dispose C-1"* refer to DIFFERENT OBJECTS.** ✅ **Qualify the set every time you cite a `C-n`.** *Recorded as an observation; renaming either set is an authority act.*

### 3.4 Verify the C-1 *constraint* before claiming 7A closed it

**The C-1 **constraint** (*never define, default or clamp a duration*) was scoped as a **7A deliverable** and is the one constraint whose **manual enforcement is insufficient** — *the exact defect already occurred (`max(1,$days)`; 60 in two homes) and was **invisible to all four gates***.

✅ **Read the 7A GREEN report and confirm its present state.** ⛔ **Do not assume it shipped because 7A was accepted.**

---

## 4. Priority 3 — engineering work that IS authorized today

**If the ARB session cannot happen yet, this is the legitimate work available. In recommended order:**

| # | Work | Why this order |
|---|---|---|
| **1** | **WP-7B-R1 (R-60)** — extract the interim anchor into an `EvidenceAnchorResolver` port with a `TemporaryDefaultAnchorResolver`, ⛔ **behaviour unchanged** | **Already authorized · smallest surface · directly reduces recorded architectural debt · makes Q-2's eventual ruling a substitution rather than a redesign** |
| **2** | **WP-4 RED** — the Phase-10 keystones: *loop head fires (one process per routed challenge) · redelivery inert · registration resolves · correlation continuity (nothing minted) · malformed payload → permanent failure · no cross-context import* | Open, G-2 decided. **Report at the RED boundary before any production code** |
| **3** | **WP-3 RED** | authorized, pre-implementation assessment complete |
| **4** | **Automate the C-1 constraint** *(if 7A did not)* | the one gap with a **realized** defect behind it |

**⛔ For each of these, run the process, not just the code:** **EP-03 readiness review** (derive from the repo; ask only what cannot be derived) → **EP-01 plan + explicit approval** *(WP-5 is the reminder that a plan without approval authorizes nothing)* → **the 17 phases** → **EP-02 completion review** → **ES-004.3 closure synchronization.**

---

## 5. Priority 4 — the housekeeping act nobody has authorized but everybody needs

### 5.1 Re-synchronize the runtime state files

**`.claude/CONTEXT.md` is simultaneously right and stale** (Part 1 §7.5, Part 8 §0): header `Updated: 2026-07-30`; `Milestone: WP-4 open`; `Next action: WP-4 RED`; and Active-Work entries asserting *"three blocking gates"* and *"the WP-7 plan has never received EP-01 approval"* — **all consumed by R-43..R-64 the same day, with nothing marking them superseded.**

**`docs/implementation/PROGRAM_STATUS.md` is dated 2026-07-11** and reports *"between epics · next milestone EPIC-002."*

**Classification: a real ES-004.3 / R-41 synchronization gap.** **Correct owner: the next session that closes a slice** — *the minimum checklist binds every slice closure.*

> **⚠️ This is the highest-value low-risk act available, because every future session will otherwise re-derive the wrong state — as the first draft of this AKT's own Part 1 did.**

✅ **Do it as its own small act, under EP-01-Light, and say plainly that it is runtime synchronization — not a governance change.**

### 5.2 What NOT to do while housekeeping

⛔ **Do not** "fix" the `bounded-contexts.yaml` gap for `app/Contexts/Election` — *which of three near-homonyms is the bounded context is a modelling question* · **do not** consolidate `claude/` into `.claude/` · **do not** repair the 6 ambiguous links — *they need a human choice* · **do not** author any of the 47 never-written documents · **do not** mint numbers · **do not** create a principles document under `engineering/`.

---

## 6. What may safely stay open — and the reason it is safe

**Nineteen of twenty classified findings gate NO product code path, and `composer merge-gate` passes with all of them outstanding. That is the evidence, not the assertion.**

| May remain open while 7C runs |
|---|
| documentation migration (Phase 1 + Phase 2) · broken link recovery (53) · KnowledgeOS separation · **OQ-5** · the R-37 scope question · the unruled `cross-product + research` classification · the unminted R-65..R-71 · the ES-005 amendment · the 6 ambiguous references · the classification map · ENG-008/009/010/011 · the ES ratification batch · Q-FW-1 · Q-GG-1 · D-1..D-5 · the KnowledgeOS G-1 charter · PKS ARB-bucket OQs |

> ### **What remains before product resumes: ONE ARB commission, FOUR decisions, NO engineering prerequisites.**

---

## 7. The first ten minutes of the next session — a concrete checklist

```
[ ] 1. Read .claude/MEMORY.md, then today's .claude/sessions/YYYY-MM-DD.md
[ ] 2. Read .claude/plans/WP-7-retention-alignment.md — its STATUS LINE is authoritative
[ ] 3. Check the register tail:
       grep -oE "^\| R-[0-9]+" engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md | tail -5
       → if it still ends at R-64, NO 7C ruling has issued and nothing is authorized
[ ] 4. Read docs/publicdigit/WP-7C_Engineering_Readiness.md — C-1..C-4 and the execution contract
[ ] 5. Confirm the baseline yourself, do not assume it:
       composer merge-gate          (expect PASS · 266/665 · 0 failures)
       git status                   (expect clean)
[ ] 6. Treat .claude/CONTEXT.md as CORROBORATION ONLY — never as the conclusion
[ ] 7. Decide which of §2 / §4 / §5.1 the human is actually asking for — and say which
       one you are doing before you start
```

---

## 8. The four sentences to say back to the human at the start

1. **"Slice 7C is architecturally ready and governance-blocked on four dispositions — C-1 authorization wording, C-2 release governance, C-3 test amendment, C-4 identifier allocation. No engineering prerequisite remains."**
2. **"There is no authorized WP-7 engineering activity. What I can do without new authorization is WP-7B-R1 (R-60), WP-4 RED, WP-3 RED, or prepare the ARB package."**
3. **"Before any 7C ruling is drafted, the R-65..R-71 collision needs to reach decision C-4 — the register ends at R-64 and those numbers are already cited across at least eight documents."**
4. **"`CONTEXT.md` and `PROGRAM_STATUS.md` are stale; I will establish state from the plan Status lines and the rulings register."**

---

## 9. The failure modes most likely in the next session

| Trap | The correct move |
|---|---|
| **Reading `CONTEXT.md` and concluding "WP-4 RED is the next action"** | that line is stale — **but WP-4 RED does happen to be authorized.** *Get there by the right route, and say so* |
| **Treating "continue WP-7" as authorization to implement 7C** | **prepare the disposition; do not write code** |
| **Minting R-65/R-66 for 7C** | ⛔ **raise the collision; do not allocate** |
| **Re-preparing the exercised decision pack** | **R-46/R-47 → R-55 → R-56/R-58 → R-59/R-60 already consumed it** |
| **Re-litigating G-1 or A-1** | **disposed by R-44; 7A shipped the port and was accepted** |
| **Citing "146 green" or "WP-6 passed all gates"** | **149 green** — and **attach R-53's annotation** |
| **Writing a "Record" before a ruling exists** | **it is a PACKAGE** |
| **Fixing a finding you just raised** | **record → dispose → apply** |
| **Reporting *"migration complete"* / *"links fixed"*** | ⛔ **both would retire gates nobody ruled on** |
| **Producing a governance document instead of operational evidence** | ⛔ **a Phase III act that produces a governance artifact without operational evidence has FAILED THE MANDATE** |

---

## 10. The one-line standing instruction from the Charter

> ### ***Effort goes to PublicDigit engineering. No further governance document unless NEW EVIDENCE requires it.***
>
> **Per Charter §6.1, PublicDigit is where the evidence is generated.**

**And what to observe while doing real feature work:** *did the governance help? · **which rule PREVENTED a defect?** · **which rule created UNNECESSARY FRICTION?** · which capability is missing? · **which assumption turned out WRONG?***

---

## Traceability

**Primary sources (read at authoring):** `.claude/sessions/2026-08-01.md` (**the Next Steps section verbatim in substance**) · `docs/publicdigit/WP-7C_Engineering_Readiness.md` (**C-1..C-4 · PS-11 · the execution contract · expected RED scope · the stop condition**) · `engineering/verification/reports/2026-08-01-programme-state-convergence-package.md` (**twenty findings · exactly one blocks the product · PS-19 and its narrowing**) · `…-programme-state-verification.md` (the three corrected states) · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (**R-44 · R-46 · R-47 slice-granularity · R-53 · R-59 · R-60**, and the verified register tail at R-64) · `engineering/verification/reports/2026-08-01-classification-model-approval-record.md` (**R-65..R-71 unminted**) · `…-arb-decision-dependency-verification.md` (**DD-1 · DD-2 · DD-3**) · `.claude/plans/WP-3/WP-4/WP-5/WP-7*.md` (authorization states) · `.claude/CONTEXT.md` (⚠️ stale, corroboration only) · `docs/implementation/PKS_Phase_III_Operational_Validation_Charter.md` §6.1/§7 · `docs/implementation/Implementation_Process_v1.1_Draft.md` (EP-01/02/03, EP-01-Light).

**Nothing in this Part is a decision.** *Every item is either a recorded authorization, a recorded blocker, or a prepared recommendation. **The four dispositions belong to the ARB; the identifier allocation belongs to C-4; the runtime synchronization belongs to the next slice closure.***

**Supersedes:** nothing. **Depends on:** Parts 1–8. **⚠️ Expected to go stale at the next ARB ruling — re-derive from the plan Status lines and the register.**
