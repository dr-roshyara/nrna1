# 6.4 Who owns KnowledgeOS, a threshold for admission, and invariants that are stated but broken

## 6.4.1 In plain language

Three sources from 2026-08-02 (S0036, S0037, S0039, all generated candidates) ask what KnowledgeOS is by asking who owns its parts. Their answer: no single owner. They also supply what §6.2 found missing, a threshold for admitting a domain, and they record two invariants that the architecture states and the repository breaks.

## 6.4.2 Definitions

- **Admission threshold (Src).** S0037 admits a strategic domain only if it meets "≥ 3 of `Round47-OP`'s nine criteria", which it calls stricter than the protocol's own "one or more" [S0037 L10].
- **Evidence grades (Src).** OBSERVED (read from an artifact), INFERRED, ASSUMED, HYPOTHESIZED; "Only OBSERVED is authoritative" [S0037 L11].
- **Stated-and-breached invariant (Src).** "These are not candidate invariants. They are invariants the architecture asserts and the repository breaks." [S0037 L241] `[CON-0102 · validated]`
- **Capability identity (Src).** One capability "with five responsibilities" when they share one lifecycle and one ownership and were exercised as one programme; five capabilities when "Each has its own invariant (DP-1, DP-4, DP-6…), its own lifecycle, and independent implementations" [S0036 L137–138]. `[CON-0103 · provisional]`
- **Knowledge flow and authority flow (Src).** "The knowledge flow and the authority flow run in opposite directions." [S0039 L133]: the platform supplies rules to the product, while the product holds authority over the platform's evolution (AIP-14). `[CON-0104 · validated]`

## 6.4.3 Claims and derivations

**C1. The threshold makes admission reproducible, not justified (Ded).**
- §6.2 C1 found the nine criteria a necessary condition with no sufficiency condition, so admission was underdetermined. S0037's "≥ 3" makes the rule **determinate given the scores**. It is not treated as sufficient even in its source: PD-4 meets the bar and is still blocked by a standing refusal (H-CAT-1) [S0037 L96–97], and PD-5a is "at the bar" but unexercised [S0037 L103]. Reproducibility across raters is untested: there is one rater.
- **But the threshold is stipulated, not derived:** "as commissioned" [S0037 L10]. A count over criteria also weighs them equally, and S0037 counts "transactional consistency" in the non-DDD sense that §6.2 C2 rejects [S0037 L53]. The same source calls ownership "the strongest boundary criterion" [S0037 L31], and S0001, written earlier, already found shared ownership insufficient alone as a boundary signal (S0001 L29; consolidation inventory). Unequal criteria with an equal-weight count can admit a domain on three weak criteria and reject one on two strong ones.
- **A criterion that cannot vary still counts:** team autonomy is graded "INFERRED" for PD-1 [S0037 L57] in a one-developer setting (§6.2 C2).
- *Scope:* this concerns the rule's form. Whether PD-1 to PD-3 are domains is not decided here.

**C2. "No single owner" is an observation by absence; the causal claim is a hypothesis (Src + Hyp).**
- S0037 lists owners: Decision Authority (PD-1), sponsor and ARB (PD-2), "an INDIVIDUAL" (PD-3), and "NO SINGLE OWNER EXISTS" for KnowledgeOS itself, "OBSERVED by absence" [S0037 L199–208].
- An observation by absence is only as strong as the search behind it (Ch. 8: a search miss is not absence).
- **The causal claim** that the programme's symptoms (ten rediscoveries, two ontologies, colliding vocabularies) "are what three unreconciled authorities over one subject produce" [S0037 L220] is untested. No comparison case is given. *Hyp.* S0038 offers a rival cause, unindexed knowledge (§8.2).
- **This is the corpus source of CON-0026** (three authority systems, PROVISIONAL), which S0037 carries as an annotation from S0019 [S0037 L23]. So it is one line of support, not two.

**C3. A stated invariant is not yet an invariant (Ded).** `[CON-0102]`
- S0037 separates seven invariants it observes, I-1 to I-7 [S0037 L226–232], from two that are proposed and violated: V-1 "KnowledgeOS never contains business knowledge" and V-2 "PKS never contains reusable engineering capability" [S0037 L238–239].
- **What makes a statement an invariant** is that something in the model guarantees it. V-1 and V-2 are asserted, and no operation maintains them.
- **Breach:** V-1's breach is shown (ES-005.1 names the product). **V-2's is not established:** it rests on a lexical test (zero election terms), and a lexical test cannot establish a semantic property (CON-0059, Ch. 8 §8.1); S0039 found the same trio mostly non-portable case law [S0039 L202].
- This is the same distinction as maturity ≠ enforcement (Ch. 2 §2.10, CON-0056) and running ≠ enforcing (§3.9, CON-0092), applied to invariants: **stated, held, and enforced** are three properties.
- **Its own invariant I-3** is "One authoritative document per topic + context", called "OBSERVED AND MACHINE-ENFORCED" [S0037 L228]. This is the scoped form of RC-9's "one home per rule" (Ch. 1 §1.10; consolidation review). **But by this section's own distinction it is held, not enforced:** the same day S0036 lists the lint check as advisory, it "runs only when invoked" [S0036 L198]. "Enforced" is a homonym here, lint severity against a blocking gate (compare CON-0092).
- **Numbering:** S0037's I-1 … I-7 are document-local, and its I-7 ("Status is independent of authority") is not the book's I-7 (Ch. 0 §0.5; RC-3).

