# R2 (undefined-term check): disposition of every candidate on the working book

*2026-10-19. By the Professor, on the Principal's instruction ("record a disposition for every candidate; this is the false-positive count"). Input: `PROFESSOR/checks/undefined-terms.working.txt` (75 candidates). R2 is **proposed, not in force**. False negatives are measured by the Rule Reviewer's next planted-gap test, not here.*

**Codes:** T = true candidate, a term or code used without definition (a real finding for stage 2) · R = a review/revision label (removed with the inline review notes, or history in a change log) · S = a source code cited with its source (acceptable; no book definition needed) · D = defined in place, but the tool's definition forms missed it · O = ordinary English · I = a tokenisation artefact.

| # | Tier | Token | First section | Disposition | Reason |
|---|---|---|---|---|---|
| 1 | A | pinning | 01.14 | **D** | defined at its home §1.16.6 (pinned or live with detection); the occurrence cross-references it |
| 2 | A | unattested | 05.07 | **O** | ordinary adjective |
| 3 | A | annulment | 01.14 | **O** | ordinary (legal) English |
| 4 | A | KT-8 | 00.06 | **T** | background-note label used in §0.6 before Ch. 1's index explains it (01-basis/B-10-knowledge-transfer); define at first use |
| 5 | A | telo | 00.05 | **I** | tokenisation artefact of "telos" |
| 6 | A | LA-4 | 01.08 | **S** | cited with its source (GA L401) |
| 7 | A | RK-1 | 01.99 | **S** | cited with its source (S0026, "rule RK-1"), in the change log |
| 8 | A | undeterminable | 01.14 | **O** | ordinary English |
| 9 | A | worksheet | 01.14 | **O** | ordinary English |
| 10 | A | recasting | 02.09 | **O** | ordinary English |
| 11 | A | laminar | 03.10 | **D** | defined in place ("A family is **laminar** if …", §3.10 C4); the definition form was missed |
| 12 | A | mechanizing | 01.14 | **O** | ordinary English |
| 13 | A | snap | 01.16 | **D** | defined in place ("let snap(p) be the canonical snapshot …", §1.16) |
| 14 | A | truster | 09.09 | **D** | introduced in place as the relying party x (§9.9) |
| 15 | A | unminted | 01.13 | **O** | ordinary derivation of "mint" |
| 16 | A | fetche | 01.17 | **I** | tokenisation artefact of "fetches" |
| 17 | A | subtree | 02.08 | **O** | ordinary mathematical English |
| 18 | A | rater | 06.04 | **O** | ordinary English |
| 19 | A | regrouped | 08.03 | **O** | ordinary English |
| 20 | A | KBI-1 | 00.09 | **S** | cited with its source (V3 L467, cited-only) |
| 21 | A | ALLOC-4 | 01.17 | **S** | quoted with its rule text ("every analysis states the world it was true of") |
| 22 | A | AR-11 | 08.01 | **T** | label used in §8.1 ("a blind extraction (AR-11)") without explanation or source; define or cite |
| 23 | A | codified | 00.07 | **O** | ordinary English |
| 24 | A | blamed | 01.08 | **O** | ordinary English |
| 25 | A | misdescribing | 01.10 | **O** | ordinary English |
| 26 | A | annulling | 01.14 | **O** | ordinary English |
| 27 | A | unpoliced | 01.14 | **O** | ordinary English |
| 28 | A | dialog | 01.14 | **O** | ordinary English |
| 29 | A | mechanize | 01.14 | **O** | ordinary English |
| 30 | A | unexplainable | 01.16 | **O** | ordinary English |
| 31 | A | voided | 02.05 | **O** | ordinary English |
| 32 | A | initio | 02.05 | **O** | Latin phrase "ab initio", ordinary |
| 33 | A | obedient | 04.01 | **O** | ordinary English |
| 34 | A | lexicographically | 05.04 | **O** | ordinary mathematical English |
| 35 | A | staffing | 05.07 | **O** | ordinary English |
| 36 | A | workbenche | 06.07 | **I** | tokenisation artefact of "workbenches" |
| 37 | A | undercounting | 08.06 | **O** | ordinary English |
| 38 | A | undeclarable | 08.08 | **O** | ordinary English |
| 39 | A | attestor | 08.10 | **O** | ordinary English |
| 40 | A | unfaithful | 09.09 | **O** | ordinary English |
| 41 | B | RQ-002 | 00.00 | **D** | explained in Ch. 0's source list ("the RQ-002 General Knowledge Constitution") |
| 42 | B | C-1 | 00.01 | **T** | homonym: "batch C-1" (the book's corpus batch) and C-1 of S0002/the workflow engine (§1.10 lists the source senses, not the batch label); qualify (N1-03) |
| 43 | B | CV-1 | 00.02 | **T** | label used in 9 sections ("kept apart per CV-1 §2a") without a definition; S0022 also has a CV-1 (an open question): define or qualify |
| 44 | B | CAP-001 | 00.02 | **T** | registry capability ID used as a name in 8 sections; the book never says what CAP-001 is; define at first use |
| 45 | B | DP-2 | 01.14 | **D** | explained in place ("the design policy DP-2 of CAP-002") |
| 46 | B | C-5 | 01.10 | **D** | §1.10 explains both source senses ("correction propagation"; "Separation Attestation") |
| 47 | B | D-1 | 03.05 | **D** | explained in place ("D-1…D-7 (governance, method, …)") |
| 48 | B | SC-2 | 01.14 | **D** | its home is §2.8.3 (the SC-2 ruling on scope), cross-referenced |
| 49 | B | SD-1 | 05.02 | **S** | cited with its source line (S0024 [L…]) |
| 50 | B | H-3 | 01.11 | **D** | explained in place ("the question whether knowledge spaces nest (H-3)") |
| 51 | B | PM-1 | 01.02 | **S** | cited with its source (S0012 L152; S0018 L129) |
| 52 | B | P-7 | 01.06 | **S** | quoted with its source (S0018 L106) |
| 53 | B | H-08 | 01.00 | **R** | an audit label of the book's own baseline audit ("Audit H-08, E-01, E-06"): an inline review note, removed with them |
| 54 | B | Tier-1 | 03.06 | **D** | defined by the quotation ("Only Tier-1 items — domain-free ∧ binding-free ∧ evidence-free", S0024 L137) |
| 55 | B | C-14 | 08.04 | **T** | used in §8.4 ("C-14: absent, then live, then contested") without saying what C-14 is (a capability row of S0090); define or cite |
| 56 | B | R-6 | 04.01 | **D** | explained in place (the edge "guides product engineering") |
| 57 | B | V-1 | 05.06 | **D** | explained in place ("note V-1, an imprecision in the repair's supersession accounting") |
| 58 | B | N-10 | 00.05 | **S** | quoted with its source (S0023 L168) |
| 59 | B | F-01 | 01.02 | **R** | a review-finding label ("review C3 F-01; RC-36a"): an inline review note, removed with them |
| 60 | B | F-14 | 01.99 | **R** | a review-finding label in the change log (01.99): history, kept there; not a defect |
| 61 | B | B-11 | 00.02 | **S** | a background note, listed in Ch. 1's source list (01-basis) |
| 62 | B | H-01 | 00.03 | **R** | an audit label ("audit C-02, H-01"): an inline review note, removed with them |
| 63 | B | C-2 | 01.13 | **S** | cited with its source line (S0014 L53) |
| 64 | B | MM-1 | 03.00 | **D** | explained in place ("COMPLETE but unadopted (MM-1)") |
| 65 | B | OQ-10 | 06.05 | **S** | cited with its source (S0078 L258) |
| 66 | B | G-1 | 06.05 | **S** | cited with its source (S0078 L208) |
| 67 | C | keep | 01.09 | **O** | ordinary English |
| 68 | C | adopt | 01.13 | **O** | ordinary English |
| 69 | C | action | 03.09 | **D** | defined in §3.9's vocabulary table (act / action); the table form was missed |
| 70 | C | port | 03.09 | **T** | architecture term ("through a port") used in §3.9's definition table without a definition; define (hexagonal port) or rephrase |
| 71 | C | agent | 03.09 | **O** | ordinary English |
| 72 | C | registration | 06.05 | **O** | ordinary English (a Governance registration) |
| 73 | C | separation | 08.06 | **O** | ordinary English |
| 74 | C | untracked | 08.09 | **O** | inside a quotation of S0090 (git sense); ordinary |
| 75 | C | correct | 09.09 | **O** | ordinary English |

## Counts

| Disposition | Tier A | Tier B | Tier C | Total |
|---|---|---|---|---|
| T | 2 | 4 | 1 | 7 |
| R | 0 | 4 | 0 | 4 |
| S | 4 | 8 | 0 | 12 |
| D | 4 | 10 | 1 | 15 |
| O | 27 | 0 | 7 | 34 |
| I | 3 | 0 | 0 | 3 |
| **all** | 40 | 26 | 9 | 75 |

**False positives** (no defect: S, D, O, I): **64 of 75**. **Actionable:** 7 true candidates (T) + 4 review labels (R, three of them removed with the inline notes; F-14 stays in the change log as history).

**What the count says.** As drafted, R2 is a reading list with a low hit rate (about 1 in 7 actionable). Most false positives are ordinary English in tier A (27 of 40) and in-place definitions the forms miss (15 overall). Two cheap refinements are suggested by this count:
- a stoplist of derived English forms (un-/-ing/-able words present in the system dictionary) for tier-A words;
- one more definition form ("**X** if", "let X be").

Neither is adopted here: tuning on the same list would not be validation. Precision should be re-measured on a fresh book state.

**The 7 true candidates go to stage 2 as real findings:** KT-8 (§0.6), AR-11 (§8.1), C-1 (homonym), CV-1, CAP-001, C-14 (§8.4), "port" (§3.9).

**Known case at adbe16c5c** (read-only, recorded in ACCESS-RECORD; reported as a known case, **not** as validation): on the book at that commit, R2 as drafted flags **neither "lane" nor "BC-7"**. BC-7 occurs in 2 sections, below tier B's threshold of 3. In the sources §6.5 cited then, "lane" occurs 64 times but is never backticked or bolded, so tier C's term-mark condition fails. The constructed fixture (current §6.5, more sources) had passed both conditions. **R2 as drafted misses the original case.** Lowering the thresholds would catch it, but that is tuning on the known case, so it is reported here and not applied.
