# How the theory work is done in practice (under M-04 v1.0)

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Basis | the Principal's instruction "Simplify the KnowledgeOS Theory Development Process" (2026-10-18) |
| Standing | **working practice within the approved M-04 v1.0.** It amends nothing, and changes no approval, access or batch authorization. The one point that would need a formal amendment is listed separately at the end |
| Replaces | the author's trial protocol `M-04-T1-trial-protocol-PROPOSED.md` (v3), which the author **sets aside**: not adopted, not pursued. Its record stays for traceability |

## The aim

**A clear, defensible and traceable theory book.** Progress is measured by the book, not by the number of reports, checks or registers.

## Per source

1. **Read it whole** to understand what its author is saying: concepts, arguments, assumptions, examples, evidence, limits, open questions, appendices. Do not reduce it to keywords or a short summary. *(M-04 steps 2 and 6a)*
2. **Compare each meaningful idea with the book.** Is it new, already there, a refinement, a correction or contradiction, evidence, or an open question? The labels help thinking; they are not paperwork. A later document is not automatically right. *(step 3)*
3. **Examine it by reasoning.**
   - Does the argument hold?
   - Do the assumptions support the conclusion?
   - Is the concept defined clearly?
   - Is there a counterexample?
   - Does it conflict with what is established?
   - Does the source really say it?
   - Is an inference being presented as a fact?
   - Would a reader understand what it means in practice?

   Use formalization, code inspection or scripts only where they help answer one of these questions. Keep "the source says", "I verified" and "I infer" apart. *(step 4; rule 1)*
4. **Write it into the book** in plain language **and** precisely, saying what it means in practice. Improve existing sections rather than duplicating them. When a source corrects the book, examine the evidence and explain the change. *(step 5)*
5. **Keep a light record:** the idea, its source reference, what was done with it, and any open question. *(steps 6 and 6a)*
6. **Look back:** did I miss anything important, and does what I changed still fit what it touches? *(step 7; see the note below)*
7. **Move on.** An open question is recorded, not a reason to stop. *(rule 3)*

## Three review perspectives, used in proportion

1. **The Professor's own review**, before asking anyone else.
2. **An independent AI reviewer**, at sensible points (e.g. per five files, or after a foundational change). It gets the claims and the evidence, not my conclusions, and is asked to find missed ideas, misreadings, weak arguments, contradictions and overreach.
3. **A ChatGPT chapter review** of clarity, logic, completeness, traceability and fit with the whole theory.

Findings are judged on their merits: accept what is justified, reject unsupported criticism with reasons, keep real uncertainties open. **No endless cycles:** a further review follows only a foundational change.

## Tools

Scripts are for mechanical help, such as checking that a cited line exists. **A passing check is never evidence that the theory is complete or correct.**

## Not done any more

- line-by-line coverage marking;
- lists of non-propositional lines;
- strict per-row validation;
- a new audit document per round;
- repeated full-book scans;
- re-wording stable concepts without a substantive reason.

Findings go into the chapter itself, with one short review note per round.

## Research continuity and controlled revision (Principal, 2026-10-18)

The objective is to complete the theory, not to perfect each chapter indefinitely.

- **Baselines.** Each reviewed chapter becomes a **versioned baseline** (identified by date and hash). Research continues with the next authorized batch even when non-blocking questions or cross-chapter dependencies remain open.
- **When an earlier chapter may change.** Only for one of these reasons:
  - new evidence;
  - a new derivation;
  - a demonstrated contradiction;
  - a material traceability defect;
  - an authorized governance decision.

  Never for editorial preference alone.
- **Every revision records:**
  1. the exact previous and proposed statements;
  2. the reason and the supporting evidence;
  3. its type: substantive, cross-chapter, traceability-related, or editorial-only;
  4. its impact on existing conclusions, decision registers and downstream files;
  5. whether an independent review or a Principal decision is required.
- **Alternatives and decisions.** Unresolved alternatives stay explicit. A research recommendation is never silently turned into an authorized decision.
- **At each batch checkpoint:** consolidate the necessary changes across affected chapters, and report the remaining material issues. Open questions block the next step only if one of them makes it invalid or unauthorized.
- **Success criterion.** The theory becomes more complete and internally consistent across batches, while unnecessary revisions decrease and every material change stays traceable.

## Needs a formal decision (separately)

**Step 7 of M-04** asks after every file whether "the book still makes sense" across **all** chapters. The instruction's "avoid repeated full-book scans; review the affected material; broader coherence reviews at sensible intervals" would move the full check to the five-file review. That is the pending change **E-3**.
- **Benefit:** less repeated cross-checking per file.
- **Risk:** an inconsistency can persist for up to five files.
- **Until it is approved:** step 7 is applied in proportion. I review the passages and definitions a file touched, and do a full sweep only when a file changes the meaning of an established term.

**Still pending, not blocking:** A-1 v2 (the Principal may adopt, trim or set it aside) and the confirmation of the M-04 v1.0 baseline hash.
