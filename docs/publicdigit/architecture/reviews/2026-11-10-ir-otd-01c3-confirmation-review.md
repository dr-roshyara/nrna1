# IR-OTD-01-C3 — Narrow Confirmation Review

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51 (third revision) — SHA-256 `fa68a5897bc3c5cfe3fdeed57b826ded072a226b171ca71c7157d7c025a17dbe` |
| **Review being confirmed** | `docs/publicdigit/architecture/reviews/2026-11-10-ir-otd-01c2-confirmation-review.md` — SHA-256 `6ee57c68ffc7670f0e2dd5233b2e7662c18c67e9d7ded38dfbd3a0d919899bf2` (IR-OTD-01-C2, verdict NOT CONFIRMED: 15 of 17) |
| **Decisions** | D-114 … D-122 (decision record §11.43–§11.47) and **D-136** (§11.49); D-123 … D-135 (§11.48) are other decisions and are not relied on |
| **Reviewer** | `deepseek-chat`, DeepSeek family, in the Claude Code harness — **not a Claude model**; author of IR-OTD-01, -C and -C2 (independence limit disclosed in §5) |
| **Integrity** | Handover `SHA256SUMS` 4/4 OK; bundle `MANIFEST.sha256` 4/4 OK; subject, decision record and C2 report hash to the required values. I modified nothing |
| **Status** | Confirmation only. No new architecture review; no acceptance; nothing decided for the Principal |

**What I checked, and by what rule.** The commission is narrow: the two C2 residuals (nested-area authority; the containment-slot citation) and the Principal's new decision **D-136**, implemented in the **normative text**, consistently with D-114 … D-122 and D-136, with no new contradiction. I treated the invariant tables (§49.4, §50.6, §51.4), §50.15, the descent algorithm (§51.3), the lifecycle tables (§50.5, §51.5) and the scenarios as normative, and §51.11–§51.14 and the "Update" paragraphs as history; a correction found only in history would count as MISSING. I read D-119 and D-136 verbatim in the decision record, not in summaries. I re-checked the six items this round did not intend to change, for regressions.

---

## 1. Checklist: verdict per item

| # | Item | Verdict | Where I found it |
|---|---|---|---|
| 1 | C2 item 2 / D-136 — A-7: authority first, whatever the order; contained areas; written-rules competence with an identifiable provision recorded as basis; unknown competence refused and reported; specificity only among equal (or expressly authorised) allocations; no authority from names, levels or containment | **CONFIRMED** | §51.4 **A-7**, line 1419 |
| 2 | C2 item 2 / D-136 — the Adopt row refuses a lower body's allocation for the same or a contained area while a higher body's stands, with the same exception | **CONFIRMED** | §51.5 **Adopt allocation**, line 1434 |
| 3 | C2 item 17 / L-1 — A-7 cites the containment slot `(CONTAINMENT, unit, scheme)`, not G-4; designated administrative scheme; "valid at d, as known at τ"; no dangling A-7 reference | **CONFIRMED** | §51.4 **A-7**, line 1419; **A-1** cites it, line 1417; every other A-7 reference is in §51.11–§51.14 (1541, 1551, 1585, 1624, 1626), i.e. history |
| 4 | C2 precision notes — step 4: later decision for that member by an authority at least equal to the grantor; equal or higher expressly addressing it; a later general rule does not end it unless it says so; D-119's written-rules clause | **CONFIRMED** | §51.3 **step 4**, line 1405 |
| 5 | Scenarios 25–31: removal refused; lower placement does not end an exception; same area refused; contained area refused; written-rules authorisation takes effect; rules not on file refused; higher body later prevails from its date | **CONFIRMED** | §51.6 **scenarios 25–31**, lines 1476–1482 |
| 6 | Status hygiene — D-115 not in the "Binding" list; Q-2 marked decided | **CONFIRMED** | §51 status block, line 1367 (and the Binding list at 1362–1366 excludes it); §51.9 **Q-2**, line 1511 |
| 7 | No new contradiction between A-7 and A-1, A-2, A-3, step 2, C-2, the closure rows, and D-114 … D-122, D-136; no reference points to the wrong D-123 | **PARTIAL** | A-1 line 1417 still states specificity unqualified — see §2; numbering check clean — see §3 |

