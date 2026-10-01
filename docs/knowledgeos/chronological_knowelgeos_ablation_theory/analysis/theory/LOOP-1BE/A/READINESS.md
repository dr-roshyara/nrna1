# READINESS: discrimination-readiness review (1be)

Sources: the eight files listed in TASK.md and nothing else. I ran no commands and captured nothing new. I scored no hypothesis.

**Verdict: NOT_DETERMINABLE.** No item in A or B is shown to be both independent and able to establish the norm in force at t. Three channels remain INDEPENDENCE_NOT_DETERMINABLE, and QUESTIONS.md forbids a forced negative (details in §3).

## 1. Inventory (A and B packages)

| # | Observable | Measures | Author class | M-TIME | Independent? |
|---|---|---|---|---|---|
| A1 | `e4724a44` "WP-4C-1 batch B1 -- RED for K1..K4", 2026-08-04T00:30:25Z | work | executor | RECORDING_ORDER (author = committer date) | No |
| A2 | `939e35dc`, 2026-08-04T15:19:21Z, WP-4C-1 implementation | work | executor | RECORDING_ORDER | No |
| A3 | `cc1a3394`, 2026-08-04T18:38:51Z (substantively WP-6, L1) | work | executor | RECORDING_ORDER | No |
| A4 | 47 DOC-OR-GOVERNANCE commits (27 historical; the rest are programme artefacts) | recording time of norm documents | norm-maker (historical); self-artefact (excluded by QUESTIONS.md) | RECORDING_ORDER | No |
| A5 | 1 UNCLASSIFIABLE commit | unknown | unknown | RECORDING_ORDER | Not shown |
| A6 | Plan-file name implying 19:00 on 08-04 (L3) | nothing usable | executor | not a timestamp source | No |
| A7 | Commit timestamps as such (LOCAL_REF_SET; completeness vs host NOT SHOWN) | recording time | executor-side clock | RECORDING_ORDER | No |
| B1 | K-NORMREG (202 paths, 3 writers) | norm state as asserted | norm-maker / self-asserting | RECORDING_ORDER; no content-timestamp qualifier (N13) | No: SELF_ATTESTED (definitional) |
| B2 | K-STATUS (153 paths) | norm status | unknown; writers overlap the norm set 3/3 | RECORDING_ORDER | Not shown: NOT_INDEPENDENT[overlap_norm=3], meaning "no evidence of role separation" (F-B2) |
| B3 | K-DEPLOY (30 paths) | work / release status | unknown; overlap 3/3 | RECORDING_ORDER | Not shown (as B2) |
| B4 | K-CI (6 paths; its one writer is in E and N_c) | enforcement config; content not read | unknown (the file writer overlaps both sets; enforcement is host-side) | RECORDING_ORDER for the file; enforcement time unobservable | INDEPENDENCE_NOT_DETERMINABLE |
| B5 | K-HOOK (3 paths) | as B4 | as B4 | as B4 | INDEPENDENCE_NOT_DETERMINABLE |
| B6 | K-SIG (23 signed commits, a lower bound; 0 signed tags) | signer attestation of a commit (recording), not norm state | unknown (A3 a–g) | signer clock (Q4 NO_CLIENT_SET); not verified | INDEPENDENCE_NOT_DETERMINABLE |
| B7 | K-CODEOWNERS | — | — | — | NOT_EVIDENCED_AT_TIPS |
| B8 | K-LOCALHOOK | — | — | — | NOT_EVIDENCED_LOCALLY |
| B9 | 5 host-side channels | could cover norm state and work | host / unknown | not acquired | NOT_CHECKABLE_LOCALLY |

The register-derived items in STATE.md and IDENTIFIABILITY.json are not part of A or B: the self-reported adoptions E1 and E3, the Decision Authority's "R-89 already governs authorization", R-94 "Executed at …:58", R-86 "waiting", and S0804. Each was written by the norm-maker or is a ruling that declares its own force, so none of them qualifies either.

## 2. D1 check (symmetry rule)

**D1 broken: no, not on the acquired evidence.**

