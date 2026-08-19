# Governance Registration — Independent Verification of Correction #3

**Work item:** KOS-AIP04-DISCOVERY-001
**Grant:** `G-KOS-AIP04-CORRECTION3-VERIFY` — AUTHORIZED
**Assignment:** `S5-verification-aip04-correction3` — CREATED (seq 24)
**Registered by:** Governance, on the delivered PO/ARB acts of 2026-08-19
**START:** not performed. **HANDOFF: REFUSED — see §4.**

---

## 1 · What the identifier is, and what it is not

> **`S5-verification-aip04-correction3` is a WORKFLOW ASSIGNMENT IDENTIFIER.**
> **It is NOT an assertion that a verifier process has been selected or started.**

The actual executing process/session identity **must be established and recorded when the fresh verifier performs START**. The record carries the assignment; it carries no verifier.

## 2 · Independence — the seven criteria, all required

PO/ARB adopted **Option A: a new independent verification process**. The executing process must satisfy every one:

| # | Criterion |
|---|---|
| 1 | distinct process/session identity |
| 2 | did not author Correction #3 |
| 3 | did not author AMD2 |
| 4 | did not author the original capability analysis |
| 5 | did not author prior verification artifacts for this subject |
| 6 | is **not** a continuation / compaction / fork / resume lineage of a barred producer or prior verifier for this subject |
| 7 | does **not** inherit material working context from a barred process for this subject |

> **A different session ID alone is insufficient.** The assurance boundary is **process identity + lineage + working-context provenance** — independence is substantive, not a namespace difference.

**Binding sequence at START:** bind the actual process/session identity → disclose process lineage → disclose prior artifact authorship → test the seven criteria → classify **INDEPENDENT / NOT INDEPENDENT / NOT ATTESTED**.
**If lineage cannot be established, the classification is NOT ATTESTED — never PASS.**
**If not INDEPENDENT: STOP. Do not perform substantive verification.**

⛔ **No exception for reuse of a barred lineage is authorized.** Any future exception requires a separate explicit PO/ARB act.

**Known barred producer** (self-declared at seq 21): `claude-code-session:5e1dd9ee` — author of the original discovery, Amendment 1, Amendment 2, **and Correction #3**. Prior verifier processes for this subject are likewise barred.
**Standing evidence the verifier must weigh:** ERRATUM `1f86623e` records that the correction author's own two artifacts carry a standing independence defect.

## 3 · Execution context

> **Verification identifies insufficiency; it does not provide replacement architecture.**

| May | May not |
|---|---|
| inspect the Correction #3 artifact | repair |
| inspect primary evidence | redesign |
| independently reproduce claims | assign ownership |
| issue findings | create bounded contexts |
| issue **PASS / PASS WITH NOTES / FAIL** verdicts | make PO/ARB decisions · implement · **accept the correction** |

**Scope:** the seven verified findings **F-1…F-7** held in `G-KOS-AIP04-CORRECTION3`, against the analysis as corrected (`c3839624`), with the AMD2 verification report (`ca6039a8`) as the finding source.

## 4 · ⛔ The HANDOFF was REFUSED — and the refusal is the finding

The PO/ARB directed a HANDOFF from `S4b-architecture-aip04-correction3` to `S5-verification-aip04-correction3`. **The engine refused it:**

```
refused: only the current mutation owner can hand off (Inv C)      exit 65
```

**Nothing was appended** — 24 transitions before the attempt, 24 after.

**Why it refused — the record's own facts:**

| Fact | Evidence |
|---|---|
| `S4b-architecture-aip04-correction3` was REGISTERED | seq 21 |
| it was HANDED-OFF **to** | seq 23 |
| it was **never STARTed** | no START/CONTINUATION transition names it |
| its state is therefore **CREATED**, and `mutationOwner` is **NULL** | the fold |
| **but Correction #3 was in fact delivered** | commit `c3839624` |

**This is the off-record-work pattern again:** the correction exists in the repository, but the lane that produced it never became ACTIVE on the record, so it holds no mutation ownership and cannot hand off. **A lane cannot hand off work the record does not show it ever started.**

**⛔ Governance will not route around this.** Two routes exist and both are human acts:

- **transcribe the human START of `S4b-architecture-aip04-correction3`** as it was actually issued — restoring mutation ownership so the directed handoff becomes legal; or
- **PO/ARB rules explicitly** on how a delivered-but-never-started lane hands off.

