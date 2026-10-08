# Membership Decision Register

**Type:** The single decision register for the Membership architecture · **Date:** 2026-10-08 · **records no new decision**
**Companion:** `2026-10-08-membership-architecture-final.md` (**FA**) · `2026-10-08-membership-implementation-readiness.md`
**Rule:** an item is listed as *adopted* only when an existing PublicDigit record adopts it. Every other item keeps its category until a PO, ARB or Principal Reviewer act moves it.

---

## 1. Adopted (existing PublicDigit record)

| ID | Decision | Source |
|---|---|---|
| EM-VOC-001 | "Organisation Membership" = the `Member` aggregate (identity, type, fees, term) | Manifesto §4, PO 2026-08-12 |
| EM-VOC-002 | an affiliation / role is not Organisation Membership | same |
| EM-VOC-003 | ElectionMember ≠ Organisation Member | same |
| EM-ENT-001…007 | ElectionMembership = the election entitlement record; existence ≠ exercisability; Election-owned | PO 2026-08-12 (A-1…A-6) |
| EM-EO-001…004 | Election-Only needs no Organisation Membership | PO 2026-08-12 |
| EM-FM-001…006 | Full Membership: removal auto-suspends (**deferred**) | PO 2026-08-12 (D-ENT-2) |
| EM-SEQ-001/002 | Election-Only first; Full Membership a later phase | PO 2026-08-12 |
| EM-GOV-001…003 | election governs exercisability; Chief suspension ≠ organisation-driven suspension | PO |
| SM2 | ElectionMembership lifecycle; REMOVED final | PO/ARB 2026-09-16 |
| ADR_20261004_1549 | Registration → derived Admission → VotingEntitlement (incl. `canVote`) | PO 2026-10-04 |
| ADR-T11 | anonymity | ADR-T log |
| Manifesto ≠ Constitution | terminology and placement | Principal Architect 2026-08-13 |
| Delivery baseline freeze | new PBDIGIT IDs only for uncovered obligations | PO 2026-10-01 |

## 2. Architecture: for Principal Reviewer ratification (not yet ratified)

| ID | Proposal | Basis (FA §) |
|---|---|---|
| AR-1 | D1: one Organisation Membership root per (Person, Organisation), with identified periods | §10 (minimal under BD-SYNC) |
| AR-2 | B′ structure (a new PeriodId per admission; MembershipId enduring) | §4 (semantics = BD-1) |
| AR-3 | INV-9 period lifetimes pairwise disjoint; guards g1 (interval) / g2 (lifetime-enlarging facts) | §5 (proven under A1–A5) |
| AR-4 | **NE: non-empty lifetimes** (an end at or before the start = invalidating the admission) | §5 (needed by the proof); the business acceptance is BD-NE |
| AR-5 | reversal policies limited to **refuse** or **supersede-by-trim**; void-merge and overlap **rejected** | §5, §6 |
| AR-6 | the g2 consequence is recorded on the triggering fact | §0, §3 |
| AR-7 | append-only facts with three clocks + a total recording order (seq) | §3, §8 |
| AR-8 | provenance {decision, rule, attested} | §3 |
| AR-9 | fact kinds: Admit, Renew, ChangeType*, Suspend, Restore, End(kind), **Invalidate{annulment \| nullity}**, Correct | §7, §19 |
| AR-10 | no stored status; resolution classifications are derived | §6 |
| AR-11 | term segments (domain concept); type segments conditional on BD-TYPE | §11 |
| AR-12 | authority evidence snapshot; rules stay in Governance | §14 |
| AR-13 | RestrictionSet: Membership owns `Suspended`; bans and standing restrictions owned elsewhere | §9 |
| AR-14 | number uniqueness enforced outside the aggregate (organisation-level) | §12 |
| AR-15 | section enrolments outside the root | E0b §14 |
| AR-16 | published boundary: IsMember, Suspended, CurrentType, the named tenure predicates, standing (E0b-S), End/Suspend/PeriodTrimmed/PeriodSuperseded events, PeriodId (only if Q-FM-MAP A) | §15 |
| AR-17 | Event Sourcing **not required** | §8 |
| AR-18 | INV-14: no PeriodId or MembershipId is ever deleted, re-pointed or rewritten | §5, §16 |

