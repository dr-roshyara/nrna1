*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind). Harness: commissioned 16:06:50Z, received at this save.*

# STEP 1 INVENTORY — THEORY TURN F22

**Topic:** where does the machinery that records authority belong: in a knowledge-governance kernel (a "plane" under every context) or in one application context?
**Mode:** topic review, step 1 only (blind). I did not open anything under `06-theory/` or `RESEARCH-PURPOSE.md`. I read all eight sources in full, one read each, plus the reviewer instructions.
**Independence:** this is process independence only, not personal independence.
**Notation:** [I] marks my own judgment. Anchors are [Snnnn Lx–y].

**Framing note [I]:** none of the eight sources asks the kernel question. Every one of them assumes the bounded-context frame. S0234 explicitly forbids kernel candidates [S0234 L37] and quarantines kernel analyses [L389]. So the evidence for "kernel" placement can only be indirect.

---

## S0062 — Stage-2 Bounded Context Confirmation (2026-08-17)
*Lineage:* it is derived only from the accepted Phase A baseline [L5], with no new measurement. Every judgment it adds is marked `Inferred` [L5]. It is the root of the BC-7 line.

1. **Central claim.** The capability the platform is "actually organized around" is T-1 (authority registration and audit) plus T-3 (role separation). T-1 had no declared context and no CAP id [L60, L69]. The source calls this "the single most important Stage-2 finding" (`Inferred`/medium).
2. **Three-test method:** language, ownership and change-reason. A context can be rules-only, with no code [L14–20]. This is a *context* test, not a kernel test [I].
3. **CBC-1 "Governed Session Orchestration" is promoted to candidate BC-7** [L75–84]:
   - its language is disjoint from all six declared contexts [L79];
   - it has its own aggregate, invariants I-1/2/3/10 and its own persistence [L80];
   - it changes when the governance process changes [L81].
