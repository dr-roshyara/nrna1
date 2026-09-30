# evidence-integrity-provenance-authenticity-algebra

**Scope(s):** OBJECT · **Row count:** 49 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A(E)=(I,Au,Auth,R,Ind,T,S)`; `Descendants(E)`; `E=(Content,Hash,Origin,Producer,Timestamp,Signature,Context,Provenance)`; `Integrity!=Authenticity!=Authority!=Truth`
**Aliases:** "Evidence Integrity, Cryptographic Provenance, Authenticity, Tamper Detection and Chain of Custody"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0022, scope OBJECT: "S0917's Step 25Y: the fully worked cryptographic-provenance/chain-of-custody model with the central Integrity/Authenticity/Authority/Truth four-way separation, forward/backward provenance traversal, and an explicit no-blockchain-required conclusion."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0917 §"Integrity\neq Authenticity\neq Authority\neq Truth"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0917 §"Artifact -> Integrity, Authenticity, Authority, Truth assessment ... Each is evaluated separately"]
- CANDIDATE-FORMAL-BIRTH: [S0917 §"E=(Content,Hash,Origin,Producer,Timestamp,Signature,Context,Provenance)"]
- CANDIDATE-OPERATIONAL-BIRTH: [S0917 §"Falsification tests A-I: modified artifact->HashMismatch PASS; signed false statement->Authenticity=True,Truth=Unknown/False PASS; authenticated-unauthorized approval attempt->Authenticated=True,Authorized=False PASS; invalidated evidence->dependency graph identifies affected conclusions PASS; artif"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0917. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S0917), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0917 (×3) |
| informal_meaning | PRESENT | S0917 (×16) |
| formal_definition | PRESENT | S0917 (×7) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0917 (×2) |
| dependencies | PRESENT | S0917 (×4) |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0917 (×17) |
| examples | PRESENT | S0917 (×14) |
| warnings | PRESENT | S0917 (×2) |
| experiments | PRESENT | S0917 |
| open_questions | PRESENT | S0917 |

## Rationale

States cryptography cannot establish Truth: a signed false statement remains signed and false — cryptographic proof establishes artifact properties, not reality properties [S0917]. Additionally, States the architectural conclusion that a tamper-evident knowledge history does not require blockchain, achievable with conventional cryptographic infrastructure — blockchain should not be introduced merely because 'immutable knowledge' sounds blockchain-like [S0917]. Further, Lists the evidence-integrity layer's operations as normal-infrastructure computable, with storage/indexing/retention/access-control identified as the larger engineering challenge rather than cryptography itself [S0917].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

### Definitions (16 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917

- [S0917] States four properties often incorrectly collapsed into one: Integrity ≠ Authenticity ≠ Authority ≠ Truth. (anchor: "Integrity\neq Authenticity\neq Authority\neq Truth")
- [S0917] Defines Integrity via hash comparison, verifying content has not changed without establishing truth. (anchor: "h=Hash(x) ... h\neq h' ... Hash verifies content integrity. It does not establish truth")
- [S0917] Defines Authenticity via signature verification establishing producer identity, not statement correctness. (anchor: "Signature=Verify(PublicKey,Signature,Document) ... AuthenticProducer=KnownKeyOwner ... does not prove that the producer's statement is correct")
- [S0917] Defines Authority via Authorized(a,q,t), giving Authentication ≠ Authorization and Authenticity ≠ Authority. (anchor: "Authorized(a,q,t) ... an employee might be authenticated successfully but have no authority ... Authentication\neq Authorization ... Authenticity\neq Authority")
- [S0917] Defines evidence fingerprinting via hash, giving high-confidence content identity when fingerprints match. (anchor: "Fingerprint(E)=H(Content) ... Fingerprint(E_1)=Fingerprint(E_2) ... Content(E_1)=Content(E_2) with extremely high practical confidence")
- [S0917] Defines chain of custody across a five-stage evidence-transfer pipeline, each transition recorded as provenance. (anchor: "Production->Collector->Storage->KnowledgeOS->Assessment ... ChainOfCustody ... E_0\xrightarrow{capture}E_1\xrightarrow{transfer}E_2\xrightarrow{assessment}E_3 ... Each transition becomes provenance")
- [S0917] Defines digital signature/verification proving key-holder correspondence, not claim truth. (anchor: "Sign_{privateKey}(Artifact) ... Verify_{publicKey}(Artifact,Signature) ... the artifact corresponds to the holder of the signing key. It does not prove the artifact's claims are true")
- [S0917] Requires signatures to have temporal context, with SignatureValidity(t) evaluated against the certificate's validity window. (anchor: "certificate valid 2025-01-01->2026-01-01 ... SignatureValidity(t) must be evaluated against the relevant time")
- [S0917] Defines trusted timestamping as proving temporal existence of an artifact, not the truth of its contents. (anchor: "Commitment(E,t) ... this artifact existed in relation to the timestamping mechanism. It does not prove that its contents were true")
- [S0917] Defines backward provenance traversal from any conclusion to its originating source, giving EndToEndTraceability. (anchor: "C\rightarrow D\rightarrow A\rightarrow E\rightarrow Artifact\rightarrow Source ... EndToEndTraceability")
- ... plus 6 further rows in this theme (statements not individually quoted here; see `03-CONTRIBUTIONS.jsonl`): S0917, S0917, S0917, S0917, S0917, S0917

### Rationale: arguments, analysis & alternatives (3 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917, S0917

- [S0917] States cryptography cannot establish Truth: a signed false statement remains signed and false — cryptographic proof establishes artifact properties, not reality properties. (anchor: "Signed(Document)=True while Truth(Document)=False ... a signed false statement remains a signed false statement ... Cryptographic proof establishes properties of the artifact, not automatically properties of reality")
- [S0917] States the architectural conclusion that a tamper-evident knowledge history does not require blockchain, achievable with conventional cryptographic infrastructure — blockchain should not be introduced merely because 'immutable knowledge' sounds blockchain-l... (anchor: "Do we need blockchain? No ... achieve many requirements with cryptographic hashes; signatures; append-only storage; Merkle structures; trusted timestamps; access controls; immutable object storage ... not merely because immutable knowledge sounds blockchain-like")
- [S0917] Lists the evidence-integrity layer's operations as normal-infrastructure computable, with storage/indexing/retention/access-control identified as the larger engineering challenge rather than cryptography itself. (anchor: "hashing; digital-signature verification; Merkle-tree construction; provenance graph traversal; ... the computationally expensive component is generally not cryptography itself. The larger challenge is Storage+Indexing+Retention+AccessControl")

### Other concept notes (2 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917

- [S0917] Presents a four-layer epistemic model where each of Integrity, Authenticity, Authority, Truth is evaluated separately. (anchor: "Artifact -> Integrity, Authenticity, Authority, Truth assessment ... Each is evaluated separately")
- [S0917] States the anti-tampering invariant: original evidence must remain retrievable after correction/invalidation, for historical auditability. (anchor: "OriginalEvidence must remain retrievable after correction/invalidation ... historical auditability")

### Formalizations & axioms (7 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917, S0917, S0917, S0917, S0917, S0917

- [S0917] Defines an eight-field Evidence object carrying enough metadata to establish provenance. (anchor: "E=(Content,Hash,Origin,Producer,Timestamp,Signature,Context,Provenance)")
- [S0917] Defines a five-field Correction object, worked with a Nexus-version-correction example, keeping the original auditable. (anchor: "Correction=(OriginalEvidence,CorrectedEvidence,Reason,Authority,Time) ... The original remains auditable")
- [S0917] Defines a Merkle-tree commitment over an evidence collection detecting modification/deletion/insertion, with the explicit caveat MerkleIntegrity ≠ Truth. (anchor: "H_{root}=Merkle(E_1,\ldots,E_n) ... detect modification; deletion; insertion ... MerkleIntegrity\neq Truth")
- [S0917] Defines a hash chain giving tamper evidence: modifying an earlier event propagates detectably through later hashes. (anchor: "H_i=Hash(E_i\Vert H_{i-1}) ... modification of an earlier event propagates through subsequent hashes ... tamper evidence")
- [S0917] Defines a seven-dimension evidence Assessment function, more rigorous than simple trust. (anchor: "Assessment(E)=f(Integrity,Authenticity,Authority,Reliability,Independence,TemporalValidity,SemanticValidity) ... much more rigorous")
- [S0917] Defines a seven-component evidence-assessment vector A(E)=(Integrity,Authority,Authenticity,Reliability,Independence,TemporalValidity,SemanticValidity). (anchor: "A(E)=(I,Au,Auth,R,Ind,T,S) ... a multidimensional evidence assessment")
- [S0917] Defines a seven-field AI-provenance record for reproducibility and audit of AI-generated assertions. (anchor: "ModelID, ModelVersion, Prompt/InstructionContext, InputReferences, GenerationTime, ToolCalls, OutputHash ... allows reproducibility and audit")

### Governance, principles & constraints (7 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917, S0917, S0917, S0917, S0917, S0917

- [S0917] Requires the hash algorithm itself to be versioned/recorded (Hash=(Algorithm,Digest)) to avoid future migration ambiguity. (anchor: "Hash=(Algorithm,Digest) ... SHA-256:abc123 ... otherwise future migrations can create ambiguity")
- [S0917] Prefers ImmutableEvidence with corrections modeled as new events rather than silent edits, mirroring the temporal revision model. (anchor: "ImmutableEvidence ... E_{old} is not silently edited. Instead E_{old}\xrightarrow{Correction}E_{new} ... mirrors our temporal model")
- [S0917] Requires KeyID/KeyVersion tracking across key rotation so historical signatures remain interpretable. (anchor: "KeyID and KeyVersion should be tracked ... Historical signatures should not become uninterpretable merely because an organization rotated its keys")
- [S0917] Restates non-destructive invalidation: Invalid(E) ≠ Deleted(E), modeled as an Invalidate(E,t,reason) event preserving history. (anchor: "Invalid(E)\neq Deleted(E) ... Invalidate(E,t,reason) ... preserves the historical record")
- [S0917] Warns a bare score like 0.95 is ambiguous across authenticity/authority/likely-truth/freshness/relevance — VectorAssessment > SingleConfidenceScore. (anchor: "Score(E)=0.95 ... authentic? authoritative? likely true? fresh? relevant? ... A single number hides the epistemic dimensions ... VectorAssessment>SingleConfidenceScore")
- [S0917] States EvidenceSufficiency depends on decision context: a fixed evidence-strength value may be insufficient against a stricter decision threshold. (anchor: "EvidenceStrength=0.9 ... EvidenceRequirement(q)=High ... 0.9 might be insufficient ... EvidenceSufficiency depends on decision context")
- [S0917] Warns provenance completeness must respect privacy/security/access-control/data-minimization; not every prompt or raw source should be globally visible. (anchor: "privacy; security; access control; data minimization. Not every prompt or raw source should necessarily be globally visible ... Provenance completeness must coexist with information governance")

### Extensions, distinctions & restatements (9 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917, S0917

- [S0917] Worked canonicalization problem: whitespace-differing but semantically-identical documents hash differently, requiring Canonicalize(x) while keeping raw-hash and canonical-hash conceptually distinct. (anchor: "{"version":"3.70"} versus whitespace-differing JSON ... Raw hashes differ ... Canonicalize(x) before hashing ... retain the distinction between Hash(raw artifact) and Hash(canonical representation)")
- [S0917] States ArtifactIdentity ≠ SemanticIdentity: different documents can share a proposition; one document can hold multiple propositions. (anchor: "ArtifactIdentity\neq SemanticIdentity ... Two different documents may express the same proposition. Conversely, the same document may contain multiple propositions")
- [S0917] Distinguishes evaluating key validity at signing time (KeyStatus(t_signature)) from evaluating it today, relevant to later key revocation. (anchor: "Was the key valid when the artifact was signed? ... different from Is the key valid today? ... KeyStatus(t_{signature}) must be considered")
- [S0917] Presents a complete epistemic provenance graph from source through cryptographic properties to final conclusion. (anchor: "Source->Artifact->{Hash,Signature,Timestamp}->Observation->Evidence Assessment->Assertion->Derivation->Conclusion ... a complete epistemic provenance graph")
- [S0917] Places chain-of-custody as domain-owned in certain bounded contexts (compliance, legal, security, finance, governance), not merely a generic technical concern. (anchor: "Chain of custody ... not become a generic technical concern only ... compliance; legal evidence; security incidents; financial transactions; governance decisions ... the domain may define stronger custody invariants")
- [S0917] States cryptographic provenance and semantic provenance are complementary, not substitutable — authenticity says nothing about meaning. (anchor: "A signed document may be perfectly authentic. But we still need Meaning(Document) ... CryptographicProvenance and SemanticProvenance are complementary")
- [S0917] Worked example: hashing an LLM's output proves it unaltered without proving it correct — LLMOutputIntegrity ≠ LLMOutputTruth; the output still needs evidence and derivation. (anchor: "The Nexus migration requires an Architecture Board review [LLM] ... we can prove Output_{LLM} has not been altered. But that says nothing about whether the statement is correct ... LLMOutputIntegrity\neq LLMOutputTruth")
- [S0917] States Integrity does not imply Availability, requiring separately tracked IntegrityStatus and AvailabilityStatus. (anchor: "Integrity does not imply Availability ... IntegrityStatus separately from AvailabilityStatus")
- [S0917] Presents the full architectural synthesis chain with eight cross-cutting dimensions, arguing the architecture now looks 'less like a conventional knowledge graph and more like a computable epistemic system.' (anchor: "Source->Artifact->Observation->Evidence->Assessment->Assertion->Derivation->Knowledge->Model->Decision->Action ... Identity, Semantics, Time, Provenance, Authority, Uncertainty, Integrity, Causality ... less like a conventional knowledge graph and more like a computable epistemic system")

### Examples (2 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917, S0917

- [S0917] Worked impact-analysis chain tracing a discovered-wrong evidence item through invalidated assertion/derivation to a decision requiring review and a potentially-affected action. (anchor: "Impact(E_1)=Descendants(E_1) ... E1 invalidated->Assertion A invalidated->Derivation D invalidated->Decision D2 requires review->Action A3 potentially affected ... an extremely powerful operational capability")
- [S0917] Worked example showing a mixed multi-dimensional profile is more informative than a single scalar EvidenceScore. (anchor: "Integrity: HIGH, Authenticity: HIGH, Authority: LOW, Reliability: UNKNOWN, Truth: UNKNOWN ... more informative than EvidenceScore=0.72")

### Experiments (1 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917

- [S0917] Runs nine falsification tests (A-I, all PASS) against the evidence-integrity model: a modified artifact yields HashMismatch; a signed false statement yields Authenticity=True but Truth=Unknown/False; an authenticated-but-unauthorized approval attempt yields... (anchor: "Falsification tests A-I: modified artifact->HashMismatch PASS; signed false statement->Authenticity=True,Truth=Unknown/False PASS; authenticated-unauthorized approval attempt->Authenticated=True,Authorized=False PASS; invalidated evidence->dependency graph identifies affected conclusions PASS; artif")

### Validation (1 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917

- [S0917] Step 25Y self-verdict: PASS, with four boxed non-equivalences and the summary that cryptography makes epistemic history tamper-evident and attributable, not true. (anchor: "25Y — PASS ... Integrity\neq Authenticity ... Authenticity\neq Authority ... Authority\neq Truth ... Traceability\neq Correctness ... Cryptography makes the epistemic history tamper-evident and attributable. It does not magically make the history true")

### Open questions & future research (1 rows condensed into this theme; full text in `03-CONTRIBUTIONS.jsonl`)

Representative source_ids: S0917

- [S0917] Closes by posing the action/intervention/control question and transitioning to Step 25Z (Action, Intervention, Control, Risk, Feedback), naming thirteen items to formalize (preconditions, authorization, expected utility, risk, reversibility, blast radius, i... (anchor: "How does KnowledgeOS reason about actions whose consequences are uncertain, irreversible, costly, or potentially dangerous? ... Knowledge->Decision->Intervention->World->Observation->Knowledge ... Prediction\neq Intervention ... P(Y|X) does not automatically tell us P(Y|do(X))")


## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
