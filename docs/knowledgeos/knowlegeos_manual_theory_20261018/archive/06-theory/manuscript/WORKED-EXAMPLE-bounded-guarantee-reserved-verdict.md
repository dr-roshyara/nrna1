# Worked example: a bounded guarantee, the reserved-verdict rule (2026-10-18)

*Status: **analysis of a proposed principle**, prepared for the Principal's review. The principle "Bounded Governance Guarantees" is the Principal's proposed direction, as analysed in the ChatGPT review; it is **not adopted** and is **not written into the book**. No chapter was changed. The code was read and probed read-only. The probe ran in the scratchpad against the unmodified class files and is not part of the repository.*

## 1. What KnowledgeOS theory requires (normative)

**R.** A mechanism may not produce a verdict that belongs to a review or certification act: PASS AFTER CORRECTION · EMERGENT · CERTIFIED. *(ARCH-INV-1 / AP-1, AP-8: "knowledge feeds authority, it never holds it"; Ch. 1 §1.9, §1.14.2.)*

**R** says nothing about any implementation, and it is not evidence that one exists. Its **general** form is broader than any code. A mechanism must not produce **the effect** of such an act, whatever vocabulary it uses: a gate switched to blocking by configuration is an instance (Ch. 1 §1.14.5). The example below examines only the narrow form, "no reserved value in the governed verdict type".

## 2. What the examined implementation demonstrably enforces, and within which boundary (descriptive)

### Evidence map

