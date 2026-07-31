# PKS Phase II — **ISV-1: Issuance Scope Verification of SDM v1.1 / EOP v1.1**

| | |
|---|---|
| **Act** | **A scope verification of the issuance** (Authority ruling, 2026-07-31 — item 3, revised) |
| **⚠️ NAMING — bounded 2026-07-31 by Authority terminology ruling** | **"Issuance Scope Verification" / "ISV" is a LOCAL ACT LABEL in this document's filename and identifiers. It is NOT an admitted verification class, and no such class exists.** **The governed description of the behaviour is: *perform a scope verification of the issuance.*** ***And this act's own §4 is the cautionary evidence: it declared "an instrument USED, not a class ADMITTED" while minting a filename, a title and an `ISV-F1` identifier prefix — the programme's quality-C defect exactly, since disclaimers define INTENDED responsibility while wording determines EXERCISED responsibility.*** **Not renamed, because the identifier-stability rule forbids it: ledger identifiers are part of the evidence, and the escalation ladder is clarify → alias → annotate, never renumber.** ***Which yields the lesson: a name, once issued into the record, cannot be WITHDRAWN — only BOUNDED. Premature naming is therefore more expensive than it looks, and that is the strongest available argument for the Authority's conservatism.*** |
| **Governing question** | ***Does SDM/EOP v1.1 incorporate exactly the CDR-R1 adoptions — and nothing else?*** |
| **Expressly NOT** | **An Assembly/Architectural Fidelity Verification.** *Authority ruling: for a definition-by-reference baseline there is no derived text, so a classical AFV does not exist for this structure* |
| **⚠️ INDEPENDENCE DEFECT, declared before any result** | **I authored v1.1 and am verifying it. RET-1 §5.3 records self-verification by the executing stage at 0-FOR-2 on defects later stages found.** **Mitigation: every assertion below is a mechanical text comparison a later independent reader can re-run in one command.** ***The mitigation does not cure the defect; it makes the defect cheap to test*** |
| **Verdict** | ⛔ **FAIL — ONE MAJOR DEFECT FOUND AND REMEDIATED. Seven of eight assertions pass.** |

---

## 1. The headline result, stated before the detail because it is against the verifier

> ## ⛔ **ISV-F1 — MAJOR. The Assembly Fidelity Rule was issued into the operative baseline in PARAPHRASE, while the issuing act asserted it was VERBATIM.**

