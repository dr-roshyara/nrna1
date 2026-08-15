# KOS-GOV-ATTRIBUTION-001 — Process Attribution for Governance Acts
# Architecture Decision Proposal

**Session 4 — `S4-architecture-attribution` · ACTIVE, mutation owner (resolver-verified: `RESOLVED / operable: true`) · grant `G-KOS-ATTR-ARCH` (investigation only) · 2026-08-15**

> ## PROPOSED — NOT HUMAN APPROVED · NOT GOVERNANCE-REVIEWED
> Architecture investigation only. Nothing implemented; `AST-015`/`AST-016`/`SESSION_START`/hooks/locks/workflow semantics untouched; no grant or assignment created; no remedy chosen. **`A-4.3` (permit-with-disclosure) stands unchanged** until separately decided. Deliverable goes to Governance for independent review — **not performed in this turn**.
>
> **A-4.3 disclosure, pre-emptively:** this ADP is Architecture output only. If a later Governance review of it is performed by a process that also produced it, that overlap must be disclosed under `A-4.3` — the duty this document examines applies to this document.

---

## 1 · Current-state evidence (measured this session)

| # | Evidence | Source |
|---|---|---|
| **E-1** | Governance is **not a registered session**. Its acts appear only as `recordedBy: "governance"` on transitions and `registeredBy: "governance"` on grants. `AST-015` validates `recordedBy` against `['governance','human']` for gated transitions — a **role token, never a process identity** | `workflow-state.php:179, 245, 261` |
| **E-2** | `executionContext` is captured **only on `REGISTER`**, stored verbatim, and re-emitted by `identity`. It is never validated, never compared, never used in any precondition | `:126, 199, 372` |
| **E-3** | Only two `executionContext` values exist across all five records: `"shared-worktree"` and — C-3's first application — `"claude-code-session:fbc084f0 (shared-worktree; …self-declared process label…)"`. **Both are self-declared strings** | all records |
| **E-4** | **Git provides no process discrimination either.** Every commit across all four lanes carries one identical author/committer identity (`Dr. Nab Raj Roshyara <…>`); distinct identities across the last 60 commits: **exactly one** | `git log --format="%an <%ae>"` |
| **E-5** | The **only** de-facto lane discriminator today is the **commit-subject convention** (`docs(governance)` ×30 · `MAINT(verification)` ×2 · `docs(architecture)` ×1 · `(KOS-…)` ×4). A convention is a **declaration**, not an attestation | commit-subject census |
| **E-6** | The overlap under examination is real and disclosed, not hypothetical: the same process acted as Architecture (`86b2e536`) then Governance reviewing that proposal (`e77fa724` §4) | intake §2 |
| **E-7** | `AST-016` already reports `is the grant holder: UNKNOWN — grants carry no session/role linkage (D-2)`. The record's inability to bind actors to authority is **already surfaced**, not newly discovered | resolver output, this session |

> **The consolidated finding — sharper than the intake's:** attribution is absent at **every** level simultaneously. Not only can the record not attribute a governance act to a process (E-1/E-2), **neither can git** (E-4). The entire attribution surface, top to bottom, is **declaration-based**: `recordedBy` is a declared role, `executionContext` is a declared label, the commit prefix is a declared lane. `A-4.3`'s disclosure duty is therefore not an isolated weak link — it is *consistent with* every other attribution channel the programme currently has.

## 2 · The domain problem, and invariant candidates

### 2.1 The problem stated in domain terms

Two distinct questions are being conflated by the word "attribution":

```
PROVENANCE  — "which actor performed this recorded act?"        ← absent today
AUTHORITY   — "was that actor permitted to perform it?"          ← present: grant + human act + G-3
```

The programme's constitutional position (`INV-DISC-2`, accepted principle 5, `A-1.4`/`D-5`) is that **process/terminal identity must never become an authority mechanism**. The intake correctly names reconciling that with attribution as "the heart of Q-1."

**The reconciliation is a type distinction, not a compromise:** provenance is an *attribute of a recorded act*; authority is a *precondition of performing one*. A record may carry who acted without that fact ever becoming an input to whether they may act. The two live in different positions in the grammar of a transition — payload versus gate.

### 2.2 Invariant candidates (proposed for consideration; none adopted here)

**INV-ATTR-1 (evidential-only attribution).** *A process identity may appear in the record only as an attribute of a recorded act. It must never be an input to any precondition, gate, grant evaluation, or activation decision. Attribution answers "who acted"; it never answers "who may act."*

