# 9.2 Portability of the parts is not sufficiency of the whole

*Corpus evidence for §9.1. Source: S0039 (Product Boundary Discovery, 2026-08-02, a generated candidate), with its MVK test record (S0025, Ch. 8 §8.1). **Status: reviewed in C7 (2026-10-19); settled in `review/C7-S0036-S0040/SETTLEMENT.md`.***

## 9.2.1 In plain language

Before the corpus tested its minimum kernel, it warned that checking each part is not the same as checking the set. The test then failed, for this reason among others: some parts also turned out not to be portable. This is the corpus's own form of §9.1's result: a kernel needs requirements on the whole set, beside conditions on each part.

## 9.2.2 Definitions

- **The litmus (Src).** "would it exist if PublicDigit disappeared?" [S0039 L33]. Each candidate is tested for whether it survives the removal of the product.
- **Minimum Viable KnowledgeOS (Src).** "what is the smallest coherent KnowledgeOS that could still bootstrap a completely different product?" [S0039 L210]; a candidate set of elements, each of which "passes ES-005.3" [S0039 L212].

## 9.2.3 Claims and derivations

**C1. A per-element test cannot decide a whole-set property (Src + Ded).** `[CON-0105 · validated]`
- **Src:** "The litmus (ES-005.3) tests each element for *portability* — it does NOT test the set for *completeness*. A set can be entirely portable and still fail to bootstrap anything, because portability is a property of parts and sufficiency is a property of the whole." [S0039 L234]
- **Ded (a non-implication, by counterexample):** in §9.1 C4, {record} and {record, grant} have the same per-element status (each mechanism preserves the invariants), and only the second meets the requirements. So per-element tests do not decide sufficiency. Some properties are decided element by element (preservation, §9.1 C3); bootstrapping a product is not among them.
- **Link to §9.1:** the litmus is the corpus's per-element condition, like preservation (P) in §9.1 C2; "can bootstrap a product" is a required capability (Req). §9.1 C1 showed that per-element conditions alone admit the trivial set. S0039 shows the same gap in the corpus's own terms, before its test and seven weeks before the Principal's direction (EXT-010, 2026-09-24). Whether the direction drew on this line is not known, so the two are not counted as independent support.
- **The test (Src):** the bootstrap attempt failed, "Portability of parts ≠ sufficiency of the whole — measured, no longer inferred" [S0039 L275]; the record is S0025 (Ch. 8 §8.1). It also found a per-element failure: domain-free parts that were not portable (CON-0059).

**C2. Reusability can be established by inspection (Src; Int).**
- S0039 holds that a product's reusability can be declared from an inspection of its parts, before it has been used [S0039 L326].
- **Int:** reusability is a property of the parts, checkable without a use. The capability "bootstrap another product" need not be exercised to be claimed.

## 9.2.4 Open questions

- Which required capabilities should the kernel have, if "bootstrap another product" is one of them (T9-01)?
- Does the litmus, applied to every candidate, give a usable first filter for kernel candidates, before the two other boundary questions?

## References

| Source | S-ID | Lines |
|---|---|---|
| `docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md` | S0039 | L33, L210–212, L234, L275, L326 |
| MVK bootstrap experiment | S0025 | Ch. 8 §8.1 |
