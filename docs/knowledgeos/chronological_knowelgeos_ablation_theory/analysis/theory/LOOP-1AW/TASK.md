# Task (frozen): anchor each event to a unique verbatim span

Use ONLY `EVENTS.json` (36 events, each with a description `event_id`, an operation, an anchor = the source it was drawn from, and a target) and `SOURCES.md` (the verbatim sources). No other path, no web, no commands.

**The problem.** Some events are currently distinguished from each other only by their description, not by any source text. A research event must be **individuated by the source**: there must be a verbatim span of text that states *this* event specifically.

For **each** event:
1. Find the **smallest verbatim span** in SOURCES.md that states the event described: the act (performed, attempted, refused) or the norm it describes, **on this target**. Copy it exactly, with its section heading (e.g. "## R-60"). If several spans qualify, give the most specific one.
2. **Status:**
   - `ANCHORED`: a span exists that states this event and **no other event** in the list;
   - `SHARED`: the best span is the same as (or contains) the span of another event, so the two cannot be told apart by source text. Name the other event(s);
   - `UNANCHORED`: no span in SOURCES.md states this event; the description goes beyond the text.
3. For SHARED events, say whether they are **one event described twice** (→ MERGE), or **two events stated in one sentence** (e.g. an act and a norm in the same clause) → DUAL-SPAN. Quote the words that carry each.
4. Does the description **add** anything the span does not state (a count, a target, an outcome)? List what is added.

Then answer one question about the two pairs below. Are both members ANCHORED to **different** spans, and do those spans differ in **who acts** (authority), with everything else the same?
- (a) "ADOPT R-81..85 by the Chief (declined)" vs "ADOPT R-81..85 by the DA (R-86)";
- (b) "AUTHORIZE-IMPL R-70 (ARB)" vs "AUTHORIZE-IMPL R-89 (Chief, PREPARED)".

Write **ANSWER.json** ({events: [{n, event_id, span, section, status, shared_with, merge_or_dual, description_adds}], counts, distinct_records_after_merge, authority_pairs: {a: {both_anchored, distinct_spans, differ_only_in_actor, quotes}, b: {...}}}) and **ANSWER.md** (labels SOURCE FACT / INFERENCE / UNKNOWN; list the ambiguities).

There is no expected answer.
