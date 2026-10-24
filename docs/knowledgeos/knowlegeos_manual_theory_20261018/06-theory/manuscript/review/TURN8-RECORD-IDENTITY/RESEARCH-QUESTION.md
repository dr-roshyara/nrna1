# Theory turn 8: record identity and anchoring (K1, K2): the research question

*Written 2026-10-24, before any search for this turn (Rulebook v1.4 §12b: "a research question first"). The Principal's instruction: "one theory turn on record identity and anchoring (K1/K2), from the kernel questions collected in §8.18–§8.21". Rulebook v1.4 applies.*

## What must be explained

§9.15's K1 ("exclusive write path: the record changes only through the kernel's steps; no entry is lost, altered or removed outside them") and K2 ("a single view: readers agree on the entries of the cut") both speak of **"the record"**. Windows C25–C28 found two things:
- what "the record" is, is not fixed by the record itself;
- what an outside anchor can show depends on when it was taken and on what it carries.

**The questions collected there (verbatim headings; locations):**
1. **Record identity** (§8.18.3; CON-0239): "Does §9.15 need an assumption of its own (every reader and writer resolves the same record), or is it part of K2?"
2. **The anchor's cut** (§8.18.3; CON-0243): "Should K1 say so, and say that the anchor must be taken again at the operation it protects?"
3. **Membership at write time** (§8.19.3; C18): "does D need the resolution in force at each entry's write?"
4. **Cross-record order** (§8.20.3; CON-0251): "Cross-record order through citation chains as a K2 corollary."
5. **The closing-record rule** (§8.20.3; CON-0254): "for records about records".
6. **Reference, anchor and operand conditions** (§8.21.3; CON-0257, CON-0243, CON-0259): "one concept or several?"

## The research question

**What does "the record" denote in K1 and K2, and what must hold for the kernel's answers to be the answers for that record?** In particular:
- **(Q-I)** Is a fixed identity of the record a kernel assumption of its own, a part of K2, or derivable from K1 and K2 as they stand?
- **(Q-A)** Under which conditions does comparing the record with an outside anchor detect a loss or an alteration that K1 forbids, and which of CON-0243, CON-0257 and CON-0259 are parts of one condition?
- **(Q-M)** What makes an entry an entry **of** the record (membership), and is it decidable from the record alone?
- **(Q-C)** Do CON-0251 and CON-0254 follow as corollaries of the restated assumptions, and under which premises?

## What is necessary, what is derivable (the deliverable)

The deliverable is **a step of the derivation chain**: a restated K1 and K2, with any new assumption named, each with:
- its scope and premises;
- proofs (Ded) for what follows;
- countermodels for each independence claim (an assumption is a separate one only if a model exists in which it alone fails while the others hold, Rulebook v1.4 §12b, REVIEWER-INSTRUCTIONS "independence and minimality");
- the three boundary questions kept apart for any new assumption: is it necessary? must the kernel provide it? can an application provide it without breaking kernel invariants?

The deliverable goes into a new section §9.17 and into §9.15's K rows. §8.18.3–§8.21.3 then point there.

## What would disprove a proposed answer

- **For Q-I**, either of these:
  - a model in which K1 and K2 as restated hold, yet two readers' CE* answers for "the record" differ, so an assumption is still missing;
  - a model in which the proposed identity assumption fails, yet every reader's answers are those of the intended record, so it is not necessary.
- **For Q-A**, any of these:
  - an anchor that detects the loss of an entry it did not commit to, with no surviving citation and no numbered successor, so the cut condition is wrong;
  - a reference whose information comes only from the checked content, yet detects a divergence that leaves that content internally consistent (CON-0257's own falsifier);
  - a case where re-taking the anchor at the protected operation is not needed for detection, so the "again" clause is not necessary.
- **For Q-M:** a membership test computable from the entries alone that separates a write to the record from a write to a non-record in C18's case.
- **For Q-C:** two records with a shared order source in which order is shown without citation. That is within CON-0251's premise, so it is not a counterexample. A counterexample would be one **without** such a source.

## Out of scope (named, so that the turn does not drift)

- K5's two levels of identity (CON-0240): integrity over fields CE* does not read.
- The hold state (CON-0250).
- L9's serialisation (CON-0245), except where K1's durability enters.
- The declared-write claim (CON-0249), except as an anchor case.
- How K1 and K2 are provided (§9.15.9: an architecture question).