**C4. Capability identity by invariant, lifecycle and implementation (Int).** `[CON-0103]`
- S0036's two verdicts [S0036 L137–138] give a working criterion: a group of responsibilities is **one** capability if it shares one lifecycle and one owner; it is **several** if each has its own invariant, lifecycle and implementation.
- It agrees with Ch. 1 §1.8's component rule (CON-0076) in using lifecycle and independent exercise, and adds "its own invariant".
- **Limit:** "exercised as one programme" [S0036 L137] is evidence of history, not of structure. A programme can exercise several capabilities together.

**C5. The authority flow against the knowledge flow (Src; Int).** `[CON-0104]`
- S0002 named a "knowledge-flow/authority-flow inversion" without defining it (consolidation inventory). S0039 defines it [S0039 L129–133].
- **In the book's terms:** the platform is the source of **rules** for the product, while the product is the subject of the **authority** that governs the platform's change. The two relations have opposite directions, so a single "upstream/downstream" label cannot describe the pair (Int). This fits §3.8's separate orders (CON-0064).

**C6. Other points.**
- "Replaceable" and "reusable" are different properties of the runtime adapter [S0039 L69] (Ch. 1 §1.5).
- **Location is not a boundary:** "one rule → one home" can be "satisfied by accident of location, not by boundary" [S0039 L278].
- **A classification by flat classes is rejected by the sources themselves:** the capability classes are to be read "as values on **AUTHORITY-SCOPE × GOVERNANCE-STATUS**" [S0036 L20], and S0038 maps the commissioned classes Kernel · Platform · … · Archives onto the same two dimensions [S0038 L201]. So "kernel" here is a **value of authority scope**, the portable engineering kit (F14), not the knowledge kernel of Ch. 9.

**C7. Which check can be made enforcing (Src; Ded).** `[CON-0108 · validated]`
- S0036 picks the placement check over the identifier check for enforcement, because only it "has a deterministic pass/fail with no human judgment". The identifier check "returns `INCONCLUSIVE` on ungoverned series — a verdict that requires a human, and therefore cannot be enforced without either suppressing it or blocking legitimate work" [S0036 L263].
- **Ded (restated after review C7):** a check can block automatically without error only if no verdict value covers both cases that should pass and cases that should block. INCONCLUSIVE is a decided output, but it covers both kinds of case, so mapping it to pass suppresses some, mapping it to block stops others.
- *Unverified:* that the placement check has no such value. Its resolver has an "unruled" outcome that is escalated to a person (repository instructions, not a corpus source), so S0036's choice may not meet its own condition.
- *Scope:* enforcement by blocking. A check with an INCONCLUSIVE value can still be enforced by routing that value to a person, which is no longer automatic.
- This refines running ≠ enforcing (CON-0092): to enforce, a check must also have a decidable verdict set.

**C8. Counts that conflict (Src).**
- S0036 reports "0 of 25 capabilities are ENFORCING" [S0036 L245], although S0024's correction, on the same day, found deny and ask rules runtime-enforced (Ch. 2 §2.10). The two count different things, capabilities against runtime rules, so the class counted decides the answer (CON-0070).
- **Observed links in the chain:** "Two of nine links are observed inside the platform" [S0037 L259]; "Two of six transitions are evidenced. One is empty." [S0039 L167]. Both call the loop a destination rather than an observed cycle (S0037 L259; S0039 L167); compare Ch. 4 §4.1 C7. *S0036–S0039 are one line of support (one day, one operator, annotating one another), so their agreement is not corroboration.*

## 6.4.4 Open questions

- What would justify a threshold for admission, rather than stipulate it (C1)?
- Who guarantees each invariant, and by which operation (C3)?
- Is "no single owner" a defect, or the correct shape for a platform with three kinds of authority (C2; CON-0026)?

## References

| Source | S-ID | Lines |
|---|---|---|
| `docs/knowledgeos/KnowledgeOS_Platform_Capability_Model.md` | S0036 | L20, L137–138, L245, L263 |
| `docs/knowledgeos/KnowledgeOS_Strategic_Architecture_Discovery.md` | S0037 | L10–11, L23, L31, L57, L199–208, L220, L226–241, L259 |
| `docs/knowledgeos/KnowledgeOS_Engineering_Knowledge_Landscape.md` | S0038 | L201 |
| `docs/knowledgeos/2026-08-02-knowledgeos-product-boundary-discovery.md` | S0039 | L69, L129–133, L167, L278 |
