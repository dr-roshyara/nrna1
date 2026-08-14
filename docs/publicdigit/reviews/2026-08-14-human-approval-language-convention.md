# Human Approval Language — Governance Design Rule (adopted operating convention)

**Type:** Governance communication convention · **Date:** 2026-08-14 · **Source:** PO design-rule instruction (delivered this date), registered verbatim in substance · **Status:** ADOPTED as operating convention, effective immediately. *(Amendment-candidate for KOS-AI-ORCH-001 if the PO later wants it as rule text — not enacted as rule here.)*

## The two languages — strictly separated

**1 · MACHINE / GOVERNANCE LANGUAGE** — workflow records, grants, transitions, provenance, evidence, authorization, audit. Precise, technical, unchanged. **The machine governance is never weakened to simplify the human interface.**

**2 · HUMAN DECISION LANGUAGE** — every request for a PO/ARB decision. **The approval request must never require understanding the orchestration mechanism.** Never ask "do you ratify seq-3?" / "is the provenance defect admissible?" / "should G-… remain authorized?" — unless the PO explicitly requests the technical decision.

## The approval-request format (mandatory)

```
Subject: Approval Request — <business/architecture subject>
Purpose: <one or two sentences — the goal>
Why it matters: <business/engineering reason>
What has been produced: <plain language>
What I am being asked to approve: <exact decision boundary>
What this approval does NOT authorize: <important exclusions>
Technical reference: <document/path with the detailed evidence>
Decision: APPROVE / AMEND / DECLINE
```

**One decision per request.**

## Truth preservation — the non-negotiable clause

The human-facing format must **never conceal technical problems**. A provenance, authorization, verification, scope, or record discrepancy that materially affects the decision is **summarized plainly** (e.g. *"There was a record-keeping problem during the work; the technical record contains the details; the work itself is presented for your decision separately"*). Never claim a defect is resolved when it is not. Never silently convert a technical defect into a business approval.

## Lifecycle decision points (each translated to its own decision language)

Governance decision → *"Should we do this?"* · Architecture decision → *"Is this the right design?"* · Implementation-boundary decision → *"Is this the exact change we want built?"* · Qualification decision → *"Does the completed result meet the requirement?"*

**Target: SIMPLE HUMAN DECISION + PRECISE MACHINE GOVERNANCE + FULL TECHNICAL AUDITABILITY.**

## Standing application to KOS-SESSION-DISCOVERY-001

**No architecture approval is requested yet.** Session 4 must first produce the fresh governed proposal; `cee1ee6b` is prior/untrusted input and is never presented as the current assignment's completed architecture. **Barred wording (registered):** *"the work itself is valid and complete"* — too strong; what is true: an earlier artifact exists, its provenance was defective, the activation was ratified, the assignment is now legitimately active, **the current architecture work is not yet complete.** When the fresh proposal lands, Governance prepares the approval request in the PO-supplied business framing (*a capability letting a terminal/worktree discover its governed assignment from the authoritative record; separates discovering from being authorized; defines safe behavior when the assignment cannot be established*) and asks exactly: **"Do you approve this architecture as the basis for implementation?" APPROVE / AMEND / DECLINE** — implementation approval never bundled.

**Traceability:** the PO design-rule instruction (2026-08-14) · the prior business-language adoption + ambiguity flag (`0caaac2d`) · the Business Decision Request seven-question structure (earlier commission) · ratification §H.1 (`4603b93d`) · fresh-proposal constraint (`c1215669`).
