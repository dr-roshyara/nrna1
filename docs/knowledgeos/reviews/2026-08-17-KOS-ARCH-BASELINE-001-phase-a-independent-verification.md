# KOS-ARCH-BASELINE-001 — Phase A
# Independent Verification Report

**Session `S1-verification-baseline-phase-a` · role `verification` · ACTIVE, mutation owner (verified via `workflow-state.php fold`, NOT the resolver — V-3 caveat honored) · grant `G-KOS-ARCHBASE-A-VERIFY` · 2026-08-17**

> ## VERDICT — THE BASELINE IS A FAITHFUL RECONSTRUCTION WITH FOUR PRECISION DEFECTS, NONE STRUCTURAL
> **11 of 12 sampled `Observed` claims re-derived TRUE by independent methods. All four `Declared` mechanism probes CONFIRMED by code re-derivation. All three producer-disclosed defects (D-1/D-2/D-3) CONFIRMED — plus one NEW same-shape internal contradiction (V-A) and three minor observations.** No defect changes the direction of any Phase-A conclusion; what is damaged is the precision and internal consistency of the executive summary and one evidence row. **Acceptance is the PO/ARB's act, not this report's** — the verification result it should weigh: *sound baseline, correct thesis, four sentences that must not survive into an accepted version unamended.*

```
─────────────────────────────────────────────────────────────────────────────
 STARTUP CHECK (commission requirement, run first)
 Am I the process that produced Phase A?   NO — this process's entire history
   in this estate is the 2026-08-16 KOS-ATTR-ARCH-001 rev-3 review (session
   34210a39-b8a4-42ca-b458-42630fe4b510). It began after Phase A was delivered
   (75bfcaae, 2026-08-15) and authored none of it, none of the architecture
   under reconstruction, and is not claude-code-session:fbc084f0.
 Prior contact, disclosed:                 during the rev-3 review this
   process independently read workflow-state.php — prior contact with one
   evidence source, NOT authorship of the artifact under verification.
 LIMITATION (INV-ATTR-2):                  this separation is DECLARED, not
   attestable. The record cannot verify which process wrote this report.
   In the assurance model's own ladder: Independence = Declared.
─────────────────────────────────────────────────────────────────────────────
```

**Method:** every checked claim re-derived by a method different from both the original and (where known) the producer's self-review method. Falsification attempted on the load-bearing claims, not confirmation of formatting. Producer disclosure `3d42546a` used as **leads only** — each of D-1/D-2/D-3 re-established from primary evidence before being marked confirmed. Drift controlled: `settings.json`, `registry.yaml`, and `.claude/scripts/` are **byte-unchanged since `75bfcaae`** (empty diffs), so today's measurements are valid measurements of the baseline's subject.

---

## 1 · Producer-disclosed defects — independently CONFIRMED

