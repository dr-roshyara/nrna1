# Registration — **`S4-architecture-v3-determination`: the V-3 finding author is now barred**

**Registered by:** Governance · 2026-08-19 · **Work item:** `KOS-CONTRACT-NEUTRALITY-001` (Track 1)
**Amendment:** `G-KOS-CONTRACT-V3-ARCH-AMD1` (`AUTHORIZED`) · **⛔ `START` NOT performed by this act.**

## 1 · The act, and why the PO/ARB raised it

The PO/ARB found that the record expressed a **weaker bar than the assignment requires**:

```
Track-1 implementer   → barred          (seq 38: "a process OTHER THAN the Track-1 implementer")
V-3 finding author    → NOT barred
```

> **The reason, in the act's own words:** *"The finding author should also be excluded because this architecture assignment asks: **is my own verification finding actually entailed by the accepted model?**"*

⭐ **This is `R-34` read in the mirror.** `R-34` stops a producer verifying its own work; the same principle stops a **verifier adjudicating the architectural standing of its own finding.** The seq-38 context covered only the first direction.

## 2 · The condition registered — verbatim, as the act gave it

> **"The performing Architecture process MUST NOT be:**
> **1. the Track-1 implementation producer; or**
> **2. the author of the V-3 finding / Track-1 independent verification report that produced V-3.**
>
> **The purpose is to ensure that the Architecture determination does not judge the adequacy or entailment of a finding authored by the same process.**
>
> **If the performing process matches either excluded process: STOP before substantive analysis."**

**Named, so the bar is checkable rather than abstract:**

| # | Excluded process | Identified by |
|---|---|---|
| **1** | Track-1 **implementation producer** | self-declared `claude-code-session:1c8b041b` · delivery `4c6c1dac` · **also authored the prior V-3 determination `99aeac7c`** |
| **2** | Author of the **V-3 finding** | self-declared `claude-code-session:2da45a86` · report `50d55d26` · **its lane `S1-verification-track1-php-adapter` is the REGISTERED PREDECESSOR of this assignment** |

⚠️ **Both identities are self-declared and NOT attestable** (`INV-ATTR-2`/`G-2`), and per **`INV-ATTR-1`** no gate reads process identity. **This is a duty on the performing process and on where the PO/ARB starts the session — never a mechanism.** Governance registers the duty and cannot enforce it.

## 3 · ⚠️ Governance disclosure — this act was recorded by excluded process 2

**The process recording this amendment is the V-3 finding's author** — the very process the amendment bars. **Disclosed rather than left for the reader to notice**, per `P-2`:

* **`D-a`** it held Verification on Track 1 (`50d55d26`) and Governance on this work item (the Track-1 verification registration), and now Governance again;
* **`D-b`** prior acts by SHA: `50d55d26` (the report that produced V-3), `f56f129e` (the earlier Governance registration);
* **`D-c`** `asserted`, never attested;
* **`D-d`** the independent contribution here is mechanical only: verifying that the amendment can be recorded **without rewriting history** (§4).

> ⭐ **The direction of this conflict is the safe one, and that is why it was proceeded with: the act REMOVES the recording process's own eligibility.** A process cannot advantage itself by disqualifying itself. **Had the amendment widened this process's authority instead, it should have been refused and returned.**

## 4 · Why this is a grant amendment and **not** an edit of the assignment

**The seq-38 `executionContext` is immutable by construction — measured, not assumed:**

* `AST-015` refuses a second `REGISTER` for a registered session — *"session assignment already registered — role is immutable; a role change is a new assignment (`R8`)"* (`workflow-state.php:191–193`);
* the machine has **no `AMEND` edge**; an unknown transition type is refused;
* the transition log is **append-only**, and **decision text and history are never rewritten** (`ES-004.3`).

⇒ **The condition binds the assignment BY REFERENCE from the Authority State** — the estate's established `-AMD` pattern (`…-IMPL-ARCH-AMD1…AMD4`, `…-IMPL-TRACK1-AMD1`, `…-TRACK1-VERIFY-AMD1`, `…-ARTIFACT-UPDATE-AMD1`). ⛔ **The assignment was NOT re-registered and its recorded `executionContext` was NOT altered.** A reader must read `G-KOS-CONTRACT-V3-ARCH` **together with** `AMD1`.

## 5 · What did NOT change

⛔ **No new assignment · no scope change · no deliverable change · no question added or removed · no semantic decision touched · `13.3`/`13.5` untouched · no other grant amended · `START` not performed.**
✅ **The parent grant stands in full:** the two questions `V-3a`/`V-3b` · the DDD test · the classification rule · the **ten-section deliverable including both PROPOSED WORDING sections** · the standing caveat that **`99aeac7c` is EVIDENCE AND PROPOSAL MATERIAL ONLY** · the forbidden list · `R-34`/`P-2` binding forward on whoever performs it.
✅ **The seq-39 `HANDOFF` stands and is unaffected**; the `G-3` conjunction still requires the human `START` act, which has not occurred (`state: CREATED`, `mutationOwner: None`).

## 6 · One question preserved for the fresh architect — as EVIDENCE, not as a conclusion

The PO/ARB restated the assignment's own DDD test in a sharper form, and it is recorded here so the fresh lane meets it head-on:

```
dynamic METHOD reference   $this->$m()  →  BehaviourReference?
dynamic PROPERTY reference $this->$p    →  StateAccess with determinability? another fact? explicitly out of model?
```

**The observed asymmetry:** `BehaviourReference` carries `QualifierKind::ComputedTarget` **and** `Determinability`; `StateAccess` carries `propertyName` and `accessMode` **and nothing else** — so it has no field in which *"the property name was not determinable"* could be expressed. `OBSERVED`.

> ⚠️ **Provenance of that observation, disclosed because it matters: it originated with the V-3 finding's author — excluded process 2 — and it is therefore EVIDENCE TO BE TESTED, never a conclusion to be inherited.** ⛔ **The fresh architect must not copy the prior `V-3a` conclusion, and must not assume the two constructs are one fact.** **This adds no scope: the parent grant already asks *"what is the domain fact?"* and *"what distinction must L3 preserve?"***

## 7 · Next actor

🔵 **The PO/ARB — `START` `S4-architecture-v3-determination` in a fresh Architecture process** holding **neither** Track-1 implementation authorship **nor** V-3 finding authorship.
⛔ Until that determination is settled, **`G-KOS-CONTRACT-ARTIFACT-UPDATE` stays gated** by `AMD1`: no expected node/edge evidence is authored and the artifact-update assignment does not execute.

**Traceability:** `G-KOS-CONTRACT-V3-ARCH` + **`AMD1`** · assignment `S4-architecture-v3-determination` (seq 38 REGISTER · 39 HANDOFF · **no START**) · predecessor `S1-verification-track1-php-adapter` (seq 35–37) · V-3 finding `50d55d26` · prior determination `99aeac7c` (evidence only) · Track-1 delivery `4c6c1dac` · Track-1 acceptance registration `2026-08-18-…-track1-acceptance-registration.md` · `G-KOS-CONTRACT-ARTIFACT-UPDATE` + `AMD1` (gated on V-3) · `workflow-state.php:191–193` · `R-34`/`P-2` · `R8` · `G-3` · `ES-004.3` · `INV-ATTR-1`/`INV-ATTR-2`.
