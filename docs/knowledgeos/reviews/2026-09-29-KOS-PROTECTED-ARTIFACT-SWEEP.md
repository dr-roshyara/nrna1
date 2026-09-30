# Protected-Artifact Sweep — Research Result

**Date:** 2026-09-29. Empirical experiment, not architecture. No implementation, no ML, no
EKS/PKS boundary ruling performed here, per instruction.

---

## 0 · Correction carried forward from the prior document

`2026-09-29-KOS-evidence-fusion-model.md` §11 stated "201 of 379 (53%) are not `CONFIRMED`."
**Verified: `159 + 35 + 6 = 200`, not 201.** The percentage (52.8%) was approximately right; the
count was wrong. Corrected here, disclosed rather than silently edited in the source document (a
pointer correction is added there).

## 1 · Research question

Does protected-artifact evidence (independent of commit-message citation) reveal genuine
governance-relevant information that citation-gated evidence systematically cannot observe? Is the
`9f83a369c` case isolated or systematic?

## 2 · Population and scope

All 22 real ledgers in `.claude/runtime/workflow/`. All commits in the **full repository history**
(not the 379-commit "KOS-"-citing subset) that touch any mechanically-derived protected artifact.

## 3 · Evidence extraction method

For every grant, extract text following `Does NOT authorize:` (case-normalized) up to the next
sentence boundary, then extract filename-shaped tokens (`\w[\w\-/]*\.(json|php|py|yaml|yml)`) from
that text only. This is **deterministic extraction from explicit evidence** — `DERIVED`, per the
provenance classes required. No inference from directory conventions was performed.

## 4 · Protected-artifact derivation — the real, disclosed result

**Only 1 of 22 work items has a mechanically-derivable protected-artifact rule at all:**

| Work item | Grants | Result |
|---|---|---|
| `KOS-CONTRACT-NEUTRALITY-001` | 40 | `DERIVED` → `{expected.json, deptrac.yaml}` |
| `KOS-AIP-GOV-STATE-DURABILITY-ADR` | 26 | `NO_FILE_TOKEN_IN_TEXT` — has "does not authorize" language, but no filename-shaped token found |
| All other 20 work items | 0–28 each | `NO_PROTECTED_ARTIFACT_RULE` |

**This is itself the first real finding, not a limitation to apologize for:** the vast majority of
this repository's real governance grants express their constraints in a way that does not reduce
to a clean, machine-checkable file list. A protected-artifact sweep, run honestly, is currently
**narrow by construction** — not because the method is weak, but because the corpus mostly doesn't
express constraints this way yet.

## 5 · Sweep results — full repository history, both tokens

| File touched | Commits (full history) | Dates |
|---|---|---|
| `deptrac.yaml` | 3 | 2026-06-26, 2026-07-09 ×2 |
| `expected.json` | 3 | 2026-08-04, 2026-08-16, 2026-09-28 |

**All 6 hits, classified individually, per the required non-binary vocabulary:**

| Commit | File | Date | Cites `KOS-CONTRACT-NEUTRALITY-001`? | Relevant grant active? | Classification |
|---|---|---|---|---|---|
| `baaded576` | `deptrac.yaml` | 2026-07-09 | No | No — predates the work item's own earliest grant (2026-08-16) by 5 weeks | `PROTECTED_ARTIFACT_OBSERVED`, `NOT_APPLICABLE` (rule did not yet exist) |
| `18dac93ff` | `deptrac.yaml` | 2026-07-09 | No | No — same | `PROTECTED_ARTIFACT_OBSERVED`, `NOT_APPLICABLE` |
| `bc9ded4b9` | `deptrac.yaml` | 2026-06-26 | No | No — same | `PROTECTED_ARTIFACT_OBSERVED`, `NOT_APPLICABLE` |
| `d61bf5e84` | `expected.json` | 2026-08-04 | No | No — predates the work item's earliest grant (2026-08-16) by 12 days | `PROTECTED_ARTIFACT_OBSERVED`, `NOT_APPLICABLE` |
| `286cad1e1` | `expected.json` | 2026-08-16 | No — cites a **different** work item, `KOS-LCOM4-CONTRACT-001` | Yes, under the cited work item's own grant `G-KOS-LCOM4-CONTRACT-APPLY` (real, complete `REGISTER→HANDOFF→START`, already confirmed §10) | `CITED` (of a different, applicable work item) → `AUTHORIZED` |
| `9f83a369c` | `expected.json` | 2026-09-28 | **No citation of any kind** | Yes — within the work item's active grant era (2026-08-16–2026-09-27); the specific grant naming this file's modification as forbidden was active | **`UNCITED` + `UNAUTHORIZED`** — the one real case |

