# EKS-38 — Two separate registers of pending decisions use the same identifiers for different decisions, so an instruction to "enact N-4" has two possible meanings

**Status:** **BACKLOG · GOVERNANCE CORRECTNESS EXPOSURE (WRONG-ENACTMENT RISK)** — registered from `P-89` (`docs/knowledgeos/theory-extraction/110-P89-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — identification of pending decisions.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, proposes no numbering scheme, and takes no position on any decision's content.**

---

## 1 · The problem in plain terms

Two parts of the programme each keep a list of **decisions somebody still has to make**. Each list
numbers its entries `N-1`, `N-2`, `N-3`, `N-4`. ⭐⭐⭐ **The numbers overlap completely, and they mean
entirely different things.**

| | one register says | the other register says |
|---|---|---|
| **`N-1`** | are two relations the same thing? | ⭐ **decide whether the system claims truth** |
| **`N-2`** | which of three repairs to apply | ⭐ **define contradiction** |
| **`N-3`** | how far one operator set extends | ⭐ **define the progress ordering** |
| ⭐⭐ **`N-4`** | ⭐ **rule on which operations are mandatory** | ⭐ **test thirty-seven unsupported distinctions, or drop them** |

$$\boxed{\begin{array}{c}\textbf{Four identifiers. Four collisions. The same numbering range.}\\ \textbf{⛔ And } \mathbf{no\ crosswalk\ anywhere} \textbf{ — neither register mentions the other's items.}\end{array}}$$

## 2 · Why this is different from a notation clash

The programme already tracks symbol collisions, where one letter means two things in two formulas. ⭐
**Those cause a reader to misunderstand a sentence.** ⛔ **This one is worse in kind, because these
identifiers name *acts*.**

$$\boxed{\textbf{A symbol collision produces a } \mathbf{misreading}\textbf{. A decision-ID collision produces a } \mathbf{WRONG\ ENACTMENT.}}$$

⭐⭐ **Someone instructed to "enact `N-4`" has two candidates**: rule on which operations are mandatory,
or test thirty-seven distinctions. **They are not variants of one act.** One is a governance ruling with
a named authority; the other is a piece of research work.

## 3 · Why this is a business problem

**a. The wrong act can be performed, correctly.** ⭐ Everyone involved can behave impeccably —
follow the instruction, do good work, record it properly — and **the wrong thing still gets done**,
because the instruction was ambiguous and nothing in it revealed that.

**b. It bites precisely at the handover.** ⭐⭐ These identifiers exist **so that work can be handed to
somebody else**. A number is used instead of a description exactly when the parties are not in the same
conversation — ⛔ **which is also when neither can notice the clash.**

**c. It is invisible from inside either register.** Each list is internally consistent, sensibly
numbered, and correct. ⛔ **The defect exists only between them**, and no one is responsible for the
space between two registers.

**d. It has already reached a live instruction.** ⭐ A recent commission asked for an audit of *"`N-4`"*
naming an authority for it. **The audit had to determine which `N-4` was meant before it could
proceed** — and the answer was not in the material the commission pointed at.

**e. Both registers are active and growing.** Neither is closed. ⚠️ **Every new entry in either extends
the overlap.**

## 4 · What is *not* the problem

⛔ **Neither register is badly kept.** Both are careful — one of them goes out of its way to *remove*
four items from its decision list on the grounds that *"calling them governance questions would have
manufactured four decisions."* ⭐ **That is unusually disciplined practice, and it is not in question.**

⛔ **The numbering convention is not wrong.** `N-` for a pending normative item is a reasonable choice.
**The problem is that two parties made the same reasonable choice independently.**

⛔ **This is not about which decisions are right, or who should take them.**

⛔ **No numbering scheme, prefix or registry format is proposed here.**

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **A pending decision should be identifiable unambiguously across the whole programme — so that an
> instruction naming one can only be read one way.**

⭐⭐ Two properties matter more than the format:
1. **Uniqueness must hold where the identifier is *used*, not where it is *defined*.** ⛔ Each register
   is already unambiguous internally; **that is exactly what did not help.**
2. ⭐ **An instruction to act should carry enough beside the identifier to be self-checking** — the
   act's subject in words, not only its number. **A reader who cannot tell which `N-4` was meant should
   be able to tell that they cannot tell.**

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| ⭐⭐ `EKS-27` — the same symbol means two different things | ⭐ **same cause** | ⛔ **the consequence class differs, and that decides the remedy.** `EKS-27`'s collisions are in *formulas*, and the fix is a glyph register — a notation artifact. These are *decision identifiers*, the failure is a **wrong enactment**, and the fix is a **governance** artifact |
| `EKS-34` — a naming collision split across an information barrier | ⭐ both are ID collisions | ⛔ there, **neither side can see the other**, so detection is impossible; here **both registers are readable**, and nobody looked. Different failure, different remedy |
| `EKS-28` — two commissions on sibling kernel questions never cross-reference | both concern two efforts not meeting | ⛔ that concerns **duplicated work**; this concerns **identical labels on different work**. Two commissions could cite each other perfectly and still collide on `N-4` |
| `EKS-36` — two same-day efforts never cross-cite | as above | ⛔ same distinction |
| `EKS-32` — a ruling recorded only in the consuming lane's notes | both concern the governance record | ⛔ that is a decision **taken** and unregistered; this is decisions **not yet taken** and ambiguously named |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency

⚠️ **Immediate, because an instruction using one of these identifiers is already in circulation** and
the programme is approaching the point where these decisions are meant to be put to an authority.
⛔ **An ambiguous instruction reaching a decision-maker is the failure this item exists to prevent.**

⭐ **And it is cheap:** the two lists are short, both are readable, and the overlap is four entries.

## 8 · Evidence

| | |
|---|---|
| the four collisions, side by side | `docs/knowledgeos/theory-extraction/110-P89-…` §0.2 |
| register A | `…/phase_measure_theory/knowledgeos_kernel/research/step-290/08-decision-register.md` · `…/step-291/07_governance-vs-derivation.md` · `…/step-291/10_recommendation-and-next-act.md` |
| register B | `…/mathematical_ideas_that_can_be_implemented/20260902-122338_what-eleven-experiments-established-a-synthesis.md` |
| **no crosswalk** | the approved TODO register cites `N-4`/`N-8`/`N-13` **zero** times; **no file holds both `"TODO Group"` and `N-4`** |
| the live instruction | the commission behind `P-89` |

⛔ **No claim is made about either register's content, and no identifier is reassigned here.**