| # | Path by which a reserved verdict could appear | Evidence | Kind of evidence | Result |
|---|---|---|---|---|
| P1 | `Assessment::of(reserved, …)` | `Assessment.php` L38–42; probe with all three reserved values | code reading + execution | **blocked**: `InvalidArgumentException` |
| P2 | `new Assessment(...)` from outside the class | constructor `private` (L29); class `final readonly` (L27); probe | code reading + execution | **blocked**: `Error`, private constructor |
| P3 | a subclass that relaxes the check | class is `final` (L27) | code reading | **blocked** by the language |
| P4 | changing the verdict after construction | properties `readonly` | code reading | **blocked** by the language |
| P5 | reflection: `newInstanceWithoutConstructor` + `setValue` | probe, PHP 8.5.11 | execution | **not blocked**: object created with CERTIFIED |
| P6 | a closure bound to the class's scope | probe | execution | **not blocked** |
| P7 | `unserialize` of a crafted string | probe | execution | **not blocked** |
| P8 | P5–P7 used anywhere in `scripts/` or `.claude/scripts/` | text search for `unserialize`, `newInstanceWithoutConstructor`, `bindTo`, `Closure::bind` | search | **none found** (absence in today's tree only) |
| P9 | the reserved value used outside `Assessment` | `Verdict::CERTIFIED` is an ordinary enum case (probe line 6); search for reserved literals in executable code | execution + search | **not constrained**. Today the literals occur only in `Verdict.php` and one test |
| P10 | another vocabulary entirely | `AssessmentService` emits plain strings (L10–41); `recommendation-decide.php` records ACCEPTED · IGNORED · DEFERRED, a human's disposition of a recommendation and not a verdict | code reading | **outside the guard**, which knows only `Verdict` |
| P11 | aggregation of assessments into a handoff verdict | `AssuranceHandoffReport::aggregateVerdict()` L121–141 returns only PASS, WARN, INCONCLUSIVE or FAIL; `of()` admits only `Assessment` objects (L69–72) | code reading | **closed** under the four emittable values |
| P12 | the existing test | `AssessesIdentifierIntegrityTest` L117–121: `of(CERTIFIED)` throws | test exists (not run by the main `phpunit.xml`, whose suites do not include `scripts/`) | covers **one** of three reserved values on P1 |

### The bounded claim the evidence supports

> **Within the `EngineeringKnowledge` library, no `Assessment` object can carry a reserved verdict when it is created through the language's ordinary means (P1–P4).** This is established by code reading and by execution against all three reserved values. The guarantee is **not** a compile-time guarantee. It does **not** hold against code that deliberately uses reflection, scope-bound closures or deserialization (P5–P7), none of which occurs in today's tree (P8). It says **nothing** about reserved values used outside `Assessment` (P9) or about other vocabularies (P10).

Two corrections follow for the book's current wording (§1.9, "one runtime guard on one code path"):
- **It is stronger than stated.** The guard covers every ordinary construction path of the *type*, not one call path, because the constructor is private and the class is final and read-only. The independent reviewer's "one code path" was itself an overstatement in the other direction.
- **It is weaker than an invariant.** Three standard language mechanisms bypass it.

So the precise statement is **one type, all ordinary construction paths, under a non-adversarial-code assumption.**

### What happens outside the boundary (failure behaviour)

If a reserved verdict did enter through P5–P7:
- `aggregateVerdict()` would **count it as PASS**. CERTIFIED is not FAIL, WARN or INCONCLUSIVE, so the loop leaves `PASS` standing (L123–139). The aggregate stays inside the emittable set, but it **fails open**: an unrecognised value becomes the most permissive result;
- `StructuralCliReporter::badge()` would print `[CERTIFIED]` through its `default` arm (L110);
- `knowledge-lint.php` would omit it from the summary counts (L266–269) but still list it, since `isClean()` is false.

None of this happens today. It shows that a bounded guarantee needs a statement of **what the system does when the boundary is crossed**, not only where the boundary lies.

## 3. What would justify a broader claim

| Broader claim | Additional evidence or design needed |
|---|---|
| "No `Assessment` with a reserved verdict exists anywhere, even with hostile in-repo code" | a static rule forbidding P5–P7 against `Shared\Domain` types (an architecture test or a lint rule), plus `__unserialize` validating through the same check. Or a separate enum for emittable verdicts, so the reserved values cannot be expressed in the type at all |
| "Reserved verdicts never cross any boundary" | the guard moved to, or repeated at, every boundary crossing: the reporters, serialization and exports. And the aggregate made fail-closed for unknown values |
| "No mechanism produces the effect of a review act" (the general **R**) | an inventory of every mechanism that can block, approve or change standing, in any vocabulary, each mapped to an authorizing act (Ch. 1 §1.14.5, premise P-E). Not achievable by strengthening this one class |
| "the test suite protects this" | the `scripts/` tests wired into a suite that actually runs; tests for all three reserved values and for P2 |

These are possible design steps, not recommendations to change code. Code is out of scope for this lane.

## 4. What the example teaches about the proposed principle

The principle worked: it turned "verified / compiled" into a statement that can be checked. The example also shows **four refinements** that the proposed wording lacks:

1. **The threat assumption is part of the boundary.** "Which paths" is not enough. The guarantee holds against *ordinary* code and fails against *deliberate* bypass. The scope must name the actors or code it is claimed against.
2. **Strength and scope are separate axes.** The guarantee is strong (enforced by the language and at runtime, not by convention) inside a narrow scope (one type). A single grade such as "verified" or "compiled" merges the two. The ChatGPT analysis noted this ("a bounded guarantee is not necessarily a weak guarantee"); the example confirms it.
3. **Failure behaviour outside the boundary is part of the claim.** Here it is fail-open in the aggregate (§2).
4. **Not every guarantee has a consistency boundary.** This guard checks a value, with no time window, so atomicity does not arise. The principle's "consistency boundary" applies to guarantees about state over time, which is why the check-and-effect guarantee is the right second example.

**Overlap check (canonical discovery, before anything is adopted).** The principle extends distinctions the book already has; it should consume them rather than add parallel terms:
- **realization ≠ enforcement consequence** (Ch. 2 §2.9);
- the four properties of a condition, including the **required consistency guarantee** (Ch. 2 §2.9). The ChatGPT text's "evaluability vs enforcement vs atomicity" matches these once "evaluability" means only *can it be determined*;
- **self-reports are claims** (Ch. 1 §1.12);
- the **kinds of evidence** table of the working method.

It also restates the repository's standing rule that "the strongest statement made must never exceed the strength of the available evidence". If adopted, it should **extend** that rule and the existing engineering standards (ES-005.4: never a copy), not stand as a new standard. That placement is the Principal's decision; I have not checked the ES documents for an existing home.

## 5. Proposed next steps (none started; each needs authorization)

1. **The Principal reviews this example** and decides whether the principle, with refinements 1–4, is precise enough to continue.
2. **Book revision R1-11d (proposed, not applied).** Replace §1.9's "one runtime guard on one code path" with the bounded claim of §2, including the bypass and fail-open facts. Type: new evidence, a traceability-level correction; needs no Principal decision under the continuity rule. I am holding it only so that it lands together with your view of the example.
3. **Second example: check-and-effect** (grant check vs effect; grant records outside version control), where the consistency boundary is the central question.
4. **Guarantee register:** claims in Ch. 0–2 that use *guarantees, prevents, enforces, atomic, compiled, verified*, each mapped with the six fields. This step is mechanical (a term search) plus reasoning per claim.
5. **One targeted independent review** of the revised claims: uncovered paths, races, weaker readings.

*Traceability:* the Principal's pasted ChatGPT analysis (2026-10-18); batch-4 review F3 and Q5 (`BATCH-04-checkpoint.md`); code read: `Verdict.php`, `Assessment.php`, `AssuranceHandoffReport.php`, `StructuralCliReporter.php` L103–112, `knowledge-lint.php` L255–284, `AssessmentService.php`, `recommendation-decide.php`, `AssessesIdentifierIntegrityTest.php` L106–131; probe at scratchpad `verdict-probe/probe.php` (not in the repository).
