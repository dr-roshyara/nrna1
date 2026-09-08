# MD-044 §2 — Minimality Semantics, Existence, Uniqueness (Phases B, C)

## Phase B — the four claims, kept separate

**1. Representability** — Can a candidate Kernel be represented in the formalism? **Partially yes.**
`K=(Carrier,Transitions,Capabilities,Invariants,Observables)` is a real, if abstract, product-type
shape. The toy Python testbed (`EpistemicState`, `Capability`, `EpistemicKernel`) is a genuine,
concrete instantiation of this shape — for a 3-capability toy universe only.

**2. Admissibility** — Can a candidate be shown to belong to `𝔎_adm`? **No.** `𝔎_adm` itself has no
stated membership criterion anywhere in the 5-file chain. No candidate (toy or real) has ever been
checked against it, because there is nothing to check against.

**3. Satisfaction** — Can we establish `K⊨𝔠_KOS`? **No, for the same reason** — `𝔠_KOS` is never
enumerated as a concrete requirement set. The toy testbed's own "invariant check" (qualifiers stay in
`[0.0, 1.0]`) is a real, executed check, but it checks one narrow, toy-specific invariant, not the
declared-but-unspecified `𝔠_KOS`.

**4. Minimality** — Can we establish that no semantically smaller admissible `K` satisfies `𝔠_KOS`?
**No, for the compounded reason above** — minimality is defined in terms of satisfaction and
admissibility, both unspecified. The toy testbed's own `test_irreducibility_witness("Qualify", ...)`
DOES establish something real and executed: that removing `Qualify` from a specific 3-step scenario
changes the observed state digest — this is a genuine, if toy-scale, **witness instance** of Lemma 1's
own proof method, not a claim about the real 13-capability candidate universe.

**These four claims must not be collapsed.** The toy execution genuinely demonstrates (1) and a
narrow instance of the *method* behind (4); it demonstrates nothing about (2) or (3) at all, because
the objects those claims are about (`𝔎_adm`, `𝔠_KOS`) are not defined at any scale, toy or real.

## Phase C — existence and uniqueness

**Existence**: `∃K: K∈𝔎_adm ∧ K⊨𝔠_KOS` — **NOT established.** The source's own text is explicit and
direct: *"We currently explicitly do not know whether `MinKer(𝔠_KOS)≠∅`. This cannot simply be
assumed"* (M0239 item 6). No additional theorem or definition beyond what M0239 itself already names
would be required to change this — M0239's own item 6 already states exactly what is needed (either
a constructive existence proof or an abstract theorem guaranteeing minimal elements exist in the
admissible implementation space).

**Uniqueness**: `∃!K` (a unique minimal admissible satisfying kernel) — **NOT established, and its
own history within this source chain is itself informative.** File 3 (M0237) states as if settled:
*"Uniqueness: Exactly one semantic equivalence class `[K*]_≡sem` exists in `MinKer(𝔠_KOS)`."* File 4
(M0238), ten minutes later, catches this as a category error and introduces the correctly-typed
`MinKer_/≡sem` (a SET, cardinality unstated). File 5 (M0239), the session's own final ledger, settles
the matter: *"Minimal Kernel Uniqueness: NOT YET PROVED... If not [unique], that is not a failure...
KnowledgeOS has multiple semantically minimal Kernel realizations. This is why uniqueness must remain
separate from minimality."* **The source material corrected its own overclaim within the same
session, and this study reports the corrected, final position — not the superseded intermediate one.**

## The self-caught governance-fabrication event (methodological, not a Kernel-science finding)

M0236's own early draft declared a register entry `(RATIFIED)` under authority "KnowledgeOS Core
Epistemic Framework Committee" — a body with no evidenced existence anywhere in this corpus. The
same session's own senior-review pass caught this within the same file: *"Unless that committee and
ratification act actually exist in your governance system, this must not appear as a factual
ratification... this is exactly the kind of provenance error KnowledgeOS is supposed to prevent."*
Corrected to `UNRATIFIED / PROPOSAL FOR FOUNDATIONAL FREEZE`. **This is not evidence about Kernel
minimality itself** — it is evidence that this corpus, at least in this instance, catches its own
governance-fabrication attempts before they stand unchallenged, a data point consonant with (not
independent confirmation of) this reconstruction's own repeated finding elsewhere (Phase 5M's "HPA"
provenance question, MD-041's unratified `EC` authority) that no legitimate governance authority has
been evidenced anywhere in this corpus for Kernel-related claims. `M0239`'s own final ledger:
`"Governance Ratification: OPEN"`, `"AUTHORITY: Research Lead Consensus; Pending Formal Governance
Review"` — i.e., still no evidenced authority, consistent with (not contradicting) this
reconstruction's standing finding.

## What IS genuinely new relative to F1–F8

The toy-scale executable test is a real, if narrow, computational artifact — the first time any
material this reconstruction has examined for the minimal-kernel question has produced an actual
run, rather than pure formal argument or narrative claim. It tests one capability (`Qualify`) in a
3-capability universe, not the real 13-capability candidate set, and establishes nothing about any
of F1/F3/F4/F5/F6. It is noted here for completeness and is not treated as evidence resolving any
open gap.
