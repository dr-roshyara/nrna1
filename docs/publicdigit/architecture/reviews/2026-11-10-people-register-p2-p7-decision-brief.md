# People register: decisions P2…P7 (Principal decision brief)

| | |
|---|---|
| **Status** | **Decision brief.** Records no ruling; changes no other document. Written by Claude, **not independent**. Builds on the independent review IR-OPR-01 (non-Claude reviewer, verdict "conditionally sound"). |
| **Why now** | The Principal's status note of 2026-11-10 recommends completing the people-register decisions first. **P1 is already decided:** D-113 (R2b: reference issued at import, Person created at claim; binding is not a merge; at most one of an organisation's references bound to one Person). D-112 (the amendment note on baseline D-106) is approved. |
| **Sources** | `./2026-11-10-ir-opr-01-independent-review.md` §5, §8, §10, §11; `./2026-11-10-organisation-people-register-strategic-and-tactical-design.md` rev. 3; decision record D-100…D-113; addenda 1 and 2 of the Organisation decision brief; PBDIGIT-341 plan. |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. What D-113 already settles in P2…P7
- **P4 (duplicates), part:** "at most one of an organisation's references may be bound to one Person; a second is refused and reported to that organisation". So **after a binding, two of an organisation's records can never both point to the same human through Identity**: the second claim is refused. IR-OPR-01's finding F-7 (two records sharing one `PersonRef` after a merge notice) **cannot arise through claims**. It can still arise through an **Identity merge of two Persons** (C-5), which D-113 leaves open.
- **I-13** must read "at most one Account per Person; duplicate Accounts possible until Identity's explicit merge" (F-6). This is a correction, not a decision.

**Dependency order:** P2 (archiving) and P3 (overlap) shape the aggregates. P4 needs P2. P6 needs P3/P5. P5 is independent. P7 is operational and last.

## 1. P2: what does "archiving" a person mean?
**Business question:** when an organisation archives someone from its register ("we no longer deal with this person"), what happens to their relationships and workspace access, and can it be undone?

| Option | Meaning | Consequence |
|---|---|---|
| (i) **archive ends everything** | a recorded decision that ends all active relationships and revokes access, with the archiving officer as actor and the archive as basis | one act does everything; needs a guarantee that nothing stays active (hard: one consistency unit; or soft: detected and repaired, with a stated window) |
| (ii) **archive only when nothing is active** | the organisation ends relationships first; archiving is refused otherwise | no cascade; each end keeps its own basis; a race with a *new* relationship remains, closable by refusing new relationships on archived people |
| (iii) **status flag only** | relationships continue | "archived" would not mean what organisations expect; not recommended |

- **REC (ii).** Political organisations end memberships, roles and access by **separate decisions** with their own authority. A cascade would record ends whose real basis was never decided (IR-OPR-01 F-2). With (ii), the register shows what is still active and the officer ends each item properly. A new relationship on an archived person is refused.
- **Restoration:** REC allow an **unarchive** act with a basis, forward-only; earlier ends stay ended.
- **Erasure:** depends on XD-PROV (open). An erased person must have nothing active (follows from ii).

**PRINCIPAL:** (i), (ii) or (iii); and whether unarchiving is allowed.

## 2. P3: may two periods of the same relationship overlap?
**Business question:** can a person hold, for example, two overlapping Guest relationships with the same organisation, or two overlapping Worker engagements?

| Option | Consequence |
|---|---|
| **no overlap** | a real rule must be enforced: one consistency unit per (person, capacity) holding all occurrences (IR-OPR-01 F-1, amendment 1) |
| **overlap allowed** | each occurrence stays its own unit; no enforcement needed; displays show several active entries |

**Domain view:**
- Two Worker engagements at once can be real: two different roles, or a paid post plus volunteer work.
- Two Guest relationships at once add nothing.
- For **voter**, an overlap would be meaningless (one standing relationship, P5).

**REC:**
- **overlap allowed for Worker and Guest**, each occurrence carrying its own description;
- **at most one active voter relationship per person** (enforced with the voter relationship itself);
- the organisation's rules may add limits (D-97); the platform invents none.

**PRINCIPAL:** confirm.

## 3. P4: who may merge an organisation's own duplicate records?
**Business question:** NRNA finds it has two records for the same human (imported twice). May it merge them, who decides, and what happens meanwhile?

**Settled by D-113:** a second claim against a bound Person is refused and **reported to the organisation**. That report is the main trigger.

| Point | REC (P) |
|---|---|
| may the organisation merge its own records? | yes; it is the organisation's own data (D-101) |
| who | the authority the organisation's rules name; by default the officer who maintains the register, as a **recorded decision** with a basis |
| condition | the records may hold **at most one bound reference**; otherwise Identity must be involved (C-5) |
| what is kept | one record continues; the other is closed as "merged into", history kept; its unbound reference is retired through Identity |
| meanwhile | each record stays separate; Membership and Election treat references as different until the organisation merges |
| after an **Identity** merge of two Persons (C-5) | the organisation is told only about its own references; if two of its records are affected, it **must** decide (merge or confirm distinct) before its next electorate cut-off; Election refuses to fix an electorate while such a case is open (the D-41 pattern) |

