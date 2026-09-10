# MD-097 §01 — Document→Object Impact Map (3 files, `kernel/`, closing)

## File 170 — `20260827-094057_cavell-must-we-mean-what-we-say-lenses.md`

A pure multi-lens philosophical-brainstorming document (Stanley Cavell), structurally identical in
kind to the corpus's own earliest material (MD-093) despite arriving nearly a week later in
chronological time. Ten "strongest observations" (Knowledge≠Acknowledgment; the "must" of meaning;
the ordinary-as-unseen; fraudulence as a standing feature, not a bug; self-concealment in assertion;
the end of justification; taste as partiality; first-person-plural as invitation not report;
tragedy-as-failure-of-acknowledgment; audience implication), each posed as an "architectural question"
to an unspecified "Kernel" — never itself defined in this file, presupposed as already-known context.
Explicit self-limiting section ("5 observations that should NOT influence architecture without
further evidence"). Zero formal notation anywhere in the file; zero occurrences of any tracked F4
token, `Kernel` used only as an undefined presupposed referent, no `K-1`/`Zero`/`ConflictRecord`/
`KnowledgeAggregate` anywhere.

## File 171 — `20260827-094108_question-5-updating-and-preserving-assertions.md`

By contrast, a genuinely well-typed formal document — one of the most mathematically developed single
documents found anywhere in `kernel/`, arriving at the very end of the directory's own chronology.
Defines a complete Assertion-versioning/provenance/history model: `Update(A,E,τ)→A_new`;
`Provenance=(VersionID,τ,Previous,Reason,Evidence,Actor)`; `A_versioned=(A,VersionID,τ,Provenance)`;
`History(A)={A_versioned^(1),A_versioned^(2),...}`; two stated preservation invariants
(`∀A:History(A)≠∅`; `∀A,τ_i:A_{τ_i}∈History(A)`); an eight-stage assertion lifecycle (Created→
Evaluated→Accepted→Updated→Challenged→Revised→Retired/Rejected); `StateUpdate`/`ValueUpdate`/`Retire`
functions; a fully worked example (an Arjuna/Bhīṣma conflict-detection-and-resolution scenario across
three assertion versions). Closes with "The Complete KnowledgeOS Model" — an eight-item list
(Proposition, Assertion, Epistemic State, Evidence, Comparison, Challenge, Update, Preservation) —
and three explicitly deferred next questions (coherent Knowledge State from Assertions; conflict
resolution; quality measurement). **No occurrence of `Kernel`, `K-1`, `ConflictRecord`,
`KnowledgeAggregate`, `Sat`, `Det_r`, `EvalReq`, `EC`/`EC_t`, `Γ`, `Δ_t`, `≡_sem`, `⪯_cap`, or `MinKer`
anywhere in this file** — the word "Zero" appears only as "Zero Lens" (a detection heuristic for
stale/weak/conflicted assertions), and "Conflict" appears only as a lowercase per-assertion status
field (`Conflict = Active`/`Resolved`), structurally and lexically unrelated to the tracked
`ConflictRecord` governance object.

## File 172 — `20260902-185000_review-yes12345.md`

**Confirmed byte-identical** (via `md5sum`/`diff`) to `20260825-192351-relational-logical-structure-
as-core-mathematics-as-regimes.md` (MD-096, Batch G) and its own `-duplicate` sibling
(`20260825-192948`). This is therefore a *third* physical copy of the same essay — the core+regime
mathematics-as-regimes synthesis already fully extracted in MD-096 §02 (Kernel Identity Ledger entry
#40, `𝔎=(P,C,T,X,I,E,R,Λ)`). No new content; see §02 for the corpus-hygiene/provenance adjudication.
Its filename follows the `YYYYMMDD-HHMMSS_slug.md` (underscore, not hyphen) convention shared with
the `refinement_phase/` files rather than `kernel/`'s own standard `YYYYMMDD-HHMMSS-slug.md` pattern —
noted as a further, minor provenance signal, not investigated further this phase.

## `kernel/` — corpus-completion statement

With this phase, all 172 files under `docs/knowledgeos/brainstorming/kernel/` have now been read in
full, in strict chronological order, across MD-093 (39 files) → MD-094 (20 files) → MD-095 (52
files) → MD-096 (50 files) → MD-097 (3 files, one confirmed duplicate) = 172 files exactly, plus the
8 control/classification artifacts named in MD-096 §00 (identified, not yet read). `kernel/`'s own
primary-content chronology is therefore corpus-converged for the multi-object reconstruction method,
modulo those 8 control artifacts.
