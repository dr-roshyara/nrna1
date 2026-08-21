---
source:
  original_name: "Yes.md"
  original_path: "docs/knowledgeos/brainstorming/Yes.md"
  detected_timestamp: "2026-08-19 10:48:04"
  timestamp_source: filesystem-mtime
  timestamp_confidence: medium
classification:
  theme: "off-topic-publicdigit"
  type: noise
  note: "PublicDigit voting-election outcome — off-topic for the KnowledgeOS architecture corpus"
status:
  authoritative: false
  proposed: false
---

Yes. I think the proposed model is strong, but I would add one important refinement:

> A scheduled voting opportunity should reach a definitive **business outcome**, but “expired” and “cancelled” should remain distinct outcomes.

That distinction matters because they carry different meanings.

## My assessment

The model correctly prevents an election from remaining indefinitely ambiguous:

\[
\text{Voting opportunity}
\rightarrow
\begin{cases}
\text{Voting started}\\
\text{Expired unused}\\
\text{Cancelled explicitly}
\end{cases}
\]

This is better than allowing a past schedule to remain reusable or allowing the system to infer that voting started merely because time passed.

It also supports auditability. Election-log guidance emphasizes recording normal and abnormal events, timestamps, outcomes, identities where applicable, and preserving original audit data rather than silently overwriting it.  The proposed protocol history is therefore directionally correct. [eac](https://www.eac.gov/sites/default/files/testing_standard/files/EAC%20Decision%20on%20Audit%20Log%20Events.pdf)

## Important refinement

I would distinguish the outcomes as follows:

| Outcome | Meaning |
|---|---|
| Voting started | The Chief requested progression, and all mandatory rules permitted it |
| Opportunity expired | The permitted period for using the schedule ended without valid progression |
| Opportunity cancelled | An authorized decision or rule deliberately terminated the opportunity before or instead of normal expiry |
| Superseded | A later schedule replaced the previous opportunity, subject to governance rules |
| Blocked or unresolved | Conditions prevent progression, but the opportunity has not yet reached its terminal point |

The last category is important. A system should not automatically mark an opportunity as expired merely because a prerequisite is missing unless the Election Rules say that the expiry boundary has been reached.

So the model should be:

\[
\text{Scheduled}
\rightarrow
\text{Available}
\rightarrow
\begin{cases}
\text{Voting started}\\
\text{Blocked / awaiting resolution}\\
\text{Expired}\\
\text{Cancelled}
\end{cases}
\]

The exact transition from “Blocked / awaiting resolution” to “Expired” remains a Governance decision.

## Do not make “no Proceed at 10:00” terminal

I agree with the caution in the proposal. The absence of a button press at exactly 10:00 should not itself be treated as cancellation.

For example, if the voting window is 10:00–12:00, Governance might decide that the Chief may validly proceed at 10:05 or 11:30. Alternatively, it might establish a shorter authorization period. Those are different rules.

The important invariant is:

\[
\text{No valid authorization}
\Rightarrow
\text{Voting cannot start}
\]

But:

\[
\text{No authorization at start time}
\not\Rightarrow
\text{Immediate expiry}
\]

unless the rules explicitly establish that consequence.

## Add a schedule version

I would also make the “new schedule” concept explicit. Each opportunity should be associated with an immutable schedule version or published schedule record.

Conceptually:

\[
O_i = (\text{schedule version}, \text{window}, \text{status}, \text{authorization history})
\]

If the schedule changes, the system should not silently mutate the old opportunity. It should either:

- create a new opportunity;
- explicitly supersede the old one; or
- record a governed correction to the existing schedule, if that is legally and operationally permitted.

In either case, the old authorization must not become valid for the new window automatically.

Thus:

\[
\text{Authorization}(O_1)
\not\Rightarrow
\text{Authorization}(O_2)
\]

This is the correct anti-reuse property.

## Protocol evidence

For each material event, the election protocol should preserve at least:

- the event type;
- the affected election and voting opportunity;
- the relevant schedule version;
- the event time and the applicable business time interpretation;
- whether the event was automatic or explicitly initiated;
- the responsible authority or process, where applicable;
- the outcome;
- the reason or failed conditions;
- the resulting status;
- references to any replacement or superseding opportunity.

The protocol should be append-only in meaning: later corrections may add an explanatory event, but should not erase the original event. That aligns with election audit-log principles requiring permanent preservation of original audit data and recording both normal and abnormal events. [eac](https://www.eac.gov/sites/default/files/testing_standard/files/EAC%20Decision%20on%20Audit%20Log%20Events.pdf)

It should also avoid recording sensitive ballot or voter information unnecessarily. Election logging standards specifically caution that audit logs must not compromise ballot secrecy, voter privacy, or system security. [eac](https://www.eac.gov/sites/default/files/testing_standard/files/EAC%20Decision%20on%20RFI%202023-01%20Logging%20of%20Ballot%20Selections_0.pdf)

## Recommended G-1 refinement

I would recommend sending Session 2 this formulation:

> Refine G-1 as follows: a published voting schedule defines a specific voting opportunity. That opportunity may become active only when the scheduled conditions and Election Rules permit progression and the Chief Election Officer provides valid authorization. The opportunity must eventually reach a definitive governed outcome, such as voting started, expired unused, cancelled, or superseded. A past or terminated opportunity cannot be reused to start voting. A later voting date requires a new or formally governed replacement schedule and a new authorization. Every material transition, failed progression attempt, termination, and resulting reason must be recorded in the election protocol. Do not decide in this commission the exact expiry boundary, cancellation authority, rescheduling powers, or technical representation.

## Final recommendation

I agree with the direction and would support including it in the governance work. My only substantive change is to avoid collapsing **expired**, **cancelled**, **superseded**, and **temporarily blocked** into one generic “did not start” result.

The strongest principle is:

> Every voting opportunity must have a governed, non-reusable lifecycle and an auditable outcome; however, the specific terminal outcome and timing must be determined by Election Rules rather than assumed by Architecture.