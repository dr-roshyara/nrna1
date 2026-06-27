# Developer Guide: Face Verification Integration

## Constitutional Voting Trust Infrastructure

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Provider Abstraction Layer](#2-provider-abstraction-layer)
3. [Step-by-Step Integration](#3-step-by-step-integration)
4. [AWS Rekognition Integration](#4-aws-rekognition-integration)
5. [OpenCV + dlib Self-Hosted Integration](#5-opencv--dlib-self-hosted-integration)
6. [Azure Face API Integration](#6-azure-face-api-integration)
7. [Constitutional Policy Implementation](#7-constitutional-policy-implementation)
8. [Testing & Verification](#8-testing--verification)
9. [Privacy & Compliance](#9-privacy--compliance)
10. [Troubleshooting](#10-troubleshooting)

---

## 1. Architecture Overview

Face verification integrates as a **constitutional evidence type** - it follows the same pattern as IP binding and device fingerprinting.

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                    FACE VERIFICATION FLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  1. Election Creation (Constitutional Article)                             │
│     └── face_verification_required = true                                  │
│     └── face_verification_threshold = 0.85                                 │
│                                                                             │
│  2. Voter Verification (Officer captures face)                              │
│     └── FaceVerificationProvider::verify()                                 │
│     └── FaceVerificationEvidence stored in VoterVerification               │
│                                                                             │
│  3. Vote Time (Evidence evaluated)                                          │
│     └── FaceVerificationPolicy::evaluate()                                 │
│     └── ConstitutionalFinding (concern level based on match)               │
│     └── Resolver derives legitimacy                                         │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 2. Provider Abstraction Layer

### 2.1 Interface Definition

```php
// app/Domain/Election/Security/FaceVerification/FaceVerificationProvider.php

<?php

namespace App\Domain\Election\Security\FaceVerification;

interface FaceVerificationProvider
{
    /**
     * Verify a face image against a registered face encoding.
     *
     * @param string $imagePath Path to the captured image file
     * @param string $registeredFaceEncoding Base64-encoded face embedding from registration
     * @return FaceVerificationResult
     * @throws FaceVerificationException
     */
    public function verify(string $imagePath, string $registeredFaceEncoding): FaceVerificationResult;
    
    /**
     * Extract face encoding from an image for storage.
     *
     * @param string $imagePath Path to the image file
     * @return string Base64-encoded face embedding
     */
    public function extractFaceEncoding(string $imagePath): string;
    
    /**
     * Check if the subject is a live person (anti-spoofing).
     *
     * @param string $imagePath Path to the image file
     * @return bool
     */
    public function isLive(string $imagePath): bool;
}
```

### 2.2 Result Value Object

```php
// app/Domain/Election/Security/FaceVerification/FaceVerificationResult.php

<?php

namespace App\Domain\Election\Security\FaceVerification;

readonly class FaceVerificationResult
{
    public function __construct(
        public float $matchConfidence,      // 0.0 - 1.0
        public bool $isLive,                // anti-spoofing result
        public string $faceIdHash,          // SHA-256 of face encoding for replay
        public ?string $errorMessage = null,
    ) {}
    
    public function isMatch(float $threshold = 0.85): bool
    {
        return $this->matchConfidence >= $threshold;
    }
    
    public function isReliable(): bool
    {
        return $this->isLive && $this->matchConfidence > 0;
    }
}
```

### 2.3 Evidence Value Object

```php
// app/Domain/Election/Security/FaceVerification/FaceVerificationEvidence.php

<?php

namespace App\Domain\Election\Security\FaceVerification;

readonly class FaceVerificationEvidence
{
    public function __construct(
        public float $matchConfidence,
        public string $faceIdHash,
        public \DateTimeImmutable $capturedAt,
        public string $captureMethod,   // 'live_camera' | 'uploaded_photo'
        public bool $isLive,
    ) {}
    
    public static function fromResult(FaceVerificationResult $result, \DateTimeImmutable $capturedAt): self
    {
        return new self(
            matchConfidence: $result->matchConfidence,
            faceIdHash: $result->faceIdHash,
            capturedAt: $capturedAt,
            captureMethod: 'live_camera',
            isLive: $result->isLive,
        );
    }
    
    public function isMatch(float $threshold = 0.85): bool
    {
        return $this->matchConfidence >= $threshold;
    }
}
```

---

## 3. Step-by-Step Integration

### 3.1 Database Migration (Add Face Verification Columns)

```php
// database/migrations/2026_05_30_000001_add_face_verification_to_elections.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->boolean('face_verification_required')->default(false)->after('voter_verification_mode');
            $table->float('face_verification_threshold')->default(0.85)->after('face_verification_required');
        });
        
        Schema::table('voter_verifications', function (Blueprint $table) {
            $table->json('face_verification_evidence')->nullable()->after('device_fingerprint');
            $table->float('face_match_confidence')->nullable()->after('face_verification_evidence');
            $table->string('face_id_hash', 64)->nullable()->after('face_match_confidence');
        });
    }
    
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['face_verification_required', 'face_verification_threshold']);
        });
        
        Schema::table('voter_verifications', function (Blueprint $table) {
            $table->dropColumn(['face_verification_evidence', 'face_match_confidence', 'face_id_hash']);
        });
    }
};
```

### 3.2 Update Constitutional Articles

```php
// app/Domain/Election/Constitution/ElectionConstitution.php

const SECURITY_ARTICLES = [
    // ... existing articles ...
    'face_verification' => [
        'required' => 'boolean',
        'threshold' => 'float|min:0|max:1',
        'protocol' => ['live_camera', 'uploaded_photo'],
    ],
];
```

### 3.3 Update Election Settings UI

```vue
<!-- resources/js/Pages/Elections/Settings/Index.vue - Add Face Verification Section -->

<div class="border rounded-lg p-4 mt-6">
  <h3 class="text-lg font-medium">Face Verification</h3>
  
  <label class="flex items-center mt-2">
    <input type="checkbox" v-model="form.face_verification_required">
    <span class="ml-2">Require face verification</span>
  </label>
  
  <div v-if="form.face_verification_required" class="mt-4 pl-6">
    <label class="block text-sm font-medium mb-2">
      Match threshold ({{ Math.round(form.face_verification_threshold * 100) }}%)
    </label>
    <input 
      type="range" 
      v-model.number="form.face_verification_threshold"
      min="0.5" 
      max="0.99" 
      step="0.01"
      class="w-64"
    />
    <p class="text-xs text-gray-500 mt-1">
      Lower = more lenient, Higher = more strict
    </p>
  </div>
</div>
```

### 3.4 Update ConstitutionalArticlesSnapshot

```php
// app/Domain/Election/Constitution/ConstitutionalArticlesSnapshot.php

readonly class ConstitutionalArticlesSnapshot
{
    public function __construct(
        // ... existing properties ...
        public bool $faceVerificationRequired,
        public float $faceVerificationThreshold,
    ) {}
    
    public static function fromElection(Election $election): self
    {
        return new self(
            // ... existing ...
            faceVerificationRequired: (bool) $election->face_verification_required,
            faceVerificationThreshold: (float) $election->face_verification_threshold,
        );
    }
}
```

---

## 4. AWS Rekognition Integration

### 4.1 Install AWS SDK

```bash
composer require aws/aws-sdk-php
```

### 4.2 Configuration

```php
// config/face_verification.php

<?php

return [
    'provider' => env('FACE_VERIFICATION_PROVIDER', 'aws'),
    
    'aws' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
        'collection_id' => env('AWS_REKOGNITION_COLLECTION_ID', 'election_voters'),
    ],
    
    'threshold' => env('FACE_VERIFICATION_THRESHOLD', 85), // percentage
];
```

### 4.3 AWS Rekognition Provider Implementation

```php
// app/Infrastructure/Biometric/AwsRekognitionProvider.php

<?php

namespace App\Infrastructure\Biometric;

use Aws\Rekognition\RekognitionClient;
use App\Domain\Election\Security\FaceVerification\FaceVerificationProvider;
use App\Domain\Election\Security\FaceVerification\FaceVerificationResult;
use App\Domain\Election\Security\FaceVerification\FaceVerificationException;

final class AwsRekognitionProvider implements FaceVerificationProvider
{
    private RekognitionClient $client;
    private string $collectionId;
    private float $threshold;
    
    public function __construct()
    {
        $this->client = new RekognitionClient([
            'version' => 'latest',
            'region' => config('face_verification.aws.region'),
            'credentials' => [
                'key' => config('face_verification.aws.key'),
                'secret' => config('face_verification.aws.secret'),
            ],
        ]);
        
        $this->collectionId = config('face_verification.aws.collection_id');
        $this->threshold = config('face_verification.threshold', 85);
    }
    
    public function verify(string $imagePath, string $registeredFaceEncoding): FaceVerificationResult
    {
        try {
            // Decode the stored face encoding
            $faceId = $registeredFaceEncoding;
            
            // Search for face in collection
            $imageBlob = base64_encode(file_get_contents($imagePath));
            
            $result = $this->client->searchFacesByImage([
                'CollectionId' => $this->collectionId,
                'Image' => ['Bytes' => $imageBlob],
                'FaceMatchThreshold' => $this->threshold,
                'MaxFaces' => 1,
            ]);
            
            $faceMatches = $result['FaceMatches'] ?? [];
            
            if (empty($faceMatches)) {
                return new FaceVerificationResult(
                    matchConfidence: 0.0,
                    isLive: false,
                    faceIdHash: '',
                    errorMessage: 'No matching face found'
                );
            }
            
            $match = $faceMatches[0];
            $confidence = $match['Similarity'] / 100;
            
            // Liveness detection via additional call
            $isLive = $this->detectLiveness($imageBlob);
            
            return new FaceVerificationResult(
                matchConfidence: $confidence,
                isLive: $isLive,
                faceIdHash: hash('sha256', $match['Face']['FaceId']),
            );
            
        } catch (\Exception $e) {
            throw new FaceVerificationException(
                "AWS Rekognition verification failed: " . $e->getMessage(),
                previous: $e
            );
        }
    }
    
    public function extractFaceEncoding(string $imagePath): string
    {
        $imageBlob = base64_encode(file_get_contents($imagePath));
        
        $result = $this->client->indexFaces([
            'CollectionId' => $this->collectionId,
            'Image' => ['Bytes' => $imageBlob],
            'ExternalImageId' => uniqid('face_', true),
        ]);
        
        $faceRecords = $result['FaceRecords'] ?? [];
        
        if (empty($faceRecords)) {
            throw new FaceVerificationException('No face detected in image');
        }
        
        return $faceRecords[0]['Face']['FaceId'];
    }
    
    public function isLive(string $imagePath): bool
    {
        return $this->detectLiveness(base64_encode(file_get_contents($imagePath)));
    }
    
    private function detectLiveness(string $imageBlob): bool
    {
        // AWS Rekognition doesn't have native liveness detection
        // Use additional service or fallback to true
        // For production, consider Amazon Rekognition Face Liveness
        return true;
    }
}
```

### 4.4 Initialize AWS Collection (One-time Setup)

```bash
# Create collection in AWS Rekognition
aws rekognition create-collection --collection-id election_voters --region us-east-1
```

---

## 5. OpenCV + dlib Self-Hosted Integration

### 5.1 Install Python Dependencies

```bash
pip install face-recognition opencv-python numpy
```

### 5.2 Python Bridge Script

```python
# scripts/face_verify.py

#!/usr/bin/env python3
import face_recognition
import sys
import json
import base64
import numpy as np
import cv2

def detect_liveness(image_path):
    """Basic liveness detection using eye blink detection."""
    # Simplified - production would use more sophisticated methods
    return True

def extract_face_encoding(image_path):
    """Extract face encoding from image."""
    image = face_recognition.load_image_file(image_path)
    encodings = face_recognition.face_encodings(image)
    
    if not encodings:
        return None
    
    return encodings[0].tolist()

def compare_faces(image_path, registered_encoding_json):
    """Compare captured face with registered encoding."""
    # Load and decode registered encoding
    registered_encoding = np.array(json.loads(registered_encoding_json))
    
    # Extract encoding from captured image
    captured_encoding = extract_face_encoding(image_path)
    
    if captured_encoding is None:
        return {'confidence': 0, 'is_live': False, 'face_encoding': ''}
    
    # Calculate face distance (lower = more similar)
    face_distances = face_recognition.face_distance([registered_encoding], captured_encoding)
    confidence = 1 - face_distances[0]
    
    # Clamp to 0-1 range
    confidence = max(0, min(1, confidence))
    
    is_live = detect_liveness(image_path)
    
    return {
        'confidence': confidence,
        'is_live': is_live,
        'face_encoding': json.dumps(captured_encoding)
    }

if __name__ == "__main__":
    command = sys.argv[1] if len(sys.argv) > 1 else 'compare'
    image_path = sys.argv[2] if len(sys.argv) > 2 else ''
    
    if command == 'extract':
        encoding = extract_face_encoding(image_path)
        if encoding is None:
            print(json.dumps({'error': 'No face detected'}))
            sys.exit(1)
        print(json.dumps({'face_encoding': json.dumps(encoding)}))
        
    elif command == 'compare':
        registered_json = sys.argv[3] if len(sys.argv) > 3 else '[]'
        result = compare_faces(image_path, registered_json)
        print(json.dumps(result))
    
    elif command == 'liveness':
        is_live = detect_liveness(image_path)
        print(json.dumps({'is_live': is_live}))
```

### 5.3 PHP Bridge Implementation

```php
// app/Infrastructure/Biometric/OpenCvFaceProvider.php

<?php

namespace App\Infrastructure\Biometric;

use App\Domain\Election\Security\FaceVerification\FaceVerificationProvider;
use App\Domain\Election\Security\FaceVerification\FaceVerificationResult;
use App\Domain\Election\Security\FaceVerification\FaceVerificationException;

final class OpenCvFaceProvider implements FaceVerificationProvider
{
    private string $pythonPath;
    private string $scriptPath;
    
    public function __construct()
    {
        $this->pythonPath = env('PYTHON_PATH', 'python3');
        $this->scriptPath = base_path('scripts/face_verify.py');
    }
    
    public function verify(string $imagePath, string $registeredFaceEncoding): FaceVerificationResult
    {
        $command = sprintf(
            '%s %s compare %s %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($this->scriptPath),
            escapeshellarg($imagePath),
            escapeshellarg($registeredFaceEncoding)
        );
        
        $output = shell_exec($command);
        $result = json_decode($output, true);
        
        if ($result === null || isset($result['error'])) {
            throw new FaceVerificationException(
                $result['error'] ?? 'Face verification failed: ' . $output
            );
        }
        
        return new FaceVerificationResult(
            matchConfidence: (float) $result['confidence'],
            isLive: (bool) $result['is_live'],
            faceIdHash: hash('sha256', $result['face_encoding'] ?? ''),
        );
    }
    
    public function extractFaceEncoding(string $imagePath): string
    {
        $command = sprintf(
            '%s %s extract %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($this->scriptPath),
            escapeshellarg($imagePath)
        );
        
        $output = shell_exec($command);
        $result = json_decode($output, true);
        
        if ($result === null || isset($result['error'])) {
            throw new FaceVerificationException(
                $result['error'] ?? 'Face extraction failed: ' . $output
            );
        }
        
        return $result['face_encoding'];
    }
    
    public function isLive(string $imagePath): bool
    {
        $command = sprintf(
            '%s %s liveness %s 2>&1',
            escapeshellcmd($this->pythonPath),
            escapeshellarg($this->scriptPath),
            escapeshellarg($imagePath)
        );
        
        $output = shell_exec($command);
        $result = json_decode($output, true);
        
        return $result['is_live'] ?? false;
    }
}
```

---

## 6. Azure Face API Integration

### 6.1 Install Azure SDK

```bash
composer require microsoft/azure-storage-blob
# or use Guzzle for direct REST calls
composer require guzzlehttp/guzzle
```

### 6.2 Azure Face API Provider

```php
// app/Infrastructure/Biometric/AzureFaceProvider.php

<?php

namespace App\Infrastructure\Biometric;

use GuzzleHttp\Client;
use App\Domain\Election\Security\FaceVerification\FaceVerificationProvider;
use App\Domain\Election\Security\FaceVerification\FaceVerificationResult;
use App\Domain\Election\Security\FaceVerification\FaceVerificationException;

final class AzureFaceProvider implements FaceVerificationProvider
{
    private Client $http;
    private string $endpoint;
    private string $key;
    
    public function __construct()
    {
        $this->http = new Client();
        $this->endpoint = env('AZURE_FACE_ENDPOINT');
        $this->key = env('AZURE_FACE_KEY');
    }
    
    public function verify(string $imagePath, string $registeredFaceId): FaceVerificationResult
    {
        $imageBlob = base64_encode(file_get_contents($imagePath));
        
        // Detect face in captured image
        $detectResponse = $this->http->post($this->endpoint . '/face/v1.0/detect', [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => ['url' => $this->uploadToBlob($imagePath)], // or send base64
        ]);
        
        $detectData = json_decode($detectResponse->getBody(), true);
        
        if (empty($detectData)) {
            return new FaceVerificationResult(0.0, false, '');
        }
        
        $capturedFaceId = $detectData[0]['faceId'];
        
        // Verify against registered face
        $verifyResponse = $this->http->post($this->endpoint . '/face/v1.0/verify', [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'faceId1' => $registeredFaceId,
                'faceId2' => $capturedFaceId,
            ],
        ]);
        
        $verifyData = json_decode($verifyResponse->getBody(), true);
        
        return new FaceVerificationResult(
            matchConfidence: $verifyData['confidence'] ?? 0,
            isLive: $this->detectLiveness($detectData[0]),
            faceIdHash: hash('sha256', $capturedFaceId),
        );
    }
    
    public function extractFaceEncoding(string $imagePath): string
    {
        $imageBlob = base64_encode(file_get_contents($imagePath));
        
        $response = $this->http->post($this->endpoint . '/face/v1.0/detect', [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => ['url' => $this->uploadToBlob($imagePath)],
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        if (empty($data)) {
            throw new FaceVerificationException('No face detected');
        }
        
        return $data[0]['faceId'];
    }
    
    public function isLive(string $imagePath): bool
    {
        // Azure Face API can detect liveness via HeadPose and facial landmarks
        $imageBlob = base64_encode(file_get_contents($imagePath));
        
        $response = $this->http->post($this->endpoint . '/face/v1.0/detect', [
            'headers' => [
                'Ocp-Apim-Subscription-Key' => $this->key,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'url' => $this->uploadToBlob($imagePath),
                'returnFaceAttributes' => 'headPose',
            ],
        ]);
        
        $data = json_decode($response->getBody(), true);
        
        if (empty($data)) {
            return false;
        }
        
        $headPose = $data[0]['faceAttributes']['headPose'] ?? null;
        
        // Basic liveness: face should have some angle variation
        return abs($headPose['roll'] ?? 0) < 30;
    }
    
    private function uploadToBlob(string $imagePath): string
    {
        // Upload to temporary Azure Blob Storage and return URL
        // Implementation depends on your Azure setup
        return 'https://yourstorage.blob.core.windows.net/temp/face.jpg';
    }
}
```

---

## 7. Constitutional Policy Implementation

### 7.1 Face Verification Policy

```php
// app/Application/Election/Security/Policies/FaceVerificationPolicy.php

<?php

namespace App\Application\Election\Security\Policies;

use App\Application\Election\Security\ConstitutionalPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\ConstitutionalFinding;
use App\Domain\Election\Security\EvidenceWeightCategory;
use App\Domain\Election\Security\FaceVerification\FaceVerificationEvidence;

final class FaceVerificationPolicy implements ConstitutionalPolicy
{
    public function identifier(): string
    {
        return 'face_verification';
    }
    
    public function dependencies(): array
    {
        return ['verification']; // Depends on base verification policy
    }
    
    public function evaluate(TrustCapabilityContext $ctx): ConstitutionalFinding
    {
        $constitution = $ctx->election->getConstitutionalSnapshot();
        
        // Skip if face verification not required
        if (!$constitution->faceVerificationRequired) {
            return ConstitutionalFinding::noFinding($this->identifier());
        }
        
        $verification = $ctx->attestation;
        
        // Get face evidence from stored verification record
        $faceEvidence = $verification->getFaceEvidence();
        
        if ($faceEvidence === null) {
            return new ConstitutionalFinding(
                concernLevel: ConstitutionalConcernLevel::HIGH,
                evidenceWeight: EvidenceWeightCategory::DEFINITIVE,
                constitutionalBasis: 'face_verification_missing',
                supportingFacts: [
                    'required' => true,
                    'present' => false,
                ],
                policyIdentifier: $this->identifier(),
            );
        }
        
        $threshold = $constitution->faceVerificationThreshold;
        
        if (!$faceEvidence->isMatch($threshold)) {
            return new ConstitutionalFinding(
                concernLevel: ConstitutionalConcernLevel::CRITICAL,
                evidenceWeight: EvidenceWeightCategory::STRONG,
                constitutionalBasis: 'face_verification_failed',
                supportingFacts: [
                    'confidence' => $faceEvidence->matchConfidence,
                    'threshold' => $threshold,
                    'is_live' => $faceEvidence->isLive,
                ],
                policyIdentifier: $this->identifier(),
            );
        }
        
        if (!$faceEvidence->isLive) {
            return new ConstitutionalFinding(
                concernLevel: ConstitutionalConcernLevel::HIGH,
                evidenceWeight: EvidenceWeightCategory::MODERATE,
                constitutionalBasis: 'face_liveness_check_failed',
                supportingFacts: [
                    'confidence' => $faceEvidence->matchConfidence,
                    'is_live' => false,
                ],
                policyIdentifier: $this->identifier(),
            );
        }
        
        return ConstitutionalFinding::noFinding($this->identifier());
    }
}
```

### 7.2 Update PolicySequence

```php
// app/Application/Election/Security/PolicySequence.php

final class PolicySequence
{
    public function __construct(
        private VerificationAttestationPolicy $verificationPolicy,
        private FaceVerificationPolicy $faceVerificationPolicy,  // ← ADD
        private NetworkBindingPolicy $networkPolicy,
        private DeviceBindingPolicy $devicePolicy,
    ) {}
    
    public function evaluate(TrustCapabilityContext $ctx): VotingTrustResult
    {
        // Step 1: Base verification
        $verificationFinding = $this->verificationPolicy->evaluate($ctx);
        
        if ($verificationFinding->hasConstitutionalConcern()) {
            return $this->createInsufficientResult($verificationFinding);
        }
        
        // Step 1.5: Face verification (if required)
        $faceFinding = $this->faceVerificationPolicy->evaluate($ctx);
        
        if ($faceFinding->hasConstitutionalConcern()) {
            return $this->createInsufficientResult($faceFinding);
        }
        
        // Step 2: Network binding
        // Step 3: Device binding
        // ... rest unchanged
    }
}
```

### 7.3 Update Service Provider

```php
// app/Providers/AppServiceProvider.php

$this->app->singleton(FaceVerificationProvider::class, function ($app) {
    // Choose provider based on config
    $provider = config('face_verification.provider', 'aws');
    
    return match($provider) {
        'aws' => new AwsRekognitionProvider(),
        'azure' => new AzureFaceProvider(),
        'opencv' => new OpenCvFaceProvider(),
        default => throw new \RuntimeException("Unknown face verification provider: {$provider}"),
    };
});

$this->app->singleton(PolicySequence::class, function ($app) {
    return new PolicySequence(
        verificationPolicy: new VerificationAttestationPolicy(),
        faceVerificationPolicy: new FaceVerificationPolicy(),  // ← ADD
        networkPolicy: new NetworkBindingPolicy(),
        devicePolicy: new DeviceBindingPolicy(),
    );
});
```

---

## 8. Testing & Verification

### 8.1 Unit Test for Face Verification Policy

```php
// tests/Unit/Application/Election/Security/Policies/FaceVerificationPolicyTest.php

<?php

namespace Tests\Unit\Application\Election\Security\Policies;

use App\Application\Election\Security\Policies\FaceVerificationPolicy;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Constitution\ConstitutionalArticlesSnapshot;
use App\Domain\Election\Security\FaceVerification\FaceVerificationEvidence;
use App\Domain\Election\Security\VerificationAttestationRecord;
use PHPUnit\Framework\TestCase;

class FaceVerificationPolicyTest extends TestCase
{
    private FaceVerificationPolicy $policy;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new FaceVerificationPolicy();
    }
    
    /** @test */
    public function it_returns_no_concern_when_not_required(): void
    {
        $constitution = $this->createMock(ConstitutionalArticlesSnapshot::class);
        $constitution->faceVerificationRequired = false;
        
        $finding = $this->policy->evaluate($this->createContext($constitution));
        
        $this->assertFalse($finding->hasConstitutionalConcern());
    }
    
    /** @test */
    public function it_returns_concern_when_face_evidence_missing(): void
    {
        $constitution = $this->createMock(ConstitutionalArticlesSnapshot::class);
        $constitution->faceVerificationRequired = true;
        $constitution->faceVerificationThreshold = 0.85;
        
        $attestation = $this->createMock(VerificationAttestationRecord::class);
        $attestation->method('getFaceEvidence')->willReturn(null);
        
        $finding = $this->policy->evaluate($this->createContext($constitution, $attestation));
        
        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals('face_verification_missing', $finding->constitutionalBasis);
    }
    
    /** @test */
    public function it_returns_concern_when_face_match_below_threshold(): void
    {
        $constitution = $this->createMock(ConstitutionalArticlesSnapshot::class);
        $constitution->faceVerificationRequired = true;
        $constitution->faceVerificationThreshold = 0.85;
        
        $evidence = new FaceVerificationEvidence(0.70, 'hash', new \DateTimeImmutable(), 'live_camera', true);
        
        $attestation = $this->createMock(VerificationAttestationRecord::class);
        $attestation->method('getFaceEvidence')->willReturn($evidence);
        
        $finding = $this->policy->evaluate($this->createContext($constitution, $attestation));
        
        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals('face_verification_failed', $finding->constitutionalBasis);
    }
    
    /** @test */
    public function it_returns_no_concern_when_face_matches_and_live(): void
    {
        $constitution = $this->createMock(ConstitutionalArticlesSnapshot::class);
        $constitution->faceVerificationRequired = true;
        $constitution->faceVerificationThreshold = 0.85;
        
        $evidence = new FaceVerificationEvidence(0.95, 'hash', new \DateTimeImmutable(), 'live_camera', true);
        
        $attestation = $this->createMock(VerificationAttestationRecord::class);
        $attestation->method('getFaceEvidence')->willReturn($evidence);
        
        $finding = $this->policy->evaluate($this->createContext($constitution, $attestation));
        
        $this->assertFalse($finding->hasConstitutionalConcern());
    }
    
    /** @test */
    public function it_returns_concern_when_face_matches_but_not_live(): void
    {
        $constitution = $this->createMock(ConstitutionalArticlesSnapshot::class);
        $constitution->faceVerificationRequired = true;
        $constitution->faceVerificationThreshold = 0.85;
        
        $evidence = new FaceVerificationEvidence(0.95, 'hash', new \DateTimeImmutable(), 'live_camera', false);
        
        $attestation = $this->createMock(VerificationAttestationRecord::class);
        $attestation->method('getFaceEvidence')->willReturn($evidence);
        
        $finding = $this->policy->evaluate($this->createContext($constitution, $attestation));
        
        $this->assertTrue($finding->hasConstitutionalConcern());
        $this->assertEquals('face_liveness_check_failed', $finding->constitutionalBasis);
    }
}
```

### 8.2 Integration Test

```php
// tests/Feature/Election/FaceVerificationIntegrationTest.php

<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaceVerificationIntegrationTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function vote_blocked_when_face_verification_fails(): void
    {
        // Create election with face verification required
        $election = Election::factory()->create([
            'face_verification_required' => true,
            'face_verification_threshold' => 0.85,
        ]);
        
        // Create user with face verification FAILED
        $user = User::factory()->create();
        $verification = VoterVerification::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'face_verification_evidence' => json_encode([
                'match_confidence' => 0.60,
                'is_live' => true,
            ]),
        ]);
        
        // Attempt to vote
        $response = $this->actingAs($user)->post(route('vote.store', $election->slug), [
            'candidates' => [1, 2, 3],
        ]);
        
        // Should be denied
        $response->assertSessionHasErrors(['vote' => 'Face verification failed']);
    }
    
    /** @test */
    public function vote_allowed_when_face_verification_passes(): void
    {
        // Create election with face verification required
        $election = Election::factory()->create([
            'face_verification_required' => true,
            'face_verification_threshold' => 0.85,
            'status' => 'voting_active',
        ]);
        
        // Create user with face verification PASSED
        $user = User::factory()->create();
        $verification = VoterVerification::factory()->create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'face_verification_evidence' => json_encode([
                'match_confidence' => 0.95,
                'is_live' => true,
            ]),
        ]);
        
        // Create vote data
        // ... setup candidates, codes, etc.
        
        // Attempt to vote
        $response = $this->actingAs($user)->post(route('vote.store', $election->slug), [
            'candidates' => [1, 2, 3],
        ]);
        
        // Should succeed
        $response->assertRedirect();
        $this->assertDatabaseHas('votes', ['user_id' => $user->id]);
    }
}
```

---

## 9. Privacy & Compliance

### 9.1 GDPR Compliance Notes

```php
// app/Infrastructure/Biometric/Privacy/FaceDataPrivacyPolicy.php

<?php

namespace App\Infrastructure\Biometric\Privacy;

final class FaceDataPrivacyPolicy
{
    /**
     * Face images are NOT stored permanently.
     * Only face embeddings (hashed) are stored.
     * Raw images deleted after processing.
     */
    public static function processTemporaryImage(string $imagePath): void
    {
        // Process image
        // ... extract face encoding
        
        // Delete raw image immediately
        unlink($imagePath);
    }
    
    /**
     * Face embeddings are hashed and election-salted.
     * Cannot be reverse-engineered to reconstruct original face.
     */
    public static function hashFaceEncoding(string $encoding, int $electionId): string
    {
        return hash('sha256', $encoding . $electionId . config('app.key'));
    }
    
    /**
     * Retention policy: face evidence deleted after election closes + 30 days.
     */
    public static function shouldRetainFaceEvidence(Election $election): bool
    {
        $electionClosedAt = $election->closed_at;
        
        if ($electionClosedAt === null) {
            return true;
        }
        
        $retentionDeadline = $electionClosedAt->copy()->addDays(30);
        
        return now()->lessThan($retentionDeadline);
    }
}
```

### 9.2 Consent Management

```vue
<!-- resources/js/Pages/Voter/Consent.vue -->
<template>
  <div class="consent-container">
    <h2>Face Verification Consent</h2>
    
    <div class="privacy-notice">
      <p>To vote in this election, you must consent to face verification.</p>
      <ul>
        <li>Your face image will be processed but NOT permanently stored</li>
        <li>Only a mathematical representation (face embedding) will be stored</li>
        <li>This data will be deleted 30 days after the election closes</li>
        <li>Your data will not be shared with third parties</li>
      </ul>
    </div>
    
    <label class="flex items-center mt-4">
      <input type="checkbox" v-model="consentGiven">
      <span class="ml-2">I consent to face verification for voting in this election</span>
    </label>
    
    <button 
      @click="submit" 
      :disabled="!consentGiven"
      class="mt-4 btn btn-primary"
    >
      Proceed to Verification
    </button>
  </div>
</template>
```

---

## 10. Troubleshooting

### Common Issues & Solutions

| Issue | Likely Cause | Solution |
|-------|--------------|----------|
| "No face detected" | Poor lighting, angle, or occlusion | Add user guidance for better photo capture |
| Low match confidence | Different appearance (glasses, beard) | Store multiple face encodings per user |
| High false positive rate | Threshold too low | Increase threshold to 0.90-0.95 |
| Slow verification | Network latency (AWS/API) | Use self-hosted OpenCV for production |
| Liveness detection false | Photo attack | Upgrade to provider with better liveness |

### Performance Optimization

```php
// Cache face encodings to avoid repeated API calls
Cache::remember("face_encoding:{$userId}", 3600, function () use ($userId) {
    return $this->provider->extractFaceEncoding($user->face_image_path);
});
```

### Logging & Monitoring

```php
// Log verification attempts for audit
Log::channel('security')->info('Face verification attempted', [
    'user_id' => $userId,
    'election_id' => $electionId,
    'match_confidence' => $result->matchConfidence,
    'is_live' => $result->isLive,
    'success' => $result->isMatch(),
]);
```

---

## Summary

| Step | What to Do |
|------|------------|
| 1 | Choose provider (AWS, Azure, OpenCV) |
| 2 | Run database migration |
| 3 | Implement provider class |
| 4 | Add constitutional policy |
| 5 | Update PolicySequence |
| 6 | Add UI for election settings |
| 7 | Add voter verification UI |
| 8 | Write tests |
| 9 | Deploy |

**The constitutional engine handles the rest - no changes to resolver, overlays, or core sovereignty logic needed.**