| Candidate | How H-D explains it | How H-B explains it | Discriminates? |
|---|---|---|---|
| A1–A3 | The work is outside the record. Either an act not observed here authorized WP-4C-1 (R-89's issue time and status are unresolved), or the work ran against an unchanged norm. H-D predicts nothing about conformance either way. | The work conformed because the norm changed before 00:30:25Z (by an act, or in response to work), or it is a non-conformance anomaly. | No. This is D1's own case: the ordering is known, but the norm state at t is not. |
| B1 | Norm-making acts record their own force. | Both hypotheses accept that norm-making acts exist. | No. Both predict that rulings assert their own force. |
| B2, B3 | Status files are documents written by the norm-maker. | Status and deploy files are records of the coupling between norms and work. | No. The same identities wrote both. |
| B4, B5 | CI and hooks are work tooling outside the record, or they implement norms made by acts. | They are the coupling itself: work starts only when permitted. | No. Their existence fits both, and their content and enforcement history were not acquired. |
| B6 | Neither hypothesis predicts anything about signatures. | (same) | No. A signature dates and attributes a commit. It does not state a norm. |
| B9 | Not acquired. | (same) | Nothing to explain. |

## 3. Verdict: NOT_DETERMINABLE

**Decisive reason.** Q5 ("can it establish the norm in force at t?") is NOT_DETERMINABLE_BY_EXISTENCE_CHECK for every channel. No channel reached INDEPENDENT_AT_IDENTITY_COUNT_LEVEL, so the answer is not YES. K-CI, K-HOOK and K-SIG are still INDEPENDENCE_NOT_DETERMINABLE, and QUESTIONS.md says such a result is "never a forced negative". NO therefore cannot be asserted either.

**Missing fact.** Does a record kept by someone other than the norm-maker, the executor or the self-asserting ruling attest the norm state of a register target at a stated t? In practice that means a host-side record (required checks or branch protection, PR approvals, CI run history, audit log), or a verified host-generated signature tied to one. The concrete instance: was WP-4C-1 authorized or blocked at 2026-08-04T00:30:25Z, the time of the earliest execution trace?

**Consequence (PREREG).** Per PREREG, the next step is a decision brief for the human. Until this is resolved, H-D vs H-B stays NON-IDENTIFIABLE on the acquired evidence. That is a limit of scope, not a falsification of either hypothesis.

## 4. What would change the verdict (all outside the authorized scope)

| Channel | Could establish | Needs a human decision because | Info value |
|---|---|---|---|
| Host-side channels (PR approvals, branch protection, audit logs, CI run history, membership) | A host-kept, host-timestamped record of what was allowed to merge, and who approved it, at t. Membership data would also give the closed-world capability register that IDENTIFIABILITY.json asks for (P1, P5). | It is outside local scope (F-B3). It needs host access and credentials, which is a new evidence channel. It brings in identity data, which the outputs deliberately kept out. | **High.** It is the only listed channel that could be both independent and about norm state. That holds only if the host rules map onto register targets and the approvers are not the norm-maker identities, and neither is known. |
| Signer verification (K-SIG, 23 headers) | The kind of signer (A3 a–g). If it is a host web-flow key, the signature is a host-generated attestation of a commit's identity and time. | It requires keys and/or host data (F-B3). | **Low** for D1: a signature attests a commit, not a norm. **Medium** for the timing confound in P2, but only if the signed commits include the relevant ones, which is not known. |
| R-SELF rule | Cleaner class counts in A (21 of 51 matches are programme artefacts) and in B (`.claude/` and `claude/` are not excluded, N15). That would show the real composition and overlaps of K-NORMREG and K-STATUS. | It is a protocol change for future captures, explicitly held for a human decision (L2, N15). | **Low.** It does not change the execution-trace set (L2). With identity sets of size ≤ 3, even a zero overlap would not discriminate (F-B2). It removes contamination but cannot create a norm-state observable. |
| Non-Claude Gate 1 coding | None of the given files defines "Gate 1". I read it as the coding of the register (STATE's "current coding"). A non-Claude coder could give independent codes for the contested reading of R11 and for the uncoded execution reports (R-94, R-86, S0804). | Choosing and authorizing an outside coder is a decision about method and resources that lies outside this frozen task. | **Low** for D1: a second coder still reads text written by the norm-maker or by self-asserting rulings, so author independence does not change. **Medium** for the reliability of the P2 and P7 codings. |

## 5. Strength audit

| # | Inferential claim (quoted) | Evidence |
|---|---|---|
| S1 | "No item in A or B is shown to be both independent and able to establish the norm in force at t." | B review §6: Q5 is ND on every row and no channel is INDEPENDENT; A's traces are executor commits (A recon). |
| S2 | "Each was written by the norm-maker or is a ruling that declares its own force, so none of them qualifies either." | STATE.md (status mediation "self-reported by the rulings themselves"); IDENTIFIABILITY P4, P5, P7; QUESTIONS.md (a)–(c). Limit: this rests on the reviewed state, not on fresh coding. |
| S3 | "D1 broken: no, not on the acquired evidence." | §2 table; PREREG symmetry rule. |
| S4 | "No. This is D1's own case: the ordering is known, but the norm state at t is not." | A recon: RECORDING_ORDER only; IDENTIFIABILITY P2 and P7: R-76, R-78, R-80 and the timing and status of R-89 are unresolved. |
| S5 | "No. The same identities wrote both." | B recon F-B1: overlap_norm=3. This is weakened by F-B2 (low power, aliases): it means "no evidence of separation", not proof of fusion. |
| S6 | "No. A signature dates and attributes a commit. It does not state a norm." | B review A3: only the presence of a header was checked. What a commit signature covers is general knowledge, not a corpus fact. |
| S7 | "NO therefore cannot be asserted either." | QUESTIONS.md rule "never a forced negative"; B recon F-B1 (three IND channels). |
| S8 | "It is the only listed channel that could be both independent and about norm state." | B recon F-B3; B review §6. This states a possibility only; nothing confirms it. |
| S9 | "Low for D1: a second coder still reads text written by the norm-maker or by self-asserting rulings, so author independence does not change." | QUESTIONS.md definition of independence (it is about authorship, not about the coder); STATE.md. |
| S10 | "With identity sets of size ≤ 3, even a zero overlap would not discriminate (F-B2)." | B review A1. |
| S11 | "That is a limit of scope, not a falsification of either hypothesis." | PREREG outcome table (wording for NO, applied here by analogy); IDENTIFIABILITY "overall". |
