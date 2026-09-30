# identity-name-state-representation-distinction

**Scope(s):** THEORY-LEVEL · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Identity != Name != State != Representation · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0785** [`identity-continuity-program` · `identity-name-state-representation-distinction`] — labels share the notation 'Identity != Name != State != Representation'


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0034`, scope `THEORY-LEVEL`: Step 195's foundational distinction between an entity's identity, its name(s), its state, and its representation(s) in different systems, plus the technical-ID vs domain-identity distinction and the requirement for identity-aware, provenance-preserving transitions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1412] §"Identity\neq Name\neq State\neq Representation"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1412. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1412), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1412, S1412 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1412 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1412 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1412, S1412, S1412, S1412 |
| examples | PRESENT | S1412, S1412, S1412 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1412] (ARGUMENT/EXAMPLE) The same entity may appear as many different representations (CMDB, DNS, monitoring, firewall, KnowledgeOS, Git, Jira, docs); worked example (CMDB:Nexus, DNS:nexus3.dgverlag.de, IP, container, repo, KnowledgeOS:Nexus) shows the question of whether these are six entities or one entity with six representations cannot be answered from names alone -- Representation->Entity is itself an evidence-requiring mapping.
- [S1412] (RESTATEMENT/ARGUMENT) Step 195 verdict: the architecture cannot safely be a simple CRUD model of 'things and their current status'; it requires identity-aware, temporally versioned, provenance-preserving transitions, restating the full forward chain Identity->Continuity->Lineage->State->Evidence->Knowledge->Decision->Action and the reverse observation chain Reality->Observation->Representation->IdentityAssessment->Knowledge.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1412] types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Core Step 195 distinction: an entity's Identity, its Name, its State, and its Representation(s) are four separate things; StateChange does not imply IdentityChange (worked example: NexusSystem's version changing from 3.69 to 3.70 does not change its identity)." (anchor: "Identity\neq Name\neq State\neq Representation")
- [S1412] types=[INVARIANT, EXAMPLE] scope=OBJECT — "A system's hostname changing (nexus3.dgverlag.de -> nexus-prod.dg.de) does not necessarily change the underlying entity; Name(x,t)=n_t is an attribute of Identity(x)=i, not the identity itself." (anchor: "Name is an attribute of an identity, not necessarily the identity itself.")
- [S1412] types=[ARGUMENT, EXAMPLE] scope=OBJECT — "The same entity may appear as many different representations (CMDB, DNS, monitoring, firewall, KnowledgeOS, Git, Jira, docs); worked example (CMDB:Nexus, DNS:nexus3.dgverlag.de, IP, container, repo, KnowledgeOS:Nexus) shows the question of whether these are six entities or one entity with six representations cannot be answered from names alone -- Representation->Entity is itself an evidence-requiring mapping." (anchor: "Representation\rightarrow Entity is a mapping that itself requires evidence.")
- [S1412] types=[RESTATEMENT, EXAMPLE] scope=OBJECT — "RefersTo(R_i,x) relations carry their own epistemic status (e.g. RefersTo(CMDB:Nexus,x)=Confirmed vs RefersTo(DNS:...,x)=Likely), restating the earlier principle that relationship claims are themselves knowledge claims, applied specifically to identity reference." (anchor: "Relationship claims are themselves knowledge claims.")
- [S1412] types=[PRINCIPLE, DISTINCTION] scope=OBJECT — "Recommends a stable identity key distinct from mutable attributes (Name, Location, Version, Configuration, Owner); separately warns that a technical ID (e.g. a database UUID) is not automatically a domain identity -- if a record is deleted and recreated under a new UUID, the domain may or may not regard it as the same business entity; the correct question is what makes an entity the same entity in the domain, not what the database schema says." (anchor: "ID(x)=constant while: Attributes(x,t) may vary. ... TechnicalIdentity \neq DomainIdentity.")
- [S1412] types=[RESTATEMENT, ARGUMENT] scope=THEORY-LEVEL — "Step 195 verdict: the architecture cannot safely be a simple CRUD model of 'things and their current status'; it requires identity-aware, temporally versioned, provenance-preserving transitions, restating the full forward chain Identity->Continuity->Lineage->State->Evidence->Knowledge->Decision->Action and the reverse observation chain Reality->Observation->Representation->IdentityAssessment->Knowledge." (anchor: "Identity \rightarrow Continuity \rightarrow Lineage \rightarrow State \rightarrow Evidence \rightarrow Knowledge \rightarrow Decision \rightarrow Action. ... needs identity-aware, temporally versioned, provenance-preserving transitions.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
