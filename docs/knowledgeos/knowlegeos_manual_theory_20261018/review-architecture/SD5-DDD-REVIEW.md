# SD5 — DDD review of strategic-design turn 5 (§10.5 Published language)

*Lead DDD Architect, part 1 (review only), under the mandate revision 2 (approved 2026-10-29). Every element reviewed is at S0. Nothing here raises a level, writes theory or decides. Decisions of the Principal (T10-01 (b), T10-05 (B), T10-10 (1), T10-16 (b)) are not judged wrong; DDD consequences are reported as recommendations.*

## 1. Baseline

| Item | Content |
|---|---|
| Date | 2026-10-29 |
| Run | new review |
| Theory and design baseline | `06-theory/manuscript/chapter_10/10.05-published-language.md` at `dca72c57f` (turn 5 settled). Context: `10.02`, `10.03`, `10.04` and `TODO.md` at `dca72c57f` (diffs from `87fbfa2ed`; and `10.04` at `f8f6bbb2f`, now settled with turn 5) |
| Decisions applied | decision record at `382fa6d85`: addenda 2026-10-29T10:00:44Z (T10-10 (1)), 10:12:39Z (routing A, B, C), 10:46:12Z (T10-16 (b)). **T10-16 (b) is decided but not yet applied to Ch. 10; §10.5 still shows PL-0's owner as open** |
| Book lines verified | none newly opened in Ch. 9 for this report. §10.5's Ch. 9 citations are taken as the book states them, except those already verified in SD1–SD4 (§9.15 L75, §9.17 L33, §9.16 R4a, §9.13 L49) |
| Implementation baseline | none |
| Standards | `DDD_Tactical_Governance_Principles.md` at `668cc7b22` (§1 by analogy); Evans (2003; published language, shared kernel, conformist), Vernon (2013) **from the Architect's knowledge, not opened** |
| Scope | mandate §5 "Published language": is each contract a domain meaning, free of technical interface? Also: is the language one language, with its terms consistent across contexts? Excluded: corpus lines (S0028, S0035, S0036, S0039, S0062, S0069, S0196, S1040, S0633: not opened, taken as reported); Ch. 1 lines (§1.14.1-12, §1.17.3) |

## 2. Summary

- §10.5 is a domain-meaning contract, free of technical interface (§10.5.0, §10.5.5), and it is honest about its limits. Each question has its own answer form. T, F and U have stated meanings. The conditions are split into values, guards and legitimacy. What is not promised is published. Whether anyone relies on it is "not attested". This is good published-language practice.
- **The vocabulary has three term clashes that will meet in the joint display of promise (b):**
  - Q1's **F** means "refuted *or* not recognized", while W's class uses **"refuted"** for a different thing (SD5-02, high).
  - **"Attested by the kernel"** sits beside W's **"not attested"** (SD5-04).
  - The context is named **"Standing"**, but it publishes **"current authority"**, which the book separates from standing (T10-15; SD5-03, high, open).
- **"Standing's published language" also holds the record's language.** PL-0 to PL-4 mix record-keeping with standing's own rules. T10-16 (b) now places the record-keeping rules outside standing, so this section will need to be split (SD5-01, high).
- **T10-10 (1) and T10-16 (b) leave one point unclear:** whose rule governs the `designate` kind (SD5-05, high, recommendation).

## 3. Findings

