# Membership Decision Register (Final)

**Date:** 2026-10-08 · **Supersedes:** `2026-10-08-membership-decision-register.md` · **records no new decision**
**Canonical model:** `2026-10-08-membership-architecture-ratified-candidate.md` (**RC**) · **Proofs:** `2026-10-08-membership-proof-and-verification.md` (**PV**)

## A. Already adopted

EM-VOC-001/002/003 · EM-ENT-001…007 · EM-EO-001…004 · EM-FM-001…006 (deferred) · EM-SEQ-001/002 · EM-GOV-001…003 · SM2 (2026-09-16) · ADR_20261004_1549 · ADR-T11 · Manifesto ≠ Constitution (2026-08-13) · delivery baseline freeze (2026-10-01).

## B. Architecture ready for Principal Reviewer ratification

| ID | Item |
|---|---|
| AR-1 | D1: one Organisation Membership aggregate per (Person, Organisation); **a conditional proposal** (minimal iff BD-I5 + BD-SYNC) |
| AR-2 | B′ structure: an enduring MembershipId; a new PeriodId per admission; the identifier matrix (RC §7) |
| AR-3 | INV-9 + guards g1, g2, **ER** (replaces NE) |
| AR-4 | **Trim as a recorded consequence fact**; trim reversals subject to ER |
| AR-5 | fact kinds: Admit, Renew, ChangeType*, Suspend, Restore, End(kind), **Annul**, **Void**, Correct, Trim (+ Recognise / TermExtension only if BD-REC0 / BD-SUSP require them) |
| AR-6 | append-only facts; `effectiveAt` / `decidedAt` / `recordedAt` + immutable `seq`; precision for attested dates; three-valued predicates under imprecision |
| AR-7 | provenance {decision, rule, attested}; authority evidence snapshot |
| AR-8 | the predicate set (RC §3): InLifetime, Ended, TermCovered, InForce, Lapsed, Suspended, IsMember, CurrentPeriod, CurrentType, the Q-TEN set; **no stored status** |
| AR-9 | lifetime ≠ term ≠ standing ≠ eligibility ≠ service validity |
| AR-10 | RestrictionSet: Membership owns `Suspended` only |
| AR-11 | number uniqueness outside the aggregate; MembershipNumber is a credential, not an identifier; no Number Registry |
| AR-12 | the published boundary (RC §10); (MembershipId, asOf) references preferred over PeriodId |
| AR-13 | Event Sourcing not required |
| AR-14 | INV-14: ids and facts are never deleted or rewritten |
| AR-15 | sections outside the root; person merge = an Identity act + an explicit Membership root-merge / reconciliation |

## C. Business / PO decisions: architecture-impact classification

**Key:**
- **PARAM** = the answer selects a pre-defined architecture variation point (no redesign);
- **BUS** = business-only (no architecture element changes);
- **BLOCK** = would change architecture if left unparameterised.

