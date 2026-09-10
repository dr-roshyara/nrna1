---
artifact: G-19 · LIFECYCLE OF THE COMMISSIONED K-FALSIFICATION
date: 2026-09-10
lane: theory-extraction / chronological reconstruction (bounded local investigation)
status: **DISPOSITION — `EXECUTED_AND_REFUTING`, by convergence, never in response to the commission**
supersedes: the `G-19` row in `06-GAP-REGISTER.md` ("commissioned and never written")
---

# G-19 — What was commissioned in Step 267, and was it ever executed?

> **Governing principle applied:** a commissioned falsification is not a falsification result; a
> claimed proof is not the execution of its validation protocol. `COMMISSION`, `EXECUTION`, `RESULT`
> and `ADJUDICATION` are reconstructed separately below and never collapsed.

**Headline correction.** The register's standing `G-19` row asserted that *"the one document
commissioned to attack the cluster's central claim is missing, and the unfalsified claim proceeded
straight into `272a`–`277`."* **The second half of that sentence is REFUTED by this investigation.**
The claim did not proceed unfalsified. It was attacked three times, by executed machinery, within
74 minutes of the commission — in a lane the commissioning lane never read.

---

## 1. The commission, captured verbatim before paraphrase

**Source:** `brainstorming/phase_measure_theory/20260830-195900_step_267_empirical-bridge-to-knowledgeos.md`,
**L1327–1375** — a 48-line trailing section, written **2026-08-30 19:59:00**.

```
# STEP 268 — INDEPENDENT FALSIFICATION

We now have enough structure to attack the theory rather than extend it.

The prompt explicitly requires trying to **destroy** the candidate theory through adversarial tests of:

  K, congruence, minimality, equality, identity, Σ,
  provenance, evidence, policy, authority, replay, merge,
  supersession, contradiction, uncertainty.

The key change is that Step 268 should **not** ask:
  "Can we find examples supporting the model?"
It must ask:
  ┌──────────────────────────────────────────────────────────────────┐
  │ Can we construct one valid counterexample that forces the        │
  │ current model to fail?                                           │
  └──────────────────────────────────────────────────────────────────┘

If we find one, we modify the theory.
If we cannot find one after systematic adversarial construction, confidence increases—but we
still do not call the theory proven merely because the tests passed.

The first attack should be on the most consequential claim:
  K = (𝒜, ℛ)
with the minimality claim:
  No component can be removed without losing a mandatory capability.

That is where the next step should begin.
```

| field | value as commissioned |
|---|---|
| **test objective** | *destroy* the candidate theory; construct **one** valid counterexample |
| **K formulation under test** | `K = (𝒜, ℛ)` — the **2-tuple**, explicitly. Not the 4-tuple |
| **minimality claim under test** | *"No component can be removed without losing a mandatory capability."* — a **removal / necessity** claim, stated over *mandatory capabilities* |
| **transformation set `𝒯`** | ⚠️ **never named in the commission.** The claim is quantified over *capabilities*, not over `𝒯`. See §8 |
| **assumptions** | that the capability list is complete (unstated); that `𝒜`,`ℛ` are the components in scope |
| **required conditions** | *"systematic adversarial construction"* across the 15 named targets |
| **expected output** | a counterexample, or a recorded failure to find one |
| **success/failure criterion** | **existential and asymmetric.** One counterexample ⇒ modify the theory. Zero counterexamples ⇒ *"confidence increases—but we still do not call the theory proven"* |
| **named next step** | *"Step 268"* — a step number, **no filename, no deliverable list, no owner** |

### The commission's own epistemic guard, from the same document

`step_267` **§267.18** (L886 ff.), written 73 lines earlier:

> *"The corpus itself contains evidence of earlier 'empirical' claims that were not actually executed.
> … A statement such as: 'The model has been validated against KnowledgeOS' **cannot be accepted
> merely because a previous step says: 'empirical validation complete.'**"*

**This guard is violated ten minutes later by `step_269`** (§3 below).

### What the commission believed about the evidence at 19:59

`step_267` L1310 ff.:

