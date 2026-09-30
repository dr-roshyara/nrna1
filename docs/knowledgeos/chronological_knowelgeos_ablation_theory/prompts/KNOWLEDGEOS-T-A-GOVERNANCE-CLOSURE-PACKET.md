# T-A GOVERNANCE CLOSURE PACKET — decision layers + independent-reader handoff (preparation only)

| | |
|---|---|
| **Kind** | governance input. ⚠ authority: generated. **No RAG, no classification, no decision, no commissioning** |
| **Commission** | human, 2026-09-26: the pasted "Governance Closure and Controlled T-A Launch" prompt |
| **Companion** | `prompts/KNOWLEDGEOS-T-A-DRAFT-RRC-02-AND-L0-DEC-31.md` (F-LOG-0036, `2ff3d996c`). That draft already holds the ready-to-adopt RRC-02 and L0-DEC-31 texts (prompt §C, §D). This packet adds only what the draft lacked |
| **State (read-only, HEAD `2f0e2c2f2`)** | RRC-02: **absent**. L0-DEC-31: **absent**. Governance lane last changed `40aa72590` (2026-09-23) → **RELEASE PENDING GOVERNANCE DECISION · L0 DECISION PENDING** |

---

## 1. Decision packet in five layers (prompt §B)

| Item | 1 Observed evidence | 2 Method and its limits | 3 Unresolved | 4 Proposed disposition (proposal only) | 5 Approval field |
|---|---|---|---|---|---|
| **Q1** methodology unchanged | no commit to the governance-lane `prompts/` since `628d02169`; r3 = `be16deb7…` | git log and hashes; **limit:** untracked files are invisible to git log | the 4 untracked files (T3) | "unchanged in committed form; untracked files pending T3" | governance answer |
| **Q2** corpus/state identifiable | manifest `--verify` STALE (F2800 only); T-A objects pinned per object | `build-manifest --verify`, in-memory diff, git object hashes; **limit:** a stale manifest means the global corpus state is **not** certified | global manifest validity | "T-A scope identifiable by per-object pins; global manifest **not** valid" | governance answer |
| **Q3** defects classified, no open R1 | RC-H-04 (R2, unchanged); F2800; 4 files | admit audit; hashes; **limit:** classification is a governance act | F2800 class; 4-file class | per T1/T3 | governance answer |
| **Q4** controls detect what they claim | self-test 55/55; regressions 66/66 and 12/12; pins 5/5; control hashes identical; preflight CLEAR | the existing batteries; **limit:** run by the requesting session (F-LOG-0033); KOS-G-020 not activated and FAIL (includes T-0056) | re-run or accept | "re-run by the governance session" | `[RE-RUN / ACCEPTED]` |
| **Q5** L0 accepted state | pins match; HD-1 given | git; **limit:** none | L0-DEC-31 | — | L0 |
| **T1** F2800 | untracked, `e140e172…` → `a3df1871…`; outside the scope; 0 receipts; manifest not rebuilt | byte hashes, receipts, references; **limit:** the changed bytes are unrecoverable (untracked) | the class | R2, contained per the F-LOG-0035 text; **the global manifest remains stale** | governance class + containment acceptance |
| **T2** T-0056 | sources ['F0018']; 0 relation rows; r3 does not name it; batch 3 not retroactively certified (L0-DEC-20) | structural fields only; **limit:** the statement text was not read | accept/reject Option A | Option A with the five-point caveat (F-LOG-0035) | governance accept/reject |
| **T3** 4 untracked files | untracked; created 2026-09-24; unreferenced by the controls; hashes in the draft | metadata; **limit: unchanged hashes do not prove the absence of a proposal**; content not read by the F-lane | **bounded content review** | none until reviewed. Any proposed change → a separate future proposal, **not** incorporated into T-A | per-file class + evidence anchor + human confirmation |

**T3 bounded content review: template for the governance session** (one row per file):

| File | Category (operational commentary / review / proposed methodology change / other) | Evidence anchor (heading or quote) | Conflicts with r3 / release criteria / committed methodology? | If a proposal: future-proposal id | Human confirmation |
|---|---|---|---|---|---|
| `evaluation_researchmethod.md` | | | | | |
| `review_of_phase1.md` | | | | | |
| `review_of_phase_2.md` | | | | | |
| `review_of_v1.2 architecture.md` | | | | | |