| ID | Element | Type | Status | Finding | Evidence | What would resolve it | Severity |
|---|---|---|---|---|---|---|---|
| SD5-01 | PL-0 to PL-5, the "in" half of standing's language | design ambiguity | verified | The "in" half mixes two languages: **the record's** (one designated record, citations, entry identity and merge, integrity, tombstones, attestation, designation: PL-0, PL-2 "every entry", PL-3, PL-4) and **standing's** (its ten kinds' acceptance: T at append, single-use and owner application, transfer from issuer: PL-1, PL-2, PL-5). In DDD, the first is the shared element (R0); the second is standing's published language. T10-16 (b) now decides that "the record-keeping rules (designation, merge, identity, integrity) move out of standing's list", so as written §10.5.2 assigns them to the wrong owner | §10.5 L52–148 (heading "Standing's published language"); PL-2 L94–97 vs L98–106; T10-16 (b) | when (b) is applied: publish PL-0, PL-2's "every entry" guards, PL-3 and PL-4 as **the record's language** (owned by no area), and PL-1's standing kinds, PL-2's kind guards and PL-5 as **standing's** | high |
| SD5-02 | PL-6, Q1's **F**, against W's class **refuted** | DDD assessment | verified | Q1's F means "refuted, or not recognized", including "nothing recorded" under the closed world (L160). The text warns: "A consumer must not read F as 'refuted'". Promise (b) then shows this F **next to** W's class vocabulary, whose first value is **"refuted"** with a different meaning: evidence against the content, not refusal of authority (§10.5.3 L277; §10.2.3). In a published language that a conformist must show unaltered (R3, R4), a value that needs a warning, beside a homonym from the other half of the same display, is a known integration hazard. Users will read "F … refuted" as one thing | §10.5 L160, L170, L277; §10.2.3 (class definitions); promise (b) | **Recommendation, for the Principal (input to the G1 turn, which defines W's vocabulary):** keep the two vocabularies lexically disjoint. Either W's class or the gloss of Q1's F avoids "refuted", and Q1's F is shown with which of its two cases applies once the book defines reasons for F (PL-13). *A DDD recommendation; the meanings are the book's* | high |
| SD5-03 | The context's name "Standing" against what it publishes ("current authority") | open question | verified | The book publishes "current authority" for Q1, keeps "Standing" as the area's name, and records that §1.14.1-12 separates the two and that whether standing in that sense is answered by anything is open (T10-15). In DDD, the name of a context and its central published concept are the first terms of its ubiquitous language. A mismatch here reaches promise (b) itself, whose wording is "current standing" | §10.5 L34, L156, L317–320; TODO T10-15 | T10-15 (a theory turn, or the Principal on the promise's wording), as the book states. The Architect adds only that it should be closed **before part 2**, because tactical names follow it | high |
| SD5-04 | "Attested by the kernel" (PL-2) against W's "not attested" | recommendation | verified | The text defines the two senses apart (L33), which is correct. Both terms will still appear in the joint display and in W's acceptance language (evidence entries carry the kernel's attestation). One word with two meanings in one display is the homonym pattern that SD2-01 asks the book to record | §10.5 L33, L108–110; §10.5.3 L277 | **Recommendation:** use a distinct term for one of the two when W's language is written (G1 turn). Low cost now, expensive after part 2 | normal |
| SD5-05 | `designate` under T10-10 (1) and T10-16 (b) | recommendation | verified | T10-10 (1) (and PL-0 L67, PL-1 L89): "removing, annulling and designating stay with standing for every kind". T10-16 (b): "The record-keeping rules (designation, merge, identity, integrity) move out of standing's list to that element". Read together, the **authority of a designation act** may stay standing's (CE\* evaluates it, PL-2 L105), while the **designation rule** (old store in force, new carrier never a carrier, cites every maximal entry, store owner) becomes the element's. The two decisions do not say whose **acceptance rule** governs the `designate` kind, and under (d2) the owner of a kind is the owner of its acceptance rule | decision record 10:00:44Z item 1; 10:46:12Z; §10.5 L67, L102–106; §10.3.6 (d2) | **Recommendation, for the Principal:** when (b) is applied, state whether `designate` (and `remove`, whose tombstone form is PL-3's) is a kind of the element, or standing's kind with the element's structural guard. *Prepared as one question if the Professor's application does not settle it* | high |
| SD5-06 | PL-2: no reason promised for a guard refusal | recommendation | verified | The appender learns only "refused" (L107). For a language at the writing seam (WM writes under it, R8; A will write under W's, R7), a refusal without a reason in domain terms forces each appender to reconstruct the guard. The book's G8 asks this for W ("accepting evidence, with its refusal reasons"); standing's guards have the same need | §10.5 L107; §10.2.3 G8; §10.4 R8 | a later theory turn: state guard-refusal reasons as part of the language, as G8 does for W. *Not derived; a DDD recommendation* | normal |
| SD5-07 | PL-14, the joint display | DDD assessment | verified | Sound. The text separates what a producer can guarantee (no **omission** at delivery, through a joint answer) from what it cannot (**alteration** at display), and publishes the display duty as an assumption on consumers. It also names the join problem: Q1 is keyed by (k, x, t, s, u), Q4 by (k, s, g), per claim under F37. That is the real modelling obstacle to one answer. **Holds**; see the follow-up for the routed finding | §10.5 L261–271 | the G1 turn (the join) | normal |

## 4. What holds

- **Meaning, not interface** (L22–24). Model names are cited as evidence only, and decided design terms carry their stated meaning. No transport or format is fixed. This meets mandate §5's test.
- **One answer form per question** (PL-6 to PL-9). Q2 returns entries, not T/F/U. Q3's "not found" is not "unauthorized". Separation-of-duties is a separate positive finding. This is a careful published language.
- **The values' meanings are published** (T forced; F including closed-world non-recognition; U with named reasons; "refused" outside D). Publishing what F does **not** mean is exactly what a conformist consumer needs.
- **Conditions in three groups** (PL-12): values, guards and legitimacy, with "(c) is published so that a consumer knows what a T **does not** establish". This is published-language practice at its best.
- **The non-promises are a section of the language** (PL-13).
- **Not frozen** (L26), and "published" is tested, not assumed (§10.5.6): consistent with S0.
- **PL-0 and R0** make the shared element visible in the map, and R0 is typed "conforms" for every area. That is the right reading under T10-10 (1).
- **The other areas publish nothing as contexts** (§10.5.3), and their content relations are kept conditional (◇).

## 5. Files opened and commands run

1. `git log --oneline -8`; `git show dca72c57f --stat` (**printed `review/SD5-PUBLISHED-LANGUAGE/` file names; none opened**); `git log -1` of the decision record; `git status`.
2. `git log` of `review-architecture/`; `sha256sum -c SHA256-SD-REVIEWS.txt` (all four OK); `grep '^## Addendum'` and `sed -n '530,539p;553,$p'` of the decision record (working tree = `382fa6d85`).
3. `git show dca72c57f:…/10.05-published-language.md` into the session scratchpad, read in full; `git show dca72c57f:…/TODO.md | grep T10-1x/T10-20`; `git log --oneline 87fbfa2ed..dca72c57f -- chapter_10`.
4. `git diff -U0 87fbfa2ed dca72c57f` on 10.02, 10.03, 10.04 (changed lines only).
5. `git show f8f6bbb2f:…/10.04` (L6 and the count line); `git show f8f6bbb2f --stat`.
6. `grep -n` for line numbers on 10.04, 10.03 and TODO at `dca72c57f`.

**Not opened:** corpus sources, `00-access/`, any `review/` folder file, `review-implementation/`, `CONCEPT-REGISTER.tsv`, `THEORY-MAP.md`, Ch. 0–8, and any text after `dca72c57f` except the decision record.