$$12\ falsification\ scenarios\ designed \quad\text{but}\quad 0\ runnable\ against\ KnowledgeOS$$

and its own assessment table lists `K=(𝒜,ℛ)` under **"Explicit implementation gaps"** with
verdict **`EMPIRICAL BRIDGE: PARTIALLY ESTABLISHED — NOT CLOSED`**.

*(The "12 designed, 0 runnable" figure traces to the step 48/49 falsification experiments of
2026-08-28, independently classified in `verification/spec/STEP-TRACE-B5-20260828-early.md` L275 as
**"12 falsification experiments … all PASS, CONCEPTUAL_ONLY"** — designed, never mechanically run.
The count is corroborated.)*

---

## 2. The six propositions, kept separate

The commission attacks **one** of these. It is essential not to let a result about one migrate to another.

| | proposition | what the corpus does with it |
|---|---|---|
| **A** | `K=(𝒜,ℛ)` **exists** | **ESTABLISHED.** Artifact D §2 constructs a finite valid instance; `exp_ekp_bridge` EXP-11 finds a running one (`\|𝒜\|=40`, `\|ℛ\|=59`) |
| **B** | `K=(𝒜,ℛ)` is a **candidate kernel** | **ESTABLISHED** — uncontested throughout |
| **C** | `K=(𝒜,ℛ)` is **minimal relative to `𝒯`** | **SUPPORTED** by an executed removal test (artifact D §3). This is what the corpus calls `Minimality(K \| 𝒯)`, and it is **type A only** (§6) |
| **D** | `K=(𝒜,ℛ)` is **globally / ontologically minimal** | **EXPLICITLY DENIED BY ITS OWN SOURCES.** Artifact D: *"**Not ontological necessity.** A richer `𝒯` could force more."* `THEORY-COMPLETION-GAP-REGISTER` §2: type B **"NOT PROVEN, and not provable by this method"** |
| **E** | `K=(𝒜,ℛ)` is **sufficient** | ⭐ **THIS IS WHAT WAS ACTUALLY ATTACKED, AND IT IS CONTESTED.** Three executed attacks; one self-withdrawn (§3) |
| **F** | `K=(𝒜,ℛ)` is **canonical** | **NOT ADJUDICATED.** No governance act found |

> ⚠️ **The commission's stated claim is a NECESSITY claim (C). Every executed attack landed on
> SUFFICIENCY (E).** These are the two halves of minimality and the corpus itself says so:
> `IDENTITY-ROUNDTRIP-AUDIT.md` L220 — *"Is minimality proven? **NO** — necessity shown for 8
> components; **sufficiency not shown**."*

---

## 3. Execution status — the complete bounded interval, 18:32 → 22:42

Every row verified at the primary source. Times are file mtimes, corroborated by internal `mandate:`
and `date:` keys where present.