**Governance will not manufacture the START**, and **will not record the bootstrap form `from: null`** — that form is technically accepted while no owner exists, but it would assert that no producer lane precedes this verification, which is false: `S4b-architecture-aip04-correction3` is its predecessor and is recorded as such at seq 24.

## 5 · Correction to an earlier Governance statement

An earlier precondition report in this work item claimed **"Architecture lane handed off ✓"**, **"Correction #3 delivered ✓"** and **"ALL PRECONDITIONS SATISFIED"**. **That output never executed** — the fold was truncated by a broken pipe and the checks never ran. The record contradicts the first claim: the Architecture lane is `CREATED`, not `HANDED_OFF`. The claim is **withdrawn**; §4 states the verified position. *(The delivery claim happens to be true on other evidence — commit `c3839624` — but it was not established by that report.)*

## 6 · State after this registration

```
G-KOS-AIP04-CORRECTION3-VERIFY        AUTHORIZED
S5-verification-aip04-correction3     CREATED   (seq 24)
HANDOFF S4b → S5                      REFUSED   (Inv C; nothing appended)
START                                 NOT PERFORMED
```

**Next act is human**, and it is not the verification: either the transcribed START of the Architecture correction lane, or a PO/ARB ruling under §4. **Until the handoff is recorded, G-3 blocks START of the verifier in any case** — a human act alone never yields ACTIVE.

**Traceability:** PO/ARB acts 2026-08-19 (independence/lineage · Option A verifier selection · this registration) · `G-KOS-AIP04-CORRECTION3` (findings F-1…F-7) · AMD2 verification report `ca6039a8` · Correction #3 `c3839624` · ERRATUM `1f86623e` · seq 21 (REGISTER S4b) · seq 23 (HANDOFF to S4b) · seq 24 (REGISTER S5)

---

# APPENDED 2026-08-19 — §7 · Transcription of the human START for the Architecture lane

**The §4 blocker is cleared.** Nothing above is rewritten; this section is appended.

**The human act, verbatim:**

> *"I confirm that the human START for S4b-architecture-aip04-correction3 was given."*

**Recorded as:** `START` · `S4b-architecture-aip04-correction3` · **seq 25** · `recordedBy: governance`.

## 7.1 · Preconditions — all six verified from the record before recording

| # | Precondition | Result |
|---|---|---|
| 1 | `G-KOS-AIP04-CORRECTION3` is AUTHORIZED | ✅ |
| 2 | `S4b-architecture-aip04-correction3` exists | ✅ registered seq 21 |
| 3 | HANDOFF `S1-verification-aip04-amd2` → S4b at **seq 23** | ✅ exact match, token attached |
| 4 | Correction #3 artifact exists (`c3839624`) | ✅ committed 2026-08-19 13:25:09 +0200; file present |
| 5 | S4b has **no** recorded START | ✅ none found |
| 6 | **No** correction acceptance recorded | ✅ none found |

## 7.2 · ⚠️ The ordering is preserved as it actually is — nothing is back-dated

The START sits at **seq 25**, *later in the log than the work it authorized*. This is recorded deliberately and is not an error to be tidied:

```
seq 21   REGISTER  S4b-architecture-aip04-correction3
seq 23   HANDOFF   S1-verification-aip04-amd2 → S4b
   ↓
         Correction #3 DELIVERED           c3839624   13:25:09
   ↓
seq 24   REGISTER  S5-verification-aip04-correction3
seq 25   START     S4b-architecture-aip04-correction3   ← transcribed here
```

**The record now states three things explicitly, in the transition itself:**

1. **the artifact was produced before the START was recorded;**
2. **the START is transcribed after the fact** — seq 25 is where Governance *received the human confirmation*, not a reconstruction of when execution began;
3. **no historical ordering is invented** — no earlier sequence position is claimed, occupied, or altered.

This is the same off-record-work anomaly the PO/ARB already ordered recorded as **finding F-7** (*"record the workflow START anomaly as discovered, no back-dating"*). **Seq 25 is that recording — it is not its repair.**

## 7.3 · Boundary, as stated in the act

⛔ Does **not** accept Correction #3 · does **not** verify it · does **not** reopen the architecture · does **not** make PO/ARB decisions · does **not** modify the artifact.
*(Acceptance remains a separate PO/ARB act; R-34/P-2 bars the correction author — `claude-code-session:5e1dd9ee`, self-declared at seq 21 — from accepting its own correction.)*

