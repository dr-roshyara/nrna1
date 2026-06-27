# Face Verification Spike

**Phase:** Product Feature Delivery — Observation BC
**Status:** Design spike — TDD implementation follows
**Context:** First feature in the test-protected architecture (F1-F11)
**Pipeline:** Observation BC → Evidence BC → Legitimacy BC

---

## Question 1 — What Observation Is Produced?

### Decision: Three typed observations, not a scalar score

Face verification produces exactly three observation types in the Observation BC:

| Observation | Meaning | When |
|---|---|---|
| `FaceMatchedObservation` | Reference photo matches live capture | Face match succeeds within threshold |
| `FaceMismatchObservation` | Reference photo does NOT match live capture | Face match fails above threshold |
| `FaceVerificationUnavailableObservation` | Provider is down, timeout, or error | Port returns unavailable |

### Why three, not one

A single `FaceVerificationResult` with a status field is an implementation detail. The Observation BC owns **observations** — discrete facts about the world. Each outcome is a separate observation because each has different constitutional meaning (Q5).

### Enum placement

These are NOT new `OverlaySignal` variants. `OverlaySignal` is for generic constitutional signals (CONTEXT_STABLE, EVIDENCE_INCONSISTENT, etc.) that any policy can consume. Face verification observations are domain-specific evidence types.

Instead, they are a new observation type family:

```
App\Domain\Election\Observation\Verification\FaceMatchedObservation
App\Domain\Election\Observation\Verification\FaceMismatchObservation
App\Domain\Election\Observation\Verification\FaceVerificationUnavailableObservation
```

These are consumed by a new `FaceVerificationOverlay` which maps them into the `OverlaySignal[]` that feeds `ConstitutionalObservationContext`.

```
FaceMatchedObservation ──┐
                          ├──▶ FaceVerificationOverlay ──▶ OverlaySignal[] ──▶ ConstitutionalObservationContext
FaceMismatchObservation ──┘
```

### Constitutional signal mapping

| Observation | OverlaySignal |
|---|---|
| `FaceMatchedObservation` | `CONTEXT_STABLE` (evidence affirms identity) |
| `FaceMismatchObservation` | `EVIDENCE_INCONSISTENT` (evidence contradicts claim) |
| `FaceVerificationUnavailableObservation` | `CONTEXT_STABLE` with degraded provenance (observation unavailable is itself an observation) |

This means face verification naturally flows through the existing constitutional pipeline without new legitimacy logic.

---

## Question 2 — What External Port Exists?

### Decision: Port interface in Observation BC, adapters external

```php
namespace App\Domain\Election\Observation\Verification;

interface FaceVerificationPort
{
    /**
     * @param FaceReference $reference  — Reference photo (e.g., from identity document)
     * @param FaceCandidate $candidate  — Live capture (e.g., from webcam)
     * @return FaceVerificationResult   — Classified result, NOT raw confidence
     */
    public function verify(
        FaceReference $reference,
        FaceCandidate $candidate
    ): FaceVerificationResult;
}
```

### Supporting types

| Type | Role |
|---|---|
| `FaceReference` | Value object — reference image data (hash + reference to stored original) |
| `FaceCandidate` | Value object — live capture image data (temporary, deleted after verification) |
| `FaceVerificationResult` | Value object — classified outcome (MATCHED / MISMATCHED / UNAVAILABLE) + verification metadata |
| `FaceVerificationPort` | Interface — sole dependency boundary for external face providers |

### Port boundary rules

- The port returns `FaceVerificationResult`, NOT raw confidence float
- The port handles provider errors internally and returns UNAVAILABLE rather than throwing
- The port is the ONLY place where provider SDKs are imported
- Adapters live in `app/Infrastructure/FaceVerification/` — NOT in Domain or Application

### Provider adapter examples

| Provider | Adapter class |
|---|---|
| AWS Rekognition | `AwsRekognitionFaceVerificationAdapter` |
| Azure Face API | `AzureFaceVerificationAdapter` |
| Mock/Testing | `InMemoryFaceVerificationAdapter` (for tests) |

---

## Question 3 — Confidence Interpretation

### Decision: AI confidence is absorbed at the port boundary. Never leaks into domain.

The `FaceVerificationPort` interface (Q2) already enforces this: it returns `FaceVerificationResult`, not a float.

### Internal mapping (inside adapter, not exposed)

```
Provider confidence 0.0-0.39   → FaceVerificationResult::MISMATCHED
Provider confidence 0.40-0.69  → FaceVerificationResult::UNAVAILABLE (equivocal)
Provider confidence 0.70-1.0   → FaceVerificationResult::MATCHED
```

Thresholds are configurable via `config/face-verification.php` but the **classification** is structurally enforced by the result type.

### Why categorical, not continuous

The Constitutional Algebra Boundary (from C.5 doctrine) forbids scalar sovereignity:

> Constitutional observations are NOT arithmetic operands.

A float confidence score flowing through `Evidence` would be a scalar signal. `Legitimacy` could accidentally weight it. By classifying at the port boundary, the following proof holds:

```
FaceVerificationResult is always one of three discrete values.
No code path can treat it as a continuous scalar.
F1 fitness function enforces no scalar authority fields.
```

### Replay implication

`FaceVerificationResult::MATCHED` produces an identical observation regardless of:
- Which provider was used
- What the raw confidence was
- When the verification occurred

This guarantees replay determinism (F3/F6).

---

## Question 4 — Privacy Model

### Decision: Verify and discard. Store evidence, not images.

