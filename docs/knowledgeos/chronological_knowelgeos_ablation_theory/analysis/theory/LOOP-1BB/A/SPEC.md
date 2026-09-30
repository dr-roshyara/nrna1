# SPEC: the minimum new evidence that would discriminate H-D from H-B

This is a specification only. Nothing has been collected. Full field lists are in `SPEC.json`.

## 0. Scope and shared rules
- **SOURCE FACT:** No prediction is identified on the current corpus (IDENTIFIABILITY `identified: []`).
- **PROPOSAL:** A prediction is in scope if some observation puts one hypothesis at risk while the other survives it. In scope: **P1** (only under H-B reading 1), **P2, P4, P5, P7, P8**. Excluded:
  - **P3**: act-mediated feedback fits both hypotheses, and its act-less part is P1.
  - **P6**: both hypotheses are met at every record boundary.
- **PROPOSAL, independent attestation:** a field counts as independently attested only if the artefact that establishes it meets all three conditions:
  1. It was written by a different author or process than the norm-making record.
  2. It is not derived from that record.
  3. Its timestamp is set by a system (CI start, server push), not by the actor. A committer date set by the actor is weaker.
  - Anything a ruling says about itself is self-report: "effective immediately", "R-89 already governs".
  - **SOURCE FACT:** recording time ≠ decision time ≠ entry into force. So entry into force must come from an **adoption record**, never from the time a row was recorded.
- **PROPOSAL, closed-world capability register:** fix in advance who may make which norm type (**SOURCE FACT:** R-86 names the Decision Authority as the only adopter) and the single channel they must use (register row plus adoption record).
  - "No act" then means "no entry in that channel's write history during the window".
  - It never means proving that no unrecorded act happened anywhere.
- **Independence (all specs):** two episodes are independent if all of these hold:
  - they concern different targets or work packages;
  - they are governed by different acts;
  - different adoption acts bring those acts into force;
  - their execution artefacts are separate (not successive commits of one batch or session);
  - their attestation sources are not copies of each other.

## 1. Specifications

| # | Target and claim | Key fields (all attested as in §0) | Min episodes: reason | Falsifier H-D | Falsifier H-B | Window |
|---|---|---|---|---|---|---|
| **P1** | **H-D:** "only through acts". **H-B, reading 1:** work changes a norm with no act. **UNKNOWN:** which reading of H-B is intended; under reading 2, P1 cannot discriminate. | target; state before (last execution or refusal to execute); state **after, attested outside the register** (queue, work plan, first execution on the new state); closed-channel acts with entry into force; prior conditional clauses (R-72, R-79); work event (CI or commit) | **H-D: 1.** "Only" is universal. **H-B: a census** of every eligible work-gated target in the window, with at least 1 eligible case. H-B's clause is existential, so no sample can refute it. The draft's "≥3" had no basis. | The after-state is used by engineering, no closed-channel act produced it, and no prior act made it conditional on that work. | In the census, every change whose work condition was met occurred only after a separate act, and nothing was executed before that act. This is a behavioural test (the R-86 "waiting" pattern is a candidate). | From the work event to the first use of the new state or the next act. Census: corpus span to 2026-08-15. |
| **P2** | **H-B:** execution begins only when permitted, and non-conformance draws a response. **H-D:** silent. | execution (actor, diff, system time); operation class under a rule fixed in advance (test paths only?); texts of the norms in force plus adoption times; scope text in force before t_exec; response acts | **1.** A universal clause falls to one clean counter-instance. | **None.** H-D makes no conformance claim; P2 is one-sided. | A non-permitted operation, fully determined, with no sanction, remediation or waiver before the acceptance act takes force. | t_exec → acceptance takes force. R11: 2026-08-03 16:10 → R-87. |
| **P4** | **H-D:** a PREPARED ruling has no force until adopted. **H-B:** silent. | R-87, R-88 and R-89 text; status sequence from the adoption record's write history (who wrote it, and when); adopting actor versus the register; uptake (work, or citation by a *non-adopting* actor) | **1.** Universal. More episodes would only corroborate H-D, since H-B is silent. | No adoption entry in the window, yet work was executed under R-89 or a third party cites it as governing. The DA's own "already governs" does not count. | None on P4. Paired with P7, the same data put H-B at risk. | R-89's recording time → first adoption or withdrawal, or 2026-08-15. |
| **P5** | **H-D:** only a capable actor can make a norm take effect. R-94 is the candidate. | capability register **by norm type** (R-86, R-90); R-94 write history; execution at IMPLEMENTATION_PROGRESS.md:58, attested by that file's revision; adoption or its absence | **1.** Universal. | R-94-type dispositions need DA adoption, none is recorded, and execution under R-94 is attested. | Via P2: if R-94 needed adoption and had none, the execution was non-conforming. **INFERENCE:** the likely outcome is operational scope (R-90), which means neither hypothesis is at risk. | R-94 recorded → execution, then the P2 window. |
| **P7** | **H-B:** successor work starts only after the predecessor's acceptance takes force (R-72). | R-87 text and entry into force; WP-4B GREEN/VERIFY CI run; R-89 entry into force; **first WP-4C-1 commit or CI run**; any waiver | **1.** R-72's gate is universal. The draft's "2 pairs" would show a pattern, but a pattern is not needed to discriminate. | The successor is worked as authorized because the predecessor was accepted, with no authorizing act. This is P1 applied to this gate. | WP-4C-1 work began before WP-4B's acceptance took force, the gate was in force, no waiver existed, and nothing responded. | R-89 recorded → WP-4C-1 acceptance, or 2026-08-15. |
| **P8** | **H-B:** failure leads to remediation or an explicit deferral before work resumes. | failure (a failing CI run or log entry, not the ruling that responds to it; R-91); next execution on the same target; remediation act that cites the failure id | **1** if the clause is read as universal. **UNKNOWN:** it may be meant as a tendency, which would need a census. | Remediation took effect with no act (this reduces to P1). | Failure, then execution on the same target, with no remediation or deferral-with-trigger taking force in between. | Failure → next execution on the same target. No execution means the falsifier is not triggered. |