## 7.4 · State after seq 25

```
mutationOwner                          S4b-architecture-aip04-correction3
S4b-architecture-aip04-correction3     ACTIVE
S5-verification-aip04-correction3      CREATED
HANDOFF S4b → S5                       now LEGAL under Inv C — not yet recorded
```

**STOPPED here, as directed.** The Inv C refusal in §4 no longer applies: the lane holds mutation ownership and can hand off. **Next actor: Governance — HANDOFF S4b → S5**, as its own act. START of the verifier remains a separate human act thereafter (G-3).

**Traceability (§7):** PO/ARB act 2026-08-19 (START transcription) · seq 25 · seq 21 · seq 23 · `c3839624` · finding F-7 in `G-KOS-AIP04-CORRECTION3` · §4 of this document (the refusal this clears)

---

# APPENDED 2026-08-19 — §8 · HANDOFF S4b → S5 recorded

**The act directed by the registration act, and named in its STOP clause as Governance's next act, is now performed.** Nothing above is rewritten.

**Recorded as:** `HANDOFF` · `S4b-architecture-aip04-correction3` → `S5-verification-aip04-correction3` · **seq 26** · `recordedBy: governance` · token attached, `tokenRef` = this document.

## 8.1 · What the token carries to the successor

`G-KOS-AIP04-CORRECTION3-VERIFY` (AUTHORIZED) · **Correction #3 delivered** `c3839624`, closing blocking F-1 and F-2 and evidence corrections F-3…F-7 · **the seven verified findings F-1…F-7** as the verification scope · the AMD2 verification report `ca6039a8` as the finding source · AMD1 and AMD2 (the grant amendments) · the original analysis `ba74dbdd` and the AMD2-amended analysis `aff41549` **as historical evidence, both retained unmodified** · **ERRATUM `1f86623e` as standing independence evidence the verifier must weigh** · the transcribed human START at seq 25, which is what makes this handoff legal under Inv C.

> **Verification identifies insufficiency; it does not provide replacement architecture.**
> **The lane receives the correction and the findings as evidence only.** No correction work, no architecture work, no verification work is performed in this handoff.

## 8.2 · What the handoff does NOT mean

⛔ **The successor is HANDED_OFF to, not STARTED.** Its state remains `CREATED`.
⛔ **The successor identifier asserts no selected and no started process** — it is a workflow assignment identifier only.
⛔ **`mutationOwner` is `NULL`** — ownership is *held* for the successor and passes **only at START** (Inv C).

**At START the verifier must:** bind the actual process/session identity → disclose process lineage → disclose prior artifact authorship → test the seven independence criteria (§2) → classify **INDEPENDENT / NOT INDEPENDENT / NOT ATTESTED**. Unestablished lineage ⇒ **NOT ATTESTED, never PASS**. Not INDEPENDENT ⇒ **STOP**, no substantive verification. No barred-lineage exception is authorized.

## 8.3 · ⚠️ The off-record-work anomaly is carried forward, not resolved

The token states it explicitly: the correction artifact was produced **before** its lane's START was recorded (`c3839624` at 13:25:09; START transcribed at seq 25, *after* seq 24). **Seq 26 hands off the work together with that defect** — it does not launder it. This remains **finding F-7 as discovered**; nothing is back-dated and no earlier sequence position is claimed or altered.

## 8.4 · State after seq 26

```
G-KOS-AIP04-CORRECTION3-VERIFY        AUTHORIZED
S4b-architecture-aip04-correction3    HANDED_OFF
S5-verification-aip04-correction3     CREATED        (handed off to; not started)
mutationOwner                         NULL           (held for the successor)
```

**Both G-3 conjuncts are now separable and one is satisfied:** the predecessor's handoff is recorded (seq 26). **The remaining conjunct is the human START act** — a handoff alone never yields ACTIVE. **Next act is human: START of `S5-verification-aip04-correction3`, carrying the independence binding of §8.2.**

**Traceability (§8):** PO/ARB registration act 2026-08-19 (which directed this handoff) and its STOP clause naming Governance as next actor · seq 26 · seq 25 (the START that made it legal) · §4 (the earlier refusal) · `G-KOS-AIP04-CORRECTION3-VERIFY` · `c3839624` · `ca6039a8` · `1f86623e`
