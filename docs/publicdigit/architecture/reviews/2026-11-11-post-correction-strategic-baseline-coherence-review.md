# Post-correction strategic baseline coherence review (through D-172)

| | |
|---|---|
| **Status** | **Review.** Records no ruling; changes no document. Written by Claude, **not independent** |
| **Commission** | "Post-Correction Strategic Baseline Coherence Review", accepted by the Principal 2026-11-11 |
| **Evidence** | Current repository texts, searched for remnants of superseded rules; decision record through D-172; IR-R1-02 (GPT-6) and its recorder note |

**Labels:** [AD] · [BF] · [AC] · [TD] · [TU] · [OQ] · [LQ].

**Disagreements:** none.

## Part 1. In plain language

- **PublicDigit centrally keeps only:**
  - who has registered and how they log in;
  - which organisations exist and where their data lives;
  - which organisations each account is connected to (for routing only);
  - the shared official map.
- **Each organisation keeps and controls everything else in its own database:**
  - its people, units and membership;
  - its committees and decisions;
  - its forums and newsletters;
  - (later) its elections and money.
- **A person registers once** and can connect to several organisations. **In each, the organisation alone decides** whether they are a member, where they belong and what office they hold.
- **Authority always comes from the organisation's own rules or its committees' designations. PublicDigit never fills a gap with its own choice of decision-maker:** if nobody is designated, the act waits.
- **PublicDigit's staff may operate the system but may look at an organisation's content only with that organisation's permission for that case**, recorded and reported.

## Part 2. Context map and ownership

| Context | Owns (authoritative) | Never owns | Consumers | Status |
|---|---|---|---|---|
| **Platform (tenancy)**, *recommended* | organisation registry, database locator, platform status and `schemaState`, record of the **service-operation grant** (D-166), **pending case-grant records** (D-170) | any organisation's business facts; any authority | all contexts (routing); Identity | [AC] **recommended**; E-11 says tenancy is a platform capability; owner open (HD-8) until accepted |
| Identity | Person, Account, login, sessions, issued `PersonRef`s, claims, bindings (routing) | membership, access, authority | Platform (routing), Organisation | [AD] D-75, D-110, D-111, D-113 |
| Organisation | profiles, contacts, capacities, units, belonging, placement, workspace grants, **case grants once recorded locally** | membership truth, authority | Governance, Deliberation, Communication | [AD] D-106, D-156 |
| Reference Geography | official references | belonging, authority | Organisation (later) | [AD] D-93; dormant in release 1 |
| Membership | periods, terms, renew / suspend / restore / end, coverage and standing intervals | belonging, rights | Organisation (C1/C2), Governance (C3) | RAT; D-161 … D-163 |
| Governance | rules on file (incl. **participation rule**, S2 §15), committees, offices, decisions, AuthorityCheck | structure, membership facts | Membership, Organisation, Communication, Deliberation | [AD] D-144, D-157 |
| Deliberation | forums, posts, moderation acts, **forum access decisions** | belonging, authority | Communication | [AD] D-158 |
| Communication | series, issues, audience decisions, opt-outs | audience facts, authority | — | [AD] D-158 |
| Election / Voting | electorate, issuance, ballots | membership, belonging | — | RAT boundary; outside release 1 |
| Finance | payments, settlement | standing, eligibility | — | RAT outline; outside release 1 |

**Platform (tenancy) assessment [AC]: properly positioned.**
- **Its records are platform infrastructure; the grants they hold are the organisation's decisions** (three kinds of ownership: Platform is domain owner of the record, the organisation is information owner of the grant).
- **D-170's pending record holds grant metadata only and is copied to the organisation.** A narrow exception that centralises no content and no authority.
- **No overlap with Identity** (Identity routes people; Platform locates organisations).
- **No missing owner; no double owner** in release-1 scope.

## Part 3. Decision consistency

