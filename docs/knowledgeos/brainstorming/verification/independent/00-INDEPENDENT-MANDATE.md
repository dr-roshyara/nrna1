---
artifact: 00-INDEPENDENT-MANDATE
date: 2026-08-30
status: DISCLOSURE + SCOPE
---

# 00 · Mandate, and a Disclosure That Must Come First

## 1. Disclosure — I am not context-free, and saying otherwise would be the first error

The mandate asks for a **fresh, independent verification researcher** who is *"NOT a continuation of the
previous Claude session."* **I cannot truthfully claim that.** This process ran the immediately
preceding adversarial re-verification pass in the same session and carries its context.

Declaring independence I do not have would be exactly the failure this corpus's own governance
discipline exists to prevent (`INV-ATTR-1/2`; the estate's repeated start-gate refusals over
pre-declaration reads). So the disclosure is made first, and the mandate is then executed with a
**compensating method** rather than a false claim:

| Compensation | How it is applied here |
|---|---|
| **Primary sources only** | Every finding below is anchored in a corpus file or an executed run, never in a prior verification artifact. Prior verdicts are cited only as *claims under test*. |
| **New method, not new opinion** | Whole-corpus definitional scans, step-sequence archaeology by timestamp, execution of all three reference implementations, and *constructed* K candidates — none of which the prior passes performed. |
| **Falsify my own prior findings too** | Where this pass contradicts the previous pass, that is stated (§ `11-CONTRADICTION-REGISTRY`, `SELF-1`…`SELF-3`). Three prior findings are corrected here. |
| **Contamination boundary measured, not assumed** | § `01` establishes by timestamp which corpus files predate the verification programme and which do not. |

**Read this pass as: a differently-instrumented second look by a non-independent observer, whose
findings stand or fall on primary evidence that any third party can re-run.** Where it agrees with
the previous pass, that agreement is worth less than the disagreements.

## 2. What was executed

| Artifact | Command | Result |
|---|---|---|
| `zero_reference.py` | `python3 zero_reference.py` | exit 0 — 8/8 falsification tests PASS |
| `ladder_dc_reference.py` | `python3 ladder_dc_reference.py` | exit 0 — all checks PASS |
| `exp01_recheck.py` | `python3 exp01_recheck.py` | exit 0 — negative verdict CONFIRMED and *provable* |
| PHP `KnowledgeOs*Test` | `vendor/bin/phpunit --filter KnowledgeOs` | **10 tests, 34 assertions, 0 failures** |
| constructed attacks | `attack.py` | 3 dependency cycles found; K/Σ/Γ counterexamples |
| concept ledger | `ledger.py` | 34 concepts scanned across 1 555 md files |

## 3. Scope boundary — what this pass does NOT do

Per mandate §20: nothing is repaired, no definition is invented, no competing normative alternative
is chosen, no candidate is promoted, no contradiction is resolved by fiat. **Every proposal-shaped
sentence in these artifacts is marked `VERIFIER RECOMMENDS` and is a recommendation to a human, not
an act.** One normative decision is isolated and put to the human unanswered (`14-INDEPENDENT-VERDICT`
§6).

## 4. Deviation from the prescribed artifact list

None. All fourteen artifacts are produced under the prescribed names.