**INV-ATTR-2 (attribution is self-declared until attested).** *Any process identity in the record is a declaration by the declaring process. It must be labelled as such and must never be presented as an attestation, unless produced by a mechanism the declaring process cannot forge.*

**INV-ATTR-3 (candidate, Q-2-dependent).** *A process acting in a Governance capacity on evidence it produced in an Engineering capacity must disclose the overlap [current `A-4.3`] — or must not do so [strengthened form].* **The choice between these is the PO/ARB's; §5 recommends, does not decide.**

## 3 · Q-1 — Alternatives for process attribution

| # | Alternative | Mechanism change? | Attribution strength |
|---|---|---|---|
| **A1** | **Governance becomes a registered session** — REGISTER/HANDOFF/START like other roles | **AST-015 semantics** (large) | strong in principle |
| **A2** | **`actor` field on transitions** — an evidential process identity on every recorded act, validated as *present*, never *compared* | **AST-015** (small, additive) | self-declared, but uniform and queryable |
| **A3** | **`executionContext` + the C-3 convention** — self-declared process label at REGISTER; extend by convention to governance artifacts | **none** | self-declared, partial (REGISTER only) |
| **A4** | **External attestation** — per-lane git identities and/or signed commits | none to AST-015; changes operating setup | strongest available *if* keys are not shared |
| **A5** | **Hybrid: A3 now → A2 if evidence demands → A4 for high-assurance acts** | staged | graduated |

### 3.1 Consequences and trade-offs

**A1 — Governance as a registered session. Recommended against, for a structural reason.** Governance is deliberately an **authority function, not a session** (`G-2`: it registers human acts; it does not hold authority). Making it a session raises an unresolvable regress: a Governance session needs REGISTER + HANDOFF + a human START — **and the actor who registers those is Governance**. Either the registrar registers itself (defeating the purpose) or a meta-registrar is required. It would also make Governance subject to mutation-ownership (`O-1`: ACTIVE ⇒ owner), so registering anything would seize ownership from the working lane — the very defect that caused Alternative B to be rejected in `KOS-EXEC-TOPOLOGY-001`. **This alternative fails on the model, before any cost question.**

**A2 — `actor` on transitions.** Smallest change that makes attribution *uniform and queryable*: every recorded act carries who performed it, in one place, with one shape. Costs: touches `AST-015`, which is **qualified and closed** — a dependency requiring separate authorization; and it remains **self-declared** (INV-ATTR-2), so it improves *auditability*, not *trustworthiness*. Risk to guard: an `actor` field is one careless precondition away from becoming an authority input — INV-ATTR-1 exists precisely to forbid that, and any implementation would need a contract test proving no gate reads it.

**A3 — convention only (status quo +C-3).** Zero mechanism change; already in first application (E-3). Costs: only `REGISTER` carries it, so **governance acts — the actual subject of Q-1 — remain unattributed**, since Governance performs no REGISTER of its own. **A3 alone cannot answer Q-1.** It can, however, answer a *narrower* useful question: which process registered each session assignment.

**A4 — external attestation.** The only option that can produce attribution a process **cannot forge** (signed commits with unshared keys). Measured obstacle: today every lane shares one git identity (E-4), so this is a change to *operating setup*, not to the mechanism. Costs: key management; and the identity attested is a *machine user*, not a *session role* — mapping user→lane is itself a declaration unless one identity is provisioned per lane.

**A5 — hybrid, staged.** Matches the platform's own doctrine (*governance precedes automation*; record before enforcement): adopt the convention now, upgrade only on demonstrated need.

### 3.2 Q-1 answer

> **Yes, the record could attribute governance acts to a process — but only A2 (+A4 for non-forgeable strength) actually achieves it, and both are mechanism/setup dependencies requiring separate authorization. A1 is rejected on model grounds. A3 alone cannot, because Governance performs no `REGISTER`.**
> **In every case, attribution must be constrained by INV-ATTR-1 (evidential-only) and honestly labelled under INV-ATTR-2 (self-declared unless attested).**

## 4 · Q-2 — Should the Engineering→Governance overlap convention change?

The commission's four options, assessed against `R-34`'s actual purpose — *engineering never accepts its own work* — noting that **acceptance is already reserved to the PO/ARB**, so what is at stake is **review**, not acceptance.

### 4.1 The asymmetry that decides the analysis

