# Coding task: what does each record observe?

Use ONLY `EVENTS.json` (36 events: an id with a short description, an **operation** such as ADOPT, START or RAISE, a cluster = its source anchor, and a target) and `SOURCES.md` (verbatim register rows, two session-log excerpts and one git commit). No other path, no web, no commands.

For **each** event, code two levels. Quote every value.

**Level 1: `observation_type`, relative to the event's own operation.** Ask: *does the source attest that this operation was actually performed, attempted, or refused on this target?*
- `ACT_OBSERVATION`: yes. The source records the operation being performed, or being attempted / proposed and then refused or held. A register row that itself *performs* the operation (e.g. a ruling that adopts something is an ADOPT) counts.
- `NORM_STATEMENT`: no act of this operation is recorded; the source only states that it is permitted, forbidden or required (e.g. "X is not authorized", "does not begin").
- `GENERIC_PRACTICE`: the source states a general rule or practice, not a particular case.
- `UNKNOWN`: the sources do not settle it, including where no source text for the anchor is provided.

**Level 2: `source_act` (mandatory).** Which row or anchor is the record, and what operation does **that row itself perform**? Use the operation list: ADOPT, AUTHORIZE-IMPL, AUTHORIZE-PLAN, REGISTER, OPEN-WORK, START, RAISE, ASSIGN-ID, SUPERSEDE, ANNOTATE, or OTHER:<verb>, NONE, UNKNOWN.

Also code **`deontic`** for NORM_STATEMENT records: PERMITTED / FORBIDDEN / REQUIRED / UNK. For other records write n/a.

Also code **`dual`**: YES if the record is *both* an act of one operation (by the row itself) *and* states a norm about another act; else NO; or UNK.

Code each event on its own. There are no expected answers.

Write **CODING.json** ({"events": [{"event_id", "observation_type", "type_quote", "source_act": {"anchor", "operation"}, "deontic", "dual"}]}) and **NOTES.md** (one line per hard or ambiguous event, saying why).
