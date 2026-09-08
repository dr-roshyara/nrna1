# Phase 5J — `id` Semantics Adjudication

## The discrepancy, restated

D285-6 lists `id` as a peer field in `{id,c,t,Π}`. `e_equality.py` computes `id = H(P,e,c,t,Pi)` — a
SHA-256 hash of the *other* fields.

## The authorization's own required distinction: field existence vs. field derivability

**These are not necessarily in conflict.** A derived identifier can legitimately be *represented* as a
field of a record (e.g., a database row's own `id` column, even though it is computed from the row's
content by a hash function at insert time) without being an independent *primitive* input. D285-6's own
prose does not state whether `id` is primitive or derived — it simply lists it as present. **This phase
does not assume either interpretation**, per the authorization's own explicit instruction.

## Testing which reading the evidence supports

- **Is `id` primitive (an independently-supplied value)?** No evidence found supporting this reading —
  no document shows `id` being supplied externally rather than computed.
- **Is `id` derived?** **Directly evidenced, in the one place `id` is actually computed**
  (`e_equality.py`'s own `H(P,e,c,t,Pi)`).
- **Are both representations compatible?** **Yes, under the "field existence ≠ field derivability"
  reading** — D285-6's own `{id,c,t,Π}` list can be read as "the record, once constructed, *carries*
  these four fields" without contradiction, since a derived field is still, once computed, a genuine
  field of the resulting record.
- **Is the code an implementation refinement of the prose?** **Plausible, and the best-supported
  reading of this specific discrepancy** (in contrast to the broader field-set conflict, `08`, where no
  compatible reading was found) — `e_equality.py`'s hash-based `id` is a natural, unsurprising way to
  implement "a record that carries an `id` field," and does not contradict D285-6's own prose in the
  way the Assertion-unpacking conflict does.
- **Do the definitions conflict?** **No** — this is the one sub-question in this phase's entire scope
  where the evidence supports compatibility rather than conflict.
- **Can authority be established?** No explicit governance marker exists for `id` specifically, but the
  compatibility finding above makes this less consequential than the broader Assertion conflict.

## Verdict

**Compatible, not conflicting** — `id` as a derived hash (code) is a reasonable, non-contradictory
implementation of `id` as a listed field (prose). This is the **one genuinely resolved sub-question**
in this phase's entire investigation, reported explicitly as such rather than folded into the broader
"unresolved" verdict for the rest of the Assertion conflict.
