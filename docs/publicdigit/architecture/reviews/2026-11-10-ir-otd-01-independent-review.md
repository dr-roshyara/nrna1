# IR-OTD-01 — Independent Architecture Review: Organisation Tactical Design (§49–§51)

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49, §50 (incl. §50.15), §51 (incl. §51.11–§51.13), with §9–§12, §38, §45 as base — SHA-256 `8b7a6b3ee981699eaf582755a0176b8b138f98516b7f013b1d54ca5a157edab1` |
| **Reviewer** | A DeepSeek-family model, designation **`deepseek-chat`**, running inside the Claude Code command-line harness (an Anthropic product). **Not a Claude model.** Disclosures in §7 |
| **Integrity** | I computed all ten hashes myself with `sha256sum -c MANIFEST.sha256`: 10/10 OK. The subject's computed hash equals the required value. No stop triggered |
| **Status** | Review and report only. It decides nothing for the Principal, modifies nothing, and authorises no implementation |

**Note on the author's self-review.** §51.11 was written by the design's author and is therefore **not independent evidence**. I have re-derived its eight findings from the normative text and the decision record; where I agree, I say so and give my own reason; where the correction has not reached the text it corrects, that is itself a finding (§3, H-6).

---

## 1. The business behaviour in plain language

An organisation — a party, association, union or federation — **draws its own map of itself**. It defines its levels (national, province, district, ward; or zones, or country chapters for a diaspora body), sets up units at those levels, and moves, divides, merges and closes them over time. Geography is the official atlas it *may* point to; it is never the template the organisation must copy.

**Every member has a place.** If no lower unit applies, that place is the organisation's top (root) unit. A province committee can adopt a rule — "everyone the organisation records in official district D4 is looked after by our district D3" — and from its effective date every qualifying member in D4 descends to D3 with no individual decision. A committee can also place one person expressly, and can expressly except one person from a rule. An ordinary placement is a floor (rules may still place them further down); an express exception wins while it stands.

**Closing a unit never deletes history and never leaves a member without a place.** Its allocations lapse, its rule-derived members re-derive by the remaining rules, its explicitly placed members fall back to the nearest active higher unit and are flagged "awaiting placement" until a competent body decides. Membership's ups and downs change only whether a member *counts*; they never rewrite the belonging record. Election asks the organisation who belongs where **as at a cut-off and as known at that moment**, and decides for itself who may vote.

The design implements this with three things: the organisation's **structure** (trees and units, changed only by whole change sets), its **allocation rules** (one scheme per unit that has one), and each person's **belonging aggregate**. The member's actual place is **derived** from dated records rather than written down, so a structural change needs no mass update.

---

## 2. Independent architectural assessment

### 2.1 What is sound

The core of the design is, in my judgement, sound and better than the alternatives it replaces:

- **The five facts are genuinely kept apart** (§51.2): official reference, organisational unit, area allocation, recorded association, belonging. Each has its own owner and source of authority. This is the design's strongest feature.
- **Belonging is derived, not written.** The descent rule (§51.3) is a pure function of dated, recorded facts: root → recorded association → effective allocation → active child, repeated. It is reproducible at any (date, τ), needs no mass writes when a unit is created or closed, and satisfies D-73's real requirement (facts recorded once, corrections as new records, "what was known at τ" reproducible) without assuming event sourcing. The author's claim that D-73 does not require event sourcing (§49.8) is correct.
- **Closure never deletes and never writes a person's record** (§50.5, C-2, C-3). Allocations lapse by derivation (A-2), so closure needs no cross-aggregate write. This is a clean solution to a problem that usually produces a cascade of updates.
- **The placement / exception distinction** (§51.11 R-2) is exactly what D-116 needs: D-116 makes an *exception* prevail, while D-117 makes a *rule* apply to all existing members. Without R-2 those two rulings contradict each other. The design found and fixed this. I agree with R-2 independently.
- **Refuse, don't bump** for Organisation's stream (§49.8) correctly applies D-73 and D-93's knowledge-time contract, and the refusal beyond `closedThrough` is stated.
- **Coverage is not allocation** (§51.2): coverage says which areas a unit *serves*; allocation says which members it *holds*. The design does not collapse them (D-80).
- **Authority comes from the written rules, never from the platform** (§49.2 C-1, §50.10, S-7). No organ is named by the platform.

### 2.2 What is not yet sound

The derivation is right in shape but **incomplete at exactly the points where two sources meet**:

