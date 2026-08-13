# `ElectionConstitution` authority investigation — findings and ARB disposition

**Type:** Governance / authority investigation · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Status:** ✅ **ARB-ACCEPTED (Product Owner, 2026-08-12 — §5a, verbatim). Findings accepted. NO implementation authorised.** *"Accept in principle" on the candidate requirement settled its HOME only — the gate is `SD-14`, and implementation needs its own authorisation even after it (§5a.1).*
**⛔ No production code, test, schema or migration change. `ElectionConstitution` NOT modified. The Manifesto NOT renamed, deleted or rewritten. `EM-VOT-001` NOT moved. No settled business decision reopened. Session 1's Master Matrix not read, classified, consumed or modified.**

> **Why this artifact exists:** the findings below were first delivered only in conversation. **A finding that exists only in chat is the failure mode this programme exists to prevent** — this document is the durable record, together with the ARB disposition the Product Owner has now given.

---

## 1 · Authority findings

### 1.1 Is `ElectionConstitution` formally authorised as the canonical constitutional source?

> ## ✅ **YES — the PATTERN and the HOME. NOT every rule inside it.**

| Question | Finding | Evidence |
|---|---|---|
| What authorises it? | **`ADR-001: Constitutional Capability Sovereignty` — Status: Accepted, 2026-05-23** | `docs/architecture/decisions/001-constitutional-capability-sovereignty.md` |
| What exactly does ADR-001 authorise? | The **constitutional-capability pattern** — centralized rules; backend-computed capabilities; frontend renders, never infers — and it **names `ElectionConstitution.php` as the implementation** | ADR-001 §Decision · line 78: *"**Implementation:** … `app/Domain/Election/Constitution/ElectionConstitution.php`"* |
| Does ADR-001 ratify the **individual actions, roles and preconditions** in the `RULES` array? | ❌ **NO.** It ratifies the registry's existence and role. **The array's contents are implementation of business rules, not their source** | ADR-001 describes the mechanism; it enumerates no per-action rule |
| Is the docblock's *"THE SINGLE SOURCE OF TRUTH"* claim governance authority? | ❌ **On its own, no — a code comment is not governance.** ✅ **But the claim is BACKED**: an Accepted ADR designates this class as the implementation of the centralized-rules decision | the distinction the Product Owner drew: *code claims "SSOT" ≠ governance says "this is the constitutional SSOT"* — here, **governance does say it, for the pattern and home** |
| Related accepted decisions | `ADR-003` (lifecycle vs phase projection) · `ADR-004` (deterministic capability resolver) · `ADR-005` (projection sovereignty) — same folder, same acceptance | `docs/architecture/decisions/` |

**The resulting authority chain, now ARB-confirmed:**

```
ADR / PO decision  ──►  authoritative business rule  ──►  ElectionConstitution  ──►  implementation
```

**NOT:** *"whatever is written in the array is automatically the business rule."*

### 1.2 Consequence for the two status dimensions

`ElectionConstitution`'s **home status** is settled (ADR-001). **Each rule inside it still needs its own traceable authority** — exactly as with any other implementation artifact. The capacity threshold (§3, `EM-OPEN-019`) is the live example: **the array says ≤ 40; the Product Owner said ≤ 30; the array's saying so settles nothing.**

## 2 · Rule ownership map — ARB disposition applied

| Rule kind | Examples | **Authoritative home** | ARB disposition |
|---|---|---|---|
| **Constitutional workflow** — may the election transition? who may act? what preconditions? | `open_voting`, `publish_results`, `suspend`/`resume`, approval workflow | **`ElectionConstitution`** *(pattern + home per ADR-001; each rule needs its own authority)* | ✅ **ACCEPTED** |
| **Election-domain business rules** — entitlement, membership, participation, suspension of a *voter*, credential, one-vote | `EM-ENT-*`, `EM-EO-*`, `EM-GOV-*`, `EM-VOC-*` | **Election domain policies / aggregates / value objects** — *specified in the business-rule artifact, implemented in domain code* | ✅ **ACCEPTED — and putting these INTO `ElectionConstitution` is ❌ REJECTED** |
| **Architectural decisions** | capability sovereignty, projection sovereignty | **ADRs** | ✅ standing |
| **Authority + rationale** | who decided what, when, why | **business-decision / governance register** | ✅ standing |
| **Investigation evidence** | Session-2 reviews | review documents — **never authority** | ✅ standing |

