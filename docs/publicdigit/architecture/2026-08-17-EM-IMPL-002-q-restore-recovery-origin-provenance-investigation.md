# `Q-RESTORE` — where recovery ORIGIN is owned: investigation, options, no decision

**Type:** Architecture investigation (`EM-IMPL-002`, architecture hold before GREEN-5) · **Date:** 2026-08-17
**Status:** 🟡 **INVESTIGATION ONLY — nothing here is authorized, proposed-for-adoption, or implemented.** The domain core is frozen; several options below require **PO-authorized domain change** and are marked as such.
**Recorded by:** the implementation lane (evidence and options — `R-34`). **Instructed constraints honoured: no `HaltedAtGate` constructed · no protocol read port added · no domain modification · no new application service.**
**Evidence base:** `docs/publicdigit/implementation/2026-08-17-EM-IMPL-002-green4-verification.md` §4 · the UC-3 handler as committed (`4651a3e7`).

---

## 1 · The question, precisely

`EM-GOV-059`(c) and P-7 (`ResumptionTarget`) say restoration returns the election **to the unresolved gate** — it restores the *ability* to decide, never a decision. P-7's signature is:

```php
ResumptionTarget::resolve(HaltedAtGate $halt): GateDesignation
```

**The application cannot supply that argument.** In the authorized six-port universe:

| Possible source | Why it cannot answer |
|---|---|
| `ProtocolAppend` | append-only by contract — **no read side exists** (F-PROTO-1 properties 2/5; the port exposes exactly one method) |
| AG-3 `RecoveryProcess` | holds `electionId`, `kind`, `PolicyBinding`, recorded intervals — **no gate, no halt** |
| AG-2 `AcceptanceGateDecision` | holds a designation and positions — **records no halt**; "halted" is a progression condition, not a gate field |
| AG-1 `ElectionCommittee` | seats only |
| The UC-3 command | carries `electionId`, `seatId`, `appointeeReference` — and **must not** carry the target (see option E) |

**So `HaltedAtGate` — a type the frozen domain declares — has no producer anywhere in the system.** It is constructed only in tests. That is the finding: **this is the same shape as A-9 — a fact the model names but does not persist.**

### 1.1 What UC-3 does today (the proxy under review)

`ElectionRestored` names **the election's established acceptance decision** (first found in designation order). ✅ Exact while exactly one decision is established — which is Model A today. 🔴 **Silently wrong the day two designations are established at once**, and no tie-break or fallback was authored.

### 1.2 ⚠️ The half of `Q-RESTORE` that is easy to miss

**Restoration also happens with no prior halt at all.** The accepted RED pin `w8` restores an election whose gate is merely *Unachievable* (vacancy arithmetic) with **no halted-recovery period in existence** — and `ElectionRestored`'s constructor requires a non-nullable `GateDesignation`.

**Therefore any option that sources provenance from the halt covers only the halted case.** A complete answer must also say what the returned-to gate is **when nothing ever halted** — where, by `EM-GOV-059`(c), the election returns to a condition that was *in progress*, not halted. Options below are marked for whether they answer this half.

---

## 2 · Options

### Option A — AG-3 `RecoveryProcess` owns originating-gate provenance *(the PO's preferred investigation)*

The halted-recovery period is *started because of a halt at a gate*; it would carry that origin.

* **Mechanism:** the halted-recovery period records the designation it was started for; UC-3 reads it and P-7 becomes usable (or the read alone suffices).
* **Why it is the strongest fit:** ⭐ **the information already exists at the exact moment the period is created.** In the F-2 flow the UC-1 handler holds `$command->gate` when it starts the halted period — today it simply has nowhere to put it. **No new knowledge is invented; a fact currently discarded is retained.** This also matches the domain's own discipline: the period is a recorded consequence of a specific halt.
* **Consequences:** provenance lives with the aggregate whose existence it explains; no new port; no protocol reading; `HaltedAtGate` finally gets a producer.
* 🔴 **Requires DOMAIN AUTHORIZATION** — a new field/constructor argument on a frozen aggregate (`C-1`).
* ⚠️ **Also requires a RED amendment:** the accepted base fixture `seedRecoveryProcess()` constructs AG-3 directly, so a signature change touches the committed RED suite — its own authorization and its own commit.
* ❌ **Does not answer §1.2** (no halted period ⇒ no provenance). Needs a companion ruling.