---

## 2. Independent-reader handoff (prompt §E): prepared, not commissioned

### 2.1 ⚠ Blindness finding: must be settled before commissioning

The senior reviewer (the other model family) offered to act as the independent reader. That reviewer has been shown Claude's reports, which state the predictions (e.g. the closure pass: *"F-A6 predicted AMBIGUOUS or COUNTEREXAMPLE"*) and Claude's analyses.

- It **is independent in lineage**.
- It is **not blind**.

Using it would breach the prompt's §E (*"Do not disclose … predictions … attack reports … expected outcome"*) and r3 §4 (a second reader classifies without access to §3.2).

**Requirement:** the independent reader is a **fresh context** (a new conversation or a new person) that has seen **none** of this project's reviews, reports or chat history. The reader declares this in its `reader` field.

### 2.2 What the human hands over (and nothing else)

| # | Item | Identity |
|---|---|---|
| 1 | `analysis/t_a/reader_packet/PREREGISTRATION-r3-BLIND.md` | sha256 `104defaad9092f87638b798d814b4d87551b0ddbe10387b006446ce38db59eb0` |
| 2 | `analysis/t_a/reader_packet/aggregate_blind.py` | sha256 `75a5f0d2c230020ad5d9c6affc81d2d1d36fe1019f7cc33ff1883ca6565482da` |
| 3 | `analysis/t_a/reader_packet/README.md` | sha256 `475c42b86bf3c66f87c6e03221fbaddb5b7e4e378c9ba48a25af42ee0b9bee37` |
| 4 | F0018: the bytes of `d61bf5e84:docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md` | sha256 `b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc` |
| 5–11 | ES-006: the bytes of the 7 objects (commit:path) | sha256 as in the draft ("Frozen research inputs") |

**Export without the working tree.** For each object: `git show <commit>:<path> > <file>`; then `sha256sum <file>` must equal the listed hash. Hand over the exported files. The reader needs no repository access.

**Not handed over:**
- any other lane file, report, log or ledger;
- the attack document, `results.json`, `verifier_results.json`;
- the full r3;
- this packet;
- any chat.

### 2.3 Reader record

| Field | Value |
|---|---|
| Reader identity | `[human name / model family + model id]` |
| Independence basis | commissioned by the human; different lineage; **fresh context, no prior exposure** (declared) |
| Scope | exactly items 1–11 |
| Output | `records.jsonl` (the §4 schema), returned to the human |
| Output location (after sealing) | `analysis/t_a/ledgers/INDEPENDENT/records.jsonl` |

### 2.4 Sealing protocol (both ledgers, before comparison)

1. The INDEPENDENT reader returns `records.jsonl`. The human records its sha256 in a governance-lane or F-lane log entry **before** the SELF ledger is revealed to anyone.
2. The SELF reader (Claude) writes `analysis/t_a/ledgers/SELF/records.jsonl` and records its sha256 in `F-GOVERNANCE-LOG.md` **before** receiving the INDEPENDENT ledger.
3. Only after both seals exist:
   - both ledgers are committed;
   - `aggregate.py --scope UNIVERSAL --released M-1,M-4` runs on each;
   - a per-passage and per-question disagreement table is produced;
   - disagreements are **recorded, not resolved in the author's favour**.

---

## 3. Status (prompt §I)

1. **RRC-02:** pending; the draft is ready.
2. **L0-DEC-31:** pending; the draft is ready. AUTHORIZE / DO NOT AUTHORIZE is not entered.
3. **Unresolved:**
   - RRC_RESULT and the governance answers to Q1–Q5;
   - re-run or accept of the measurements;
   - the F2800 class;
   - acceptance of Option A;
   - the T3 content review, class and confirmation for each file;
   - L0 identity, date, reference and the authorization;
   - the independent reader, **including the blindness requirement in §2.1**.
4. **Independent reader:** not commissioned; handoff prepared.
5. **Hash/pin integrity:** all match (F-LOG-0036 verification; unchanged since).
6. **T-A executed:** **no**.
7. **Ledgers, aggregate, outcome:** none. None anticipated here.
8. **Next action:**
   - governance session: RRC-02, including the T3 content review;
   - human: record L0-DEC-31 and commission a **fresh-context** independent reader;
   - then Claude runs the prompt's §F integrity check, then T-A.