1. **The number of a member's geographic belongings is left to the rules, and the derivation has no answer when the rules are silent** (§3, H-1). The commission asks whether there is *exactly one* effective belonging; the design guarantees *at least one*. D-114's recorded text supports the design's reading, but the derived result for two placements is undefined.
2. **Precedence is not complete.** Authority is not consulted when a later decision ends an earlier one (H-2); a shallower-scope rule can bypass a deeper one in a way A-3 says cannot happen (M-1); a Geography reconciliation can merge two areas that each carry an allocation, which A-1 cannot see (H-3).
3. **The root default is unconditional in the text and conditional in reality** (H-4): B-7 cannot hold before the root exists or after it is closed.
4. **Two versions of the same rule now live in the document**: the author's corrections R-1…R-8 sometimes contradict the normative text they correct — most seriously §51.3 step 4, which still says any individual act overrides the rule (H-6).
5. **Re-admission is stated two ways** (§50.11 scenario 10 and §51.11 R-6) — a direct contradiction (H-5).

None of these requires a new model. They are rule-level corrections within the model the design has chosen. That is why the verdict is *ready after corrections*, not *not ready*.

### 2.3 The commission's eight questions, answered

**Q1 — Exactly one effective belonging; is "awaiting placement" always accompanied by a unit?**
**At least one is guaranteed, exactly one is not.** B-7 (§50.15) gives every effective member an effective geographic membership on every coverage date, defaulting to the root unit. Uniqueness exists only where the organisation's rules impose a limit (B-3; V-24 is [PK], not a platform rule). D-114's recorded text says "not decided here: how many geographic belongings a member may hold (the organisation's rules)". The commission's "exactly one" is therefore stronger than D-114; the design is faithful to D-114 but must still define the derived result when two placements stand (H-1). "Awaiting placement" is accompanied by the nearest active higher unit (C-2, R-3) — **except when there is no active ancestor or no root** (H-4).

**Q2 — Complete, deterministic precedence.**
**Not complete.** Three combinations have no determinate winner, and one has a determinate but unauthorised winner: two active placements (H-1); a later lower-authority decision ending a higher authority's allocation or exception (H-2); a shallower-scope allocation that lands below a deeper scope, making the deeper rule unreachable (M-1). A fourth — reconciled areas each carrying an allocation — is indeterminate at read time (H-3). The other combinations the commission lists (exception vs later general rule; exception vs later equal/higher decision expressly addressing it; placement as floor; closure) are determinate and correct.

**Q3 — Overlap.**
Deterministic **only** for one recorded association and a single-parent containment. Geography's containment is one parent per unit **per scheme** (Geography §3, slot `(CONTAINMENT, unit, scheme)`), which makes the matching areas of one association a chain and A-7's "most specific wins" unique. Two gaps: the design does not name the scheme used (M-4), and reconciled identities break the chain (H-3). The design cites "Geography G-4" for single-parenthood; G-4 is in fact "tiers strictly increase along containment" — the correct source is the containment slot key (L-1).

**Q4 — Closure and re-admission.**
Closure is well covered at every level (C-1, C-2, C-3, C-4, R-3; scenarios 14–22): a province with active children is inapplicable unless the change set re-parents or ends each child; a district's allocations lapse and its members re-derive or fall back; several closures at once resolve to the nearest active ancestor at the date; future-dated allocations lapse at closure. Two exceptions: **the root** (H-4) and **the meaning of the "awaiting placement" flag** for members whose place the rules already determine (M-5). Re-admission is **contradictory** (H-5): R-6 says rule-derived belonging re-applies and explicit placements do not revive; scenario 10 says "a new act places the person".

**Q5 — Reproducibility under one τ.**
The model is the right shape — every input is dated and read at τ — but three things must be settled before the claim can be made: whether Organisation's structure, belonging and allocation schemes share **one** knowledge barrier (M-3); whether Geography containment is read "at d" or "as known at τ" (§51.3 says at d, R-8 says at τ — L-2); and which root the default uses "as at (d, τ)" (H-4). Beyond `closedThrough`, queries are refused, never answered partially — correct.

**Q6 — Geography affects belonging only through an act or rule.**
Through an **act**: yes (A-5, B-1, S-8). Through a **rule**: yes — but the design does not state that a Geography containment change can change a rule-derived result without any organisational act, and it raises `AllocationReviewNeeded` only for an area's end, division or reconciliation, not for a mere re-parenting (M-4). Reconciliation can also merge two allocated areas (H-3). A rule that the map silently rewrites is still a rule, but it should be visible.

**Q7 — Aggregates.**
The three boundaries are sufficient for the invariants that are genuinely local: A-1 over one reference, A-6, A-7, B-1, B-3, B-4, B-8, S-0…S-4. The problems are at the seams: A-1 is not enforceable over **canonical** identities (H-3); B-5 is a check-then-act stated as an invariant (M-8); A-2 is derived and correctly not enforced at write, but the claim that the change-set check "finds" lapsed allocations is unsupported (M-6); a **unit moving between trees with continuity** is an atomic write across **two** Tree aggregates, which the "the tree is the consistency boundary" argument does not cover (M-2); B-7 is derived and depends on the root existing (H-4).

