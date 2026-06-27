# 🏛️ CONTESTATION CONTEXT — Purpose & Role

---

## 📋 EXECUTIVE SUMMARY

**Contestation** is the bounded context responsible for **initiating, managing, and tracking electoral challenges**. It is the **entry point** for dispute resolution in the Greenfield Core.

**Core Purpose:** To provide a **structured, constitutional pathway** for challenging election decisions.

---

## 🎯 PRIMARY RESPONSIBILITY

### The Challenge Initiation Engine

```
Citizen / Observer / Authority
         │
         ▼
┌─────────────────────────────────────────────────────────────────┐
│                    CONTESTATION CONTEXT                         │
│                                                                 │
│  1. Receive Challenge (Challenge aggregate)                    │
│  2. Verify Standing (RaiserStandingRef)                       │
│  3. Validate Target (TargetRef)                               │
│  4. Manage State Machine (Raised→Admitted→Routed→Resolved)    │
│  5. Emit Events (ChallengeRaised, ChallengeAdmitted, etc.)    │
└─────────────────────────────────────────────────────────────────┘
         │
         ▼
Adjudication Context (Determination)
```

---

## 🔍 WHAT IT DOES (Domain Level)

| Responsibility | Description | Owned By |
|----------------|-------------|----------|
| **Initiate challenge** | Create a new Challenge | Challenge aggregate |
| **Verify standing** | Confirm raiser has the right to challenge | RaiserStandingRef VO |
| **Validate target** | Confirm target is challengeable | TargetRef VO |
| **Admit challenge** | Accept for adjudication | Challenge aggregate (state) |
| **Route challenge** | Send to appropriate authority | Challenge aggregate (state) |
| **Resolve challenge** | Mark as resolved via Determination | Challenge aggregate (state) |

---

## 🔍 WHAT IT DOES NOT DO

| Responsibility | Why Not | Owned By |
|----------------|---------|----------|
| **Decide outcome** | Different context | Adjudication |
| **Judge constitutionality** | Different context | Adjudication |
| **Correct elections** | Different context | Election |
| **Manage evidence** | Different context | EvidenceEnvelope |
| **Audit** | Different context | Audit |

---

## 📊 KEY CONCEPTS

### 1. Challenge (Aggregate Root)

The core concept. A formal objection to an election decision.

| Property | Type | Description |
|----------|------|-------------|
| id | ChallengeId | Unique identity |
| raiserStandingRef | RaiserStandingRef | Who is raising the challenge |
| targetRef | TargetRef | What is being challenged |
| submittedContent | SubmittedContent | The actual challenge |
| state | ChallengeState | Raised→Admitted→Routed→Resolved/Dismissed/Lapsed |

### 2. State Machine

```
                    ┌─► Admitted ──► Routed ──► Resolved
                    │
Raised ─────────────┤
                    │
                    ├─► Dismissed (rejected early)
                    │
                    └─► Lapsed (expired)
```

| State | Meaning |
|-------|---------|
| **Raised** | Challenge submitted, awaiting review |
| **Admitted** | Accepted for adjudication |
| **Routed** | Sent to appropriate authority |
| **Resolved** | Adjudicated via Determination |
| **Dismissed** | Rejected (no adjudication) |
| **Lapsed** | Expired (timed out) |

### 3. Standing Classes

Who can raise a challenge (from ADR-5):

| Class | Meaning | Example |
|-------|---------|---------|
| **S-1: Directly Affected** | Voter, candidate | Individual whose vote was affected |
| **S-2: Constitutional Observer** | Designated observer | Election monitor, ombudsman |
| **S-3: Authority Peer** | Other authority holder | Oversight body |

---

## 🏛️ RELATIONSHIP TO OTHER CONTEXTS

### Downstream: Adjudication

```
Contestation                         Adjudication
─────────────                        ────────────
Challenge ──────(ChallengeRef)──────► ChallengeRef
          ────(event)───────────────► AdjudicationService
                                    └─► Determination
```

### Downstream: Election (Reaction)

```
Contestation                         Election
─────────────                        ────────
ChallengeResolved ──(event)─────────► ElectionCorrectionApplied
                                    └─► Correction Type
```

### Downstream: Audit

```
Contestation                         Audit
─────────────                        ─────
ChallengeRaised ───(event)──────────► Audit Trail
ChallengeAdmitted ──(event)──────────► Audit Trail
ChallengeResolved ──(event)──────────► Audit Trail
```

---

## 📋 HOW IT FITS THE LAYERED MODEL

From ARB Round 11 (Layered Model):

| Layer | Concept in Contestation |
|-------|------------------------|
| **Decision Lineage** | What was challenged (Challenge) |
| **Authority** | Who challenged (RaiserStandingRef) |
| **Compliance** | Was the challenge valid (Standing) |

**The Layered Model is visible in Contestation's design.**

---

## 🔗 ADR MAPPING

| ADR | Implementation in Contestation |
|-----|-------------------------------|
| ADR-5 (Challenge Architecture) | Primary implementation |
| ADR-7 (GovernanceState) | Challenge lifecycle recorded |

---

## 💡 WHAT MAKES CONTESTATION UNIQUE

### 1. Constitutional Standing
Not everyone can challenge. Standing is constitutionally defined (S-1, S-2, S-3) and cannot be granted by an authority.

### 2. Terminal Authority Principle
The membership assembly is the terminal authority for challenges. ChallengeAdjudicationBody decisions are appealable.

### 3. One Challenge Per Decision
Challenge targets a specific decision. Once adjudicated, no further challenge on same matter.

### 4. Temporal Boundaries
Challenges have windows. Lapsing prevents stale challenges.

---

## ✅ FINAL STATEMENT

> **The Contestation context is the gateway to constitutional dispute resolution.**
>
> It receives challenges from stakeholders, validates their standing, manages the challenge lifecycle through its state machine, and passes valid challenges to Adjudication for determination. It does **not** decide outcomes—it **initiates** the process.
>
> **Core principle:** Contestation ensures *who* can challenge and *what* can be challenged. Adjudication decides *what is right*.
>
> **Contestation = the "court filing" system of the election.**

---

## 🔄 THE COMPLETE DISPUTE RESOLUTION FLOW

```
                    CONTESTATION                     ADJUDICATION
                        │                                 │
   1. Challenge Raised  │                                 │
   2. Standing Verified │                                 │
   3. State: Raised     │                                 │
                        │                                 │
   4. Admitted          │─────────────────────────────────►│
   5. State: Admitted   │                                 │
                        │                                 │
   6. Routed            │─────────────────────────────────►│
   7. State: Routed     │                                 │
                        │                                 │ 8. Issue Determination
                        │                                 │ 9. Emit DeterminationIssued
                        │                                 │
   10. Resolved         │◄─────────────────────────────────│
   11. State: Resolved  │                                 │
                        │                                 │
                        ▼                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    ELECTION (Reaction)                                   │
│  - If Upheld: ElectionCorrectionApplied                                 │
│  - If Dismissed: No correction                                           │
└─────────────────────────────────────────────────────────────────────────┘
```