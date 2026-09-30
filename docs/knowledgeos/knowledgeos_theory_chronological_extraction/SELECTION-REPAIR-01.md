# `SELECTION-REPAIR-01` — corpus-selection state repaired *(2026-09-23)*

| | |
|---|---|
| **Cause** | ⛔ **the research universe was being set by a filesystem glob, not by the canonical manifest** |
| **Fix** | ⭐ **one architectural rule — no new control, no new series** |
| **Read during this repair** | ⛔ **nothing** |

> ## ⭐⭐ **CANONICAL MANIFEST = SELECTION AUTHORITY. FILESYSTEM = PHYSICAL LOCATOR ONLY.**

---

## 1 · Nothing deleted — findings frozen as issued

| Artifact | Status |
|---|---|
| `FINDINGS-F2847.md` | ⭐ **preserved as issued** |
| the 5 earlier out-of-order findings | ⭐ **preserved as issued** |
| **Candidate Theory v0.9** | ⛔ **unchanged** |
| governance correction state | ⛔ **unchanged** |

⭐ **`F2847` is classified `VALID EVIDENCE — OUT-OF-ORDER DISCOVERY`.** ⛔ **Not invalid research.** *Its identity was verified, it was admitted, a receipt was issued, and the whole file was read. **The defect was in HOW IT WAS CHOSEN, not in what it says.***

## 2 · The universe — `evidence/SELECTION-UNIVERSE.jsonl`, **3,081 entries**

**Every canonical entry now carries:** `file_id · canonical_position · canonical_path · sha256 · read · admission · selection_provenance · track`.

| Admission | |
|---|---|
| **ADMISSIBLE** | **3,059** |
| ⛔ **GOVERNANCE_HELD** | **4** — `F0032` `F0035` `F0036` `F0040` |
| ⚠️ **UNUSABLE** | **18** — defective manifest rows (`B-14`) |

## 3 · Selection-provenance audit — ⭐ the question is not *"were they useful?"*

| Provenance | n | Verdict |
|---|---|---|
| `IN_CANONICAL_ORDER` | **30** | ✅ `F0001`–`F0030`, selection justified |
| ⛔ `READ_UNDER_WRONG_RESEARCH_ID` | **10** | the `F0031` incident — **content read, identity wrong** |
| ⚠️ `OUT_OF_ORDER_DISCOVERY` | **6** | `F0041` `F0043` `F0044` `F0046` `F2839` `F2847` |
| `NOT_READ` | **3,035** | |

### ⚠️ The six, judged against the rules that existed at the time

⛔ **Their selection was NOT justified — and the reason is the same for all six.** They were the residue of `glob("docs/knowledgeos/*.md")`: ⭐ **the top-level directory, which is not a research category.**

⭐ **The Phase-1 rule was explicit and I did not apply it:** *"After processing $F_i$, update the reconstruction state before reading $F_{i+1}$."*

⚠️ **I had a defence available and it does not hold.** §3C.5 lets Phase-2 select the *highest-value* candidate, and §3C.6 says *"when the next useful act is a targeted search rather than sequential reading, do that instead."* ⛔ **But §3C.5 also requires the choice to be a research-state decision RECORDED IN THE CHECKPOINT.** **I recorded none — because I made none. I did not select these files; a glob did.**

> ⭐ **Keep their findings. Classify their selection provenance. Those are different acts.**

## 4 · ⛔ `F2847` quarantined into a separate track

```
TERMINAL-THEORY-EVIDENCE        CHRONOLOGICAL RECONSTRUCTION
   F2847  ✅ read                  F0001…F0046 (as processed)
   F2841  ⛔ NOT READ                      ↓
   F2843  ⛔ NOT READ              one file at a time
   F2844  ⛔ NOT READ                      ↓
   F2845  ⛔ NOT READ              Candidate Theory
```

⛔ **The eleven kernel laws do NOT enter the Candidate Theory** because I happened to find them. ⭐ **They are a later-state reference, held apart.**

⛔ **And `F2841`/`F2843`/`F2844`/`F2845` are NOT read now.** ⭐ *I have already encountered the answer once by accident; reading the four destination artifacts would destroy what remains of the test.*

### ⭐ Two experiments, now explicit

| | Question |
|---|---|
| **A · Historical reconstruction** | ⭐ **can KnowledgeOS be recovered from the corpus WITHOUT looking ahead?** |
| **B · Terminal-state recovery** | what mature theory did the corpus eventually produce? |

⭐ **`F2847` belongs to B. The file-by-file cycle is A. Comparing them later is the point** — and it only stays a comparison if A is not contaminated by B.

⚠️ **Honest limit: A is already partly contaminated.** I have read `F2847` and cannot unread it. ⛔ **What I can do is refuse to let its content into A's artifacts, and say so where it matters.**

## 5 · The next-file rule

> ### **Select the FIRST ADMISSIBLE UNREAD ENTRY in the canonical manifest.**

```
F0032 → HELD → skip
F0035 → HELD → skip
F0036 → HELD → skip
F0040 → HELD → skip
F0047 → NEXT ADMISSIBLE CANONICAL-MANIFEST FILE
```

⛔ **`F0047` is NOT "chronologically next."** ⭐ **It is the next admissible canonical-manifest file.** *The historical chronology is reconstructed from CONTENT; the manifest supplies traversal order, not dates.*

⚠️ **Worth noting before it misleads:** `F0047` is dated **2026-08-19** and lives under `brainstorming/`, while `F0046` is 2026-08-03. ⛔ **Manifest order is not date order.**

## 6 · ⛔ No new governance layer

**No new `H-` or `RC-` series · no new phase · no new gate · no new control document.** ⭐ **The correction is one architectural sentence**, recorded at the top of this file.

---

*`SELECTION-REPAIR-01` · universe 3,081 · 6 out-of-order reads audited · `F2847` quarantined to track B · next = `F0047` · ⛔ nothing read, nothing deleted, no control created.*