## 3. PO decisions (business; Recommended, awaiting PO confirmation)

| ID | Business question (plain German) | Why it matters | Evidence | Recommendation | If YES | If NO | PO decides | Legal / statutory |
|---|---|---|---|---|---|---|---|---|
| **BD-1 (D-ID.1)** | *Wenn jemand austritt und später wieder eintritt: Ist das dieselbe Mitgliedschaft (mit einem neuen Zeitraum) oder eine ganz neue Mitgliedschaft?* | identity, references, reports | none direct; the legacy is split | **same Membership, new period (B′)** | AR-2; EM-VOC-001 unchanged | the period is "the membership" → **EM-VOC-001 wording amendment** (PO act); structure unchanged | the answer | no |
| **BD-2 (D-ID.2)** | *Behält ein wieder eingetretenes Mitglied seine alte Mitgliedsnummer?* | credential continuity | the number is organisation-issued | **yes, retained (on the root)** | number on the root | number on the period | the answer | no |
| **BD-3 (D-TERM-1)** | *Wodurch endet eine Mitgliedschaft: Austritt, Ausschluss, Beendigung durch den Verein (z. B. Nichtzahlung), Tod? Ist „nicht verlängert“ etwas anderes als „beendet“?* | ending semantics | guide (resignation, non-payment) | **these four; non-renewal is a lapse, not an ending** (an optional lapse→End rule = `rule` provenance) | End kinds fixed | different kinds (an enumeration only) | the list | **yes** (the statutes define the grounds) |
| **BD-SUSP (D-SUSP)** | *Bleibt ein suspendiertes Mitglied Mitglied (zählt mit, zahlt Beitrag, Laufzeit läuft weiter) – nur seine Rechte ruhen?* | the IsMember definition | no S1 evidence | **yes (S2)** | IsMember unchanged | a counting definition and optional term-pause facts | the answer | yes (statutes) |
| **BD-REC0 (REC-0)** | *Kann jemand „Mitglied, aber noch nicht bestätigt“ sein – oder ist man entweder bestätigtes Mitglied oder nur Antragsteller?* | is "pending" a state? | none | **no pending membership** | no Recognise fact | adds a Recognise fact | the answer | no |
| **BD-I5 (I5)** | *Darf eine Person gleichzeitig zwei Mitgliedschaften (Zeiträume) in derselben Organisation haben?* | INV-9 | no counterexample | **no** | INV-9 rule | INV-9 relaxed; D1 still valid | the answer | possibly |
| **BD-SYNC** | *Muss eine doppelte Mitgliedschaft jederzeit ausgeschlossen sein – auch nicht für einen Moment –, oder darf sie nachträglich bereinigt werden?* | D1 minimality | none | **always excluded** | D1 minimal | D2 admissible | the answer | no |
| **BD-NE** | *Wenn ein Austritt auf den Tag des Eintritts (oder davor) fällt: Ist das ein Austritt – oder war die Aufnahme einfach ungültig?* | the proof assumption | the counterexample (52 splits) | **the admission is invalid (no zero-length membership)** | NE holds | needs point-inclusive overlap handling | the answer | no |
| **BD-REV (Q-ANNUL-RE)** | *Ein Ausschluss wird nach einem Wiedereintritt aufgehoben: Lehnen wir die Aufhebung ab, oder gilt die alte Mitgliedschaft als nie beendet (der neue Zeitraum wird entsprechend gekürzt)?* | reversal | verified both clean | **supersede-by-trim** (refuse is acceptable) | the trim policy | the refuse policy | the choice | yes (appeal law) |
| **BD-TERM4 (D-TERM-4)** | *Wenn ein Ausschluss erfolgreich angefochten wird: Gilt er als nie geschehen?* | annulment semantics | none | **yes (A1 annulment)** | Invalidate{annulment} | restoration or a new admission | the answer | **yes** |
| **BD-CONSENT (Q-CONSENT)** | *Darf jemand ohne eigenen Antrag / eigene Zustimmung Mitglied werden (außer bei satzungsmäßiger oder automatischer Mitgliedschaft)?* | admission validity | path analysis | **no** (a declaration is required; `rule` / `attested` substitute) | direct add needs evidence | a direct add is allowed | the product rule | **yes: legal validation required** |
| **BD-TEN (Q-TEN)** | *Was bedeutet „Mitglied seit“: seit dem ersten Eintritt, seit dem letzten Eintritt, oder die gesamte Mitgliedszeit?* | committee rules, election criteria | `minMembershipYears` exists | **named predicates; display = since the latest admission, with the first admission shown too** | — | — | the display default; consumers choose their predicate | statutes (per rule) |
| **BD-NU (N-U)** | *Dürfen zwei verschiedene Organisationen dieselbe Mitgliedsnummer vergeben?* | uniqueness scope | organisation-issued numbers | **yes (unique per organisation)** | an org-scoped constraint | global | the answer | no |
| **BD-TYPE** | *Kann sich die Mitgliedsart innerhalb eines Zeitraums ändern (z. B. Student → ordentlich), oder erfordert das einen neuen Eintritt?* | type segments | none | **may change (ChangeType)** | type segments | the type is a period attribute | the answer | no |
| **BD-GVR** | *Soll das Merkmal „gewährt Stimmrecht“ der Mitgliedsart entfallen oder nur noch als Einordnung (ohne Rechtewirkung) dienen?* | type ≠ entitlement | ADOPTED EM-ENT | **reinterpret as a non-granting classification** | kept as a classification | retired (+ its tests) | the answer | no |

