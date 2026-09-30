# Task (frozen): which differences between two hypotheses can this record actually test?

Use ONLY:
- `HYPOTHESES.md`;
- `RECORDS.json` (36 source spans, opaque ids);
- `CODING.json` (ACT and NORM components of each record) plus `CODING-REVIEW.json` (a reviewer's corrections to it; apply them where they state a corrected value);
- `SOURCES.md` (the full sources, with dates in the register rows).

No other path, no web, no commands.

## Part 1: norm-change sequences
For each **target** that has two or more NORM records, order them in time (use dates or register order, and say which). Build the sequence: norm state → [the intervening act, i.e. which record performs it, the actor, the operation] → next norm state.
- Mark every **polarity change** (e.g. UNAUTHORIZED → AUTHORIZED).
- For each change, is an intervening **norm-making act** attested (quote it), and by whom?
- Does any **execution record** (an act of doing the governed work) appear between or after norm states? Quote it.

## Part 2: status mediation
List every episode in which a norm's **force** (in force / not in force) changes because of a **status change** of the act that made it (e.g. prepared → adopted). How many **independent** episodes are there (different decisions, not repetitions)? Quote each.

## Part 3: identifiability
List the predictions on which H-D and H-B **differ**; at least these:
- (P1) whether norm polarity changes need an intervening norm-making act;
- (P2) whether execution conforms to the norm in force;
- (P3) whether execution outcomes feed back into norm changes;
- (P4) whether the force of a norm is status-mediated;
- plus any others you find.

For each prediction state:
- what H-D predicts and what H-B predicts;
- whether this record **contains the observations needed** to tell them apart;
- a status: IDENTIFIED · PARTIALLY IDENTIFIED · NON-IDENTIFIABLE · CONFOUNDED · NOT OBSERVED · FALSIFIED (say which hypothesis) · INSUFFICIENT EVIDENCE, with quotes.

**Keep two things apart:** "the record does not contain X" (a **limitation of the corpus**) vs "X does not happen" (a **property of the domain**). Never infer the second from the first.

## Part 4: the minimum discriminating evidence
For each prediction that is not IDENTIFIED, specify the **smallest new observation** that would discriminate: target hypothesis, required observation (fields), minimum number of independent episodes, independence requirement, and the falsifier for each hypothesis.

Write **ANSWER.json** ({sequences: [...], polarity_changes: [...], status_mediation: {episodes: [...], independent_n}, predictions: [{id, HD, HB, observable_in_corpus, status, quotes}], min_evidence: [...]}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / HYPOTHESIS / UNKNOWN; list the ambiguities).

There is no expected answer.
