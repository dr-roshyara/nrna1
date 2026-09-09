# EKS-21 — A search that fails silently reports "nothing found", and "nothing found" is this programme's most common published result

**Status:** **BACKLOG · MEASUREMENT-VALIDITY EXPOSURE** — registered from `P-60` (`docs/knowledgeos/theory-extraction/78-P60-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — validity of negative findings.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no tool, and questions no finding that has been re-checked.**

---

## 1 · The problem in plain terms

A very large share of this programme's conclusions are **negative**: *"this concept appears nowhere in
the corpus"*, *"no document resolves this question"*, *"the matrix is entirely zero"*. Negative findings
are genuinely valuable here — several of the strongest results in the last ten reviews are negative.

⭐⭐⭐ **But a search that is broken and a search that finds nothing produce exactly the same output: zero.**

There is currently **no step that distinguishes them.**

## 2 · It happened, today, inside the review that is filing this item

While testing whether the corpus relates one concept to another, four searches were run and **all four
returned zero**. On that evidence the review was about to conclude that the relationship is entirely
absent from the estate — a conclusion that would have driven its verdict.

⭐⭐ **The searches were mis-addressed.** They were issued from the wrong folder, so the paths they were
told to search did not exist, and each one reported *"no matches"* rather than *"that location is not
there."* Re-run correctly:

| what was searched for | reported | ⭐ **actual** |
|---|---:|---:|
| the core concept | **0 documents** | ⭐⭐⭐ **917** |
| a specific phrase form of it | **0** | **13** |
| the first relationship | **0** | **10** |
| the second relationship | **0** | ⭐ **43** |

$$\boxed{\textbf{Four findings of } \mathbf{"nothing\ exists"} \textbf{, against } \mathbf{983\ documents\ that\ do.}}$$

⭐ It was caught only because the numbers **contradicted something already read by hand** minutes
earlier. ⚠️ **Nothing in the process would otherwise have caught it**, and the wrong conclusion would
have been published, cited by the next review, and inherited from there.

## 3 · Why this is a business problem, not a technical slip

**a. The failure mode is invisible by construction.** ⭐ A wrong positive result gets challenged,
because someone reads the quoted evidence and disagrees. **A wrong negative result has no evidence to
read** — there is nothing to check, which is precisely why nobody checks it.

**b. Negative findings here are load-bearing.** They are not asides. Recent reviews have concluded that
a term appears zero times, that a whole matrix is empty, that a body of work resolves nothing. ⭐⭐
**Downstream decisions rest on them**, including decisions about what work still needs doing.

**c. It corrupts the record in the most expensive direction.** A false negative causes work to be
**redone that was already done** — which is the failure already recorded twice in this backlog, now with
a second, independent cause.

**d. Confidence is inverted.** ⚠️ A clean round zero *looks* more authoritative than a messy partial
result. **The output most likely to be wrong is the one that reads as most certain.**

**e. The estate already requires the fix, and requiring it was not enough.** ⭐⭐⭐ The corpus search rule
already says that an absence claim must carry **a positive control proving the pattern can match**. The
rule exists. **It was not applied, and nothing noticed.** *(That is the finding here — not that the rule
is missing.)*

## 4 · What is *not* the problem

⛔ **Not the searching tools.** They behaved correctly: they were asked about locations that did not
exist, and truthfully reported no matches there.

⛔ **Not carelessness alone.** ⭐ A control exists precisely because careful people make addressing
mistakes. **A discipline that only works when nobody slips is not a control.**

⛔ **Not a call to re-verify every past negative.** That would be disproportionate. ⭐ The reasonable
question is which **load-bearing** negatives warrant a second look, and that is a judgement for whoever
activates this item.

⛔ **No tool, script, checker or template is proposed here.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **A published claim that something is absent should carry evidence that the search could have found
> it if it were there — and the search's scope should be stated in a form a reader can re-run.**

⭐⭐ **The cheapest version costs one extra search:** look for something that is *known* to be present in
the same place. If that also returns zero, the measurement is broken, not the corpus.

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-16` — derivation without consulting existing theory | both end in material not being used | ⛔ `EKS-16` is **the search was never run**. Here the search **was run, was reported, and was wrong** |
| `EKS-17` — duplicate research roots | both involve addressing | ⛔ `EKS-17` is **two real folders sharing a name**. Here the folder searched **did not exist at all**, and the result still looked normal |
| `EKS-20` — derivations that do not record their sources | both concern auditability | ⛔ `EKS-20` is about **what was read**. This is about **whether a reported non-result is real** |
| `EKS-18` — open questions with no route to the work | both cost duplicated effort | ⛔ different cause entirely |

⭐ **Checked and distinct on all four counts.**

## 7 · Urgency

⭐⭐ **High relative to its cost.** The remedy is one additional search per absence claim; the failure
silently invalidates conclusions and propagates into every review that cites them. ⚠️ **And unlike the
neighbouring items, this one has a confirmed live instance** — caught by luck, in the review that filed
it.

## 8 · Evidence

* `docs/knowledgeos/theory-extraction/78-P60-…` §2.2 — the four false zeros and the corrected counts.
* The same review's §3, §6 — findings that depend on correctly-scoped zero counts, re-run with absolute
  paths before publication.
* The corpus search rule's existing requirement of a positive control for absence claims.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed and no work is commissioned.**

## 9 · Third corroborating instance, 2026-09-09 — from the `three_model_convergence` programme's own MD-047

**A different work stream than the one that filed this ticket hit the identical pattern.** A prior
study (MD-046) reported "zero hits, for all 13 [capability] names, searched in kernel-capability
context" across two directories, with no positive control run alongside that specific claim. A
follow-up study (MD-047) re-ran the same search directly and unfiltered, as its own first act, before
reconstructing anything else — precisely because this ticket already exists and names the risk. ⭐ **The
positive control found the underlying tooling and paths were genuinely live** (confirmed by 117 files
matching an unrelated, known-present term in the same locations) — so this was not a repeat of the
`P-60` false-zero failure mode itself. ⚠️ But the check **did** catch a real, if smaller, overstatement
the original claim's own scoping had missed: one of the thirteen names turned out to share a surface
word with material in a second, separately-searched vocabulary — present in 26 files, filtered out as
"generic usage" without the specific instance that mattered being individually inspected until this
follow-up did so. The underlying conclusion held once inspected, but it held **because someone checked**,
not because the original claim was self-evidently safe. **A fourth data point, from a second work
stream, that a discipline requiring someone to remember to add a control is not yet a control.**

**Traceability:** `docs/knowledgeos/brainstorming/three_model_convergence/14_decision-log/MD-047-
capability-identity-completeness-adjudication/01_md046-search-completeness-audit.md` (the check and
its finding) · `docs/knowledgeos/brainstorming/three_model_convergence/14_decision-log/MD-046-
capability-identity-granularity-adjudication/01_evidence-census.md` (the original claim, not modified).