4. **Counter-argument recorded:** the Increment-1 decision deliberately put the mechanism inside CMP-004 under the `ES-001.1` parsimony rule ("no new independent subsystem") [L82]. Placement was a contested decision from the start.
5. **Authority origin is outside the software.** Human PO/ARB acts reach the software only through Governance registration [L133–137]. The thing that sits in the software is a *registration* of authority, not authority itself [I].
6. **Conformist relationship:** every role session conforms to the record (AST-016 refuses prose; I-10) [L144–145]. This makes the mechanism upstream of every working context, a plane-like position [I].
7. **C-3: the authority state has no provenance.** All workflow records are gitignored. "The orchestration candidate's own store contradicts the context's purpose (auditable authority)" [L113].
8. **C-2: the interpreting mechanism can be swapped by an environment variable** (`KOS_MECHANISM_PATH`), so the perimeter is "unhardened" [L112].
9. **C-4: the writer boundary is backed only by convention.** I-5 (only Governance writes authority) is said to be mechanism-backed on 2 of 5 transitions [L114]. *(Later re-attributed: see S0078 #3.)*
10. **BC-4's reading (b):** adversarial review may be a policy *inside* orchestration, because "the producer≠verifier invariant is enforced … by the same record mechanism that holds assignments" [L44]. This is still open (OQ-2) [L178].
11. **OB-2/OQ-5:** who owns role definitions is "undecidable from the baseline" [L125, L181].
12. **Platform vs product:** the whole platform is a Supporting subdomain to the PublicDigit Core, with a one-way guard seam [L164–166].

## S0067 — Architecture Landscape v2 (2026-08-17; ACCEPTED)
*Lineage:* same author and session as S0068 and S0069. It builds on S0062 and ADR-AIP-03, with a fresh re-measurement of AST-015 [L9, L13]. S0067–S0069 count as **one line of support**.

1. **BC-7 is recognized as a bounded context and is "Supporting — never Product Core"** [L19–21, L33]. The decision comes from an ADR plus the PO/ARB [L7, L13], not from a placement test [I].
2. **The Grant is split:** BC-7 owns the grant *record* (schema, registration discipline, status vocabulary). It does not own the grant's *authority content*: what the scope means, who may grant, whether granting was right [L51].
3. **Human Act corrected to "not owned"** (L52):
   - BC-7 owns only the act's registration;
   - "Owning the registration of a thing is not owning the thing."
4. **Owned vs preserved invariants** [L57–69]:
   - BC-7 *owns* I-1, I-3, I-4 and I-10, and the mechanics of the I-2 conjunction;
   - BC-7 *preserves* I-5, I-7 and I-8, which Governance owns.
   This is the key instrument for the third test (can an application context preserve an invariant it does not own?) [I].
5. **Boundary sentence:** "Governance decides whether an act was authorized; BC-7 answers whether it was recorded — and refuses to answer anything else" [L144].
6. **The mechanism's inability to evaluate policy is "not a gap to fix but the boundary, realized as designed"** [L144]. **[I] Circularity risk:** the boundary is read off the mechanism, and the mechanism's behaviour is then cited as confirming the boundary.
7. **Knowledge seam:** knowledge truth is *adjudicated*; BC-7 truth is *folded*. Merging them "would force one authority model onto both" [L162–165]. "Knowledge Engineering holds the meaning; BC-7 holds the movement" [L178]. This is the strongest source argument against putting the recorder in a knowledge-governance kernel [I].
8. **T-f: one-way reference.** The mechanism reads only its own JSON and points into the knowledge estate, never parsing it [L160].
9. **Tension recorded:** BC-7's store violates the knowledge estate's provenance discipline (C-3). The source treats this "different rules" as *evidence the boundary is real* [L180]. **[I]** A rule violation is read as confirming the boundary.
10. **Two senses of "Governance":** the external upstream domain, and the role that registers acts through BC-7 [L115–117]. This is a homonym.
11. **Exclusions** [L91–94]: authority evaluation of any kind, actor-identity attestation, and acceptance decisions.
12. **CONTRADICTED later.** T-a states that `authorized` "never consults the session", tagged `Observed` (re-measured) [L83, L123]. S0072 F-1 falsifies this. S0078 corrects only the three sites in S0071, so **these two S0067 sites remain uncorrected** [I].
13. **Name-fitness:** the context is named after an entity. The root is the Work Item, and grants are "not session concepts" [L107].

## S0068 — Capability Map v2 (2026-08-17; ACCEPTED)
*Lineage:* companion to S0067.

1. **CAP-14 is assigned to BC-7.** T-1 (the authority capability) becomes CAP-14, and "T-1's homelessness [is] closed *in the model*" [L28, L32].
2. **Capability Identity Invariant:** one capability identifier = one semantic owner, never shared across bounded contexts [L12]. **[I]** Taken literally, a plane-wide capability with no single owner cannot be expressed in this map. The invariant rules out the kernel placement by notation.
3. **The ownership is model-only:** the assignment "establishes capability ownership in the architecture model only" [L33].
4. **Growth evidence:** 14 records in "continuous governed use" [L28]. This is a usage measure, not a necessity argument [I].
5. **CAP-11 (adversarial review) stays under BC-4**, and its home is open [L26, L33].
6. **CAP-13 (platform self-governance) placement is challenged and carried open** [L27].

## S0069 — Context Map v2 (2026-08-17; ACCEPTED)
*Lineage:* companion to S0067. It adds no new evidence.

1. **R-3: BC-7 is an Open Host Service** (fold/identity/authorized) to BC-2/3/4/5 role sessions, which are Conformist [L33–37, L63]. **[I]** Structurally this is a supplier under every working context: the plane-like position.
2. **R-1 policy flow (Customer–Supplier) and R-2 operating flow (Published Language) are separated** [L18–23, L61–62].
3. **R-5: mutual reference with the knowledge estate across a Published-Language seam.** There is no shared model and no shared lifecycle discipline [L65].
4. **R-6: BC-7 and BC-6 are Separate Ways,** with disjoint stores [L66].
5. **R-7: the BC-7 to BC-4 line is unresolved (dashed)** [L67].
6. **The mechanism is physically hosted in CMP-004** ("placement, not boundary") [L30–31, L64]. Model placement and physical placement are kept apart.
7. **C-4 is restated as "convention-backed on 3/5"** [L23, L62]. *(Later re-attributed: see S0078 #3.)*

## S0071 — BC-7 Domain Model Proposal (2026-08-17)
*Lineage:* built on the accepted S0067–S0069 set plus new measurement of records and source [L11, L31–48]. Same lineage as S0067, with a tactical layer added.

1. **Mutation ownership is a fold projection only:** 0 stored occurrences [L47–50, L71]. This is a structural fact about how authority state exists [I].
2. **Work Item is the aggregate root.** All of I-1…I-4 are internal to it, and no cross-work-item data is needed [L82–92]. **[I]** No invariant spans work items, so nothing here requires a shared plane.
3. **Human Act as root is rejected:** it has no identity or lifecycle in BC-7. Promoting it "would drag authority semantics into a context that provably cannot evaluate authority" [L117–126]. RA-3: "importance is not ownership" [L218].
4. **One aggregate or two (§4.5):** no positive invariant spans the lifecycle part and the authority part [L132–137]. One root is kept because of the *negative* invariant I-4 and the single write boundary [L139–145]. RA-4 (split) is "the strongest rejected option" [L219]. OQ-9 is open [L353].
5. **Domain vs governance policy:**
   - "BC-7 can refuse a *malformed* act. It can never refuse an *unauthorized* one" [L293];
   - all of DP-1…DP-7 are record-consistency rules [L282].
   **[I]** Trivial-case concern: the authority question goes unanswered by design, so the mechanism's "authority" content is empty.
6. **T-2: a reference is never dereferenced** [L241, L320]. This is the guard against BC-7 breaking knowledge-estate invariants.
7. **Rule: no entitlement predicate may exist** ("may X do Y?") [L309].
8. **Seam with the attribution model:** "BC-7 records the claim; the attribution model adjudicates it" [L330].
9. **Census** [L41–46]:
   - 117 transitions and 41 grants, all AUTHORIZED;
   - CONTINUATION and FAIL have never been used (OQ-5);
   - COMPLETE carries a human act in only 3 of 16 cases.
10. **OQ-6:** is Grant revocable at all? None of the 41 grants ever changed status [L187, L350]. **[I]** Withdrawal of authority is an AGM contraction that the recorder may not support.
11. **CONTRADICTED.** "never consults the session" at L135 and L307 is falsified by S0072.
12. **Overreached by S0078.** T-1 says no derived projection is "ever … accepted as input" [L240, L208, L279].
13. **"Tactical proof of the boundary"** [L282]. Same circularity risk as S0067 #6 [I].

## S0072 — Verification Report (2026-08-17)
*Lineage:* independent in process. Its eligibility is `Unknown` [L15]. It re-derives the mechanism facts from source, but **does not verify** the strategic placement: by grant scope, BC-7 recognition and CAP-14 were fixed inputs [L22].

1. **F-1:** `authorized` *does* consult the session, as a precondition: it refuses on an unknown session [L32].
2. **C-1:** F-1 undercuts §4.5's claim that the two parts never need to be consistent [L33].
3. **C-2:** the "accepted as input" half of T-1 is not demonstrated. "Absence in practice ≠ refusal by contract" [L34].
4. **C-3:** the `recordedBy: architecture` instance is an observed instance of a preserved invariant, under-weighted by the proposal [L35].
5. **Eleven claims confirmed from source** [L43–53]:
   - START has 0 grant references;
   - only the current owner may hand off;
   - HANDOFF is atomic;
   - T-2 holds (emptiness checks only);
   - T-3 holds (seq contiguous; no splice, pop or unset).
6. **Boundary verdict:** "no mechanism in the model grants, evaluates or blocks authority" [L84]. "Correctly separated" [L70–72].
7. **"Grant" is the one term doing double duty across contexts** [L80]. This is a homonym.
8. **Source defect [I].** It reports "all 13 live workflow records / 121 transitions" [L26, L36], but S0071 earlier the same day measured 15 records [L39], and S0078 measured 15 [S0078 L58]. A record count falling and then rising cannot be checked, because the store is gitignored (C-3).
9. **Undercut by S0234 E-1 [I].** S0072 accepts seq contiguity as the evidence for T-3 (append-only). S0234 shows that a dense seq hides lost writes.

## S0078 — Domain Model Refinement (2026-08-17; ACCEPTED "as refined")
*Lineage:*
- it builds on S0071 and S0072 and adds source and records;
- the refiner disclosed prior exposure to the ARB's framing [L38];
- the correction pen is the same process as Verification #2 [L21].

1. **Authority is split into three parts** [L92–98]:
   - an **answerability precondition** (who may ask);
   - a **verdict function** (grants and scope only);
   - a **consistency invariant**, of which "measured: none exists".
2. **I-5 is mis-scoped** [L212–222]:
   - I-5 governs the *Authority part*, the grant register;
   - there it is enforced totally: 47/47 grants by governance, refused at the boundary [L206, L244];
   - measuring I-5 against lifecycle transitions is "the conflation I-4 forbids".
3. **CONTRADICTS S0062 C-4 and S0067/S0069** [L208]. The recorder checks on COMPLETE and CONTINUATION belong to G-1 and Inv E, not to I-5. The earlier "I-5 mechanism-backed on 2 of 5" claim is a misattribution.
4. **D.5: no bounded context owns "the ability to produce a lifecycle fact."** That ability is "a property of *access to the mechanism* — an operational and governance concern, not a domain-model one" [L222]. **[I]** This is the single most important line for the topic: the integrity of authority recording rests on *access control*, which lies outside every context in the model.
5. **T-1 is restated in parts** [L157–163, L169]:
   - T-1a: the submitter never supplies the state its transition is judged against;
   - T-1b: a derived value is inert (non-consultation);
   - T-1c: writers have an obligation not to store derived projections;
   - there is no key whitelist [L142–146].
6. **OQ-12:** should every invariant carry an enforcement class (refused, overwritten, not-consulted, writer obligation)? [L314]. **[I]** This bears directly on what "enforced" means in S0234's asymmetry claim.
7. **OQ-10: the referent of `recordedBy` shifts.** On a START it names the origin (the human), although Governance recorded it. "Answered wrongly by the field on 37 of 37 STARTs" [L258]. **[I]** The authority record misattributes the registering actor.
8. **N-1:** `identity` reads both parts in one call, and the linkage is work-item-scoped [L266]. OQ-13 [L315].
9. **Source defect.** The T-1 replacement text [L169] still says "no non-canonical key appears anywhere in the estate". The corrected §2 says **20 instances** [L67]. The count basis also differs: 0/126 [L169] vs 0/133 [L67].
10. **Decisions unchanged:** "BC-7 owns workflow lifecycle …; Governance owns authorization policy and authority meaning" [L277].

## S0234 — AIP Current Architecture Reconstruction, Stage 3 (2026-08-21)
*Parts read:* everything, with weight on:
- erratum E-1/E-2 (L13–23);
- §0 (L27–66);
- §3.2 (L143–145);
- §4 (L161–181);
- §6 (L211–235);
- §7 (L239–272);
- §8 (L276–305);
- §9.3 (L337–341);
- §13 (L413–447);
- §16–17 (L468–492).

*Lineage:* a different corpus (AIP documents plus the MIGRATION-PLAN), with a live fold measurement. It **inherits** the BC-7 and CAP placements from KOS-ARCH-BASELINE-002 as "T4·A" [L118, L197]. Placement is therefore not an independent line.

1. **Invariant asymmetry, the headline** [L270–272, L488]:
   - "Every invariant BC-7 owns is mechanically enforced; almost every invariant outside BC-7 is declared only";
   - "the platform mechanically protects orchestration, and protects knowledge/verification/communication only by declaration."
   **[I]** This is the strongest source fact for a "plane". The only executable assurance anywhere sits in this one context.
2. **Internal contradiction [I].** The §7.3 table lists **I-4 and I-10** as "declared-only, NOT mechanically enforced" [L264–266]. Yet S0067 L64 and L69 have BC-7 *owning* both, with I-10 realized by AST-016's refusal of prose. That contradicts S0234's own claim that "every invariant BC-7 owns is mechanically enforced" [L272].
3. **"AST-015/016 are advisory"** [L339]. Violations are visible and adjudicable, not physically prevented. The same document calls I-1/2/3/G-1/G-2 "mechanically enforced" [L249–253]. **[I]** "Enforced" here means "refused if you call the mechanism", not "impossible to bypass". The homonym is unresolved (see S0078 OQ-12).
4. **E-1: dense seq is not completeness.** 23 of 30 concurrent-append trials silently lost a transition, because the read-modify-write is not atomic [L19, L282]. This contradicts treating contiguity as the evidence for append-only integrity (S0072 L53).
5. **E-2: the single writer is not structural.** The `KOS_MECHANISM_PATH` path in `session-resolve.php` executes an environment-named program and hands it the authority directory [L20, L284, L341]. This sharpens S0062 C-2.
6. **Durability inversion:** the most authoritative records live in the only gitignored directory. "An aggregate cannot depend on accidental reconstruction" [L299–301]. The migration has not been executed [L305].
7. **D1/D2 contradiction** [L228–235]. The runtime grant says B′ selected; the tracked registration review says NOT SELECTED. I-10 ("the record outranks prose") would decide for the runtime record, but the source leaves both standing. **[I]** This is a live test case of whether record-over-prose may serve as a kernel law.
8. **C-5 separation attestation is called a "cross-context control-plane assurance capability — NOT a bounded context"** [L126]. This is the only "control-plane" category in these sources [I]. Its source tier is T4·G (proposal).
9. **CONTRADICTS S0067 L53.** "BC-7 owns the executable lane-role model — roles like REGISTER, HANDOFF, START" [L145]. Those are transition types, not roles, and S0067 says role-model ownership was deferred [I].
10. **Three senses of "role" recorded** [L179–181]. Separately, the six-role model "must not be used as proof that a capability or bounded context exists" [L141].
11. **Knowledge-concern vs platform-concern split is UNDECIDED** [L468–480, U-6 L505]. BC-1 Knowledge Governance is NOT BUILT [L191, L442]. **[I]** A knowledge-governance kernel has no executable realization to host anything today.
12. **"Only 2 of 218 transitions carry any date"** [L283]. The authority record has no time dimension (S0071 L197: "BC-7 holds no clocks"). **[I]** Temporal validity and supersession of authority cannot be expressed in the record.

---

## Cross-source changes over time (short)
- **Placement path:** CBC-1 candidate inside CMP-004 [S0062] → BC-7 recognized [S0067] → domain model [S0071] → accepted as refined [S0078] → treated as a T4·A given [S0234].
- **I-5 attribution:** "2/5 mechanism-backed" [S0062, S0067, S0069] → "total at the grant register; the 2/5 checks are G-1 and Inv E" [S0078].
- **Counts:** 8 → 14 → 15 → 13(?) → 15 → 18 records; 117 → 121 → 126 → 133 → 218 transitions. Not auditable (C-3).

## Arguments for and against each placement

| Placement | Argument (source) | What it assumes [I] |
|---|---|---|
| **Application context (BC-7)** | It has its own disjoint language, aggregate, invariants and change-reason [S0062 L79–81; S0067 L42–53] | The three-test DDD frame decides placement; passing the context tests excludes the kernel. This does not follow: a kernel element could pass them too |
| Application context | Its truth is folded, while knowledge truth is adjudicated; merging forces one authority model [S0067 L162–165, L178] | A knowledge-governance kernel must impose adjudication on everything it hosts. Yet a kernel could host several truth disciplines |
| Application context | It references the estate one way and never dereferences (T-2) [S0067 L160; S0071 L241; S0072 L52] | Not breaking knowledge invariants means not reading knowledge. Provenance (C-3) is still broken |
| Application context | No invariant spans work items, so per-file isolation suffices [S0071 L88–92] | "Plane" status needs cross-instance invariants. A plane might instead be needed because *every context depends on it* |
| Application context | It is declared Supporting and the product is Core [S0067 L21] | The subdomain class (strategic value) settles the architectural layer. It does not |
| Application context | Capability Identity Invariant: one id, one owner [S0068 L12] | The notation is complete. A plane-wide capability cannot be written in it, so the conclusion is fixed in advance |
| Application context (counter) | Parsimony: originally no new subsystem inside CMP-004 [S0062 L82] | Fewer subsystems is better; this was a decision, not evidence |
| **Kernel / plane** | It is the platform's centre of gravity (T-1 + T-3) [S0062 L69] | Centrality of use implies foundational status. This mixes usage with necessity |
| Kernel / plane | It is an Open Host Service to all role sessions, which are Conformist [S0069 L33–37, L63] | Being upstream of every context means being underneath them. An upstream supplier can still be a context |
| Kernel / plane | Only BC-7's invariants are mechanically enforced [S0234 L272] | Enforcement location shows where the kernel should be. The claim is internally inconsistent [S0234 L264–266] and "enforced" means "advisory" [L339] |
| Kernel / plane | The ability to produce a lifecycle fact is owned by no context; it is "access to the mechanism" [S0078 L222] | An integrity property that no context owns belongs to an underlying layer. It could equally belong to operations or governance, outside the model |
| Kernel / plane | "Control-plane assurance capability, NOT a bounded context" (C-5) [S0234 L126] | The plane category exists in the corpus. It appears only as a T4·G proposal, and it is about separation attestation, not recording |
| **Neither / against both** | Authority originates outside the software; the recorder holds only registration [S0062 L133; S0067 L51–52; S0071 L293] | "Machinery that records authority" may be misnamed. What is recorded is *occurrence plus a reference*; authority itself is in neither placement |
| Against current fitness for a kernel | No provenance (C-3), lost writes (E-1), a swappable interpreter or second writer (C-2/E-2), the shifting `recordedBy` referent (OQ-10), no time dimension [S0062 L112–113; S0234 L19–20, L283; S0078 L258] | Kernel invariants include integrity, provenance and attribution. These facts show non-satisfaction *now*; they say nothing about placement as such |
| Against the kernel being knowledge-governance specifically | BC-1 Knowledge Governance is not built; the knowledge/platform split is undecided [S0234 L191, L505] | A kernel needs an existing host. "Not yet built" ≠ disproved |

**Three-test status in these sources [I]:**
- **Necessity:** only *usage* is shown (growth, centrality). Necessity is not shown.
- **Kernel-must-provide:** no source tests it. The question is excluded by frame [S0234 L37].
- **Application-can-provide-without-breaking-kernel-invariants:** partially supported for *meaning* (T-2), and contradicted for *provenance and integrity* (C-3, E-1, E-2). Which invariants a kernel would carry is not stated anywhere in these sources.
