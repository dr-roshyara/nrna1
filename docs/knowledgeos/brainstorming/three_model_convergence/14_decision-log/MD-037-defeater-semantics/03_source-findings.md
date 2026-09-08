# Source Findings

Every relevant passage from the 17-file search, classified per the required seven-way vocabulary:
(1) direct source definition, (2) source-stated rule, (3) source-stated example, (4) reconstructed
interpretation, (5) hypothesis, (6) analogy, (7) absence of evidence.

## `11-pairwise-results.md` (lines 27–28)

> *"The reason is structural, not about any of the three operators: a `Defeater` requires a `Claim`
> or a `Hypothesis`; a `Verdict` requires a `Claim` or a `Hypothesis` plus `Evidence`."*

**Classification: (2) source-stated rule.** Restates (does not extend) `06`'s own derivation table
— `Defeater`'s own input requirement, already implicit in the admitted evidence. No new information
about "surviving."

## `13-ddd-analysis.md` (lines 16–17)

> `Challenge`: *"unchallenged ≠ validated"* · `Claim → Defeater` · domain primitive.
> `Validate`: *"warrant requires evidence + assumptions"* · `Claim × Evidence → Verdict` · domain
> primitive; its standard is policy.

**Classification: (6) analogy/aphorism for `Challenge`'s own rationale, (2) source-stated rule for
`Validate`'s domain formula.** Notably, this DDD-dedicated document's own formula for `Validate` is
the **baseline** (`Claim × Evidence → Verdict`) — it does not mention `V6` or a `Defeater`-inclusive
alternative anywhere. The "unchallenged ≠ validated" aphorism is the closest thing to a plain-language
statement of the `fit ⇒ validation` concern found in this document, but it is not a formal definition
— it is a slogan-length domain rationale.

## `15-falsification.md` — F-9, F-10 (lines 15–16)

> F-9: *"Show a `Defeater` obtainable from `Hypothesize` (generate a competitor) + `Discriminate`
> (prefer it). Not tested here — plausible and important. **Real open falsifier.**"*
> F-10: *"Show warrant is computable as `Discriminate` over `{claim, defeaters}` given evidence.
> Under V6 a verdict already **requires** a surviving-defeater step, halfway to this. **Real open
> falsifier.**"*

**Classification: (5) hypothesis — explicitly labeled "open," "not tested," "halfway."** This is the
single most direct engagement with "surviving a defeater" in the whole series, and it explicitly
frames it as an **untested, open research question**, not a settled definition. The phrase "surviving-
defeater step" is used, not defined — F-10 treats it as a given label for V6's own requirement,
exactly matching `12`'s own usage.

## `17-open-questions.md` — Q-6, Q-7 (lines 19–20)

> Q-6: *"Is `Challenge` derivable as `Hypothesize` a competitor + `Discriminate` in its favour?
> (F-9, untested)"*
> Q-7: *"Is `Validate` derivable as `Discriminate` over `{claim, defeaters}` given evidence?
> (F-10; V6 is halfway there)"*

**Classification: (5) hypothesis, explicitly catalogued as "Substantive but non-blocking."** This is
the research programme's own self-audit stating, in its own structured open-questions register, that
this exact question is unresolved and does not block further work — a direct, structural confirmation
that no formal answer exists anywhere in this series.

## `18-audit-response-and-protocol-audit.md` §3.3 (lines 107–116)

> *"V6 — does it bake in the answer? Partly; here is the exact scope. (audit §10) Conceded: `V6 ⇒
> Challenge required` is close to definitional, and V6 is not evidence for `Challenge`. But V6 was
> never the evidence. **The evidence is V0**: under the baseline model, with `Challenge` removed, a
> `Verdict` is still reachable — a system that validates without ever being able to attack. V6 is the
> **contrast case** showing a model in which that is structurally impossible. The finding is
> therefore about a defect in the baseline capability model, not about the operator..."*

**Classification: (1) direct source definition of what V6's own evidentiary role is (not a definition
of "surviving," but a definition of what work V6 does in the argument) + (4) reconstructed
interpretation of the audit's own prior concern.** This is the single most load-bearing passage found
by this study — it explicitly, directly states that V6 is a **contrast/illustration device**, not
independent proof, and that the real evidentiary weight sits with `V0`'s own reachability property.
This matches MD-036's own independently-reached conclusion (`04` of that study) almost exactly,
without MD-036 having had access to this passage.

## `18` §5.3 — the invariant `I9` (lines 297–316)

> *"The audit lists as candidate invariant **I9**: `Verdict ⇒ Defeater consideration`."* Followed by
> a table: under `V0`, `Challenge` is the sole custodian of `I9`; under `V8` (a different,
> audit-proposed variant, not `V6`), two operators (`Hypothesize` and `Infer`) jointly become
> custodians — "the operator count falls by one and the invariant's attack surface doubles."

**Classification: (2) source-stated rule, for a *different*, though closely related, invariant.**
This is the closest thing to a formally-named invariant anywhere in the series — but it is named
`I9`, stated as "Defeater **consideration**," not "surviving a defeater," and it is analyzed only
for `V0` vs. `V8` (the audit's own proposed `Challenge`-elimination variant), never for `V6`
specifically. **"Consideration" and "survival" are not shown to be the same condition anywhere** —
a defeater could be considered and then defeated itself (i.e., not survive) without `I9` (as stated)
distinguishing that case. This is flagged explicitly, not glossed over.

## `18` §1, item A-7 (line 33)

> *"A-7 | V6 is close to definitional | **ACCEPTED** — see §3.3 for the precise scope of what it did
> and did not establish"*

**Classification: (1) direct source statement**, cross-referencing the §3.3 passage already quoted
above — the series' own audit-response table explicitly concedes V6 is "close to definitional," i.e.
close to a tautology (a defeater-survival requirement structurally guarantees a defeater was
considered, almost by the wording alone) rather than an independently-derived semantic result.

## `FINAL-kernel-reduction-report.md` — the Validate row and Q7 (lines 324, 371)

> Falsification test for `Validate`: *"F-10: `Discriminate` over `{claim, defeaters}`"* — untested,
> confidence "medium." Q7: *"`Validate` + `Qualify` (uncertainty and assumptions — both ride on
> `Verdict`, which requires `Evidence`)"* — no mention of `Defeater`/`V6` at all in this specific
> answer, which concerns the `Qualify`/`Evidence` dependency instead.

**Classification: (7) absence of evidence** for a formal defeater-survival definition in this
document specifically — it restates the same untested-falsifier framing found in `15`/`17` without
adding new content.

## Files with zero relevant hits, confirmed by full read (not merely grep)

`00-INDEX.md`, `01-research-question.md`, `02-evidence-matrix.md` (one incidental "Challenge...
produce a defeater" corpus-support row, already covered by `04`'s own admitted table),
`05-operator-capability-matrix.md`, `07-ablation-design.md`, `08-scenario-suite.md`,
`09-simulation-design.md`, `10-ablation-results.md`, `14-alternative-kernels.md`,
`16-negative-results.md`, `19-directive-adoption-and-research-restructure.md` (discusses `Validate`'s
status extensively, §10, but never engages the defeater-survival question specifically — see `05`).
**Classification: (7) absence of evidence**, confirmed by direct reading, not inferred from a title.
