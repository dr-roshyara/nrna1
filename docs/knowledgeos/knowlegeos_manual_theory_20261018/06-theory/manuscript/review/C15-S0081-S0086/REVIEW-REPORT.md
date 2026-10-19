*Reviewer's steps 2–3 report, verbatim (window C15; saved by the Professor, 2026-10-19).*

# REVIEW REPORT — WINDOW C15 (steps 2–3)

*Transition review of: §6.7 (new, C1–C7); the §1.12 form rewrite; STEP8-AND-SCAN; CON-0146 and CON-0147; and the changed theory from the TT2 and C14 settlements. I re-read my saved step-1 inventory first. Independence is process independence, not personal independence. The analysis needs more than about 1,200 words because it covers three objects of review; lower-priority points are in the appendix.*

## 1. Assessment
- **§6.7 within its scope:** it quotes its sources accurately. C1 and C3 hold.
- **Main weakness of §6.7:** C2, C4 and C7 state more than the sources and the mathematics support. It also misses half of the cross-source differences, including the two that bear most on trust: verification never reaches acceptance, and the lifecycle has no negative verbs.
- **Largest risk:** a settled TT2 correction is itself too general. "The order of acts is a uniqueness" (F26, §9.7 result 3, the §9.6.6a qualification) holds only where act times are missing or tied. §1.14.1 orders acts by (t_x, s_x), with act time first. Separately, the C14 restatement of CON-0144 lets anything be closed by stating a defect, which its own source contradicts.

## 2. Findings (ranked)

