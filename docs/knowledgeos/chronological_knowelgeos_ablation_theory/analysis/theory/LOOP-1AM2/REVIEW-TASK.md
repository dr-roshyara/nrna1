# Independent review task (frozen)

You are an independent reviewer. Another researcher (A) executed `TASK.md` using only `SOURCES.md`, and produced `EXECUTION.json` and `EXECUTION.md`. You do not know what anyone expects the result to be. Your job is to inspect **A's execution against the evidence**, not to agree or disagree with A. Use only these four files. No other path, no web, no commands.

For every coded event, and for the model verdicts, check each of the following.

1. **Permitted evidence.** Did A use only SOURCES.md? Flag any fact that is not in it.
2. **Source accuracy.** Re-read each quote. Is it verbatim, and does it actually support the coded value? Check `actor`, `target_type`, `evidence` (the number *and* its unit), `exception`, `outcome` and `ground` individually.
3. **Silent inference.** Is any value filled that the text does not establish (it should have been UNK)? Or is any value left UNK that the text does establish?
4. **Category confusion.** Is operation, object kind or target confused? Examples:
   - a target *home* vs the *item* promoted;
   - a candidate-pattern registration vs a promotion;
   - a recommendation vs a decision;
   - an actor that *recommends* vs an actor that *decides*.
5. **Independence.** Are the clusters right? Are items from one decision wrongly counted as independent, or independent decisions wrongly merged?
6. **Logical validity.** Given the frozen falsification rule in TASK.md §3, do A's verdicts follow from A's own coding? Recompute each deciding pair. Also recompute the verdicts using **your** corrected coding wherever you disagree.
7. **Alternative interpretations.** For each deciding pair, state the strongest alternative reading of the source that would change a verdict, and whether the text rules it out.

Classify every disagreement with A as exactly one of: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive. Do not average or soften. If A is right, say so.

## Output (write both files in this directory)
- **REVIEW.md**, with these sections:
  - Summary verdict;
  - Per-event findings (a table);
  - Recomputed model verdicts (A's coding vs your corrected coding);
  - Alternative interpretations;
  - Disagreement register (each item with its class);
  - What this evidence can and cannot establish.

  Label statements SOURCE FACT / OBSERVATION / INFERENCE / FORMAL CONSEQUENCE / UNKNOWN.
- **REVIEW.json:**
  - `corrected_events`;
  - `verdicts_A_coding`;
  - `verdicts_corrected`;
  - `disagreements`: a list of {event, field, A_value, reviewer_value, class, quote}.
