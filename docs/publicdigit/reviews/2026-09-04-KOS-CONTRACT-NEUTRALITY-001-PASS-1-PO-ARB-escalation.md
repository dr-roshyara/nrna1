# `KOS-CONTRACT-NEUTRALITY-001` — Pass-1 handoff: two open decisions for PO/ARB

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-04
**Recorded by:** performer, lane `S5-architecture-pass1-evidence-reconciliation` —
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`

> ⛔ **This is a handoff, not a decision.** It packages what Pass 1 already reconciled
> (`2026-09-04-...-PASS-1-evidence-determination.md`) into two discrete decisions for
> PO/ARB. **It recommends nothing, decides nothing, and closes no lane.** Each option below
> is stated with its consequence, not its preference. Lane `S5` remains `ACTIVE`;
> this document does not touch it.

---

## Why two decisions, and why now

Pass 1's evidence determination reached `NOT YET DETERMINABLE` — correctly, per its own
containment rule, rather than forcing a verdict. Two specific gaps are what stand between
the record and a defensible verdict, and both require an authority Pass 1 does not have.
Bundling them separately (rather than as one "finish Pass 1" ask) keeps them decidable
independently, since they turn on different evidence and different prior acts.

## Decision 1 — V-3: dynamic own-behaviour member access

**The gap.** The pinned contract requires dynamic member access (`$this->$m()`,
`call_user_func([$this,'m'])`, `$this->$p`) to be recorded `SEEN-AND-EXCLUDED`; the accepted
binding currently emits no fact for it at all (`UNSEEN`). Two non-interchangeable proposals
exist on record:
- The original determination (`99aeac7c`), self-classified by its own governing grant as
  *"evidence and proposal material only, not an authoritative architecture decision"* —
  produced by the barred Track-1 implementer, judging its own implementation.
- The independent determination (produced under a mandatory independence gate, `AMD1`),
  self-labeled *"PROPOSAL ONLY. It decides nothing."*, proposing five sub-decisions
  `D-1`–`D-5`.

The decisions-registration disposed of five *narrow scope* sub-questions but states plainly:
*"the delivered V-3 architecture determination is neither accepted nor amended here."*
Representation (`D-1`) is `OPEN`; the enumerated list (`D-4`) and vocabulary (`D-5`) are not
chosen; the artifact-update assignment remains `UNCREATED`.

**Options, each with its consequence — none recommended:**

| Option | What it does | Consequence |
|---|---|---|
| **1** | Accept the independent determination's proposals (`D-1`–`D-5`) as the architecture ruling on V-3 | Closes V-3's substantive question; unblocks Deliverable B/C's dynamic-member sub-case; requires a fresh Architecture/PO-ARB acceptance act — not a rubber stamp of the existing `PROPOSAL ONLY` document |
| **2** | Request targeted continuation — resolve only representation (`D-1`), the enumerated list (`D-4`), and vocabulary (`D-5`), which the decisions-registration already flags as the specific open items | Narrower ask than a full V-3 ruling; leaves the two-determination ranking question formally open even if the practical gaps close |
| **3** | Commission a third, freshly independent determination, reasoning that both existing ones carry disclosed conflicts (self-judgment in one, the drafter's own prior semantic proposal in the other) | Cleanest independence story; highest cost — restarts analysis rather than adjudicating what exists |
| **4** | Leave V-3 open and require any neutrality verdict to explicitly carve out dynamic member access as unresolved | No new work; a partial/conditional verdict becomes possible sooner, at the cost of leaving a pinned contract requirement permanently unmet in the current binding |

## Decision 2 — Deliverable E vs. the language-scope decision

**The gap.** The commission (registered 2026-08-16, re-registered/framing-amended
2026-08-24) still lists **Deliverable E — "Python conformance plan (only after the contract
is precise)"** as live. But `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-language-scope-registration.md` —
dated *before* the 2026-08-24 re-registration — records a standing PO/ARB decision: *"Proceed
with PHP as the sole implementation language… Java and other future language bindings are
explicitly OUT OF CURRENT IMPLEMENTATION SCOPE… neutrality is now claimed architecturally…
rather than empirically, by a second implementation agreeing."* Nothing on record reconciles
the two. Stage-2's Python collector was, separately, independently verified `FAIL` and never
repaired — but that fact is now secondary to the scope conflict: repairing it is not the
authorized path regardless of its defects.

**Options, each with its consequence — none recommended:**

| Option | What it does | Consequence |
|---|---|---|
| **1** | Retire or reword Deliverable E to match the architectural-claim framing (drop the Python-conformance-plan requirement) | Commission and standing scope decision become consistent; Deliverable F (verdict) can proceed on architectural grounds alone once V-3 and Deliverable D are addressed |
| **2** | Reopen/amend the language-scope decision to bring Python back into scope, satisfying Deliverable E as originally worded | Restores the empirical cross-language claim; requires repairing Stage-2's confirmed `FAIL` (heredoc/nowdoc/attribute defects) plus the 9 further breadth-found divergences before any Python evidence is usable |
| **3** | Leave both acts on record as an unreconciled conflict, and require any future implementation authorization to explicitly pick one framing first | No decision forced now; risks the same ambiguity resurfacing at the next commissioning of implementation work |
| **4** | Split the neutrality claim into two explicit tracks — an architectural claim now (per the 2026-08-18 decision), with an optional, separately-authorized future empirical Python track decoupled from the current commission | Makes the current commission's Deliverable F answerable without waiting on Python; defers rather than resolves the original empirical ambition |

## What this document does not do

No V-3 ruling · no acceptance of either V-3 determination · no change to Deliverable E's
wording or the language-scope decision · no reopening of `KOS-LCOM4-CONTRACT-001` · no
contract, fixture, or code change · no closure of lane `S5` (that is a Governance act on a
human decision, `G-1`, and none is taken here).

**Next actor: PO/ARB**, for both decisions independently.

**Traceability:** Pass-1 evidence determination `2026-09-04-...-PASS-1-evidence-determination.md`
· V-3 chain (`99aeac7c` determination, assignment/`AMD1`, independent determination,
decisions-registration) · language-scope decision
`2026-08-18-KOS-CONTRACT-NEUTRALITY-001-language-scope-registration.md` · commission
`2026-08-16-KOS-CONTRACT-NEUTRALITY-001-commission.md` and its 2026-08-24 re-registration ·
Stage-2 (`4d4738db` FAIL, breadth report) · `G-KOS-CONTRACT-PASS1-RECONCILE`
(+`AMD1`) · `G-3`/`G-1`.