**No new violation was found beyond `9f83a369c`.** Every other hit is either temporally
inapplicable (the rule didn't exist yet) or independently, cleanly authorized under a different,
legitimately-applicable work item.

## 6 · Comparison M0–M4

| Model | What it uses | Result on the 6-hit protected-artifact population |
|---|---|---|
| M0 — citation only | commit → work-item citation | 5/6 `UNCITED` to `KOS-CONTRACT-NEUTRALITY-001` (only `9f83a369c`'s *absence* of citation is diagnostic; `286cad1e1` cites a *different* item) |
| M1 — citation + grant | + grant evidence | No change — `9f83a369c` still unresolved by citation alone |
| M2 — protected artifact | + artifact touched, rule matched | All 6 flagged as touching a protected file — **this is the entire value of the sweep**: it surfaces all 6 candidates that M0/M1 would never even present for review |
| M3 — + temporal containment | + date vs. grant-era window | Correctly excludes 4/6 as `NOT_APPLICABLE` (pre-dates the rule) — leaves exactly 2 genuinely testable cases (`286cad1e1`, `9f83a369c`) |
| M4 — full evidence | + citation of *any* applicable work item | Correctly separates the 2 remaining cases: one cleanly authorized under a different item (`286cad1e1`), one genuinely unauthorized (`9f83a369c`) |

**Measured, not asserted:** M2 (protected-artifact matching) is the single evidence dimension that
does the most work here — it is what surfaces all 6 candidates at all. M3 (temporal containment)
does the second most work — it eliminates 4 of 6 as not-yet-applicable, cheaply and correctly. M4
resolves the remaining ambiguity for `286cad1e1` only because it can recognize citation of *any*
applicable work item, not just the one under test — a real, disclosed requirement this sweep's
initial scope (single work item) would otherwise have missed.

## 7 · Newly discovered cases

**None, beyond `9f83a369c`.** Explicitly searched for and not found.

## 8 · Independent verification status, and the falsification result

**`SUPPORTED BY ONE INDEPENDENT CASE — INSUFFICIENT FOR GENERALIZATION.`** Per the redirect's own
required reporting rule, this is stated as the honest result, not softened. One real,
independently-verified case (`9f83a369c`, `6b983bc12`) demonstrates the blind spot is real. Six
total protected-artifact hits in the whole repository's history is not enough to claim the blind
spot is *systematic* — only that it is *real* and *not yet contradicted*.

## 9 · Quantified uncertainty reduction

On the original 379-commit corpus (`2026-09-29-KOS-evidence-fusion-model.md` §11.3), comparing
raw and **normalized** entropy (`H / H_max`, since S1 and S4 have different category counts and
raw entropy alone is not a fair comparison):

| Stage | Categories | Raw entropy `H` | `H_max` (log₂ categories) | Normalized `H/H_max` |
|---|---|---|---|---|
| S1 (citation only) | 2 | 0.9812 bits | 1.0 | **98.1%** — close to maximally uncertain (near 50/50) |
| S4 (full evidence) | 4 | 1.4489 bits | 2.0 | **72.4%** — meaningfully more concentrated than chance |

**Interpretation, stated carefully:** the drop in normalized entropy (98.1% → 72.4%) indicates the
S4 evidence set produces a genuinely more structured, less uniform distribution than citation
alone — this is evidence that additional dimensions carry real information, not merely evidence
that more categories exist. **This does not, by itself, validate accuracy** — no ground-truth
labels exist for the 379-commit population to compute an error rate, and none is manufactured here.

## 10 · Falsification results

- **Falsified:** the informal earlier claim that "each dimension reduces uncertainty" merely
  because output categories increased. Corrected to the normalized-entropy comparison above, which
  is the actual defensible claim.
- **Not falsified, strengthened:** the core hypothesis that citation-gated evidence is blind to a
  real class of violation — the sweep found the mechanism precisely (`286cad1e1` vs. `9f83a369c`
  differ only in whether *some* applicable citation exists, not in file sensitivity).
- **Falsified by absence:** no evidence found that the blind spot is common — 6 hits, 1 real case,
  is not a base rate claim.

## 11 · Kernel-candidate reassessment — corrected vocabulary, more cautious than the prior pass

| Candidate | Prior tag | Corrected assessment |
|---|---|---|
| Canonical representation (work-item identity) | `SUPPORTED` | **`SUPPORTED WITH LIMITATION`** — the 58/42 split (§11.3 of the fusion model) shows information content (near-maximal entropy is itself informative about the corpus's citation discipline) but does not by itself demonstrate this representation is *necessary* for correct determination, only that it is *used* and *partially predictive* |
| Authorization/scope | `HYPOTHESIS, refinement pending` | **`SUPPORTED WITH LIMITATION`** — the sweep confirms protected-artifact matching (M2) does real, irreplaceable work (§6), but only demonstrated on 1 work item of 22; generalization is `UNKNOWN` |
| Governed lifecycle (temporal/transition) | `SUPPORTED` | **`SUPPORTED WITH LIMITATION`** — temporal containment correctly excluded 4/6 hits as inapplicable (real, useful work), but a temporal "mismatch" is not automatically a violation — it correctly narrows the candidate set, it does not itself prove wrongdoing (`286cad1e1` would have looked temporally fine and still needed the citation check to resolve) |
| Uncertainty/indeterminacy as distinct state | `SUPPORTED` | **`SUPPORTED`** — retained; the sweep's own vocabulary (`NOT_APPLICABLE`, distinct from both `AUTHORIZED` and `UNAUTHORIZED`) was required to correctly classify 4 of 6 real hits, and collapsing it would have produced a wrong answer |
| Provenance | `UNKNOWN this pass` | **`UNKNOWN`** — not independently tested this pass either |
| Observation | `SUPPORTED by construction` | **`UNKNOWN — not an empirical kernel result`** — this is a logical precondition of running any experiment at all, not a finding the experiment tested or could falsify. Withdrawn as a `SUPPORTED` claim, per the redirect's own correct objection. |
| Rule/classification (never reasoning) | `SUPPORTED by construction` | **`SUPPORTED WITH LIMITATION`** — the sweep's own classification step is exactly this pattern (match, don't reason about intent) and it worked correctly on all 6 real cases; "by construction" is retained as a caveat since it is partly definitional |

## 12 · Fuse refinement decision

**Adopt the protected-artifact sweep as a required, permanent, citation-independent second entry
point to `Fuse` — but scoped honestly to the 1 work item where it is currently derivable.** Not
adopted as a general mechanism for all 22 work items, since 21 of them have no extractable rule
to sweep with. This is a narrow, evidenced addition, not a redesign.

## 13 · Remaining unknowns

- Whether the 21 work items without a derivable rule genuinely have no negative-scope constraints,
  or merely express them in a form this extraction method cannot see (`INFERRED` territory,
  deliberately not entered here).
- Whether `KOS-AIP-GOV-STATE-DURABILITY-ADR`'s `NO_FILE_TOKEN_IN_TEXT` case (has "does not
  authorize" language but no filename token) represents a real gap or a rule about something other
  than files (e.g. actions, not artifacts) — not investigated this pass.
- Generalizability of the blind-spot finding beyond this one work item — explicitly `UNKNOWN`,
  stated per §8.

## 14 · Next smallest decisive experiment

Extend the extraction method (still `DERIVED`, not `INFERRED`) to catch non-filename-shaped
protected references — e.g. `KOS-AIP-GOV-STATE-DURABILITY-ADR`'s unmatched "does not authorize"
clauses — and re-run the derivation step (§4) only, before any further sweep. This is cheap
(re-run one script against already-loaded data) and would show whether the 21-of-22 "no rule"
result is a real corpus property or an extraction-method limitation.

---

**Traceability:** `2026-09-29-KOS-evidence-fusion-model.md` §§8–11 (the originating finding and
the corrected arithmetic) · this session's direct `git log --all` sweep, both protected tokens ·
`6b983bc12` (the independent verification that first surfaced `9f83a369c`) · `KOS-LCOM4-CONTRACT-001`
ledger (for `286cad1e1`'s own, separately valid authorization).