| Data | Stored? | Where | Duration |
|---|---|---|---|
| Face match result (MATCHED/MISMATCHED/UNAVAILABLE) | ✅ Yes | Evidence snapshot | Until snapshot archived |
| Verification timestamp | ✅ Yes | Evidence snapshot | Until snapshot archived |
| Provider name | ✅ Yes | Evidence metadata | Until snapshot archived |
| Reference photo hash | ✅ Yes | Evidence snapshot | Until snapshot archived |
| **Reference photo** | **❌ No** | — | Deleted after verification |
| **Live capture** | **❌ No** | — | Deleted immediately after verification |
| **Face template** | **❌ No** | — | Never stored |
| **Raw provider response** | **❌ No** | — | Logged (no PII) then discarded |

### Evidence structure

```php
final readonly class FaceVerificationEvidence
{
    public function __construct(
        public FaceVerificationResult $result,
        public FaceVerificationTimestamp $verifiedAt,
        public FaceVerificationProvider $provider,
        public FaceReferenceHash $referenceHash,
    ) {}
}
```

### Alignment with existing privacy policy

`TrustEvidencePrivacyPolicy` already covers hashing and minimization. Face verification evidence follows the same pattern — store only what is needed for constitutional replay, delete everything else.

### Legal carve-out (configurable)

If a jurisdiction requires storing verification records for audit:

```php
'face_verification' => [
    'store_reference_hash' => true,
    'store_provider_metadata' => true,
    'retention_days' => env('FACE_VERIFICATION_RETENTION_DAYS', 0), // 0 = no storage
]
```

The default is 0 (no retention). Storing face images requires explicit configuration and legal justification.

---

## Question 5 — Constitutional Meaning

### Decision: Face verification is an observation. NOT legitimacy.

The constitutional rule is absolute:

> FaceMatchedObservation does NOT mean "allowed to vote."
> FaceMismatchObservation does NOT mean "denied."

Face verification creates **observations**. Observations feed into Evidence evaluation. Evidence evaluation feeds into Legitimacy.

### Pipeline trace

```
Step 1: Observation BC
    Face verification produces FaceMatchedObservation
    ↓
Step 2: FaceVerificationOverlay
    Maps observation → OverlaySignal (CONTEXT_STABLE / EVIDENCE_INCONSISTENT)
    ↓
Step 3: ConstitutionalObservationContext
    Collects all overlay signals (face + device + network + ...)
    ↓
Step 4: Evidence BC — TrustSnapshotAssembler
    Freezes all evidence including face verification evidence
    ↓
Step 5: Evidence BC — PolicySequence
    NetworkBindingPolicy evaluates network evidence
    DeviceBindingPolicy evaluates device evidence
    VerificationAttestationPolicy evaluates face/verification evidence
    ↓
Step 6: Legitimacy BC — ConstitutionalLegitimacyDecision
    Derives LegitimacyOutcome from evaluated evidence
```

### What a face match means constitutionally

A face match strengthens the evidence set. It does not bypass evaluation.

| Scenario | Constitutional meaning |
|---|---|
| Face matched + device trusted + IP whitelisted | Strong evidence → likely Allowed |
| Face matched + device unknown + IP unknown | Mixed evidence → may require review |
| Face mismatched + device trusted | Contradictory evidence → Investigate |
| Face unavailable + device trusted | Degraded evidence → evaluate with caveat |

The **combination** matters, not any single observation.

### Invariant preserved

F4 (Resolvery Exclusivity) is structurally protected:

```
ConstitutionalLegitimacyDecision::decide()
    is the ONLY code path that produces LegitimacyOutcome.
```

No face verification code path can create `LegitimacyOutcome::Allowed` or `Denied`.

---

## Summary: The 5 Answers

| Question | Answer |
|---|---|
| What observation is produced? | `FaceMatchedObservation`, `FaceMismatchObservation`, or `FaceVerificationUnavailableObservation` — typed, discrete, not scalar |
| What external port exists? | `FaceVerificationPort` interface in Observation BC — adapters are infrastructure concern |
| Confidence interpretation? | Absorbed at port boundary. Only classified results (MATCHED/MISMATCHED/UNAVAILABLE) reach the domain |
| Privacy model? | Verify and discard. Store evidence + hash only. No raw images, no templates, no permanent storage of biometric data |
| Constitutional meaning? | **Observation, not legitimacy.** Face verification creates evidence for the constitutional pipeline. `LegitimacyOutcome` remains exclusive to `ConstitutionalLegitimacyDecision` |

---

## Next: TDD Implementation

After spike approval, tests in this order:

| Test | What it proves | F-function validated |
|---|---|---|
| `FaceMatchedObservation` is created on match | Observation creation | — |
| `FaceMismatchObservation` is created on mismatch | Observation creation | — |
| `FaceVerificationUnavailableObservation` on failure | Provider error handling | — |
| `FaceVerificationOverlay` maps to correct `OverlaySignal` | Overlay mapping | — |
| Face evidence in `EvidenceSnapshot` | Evidence preservation | F5 |
| Identical face observations → identical hash | Replay determinism | F3, F6 |
| `FaceVerificationEvidence` has no scalar authority fields | Constitutional algebra | F1 |
| Controller does NOT create `LegitimacyOutcome` from face result | Resolver exclusivity | F4, F11.1d |

---

## References

| Artifact | Relevance |
|---|---|
| `ConstitutionalContextMap.md` (P2) | Observation BC — owns observation creation |
| `ContextDependencyRules.md` (P7d) | ADR-1: Evidence → Observation |
| `CoreDomainProtection.md` | P-CORE-1: Only ConstitutionalLegitimacyDecision derives LegitimacyOutcome |
| `ObservationAggregateDiscovery.md` (P10) | Observation produces signals only |
| `F1-F11` | All pass — regression must hold |