| time | lane | document | act | what it did to the K claim |
|---|---|---|---|---|
| 18:32:30 | verification | `KNOWLEDGE-STATE-REQUIREMENTS-AND-DERIVATION.md` L124 | **EXECUTION** | *"**MINIMALITY: PROVEN.** Each of the six fields has a removal counterexample"* — the **necessity** half, run |
| 19:04:23 | verification | `THEORY-COMPLETION-GAP-REGISTER.md` §2 | **ADJUDICATION** | ⭐ self-correction: *"Which minimality was proven? **A only.**"* — and *"**A is not evidence for B, C or D.** My earlier phrasing … "* |
| 19:24:42 | verification | `KNOWLEDGE-STATE-CANONICAL-MODEL.md` (artifact D) §3 | **EXECUTION** | 13-component removal table; all of `𝒜,ℛ,id,P,e,c,t,Π` **NECESSARY**; `Σ` **DERIVED**; History/Policy/Authority **EXTERNAL**; `𝒵,ℒ` **REDUNDANT**. Qualified `Minimality(K\|𝒯)` |
| 19:25:19 | verification | `TRANSFORMATION-CANONICAL-MODEL.md` (artifact E) §2 | **DEFINITION** | enumerates `𝒯` — see §8 |
| 19:58:34 | verification | `CANONICAL-KNOWLEDGEOS-THEORY.md` L259 | **RESTATEMENT** | *"proven relative to `𝒯`, **not** ontologically"* — qualification **preserved** |
| **19:59:00** | **commission** | **`step_267` L1327** | ⭐ **COMMISSION** | **orders Step 268 — INDEPENDENT FALSIFICATION** |
| 20:09:07 | commission | `step_269` L5 | ⭐ **CANCELLATION** | *"We should not simply continue with 'Step 268 = falsification' as if nothing had happened. A substantial amount of that work has already been performed."* Redirects to **Policy** |
| 20:09:07 | commission | `step_269` L1176 | **RESTATEMENT** | `\| K minimality \| 🟢 representation-minimality proven \|` — the qualifier is **substituted**, not dropped (§6) |
| 20:28:40 | commission | `step_270` | **EXECUTION (other target)** | genuine adversarial audit — of **Policy + Evidence Assessment**, not `K` |
| **20:47:18** | verification | **`KNOWLEDGE-STATE-FINAL-AUDIT.md`** | ⭐ **EXECUTION + RESULT** | *"`K = (𝒜, ℛ)` **IS UNDER-SPECIFIED AND INTERNALLY CONTRADICTORY** — 3 executed refutations"*; *"**A construction score is not a proof of adequacy.**"* |
| **21:05:24** | verification | **`gap-discovery/14-FALSIFICATION-RESULTS.md`** | ⭐ **EXECUTION + RESULT** | 30 claims tested, **17 REFUTED · 11 SURVIVED · 2 mixed**. **Row 1 is the commissioned claim** |
| 21:10:45 | verification | `independent/00-INDEPENDENT-MANDATE.md` | **COMMISSION (independent)** | *"do not score candidates by naming capabilities — **construct them and test**"* |
| **21:13:24** | verification | **`independent/05-K-ATTACK.md`** | ⭐ **EXECUTION + RESULT** | *"`K=(𝒜,ℛ)` with a bare-triple `ℛ` is **REFUTED as adequate** — by construction, not by scoring"* |
| **21:41:38** | verification | `gap-discovery/second-order/00-ERRATA-TO-FIRST-ORDER.md` L21 | ⭐ **ADJUDICATION — WITHDRAWAL** | withdraws the 21:05 inference: *"`ever_contested` is a class-4 audit operation … **The arithmetic was right; the inference was not.**"* |
| 21:44:01 | commission | `step_273` | ⭐ **RE-COMMISSION** | 12 named deliverables incl. `K-DELETION-TESTS.md`, `K-COUNTEREXAMPLE-CATALOGUE.md` |
| 21:43:17 | commission | `step_274` L1359 | **RE-COMMISSION** | adds `K-COUNTEREXAMPLE-RESULTS.md` |
| 22:01:34 | commission | `step_277` L1621 | ⭐ **STATUS RECORD** | `\| K-minimality \| Not yet tested \| **OPEN** \|`; L1038 *"Minimality remains Step 278 work."* |
| 22:14–22:21 | commission | `step_278` (3 versions) | **DEFERRAL** | does **Policy–Authority integration** instead. `K`-minimality is not carried forward |
| 22:42:42 | commission | `step_272a` | — | (G-14: the 272 mandate's execution) |

### `EXECUTED_AND_REFUTING` — with three qualifications

1. **The execution was by convergence, not by commission.** None of the three attacking documents
   cites Step 268 or the commission. `find docs/knowledgeos -iname "*268*"` returns **0 matches
   across 3,105 files** (firewalled tree excluded). The gap-discovery lane's own mandate
   (`gap-discovery/INDEX.md`) is independent, dated the same day, and reads `step_267` as *source
   material* — it refutes `step_267`'s own empirical conclusion (`13-IMPLEMENTATION-REALITY-CHECK.md`:
   *"Step 267 searched the wrong bounded context"*).
2. **One of the three refutations was self-withdrawn 36 minutes later** by the same lane's
   second-order pass. It stands as an *arithmetic* result and falls as an *inference*.