**PRINCIPAL:**
- confirm that organisations may merge their own records under their own rules;
- the "decide before cut-off" duty after an Identity merge.

## 4. P5: the voter relationship (= O-7, = Q-1)
**IR-OPR-01 §8 and addendum 1 agree:** a **standing** relationship in the organisation's register (election-only organisations, D-109). The **electorate for each election is Election's own fact**, fixed at its cut-off from that relationship (or from a certified roll). Being on the list never creates entitlement. Membership-mode organisations need no voter list (Election reads Membership's C4).

**Evidence still missing (§8.3 of the review):**
- how NRNA produces lists today (one per election, or a maintained register);
- turnover between elections;
- whether lists exist outside elections.

**REC: standing**, with V-1…V-4 (addendum 1). **PRINCIPAL:** rule now, or after asking NRNA how lists are kept.

## 5. P6: what must an import guarantee?
**Business question:** an import row records a person and their voter relationship. If the person is recorded but the voter relationship fails, what is the organisation told, and what may it rely on?

| Option | Consequence |
|---|---|
| R-a all or nothing per row | simple for the organisation; forces two facts into one transaction |
| **R-b ordered, with a visible state** | the person is recorded; the voter relationship is a second step; the row shows "**person recorded, voter relationship pending**" until completed or abandoned |
| R-c person only | a failed voter relationship is silently lost unless the officer notices |

- **REC R-b** (also IR-OPR-01's). The voter relationship is the sensitive fact; it must never be *assumed* present, and the organisation sees exactly what is pending.
- **Row identity:**
  - REC: the organisation's **own stable identifier** per person (membership number or similar), where it has one;
  - otherwise file plus row, with re-import treated as new rows and reported as possible duplicates (P4).
- **Re-import of an updated list:** profile changes apply as **corrections with the import as basis**; nothing is deleted by omission (a person missing from a new list is reported, not archived).

**PRINCIPAL:** R-a, R-b or R-c; and "omission never archives" (REC yes).

## 6. P7: may the first capability coexist with the legacy voter import?
**Business question:** the people register's first capability (recording people without accounts, explicit access) could go live while the old voter import still links accounts by e-mail and creates affiliations silently (PBDIGIT-341).

| Option | Consequence |
|---|---|
| **containment first** | PBDIGIT-341 containment (neutral messages; no IP or name exposure; no silent linking) before or with the first capability |
| coexist | two ways of "knowing a person" at once: the new register (correct) and silent linking (incorrect), which confuses organisations and keeps the privacy defect live |

- **REC: containment first**, at least the steps that stop silent linking and existence disclosure (plan C-1…C-3, plus R-1 only in the claim-shaped form).
- **The new register does not depend on it technically**, but trust does: an organisation shown "your own people" must not meanwhile be shown other organisations' account data.

**PRINCIPAL:** confirm.

## 7. Technical corrections that are not Principal decisions (to apply in revision 4 of the design)
- F-1 (follows P3);
- F-2/F-3 (follow P2);
- F-5 (follows P6);
- F-6 (I-13 wording);
- F-9 (D-73 τ on every read);
- F-10 (mark unsourced contracts UNKNOWN: `ClaimRedeemed`, merge notice, Authorization mechanism; C1, C2 and `AuthorityCheckPort` are sourced);
- F-11 (name Election's electorate fact, after P5);
- F-12, F-15 (provenance; claim status on the organisation side);
- F-13 (refresh against D-112, D-113);
- F-14 (reconcile §12 with PBDIGIT-341).

## 8. Consequences for the rest of the work
- **Identity:**
  - **D-113 unblocks I-TDD-02**: P-I1 was its only blocking entry criterion;
  - I-TDD-02's scope must be refreshed to R2b: the claim names the organisation's reference, binding not merging, and "at most one bound reference per Person per organisation".
- **Election constituency** (`./2026-11-10-election-constituency-and-regional-voting-authority-decision-brief.md`): independent of P2…P7; E-1…E-4 still await rulings.
- **Mobile and PWA (the Principal's staged direction):**
  - stage 1 (responsive) is delivered (PBDIGIT-338);
  - **stage 2 (installable app) waits for the claim capability** (I-TDD-02 → a slice), so members can connect their account to their organisation's record on a phone;
  - phone voting correctness waits for the Election post-district model (E-1…E-4).
  - The status note's roadmap does not list this track; it should, because its gates are exactly P7, I-TDD-02 and E-1…E-4.

## 9. Rulings requested
| # | Ruling | REC |
|---|---|---|
| P2 | archiving: (ii) only when nothing is active; unarchive allowed, forward-only | yes |
| P3 | Worker/Guest may overlap; at most one active voter relationship | yes |
| P4 | organisations merge their own records by recorded decision; after an Identity merge, decide before the next cut-off | yes |
| P5 | standing voter relationship (or wait for NRNA's list practice) | standing |
| P6 | R-b; the organisation's own identifier as row key; omission never archives | yes |
| P7 | PBDIGIT-341 containment before the first capability | yes |