**Tally: 6 CONFIRMED, 1 PARTIAL, 0 MISSING.**

Items 1–6 are in the normative text at the lines given, not only in §51.14 or the Update paragraphs. Specifically: A-7 is a real §51.4 row (1419), so A-1's "(A-7, …)" no longer dangles; the Adopt row carries the refusal and the identifiable-provision exception (1434); step 4 carries both D-116's grantor comparison and D-119's "unless the written rules provide otherwise" (1405); all seven scenarios are present with the expected outcomes (1476–1482); D-115 is under "Not binding" (1367) and Q-2 is annotated "(Decided: D-117, option (a).)" (1511).

---

## 2. The one finding: A-1 still states specificity without the authority step

**C-1 (new, material in effect, one clause to fix).**

A-1, line 1417, still reads:

> within one scope **the most specific matching area wins** (A-7, by containment in the designated administrative scheme as known at τ).

A-7, line 1419, now reads:

> **(1) authority first:** an allocation made by a **higher** body governs every area its allocated area contains, **whatever their order**; a lower body's allocation for a contained area **does not take effect**, unless … **(2) Then specificity:** among allocations of **equal** authority …

For the exact case D-136 decides — scenario 28 (line 1479): highest committee `D4 → D2`, Y's committee `W (inside D4) → D3` — A-7 gives **D2** (the lower allocation is refused), while A-1 read on its own gives **D3**, because W is the more specific area. The parenthetical "(A-7, …)" names the operative rule but does not qualify A-1's sentence; A-1 is a normative invariant row, and a reader or implementer applying A-1 alone reaches the result D-136 rejects. That is a contradiction in the normative text, not merely an incomplete summary: the two rows state different rules for the same case.

**What would remove it** (textual, not an architectural choice): make A-1 defer to A-7, e.g. "within one scope **A-7 governs which matching allocation wins** (by containment …)", or "the most specific matching area wins **among allocations of equal authority (or expressly authorised under A-7)**". Either keeps A-1's own content — one destination per area per date, canonical identity at τ, `AllocationReviewNeeded` on a merging reconciliation — and stops it from restating A-7's second step as if it were the whole rule.

**Checked and found consistent — no finding:**
- **A-3 (1420) versus A-7.** A-3's "a higher body overrides them only by deciding within that scope" is about a **deeper scope's** rule; A-7 is expressly "**choosing among matching allocations in one scope**". The two operate on different axes (organisational scope versus authority within a scope) and do not give different answers. Scenario 31 (1482) confirms the within-scope, whatever-the-order case.
- **A-2 (1418), step 2 (1403), C-2 (1425), closure row (1441).** A-2 now says "active direct child" (the C-7 wording defect is gone), and its lapse-on-closure rule composes with C-2's re-derivation; step 2 and A-7 use the same "valid at d, as known at τ" containment; C-2 and the closure row match D-121 (rule-derived members get `PlacementReviewNeeded`, only the wholly unplaced are "awaiting placement").
- **Higher-body-overrides row (1438).** Its "on the same scope" matches A-7's "in one scope"; no narrowing.
- **Adopt row (1434) versus A-7.** Same-area and containing-area refusals, same identifiable-provision exception, same reporting. Consistent.

**Notes, not findings** (stated so the Principal sees them; neither contradicts D-136 and neither changes the verdict):
- **Scenario 31's "Y's W allocation stops having effect" (1482) versus the Adopt row's "Refused" (1434).** The two describe different histories: a lower allocation adopted **before** the higher one is suspended while the higher stands; one adopted **after** is refused outright. The text does not say whether the suspended allocation resumes if the higher one later lapses (A-2 closure, line 1418). That is an unstated interaction, not a contradiction; it would be worth one sentence when the text is next touched.
- **§51.2's electoral-division note (1391)** says the only shared discipline with allocation is D-79/D-80 "(no address alone)". D-125/D-126 also exclude an **organisation area alone**. The note is a pointer, not a restatement, so I do not treat the omission as a contradiction; it is a low wording point.
- **R-1 (1541, history)** still describes A-7 as "the most specific matching area wins" without the authority step. §51.11 is history and is not evidence, so it is not a finding; the sentence will read stale beside the corrected A-7.

---

