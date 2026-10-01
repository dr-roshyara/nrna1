# REVIEW of READINESS.md / READINESS.json (1be)

Sources: REVIEW-TASK.md, TASK.md, READINESS.md, READINESS.json, HYPOTHESES.md, STATE.md, IDENTIFIABILITY.json, QUESTIONS.md, PREREG.md, PROTOCOL-A-RECONCILIATION.md, PROTOCOL-B-RECONCILIATION.md and PROTOCOL-B-OUTPUT-REVIEW.md. I ran no commands. I could not list the directory, so I used the file set that TASK.md names.

**Verdict: PASS_WITH_LIMITATIONS.** There are no blocking issues. The verdict NOT_DETERMINABLE follows from the inventory. It is the weakest verdict the evidence supports: YES is ruled out, and NO would be the forced negative that QUESTIONS.md forbids. The defects below are in the reasoning and in how complete the review is. None of them changes the verdict.

## 1. Fidelity

**Traceability: OK.** Every inventory row traces to a source file:
- A1–A7 trace to A recon (evidence package, L1–L3).
- B1–B9 trace to B recon F-B1 to F-B3 and the B output review §2, §4 and §6.

Each count matches its source: 51 = 3 + 47 + 1; 23 signatures and 0 signed tags; 202, 153, 30, 6 and 3 paths; one writer for K-CI and K-HOOK, who is in both E and N_c (A7).

**Omissions** (none of them blocking; none would supply an independent norm-state observable):
- **O1. A5 can be classified, but is left as "unknown".** L2 gives 21 of 51 matches as programme artefacts, and 47 − 27 = 20 of those are DOC-OR-GOVERNANCE. L2 also says R-SELF "does not change the ET set". The remaining artefact must therefore be the 1 UNCLASSIFIABLE commit, which makes it a SELF-ARTEFACT that QUESTIONS.md excludes. This is my inference from the counts; the source does not say it directly. The readiness review keeps A5 as "unknown / not shown". That is conservative, but it misses the point.
- **O2. The WP-4B RED commit (R11) is missing.** STATE.md lists it as the corpus's one execution record and its one git commit, with a contested reading of non-conformance. It is the main execution trace in the prior state that bears on work timing. The review's paragraph on register-derived items leaves it out.
- **O3. The list of uncoded execution reports is incomplete.** The review names R-94, R-86 and S0804. IDENTIFIABILITY.json also cites:
  - "Container resolution executed and reproduced";
  - the grep in R-83;
  - R-41's "first checklist execution … caught a second instance";
  - S0815's "Applied immediately".
- **O4. IMPLEMENTATION_PROGRESS.md is not identified as a separate record.** R-94 points to IMPLEMENTATION_PROGRESS.md:58, a work artefact that was not acquired and whose author is unknown. It records work, so D1 would apply to it. It should still have been listed as not acquired and as "author unknown", not folded into "the ruling".
- **O5. The B output review's §4.2 over-capture caveat is not applied to the "Measures" column.** B1's "norm state as asserted" and B2's "norm status" are stated without qualification. K-NORMREG's vocabulary can catch i18n files, fixtures and config (NB-12, NB-13), so the composition is unknown.

The four checks REVIEW-TASK names:
- **23 signature headers:** covered (B6 and §4).
- **Execution traces A1–A3:** covered, but see O2.
- **Self-reported status mediation (E1, E3):** covered and correctly treated as self-asserting.
- **Uncoded execution reports:** partly covered (O3, O4).

## 2. Independence classification

- **No self-attested record is called independent.** E1, E3, "R-89 already governs authorization" and K-NORMREG are all excluded correctly. The QUESTIONS.md rule that a timestamp does not make a record independent is respected.
- **NOT_INDEPENDENT in the inventory: correct.** B2 and B3 read it as "no evidence of role separation" (F-B2).
- **M1. NOT_INDEPENDENT leaks in as role fusion in §2.** The B2/B3 symmetry row gives "No. The same identities wrote both" as its reason. The H-D cell also says "Status files are documents written by the norm-maker". Both use the identity overlap as if it attributed authorship, which is the reading F-B2 forbids. The S5 hedge comes after the claim. The row's conclusion still holds on other grounds: the content was not read, Q5 is ND, and the files' existence fits both hypotheses. The stated reason should be replaced with those.
- **M2. S0804's author class is wrong.** S0804 is a session log (an S-prefixed log, like S0815), not a ruling. "Each was written by the norm-maker or is a ruling that declares its own force" (S2) is therefore unsupported for S0804, whose author class is unknown. It is still not independent, because independence is not shown and the record measures work.
- **M3. Author classes for A4 and B1 are assigned by construction.** "norm-maker (historical)" for A4 and "norm-maker" for B1 rest on path class, not on author data. B1 even has overlap_exec = 3. This is the same move QUESTIONS.md warns against for executors. The independence result ("No") still holds: K-NORMREG is SELF_ATTESTED by definition, and A4 is the recording history of those documents.
- **M4. A1–A3 are rejected for the wrong reason.** They are labelled "executor, not independent". The decisive reason is that they measure work, not norm state, and so fail Q5 whoever authored them. A recon does not emit authors (S1 cites "executor commits (A recon)").