| | Text |
|---|---|
| **Authoritative (register, PMR-5, the reviewer's original)** | *"…Improvements to inherited normative language must be **proposed against the authoritative source and propagated through the established publication chain.**"* |
| **As issued into v1.1** | *"…Improvements to inherited normative language must be **proposed and disposed, not applied in assembly.**"* |

**The paraphrase dropped TWO substantive requirements — it is an under-specification, not a stylistic variant:**

| Lost | Consequence |
|---|---|
| improvements must be proposed **AGAINST THE AUTHORITATIVE SOURCE** | *the paraphrase does not say WHERE a proposal goes* |
| improvements must be **PROPAGATED THROUGH THE ESTABLISHED PUBLICATION CHAIN** | *the paraphrase does not require propagation at all* |

### 1.1 What makes this more than an error

> ### ***The rule prohibits modifying normative wording inherited from an authoritative source. Issuing it in modified wording breached the rule at the moment of enacting it — and the issuing act simultaneously asserted it had not.***

**And the propagation pattern is the rule's own evidence, reproduced:** **the paraphrase ENTERED at MCA-R1, CASCADED through CDR-R1, and landed in the operative baseline — a THREE-SITE cascade.** ***PMR-5 was adopted on the evidence of an eleven-site restatement cascade, and was then itself propagated by a three-site restatement cascade. The candidate's own failure mode consumed the candidate.***

**This is the strongest possible vindication of PMR-5 and the clearest possible demonstration that the party restating normative text cannot detect its own restatement.**

### 1.2 Remediation — split by artifact class, per the established discipline

| Artifact | Class | Action |
|---|---|---|
| **SDM v1.1** | **OPERATIVE baseline** | ✅ **CORRECTED to the authoritative wording.** *An operative baseline carrying wrong normative text is a LIVE defect. The correction executes CDR-R1's actual decision — "adopt verbatim" — and decides nothing new* |
| **v1.1's false verbatim claim** | operative, but evidentiary | ✅ **RETAINED, with the falsity marked.** ***An issuance record that deleted its own false claim would conceal the defect it caused*** |
| **MCA-R1 · CDR-R1** | **FROZEN records** | ✅ **ANNOTATED, not corrected.** *Each records what that act actually quoted; rewriting them would erase the evidence of the cascade* |

***One defect, three artifacts, two mechanisms — chosen by class, not by convenience.***

### 1.3 A correction I owe on my own reasoning

**I argued that verifying v1.1's §2 against the register would be *"comparing the register's text to the register's text — a tautology, not a fidelity check."*** **That argument was wrong, and the check I dismissed as near-empty is the one that found this defect.**

***A comparison is only a tautology if the two texts are in fact identical — which is the very thing the comparison exists to establish. I mistook the intended outcome for the guaranteed one.***

---

## 2. The eight assertions

| # | Assertion | Result |
|---|---|---|
| 1 | **Only PMR-5 adopted whole** | ✅ **PASS** |
| 2 | **PMR-4 only partially adopted** | ✅ **PASS** — status adopted; the no-precedence exclusion is written into the baseline text, not merely referenced |
| 3 | **PMR-7 only partially adopted** | ✅ **PASS** — 7a's vocabulary present; **7b and 7c named and expressly excluded**, so exclusion is verifiable rather than inferable from absence |
| 4 | **PMR-8 absent** | ✅ **PASS** — appears once, in the "does NOT change" table, as *"Absent — unassessed"* |
| 5 | **No deferred item incorporated** | ✅ **PASS** — PMR-1 · 2 · 3 · 6 · 7b · 7c appear only in the exclusion table |
| 6 | **No wording strengthened** | ⛔ **FAIL → ISV-F1.** *And note the direction: the defect was WEAKENING, not strengthening. The assertion as drafted would not have caught it — see §3* |
| 7 | **No implicit adoption** | ✅ **PASS** — §2.3 states explicitly that the vocabulary's implied controls are not adopted |
| 8 | **No structural change** | ✅ **PASS** — v1.1 declines to consolidate and says why |

**Verified by mechanical comparison, not by reading: assertions 1–5 and 7 by identifier sweep across v1.1; assertion 6 by diffing each adopted clause against the register.** *Assertion 8 is the only one resting on judgment.*

---

## 3. A defect in the VERIFICATION SPECIFICATION itself

**The Authority's assertion list contained *"no wording strengthened."* The defect found was a WEAKENING.**

> ### ***A scope verification that tests only for strengthening is blind in one direction. The correct assertion is "no wording ALTERED" — because an issuance can betray its source by saying LESS as easily as by saying MORE.***

**Recorded as a finding against the specification, not against the Authority: the list was written before anyone knew which direction the risk ran, and the programme's prior experience was all of over-strengthening** (AFV-1's four interpretive over-strengthenings, VR-2's weakened governed term). ***The one case where a governed term was WEAKENED — VR-2 — was in the record and should have been the warning.***

**ISV-O1 (observation, carried):** *the "no wording strengthened" assertion should read "no wording altered" if an ISV-class act recurs. Not adopted as a rule — an ISV class does not yet exist (§4).*

---

## 4. Is "Issuance Scope Verification" a new construct? — **NO, and the distinction is deliberate**

**Tested against the three stabilizers, because naming a thing is how taxonomies grow:**

| | |
|---|---|
| **Is ISV a new review emphasis?** | **No — it is not a review.** *It assesses an ISSUANCE against a DECISION, not an artifact against a responsibility* |
| **Is ISV a new governance contract?** | **No.** *No new responsibility: the responsibility is "execution matches decision", which the programme already exercises* |
| **Is ISV a new governed class?** | **NOT YET.** ***Naming a verification is not admitting a construct.*** **The Authority named it descriptively; n = 1. It is an instrument USED, not a class ADMITTED** |

***If an ISV-class act recurs, admission becomes a question. One use is an act; repeated use is a pattern; only a pattern with evidence of escape becomes a construct.***

## 5. The AFV naming collision — queued, not resolved

**Three distinct objects share the token: AFV-1 (a promoted artifact) · AFV-class verification (an activity type) · PMR-2's discharge verification (a specific act).** **The Authority ruled normalization is warranted *"not urgently — but eventually."*** **QUEUED. Not resolved here, and expressly not resolved by inventing names for the other two.**

## 6. Consequences

| | |
|---|---|
| **v1.1 status** | **OPERATIVE, with the Assembly Fidelity Rule now carrying its authoritative wording** |
| **Was the CDR's decision changed?** | **NO.** *CDR-R1 decided "adopt PMR-5 verbatim". The issuance failed to execute that; the correction executes it. No decision was revisited* |
| **Does anything need re-deciding?** | **No — but the Authority should be aware the operative rule is now STRONGER than the one described in three records**, all of which are annotated to that effect |
| **Certification state** | **Unchanged.** Operational Evidence still zero-independent |

---

*Traceability: **ISV-1** per Authority ruling (2026-07-31, item 3 revised) — **expressly NOT an AFV: for a definition-by-reference baseline there is no derived text** · **independence defect DECLARED IN ADVANCE (author verifying own work; self-verification measured 0-for-2), mitigated by making every assertion a re-runnable mechanical comparison** · **⛔ ISV-F1 MAJOR: the Assembly Fidelity Rule was issued in PARAPHRASE while the act asserted VERBATIM — dropping "proposed against the authoritative source" and "propagated through the established publication chain"; a WEAKENING, entering at MCA-R1 and cascading through CDR-R1 into the baseline (3 sites)** · ***the rule adopted on the evidence of an eleven-site restatement cascade was itself propagated by a three-site restatement cascade — the candidate's own failure mode consumed the candidate*** · **remediation split by class: OPERATIVE baseline CORRECTED (executing CDR-R1's actual decision), its false verbatim claim RETAINED with the falsity marked, FROZEN records ANNOTATED so the cascade evidence survives** · **verifier's own prior reasoning CORRECTED: the comparison I called a tautology found the defect — a comparison is only a tautology if the texts are in fact identical, which is what the comparison exists to establish** · **§3 finding against the SPECIFICATION: "no wording strengthened" is blind in one direction; the correct assertion is "no wording ALTERED"** · **§4 ISV is an instrument USED, not a class ADMITTED — naming a verification is not admitting a construct** · **§5 AFV naming collision QUEUED per Authority ruling** · 7 of 8 assertions pass; no decision revisited; certification state unchanged.*