Independence protects against different hazards in the two cases, and the two are **not equally recoverable**:

| | `implementation → verification` (prohibited, `A-1.4`/`D-5`) | `engineering → governance review` (permitted with disclosure, `A-4.3`) |
|---|---|---|
| Hazard | self-serving **evidence**: an implementer verifying its own work can select what to test | self-serving **scope interpretation**: a producer reviewing its own work can read the boundary generously |
| Recoverable from the record afterwards? | **Poorly** — re-deriving evidence requires redoing the verification | **Well** — grant scope vs. delivered artifact is mechanically comparable, by anyone, later |
| Consequence | prohibition is proportionate | prohibition is **not obviously proportionate**; the error is detectable after the fact |

**This asymmetry is the substantive architectural argument of this ADP**, and it explains why `DEC-2`'s permit-with-disclosure is defensible rather than merely pragmatic.

### 4.2 The four options

| Option | Assessment |
|---|---|
| **REMAIN unchanged** | Defensible on §4.1. But it leaves the duty purely declaration-based with **no structural consequence for non-disclosure** beyond being "a governance defect" in prose |
| **STRENGTHEN** *(recommended, §5)* | Keep the permission; harden the duty: make disclosure **structured and mandatory-by-shape** (a required section naming the prior act by commit/artifact id), and require the review to state **what it checked that the producer could not check itself**. Attacks the real risk — a review that adds no independent signal — rather than the proxy (who ran it) |
| **RESTRICT** | Prohibit only the highest-risk sub-class — e.g. governance review of *one's own* architecture proposal, while permitting governance registration of others' evidence. Coherent; costs a case-by-case boundary that is itself declaration-based today (no attribution ⇒ unenforceable) |
| **REPLACE** (blanket prohibition) | **Not recommended on current evidence.** Unenforceable today (no attribution — E-1…E-5), and with a small number of processes it could make ordinary governance work impossible, since Governance routinely reviews evidence from the same programme. Would also be a *stronger* rule than `R-34` itself, which restricts acceptance, not review |

### 4.3 Q-2 answer

> **Do not replace. Recommend STRENGTHEN** — retain permission-with-disclosure, raise the duty from prose to a structured, shaped obligation, and require the review to name its independent contribution. **RESTRICT is a viable second choice but is unenforceable until Q-1 yields attribution.** The intake's caution is upheld: **a stronger independence invariant is not obviously required**, and this investigation did not find that it is.

## 5 · Recommendation (recommendation only — the PO/ARB decides)

1. **Q-1:** adopt **A5 (hybrid)** — continue C-3's convention now (A3, zero mechanism change); register **A2** as the dependency to authorize *if and when* evidence shows the convention insufficient; hold **A4** for genuinely high-assurance acts. **Reject A1** on model grounds (§3.1).
2. **Q-2:** **STRENGTHEN** the `A-4.3` duty; do not prohibit (§4.3).
3. **Q-3:** treat machine verifiability as **strictly gated on Q-1**, and adopt the honesty constraint in §6 before building anything.
4. Adopt **INV-ATTR-1** and **INV-ATTR-2** as the invariants governing any attribution work, whichever option is chosen.

## 6 · Q-3 — Machine verifiability, and its honest limit

**What a machine check would require, in order:** (a) attribution of engineering acts → (b) attribution of governance acts → (c) a machine-readable link from a governance review to the evidence it reviews. Today **(c) partially exists** (traceability references in artifacts, by convention); **(a) and (b) do not exist at all**. Therefore **Q-3 is unreachable without Q-1**, and any check built on today's declarations would verify declarations against declarations.

> **The limit that must be recorded before anyone builds this.** Even with perfect attribution, a machine could verify only **process-distinctness** — a *proxy* for independence, not independence itself. All four lanes are the same model, the same human director, the same worktree; two distinct processes do not produce two independent judgments. **A green machine check would therefore certify something weaker than what the rule intends, while looking stronger than honest disclosure.** Over-trusting the proxy would be a net loss in assurance. Any Q-3 implementation must state what its check does *not* establish — the same discipline `AST-016` already applies with its `UNKNOWN` answers.

**Modest, achievable form (if Q-1 lands):** a *reporting* instrument that surfaces "the process that recorded this evidence also recorded its governance review" as a **flag for human attention**, never as a blocking gate — consistent with the platform's zero-new-hooks position and with `AST-016`'s surface-don't-decide precedent.

## 7 · Dependencies (recorded, not designed — none authorized)

