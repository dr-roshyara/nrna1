# PLAN — Book Readiness / Editorial Delta (bounded continuation)

## Context — what changed

The BOUNDED CONTINUATION mandate (`prompts/20260831_1624_prompt_BOOK SESSION — BOUNDED
CONTINUATION.md`) **supersedes the deliverable of the previously approved plan.** The book session is
now explicitly **a documentation lane, not the decision-making lane**. Its required output is no
longer the five remaining synchronization artifacts but a single bounded **Book Readiness /
Editorial Delta** (mandate §9, eight items), and then a stop.

**Superseded:** artifacts 01–05 of the editorial-sync package as separately specced.
**Retained as inputs, not re-written** (`ES-005.4` — consume or extend): the three already delivered
under `analysis/editorial-sync/` — `06-EDITORIAL-BLOCKER-REGISTER.md` (B-01…B-10 with the
collision-mapping to Step 285's independent numbering), `07-CHAPTERS-WRITABLE-NOW.md`,
`08-CHAPTERS-AWAITING-DECISIONS.md`. The Delta cites them; it does not duplicate them.

**Two genuinely new tasks the earlier plan did not contain**, and they are the substance of this one:
mandate §9 item 3 — **existing passages that risk overstating canon** — and item 4 — **places where
DERIVED / PROPOSED / RATIFIED status must be corrected**. Both require a read-only audit of the
**fourteen produced chapters** (Part II ×4 accepted/frozen, Part III ×10 gated), which no prior pass
has performed against the post-Step-285 findings.

## ⚠ One flag to raise, not to silently adopt

The mandate's §7 execution order differs from the chain recorded at GN-94:

- **§7:** governance decision on canonical `K` → close `ℐ` → identity + equality → derive/test
  operation registry → ratify → typed rejection semantics → `δ` → implementation specification →
  implementation / empirical certification.
- **GN-94 (recorded):** Constitution status → P-11a/b/c → P-1 → identity + equality → closed
  invariant register + typed rejection → operation contracts → transformation contracts → …

Two differences: **canonical `K` is placed first**, and **Constitution status (B-01, recorded as
prior to everything) is not listed at all**. The Delta records §7 verbatim as the coordinating order
and **flags the divergence as a dependency question for the HPA** rather than reordering either chain
editorially (mandate §8: flag conflicts, never silently resolve). It also notes that `ℐ` enters as a
new symbol for the invariant register.

## Deliverable — one artifact, eight sections

`analysis/editorial-sync/BOOK-READINESS-EDITORIAL-DELTA.md`, opening with a status block stating
nothing in it is ratified and nothing in it resolves an open question.

1. **Chapters that may safely be updated now** — from artifact 07, with the riders that make each
   safe; plus the mandate's §2A/§2C additions (status documentation, structural preparation,
   terminology consistency **without resolving semantic disputes**).
2. **Chapters that must remain frozen** — Part II (accepted, GN-70) · Part III (gated, GN-53) ·
   Edition 1 · with §4's rule attached: no substantial rewrite of Parts III/IV as canonical theory
   while the governance blocker stands.
3. **Passages that risk overstating canon** — the new audit. Read-only sweep of the fourteen produced
   chapters for: constraint-presented-as-definition (*"a ratified constraint on a concept ≠ a ratified
   definition of that concept"*), implemented-read-as-canonical, minimal-read-as-canonical, and any
   research-lane term used in a canonical voice. Each hit: chapter · quote · why it risks overstating
   · the correct status · whether a correction requires authorization (Part II/III do).
4. **Status corrections required** — per site, the DERIVED / PROPOSED / RATIFIED (or NOT ESTABLISHED)
   value the evidence supports, citing the act where one exists.
5. **V.5 safe content** — Operations: NOT ESTABLISHED · why the registry is blocked · **computational
   minimality ≠ canonical minimality** · the dependency on `K`, `ℐ` and the membership criterion ·
   that **six candidate registries were reported, none selected or ratified**. No operation contracts.
6. **V.6 safe content** — Transformations: NOT ESTABLISHED · dependency on identity/equality · absence
   of canonical postconditions · `δ` unresolved · rejection semantics unresolved. No transformation
   semantics.
7. **Editorial TODOs awaiting governance** — using the mandate's own two forms verbatim:
   `TODO — canonical state-model decision required before this section can be frozen.` and
   `STATUS — this section records research-derived material and is not yet part of the canonical
   KnowledgeOS specification.` Placed against named sections; **never filled with invented semantics**.
8. **Explicit statement that no new canonical theory is produced**, with the §1 status list carried
   **unweakened** — including `ℐ` not closed, architecture/governance incorporation largely absent,
   and the EKP demonstrating **an unblocked subset, not the complete kernel**.

## No book file is edited this session

§9's output is an identification, and §2's permissions are capabilities rather than instructions. Two
further reasons this is the faithful reading: **Part V does not exist on disk** (Edition 2 has Part II
and Part III only), so its "safe updates" are content specifications for a later authorized pass, not
edits; and Parts II/III are frozen, so item 3's findings are **reported, not applied** (GN-43:
discover · classify · evidence · report — never resolve).

## Hard constraints (mandate §3, binding)

Do not choose between the two `K` models · do not define canonical `K` · do not select an operation
registry or declare `𝒪_core` canonical · do not derive a closed operation universe · do not define
`ℐ` · do not repair Reject · do not resolve Article 8.3/A6 · do not define `δ` · do not invent
pre/postconditions · do not select an identity/equality rule · do not promote the EKP implementation
into the kernel · do not convert research-lane terminology into canonical terminology · do not
declare anything RATIFIED · **do not create or imply an HPA governance act** · do not modify the
ratified architecture · do not write implementation specifications depending on unresolved decisions.

**Three distinctions to preserve in every line of the Delta:** *minimal under a tested computational
criterion ≠ canonical operation set* · *implemented ≠ architecturally canonical ≠ ratified* · *a
ratified constraint on a concept ≠ a ratified definition of that concept.*

## Stop conditions (mandate §10)

Stop and report the dependency if completing any part would require answering: which `K` is canonical
· what belongs to the operation universe · what `ℐ` is · the canonical identity/equality rule · the
canonical Reject semantics · what `δ` is · which operation registry is canonical · which
transformation semantics are canonical.

## Files touched

**Create (1):** `analysis/editorial-sync/BOOK-READINESS-EDITORIAL-DELTA.md`.
**Append (2):** `analysis/governance-notes.md` (GN entry: mandate received, the §7-vs-GN-94 chain
divergence flagged, the Delta recorded) · today's session log.
**Never touched:** `book/**` · `book-edition-2/**` · `model/canonical-architecture-v0.2.md` ·
`final-architecture/FA-*` · `book-architecture/BA-1…BA-7` · anything under `brainstorming/`.

## Verification

Before the Delta is reported: **forbidden-wording grep** (`still being implemented`, `remains to be
completed`, `validat*`, `theory is complete`, `the model is correct`) · **status-vocabulary check**
(no invented intermediate statuses) · **provenance check** — every item-3/item-4 finding carries
chapter, quote and the act it is measured against (BA-ED2-14) · **collapse check** on the three
distinctions above · **§1 fidelity check** — the status list is reproduced unweakened · **frozen-file
verification**: v0.2 = `e928af571f44707867034ae0b7a7ade9`, Part II = 4 chapters, Part III = 10
chapters, all unchanged. md5 + line/word counts recorded (BA-ED2-13).

## Stop

After the Delta and the ledger entry: **STOP.** No writing phase begins until the governance decision
on canonical `K` — the head of §7's chain — exists and has been independently verified.
