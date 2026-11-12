# IR-OTD-01-C2 — Second Confirmation Review

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51 as corrected — SHA-256 `d60af0a2fd66a4d913d9368c18c82e37a9a4208a2db4706819917b67e1288132` |
| **Decisions** | D-114 … D-122, decision record §11.43–§11.47 — SHA-256 `1405e12ff518f061767bae6494dc951855285eea77d0f9a5e8706d7b348dfb65` |
| **Reviews being confirmed** | IR-OTD-01 — SHA-256 `0db4623fc54c4fbfdaf92bab71b96a5d6b50aa298a3f72b8a3af2e96ec8801a8`; IR-OTD-01-C — SHA-256 `2ae7a2a0bf46984ec650d5109e0f5101959a02fbca0840babd1887b77a3a53d1` |
| **Reviewer** | `deepseek-chat`, DeepSeek family, running in the Claude Code harness — **not a Claude model** |
| **Integrity** | Outer `SHA256SUMS` 4/4 OK; bundle `MANIFEST.sha256` 5/5 OK; subject and decision-record hashes match the commission; both earlier reports match the bundle copies. I modified nothing |
| **Status** | Confirmation only. No new architecture review; no acceptance; nothing decided for the Principal |

**What I checked.** I read the commission first, then the normative text itself: the invariant tables (§49.4, §50.6, §51.4), §50.15, the descent algorithm (§51.3), the lifecycle tables (§50.5, §51.5) and the scenarios (§49.10, §50.11, §50.15, §51.6). I read D-114 … D-122 verbatim in the decision record. I used §51.11–§51.14 and the "Update" paragraphs only to learn what was supposed to have been corrected; I did not count them as evidence. Where a correction appeared only there, I record it as **MISSING** under the commission's rule.

