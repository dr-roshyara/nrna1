# EKS-33 — A binding ruling governs a field on a register that cannot reliably carry it

**Status:** **BACKLOG · GOVERNANCE ENFORCEABILITY EXPOSURE** — registered from `P-84` (`docs/knowledgeos/theory-extraction/102-P84-…`). ⛔ **Not commissioned; activation requires a Human/PO-ARB authorization act.**
**Class:** operating-model problem — whether the thing a rule governs can bear the rule.
**Registered by:** Lane T (theory extraction), 2026-09-09.

> ### ⛔ **This item records a problem and a candidate requirement. It commissions nothing, questions no decision, criticises no register, and proposes no schema.**

---

## 1 · The problem in plain terms

A ruling is in force. It says, of a particular progress ladder that concepts are supposed to climb:
**movement is forward-only; nothing may slip back.** ⭐ **It is a sensible rule and it was properly
made.** A research programme's headline result — *how many things a knowledge system must not lose* —
depends on it.

⭐⭐⭐ **Nobody has checked whether the register the rule governs can actually carry it.**

Three properties of that register are already measured, and each of them was recorded by the team that
owns it — this item invents no measurement:

| what was measured | the number |
|---|---|
| entries that carry the ladder's status field at all | ⚠️ **28 of 123** |
| entries that are **concepts** *(the things the ladder is about)* | ⭐⭐ **27** — the register **changed its indexing axis partway through**, so the other **96 are file landmarks** |
| entries with a **stable identifier** | ⛔ **0** — *"entries keyed by concept **name**"* |

$$\boxed{\textbf{A forward-only rule over a field that } \mathbf{most\ rows\ do\ not\ have}\textbf{, on rows with } \mathbf{no\ identifiers}\textbf{, of } \mathbf{two\ different\ kinds}.}$$

## 2 · Why each of the three matters

**a. Most rows have no status.** A prohibition on moving backward has nothing to act on where there is
no position. ⭐ Whatever the rule protects, it protects it for **at most a fifth** of the register.

**b. The rows are two different kinds of thing.** After a certain point the register stopped listing
*concepts* and started listing *files*. ⭐⭐ **A file has no epistemic standing to advance or slip back.**
So for three-quarters of the rows the rule is not merely unenforced — **it is not meaningful.** ⚠️ The
owning team recorded the axis change and its consequence honestly; ⛔ **what has not been recorded is
that a governing rule now ranges across both kinds.**

**c. The rows have no identifiers — they are keyed by name.** ⭐⭐⭐ **And names in this estate change.**
The same programme records **fifty-six renames in a single day** as ordinary practice. A rename
therefore breaks the key. ⚠️ **A rule that says *"this thing may not move backward"* needs to know that
it is still the same thing** — and the register provides no way to say so.

## 3 · Why this is a business problem

**a. The rule may be unenforceable where it matters most.** Enforcement needs three things the register
does not supply: a position for every governed row, one kind of governed row, and a stable way to say
*"this is the same row as before."*

**b. The programme's headline number inherits the weakness.** The count of obligations is what it is
**because of this ruling**. ⭐ If the ruling cannot bite, the number rests on something softer than it
appears — **and nothing in the record says so.**

**c. Nobody owns the question.** The team that owns the register measured its own gaps and said so
plainly. The team that consumes the ruling recorded the ruling carefully. ⭐⭐ **Neither was asked
whether the second can be applied to the first**, and the two records live in different places.

**d. It is invisible from the outside.** A reader sees a properly recorded ruling and a properly
measured register. ⛔ **The mismatch only appears when someone reads both**, which is not anyone's job
today.

**e. It cannot be checked from where it was found.** ⚠️ The register and the decision record that
defines the ladder both sit in a part of the estate the reporting lane is **not permitted to open**.
⭐ **So this item can name the problem and must hand it on** — see §7.

## 4 · What is *not* the problem

⛔ **The ruling is not questioned.** Its content, its author's standing and its correctness are outside
this record.

