# GIA-DECISION-PACKAGE-01 — L0 Human Decision Package

| | |
|---|---|
| **Kind** | governance preparation: decisions for the human authority (L0). ⛔ **Not an implementation** |
| **Prepared by** | governance / control-plane session |
| **Date · HEAD** | 2026-09-23 · `15a05f297` |
| **Evidence baseline** | `governance/GOVERNANCE-INTEGRITY-AUDIT-01.md`: **CONTROL-PLANE VERDICT FAIL · TRANSITION OUTCOME B — READY WITH CORRECTIONS** (54 valid mechanical cases, 55 runs, isolated `git archive` copies). Its findings are treated as established and are **not reopened here** |
| **Status** | ⛔ **IMPLEMENTATION NOT AUTHORIZED UNTIL THE REQUIRED L0 DECISIONS ARE MADE.** |

---

## 1. Executive state

- **Research:** v0.9 as issued. F0031–F0040 are still divergent and unrepaired. F0041+ has not started.
- **Governance mechanism:** 25 gates, 5 activated, and **none changed since the audit**.
  - The audit showed that the mechanism can report CLEAR without having checked anything.
  - It also showed that the mechanism cannot detect the F0031–F0040 incident class.
- **The research session has implemented evidence-binding components** (`evidence/*`, `governance/PROPOSED-GATES-RCI.yaml`). The allocation question is recorded as **B-15**.
- **Nothing can move forward without L0.** The next step (extended 1a) needs GIA-2 and GIA-3 at minimum.

## 2. Governance Integrity Audit verdict

**FAIL**, with transition outcome **B — READY WITH CORRECTIONS**.

These findings are established by mechanical evidence (audit case IDs in brackets):

| Finding | Cases |
|---|---|
| false CLEAR when activated gates are re-statused | D08, D09, D16, D17 |
| false CLEAR when an activated gate is re-tiered | D10 |
| false CLEAR on missing or empty inputs | B04, B09 (suite level); B01, B03, B06–B08 (gate level) |
| false CLEAR while the instrument's own self-test fails (30/31), or with fixtures deleted | A0, D06 |
| duplicate gate IDs and schema defects accepted | D04, D07 |
| declared coverage exceeds implementation (KOS-G-001 nested JSONL; KOS-G-003 `.md`) | G01, B17 |
| correct canonical IDs produce false FAIL | G02 |
| historical F0031–F0040 incident not detected | C1, A0 |
| RCI-015 cannot tell destructive change from valid evolution | E1–E4 |
| epistemic preservation not mechanically testable | F1, F2 |
| namespace collisions exist | audit §11, §2A |
| the research session crossed the governance allocation boundary | audit §2, §2A (→ B-15) |

## 3. Evidence basis (frozen reference hashes, sha256 first 16, at `15a05f297`)

| Artifact | Hash |
|---|---|
| `governance/gate-schema.yaml` | `4ebde2c923ede3a6` |
| `governance/gates.yaml` | `7a01bac1a45ef634` |
| `governance/governance-state.yaml` | `e20b4d67ab71caf8` |
| `governance/gate-runner.py` | `fcafa9cd27976ff2` |
| `.claude/hooks/governance-preflight.sh` | `4b53c0b30e0851a8` |
| `governance/fixtures/` (digest over sorted `sha256sum` lines) | `e880a4b3032ec14c` |
| `governance/PROPOSED-GATES-RCI.yaml` | `9a914c9d64fc7808` |
| `evidence/build-manifest.py` · `admit.py` · `CORPUS-MANIFEST.jsonl` · `MANIFEST-HASH.txt` · `README.md` | `5e2c524961ae513d` · `23ccde6c2ec510c4` · `54977c6e1213028d` · `98e70d1187b93189` · `5c820095599186c7` |
| canonical list `docs/knowledgeos/list_of_files_to_read.log` | `e0cb8f010265f2c8` (commit `7698c99b4`) |

The first six hashes match the audit's evaluated state: the control plane is unchanged.

## 4. GIA-1 … GIA-10