3. **The commissioning lane never learned.** At 22:01 — 74 minutes after the first refutation
   landed — `step_277` records K-minimality as *"Not yet tested · OPEN"*.

### Re-commissioned deliverables: 0 of 13 exist

`find` across the whole repository (firewall excluded), by exact filename:

| commissioned by | file | exists? |
|---|---|---|
| 273 §1 | `K-CANONICAL-TYPE-SPECIFICATION.md` | ❌ |
| 273 §2 | `ASSERTION-SEMANTICS.md` | ❌ |
| 273 §3 | `RELATIONSHIP-SEMANTICS.md` | ❌ |
| 273 §4 | `EVIDENCE-LINK-MINIMALITY.md` | ❌ |
| 273 §5 | `SIGMA-PRIMITIVE-VS-DERIVED.md` | ❌ |
| 273 §6 | **`K-DELETION-TESTS.md`** | ❌ |
| 273 §7 | **`K-COUNTEREXAMPLE-CATALOGUE.md`** | ❌ |
| 273 §8 | `K-IDENTITY-ANALYSIS.md` | ❌ |
| 273 §9 | `K-STATE-ALGEBRA.md` | ❌ |
| 273 §10 | `K-DDD-MAPPING.md` | ❌ |
| 273 §11 | `K-IMPLEMENTATION-TRACE.md` | ❌ |
| 273 §12 | `UPDATED-THEORY-CLOSURE-MATRIX.md` | ❌ |
| 274 §11 | **`K-COUNTEREXAMPLE-RESULTS.md`** | ❌ |

> **The `phase_measure_theory/` lane commissioned the K falsification three times (267, 273, 274)
> and executed it zero times. The `verification/` lane executed it three times and was commissioned
> to do so zero times.** This is the two-lane rule at its sharpest.

---

## 4. `COMMISSION` ≠ `EXECUTION` ≠ `RESULT` ≠ `ADJUDICATION` — the four records

| record | what exists | where |
|---|---|---|
| **COMMISSION** | 3 commissions: `step_267` L1327 (19:59), `step_273` §§1–12 (21:44), `step_274` §11 (21:43) | `phase_measure_theory/` |
| **EXECUTION** | 3 executions + 1 prior: artifact D §3 (19:24, necessity), `KNOWLEDGE-STATE-FINAL-AUDIT` (20:47), `14-FALSIFICATION-RESULTS` row 1 via `exp_congruence` EXP-3 (21:05), `05-K-ATTACK` §2 via `attack.py` §A (21:13) | `verification/` |
| **RESULT** | necessity: **SURVIVED** (8 components necessary). sufficiency: **REFUTED** on `ℛ`-adequacy (standing); **REFUTED (conditionally)** on `𝒪`-dependence (**withdrawn**); **UNDER-SPECIFIED + INTERNALLY CONTRADICTORY** (standing) | as above |
| **ADJUDICATION** | 2 acts, both self-administered within `verification/`: `THEORY-COMPLETION-GAP-REGISTER` §2 (19:04, *"type A only"*) and `second-order/00-ERRATA` (21:41, EXP-3 inference withdrawn). **No governance act found.** No cross-lane adjudication found | `verification/` |

---

## 5. Step 268 — direct verification

| question | answer | evidence |
|---|---|---|
| Does Step 268 exist? | **As a commission only.** | `step_267` L1327–1375, 48 lines |
| Is there a step-268 artifact? | **No.** | `find docs/knowledgeos -iname "*268*"` → **0 matches / 3,105 files** (firewall excluded) |
| Does the corpus record its absence? | **Yes, four independent times.** | `independent/01-CORPUS-INVENTORY.md` L24 · `independent/14-INDEPENDENT-VERDICT.md` L28 · `second-order/07-SECOND-ORDER-VERDICT.md` L92 · `second-order/04-GAP-RECLASSIFICATION.md` L68 — all read **"217, 229, 268 absent"** |
| Did another artifact execute it under a different name? | **Yes — three, by convergence.** See §3 | 20:47 · 21:05 · 21:13 |
| Does a later document claim to consume its result? | **No.** No document cites Step 268's output. `step_269` claims its *work* was done; it names the A–J batch, which is **constructive**, not the three adversarial artifacts | §6 |
| Is there a reserved-but-empty location? | **Yes, and it is deliberate.** `brainstorming/falsification/README.md`: *"Reserved for attacks … None exist. **Currently empty by design, not by delay.**"* | direct |

