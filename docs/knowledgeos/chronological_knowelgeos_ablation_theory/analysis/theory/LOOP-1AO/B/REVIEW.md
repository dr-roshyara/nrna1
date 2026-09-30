# Independent review of ANSWER.json / ANSWER.md (analytic vs synthetic guards)

Evidence used: SCHEMA.md, EVENTS.json, CODING-LINES.txt. TASK.md was read only for the class definitions. Machine-readable version: `REVIEW.json`.

## Verdict in one paragraph

A's work is careful and mostly sound. It documents the recodings between CODING-LINES and EVENTS.json very well. I disagree with A on the class of **one item: REGISTER/k should be SYNTHETIC, not UNKNOWN.** A decided that item on how good the evidence was, not on what the words mean, and A leaned towards ANALYTIC because the coder treated the register scope as *constitutive*. That is exactly the "binding rule ≠ definition" confusion.

The larger problem is in **premise independence for START/s**. A marks 7 of 11 events INDEPENDENT, but the only basis is the coder's own event labels. For several events the coded value "not-authorized" / "authorized" was itself produced by applying an unstated *synthetic* rule, for example "Batch 7 frozen ⇒ not-authorized". That moves the binding rule into the coding step, where it looks analytic. I rate 2 of 11 INDEPENDENT.

The other disagreements are about evidence scope, wording, coding and alternative readings. None of them changes a class.

## 1. Evidence scope and verbatim quotes

- **Scope.** A cites no outside source. One exception: RAISE/e takes the *content* of its rule ("promotion requires ≥ 2 contexts") from TASK.md's illustrative example. The three evidence files show only `e="2+"` / `e="1"` and the label "(single occurrence)". Nothing in them says the count is of *contexts*, and the threshold is not stated as a rule. The pattern supports *some* threshold. The wording of the rule is imported. (D4)
- **Verbatim.** The quotes from CODING-LINES and SCHEMA are verbatim. Two minor faults:
  - The ADOPT/a quote reorders the positional arguments across the `...` elisions (`a="ARB-CHIEF" ... "REFUSED", "RULE"`; in the source the outcome comes first).
  - The RAISE/e "basis_quote" for EVENTS.json is a paraphrase in quotation form (`e="2+" → "PERFORMED" (#2, #3, #9, #10)`).

  Neither distorts the content. (D11)
- **Factual claims about the files: all checked and correct.** These cover the recodings (R-81..85 k/c, WP-4B s, 7A/7B s, L493-B a), the missing semobs.py lines 25/35/36, R-94 being absent from EVENTS, and the routes of L493-B and R-41.
- **Internal miscount.** ANSWER.md says START/s has "6 INDEPENDENT, 5 UNCLEAR". ANSWER.json actually has **7 INDEPENDENT** (7A, R-56, R-58·7B, R-58·7C, R-65, R-81, R-86) and **4 UNCLEAR** (R-72, WP-4B 08-03, R-79, 7B@R-47). (D3)

## 2. Definition or rule? Item by item

| Item | A | Reviewer | Comment |
|---|---|---|---|
| ADOPT/a | SYN | **SYN** | Role names carry no prohibition. Nothing defines ADOPT as reserved to the DA. Agree. |
| ADOPT/c | ANA | **ANA** | This holds only because SCHEMA defines the *field* as "role conformance", which is normative. The *value* "collapsed" names a fact, and the ban on collapse would be a separation-of-duties rule. The field definition is the only definition in the files, so ANALYTIC stands. It is the weakest ANALYTIC item. |
| AUTHORIZE-IMPL/a | SYN | **SYN** | The chain is a=Chief ⇒(rule) PREPARED ⇒(definition) NOT-IN-FORCE. One link is synthetic, so the whole link is synthetic. Note: PREPARED is *not coded* (s="guards-met" on both sides), so the contrast credited to a runs through an uncoded status. (D10) |
| OPEN-WORK/k | SYN | **SYN** | Agree. |
| REGISTER/k | UNK | **SYN** | See D1. |
| START/s | ANA | **ANA** | "authorized" / "not-authorized" contain the permission, which is TASK's own case. "permission-only" (R-79) is a synthetic sub-link, and both of us say so. But the analytic values are *outputs of rules* applied during coding (see §4). |
| START/t | ANA | **ANA (derivative of s)** | t alone never decides the outcome, since the same target gets both outcomes. The analytic content is the "for this act" index of s. t is not a separate guard. (D8) |
| RAISE/e | SYN | **SYN** | Counts are descriptive, so the threshold is contingent. Agree, but the rule's wording is imported (D4). |
| RAISE/t | UNK | **UNK** | Not analytic (descriptive values, no definition), and no rule is shown. Whether this is a guard at all is open. Agree. |
| ASSIGN-ID/h | SYN | **SYN** | Agree on the class, but A's argument is invalid (D6). "retired" could mean "withdrawn from assignment", and the text does not rule that out. |
| SUPERSEDE/k | UNK | **UNK** | The stated reasons for the refusals are in e, not k. Agree. |

