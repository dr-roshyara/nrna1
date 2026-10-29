# KnowledgeOS implementation track: decision record

| Field | Value |
|---|---|
| Purpose | The Principal's decisions for the **implementation track** of KnowledgeOS (Lead DDD Architect, Verification Auditor, Implementation Engineer; the role environment). Research decisions stay in `knowlegeos_manual_theory_20261018/03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md` |
| Created | 2026-10-29 by the Governance Engineer, on the Principal's decision (research record, addendum "two tracks; a separate record for the implementation track") |
| Placement | `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos/` (exit 0) |
| Decides | the Principal (human ARB), who leads the implementation track |
| Kept by | the Governance Engineer, under its mandate (`knowlegeos_manual_theory_20261018/03-methodology/MANDATE-GOVERNANCE-ENGINEER-PROPOSED.md`, §6): only explicit approvals are recorded, the Principal's words verbatim, "not decided here" stated |
| State | `docs/knowledgeos/IMPLEMENTATION-TRACK-STATE.md` |

*Append-only. Corrections are new entries; earlier entries are never rewritten.*

## Index: implementation decisions recorded earlier (in the research record, which they stay in)

All in `knowlegeos_manual_theory_20261018/03-methodology/PRINCIPAL-DECISION-RECORD-RULES-20261018.md`:

| Addendum (UTC) | Decision |
|---|---|
| 2026-10-29T02:08:48Z | S0200 decisions: D7–D8 the implementation strategy (part A; part B in principle); D9 role names; D11 the Verification Auditor mandate (part A may start) |
| 2026-10-29T08:51:08Z | the auditor may run tests that read allowed corpus files as fixtures |
| 2026-10-29T09:22:58Z | the Lead DDD Architect (seventh role) and the Implementation Engineer mandates approved (revision 2); the engineer inactive until stage 1 |
| 2026-10-29T09:50:07Z | the implementation strategy revision 3 approved in principle (stage 1 in five separate authorisations) |
| 2026-10-29T10:12:39Z | routing of the Architect's part-1 findings (A, B, C; D kept for the stage-1 package) |
| 2026-10-29T10:18:15Z | the Verification Auditor's part B authorised (after turn 5 and its review) |
| 2026-10-29T10:37:27Z | a KnowledgeOS-only pilot of the role environment, approved in principle (prepare for review; nothing live) |
| 2026-10-29T11:02:47Z | routing of the Architect's turn-5 findings; part B proceeds |
| 2026-10-29T11:06:44Z | two tracks; this separate record |

## Pending with the Principal (implementation track)

1. The stage-1 decision (proposed after the S0225 consolidation), including SD1-05: which reference product governs design investment (KnowledgeOS as its own product, or a supporting subdomain of PublicDigit); **widened by I-006 to EKS and PKS** (what each of the three systems is for, and which decides what).

## Entries

### I-001 (2026-10-29T11:11:48Z): the role-environment pilot configuration approved

- **Principal (verbatim):** "approve the pilot configuration".
- **Decided:** all six items of `knowlegeos_manual_theory_20261018/03-methodology/role-environment-proposal/README.md` (commit `479ca02e2`) are approved for activation: (1) three deny lines merged into `.claude/settings.json` for the hold-out files; (2) agent `kos-verification-auditor`; (3) agent `kos-lane-reviewer`; (4)–(6) skills `/role-governance-engineer`, `/role-lead-ddd-architect`, `/role-implementation-engineer` (user-invoked only). Activation follows the proposal's §4: copy, then tests T1–T9 (none on the real hold-out files); **a FAIL on T1, T2 or T5 rolls the affected item back** until the Principal decides. KnowledgeOS-only; repository-wide adoption stays a separate decision.
- **Not decided here:** a role-path hook (would need a grant for `.claude/scripts/`); sandboxing; repository-wide use.

### I-002 (2026-10-29T11:20:37Z): ineffective Write(...) ask rules corrected (test finding F2)

- **Principal (verbatim):** "F2 — File-protection rules: I approve correcting the identified ineffective Write(...) rules to the appropriate Edit(...) rules in `.claude/settings.json`. Keep the change limited to the identified rules, validate the configuration, and report the test results."
- **Done:** exactly two rules in `permissions.ask` changed: `Write(docs/implementation/PKS_*)` → `Edit(docs/implementation/PKS_*)`; `Write(engineering/governance/*)` → `Edit(engineering/governance/*)`. No other line changed (git diff: 2 lines out, 2 in). JSON valid.
- **Test (headless session at the repository root, `--permission-mode acceptEdits`, so writes are auto-approved unless a rule asks):** a control file outside the protected paths was **created** (then removed); `docs/implementation/PKS_ZZ-ROLEENV-TEST.md` and `engineering/governance/ZZ-ROLEENV-TEST.md` were **stopped by the permission prompt** (not created); Claude Code's warning "is not matched by file permission checks" no longer appears (0 occurrences). **Result: PASS.**

### I-003 (2026-10-29T11:29:23Z): dispositions of the Verification Auditor's part-B findings; one finding class added