### D-1 — "8 wired hooks" is FALSE; actual is 10 · **CONFIRMED**
My method (JSON walk, not the producer's one-liner): `PreToolUse 5 · PostToolUse 2 · SessionStart 1 · Stop 2 = 10`, against a `settings.json` unchanged since the baseline commit. Phase A states 8 in §1, §6, and §11 — classified **`Observed`, confidence `high`**. Cross-check: 10 distinct hook scripts are wired. **A wrong `Observed`/high measurement is the defect class the classification scheme exists to prevent** — the severity is in the calibration damage, not the number.

### D-2 — "Four of seven components have no executable substance" is FALSE · **CONFIRMED**
My method (per-component asset census of the registry, unchanged since baseline): **CMP-003 and CMP-006 have zero assets — two, not four; of eight components, not seven.** CMP-005 has 2 assets, CMP-007 has 1, CMP-001 has 2, CMP-002 has 3, CMP-004 has 7, CMP-008 has 1. **The aggravation is confirmed too:** Phase A's own §3.1 table marks CMP-005 and CMP-007 *PARTIAL* — the executive summary contradicts the detail table in the same document. The producer's defensible restatement ("two of eight zero; two more partial or by-reference") matches my census.

### D-3 — the unflagged 7-vs-8 component discrepancy · **CONFIRMED, with added precision**
`Phase-03A-Reference-Architecture.md:19` declares **"Seven components"**; the registry carries **eight** (`CMP-001…008`). The added precision my re-derivation surfaces: Phase-03A **line 107 itself lists the composition root as a build element *beside* the seven components** ("3B builds: the composition root, the seven components…") — so the declared model treats the root as *not a component* while the registry models it *as one* (`CMP-001`, 2 assets). That is a genuine declared-vs-registry modeling divergence, of exactly the class Phase A's thesis exists to catch, and Phase A used "7" and "8" in different sentences without noticing.

---

## 2 · NEW findings — the extension the producer asked for ("what else is of D-2's shape?")

| # | Severity | Finding |
|---|---|---|
| **V-A** | **MODERATE** | **Phase A §6's own diagram sums to 10 while its caption says 8.** The diagram lists CMP-002: 3 hooks · CMP-004: 3 · CMP-005: 1 · "3 UNREGISTERED hooks" = **10**, under the caption "wires 8 hooks at 4 runtime moments." Same-page self-contradiction — D-2's exact shape, in a second location. It also upgrades D-1 from *miscount* to *internal inconsistency*: the correct number was already inside the document. |
| **V-B** | MINOR | §4 presents "Declared capabilities CAP-01…CAP-13" and silently omits CAP-04 from its table. The omission is **substantively correct** — `Phase-02.5:41` merges CAP-04 into CAP-03 by ARB observation — but a census document should say why a row is absent; as written it reads as either 13 capabilities or a missing row, and it is neither. |
| **V-D** | MINOR | §6's "22 matches for require/include/exec" in AST-015 is a **method-dependent count** (a near-identical pattern family yields 25; every match is the English word "requires" inside refusal strings). The conclusion — no dependency constructs, AST-015 is the dependency root — is **confirmed**; the bare number carried evidential weight without its method. |
| **V-E** | OBSERVATION (material for acceptance) | **"8 workflow records" is retroactively unverifiable — because of Phase A's own finding.** The records are gitignored with no history (U-8), so no one can ever audit the count as of 2026-08-15 (13 exist today; the producer correctly classifies the growth as temporal). The baseline's own snapshot number inherits the no-provenance defect it reports. Not an error — but the PO/ARB should accept the baseline knowing its machine-state figures are declarations of a moment that left no trace. |
| V-C | trivia | `.gitignore` lines 25 **and** 32 are duplicate identical entries (`.claude/runtime/`). Confirms the citation; also a two-line specimen of unnoticed duplication. |

---

## 3 · The sampled re-derivations (grant items a, b, c)

### (a) Classification honesty — 12 `Observed` claims sampled, 11 survive

| Phase-A claim (class) | My method | Result |
|---|---|---|
| 16 registered assets, contiguous `AST-001…016` | regex id-set extraction | ✅ exactly 16, no gaps |
| 8 wired hooks (`Observed`, high) | JSON walk | ❌ **10 — D-1** |
| 3 wired-but-unregistered hooks | per-name registry grep (0 hits each) vs wired set | ✅ exactly those three |
| 12 scripts | directory count minus README | ✅ 12 |
| all six ES standards `PROPOSED` | header census | ✅ six of six |
| `AST-016 → AST-015` one-way via `proc_open` | source: `:90` env-path, `:103` proc_open, `:288` interpretationAuthority self-doc | ✅ |
| AST-015 the dependency root | dependency-construct scan | ✅ (count trivia: V-D) |
| Platform → Product one-way | repo-wide grep of `app/` for `.claude` | ✅ **falsification attempted and failed**: the single hit is a docblock *comment* citing a plan path (`ChallengeRoutedReactionHandler.php:46`) — neither an import nor an invocation; the claim's careful wording ("imports or invokes") holds |
| workflow records gitignored/untracked | `.gitignore` 25/32 + `git ls-files` (0) | ✅ |
| CMP-004 holds 7 of 16 assets | census: AST-005/006/012/013/014/015/016 | ✅ |
| "four of seven no substance" (`Observed`) | per-component census | ❌ **D-2** |
| 6 contexts + 2 external declared, FROZEN, genuinely derived | source headers + §346 derivation | ✅ (the derivation is real, not asserted) |
| I-7: no code path reads process identity for a gate | identity-API scan of all scripts | ✅ `getmypid` appears once — a temp-file name, not a gate; `getenv` once — the mechanism path (U-6), not a gate |

**The four `Declared` mechanism probes** (dual-ACTIVE possible · second START transfers ownership unguarded · no CLOSE vocabulary · `recordedBy` an unvalidated free string on REGISTER/HANDOFF/START) — **all four re-derived from the `assertTransitionAllowed`/`foldSessions` source by this session: CONFIRMED.** Phase A's choice to classify them `Declared`/medium rather than borrow them as `Observed` was honest; they are now code-verified and may be upgraded by whoever revises the document.

**Nothing classified `Observed` was found to rest only on a document assertion.** The two `Observed` failures (D-1, D-2) are *wrong measurements*, not disguised declarations — a different defect class, disclosed as such.

### (b) The central rule — held on the sample
Re-derived three boundary claims: BC-realization verdicts in §3.1 rest on registry status fields and hook wiring, not names; the §3.2 seventh-context claim is explicitly `Inferred` with evidence both ways and a matching Unknown (U-3) — the *discipline* of not choosing is itself compliance; the §2 system boundary rests on hook execution plus a *flagged* rule-vs-practice deviation (`ES-005.1`), not on folder identity. **No instance found of a boundary inferred from a name alone.**

### (c) Evidence hierarchy — respected
The document demonstrably worked declared architecture (frozen Phase-02/03A) → wiring (`settings.json`) → runtime records → executing code → directory structure, and where levels disagree it records the disagreement (e.g., §2 boundary rule-vs-practice; §6.2 registry-first breach) without adjudicating. Compliant.

### (d) Self-authorship disclosure — present and honored
The mandatory disclosure is at the head and in §11; self-authored elements are classified `Declared` unless re-measured *in that session*; the handover's declared list is carried with its "not exhaustive, absence proves nothing" warning verbatim. **No self-authored element found presented as independently validated.**

### (e) Architecture Unknowns — present and substantive
Nine unknowns, each with a stated reason it cannot be settled in Phase A. Spot-checked for false unknowns (unknowns recorded where evidence existed): U-8 is genuinely unverifiable (no history); U-9's "runtime behaviour" genuinely requires execution, though a static read of the three scripts was available and would have narrowed it — a scoping choice, not a false classification. **No unknown was found to be filled by silent inference elsewhere in the document.**

### (f) Target-architecture leakage — none found
Scanned for prescriptions: findings are recorded with "not repaired, not judged"; the sharpest sentence (§1.5, on gitignored state) is rhetorical color on a measured fact, and proposes nothing. **The document reconstructs; it does not design.**

### (g) The Option A separation condition — **cannot be established from the record; saying so**
The record's own seq-1 `executionContext` states it: separation is *"DECLARED and NOT ATTESTABLE (INV-ATTR-2): the record cannot verify which process executes this — compliance rests on where the PO/ARB starts the session."* Nothing in the record, the commits, or the git identity (one shared committer identity across all lanes — Phase A's U-7) can distinguish the producing process from `fbc084f0`. **Verdict: the condition's satisfaction is Declared — by the producer, by the session log, and by the PO/ARB's own routing of the sessions. It is not, and cannot be, verified from the record. The same holds for this report's independence.**