⛔ **The register is not criticised.** ⭐ Its owning team **found and published its own correction**,
including that its coverage gap was four times larger than first reported. **That is good practice, and
this item depends on it.**

⛔ **This is not a request for a schema, an ID scheme, or a migration.** Designing any remedy is outside
this record.

⛔ **This is not about where the ruling is filed.** ⭐ That is a separate, already-registered item; this
one is about **what the ruling governs**.

## 5 · Candidate requirement (a direction, not a design)

> ⭐ **When a rule is adopted over a register, someone should check — once, and on the record — that the
> register can bear it: that the governed rows can be identified, that they are of one governed kind,
> and that they carry the property the rule constrains.**

⭐⭐ Two properties matter more than the format:
1. **The check belongs to adoption, not to enforcement.** By the time enforcement fails, downstream
   results already depend on the rule.
2. ⭐ **A negative answer is a useful result, not a blocker.** *"This rule governs 27 of 123 rows"* is a
   perfectly good thing to record — ⛔ **it is silence that causes the damage**, because a reader
   reasonably assumes the rule covers what it names.

## 6 · Relationship to existing items (`ES-005.4` — never a copy)

| item | overlap | why this is separate |
|---|---|---|
| ⭐ `EKS-32` — a ruling that fixes the kernel size is in no governance register | ⭐ **same ruling, adjacent question** | ⛔ `EKS-32` asks **where the ruling is recorded**. This asks **whether what it governs can carry it.** A ruling could be perfectly filed and still ungovernable, and vice versa |
| `EKS-06` — reference resolution bound to a fixed token list | both touch identifiers | ⛔ that concerns how **references** are resolved against a token list; this concerns whether **register rows** can be identified at all |
| `EKS-13` — cross-lane dependency without change notification | both concern a document in another lane | ⛔ that is *"it may change and nobody will tell us."* This is *"as it stands today, it cannot bear the rule"* |
| `EKS-19` — no registry of already-spoken-for directories | both are registry problems | ⛔ that concerns **research-boundary discoverability**; this concerns a **governed property on rows** |
| `EKS-22` — a large body of theory with no status and no anchor | ⭐ both involve missing status | ⛔ there, **documents** were never given a status. Here a status field **exists and is governed**, and the question is whether the rows can carry it |

⭐ **Checked and distinct on all five counts.**

## 7 · Urgency and ownership

⚠️ **Ownership is the point.** ⛔ **The reporting lane cannot resolve this** — both the register and the
decision record that defines the ladder are outside the part of the estate it may read. ⭐⭐ **It must go
to a reader who may open both.**

⭐ **Timing:** the programme reaches a governance adjudication stage at which the ruling's consequences
are meant to be frozen. ⛔ **Freezing a number whose supporting rule may govern a fifth of its register
is the specific outcome this item exists to prevent.**

## 8 · Evidence

| | |
|---|---|
| the ruling and its scope | `docs/knowledgeos/theory-extraction/25-INTAKE-001-…`, 2026-09-07 |
| the object and bearer identification | `docs/knowledgeos/theory-extraction/102-P84-…` §§3–4 |
| **28 of 123 carry the field · 0 identifiers · keyed by name** | `…/gap-discovery/theory-inventory-assessment/README.md` §72 |
| **27 concepts + 96 file landmarks; the axis change** | `…/gap-discovery/registry-extension-proposal/README.md` §0 · `…/gap-discovery/INDEX.md` §258 — *the owning team's own correction, 2026-09-07* |
| the ladder's six values, and *"no entry has left `candidate`"* | `…/gap-discovery/INDEX.md` §242 · `theory-inventory-assessment/README.md` §54 |
| renames as ordinary practice | `18-P08-…` §1, `τ7` — **56 renames in one day** |

⛔ **No claim is made about the ruling's content, the register's quality, or any remedy.** ⭐ **Every
number above was measured and published by the team that owns the register.**
