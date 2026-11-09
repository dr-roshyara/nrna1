# Mobile capability mapping: readiness assessment (gate not met)

| | |
|---|---|
| **Status** | **PROPOSED assessment.** It decides and authorises nothing. Not independently reviewed (Claude family). |
| **Commission** | Principal, 2026-11-09: "Mobile Capability Mapping within the Existing Strategic DDD Baseline". |
| **Outcome** | The commission's own precondition applies: *"If the required baselines are not yet sufficiently established, do not start a parallel Mobile discovery track. Identify the specific unresolved domain evidence that prevents integration."* **They are not established** (§1). This document therefore delivers that identification, plus what holds regardless of the gaps. **The full C1–C18 × mobile matrix is not produced.** |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `reviews/` |
| **Snapshot** | branch `election-flow-updated`, HEAD `5a26453d5` |

**Labels:** **FACT** · **INTERPRETATION** · **ASSUMPTION** · **UNKNOWN** · **RECOMMENDATION** · **[PK]** Principal's recorded practical knowledge · **[PRAC]** general practice known to the analyst (never evidence about a specific organisation).

## 0. Disagreements (stated first)

1. **The inputs called "canonical" are proposals.**
   - **C1–C18** is my own first-draft inventory of roadmap Phase 4 step C-2 (`../architecture/reviews/2026-11-09-business-capability-and-strategic-ddd-baseline-discovery-report.md`: PROPOSED, not independently reviewed, not accepted). Phase 4 is accepted only together with Phase 3.
   - **R-01…R-18** (`context-map.md`) is PROPOSED and lags behind the Session B working map (X-2 of the Gate F report).
   - Building a mobile matrix on them "as canonical" would turn proposals into facts. That is the failure this programme guards against.