**UNKNOWN (conflict between the inputs):**
- IDENTIFIABILITY P7 infers that R-89 very probably authorized WP-4C-1 before WP-4B was accepted.
- CONFORMANCE-CASE C2 orders R-87 (the WP-4B acceptance) before R-89.
- Only R-87's text and its adoption time can settle this.

**Feasibility.** These are *candidate* sources; this spec does not claim they contain the records.
- The git write history of the register file, as the closed channel.
- Commits and CI runs on WP-4B and WP-4C-1.
- Revisions of `IMPLEMENTATION_PROGRESS.md`.
- The queue, work plan, and `CONTEXT.md`.
- Session logs S0804, S0815 and any later ones.
- The adopted texts of R-86 and R-90, as the capability register.

## 2. (a) What would resolve the contested case (C1: the R11 commit "WP-4B RED")
**Acquire:**
- the full texts of R-73 to R-76, R-78 and R-80;
- the entry-into-force times of R-77 to R-80, from their adoption records;
- the R11 diff, with a CI or push time, classified by a rule fixed in advance;
- any scope text in force before 16:10;
- R-87's text.

**PROPOSAL, decision rule:**
- **NON-CONFORMS** if a blocking text in force before 16:10 covers the RED operation class.
- **CONFORMS** if R-73 to R-76 met R-72's proviso and a text in force before 16:10 either removed transport from the "governance blockers" (R-78, if it was in force then) or scoped the block to exclude producer-agnostic RED.
- **UNDETERMINED** otherwise.

The case counts against H-B only if it is NON-CONFORMS **and** no response takes force before R-87.

## 3. (b) Moving status mediation from self-reported to independently observed
**SOURCE FACT:** there are 2 episodes, and both are self-declared inside the rulings.

**PROPOSAL:** acquire three things:
1. An **adoption entry** written by the adopting actor in the closed channel, with a separate identity and a system time.
2. **Uptake by others** after adoption: engineering commits or CI runs, or citations by non-adopting actors.
3. A **behavioural contrast**: no execution or third-party citation during the prepared-not-adopted interval.

Force is independently observed when behaviour differs between the two intervals. One contrary episode would falsify H-D's status clause. Even so, this only tests H-D, because H-B is silent.

## 4. Ranking by information gain per unit of acquisition effort (INFERENCE)
1. **P4+P7 (R-89/R-87): HIGH.** About 3 records. Two-sided in combination, and it resolves the conflict between the inputs.
2. **P2 (R11): HIGH.** About 8 named records and one episode. It is the only live candidate against H-B, but it is one-sided.
3. **P5 (R-94): MEDIUM.** Low effort and two-sided, but the likely outcome discriminates nothing.
4. **P8 (R-91): LOW–MEDIUM.** One-sided, and it probably needs records after 2026-08-15.
5. **P1: LOW.** It is the only direct falsifier of H-D's "only", but it needs the H-B reading fixed, a closed-world register, and after-states attested outside the register, and no candidate act-less change is observed.

## 5. Single smallest discriminating acquisition (PROPOSAL)
Three items:
- **R-89's adoption status in the register's write history:** who wrote the adoption and when, or that no adoption appears up to 2026-08-15.
- **R-87's text and its entry-into-force time.**
- **The system time of the first WP-4C-1 commit or CI run.**

How the outcomes separate the hypotheses:
- R-89 not adopted, but work ran under it → H-D's status clause is falsified.
- R-89 in force, and WP-4C-1 work started before WP-4B's acceptance with no waiver → H-B's gate conformance is falsified.
- Acceptance, then adoption, then work → neither is falsified, and C2 is confirmed.

This is the only acquisition this small that puts **both** hypotheses at risk.
