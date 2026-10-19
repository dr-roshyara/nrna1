*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back (window C9; one step-2 message).*

**C9 transition review, steps 2–3 (S0046–S0050 + S0091, S0113)**

**Tool clock:** start 13:36:22 UTC (`date -u`, first command of this step), end 13:40:17 UTC (`date -u`, last command before this hand-back). That is 3 min 55 s by the tool clock.

**What I read:** first my saved STEP1-INVENTORY.md, then §9.4, §9.5, §8.4, STEP8-AND-SCAN, CONCEPT-REGISTER (CON-0099…0120), THEORY-MAP and RESEARCH-PURPOSE. From Ch. 1 I read §1.6 (A1), §1.14.2–3, §1.14.5, §1.16.1 (part), §1.16.6 (part), §1.16.7–8 and §1.17.3–4. I also read §9.1, §6.4, §8.2 and §5.5, plus Ch. 0 §0.7 and Ch. 2 §2.8.7 (part), which my own searches turned up. I ran `book_search.py` twice; the commands are listed under Limits. Independence is process independence only (a separate AI session), not personal independence.

---

### 1. Assessment
- The new sections report their sources faithfully, and the lineage notes are correct. The new derived results are weaker than stated:
  - CON-0114 ("vacuous") ignores the content of its own default.
  - CON-0118 ("inert") ignores three-valued rules.
  - The §9.5 "tension with INV-A3" treats INV-A3 as law, although Ch. 1 §1.14.5 says no adopting act was found for it.
- **Largest risk:** the book search reported "no home" for distribution, but Ch. 0 §0.7 and §1.16.6 are homes, and §1.14.5 already resolves most of §9.5 C5. So §9.5 builds "new" theory on a search miss, which is the failure CON-0120 describes.
- No critical findings. Seven high, six normal (appendix).

### 2. Findings (ranked)