**Bottom line.** Fifteen of the seventeen items are confirmed in the normative text. Two residuals remain: one **PARTIAL** (item 2: the nested-area case of D-119's authority order) and one **PARTIAL** that contains a **MISSING** correction (item 17: L-1, the containment-slot citation). No item is wholly absent, but I cannot confirm the design while these two remain.

---

## 1. Checklist: verdict per item

| # | Item | Verdict | Where I found it |
|---|---|---|---|
| 1 | H-1, D-118, D-122 — one geographic member belonging by default; second refused unless the rules allow | **CONFIRMED** | §50.6 **B-3**, line 1213; §50.15, line 1342 ("How many … remains the organisation's rule"); D-122(1) in the decision record |
| 2 | H-2, D-119 — lower body cannot undo a higher one, whatever the order; exception grant and termination by equal or higher authority | **PARTIAL** | §51.5 **Adopt** 1431, **Change** 1432, **Remove** 1433, **Higher body overrides** 1435; §51.3 **step 4** 1403; §51.6 **scenarios 25–26** 1473–1474 — see §2 |
| 3 | D-119 written-rules clause | **CONFIRMED** | §51.4 **A-3**, line 1417 ("Unless the organisation's written rules provide otherwise") |
| 4 | D-119 direct child — A-2 and A-3 consistent | **CONFIRMED** | A-2 line 1416 ("active **direct child**"); A-3 line 1417 ("only a direct child") |
| 5 | H-3 — A-1 over canonical identities at τ; merge of allocated areas; interim destination | **CONFIRMED** | A-1 line 1415; §51.6 **scenario 12**, line 1460 |
| 6 | D-120 — S-9 with dissolution carve-out and C2 read; §49.7 step 4 range; B-7 rests on S-9 | **CONFIRMED** | §49.4 **S-9**, line 935; §49.7 **step 4**, line 992 ("S-0 … S-5, S-7 and S-9"); B-7 line 1336 |
| 7 | D-122 / C-1 — B-7 default "root as at admission"; step 1 the same; S-9 "no member moved"; scenarios 27–28 | **CONFIRMED** | B-7 line 1339; §51.3 **step 1**, line 1400; S-9 line 935; §50.15 **scenarios 27–28**, lines 1356–1357 |
| 8 | H-5 — re-admission: rule-derived re-applies; explicit placements and exceptions do not revive | **CONFIRMED** | §50.11 **scenario 10**, line 1274 |
| 9 | H-6 — placement is a floor, only an exception fixes; S-6 withdrawn; scenario 13 no longer cites S-6 | **CONFIRMED** | §51.3 **step 4**, line 1403; §49.4 S-6, line 936; §49.10 **scenario 13**, line 1071 |
| 10 | M-2 — cross-tree continuity: one change set, one transaction; scheme moves or lapses | **CONFIRMED** | §50.1 **R-1**, line 1125 |
| 11 | M-3 — one Organisation knowledge boundary | **CONFIRMED** | §49.8 **(0)**, line 1018; §50.8, line 1243 |
| 12 | M-4, item 10 — designated scheme; re-parenting that could alter a rule-derived result raises AllocationReviewNeeded in §49.6 **and** §51.5 | **CONFIRMED** | §49.6, line 980; §51.5 **Geography row**, line 1436 |
| 13 | D-121, D-122 — "awaiting placement" only if unplaced; `PlacementReviewNeeded` otherwise | **CONFIRMED** | §51.1, line 1378; **C-2**, line 1422; §51.5 **Close unit**, line 1438; §50.5 **Fallback**, line 1196; §50.11 **scenario 5**, line 1269; §51.6 **scenario 15**, line 1463 |
| 14 | M-6 — closure impact is a report, not a write-time check | **CONFIRMED** | §51.5 **Close unit**, line 1438; §51.6 **scenario 19**, line 1467 |
| 15 | M-7 — "allocation rule" versus "placement" and "change-set placement" consistent | **CONFIRMED** | §50.5, lines 1192 and 1195; §50.15, line 1341; §51.2, line 1387; §51.3 step 4, line 1403. Note: scenario 20's verb "allocates 300 members" is ambiguous, but the M-7 glossary resolves it and no rule depends on the verb |
| 16 | M-8 — B-5 a record-time assertion | **CONFIRMED** | §50.6 **B-5**, line 1215 |
| 17 | L-1 … L-9 — containment-slot citation; "valid at d, as known at τ" in step 2; D-115 not binding; tree in step 1; B-1 residence wording; scenario 23 historical; §51.9 Q-1 marked decided | **PARTIAL — L-1 MISSING** | L-2 step 2, line 1401; L-3 status block, line 1364; L-4 step 1, line 1400; L-5 B-7 1339 and scenarios 27–28 1356–1357; L-6 line 1336; L-8 B-1 1211; L-9 scenario 23 1471; Q-1 marked decided, line 1494. **L-1 appears only at §51.11 R-1, line 1533, and §51.14 L-1, line 1596; there is no A-7 row in §51.4** — see §3 |

**Tally: 15 CONFIRMED, 2 PARTIAL, 0 wholly missing.** Within item 17, the L-1 correction is **MISSING** from the normative text under the commission's rule.

---

## 2. Item 2 — the nested-area case is not resolved

The four lifecycle rows and step 4 are present, and the core of D-119 is in them:

- **Adopt** (line 1431) refuses a new allocation "where a higher body's allocation for the **same area** in the same scope stands";
- **Change** (line 1432) and **Remove** (line 1433) refuse a lower body, "whatever the order";
- **Higher body overrides** (line 1435) says the higher body's decision "prevails from its date and **continues to prevail over any later decision of a lower body on the same scope** (D-119); Y's allocation ends where they conflict";
- **step 4** (line 1403) grants an exception only "by the authority of the overridden rule or higher" and ends it only by an act, a later exception or placement "made by an authority at least equal to the one that granted it", a later decision of an equal or higher authority expressly addressing it, or closure — and "**A lower body's later act never ends it**";
- **scenarios 25–26** (lines 1473–1474) confirm removal and exception termination by a lower body are refused.

The residual is a case the rows do not settle. Suppose scope Y has districts D1 … D4, and ward W is inside geographical D4. The **highest committee** allocates, in Y's scope, `D4 → D2` (D2 is a direct child of Y). Later, **Y's committee** allocates, in the same scope, `W → D3` (D3 is also a direct child of Y). A member recorded in W matches both areas.

- **A-7** (stated in A-1, line 1415, and defined only in §51.11 R-1, line 1533) says "within one scope, **the most specific matching area wins**". W is smaller than D4, so the member goes to **D3**.
- **D-119** says "A higher body's decision prevails over a lower body's **on the same scope**, whatever their order; a lower body's later act cannot undo it." For W members, the lower body's later rule changes the result from D2 to D3 — it undoes part of the higher body's decision.
- The **Adopt** row does not refuse this: W is not "the same area" as D4.
- The **Higher body overrides** row might cover it ("Y's allocation ends where they conflict"), but "conflict" is undefined, and A-7 is unqualified. The normative text does not say whether authority or specificity governs when a lower body's area is **contained in** a higher body's allocated area.

This is not the M-1 proposal, which the Principal did not adopt; it is D-119 applied to a case the lifecycle leaves open. The commission's item 2 asks that the rows "refuse lower-body acts whatever the order"; for a nested area, **Adopt does not**. The design needs one sentence, in A-7, the Adopt row or the Higher-body-overrides row, choosing one of: specificity applies only among allocations of the same authority; or a lower body's allocation for an area contained in a higher body's standing allocation is refused; or "conflict" is defined to include overlap. I do not choose among them.

**Severity:** material, because it changes a member's unit in an ordinary nested-area configuration the design itself uses (R-1's example is `D2 → D2` and `W (inside D2) → D3`). It is a text gap, not a new architecture proposal.

