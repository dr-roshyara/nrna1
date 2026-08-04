# 💡 AI Engineering Orchestration

**Status: VISION** *(see the folder README — nothing here is architecture or a decision)*

## The idea

Today's workflow is manual: *Human (ARB) ⇄ ChatGPT (architecture/review) ⇄ Claude (implementation) ⇄ Git*, glued by copy-paste. The vision: an orchestration layer assigns work, routes artifacts between AI workers, collects results, detects disagreements, escalates only governance questions to the human — **KnowledgeOS coordinates the AI; the human decides governance.**

**The five-year sketch (kept so it isn't forgotten — no authority):**

```
             Human (ARB)
                  │  governance questions only
                  ▼
      AI Engineering Orchestrator
   ┌────────┬───────────┬─────────┐
Architecture Coding    Testing   Research
   Agent     Agent      Agent     Agent
   └────────┴─────┬─────┴─────────┘
                  ▼
          Knowledge Memory → Operational Evidence → Human Decision Authority
```

**Four-layer reading:** AI models → AI workers (roles) → AI orchestration (routing/retry/escalation) → knowledge governance (KnowledgeOS). *Layer 3 is NOT KnowledgeOS — KnowledgeOS governs knowledge; orchestration governs AI work. A future bounded context, at most.*

## Why interesting

The manual workflow works but does not scale; every handoff is human time. If routing patterns prove stable, the orchestrator is the natural extraction.

## Evidence that would promote it

Months of the **AI Workflow Observation Log** (`docs/knowledgeos/AI_Workflow_Observation_Log.md`) showing: repeated routing patterns · quantified copy-paste cost · stable review roles · which disagreements genuinely need humans. **The governing principle: automation emerges from repeatedly observed human workflows, never before they are understood.**

## Not allowed today

⛔ No orchestrator · no workflow engine · no state machine *(the N-10 bar names exactly this machinery)* · no ADR *(existing canon governs: Reference Architecture §1 · EDM automation stance · I-8; a separate AI-Orchestration ADR becomes writable only when the log shows recurring patterns AND reasoning-over-standards demonstrably fails)* · no bounded context · "AI Engineering Orchestration" = candidate name only.

## Canon guards

AIP-14 Product Primacy holds until its gate opens · KnowledgeOS stays consumer, never producer · one workflow pattern observed so far (n=1: Claude implements, GPT reviews) — one pattern is not an architecture.
