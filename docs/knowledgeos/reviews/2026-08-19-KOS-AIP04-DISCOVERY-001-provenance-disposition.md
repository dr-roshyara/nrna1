# Provenance Disposition — Correction #3 verification path

**Work item:** KOS-AIP04-DISCOVERY-001
**Registered by:** Governance (`b64828fe`), on the delivered PO/ARB act 2026-08-19 ("PROVENANCE DISPOSITION — CORRECTION #3 VERIFICATION")
**Recorded as:** grant amendment **`G-KOS-AIP04-CORRECTION3-VERIFY-AMD2`** — **additive**; extends `-AMD1`; **no prior text deleted, rewritten or superseded.**

> **Governance / PO-ARB recording act only.** ⛔ No substantive verification · C-5 analysis not reopened · **no replacement verifier registered or started** (that authorization was expressly withheld).

---

## A · Correction #3 producer attribution

| | |
|---|---|
| **Original attribution — stated, not denied** | The grant text **did contain, and still contains in history**, `claude-code-session:5e1dd9ee` — *"author of the original discovery, Amendment 1, Amendment 2 and Correction #3"*, self-declared at **seq 21**. ⛔ **This record makes no claim that the historical text never said `5e1dd9ee`.** |
| **Evidence-derived attribution** | **`claude-code-session:4858c37c`** |
| **Primary evidence** | write-class tool provenance correlated to commit timestamps (transcripts UTC, commits `+0200`): `4858c37c` issued edits at **11:21:56Z** and **11:22:27Z** and a heredoc append at **11:25:05Z** — **4 s** before commit `c3839624` (11:25:09Z); payload consistent with the recorded **+179 / −5**. Across all **33** transcripts the analysis has **exactly three** content-write windows — `5e1dd9ee` 07:41, `5e1dd9ee` 08:27–08:29, `4858c37c` 11:22–11:28 — and **no other process ever wrote to it**. `5e1dd9ee` issued **no** write to that artifact after **08:29:20Z**. |
| **Why the original was incorrect** | It was **inherited from a self-declaration rather than measured.** It was **true of the predecessor work** (`5e1dd9ee` did author `ba74dbdd` and `aff41549`) and was **carried forward to Correction #3 without re-testing**; the grant then repeated it. That is authorship inferred from grant text and self-declaration alone. |

## B · Status of `ca6039a8`

> ## **HISTORICAL VERIFICATION EVIDENCE · NOT INDEPENDENT / PROVENANCE-CONFLICTED**

