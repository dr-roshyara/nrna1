*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back. Harness: step 1 90 s; step 2 sent 17:13:26Z, received at this save.*

# REVIEW REPORT — C13

*Transition review of S0071–S0075: §8.8, CON-0141, F25, the S0071/S0072 checks against §6.5 and §9.6, and the form-only rewrite of §1.14. I re-read my saved inventory first. The independence here is process independence only; model separation is not attestable.*

## 1. Assessment

- **§1.14 rewrite:** reliable as form. With the inserted `C*n*.` labels stripped, the body is byte-identical to the previous version. But the new header and the plain-language summary add or strengthen claims, and the section is labelled "reviewed" before it was reviewed.
- **§8.8:** C1 and C2 are sound within a narrower scope than written.
- **Largest risk:** F25 is mis-posed. One step of its logic is inverted, and the variable the evidence actually points to (whether a grant existed) is omitted.

## 2. Findings (ranked)

| # | Sev | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | §8.8 C3: "Only exclusion also uses up the pool of independent verifiers" | §8.8 L58; §8.7 C5 | Inverted. §8.7 C5 says it is the verifier who repairs (the forward-constraint path) that uses up verifiers. Exclusion keeps them. | F25 rests on a false comparison | Swap the two, or delete the sentence |
| 2 | high | defect | F25: "exclusion vs forward constraint" | S0074 L17–18; §8.7 C5 (S0056 L166) | S0056 gives the same forward rule: repairing would bar it from the next verification. S0074 obeys it (barred from V#3). They differ on (a) a grant: S0056 had none, S0074 did; (b) **role vs process**: §1.14.3 forbids the *Verification role* to repair, while S0074 repaired in a new *architecture* lane run by the same process. Mechanical re-checkability is only S0074's justification, not a variable shown to decide. | The fork as stated is not the one in the corpus | Restate F25: may a process that verified then take a producer lane on the same object, and is a grant sufficient? List the grant as an alternative explanation. |
| 3 | high | defect (scope) | CON-0141 / C1 "can never become truth" | S0073 L79; S0071 L48; S0072 L98 (V-3) | Non-consultation is a property of **one** reader, `foldSessions`. `session-resolve.php` holds 5 `mutationOwner` refs and is "handoff-blind" (V-3), so it is a second derivation that can disagree. Humans also read `note` and `humanAct` on COMPLETE. The premise "the fold is fixed" is weaker than what is needed: "the fold is the **only** reader that confers standing". | "Stored is not true" holds only for mechanism truth relative to a single reader | Scope C1 to "inert for the fold". Add the reader-set premise. Cite V-3 as a counterexample to a single producer. |
| 4 | high | defect | §1.14 status "reviewed in window C13" | §1.14 L8; STEP8 L1; commit d146015f8 | Written before review | Wrong status | Change it to "provisional, before review" until settlement |
| 5 | high | omission | Handoff definition contradicted | S0071 L66, L254 vs S0073 L151 | S0071 defines HANDOFF as moving mutation ownership. S0073 shows ownership passes at START. §6.5 L58 uses "confers nothing" but never marks S0071's definition as contradicted. "Mutation ownership" therefore has two senses (pending vs actual). | A homonym in a kernel concept (lifecycle/authority) | Record the contradiction. Qualify the two senses. |
| 6 | normal | defect | CON-0141 trivial case | — | A fold that reads nothing, or a mechanism that refuses everything, satisfies "derived values come only from the fold". No must-do requirement exists (what the fold *must* consult). | A lemma, not a law | Add the completeness obligation, or mark it as a derived lemma of CON-0134 |
| 7 | normal | omission (lineage) | "Four ways…" [S0073 L122] and "T2-05 met in a mechanism" | S0073 L122 ("drawn correctly"); T2-05 cites S0078 OQ-12; S0078 is the refinement S0073 verifies | One origin presented as a convergence | Overstates support | Attribute the list to S0078. One line, shown for BC-7 only. |
| 8 | normal | defect | §8.8 C2 "third and fourth instances" | §8.5 C9 (six cases) | Inconsistent tally. It also omits further instances in these sources: S0072 "13 records" vs 15 [S0072 L26]; S0073 "6 of 37" vs "37 of 37" [L88, L155] | Wrong count | Re-tally, as a count only |
| 9 | normal | defect | §1.14 header and §1.14.0 add claims | §1.14 L10, L14 | New claims: "one line of support"; "S0011–S0025 are the 2026-08-02/04 corpus" (not checked); "Track B" stated as fact, though the body says "V3 does not label itself so" (L444). The summary states ARCH-INV-1 and P-8 without "proposed". | "No claim was added" is false in a narrow sense | Mark the lineage note as Int. Put the status back into the summary. |
| 10 | normal | defect | Label collision in §1.14 | §1.14 L149–230, L503 | Claim labels C1–C14 collide with alternatives C1–C8, with "C1-b", with review windows "C2/C3/C4" and with "C4 diagram". L503 mixes "alternatives C1–C8" with "§1.14.5 C2–C5". | A ubiquitous-language homonym | Relabel the alternatives (e.g. Alt-1…8) or the claims (§1.14.1/C1) |
| 11 | normal | omission | Two different A–D defect taxonomies | S0073 L151 (violation / missing enforcement / missing distinction / mis-scoped); S0075 L171 (error *about* / defect *inside* / scope / model) | Neither is in §8.8. The *about vs inside* distinction bears on §8.7. Same letters, two meanings. | Missing concept | Add both to §8.7 or §8.8, qualified |
| 12 | normal | omission | S0072's new content treated as "no new claim" | S0072 L34 ("absence in practice ≠ refusal by contract"); S0073 L98–108 (precondition / verdict / consistency invariant) | These are the origin of §8.8 C1 and of the §9.6 cross-part reads, and neither is recorded | Lineage and concept gap | Add them to C1 and §9.6 |
| 13 | normal | omission | Model labels conflict | S0071 L11 (Opus 5); S0073 L34–38; S0075 L139–146 (Fable 5 per commit) | C4 says the model is "undeclarable". The corpus also *declares it inconsistently*. | C4 understated | Add it |
| 14 | normal | defect | Generalisation rule | §8.8 C1 "four ways", C2 head, C5 | General statements from one estate or one self-description | Rule breach | Write them as Hyp, "shown for BC-7 / S0075 only" |
| 15 | normal | defect | Independence factor count | §8.8 C3 quotes "two of the three … factors" [S0075 L163]; §8.6 C1 has seven | Not reconciled | Homonym ("factor") | Map S0075's three onto §8.6's seven |

## 3. Concept verdicts

| Concept | Verdict | Reason / scope |
|---|---|---|
| CON-0141 | **PROVISIONAL** | Correct as a lemma for a single fixed reader. A second reader exists (V-3). Trivially satisfiable. Taxonomy shown for BC-7 only. Derivable from CON-0134 plus the fold's read set, so not a primitive. |
| §8.8 C2 (count needs method) | PROVISIONAL | A normative rule from one estate. "Proportions held exactly" rests on drift of +2 transitions. |
| §8.8 C3 / F25 | **INVALID as posed** | The pool step is inverted; a confound (grant) and the role/process split are omitted |
| §8.8 C4 | VALIDATED | An existence claim; one case suffices; scope: a self-declared process |
| §8.8 C5 | PROVISIONAL | One self-description; its tie to I-7 is Int |
| §8.8 C6 | VALIDATED | Instances quoted correctly. It rightly avoids the unreproducible 6/37. |
| §1.14 rewrite | form VALIDATED; header and summary PROVISIONAL | Body identical; see findings 4, 9, 10 |

## 4. Coverage, new forks, source errors

- **Ranked gaps:** 2 (role vs process, grant) · 5 (Handoff and ownership) · 3 (reader set) · 12 · 11 · 13.
- **New forks:**
  - (a) Is non-consultation sufficient when there are several readers?
  - (b) Is separation of duties bound to a lane or to a process?
- **Source defects (separate):**
  - S0072 "13 records" (L26, L43, L98);
  - S0073 "6/37" vs "37/37";
  - S0074 "14 added" (git reports 16).
  The theory relies on none of them.

## 5. Next steps (in dependency order)

1. Fix the inverted sentence in C3 and restate F25 (done when the alternatives include the grant and role vs process).
2. Scope CON-0141 to a single reader and cite V-3 (done when the premise names the reader set).
3. Correct the §1.14 status, mark the header's lineage as Int, and put the status qualifiers back into the summary.
4. Relabel the colliding C-labels in §1.14.
5. Record the Handoff contradiction and the two senses of "mutation ownership".
6. Re-tally the count instances, add the A–D taxonomies and S0072's distinctions, and reconcile the independence factors.

No question for the Principal is needed.

## 6. Limits

- I did not read §8.5–§8.7 in full, nor §9.6 apart from grep hits, nor S0078.
- "S0049/S0055/S0059" was matched by path only.
- The date claim "S0011–S0025 = 2026-08-02/04" was not checked.
- Quote checks were spot checks of all 18 quotes in §8.8, by eye, not by tool.

## Appendix: computations

- **§1.14 integrity:** I ran Python difflib on `git show d146015f8~1:…` against the working file, from §1.14.1 to before §1.14.6, with `^\*\*C\d+\. ` → `**`. Result: one difference, a trailing blank line. The old header paragraph was moved verbatim to L69.
- **Pointers in the added blocks:** all resolve to their line positions. Examples: finding 2 → C2 L97; "Corrected 2026-10-18" → C4 L113; property 4 → C8 L248; seven defects → status box L212–219; RC-30a, RC-35a and D-6 → §1.14.3 C3; CON-0033, CON-0023 and CON-0015 → C10, C12 and C14.
- **Reference paths:** all 23 exist on disk.
- **Structure:** the H2 level of §1.14.0, §1.14.6 and §1.14.7 is inconsistent with the H3 level of §1.14.1–§1.14.5.
- **Claims index:** the index covers numbered heads only. Claims in unnumbered blocks are not indexed: definitions, the answer, the Root-acts bullet (CON-0054), and the status box.

**Running measure:** 15 findings · 0 critical · 5 high · 6 material omissions.
