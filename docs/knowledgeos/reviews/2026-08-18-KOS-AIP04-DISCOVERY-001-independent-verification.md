# `KOS-AIP04-DISCOVERY-001` — **Independent verification** of the ADR-AIP-04 capability discovery

**Assignment:** `S1-verification-aip04-capability-discovery` (seq 4 REGISTER · 5 HANDOFF · 6 START) · **Grant:** `G-KOS-AIP04-VERIFY` (+ `AMD1`)
**Date:** 2026-08-18 · **Type:** 🔴 **ASSURANCE ONLY.** No redesign · no ownership assigned · no acceptance · no closure.
**Placement derived:** `doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0); `reviews/` matches the artifact class.

---

# 1 · Independence disclosure — 🟡 **QUALIFIED**, with one recusal

**Producer, established from the record and the proposal's own §0:** the process that performed the `KOS-CONTRACT-NEUTRALITY-001` **breadth verification** and the **semantic-clarification drafting**, the `EM-IMPL-002` ADR gate and `EM-DOM-001` Phase 2A — i.e. `claude-code-session:5e1dd9ee`.

**This verifier, self-declared and NOT attestable (`INV-ATTR-2`/`G-2`):** `claude-code-session:1c8b041b`.

| Bar | Status |
|---|---|
| **Not the producer (`R-34`/`P-2`)** | ✅ **ESTABLISHED.** Different process; did not draft this proposal; had not read its substance before this assignment |
| Did I author evidence this discovery relies on? | ✅ **No.** `F-9`'s role-fluidity evidence is the **producer's own** history. My additions to the semantic-clarification proposal (§1.4, §2.8, §6.0) are not cited here |
| **Subject-matter neutrality** | 🔴 **NOT ESTABLISHED — and not establishable by any process in this estate** |
| **`SB-1` specifically** | 🔴 **CONFLICTED — see below** |

**Two exposures I must not leave for the reader to discover:**

**① I am an instance of the phenomenon `F-9` measures.** In this session I have occupied **architecture**, **implementation** and now **verification** lanes, and **recorded my own STARTs** (`KOS-CONTRACT-NEUTRALITY-001` seq 22, 31, 34) — precisely `F-9`'s observation. ⇒ **A finding that the six roles are "personas" retroactively normalises my own history; a finding that they are real architectural roles makes it a series of separation questions.** *(It also means I can corroborate `F-9` from independent first-hand evidence — see §5, `F-9`.)*

> **Consequence, stated plainly: the subject of this discovery is the role model, and every process in this estate occupies roles. Subject-matter neutrality is therefore unattainable by ANY verifier.** It cannot be the bar without making the verification unperformable. **The bar the assignment sets — *"Producer barred"* — is met.**

**② `SB-1` protects work I produced.** I am the **Track-1 implementation engineer** (`4c6c1dac`) and the author of the Track-1 fit assessment and reconciliation. `SB-1` exists to stop ADR-AIP-04 relocating **Track-1 conformance authority**. ⛔ **I therefore do not treat my `SB-1` verdict as independent.** I verify its evidence and record the conflict; **the PO/ARB should have `SB-1` re-verified by a process with no Track-1 authorship.** *(Note the direction of the conflict: the proposal already halts `SB-1`, so confirming it preserves the status quo rather than advantaging my work.)*

⛔ **I have not answered any ADR-AIP-04 decision, and I propose no replacement architecture.**

---

# 2 · Inputs

**Governed record first:** `.claude/runtime/workflow/KOS-AIP04-DISCOVERY-001.json` (6 transitions; grants `G-KOS-AIP04-DISCOVERY`, `G-KOS-AIP04-VERIFY`, `AMD1`; declared role set **`governance · architecture · verification`**) · the commission · the discovery proposal (378 lines).
**Primary artifacts read directly, not through the proposal:** `.claude/scripts/workflow-state.php` · `.claude/platform/registry.yaml` · `engineering/architecture/baseline/Phase-02-Domain-Model.md` §7 · `ADR-AIP-03` · **`docs/knowledgeos/architecture/01-system-context.puml`** · all 17 workflow records · `git log` on the §7 matrix and ADR-AIP-03.

---

# 3 · Method

**Falsification.** For each load-bearing claim I stated the observation that would prove it wrong, then attempted it against primary evidence. **I did not read and agree.** Where the proposal cites a mechanism, I read the mechanism; where it cites a count, I recounted; where it cites absence, I searched for presence.

---

# 4 · Evidence-by-evidence findings

## 4.1 ✅ Mechanism claims — **independently reproduced, all correct**

| Claim | Test | Result |
|---|---|---|
| `COMPLETE` refuses any writer but `governance`/`human` (`G-1`) | read `workflow-state.php` | ✅ **line 260–263**, refusal *"closure is a governance act (G-1)"* |
| Lane roles are **mechanically validated** at `REGISTER` | read the machine | ✅ **line 195–198**: `REGISTER requires a role (Inv B)`; *"role '…' is not in the workflow's declared role set"* |
| `R8` role immutability | read the machine | ✅ **line 192–193** |
| This lane excludes `implementation` **mechanically** | read the record | ✅ role set is `['governance','architecture','verification']` |
| §7 matrix **predates BC-7** (`OQ-5`) | `git log` both | ✅ **§7 file 2026-07-10 · BC-7 recognized 2026-08-17** — ~5 weeks |
| §7 matrix carries **no BC-7 vocabulary** (no hidden coupling) | grep | ✅ **0 occurrences** of `BC-7`, `lane role`, `session assignment` |

> **The proposal's mechanism evidence is strong and survives independent reproduction.** Its defects are elsewhere.

## 4.2 🔴 **Two material overclaims — both the same error the brief named**

### 🔴 **V-1 · `F-3` is falsified as stated.** *(absence of registration presented as absence of capability)*

**The claim:** *"The six 'Engineer' roles appear **nowhere** in the declared architecture, the capability map, or `registry.yaml`. Where such titles occur they are **commissioning personas in document headers**."*

**The falsifier fired.** `docs/knowledgeos/architecture/01-system-context.puml` — **a C4 system-context diagram in the very folder this proposal placed itself in** — models them as **`Person()` actors with responsibilities and relationships**:

```
Person(governance, "Governance Engineer", "Authority, lifecycle, grants, acceptance")
Person(arch,       "Architecture Engineer", "DDD strategic and tactical architecture")
Person(impl,       "Implementation Engineer", "Builds approved implementation")
Person(verifier,   "Independent Verification Engineer", "Falsifies and assures")
Person(comm,       "Governance Communication", "Next-actor and handover communication")
Rel(arch, kos, "Produces architecture") …
```

**Both halves of the claim are wrong:** they do not appear *nowhere*, and where they appear they are **not** header personas — they are **modelled actors in an architecture diagram**.

**What survives, and it matters:** ✅ **the file is UNTRACKED (`??`)** — never committed, therefore not a governed artifact. ⇒ **the correct classification is *evidence of NON-REGISTRATION*, not *evidence of NON-EXISTENCE*** — the precise distinction §5 of the brief called critical, and the proposal collapses it.

⚠️ **`F-3a`'s conclusion may well survive** (an uncommitted draft is weak evidence of an established role model) — **but it must survive on corrected evidence, disposed of explicitly, not on a survey that missed the artifact.** **Also unengaged: the diagram contains a *"Governance Communication"* Person, which bears directly on `F-1` (*"the accepted model contains no Communication capability"*) and on `C-19`.**

### 🔴 **V-2 · `C-14`'s status "absent / NO OWNER" is falsified.**

**Verified in `registry.yaml`:** **`AST-007` `db-safety-check.sh` — `governance_tier: 1`, commented *"blocking gate"*, `adoption: adopted`, `runtime_moments: [PRE_ACTION]`**, verified 2026-07-08, *"blocks migrate:fresh/refresh/db:seed unless testing env"*.

> ⭐ **That is policy enforcement — mechanical, blocking, adopted, in production today.**

**The proposal's narrower C-15 claim is accurate:** `AST-005`, `AST-006`, `AST-014` — C-15's own assets — **are all `governance_tier: 2`**. ✅ **But that was generalised into "enforcement is absent", and `AST-007` refutes the generalisation.**

⇒ **The correct characterisation is a COVERAGE and OWNERSHIP gap, not an ABSENT capability.** `C-14`'s substance (*unowned as a modelled capability*) may stand; its **status must change from `absent` to `live, narrow, unmodelled`**, and **`SB-3`'s premise** (*making enforcement blocking would change the conditions under which Track-1 lanes execute*) **is weakened: blocking enforcement already exists.**

## 4.3 🟡 Minor

| | Item | Finding |
|---|---|---|
| **V-3** | `EKS-03` *"39 `tokenRef`s hard-code paths"* | **Recount: 40** (of 48 `tokenRef`s). Stale by one — **and the drift was caused by ongoing work (the Track-2 handoff itself)**, which *strengthens* `F-8` rather than weakening it. Correct the number |
| **V-4** | `F-2` *"contains no actors at all"* | **Overstated.** §7 names **Chief Architect · ARB · Sponsor** as approval authorities. ✅ **The substance is correct** — it is an *activity × decision-right* matrix, not a role model — but the wording must read *"names decision authorities, not engineering roles"*. This also refines `OQ-5`: what BC-7 would consume **does** name authorities |

---

# 5 · Capability findings

**`C-5` Separation attestation — ✅ verified.** *Declared / Not attestable* is confirmed by the machine (`executionContext` is free text; nothing binds an act to a performer). The five DDD tests are applied and the **context-vs-stewardship question is correctly left OPEN (`OQ-A`)**. ⭐ **The strongest argument in the proposal survives scrutiny: a process cannot attest its own separation, so `C-5` cannot be discharged by an agent.** **I corroborate `F-9` from independent first-hand evidence — I recorded my own STARTs at seq 22, 31 and 34 (§1).** ⛔ **No mechanism is proposed here.**

**`C-10` Knowledge distribution — ✅ verified as a legitimately open question.** `F-5`/`EKS-01` (a `tokenRef` taught a lane the superseded path) is the strongest evidenced gap, and the proposal **correctly declines to decide BC-1 vs BC-6**. **The evidence does demonstrate a capability gap rather than a single implementation defect**, because the mechanism (`tokenRef`) is structural and used 48 times, not incidental.

**`C-14` Policy enforcement — 🔴 see `V-2`.** The authorship / derivation / enforcement distinction (`F-7`) is sound and verified (`doc-placement.php` is live for derivation). **The defect is the status claim, not the distinction.**

**`C-19` Communication composition — ✅ verified, and verified honestly.** The proposal **fails `C-19` against its own five-test matrix** (borrowed language · one candidate invariant · changes with audience) and classifies it a **stewardship, not a context**. ⭐ **A discovery that declines to promote its own most rhetorically attractive candidate is applying the test rather than decorating it.** ⚠️ **But it must dispose of the untracked diagram's *"Governance Communication"* Person (`V-1`).** ✅ **Distribution and composition are kept distinct, not merged because both emit documents.**

---

# 6 · Role-model findings — the six-role hypothesis

**Re-derived independently from ADR-AIP-01, the capability map, `registry.yaml`, the workflow role model, and the actual assignments and grants.**

✅ **`registry.yaml` contains no role or actor concept at all** (verified: no `role:`/`actor:` keys). ✅ **The estate's executable role model is BC-7's declared role set** — validated at `REGISTER`, immutable per `R8`, bound to authority through grants, **not** through role identity. ✅ **`F-11` verified:** this lane's own role set excludes `implementation` mechanically, and `ADR-AIP-02` Product Primacy places product implementation in the core domain.

🔴 **But the hypothesis' stated evidence base is falsified by `V-1`.** The six titles are **modelled as Persons in an architecture diagram** the survey missed. **The absence is non-registration, not non-existence** — and the proposal asserts the stronger form.

> **The conclusion is plausible and may well be right. It is not, as delivered, established on the evidence cited.**

---

# 7 · `OQ-5` verification — ✅ **PASS**

| Challenge from the brief | Result |
|---|---|
| Does the §7 authority matrix exist as a published governance language? | ✅ **Yes** — `Phase-02-Domain-Model.md` §7, activity × decision-right |
| Does it predate BC-7? | ✅ **Yes — 2026-07-10 vs 2026-08-17** |
| Does it contain actors or only decision rights? | 🟡 **Decision rights, plus three approval AUTHORITIES** (Chief Architect, ARB, Sponsor) — **not engineering roles.** See `V-4` |
| Does BC-7 genuinely consume it? | ⚠️ **`INFERRED`, not observed.** Grants carry scope and cite human acts; **no mechanical link to §7 exists.** The proposal should not present consumption as observed |
| Does the split create hidden coupling? | ✅ **No** — the matrix carries **zero** BC-7 vocabulary |

⛔ **`OQ-5` is NOT decided here.** The split is well-founded on precedence and non-coupling; the *consumption* leg needs its classification corrected to `INFERRED`.

---

# 8 · Shared-boundary findings

| | Verdict |
|---|---|
| **`SB-1`** conformance authority | ⚠️ **CONFLICTED — I recuse (§1②).** Evidence checked: the proposal **does** halt and return it, and **modifies nothing** in Track 1 (verified: `scripts/lib`, `scripts/observations`, `.claude/platform`, `app/` all clean). **On the evidence it is correctly handled — but this verdict should be re-taken by a process with no Track-1 authorship** |
| **`SB-2`** evidence-chain location coupling | ✅ **PASS**, with `V-3`'s corrected count (40, not 39) |
| **`SB-3`** enforcement posture | 🟡 **PASS WITH NOTES — premise weakened by `V-2`.** Blocking enforcement (`AST-007`, Tier-1) already exists, so "making enforcement blocking" is not a change of kind, only of coverage |

---

# 9 · Verdict matrix

| | Area | Verdict |
|---|---|---|
| **A** | Evidence integrity | 🟡 **PASS WITH NOTES** — mechanism evidence reproduced exactly; two material overclaims (`V-1`, `V-2`), two minor (`V-3`, `V-4`) |
| **B** | Capability identification | 🟡 **PASS WITH NOTES** — the 20-capability inventory is sound; one **status** wrong |
| **C** | Capability ownership analysis | 🟡 **PASS WITH NOTES** |
| **D** | DDD bounded-context analysis | ✅ **PASS** — five tests applied consistently; candidates failed by their own test, not promoted |
| **E** | **Six-role hypothesis** | 🔴 **FAIL — material correction** (`V-1`) |
| **F** | `OQ-5` split | ✅ **PASS** — precedence and non-coupling verified; correct the *consumption* classification |
| **G** | `C-5` analysis | ✅ **PASS** |
| **H** | `C-10` analysis | ✅ **PASS** |
| **I** | **`C-14` analysis** | 🔴 **FAIL — material correction** (`V-2`) |
| **J** | `C-19` analysis | ✅ **PASS** |
| **K** | Shared-boundary risks | 🟡 **PASS WITH NOTES · `SB-1` CONFLICTED, re-verification recommended** |
| **L** | Classification discipline | 🟡 **PASS WITH NOTES** — `OBSERVED`/`INFERRED` are separated rigorously throughout; **both failures are the same error: absence of registration presented as absence** |
| **M** | **Track-1 safety** | ✅ **PASS** — `SB-1` halted and returned; no L3 contract, semantic decision or Track-1 artifact touched (verified clean) |

---

# 10 · Overall verdict

> # 🔴 **RETURNED FOR MATERIAL CORRECTION**
>
> **Two defects, both of one kind, both load-bearing:**
> **`V-1`** — the six-role finding's evidence is falsified by an architecture diagram the survey missed; **`V-2`** — `C-14`'s "absent" status is falsified by an adopted Tier-1 **blocking** gate.
>
> ⚠️ **This is NOT a rejection of the thesis.** The four-unowned-capability framing, the DDD discipline, the mechanism evidence and the refusal to promote `C-19` are strong and survive independent falsification. **What fails is evidence sufficiency on two claims the PO/ARB would rely on.**
>
> **The verification question was: *is this sufficiently justified to be placed before PO/ARB for decision?*** **On `E` and `I` — not yet.** Correct those two and the answer becomes yes.

⛔ **This is a verification verdict, NOT PO/ARB acceptance.**

---

# 11 · Conditions / notes

1. **Correct `F-3`/`F-3a`:** survey `docs/knowledgeos/architecture/*.puml`; dispose of `01-system-context.puml` explicitly; restate the finding as **non-registration**, not non-existence.
2. **Correct `C-14`'s status** from `absent` to `live, narrow, unmodelled`; adjust `SB-3`'s premise accordingly.
3. **Correct `F-2`'s wording** (`V-4`) and `EKS-03`'s count (`V-3`).
4. **Reclassify the `OQ-5` consumption leg** as `INFERRED`.
5. **Dispose of the diagram's *"Governance Communication"* actor** under `F-1`/`C-19`.
6. **`SB-1` should be re-verified by a process with no Track-1 authorship** (§1②).

⛔ **None of these is a redesign instruction. Where a replacement is needed, the question returns to Architecture / PO-ARB.**

---

# 12 · PO/ARB decision questions — **preserved verbatim, unanswered**

The proposal's open questions `OQ-A`…`OQ-G`, its `OQ-5` split proposal, `SB-1`/`SB-2`/`SB-3`, and `ADR-C2`/`ADR-C7` **are carried forward unchanged and unanswered.** ⛔ **The verifier has answered none of them, and must not.**

---

**INDEPENDENT VERIFICATION COMPLETE · STOPPING.**
⛔ **No ADR-AIP-04 decision answered · no proposal modified · no BC-7 change · no Track-1 change · no `.claude/platform` change · no role, agent or context created · no technology selected · no acceptance · assignment NOT self-closed (`G-1`).**
**Next actor: PO/ARB.**

**Traceability:** `G-KOS-AIP04-VERIFY` + `AMD1` · assignment seq 4–6 · the discovery proposal · `workflow-state.php` lines 192–198, 260–263 · `registry.yaml` `AST-005/006/007/014` · `Phase-02-Domain-Model.md` §7 · `ADR-AIP-03` · **`docs/knowledgeos/architecture/01-system-context.puml` (untracked)** · 17 workflow records (48 `tokenRef`s, 40 path-bearing) · `git log` precedence 2026-07-10 / 2026-08-17 · `R-34`/`P-2` · `G-1` · `INV-ATTR-2`/`G-2`.
