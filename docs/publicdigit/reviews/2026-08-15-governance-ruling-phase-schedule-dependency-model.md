# Governance Ruling — Phase Schedule and Dependency Model

**Type:** Governance ruling (Session 2) · **Date:** 2026-08-15 · **Commission:** PO/ARB, *"Phase Schedule and Dependency Model"*
**⛔ Business/governance language only. No architecture, no Constitution change, no code, no tests, nothing inferred from current implementation. Architecture ⏸️ · Session 3 🛑 · Session 1 independent.**

---

## 0 · Authority for this ruling — stated before the ruling

**Governance does not ordinarily adopt new business policy.** It does so here **only** because the commission expressly delegates it: *"Please explicitly decide whether this is the intended business model"*, and *"I would not adopt A without your explicit Governance ruling."*

**The ruling is therefore split by provenance, and every clause is labelled:**

* **DERIVED** — follows from an already-adopted rule; Governance states it and adds nothing.
* **NEW POLICY (delegated)** — genuinely new, adopted under the express delegation above. **The PO may overrule any of it in one line.**

---

## 1 · The ruling

> ## ✅ **YES — the model as stated is the intended business model, and it is adopted.**
>
> **Publication of the complete schedule establishes the planned phase opportunities for the whole election. Each phase remains non-actionable until all four conditions hold together:**
>
> **① its own scheduled conditions are satisfied · ② its predecessor phase has legitimately completed all mandatory conditions · ③ the phase-specific Election Rules permit progression · ④ the required authorized actor has requested progression, where such authorization is required.**
>
> **This adopts reading `A` of `EM-OPEN-037`(b): planned opportunities come into existence at publication and remain dormant until their conditions hold. `EM-OPEN-038` is adopted with it.**

**Provenance of each condition:**

| | Condition | Status |
|---|---|---|
| ① | scheduled-time condition | **DERIVED** — `EM-VOT-004` (time makes an election *eligible for consideration*, never more) |
| ② | **predecessor legitimately completed** | **NEW POLICY (delegated)** — **no adopted rule stated a general dependency chain.** Partial support existed only incidentally *(e.g. `EM-VOT-003` needs approved candidates, which presuppose candidacy)*; **a general chain is new** |
| ③ | phase Election Rules permit | **DERIVED** — `EM-VOT-004`'s principle: the Rules decide whether progression is permitted |
| ④ | authorized request **where required** | **DERIVED for voting** (`EM-VOT-004`) · **deliberately NOT generalised** — see §5 |

**What the ruling forecloses, restated as adopted:** **a later scheduled phase can never outrun an incomplete predecessor.** *Candidacy's time arriving does not authorize candidacy.* The system records what happened and why progression could not occur.

## 2 · The rules adopted

| ID | Rule | Provenance |
|---|---|---|
| **`EM-GOV-010`** | **The published schedule is a plan, and it activates nothing.** Publication establishes the **planned phase opportunities** for the complete election; **each phase remains non-actionable until ① its scheduled conditions are satisfied, ② its predecessor phase has legitimately completed all its mandatory conditions, ③ the phase-specific Election Rules permit progression, and ④ the required authorized actor has requested progression, where such authorization is required.** **A phase must never progress because its scheduled time has merely arrived. The election phases are not independent: administration → candidacy → voting → counting → result publication, each conditioned on its predecessor's legitimate completion.** | ①③ DERIVED · ② **NEW POLICY (delegated)** · ④ derived-and-hedged |
| **`EM-VOC-008`** | **Six concepts, which must never be collapsed into one another.** **Schedule** = what was officially planned · **phase opportunity** = the governed opportunity created by the published plan · **phase eligibility** = whether the phase is currently permitted to be *considered* for progression · **progression request** = the authorized request to advance · **progression decision** = whether the Election Rules permit the requested progression · **phase activation** = the actual successful transition into the phase. **The protocol is the permanent historical record of material events, evaluations, requests, refusals, corrections and outcomes.** | **NEW POLICY (delegated)** — vocabulary discipline |

## 3 · The six concepts, and why the separation is load-bearing

**Each boundary in the chain is a place where information is destroyed if the two sides are merged:**

* **schedule vs opportunity** — merging them means a corrected schedule silently becomes "the schedule", erasing what was promised (`EM-GOV-005`, `D-2`);
* **opportunity vs eligibility** — merging them means a dormant planned phase is indistinguishable from one that was available and unused;
* **eligibility vs request** — merging them is exactly *"the clock started voting"*, which `EM-VOT-004` forbids;
* **request vs decision** — merging them makes the Chief's request self-authorizing, which `EM-GOV-008` forbids;
* **decision vs activation** — merging them means a refusal cannot be recorded unless a transition succeeded, which `F-PROTO-1`'s property 6 forbids;
* **all of them vs protocol** — merging any into the protocol makes history a by-product of current state.

> **The commission's worked example is now expressible, and that is the test the model had to pass:** *voting was scheduled for 21.08.2026 10:00 · the scheduled time was reached · candidacy had not legitimately completed because condition X failed · therefore voting could not progress · the event and reason were recorded · no authorization was created · no phase was activated.* **Neither "voting started because the clock reached 10:00" nor "voting did not exist" can be produced by this model.**

## 4 · ⚠️ One adopted rule is now carrying two meanings — ratification needed

**`EM-VOC-008` defines *phase eligibility* as whether a phase may currently be considered for progression — which, under `EM-GOV-010`, requires the time condition **and** the predecessor's completion.**