---

## 4 · Materiality — do the defects change any conclusion?

| Defect | Conclusion affected? | Materiality |
|---|---|---|
| D-1 (8→10 hooks) | none — no thesis rests on the count | calibration damage: an `Observed`/high figure was wrong; the classification currency is what acceptance relies on |
| D-2 (4/7→2/8 zero-substance) | the executive summary **overstates** the declared-vs-executing gap; §3.1 already carries the correct picture | **material to the most-read section**; direction preserved, magnitude wrong, self-contradicting |
| D-3 + V-A (unflagged 7-vs-8; diagram vs caption) | no stated conclusion becomes false | a missed instance of the document's own thesis class; an internal contradiction |
| V-B/V-C/V-D/V-E | none | precision hygiene; V-E is an acceptance-context fact, not a defect |

**The central thesis survives everything:** declared and executing architectures differ materially; the rule corpus is the substance and the code is thin; exactly one component carries the new mechanism; authority is human-only by design; the platform's machine state is untracked. Every one of those five statements was independently re-derived here and **held** — including the two whose supporting *numbers* were wrong.

## 5 · What the PO/ARB decision should weigh (recommendation, not decision)

**Do not accept v1.0 unamended** — an accepted baseline whose executive summary contradicts its own detail table would enter the canon carrying known-false `Observed` claims. The bounded repair is small: §1 statements 1 (hook count, component count), the §6 caption, the §11 hook row, a flag on the 7-vs-8 census, and optionally the V-B one-word note. Whether that is an erratum, a v1.1, or acceptance-with-recorded-errata is the PO/ARB's choice; **under R-34 the producer performs the correction and this or another verification confirms it — this report must not be read as pre-approving any corrected text it has not seen.**

## 6 · What this verification did not do

No repair · no modification of Phase A (`75bfcaae` untouched — verified byte-identical at report time) or any `KOS-ARCH-BASELINE-001` artifact · no target-architecture proposal · no Phase B or C work · **no acceptance** — that is the PO/ARB act that follows · no qualification, adoption, or closure · no Election work (`A-8`) · no self-certification — this report's independence is **Declared** (§ startup block) · **its own assignment not completed** — the `COMPLETE` is a Governance act for another pen.

---

**Traceability:** grant `G-KOS-ARCHBASE-A-VERIFY` (checks a–g + falsification duty) · record seq 4 (REGISTER, independence clause) · seq 5 (HANDOFF `T-BASELINE-PHASEA-EVIDENCE`) · seq 6 (human START) · Phase A `75bfcaae` (unmodified) · producer disclosure `3d42546a` (leads, independently re-established) · commission `2026-08-15-KOS-ARCH-BASELINE-001-phase-a-commission.md` · `.claude/settings.json` + `.claude/platform/registry.yaml` (both byte-unchanged since `75bfcaae`) · `Phase-03A-Reference-Architecture.md:19,107` · `Phase-02.5-Certification-Plan.md:41` · `Phase-02-Domain-Model.md:6,56,346` · `.claude/scripts/workflow-state.php` + `session-resolve.php:90,103,288` · `.gitignore:25,32` · `V-3` (resolver handoff blindness — fold used instead) · `R-34` · `P-2` Class-B · `INV-ATTR-2`.

---

> # VERIFICATION DELIVERED — evidence and recommendation only · acceptance is the PO/ARB's act · this session does not complete its own assignment
