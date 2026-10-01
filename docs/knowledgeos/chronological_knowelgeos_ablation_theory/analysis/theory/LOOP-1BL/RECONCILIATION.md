# R5 reconciliation: independent finite-state verification of FORMAL-THEORY-R4 §7

| | |
|---|---|
| Verifier | a fresh, isolated headless Claude Opus 5.5 session (human act F-LOG-0223), in `~/r5-verify`, outside the repo. It saw `R5-SPEC.md` **without proofs or expected outcomes** and wrote its own enumerator, `verify.py` (stdlib only) |
| Isolation audit | Reads: only its 2 bundle files. Bash: only `python3`/`ls`/`wc`. **Disclosed deviation:** it wrote scratch output to `/tmp/r5out.txt` / `/tmp/r5err.txt` (writes outside its folder; no reads outside) |
| Seal | `RETURN.sha256`, committed (892701626) before the results were read; verifies 6/6 |
| Reproducibility | The main analyst re-ran `verify.py`: **byte-identical RESULTS.json**, about 130 s |
| Frame size | U: 665,920 histories (F1 ∧ F2); U₆: 100,880 |

## Comparison with the sealed expected outcomes (FORMAL-THEORY-R4 §7.2)

| Checks | Expected | Verifier | Match |
|---|---|---|---|
| K1–K7 (class structure, near-nesting, **D1 (i), (i′), (ii), (iii)**, Lem 3.7) | true | TRUE | ✅ |
| K8.1 | O₁ difference exactly σ = (q, q) for every o | TRUE; difference **= {(q, q)} for every o**, derived independently | ✅ |
| K8.2–K8.7 (each countermodel breaks its assumption) | as stated | TRUE | ✅ |
| K9.1–K9.8 (sufficient conditions; Q*, Q1ʙ, Qm, Q′) | as stated | TRUE | ✅ |
| K10.1–K10.7 (performative ambiguity, Lem 5.2, O⁺) | as stated | TRUE | ✅ |
| K11.1–K11.2 (no norm→work or absence constraint; no r-bound) | true | TRUE | ✅ |
| K12.1 (dependence groups) | O none; O₁ σ; O₂ f; O₃ Ref; O₃′ con; O₄, O₄′ additions; O₈ d | identical; additionally split O₄ = (+work), O₄′ = (+act) | ✅ (refinement) |
| K12.2 (frame closure failures) | U none; U₅ A5; U₇ A7; U₉ A9 | identical (U₇: A7 via +act and +work; U₉: A9 con-only) | ✅ |

**Result: 33/33 checks agree; 0 deviations.** The verifier recorded 20 reading choices (NOTES.md). None was flagged as outcome-tuned.

## What this establishes and what it does not
- **Established, as a finite check:** in the finite universe U and the listed variants, every §7 claim of R4 holds, by exhaustive enumeration, implemented independently of the proofs.
- **Not a proof of the generic statements:** Prop 4.1 for an arbitrary (O′, 𝓗′) is untestable in U (§7.3). The generic results rest on the reviewed proofs (1bk r2).
- **Within-family:** the verifier is Claude, though on a different reasoning path (code vs prose).
- **Not tested:** Def 4.9 / Prop 5.4(c), (+actor), phenomena needing larger parameters, and Conj 5.5.
- **Pending:** a human spot-check (human act F-LOG-0223).
