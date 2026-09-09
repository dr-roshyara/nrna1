# EKS-34 — The same short names mean two different things on opposite sides of an information barrier, and neither side can see the clash

**Status:** **BACKLOG · CROSS-LANE CORRECTNESS EXPOSURE** — registered from `P-85` (`docs/knowledgeos/theory-extraction/103-P85-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — naming across a deliberate research boundary.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no naming scheme, and criticises neither lane.**

---

## 1 · The problem in plain terms

Two research lanes work on the same programme and are **deliberately kept apart**. Each is forbidden to
read the other's working material, on purpose: the separation is what makes their conclusions
independent, and it is a good rule.

⭐⭐⭐ **Both lanes independently adopted the same short labels — `F1`, `F2`, `F3`, `F4` … — for
completely different things.**

| lane | what `F1…Fn` means there | how many |
|---|---|---|
| one lane | ⭐ **candidate semantic models** being compared against each other | **six** |
| the other | ⭐ **failure classes** — *representation failure, semantic-interpretation failure, relation failure, **discrimination failure**, …* | **twenty** |

$$\boxed{\textbf{So } \mathbf{F3} \textbf{ is } \textit{"the only model with a working construction"} \textbf{ in one lane and } \textit{"relation failure"} \textbf{ in the other.}}$$

⚠️ **And because the two lanes cannot read each other, neither can discover this.** Each searches its
own material, finds its own `F4`, and has no way to learn that the label is taken.

## 2 · What actually happened

A reviewer was asked to recover what one lane knows about **candidate `F4`**. Searching the material it
*is* allowed to read:

| what the search returned | what it actually was |
|---|---|
| *"`F4` — **discrimination failure**"* | ⛔ a failure class |
| *"`F4` `ActionRequest → DENY`"* | ⛔ a step identifier |
| *"`F3` evidence unavailable, **`F4` authorization denied**"* | ⛔ a finding identifier |
| *"`E14,F2,F4,F7` PASS"* | ⛔ fitness-test identifiers |

⭐⭐⭐ **Seventy-one documents matched. Every one inspected was a different `F4`.** ⛔ **Nothing in the
readable material said so** — the document that would have disambiguated is on the other side of the
barrier.

⭐ **It was caught only by accident:** two handover reports written that afternoon happened to quote
*both* lanes, and the clash became visible for the first time.

## 3 · Why this is a business problem

**a. A search can be confidently, silently wrong.** This is worse than finding nothing. A reviewer who
searches, gets seventy-one hits, and reads a few of them will reasonably conclude the topic is
well-covered. ⚠️ **Every hit can be about something else entirely.**

**b. The damage lands exactly at handover.** ⭐ The barrier exists so the two lanes can eventually
compare independent results. **That comparison is the moment the labels are put side by side** — and it
is the first moment anyone could notice they mean different things. ⛔ **A collision discovered during
the comparison has already contaminated whatever was compared.**

**c. Neither lane owns the problem.** Each label is perfectly well defined **inside its own lane**, so
neither lane has done anything wrong and neither has a reason to change. ⭐⭐ **The defect exists only
in the space between them, which nobody is responsible for.**

**d. The usual remedy cannot be applied.** *"Check whether the name is already taken"* is the normal
answer — ⛔ **and here it is impossible by design**, because checking would mean reading the other
lane's material.

**e. It is very likely not the only one.** ⭐ `F1`…`F6` is ambiguous in **both** families
simultaneously. Short capital-letter-plus-number labels are the estate's default naming habit, and the
same programme already tracks several single-lane symbol collisions. ⚠️ **This is the first found that
straddles the barrier; there is no reason to think it is the only one.**

## 4 · What is *not* the problem

⛔ **The barrier is not the problem and should not be relaxed.** It is doing its job. ⭐ **The point of
this item is that a correct separation has an unmanaged side effect.**

⛔ **Neither naming choice is wrong.** Numbered failure classes and numbered candidates are both
ordinary, sensible conventions.

⛔ **No naming scheme, prefix convention, or registry format is proposed here.** Designing the remedy is
outside this record.

⛔ **No claim is made that any conclusion has actually been corrupted.** ⭐ **One near-miss is recorded;
whether a real misreading has occurred is a question only a reader with access to both sides can
answer**, and that question is handed on rather than answered here.

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **Where two lanes are deliberately separated, the short labels each uses should be visible across
> the barrier even though the content is not — so that a lane can learn a name is taken without
> learning what it means.**

⭐⭐ Two properties matter more than the format:
1. **Names are not findings.** ⭐ A shared list of *which labels are in use, by whom, for what kind of
   thing* leaks no evidence and prejudges no conclusion — **it is exactly the part of a lane's work
   that is safe to share.**
2. ⭐ **The check must be possible at search time, not only at handover.** By handover the labels have
   already been reasoned with.

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| ⭐⭐ `EKS-27` — the same mathematical symbol means two different things | ⭐ **same cause, and the closest neighbour** | ⛔ `EKS-27`'s collisions sit **inside one readable estate**, so a reader *can* find both meanings and the remedy is a glyph register. Here the two meanings are **on opposite sides of a barrier**, so ⭐⭐ **neither lane can detect the clash and no single-estate register can fix it** |
| `EKS-23` — two capability vocabularies never reconciled | both concern divergent vocabulary | ⛔ that is two vocabularies **for the same subject** that were never compared; this is one **label set** accidentally reused **for different subjects** |
| `EKS-19` — no registry of already-spoken-for directories | ⭐ **structurally the same shape, one level up** | ⛔ that concerns **directories** claimed by different programmes; this concerns **identifiers**. ⭐ Related enough to be worth reading together, distinct in object and owner |
| `EKS-13` — cross-lane dependency without change notification | both cross a lane boundary | ⛔ that is *"their document changed and nobody told us"*; this is *"their word means something else and nobody can tell us"* |
| `EKS-31` — externally authored files enter the corpus without provenance markers | both concern material crossing a boundary | ⛔ that concerns **provenance of files**; this concerns **meaning of names** |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency and ownership

⚠️ **Ownership is the difficulty.** ⛔ **Neither lane can resolve this alone** — that is the defect. ⭐⭐
**It needs someone who can see both label sets**, which is a governance position rather than a research
one.

⭐ **Timing:** the programme is approaching the cross-lane comparison the barrier was built to make
possible. ⛔ **That comparison is precisely where an undetected collision does its damage**, so the
window to act is before it, not after.

## 8 · Evidence

| | |
|---|---|
| the collision, both sides quoted | `docs/knowledgeos/theory-extraction/103-P85-…` §1 |
| failure classes `F1`–`F20`, permitted side | `…/mathematical_ideas_that_can_be_implemented/20260901-222111_prompt-kernel-reduction-and-minimality-experiment.md` §§1299–1319 |
| candidate family, six members | the two handover reports of 2026-09-09 (15:32, 15:37), which cite the firewalled register; **`15` pairwise comparisons = `C(6,2)`** |
| the four false-positive sites | `…/verification/spec/STEP-VERIFY-141-158.md` · `…/verification/spec/STEP-TRACE-B6-20260828-late.md` · `…/verification/step-280/` |
| the barrier, declared by the other side | the 15:37 handover report §4.5 — the other lane records keeping this lane's material firewalled throughout |

⛔ **No claim is made about either lane's conclusions, and no remedy is proposed.**