---

## 6. Downstream K claims — every one, with what it actually rests on

| source | time | claim | claimed predecessor | actual evidence | qualification |
|---|---|---|---|---|---|
| `KNOWLEDGE-STATE-REQUIREMENTS-AND-DERIVATION` L124 | 18:32 | *"MINIMALITY: PROVEN"* | its own Stage 1–3 | 6 field-removal counterexamples + 1 non-reconstructibility | **none stated at this point** |
| `THEORY-COMPLETION-GAP-REGISTER` §2 | 19:04 | *"**A only**"* | the above | same | ⭐ **qualification ADDED — a self-correction that withdraws its own earlier phrasing** |
| artifact D §3 | 19:24 | *"MINIMAL KERNEL … **earned**"* | its own executed table | 13-row removal table | **`Minimality(K\|𝒯)`; "Not ontological necessity"** |
| `CANONICAL-KNOWLEDGEOS-THEORY` L259 | 19:58 | *"proven relative to `𝒯`"* | artifact D | same | **PRESERVED** |
| `step_262` L9 | 19:27 | *"reported as **PROVEN**"*, *"should not reopen"* | *"the latest executed reconstruction"* | artifact D, 3 min earlier | ⛔ **DROPPED** → `EKS-46` |
| **`step_269` L1176** | **20:09** | **`🟢 representation-minimality proven`** | the A–J batch | artifact D | ⚠️ **SUBSTITUTED** — `Minimality(K\|𝒯)` becomes *"representation-minimality"*. A different predicate, not a weaker rendering of the same one |
| `KNOWLEDGEOS-THEORY-CLOSURE-REPORT` L119 | 18:48 | *"minimality proven"* | — | — | ⛔ **absent** |
| `IDENTITY-ROUNDTRIP-AUDIT` L220 | 11:06 | *"Is minimality proven? **NO**"* | its own §6 | 8-component necessity table | — *(pre-dates artifact D; superseded chronologically, but its distinction stands)* |
| `step_277` L1621 | 22:01 | *"Not yet tested · **OPEN**"* | — | — | — *(contradicts 269 and 262 within the same lane)* |

> **Three mutually incompatible downstream states coexist on 2026-08-30**: *proven-and-qualified*
> (19:58), *proven-unqualified* (19:27, 18:48), and *not tested* (22:01). They are not a
> chronological progression — 22:01 is the **latest** of the three.

### `step_269`'s substitution is a distinct defect from `EKS-46`

`EKS-46` records a **drop**: `Minimality(K|𝒯)` → *"PROVEN"*, 3 minutes, `step_262`.
`step_269` records a **substitution**: `Minimality(K|𝒯)` → *"representation-minimality"*, 10 minutes.
These are different failure modes. *"Representation minimality"* is in fact the **correct type-A
name** used by `THEORY-COMPLETION-GAP-REGISTER` §2 — so the substitution is, unusually, **more
accurate than the qualifier it replaced in one respect and less traceable in another**: it names the
right type but severs the link to `𝒯`, which is what makes the claim conditional.

**`G-19` is therefore NOT folded into `EKS-46`.** Related, distinct.

---

## 7. Does `Minimality(K | 𝒯)` depend on the commissioned falsification?

**Verdict: `Independent proof` for the claim's existence; `Required validation` for its independence.**

- The claim's evidential basis is artifact D §3's executed removal table (19:24), which **pre-dates
  the commission by 35 minutes**. The falsification is therefore **not required** for `C` to have an
  executed basis.
- But artifact D is **self-administered**: the same reconstruction that defines `K` also tests it,
  in the same batch, under the same mandate, 42 seconds apart from the transformation model that
  supplies its `𝒯`. The commission's title is **INDEPENDENT** falsification. That independence
  check was never performed *as commissioned* — and when independent parties did attack (20:47,
  21:05, 21:13), **all three found something wrong**, two of which still stand.
