*Saved verbatim 2026-10-19 from the reviewer's step-1 hand-back (blind). Harness: commissioned 16:59:29Z, received at this save.*

# STEP 1 INVENTORY — C12

*Blind, transition-review mode. Read: REVIEWER-INSTRUCTIONS.md, then S0066–S0070, each whole. Nothing under 06-theory/ was opened, and RESEARCH-PURPOSE.md was not opened. Dates come from `git log` (first commit): S0066 2026-08-28 (`f24edf316`); S0067–S0069 2026-08-17 (`f278dc54`, with ACCEPTED banners added by `a265e1b7`); S0070 2026-08-17 (`b8769fa7`). [I] marks my own judgments. Independence here means process independence (a separate session with no access to the Professor's work), not personal independence.*

## S0066 — Developer guide: setting up AI agents with KnowledgeOS (918 lines; chat-answer origin; first commit 2026-08-28, after S0067–S0070)
1. Central law: "Linked, not copied". The agent harness may reference KnowledgeOS but must not reproduce it, because a copy "creates a competing source of truth". [S0066 L103–146; restated L5, L916]
2. Primitive distinction between agent execution (how the agent operates: process, permissions, stop points, verification) and engineering knowledge (architecture, DDD, governance, ADRs, decisions). They have two different owners: the harness and KnowledgeOS. [L53–83]
3. A one-way dependency direction: harness → KnowledgeOS → Project Engineering Truth. "Never reverse this relationship." [L85–99] This is a direction law of the kernel/application kind: KnowledgeOS is the substrate and agents are its consumers. [I]
4. "One project engineering truth. Multiple AI execution harnesses." The harnesses are peer adapters (ports-and-adapters shape), and neither may modify the other. [L47–49, L553–572]
5. A linear authority hierarchy: KnowledgeOS/Governance → Architecture → DDD → ADRs → Approved Design → Implementation → Verification. Its stated purpose is to block "implementation treated as architectural authority". [L415–467] [I] This anti-inversion rule is the same rule as "the record outranks prose" seen from the other side. Note that it names KnowledgeOS and Governance as one top node, which merges the two at the top.
6. Use ≠ change. Reading, citing and identifying gaps do not change KnowledgeOS, and "knowledge changes should be governed separately". [L576–598] [I] This separates the query operations from the mutation operations and puts the mutations under a governed path.
7. Four distinct states: Authorized ≠ Implemented ≠ Verified ≠ Completed. Also: proposal ≠ approved, code exists ≠ authorized, tests pass ≠ completed. [L644–673] [I] This is a candidate primitive status lattice. The guide claims the four are separate but does not state their order or the transitions between them.
8. Stop conditions (nine) and the stop protocol: identify → cite → explain impact → STOP → ask. [L602–640] [I] This is a deontic "must-stop" set, the refusal half. Under the trivial-solution test, an agent that always stops satisfies the conditions as written.
9. Determining the ownership of a state artifact comes before sharing it. There are six questions: what it represents, who owns it, who reads it, who writes it, whether it is authoritative or derived, and whether it is safe for many writers. Do not migrate CONTEXT/MEMORY until ownership is known. [L677–704] The authoritative vs derived distinction recurs in S0067 (the fold). [I]
10. Single implementation of an engineering rule: shared scripts are agent-neutral, and copying them per agent gives "multiple implementations of the same engineering rule". [L471–509] This is the code form of law 1.
11. The DDD order for agents: problem → domain meaning → ubiquitous language → bounded context → ownership → invariants → architecture → ADR → implementation → verification. Plus the instruction to start from the responsibility being changed, not the class to edit. [L330–411]
12. Producer ≠ sole reviewer: the agent that built its harness must not be its only verifier. [L738–763] (The same principle appears in S0067 as R-34/P-2.)
13. Agent memory is not authoritative engineering truth, and Claude memory is not to be moved into KnowledgeOS automatically. [L851–853]
14. Source character: chat-answer prose ("Yes. Based on the architecture we have established…" L1). It has no evidence tags and is normative, not measured. "Should be"/"must" statements stand without evidence. [L1–5] [I] Its evidential weight is low and it is application-level (agent onboarding). Its laws (1, 3, 5, 6, 7) are candidates for the kernel only if independent sources support them.

## S0067 — Architecture Landscape v2: BC-7 boundary analysis (214 lines; 2026-08-17; ACCEPTED)
1. The model grows from 6 to 7 bounded contexts. BC-7, Governed Session Orchestration, is recognised as "the platform's only executable governance surface" (AST-015 record machine plus AST-016 resolver, one JSON record per work item). Every context, BC-7 included, is classed Supporting, and PublicDigit is the Core Domain. [S0067 L19–33]
2. Owned vs preserved invariants, stated as "the sharpest boundary instrument". A context owns an invariant whose reason lives in that context, and preserves one that it enforces for an owner elsewhere. [L55–69] [I] This is a candidate primitive distinction for the kernel/application boundary. It separates the invariant's reason, its enforcement and its owner.
3. The invariant set I-1…I-10 with owner verdicts:
   - owned by BC-7: I-1 single mutation owner, I-3 sticky STOPPED, I-4 registry ≠ authority state (never merged), I-10 record outranks prose;
   - split: I-2 activation = HANDOFF ∧ human START (BC-7 owns the conjunction mechanics; the authority premise is preserved);
   - preserved: I-5 only Governance writes authority, I-7 process identity never authorizes, I-8 engineering never accepts its own work.
   [L59–69] I-9 is missing from the table without explanation. [I] This is a numbering gap: source defect or deliberate exclusion, undecided.
4. Record ≠ authority, in the Grant split. BC-7 owns the grant record (schema, status, queryability) but not its authority content. "The record never manufactures authority (G-2/R5b)": the grant path refuses to exist without `humanActRef`. [L51] This is a law: authority enters only by a registered reference to an act outside the system. [L123–124]
5. Registration ≠ the thing registered, in the Human Act correction. The act exists outside the software, and BC-7 owns only its registration. "Owning the registration of a thing is not owning the thing." [L52] It is presented as a hypothesis corrected by evidence (the commission had listed Human Act as owned). [L96–103]
6. State = fold of an append-only log, and derived state is never stored. Responsibilities: Record, Refuse, Fold, Answer, Reference-never-contain. [L48, L71–77] [I] Candidate kernel primitives: event log, fold and refusal. This list gives the "must-do" commands (record, fold, answer) beside the invariants, so the trivial-model test is partly met.
7. The machine decides only record consistency, never policy. `authorized` checks scope-string equality, never consults the session and cannot bind a grant to a session. The source calls this "boundary-conforming behaviour", not a defect. [L74, L83, L123–126, L144] [I] Risky inference: a measured limitation is reread as boundary by design, which is an evidence-to-architecture conversion.
8. Boundary sentence 1: "Governance decides whether an act was authorized; BC-7 answers whether it was recorded — and refuses to answer anything else." Correction: BC-7 does not execute approvals; humans do. BC-7 is "a registry of governed occurrence". [L129, L144]
9. "Governance" is a homonym. It names (a) Project Governance, the external domain where policy and authority originate, and (b) the Governance role, an actor that registers acts through BC-7. There are two upstream flows: policy (Customer–Supplier) and operating (Published Language). [L115–142]
10. Boundary sentence 2: adjudicated identity vs folded occurrence. Knowledge truth is adjudicated (a human accepts a meaning). Workflow truth is folded (a deterministic computation over recorded facts). "Knowledge holds the meaning; BC-7 holds the movement." Reference runs one way by identity, and narrative is never authoritative. [L160–178] [I] This is the strongest kernel-level distinction in the window: two truth disciplines, one boundary.
11. Contradiction/tension that the source keeps visible: BC-7's own store is gitignored with no provenance (C-3), which breaks the provenance discipline of the knowledge estate. The authority ledger is "the one governance artifact git cannot see". The source reads the difference as evidence that the boundary is real. [L162, L180] [I] This inference is weak: differing rules could equally be a defect.
12. Enforcement gap: I-5 is enforced mechanically on 2 of 5 transition types only (COMPLETE and CONTINUATION). On REGISTER, HANDOFF and START, `recordedBy` is a free string (C-4). `recordedBy` is a claim, not an authenticated identity, and attribution is "Declared, never attested" (INV-ATTR-2). [L65, L91–92] [I] So the invariant owner exists only partly. Three of five writes rest on convention.
13. Further exclusions found: authority evaluation, actor identity attestation, acceptance/closure decisions, and time/scheduling. On time: "sequence is `seq`, never wall-time authority". [L89–94] [I] Ordering here is a sequence, not a clock. This bears on temporal validity.
14. Open forks, carried: role-model ownership (ADR-AIP-04) [L53]; BC-4's nature (rules-only context vs role-model policy) [L30]; BC-5's Core challenge [L31]; U-4, one knowledge system or two [L27]; the name-fitness issue ("Session" names an entity, while the aggregate root is the Work Item) [L105–107].
15. Epistemic grading and lineage: mechanism facts are Observed (re-measured), the boundary and relationship verdicts are "Inferred · high — single-step readings", and the rest is inherited from baseline v1.1. Producer disclosure: separation is "Declared, not attestable", and the producer must not verify its own model. [L9, L198–206] Growth fact: 14 work-item records against the baseline's 8. [L36]

## S0068 — Capability Map v2 (38 lines; 2026-08-17; ACCEPTED; same producer and work item as S0067)
1. Capability Identity Invariant: one capability identifier = one semantic owner. Ids are never reused, never shared across contexts, never reassigned by merge, and a retired id stays retired. [S0068 L10–12] [I] This is an identity law for the kernel (identifier ↔ owner, a functional and injective relation over time, with retirement monotone).
2. The CAP-07 collision pause is cited as "this invariant working". [L12] [I] This is a single instance, not population evidence.
3. CAP-14 (Governed Session Orchestration) is assigned to BC-7 and realised by AST-015/016. It is "live", with 14 work-item records. This closes the gap where "the newest and most consequential executable capability … has no declared CAP- identifier". [L28, L32]
4. Realization states form a vocabulary: live, not built, convention only, partial, by reference, live (data, not executable), retired-by-merge. [L16–28] [I] This is a maturity scale for declared vs realised capability, and it is implicitly ordered.
5. Declared ≠ realized: CAP-03 Knowledge curation (BC-1) is "not built" (constitution plus convention only), and CAP-11 adversarial review is "not built as component" (performed by the role model and human acts). [L20, L26] [I] So the knowledge-governance context itself has no executable realisation. This matters for any claim that the kernel exists.
6. The assignment "establishes capability ownership in the architecture model only". Model ≠ physical placement. [L33]
7. Grouped rows (CAP-07/08/09, CAP-10/12) give one owner for several ids. That is consistent with the invariant (many ids to one owner), but their states are recorded jointly. [L24–25]
8. Open, carried: CAP-05 ownership ambiguity between BC-2 and BC-6 (OB-3); CAP-13 placement (ADR-C2); CAP-11's home (ADR-C7). [L22, L26–27, L33]
9. Register state: CAP-01…14 declared, CAP-04 retired, next free id CAP-15. [L34]
10. Lineage: a companion of S0067, by the same producer, on the same date. It is not independent confirmation of S0067. [L6]

## S0069 — Context Map v2 (84 lines; 2026-08-17; ACCEPTED; same producer)
1. Authority topology: the Human PO/ARB is the "authority origin — outside the software". Performative acts are committed artifacts, flowing to Project Governance and then to BC-7. [S0069 L13–23]
2. Relationship register R-1…R-8 with DDD patterns. R-1 Customer–Supplier (policy); R-2 Published Language (writing seam); R-3 Open Host Service (fold, identity, authorized) with Conformist downstream sessions; R-4 and R-6 Separate Ways; R-5 mutual reference across a Published-Language seam; R-7 UNRESOLVED (dashed); R-8 Supporting → Core one-way guard seam. [L57–68]
3. Model relationship ≠ physical coupling. BC-7 and BC-2 share CMP-004 physically but are "Separate Ways" at model level, so the smell is "dissolved at the model level, standing at the physical level". [L42–45, L64, L74] [I] A relabelling resolves a coupling only in the model. That fits the model/physical distinction, but nothing has been tested.
4. Change over time against Stage-2: one Governance arrow is split into two flows (policy vs operating), CBC-1 becomes BC-7, and the knowledge seam R-5 is named. [L70–76]
5. Evidence grading: the labels are "Inferred strategic readings of Observed facts" and R-7 is Unknown. [L6, L59–68]
6. A refusal to imply a resolution: the BC-4 line is drawn dashed. This is an explicit open fork. [L67, L75]
7. One-way platform → product seam: there is no `app/` reference to `.claude/` (Observed), and the product never invokes the platform. [L52–54, L68] [I] This is the kernel/application direction at the platform level, and it parallels S0066 law 3.
8. BC-6 supplies bootstrap to all sessions and has no measured dependency on BC-7 state (disjoint stores). [L47–50, L66]
9. Carried open: U-2/M-5, U-4, OB-1, ADR-C3 (3 unregistered hooks), C-3/C-4, OQ-2, ADR-AIP-04. [L78–80]
10. Lineage: a compression of S0067 §§3–5 ("compresses"), so it is the same line of support. [L6]

## S0070 — KOS-ARCH-BASELINE-003 commission: BC-7 Domain Model Discovery (41 lines; 2026-08-17)
1. The work item is created but not started: grant AUTHORIZED, assignment REGISTERED, HANDOFF recorded, START NOT performed (it needs a Human START). [S0070 L7] [I] This is a live instance of I-2 (activation = HANDOFF ∧ human START), seen in the record as a state where the conjunction is incomplete.
2. Phase change: strategic design is accepted and tactical design begins. It is a "different cognitive mode, a fresh Architecture terminal, a new work item". [L13, L19]
3. Required tactical deliverables: aggregates, the aggregate-root decision, entities, VOs, events, policies, invariants and ubiquitous language. [L23] [I] The aggregate root (Work Item, per S0067) is left open for decision, so S0067's "OWNED — the aggregate root" was provisional.
4. The boundary sentence from S0067 is adopted as the anchor ("Governance decides whether … authorized; BC-7 answers whether … recorded"). [L25] [I] A later commission takes an earlier inference as the basis for the next step: an inferred reading becomes a premise (lineage, not confirmation).
5. The knowledge seam is reworded: "artifact identity vs workflow occurrence (meaning/identity/lineage vs movement/lifecycle/occurrence)". [L25] This drifts from S0067's "adjudicated identity" [I], since "lineage" is added to the knowledge side.
6. Forbidden: implementation, code, component movement, workflow_engine changes, and the ADR-AIP-04 or role-ownership decision. "Architecture may propose; the PO/ARB decides." Accepted models are inputs, never overwritten. [L31] Declared role set: implementation excluded, mechanically. [L6]
7. Inputs name BC-7's invariants as "I-1…I-3/I-10". [L35] [I] I-4 is absent here, though S0067 L64 marks I-4 OWNED by BC-7 and calls it "the context's central structural rule". This may be a cross-source inconsistency.
8. Relate, do not duplicate: the attribution work's claim → evidence → assessment chain "may relate to BC-7's Human Act concept" (ES-005.4). [L35] [I] This hints at a possible shared primitive (claim/evidence/assessment) across attribution and workflow, and an open fork.
9. Carried open: role ownership, the BC-4 relationship, future CAP refinement. [L27]
10. Producer self-disclosure: separation is self-declared (INV-ATTR-2), and the producer must not verify its own model (R-34/P-2). [L39]

## Cross-source table [I]

| Theme | S0066 | S0067 | S0068 | S0069 | S0070 | Note |
|---|---|---|---|---|---|---|
| Reference, not containment | "linked, not copied" L103 | "reference, never contain" L77 | — | mutual reference by identity L33–37 | relate, not duplicate L35 | One law in three scopes: harness, mechanism, documents. S0066 is later and has no citations, so its lineage is uncertain. |
| Record/prose authority order | anti-inversion hierarchy L415–435 | I-10 L69 | — | Conformist downstream L63 | accepted models are never overwritten L31 | Same direction. |
| Authority comes from outside the system | KnowledgeOS/Governance at the top L420 | humanActRef required L51 | — | PO/ARB outside the software L13 | Human START required L7 | S0066 puts authority inside KnowledgeOS; S0067 and S0069 put it outside the software. Possible tension. |
| Status separation | Authorized ≠ Implemented ≠ Verified ≠ Completed L646 | record ≠ authority; registration ≠ act | declared ≠ realized | model ≠ physical | AUTHORIZED but not STARTED | Several "≠" status axes that are not unified. |
| Independence of the evidence | uncited normative | Observed re-measurement plus Inferred readings | companion of S0067 | compression of S0067 | consumes S0067 | S0067–S0070 form one line of support. |
| Invariant numbering | — | I-1…I-8, I-10 (no I-9); I-4 owned | — | I-5, I-10 cited | I-1…I-3/I-10 (no I-4) | Possible inconsistency or source defect. |

**Step 1 ends here.** I am waiting for the Step 2 comparison material.
