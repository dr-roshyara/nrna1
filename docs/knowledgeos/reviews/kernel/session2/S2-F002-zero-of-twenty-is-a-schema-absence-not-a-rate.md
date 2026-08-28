# S2-F002 · `0/20` is one schema fact observed twenty times, not twenty observations

**Class:** NEW FINDING (strengthens S1-F001)
**Status:** OPEN · review record only

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F001-authority-temporal-semantics-gap.md` |
| **Lens** | Evidence · Zero |

---

## FINDING

S1-F001 presents its central result as a **measured rate**: *"Grants with validity information: 0/20."* Presented that way it reads as an empirical frequency — as if some grants might have carried validity information and none happened to.

**If the grant record has no field in which validity could be expressed, then `0/20` is not twenty observations. It is a single structural fact — the schema cannot represent validity — encountered twenty times.**

## EVIDENCE

The same table reports `0/20` for **three** properties simultaneously: validity, delegation, ownership. Three independent properties all landing at exactly zero across twenty records is the signature of **absence of representation**, not of measurement. By contrast the properties that *are* representable vary as one expects of real measurement: human-act reference 20/20, immutable commit addressing 13/20, descriptive references 7/20.

**The variation pattern itself distinguishes the two kinds of zero.**

## WHY IT MATTERS

This **strengthens** S1-F001 rather than weakening it, and makes it cheaper to state:

- A *rate* invites the question "would a larger sample find some?" — which a schema absence forecloses.
- A *schema absence* is a categorical claim about what the record **can** express, which is the stronger and more useful form.
- It connects the finding to the **Zero lens** properly: this is *absence of the means of expression*, which is a different thing from *absence of a value*. Conflating them is precisely what the Zero discipline exists to prevent, and S1-F001's framing conflates them.

## POSSIBLE IMPACT

Research framing. Restating the finding as *the authority record has no representation for temporal validity* is both stronger and verifiable from a schema rather than from a sample.

## PROVENANCE

Derived solely from the table in `session1/S1-F001`. Not verified against the record schema — outside this review's read scope.

## STATUS

**OPEN.** Offered as a reframing that Session 1 may adopt or reject; Session-1's document is not modified.