### Option B — provenance carried on the `RecoveryPeriodStarted` fact

The recorded fact that starts the period names the gate.

* **Mechanism:** the event gains the designation; the record then *contains* the origin.
* **Consequences:** truest to "the record is the truth" (B-7) — the provenance becomes permanent history rather than aggregate state, and reconstitution (`fromRecordedFacts`) can rebuild it.
* 🔴 **Requires DOMAIN AUTHORIZATION** (the event class is frozen domain) **and** a read side to consult it — the application cannot read the protocol (option C). **So B alone does not make the value available at UC-3 time; it pairs with A or C.**
* ❌ Does not answer §1.2.

### Option C — a protocol read port

A new driven port lets the application query recorded facts.

* **Consequences:** would answer many future questions (replay, idempotency across restarts — cf. `Q-UC4`). **But** it expands the architecture to compensate for missing domain state, weakens the append-only contract's simplicity, and gives the application a general power to interpret history — the PO's stated reason to avoid it.
* 🔴 **Requires AUTHORIZATION for a NEW PORT** (grant `C-2`: closed six-port universe).
* ❌ Does not answer §1.2 by itself.

### Option D — keep the application-side proxy, ruled explicitly

The status quo, made intentional: "the election returns to its established acceptance decision; where several are established, the earliest unresolved designation."

* **Consequences:** zero new artifacts, zero domain change, works today. **But** the tie-break becomes an **application-authored rule about election meaning** — the G-1/G-5 line — and it is fragile precisely where it matters (two gates). If chosen, it should be pinned by RED and marked as a known limitation to revisit when a second decision can be established.
* ✅ No authorization beyond a ruling. ⚠️ **Weakest on sovereignty.**
* ✅ **Answers §1.2** (it never consults a halt at all).

### Option E — the command carries the target *(recorded as REJECTED, with reason)*

UC-3's caller supplies the returned-to gate.

* ⛔ **Wrong by the boundary:** UC-3's only legitimate caller is the **external appointment authority's** future D-1 adapter (A-3). Letting it name the resumption target would let an appointment body determine **election progression meaning** — the exact ownership transfer the increment exists to prevent. Recorded so it is not rediscovered as attractive.

---

## 3 · Summary — authorization required

| Option | Domain change | New port | Answers §1.2 (no-halt case) | Sovereignty |
|---|---|---|---|---|
| **A** AG-3 owns origin *(PO preferred)* | 🔴 **yes** (+ RED amendment) | no | ❌ | ✅ strong |
| **B** provenance on the started fact | 🔴 **yes** | pairs with A or C | ❌ | ✅ strong |
| **C** protocol read port | no | 🔴 **yes** | ❌ | ⚠️ broad new power |
| **D** ruled application proxy | no | no | ✅ | 🔴 weakest |
| **E** command carries it | no | no | ✅ | ⛔ rejected |

**The lane implements none of these and recommends none as a decision.** Two observations are offered as evidence, not as a choice: **(i)** option A retains a fact the system already has and then discards, which is why it reads as the natural home; **(ii)** no option covers §1.2, so **whatever is chosen needs a companion ruling for restoration without a prior halt** — otherwise `Q-RESTORE` will reopen at the first election that is restored while merely unachievable.

---

## 4 · Interim state, until a ruling exists

UC-3 keeps the §1.1 proxy, documented in the handler as an open question. **No further use case should consume the proxy**, and **no second acceptance decision should be established for one election** until this is settled — that is the condition under which the proxy is exact.

---

## 5 · Traceability

GREEN-4 verification record §4/§5.2 · UC-3 handler `4651a3e7` (class docblock, question 2) · RED acceptance record §3 (`Q-RESTORE`, registered) · P-7 `ResumptionTarget` · `HaltedAtGate` · `EM-GOV-059`(c) · `EM-GOV-060`/`061` · A-9 (the analogous unmodelled-fact precedent) · grant `C-1`/`C-2` · A-3 (external authority has no internal caller).