## 3. D1 and symmetry

- **d1_ok: yes.** D1 is applied correctly. The A1–A3 row is D1's own case: the order is known, and the norm in force at t is not. No item is wrongly accepted as a discriminator.
- **No genuine discriminator is wrongly dismissed.** K-CI and K-HOOK might in principle record enforcement, but their content and enforcement history were not acquired. K-SIG attests a commit, not a norm.
- **Symmetry rule: applied correctly to every candidate.** The weak spot is the B2/B3 rationale (M1).
- **Wording.** `d1_broken: false` in the JSON drops the qualifier "not on the acquired evidence" that the MD uses. Read without it, the field sounds like a negative finding.

## 4. Verdict

The verdict is supported. Q5 is ND, and no channel reached INDEPENDENT_AT_IDENTITY_COUNT_LEVEL, so the answer is not YES. K-CI and K-HOOK are INDEPENDENCE_NOT_DETERMINABLE, and QUESTIONS.md forbids forcing that to NO. The missing fact is named, and the next step follows PREREG: a decision brief.

Two weaknesses:
- **(a) K-SIG is used inconsistently.** The decisive reason counts K-SIG as one of the channels that block NO. But S6 and §4 say a signature "does not state a norm" and is "Low for D1". Only K-CI and K-HOOK actually carry the ND, unless a signature is tied to a host record, as the missing fact correctly puts it.
- **(b) Two pieces of wording go further than the sources.**
  - "Q5 … for every channel": Q5 is ND on every *evidenced* channel. The other channels are NOT_APPLICABLE or NOT_CHECKABLE_LOCALLY.
  - The "concrete instance" (was WP-4C-1 authorized or blocked at 00:30:25Z?) is the P2/P7 conformance question. The readiness question is whether a record that could answer it exists. These should be kept apart.

## 5. Out-of-scope channels

- **No channel is recommended as if it were authorized.** Each is tied to a human decision, with the reason taken from F-B3, L2 or N15.
- **Information-value judgements: justified overall.**
  - Host-side: high, with conditions stated.
  - Signer verification: low for D1, medium for P2, with conditions stated.
  - R-SELF: low, per L2 and F-B2.
  - Gate 1 coding: low for D1. The review openly says it is reading "Gate 1" as the register coding.
- **Overclaim.** The host-side row says "Membership data would also give the closed-world capability register … (P1, P5)". Host membership shows who has repository access. P1 and P5 need *normative* capability: who may adopt, authorize or dispose. Membership could at most feed into that register.

## 6. Neutrality

- **OK.** H-D and H-B are not scored, and no falsification or validation is declared.
- **NON-IDENTIFIABLE is framed as a scope limitation.** The review discloses that it applies PREREG's NO wording by analogy (S11). That is consistent with IDENTIFIABILITY "overall".

## 7. Strength audit (TASK step 5)

The strength audit is incomplete. These inferential sentences are not quoted:
- "Their existence fits both";
- "Status files are documents written by the norm-maker";
- "It removes contamination but cannot create a norm-state observable";
- "Membership data would also give the closed-world capability register";
- "H-D predicts nothing about conformance either way";
- "Medium for the reliability of the P2 and P7 codings".

## Blocking
None.

## Nonblocking (fix in a revision)
1. M1: base the B2/B3 symmetry rationale on Q5 = ND and content not read, not on identity overlap.
2. M2: change S0804's author class to unknown, and qualify S2.
3. O1–O5: classify A5 as SELF-ARTEFACT (inferred), add R11, list the further execution reports and IMPLEMENTATION_PROGRESS.md, and qualify what B1 and B2 measure.
4. Decisive reason: rest the ND on K-CI and K-HOOK, and say how K-SIG contributes only if it is tied to a host record. Change "every channel" to "every evidenced channel".
5. Qualify `d1_broken` in the JSON.
6. Correct the membership → capability-register overclaim.
7. Separate the missing fact (does a record exist?) from the conformance question.
8. M3 and M4: replace author classes assigned by construction with "unknown", and reject A1–A3 because they do not measure norm state.
9. Complete the strength audit.
