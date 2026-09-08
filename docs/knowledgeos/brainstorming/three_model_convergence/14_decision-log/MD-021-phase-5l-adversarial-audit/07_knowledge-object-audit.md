# Phase 5L — Knowledge-Object Audit

## Testing "preserving a historical definition" vs. "keeping it live as an operational knowledge object" (per the authorization's §9)

Phase 5K's own Option E (`09`) proposes registering D1/D3 and D2/D4 as `K-2-Variant-Alpha`/`Beta`, "each
retaining its own field structure... with canonicalization explicitly postponed." **This phase tests
whether this framing conflates "preserved" with "operationally usable."**

## Applying the authorization's own required status vocabulary

| Variant | Current status (as Phase 5K left it) | Status this audit finds necessary, if Option E were adopted |
|---|---|---|
| D1/D3 (`K-2-Variant-Alpha`) | Implicitly `COMPETING`/`RESEARCH` (Phase 5J `15`) | Would need an explicit `PROPOSED` or `COMPETING` marker distinguishing it from a hypothetically `CURRENT` status — **Phase 5K's own Option E does not itself assign this**, leaving the variant's own operational status ambiguous |
| D2/D4 (`K-2-Variant-Beta`) | Same | Same gap |
| D5 (`e_equality.py`, narrower scope) | Not addressed by Option E at all — Phase 5K's own `04`–`09` treat D5 as a separate, narrower-scope record throughout, but Option E's own text (`09`) only names "Alpha"/"Beta," not a third variant | **A genuine gap**: Option E's own 2-variant framing silently drops D5 from the preservation scheme, even though Phase 5J/5K's own registers always treated it as a third, distinct record |

## Verdict

**A real, if minor, gap found**: Option E's own proposed 2-variant naming scheme (`Alpha`/`Beta`)
under-represents the actual register, which contains (at minimum) 3 materially distinct records (D1/D3,
D2/D4, D5). **Corrected finding**: any future adoption of Option E should register **3** variants, not
2, or explicitly justify folding D5 into one of the other two with a stated reason (none is currently
given).

## Does the absence of explicit status distinctions (HISTORICAL/RESEARCH/COMPETING/CURRENT/DEPRECATED/
SUPERSEDED/PROPOSED/UNRESOLVED) create a governance problem?

**Yes, mildly** — without an explicit status tag, "preserved" could be silently read by a future
consumer as "equally valid, pick either," when the actual evidentiary picture (`10` of Phase 5K, `04`
of this phase) shows the three records differ in evidentiary richness (D2/D4's own paired
`t285_equality.py` performs more genuine computation than D1/D3's own `t285_reconcile.py`, per Phase
5I's own `03`/`04`). **This audit does not introduce new status tags into the corpus** (outside its own
authorized scope) — it only records that their absence is a real, disclosed gap in Option E's own
completeness, not a fatal flaw.