**Q8 — Ownership.**
**Respected.** Organisation holds structure, belongings and the allocation facts; Governance holds the decisions, competence and the rule content; Membership holds coverage only; Reference Geography holds references and containment; Election decides eligibility and entitlement. I found no ownership violation, no duplicated owner, and no fact invented by the platform. Two wording points only: the allocation *rule* is Governance's and the allocation *fact* is Organisation's — the design says so; and the recorded geographical association sits in the person's belonging aggregate, which is the right place for it (§51.8).

### 2.4 The commission's scenarios

| # | Scenario | Outcome under the design | Verdict |
|---|---|---|---|
| 1 | Ordinary placement at Y, then a general allocation to D3 | Placement is a floor; descent from Y applies the D4→D3 rule | **Pass** — but only after H-6 corrects §51.3 step 4 |
| 2 | Exception to D3, then a later general allocation | Exception stands (R-4: a later general rule does not end it unless it says so) | **Pass**, subject to H-2 (who may grant the exception) |
| 3 | Exception, then decision of an equal or higher authority, expressly / not | Expressly → ends; not → stands (R-4(3)) | **Pass** |
| 4 | Allocation to an area contained within another allocated area | A-7 most specific wins; unique given one association and single-parent containment | **Pass**, subject to M-4 and H-3 |
| 5 | Closure of a district holding explicit and rule-derived placements | Explicit → nearest active higher, flagged; rule-derived → re-derive, flagged (C-2 + R-3) | **Pass on rules**; flag semantics M-5, detection M-6 |
| 6 | Closure of a province with several active children | Inapplicable unless the change set re-parents or ends each child (C-1) | **Pass** |
| 7 | Re-admission after a Membership end | R-6: rule-derived re-applies, explicit does not revive — **but** scenario 10 says a new act places | **Contradiction — H-5** |
| 8 | Re-admission after an earlier explicit placement or exception | Anchored to the old coverage interval; no revival (R-6, §12.2) | **Pass**, subject to H-5's wording |
| 9 | A Geography division, and a Geography correction | Division → `AllocationReviewNeeded`; ended reference keeps matching; new children stop at Y (R-7). Correction/reconciliation → **H-3** | **Partial** |
| 10 | Historical query before and after a decision was recorded | As at (d, τ); both versions of knowledge are reproducible | **Pass**, subject to M-3 |
| 11 | Query beyond `closedThrough` | Refused, never answered partially (§49.8(4), §50.8) | **Pass** |
| 12 | *Added:* two placements for one member and capacity, rules silent | No rule picks one; the derived effective belonging is ambiguous | **Gap — H-1** |
| 13 | *Added:* reconciliation merges two areas, each with an allocation | A-1 (over references) does not see it; "nothing changes" is false | **Gap — H-3** |
| 14 | *Added:* members before the root exists; the root closed | B-7 has no unit to return | **Gap — H-4** |
| 15 | *Added:* a new top unit created above the old root | Derived default re-derives unless the root placement was recorded | **Ambiguity — L-5** |
| 16 | *Added:* a unit moves to another tree with continuity (R-1a) | Requires one atomic change across two trees; level fit and allocations unaddressed | **Gap — M-2** |

---

## 3. Findings

Severity: **H** high, **M** medium, **L** low. Each finding states evidence (section and line of the subject unless another file is named), its kind, its impact, and whether a Principal decision is needed.

### High

**H-1. With two active placements for one member and capacity, the derived effective belonging is undefined; "exactly one" is not guaranteed.**