| ID | Plain German question | Aggr. | Ident. | Inv. | Temporal | Facts | Cmd | Guard | Contract | Migr. | Persist. | Class |
|---|---|---|---|---|---|---|---|---|---|---|---|---|
| **BD-1** | *Ist ein Wiedereintritt dieselbe Mitgliedschaft mit neuem Zeitraum – oder eine neue Mitgliedschaft?* | – | **●** | – | – | – | – | – | **●** (which id is "the membership") | ● | – | **BLOCK → PARAM** (RC §7: both ids are always published; an A-answer needs an EM-VOC-001 wording act) |
| BD-2 | *Behält ein wieder eingetretenes Mitglied seine Mitgliedsnummer?* | – | – | – | – | – | – | – | ● | ● | ● (slot) | **PARAM** (root vs period slot) |
| BD-3 | *Wodurch endet eine Mitgliedschaft (Austritt, Ausschluss, Beendigung durch den Verein, Tod)?* | – | – | – | – | value list | – | – | – | ● (mapping) | – | **BUS** |
| BD-SUSP | *Bleibt ein suspendiertes Mitglied Mitglied (zählt, zahlt, Laufzeit läuft)?* | – | – | – | (term pause?) | (TermExtension, opt.) | – | – | – | – | – | **BUS** (IsMember is fixed; counting = consumer policy) / **PARAM** if a term pause is wanted |
| BD-REC0 | *Gibt es „Mitglied, aber noch nicht bestätigt“?* | – | – | – | – | (Recognise, opt.) | ● | – | ● | – | – | **PARAM** |
| **BD-I5** | *Darf jemand gleichzeitig zwei Mitgliedschaften (z. B. Ehren- und ordentliche Mitgliedschaft als getrennte Klassen) in derselben Organisation haben?* | – | – | **●** (scope) | – | – | – | **●** (g1 key) | – | ● | ● | **BLOCK → PARAM** (INV-9 scope = (p,o) or (p,o,class); the root is unchanged) |
| BD-SYNC | *Muss eine doppelte Mitgliedschaft jederzeit ausgeschlossen sein, oder darf sie nachträglich bereinigt werden?* | (minimality only) | – | – | – | – | – | – | – | – | – | **BUS** (D1 is valid either way) |
| BD-NE | *Darf eine Mitgliedschaft im selben Augenblick beginnen und enden (z. B. sofortiger Rücktritt)?* | – | – | – | – | – | – | – | – | – | – | **BUS** (ER handles both answers) |
| BD-REV | *Ausschluss nach Wiedereintritt aufgehoben: Aufhebung ablehnen oder alten Zeitraum fortsetzen und neuen kürzen?* | – | – | – | – | (Trim used / unused) | – | ● | – | – | – | **PARAM** (g2 policy) |
| BD-TERM4 | *Gilt ein erfolgreich angefochtener Ausschluss als nie geschehen?* | – | – | – | – | – | ● (Annul vs re-Admit) | – | – | – | – | **BUS** |
| BD-CONSENT | *Darf jemand ohne eigenen Antrag / eigene Zustimmung Mitglied werden (außer satzungsmäßig/automatisch)?* | – | – | – | – | – | – | ● | – | ● | – | **PARAM** (consent guard) + legal |
| BD-TEN | *Was bedeutet „Mitglied seit“?* | – | – | – | – | – | – | – | – | – | – | **BUS** (all predicates exist) |
| BD-NU | *Dürfen zwei Organisationen dieselbe Mitgliedsnummer vergeben?* | – | – | – | – | – | – | – | – | ● | ● | **PARAM** (constraint scope) |
| BD-TYPE | *Kann sich die Mitgliedsart innerhalb eines Zeitraums ändern?* | – | – | – | – | (ChangeType opt.) | ● | – | – | – | ● | **PARAM** |
| BD-GVR | *Soll „gewährt Stimmrecht“ entfallen oder nur noch Einordnung sein?* | – | – | – | – | – | – | – | – | ● | – | **BUS** |

**Result:**
- **No answer forces a redesign.** BD-1 and BD-I5 are the two that would without the parameterisation already in RC.
- **Must decide before ratification closes:** BD-1, BD-I5 (they fix the parameters).
- **Must decide before the related commands are built:** BD-2, BD-REC0, BD-REV, BD-CONSENT, BD-NU, BD-TYPE, BD-SUSP (the term-pause part).
- **Business-only, any time:** BD-3, BD-SYNC, BD-NE, BD-TERM4, BD-TEN, BD-GVR.

**Recommendations** (unchanged; *awaiting PO confirmation*):
- BD-1: B′
- BD-2: retain
- BD-3: four grounds; lapse ≠ end
- BD-SUSP: S2, no term pause
- BD-REC0: no
- BD-I5: (p,o) unless the statutes define concurrent classes
- BD-SYNC: yes
- BD-NE: allow (harmless under ER)
- BD-REV: trim
- BD-TERM4: annulment
- BD-CONSENT: required unless rule / attested
- BD-TEN: named predicates
- BD-NU: per organisation
- BD-TYPE: may change
- BD-GVR: non-granting classification

## D. Legal / downstream / future (non-blocking for the Membership architecture)

| ID | Item | Owner |
|---|---|---|
| LQ-1 | consent; statutory / ex-officio / automatic membership | legal |
| LQ-2 | effect of a successful appeal | legal |
| LQ-3 | termination grounds and procedures | legal |
| LQ-4 | retroactive statute changes | legal |
| DS-1 | fee policy for trimmed periods | Finance / E0b-S |
| DS-2 | Q-FM-MAP, FM-5, FM-6 | Election (Full Membership phase) |
| DS-3 | person equivalence → Membership root-merge / reconciliation | Identity |
| DS-4 | authority model and AuthorityCheck port | Governance |
| DS-5 | standing | E0b-S |
| F-1 | ML for duplicate detection / migration anomalies (advisory) | future |
| F-2 | section enrolments (if a multi-section tenant appears) | future |

## E. Implementation choices

Fact storage shape (one table, or one per kind; Annul and Void may share a shape) · the `seq` mechanism · materialised current state · the (organisation, number) unique index and the allocator · the tenant time-zone source · query port vs events · migration mechanics.
