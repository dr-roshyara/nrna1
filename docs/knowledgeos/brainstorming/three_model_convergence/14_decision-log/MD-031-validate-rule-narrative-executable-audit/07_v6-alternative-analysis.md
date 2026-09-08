# V6 Alternative Analysis

## Does `06-composition-rules.md` select, present alternatives, remain silent, predate, or explain
a preference among rules for the `Verdict`-producing step?

**Silent.** `06`'s derivation table (line 41) states exactly one rule for the `warrant-assessment`
step: `Claim,Evidence | Hypothesis,Evidence → Verdict`. No alternative formulation, no mention of a
`Defeater`-inclusive variant, no discussion of "validation presupposes challenge," and no reference
to any variant-testing methodology at all appears anywhere in this document. The document's own
"What `Reach` deliberately does NOT include" section (lines 71–78) discusses only what the *baseline*
engine excludes by design ("no obvious closures added by hand," "no helper functions") — it does not
discuss robustness variants as a category.

## Does this establish the executable rule is "wrong," or that `V6` is spurious?

**No — and this study does not draw that conclusion.** Per the authorization's own explicit
instruction: "Do NOT treat the existence of V6 as proof that the executable rule is wrong. It
establishes only that the executable lane contains internal variation unless stronger evidence says
otherwise." `06`'s silence on `V6` is consistent with several distinct explanations, none of which
this study can distinguish from the evidence available: (a) `06` documents only the baseline design,
and `V6` is a later, purely-code-side robustness exploration never written up narratively; (b) `06`
predates `V6`'s addition to the codebase and was simply never updated; (c) `06`'s authors considered
and rejected the `V6` alternative without recording that reasoning in this document. No evidence
distinguishes these.

## Does the narrative source resolve the internal variation MD-030 found?

**Only partially, and only for the baseline, not for the variation itself.** `06` gives independent,
narrative-lane textual support for the baseline two-input rule specifically (the same rule `V0` is
explicitly labeled as tracking, per MD-030 `03`). It does **not** discuss, endorse, or rule out `V6`.
So: the *baseline* rule now has a second, narrative source behind it (though common-cause, not
independent — per `06`); the *alternative* rule (`V6`) still has only the executable lane's own
internal robustness-testing framing behind it, unchanged from MD-030's own finding.

## Net effect on MD-030's own qualification #3 (`08`, "internally contested even within its own lane")

**Narrowed, not removed.** MD-030 correctly found the two-input rule is not presented, even
internally, as the codebase's sole answer. This study adds: the *narrative* lane (`06`), so far as
read, presents it *as if* it were the sole answer — with no hedge, no mention of `V6` — which is
itself worth flagging as a discrepancy in confidence-framing between the two media, not resolved
here. The underlying fact remains: at least one tested alternative rule exists for this exact step,
and no source read by this study (narrative or executable) explains why the baseline should be
preferred over it.
