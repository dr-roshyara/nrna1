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

---

## Extension, 2026-09-09 — the same fault also runs the other way, and that direction is worse

⭐⭐ **The item above describes a search that wrongly reports *nothing*. A second live instance shows the
identical fault producing the opposite error: a search that wrongly reports *a great deal*.**

A review searching for how much of the estate discusses a particular technical assumption got back
**522 documents**. That number was implausible on its face, so it was checked. The search pattern had
been written loosely, and it was matching the letters of an abbreviation **inside unrelated ordinary
words**. The real matches, once extracted:

| what the search reported | what was actually there |
|---|---|
| ⭐⭐⭐ **522 documents** | a security acronym (7), the word *"toward"* (4), and two forms of *"knowable"* (2) |

$$\boxed{\textbf{The finding } \mathbf{"522\ documents\ discuss\ this\ assumption"} \textbf{ was about to be published. } \mathbf{The\ true\ figure\ is\ effectively\ zero.}}$$

### ⭐⭐⭐ Why this direction is worse than the first

**a. A false negative hides a finding. A false positive *manufactures* one.** With a wrong zero, work
gets redone. **With a wrong large number, a conclusion is asserted that the evidence does not support at
all** — and it will be cited.

**b. Large numbers are persuasive.** ⭐ A reader challenges *"we found nothing"* far more readily than
*"we found 522 documents"*. **The error that survives scrutiny is the one that looks like strong
evidence.**

**c. The remedy in §5 above does not catch it.** ⚠️ A positive control confirms the search *can* find
things — and a false positive already finds things. ⭐⭐ **Catching this direction needs the opposite
check: when a count is surprisingly high, look at what actually matched.** Both checks are cheap; **the
item above only asked for one of them.**

**d. It was caught by luck again.** The number simply looked wrong to the person reading it. ⭐ **Nothing
in the process would have flagged it**, and a slightly less implausible figure — 40 rather than 522 —
would have passed unexamined.

### What this changes about the candidate requirement

⭐⭐ The direction in §5 should be read as covering **both** failures, not only absence:

> **A published count — zero or large — should carry evidence that the search measured what it claims
> to measure: for a zero, that the search could have found the thing; for a large number, what the
> matches actually are.**

⛔ **No tool or format is proposed, and no past count is called into question except the one recorded
here.**

**Evidence:** `docs/knowledgeos/theory-extraction/85-P67-…` §3 — the 522, the four real matches, and the
corrected counts published in its place.

---

## ⚠️ Provenance correction, 2026-09-09 — this was not a new discovery

⭐⭐⭐ **This item was filed as though the failure mode had just been found. It had not.** A later review
reading a primary document from **two days earlier** — written by the same work stream that filed this
ticket — found the failure already recorded there, in its list of settled findings:

> **"a glyph-literal pattern over a LaTeX corpus produced a false zero"**

⭐⭐ **The same document also names the underlying mechanism as an established lesson**: mathematical
notation in the source can hide the very words a plain-text search is looking for. **So the work stream
knew the mechanism, wrote it down, and then repeated the failure four more times** — in the reviews that
followed, including the one that filed this ticket.

### Why the correction matters, and why the item still stands

⛔ **The item is not withdrawn, and its substance is unaffected.** Its point was never *"this can
happen"* — it was that **nothing in the process catches it when it does**. ⭐⭐⭐ **That claim is now much
better evidenced than when it was filed:** the failure was recorded, understood, named as a lesson, and
still recurred **four times** afterwards.

$$\boxed{\textbf{A failure that is documented and keeps happening is stronger evidence for this item than a failure newly found.}}$$

⚠️ **But two things do change.**

**a. The urgency assessment in §7 above understated the case.** It described a risk. ⭐ **The record
shows a repeat offence with a written prior.**

**b. And the filing itself is an instance of a different recorded problem.** ⭐⭐ Filing this as novel,
while the evidence sat in the filer's own earlier document, is precisely the *"work proceeds without
consulting what already exists"* pattern recorded elsewhere in this backlog. ⛔ **No new item is opened
for that** — it is noted here so the two records read consistently.

**Evidence:** `docs/knowledgeos/theory-extraction/88-P70-…` §6 · the settled-findings list in
`docs/knowledgeos/theory-extraction/18-P08-…` §21, item 10, dated 2026-09-07 · the four subsequent
recurrences recorded in `82-P64-…`, `83-P65-…`, `85-P67-…` and this correction's own audit.