---

## 3. Item 17 / L-1 — the containment-slot citation is only in the history

The commission's §1 is explicit: the normative text is the invariant tables, §50.15, the descent algorithm, the lifecycle tables and the scenarios; **§51.11–§51.14 are history and summaries**, and a correction found only there counts as **MISSING**.

I searched the subject for the containment-slot citation and for G-4:

- `CONTAINMENT` and `G-4` appear **only** at line 1533 (§51.11 **R-1**, "the containment slot `(CONTAINMENT, unit, scheme)`; corrected per IR-OTD-01 L-1, not G-4") and line 1596 (§51.14 **L-1**).
- §51.4 **A-1** (line 1415) states the specificity rule and says "by containment in the designated administrative scheme as known at τ". It does **not** cite the containment slot.
- There is **no A-7 row in §51.4**. A-1 refers to "(A-7, …)", and the only definition of A-7 is §51.11 R-1, which the commission classifies as history.

So the rule's substance is in A-1, but the L-1 correction — that single-parenthood comes from the containment slot, not G-4 — is only in the non-normative sections. Under the commission's rule it is **MISSING**. The earlier C review treated R-1 as normative; the C2 commission places §51.11 in history, so that treatment was wrong.

**Minimal fix:** add A-7 to the §51.4 invariant table (with the containment-slot citation), or state the slot citation in A-1 and remove the dangling A-7 reference. This is the existing correction placed in the normative table, not a new rule.

---

## 4. "D-116 read with D-119" — is the reading faithful?

**Yes in substance.** The design's step 4 does three things that track the two decisions:

1. **Grant:** an exception must be "granted by the authority of the overridden rule or higher" — this is D-119's "An exception may be granted only by the authority of the rule it overrides, or a higher one."
2. **Termination:** it ends by an act, a later exception or placement for that member made by an authority at least equal to the one that granted it, a later decision of an equal or higher authority expressly addressing it, or closure of its destination. D-116 lists the same four routes without an authority qualifier; **adding the qualifier is exactly D-119's "a lower body's later act cannot undo it"**, applied to an exception granted by a higher body. The final sentence ("A lower body's later act never ends it") states the result.
3. **Floor versus fixed unit:** an ordinary placement is a floor; only an exception fixes the unit while it stands — D-116's two sentences.

Two precision notes, neither a contradiction on the stated reading:

- **The anchor of (b).** "A later decision of an equal or higher authority expressly addressing it" does not say equal or higher **than what**. Read with (a) and the final sentence, it is the granting authority; read alone, it could be the overridden rule's authority, which may be lower than the grantor. The final sentence already forbids a lower body, so the substance is consistent, but the anchor should be explicit ("equal or higher than the authority that granted the exception").
- **The written-rules caveat.** D-119 ends "The organisation's written rules may provide otherwise." A-3 carries it for allocations; **step 4 does not carry it for exceptions** and says "never ends it" absolutely. If the caveat reaches exceptions, step 4 needs the same clause; if it does not, the decision's scope should be stated. This is the same caveat noted under item 3, and it is the only place where the design's reading may be narrower than D-119 as written.

A smaller wording point: D-116 says "a later decision for that member", while step 4 says "a later exception or placement". A ratification or a change-set placement is a decision for that member (BelongingBasis lists both). If such a decision can end an exception, step 4 should use D-116's phrase with the authority qualifier. On the design's own ontology, a change-set placement is a placement; a ratification is less clear.

---

## 5. Other observations (not defects)

