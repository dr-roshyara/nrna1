# S2-F001 · A report *about* persistence is not persistence-derived evidence

**Class:** CHALLENGE
**Status:** OPEN · review record only · not adjudicated, not architecture

---

| | |
|---|---|
| **Source Session-1 artifact** | `session1/S1-F001-authority-temporal-semantics-gap.md` |
| **Target of challenge** | its evidence-class assignment, **not** its substantive claim |
| **Lens** | Provenance · Evidence |

---

## FINDING

S1-F001 assigns its source the **strongest evidence class in the corpus**, on the ground that it is *"implementation/persistence-derived evidence — priority 1–3 — not priority-7 brainstorming."*

**The artifact in hand is not the persistence record. It is a Phase-1 narrative document, located in `brainstorming/`, that *describes* measurements over persistence records.**

Those are different evidence classes. The workflow records themselves would be priority 1–3. A document reporting what they contain is a **secondary description** of them, and inherits the reliability of its author's inspection rather than of the data.

## EVIDENCE

- S1-F001's own provenance table gives the source path as `docs/knowledgeos/brainstorming/20260821-2033-what-eks-is-today.md` — i.e. **inside the brainstorming corpus**, not a persistence artifact.
- S1-F001 labels it `⟦RESEARCH FACT⟧ — measured over 20 observed grants, not proposed` and then explicitly elevates its priority band.
- No quoted grant identifier, record path, or query appears in S1-F001. The measurement is **asserted by the source document**, not exhibited.

## WHY IT MATTERS

S1-F001 leans on this classification: *"the strongest evidence class encountered in the corpus so far."* That sentence will be cited. If the class is one band too high, every downstream weighting inherits the inflation — and this programme has already had one provenance failure in which a description of reasoning was treated as the reasoning itself.

**The substantive claim may well be exactly right.** The challenge is narrow: *unverified-by-this-review, and one class lower than recorded* is different from *measured*.

## POSSIBLE IMPACT

Corpus-methodology only. **A precise remedy exists and is cheap:** record the class as *secondary report of persistence-derived measurement*, and mark the underlying grants as **verifiable-on-request** rather than verified. That preserves the finding's force and removes the inflation.

## PROVENANCE

Derived solely from `session1/S1-F001`. **The corpus source was not read** — this review operates under a read restriction to `session1/` only, which is itself why the measurement could not be verified here.

## STATUS

**OPEN — CHALLENGE to classification, not to substance.** Session-1's conclusion is not overwritten; the independent challenge is recorded per instruction.

---

## EXTENSION 1 — the pattern recurs in `S1-F003` (added on review of S1-F003)

**The same class assignment appears again, for `ChangeSet`:**

> ⟦I⟧ *"This is an **implemented** mechanism, not a proposal — evidence priority 1–3, not 7."*

**The artifact in hand remains a Phase-1 brainstorming document that *states* the mechanism is implemented.** No code path, module, test or commit is cited. So the same one-band elevation is applied a second time, to a second subject, from a second document in the same corpus location.

**Two instances make this a pattern rather than a slip**, and the pattern has a direction: it always elevates. Worth recording because a systematic upward bias in evidence class is harder to detect than a single misclassification — each instance looks locally reasonable.

**Note what is *not* being challenged.** S1-F003 pairs the claim with an explicit and correct caution — *"⚠ `implemented ≠ Kernel-authoritative`. Implementation raises the *evidence class*; it confers no architectural standing."* That guard is exactly right and Session 1 deserves credit for it. The challenge is narrower: **the evidence class it raises has not itself been verified.**

## EXTENSION 2 — the distinction to keep explicit throughout

Per HPA direction, this challenge stands as a **methodological caution and does not invalidate the underlying observation.** The distinction to preserve everywhere in both registers:

| Statement | Status |
|---|---|
| *"The document reports that persistence was observed"* | ✅ what these artifacts establish |
| *"We independently verified the persistence measurement"* | ❌ not established by any artifact in either register |

Both S1-F001's `0/20` grants and S1-F003's `ChangeSet` implementation sit in the first row. **Neither claim is doubted; both are unverified-by-this-review**, and the read restriction to `session1/` is what makes verification impossible from here rather than merely undone.