**Reason:** `4858c37c` authored **both** `ca6039a8` (the AMD2 verification report, source of findings **F-1…F-7**) **and** `c3839624` (Correction #3, the answer to those findings). **The process evaluated the insufficiency and then produced the correction to that insufficiency** — contrary to **R-34/P-2** and to the seq-23 token's own rule: *"VERIFICATION IDENTIFIES INSUFFICIENCY; ARCHITECTURE SUPPLIES THE CORRECTION."*

| | |
|---|---|
| ⛔ **Cannot** | serve as **independent assurance** of the Correction #3 artifact |
| ✅ **Remains** | **valid historical evidence**; its substantive findings **stand** and **remain the verification scope** |
| ⛔ **Not done** | `ca6039a8` is **not deleted**, **not rewritten**, and its findings are **not erased** |

**Only its independence status is refused as assurance.** The distinction is deliberate: the findings were sound enough that Correction #3 acted on them; what fails is the *assurance* claim, not the *content*.

## C · Status of the S5 refusal (seq 27)

**Recorded as a VALID independence-gate refusal.**

- ✅ valid refusal · **no substantive verification performed** · **no verdict issued** · **no acceptance** · **NO CLEARANCE**
- ⛔ **must not be interpreted as a verification result**, and must never be read as the barred process having performed the verification
- **`S5-verification-aip04-correction3` remains OUTSTANDING and UNCONSUMED**

## D · Required independence criteria for the fresh verifier

**A genuinely fresh verification process is required. All seven must hold:**

| # | Criterion |
|---|---|
| 1 | distinct process/session identity |
| 2 | no authorship of the original capability analysis |
| 3 | no authorship of AMD1 / AMD2 |
| 4 | no authorship of Correction #3 |
| 5 | no prior verification artifact for this subject |
| 6 | no barred continuation / compaction / fork / resume lineage |
| 7 | no inherited material working context from a barred process |

> **Session ID alone is insufficient.** **If lineage cannot be established the classification is `NOT ATTESTED` — and `NOT ATTESTED` must never be treated as `PASS`.**

**Barred set, on evidence rather than self-declaration:**

| Process | Ground |
|---|---|
| **`4858c37c`** | authored **both** the findings (`ca6039a8`) **and** the correction (`c3839624`) |
| **`5e1dd9ee`** | original analysis (`ba74dbdd`) and artifact amendment (`aff41549`); **also already refused this verification** at seq 27 |
| **`2da45a86`** | Independent Verification #2; **source of `4858c37c`'s inherited working context** |
| **`1c8b041b`** | Verification #1 |

### D.1 · ⚠️ The bar on `5e1dd9ee` is preserved, and not weakened

`5e1dd9ee` **remains barred**. **Eligibility must not be inferred from the fact that it did not author Correction #3:** criterion 2 ceasing to bind **does not clear criteria 4, 6 or 7**, each of which fails independently on write provenance.

*Recorded for precision, not as a challenge to the bar:* the reconciliation established that **"AMD1"/"AMD2" name grant amendments authored by Governance `b64828fe`**, while the **amended artifact (`aff41549`) was authored by `5e1dd9ee`**. On either reading `5e1dd9ee` authored the analysis and its artifact amendment, **so the bar stands unchanged.**

## E · Current next actor

> **Governance — register the corrected verification path**, as its own separate act.

⛔ **Not registered or started here.** The act withheld that authorization explicitly, and Governance does not extend its own mandate. Note also that **`S5` currently holds mutation ownership** (seq 27, ACTIVE), so the corrected path will have to address that lane's disposition — Governance manufactures no handoff.

## F · Explicitly not decided by this act

C-5 category · C-5 ownership · C-5 assurance level · **OQ-L** · capability ownership · bounded-context status · implementation · build order. **These remain separate PO/ARB decisions.**

## G · History discipline

**Every correction in this record is additive, explicitly attributed, and traceable to primary evidence.** No prior record is rewritten: seq 21's self-declaration stands as written; `G-KOS-AIP04-CORRECTION3-VERIFY` stands as written; `-AMD1` stands; `ca6039a8` and `c3839624` are untouched; seq 25/26/27 are untouched.

**Traceability:** PO/ARB act 2026-08-19 (provenance disposition) · `G-KOS-AIP04-CORRECTION3-VERIFY-AMD2` · `-AMD1` · provenance reconciliation `2026-08-19-…-provenance-reconciliation.md` §1/§3/§4/§5/§6/§10 · seq 21 · seq 23 · seq 25 · seq 26 · seq 27 · commits `ba74dbdd` `aff41549` `acdc613f` `12351dd6` `ee77c6c2` `ca6039a8` `c3839624` `1f86623e` · R-34/P-2 · INV-ATTR-1/INV-ATTR-2

---

# APPENDED 2026-08-19 — **H · `S5` released ownership at seq 29; what its `HANDED_OFF` state does and does not mean**

**Nothing above is rewritten. §C stands as recorded.**

## H.1 · What happened

| seq | transition |
|---|---|
| **28** | `REGISTER` `S4c-architecture-c10-d5-criteria` *(governance)* |
| **29** | ⭐ **`HANDOFF` `S5-verification-aip04-correction3` → `S4c-architecture-c10-d5-criteria`** *(governance)* |
| **30** | `START` `S4c-architecture-c10-d5-criteria` *(human)* |

**Current fold:** `mutationOwner = S4c-architecture-c10-d5-criteria` (ACTIVE) · **`S5-verification-aip04-correction3` = `HANDED_OFF`.**

## H.2 · ⚠️ The state is reconcilable with §C — but only if it is stated

**§C recorded `S5` as *"a valid independence-gate refusal … the assignment remains OUTSTANDING and UNCONSUMED."* The fold now shows `S5 = HANDED_OFF`. These do not conflict, because they describe different things:**

| | |
|---|---|
| **`HANDED_OFF` means** | **the lane RELEASED MUTATION OWNERSHIP.** The engine sets this on the `from` session of any handoff |
| **`HANDED_OFF` does NOT mean** | ⛔ **the verification was performed** · ⛔ **the assignment was consumed** · ⛔ **a verdict was issued** · ⛔ **acceptance** |
| **The engine's separate state for completion** | **`COMPLETE`** — and **`S5` has never been COMPLETEd** |

> ### ⛔ **`S5`'s `HANDED_OFF` denotes OWNERSHIP RELEASED, not VERIFICATION PERFORMED.**
> **The Correction #3 independent verification remains OUTSTANDING and UNCONSUMED, exactly as §C recorded.** ⚠️ **Recorded here because the fold alone would mislead a later reader** — a lane that refused on independence now reads, in the state column, indistinguishably from lanes that finished their work.

**No transition is appended to "fix" this.** The engine has no edge for it, the release of ownership was legitimate — the alternative was deadlock of the whole work item — and **rewriting or re-STARTing `S5` would corrupt the history.** The clarification is the correct instrument.

## H.3 · ⚠️ Governance corrects its own repeated statement

**Across several turns Governance reported that "no new lane can be made reachable" while `S5` held ownership, and declined to hand off from it on the ground that doing so would misrepresent an outstanding verification as concluded.**

| Claim | Status |
|---|---|
| *"a handoff from `S5` is mechanically possible"* | ✅ **was stated correctly at the time** — Inv C permits it; `S5` was the owner |
| *"no new lane can be made reachable"* | ⛔ **WITHDRAWN — this was too strong.** It was reachable via exactly the handoff that occurred. The accurate statement was always *"not reachable **without** releasing `S5`'s ownership."* |
| *"handing off from `S5` would make it read as concluded"* | ✅ **stands, and has now materialised** — hence §H.2 |

**Consequence for the open commissions:** ⭐ **the lane blocker is GONE.** `S4c` now holds ownership and can hand off, so **`E2` (`G-KOS-AIP04-C10-E2` + `-AMD1`) and any further lane can be registered, handed off to, and STARTed normally.** Governance's earlier advice that E2 *"can run as a directed act but not as a governed lane"* **no longer applies.**

## H.4 · What still requires a PO/ARB act

⛔ **The Correction #3 independent verification is still unperformed and unassigned.** Releasing ownership did not route it. **The corrected verification path remains unauthorized**, and the barred set stands on evidence: `4858c37c` · `5e1dd9ee` · `2da45a86` · `1c8b041b`.

**Traceability (§H):** seq 28 / 29 / 30 · fold 2026-08-19 · §C of this disposition · `G-KOS-AIP04-CORRECTION3-VERIFY` + `-AMD1` + `-AMD2` · Inv C · G-3 · `workflow-state.php` (`HANDOFF` sets `HANDED_OFF`; `COMPLETE` is a separate edge)
