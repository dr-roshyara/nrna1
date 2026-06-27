# Orthogonal Sovereignty Dimensions

## Why Separation Matters

Most voting systems collapse **trust, identity, authorization, participation, and verification** into one procedural security service. This creates **hidden procedural sovereignty** — a single point where all decisions blend together, making audit, replay, and constitutional reasoning impossible.

This architecture separates them into **orthogonal sovereignty dimensions**:

```
┌──────────────────────────────────────────────────────────────┐
│                  ElectionCapabilityResolver                   │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────────┐ │
│  │  TRUST   │  │  AUTH    │  │ VERIFY   │  │  LIFECYCLE   │ │
│  │Policy    │  │Policy    │  │Policy    │  │  Policy      │ │
│  └──────────┘  └──────────┘  └──────────┘  └──────────────┘ │
│                                                              │
│  "Can this     "Is ballot    "Was officer  "Is election     │
│   participant   workflow      evidence      in valid        │
│   be trusted?"  valid?"       complete?"    state?"         │
└──────────────────────────────────────────────────────────────┘
```

Each dimension is **independent** and **non-overlapping**:
- Trust may be sufficient but authorization may fail
- Authorization may be valid but verification incomplete
- All dimensions may pass but lifecycle may deny

## BallotAuthorization (Preconditions Layer)

**Purpose:** Evaluates ballot code/token protocol validity.

**Domain concept:** A voter's ballot session has a protocol (single_code or dual_code) and associated codes. The policy checks whether the protocol requirements are met.

**Key design decisions:**
- `BallotAuthorizationPolicy` implements `CapabilityPolicy` at `CapabilityPolicyLayer::Preconditions`
- It evaluates **token workflow** only — never trust, never participation authority
- It receives ballot session data through `$context->actionMetadata['ballot_session']`
- **Does not import or reference any trust concepts**

### BallotSession States

| Protocol | View Completed | Codes Available | Result |
|----------|---------------|-----------------|--------|
| single_code | n/a | 1 unconsumed | authorized |
| single_code | n/a | 0 or consumed | prohibited |
| dual_code | no | any | prohibited |
| dual_code | yes | 2 unconsumed | authorized |
| any | n/a | no session data | abstain (non-voting) |

### Test: `independent_of_trust_state`

This is the most architecturally important test. It proves that authorization validity does NOT implicitly inherit trust legitimacy — the policy evaluates code protocol without any reference to trust evidence.

## VoterVerification (Preconditions Layer)

**Purpose:** Evaluates whether officer evidence capture is complete.

**Domain concept:** An election officer attests to the voter's identity and evidence. This is **evidence attestation**, not participation authorization.

**Key design decisions:**
- `VoterVerificationPolicy` implements `CapabilityPolicy` at `CapabilityPolicyLayer::Preconditions`
- The officer is **evidence attestation authority**, NOT voting authority
- The policy only returns `null` (abstain) or `prohibited` — it **never** returns `authorized`
- Receives verification session data through `$context->actionMetadata['verification_session']`

### VerificationSession States

| Required | Complete | Result |
|----------|----------|--------|
| false | n/a | abstain (precondition not applicable) |
| true | true | abstain (precondition met) |
| true | false | prohibited |
| no session data | n/a | abstain (non-voting action) |

### Critical Invariant

`VoterVerificationPolicy` must **never** authorize participation. It only checks if a constitutional precondition is met. Authority is always derived by `ElectionCapabilityResolver`.

## How Dimensions Combine

Each dimension produces either `null` (abstain — let others decide) or a `CapabilityDecision`. The `ElectionCapabilityResolver` collects all decisions:

```php
// Pseudocode — how the resolver evaluates
$decisions = [
    $ballotAuthPolicy->evaluate($context),      // Preconditions
    $voterVerificationPolicy->evaluate($context), // Preconditions
    $trustPolicy->evaluate($context),            // Trust
    $lifecyclePolicy->evaluate($context),        // Lifecycle
];

// Any prohibited → overall prohibited
// All abstain → abstain (let system decide default)
// Mixed → aggregate severity
```

This prevents any single dimension from becoming sovereign.