> **One concept → one authoritative home.** The Constitution does not become an everything-registry; the business-rule artifact does not become a second constitution.

## 3 · Election-Only constitutional gaps *(from the accepted findings — none actioned)*

| # | Gap | State |
|---|---|---|
| **CG-A** | **`open_voting` carries no candidate precondition** — measured: `preconditions => ['voting_window_defined', 'timezone_set']`, no `has_approved_candidates`. The adopted rule `EM-VOT-001` (*"without a candidate an election must not go into the next phase"*, PO 2026-08-08) is **not encoded at the voting boundary**; only `complete_nomination` carries it — and `PBDIGIT-64` established the clock path **bypasses** that transition | 🟡 **conformance gap CONDITIONAL on `SD-14`** (§4) |
| **CG-B** | **The capacity threshold is disputed** — array + docblock say **≤ 40**; the Product Owner said **≤ 30** (2026-08-09) | **`EM-OPEN-019`** — PO decision required |
| **CG-C** | **No voter-level action exists** in the Constitution (`admit`/`suspend`-voter/`restore`/`revoke`: 0) | recorded; **voter-level rules are domain rules per §2, so this may be correct as-is** — the boundary question, not a defect |

## 4 · `SD-14` — presented as a business decision, NOT answered

> ## **May an election open voting with zero approved candidates?**

| Source | What it says | What it does NOT establish |
|---|---|---|
| **Product Owner, 2026-08-08** (`PBDIGIT-64:9`, labelled *"Business rule"*) | *"Without a candidate an election must not go into the **next phase**."* | 🟡 **whether "next phase" means VOTING** — stated while observing an election that had entered voting candidate-less, so the reading is *plausible*, **not established** |
| **`ElectionConstitution`** | `complete_nomination` requires `has_approved_candidates`; **`open_voting` does not** | the Constitution's silence is **not** permission — its contents are not automatically business authority (§1.1) |
| **Runtime** (`PBDIGIT-64`) | an election **was** `voting_active` with zero candidates, via the clock path | behaviour is not the rule |

> **ARB instruction, recorded:** the failing-test expectation (*no candidates → cannot open voting*) must **not** be treated as wrong, and the Constitution's silence must **not** be treated as permission. **This is a potential conformance gap pending one confirmation:**
>
> ## **`SD-14` → Product Owner: does *"the next phase"* include VOTING — i.e. is `EM-VOT-001` binding at the `open_voting` boundary (and on the computed path that bypasses it)?**
>
> **If YES:** `EM-VOT-001` extends to `open_voting` as a conformance requirement → **then** Session 3 may be authorised to encode `has_approved_candidates` there via strict TDD *(and the `PBDIGIT-64` clock-bypass means the guard alone may not suffice — an implementation matter for that authorised slice)*.
> **If NO:** the failing test's expectation is wrong **at that boundary**, and `EM-VOT-001` binds only the nomination transition.

## 5 · Manifesto disposition — ARB ruling recorded

| Aspect | Ruling |
|---|---|
| The artifact (`docs/publicdigit/business_rules/ELECTION_MANIFESTO.md`) | **Useful, non-redundant** — it holds the entitlement/membership/participation rules and traceability that the Constitution **must not** absorb. **NOT redundant with `ElectionConstitution`**: different rule kinds (§2) |
| **Canonical status** | ⏸ **NOT RATIFIED — deliberately.** `EM-OPEN-017` stays open |
| **Name** | 🟡 **"Manifesto" risks reading as a second Constitution** — renaming is on the table. **Not renamed** *(and not deleted, not rewritten)* per instruction |
| `EM-VOT-001` | **Stays where it is.** Moving it into the Constitution is *accepted in principle* — **blocked on `SD-14`** |
| Second constitutional registry | ❌ **REJECTED — none was created, and none will be.** The Manifesto's §7 *references* the Constitution rather than duplicating it, which is what keeps it from being one |