- **§51.9 Q-2 is stale.** It still presents the choice as open and recommends "(a)" (lines 1502–1506), while §51.13's Update records Q-2 as decided by D-117. The recommendation matches D-117, so there is no substantive contradiction; it is the same staleness that Q-1 had, and Q-1 is now marked "(Decided: D-116)" at line 1494. Low.
- **R-4's general-rule clarifier is only in §51.11.** "A later general rule does not end it unless it says so" (R-4, line 1536) is in the history. Step 4's "only" and "expressly addressing it" carry it implicitly. It is not a correction from the earlier reviews, so I do not count it as MISSING under the commission's rule, but if the Principal wants it normative, step 4 should say it.
- **§50.15 scenario 20's verb.** "Its creating decision allocates 300 members" is ambiguous under M-7; the glossary (§51.14 M-7) resolves it, and no rule depends on the verb. No change needed.
- **Status-block formatting.** "*(not binding)* D-115 …" sits as a sub-bullet inside the "**Binding:**" list (line 1364). The substance is correct — D-115 is a working direction — but the list invites misreading. Low.
- **§49.6 line 985** ("Reconciliation never changes committee structure, coverage decisions or belonging") is consistent with A-1 and scenario 12: reconciliation changes how an identity is read; the interim rule keeps the destination of the reference the member was recorded against. No contradiction.
- **B-7, step 1 and scenarios 27–28** are mutually consistent: the default is the root **as at the member's admission**, carried forward by unit identity; a new root above the old one moves no member without a decision. The C-1 residue from IR-OTD-01-C is gone.

---

## 6. Verdict

**NOT CONFIRMED.**

Fifteen of the seventeen items are CONFIRMED in the normative text. Two residuals remain:

1. **Item 2 (PARTIAL):** the nested-area authority case. A lower body's allocation for an area contained in a higher body's standing allocation in the same scope is not refused by the Adopt row, and A-7's unqualified "most specific wins" gives it effect against D-119's "higher body prevails on the same scope". One sentence is needed to say which governs.
2. **Item 17 (PARTIAL; L-1 MISSING):** the containment-slot citation appears only in §51.11 R-1 and §51.14 L-1, both history; there is no A-7 row in §51.4. Under the commission's rule this counts as MISSING. Add A-7 to the invariant table (or state the slot citation in A-1 and remove the dangling reference).

With those two text corrections, the design would implement D-114 … D-122 and the findings of both earlier reviews without a remaining contradiction I can see within the commission's scope. I express no view beyond this list, and I make no decision for the Principal.

---

## 7. Reviewer identity, prior involvement, integrity method — and the limits of this review

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, running in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model. The harness is Anthropic's; the analysis is mine.

**Prior involvement and the independence limit.** I wrote **both** reviews being confirmed: IR-OTD-01 and IR-OTD-01-C. The commission asked for a fresh reviewer, **not Claude and preferably not the DeepSeek model that wrote both earlier reviews**, to reduce self-confirmation risk. The Principal instructed me to proceed with this package, so this is a **self-confirmation review with a disclosed independence limit**. I mitigated it by reading the normative text and the decision record afresh, by not treating §51.11–§51.14 or any Update as evidence until I had found the rule in the normative text, and by reporting residuals that cut against my own earlier work: the nested-area case is not in either earlier report, and the L-1 placement error was in my own IR-OTD-01-C, which treated R-1 as normative when the C2 commission places §51.11 in history. I have also reviewed the Organisation people register (IR-OPR-01) earlier the same day, a different design; I did not use it as evidence here.

**Integrity check, performed by me.**
1. In `~/ir-otd-01c2-review-handover` I ran `sha256sum -c SHA256SUMS`: **4/4 OK**.
2. In `ir-otd-01c2-review-bundle` I ran `sha256sum -c MANIFEST.sha256`: **5/5 OK**.
3. The subject's computed SHA-256 is `d60af0a2fd66a4d913d9368c18c82e37a9a4208a2db4706819917b67e1288132`, exactly the commission's value. **No stop.**
4. The decision record hashes to `1405e12f…`, also the commission's value.
5. The bundle's IR-OTD-01 report hashes to `0db4623f…` and the IR-OTD-01-C report to `2ae7a2a0…`; both are the reports I delivered, not later versions. I also verified that the repository copies at `docs/publicdigit/architecture/reviews/` have the same two hashes.
6. I modified no supplied document, ran no code from the bundle, and changed no file in the package.

**Labels.** *Confirmed* — the rule or correction is in the normative text at the line given. *Partial* — part is present, part is missing or not resolved. *Missing* — absent from the normative text; under the commission's rule, present only in §51.11–§51.14 or an Update. **UNAVAILABLE:** nothing. All five bundle files were readable, and I needed no source outside the bundle for this confirmation.

**What this review does not establish.**
- No re-review of the architecture beyond the contradictions within the commission's scope.
- No verification against code, schemas, locks or runtime; no event sourcing assumed.
- No reading of any organisation's written rules.
- No acceptance, ratification or authorisation; no decision for the Principal.
- The author's self-review (§51.11) is not treated as independent evidence.

**End of report.**