- **Dependency is therefore asymmetric:** proposition **C** does not depend on the falsification;
  proposition **E** (sufficiency) does, and it is the one that failed.

---

## 8. Which `𝒯` was the test supposed to use?

| field | finding |
|---|---|
| **named in the commission?** | ⛔ **No.** `step_267` quantifies over *"a mandatory capability"*, never over `𝒯`. The commission and the claim it attacks **use different quantifiers** |
| **source of the `𝒯` actually used** | `TRANSFORMATION-CANONICAL-MODEL.md` (artifact E) §2, mandate `20260830_1918 §8`, **19:25:19** |
| **version** | the artifact-E table, 5 rows |
| **membership** | `𝒯 = { assert, relate, retract, merge, noop }` — each with precondition, state effect, evidence effect, provenance effect, authority, policy, assessment effect, failure mode, determinism, idempotence |
| **enumerated?** | ✅ **Yes, fully.** This is the exception, not the rule, in this corpus |
| **open or closed?** | **Closed for the test, declared open in principle.** Artifact D: *"REDUNDANT **for the specified `𝒯`**"*, *"**A richer `𝒯` could force more.**"* |
| **was the test therefore executable?** | ✅ **Yes — and it was executed**, at 19:24, before the commission existed |
| **sequencing note** | artifact D (19:24:42) cites *"the specified `𝒯`"* **37 seconds before** artifact E (19:25:19) writes it down. Same mandate, same run — recorded, not treated as a defect |

⚠️ **The unresolved-`𝒪` finding is a different object.** `14-FALSIFICATION-RESULTS` FR-3 concerns
the **mandatory operation set `𝒪`** (which operations the theory is *required* to support), not the
**transformation set `𝒯`** (which state transitions were *specified*). `𝒯` is enumerated; `𝒪` is
not. The commission's own quantifier — *"a mandatory capability"* — ranges over `𝒪`, the
unenumerated one. **`𝒯` is not redefined or solved here.**

---

## 9. Falsification ≠ minimality

The commission's criterion is **existential and asymmetric**, and it says so:

> *"If we cannot find one after systematic adversarial construction, confidence increases—but we
> still do not call the theory proven merely because the tests passed."*

Therefore, applied strictly to what the corpus contains:

| inference | admissible? |
|---|---|
| falsification found ⇒ minimality false | ❌ **No** — the found counterexamples attack **sufficiency (E)**, not the **necessity (C)** the minimality claim states |
| falsification absent ⇒ minimality true | ❌ **No** — and the commission itself forbids it |
| removal test passed ⇒ `Minimality(K\|𝒯)` | ✅ **Yes, and only that** — type A, relative to an enumerated `𝒯`, over an **assumed-complete** capability list |
| `Minimality(K\|𝒯)` ⇒ K is the kernel | ❌ **No** — `THEORY-COMPLETION-GAP-REGISTER` §2: *"A is not evidence for B, C or D"* |

**What the commissioned test actually establishes, if run and passed:** nothing beyond raising
confidence in **E**. It could never have established **C**, **D** or **F**.

---

## 10. K genealogy — versions kept apart

| | `K_v3 = (𝒜,ℛ)` | `K_v4 = (𝒜,ℛ)` | `K_v5 = (A,R,Σ,E_L)` |
|---|---|---|---|
| **existence** | ESTABLISHED — constructed and found running | same object, restated | ESTABLISHED as a written form |
| **provenance** | artifact D, 19:24 (`G-18`, CLOSED) | imported from `K_v3` | the Step-272 **mandate's proposal** (`G-14`, weak) |
| **mathematical definition** | `𝒜=Set(Assertion)`, `Assertion=(id,P,e,c,t,Π)`; `ℛ⊆𝒜×𝒜×RelationType` | identical | `A,R,Σ,E_L` — `Σ`,`E_L` **UNWITNESSED** as `K`-components |
| **minimality claim** | `Minimality(K\|𝒯)`, **type A only** | *"PROVEN"* (unqualified) | *"conditional minimality"* rel. `𝒪_core` (`step_276`) |
| **validation status** | necessity **EXECUTED, SURVIVED**; sufficiency **EXECUTED, REFUTED** | none of its own | **NONE** — `step_277`: *"Not yet tested"* |
| **falsification status** | ⭐ **attacked 3×; 2 refutations standing, 1 withdrawn** | never attacked | **never attacked** |
| **governance status** | **UNRESOLVED** — no governance act found | UNRESOLVED | UNRESOLVED |

