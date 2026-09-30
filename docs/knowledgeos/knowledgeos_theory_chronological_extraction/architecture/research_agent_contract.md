# Research Agent Contract — KnowledgeOS Theory Reconstruction & Construction

| | |
|---|---|
| **Status** | ⚠️ **PROPOSED — authority: generated.** Not in force until a human adopts it |
| **Level** | **L5** of the authority hierarchy (`research-control-architecture.md` §2). **It holds no rule of its own.** It points to where each rule lives, so that the binding rules are in the agent's working context |
| **Why it exists** | the file-ID rule was broken because it lived only in P1P §4 and none of the documents the agent actually worked from mentioned it (`research-control-architecture.md` §0A, R-1). **This contract is the pointer that was missing** |
| **Placement** | proposed here; moving it to `prompts/` (human-curated) is decision RC-H-07 |

---

## Authority hierarchy (a lower level never overrides a higher one)

```
L0 human decision  >  L1 ARCH v1.1 (+ control architecture if adopted)  >  L2 P1P / P2P
>  L3 control registry  >  L4 governance/gates.yaml + activation  >  L5 THIS CONTRACT
>  L6 session instructions  >  L7 research output
```

- If a session instruction conflicts with a higher level, the session instruction is invalid. **STOP** (`PROTOCOL_CONFLICT`); do not choose between them.
- If two higher levels conflict with each other, **STOP** (`PROTOCOL_CONFLICT`) and report. Known case: Q18 vs Q61.

## Before each unit: load these, not a memory of them

> ⚠️ **Loading is an agent action, so it is instruction, not enforcement.** Once Increment 1b exists, file identity is resolved **by the source-admission step**, not by you: you request a canonical ID and receive the verified path. Until then this list is the only protection, and a machine check does not yet back it.

| Load | Why |
|---|---|
| `KNOWLEDGEOS-RESEARCH-STATE.md` | position (RA-11). ⚠️ **Its next target must resolve to canonical IDs** (RCI-006). If it does not, STOP (`IDENTITY_CONFLICT`) |
| `docs/knowledgeos/list_of_files_to_read.log` and **P1P §4** | ⭐ **the only source of file identity** |
| the applicable protocol section (P1P for Phase-1 work, P2P for Phase-2 work) | a unit that reads files is **Phase-1 work**, even when it is run from a Phase-2 loop |
| `governance/README.md` §§3–5 and P2P §0.3 | what gate verdicts mean; what is forbidden on a BLOCK |

## You MUST

1. **Treat the canonical list as the only authority for file identity.** For every file you read, take its `file_id` **from the list**, and copy `file_id`, `timestamp` and `path` verbatim into the registry (P1P §4, §5).
2. **Never create, renumber, reassign or regenerate a `file_id`.** You may read files **out of list order**: targeted search and expansion are legitimate (P2P §3C.6, Q37). But a file keeps **its own** canonical ID, whenever it is read.
3. **Express position and coverage in canonical IDs.** Write "next: F0041–F0045", never "the next five unread files" (RCI-006).
4. **Never modify a corpus file** (RA-1).
5. **Never silently delete, overwrite or semantically replace** a recorded derivation, interpretation, relationship, theory object or theory version. Record a change as a **new** record with an explicit relation: qualification · contradiction · refinement · supersession · split · merge · replacement proposal · withdrawal · unresolved. Write the old text verbatim to `THEORY-EVOLUTION` (Q24) (RCI-015).
6. **Read the whole file, and look for banners, withdrawals and supersessions** that change how its body should be read. Record them as intra-file revisions (RCI-010; your own C-0009).
7. **Give every theory-bearing contribution a disposition:** represented, excluded with a reason, or unresolved. Never let one vanish (Q45, Q46).
8. **Preserve epistemic status and qualifications** exactly: *possible* stays possible, *candidate* stays candidate, *proposed* stays proposed (Q3, Q50).
9. **Mark every Phase-2 statement's `asserted_by`.** Your proposals are `MACHINE_PROPOSAL` (I-14).
10. **Keep each commit to one unit type**, and name the files read in the commit message (RCA §0A R-5).
11. **Run the governance preflight where P2P §0.3 requires it, and record the status**, including `NO_ACTIVE_GOVERNANCE_CONTROLS`.

## You MUST NOT

- activate, deactivate, edit or **repair** anything in `governance/` or `architecture/`, or the hook. If it is broken: **STOP** (`GOVERNANCE_INOPERATIVE`) (RCI-011).
- accept, adopt, freeze or canonicalize your own results (RCI-012, Q1).
- repair a gate that failed you, or keep reading after a `BLOCK` (P2P §0.3).
- fix an identity conflict by editing the registry. An identity correction is a **scheduled correction unit** (RA-5), decided by the human.
- treat a gate PASS as evidence that the research is valid.

## STOP

On any stop code (`IDENTITY_CONFLICT` · `MISSING_CANONICAL_SOURCE` · `HASH_MISMATCH` · `CORPUS_MODIFIED` · `GOVERNANCE_INOPERATIVE` · `PROTOCOL_CONFLICT` · `FROZEN_ARTIFACT_MODIFICATION` · `UNRESOLVED_PROVENANCE`) do **exactly two things**:
1. append a stop record (`research-control-architecture.md` §7);
2. report.

Do not work around it to keep momentum. **You do not resume yourself.** The authority named in the stop record does.

## What you may do freely

Propose interpretations, constructions and theories. Search beyond the window. Disagree with the corpus, with an earlier version of yourself, or with a reviewer, **on the record**. A proposal is not promoted because you generated it.

---

*Traceability:* consumes `ARCH` v1.1 (RA-1, RA-2, RA-5, RA-11, RA-12), P1P §4/§5/§5A/§9A, P2P §0.3/§3C.6/Q3/Q24/Q37/Q45/Q46/Q50/I-14, and `governance/README.md`. It adds no rule. Motivated by the root cause in `research-control-architecture.md` §0A.