| Decision | Reflected in | Status |
|---|---|---|
| D-113 | S2 §4, §13.0–§13.1; I-TDD-01 supersession note | **CONFIRMED** |
| D-154 + D-172 | thin-slice note; coherence-review note; IR-R1-02 recorder note | **PARTIAL**: `2026-11-10-minimum-package-coherence-check.md` K-1 (line 48) still states D-154 (2) unqualified (**P-4**) |
| D-164 | S2 §2, §13, §15–§16; Addendum M | **CONFIRMED** |
| D-166 + D-170 (INV-ACCESS-1) | H-1 analysis §D, §H; S2 ISO-8, §16 M-6, D-170 note | **CONFIRMED**; procedural, not cryptographic, stated; release-1 criteria listed |
| D-167 | H-1 analysis §B, §I; thin slice (D-145 note); round 7 note | **PARTIAL**: **D-53's tribunal default remains in body text**: thin slice line 52 ("default the tribunal") and scenario 11 (line 168); Newsletter / Forum design `ModerationAct` row (line 114) (**P-1**). Round 7 rows (lines 103, 107, 147) still state the Highest Committee clause in place, though its closing note supersedes them (**P-3**) |
| D-168 | S1 §18; S2 §15 (`ParticipationRuleAsAt`, `ruleSource`) | **PARTIAL**: **the accepted Newsletter / Forum design (D-158) and thin slice (D-157) do not carry the participation-rule read or the label** (**P-2**) |
| D-169 | decision record §11.73 | **CONFIRMED**, but see **P-6** |
| D-171 | thin-slice note | **CONFIRMED** |
| D-146, D-147, D-160, D-163 | integrated design §9; S1; S2 | **CONFIRMED** (demo mode deferred, S2 §16) |

## Part 4. Findings

| ID | Sev. | Evidence | Fact / inference | Business impact | Correction | Principal? |
|---|---|---|---|---|---|---|
| **P-1** | medium | thin slice lines 52, 168; Newsletter / Forum design line 114: D-53's "default the tribunal" | fact | a builder could route moderation challenges to a body the organisation never created | **conformance note** in both: challenge recorded and waits until the rules or a designation name the reviewer (D-167) | no |
| **P-2** | medium | D-168's override and D-167's label exist only in S1 / S2; absent from the accepted D-157 and D-158 texts | fact | the accepted Forum / Newsletter design would impose D-161 without the organisation's override, or without saying it was a platform default | **conformance notes**: Governance exposes `ParticipationRuleAsAt`; Forum and Newsletter apply it, else D-161, and record `ruleSource` | no |
| **P-3** | low | round 7 lines 103, 107, 147 | fact | none (closing note supersedes; round 7 is not accepted) | mark rows superseded when round 7 is next revised | no |
| **P-4** | low | coherence check K-1 line 48 | fact | old reading of D-154 (2) | add D-172 note | no |
| **P-5** | low | S2 line 162: `ReferenceIssuer` "no personal data" | fact | contradicts M-5 | reword: pseudonymous personal data | no |
| **P-6** | medium (process) | every conformance note after D-169 changes an accepted text's fingerprint again (thin-slice D-171 / D-172 notes; P-1, P-2 would add more) | fact | **"accepted" drifts from "in the repository" each time a later decision is applied** | **apply P-1 … P-5 in one pass, then re-fingerprint all affected accepted texts in the baseline acceptance act** | **yes**: part of the acceptance |
| P-7 | low | context map and status record lag the decision record (consolidation review §11.6) | fact | readers may trust the map over the record | documentation pass after acceptance; **the decision record governs** | no |

## Part 5. Principal decisions

**One, combined with acceptance: accept the strategic baseline through D-172.** It would include:
1. **the Platform (tenancy) context as owner of:**
   - the registry;
   - the database locator and status;
   - the service-operation grant record;
   - D-170 pending case grants.

   This closes HD-8's owner part.
2. **conformance notes P-1 … P-5 applied in one pass**, with **all affected accepted texts re-fingerprinted in the same act** (D-156, D-157, D-158, D-162 as at that moment);
3. **a stated basis:** one independent review (IR-R1-02, GPT-6) plus self-reviews.

**Nothing else needs you:** no new business rule; D-169 … D-172 are not reopened.

## Part 6. Verdict

**COHERENT AFTER SPECIFIC CORRECTIONS.** The corrections are P-1, P-2, and the lows P-3 … P-5. All are conformance notes implementing decisions already made. **No contradiction remains between decisions.**

**Three levels, kept apart:**

| Level | Status |
|---|---|
| **Strategic baseline** | ready for the Principal's acceptance once the notes are applied in the acceptance pass |
| **Tactical designs** | S1 (rev 6) and S2 (rev 5) await acceptance; Identity's claim design (I-TDD-02) is pending in its own session |
| **Implementation** | not ready: every proof obligation is untested; legal answers outstanding |

**Next tactical subject after acceptance: the Platform (tenancy) context.** Its boundaries are clear (Part 2), and it is the foundation every organisation database depends on:
- organisation lifecycle;
- registry and locator;
- service-operation and pending grants;
- routing.
