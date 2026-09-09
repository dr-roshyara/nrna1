# EKS-14 — A research construct carries a name that reads as a KnowledgeOS component, and fifty documents now use it

**Status:** **BACKLOG · NAMING & ATTRIBUTION EXPOSURE** — registered from `P-50` (`docs/knowledgeos/theory-extraction/68-P50-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** naming / attribution risk — KnowledgeOS research communication.
**Registered by:** Lane T (theory extraction), 2026-09-08.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no rename, and makes no claim about the research itself.**

---

## 1 · The problem in plain terms

For two days one research lane has been developing something it calls **"the persistence kernel"** — a set of eleven obligations describing what a knowledge system must not lose when things change. The work is careful and heavily audited.

⭐⭐ **But the phrase *"persistence kernel"* appears in the underlying research corpus exactly **zero** times.** The corpus does discuss kernels — *"the Kernel"* in 610 documents, *"epistemic kernel"* in 49, *"knowledge kernel"* in 48, *"operator kernel"* in 5 — **none of them this one.**

$$\boxed{\textbf{The eleven obligations are drawn from real evidence. The } \mathbf{thing\ called\ "the\ persistence\ kernel"} \textbf{ is the research lane's own assembly of them.}}$$

## 2 · Why this is a business risk rather than a wording preference

⭐ **The name reads like a product component.** *"The KnowledgeOS persistence kernel"* sounds like part of KnowledgeOS — a module, a layer, something a system would contain. It is currently **a research finding about what such a system would have to preserve.**

| a reader could reasonably conclude… | the actual position |
|---|---|
| KnowledgeOS **has** a persistence kernel | ⛔ it does not; **no such component exists** |
| the eleven obligations are **KnowledgeOS law** | ⛔ they are `[REC]` — recommendations, never adopted |
| the number **eleven** is settled | ⛔ it is a **lower bound under one construction** |
| the corpus **defines** this object | ⛔ ⭐ **the corpus never mentions it** |

⚠️ **Fifty documents now use the phrase**, several with confident language about *"the eleven cells"*. ⭐⭐ **The risk grows with each additional artifact and with distance from the lane that wrote them.**

## 3 · Concrete exposure

* **Downstream build risk.** Someone planning a formal specification, schema or architecture could take "the persistence kernel" as settled input and design against eleven obligations that were never adopted and whose count is explicitly provisional.
* **External communication risk.** A summary to a stakeholder, board or partner naming *"the KnowledgeOS persistence kernel"* would misdescribe the estate's actual position.
* **Archaeology risk.** ⭐ A future reader searching the corpus for *"persistence kernel"* finds **nothing**, and cannot tell whether the term was retired, renamed, or never existed. ⚠️ **The audits explain this; the name does not.**

## 4 · What is *not* the problem

⛔ **The research is not in question.** Each of the eleven obligations rests on a witnessed loss in the real record — a status change, a re-disposition, fifty-six file renames. `P-47` established that every one has an independence ground.
⛔ **This is not the governance question.** `EKS-12` asked who may adopt such theory and was **closed** as a deliberate boundary. This item is about **how the work is named and read**, not about whether it can be adopted.
⛔ **Not a duplicate of `EKS-13`**, which concerns a dependency on another lane's document.

## 5 · Candidate requirement — ⛔ hypothesis only

> **Candidate:** *"A research construct that has no counterpart in the source corpus SHALL be named or labelled so that a reader can tell it is a research finding rather than a system component."*

⚠️ **Not adopted, and deliberately silent on remedy.** ⭐ A rename is one option; a standing label on the artifacts is another; a note in the corpus index is a third. ⛔ **Choosing among them is not this lane's call** — `P-44`/`P-45` established it has no authority to design one.

## 6 · Urgency

⭐ **Low today, rising.** Nothing downstream has been built on it yet — no specification, architecture or schema exists, and all of those remain deliberately gated. ⚠️ **The right moment to settle the naming is *before* the first downstream artifact cites it, not after.**

## 6a · ⚠️ CORRECTION to §1's premise *(additive, `ES-004.3` — 2026-09-08, from `P-52`)*

⭐⭐ **The ticket SURVIVES an adversarial re-examination, but its premise was too strong and is corrected here rather than rewritten above.**

§1 said the phrase *"persistence kernel"* has **no corpus counterpart**. ⭐ **That is true of the NAME and false of the SUBSTANCE.** `P-52` found a corpus framework dated **2026-09-04** — three days before this lane's own derivation — defining a Kernel as:

> *"the smallest domain-independent bounded context responsible for **identity, lifecycle, provenance, and invariant-preserving evolution of epistemic states**"*

⚠️ **That overlaps this lane's subject matter substantially.**

$$\boxed{\textbf{⭐⭐ The exposure is not weakened — it is } \mathbf{SHARPER.}}$$

⭐ Previously the risk was that a reader would think a component exists when none does. ⭐⭐⭐ **Now the risk is that a reader conflates two different things that both exist**: the corpus's *epistemic Kernel* (a governed-theory object, itself unratified) and this lane's *persistence kernel* (a research assembly of eleven obligations). **A conflation is harder to detect than an invention.**

⛔ **Still no rename proposed.** ⭐ The candidate requirement in §5 is unchanged and now covers both cases: a research construct should be nameable apart from any corpus object it resembles.

## 7 · Traceability

`P-50` `docs/knowledgeos/theory-extraction/68-P50-PERSISTENCE-KERNEL-OBJECT-TYPE-AND-ADEQUACY-AUDIT.md` §3, §14
Status context: `P-43` (`[REC]`, no adoption), `P-44` (no adoption pathway), `P-45` (no authority scope), `EKS-12` (closed — deliberate boundary).
⚠️ Distinct from `EKS-13` (cross-lane dependency) per `ES-005.4`.

---

## Second instance, recorded 2026-09-09 — a disambiguation register already existed, and was not used

⭐⭐ **The item above records that a research construct carries a name reading like a product component.
A follow-up review found something that makes the exposure both worse and cheaper to fix.**

⭐⭐⭐ **The estate already maintains a register that disambiguates this exact word.** Dated **2026-08-29
— nine days before the construct in question was named** — it lists **five distinct senses** in which
the term is used across the programme, says for each what it is, and states plainly which ones are
**not** mathematics and which **do not claim** to be complete. It is a good, short, purpose-built table.

⚠️ **Two of its five entries already use the same short label** the new construct then adopted. **A
sixth sense was minted without consulting it.**

### Why this changes the assessment

**a. The confusion is documented, not latent.** ⭐ The item above treated the naming risk as something a
future reader might stumble into. **In fact the programme had already stumbled into it enough times to
write a register about it** — so the risk is demonstrated, not hypothetical.

**b. The fix is smaller than first thought.** ⭐⭐ No rename is needed to remove most of the exposure:
**adding the new sense as a sixth row to a table that already exists** would tell any reader
immediately which kernel is meant. **That is a one-line act against an existing artifact.**

**c. But the cost of leaving it grows differently now.** ⚠️ A reader who *does* find the register will
reasonably conclude it is complete — **and will therefore not expect a sixth sense to exist.** An
incomplete disambiguation register is more misleading than none at all, because it invites trust.

**d. It also explains several near-misses.** ⭐⭐ The register distinguishes senses that a separate work
stream repeatedly had to disentangle by hand — including one case where two unrelated things both
carried the count **eleven** and had to be explicitly held apart to avoid a false equivalence.

### What this does *not* change

⛔ **No rename is proposed, and none is needed for the cheap fix.** ⛔ **No new item is opened** — under
the never-a-copy rule a second instance belongs on this record. ⛔ **The register itself is not
criticised**; it is the best available artifact on the problem and is the reason this instance could be
identified at all.

**Evidence:** `docs/knowledgeos/theory-extraction/86-P68-…` §7 · the disambiguation register in
`docs/knowledgeos/brainstorming/verification/spec/` (2026-08-29), §1 of that document.
