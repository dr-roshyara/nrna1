# Task (frozen): specify the minimum new evidence that would discriminate two hypotheses

Use ONLY the files in this directory:
- `HYPOTHESES.md`: H-D and H-B;
- `IDENTIFIABILITY.json`: reviewed predictions; which observation types discriminate; a draft minimum-evidence list and its critique;
- `CONFORMANCE-CASE.json`: one reviewed non-conformance case;
- `STATE.md`: research facts.

No other path, no web, no commands.

**Do not collect anything.** Produce a **specification** only.

For **each** prediction on which H-D and H-B differ **and** that is not identified on the current corpus, specify:
1. **The target hypothesis** and the exact claim tested.
2. **The required observation:** the record fields needed (e.g. actor, operation, target, time, the norm in force and how it is established, the pre-state, the post-state, the source, the attestation), each with **why it is needed**.
3. **Attestation:** how each field must be established **independently** of the norm-making record. Examples: an execution time from a commit, not from the ruling; the entry into force from an adoption act, not from recording time. **Avoid self-report.**
4. **The minimum number of independent episodes, with a stated reason.** Examples: "one clean counter-instance falsifies a universal clause", or "k episodes to distinguish a rule from coincidence". No arbitrary numbers.
5. **The independence requirement** (what makes two episodes independent).
6. **A falsifier for each hypothesis that is testable within a stated time window.** No falsifier may require proving the absence of unrecorded acts; use a **closed-world capability register** where needed. No falsifier may rest on attitudes.
7. **Feasibility:** what kind of existing repository artefact **could** carry such records (e.g. commits, CI runs, progress files, session logs), stated as a **candidate**, without claiming it does.

Also specify:
- (a) what would **resolve the contested conformance case**;
- (b) what would move **status mediation** from self-reported to independently observed.

Then rank the specifications by **information gain per unit of acquisition effort** (qualitative: HIGH / MEDIUM / LOW), and name the **single smallest acquisition** that would discriminate most.

Write **SPEC.md** (at most ~2 pages; labels SOURCE FACT / INFERENCE / PROPOSAL / UNKNOWN) and **SPEC.json** ({specs: [{prediction, hypothesis, claim, fields: [{name, why, attestation}], min_episodes, reason, independence, falsifiers: {HD, HB}, window, feasibility_candidates, ig_per_effort}], contested_case_resolution, status_mediation_upgrade, smallest_discriminating_acquisition}).
