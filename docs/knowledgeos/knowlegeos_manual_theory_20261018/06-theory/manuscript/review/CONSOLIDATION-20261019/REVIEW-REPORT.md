*Saved verbatim 2026-10-19 from the reviewer's step-2 hand-back (one step-2 message; total reviewer time ≈ 23 min by the clock, 7 min step 1 + 16 min step 2).*

# Consolidation review CONSOLIDATION-20261019: steps 2–3 report

Reviewer note: independence here means process independence only (separate session, blind step 1), not personal independence; same model family as the Professor. Time spent: about 30 min for step 1 plus about 45 min for steps 2–3. Read-only throughout; no files written.

## 1. Assessment

Within its stated scope, the Ch. 0–3 core is careful and mostly faithful to the sources. But 6 of the 24 self-validated concepts fail as stated (see the verdict tally in §3).

**Largest risk:** the kernel's state space is not defined in one place.
- "Authority" carries three different definitions.
- Ch. 1's labels on a claim (provenance, evidential grade, standing, visibility, owner, identity) and Ch. 3's "six dimensions" are two attribute systems that are never mapped onto each other.
- So RESEARCH-PURPOSE deliverables 1 and 5 (primitives; the kernel's type) currently rest on two uncoordinated models.

## 2. Findings (ranked)

| # | Sev. | Type | Claim / topic | Where | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | high | omission | **No single state space for a knowledge item** | Ch. 1 §1.3 (provenance, grade, standing), §1.7 (visibility, "a fourth label"), §1.10 (I-0 identity), §1.11 (owner) vs Ch. 3 §3.1 ("the six dimensions") | Provenance, standing, visibility, owner and identity lie outside the six. Evidence status (3 values) and §1.3's grade (4 values, including unknown) are not reconciled. T3-02 notices the owner case only. | The kernel's 𝒮 is undefined; "six dimensions" reads as complete when it is not. | Write one table: every attribute of a claim, its value set, and whether it is a dimension, a component or a derived attribute. Mark the six as the corpus's view, not the book's. |
| 2 | high | defect | **"Authority" has three meanings** | §1.2 / CON-0016 (provenance × standing) · §1.6 refined item 2 and THEORY-MAP §2 (act × scope × time) · §3.8 / CON-0064 ("authority = who justifies whom") | One term for (a) a claim's standing structure, (b) an actor's permission, (c) a justification order. | The candidate primitive "authority" is ambiguous; so is axiom A1's domain. | Split into claim standing, actor permission (grant) and justification. Give each its own ID. Rename the §3.8 order to "justification". |
| 3 | high | defect | **CON-0017 provenance: the value set is not a partition** | CON-0017; S0020 L86 (authorities.yaml: derived = "e.g. a generated index"); S0018 L79–80 | "Generated" (who produced it: tool or AI) and "derived" (relation to a source) are not exclusive: a generated index is both. There is no value for an original human-authored item. "Derived" is defined as "from an *authoritative* source", which ties it back to standing. | The flagship split (CON-0016) rests on a provenance half that is itself two questions in one field. | Provenance = (producer: human / tool-AI / external) × (derivation: original / derived-from {s}@t), both immutable. Immutability presupposes that a revision creates a new version (I-0, I-7). |
| 4 | high | defect | **CON-0020: the reversibility clause is refuted inside the book** | Ch. 4 §4.1 C3/C5; Ch. 0 I-6; S0018 L53 vs L62 | The book lowers the R-6 evidential standing by re-examining the counting class and independence. That is neither new evidence nor a refutation. Staleness lowers standing with time alone. The source calls P-10 "monotonic" and also "reversible by refutation". | "Moved only by accumulating evidence; reversible only by refutation" is false. | Keep only the ban: evidential standing cannot be decreed. Add re-analysis and decay as ways it falls. |
| 5 | high | defect | **CON-0021 contradicted by its own instance** | S0018 L50 (P-7 EEP: "human approval"), L63 | The EEP's stages need approval acts, so they are moved by acts, not only by time and effort. A failed verification re-enters implementation, so the stages are not irreversible. | The four kinds of change are not exclusive. | Keep only GEP-F1 (the work lifecycle ≠ the lifecycle of the artifact that governs it). Restate P-7 as composite. |
| 6 | high | defect | **CON-0057 "VALIDATED as a necessary condition" is refuted by §6.1 C4** | §6.1 C4; register; TODO T3-18 | §6.1 C4 says a method that declares its binding is not binding-free, yet portable. Then binding-free is **not** necessary. Second problem: "kernel" here is the portable engineering kit (S0024, S0002 A-5, S0008 "Engineering Kernel"), not the knowledge kernel of RESEARCH-PURPOSE. | Wrong concept status. There is also a risk of importing an artifact-portability test as a kernel-admission law. | Mark INVALID as stated. Restate as "domain-free ∧ no undeclared binding ∧ no product case law, necessary for portability of an artifact". Record the "kernel" homonym. Fix T3-18 vs the register. |
| 7 | high | defect | **RC-7 generalised beyond its source** | §1.6 RC-7 ("Int: a necessary condition on acts") | The source states it for **promotion** only (S0002 L37, S0018 L105). Counterexamples inside the book and corpus: §1.4, where 13 of 29 questions "need only a human act"; freezes by declaration (n≥3, S0017 L55); genesis, where no prior evidence exists (S0024 L159–162). S0007 L28 (TRL, GRADE) supports separating the two keys, not needing both. | As a kernel law, "every act needs evidence" contradicts §1.4's closure rules. | Scope RC-7 to promotion (a standing upgrade on an evidence ladder). Record the general form as rejected. |
| 8 | high | defect | **CON-0009 admission test as stated** | §3.2; S0016 L37–47 | "Sufficient" is a property of a set of dimensions, not of one dimension. "Necessary" is argued from history (errors the dimension would have prevented), not tested. Only orthogonality is applied. | The test that guards kernel minimality cannot be applied as written. | Restate: (i) non-derivability from existing dimensions on the cases in scope; (ii) at least one required distinction is inexpressible without it. Move sufficiency to the set. |
| 9 | normal | defect | **CON-0033 projection: the norm sits inside the definition** | §3.5; S0020 L89 | "Cited as authority by nothing" is a prohibition. Made definitional, no projection can ever violate it. S0029 (a self-declared projection that states "standing rules") cannot be expressed. Also S0020 L89 equates "cited by nothing" with rank 2. | The norm cannot be falsified. | Definition: regenerable from its sources (consider idempotence, §3.10 C7). Norm: a projection confers no standing (follows from A1 + provenance ≠ standing). |
| 10 | normal | defect | **CON-0013 / CON-0014 put contingent facts into the definitions** | register meanings | "Catalogued in advance" and "recorded only in a log" are facts about this repository (U-DM-1 asks whether to catalogue). Cataloguing governance decisions would make CON-0014 stop applying. | Two meanings merged into one. | Define both by resolution mode only; record the catalogue state as observation. |
| 11 | normal | defect | **CON-0064's "realization" is two relations** | §3.8; S0026 L34, L40, L85 | Knowledge → artifact is representation; capability → runtime asset is implementation. | The order mixes kinds; the formal result is untouched. | Split it, or justify the merge. |
| 12 | normal | defect | **Map and register overstate CON-0071** | THEORY-MAP §4; the register's term | The term "an obligation shrinks to its monitor's coverage" asserts the n=1 hypothesis. Only the definitional part was validated. | A result is presented as established. | Rename to "monitored compliance = scope ∩ coverage". |
| 13 | normal | defect | **I-7 used in two senses in Ch. 1** | L513, L646 (RO: a PKS holds no method) vs L553, L561, L1314, L1422 (GKC: supersession is a link) | It also says "S0008 does not name the document that numbers I-7", which is stale: T0-01 cites S0021 L111. The same problem appears in Ch. 0 L189 ("which I-1 forbids", RO numbering before §0.5 defines GKC's I-1). | Breaks the book's own rule RC-3. | Qualify every I-n by its source. |
| 14 | normal | defect | **§3.10 C2 misdates S0005 and S0008** (given as 08-02) | S0005 L8 and S0008 L8: 2026-08-03; Ch. 1 RC-19a has 08-03 | The table is date-ordered, and Ch. 0 L118 makes dates decide precedence. | The nesting positions are mis-sequenced. | Correct the dates. |
| 15 | normal | omission | **Several evidence scales are never related** | CON-0005; P-4 maturity; P-10 count; §1.3 grade; S0004 L72 ("TIME is not" validated) | The ordinal grade, the maturity ladder and the count are separate measures of evidence. | The "evidence" primitive is underspecified. | Map the scales, or declare them distinct. |
| 16 | normal | omission | **DOMAIN: evidence for the kind vs "owns nothing"** | S0009 L52 ("observed owners") vs S0026 L39; §3.8 C4 | The kind was confirmed by ownership criteria, then said to own nothing. §3.8 C4 does not note this. | Weakens CON-0065 further, and CON-0008. | Record it. |
| 17 | normal | omission | **The R-2 PAP/PDP/PEP mapping is accepted unchallenged** | Ch. 2 L679; S0005 L19 | Capabilities are advisory and DP-n is undeclared, so "DP-n + capability = PDP" names a decision point that does not decide. | Overstates external validation. | Mark it Int and give the challenge. |

**Source defects** (they count only if relied on):
- S0007 L64: "17 concepts" with buckets 11 + 3 + 5 = 19.
- S0008 L16: "14 + 3", but the table has 18 rows.
- S0011 L13 and L146: tallies do not match the table.
- S0024 L20–24: "4 evidence gaps", but L177–199 list 3.
- S0019 L31: "Mission (VACANT)" (covered).
- S0001 L76/L80: the closing restates the downgraded claim un-downgraded.

None of these is relied on by the 24 concepts.

## 3. Concept verdicts and marking (A)

K = kernel, App = application. Minimal: "no" names what it derives from.

| CON | Concept | Verdict (scope; reason) | K / App | Minimal | Stable |
|---|---|---|---|---|---|
| 0001 | nature | **VALIDATED** as a dimension distinct from representation (the RQ-002 encoding case). The value list is not closed in the corpus's own use: S0016 L127–132 ("capability spec", "constitution", "model"); S0009 L56 ("normative"). | K | yes | yes |
| 0002 | representation | **VALIDATED** as distinct from nature. Only non-identity is shown (one case), not full orthogonality; the value "executable" depends on F11. | K (carrier link) | yes | yes |
| 0004 | canon scope | **PROVISIONAL.** Values inconsistent ("product" missing; "runtime" is a location, not a reach). Relation to grant scope and to Ch. 2 applicability is undefined. | dimension K, values App | not shown (overlaps Ch. 2 §2.8 scope) | dimension yes, values no |
| 0005 | evidence status | **VALIDATED** as separate from standing (Ch. 1 §1.3). Values provisional: no unknown or conflicted; "inferred" merges deduction with induction (finding 15). | K | yes | yes |
| 0009 | admission test | **INVALID** as stated (finding 8). The restated form is PROVISIONAL. | K meta-rule | no (it is RESEARCH-PURPOSE's minimality test) | yes |
| 0013 | engineer decision | **VALIDATED** by resolution mode; drop "catalogued" (finding 10). | App (the eight); the distinction follows from A1 + "mechanisms record" | no | yes |
| 0014 | governance decision | **VALIDATED**, same restatement; "77" not verified. | K (= an act applied to a decision question) | no (the act) | yes |
| 0016 | provenance × standing | **VALIDATED**, restricted: the field asks two independent questions, which need separate components. The name collides (finding 2); the provenance values are defective (finding 3). | K | no (a pair: 0017 + standing) | yes |
| 0017 | provenance | **INVALID** as stated (finding 3). The core survives: origin is an immutable fact, distinct from standing. | K (history) | yes (the core) | yes |
| 0019 | governance-act progression | **VALIDATED** as "evidence cannot effect it", with validity periods read as part of the original act. | K law | no (follows from A1) | yes |
| 0020 | evidential progression | **INVALID** as stated (finding 4). Survives as "cannot be decreed". | K law | yes (the ban) | yes |
| 0021 | work-execution progression | **INVALID** as stated (finding 5). GEP-F1 survives. | App | no | yes (GEP-F1) |
| 0026 | authority systems | **PROVISIONAL.** n = 1 organisation. The subjects are not disjoint: policy includes adopting method. "Method & implementation" merges two holders. | App | no (an instance of subject-scoped grants, §2.8.2) | no (organisation's roles) |
| 0033 | projection | **INVALID** as stated (finding 9). | K (derived objects) | no | yes |
| 0057 | kernel admission (Tier-1) | **INVALID** as a necessary condition (finding 6). | App (artifact portability) | no (the top of 0075) | yes |
| 0064 | relation orders | **VALIDATED** (Ded): the four named relations on the listed edges; the labelled-union result is general. Excludes references (cyclic) and constrains. Caveats: findings 2 and 11. | principle K, content App | no (reachability of typed edges) | principle yes |
| 0068 | evidence entry | **VALIDATED** as a record design (four kinds + disclaimer, C1). | App | no (evidence, provenance, A1) | yes |
| 0069 | patterns from entries | **VALIDATED**, with C2's rule (the proposing instance does not count). | K evidence law | no (§4.1 independence) | yes |
| 0070 | counted class fixed | **VALIDATED** for counts used as confirmatory evidence. "Meaningful only if" is too strong for descriptive counts. | K evidence law | yes | yes |
| 0071 | monitor coverage | **VALIDATED**, definitional part only (finding 12). | App | no (a set intersection) | yes |
| 0072 | product binding | **VALIDATED** for methods with a declared input contract. Otherwise β is not identifiable (§6.1 C1). Same structure as SPECIALIZES / INSTANTIATES, but not linked to them. | K candidate (parameterised knowledge) / App | open (may reduce to rule applicability, Ch. 2 §2.2) | yes |
| 0075 | portability lattice | **VALIDATED** (Ded). All four (b, e) combinations occur in S0028 L125–133, so the lattice is not vacuous. | App | no (derived) | yes |
| 0076 | responsibility → component | **PROVISIONAL.** Condition (1) is implied by (3). Condition (2) is unjustified (only (3) is grounded, §6.1 C1) and undefined for AI sessions. Scope: claims of reuse. | App | no | yes |
| 0077 | deferral with criterion | **VALIDATED** as the row design. "Or an authority re-rules" makes the update rule vacuous unless re-rulings are recorded with reasons (S0029: 6 of 7 activations by acts or incidents). | App | no (a pre-specified rule + A1) | yes |

Tally: 15 VALIDATED (several restricted) · 6 INVALID as stated (0009, 0017, 0020, 0021, 0033, 0057) · 3 PROVISIONAL (0004, 0026, 0076).

Kernel primitives with **no concept ID among the 24**: **identity** (I-0, Ch. 1 §1.10; it has no register ID at all), **standing** (CON-0018 is INVALID), **visibility**, and **policy**.

## 4. RC-7 and RC-9 (B)

| Change | Verdict |
|---|---|
| **RC-7: two keys** | **VALIDATED (Src)** for promotion along an evidence ladder. **INVALID** as the Int generalisation "a necessary condition on acts" (finding 7). "Authority acts outside the catalogue by design" is **VALIDATED (Src, S0006 L36)**; whether that is the right shape stays open (U-DM-1). |
| **RC-9: identifier collisions** | **VALIDATED** and **undercounted.** Further collisions: H-3 (nesting vs "PURPOSE > CONTAINER", S0013 L71); C-1 (model amnesia vs Product Primacy, S0013 L32); D-n (domains vs docket packages); R-n (also S0013 rejected, S0017 rulings, S0019 responsibilities); G-n; E-n; and the book's own I-7 (finding 13). |
| **RC-9: "one home per rule"** | **VALIDATED** with scope: one authoritative home per rule per context (I-5 is per topic + context); derived copies allowed if they confer no standing. **Not a new primitive:** it follows from I-0 plus uniqueness of authoritative standing per subject and context. The corpus breaks it itself: S0029 states "standing rules" while calling itself a projection; S0013 restates canon. |

## 5. Sequence check, Ch. 0–3 (C)

**Already-resolved items: confirmed.**
- Confirmed: SEQ-0003, 0004, 0005, 0006, 0008, 0009, 0010, 0011, 0015.
- SEQ-0007: the RC-59a heading is present; its content was not re-derived.
- SEQ-0001: confirmed. Record mismatch: its TODO row says "CON-0084 ✓", but §1.5 marks CON-0084 invalid.

**SEQ-0002 (levels L0–L4): keep open, but removable.** CON-0056 can be restated on S0024's four named columns (method, binding, evidence, implementation) without the levels. Then close SEQ-0002 as "source-undefined label, not used in any derivation".

**SEQ-0012 (knowledge/capability circular): reopen as "not reduced".**
- The "reduction" depends on CON-0010, which is INVALID.
- It also depends on "only a capability executes by essence", which is I-11 itself. §3.8 C5 says exactly this.
- The book contradicts itself on the status: §3.8 C5 says "not reduced"; the Ch. 3 TODO says "reduced"; the map says "not reduced" (§1) and "partly reduced" (§8).
- **Close by stipulation:** record I-11 as the definition of a capability in sense B, plus its testable counterpart from §3.9 C3 (running an artifact changes no standing except through a recorded act).

**New sequence items:**
1. **"Authority" defined three ways** (finding 2). This is a circular or ambiguous base for A1.
2. **Unqualified I-n numbers.** Ch. 0 L189 uses RO's I-1 before §0.5 defines GKC's I-1 differently; Ch. 1 uses I-7 in two senses (finding 13).
3. **Ch. 0 §0.4.** The correction box (three moves, L217) sits above unrevised text ("exactly two ways", L222).
4. **Evidence values.** §3.1 lists 3 evidence values; §1.3 lists 4. Not reconciled.
5. **CON-0057 status.** The register says VALIDATED; TODO T3-18 says INVALID.
6. **Ch. 3 index** omits §3.10.
7. **§3.10 C2 dates** (finding 14).

## 6. Completeness against my step-1 inventory (D)

Ranked by importance.

| Item (inventory) | Mark |
|---|---|
| A single attribute model, from Ch. 1 labels vs Ch. 3 dims vs S0018's split | **MISSING** (finding 1) |
| Provenance values not exclusive (S0020 L86) | **MISSING** (finding 3) |
| P-10 "monotonic" vs "reversible" (S0018 L53/L62); COMPOSITE is a combination, not a kind of change | **MISSING** |
| P-7 includes approval acts (S0018 L50) | **MISSING** (finding 5) |
| The guard couples provenance and standing (S0018 L104) | **OPEN** (a C3 F-32 note only) |
| "Operational = baseline" vs OPERATIONAL-STATE (S0016 L54) | **MISSING** |
| Nesting (cross-source contradictions table) | **COVERED** (RC-26/26a, §3.10), with **DISTORTED** dates (finding 14). S0026 L86 "H-3 OPEN" is absent from the §3.10 C2 table. |
| Back-edge counts | **COVERED** (§1.8 table, §4.1 C7). S0019 L139 and S0024 L57 not listed (minor). |
| What executes | **COVERED** (F11). S0009 L57 ("runtime assets the only executable") not cited (minor). |
| Mission, init, PKS-as-projection | **COVERED** |
| DP-n canonical vs candidate | **COVERED** (CON-0034) |
| DOMAIN as a kind via owners vs owns nothing | **MISSING** (finding 16) |
| Ownership as a BC test (S0011 S-2 vs S0001 L29) | **MISSING** (normal) |
| Meta-model dimensions vs the authority split | **OPEN** |
| "Realizes" in two senses (S0026) | **MISSING** (finding 11) |
| S0003 calls it a "supplier relationship" while patterns are refused; the 5-for-5 convergence may be circular | **MISSING** (normal) |
| R-2 PDP mapping | **MISSING** challenge (finding 17) |
| Polanyi tacit/explicit offered as support for content/carrier (S0007 L29) | **MISSING** (low) |
| S0004 "TIME not validated" | **MISSING** (normal; part of finding 15) |
| FREEZE by declaration, n ≥ 3 (S0017 L55) | **not found** in the sections read; relevant to RC-7 |
| S0028 "~90%" figures unsourced | **MISSING** (low) |

**New topics and forks the map lacks:**
- **F13:** the kernel's attribute set (state space 𝒮).
- **F14:** the "kernel" homonym (portable engineering kit vs knowledge kernel).
- The three senses of "authority".
- Binding ≈ SPECIALIZES / INSTANTIATES: unify or keep apart.
- The relation between the evidence scales.

## 7. Next steps (in dependency order)

1. Write the attribute table and settle F13 (finding 1). **Done when** every Ch. 1 label and every Ch. 3 dimension has one row, a value set and a kind.
2. Split "authority" into three IDs (finding 2). **Done when** A1's domain names exactly one of them.
3. Restate CON-0017, then re-check CON-0016 (finding 3).
4. Restate CON-0020, 0021, 0009, 0033, 0057 and RC-7 per findings 4–9, and fix the register, TODO T3-18 and the map.
5. Close SEQ-0012 by stipulation, and SEQ-0002 via the CON-0056 restatement.
6. Qualify every I-n by its source, and fix the §3.10 dates.
7. Relate the evidence scales (finding 15). This is needed before Ch. 4's taxonomy of change.

No question for the Principal is required. F14 (which "kernel" the book means) is answered by RESEARCH-PURPOSE; it only needs to be recorded.

## 8. Limits

- Of Ch. 1 I read §1.1–§1.11 in full and only the headings of §1.12–§1.17. Of Ch. 2 I read only §2.4, §2.8.3 and §2.10 excerpts. The C3 and C4 review reports I checked by grep only.
- Not re-verified against configuration: authorities.yaml, statuses.yaml, the EEP. My statements about them rest on what S0018 and S0020 quote.
- "Not found" marks in §6 cover only the sections I read.
- Computation: none was needed beyond checking counts by hand.
