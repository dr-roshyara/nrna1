# Phase 5N — DDD Governance-Model Analysis (per the authorization's §12)

## Distinguishing naming, object, technical artifact, mathematical model, governance decision, authority, canonical model

The corpus's own D-FA-1 through D-FA-7 series **already draws exactly this distinction internally**,
without this reconstruction needing to impose it:

- **Naming** — D-FA-3 ("Zero naming triple"), D-FA-6 ("K_t naming register") — both explicitly labeled
  "terminology policy" acts.
- **Object/mathematical model** — D-FA-1 ("state model"), D-FA-4 ("three kernel traditions") — acts
  concerning the underlying structures themselves.
- **Governance decision** — every `D-FA-N` row is itself a governance decision, of one of the above two
  kinds (or a hybrid).
- **Authority** — HPA, named but not independently legitimated (`07`).
- **Canonical model** — explicitly **refused** at D-FA-4 ("layered, not merged... no CONTRADICTS
  exists between the three streams" — a deliberate non-canonicalization).

## Does "K_t naming register" belong to the same bounded context and aggregate as "K-1 ontology/kernel"?

**Tested directly, not assumed.** The corpus's own D-FA-6 explicitly separates these: *"No formal
object changes; this is a terminology policy."* **This is itself a DDD-relevant self-disclosure**: the
corpus's own governance practice treats "naming register" as its own aggregate (a Value-Object-like
lookup table mapping preferred labels to referents), explicitly distinct from the "kernel object"
aggregate it names. **Aggregate identity is NOT assumed merely because the same symbol (`K_t`) appears
in both.**

## DDD structure, as evidenced (not invented)

- **Invariant**: "future usage must qualify according to the register" (D-FA-6's own consequence) — a
  genuine, stated invariant, but scoped to naming consistency, not object correctness.
- **Domain event**: the ruling itself ("D-FA-6 ACCEPT") functions as a domain event within the
  corpus's own governance-process model.
- **Governance event vs. object event**: kept distinct — D-FA-6 is a governance event about naming;
  no object-level domain event (e.g., "K-1 object ratified") was found anywhere.
- **Context mapping**: **not adopted anywhere in this phase** — per the authorization's own
  instruction, DDD structures are identified only where directly evidenced by the corpus's own text,
  never introduced as new facts.

## Verdict

**The corpus's own governance practice already implements the naming/object distinction this phase was
asked to test for** — confirming, from the DDD angle, the same finding `04`/`06` reach from direct
textual analysis: naming and object-ratification are treated as genuinely separate acts, and only the
former is established for K-1.
