# T-A reader packet — instructions for the reader

You are a reader for test **T-A**, a source-fidelity and falsification test. Your job is to **record what the released sources say**, following the procedure in `PREREGISTRATION-r3-BLIND.md`. You are not asked to judge whether any hypothesis is true.

## What you receive

1. `PREREGISTRATION-r3-BLIND.md` — the frozen procedure, with the declared expectations removed.
2. The **released source objects**, identified only by git commit and sha256 (list in the release record). Read each one **completely**, by object (`git show <commit>:<path>`). Before reading, check its sha256; on any mismatch, stop.
3. `aggregate_blind.py` — optional. It validates your records (`python3 aggregate_blind.py --scope UNIVERSAL --released <items> records.jsonl`). Its `expected_finding_matched` output is meaningless in this packet and must be ignored.

You receive nothing else: no analysis documents, no other reader's records, no model results. If you have seen any of these, say so in your records (`reader` field).

## What you produce

`records.jsonl`: one JSON object per relevant passage, with the fields of §4 of the procedure.

For each passage:
1. **Fill `operation` completely first**, using the §3.0 procedure:
   - use the source's declared type if it gives one;
   - otherwise use the described action;
   - actor and object are context only;
   - never use the effect to decide the kind;
   - use `UNKNOWN` when the kind cannot be established without the effect.
2. **Only then fill `effect`**, from the source's own wording.
3. **Classify** using only the four definitions in §3.1.

Further rules:
- Split an operation only where the source itself describes separate steps.
- Record ambiguity; do not resolve it silently.
- State your identity and your independence class (INDEPENDENT) in every record.

## Independence

- You are commissioned by the human, not by the author of the hypothesis.
- Do not contact the other reader, and do not read their records, before you hand in yours.