## 3. Numbering check (D-123 / D-136)

**Clean.** The subject contains **no reference to D-123**. D-123 … D-135 are recorded as other decisions in §11.48 (decision record line 914); D-136 is §11.49 (lines 934–938). In the subject, `D-136` appears at A-7 (1419), the Adopt row (1434), scenarios 28 and 30 (1479, 1481) and the §51.14 update (1626) — all in the nested-area sense, none pointing at the parallel session's decisions. `D-125`/`D-126` appear only in the §51.2 electoral-division note (1391). No wrong-D-123 reference exists to correct.

---

## 4. Verdict

**NOT CONFIRMED.**

The purpose of the round is met in substance: D-136 is in the normative text at A-7 (1419) and the Adopt row (1434), the containment-slot citation is in A-7, the C2 residuals are closed, scenarios 25–31 exercise all seven required cases, and the numbering is clean. Six of the seven items are CONFIRMED with no regression in the previously confirmed material.

One residual remains, and it is a contradiction rather than a missing rule: **A-1 (1417) still states the specificity rule without A-7's authority-first step**, and it is the sentence an implementer of A-1 would follow in the scenario D-136 was decided on. It is a one-clause correction to A-1 (the two possible wordings are in §2); no other normative sentence needs to change for this item. Until A-1 defers to A-7, the text gives two answers for scenario 28 and I cannot confirm that this round introduced no new contradiction.

I decide nothing for the Principal: whether to make that correction and then accept the design is the Principal's decision. A re-confirmation after the edit would be narrow (A-1 alone).

---

## 5. Reviewer identity, prior involvement, integrity — and the limits of this review

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, running in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model. The harness is Anthropic's; the analysis is mine.

**Prior involvement.** I wrote **IR-OTD-01** (the independent review), **IR-OTD-01-C** (first confirmation) and **IR-OTD-01-C2** (second confirmation). I also reviewed the Organisation people register (IR-OPR-01) earlier the same day; that is a different design and I have not used it as evidence here.

**The independence limit, stated plainly.** The C3 commission requires a reviewer who is **not Claude and not the DeepSeek model that wrote the three earlier reviews** — that is, not me. I am that model, and this is the third consecutive self-confirmation. The Principal instructed me to proceed and to work as a senior DDD architect, and I have; but the commission's independence requirement is **not met**, and a reader should weigh the confirmation accordingly. I mitigated the risk by reading the normative text and D-119/D-136 afresh, by treating §51.11–§51.14 as history rather than evidence, by re-checking the items this round did not intend to change, and by reporting the one item that is **not** confirmed. That mitigation does not remove the limit. A different reviewer remains preferable before acceptance; that is an observation about this review's weight, not a decision for the Principal.

**Integrity check, performed by me.**
1. In `~/ir-otd-01c3-review-handover` I ran `sha256sum -c SHA256SUMS`: **4/4 OK**.
2. In `ir-otd-01c3-review-bundle` I ran `sha256sum -c MANIFEST.sha256`: **4/4 OK**.
3. The subject's computed SHA-256 is `fa68a5897bc3c5cfe3fdeed57b826ded072a226b171ca71c7157d7c025a17dbe` and the decision record's is `91536d017c9a5f2b4293341d08a54ee33ce3796c0e6e7a5daadea82d52f4a874`, exactly the required values. **No stop.**
4. The bundle's copy of the C2 report hashes to `6ee57c68…`, the report I delivered — the review being confirmed is that version.
5. I modified no supplied document, ran no code from the bundle, and changed neither the subject nor the decision record. After writing this report I re-hashed the subject; it is unchanged.

**Labels.** *Confirmed* — the rule is in the normative text at the line given. *Partial* — part of the item is present and part is missing or contradicted at the line given. *Missing* — absent from the normative text; I found none. Anything not in the bundle is **UNAVAILABLE**; I needed nothing beyond it.

**What this review does not establish.**
- No re-review of the architecture and no new findings outside the narrow scope, except C-1.
- No verification against code or runtime.
- No reading of any organisation's written rules; A-7's competence exception is confirmed as a rule, not tested against a real text.
- No acceptance, ratification or authorisation; no decision for the Principal.
- The author's self-review (§51.11) is not treated as independent evidence.

**End of report.**