## 5a · ✅ ARB ACCEPTANCE — recorded verbatim (Product Owner, 2026-08-12)

> **ACCEPT — Investigation and findings accepted.**
>
> `ElectionConstitution` remains the canonical implementation home for constitutional election workflow rules, as established by ADR-001.
>
> **This acceptance does NOT authorize implementation changes.**
>
> Do not create a second constitutional registry.
>
> Do not move ElectionMembership, entitlement, suspension, credential or participation rules into `ElectionConstitution`.
>
> Election-Only implementation may proceed only for already-authorized slices.
>
> **`SD-14` remains unresolved and must not be inferred.**
>
> Full Membership implementation remains deferred.
>
> **Any extension of `ElectionConstitution` requires an adopted business rule and strict TDD.**

### 5a.1 ⚠️ The clarification that must not be lost

**The ARB explicitly corrected how one row of §6 is to be read:**

> ***"Candidate requirement belongs in the Constitution — Accept in principle"* does NOT mean "Session 3 is authorised to add `has_approved_candidates` now."**

**The binding reading:**

```
Candidate requirement
        ↓
Business decision SD-14        ←  the gate
        ↓
IF the Product Owner says YES
        ↓
ElectionConstitution is the correct implementation HOME   ←  what "in principle" settled
        ↓
Session 3 may implement — strict TDD                       ←  a separate authorisation
```

**"Accept in principle" settled the HOME. It authorised nothing.** The gate is `SD-14` (`EM-OPEN-020`), and implementation needs its own authorisation even after that gate opens.

## 6 · ARB disposition table — verbatim

| Session-2 item | ARB ruling |
|---|---|
| Investigation report | ✅ **Accept** |
| `ElectionConstitution` is the constitutional workflow home | ✅ **Accept** |
| Constitution contents automatically = business authority | ❌ **Reject** |
| Create a second constitutional registry | ❌ **Reject** |
| Manifesto as canonical authority | ⏸ **Do not ratify yet** |
| Entitlement/membership rules in the Constitution | ❌ **Reject** |
| Candidate requirement belongs in the Constitution | ✅ **Accept in principle** |
| `SD-14` | 🟡 **PO confirms the meaning of "next phase"** |
| Extend `open_voting` after `SD-14` | ✅ **Then authorise Session 3** |
| Full Membership implementation | ⛔ **Deferred** |

## 7 · Recommendation for Session 3 — per the ARB ruling

* **Do NOT wait for a Manifesto ratification to begin all Election-Only work.** Work whose authoritative home is established may proceed where already authorised.
* **Blocked, specifically:** the **admission slice** (`EM-OPEN-001` / `BR-1.12` — undecided, and building current behaviour would decide it) · **any `open_voting` candidate-precondition work** (blocked on `SD-14`) · anything requiring an unresolved authority from the open register.
* **Do not create or modify any rule registry.** Extending `ElectionConstitution` happens only after the specific business rule is confirmed — first candidate: `has_approved_candidates` on `open_voting`, **iff `SD-14` = yes**.

---

**Awaiting from the Product Owner / ARB:** **`SD-14`** *(meaning of "next phase")* · **`EM-OPEN-019`** *(30 vs 40)* · **`EM-OPEN-001` / `BR-1.12`** *(admission state)* · **`EM-OPEN-017`** *(the business-rule artifact's status and name)*.

**Traceability:** `docs/architecture/decisions/001-constitutional-capability-sovereignty.md` *(Accepted 2026-05-23; names the class at :78)* · `docs/architecture/decisions/{003,004,005}-*.md` · `app/Domain/Election/Constitution/ElectionConstitution.php:94-100` *(`open_voting` preconditions, measured)*, `:76-82` *(`complete_nomination`)*, docblock `:7-20` · `docs/publicdigit/backlog/PBDIGIT-64-election-opens-voting-without-any-candidate.md:9` · `app/Application/Election/Services/ConstitutionalTransitionGuard.php:197-220` *(≤ 40)* · `docs/publicdigit/business_rules/ELECTION_MANIFESTO.md` *(§7 references, `EM-OPEN-017/018/019`)* · no ADR under `docs/adr/` names `ElectionConstitution` — the authorising decision lives in `docs/architecture/decisions/`.