- **Evidence:** §50.6 B-3 (limits per capacity are the organisation's rules; "where the rules state no limit, none is enforced", §50.4 line 1181–1184); §50.15 B-7; §51.3 step 4; §51.4 A-1 (areas only, not persons); D-114 (decision record line 884: "Not decided here: how many geographic belongings a member may hold (the organisation's rules; V-24 [PK])").
- **Kind:** **fact** (the design permits it and does not resolve it). D-114's recorded text is **fact**; the commission's "exactly one" is a reading not in that text.
- **Impact:** **High.** An electorate or a unit-scoped list computed for a member with two member-capacity placements is ambiguous; the descent has two starting floors and no tie-break. It also makes the commission's Q1 unanswerable as posed.
- **Principal decision:** **Yes.** Does the Principal intend one geographic member belonging as a **platform default** (overridable by the organisation's rules), or only where the rules say so? If the former, B-3 needs a default limit; if the latter, the derivation still needs a stated result (for example "the deepest active placement wins; both are reported") — that second part is technical.

**H-2. Authority is not consulted when a later decision ends or supersedes an earlier one.**

- **Evidence:** D-115 (line 890: "A decision of the highest committee on the same scope prevails over the province committee's"); §51.5 line 1428 ("Change allocation … the old one ends at d"), which lets any later allocation replace any earlier one; R-4 (line 1530) lets "a later exception or placement" end an earlier exception without regard to authority; D-116 (line 896) names "a later decision for that member" and separately "a later decision of an equal or higher authority that expressly addresses it". The design never states who may **grant** an exception that overrides an allocation rule.
- **Kind:** **fact** (the lifecycle has no authority check) + **inference** (that D-115's "prevails" is meant regardless of order).
- **Impact:** **High.** A district committee's later act could undo a highest-committee allocation, which is the opposite of D-115's working direction; an exception could be granted at any level and would defeat the province's rule.
- **Principal decision:** **Yes.** Confirm that D-115's precedence applies **regardless of order**, and state who is competent to grant an exception (the authority of the rule it overrides, at least; otherwise the organisation's rules).

**H-3. A reconciliation can merge two areas that each carry an allocation; A-1 is stated over references and cannot see the conflict.**

- **Evidence:** §51.4 A-1 ("within one scope, at most one destination per area per date"); §51.6 scenario 12 ("Geography reconciles two D4 references — Nothing changes; read through `resolve`"); §51.11 R-7 (matching "by reference identity or containment as known at τ"); Geography §12 (line 672: reconciliation's valid time is **all of time**; belief changes only by position) and §2 (line 60: consumers holding B resolve it to A).
- **Kind:** **fact** (the two allocations become allocations for one canonical area) + **inference** (the resulting ambiguity).
- **Impact:** **High.** Before reconciliation the member matched one allocation; after it, both match the same canonical area in the same scope. A-1 as written is satisfied (two different references), yet the derived belonging is ambiguous — or silently changes — with no organisational act. Scenario 12's "nothing changes" is false whenever both references carry allocations in the same scope.
- **Principal decision:** No — a rule correction: A-1 over **canonical identities as resolved at τ**, and an `AllocationReviewNeeded` when a reconciliation would merge two allocated areas in one scope.

**H-4. B-7 has no answer before the root unit exists or after it is closed.**

- **Evidence:** §50.15 B-7 (line 1334: "while a person's membership coverage includes a date, they have at least one effective geographic member belonging"; enforcement "the effective belonging is the root unit"); §49.4 S-0 (line 929: "exactly one root **on any date it is in use**", provisional [TD]); §9 (line 116: "Organisation exists before its structure"); D-114 (line 884: "An organisation may start with only its root unit").
- **Kind:** **fact** (nothing in §49 prevents a root being ended, and nothing requires it before the first admission) + **inference** (the invariant fails on those dates).
- **Impact:** **High.** D-114's own rule ("every effective member … on every date") cannot hold for coverage before the root's establishment or after the root's closure. Every derived list silently loses those members or has no unit to return.
- **Principal decision:** **Yes.** Is the organisation required to have a root unit for as long as it has effective members (so the root cannot be closed while coverage exists), or is there a defined state for "no tree in use"? This is a business/governance constraint, not a technical one.

**H-5. Re-admission is stated two ways: R-6 says rule-derived belonging re-applies; scenario 10 says a new act places the person.**

- **Evidence:** §50.11 scenario 10 (line 1272: "Re-admission after a real end — New coverage interval; no revival; **a new act places the person** (§12.2)"); §51.11 R-6 (line 1532: "**Rule-derived belonging re-applies** from re-admission … Explicit placements and exceptions do not revive"); V-36 [PK] (decision record line 374: "ends completely; a new act is needed").
- **Kind:** **fact** (direct contradiction).
- **Impact:** **High.** The same event produces different placements depending on which section an implementer reads: a re-admitted member either re-derives to their rule-based unit with no act, or needs a new placement act. V-36 is [PK], not a platform rule, so it cannot settle it.
- **Principal decision:** No — a correction, unless the Principal wants V-36's "a new act is needed" elevated (which would contradict the derived model and D-114's no-mass-writes intent). The correction: scenario 10 adopts R-6.

**H-6. The author's corrections are not applied to the normative text they correct; §51.3 step 4 still implements the rejected rule.**

- **Evidence:** §51.3 step 4 (line 1399: "An individual act … **overrides** the rule-derived result while it stands"); R-2 (line 1528: only an **exception** overrides; a **placement is a floor**); D-116 (line 896); §51.4 C-2 (line 1418: only explicit belongings flagged) vs R-3 (line 1529: every affected member flagged); §51.4 A-1/A-7 vs R-1 (line 1527); §49.4 S-6 (line 935) vs §50.1 R-1 (line 1123, S-6 withdrawn).
- **Kind:** **fact** (two versions of the same rule are in the document).
- **Impact:** **High** for step 4: an implementer reading §51.3 makes every placement an exception, which contradicts D-116 and defeats D-117 (existing members would never move down). **Medium** for C-2 and S-6 (the reader must know to apply §51.11/§50.1).
- **Principal decision:** No — editorial: rewrite §51.3 step 4, §51.4 C-2 and A-1/A-7 in place (or add explicit "as corrected by R-n" markers), and mark S-6 as withdrawn in §49.4.

### Medium

**M-1. A shallower-scope allocation can bypass a deeper scope's allocation, contrary to A-3's claim that deeper scopes refine shallower ones.**

- **Evidence:** §51.3 step 2 (line 1397: a scope's allocation applies only when the current unit **is that scope**); §51.4 A-3 (line 1413: "a deeper scope refines a shallower one"); A-2 (destination may be any active descendant); D-115.
- **Kind:** **inference** (the algorithm is plain; the conflict is interpretive).
- **Impact:** **Medium.** A root-scope allocation "D4 → D5" takes a member straight to D5; the province's allocation "D4 → D3" is never reached. A-3 says this cannot happen, but the algorithm makes it happen. It may be the intended effect of D-115 (the highest committee decides differently), but the text should say so.
- **Principal decision:** **Yes** (small): may a shallower-scope allocation target a unit below another scope, making that scope's rules for the same area inert? If not, A-2 must forbid it.

**M-2. A unit moving between trees with continuity is an atomic write across two Tree aggregates.**

- **Evidence:** §50.1 R-1(a) (line 1123: "one change set across both trees (both tree versions advanced together; S-0 … S-3 checked in both)"); §49.7 (lines 995–1000: "the tree is the consistency boundary"); §49.4 S-3, S-5; §50.11 scenario 16.
- **Kind:** **fact** (the design says both versions advance together) + **inference** (this is a cross-aggregate atomic write).
- **Impact:** **Medium–High.** If the two tree writes are not atomic, a unit is in two trees or none. The design's own consistency argument excludes exactly this case. Level fit in the new tree (S-3, S-5) and the fate of the unit's allocation scheme (its destinations must remain descendants) are unaddressed.
- **Principal decision:** No — technical; but the design must either define the two-tree write as one consistency boundary (and state the proof obligation) or split it into end + establish (succession).

**M-3. Organisation's knowledge boundary is not defined as one barrier across structure, belonging and allocation schemes.**

- **Evidence:** §38.2 (line 529: "each source: Membership, Organisation, Governance" — one barrier per source); §49.8 (line 1017: "Organisation's stream"); §50.8 (line 1241: "Organisation's belonging stream"); §38.3 (one τ per operation); §51.8 (the scheme is its own consistency boundary).
- **Kind:** **inference** (the text can be read either way).
- **Impact:** **Medium.** If structure, belonging and allocations are three streams with three barriers, one τ must close all three, or a query can mix an allocation known at τ₁ with a tree known at τ₂. The design must name the barriers.
- **Principal decision:** No — correction: state that Organisation has one knowledge boundary (or list the barriers and require each to close to τ before answering).

**M-4. Geography containment is per scheme; the design does not name the scheme, and containment re-parenting changes derived belonging with no review signal.**

- **Evidence:** Geography §3 (line 74: `(CONTAINMENT, unit, scheme)`; line 104: "A unit's parent **per scheme**"); Geography Q-2 (line 779, other schemes not yet represented); §51.3 step 2 (line 1397: "Geography's containment at d"); §51.5 (line 1432: `AllocationReviewNeeded` only for division or end); §49.6 table (line 979: re-parented → **CoverageReviewNeeded**, not allocation).
- **Kind:** **fact** (per-scheme containment) + **inference** (the silent re-derivation).
- **Impact:** **Medium.** With one scheme represented today the ambiguity is latent, but the derivation is unspecified for a unit in another scheme; and a re-parenting that changes which allocation matches a member raises no signal for belonging.
- **Principal decision:** No — correction: name the scheme (the designated administrative scheme, §13), and raise `AllocationReviewNeeded` when containment changes could change a rule-derived result.

**M-5. "Awaiting placement" flags members whose place the rules fully determine.**

- **Evidence:** §51.1 (line 1374: "If it says nothing, those members are shown as 'awaiting placement'"); C-2 (line 1418) and R-3 (line 1529: "**every** member whose effective belonging changes **because of a closure** … flagged"); the commission's business description (prompt §2: "**unresolved** members are flagged").
- **Kind:** **inference** (the design's rule is broader than the business description).
- **Impact:** **Medium.** Rule-derived members who simply re-derive from D3 to Y are fully placed by the rules; flagging them "awaiting placement" contradicts the flag's meaning and produces mass flags on every closure. Either the flag means "no rule or act answers" (then R-3 is too broad), or it means "the committee should review" (then it is a different flag).
- **Principal decision:** **Yes.** What does "awaiting placement" mean: no answer, or a changed answer needing confirmation?

**M-6. The closure change-set check does not in fact detect lapsed allocations.**

- **Evidence:** §51.6 scenario 19 (line 1463: "**Change set check** from the closure date onward finds an allocation to a closed unit: lapses at closure (A-2), reported"); §49.7 step 4 (line 991: checks S-0…S-7 only); §51.8 (line 1482: "Destination validity (A-2) is **derived**"); §51.10 (line 1507: "the closure impact report as a read model" is [TU]).
- **Kind:** **fact** (the check is not specified) + **inference** (detection is a read model).
- **Impact:** **Medium.** Scenario 19 reads as a write-time check; if detection is a read model, the timing, authority and reporting of the closure impact are undefined, and a closure can proceed with lapsed allocations invisible until someone looks.
- **Principal decision:** No — correction: state that A-2 is derived, that the closure impact is a report (not a check), and when it is produced and to whom.

**M-7. "Allocation" has three senses in one design.**

- **Evidence:** D-79's individual **allocation** (a placement act: §50.5 line 1193 "Allocation after a structural change … new belonging keyed by allocation"); D-114's "an allocation in the creating decision" (line 884); §51's **area allocation rule** (lines 1383, 1411, 1427); D-117's "allocation rule".
- **Kind:** **fact** (vocabulary).
- **Impact:** **Medium.** A reader cannot tell whether D-114's "allocation in the creating decision" is an area rule (D-117 style, no mass writes) or a list of persons (mass writes), nor whether a placement is an allocation. This is exactly the kind of collision that produces a wrong implementation.
- **Principal decision:** No — correction: keep "**allocation rule**" for the area rule, "**placement**" for an individual act (D-79), and say which one D-114's "allocation in the creating decision" is.

**M-8. B-5 is a check-then-act across aggregates, stated as an invariant.**

- **Evidence:** §50.6 B-5 (line 1213: "A belonging is in a unit active on its start date … checked against structure as known at write time"); §50.7 (line 1231: structure and belongings "are not in one transaction").
- **Kind:** **inference.**
- **Impact:** **Medium–Low.** A unit can close between the check and the write; the record is then "invalid" at birth, though C-2/D-17 resolve it for display. Restate B-5 as a record-time assertion whose later invalidity is handled by C-2, never by rewriting.
- **Principal decision:** No.

### Low

**L-1. The citation for single-parent containment is wrong.** §51.11 R-1 cites "Geography G-4"; G-4 is "tiers strictly increase along containment" (Geography line 165/232). The correct source is the containment slot key `(CONTAINMENT, unit, scheme)` (Geography line 74/104). **Fact**; impact **low** (evidence accuracy — but it matters because A-7's determinism rests on it).

**L-2. "Containment at d" versus "read at τ".** §51.3 says "per Geography's containment at d"; R-8 says Geography is read "at the operation's shared τ (`positionAt(τ)`)". Both can be true together (containment **valid at d** as known at τ), but the two sentences must be reconciled in one phrase. **Inference**; impact **low–medium** (Q5).

**L-3. D-115 is labelled "Binding" though it is a working direction.** §51 status (line 1360) lists "D-115 (WD)" under **Binding**. The commission allows it to be challenged; the design applies it as if settled. **Fact**; impact **low** (status accuracy). No Principal decision unless the Principal wishes to settle it.

**L-4. "Start at the root unit" does not name the tree.** §51.3 step 1: the root of which tree — the geographic tree, or one wing's tree? B-7 applies only to the geographic tree, and wing belongings are additional. **Fact**; impact **low** (wording; scenarios 12, 22 imply the answer).

**L-5. The default root re-derives when a new top unit is created above the old one.** B-7's default is derived, not recorded (line 1337: "derived, not written"); if a new root is established above the old root, a member with no other placement would derive to the new root, although D-114's "members not moved stay where they are" suggests keeping the old unit. **Inference**; impact **low–medium**; Principal decision: no (clarify whether the default follows the root as at the date).

**L-6. S-0 is provisional [TD], yet B-7 depends on it.** §49.4 S-0 (line 929: "provisional until a written rule shows otherwise"); §50.15 B-7. **Fact**; impact **low** (dependency should be stated: if S-0 fails for an organisation, B-7's default is undefined).

**L-7. Label collisions.** Two different R-series (§50.1 R-1…R-4 reviewing §49; §51.11 R-1…R-8 reviewing §51) and an "A-1" that is both the baseline amendment (D-112) and the allocation invariant in §51.4. **Fact**; impact **low** (a reader following "R-1" or "A-1" without the section can land on the wrong rule).

**L-8. B-1's wording against D-114's residence rule.** B-1 says "a residence move is never a basis"; D-114 permits "a residence rule the organisation's written rules expressly adopt, recorded as the basis". The two are consistent (an address is a fact, a rule is a basis), but the wording should say "a residence **rule** is a basis only where the written rules adopt it; an address or a move never is". **Fact**; impact **low**.

---

## 4. Contradictions, missing rules, unsupported assumptions

### 4.1 Exact contradictions

| # | Contradiction | Where |
|---|---|---|
| C-a | "An individual act overrides the rule-derived result" vs "a placement is a floor; only an exception overrides" | §51.3 step 4 vs R-2 / D-116 — **H-6** |
| C-b | "A new act places the person" after re-admission vs "rule-derived belonging re-applies" | §50.11 scenario 10 vs R-6 — **H-5** |
| C-c | "Geography reconciles two references — nothing changes for belonging" vs two allocations now matching one canonical area | §51.6 scenario 12 vs A-1, Geography §12 — **H-3** |
| C-d | "The change-set check finds an allocation to a closed unit" vs the check covering S-0…S-7 only and A-2 being derived | Scenario 19 vs §49.7 step 4 — **M-6** |
| C-e | A second recorded association is "flagged, interim deepest common unit" vs "a second is refused" | Scenario 23 vs B-8/R-5 — **L-9** |
| C-f | "A unit stays in its tree for life" (S-6) vs "S-6 withdrawn" | §49.4 vs §50.1 R-1 — **H-6** |
| C-g | "Awaiting placement" = unresolved (business description) vs "every member whose belonging changed by a closure" | §51.1 / prompt §2 vs R-3 — **M-5** |

**L-9 (stated here to keep the numbering): scenario 23 versus B-8.** If a second association is refused (§51.11 R-5), scenario 23's ambiguity can only arise from records that predate the rule or from an import — a migration case the design does not name. **Fact**; impact **low**; correction: label scenario 23 "historical/imported data".

### 4.2 Missing rules

1. The derived result for **two active placements** (H-1).
2. **Authority ordering** for later allocations and for granting exceptions (H-2).
3. **Canonical-identity conflict** after reconciliation, and the review signal for it (H-3).
4. **Root existence and root closure** (H-4).
5. **Re-admission** (H-5).
6. The **name of the containment scheme** and the review signal for re-parenting (M-4).
7. Whether **structure, belonging and allocation schemes** share one barrier (M-3).
8. Whether the default root **follows a new top unit** (L-5).

### 4.3 Unsupported assumptions

- That every organisation has a root unit on every date it has effective members (H-4).
- That the containment used is a single tree in one scheme (true per scheme; the scheme is unnamed — M-4).
- That any later act is authoritative enough to end an earlier allocation or exception (H-2).
- That Organisation's three record families close to the same τ (M-3).
- That a reconciliation never changes a derived belonging (H-3).
- That the structural change-set check detects allocation invalidity (M-6).
- That "awaiting placement" is only for unresolved members (M-5).
- That a placement is always a floor, although the normative derivation says otherwise (H-6).

---

## 5. Recommended corrections

Proposed wording where useful; none of these changes a ratified document or a decision.

**High.**

1. **H-1.** In B-3, add a platform default and a derivation rule:
   *"Where the organisation's rules impose no limit, a member may hold more than one geographic member belonging; the effective belonging is the **deepest active placement** on the date, and any other active placements are reported. Where the Principal adopts one-place as a platform default, a second placement for the same capacity and date is refused."* — and record the Principal's answer to Q1's "exactly one".
2. **H-2.** In §51.5 and R-4: *"A decision of a lower authority never ends or supersedes an allocation or exception of a higher authority; a later decision by an equal or higher authority ends it only where it expressly addresses it. An exception may be granted only by an authority at least equal to the authority of the allocation rule it overrides, unless the organisation's rules provide otherwise."* Resolve D-115's "prevails" for the order question.
3. **H-3.** Restate A-1: *"Within one scope, at most one destination per **canonical area** (as resolved at τ) per date"*; add: *"A reconciliation that makes two allocated references one canonical area raises `AllocationReviewNeeded` for the scope; until decided, the member is flagged and keeps the destination of the reference they were recorded against."* Correct scenario 12.
4. **H-4.** Add to B-7: *"B-7 holds from the later of the coverage start and the establishment of the tree's root; an organisation cannot close its root while any effective membership coverage exists (Principal decision), or the state 'no tree in use' is defined and no unit-scoped list is produced."*
5. **H-5.** Replace scenario 10's outcome with R-6: *"rule-derived belonging re-applies from re-admission; explicit placements and exceptions do not revive; a new act is needed only for an explicit placement."*
6. **H-6.** Rewrite §51.3 step 4 to: *"A **placement** is a floor: descent continues from it. An **exception** fixes the unit and overrides the rules while it stands."* Apply R-3 to C-2, R-1 to A-7, and mark S-6 withdrawn in §49.4.

**Medium.**

7. **M-1.** Add to A-3: *"A shallower allocation may target a descendant of a deeper scope only where the deciding authority is the highest committee (D-115); otherwise A-2 forbids it. A deeper scope's rule applies only while the member's current unit is that scope."*
8. **M-2.** State whether a cross-tree continuity move is one consistency boundary (both tree versions) or is decomposed into end + establish; add the level-fit and allocation-descendant checks to the change set.
9. **M-3.** Name Organisation's knowledge barriers and require each to be closed to τ before answering; state that one operation uses one τ across all of them.
10. **M-4.** Name the containment scheme used (the designated administrative scheme) and raise `AllocationReviewNeeded` when a containment change could alter a rule-derived result.
11. **M-5.** Define the flag: *"awaiting placement means that no rule and no act currently determines the member's unit"* — or, if the broader meaning is intended, rename it (for example "placement affected by closure").
12. **M-6.** State that the closure impact is a **report** produced from the derived result after the change set, with its authority and recipient; do not describe it as a change-set check.
13. **M-7.** Rename: **allocation rule** (§51), **placement** (D-79 individual act), **change-set placement** (D-16…D-18); and say which D-114's "allocation in the creating decision" denotes.
14. **M-8.** Restate B-5 as: *"A belonging is recorded against the structure as known at its recording; if the unit is later closed, C-2 resolves it — the record is never rewritten."*

**Low.** Correct the G-4 citation (L-1); reconcile "at d" with "at τ" in one phrase (L-2); label D-115 as a working direction where applied (L-3); name the tree in §51.3 step 1 (L-4); state whether the default root follows a new top unit (L-5); record S-0's provisional status as a dependency of B-7 (L-6); disambiguate the R- and A- label series (L-7); rewrite B-1's residence wording (L-8); label scenario 23 as historical data (L-9).

---

## 6. Verdict

**READY AFTER CORRECTIONS.**

The strategic and aggregate foundations are sound: the five facts are kept apart, belonging is derived from dated records, closure never rewrites a person's history, and the placement/exception distinction correctly reconciles D-116 with D-117. I found no ownership violation and no contradiction with a ratified decision or the accepted baseline.

What stands between this and a design the Principal can accept is a bounded set of rule-level corrections, all inside the chosen model: the multiplicity of placements (H-1), authority ordering (H-2), canonical-identity conflict (H-3), root existence (H-4), re-admission (H-5), and the fact that the author's own corrections have not reached the normative text (H-6). The medium findings are wording, boundary and signal issues. I would expect a revised §51 to be ready for Principal tactical-design acceptance after H-1…H-6 and M-1…M-5 are addressed, with the Principal answering the questions in H-1, H-2, H-4, M-1 and M-5.

This is **not** an acceptance, a ratification or an authorisation to implement. It decides nothing for the Principal.

---

## 7. Reviewer identity, prior involvement, integrity method — and the limits of this review

**Who or what I am.** I am a model of the **DeepSeek family**, designation **`deepseek-chat`**, running inside the **Claude Code** command-line harness (an Anthropic product, with its system instructions and tooling). I am **not** a Claude model. I state the harness because it bears on independence: my tools and file access are Anthropic's, though the analysis and opinions here are mine. I had no access to any Claude session's summary or assessment of §49–§51, and I was sent none.

**Prior involvement.** With **this** design (§49–§51): none. I had not seen it, the commission, the entry assessment or the decision record before this review. **Disclosed:** earlier on the same day I performed the independent review of the **Organisation people register** (IR-OPR-01), a different design in the same bounded context; the report is in my context but I have not used it as evidence here, and where this review touches the same sources (D-101, D-103, D-104) I have read them again from the bundle. I also reviewed §49–§51's self-review (§51.11) only as text, not as authority.

**Integrity check, performed by me.**
1. In `~/ir-otd-01-review-handover` I ran `sha256sum -c SHA256SUMS`: all four files OK.
2. In the bundle folder I ran `sha256sum -c MANIFEST.sha256` myself: **10 of 10 files OK**.
3. The subject's computed SHA-256 is `8b7a6b3ee981699eaf582755a0176b8b138f98516b7f013b1d54ca5a157edab1`, exactly the required value. **No stop was triggered.**
4. I modified no supplied document and ran no code from the bundle.

**Labels.** *Fact* — verifiable in the bundle. *Inference* — my conclusion from the bundle, which another reader could contest. *Recommendation* — advice the Principal may reject. Where a source is not in the bundle I mark it **UNAVAILABLE** and do not guess. **UNAVAILABLE:** the Identity discoveries beyond the act (not needed here); any organisation's actual written rules (so every "the rules decide" is UNKNOWN per organisation, as the design itself says); Geography's Q-2 schemes; the code.

**What this review does not establish.**
- No verification against code or runtime behaviour.
- No reading of any organisation's written rules; every statement about what a rule permits is UNKNOWN per organisation.
- No assessment of performance or scale of the derived descent (the design's [TU]).
- No decision on any open question (S-0, V-17 exceptions, the root-existence question, the flag's meaning).
- No ratification, no acceptance, no authorisation.
- The author's self-review is not treated as independent evidence; where I agree with R-2 and R-6 I give my own reasons.

**End of report.**