Where A mixed up a binding rule with a definition:
- **(a) REGISTER/k.** A treats a scope rule as possibly definitional because the coder presented it as a rule the register is built on.
- **(b) The START/s premise coding.** Rules such as freeze ⇒ no start and plan approval ≠ execution authorization were folded into the value "not-authorized" and then read back out as definition.

## 3. Consistency across operations (START s, ADOPT a, ASSIGN-ID h, REGISTER k)

A describes its own criterion as "I classified by field type (descriptive vs normative)" (Ambiguity 1). That does not match what A did. The field for START/s is "state before", which is neutral, and A classified it by its *value* "authorized". The criterion A actually applied, reconstructed:

1. If the schema's field definition types the value as normative or descriptive, that decides.
2. Otherwise, the value's own meaning decides.
3. The operation is analytic only if it is *defined* as reserved.

Under this criterion A's results are consistent:
- s: neutral field, normative value → ANA.
- c: normative field → ANA.
- h: descriptive field → SYN.
- a: neutral field, role-name value → SYN.

**REGISTER/k is the exception.** Its field ("object kind") and values ("operational-acceptance", "constitutional-decision") are descriptive, and REGISTER is not defined anywhere. That is the same position as OPEN-WORK/k, which A classes SYN. A's reasons for UNKNOWN do not bear on meaning:
- "The register's scope is undefined." The work-opening instrument rule is undefined too.
- "The PERFORMED side is the rule itself." That concerns testability, not class.
- "The coder treated the scope as constitutive." A constitutive, binding rule is still a rule.

One side effect of the criterion is worth recording: it ranks c ("collapsed", a factual word) as analytic and h ("retired", arguably a status word) as synthetic. Value-level reading would suggest the opposite order. Field definitions are the only definitions present, so I accept the ranking. It is still the main place where an alternative reading could flip classes (§6). (D7)

## 4. ANALYTIC items: is premise independence supported or inferred?

CODING-LINES has **no quoted source basis**; A says so itself. Every INDEPENDENT judgement therefore rests on the *event label*, and the label is written by the same coder in the same line that codes the outcome.

**START/s, per event (reviewer):**

| Event | A | Reviewer | Why |
|---|---|---|---|
| R-72 WP-4B (proviso unmet) | UNCLEAR | UNCLEAR | Agree |
| WP-4B 08-03 | UNCLEAR | UNCLEAR | Agree (s=UNK in EVENTS) |
| R-79 WP-8 | UNCLEAR | UNCLEAR | Agree |
| R-47 7A | INDEP | INDEP | The label names a prior act "after AUTHORIZE(7A)" |
| R-47 7B | UNCLEAR | UNCLEAR | Agree (recoded from auth-full(7A)) |
| R-56 7B | INDEP | INDEP | "execution not issued" directly states that the EXECUTION-route authorization is absent |
| R-58 7B | INDEP | **UNCLEAR** | Authorization and PERFORMED start are in one row. No separate act is observed, so "before the act" cannot be checked, and the prediction is fixed by the coding |
| R-58 7C | INDEP | **UNCLEAR** | No start attempt is shown. REFUSED is read off s |
| R-65 7C | INDEP | **UNCLEAR** | Same as R-58 |
| R-81 §12 (Batch 7 frozen) | INDEP | **UNCLEAR** | "frozen" is a fact, but s="not-authorized" is *derived* from it by an unstated rule (freeze ⇒ no execution authority). SCHEMA allows DERIVED only "by an explicit rule". The freeze rule is synthetic |
| R-86 §12 (Batch 7 released) | INDEP | **UNCLEAR** | "released" ≠ "authorized". The authorization presumably comes from the R-86 adoption in the same cluster, which is not stated |

Tally: A has 7 INDEPENDENT / 4 UNCLEAR. I have **2 INDEPENDENT / 9 UNCLEAR / 0 OUTCOME-DERIVED shown.** (D2)

A saw the reverse circularity at R-58 and R-65 but still marked them INDEPENDENT. If the outcome is derived *from* the premise, the event does not test the premise against an independently observed act, so INDEPENDENT overstates the case.

**START/t:** I agree with A (7A INDEPENDENT; 7B scope INDEPENDENT, "no other 7B authorization" UNCLEAR).

**ADOPT/c:** I agree, all UNCLEAR. For the two R-81..85 events the source has *no* c value (identity only). "conformant" is an unsourced fill, which goes against "Nothing is filled in silently".

## 5. SYNTHETIC items: is the refuting contrast in EVENTS.json?

