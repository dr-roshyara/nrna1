# EKS-18 — A register of open questions exists, and the people producing the answers do not know it is there

**Status:** **BACKLOG · OPERATIONAL EXPOSURE (DUPLICATED EFFORT / UNCLOSED QUESTIONS)** — registered from `P-56` (`docs/knowledgeos/theory-extraction/74-P56-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — matching open work to the work that would close it.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no process, and questions no finding on either side.**

---

## 1 · The problem in plain terms

On **2026-08-28** the estate completed a substantial piece of work: it assembled a proposed
architecture and, alongside it, a **register of twelve open questions**. That register is unusually
good — it does not merely list what is unresolved, it states, for each item, **exactly what would be
needed to resolve it**.

⭐⭐⭐ **One of those twelve reads, in effect: *"we cannot yet say which objects belong to the kernel;
what would settle it is a formal irreducibility analysis."***

⭐⭐ **For the eleven days since, a different work stream has been producing formal irreducibility
analysis — twenty-eight consecutive reviews of it — and neither side knows the other exists.**

$$\boxed{\textbf{The question is written down. The answer is being produced. } \mathbf{Nothing\ connects\ them.}}$$

## 2 · Why this is a business problem

**a. Paid-for work does not reach the question it answers.** ⭐ The register's whole purpose is to say
what is needed. When the people who could supply it never see it, the register documents a need it
cannot meet, and the work satisfies a need it does not know it is satisfying.

**b. Questions stay open longer than they need to.** ⚠️ An open question with a **known** resolution
prerequisite is not really blocked on knowledge — it is blocked on **routing**. That is a much cheaper
problem, and a much more embarrassing one to leave standing.

**c. The work is shaped by the wrong target.** ⭐⭐ Analysis produced without knowing which question it
answers is aimed at nothing in particular. It may be more general than needed, or — worse — subtly the
wrong shape, and nobody discovers this until someone tries to use it.

**d. It compounds.** ⭐ There are **twelve** such questions, each with a stated prerequisite. This item
concerns the pattern, not the single instance that exposed it. Any of the twelve could be in the same
position right now, and there is no way to tell without checking each by hand.

**e. Detection was accidental, again.** The connection surfaced only because an unrelated audit
followed a file-path lead into the architecture folder. ⚠️ **Nothing in either work stream would ever
have raised it.**

## 3 · What is *not* the problem

⛔ **The register is not at fault — it is the best artifact in this story.** Stating a resolution
prerequisite for every open item is exactly right, and it is what made the mismatch visible at all.

⛔ **Neither work stream did anything wrong.** Both were authorized, both produced sound work, and
their independence is deliberate.

⛔ **This is not a request to merge the two streams, to redirect either one, or to have one work to the
other's agenda.** ⭐ **The candidate requirement is only that the register be *reachable* from the work,
and the work *visible* to the register.**

⛔ **No process, tool, notification mechanism or meeting is proposed.** Designing the remedy is outside
this record.

## 4 · Candidate requirement (a direction, not a design)

> ⭐ **An open question that states its own resolution prerequisite should be discoverable by whoever
> is producing that kind of work — and the register should be able to show whether anyone is.**

⭐⭐ **This is closer to a matching problem than a documentation problem**, which is why it does not
dissolve into "search better": the analysis lane could search perfectly and still not think to look in
an architecture folder for a question about its own subject.

## 5 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| `EKS-16` — derivation without consulting existing theory | both end in two things not meeting | ⛔ `EKS-16` is about **existing answers** not being found before work starts. This is about **existing questions** not reaching the work that answers them. Opposite direction |
| `EKS-17` — duplicate research roots | both are discoverability | ⛔ `EKS-17` is **path ambiguity** — the right folder is hard to name. Here the folder is unambiguous; **nobody had a reason to look in it** |
| `EKS-13` — cross-lane dependency without change notification | both concern awareness across streams | ⛔ `EKS-13` is about **a known dependency** going stale. Here there is **no known dependency at all** — that is the problem |
| `EKS-07` — multi-process coordination | adjacent | ⛔ that is concurrent writers colliding; this is two streams that never touch |

⭐ **Checked and distinct on all four counts.**

## 6 · Urgency

**Moderate.** The immediate instance is now visible and can be handled by hand. ⚠️ **What justifies
recording it is the other eleven** — each carries a stated prerequisite, and there is currently no way
to know whether any of them is in the same position. ⭐ The cost of checking once is small; the cost of
the situation persisting unnoticed compounds with every additional week of analysis work.

## 7 · Evidence

* `docs/knowledgeos/reviews/synthesis/final-architecture/FA-6-open-questions-register.md` — twelve open
  questions, each with a stated resolution prerequisite; `OQ-2` is *"Kernel membership at object
  level"*, prerequisite *"formal irreducibility analysis of L2 objects, or a step-⑤-style governance
  selection on the formal side"*.
* `docs/knowledgeos/reviews/synthesis/final-architecture/FA-5-repository-correspondence-map.md` —
  *"Kernel (L1) … object-level mapping [U]"*.
* `docs/knowledgeos/theory-extraction/` — twenty-eight consecutive audits of kernel irreducibility,
  independence and minimality, 2026-09-07 to 2026-09-09, citing none of the above.
* `docs/knowledgeos/theory-extraction/74-P56-…` §4, §10, §12 — how the mismatch surfaced.

---

⛔ **Registered under the operating model's §37 — *"when a deeper requirement is discovered: record it as a follow-up and STOP."* No remedy is designed and no work is commissioned.**

---

## Second instance, recorded 2026-09-09 — a different register, the same failure

⭐⭐ **The problem recorded above is not a one-off, and the second instance is larger than the first.**

On **2026-09-04** the estate produced a **second** register of open work: twenty numbered items on the
mathematics of the knowledge theory, five of them marked as foundational blockers, each stating in
formal terms exactly what would resolve it. One of them is named, in the document's own words, *"a
foundational blocker"*, with the note that **nothing downstream becomes mathematically executable until
it is settled**.

⭐⭐⭐ **The work stream that has spent thirty consecutive reviews on precisely those questions did not
know the register existed** — and its own reviews independently rediscovered several of the same
conclusions, in weaker form, over the following five days.

### Why this instance is worth recording separately from the first

**a. It is a different register, in a different folder, produced by a different work stream.** ⭐ The
first instance could be read as bad luck about one document. Two instances in two different places
make it a **property of how the estate works**, not an accident.

**b. The rediscovered material was rediscovered *worse*.** ⚠️ The later reviews reached similar
conclusions but stated them less precisely than the register already had — in one case describing a
concept as *"undefined"* when the register had defined it and identified the single remaining gap by
name. **Duplicated effort is the smaller cost; the larger cost is that the duplicate was lower quality
and briefly became the working understanding.**

**c. It confirms the direction of the original item.** ⭐ Both registers are good artifacts. Neither
failure is a documentation failure. **In both cases the question was written down clearly and simply
never reached the people answering it.**

### What this does *not* change

⛔ **No new problem is claimed, and no separate backlog item is opened.** Under the estate's
never-a-copy rule, a second occurrence of a recorded problem belongs **on that record**, not on a new
one. ⛔ **No remedy is proposed here either** — this entry raises the evidence base for the item above,
and nothing more.

**Evidence:** `docs/knowledgeos/theory-extraction/76-P58-…` §0 · the three 2026-09-04 artifacts in
`brainstorming/mathematical_ideas_that_can_be_implemented/` (`…substantial-agreement-with-corrections-minimality-and-ratification`,
`…close-to-freeze-quality-four-final-corrections-to-sem-equivalence`, `…remaining-todos-after-v12-freeze-formal-semantic-equivalence`).

---

## Third instance, recorded 2026-09-09 — this time it is a completed verification, not a question list

⭐⭐ **The pattern has now appeared three times, and the third instance is the most costly.**

The estate contains a **completed executable verification of its own theory** — a folder of thirty-two
documents covering experiments A through Z, an experiment register, two adjudication briefs and a final
verdict, with a sibling run against the previous theory version. ⭐ **It is good work, clearly presented,
and it says plainly what passed and what failed.**

⭐⭐⭐ **Its "what failed" section reports a gap that a separate work stream has spent thirty-eight
reviews building an answer to — and neither side knows the other exists.** The verification records the
failure as **reproduced across two successive theory versions**, which makes it a standing, twice-
witnessed result rather than a one-off.

### Why this instance is worse than the first two

**a. It is an answer, not a question.** ⭐ The first two instances were registers of open questions that
never reached the people answering them. **This is a completed test result that never reached the people
whose work it directly evaluates.** The information was not merely unrouted — it was *finished*.

**b. It is the strongest available external check, and it has never been run against the work.** ⚠️ The
work stream has been arguing its case internally for thirty-eight reviews while an independent,
executable test of the same territory sat unopened. **Every one of those reviews would have been
sharper had it been consulted.**

**c. Two versions means it will recur.** ⭐⭐ Because the failure reproduced from one theory version to
the next, it is not a transient defect — **it is a standing property of the theory that any future
version will inherit until someone addresses it.** The longer it goes unconnected, the more downstream
work rests on the unexamined side.

### What this does *not* change

⛔ **Neither the verification nor the work stream is criticised.** Both did careful work. ⛔ **No new
item is opened** — under the never-a-copy rule, a third occurrence belongs on this record. ⛔ **And no
remedy is proposed here**; this entry raises the evidence base and nothing more.

**Evidence:** `docs/knowledgeos/theory-extraction/84-P66-…` §3 · `docs/knowledgeos/research/theory-v1.2-simulation/`
(32 documents incl. `FINAL-VERDICT.md`) · `docs/knowledgeos/research/theory-v1.1-simulation/` (the prior
run in which the same failure was first recorded).