| # | Sev | Type | Claim | Line | What is wrong or missing | Consequence | Correction |
|---|---|---|---|---|---|---|---|
| 1 | **critical (scoped)** | defect | TT2 settlement: "CE as defined is not a function of a union-merged record. Its global position is a uniqueness" (§9.7.4 step 2; result 3; F26; §9.6.6a qualification) | §1.14.1 L159–163: "comparisons … use the total order (t_x, s_x): by act time, and by record position when act times are equal" | TT2's counterexample has 3 interleavings only if act times do not order issue, x and revoke. With distinct recorded act times, the union of per-record logs is ordered, so CE is a function of it. s_x is a tie-break only. The counterexample holds for the **estate** (2/210 dated, S0128 L49), not for the model. | F26 is not exhaustive, so "K4 = yes for positions" and "item 3 holds under F26(b) only" are not shown. | Restate result 3: a global position is needed only where act times are absent, tied or untrusted (act times are claims, §1.12). Add F26(c): act-time order with a tie rule that needs no serialization point. Re-qualify §9.6.6a items 3–4. |
| 2 | major | defect | §9.6.6a item 2: "So the evaluation rule is a kernel-defined function: a kernel candidate" | §9.6.6a item 2; §9.7.9 | TT2 withdrew "kernel must own" as a non sequitur. The qualification block corrects only "shared or mergeable" and leaves the kernel conclusion standing. CON-0138's meaning column still says "delegable only as a kernel-defined function over a shared record". | A withdrawn inference survives in a dependent section and in the register. | Withdraw it in item 2 and in CON-0138's meaning column. |
| 3 | major | defect | Restated form of CON-0142 / result 4: under K5, "one authoritative, versioned home" for each value | §9.7.4 step 4 | It repeats finding 5's pattern one level down. One value across applications requires agreement on the value, not one home. Identical pinned copies (admitted by §6.6 C1's restricted form, §1.16.6) also give agreement. The step follows only with §1.10 ("one authoritative home per rule") as a premise, and it is not cited. | The restated form is PROVISIONAL at best. | Cite §1.10 as the premise, or state "a common, versioned value", leaving the home open. |
| 4 | major | defect | Restated CON-0144: "close when … deliverable and lifecycle both justify it, **or** when the closure states its lifecycle defect" | S0079 L57, L73; S0076 L59 | As parsed, the defect clause has no deliverable condition. A lane with no output could be closed COMPLETE by stating "never ran". S0079 L57 refuses exactly that. V#3 (L73) was closed only because its "report was delivered and accepted". The "duty" is also conditioned on the closure's own note, which is ill-formed. | The restated rule is contradicted by its own source. | deliverable ∧ (lifecycle justifies ∨ defect stated). The duty: close when deliverable and lifecycle justify it. The defect clause is a permission, not a duty. |
| 5 | major | defect | C7 / CON-0146: "agreement of the final metric cannot show the facts are right"; "must compare the intermediate levels"; "a property of the inputs cannot be recovered from the output" | S0084 L11, L21–22; §6.7 L57–59 | **(a)** Non-injectivity is global. An output check verifies G exactly when the fibre of the expected value, among admissible graphs, is a singleton. Small-scope check: with node sets fixed, singleton fibres exist (empty-edge graph; 2 methods and 0 fields is injective in both variants). **(b)** "Must compare intermediate levels" is sufficient, not necessary. Any compared quantity that determines G on the admissible set suffices. **(c)** A property can be recovered iff it is constant on fibres (LCOM4 = 1 ⇒ connected). **(d)** The asymmetry is unstated: an output check is sound for rejection, not for acceptance. | INVALID as stated. | Restate as (a)–(c). |
| 6 | major | omission | C7 reads S0084 as a rule of evidence | S0079 L32 (cited line, read for item 5b) | "KOS-CONTRACT-NEUTRALITY-001 … Stage 1: handed off having deliberately implemented nothing" (2026-08-18, the diagrams' file date). S0084 is a **specification** of an unimplemented contract, not an observed mechanism. | C7's kernel reading rests on a design intention, not an observation. | State S0084's status. Mark the kernel law Hyp (one specification). |
| 7 | major | defect | C2: "Every capability sits inside one boundary … Outside it are the repository and the AI runtime"; plain language: "with the AI runtime outside it" | S0086 L15, L17; S0083 L13–14 | The container view puts the estate ("source and tests") **inside** the boundary. It drops the external AI runtime and gives Session Orchestration the technology "Agent runtime". The three views disagree on the system boundary; C2 cites only the two that agree. The §0.2 "tension" is also not cleanly there: S0086 puts the runtime inside. | Wrong boundary fact and selective citation. | Record the three-way boundary disagreement as Src. Re-run the §0.2 comparison. |
| 8 | major | distortion | C4 Int: "Only the context view draws that: a person between the act and the record" | S0083 L6, L16; S0086 L6, L20 | **(a)** The Governance Engineer holds "Authority, lifecycle, grants, acceptance" [L6]. That is a second authority holder, not a registrar, so the context view fits §1.13 least clearly. **(b)** There is an alternative explanation: C4 notation makes persons relate to containers or components at levels 2–3. In S0086 the Governance person is folded into "Engineering Roles", so the PO's arrow must land on a box. The changing target may be a notation artefact. | Overclaim on one side of an ambiguity the section itself declares. | Keep the Src part. Restate the Int with (a) and (b). |
| 9 | major | omission | Lifecycle vocabulary | S0086 L9 | Seven verbs: no REJECT, REVOKE, SUPERSEDE or RETRACT; ACCEPT has no negative. §5.7 C3 (no retire, no un-cancel) is the existing home, and the book has the trivial-solution test. | A second instance of vocabulary gaps is missed. | Add to C3 or §5.7 C3. |
| 10 | major | omission | Verification never reaches acceptance | S0085 L30–31; S0086 L27–28, L34 | In both views, verification and review have no edge to the governance or workflow engine. How a finding affects acceptance is not modelled. The Review Engine "preserves role separation" [S0085 L13] with no enforcing relation. | Central question ("how can it be trusted"): an invariant with no owner. | Add as Src. Apply the invariant-owner test. |
| 11 | major | omission | C2's "no kernel/application line is drawn" | S0084 L7–8 | S0084 does draw a line: a language-specific binding (adapter) and a language-neutral core (L3–L5). This is the kernel/adapter shape, though not named kernel. | The section's title claim holds for three views, not for all four. | Scope C2 to the three C4 views. Add S0084's line as a candidate boundary (Int). |
| 12 | major | defect | §5.7.1 plain language: "A supersession **counts** only once it is recorded" | §5.7 C3; C14 finding 3 | "Counts" is constitution language. It reintroduces the C1-a reading that the restatement of CON-0145 removed. | Settles the fork again in the summary. | "is recognized only once recorded". |
| 13 | minor | defect | C5 / CON-0147: "can be neither confirmed nor refuted"; "only if its dependency relations are marked" | S0086 L26 | One arrow that is unambiguously a dependency ("Consumes") running the wrong way could refute the law. Untyped arrows block confirmation more than refutation. The main obstacle is C2: there is no kernel/application partition to test against. | Overstated necessity. | Testing needs (i) a partition and (ii) dependency-identifiable relations. |
| 14 | minor | defect | C6: "Not independent of §6.6: S0066 and this set were first committed together" | git `f24edf316` | That commit adds 747 files (`git show --name-only` count). Co-commit is a batch upload, not a shared origin. S0066's mtime is 2026-08-17 18:17; the set's is 2026-08-18. | The conclusion is safe, but on the wrong basis. | Drop the inference. Keep "one line" for the five files only. |
| 15 | minor | defect | C3: "BC-7 … matches the component view" | S0085 L9–10 | The component view separates session registration (Session Manager) from grants (Workflow Engine). BC-7 keeps sessions, grants and transitions in one record. | Only a partial match. | "matches in grants and handoffs, not in sessions". |
| 16 | minor | defect | C14 settlement: "REALIZED" is "at S0015 L58 only" | S0014 L68 | S0014 L68 reads: catalog "unauthorized" vs README "REALIZED". The original anchor [S0014 L68] was correct. The new body note also adds a claim ("the commissions are the authorization records") inside a section whose header still says the body is unchanged. | The settlement introduced a false "only" and an unlabelled body change. | Restore S0014 L68. Label the note Int. Amend the §1.13 header. |
| 17 | minor | distortion | C7 Int: "this is §8.8 C2's rule … put as a theorem" | §8.8 C2 | §8.8 C2 is about the reproducibility of a count (selection rule, measurement point). C7 is about identifiability. They are related, not the same. | Weak support presented as identity. | "Related to". |
| 18 | minor | defect | §9.7.1: "agree … only if they use the same rule, the same policy choices and the same order" | §9.7.4 step 3 | Disagreement is shown only for P, κ, σ, υ (and ρ if it is policy). Parameter values that differ only on irrelevant points, and orders that differ only on incomparable pairs, still agree. The plain language also says "one global sequence", omitting act-time order (finding 1). | Overgeneral. | Scope it to the parameters shown. |

## 3. Concept verdicts

| Concept | Verdict | Reason · scope |
|---|---|---|
| §6.7 C1 | VALIDATED | Src at S0081 L3; scoped. |
| C2 | **INVALID as stated** | The "outside" sub-claim is contradicted by S0086 L15, L17; "no line drawn" fails for S0084 (findings 7, 11). The restated form, scoped to three views with the boundary disagreement recorded, is PROVISIONAL. |
| C3 | VALIDATED (Src) | Two homes in S0086, one in S0085. The BC-7 match is partial (finding 15). |
| C4 | PROVISIONAL | Src holds. The Int ignores S0083 L6 and the notation explanation (finding 8). |
| CON-0147 (C5) | PROVISIONAL | Restate it as two conditions; untyped arrows block confirmation rather than refutation (finding 13). |
| C6 | PROVISIONAL | The homonym reading is Int; the independence basis is wrong (finding 14). |
| CON-0146 (C7) | **INVALID as stated** | Findings 5–6. The restated form is VALIDATED as mathematics: an output-only check verifies G iff the admissible fibre is a singleton; a property can be inferred iff it is constant on fibres. As a kernel law of evidence: PROVISIONAL, Hyp (one specification). |
| §1.12 form rewrite | VALIDATED (form) | The body is identical after labels (script check: 17/17 lines equal). The added blocks point only to body text. Defects are minor (appendix). |
| §9.7 result 3 / F26 as framed | **INVALID as stated** | Finding 1. Restated, scoped to absent, tied or untrusted act times: PROVISIONAL. |
| CON-0138 | PROVISIONAL | The meaning column is stale (finding 2). |
| CON-0142 (restated form) | PROVISIONAL | Hidden premise §1.10 (finding 3). |
| CON-0143 (K5) | PROVISIONAL | I agree with the settlement. |
| F27 | open, well-posed | I agree. |
| CON-0144 (restated form) | **INVALID as restated** | The disjunct is mis-scoped; S0079 L57 and L73 contradict it (finding 4). |
| CON-0145 (restated form) | PROVISIONAL | Sound in the body, regressed in the plain language (finding 12). |
| F28 | open, well-posed | Its compatible reading is Int. |
| §1.13 settlement edits | lineage, A2, A3, A4 VALIDATED | REALIZED anchor INVALID (finding 16). |

## 4. Coverage of my step-1 inventory

| Step-1 item | Status |
|---|---|
| S0081 a proposal; the authoritative model not located | COVERED (C1; open question) |
| S0081 README scope "L3→L5" vs L0–L2 drawn | MISSING (source defect, minor) |
| S0083 human authority source; PO → Governance | COVERED (C4) |
| S0083 a second authority holder (Governance Engineer: "Authority … acceptance") | DISTORTED (finding 8) |
| S0083 KOS defined as an *engineering* operating system | MISSING (bears on §0.2 and the kernel's scope) |
| AI acts only under grants (S0083 L24) | partly (quoted in C5, not analysed) |
| Repository and AI runtime inside or outside | CONTRADICTED (finding 7) |
| Governance Communication as role (S0083 L7) vs capability (S0085 L16, S0086 L10) | MISSING (homonym) |
| Verification as falsifier; role separation | MISSING (finding 10) |
| S0084 non-injectivity; no back-inference; layering | COVERED, overstated (finding 5) |
| S0084 neutral core vs binding as a boundary | MISSING (finding 11) |
| S0084 "determinability" and "stable identity" as L3 fact attributes | MISSING (indeterminacy at fact level; links to U and the identity primitive) |
| S0084 conformance against *declared* expectations (who declares) | MISSING |
| S0085 "authoritative state" | COVERED (C6) |
| Mutation ownership as single writer | partly (C3 quotes, no analysis) |
| Knowledge supplies evidence, does not decide (S0085 L27, S0086 L24) | MISSING |
| Seven verbs, no negative verbs | MISSING (finding 9) |
| Supersession only in S0086 L11 | MISSING (minor) |
| Grants owned twice | COVERED (C3) |
| Policy in two places (S0086 L9, L29) | MISSING (minor; same pattern as C3) |
| C4-level error (S0085 decomposes the platform) | partly (C3) |
| One line of support | COVERED; mtimes verified (16:27:48–16:32:19 on 2026-08-18); co-commit basis wrong (finding 14) |

**Answers to the coordinator's questions:**
- **C7's argument:** it is correct as an existence claim. Its scope is stated too broadly (finding 5).
- **Does the LCOM4 definition matter?** Not for the existence of collisions, which any component count has beyond tiny classes. It does matter at the boundary: the variant decides which fibres are singletons, so which expected values an output check can verify (1 method and 1 field: variant A is injective, B is not).
- **Coverage of my cross-source items:** §6.7 covers grants (C3) and the authorizer (C4) only. Repository and runtime, role vs capability, verification → acceptance, negative verbs, and policy in two places are all missing or contradicted.
- **Is C4 overclaimed?** Yes, in its Int (finding 8).
- **Does §6.7's lineage note match git?** It matches on facts. It omits that the commit adds 747 files, including a 12-line `README (1).md` (seen in the stat listing only; not opened, not identified).

**New topics or forks:**
- F26(c), act-time order with a tie rule;
- the system-boundary disagreement as a source fact;
- verification-to-acceptance as an invariant with no owner.

**Source defects:** the S0081 L12 scope; the C4 level of S0085; S0084's use of `Rel_U` for constraints. None is relied on.

## 5. Next steps (dependency order)
1. **Restate §9.7 result 3 and F26** (finding 1). Done when the third option is listed and §9.6.6a items 3–4 are re-qualified.
2. **Remove "kernel-defined function"** from §9.6.6a item 2 and from CON-0138's meaning column (finding 2).
3. **Cite §1.10 in the restated form of CON-0142,** or weaken it to "a common, versioned value" (finding 3).
4. **Re-restate CON-0144** as deliverable ∧ (lifecycle ∨ defect stated), with a separate duty, and fix §5.7.1's "counts" (findings 4, 12).
5. **Restate CON-0146 as (a)–(c),** and state S0084's status as a specification (findings 5–6).
6. **Restate C2 and C4,** and add the missing cross-source items, ordered by finding numbers 7–11.
7. **Correct the minor findings** (13–18), the REALIZED anchor and the §1.13 header.

## 6. Limits
- **Not read:** S0082 or any file outside the brief; S0066 (C6's L286–287 is unchecked); S0164 (the map note); §0.2, §6.5 and §9.6 except the cited parts; §1.14.1 only at L150–165.
- **Not checked:** the oscillation and fixed-point claim in §9.7 step 5, accepted from TT2. The ending of the T-1 text at S0078 L169 (my output was truncated).
- **Small-scope check:** `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c15/lcom4_fibres.py` enumerates all edge sets for node sets of up to 3+1 and 2+2. It shows form, not a general proof.
- **LCOM4 definitions:** the variants (method-field graph vs method graph) come from my own knowledge, not a checked reference.
- **Quotes:** about 20 quotes in §6.7 spot-checked, all matching.

## Appendix (lower priority)
- **§1.12 rewrite:**
  - the header says "§n.m.k-j", but the labels are "1.12-j";
  - the References table omits DR-AB's use in 1.12-2, "pilot 2, file 5", and Ch. 2 §2.8.7–2.8.8;
  - the lineage note (Int) adds nothing, which is correct;
  - plain language, 1.12.98 and 1.12.99 match the body, and the change log entries exist (01.99 L8, L26–27).
- **STEP8-AND-SCAN:**
  - "10 strong words … all scoped or inside a stated Ded": being inside a Ded is not scoping. The words at L57–59 are the overclaims of finding 5.
  - The dependency table omits the Ext LCOM4 definition, S0066 L286–287, and RESEARCH-PURPOSE's stability test (C2).
- **§9.7 Analysis 3 condition 4:** phrase it as conformance to the defined CE, not "a valid adoption", so it does not read as circular.
- **§5.7 C5's added duty ("reopen when new evidence arrives"):** it is the book's own norm and too strong. Label it Int and restrict it to evidence that bears on the model.
- **Not drawn out:** registry roles as configuration data (S0085 L15); the Composition Root (S0085 L8); Drafting bounded by the grant (S0085 L29).

