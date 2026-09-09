# EKS-43 — A Sanskrit word and a core formal operator share one name, in one corpus, in two live research lanes

## Problem, in business language

The research corpus uses the three letters **`Sat`** for two completely unrelated things, and both
uses are active:

1. **`Sat(K, r)`** — the central formal operator of the requirements theory: *"the degree or status
   to which knowledge `K` satisfies requirement `r`"*. Introduced 2026-08-27, still under active
   development on 2026-09-06.
2. **`Sat`** — the Sanskrit metaphysical term meaning *being* or *ultimate reality*, used in the
   Vedānta triad **`Sat` / `Asat` / `Mithya`** (reality / non-reality / appearance). Introduced
   2026-08-26 in the philosophical strand, which is one of the largest single clusters in the whole
   corpus.

There is no relationship whatsoever between the two. One is a satisfaction predicate over a
requirement set. The other is a category of existence in classical Indian philosophy.

## Why this is a serious problem rather than a curiosity

**It already caused a wrong result in this programme, and was caught only by opening the files.**
An automated scan of the entire corpus reported the earliest appearance of `Sat` as 2026-08-26 —
a full day before the real formal definition. Acting on that would have sent the reconstruction to
a philosophical document as the origin of the requirements theory. The error was caught only
because every mechanical result in this programme is verified against the source before use. A
reader without that discipline would not catch it.

**The philosophical lane is very large.** The Gītā/philosophy strand is the biggest single step
cluster found in the corpus — one step number alone spans nearly a hundred documents. So a search
for `Sat` does not return a few stray hits; it returns two substantial bodies of work mixed
together with no signal separating them.

**It is invisible from either side.** Nothing in the philosophical documents says "this `Sat` is not
the operator". Nothing in the formal documents says "this `Sat` is not the Sanskrit term". Neither
lane cites the other.

**The two lanes are deliberately kept apart, which makes the collision worse, not better.** The
programme's own rule is that research lanes stay separate until an explicit bridge is documented.
A shared name across two deliberately-unbridged lanes is precisely the situation where a reader
will assume a bridge exists because the vocabulary suggests one.

**It is a homograph across languages, so no amount of domain intuition catches it.** A reviewer who
knows the requirements theory well will read `Sat = ultimately real` as a strange claim about
satisfaction rather than as a different word.

## What is *not* the problem

- The philosophical work is legitimate and is not being questioned. The programme has repeatedly
  drawn on it, and its own audits record that this strand produced no kernel primitives — a finding,
  not a criticism.
- This is not a case of one team using another's notation carelessly. The two uses arose
  independently, a day apart, in different lanes, both entirely reasonable in their own context.
- The formal `Sat` is not mis-defined. Its problems (an under-determined codomain, three arities in
  one document) are tracked separately and are unrelated to this ticket.

## Candidate requirement

The corpus needs a rule that guarantees **a search for a formal operator returns only that
operator**. Options for governance, cheapest first:

1. **Disambiguate at the point of use.** Require the philosophical strand to write the Sanskrit
   term in a visually distinct form — italic `*Sat*`, or the transliteration `sat`/`Sát` — and
   reserve upright `Sat(` for the operator. Costs nothing structural; fixes search immediately.
2. **Rename one of them.** The operator could become `Satisf(K,r)`; the Sanskrit term could keep
   `Sat`. Renaming the operator touches many documents and the programme discourages rewriting
   accepted work.
3. **Register the collision without changing either.** Add both meanings to a glyph/term register so
   a reader who meets one is told the other exists.

**Recommendation: option 1 plus option 3.** Option 1 is a convention for future writing and costs
nothing; option 3 protects everything already written, which is where the risk actually sits.

## Relationship to existing items (ES-005.4 — consume or extend, never create a second)

Checked against the register before writing:

- **`EKS-41`** (one symbol, unrelated formal objects) — the closest relative, and it has just been
  extended twice. But every meaning it tracks is a **formal object within the mathematics**. This
  ticket is different in kind: the collision is between **a formal object and a natural-language
  term from another language**, so the remedy is a writing convention rather than a glyph ruling,
  and the detection method differs — no type analysis can separate these, only reading the sentence.
- **`EKS-42`** (two documents share one section-numbering space) — same family, different level.
- **`EKS-34`** (a naming collision split across an information barrier) — related, but that concerns
  two lanes separated by a firewall; here both lanes are readable and the barrier is linguistic.

Distinct from all three. Recorded separately because the fix is a convention, not a register entry.

## Urgency

**Medium-high, and it is the most likely of the collision family to cause a wrong conclusion.**
Unlike the others, this one has **already produced a false result** in an automated pass. The formal
`Sat` is currently the most-worked object in the programme, so searches for it are frequent, and the
philosophical strand keeps growing.

## Evidence

- Formal: `phase_measure_theory/20260827-162545_step-023-epistemic-sufficiency-readiness-completeness-and-the-knowledge-boundary.md` §10 —
  *"Define `Sat(K, r_i)` as the degree/status to which knowledge `K` satisfies requirement `r_i`."*
- Sanskrit: `phase_measure_theory/20260826-111026_metaphor-change-for-the-model.md` line 376
  (`Sat = ultimately real`) and line 721 (`Sat / Mithya / Asat distinguish reality, appearance,
  nonexistence`); also `20260826-113213_lord-lens-infinite-knowledge-space.md` line 648.
- The false result it produced, and its correction:
  `docs/knowledgeos/theory-extraction/118-P97-TRUE-BIRTH-OF-SAT-AND-REQUIREMENTS.md` §0.1.

## Status

`OPEN` — raised by the P-97 chronological reconstruction, 2026-09-09. Advisory: this ticket records
a problem and recommends a convention. It decides nothing and changes no document.