| Decision | Question | Evidence | Proposed default | L0 required |
|---|---|---|---|---|
| **GIA-1** | Accept that current `CLEAR` results are **not** evidence of governance conformance? | audit §6, §9 (A0: CLEAR at self-test 30/31; D09: CLEAR with nothing evaluated) | **Accept.** Every past and present CLEAR is read as "no activated check failed, under an instrument known to pass without checking". The research state should record this (a research-unit act) | **yes** |
| **GIA-2** | Approve **extended Increment 1a** = IC-1…IC-8 (§7), replacing the 1a in `docs/plans/20260923-0212-…-plan.md`? | audit §14, §15 (the plan's 1a covers only IC-1, part of IC-4, and IC-7) | **Approve** | **yes** |
| **GIA-3** | Who is authorized to modify `governance/gate-runner.py` and `governance/gates.yaml`? | both were **created by the research session** (`04376db9b`), which is also governed by them (`governance/README.md` §5, "the separation defect, stated rather than papered over") | **The governance session implements; the human authority accepts; the research session is excluded** (RCI-011). **The research session must not be assumed to have this authority** | **yes** |
| **GIA-4** | Must an activation record **pin the exact gate-definition hash**, so that any change to status/tier/class/check under an activated ID makes governance `GOVERNANCE_INOPERATIVE` until the human re-activates? | D08–D10 (redefinition takes effect silently); D16/D17 | **Yes** (IC-3). Cost: every gate edit requires a human re-activation act | **yes** |
| **GIA-5** | KOS-G-003's known set: the **canonical manifest** (it becomes a corpus-correspondence check), or keep it **registry-internal** and add a separate correspondence control? | G02 (false FAIL on the correct canonical F2837); C1/A0 (incident undetected); audit §8 | ⛔ **No default is chosen here** (instructed). **Option A:** manifest as known set, which fixes G02 and adds C1 detection but changes the gate's scope class. **Option B:** registry-internal, with G02 fixed by a separate rule and a new single-purpose correspondence gate. Either needs a manifest source (links to GIA-9) | **yes** |
| **GIA-6** | Adopt **NR-1** namespace qualification (governance-minted IDs carry a governance-only prefix; foreign short IDs are qualified by source; gate IDs exist only in `gates.yaml`)? | audit §11, §2A: `KOS-G-001…081` in two governance sources; **KOS-G-070…073** in `PROPOSED-GATES-RCI.yaml` vs this session's catalog; governance-introduced `G-4`, `H-1`, `D-n` | **Adopt the rule; implement later** (a lint). Does not block 1a | **yes** |
| **GIA-7** | Confirm that **no REVIEW or HUMAN gate is activated** until a mechanism records completion of the review or human decision? | D11, D14, D15: an activated REVIEW/HUMAN gate blocks **permanently** | **Confirm** (IC-8) | **yes** |
| **GIA-8** | Confirm the coordination rule: **the research session must not commit governance or governance-architecture artifacts**? | `6cffbea5e` (5 `architecture/*` files), `15a05f297` (`GOVERNANCE-INTEGRITY-AUDIT-01.md` draft, `PROPOSED-GATES-RCI.yaml` modified), `163a0f996` (`PROPOSED-GATES-RCI.yaml` created) | **Confirm.** The research session may *propose* governance content in its own tree and in its commit messages only | **yes** |
| **GIA-9** | Treatment of the research-produced evidence-binding implementation: **ADOPT / ADAPT / REJECT**, per artifact: `evidence/build-manifest.py`, `evidence/CORPUS-MANIFEST.jsonl`, `evidence/MANIFEST-HASH.txt`, `evidence/admit.py`, `evidence/README.md`, `governance/PROPOSED-GATES-RCI.yaml` | `163a0f996` / `15a05f297` (self-marked *"RESEARCH-PRODUCED PROPOSAL / IMPLEMENTATION EVIDENCE — NOT AUTHORITATIVE GOVERNANCE"*); **not audited** (audit §2A) | **Defer the verdict to an independent 1b review** (§8), **then** decide. Until then all six are **NON-AUTHORITATIVE**: existing is not adopted, and nothing may depend on them | **yes** (after the review) |
| **GIA-10** | The **18 unresolved canonical paths** (B-14): resolve them from authoritative evidence, or represent them as `MISSING_CANONICAL_SOURCE`? | independently confirmed by this session: 3,081 rows, 0 unparsed, **18 do not resolve on disk** (e.g. F1138 `…/brainstorming/Thinking`, F2807 `…/architecture/README`) | **Represent all 18 as `MISSING_CANONICAL_SOURCE` now.** Resolve any of them only from authoritative evidence (e.g. git history of the list's source), **never by guessing** | **yes** |

## 5. B-15: separation issue (recorded, not classified)

| | Fact |
|---|---|
| **What happened** | the research session implemented canonical-manifest generation, identity admission, a receipt format and four proposed gates (`163a0f996`, 02:20), then stopped "on instruction" and recorded B-15 itself (`15a05f297`, 02:24) |
| **Allocation that existed** | `docs/plans/20260923-0212-kos-evidence-binding-increment-1-plan.md` D1: *"Implemented by the governance session, never the research session"*. RCA §8 roles give the research agent no write access to `governance/`. ⚠️ **Both were PROPOSED / AWAITING APPROVAL at the time.** No L0-approved allocation existed. Whether an unapproved plan is an "authoritative allocation" is itself part of the question |
| **What the research session reports as its trigger** | an in-session instruction to "implement steps 3–6". It records this as a fact and **explicitly not as justification** (`15a05f297`) |
| **Governance artifacts it committed** | `governance/PROPOSED-GATES-RCI.yaml` (created, then modified) · `governance/GOVERNANCE-INTEGRITY-AUDIT-01.md` (earlier draft, authored by the governance session) · `architecture/*` (5 files, authored by the governance session, in `6cffbea5e`). Historically it also created the whole `governance/` mechanism (`04376db9b`) |
| **What was not changed** (verified by hash, §3) | `gates.yaml`, `governance-state.yaml`, `gate-runner.py`, the door, fixtures, activation. KOS-G-070…073 were **not** added to `gates.yaml` and **not** activated |
| **For L0** | (a) is this a separation breach under RCI-011, given that RCI-011 itself is PROPOSED? (b) does an in-session human instruction override an unapproved governance allocation? (c) what is the consequence for GIA-9? ⛔ **No classification** (intentional/benign/acceptable/…) is made here |

## 6. B-12 separation

| Problem | Owner | Here? |
|---|---|---|
| **Corpus-internal `G-4` ambiguity** (the corpus using `G-4` for different things; whether F0014's *"H-3 resolved — closed by G-4"* is uniquely referential) | **B-12**, a separate forensic research audit | ⛔ **not resolved here** |
| **Governance-created collisions** (`KOS-G-nnn` across governance sources; governance documents minting `G-4`, `H-1`, `D-n`) | **GIA-6 / NR-1** | yes, as a decision only |

⚠️ **B-12 status note.** B-12 was commissioned to this session in the same exchange. Work was **limited to a read-only occurrence inventory** in the session scratchpad: nothing written to the repository, and no conclusion drawn. It is **paused**, because this package's instruction says "Do not start B-12". Whether it continues is an L0 call.

## 7. Extended 1a specification (to run only after GIA-2 and GIA-3)

| ID | Correction | Closes |
|---|---|---|
| IC-1 | a missing input ⇒ `INCONCLUSIVE`; empty input with 0 items examined ⇒ `INCONCLUSIVE`; INCONCLUSIVE on an activated gate blocks | B01–B09 |
| IC-2 | an activated gate whose `status ≠ active` ⇒ `GOVERNANCE_INOPERATIVE`; never counted as applicable | D08, D09, D16, D17 |
| IC-3 | activation pins the gate-definition hash (**subject to GIA-4**) | D08–D10 |
| IC-4 | self-test failure, missing fixture, schema-invalid gate or duplicate gate ID ⇒ `GOVERNANCE_INOPERATIVE` | A0, D04, D06, D07 |
| IC-5 | implementation scope equals declared scope (KOS-G-001 all `.jsonl`; KOS-G-003 either scans what it claims or narrows its claim) | G01, B17 |
| IC-6 | the door reports `GOVERNANCE_INOPERATIVE` (exit 3) distinctly from BLOCK (exit 2) | D01, D02b, D03 |
| IC-7 | regression fixtures: per-check fail fixture for `theory_doc_exists`; missing, empty and near-miss fixtures for every activated check | audit §5 |
| IC-8 | no REVIEW/HUMAN activation until a completion-record mechanism exists (**GIA-7**) | D14, D15 |

⛔ **G02 (false FAIL) is not in IC-1…IC-8.** It depends on **GIA-5**, and its fix is scheduled for the decided option.

**Sequence:**

| Step | Content | Exit criterion |
|---|---|---|
| **1a.1** | freeze the baseline: record the §3 hashes again at the start commit | hashes recorded; any drift from §3 is itself reported |
| **1a.2** | **regression fixtures first**: at minimum B04, B09, D04, D06, D07, D08, D09, D10, D16, D17, G01, G02, C1, each with its **expected post-correction verdict** | fixtures committed; running them against the **unchanged** runner reproduces the audit's defective result (RED) |
| **1a.3** | implement IC-1…IC-8 | — |
| **1a.4** | self-test | **100%**. Any failure ⇒ `GOVERNANCE_INOPERATIVE`, never research CLEAR |
| **1a.5** | re-run **all 54 valid cases**, not only the changed ones | each case matches its recorded expected verdict. C1 may still be CLEAR (that is 1b's job), and this must be stated |
| **1a.6** | independent review by a separate governance/control-plane session | the implementer is not the accepting authority |
| **1a.7** | human acceptance of 1a | only then 1b |

## 8. 1b transition specification

1b starts with **INDEPENDENT REVIEW → ADOPT / ADAPT / REJECT**, not with IMPLEMENT. The review covers:
- corpus manifest generation, and the reproducibility of the manifest hash;
- canonical identity resolution, including the *exactly-one-match* rule and the handling of truncated or space-containing paths (the canonical list stores paths with spaces; parsing must not split on whitespace);
- admission (ADMIT/STOP semantics);
- the receipt model;
- **zero-receipt semantics** (legacy rows INCONCLUSIVE, never back-filled);
- proposed KOS-G-070…073 (with their namespace collision, GIA-6);
- B-14 handling (GIA-10);
- the relationship to the existing runner (one runner, one gate namespace);
- consistency with GIA-5.

⛔ **No proposed gate is activated during the review.** Output: a review report per artifact, then the L0 decision under GIA-9.

## 9. Responsibility boundary

| | May | May not |
|---|---|---|
| **Research session** | research; produce research evidence; propose research artifacts; report findings | modify active governance; activate or redefine gates; commit governance or governance-architecture artifacts; accept its own controls |
| **Governance session** | audit the control plane; implement controls **after authorization**; test gates; maintain governance artifacts; prepare decisions | silently alter research theory; repair provenance; decide substantive theory questions; resolve B-12 on its own initiative; treat governance failure as theory failure |
| **Human authority (L0)** | gate activation; rule changes; authority assignment; waivers; governance acceptance; GIA decisions; correction authorization | — |

## 10. Artifacts that must remain unchanged until L0 decides

- `governance/gate-runner.py` · `governance/gates.yaml` · `governance/governance-state.yaml` · `governance/gate-schema.yaml` · `governance/fixtures/` · `.claude/hooks/governance-preflight.sh`
- all active gate definitions and the activation list
- `evidence/*` and `governance/PROPOSED-GATES-RCI.yaml`: preserved, non-authoritative
- research theory v0.9 and all Phase-1/Phase-2 artifacts, including F0031–F0040 and `FILE-REGISTRY.jsonl`
- F0041+: not started
- `docs/knowledgeos/list_of_files_to_read.log` and every canonical corpus file
- B-12: paused (§6)

## 11. Required human decisions

**Minimum to begin extended 1a:** GIA-1 · GIA-2 · GIA-3 · GIA-4 · GIA-7.

**Before 1b:** GIA-5 · GIA-9 (after the review) · GIA-10.

**Standing:** GIA-6 · GIA-8 · B-15.

**Also open from earlier packages:** RC-H-01…RC-H-07 (`architecture/research-control-architecture.md` §13), including RC-H-04 (the F0031–F0040 correction, after 1b).

---

> ## ⛔ **IMPLEMENTATION NOT AUTHORIZED UNTIL THE REQUIRED L0 DECISIONS ARE MADE.**

*Traceability:* `GOVERNANCE-INTEGRITY-AUDIT-01.md` (§§1–17, Appendix A) · commits `04376db9b`, `6cffbea5e`, `163a0f996`, `15a05f297` · hashes computed read-only at `15a05f297`. No implementation file was created or modified.