| # | Dependency | Needed for |
|---|---|---|
| **DEP-1** | `actor` field on transitions in `AST-015` (additive; evidential; guarded by INV-ATTR-1) | A2 · Q-3(a)(b) |
| **DEP-2** | grant↔session/role linkage (the pre-existing **D-2**) | binding acts to authority; already surfaced by `AST-016` |
| **DEP-3** | per-lane git identities and/or commit signing (operating setup, not mechanism) | A4 |
| **DEP-4** | machine-readable review→evidence linkage convention | Q-3(c) |
| **DEP-5** | `AST-016` surfacing of any new attribution field | reporting form of Q-3 |

## 8 · What would require modifying AST-015 / AST-016

**Requires `AST-015` change (qualified and closed — separate authorization, own freeze/evolution reading):** A1 · A2/DEP-1 · any validation or comparison of `executionContext` · any precondition touching attribution (which INV-ATTR-1 forbids in any case) · DEP-2.
**Requires `AST-016` change:** surfacing any new attribution field (DEP-5); the reporting instrument of §6.
**Requires neither:** everything in §9.

## 9 · What is achievable without any mechanism change

- Continue and extend the **C-3 convention**: self-declared process label at `REGISTER` (already in first application, E-3), extended by convention to governance registration artifacts.
- **Strengthen `A-4.3` into a shaped duty** — a required disclosure section naming the prior act by commit/artifact id, plus a statement of the review's independent contribution. This is rule text plus artifact convention: **no code, no mechanism**.
- **Per-lane git identities** (DEP-3) — an operating-setup change that would immediately give real, non-forgeable-ish attribution to every commit, closing E-4 without touching `AST-015`. *(Flagged as the single highest-value, lowest-cost improvement available today; the decision is the PO's.)*
- Keep labelling all attribution as **self-declared** (INV-ATTR-2), so no artifact overstates what it knows.

## 10 · Explicit non-goals

No implementation of any kind · no `AST-015`/`AST-016`/`SESSION_START`/hook/lock/workflow-semantics change · no new grant or assignment · no adoption or replacement of `A-4.3` (it stands until separately decided) · no choice of the PO/ARB decision · no self-certification · no treatment of `DEC-2`'s interim permission as a settled position · no resolution of `V-3`, `E-1`, `O-CLOSURE-VOCAB`, the bootstrap gap, `D-2`, or `D-6` · no Election work · no claim that terminal/process identity should ever authorise · no assumption that two physical terminals are required.

## 11 · Items requiring a separate PO/ARB decision

| # | Decision |
|---|---|
| **P-1** | Q-1 direction: A5 hybrid (recommended) · A2 now · A4 now · A3 only · reject all |
| **P-2** | Q-2 disposition: REMAIN · **STRENGTHEN** (recommended) · RESTRICT · REPLACE |
| **P-3** | Adopt `INV-ATTR-1` and `INV-ATTR-2` as rule text? (If yes: amend `KOS-AI-ORCH-001` per the established parsimony route, or mint separately — Governance advises) |
| **P-4** | Authorize `DEP-3` (per-lane git identities)? — the cheapest real attribution gain, no mechanism change |
| **P-5** | Whether any Q-3 instrument may ever be a **gate**, or must remain **reporting-only** (§6's honesty limit) |
| **P-6** | Priority of `DEP-1` relative to the already-open `D-2`/`D-6` mechanism dependencies |

---

**Traceability:** grant `G-KOS-ATTR-ARCH` + PO/ARB commission 2026-08-15 · intake `2026-08-15-KOS-GOV-ATTRIBUTION-001-intake.md` §1–§4 · `DEC-2` / `A-4.3` verbatim (`KnowledgeOS_Controlled_Session_Orchestration_Proposal.md` §A-4.3) · `A-4.4` (what A-4 does not change) · disclosed overlap `86b2e536` → `e77fa724` §4 · `workflow-state.php:126,179,199,245,261,372` · git author census (E-4) · commit-subject census (E-5) · `AST-016` resolver output this session (E-7, and the ACTIVE gate check) · `INV-DISC-2` · accepted principle 5 · `A-1.4`/`D-5` · `R-34` · `G-2` · `O-1` · `D-2` · `D-6` · `C-3` · `KOS-EXEC-TOPOLOGY-001` Alternative-B rejection.

---

> # PROPOSED — awaiting independent Governance review, then PO/ARB decision on P-1…P-6