| Item | Contrast in EVENTS.json? | Reviewer assessment |
|---|---|---|
| ADOPT/a | Yes (R-81..85-annotation vs R-86) | Strict under R2, 1 cluster-pair, **WEAK**. A's caveats ("declined" might be CHOICE; the k/c fills) are correct. |
| AUTHORIZE-IMPL/a | Yes (R-70 vs R-89) | Only a differs among the *coded* fields, but the outcome is NOT-IN-FORCE and the operative status PREPARED is uncoded. WEAK at best. |
| OPEN-WORK/k | Yes (R-60 ×2) | Within one cluster, **WEAK**. The refiling was caused by the rule, so it is not an independent test. Agree. |
| RAISE/e | Yes (7 R-36 items) | 12 strict pairs, 1 cluster, **WEAK**. The items have no coding lines. L493-B vs the R-36 2+ items is at most POSSIBLE (k, t, a differ). Agree. |
| ASSIGN-ID/h | In form only | The PERFORMED side "ASSIGN a never-used number" (cluster "register-numbering") is a generic practice, not an anchored historical act. That is the same defect A used to deny REGISTER a genuine contrast, yet here A says "YES in form, WEAK". Treated consistently, **neither has a genuine refuting contrast**. Formally both are WEAK. (D5) |
| REGISTER/k (reviewer: SYN) | In form only | The PERFORMED side is the "(rule)" statement, and both sides are in one cluster. **No genuine test.** |

## 6. Alternative readings that would flip a class

- **ADOPT/c → SYNTHETIC** if "collapsed" is a plain fact (preparer = adopter) and the ban is a separation-of-duties rule. The text does not rule this out. Only the field *name* holds ANALYTIC.
- **ASSIGN-ID/h → ANALYTIC** if "retired" means "permanently withdrawn from assignment". The text does not rule this out. A's reason for rejecting it ("many numbering schemes do reuse freed numbers") shows that the *rule* is contingent, which is true of every analytic item's background rule too. It does not show that the *value* lacks the prohibition. (D6)
- **ADOPT/a**, an alternative A did not consider. R2 declares c intrinsic to the object, but *role conformance of an adoption* may depend on who adopts. If the Chief adopting what the Chief prepared is the same kind of "collapse" as at R-91, then the Chief/DA pair differs in a **and** c. It is then not strict, and the Chief's refusal is the ADOPT/c analytic guard in disguise. R2 formally rules this out; the substance is open. (D9)
- **START/t → SYNTHETIC** if authorizations can be batch-level. A says "nothing in the files suggests that", but R-81 and R-86 ("Batch 7 frozen / released") show that *batch-level* states do govern START. So batch scoping is attested as a mechanism. Per-package issuance (7A@R-47, 7B@R-58, 7C@R-65) is the observed *practice*. This weakens A's rejection but does not flip the class, because "AUTHORIZE(7A)" is indexed to 7A in the label. (D8)
- **REGISTER/k → ANALYTIC** only if a text defined the register as the register of constitutional decisions. No such text exists. A binding scope rule is not that definition.
- **START/s → partly SYNTHETIC**: R-79 (permission-only) and, if the coding is unpacked, R-81 (freeze). The coded value set is organized around authorized / not-authorized, so the class stays ANALYTIC.

## 7. Consequence: how each guard can be tested

**Testable by prediction (SYNTHETIC):**
- ADOPT/a, AUTHORIZE-IMPL/a, OPEN-WORK/k and RAISE/e each have a contrast in the data, and every one is WEAK: a single cluster-pair or within one cluster.
- ASSIGN-ID/h and REGISTER/k have only pseudo-contrasts, where the PERFORMED side is a rule or practice statement. They are testable in principle and untested in the data.
- The sub-value START/s = permission-only (R-79) is also prediction-testable, but has no contrast.

**Testable only by premise independence (ANALYTIC):**
- START/s, START/t (only through s) and ADOPT/c.
- For these, prediction can expose only miscoding. The real question is whether the premise was coded before and apart from the outcome. With no quoted bases, most events cannot show that.

**Neither, until defined:** RAISE/t and SUPERSEDE/k.

## Disagreements (typed, not averaged)

| # | Item | Type | Flips class? |
|---|---|---|---|
| D1 | REGISTER/k: UNKNOWN → SYNTHETIC | formal reasoning | **yes** |
| D2 | START/s premise status: 7 INDEP → 2 INDEP | independence | no |
| D3 | ANSWER.md miscount (6/5 vs 7/4) | wording | no |
| D4 | RAISE/e rule content imported from TASK example | evidence scope | no |
| D5 | ASSIGN-ID/h contrast "YES in form" vs REGISTER "NO": inconsistent | coding | no |
| D6 | ASSIGN-ID/h: contingency argument does not decide analyticity | formal reasoning | no |
| D7 | A misstates its own criterion ("by field type") | wording | no |
| D8 | START/t is derivative of s; batch-level scoping *is* attested | source interpretation | no |
| D9 | ADOPT/a: act-relative c alternative not considered | source interpretation | no (under R2) |
| D10 | AUTHORIZE-IMPL/a: operative PREPARED status uncoded | coding | no |
| D11 | Reordered quote (ADOPT/a); paraphrase as quote (RAISE/e) | wording | no |

No *substantive* disagreement: I accept A's underlying theory of analytic vs synthetic. The disagreements concern how A applied it.