## 4. Legal validation (outside the architecture corpus)

| ID | Question |
|---|---|
| LQ-1 | consent: can membership be imposed (direct add); statutory / ex-officio / automatic membership bases per jurisdiction |
| LQ-2 | effects of a successful appeal (annulment vs restoration) |
| LQ-3 | termination grounds and procedures (expulsion, non-payment) |
| LQ-4 | retroactive statutory changes |

## 5. Downstream decisions

| ID | Owner | Question |
|---|---|---|
| DS-1 | Finance / E0b-S | policy for a fee attached to a trimmed or superseded period (refund / credit / keep) |
| DS-2 | Election (Full Membership phase) | Q-FM-MAP (period-bound vs person-level); FM-5 (which End kinds = "removal"); FM-6 (suspension) |
| DS-3 | Identity | person equivalence; triggers the Membership root-merge + reconciliation act |
| DS-4 | Governance | the authority model (organs, competence; EQ-5, EM-OPEN-014) and the AuthorityCheck port |
| DS-5 | E0b-S | standing / dues compliance definition |

## 6. Implementation choices (after ratification)

Fact table design · the `seq` mechanism · the materialised state · the uniqueness index on (organisation, number) and the allocator · the tenant time-zone source · query port vs events for publishing · the migration mechanics.

## 7. Deferred

Section enrolments (when a real multi-section tenant appears) · type-prefixed numbering · person-merge tooling · ML (duplicate detection, migration anomalies) · the full record-history UI.

## 8. Status rollup

| Category | Count | State |
|---|---|---|
| Adopted | 13 | in force (EM-FM deferred) |
| Architecture for ratification | 18 | **awaiting the Principal Reviewer** |
| PO decisions | 15 | **awaiting the PO** (recommendations given) |
| Legal validation | 4 | outside the corpus |
| Downstream | 5 | owned by other contexts |
