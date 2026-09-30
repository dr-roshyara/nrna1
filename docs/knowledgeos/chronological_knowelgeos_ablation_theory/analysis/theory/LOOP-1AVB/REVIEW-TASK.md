# Independent review of a de-cued coding pilot (frozen)

**Setting.** In a first pilot, two same-family coders coded 36 events. The event ids contained interpretive descriptions (`CODING-P1-cued.json` shows one of them). In this pilot, two fresh coders (P3, P4) coded the same events with **opaque ids**: operation, anchor and target only (`EVENTS-decued.json`, `TASK.md`, `SOURCES.md`). `ID-KEY.json` maps the ids to the old descriptions. `SCORE.json`:
- P3~P4 type agreement 1.000;
- source_act 0.833;
- vs the audit 0.889;
- 3 labels changed vs the cued pilot.

`COLLISIONS.json` lists **3 groups (11 events) that are indistinguishable under the de-cued fields**.

Use only these files; you cannot run code.

Check each of the following:
1. **Recompute** P3~P4 type agreement, the changed labels, and the collision groups.
2. **Classify each of the 3 changed labels** as:
   - COLLISION (the event cannot be told apart from a sibling without the description);
   - CUE-DRIVEN JUDGEMENT (distinguishable, but the label depended on the description);
   - or CORRECTION (the de-cued label is more faithful to SOURCES.md).

   Check each against the source text and quote it.
3. **The individuation problem.** Do the collision groups show that some "events" are individuated **only by the coder's description**, not by a source-anchored span? Examine the REGISTER pair and the OPEN-WORK pair in particular: those were the two strict witness pairs for *kind* (`AUDIT-1AR-REVIEW.json`). Is kind's contrast located in the source text at all, and if so, at which span?
4. **Agreement again.** P3~P4 is still 1.000. Is it still degenerate (quote overlap; identical choices on hard cases)? Does de-cueing change what the agreement means?
5. **Schema implication.** For the human's choice between a two-level schema (A) and minimal v1 rules (B): does this pilot show that event records need **span-level anchors** (a quoted source span that individuates the event), in addition to row-level anchors? Is that required under both A and B?

Classify each issue as: wording · source interpretation · coding · evidence scope · independence · formal reasoning · substantive.

Write **REVIEW.md** and **REVIEW.json** ({recomputation_matches, changed_labels: [{event, class, quote}], individuation: {collision_groups_confirmed, kind_contrast_in_source, span}, agreement_assessment, schema_implication, disagreements: [...]}).
