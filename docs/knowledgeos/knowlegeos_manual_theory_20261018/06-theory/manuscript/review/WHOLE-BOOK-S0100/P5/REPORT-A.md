*Whole-book review S0100, part P5, reviewer A (opus). Step-2 report, saved by the Professor from the hand-back, unedited, 2026-10-19T20:05:09Z.*

# P5 (Ch. 8): Step 2 review, whole-book review S0100

**Process independence:** I am an AI session independent of the book's author as a process, not as a person.

## 1. Assessment

1. Ch. 8 is mostly reliable within its scope. Its quotes from the sources I checked are accurate, and its three formal results (CON-0126, CON-0127, CON-0130) are correct as mathematics.
2. The largest risk sits in the newest material, §8.9–§8.11. Three definitions are not held fixed across sections: eligibility, information lineage and "self-declaration". The C18 restatement of §8.11 contradicts itself.
3. Architecturally, the chapter is a catalogue by reading window. It never marks a concept kernel or application, and its small formal models (check, blind set, E(o), branching) are not tied to the kernel's primitives.

## 2. Findings (ranked)

No finding reaches **critical**. F1 is at the boundary: it defeats §8.11's main conclusion as written, but a local repair fixes it.

| # | Sev. | Type | Location | Source line | What is wrong | Consequence | Correction | Check |
|---|---|---|---|---|---|---|---|---|
| F1 | high | theory defect | §8.11 C1 (L32) vs plain language (L24), §8.11.4 (L89), CON-0157, map L209 | S0100 L120; S0099 L44, L46–47 | **Contradiction.** L32 says the correcting session's "self-declaration was false: self-declared identity failed in one direction". L24, L89 and the register say every self-declaration was true. The "every" form also overclaims: the book itself (L34) says the erratum's self-declaration by `4858c37c` is unchecked, and S0099 L44 has that transcript ending at 10:42:21Z, before the erratum. | The section's central conclusion ("attribution failed, not identifiers") is defeated as written. | Delete L32's sentence. Write "both *checked* self-declarations were true (S0100 L10; CORRECTION 1's own ID, S0100 L120); the erratum's is unchecked" in all four places. | source-verified |
| F2 | high | theory defect | §8.6.2 factor "information it read"; CON-0125; §8.11 C2 Int | — | Every check of an object reads that object, so as defined every pair of checks shares information lineage. The factor can never hold. §8.11 C2 then rejects an F32 option ("would leave no eligible verifier") by an argument that applies to any verification, not only to verifications of uptake. | A load-bearing definition for §8.8–§8.11 is degenerate. | Define information lineage over information other than the object under check, such as sources and prior findings. Re-derive §8.11 C2's argument. | internal |
| F3 | high | theory defect | §8.11 C6 "counter-instance"; §1.14.3-2 "further evidence" | S0097 L63–67 | Both call it a breach, "against the boundary just quoted", that the AMD2 verifier produced Correction #3. S0097's "MAY NOT … create the replacement architecture" binds the *verification assignment* only. Correction #3 was produced in an architecture lane, and §8.8 C3 and fork F25 explicitly hold that pattern open. | Ch. 8 and Ch. 1 prejudge F25. | Mark it as a further instance of F25, not a breach. | source-verified |
| F4 | high | theory defect (changed theory) | CON-0151 meaning ("authors the object or what **it shapes**") vs §8.9 C2 ("what o **protects**") vs §8.11 C7 ("what the object **is shaped by**") | S0097 L29–31; S0100 L32 | (a) Three different relations are used for one term. (b) §8.9 C2's "drops **only** when…" is refuted inside the corpus: `2da45a86` became ineligible for the AMD2 verification through findings that shaped its object (S0100 L32), not through authoring the object or what it protects. (c) "Non-increasing over a fixed population" also needs a fixed eligibility rule: an act can widen eligibility, as with "bars no one" (S0074, via §8.8 C3). | F32's premises are unstable. | Give one definition of E(o) relative to a stated rule, with material participation as a ground, and use it verbatim in §8.9, §8.11 and the register. | source-verified (S0097, S0100); S0074 internal |
| F5 | high | theory omission | Ch. 8 as a whole; RESEARCH-PURPOSE test | — | Ch. 8 is announced as "candidate methods for validating this book's theory" (index L3). §8.6 and §8.9–§8.11 are instead a theory of verification acts inside the estate, the trust and authority half of the central question. No concept is marked kernel or application, and none is given its minimality or stability test. | The chapter's results cannot be placed in the deliverables. | Split or label the two subjects. Mark kernel or application for each concept. | internal |
| F6 | normal | theory defect | §8.6 C1–C4, §8.9, §8.11 | — | "Independent" is a homonym with at least four senses: factor-independence (C1, C3), error or blind-set independence (C2), line of support (the lineage rule), and conditional independence (C4). C3's "a recorded shared factor refutes independence" holds only for factor-independence, while C2's value claim is about blind sets. | Inferences slide between senses. | Name each sense where it is used. | internal |
| F7 | normal | theory defect | §8.6 C2 proof "differ **exactly** when…"; CON-0126 meaning "reports B(a)ΔB(b)" | — | Two checks that both detect d can still give different outputs, so the reported set is a superset of B(a)ΔB(b), not equal to it. The main result, that the intersection is never reported, stands. | Over-strong statement of the result. | Change "exactly" and "=" to "⊇". | internal |
| F8 | normal | source misreading | §8.10 C10, "a source slip: seven exclusions" | S0094 L19, L41 | The source says "seven named roles" and "seven excluded roles", not seven exclusions. By role the count fits: `5e1dd9ee` 3 + `1c8b041b` 2 + `2da45a86` 1 + the unnamed author 1 = 7. | A source is wrongly charged with an error. | Withdraw the "slip". | source-verified |
| F9 | normal | distortion | §8.10 lineage line | S0094 L39–45 | S0094's "material limitation" is about a barred role's missing identifier for enforcing a bar. The book applies it to S0093–S0096 declaring no producer, which is a different gap. | Meaning shifted although marked "in meaning". | Present it as the book's own interpretation (Int). | source-verified |
| F10 | normal | theory omission | §8.11 C1 "How it was settled" | S0099 L40–44; S0100 L107–116 | The settlement rests on two measurements. One is by `2da45a86` (create against append, commit gaps). The other is by `4858c37c`, a compaction continuation of `2da45a86` (reference counts 4 against 1). By §8.6's own factors these are one line of support. The book merges them and does not apply its own rule. | Corroboration is overstated. | Count them as one line, mitigated because the checks are mechanical (§8.6 C5). | source-verified |
| F11 | normal | boundary missing | CON-0156 (VALIDATED) | S0094 L77; S0093 L63 | The rule holds for a record-relative reading (s fixed at the finding's time) and for later acts without retroactive validity. A retroactive ratification (validity before the act time; §1.14.1-11 calls [tₐ,∞) a convention) would make the finding false in a "now about then" query. The heading "a finding is true of its time" also presupposes the finding was true. | Validated beyond its premises. | State the premises. Reword the heading to "a true finding stays true of its time". | source-verified; internal |
| F12 | normal | boundary missing | CON-0150 | S0090 L78, L233 | Assessing content claim by claim cannot assess what a producer left out, and W-1 was such an omission. A work product's completeness is not re-derivable claim by claim. | The scope is unclear. | Add "per claim; completeness excluded". | source-verified |
| F13 | normal | status defect | CON-0058, CON-0059, CON-0060 (VALIDATED, reviewed = "self") | — | These are validated by self-review only. CON-0058's counterexample test is settled by one disclosed case, while the raw record is missing (T8-01). | The status is stronger than its review. | Make them PROVISIONAL, or have them reviewed independently. | internal |
| F14 | normal | register drift | CON-0125, CON-0126, CON-0127, CON-0128; THEORY-MAP §6 (L110–114), L349 | — | CON-0125 lists five factors (the section has seven). CON-0128 says "general form" (§8.6.5: "a heuristic, not the general form"). CON-0127's basis lacks the truthfulness premise. Many "basis" cells say "not yet reviewed" on reviewed rows. The map's §6 covers §8.1 only, and its status line says "§8.1–8.7". | Readers of the map or register get superseded claims. | Synchronise. | internal |
| F15 | normal | theory omission | §8.11 C4 (the only named owner gap) | — | Further invariants have no named owner: recusal recorded as recusal, absence scope stated, historical results not rewritten (CON-0156), referent checked first (§8.5 C1). All are satisfied by refusing everything (the trivial case), and no must-do partner is given, apart from §8.9 C2's remark that verification must stay performable. | The invariant-owner deliverable is missing. | Add an owner and a must-do per invariant. | internal |
| F16 | normal | theory omission | §8.7 C4 | S0060 L13–16, L20 | (a) Governance "verified" the preconditions largely by quoting the report it registers. (b) What standing accepted *verification evidence* has (accepted as true, as adequate, or as sufficient for the next act) is not analysed. | A kernel act type is left untyped. | Add a section, or an open question. | source-verified |
| F17 | normal | boundary missing | CON-0159 (restated: "an analysis has no normative effect by itself") | — | An analysis whose conclusions a gate or resolver reads automatically has normative effect without any adoption act. That is §1.14.5-1's "manufactures" case. | The restated form is too broad. | Scope it to analyses read only by people. | internal |
| F18 | normal | theory omission | CON-0125's seven factors | §8.1 L19; §8.10 C6; §1.14.3-3 | Two shared factors are missing: the human operator as a channel between sessions, and inherited standing instructions (CLAUDE.md, §8.1). There is also an unaddressed rival: intrinsic defect difficulty as a common cause of overlapping blind sets. §8.6 C2's "shared factors are the likely reason" is unsupported. | The factor list is incomplete; its causal reading is unproven. | Add the two factors. Make the rival a Hyp test. | internal |

Further normal and low findings are in the appendix (A1–A6).

## 3. Changed theory

| Item | Holds? | Reason |
|---|---|---|
| §8.11 plain language | **No** | "Every self-declared identifier was true" overclaims (F1). The rest holds (S0099 L46–47, L76–80 verified). |
| §8.11 C1 | **No** | It contradicts itself at L32 (F1). The quotes from S0100 L10, L61, L82, L95, L105, L128 are accurate. The "identifiers are not episodes" point is supported by S0099 L44. |
| §8.11 C2 | Partly | The question is stated faithfully (S0099 L76–80, L109). The Int's argument against information lineage rests on F2. |
| §8.11 C3 (CON-0158) | Yes, as PROVISIONAL Hyp | It is scoped to direct uptake and offers the referee case as a rival. Both of its instances are reported by one source (S0100 L36), so they are one line. |
| §8.11 C4 | Yes | Verified against S0100 L22 and S0097 L27–31, L41. The trivial case is noted. |
| §8.11 C5 | Yes | Verified against S0099 L8, L14, L19, L26 and S0097 L21. |
| §8.11 C6 (CON-0159 restated) | Partly | The restatement holds with F17's scope. The "counter-instance" does not (F3). |
| §8.11 C7 | Yes | Verified against S0097 L35–39 and S0100 L49, L90, L127, L140. |
| §8.11 C8 | Spot-checked | S0098 L3, L227, L290, L337, L407, L416, L419 match. |
| §8.11.3 open questions | Yes | — |
| §8.11.4 what does not hold | Yes, but | The first bullet conflicts with C1 L32 (F1). |
| CON-0157, CON-0158, CON-0159 | CON-0157 no (F1); CON-0158 and CON-0159 yes, with F17 for CON-0159 | — |
| THEORY-MAP block "Window C18" | Partly | Repeats F1's overclaim. |
| TT4 §8.10 C2 | Yes, as an analogy | It needs F11's premises: s fixed, no retroactive acts. |
| TT4 §8.10 C10 (two chains) | Yes | Verified against S0093 L43 and S0095 L99. S0095 L99 does give a reason for placing ownership later ("settled before ownership"), so "the sources do not say the order changed" is literally true but understates this. |
| TT4 CON-0151 (meaning column) | **No** | Three different relations under one term, and the "only" is refuted (F4). |

## 4. Refutation adequacy, and the extra task

**The book's own falsification attempts**

| Concept | Recorded attempt | Adequate? | Strongest counterexample or rival I found |
|---|---|---|---|
| CON-0058 | counterexample test, in the register | **Inadequate**: one disclosed case cannot show the register is complete, and the raw record is missing | undisclosed help from the brief, which nobody can audit |
| CON-0059 | counterexample test | adequate (a weak non-identity claim) | — |
| CON-0060 | counterexample test | adequate, with its qualification | — |
| CON-0126 | trivial cases B(a)=B(b) and B(a)=∅ | adequate | F7 (two detectors, different outputs) |
| CON-0127 | trivial case R=F; truthfulness premise added | adequate as a lemma | the independence homonym (F6) |
| CON-0130 | cases m=0, and every finding producing exactly one | adequate; caveats stated | — |
| CON-0141 | trivial case (a fold that reads nothing) | adequate; found the missing must-do | — |
| CON-0128 | dependence counterexample. I recomputed it: marginal LRs 0.6/0.4 = 1.5 each, product 2.25, joint 0.5/0.01 = 50 | adequate. The "0.857" distribution is not shown | — |
| CON-0150–0159 | **none recorded** in counterexample / boundary / rival / falsifier form. §8.x.4 lists refutations made by reviewers, not attempts by the author. CON-0158 names one rival | inadequate (not attempted) | F11, F12, F17, F4, F1 |
| CON-0120–0125, 0129 | none recorded in that form | not attempted | below |

**Extra task: the most foundational derivations**

**CON-0120 — an absence claim needs its search scope** (cited from §8.4, §8.6, §8.7, §8.8 and §8.9)
- (a) Counterexample in scope: a search that is exhaustive and states its scope still misses an item when the class of what counts was mis-fixed. That is W-1's real shape: the untracked document existed, and the question was what counts as "the untracked material".
- (b) Boundary: it stops at absence shown by construction (a fixed fold, a schema), which needs no search, and at closed registries, where absence is decidable.
- (c) Rival: absence claims fail through the predicate (class membership), not the scope; compare §8.5 C9, "what is counted decides the count".
- (d) Falsifier: claims with stated scope that fail at the same rate as claims without.
- Book's treatment: adequate on applying the rule to itself (§8.4 C3, §8.9 C3). Inadequate on the class-predicate rival, and on saying that the rule is a disclosure norm, not a sufficient condition.

**CON-0125 — independence has several factors**
- (a) Counterexample: two checks that share no factor still have identical blind sets, because the defect is hard.
- (b) Boundary: the factor list works as a proxy only where errors are driven by those factors.
- (c) Rival: blind-set overlap is caused by defect difficulty, not by shared factors.
- (d) Falsifier: pairs that share factors but have disjoint blind sets, or the reverse, at comparable rates.
- Book's treatment: partly adequate (orthogonality is "not shown"). It misses F2 and F18.

**CON-0127 — independence on the record is one-sided**
- (a) Counterexample: in the S0099/S0100 episode, the facts about who shared what were contested inside the record itself, so establishing F (the shared factors) is itself an inference from the record.
- (b) Boundary: the result holds given a truthful record. It becomes two-sided for attested factors bound at START.
- (c) Rival: the one-sidedness comes from factors being *declared*, not from the open world.
- (d) Falsifier: a record mechanism with attested and complete factors.
- Book's treatment: adequate (it states the premises).

**CON-0156 — superseded, not disproven**
- (a) Counterexample: retroactive ratification (F11).
- (b) Boundary: record-relative queries only.
- (c) Rival: S0093's "FALSE as of now"; what decides between the two readings is the time parameter.
- (d) Falsifier: an accepted retroactive act in the estate.
- Book's treatment: partly adequate (it notes the homonym). The premises are unstated.

**CON-0151 — the eligible-verifier pool**
- (a) Counterexample: S0100 L32 (F4).
- (b) Boundary: monotone only under a fixed rule.
- (c) Rival: eligibility is set by acts ("bars no one"), not derived.
- (d) Falsifier: a process that re-enters the pool by an act.
- Book's treatment: inadequate.

## 5. Concept verdicts and the architecture view

**Verdicts for concepts I tested**

| Concept | Verdict | Reason and scope |
|---|---|---|
| CON-0120 | VALIDATED | as a disclosure rule for absence claims from searches; not sufficient for truth |
| CON-0125 | PROVISIONAL | F2 (degenerate factor), F18 (incomplete list) |
| CON-0126 | VALIDATED | for common-mode failures, with "⊇" (F7) |
| CON-0127 | VALIDATED as a lemma | derivable from CON-0120 plus R ⊆ F |
| CON-0128 | PROVISIONAL | the product rule and the copy case are validated |
| CON-0129 | VALIDATED | as a sufficient condition |
| CON-0130 | VALIDATED as mathematics | its premise is unmet, so it is not applicable here |
| CON-0141 | PROVISIONAL | agree with the book |
| CON-0150 | VALIDATED | per claim; completeness excluded (F12) |
| CON-0151, recusal ≠ clearance | VALIDATED | — |
| CON-0151, pool clause as worded | **INVALID** | F4 |
| CON-0153 | VALIDATED | as a distinction |
| CON-0155 (restated) | PROVISIONAL | agree with the book |
| CON-0156 | VALIDATED | under F11's premises; PROVISIONAL as currently stated |
| CON-0157 (body as written) | **INVALID** | F1 |
| CON-0157 (register form, "every … true") | PROVISIONAL | needs "checked" in place of "every" |
| CON-0158 | PROVISIONAL Hyp | — |
| CON-0159 (restated) | PROVISIONAL | with F17's scope |

**Not tested:** CON-0058–0060 (beyond F13), CON-0106, 0112, 0119, 0121, 0122, 0152, 0154.

**Architecture view**

| Layer | What Ch. 8 has | Gap |
|---|---|---|
| Primitives | none of its own. It borrows act (A1, §1.6), roles (§1.14.3), evidence grades (§1.3) and history (§9.8 H2/H4/M5) | its local terms (check, blind set, factor, E(o), m) are not typed against the F13 attribute set or the act model. They are isolated. |
| Laws | CON-0126 and CON-0127 proved; CON-0130 an applied theorem; CON-0141 a lemma | none ties "who may verify" to standing or authority, which is the central question's "trust" |
| Results | rules of practice: CON-0120, 0121, 0129, 0150, 0156, 0158, 0159 | none marked kernel or application (F5) |

**Independence and minimality, by trying to build a model where each fails alone**
- CON-0158 is **independent** of producer ≠ verifier: Verification #2 judging AMD2's uptake satisfies producer ≠ verifier and breaches CON-0158.
- CON-0127 is **derivable** from CON-0120 plus truthfulness.
- CON-0159 duplicates the further-evidence paragraph of §1.14.5-1: **derivable**.
- CON-0150 and CON-0156 are **likely derivable**: CON-0150 from §1.16.3 plus A1, CON-0156 from Hist with s fixed plus I-7. **Undecided.**
- Recusal ≠ clearance from A1: **undecided**.
- Circularity: none found beyond what the book flags (the strength rule shares wording with the estate, §8.10 C9).

## 6. Coverage of the blind sample

**The blind comparison covers only my five sampled sources** (S0100, S0090, S0094, S0060, S0097). I reviewed the rest of the part non-blind.

**Ranked gaps**

| Rank | Point from my inventory | Mark |
|---|---|---|
| 1 | Accepting "evidence" has no epistemic analysis (S0060 L20) | MISSING (F16) |
| 2 | Settlement by measurements from one lineage (S0099/S0100) | DISTORTED (F10) |
| 3 | Eligibility lost through findings that shaped the object (S0100 L32) | CONTRADICTED by §8.9 C2's "only" (F4) |
| 4 | The engine enforces G-3 while no gate reads identity (S0097 L41, L75–77) | COVERED (§1.14.2-1, §8.11 C4) |
| 5 | Graded conflict, "narrower but real" (S0090 L25) | MISSING |
| 6 | The verifier as an instance of the phenomenon it studies (S0090 L30) | MISSING (it is reflexive for this book's own reviews too) |
| 7 | The refusal's own absence claim from a path-only search (S0100 L31, L43) | MISSING (an instance of CON-0120) |
| 8 | Weight of untracked corroboration, "a concurring sketch, not evidence" (S0090 L186) | MISSING |
| 9 | Grade downgrade OBSERVED → INFERRED (S0090 L169–173) | MISSING (§8.5 C9 has only the upgrade) |
| 10 | "Internally coherent, externally wrong" (S0100 L84) | MISSING (low) |
| 11 | Stopping on a registered bar as opposed to a judgement (S0100 L20) | MISSING (low) |
| 12 | Stage 2 gated behind the baseline act (S0060 L24) | JUSTIFIABLY EXCLUDED (Ch. 5 territory) |
| 13 | The duplicate check before writing (S0094 L21) | JUSTIFIABLY EXCLUDED |

**Covered:** every other inventory point. These include the five limbs, conduct against premise, both failure directions, content against provenance, recusal, W-1/W-2/V-3, superseded not disproven, the non-equivalences, existence against correctness, the scope charge, insufficiency only, and evidence against subject.

**New fork:** F35, "eligibility is set by an act or derived from the record". It follows from F4(c).

**Source errors (listed separately)**
- S0100 L68: "`2da45a86` ended 08:49:17Z" is contradicted by S0099 L44.
- S0099 L44 against S0100 L142: `4858c37c`'s transcript ends before the erratum it declares. The book flags this.
- S0090 L30: "neutrality unattainable for any process" overgeneralises from one estate.
- S0099 L44: a stray CJK character ("三-fold").

## 7. Next steps, in dependency order

1. **Fix F1** (§8.11, CON-0157, map L209). Done when all four places say "checked self-declarations true; the erratum's unchecked".
2. **Repair F2** (information lineage). Done when the §8.6.2 definition excludes the object under check and §8.11 C2's argument has been re-derived.
3. **One definition of E(o)** (F4: rule-relative, with material participation). Done when §8.9 C2, §8.11 C7, CON-0151 and F32 use identical wording.
4. **Reclassify F3 as an instance of F25**, in §8.11 C6 and §1.14.3-2. Done when no "breach" wording remains.
5. **Synchronise the register and map** (F14). Done when no meaning cell contradicts its section.
6. **Kernel or application mark and an owner per concept** (F5, F15). Done when each Ch. 8 concept carries both.
7. **Falsification lines for CON-0150–0159.** Done when each has a counterexample, a boundary, a rival and a falsifier recorded.

**Question for the Principal:** is Ch. 8's subject the method for validating the book, or the theory of verification acts? The answer decides where §8.6 and §8.9–§8.11 belong (F5).

## 8. Limits

- **Sources fully re-checked:** only S0090, S0094, S0060, S0097, S0099 and S0100.
- **Spot-checked lines:** S0093 L43, L63; S0095 L81, L99, L235; S0098 seven lines.
- **Not opened:** S0025, S0036–S0059, S0071–S0075, S0087–S0089, S0091, S0092, S0096, S0164, S0913. Findings resting on them are internal only.
- **Other chapters:** I read only parts of §1.14 and some §9.8 lines.
- **Not done:**
  - I did not run `book_search.py`. Every coverage judgement of "no home elsewhere in the book" rests on direct reading and greps.
  - I did not verify any git hash or time cited by the book.
  - I did not search for a planted gap as such.
- **Not opened, by choice:** `BOOK-UNDER-REVIEW/06-theory/manuscript/chapter_08/review/` exists in the copy. I left it closed as possible old review material.
- **Not read:** BOOK-OUTLINE, CHAPTER-PLAN, EXTERNAL-SOURCES, PRINCIPAL-TESTIMONY.

## 9. Files opened and searches run

Paths are relative to R = `docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/WHOLE-BOOK-S0100/`, and B = `R/BOOK-UNDER-REVIEW/06-theory/`.

**Step 1 (listed in my inventory)**
- BRIEF.md, lines 36–87, plus a heading grep.
- S0100, S0090, S0094, S0060, S0097, in full.
- A path-check grep on `CORPUS-ALLOWED.tsv`, and `wc` on S0097.

**Step 2, files opened**
- `R/P5/STEP2-COMMISSION.md` (full) and `R/P5/BRIEF.md` (full).
- Every file in `B/manuscript/chapter_08/`: 08.00–08.11 and TODO.md, each in full.
- `B/RESEARCH-PURPOSE.md` (full).
- `B/THEORY-MAP.md`, lines 108–247, plus a grep.
- `B/CONCEPT-REGISTER.tsv`: a grep for the header and the rows of CON-0058–0060, 0106, 0112, 0119–0130, 0141, 0150–0159.
- `B/manuscript/chapter_01/01.14-state-is-not-authority-roles.md`, lines 255–324 and 395–419, plus a grep.
- `B/manuscript/chapter_09/09.08-is-history-a-primitive.md`, by grep only.
- S0099 `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-correction3-verification-stop.md` (full, plus `wc`).
- S0093 `docs/knowledgeos/reviews/2026-08-19-six-role-operating-model-adoption.md`, lines 43 and 63 (sed).
- S0095 `docs/knowledgeos/reviews/2026-08-19-KOS-AIP04-DISCOVERY-001-record-integrity-disposition.md`, lines 81, 99 and 235 (sed).
- S0098 `docs/knowledgeos/brainstorming/20260819-104748-ai-engineering-lifecycle-five-responsibilities-governance.md`, lines 3, 227, 290, 337, 407, 416 and 419 (sed).

**Step 2, searches and listings (all scoped to B or to named allowed files)**
- `wc -l -c` over the chapter_08 files, RESEARCH-PURPOSE, THEORY-MAP and CONCEPT-REGISTER. This revealed the `chapter_08/review` directory, which I did not open.
- `ls` of `B/` and `B/manuscript/`; `ls` of chapter_01 and chapter_09.
- In chapter_08: `grep -i -o "kernel|application"` and `grep -c -i "falsif|counterexample|refut"`.
- In chapter_08: `grep -i "rival|falsifier|boundary:|counterexample attempt|trivial-case|trivial case"`.
- In the register: `grep -c "counterexample attempt"`, and `grep -E "^CON-0(05[89]|060|106|112|119|12[0-9]|130|141|15[0-9])\b"`.
- In THEORY-MAP: `grep "C18|§8\.|Ch\. 8|chapter_08|F32|F33|F25"`.
- In chapter_01: `grep "1\.14\.3|1\.14\.5-3|1\.14\.5-13|1\.14\.1-12|legislator"`.
- In 09.08: `grep "\bH4\b|\bM5\b|\bH2\b"` and `grep "Hist\("`.

**No forbidden file reached** (REVIEWER-NOTE item 3): no working chapter files, no working THEORY-MAP, CONCEPT-REGISTER or BOOK-OUTLINE, no BASELINE-REVISIONS, no OLD texts, no other parts' folders, no git history.

---

## Appendix (lower priority)

- **A1 (normal):** "application" is a homonym. It means kernel/application in some places and applying a correction in others (§8.9 C5 L52–53). "Scope" clashes too: the title says "one scope wider" while the quoted S0090 L85 says "narrower scope".
- **A2 (low):** the index lists §8.7 after §8.11.
- **A3 (normal):** §8.9 C2 uses "fixed population" and "closed population" as if they were different notions, without defining either.
- **A4 (normal):** §8.6 C2's model is per defect. Outputs on objects with several defects are joint, so the per-defect reading is an idealisation that should be stated.
- **A5 (low):** S0090 W-5 (a presentation defect as opposed to an evidence defect) would add to §8.8 C7's taxonomies.
- **A6 (normal):** §8.10 C10's remark on S0095 L99 understates that the source gives a reason for placing ownership later.
