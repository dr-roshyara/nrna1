# Commissions: the chain of two theory turns after the S0378–S0402 consolidation (PREPARED — NOT ISSUED)

| Field | Value |
|---|---|
| Status | **PREPARED — NOT ISSUED.** Prepared on the Principal's approval of 2026-11-03 ("approve": "After the S0378–S0402 consolidation settles, run a chain of critical-path theory turns under Rulebook v1.4 §12b (L160): first A and the output forms (T9-22), then the derivation chain with the unit of derivation fixed first, each as worded in REVIEW-STRATEGY-AND-IMPLEMENTABILITY-20261103.md R2 and R1. Reading pauses for the chain."). Issued only when the Principal dispatches it and tells the Governance Engineer |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-11-03, from repository records only |
| Built from | `REVIEW-STRATEGY-AND-IMPLEMENTABILITY-20261103.md` R1, R2 (advisory; DeepSeek API in the Claude Code harness, as reported by the Principal); Ch. 9 `TODO.md` T9-03, T9-04, T9-22; §9.19.4 (A = (B, G)), §9.19.5, §9.19.10 L403; §9.20.5 L107–111, §9.20.9; §9.11 (the 31 laws); `BOOK-OUTLINE.md` L44; `RESEARCH-PURPOSE.md` deliverables 1, 2 (L28–35); the decisions U1–U6, T10-01 (b), T9-17 (b), T9-19, T9-20, T10-21, Q-C, the T9-02 deliverable ruling (2026-11-02) |

**The chain (Rulebook v1.4 §12b, L160):** consecutive turns, one deliverable each; turn 2 starts only after turn 1 is settled. Reading stays at S0402 for the whole chain and resumes afterwards under the standing instruction, unless the Principal decides otherwise (the reading-strategy paper).

**Common method (both turns, as for T9-02):**
- research question and disproof conditions committed **before** any model or draft;
- at most **8 sources** by logged search, `topic-full`, or branch consult with predictions recorded first; allowed list only; **the reading position does not move**;
- labels Src · Ded · Int · Hyp · Ext · EA; every material claim cites its passage; any model states its premises, bound and limits and supports form, not content;
- time in Reading · Drafting · Waiting · Settlement · Rework;
- **three reviewers, blind-first**: two `kos-lane-reviewer` on different models plus one `kos-theory-reviewer`; step-1 answers saved with sha256 before any step-2 brief; reports saved after all return; copy lists and "do not open" lists in every brief (`00-access/`, `02-FILES.jsonl`, other review folders), **no git history**;
- settlement: every finding dispositioned, R3 on every accepted correction, TODO and outline changes stated **as consequences, not decisions**;
- **not in either turn:** T9-21; the T10-15 turn; any Ch. 10 change; architecture or implementation; `RESEARCH-PURPOSE.md` not edited; no open fork (F13, F37, F6, T9-14, U3's granularity beyond what turn 1 derives) treated as decided. A question that needs the Principal goes to the Principal with options and a copy-ready line, and stops the turn (stop (b)).

---

## Turn 1 — the interface assumption A and the output forms (T9-22)

### Objective
Fix what the theory can fix about the kernel's interface, **by derivation, not by choice**: the mechanism granularity and batching (G of A = (B, G)), and the output forms of Q1's act list, Q2 and Q3.

### Where it stands (anchors)
- §9.19.4 (L145 ff.): A = (B, G); B fixed; **G and batching unfixed** (U3 (b): kept unfixed, results scoped to (A, Req)).
- §9.19.6, §9.19.8: "a minimal kernel" is well posed only relative to (B, G, Req's cut); KnowledgeOS's minimal kernel **not stated**.
- §9.20.5 L107–111 and TODO **T9-22**: 𝒮's minimality turns on three output forms (Q1's "acts the answer rests on", Q2's scope and output, Q3's "found (with the grant)").
- TODO **T9-03** (two notions of minimality; the pool) and **T9-04** (uniqueness) wait on a fixed pool.
- U6 (a): O1 is one requirement per act kind (the per-kind cut).

### Research question
**Does Req's kind structure (U6's per-kind cut) and the decided theory determine the mechanism granularity, batching, and the three output forms; and where it does not, what exactly is left to decide?**

### Deliverable
For G, batching and each output form: **derived** (with the derivation), **narrowed** (to named options), or **a decision** (one question for the Principal). Then: §9.20.5's minimality restated for the derived or decided forms; T9-22 closed or reduced to a decision; the consequences for T9-03 and T9-04 stated, not settled.

### Limits
- **Fixing G by choice breaches the Req/A approval's condition 4** (no selection criterion adopted); only a derivation, or a question to the Principal, is allowed.
- No primitive fixed; no selection criterion among minimal candidates.

---

## Turn 2 — the derivation chain, with the unit of derivation fixed first

*(Starts only after turn 1 is settled. Its first step may change if turn 1 changes the interface.)*

### Objective
Begin deliverable 2 (`RESEARCH-PURPOSE.md`): the laws derived from the primitives as one chain, missing since the consolidation S0036–S0065 (`BOOK-OUTLINE.md` L44; still missing after the Req/A turn, §9.19.10 L403).

### Where it stands (anchors)
- **"Primitive" is used for three objects:** a mechanism (§9.19.4 L177: "'Primitive' here means a mechanism"), a concept (`RESEARCH-PURPOSE.md` L28–35; `BOOK-OUTLINE.md` §1), and a field (§9.19.5, L203–226, which also says "None of this is membership of a minimal kernel").
- §9.11: the 31 laws classified against Req (T + O; expressible with or without replies).
- §9.20: the record is the state; item attributes are derived (the T9-02 deliverable ruling, 2026-11-02).
- Deliverable 1: 2 of 12 candidates marked ✓; no independence model (`TARGET-TOC.md` Part II).

### Research question
**From which object are the laws derived — primitive concepts, entry kinds and their fields, mechanisms, or Req — and does one law follow end to end from it?**

### Deliverable
1. **The unit of derivation**, chosen by stated criteria and argued against the other three; the homonym "primitive" split into named senses, each with its test.
2. **One law derived end to end** from that unit, with premises and a falsifier.
3. **Every law of §9.11 marked:** *forced by Req* · *compatible with Req* · *independent of it*; each "independent" law classified as an application rule or a world assumption (the K/L split of §9.15 is the precedent).
4. What this changes for deliverable 1 (the primitives) and for primitive minimality (T9-03), as consequences.

### Limits
- No new primitive fixed; the primitive set not finalised; primitive minimality not claimed from mechanism minimality (T9-03 kept apart).
- If the laws do **not** derive from any candidate unit, that is a result, reported as such.

---

## For the Principal: the dispatch (at the gate)

- **When:** after the S0378–S0402 consolidation settles. Send turn 1; turn 2 is sent after turn 1 settles (the Governance Engineer will remind).
- **Reviewers:** three, as above (recommended, as for Req/A and T9-02). **Option:** one of the three could run on the DeepSeek API, for a different model; say so in the dispatch if wanted.
- **To dispatch:** send "Turn 1" with the common method to the Professor and tell the Governance Engineer; it is not reported as issued until then.