**`EM-VOT-004` already uses the word:** reaching the scheduled voting start time *"makes the election **eligible for the Chief Election Officer's consideration**"* — **time alone.**

**Governance rules the vocabulary and returns the amendment:** within `EM-VOC-008`, **eligibility means time-condition-satisfied AND predecessor-complete.** **This EXTENDS `EM-VOT-004`'s narrower use, and amending the meaning of an adopted rule is the PO's act, not Governance's.** ⛔ **One line ratifies the extension; until then `EM-VOT-004` reads in its original narrow sense and the two must be cited carefully.** Registered as **`EM-OPEN-041`**.

*(Governance flagged this risk before the commission arrived and does not treat the commission's wording as having resolved it.)*

## 5 · What this ruling deliberately does NOT decide

**`EM-OPEN-037`(a) survives — and the commission's own drafting preserved it.** Condition ④ says *"where such authorization is required."* **That hedge is doing real work: it does not declare every phase request-progressed, so `G-PROG-1` and `EM-OPEN-030` remain open for administration, candidacy, counting and result publication.** **Governance records this as deliberate and leaves it open.**

**Also untouched, per the commission's exclusions:** schedule-correction powers · expiry boundaries · cancellation authority · window-allowance semantics and `EM-OPEN-026`(a) · credential consequences · notification vs visibility (`EM-OPEN-036`) · technical representation · implementation · per-phase rule content.

**`EM-OPEN-022` remains settled authority** and the commission's correction paragraph restates it exactly: superseded opportunity → new opportunity → new schedule → new progression decision, with **no** authorization, refusal or eligibility decision carrying across.

## 6 · Remaining contradictions and ambiguities

**① The sixth outcome — a real gap opened by adopting `A`.** `EM-VOC-004`'s five outcomes **cannot name a planned opportunity that never became available.** *Expired unused* implies it **was** available; *cancelled* implies an act; *superseded* implies a later schedule. **A voting opportunity whose predecessor failed is none of these.**

**Governance rules the requirement and returns the naming:** **DERIVED from `EM-VOC-004` — this case MUST remain distinguishable**, because collapsing *"never became available"* into *"expired unused"* is precisely the information loss that rule exists to prevent. ⛔ **Whether that means a sixth outcome or an explicit ruling that one of the five covers it is the PO's decision — `EM-OPEN-040`.**

**② The correction cascade.** Under plan-first, one failure in an early phase may require correcting **every later window** — several corrections, several superseded opportunities, several new authorizations. **Does correcting one window require, permit or forbid correcting the downstream windows in the same governed act? — `EM-OPEN-039`.** *(Excluded from this commission as a correction power; registered, not decided.)*

**③ No contradiction was found between this model and any adopted rule.** Governance checked `EM-VOT-004` · `EM-VOT-005` · `EM-VOC-004`/`005`/`007` · `EM-GOV-004`…`009` · `P-2H` · `F-PROTO-1`. **The only tension is the vocabulary extension in §4, and it is returned rather than resolved.**

## 7 · New Governance questions requiring PO/ARB decision

| ID | Question |
|---|---|
| **`EM-OPEN-039`** | **Correction cascade** — may/must/mustn't a single governed correction cover downstream windows made unreachable by an earlier phase's failure? |
| **`EM-OPEN-040`** | **The sixth outcome** — how is *"planned, but never became available"* named and distinguished? *(That it must be distinguishable is DERIVED and already binding.)* |
| **`EM-OPEN-041`** | **Ratify the `eligible` extension** — does `EM-VOT-004`'s *eligible* now mean time **and** predecessor-complete, or do the two senses stay separate under different names? |

**Still open and unchanged:** `EM-OPEN-021` · `024` · `025` · `026`(a) and (c)(d)(e) · `027` · `028` · `029` · `030` · `031` · `033` · `034` · `035` · `036` · `037`(a) · `EM-GOV-004`'s six boundaries · `EM-VOC-004`'s wording ratification.

## 8 · Handoff

```
RULED            The phase dependency model is ADOPTED — EM-GOV-010 + EM-VOC-008
                 Reading A adopted: planned opportunities exist at publication, dormant
                 EM-OPEN-037(b) CLOSED · EM-OPEN-038 CLOSED
PROVENANCE       conditions ①③ DERIVED · ② NEW POLICY under the commission's express
                 delegation · ④ derived and deliberately NOT generalised
PRESERVED        EM-OPEN-037(a) / G-PROG-1 survive — condition ④'s "where such
                 authorization is required" kept them open, deliberately
RETURNED         EM-OPEN-039 cascade · EM-OPEN-040 sixth outcome · EM-OPEN-041 ratify
                 the "eligible" extension (amending an adopted rule is the PO's act)
ARCHITECTURE     ⏸️ PAUSED     SESSION 3  🛑 STOPPED     SESSION 1  independent
IMPLEMENTATION   NOT AUTHORIZED
```

**Traceability.** Commission (PO/ARB, 2026-08-15) · dependency-chain registration (`9949ea19`) · plan-first (`ca95560d`) · `Q4` report (`16f4dd3d`) · `EM-VOT-003`/`004`/`005` · `EM-VOC-004`/`005`/`007` · `EM-GOV-004`…`009` · `P-2H` · `F-PROTO-1` · `G-PROG-1`.

**STOP — end of Governance ruling. Architecture does not follow.**
