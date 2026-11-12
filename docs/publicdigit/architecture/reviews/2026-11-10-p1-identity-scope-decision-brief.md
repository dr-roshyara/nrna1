# P1: identity scope and when a canonical Person is created (Principal decision brief)

| | |
|---|---|
| **Status** | **Decided: R2b accepted by the Principal, 2026-11-10 (D-113).** This brief is the supporting analysis; written by Claude, **not independent** |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit`; `architecture/reviews/` |
| **Subject** | P1 (= P-I1 = K4 = BR-10), raised by IR-OPR-01 §7 and §10 |
| **Sources read** | XD-3 act (D-75), XD-3a, XD-3b (incl. K4, §2 condition, g15 D6 finding), ratified Membership (INV-M, INV-1, CR-01 with D-77), D-100 … D-112, BR-10 and its amendment note, INV-AFF-1, Identity dossier P-I1 and P-I2, Identity addendum, people-register design rev. 3 §10, IR-OPR-01 §7 |
| **Labels** | [AD] decision (RAT · ARB · ruling · WD) · [BF] fact · [AC] architectural conclusion · [TD] · [TU] · [OQ] · [LQ] legal question |

## 1. The business question, in plain language

NRNA imports 5,000 members. Most have never used PublicDigit, and many never will.
- **Does PublicDigit's central identity record each of them now?**
- **Or does it record only people who come to PublicDigit themselves**, while each organisation simply knows its own people under its own references until a person claims their record?

## 2. What the accepted and ratified architecture already requires

| Requirement | Source | Status |
|---|---|---|
| One canonical Person, owned by Identity, holding only a bare `PersonId` | XD-3 §2 item 1; XD-3b §2 | **ARB (D-75)** |
| **Each organisation receives a different opaque `PersonRef`**; only Identity resolves it | XD-3 §2 items 2–3 | **ARB** |
| No matching by e-mail or name; merges only by Identity's explicit act | XD-3 §2 item 6; C-5 | **ARB** |
| People without an Account must be supported | XD-3b (Principal, 2026-11-01: "yes") | **Principal answer, accepted with D-75** |
| **Membership: one Membership per (Person, Organisation); a period's holder never changes** (identity criterion); C2 accepts `personRefs[]` | INV-M, INV-1; D-77 | **RAT** |
| The central index of a person's organisations is minimised and data-protection reviewed (C-4); INV-AFF-1's "no index" not adopted | XD-3a; D-101 | **ARB / ruling** |
| Organisations own their people records, profiles and relationships | D-101, D-111 | **Ruling / WD** |
| A voter without an Account cannot vote; they must register and authenticate | BR-7 | **Approved rule** |

**What remains open:**
- **K4**: "Not decided" (XD-3 act §3).
- **The mechanism L1 / L2.**
- **BR-10** (a PO direction): "Identity creates or resolves the Person" at import, from the **Principal's import clarification of 2026-11-01** (XD-3b §1).

**XD-3b's own §2 condition** says a central registration of a person without an Account **must not record which organisation created them**. K4 exists because eager creation under L1 records exactly that.

## 3. The four options, precisely defined

| | R1: eager (BR-10 as written) | R1c: eager, no discovery (IR-OPR-01's option 3) | R2a: lazy with placeholder | **R2b: reference at import, Person at claim** (design rev. 3 §10 option (d)) |
|---|---|---|---|---|
| **At import** | Identity creates a Person **and** issues the organisation's `PersonRef`, recording the mapping | Identity creates a Person and issues the `PersonRef`, but **records nothing usable for discovery** | The organisation uses a local placeholder; no `PersonRef` | **Identity issues an opaque `PersonRef` bound to no Person.** No Person is created |
| **Central data for a never-registered person** | Bare `PersonId` + (organisation, `PersonRef`) mapping | Bare `PersonId`; the mapping only if L2 (computed, not stored) | Nothing | **Nothing linking to a person.** At most the fact that a reference was issued to that organisation; with a signed reference, not even that (§5) |
| **At claim** (an Account redeems the organisation's code) | The claim names the imported Person; **the Account's own Person must then be merged** (g15 D6) | Same | Person created or found; **every Membership and Election fact under the placeholder must be re-keyed** | **The reference is bound to the Account's Person. A binding, not a merge** |
| **Canonical Person represents** | Every human an organisation knows | Same | Registered humans | **Registered humans** |

**[AC] Two precisions the reviewer did not draw:**
1. **R1c is realisable only under L2,** or by deferring the mapping until claim. Under L1, issuing the `PersonRef` *is* storing the mapping. **L2's feasibility is not established** (evaluation 2026-11-01; dossier P-I2). So R1c depends on an open technical choice.
2. **R1c creates a Person that nothing can use before the claim.** A bare `PersonId` with no mapping serves no Identity function until the claim. **So eager creation costs data minimisation and buys nothing.** That is the decisive difference from R2b.

## 4. Scenarios

**Key:** P = canonical Person exists · A = Account exists · R = the organisation holds a `PersonRef`.

| # | Scenario | R1 | R1c | R2a | **R2b** |
|---|---|---|---|---|---|
| 1 | Import of 5,000, none registered | P ✓, A ✗, R ✓; **5,000 central Persons with org mapping** | P ✓, A ✗, R ✓; 5,000 Persons, no mapping (L2) | P ✗, A ✗, **R ✗**: no Membership possible (INV-M needs the reference) | **P ✗, A ✗, R ✓; Membership and Election work** |
| 2 | One imported person registers months later | Registration creates a second Person → claim → **merge** | Same merge | Person created; **re-key** Membership and Election | **Registration creates the Person; claim binds R. No merge** |
| 3 | Two organisations import the same human | Two Persons (no matching) → **merge later** | Two Persons → merge | Two placeholders | **Two references, no Person.** If the human claims both, **both bind to one Person: no merge** |
| 4 | Has an Account, claimed neither record | Three Persons for one human until merges | Same | One Person; two placeholders | **One Person; two unbound references** |
| 5 | Claims one organisation's record, not the other | One binding plus pending merges | Same | Re-key one | **One bound, one unbound; neither organisation learns about the other** |
| 6 | Two Accounts later found to be one human | Identity merge (C-5) | Same | Same | **Same** (duplicate *registrations* remain possible in every option) |
| 7 | Membership exists before registration | ✓ (keyed by R) | ✓ | **✗, or re-key later (breaks INV-1)** | **✓ (keyed by R; never re-keyed)** |
| 8 | Election imports a never-registered voter | Voter relationship keyed by R; cannot vote until registered (BR-7) | Same | No R: voter relationship only local | **Keyed by R; must register and claim to vote (BR-7)** |
| 9 | Never registers | A central Person exists for ever (retention open, XD-PROV) | Same | Nothing central | **Nothing central; only the organisation's record** |
| 10 | Asks for deletion or correction | Organisation's data **plus** a central Person and mapping (two controllers' questions) | Organisation's data plus a central Person | Organisation's data | **Organisation's data only, if never registered;** after claim, Identity's Person and binding too |

**In every option:**
- **Membership, Election and Finance refer only to the organisation's `PersonRef`.**
- **No other organisation can see anything** (XD-3 §2 items 3–5).

## 5. Cross-context consistency

| Test | R1 | R1c | R2a | **R2b** |
|---|---|---|---|---|
| XD-3 §2 (one Person; a `PersonRef` per organisation; only Identity resolves) | ✓ | ✓ | **✗ no `PersonRef` before claim** | **✓.** One Person per *registered* human; the reference is issued and later resolved by Identity only. **[TU] confirm that a reference may exist before it resolves to a Person.** XD-3 does not forbid it, but does not state it either |
| XD-3b §2 condition (no record of the creating organisation for non-registrants) | **✗** (K4 conflict) | ✓ only under L2 | ✓ | **✓.** No Person; a **signed reference** (Identity signs organisation + random value) need not even be stored until claim |
| Ratified Membership INV-M, INV-1, C2 (D-77) | ✓ | ✓ | **✗ (re-keying)** | **✓ (stable reference from import)** |
| D-110 (one registration, many organisations) | ✓ | ✓ | ✓ | **✓.** Claims bind references to the one Account's Person |
| D-111 / D-102 (Identity owns issuance, resolution, claims) | ✓ | ✓ | partly | **✓** |
| C-4 (minimised index) | Index includes non-registrants | Index only after claim (L2) | Index only after claim | **Index only after claim**, and only under L1 |
| BR-10 / the 2026-11-01 import clarification | ✓ | ✓ | ✗ | **✗: "Identity creates … a Person at import" would be superseded** (the reference part stays) |
| g15 D6 (every post-registration claim triggers a merge) | **Hit** | **Hit** | Not hit | **Not hit** (binding ≠ merge, which XD-3b itself asks to separate) |

## 6. Privacy and legal implications (not legal advice)

- **[LQ] Membership of a political party can reveal political opinions, a special category under GDPR Art. 9.**
  - A **central** record linking a person to a party is therefore sensitive. **A central record about people who never chose PublicDigit is more so.**
  - Legal basis, controllers and processors are open (OQ-3a-1). **This brief declares nothing compliant or non-compliant.**
- **Data minimisation [AC]:**
  - **R1 holds the most centrally:** a Person and a mapping for every imported human.
  - **R1c** holds a Person per imported human.
  - **R2a and R2b hold nothing central before a claim.**
  - **After a claim**, every option holds a Person and (under L1) a minimised binding, which C-4 review covers.
- **Retention:**
  - **under R1 and R1c, central Persons of never-registered people need a retention rule** (XD-PROV, open);
  - **under R2b, nothing central exists to retain.**
- **Safeguards in every option:**
  - **opaque references, different per organisation;**
  - **no cross-organisation queries;**
  - claim codes that disclose nothing.

## 7. The reviewer's arguments

**Agreed:**
- R2a's re-keying conflicts with ratified Membership.
- The real question is what identity means.
- Option (c) has no business benefit over keeping identity minimal for non-registrants.

**Disagreed (two points):**
1. **"Why not (2) or (4): they solve a privacy problem at a much higher price — re-keying append-only Membership facts."** That holds for a placeholder (R2a). **It does not hold for R2b:** the reference exists from import, so nothing is re-keyed. The review grouped (d) with the placeholder options.
2. **"There is no business benefit to discovery for someone who has no Account."** That's correct, and it applies equally to *creating the Person*. **A bare `PersonId` with nothing attached serves no function until the claim. R1c therefore keeps the cost of eager creation without its stated purpose.**

**The reviewer's assumption 3** (Membership and Election must refer to an imported person before any claim) **is met by R2b.**

## 8. Recommendation [AC]

**R2b: Identity issues the organisation's opaque `PersonRef` at import, bound to no Person. The canonical Person represents registered humans only. Claiming binds the reference to the Account's Person.**

**Reasons:**
- **Membership and Election work from day one with stable references**; ratified Membership is untouched.
- **No central record exists for people who never register** (K4 resolved without L2).
- **No merge is needed when a registered person claims records, or when two organisations imported the same human.**
- **Simplest for a solo developer:**
  - no merge tool needed for imports;
  - **merges remain only for duplicate registrations** (C-5), which every option needs.

**Risks and how they are carried:**
- **[TU]** Confirm that XD-3 permits a reference that resolves to no Person until claim (§5). If the Principal reads XD-3 §2 item 2 as requiring a Person behind every reference, R2b needs that clarification recorded.
- **[TU]** The binding rule: **one organisation's two references must not both bind to one Person without review.** That is an organisation-local duplicate (P4), and Identity refuses or flags the second binding.
- **[TU]** The reference format (stored or signed) is a mechanism choice for Identity's tactical design.

## 9. Risks and consequences of each option

| Option | Main risk | Consequence if chosen |
|---|---|---|
| R1 | **Central record of non-registrants with their organisations (K4, Art. 9)**; merges for every claim | Data-protection review of the mapping; a merge tool before imports grow |
| R1c | **Depends on L2 (unproven)**; merges remain; central Persons without purpose | L2 decided first; merge tool; retention rule |
| R2a | **Breaks ratified Membership (re-keying)** | A Membership Change Request |
| R2b | **An XD-3 clarification may be needed**; binding rule needed | Supersedes BR-10's "creates a Person at import"; I-TDD-02 designs the binding |

## 10. Consequences for the people-register design and later steps

| Area | Under R2b |
|---|---|
| `PersonRef` slot | Filled at import (no longer "open slot until P-I1"); never changed except by an Identity binding notice |
| Claim lifecycle | Organisation issues the code for its reference; Identity binds on redemption (claim issuance status: IR-OPR-01 F-15) |
| Duplicates | Imports create none centrally. **An organisation's own duplicates are P4.** After a duplicate-registration merge, two organisations' references re-point to the surviving Person (no organisation re-keys) |
| Import | Creates the person record with an issued reference (P6 still decides the partial outcome) |
| Membership, Election, Finance | Keyed by the reference from import; unchanged by claims and merges |
| Archiving, retention | Nothing central to retain for never-registered people (XD-PROV narrower) |

**Strategic consequences:** the meaning of canonical Person; BR-10 and the import clarification superseded in part.
**Tactical consequences:** the binding operation; the reference issuance mechanism; the I-13 correction (IR-OPR-01 F-6).

**Dependencies on P1 for P2 … P7:**

| Decision | Dependency on P1 |
|---|---|
| P4 | The binding rule |
| P6 | Import creates a reference |
| P7 | The legacy import must stop matching by e-mail under every option |
| P2, P3, P5 | Independent of P1 |

## 11. The decision

**Proposition:** *"PublicDigit's canonical Person represents humans who have registered. When an organisation records a person who has not registered, Identity issues that organisation's opaque `PersonRef` without creating a Person; when the person registers and claims the record, Identity binds the reference to their Person. Binding is not a merge."*

**Acceptance would mean:**
- BR-10 and the 2026-11-01 import clarification are superseded **as to creating a Person at import** (the reference is still issued at import);
- **K4 is answered** (no central record of the creating organisation for non-registrants);
- **P-I1 is decided.**

**Acceptance would not mean:**
- L1 or L2 chosen (P-I2 stays open);
- any merge policy beyond "binding is not a merge";
- any change to Membership, Election or Finance;
- any legal conclusion.

**Records affected:** a new decision record (next number); an append-only note on BR-10; XD-3b K4 noted as answered; the people-register design (by its author) and the Identity dossier (P-I1) updated afterwards. **No ratified text is edited.**

**Alternatives you may choose instead:** R1 (BR-10 as written) or R1c (the reviewer's option 3). R2a is not recommended by anyone.

---

## 12. Source validation and challenge (2026-11-10, after the Principal DDD Architect's review)

### 12.1 Exact wording

| Source | Wording | Bearing on R2b |
|---|---|---|
| XD-3 act §2 item 1 (ARB) | "One canonical Person exists, owned by Identity, holding only the minimum needed for **identity and authentication** (a bare `PersonId`)" | **Supports.** A Person exists for identity and authentication; someone who never registers never authenticates |
| XD-3 act §2 item 2 (ARB) | "Each organisation receives a different opaque `PersonRef` **for the same human**" | **Supports.** A reference is per organisation and per *human*; nothing requires a Person behind it at issue |
| XD-3 act §2 item 3 (ARB) | "Only Identity can resolve one organisation's `PersonRef` **to the canonical Person** or to another organisation's reference" | **Compatible.** An unbound reference resolves to nothing until claim; only Identity resolves |
| Ratified Membership (via XD-3 §2 item 2) | "opaque `PersonRef`, unique per organisation"; INV-M, INV-1 | **Supports** (stable from import) |
| **XD-3b §2a** (PROPOSED, **accepted with D-75**) | "Linking an account to an existing Person uses an organisation-issued one-time claim code or invitation **tied to the `PersonId`**" | **Contradicts the letter of R2b:** no `PersonId` exists at import. **Needs a clarification: the code is tied to the organisation's `PersonRef`** |
| XD-3b §1 (import clarification, Principal 2026-11-01) | "Identity creates or resolves a Person and issues that organisation's `PersonRef`"; "the account is linked to the existing Person" | **Superseded in part** by R2b (Person creation at import) |
| XD-3b §1 | separate "**claim-binding** (the account proves possession of the organisation's code) from a **duplicate merge**" | **Supports:** R2b makes this separation structural |
| BR-10 + amendment note | PO direction; "in practice BR-10 means 'creates'" | **Superseded as to Person creation**; reference issuance at import stays |
| D-110, D-111 (WD) | Identity owns "`PersonRef` issuance and resolution … claims" | **Supports** |
| Addendum (P) | "organisations may never re-bind (INV-ID-6)"; "authority evidence for officers issuing claims (P)" | **Supports** the claim rules below |

**[AC] Conclusion:**
- **R2b is consistent with the ARB and RAT texts.**
- **It changes two accepted-but-subordinate statements:**
  - the import clarification (Person at import);
  - XD-3b §2a ("tied to the `PersonId`").
- **Both changes belong in the P1 ruling, expressly.** Nothing ratified is edited.

### 12.2 Challenges answered

**A. Can a reference exist before it resolves?** Yes, under the wording (§12.1). **The business meaning of a reference:** "this organisation's handle for a human it knows". Whether that human has a Person is Identity's knowledge, never the organisation's concern.

**B. Issuance without a central person record** [TD, mechanism not chosen]:

| | Stored issuance record (organisation, reference, unbound) | Verifiable signed reference |
|---|---|---|
| What is central before claim | That an organisation was issued a reference; **no person data, no cross-organisation link** | Nothing |
| Validation at claim | Lookup | Signature check |
| Revocation and erasure | Delete the record | Key rotation or deny-list |
| Fit with L1 / L2 (P-I2) | Either | Either |

**Both meet R2b's business meaning; the choice is tactical** (P-I2 and I-TDD-02).

**C. A valid claim** (business invariant, not a mechanism):
1. **The organisation initiates.** It issues a **one-time claim code** for one of its records, through its own contact with the person, by an officer with authority to do so (authority evidence is Governance's, addendum P).
2. **The claimant proves possession of that code** (XD-3b §1), **plus a verified login address** (D-102). **Possession of a `PersonRef` proves nothing:** the reference is opaque and never given to the person.
3. **Identity binds** the reference to the claimant's Person. **Binding is not a merge.**
4. **The organisation is told** the record is claimed (`ClaimRedeemed`, mechanism UNKNOWN, IR-OPR-01 F-10), **and nothing about other organisations.**
5. **Disputed claims:** the organisation reports a wrong binding to Identity. **Only Identity unbinds** (organisations never re-bind, INV-ID-6). The organisation may issue a new code. **History keeps both.**
6. **Unused codes** expire, and are revocable by the organisation.

**D. Duplicates within one organisation (invariant):**
- **At most one of an organisation's references may be bound to one Person.** A second binding of the same organisation's reference to the same Person is **refused and reported to that organisation** as a probable local duplicate (P4 decides what the organisation does next).
- **References of different organisations bound to one Person are legitimate** and never disclosed to each other.
- **Duplicate registrations** (two Persons, one human) are a separate case: an Identity merge (C-5).

**E. Binding versus merge:**

| | Binding | Merge |
|---|---|---|
| What happens | An unbound reference gets its Person (or a wrong binding is undone) | Two Persons are found to be one human (C-5) |
| What changes | One reference's resolution | Every reference bound to the absorbed Person re-points to the survivor |
| What never changes | Any organisation's reference | Any organisation's reference |
| Downstream re-keying | None | **None** (Membership, Election and Finance keep their references) |

**F. Central data:**
- **Before registration:** nothing about the person (B).
- **After registration:** the Person (bare id), the Account and, per claim, the binding. Under L1 the bindings form the **minimised index** that C-4 review covers.
- **Organisation-local profiles and relationships never move to Identity.**
- **[LQ]** Legal basis, controllership and Art. 9 questions stay separate.

### 12.3 Fair re-evaluation

| Criterion | R1 | R1c | R2a | **R2b** |
|---|---|---|---|---|
| Ratified Membership | ✓ | ✓ | ✗ | ✓ |
| Identity (ARB text) | ✓ | ✓ | ✗ (no reference) | ✓ |
| Accepted subordinate texts (import clarification, §2a) | ✓ | ✓ | ✗ | **✗: two clarifications needed** |
| Organisational independence | ✓ | ✓ | ✓ | ✓ |
| Central data minimisation | ✗ | partial | ✓ | **✓** |
| Lifecycle complexity | merge per claim | merge per claim; needs L2 | re-keying | **binding only** |
| Claim and duplicate handling | merge review | merge review | re-key | **binding rules (C, D)** |
| Solo-developer burden | merge tool early | merge tool + L2 | highest | **lowest** |

**[AC] R2b remains the best option.** Its only cost is the two explicit clarifications.

### 12.4 Tactical subjects unlocked by acceptance

- the reference states (unbound, bound, unbound again after a dispute);
- the issuance lifecycle (mechanism per P-I2);
- the claim lifecycle and the code's lifecycle;
- the binding invariants (D);
- merge versus binding (E);
- the claim outcome contract to the issuing organisation;
- the failure cases (expired code, wrong claimant, double claim);
- the temporal behaviour (a binding has a `recordedAt`; references never change);
- the isolation invariants.

**All of these go in I-TDD-02** (written by the other session).
