# S2-F004 · Three distinct temporal questions are grouped as one "family"

**Class:** CHALLENGE
**Status:** OPEN · review record only

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F001-authority-temporal-semantics-gap.md` |
| **Lens** | Temporal · Vocabulary · Zero |

---

## FINDING

S1-F001 lists three items as one family, on the ground that all concern *"what the record says later"*:

> `W:C-18` version binding · `W:C-3` authority adequacy · `W:C-15` retraction — *"same family: what does the record say later."*

**These are three different temporal questions with three different subjects.** Grouping them invites a single answer to what needs three.

| Question | Subject | Whose property is changing? |
|---|---|---|
| Was the **authority** valid at T? | the grant | the authority's own validity interval |
| Was the **claim** valid at T? | the claim | `TemporalValidity` (already modelled — see S2-F003) |
| Does the **claimant** still assert it? | the agent's stance | neither of the above |

## EVIDENCE

Retraction is a change in the **asserter's stance** with no necessary change in either the authority or the claim's validity window: a claim may remain within `valid-from`/`valid-until`, admitted under an authority that never lapsed, and still be withdrawn by the one who asserted it. Conversely an authority may lapse while the claimant's stance is unchanged. **The three vary independently**, which is the test for whether they are one concept or three.

## WHY IT MATTERS

The corpus's own recorded method rule — quoted in `S1-F002` — is *"**preserve these distinctions rather than prematurely normalizing them**"*, and identifies over-grouping as *"a DDD vocabulary problem, not simply a naming problem."* **S1-F001 groups where S1-F002 warns against grouping.** The two Session-1 artifacts are in tension on method, from the same source document and the same day.

This is also a **Zero-lens** matter: three different absences (no validity interval · no version binding · no stance record) are not one absence, and collapsing them is how one remedy comes to look sufficient for all three.

## POSSIBLE IMPACT

Research framing only. Suggests the "family" be recorded as **three adjacent questions sharing a lens**, not one family sharing an answer.

## PROVENANCE

Derived from `session1/S1-F001` and `session1/S1-F002`. No corpus document read.

## STATUS

**OPEN — CHALLENGE.** Session-1's grouping is not overwritten; the independent challenge is recorded.