| # | Sev. | Type | Claim / topic | Source line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | defect | §9.4 C1, CON-0114: "a read invariant met by a total derivation is vacuous" | S0049 L42, L48 vs L50; L25–28 | (a) INV-ATTR-2 says "defaulting to its dimension's **weakest**". A total read that defaults to the *strongest* outcome breaks it, so the rule is **not** met by every total function. What has content is the value of the default. (b) S0049 L50 says the derivation is total **only if** "the currently-established Assessment" names at most one. So totality depends on stored state. C1's "whatever the stored state is" contradicts C2, the next claim. (c) S0049 L25–28 shows the default doing real work: every unattested independence claim is Declared *because of* INV-ATTR-2. | The section's "one new result" fails as stated. | Restate: "a read rule discharged by a total derivation constrains neither stored state nor concurrency, so it cannot justify a consistency boundary. Totality presupposes a functional selector." Keep the default-value point. |
| 2 | high | omission | §9.4 C3, CON-0115: one *current* claim per (act, dimension) | S0050 L25, L84 | **No component guarantees this invariant (no invariant owner).** The aggregate is {one Claim · its Assessments}. Two claims for the same (act, dimension) live in two aggregates, and nothing serialises claim assertion or supersession per (act, dimension). By the book's own §1.16.7, the rule can then only be **detected**, not prevented. Neither the source nor the book notices. | The rule is stated but not held (CON-0102). | Add: "owner: none in the repaired design; detection only, unless claim assertion is serialised per (act, dimension)". |
| 3 | high | defect | §9.4 C2: "uniqueness needs a serialization point (instance of §1.16.7)" | S0049 L50; §1.16.7 | Step by step: (1) U = at most one established assessment per claim. (2) Writes to U: establishments on c. (3) §1.16.7: prevention needs one serialization point. (4) The aggregate serialises per claim. (5) So U is preventable. Steps 1–5 hold if §1.16.7's "global" is read as global *over the write set that the property ranges over*; with that reading it is a valid instance. It also needs the premise "no establishment path outside the aggregate", which S0050 L120 supplies. **Hidden premise:** that outcome-definiteness needs U at all. A deterministic aggregate (e.g. "the weakest outcome among established assessments") is total and single-valued without U and without serialisation. Also: §1.16.7 is itself an unreviewed Ded (STEP8 L78), and the closer home is §1.16.1 (one owner per invariant, one atomic change), which was not cited. | C2 is conditional on a design choice that is not stated. | State the premise ("the outcome is the outcome of *the* established assessment, not an aggregate") and cite §1.16.1. |
| 4 | high | omission / defect | §9.5 header: "none for distribution (0 hits)"; STEP8 L71: "no home → C1 is new" | — | My search found homes the step-3 search missed. Ch. 0 §0.7 (a need is assembled from *live* sources into a working context; this is distribution, under other words). §1.17.4 Int (context assembly is a task's input set). §1.16.6 (a reference is pinned, or live with detection: the `tokenRef` was a pin to a superseded path that was used as an operating rule). | "New" status rests on a lexical miss, the exact error of CON-0120. | Recast §9.5 C1 as the failure case of §0.7 plus §1.16.6. Add an open fork: which references are pinned and which are resolved at use. |
| 5 | high | defect | §9.5 C5: "Ch. 1 §1.14.2's INV-A3 says absence is never permission"; the tension | S0113 L40–42, L77; §1.14.5; §1.17.3 | (a) §1.14.5 says "**No adopting act for INV-A3 was located**". Failing closed rests on INV-A3 or P-R, and "neither is adopted". C5 presents INV-A3 as an established rule. (b) INV-A3's domain is the absence of an *authority* record (assignment, grant, START). A missing *knowledge receipt* falls under it only if the rule is extended. (c) §1.14.5 already concludes that "the advisory mode … is the correct mode for any rule for which no current act authorizes a block". That is S0113's WARN floor. Its "confirmation request" by a person who holds the grant, recorded as an act, is S0113's option F. | Most of the tension is already resolved by an existing section, and the open question is partly answered. | Rewrite C5 as an instance of §1.14.5. Keep only the residue: whether INV-A3, if adopted, covers knowledge receipts. |
| 6 | high | defect | §9.5 C4, CON-0118: "the rule is inert, whatever r says" | S0113 L24, L34, L44 | Counterexample: a rule with an explicit undetermined clause, "if c is unknown then r′" (Kleene, Ch. 2 §2.8.3, §2.8.8), acts where c cannot be decided. Fail-closed and fail-open are exactly such rules. Existing homes missed: CON-0108 (enforceability needs a decidable verdict set) and CON-0081 ("Missing" asserts E1 where only E3 is known). S0113's "Missing" is the same conflation. And by S0113 L44, even Missing is not decidable (a receipt cannot be bound to a session), so the true count is zero of five, not one. | The Ded generalises past the source. | Restrict to two-valued responses. Cite CON-0108 and §1.17.3. Note that a response to "unknown" is a separate rule, which needs its own act (§1.14.5). |
| 7 | high | defect | §9.4 C4, CON-0116: "two latent channels it closes" | S0049 L126; S0050 L108–110 | The role-co-location channel is a Governance *practice* (refusing registrations), not a gate input. No rule on an input set can close it. S0049 L126 says it is "held today **by policy only**", and S0050 only "names [it] as a prohibited extension". Under the book's own CON-0102, both channels are *stated*; neither is enforced. The "extension of §1.17.4" is a category shift: §1.17.4 is about passing input *values* explicitly (for reproducibility), while C4 restricts input *kinds* (for non-coupling). The real homes: §1.6 / RC-37a (evidence alone cannot effect an act; an assurance outcome is evidence) and §1.14.5 P-E. | Overclaim, and the wrong parent cited. | Change "closes" to "declares closed". Re-home under P-E plus the two keys. |
| 8 | high | omission | §9.5 C1, CON-0117 under the trivial-case test | S0091 L188; S0113 L40 | I-K1 ("session holds the current rule at START") is satisfied by a system that never starts a session. With zero receipts plus a refusing gate, that is S0113's total halt. So CON-0117 has a must-never part and no must-do part. Needed: a requirement that governed work proceeds (Req, §9.1 C2). | CON-0117 is degenerate as an invariant. | Pair I-K1 with a Req. Treat C1 and C5 together as one result. |

### 3. Concept verdicts

| ID | Verdict | Reason / scope | Kernel or application · minimal · stable |
|---|---|---|---|
| CON-0114 | **INVALID** as stated | Findings 1 and 3. **Valid core:** a totally derived read rule cannot justify a state boundary, and totality presupposes a functional selector. | Kernel (meta-law about I) · minimal · stable |
| CON-0115 | **PROVISIONAL** | Faithful to the source, but one line of support, and no invariant owner (finding 2). Scope: one assurance design. | Application. Not minimal: derivable from "supersession is a link" plus one authoritative home per key (RC-9). Stable. |
| CON-0116 | **INVALID** as stated ("closes") | Finding 7. **Valid:** the closed input set as a *declared* constraint of one engine. Storage channel closed by declaration; role channel unreachable by any input-set rule. | Application. Derivable from P-E plus RC-37a, so not minimal. Stable. |
| CON-0117 | **PROVISIONAL** | Core ("recording ≠ reaching the actor") is from one line, n = 2, same day. **The clause "order stated → distributed → applied → enforced" is INVALID:** AST-007 enforces on sessions that never received the rule, so enforcement does not presuppose distribution. Degenerate without a Req (finding 8). Not new (finding 4). | Kernel candidate as a required capability (T9-01). Not minimal versus §0.7. Stable only if "session" is generalised to "actor at the time of acting". |
| CON-0118 | **INVALID** as stated ("inert whatever r says") | Finding 6. **Valid:** a two-valued response keyed to c cannot fire where c cannot be decided. | General logic, derivable from CON-0108 plus E1/E3, so not minimal. Stable. |
| CON-0119 | **INVALID** as a three-class scheme | "Unproven regardless" is defined as "novel AND undemonstrated" (S0046 L57). So the scheme mixes two variables, novelty and demonstration. "Extended" items are equally undemonstrated in use (map §8), and "rediscovered" patterns do not validate this realization (§1.16.8, S0005; home missed). **Valid:** two axes, plus "no external instance found" as a search-scoped result. | Method, not kernel. Stable. |
| CON-0120 | **VALIDATED** | Scope: absence claims from searches. Read "exhaustive" as "scope, method and completeness stated", because S0091 L681 admits paraphrases are not covered. The book itself violates the rule (finding 4). | Method (Ch. 8), not kernel. Minimal. Stable. |

### 4. Coverage

**Inventory points against the book, ranked:**

| Inventory point | Mark | Comment |
|---|---|---|
| S0049 uniqueness / derivation-not-state | COVERED, DISTORTED in C1 | Finding 1. Missed home: §1.14.1 / §1.16.8, "current authority derived, never stored; a stored is_valid boolean is the bug". |
| S0050 R-6: the assessor establishes its own assessment, no human act | **MISSING** (the tension) | §9.4 C5 calls it an invariant-owner answer. Producer = establisher looks like self-acceptance (§1.14.3). It is mitigated only if establishment changes no standing (R-8 says so); the book should state that premise. |
| S0049 F-4: establishment rests on standing that is itself Declared by default | **MISSING** | §9.4 C6 records the regress without analysis. The regress "terminates" only because records are finite. No bound on the outcome follows unless candidate INV-ATTR-6 is adopted. Termination is asserted, not proved. |
| S0091 "every invariant BC-7 owns is mechanically enforced" | COVERED, **uncritically** | The source's own table: I-4 is owned by BC-7/BC-5 and only "declared" (S0091 L182). That is a counterexample to "every". BC-7 is the engine, so part of the claim is true by definition. §9.5 C3 calls it a corroboration. |
| INV-ATTR-1 / INV-ATTR-2 homonym (my step 1) | Resolved as **metonymy, not homonym** | S0049 L25–28 shows "separation Declared" is INV-ATTR-2's default applied to independence claims. S0091 L185 and S0113 L68 name the rule by its consequence, and S0113 L68 is garbled (source defect). S0113's "INV-ATTR-1: no gate reads identity" (L44) is an unverified reading. The book avoided it, but its paraphrase "while identity is not attestable" drops it. Neither section states the link, and that link refutes "vacuous" (finding 1). |
| "Current" in three senses | MISSING (homonym) | Current claim (not superseded) · current rule (latest adopted version) · currently authoritative (§1.14.1, time-derived). Qualify each use in §9.4 and §9.5. |
| S0048 "precedent becomes implicit rule" | COVERED; home missed | Ch. 2 §2.8.7: "a specific, consequential value that nobody chose", an ungoverned default. |
| S0113 five knowledge conditions | OPEN, not mapped | Map Missing, Stale, Superseded, Invalidated and Disputed onto E1–E10 and onto §1.16.6 live-with-detection. Disputed = Ch. 2's "Both". |
| S0046 selection: "every strongly-validated edge is a GOVERNANCE edge" | MISSING | The edges were chosen and validated by a governance-oriented programme. The "external-convergent" side runs through internal matrices. §8.4 C2 discounts only the internal side and the gap side. |
| S0091 F-3″ residual: the 104 hits were not classified file by file | DISTORTED (dropped) in §8.4 C4 | The source limits its "no adopting act" claim to four artifact classes (L727). |
| S0046 KG-as-projection, PROV mapping | JUSTIFIABLY EXCLUDED | Covered by P-8, §1.14.4. |
| Arithmetic slips: E-R1 (14 vs 13), 39/40 tokenRefs, Person() counts, S0113 "three" vs "four" | Source defects, JUSTIFIABLY EXCLUDED | The theory does not rely on these numbers. Checked: 5+7+1+1 = 14, 5+6+1+1 = 13, 2+4+4+2+1 = 13 (`python3`, step 1). |

**New forks or topics the theory map lacks:**
- **(a) Pinned vs resolved-at-use references:** evidence needs pins (§1.16.6); operating rules need resolution at use (C-10).
- **(b) Uniqueness vs aggregation** as two ways to make an outcome definite.
- **(c)** Mapping S0113's five knowledge conditions onto the kinds of nothing.
- **(d)** Self-establishment vs producer ≠ verifier, when an act changes evidence but not standing.

### 5. Next steps, in dependency order

| # | Action | Done when |
|---|---|---|
| 1 | Re-run the existing-homes search with synonyms: assembl\|working context\|live\|pinn\|fail(ing)? (open\|closed)\|advisory\|decidable\|never stored. Append the hits to STEP8. | §0.7, §1.16.6, §1.14.5, §1.17.3, CON-0108, CON-0081 and §2.8.7 are listed or explicitly excluded. |
| 2 | Re-home §9.5 C1 under §0.7 / §1.16.6, and §9.5 C5 under §1.14.5. Shrink C5's open question to the residue. | C5 no longer calls INV-A3 established. |
| 3 | Restate CON-0114 and §9.4 C2 (findings 1 and 3). | The premise "outcome = the established assessment" is explicit, and §1.16.1 is cited. |
| 4 | Add an invariant-owner row for CON-0115 (finding 2). | Owner named, or "detection only" stated. |
| 5 | Fix CON-0116 "closes", CON-0117's order clause and its missing Req, CON-0118's scope, and CON-0119's axes. | Register meanings match the verdicts. |
| 6 | Earlier sections (report only): Ch. 1 §1.16.7 should say "global over the write set of the property". Map §2 line 40 likewise. | Next consolidation. |

No question for the Principal is needed.

### 6. Limits
- I did not read the rest of Ch. 1, Ch. 2 §2.8.3/§2.8.8 or §5.4 in full. CON-0081 and the E-codes are taken from the map and from §1.17.3.
- I did not re-run `corpus_quote_check.py`. Quote fidelity was checked by eye against my step-1 reading for the cited lines only.
- INV-ATTR-1's original text is not among the allowed sources. The "no gate reads identity" reading stays undecided.
- The §8.4 C5 observation about the book's own scripted-replace guard is reported, not shown; I did not check it.
- Searches I ran: `book_search.py "decidable|verdict set|INCONCLUSIVE" "fail(s|ing)? (open|closed)" "context assembl|working context|bootstrap|propagat" "precedent|inherit|implicit rule" "MARK_UNKNOWN|never a pass|default" "one current|current claim|superseded claim" "trivial case|must-do|does nothing"` and `"PM-1" "derived and never stored|never cached|is_valid" "Two keys|earns|cannot effect" "pinn"`. Lexical only: a miss there is not absence either.

---

### Appendix: normal findings
1. §9.4 C1 "Link" to §9.1 C1 is a loose analogy. In §9.1 the trivial solution is doing nothing; in §9.4 it is a default that must return a value, which is a must-do. Keep it marked as an analogy.
2. §9.5 C5 cites S0113 L44 as "identity is not attestable". The source's ground is INV-ATTR-1 plus D3.2, which put identity binding outside C-10. Quote the source's ground.
3. §9.5 C1 Int ("a gap at 'distributed' … is a gap in the system") generalises from n = 2, one line of support, one day. Mark it Hyp.
4. "Missing" now has three senses: S0113's (no receipt), the gap operator's (CON-0081), and E1. Qualify it.
5. "Enforcement" for C-14 (absent → live → CONTESTED) is the homonym already found in §6.4 C3 (lint severity vs blocking gate). Cross-reference it in §8.4 C3.
6. PM-1 in S0046 ("when provenance formalization opens") versus PM-1 in Ch. 1 §1.2 (the progression-model proposal): probably the same item, but unconfirmed. Check before any section uses it.