2. **"Finance owns financial facts, including FeePaid" is listed as settled again.** It is not (Gate F report §6.3: no ratified direction; a recorder's note N-5 vs the code). It does not affect this assessment.
3. **"Mobile voting must not be assumed to be in scope" needs a precise reading.**
   - FACT: voters already use mobile networks. PBDIGIT-315: "Home routers and mobile networks change the last number of the IP, so captured voters were refused and needed a re-capture" (`docs/publicdigit/backlog/PBDIGIT-315-ip-binding-network-match.md:12`).
   - FACT: the business case promises "Vote from smartphone, tablet, or computer" (`docs/publicdigit/business_case/BUSINESS_CASE.md:179`).
   - So **voting on a phone through the browser is already happening.** What stays out of scope is voting in a **store app or mobile pilot**.
   - The mobile-network risks (V-2, V-3 in mobile report `06`) are **live now** and belong to the voting-security track (PBDIGIT-286, -315), not to a future mobile track.

**Agreed:** mobile is an access channel, not a domain or a bounded context; extract or establish the authoritative operation before exposing it (D-7); security findings are handled separately from mobile strategy; the Angular/Vue question stays open; `CommitteeAssociation` belongs to Membership/Organisation (PD-3), not to mobile.

## 1. Why the gate is not met: the blocking evidence

| # | Missing evidence | Which part of the matrix it blocks | Where it is tracked |
|---|---|---|---|
| **B-1** | **Demand side: which actor needs which task on a phone, and why.** No user research, no stakeholder record, no PO statement of mobile needs per capability. Only three evidence points exist (§2) | the columns *actor, business outcome, mobile value, channel requirement* for **every** capability | not tracked. **New** |
| **B-2** | **Ownership on the organisation side is provisional:** units and belonging (D-3, D-22 working), organs and positions (D-10 working), the Organisation context "not shown to be coherent" (ODA §11.2) | *context owner, authority owner* for C1, C2, C5 and officer actors | Phase 3, Gate F, O-16, PD-3 |
| **B-3** | **Organisational actors are not modelled:** "committee official", "authorised officer" are positions within organs. Who may act in PublicDigit for an organ is "organisation rules decide" (V-59), and platform roles ≠ authority (Decision A) | every officer row (scenarios 3, 6, 7) | D-10; procedural-validity report HG-1 (statutes) |
| **B-4** | **One context map:** two maps disagree in content and numbering | *dependencies* column | platform report HD-2 |
| **B-5** | **Capability inventory acceptance** (C1–C18 is a draft) | the row set itself | roadmap Phase 4 |

**B-1 is the only blocker that is not already tracked, and it does not depend on the organisational baseline.** "Who needs what on a phone" is stakeholder evidence. The roadmap already places that in Phase 4 ("capability inventory; responsibility matrix; **stakeholders**"). Mobile is then one attribute of each actor task (the context in which it is done), **not a separate track**.

## 2. Evidence that exists today

| Evidence | What it shows | Strength |
|---|---|---|
| PBDIGIT-311 (`docs/publicdigit/backlog/PBDIGIT-311-mobile-menu-unreadable.md:3`): "Raised by: the PO, on the mobile view of `…/voters/manage`" | a voter-management page (C6/C8) was **used at phone width by the PO**. *Correction (2026-11-09, demand-discovery record Q1):* the ticket does not say the PO was acting as an election officer, so "an officer needs this on a phone" was an overstatement. The Principal has since directed that administration is desktop-only | FACT, one observation; not evidence of officer demand |
| PBDIGIT-315 `:12` | **voters act from mobile networks**; network-dependent protections refused them | FACT, one recorded problem |
| Business case and executive summary: "Mobile-friendly design for users without desktop access" (`EXECUTIVE_SUMMARY.md:32`), "Mobile-first … developing markets with mobile-only users" (`:200`), "Vote from smartphone" (`BUSINESS_CASE.md:179`) | the product's stated intent: members and voters, many mobile-only | pre-discovery product claim (Feb 2026); not user evidence |
| `public/manifesto.pdf` p. 3 (an NRNA candidate's manifesto, 2026): pledges to make membership management, finance, records and elections digital and "technology-friendly" | demand for digital membership and elections in NRNA; **says nothing about mobile** | context only |
| `public-digit/CLAUDE.md:566` (sibling project): "Angular Mobile — Member participation & voting" | an earlier product direction: members (not admins) as the mobile audience | historical direction; not reconciled |
| [PRAC] diaspora associations and Nepali parties communicate with members mainly through phones (messaging apps, social media) | plausible that members are largely mobile-only | **not evidence for NRNA**; a question for B-1 |

## 3. What holds regardless of the gaps

These follow from ratified, frozen or PO-decided records, so they apply to any channel today:

| Principle | Basis |
|---|---|
| No channel holds authority. A mobile API never decides membership, eligibility, governance outcomes or votes | act §11 (RATIFIED); Decision A; ADR Voting Authorization Boundary |
| One authoritative operation per business action; every channel calls it (extract before expose, D-7) | mobile report `05` §3 (FACT: some flows have use cases, others not) |
| Caches are for display only; authority-bearing facts (eligibility, capabilities, membership status) are refetched before any authority-bearing act | mobile report `06` §3 |
| No device identifier, push token or telemetry may be joinable to `votes` or `results` | VO-1 / BDR-12 (FROZEN) |
| No offline or queued ballot casting | BDR-02, anonymity; mobile report `06` |
| Organisation-specific rules never become platform defaults, in any channel | D-1b |

**Readiness by ownership** (which rows *could* be mapped once demand evidence exists):

| Capability | Ownership status | Mapping possible once B-1 is supplied? |
|---|---|---|
| C3 Membership | RATIFIED | yes |
| C6 Election administration, C8 Voter admission, C9 Voting, C10 Results | RATIFIED / FROZEN boundaries | yes (voting: browser only; store app excluded) |
| C14 Identity and access, C15 Tenancy | partly ratified; account model PROPOSED | partly |
| C1 Registry and structure, C2 Governance, C5 Unit belonging | working decisions, Phase 3 open | **no** (B-2, B-3) |
| C4 Dues and finance | FeePaid direction open | partly |
| C11 Contestation/Adjudication, C12 Evidence, C13 Audit | FROZEN; not user-facing today | low priority |
| C16 Reference geography | working [D] | yes (read only) |
| C17 Communication, C18 Social contribution | no design | **no** |

## 4. Principal decisions

| # | Decision | Why only the Principal | Alternatives | Recommendation | When |
|---|---|---|---|---|---|
| **MD-1** | **Confirm the principle:** mobile is an access channel to existing capabilities, not a domain, bounded context or source of authority | an architectural principle for the ARB | confirm / reject | **confirm** (the reviewer and this analysis agree) | now |
| **MD-2** | **How demand evidence (B-1) is obtained** | only the PO knows, or can reach, the actors | (a) a one-question-per-turn session with the Principal, recording [PK] actor tasks per capability, including the channel (as in Session B); (b) a short NRNA member and officer survey; (c) both, (a) first | **(c), (a) first.** Cheap; it fits roadmap Phase 4 "stakeholders"; (b) validates later. Record the answers in the Phase 4 inventory, not in a mobile document | now, if mobile is to progress |

**Not asked now:** channel strategy (D-1), Angular vs Vue (D-2), mobile authentication (D-4), the pilot (D-5), versioning (D-6). They follow from B-1 and the baselines. The live mobile-browser voting risk (§0.3) is **not** a new decision: it sits with PBDIGIT-286/315.

## 5. Answers to the commission's conclusion questions (as far as evidence allows)

1. **Capabilities that need mobile access:** evidenced for **voting through the browser** (PBDIGIT-315). PBDIGIT-311 shows only that the PO used an administration page at phone width (corrected; see §2). The Principal has directed that administration is desktop-only (demand-discovery record DD-1). Claimed for members generally (business case). **UNKNOWN** for everything else (B-1).
2. **Actors and outcomes:** voters casting votes. Others UNKNOWN.
3. **Owning contexts:** Election (ratified boundary), Voting (FROZEN), Membership (ratified). Organisation and Governance provisional.
4. **Existing operations usable by another channel:** Membership submit and approve (use cases); Governance committee members (handlers, session API). Not voting (session-bound) (mobile report `05` §3).
5. **Contexts needing tactical design before any mobile exposure:** Election/Voting (session-held ballot, V-1); Membership public apply (extraction); Governance and Organisation (baseline first).
6. **Technical requirements that already follow:** only the channel-neutral principles of §3. None selects a technology.
7. **Principal decisions:** MD-1, MD-2.
8. **Deferred:** D-1, D-2, D-4, D-5, D-6, store-app voting.

**Is the strategic baseline established enough to proceed to tactical design for the relevant contexts?** **Not for mobile-facing design.** It is enough for the channel-neutral principles above. Tactical design for Membership is already governed by its ratification. Election and Voting tactical work is driven by their own open items (PBDIGIT-284, G-1, PBDIGIT-286), independent of mobile.

## Traceability
- **Read:** the mobile investigation 00–10 (combined); the platform discovery report (C1–C18, HD-2); the Gate F report (§6.3, X-2); the procedural-validity report (HG-1); roadmap plan §5 (Phase 4); `docs/publicdigit/backlog/PBDIGIT-311*`, `PBDIGIT-315*`, `PBDIGIT-EPIC-01*:41`; `docs/publicdigit/business_case/BUSINESS_CASE.md:179`; `EXECUTIVE_SUMMARY.md:32,200`; `public/manifesto.pdf` p. 3; `public-digit/CLAUDE.md:566`.