**Not merged.** `K_v3`/`K_v4` remain distinct records of one object under two qualification states;
`K_v5` remains a separate 4-tuple whose 2→4 extension is `UNWITNESSED`.

---

## 11. TheoryState dimensions, preserved independently

```
Conceptual    = STABLE           K as (assertions, relations) is uncontested across all lanes
Mathematical  = DEFINED          𝒜, ℛ, Assertion, 𝒯 all typed and enumerated; constructed and executed
Validation    = EXECUTED,        necessity SURVIVED (8 components) ·
                CONTESTED        sufficiency REFUTED on ℛ-adequacy (standing) and on
                                 under-specification (standing); one conditional refutation WITHDRAWN
Minimality    = QUALIFIED        type A only, relative to an enumerated 𝒯,
                                 over an UNENUMERATED capability set 𝒪
Governance    = UNRESOLVED       three incompatible statuses coexist in the corpus;
                                 no governance act adjudicates between them
```

---

## 12. Findings

| ID | finding | class | severity |
|---|---|---|---|
| **G19-1** | The commissioned falsification **was executed** — three times, independently, within 74 minutes — and **two of its three refutations still stand**. The register's *"proceeded unfalsified"* is **REFUTED** | `EXECUTED` | **CRITICAL (corrective)** |
| **G19-2** | Execution was **by convergence, never by commission**. No executing document cites Step 268; no commissioning document cites an executing artifact | `EXECUTED` | **CRITICAL** |
| **G19-3** | The commissioning lane recorded *"K-minimality · Not yet tested · OPEN"* (`step_277`, 22:01) **74 min after** the first refutation landed | `EXECUTED` | HIGH |
| **G19-4** | The commission's stated claim is **necessity**; every executed attack landed on **sufficiency**. The two halves were never tested by the same instrument | `DERIVED` | HIGH |
| **G19-5** | **0 of 13** deliverables re-commissioned by `step_273`/`step_274` exist | `EXECUTED` | HIGH |
| **G19-6** | `step_269` **substitutes** the qualifier (`Minimality(K\|𝒯)` → *"representation-minimality"*) rather than dropping it — a defect distinct from `EKS-46` | `EXECUTED` | MEDIUM |
| **G19-7** | Three mutually incompatible minimality statuses coexist, and the **latest** is *"not tested"* | `EXECUTED` | HIGH |
| **G19-8** | `𝒯` is **fully enumerated** (5 operations) — but the commission's quantifier ranges over the **unenumerated** `𝒪`. The test as worded was not executable; the test as executed answered a different question | `DERIVED` | HIGH |

---

## 13. Scope discipline

This investigation establishes **nothing** about whether the kernel research programme is valid, and
**nothing** about whether `K` is false. Two standing refutations concern the **adequacy of `ℛ` as a
bare triple** and the **under-specification of `K`** — both repairable, and both stated as such by
their own authors (*"`K = (𝒜, ℛ)` is the right SHAPE and the wrong `ℛ`"*). The necessity half of the
minimality claim **survived** its executed test. No counterexample to `C` exists in the corpus.

**Firewall:** `brainstorming/three_model_convergence/` was not opened, read, grepped or cited by this
investigation or by its evidence worker. Any evidence residing only there is `FIREWALL-LIMITED`.

**Reproduction check:** `exp_congruence.py` was re-executed on 2026-09-10 from
`gap-discovery/exec/`; its output is **byte-identical** to the committed `OUT-congruence.txt`,
including the EXP-3 block at L39–62.