- **Principal (verbatim):** "yes to all four" — on the Governance Engineer's four recommendations for `review-implementation/PART-B-REPORT.md` (commit `da0b6aea5`; finding B-01 verified independently by the Governance Engineer).
- **Decided:**
  1. **B-04, B-05** ("EKS already refuses to treat missing evidence as positive, and requires evidence for every verdict") are routed to the Professor **for T9-17**, as **labelled implementation evidence (Impl)**, input the Professor may reject; the Professor receives the findings, not the code.
  2. **B-08, B-10, B-11** to the Ch. 10 open items (for the revision after G1); **B-06** (producer authorisation unsolved in both theory and code) kept as a shared open question; **B-02, B-03, B-12, B-13, B-14, B-15** stay unresolved.
  3. The Verification Auditor mandate gains the finding class **"theory element with no code"** (revision 2; fingerprint at approval, after the status update: sha256 `d27135df31672aa0a7338943e212871b2338c91acc50c6e041644ff7852cd17d`).
  4. **For planning:** EKS is a **supporting tool, not the starting point of the kernel**; the stage-1 package plans the reference model as **new**.
- **Still due:** rerun of B-14 and the PL-0 and R0 comparisons when T10-16 (b) is applied to Ch. 10 (it is applied at the G1 settlement `8e4b1a420`).
- **Not decided here:** stage 1; any change to EKS.

### I-004 (2026-10-29T11:38:16Z): the auditor may read the decision records; the rerun's findings; one full rerun later

- **Correction (Governance Engineer, self-reported):** the pilot's agent definition `.claude/agents/kos-verification-auditor.md` told the auditor first to read the decision record, which the auditor's mandate (revision 2, §2) forbade; so the rerun could not read entry I-003, which authorised it. The pilot's test T4 listed tools only and did not catch it.
- **Principal (verbatim):** "yes to all three" — on the Governance Engineer's recommendations after `review-implementation/PART-B-RERUN-1.md` (commit `aee59fc1e`; R1-03 verified independently).
- **Decided:**
  1. **Auditor mandate revision 3:** the two decision records (this one and the research record) are readable, read-only; everything else under `docs/knowledgeos/` stays forbidden except the mandate and the report folder. Fingerprint at approval, after the status update: sha256 `0ace7ce8a11344d78a1db6ff432dd475712627cab525790937ad3fefaa5d4e61`. The agent definition now names both records (sha256 `5521bdd66c20c8c02ad5620e5024764fccda9d0b736d3c860c522383e3cb5e95`).
  2. **Rerun findings:** R1-02, R1-03, R1-04 (the record element, its invariants and R0′, and standing, have no counterpart in the code; class "theory element with no code") **confirm I-003** (the kernel is built new; EKS a supporting tool); no action. **R1-01** (the kind list in theory changes only by an authorised act; EKS's register list changes by editing a configuration file, unrecorded) stays **unresolved** and is **input for the Lead DDD Architect at stage 1**.
  3. **No partial rerun now** for the other Ch. 10 changes at `8e4b1a420` (R17, the count of conditional relationships, §10.4.4): **one full rerun of part B after the Ch. 10 revision for T10-17–T10-21.**
- **Not decided here:** stage 1; any change to EKS.

### I-005 (2026-10-29T11:41:54Z): the Lead DDD Architect and the Implementation Engineer may read both decision records

- **Principal (verbatim):** "yes, fix Architect and Engineer the same way".
- **Decided:** the same correction as for the auditor (I-004). `MANDATE-LEAD-DDD-ARCHITECT-PROPOSED.md` **revision 3** and `MANDATE-IMPLEMENTATION-ENGINEER-PROPOSED.md` **revision 3** approved: **both decision records readable, read-only** (the research record and this record, which holds the stage-1 decision and the authorisations of the Architect's parts 2–3 and of each plan). Nothing else changes. Fingerprints at approval, after the status update: Architect `46d655700848774e92f71b38e8fb1d18282283d554ff90eaf251e0e5048caff5`; Engineer `2ecf66055d1bd2a6feabb6af2047dc0eee982293ff9951cf9bf9fc39dad19d24`. The start skills `/role-lead-ddd-architect` and `/role-implementation-engineer` now name both records in their first check.
- **Why now:** without it, both roles' start checks would fail at the stage-1 decision.
- **Not decided here:** stage 1.

### I-006 (2026-10-29T15:24:54Z): SD1-05 widened to EKS and PKS, in the stage-1 package

- **Principal (verbatim):** "widen SD1-05 to cover EKS and PKS in the stage-1 package" — on the Governance Engineer's recommendation (no separate cross-system architecture task now).
- **Decided:** the stage-1 package's question SD1-05 (which reference product governs design investment: KnowledgeOS as its own product, or a supporting subdomain of PublicDigit) is **widened to cover EKS and PKS**: what each of the three systems is for, and which decides what. It stays **one question for the Principal in the stage-1 package**, after the S0225 consolidation as planned; the Principal states each system's business purpose, and the Lead DDD Architect sets out the options and their consequences from the settled strategic design (Ch. 10), I-003 and the audit reports. **EKS and PKS documents are opened only on the Principal's grant per file**, checked against the corpus allowed list.
- **Not decided here:** the answer to SD1-05; any separate cross-system architecture task or context map (a separate decision, only if the stage-1 decision shows the need); any mandate change (the Architect's part for the package to be checked when the package is prepared); any file grant; stage 1.
