# Principal DDD review — Cross-Relationship Architecture v0.4

## 1. Executive assessment

The attached document, Principal Review and Next-Step Execution: Cross-Relationship Validation Matrix v0.4, correctly moves our research from broad conceptual brainstorming toward evidence-backed domain architecture. It keeps election implementation out of scope, identifies unresolved domain relationships, and proposes ten practical validation scenarios.&#x20;

Eingefügter Text.txt



My assessment as principal reviewer is that v0.4 is a good validation plan, but it is not yet a validated architecture or a completed validation matrix.

The next step is to correct the remaining formal defects and turn the relationship hypotheses into a practical, testable architecture baseline. We should not restart the conceptual research, create more terminology without evidence, or treat all the proposed concepts as separate bounded contexts.

Our target is a digitalization platform for organizations and political parties that can support different organizational rules while preserving clear domain ownership, authorization safety, historical correctness, and tenant isolation.

The scope for this work is:

- Member and Membership
- Organization and OrganizationalUnit
- Committee and committee participation
- Roles, mandates, permissions and authorization
- Geographical hierarchy and committee jurisdiction
- Discussion forums and forum access
- Cross-domain relationships, history and policy enforcement

Election implementation, voter rolls and election-specific workflows remain out of scope.

## 2. What v0.4 gets right — and what it still gets wrong

Accepted direction

Separate facts from policy, distinguish events from states, keep organizational and geographical hierarchies distinct, and require evidence before approving business rules.

Not yet safe to freeze

The temporal correction algorithm, monotonicity invariant, stale-fact handling and several relationship ownership questions remain unresolved.

### Finding A — The authorization fallback algorithm has an implementation defect

The document correctly makes fallback dependent on the fact and the action. However, its stale-fact branch does not check `maxStaleness`, whereas the unavailable-fact branch does. A stale fact could therefore be reused beyond its permitted window.&#x20;

Eingefügter Text.txt



We need one consistent freshness contract:

- `True` and `False` mean a fact has been evaluated and has a known value at a specified time.
- `Unknown` means the system cannot establish a value from its available records.
- `Unavailable` means the authoritative source could not be consulted.
- `Stale` means the available value is too old for the applicable freshness requirement.

`Unknown`, `Unavailable` and `Stale` are not interchangeable. In particular, a known false value must not be treated as a missing value.

Architecture correction: define freshness and fallback eligibility for each fact type and action category. Enforce the freshness limit regardless of whether the status is `Unavailable` or `Stale`. For authority-sensitive actions, such as exercising a committee mandate, do not reuse expired authority facts merely because the service is unavailable.

The numerical freshness values in v0.4 are examples, not established requirements. They must be justified through security, operational and business requirements.

We must also distinguish a policy configuration error from an ordinary denial. Both should prevent unauthorized access, but a configuration error should be observable, diagnosable and escalated rather than hidden as a normal business denial.

### Finding B — The event-history model still has a logical defect

The document proposes:

\\[ \operatorname{stateOf}(s,v,k) \\]

where \\(v\\) is valid time and \\(k\\) is transaction time. This is the right general direction for answering both “what was true then?” and “what did we know then?”

However, v0.4 contains the following proposed invariant:

> `stateOf` is monotonic in transaction time because knowledge only grows.

That statement is not generally correct.&#x20;

Eingefügter Text.txt



Suppose the system believes a membership became active on 1 October. On 10 October, an authorized correction establishes that the activation should have taken effect on 5 October.

For a query about 3 October:

- At transaction time 4 October, the system may report `Active`.
- At transaction time 11 October, the corrected history may report `NotYetActive`.

The system has gained knowledge, but the answer has changed. The history of what the system believed is preserved; the result itself is not necessarily monotonic.

We must replace the proposed invariant with two separate properties:

1. Historical reproducibility: the same valid-time and transaction-time inputs, against the same retained history and rules, yield the same result.
2. Correction awareness: newly recorded corrections may change the reconstructed historical state at a fixed valid time, while prior transaction-time views remain reproducible.

The event algorithm also needs a more precise definition of how correction chains, retractions and invalidated downstream transitions are interpreted. Simply removing superseded IDs and then applying corrections is not enough unless the correction payload, precedence, validation and failure behavior are specified.

Finally, simultaneous events must not be accepted as valid solely because one has a higher version number. A version number can make ordering deterministic, but it cannot establish that the resulting business transition is legitimate.

Next action: specify the correction-aware history contract before implementing it. Test it against membership status, committee appointment and role revocation.

### Finding C — Policy precedence is configurable, but configurability needs limits

The proposed `priority-based`, `deny-wins` and `permit-wins` categories are useful candidates. They are not automatically interchangeable or suitable for every authorization domain.

For example, a forum policy may allow a moderator to restrict participation, while a committee mandate may impose a non-overridable limit on a particular action. These rules have different meanings and authority.

We should distinguish:

- Positive grant: provides a basis for permitting an action.
- Mandatory prohibition: blocks an action within its defined scope.
- Mandatory condition: must be satisfied before a grant can take effect.
- Ordinary policy decision: is resolved according to the policy's explicitly defined semantics.
- Operational failure: prevents reliable evaluation and must follow the action's safety contract.

A configurable policy must never override a platform invariant that is explicitly defined as non-configurable. Conversely, we must not declare every sensible security convention to be a universal business rule without defining its intended scope and authority.

### Finding D — The relationship model needs ownership, not just vocabulary

The v0.4 document identifies the right unresolved concepts, but it does not yet demonstrate which domain owns each fact and its lifecycle.&#x20;

Eingefügter Text.txt



That is the most important DDD task now.

For example, membership status and committee appointment status may both influence forum access, but the forum should not become the authority for either fact. It should consume the authoritative information and evaluate its own access policy.

Likewise, a person's membership in an organization should not automatically imply appointment to a committee, possession of a role, or permission to perform every action associated with that role.

We need to make these ownership and dependency rules explicit before finalizing aggregates or context boundaries.

## 3. Next deliverable: Cross-Relationship Architecture Baseline v0.4.1

I recommend the next artifact be a corrected relationship and ownership baseline, not another broad conceptual specification.

It should answer five questions for every relationship:

1. What does the relationship mean in the business?
2. Which domain owns its creation, change, expiry and correction?
3. What invariants must always hold?
4. Which other domains may depend on it, and through which contracts?
5. How do we test it under normal, exceptional and historical conditions?

### 3.1 Provisional ownership model

The following is my proposed starting point for validation. It is an architectural hypothesis, not an approved context map.

| Concept or responsibility                        | Provisional owner                               | Responsibility                                                                                          |
| ------------------------------------------------ | ----------------------------------------------- | ------------------------------------------------------------------------------------------------------- |
| Person identity and authentication identity      | Identity & Access                               | Identity representation and authentication-related facts                                                |
| Organization and organizational units            | Organization                                    | Organizational identity, structure and organizational hierarchy                                         |
| Recognized membership facts                      | Membership                                      | Membership lifecycle, type, status and organizational membership relationship                           |
| Committee participation, appointments and tenure | Governance                                      | Committee composition, appointments, positions, mandates and authority                                  |
| Roles and permission evaluation                  | Governance policy / Authorization capability    | Evaluate grants, constraints, scope and applicable facts; exact ownership split remains to be validated |
| Geographical hierarchy and jurisdiction          | Geography / Organization boundary to be decided | Represent geographical entities and their relationship to organizational responsibilities               |
| Discussion and forum content                     | Communication / Forum                           | Topics, discussions, responses, moderation and forum-specific lifecycle                                 |
| Forum access                                     | Forum policy using authoritative domain facts   | Derive access from applicable membership, appointment, role, explicit grant and policy facts            |
| Audit and historical reconstruction              | Cross-cutting audit/history capability          | Preserve evidence of important changes and support reproducible historical queries                      |

The most important ownership principle is that a domain may use another domain's facts without becoming their owner.

For example, the forum can use membership status to decide whether someone may participate. It must not independently modify membership status. Similarly, a committee appointment can grant authority within a defined scope, but it must not silently become the source of truth for whether the person is a recognized member.

### 3.2 Proposed relationship map

\#chatgpt-mermaid-\_r_7r9\_{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;fill:rgb(13, 13, 13);}@keyframes edge-animation-frame{from{stroke-dashoffset:0;}}@keyframes dash{to{stroke-dashoffset:0;}}#chatgpt-mermaid-\_r_7r9\_ .edge-animation-slow{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 50s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_7r9\_ .edge-animation-fast{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 20s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_7r9\_ .error-icon{fill:rgb(243, 243, 243);}#chatgpt-mermaid-\_r_7r9\_ .error-text{fill:rgb(13, 13, 13);stroke:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .edge-thickness-normal{stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .edge-thickness-thick{stroke-width:3.5px;}#chatgpt-mermaid-\_r_7r9\_ .edge-pattern-solid{stroke-dasharray:0;}#chatgpt-mermaid-\_r_7r9\_ .edge-thickness-invisible{stroke-width:0;fill:none;}#chatgpt-mermaid-\_r_7r9\_ .edge-pattern-dashed{stroke-dasharray:3;}#chatgpt-mermaid-\_r_7r9\_ .edge-pattern-dotted{stroke-dasharray:2;}#chatgpt-mermaid-\_r_7r9\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7r9\_ .marker.cross{stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7r9\_ svg{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;}#chatgpt-mermaid-\_r_7r9\_ p{margin:0;}#chatgpt-mermaid-\_r_7r9\_ .label{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .cluster-label text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .cluster-label span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .cluster-label span p{background-color:transparent;}#chatgpt-mermaid-\_r_7r9\_ .label text,#chatgpt-mermaid-\_r_7r9\_ span{fill:rgb(13, 13, 13);color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .node rect,#chatgpt-mermaid-\_r_7r9\_ .node circle,#chatgpt-mermaid-\_r_7r9\_ .node ellipse,#chatgpt-mermaid-\_r_7r9\_ .node polygon,#chatgpt-mermaid-\_r_7r9\_ .node path{fill:rgb(222, 234, 251);stroke:rgb(83, 154, 248);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .rough-node .label text,#chatgpt-mermaid-\_r_7r9\_ .node .label text,#chatgpt-mermaid-\_r_7r9\_ .image-shape .label,#chatgpt-mermaid-\_r_7r9\_ .icon-shape .label{text-anchor:middle;}#chatgpt-mermaid-\_r_7r9\_ .node .katex path{fill:#000;stroke:#000;stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .rough-node .label,#chatgpt-mermaid-\_r_7r9\_ .node .label,#chatgpt-mermaid-\_r_7r9\_ .image-shape .label,#chatgpt-mermaid-\_r_7r9\_ .icon-shape .label{text-align:center;}#chatgpt-mermaid-\_r_7r9\_ .node.clickable{cursor:pointer;}#chatgpt-mermaid-\_r_7r9\_ .root .anchor path{fill:rgb(143, 143, 143)!important;stroke-width:0;stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7r9\_ .arrowheadPath{fill:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7r9\_ .edgePath .path{stroke:rgb(143, 143, 143);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .flowchart-link{stroke:rgb(143, 143, 143);fill:none;}#chatgpt-mermaid-\_r_7r9\_ .edgeLabel{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_7r9\_ .edgeLabel p{background-color:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7r9\_ .edgeLabel rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7r9\_ .labelBkg{background-color:rgba(252, 252, 252, 0.5);}#chatgpt-mermaid-\_r_7r9\_ .cluster rect{fill:rgb(243, 243, 243);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .cluster text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ .cluster span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ div.mermaidTooltip{position:absolute;text-align:center;max-width:200px;padding:2px;font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:12px;background:rgb(243, 243, 243);border:1px solid rgba(0, 0, 0, 0.1);border-radius:2px;pointer-events:none;z-index:100;}#chatgpt-mermaid-\_r_7r9\_ .flowchartTitleText{text-anchor:middle;font-size:18px;fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7r9\_ rect.text{fill:none;stroke-width:0;}#chatgpt-mermaid-\_r_7r9\_ .icon-shape,#chatgpt-mermaid-\_r_7r9\_ .image-shape{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_7r9\_ .icon-shape p,#chatgpt-mermaid-\_r_7r9\_ .image-shape p{background-color:rgb(252, 252, 252);padding:2px;}#chatgpt-mermaid-\_r_7r9\_ .icon-shape .label rect,#chatgpt-mermaid-\_r_7r9\_ .image-shape .label rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7r9\_ .label-icon{display:inline-block;height:1em;overflow:visible;vertical-align:-0.125em;}#chatgpt-mermaid-\_r_7r9\_ .node .label-icon path{fill:currentColor;stroke:revert;stroke-width:revert;}#chatgpt-mermaid-\_r_7r9\_ .node .neo-node{stroke:rgb(83, 154, 248);}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node rect,#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].cluster rect,#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node polygon{stroke:url(#chatgpt-mermaid-\_r_7r9\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].swimlane.cluster rect{filter:none;}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node path{stroke:url(#chatgpt-mermaid-\_r_7r9\_-gradient);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node .outer-path{filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node .neo-line path{stroke:rgb(83, 154, 248);filter:none;}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node circle{stroke:url(#chatgpt-mermaid-\_r_7r9\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].node circle .state-start{fill:#000000;}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].icon-shape .icon{fill:url(#chatgpt-mermaid-\_r_7r9\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7r9\_ [data-look="neo"].icon-shape .icon-neo path{stroke:url(#chatgpt-mermaid-\_r_7r9\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7r9\_ .node text{font-size:14px;font-weight:600;letter-spacing:normal;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_7r9\_ .edgeLabels text{font-size:13px;font-weight:600;letter-spacing:-0.08px;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_7r9\_ .node tspan[font-weight="normal"],#chatgpt-mermaid-\_r_7r9\_ .edgeLabels tspan[font-weight="normal"]{font-weight:600;}#chatgpt-mermaid-\_r_7r9\_ .edgeLabel .label rect{opacity:1;rx:13px;ry:13px;fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .node rect,#chatgpt-mermaid-\_r_7r9\_ .node circle,#chatgpt-mermaid-\_r_7r9\_ .node ellipse,#chatgpt-mermaid-\_r_7r9\_ .node polygon,#chatgpt-mermaid-\_r_7r9\_ .node path{fill:rgb(229, 243, 255);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_7r9\_ .node rect{rx:16px;ry:16px;}#chatgpt-mermaid-\_r_7r9\_ .node.mermaid-decision .label-container{fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-dasharray:2,2;}#chatgpt-mermaid-\_r_7r9\_ .edgePaths .flowchart-link{stroke:rgb(143, 143, 143);stroke-width:1px;stroke-linecap:round;stroke-linejoin:round;}#chatgpt-mermaid-\_r_7r9\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7r9\_ :root{--mermaid-font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";}Identity & AccessOrganizationMembershipGovernanceGeography / JurisdictionAuthorization EvaluationDiscussion ForumAudit / Historical Evidenceidentifies actororganizational scopecontains units and committeesmembership factsgeographical factsmembership factsappointments, mandates, rolesorganization and scopeaccess decisioncontent and moderation eventsimportant lifecycle changesappointments and authoritychangesjurisdiction changes

This diagram is deliberately provisional. It describes dependencies, not a final decision to implement every box as a separate microservice or bounded context.

A modular monolith can preserve these boundaries through explicit application interfaces, domain ownership and controlled event or query contracts. We should not create distributed services merely because a relationship crosses a domain boundary.

### 3.3 The cross-functional relationships we must validate first

### Relationship group 1 — Member, Membership and Organization

Core question: What is the difference between a person, their recognized membership and their relationship to an organizational unit?

Required scenarios:

- One person has memberships in two organizations.
- One membership is suspended while another remains active.
- A membership is transferred, corrected or terminated.
- An organizational unit changes or ceases to exist.

Acceptance criterion: the model can represent each case without duplicating person-wide facts, conflating separate memberships or losing historical meaning.

### Relationship group 2 — Committee, Appointment, Role and Mandate

Core question: Which relationship establishes participation, and which establishes authority?

Required scenarios:

- A member participates in multiple committees with different roles.
- An appointment expires while membership remains active.
- A role is revoked but the appointment remains valid.
- A mandate changes its scope or is corrected retroactively.

Acceptance criterion: committee participation, role assignment, mandate validity and membership status remain distinguishable and are evaluated according to their own rules.

### Relationship group 3 — Role, Permission and Authorization

Core question: What does a role authorize, for which resource, within which scope and at what time?

Required scenarios:

- A role exists but no applicable permission grants the requested action.
- A permission is granted for one committee but not another.
- A mandatory prohibition applies despite an ordinary grant.
- An appointment expires while a session remains active.

Acceptance criterion: the system produces an explainable decision from authoritative facts and explicit policy, with safe handling of unknown or stale facts.

### Relationship group 4 — Geography, Organizational Units and Committees

Core question: Is geographical responsibility independent of the organizational hierarchy?

Required scenarios:

- One committee is responsible for multiple geographical areas.
- Several committees have different responsibilities in the same geographical area.
- A boundary changes while an appointment remains active.
- A committee is reorganized without changing the underlying geographical facts.

Acceptance criterion: organizational membership and geographical jurisdiction are represented separately and related explicitly, without assuming that every organizational hierarchy mirrors the geographical hierarchy.

### Relationship group 5 — Forum, Membership, Roles and Access

Core question: Is forum access derived from authoritative facts, granted explicitly, or controlled by a combination of both?

Required scenarios:

- An active member has access to the general forum but not a committee forum.
- A committee appointment grants access to a specific committee forum.
- A member loses a role but remains an active member.
- Access is revoked while a cached decision or session exists.
- A moderator's authority ends while previously created discussion content remains.

Acceptance criterion: access changes according to the approved policy, while the forum remains the owner of its discussion content and moderation lifecycle.

## 4. How we will validate the architecture

The matrix should be a decision instrument, not just documentation. Every candidate relationship rule should move through the same research process.

\#chatgpt-mermaid-\_r_7ru\_{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;fill:rgb(13, 13, 13);}@keyframes edge-animation-frame{from{stroke-dashoffset:0;}}@keyframes dash{to{stroke-dashoffset:0;}}#chatgpt-mermaid-\_r_7ru\_ .edge-animation-slow{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 50s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_7ru\_ .edge-animation-fast{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 20s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_7ru\_ .error-icon{fill:rgb(243, 243, 243);}#chatgpt-mermaid-\_r_7ru\_ .error-text{fill:rgb(13, 13, 13);stroke:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .edge-thickness-normal{stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .edge-thickness-thick{stroke-width:3.5px;}#chatgpt-mermaid-\_r_7ru\_ .edge-pattern-solid{stroke-dasharray:0;}#chatgpt-mermaid-\_r_7ru\_ .edge-thickness-invisible{stroke-width:0;fill:none;}#chatgpt-mermaid-\_r_7ru\_ .edge-pattern-dashed{stroke-dasharray:3;}#chatgpt-mermaid-\_r_7ru\_ .edge-pattern-dotted{stroke-dasharray:2;}#chatgpt-mermaid-\_r_7ru\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7ru\_ .marker.cross{stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7ru\_ svg{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;}#chatgpt-mermaid-\_r_7ru\_ p{margin:0;}#chatgpt-mermaid-\_r_7ru\_ .label{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .cluster-label text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .cluster-label span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .cluster-label span p{background-color:transparent;}#chatgpt-mermaid-\_r_7ru\_ .label text,#chatgpt-mermaid-\_r_7ru\_ span{fill:rgb(13, 13, 13);color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .node rect,#chatgpt-mermaid-\_r_7ru\_ .node circle,#chatgpt-mermaid-\_r_7ru\_ .node ellipse,#chatgpt-mermaid-\_r_7ru\_ .node polygon,#chatgpt-mermaid-\_r_7ru\_ .node path{fill:rgb(222, 234, 251);stroke:rgb(83, 154, 248);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .rough-node .label text,#chatgpt-mermaid-\_r_7ru\_ .node .label text,#chatgpt-mermaid-\_r_7ru\_ .image-shape .label,#chatgpt-mermaid-\_r_7ru\_ .icon-shape .label{text-anchor:middle;}#chatgpt-mermaid-\_r_7ru\_ .node .katex path{fill:#000;stroke:#000;stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .rough-node .label,#chatgpt-mermaid-\_r_7ru\_ .node .label,#chatgpt-mermaid-\_r_7ru\_ .image-shape .label,#chatgpt-mermaid-\_r_7ru\_ .icon-shape .label{text-align:center;}#chatgpt-mermaid-\_r_7ru\_ .node.clickable{cursor:pointer;}#chatgpt-mermaid-\_r_7ru\_ .root .anchor path{fill:rgb(143, 143, 143)!important;stroke-width:0;stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7ru\_ .arrowheadPath{fill:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7ru\_ .edgePath .path{stroke:rgb(143, 143, 143);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .flowchart-link{stroke:rgb(143, 143, 143);fill:none;}#chatgpt-mermaid-\_r_7ru\_ .edgeLabel{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_7ru\_ .edgeLabel p{background-color:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7ru\_ .edgeLabel rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7ru\_ .labelBkg{background-color:rgba(252, 252, 252, 0.5);}#chatgpt-mermaid-\_r_7ru\_ .cluster rect{fill:rgb(243, 243, 243);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .cluster text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ .cluster span{color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ div.mermaidTooltip{position:absolute;text-align:center;max-width:200px;padding:2px;font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:12px;background:rgb(243, 243, 243);border:1px solid rgba(0, 0, 0, 0.1);border-radius:2px;pointer-events:none;z-index:100;}#chatgpt-mermaid-\_r_7ru\_ .flowchartTitleText{text-anchor:middle;font-size:18px;fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_7ru\_ rect.text{fill:none;stroke-width:0;}#chatgpt-mermaid-\_r_7ru\_ .icon-shape,#chatgpt-mermaid-\_r_7ru\_ .image-shape{background-color:rgb(252, 252, 252);text-align:center;}#chatgpt-mermaid-\_r_7ru\_ .icon-shape p,#chatgpt-mermaid-\_r_7ru\_ .image-shape p{background-color:rgb(252, 252, 252);padding:2px;}#chatgpt-mermaid-\_r_7ru\_ .icon-shape .label rect,#chatgpt-mermaid-\_r_7ru\_ .image-shape .label rect{opacity:0.5;background-color:rgb(252, 252, 252);fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_7ru\_ .label-icon{display:inline-block;height:1em;overflow:visible;vertical-align:-0.125em;}#chatgpt-mermaid-\_r_7ru\_ .node .label-icon path{fill:currentColor;stroke:revert;stroke-width:revert;}#chatgpt-mermaid-\_r_7ru\_ .node .neo-node{stroke:rgb(83, 154, 248);}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node rect,#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].cluster rect,#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node polygon{stroke:url(#chatgpt-mermaid-\_r_7ru\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].swimlane.cluster rect{filter:none;}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node path{stroke:url(#chatgpt-mermaid-\_r_7ru\_-gradient);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node .outer-path{filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node .neo-line path{stroke:rgb(83, 154, 248);filter:none;}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node circle{stroke:url(#chatgpt-mermaid-\_r_7ru\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].node circle .state-start{fill:#000000;}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].icon-shape .icon{fill:url(#chatgpt-mermaid-\_r_7ru\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7ru\_ [data-look="neo"].icon-shape .icon-neo path{stroke:url(#chatgpt-mermaid-\_r_7ru\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_7ru\_ .node text{font-size:14px;font-weight:600;letter-spacing:normal;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_7ru\_ .edgeLabels text{font-size:13px;font-weight:600;letter-spacing:-0.08px;fill:rgb(0, 79, 153);}#chatgpt-mermaid-\_r_7ru\_ .node tspan[font-weight="normal"],#chatgpt-mermaid-\_r_7ru\_ .edgeLabels tspan[font-weight="normal"]{font-weight:600;}#chatgpt-mermaid-\_r_7ru\_ .edgeLabel .label rect{opacity:1;rx:13px;ry:13px;fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .node rect,#chatgpt-mermaid-\_r_7ru\_ .node circle,#chatgpt-mermaid-\_r_7ru\_ .node ellipse,#chatgpt-mermaid-\_r_7ru\_ .node polygon,#chatgpt-mermaid-\_r_7ru\_ .node path{fill:rgb(229, 243, 255);stroke:rgba(0, 0, 0, 0.1);stroke-width:1px;}#chatgpt-mermaid-\_r_7ru\_ .node rect{rx:16px;ry:16px;}#chatgpt-mermaid-\_r_7ru\_ .node.mermaid-decision .label-container{fill:rgb(245, 250, 255);stroke:rgb(206, 219, 229);stroke-dasharray:2,2;}#chatgpt-mermaid-\_r_7ru\_ .edgePaths .flowchart-link{stroke:rgb(143, 143, 143);stroke-width:1px;stroke-linecap:round;stroke-linejoin:round;}#chatgpt-mermaid-\_r_7ru\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_7ru\_ :root{--mermaid-font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";}Identify relationshiphypothesisDefine meaning and intendedscopeIdentify authoritative ownerConstruct real organizationalscenariosFind counterexamplesDoes the model explain thecases?Revise relationship or ruleSpecify invariant and testsReview evidence anddependenciesAuthorized decision?Keep open or contestedApprove, reject or scope theruleUpdate architecture baselineNoYes, provisionallyNoYes

### 4.1 Evidence and testing strategy

For each relationship, we should collect evidence from representative organizations, separating what a source says from our interpretation of it.

- Organizational evidence: constitutions, bylaws, committee rules, membership policies and forum policies.
- Formal evidence: logical invariants, valid state transitions, temporal interval constraints and authorization properties.
- Scenario evidence: positive, negative, boundary and counterexample cases.
- Implementation evidence: automated tests demonstrating that the implementation conforms to the approved rules.

One organization's policy does not automatically become a platform-wide invariant. Conversely, a platform-wide integrity invariant should not become configurable simply because an organization wants a different business process.

### 4.2 Where mathematics and machine learning fit

| Technique              | Concrete next use                                                                    |
| ---------------------- | ------------------------------------------------------------------------------------ |
| Predicate logic        | Define conditions for valid membership, appointments, roles and access               |
| Temporal logic         | Verify expiry, revocation and historical reconstruction                              |
| Property-based testing | Generate combinations of memberships, committee appointments and permission scopes   |
| Constraint solving     | Detect contradictory policies and invalid relationship combinations                  |
| Model checking         | Explore lifecycle transitions and correction edge cases                              |
| NLP / LLM              | Extract candidate rules from organizational documents, with human verification       |
| Clustering             | Identify different meanings hidden behind similar organizational terms               |
| Statistical analysis   | Compare recurring patterns across a sufficiently representative set of organizations |

ML-generated interpretations must remain hypotheses until verified. ML should not make membership, appointment, mandate or authorization decisions.

## 5. How far are we from the implementation goal?

We need to distinguish three kinds of progress.

## Maturity assessment

Conceptual research

Candidate concepts, distinctions and scenarios exist.

Baseline established

Validated DDD architecture

Ownership, invariants, evidence and context boundaries still need validation.

In progress

Implementation readiness

The current research artifact does not demonstrate that all relevant architecture decisions are ready for implementation.

Not established

The attached v0.4 document estimates 45–50% progress toward an implementable organizational domain model, explicitly as a qualitative estimate rather than a measured metric. It also states that evidence collection and falsification have not been executed.&#x20;

Eingefügter Text.txt



That percentage describes the research baseline, not necessarily the state of the existing software. Earlier project work also reported a Committee vertical slice and passing test suites. Those implementation results are valuable, but they do not by themselves prove that all cross-domain semantics and ownership decisions have been validated.&#x20;

My conclusion is that the conceptual foundation is established, but the architecture is not yet fully validated. The next measurable milestone is to validate the five relationship groups above, correct the temporal and authorization contracts, and demonstrate the results with tests. We should not invent a more precise overall completion percentage until we define measurable acceptance criteria.

## 6. Remaining TODOs

### P0 — Correct the formal specification

- Produce a corrected v0.4.1 baseline for cross-relationship architecture.
- Fix stale-fact handling so every fallback obeys its maximum staleness limit.
- Define freshness contracts for membership, appointments, mandates, roles, jurisdiction and forum policies.
- Specify which actions may use cached facts and which require fresh authoritative information.
- Correct the event-history algorithm to handle superseded events, correction chains, retractions and invalidated downstream transitions.
- Replace the incorrect monotonicity invariant with historical reproducibility and correction-awareness properties.
- Define how simultaneous state transitions are validated, not merely ordered.
- Separate ordinary denial, unavailable facts and invalid policy configuration in decision results and operational handling.

### P0 — Validate domain relationships and ownership

- Establish the precise meanings of Person, Member and Membership.
- Validate Membership ↔ Organization ↔ OrganizationalUnit.
- Separate affiliation, participation, employment and committee appointment where their semantics differ.
- Validate Committee versus OrganizationalUnit and the lifecycle of committee participation.
- Define the relationships among Appointment, Role, Mandate, Permission and AuthorizationDecision.
- Define the boundary between organizational hierarchy and geographical hierarchy.
- Establish how committees acquire geographical jurisdiction and how that jurisdiction changes over time.
- Define ForumAccessPolicy using authoritative membership, appointment and role facts.
- Decide which rules are universal platform invariants and which are configurable organizational policies.

### P0 — Populate the validation matrix

- Map the existing R-01 through R-13 rules to the five relationship groups.
- Preserve existing rule IDs and wording where possible; record revisions explicitly.
- Record source evidence, intended scope, formal invariants, counterexamples, owners and test IDs.
- Obtain representative political-party and membership-association source documents.
- Separate source facts, interpretations and proposed platform rules.
- Execute CR-01 through CR-10, recording positive, negative, boundary and counterexample results.
- Link each validated rule to an automated test or an explicitly documented reason why automation is not appropriate.
- Keep unverified rules open or contested rather than marking them approved.

### P1 — Validate temporal, authorization and forum behavior

- Test membership suspension, termination and retroactive correction.
- Test committee appointment expiry, mandate changes and role revocation.
- Test historical queries across valid time and transaction time.
- Test stale facts, unavailable authoritative services and policy conflicts.
- Test forum access after membership or role changes, including active sessions and cached decisions.
- Define audit evidence and correction authority for important relationship changes.
- Use property-based tests, constraint checks and selective model checking to explore edge cases.

### P1 — Establish architecture decision governance

- Identify who may approve platform-wide invariants and who may approve organization-specific policy.
- Establish the Open → Contested → Recommended → Approved/Rejected decision workflow.
- Maintain traceability from evidence through rules and tests to decisions.
- Define which exploratory prototypes are permitted before architecture approval.
- Record unresolved questions and their dependencies so implementation does not silently decide them.

### P2 — Finalize the DDD architecture

- Group validated concepts by lifecycle ownership and business responsibility.
- Derive bounded contexts from the validated model rather than from terminology alone.
- Define aggregate boundaries from transactional invariants and concurrency requirements.
- Validate context boundaries against multiple organizational scenarios.
- Produce the context map, domain contracts and event/query integration rules.
- Resolve identity scope and tenant isolation through explicit requirements and architecture decisions.
- Define how cross-domain projections, including forum access, remain consistent with their authoritative sources.
- Review the architecture against the existing implementation and identify the smallest safe changes needed.

### P3 — Prepare and execute implementation

- Create a dependency-ordered implementation backlog.
- Define acceptance criteria for every implementation slice.
- Implement in small DDD/TDD increments with domain, integration, temporal, authorization and tenant-isolation tests.
- Preserve traceability from approved domain rules to implementation and regression tests.
- Measure progress using validated rules, passing acceptance criteria and completed architecture decisions—not subjective percentages alone.

## Final recommendation

Our next action is to create the corrected relationship baseline and validate the five cross-functional relationship groups, starting with Member–Membership–Organization and Committee–Appointment–Role–Mandate.

These relationships establish the facts that authorization and forum access depend on. If their meaning, lifecycle and ownership are wrong, implementing more features will only spread the ambiguity.

We should then validate Role–Permission–Authorization, Geography–Committee and ForumAccessPolicy, derive the architecture from the evidence, and implement only the smallest approved slices. Election remains out of scope throughout this phase.
## ########################################################
# Principal Review and Next-Step Execution: Cross-Relationship Architecture Baseline v0.4.1

## 1. Principal Reviewer Assessment

### 1.1 What This Review Contributes

This is the most consequential review in the corpus so far. It is not a restatement and not a new specification — it is a **correction set and a governance proposal**. It accepts the direction of v0.4, identifies the remaining formal defects, and proposes a corrected baseline (v0.4.1) with the election artifacts parked.

Three contributions stand out:

1. **Two remaining formal defects identified and specified.**
   - **Authorization fallback:** the stale-fact branch does not enforce `maxStaleness`, allowing stale facts to be reused beyond their permitted window.
   - **Monotonicity invariant is wrong:** the claim that `stateOf(s, v, k)` is monotonic in transaction time is false. A correction at transaction time 10 October can change the reconstructed state at valid time 3 October. The invariant must be replaced with **historical reproducibility** and **correction awareness**.

2. **Ownership model proposed as an architectural hypothesis.** The provisional ownership table (Identity & Access, Organization, Membership, Governance, Geography, Authorization, Forum, Audit) is exactly what DDD requires: not a naming exercise, but a statement of which domain owns which fact and lifecycle.

3. **Five relationship groups identified as the highest-risk validation targets.**
   - Member ↔ Membership ↔ Organization
   - Committee ↔ Appointment ↔ Role ↔ Mandate
   - Role ↔ Permission ↔ AuthorizationDecision
   - Geography ↔ OrganizationalUnit ↔ Committee
   - Forum ↔ Membership/Role ↔ Access

This review is accepted. It becomes the basis of v0.4.1.

### 1.2 Where I Strengthen the Review

The review is strong but has four remaining weaknesses.

**Weakness 1 — The two formal defects are stated but not fully formalized.**
The review correctly identifies that stale-fact handling must enforce `maxStaleness`, but does not specify:
- How the system distinguishes `Stale` from `Unavailable` operationally.
- What happens when the authoritative source is reachable but returns an old value.
- How the freshness contract is versioned when policy changes.

**Weakness 2 — The correction-aware event algorithm is described but not specified.**
The review correctly rejects the simple sort-and-fold and the monotonicity invariant, but does not yet provide:
- The precise algorithm for effective event history with correction chains.
- How invalidated downstream transitions are detected and resolved.
- The formal properties that replace monotonicity.

**Weakness 3 — The ownership model is provisional but not yet a contract.**
The review proposes owners but does not yet specify:
- What each context must publish, and to whom.
- What each context must not do (non-responsibilities).
- How contracts are versioned.

**Weakness 4 — The validation matrix workflow is proposed but not operationalized.**
The review proposes a decision workflow (Open → Contested → Recommended → Approved/Rejected) but does not yet:
- Define who has decision authority for each rule category.
- Specify how counterexamples move a rule between states.
- Establish the traceability chain as a governance artifact, not just documentation.

These weaknesses are addressed in v0.4.1.

---

## 2. Next-Step Deliverable: Cross-Relationship Architecture Baseline v0.4.1

The next artifact is a corrected baseline, not another broad specification. It corrects the two formal defects, formalizes the ownership model, and produces an operational validation matrix.

### 2.1 Correction A — Authorization Fallback (Fully Specified)

**Principle:** Fallback is per-fact and per-action, bounded by a freshness contract. `Stale` and `Unavailable` are distinct states with distinct handling.

**Fact status model (extended):**

```
FactStatus {
  value: True | False | Unknown | Unavailable | Stale
  asOf: timestamp          // when the value was last known to be valid
  observedAt: timestamp    // when the system retrieved the value
  source: authoritative context
  freshness: duration      // maximum acceptable age
  fallbackPolicy: FallbackPolicy
}

FallbackPolicy {
  maxStaleness: duration
  allowedActions: [Action]
  denyOnStale: boolean
  requireAuthority: boolean
  escalateOnExhaustion: boolean
}
```

**Status semantics:**

| Status | Definition | How Determined |
|---|---|---|
| True | Fact confirmed true at `asOf` | Authoritative source returned true |
| False | Fact confirmed false at `asOf` | Authoritative source returned false |
| Unknown | System has no record of the fact | No cached value, no source available |
| Unavailable | Source could not be consulted | Timeout, error, or connectivity failure |
| Stale | Cached value exists but `now - asOf > freshness` | Age check fails |

**Corrected evaluation algorithm:**

```
evaluate(actor, action, resource, facts, policy, time):

  requiredFacts = policy.requiredFacts(action, resource)

  # Step 1: Fact status evaluation (uniform handling)
  for each fact in requiredFacts:
    status = getFactStatus(fact, time)

    # Unknown: no value at all
    if status == Unknown:
      return Deny(reason="Required fact unknown",
                  classification="fact_missing")

    # Unavailable or Stale: apply fallback policy uniformly
    if status ∈ {Unavailable, Stale}:
      age = time - status.asOf

      # Enforce maxStaleness regardless of status
      if age > fact.fallbackPolicy.maxStaleness:
        return Deny(reason="Fact beyond maximum staleness",
                    classification="fact_exhausted")

      # Enforce denyOnStale if policy requires
      if status == Stale and fact.fallbackPolicy.denyOnStale:
        return Deny(reason="Stale fact, no fallback permitted",
                    classification="fact_stale_denied")

      # Enforce action allow-list
      if action ∉ fact.fallbackPolicy.allowedActions:
        return Deny(reason="Action not permitted with fallback",
                    classification="action_not_permitted")

      # If authority-sensitive and requireAuthority, escalate
      if fact.fallbackPolicy.requireAuthority and not hasFreshAuthority(fact, time):
        return Deny(reason="Authority fact not fresh",
                    classification="authority_stale")

    # True / False: use the fact value
    # (No special handling; fall through)

  # Step 2: Policy configuration validity
  if hasConflictingMandatoryConstraints(policy, time):
    return Deny(reason="Policy configuration invalid",
                classification="configuration_error",
                escalate=true)

  # Step 3: Mandatory prohibitions (hard block)
  if anyMandatoryProhibitionApplies(policy, facts, time):
    return Deny(reason="Mandatory prohibition",
                classification="mandatory_prohibition")

  # Step 4: Mandatory conditions (must be satisfied)
  if not allMandatoryConditionsSatisfied(policy, facts, time):
    return Deny(reason="Mandatory condition not satisfied",
                classification="mandatory_condition")

  # Step 5: Ordinary rules (decisive only)
  decisiveRules = { r ∈ OrdinaryRules
                    | r.scope applies
                    ∧ r.condition holds
                    ∧ r.effective(time)
                    ∧ r.effect ≠ abstain }

  if decisiveRules = ∅:
    return Deny(reason="No applicable grant",
                classification="no_grant")

  # Step 6: Policy category resolution
  return resolveByCategory(policy.category, decisiveRules)
```

**Key corrections over v0.4:**

1. **`Stale` and `Unavailable` share the same fallback logic.** Both enforce `maxStaleness`. Both respect `denyOnStale` and `allowedActions`.
2. **`Unknown` is distinct and always denies.** No fallback for missing facts.
3. **Decision classification is explicit.** The result carries a `classification` field for operational diagnostics.
4. **Configuration errors are escalated, not silently denied.** `escalate=true` triggers operational alerting.
5. **Authority-sensitive facts require freshness.** `requireAuthority` prevents stale mandates from authorizing actions.

**Decision result model:**

```
Decision {
  result: Permit | Deny
  reason: string
  classification: enum {
    grant,
    mandatory_prohibition,
    mandatory_condition,
    no_grant,
    fact_missing,
    fact_exhausted,
    fact_stale_denied,
    action_not_permitted,
    authority_stale,
    configuration_error
  }
  escalate: boolean
  factsUsed: [FactReference]
  policyVersion: string
  evaluatedAt: timestamp
}
```

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| AF-T1 | Fact True, fresh | Use fact |
| AF-T2 | Fact False, fresh | Use fact |
| AF-T3 | Fact Unknown | Deny, classification=fact_missing |
| AF-T4 | Fact Unavailable, age < maxStaleness, action allowed | Fallback used |
| AF-T5 | Fact Unavailable, age > maxStaleness | Deny, classification=fact_exhausted |
| AF-T6 | Fact Stale, `denyOnStale=true` | Deny, classification=fact_stale_denied |
| AF-T7 | Fact Stale, `denyOnStale=false`, action allowed | Fallback used |
| AF-T8 | Authority fact stale, `requireAuthority=true` | Deny, classification=authority_stale |
| AF-T9 | Mandatory prohibition applies | Deny, classification=mandatory_prohibition |
| AF-T10 | Mandatory conditions unsatisfied | Deny, classification=mandatory_condition |
| AF-T11 | No decisive rules | Deny, classification=no_grant |
| AF-T12 | Policy configuration invalid | Deny, classification=configuration_error, escalate |

### 2.2 Correction B — Event-History Algorithm (Fully Specified)

**Principle:** Transitions are events. States are derived intervals. Corrections and retractions are explicit events. Monotonicity in transaction time does **not** hold; reproducibility does.

**Rejected invariant:**

> ~~`stateOf(s, v, k)` is monotonic in transaction time because knowledge only grows.~~

**Replacement properties:**

| Property | Statement |
|---|---|
| **Historical reproducibility** | For fixed (s, v, k) against the same retained history and rules, `stateOf` returns the same result |
| **Correction awareness** | A correction recorded after k may change the reconstructed state for valid time v, while prior transaction-time views remain reproducible |

**Event model (extended):**

```
StateTransitionEvent {
  id
  subjectId
  fromState
  toState
  effectiveAt        // valid-time instant
  recordedAt         // transaction-time instant
  authority
  reason
  eventType          // transition | correction | retraction
  supersedesEventId  // for correction/retraction
  correctionPayload  // for correction: what is being changed
  version            // monotonic per subject
}
```

**Effective event history algorithm:**

```
effectiveHistory(subjectId, k):
  # Step 1: Collect events known at transaction time k
  known = { e ∈ StateTransitionEvents
            | e.subjectId = subjectId
            ∧ e.recordedAt ≤ k }

  # Step 2: Identify superseded events
  superseded = { e.supersedesEventId | e ∈ known ∧ e.supersedesEventId ≠ null }

  # Step 3: Identify corrections and retractions
  corrections = { e ∈ known | e.eventType = correction }
  retractions = { e ∈ known | e.eventType = retraction }

  # Step 4: Build the effective event set
  effective = { e ∈ known
                | e.id ∉ superseded
                ∧ e.eventType = transition }

  # Step 5: Apply corrections in order of recordedAt
  for c in sort(corrections, by=recordedAt):
    target = findEvent(effective, c.correctionPayload.targetEventId)
    if target == null:
      # Correction references a non-existent event; flag inconsistency
      flagInconsistency(c, "correction targets unknown event")
      continue
    applyCorrection(effective, target, c.correctionPayload)

  # Step 6: Apply retractions
  for r in sort(retractions, by=recordedAt):
    target = findEvent(effective, r.supersedesEventId)
    if target != null:
      remove(effective, target)

  # Step 7: Sort by effective time and version
  return sort(effective, by=(effectiveAt, version))
```

**State query:**

```
stateOf(subjectId, v, k):
  history = effectiveHistory(subjectId, k)
  applicable = { e ∈ history | e.effectiveAt ≤ v }
  if applicable = ∅: return Unknown
  return applicable.last().toState
```

**Correction payload model:**

```
CorrectionPayload {
  targetEventId
  field: effectiveAt | toState | fromState | both
  newValue
  reason
  authority
}
```

**Validation of downstream transitions:**

After applying a correction, the algorithm must validate:
- The corrected transition is legal under the state machine.
- All subsequent transitions are legal from the corrected state.
- If a subsequent transition is now illegal, it is flagged as **invalidated** and requires human resolution.

```
validateDownstream(history):
  state = history.first().fromState
  for e in history:
    if (state, e.toState) not a valid transition:
      flagInvalidated(e, reason="illegal transition after correction")
    state = e.toState
```

**Invariants (replacing monotonicity):**

| ID | Statement |
|---|---|
| ST-1 | ∀ event e, e.fromState ≠ e.toState (transitions only) |
| ST-2 | ∀ event e, (e.fromState, e.toState) is valid in the state machine, unless flagged invalidated |
| ST-3 | ∀ (s, v, k), `stateOf` is deterministic |
| ST-4 | Corrections and retractions never delete events |
| ST-5 | Historical reproducibility: fixed inputs → fixed output |
| ST-6 | Correction awareness: corrections may change reconstructed state |
| ST-7 | Simultaneous events are ordered by version; ordering is deterministic but does not establish business validity |
| ST-8 | Invalidated transitions are flagged, not silently accepted |

**Required tests:**

| Test | Scenario | Expected |
|---|---|---|
| ST-T1 | Late entry | Query with k < recordedAt does not see event |
| ST-T2 | Retroactive correction | Both events preserved; state reflects correction |
| ST-T3 | Retraction | State reverts; original event preserved |
| ST-T4 | Simultaneous events | Deterministic ordering by version |
| ST-T5 | Invalidating correction | System flags invalidated downstream transition |
| ST-T6 | Bi-temporal query | Correct state for (v, k) combinations |
| ST-T7 | Reproducibility | Same (s, v, k) → same result |
| ST-T8 | Correction chain | Multiple corrections apply in order |
| ST-T9 | Correction targeting unknown event | Inconsistency flagged |

### 2.3 Ownership Model (Formalized as Contracts)

The provisional ownership table is now formalized as a **context contract model**. Each context declares what it owns, what it publishes, and what it consumes.

**Ownership contract schema:**

```
ContextContract {
  contextName
  owns: [FactType]
  publishes: [EventType]
  consumes: [EventType]
  exposesQueries: [QueryType]
  doesNotOwn: [FactType]
}
```

**Provisional ownership contracts:**

| Context | Owns | Publishes | Consumes | Does Not Own |
|---|---|---|---|---|
| **Identity & Access** | Person (identity), Account, Actor | AccountCreated, AccountLinked, AuthenticationSucceeded | MembershipActivated, AppointmentGranted | Membership, Appointment, Mandate, Role |
| **Organization** | Organization, OrganizationalUnit | OrganizationCreated, UnitCreated, UnitMoved, UnitDissolved | — | Membership, Committee, Role |
| **Membership** | Member, Membership, MembershipType | MembershipApplied, MembershipApproved, MembershipActivated, MembershipSuspended, MembershipTerminated | OrganizationCreated, UnitCreated | Person identity, Appointment, Role |
| **Governance** | Committee, CommitteeAppointment, Role, Mandate | CommitteeEstablished, AppointmentGranted, RoleAssigned, MandateStarted, MandateExpired | MembershipActivated, UnitCreated | Membership, Person, Forum |
| **Geography** | GeographicJurisdiction, JurisdictionAssignment | JurisdictionDefined, JurisdictionChanged | CommitteeEstablished | Organization, Committee |
| **Authorization** | AuthorizationDecision (evaluated), PolicyRule | DecisionEvaluated, PolicyConfigurationChanged | All fact events (facts consumed, not owned) | Any fact it evaluates |
| **Communication / Forum** | Forum, Topic, Post, ForumAccessPolicy | ForumCreated, PostPublished, ModerationActionTaken | MembershipActivated, AppointmentGranted, MandateExpired | Membership, Appointment, Role |
| **Audit / Historical Evidence** | AuditRecord, HistoricalSnapshot | AuditRecordCreated | All events | Any authoritative fact |

**Key ownership principles:**

1. **Consumption is not ownership.** A context may consume another context's facts without owning them.
2. **No silent duplication.** A context that needs a fact must consume it via a defined contract, not maintain a shadow copy as authoritative.
3. **Projections are allowed with freshness contracts.** A context may maintain a cached projection if it declares freshness and revocation semantics.
4. **Events are published, not queried.** Contexts integrate primarily through events, not direct database access.
5. **Queries are explicit contracts.** Where synchronous queries are needed, they are defined as named contracts with scope and consistency guarantees.

### 2.4 Validation Matrix (Operationalized)

The validation matrix is now structured as a governance instrument.

**Matrix fields (as before):**

| Field | Purpose |
|---|---|
| RuleID | Stable traceability identifier |
| RuleVersion | Current wording version |
| Proposition | Precise, testable business statement |
| Category | Structural / Temporal / Business / Configurable / Legal / Operational |
| Scope | Universal / Configurable / Organization-specific / Legal |
| Domain | The class of organizations the rule governs |
| SourceEvidence | Document and section, or explicit rationale |
| EvidenceQuality | Observed / Derived / Hypothesis / Contested |
| CounterexampleID | Link to falsification scenario |
| CounterexampleVerdict | Rule holds / Rule scoped / Rule rejected / Pending |
| FormalInvariant | Logical representation |
| OwnerContext | Context that owns the fact |
| TestID | Executable test |
| Dependencies | Other rules |
| DecisionStatus | Open / Contested / Recommended / Approved / Rejected |
| DecisionAuthority | Required for approved |

**Decision authority model:**

| Rule Category | Decision Authority |
|---|---|
| Structural invariant | Platform architecture authority |
| Temporal invariant | Platform architecture authority |
| Business rule | Organizational stakeholder + platform architecture authority |
| Configurable rule | Organizational stakeholder |
| Legal constraint | Legal authority |
| Operational requirement | Platform operations authority |

**Counterexample workflow:**

```
Rule in Open state
  ↓ counterexample constructed
  ↓ evaluate against domain
  → If counterexample legitimate → Contested
  → If counterexample illegitimate → Recommended
Contested
  ↓ resolve via evidence
  → Recommended (evidence resolves in favor)
  → Rejected (evidence refutes)
Recommended
  ↓ ARB decision
  → Approved
  → Rejected
  → Contested (new counterexample)
```

**Traceability chain:**

```
SourceEvidence → Rule → FormalInvariant → OwnerContext → TestID → DecisionStatus
```

**Rule:** A rule cannot move to Approved without a complete traceability chain, a recorded falsification attempt, and an ARB decision.

### 2.5 The Five Relationship Groups (Validation Priority)

The review proposes five groups. I formalize them as the validation priority.

**Group 1 — Member, Membership, Organization**

| Aspect | Content |
|---|---|
| Core question | What is the difference between a person, their membership, and their relationship to an organizational unit? |
| Rules covered | R-01, R-02, R-03, R-04, R-05 |
| Validation scenarios | CR-01, CR-02, CR-04, CR-09, CR-10 |
| Key risks | Person scoping; membership episodes; affiliation independence; unit dissolution |
| Acceptance | Model represents each case without duplicating person facts or losing history |

**Group 2 — Committee, Appointment, Role, Mandate**

| Aspect | Content |
|---|---|
| Core question | Which relationship establishes participation, and which establishes authority? |
| Rules covered | R-06, R-07, R-08, R-09 |
| Validation scenarios | CR-03, CR-04, CR-09 |
| Key risks | Appointment ≠ mandate; role ≠ permission; external participants |
| Acceptance | Participation, role, mandate, membership remain distinguishable and are evaluated by their own rules |

**Group 3 — Role, Permission, Authorization**

| Aspect | Content |
|---|---|
| Core question | What does a role authorize, for which resource, within which scope, at what time? |
| Rules covered | R-08, R-13 |
| Validation scenarios | CR-04, CR-07, CR-08 |
| Key risks | Role vs. permission conflation; stale facts; session persistence |
| Acceptance | Explainable decisions from authoritative facts and explicit policy, with safe handling of stale facts |

**Group 4 — Geography, Organizational Units, Committees**

| Aspect | Content |
|---|---|
| Core question | Is geographical responsibility independent of the organizational hierarchy? |
| Rules covered | R-05, R-06, R-10 |
| Validation scenarios | CR-05, CR-06 |
| Key risks | Hierarchy conflation; many-to-many jurisdiction; boundary changes |
| Acceptance | Organizational and geographical hierarchies are separate and related explicitly |

**Group 5 — Forum, Membership, Roles, Access**

| Aspect | Content |
|---|---|
| Core question | Is forum access derived from authoritative facts, granted explicitly, or both? |
| Rules covered | R-11, R-12, R-13 |
| Validation scenarios | CR-04, CR-07, CR-08 |
| Key risks | Access caching; revocation timing; content ownership |
| Acceptance | Access changes according to approved policy; forum owns its content |

### 2.6 Mathematics, Statistics, and Machine Learning (v0.4.1 Scope)

| Technique | Application | Oversight |
|---|---|---|
| Predicate logic | Membership, appointment, role, access conditions | Manual specification |
| Temporal logic | Expiry, revocation, historical reconstruction | Manual specification |
| Property-based testing | Generate combinations of memberships, appointments, scopes, times | Automated with generators |
| Constraint solving | Detect contradictory policies and invalid combinations | Automated |
| Model checking | Explore lifecycle transitions and correction edge cases | Selective |
| NLP / LLM | Extract candidate rules from organizational documents | Mandatory human verification |
| Clustering | Identify different meanings of similar terms across organizations | Mandatory human verification |
| Statistical analysis | Compare patterns across organizations | Signal only |
| Anomaly detection | Flag unusual operational patterns | Signal only |

**ML must never decide:**
- Membership eligibility
- Appointment approval
- Mandate validity
- Authorization decisions
- Policy precedence
- Forum access

---

## 3. How Far We Are from the Implementation Goal

### 3.1 Maturity Assessment

| Dimension | Status | Notes |
|---|---|---|
| Conceptual research | Baseline established | Core concepts and distinctions identified |
| Formal specification | In progress | Two formal defects being corrected; ownership model proposed |
| Evidence and falsification | Not executed | Requires real organizational documents |
| Validated DDD architecture | Not established | Ownership contracts proposed but not validated |
| Implementation readiness | Not established | Requires validated architecture baseline |
| Election scope | Parked | Out of scope for this phase |

**Overall assessment:** We are approximately **45–50%** of the way toward an implementable organizational domain model. This is a qualitative estimate, not a measured metric. The next measurable milestone is validating the five relationship groups against real organizational evidence and demonstrating the results with tests.

### 3.2 Remaining TODOs (Bullet Points)

**P0 — Correct the formal specification (v0.4.1)**
- Fix stale-fact handling so every fallback (Stale or Unavailable) enforces `maxStaleness`.
- Define freshness contracts for membership, appointment, mandate, role, jurisdiction, forum policy.
- Specify which actions may use cached facts and which require fresh authoritative information.
- Distinguish `Unknown`, `Unavailable`, `Stale` operationally.
- Correct the event-history algorithm to handle superseded events, correction chains, retractions, invalidated downstream transitions.
- Replace the monotonicity invariant with historical reproducibility and correction awareness.
- Define how simultaneous state transitions are validated, not merely ordered.
- Separate ordinary denial, unavailable facts, and invalid configuration in decision results.

**P0 — Formalize the ownership model**
- Define context contracts: owns, publishes, consumes, exposes, does not own.
- Specify what each context must publish and to whom.
- Specify non-responsibilities for each context.
- Define contract versioning.

**P0 — Validate the five relationship groups**
- Group 1: Member ↔ Membership ↔ Organization.
- Group 2: Committee ↔ Appointment ↔ Role ↔ Mandate.
- Group 3: Role ↔ Permission ↔ AuthorizationDecision.
- Group 4: Geography ↔ OrganizationalUnit ↔ Committee.
- Group 5: Forum ↔ Membership/Role ↔ Access.
- For each group: define meaning, ownership, invariants, dependencies, tests.

**P0 — Populate the validation matrix**
- Map R-01 through R-13 to the five relationship groups.
- Record source evidence, intended scope, formal invariants, counterexamples, owners, test IDs.
- Obtain one political party constitution or bylaws.
- Obtain one membership association bylaws or statutes.
- Obtain committee, jurisdiction, role, and forum policies.
- Separate source facts from interpretations.
- Execute CR-01 through CR-10 with positive, negative, boundary, and counterexample results.
- Link each validated rule to an automated test or documented reason for manual validation.
- Keep unverified rules Open or Contested.

**P1 — Validate temporal and authorization semantics**
- Test membership suspension, termination, retroactive correction.
- Test appointment expiry, mandate changes, role revocation.
- Test historical queries with valid time and transaction time.
- Test stale facts, unavailable services, policy conflicts.
- Test forum access after role or membership change, including sessions and caches.
- Define audit evidence and correction authority.
- Use property-based tests, constraint checks, selective model checking.

**P1 — Establish architecture decision governance**
- Define decision authority for each rule category.
- Apply Open → Contested → Recommended → Approved/Rejected workflow.
- Maintain traceability from evidence through rules and tests to decisions.
- Define exploratory prototype boundaries.
- Record unresolved questions and dependencies.

**P2 — Finalize the DDD architecture**
- Group validated concepts by lifecycle ownership and business responsibility.
- Derive bounded contexts from validated model.
- Define aggregate boundaries from transactional invariants and concurrency.
- Validate context boundaries against multiple organizational scenarios.
- Produce context map, domain contracts, event/query integration rules.
- Resolve identity scope and tenant isolation with explicit requirements.
- Define cross-domain projections (forum access) with consistency guarantees.
- Review architecture against existing implementation; identify smallest safe changes.

**P3 — Prepare and execute implementation**
- Create dependency-ordered implementation backlog.
- Define acceptance criteria for every implementation slice.
- Implement in small DDD/TDD increments with domain, integration, temporal, authorization, tenant-isolation tests.
- Preserve traceability from approved rules to implementation and regression tests.
- Measure progress using validated rules, passing acceptance criteria, completed architecture decisions — not subjective percentages.

### 3.3 Final Recommendation

The next deliverable is the **Cross-Relationship Architecture Baseline v0.4.1**, containing:

1. Corrected authorization fallback (uniform staleness enforcement, explicit classification).
2. Corrected event-history algorithm (correction-aware, reproducibility replaces monotonicity).
3. Formalized ownership contracts (owns, publishes, consumes, does not own).
4. Operationalized validation matrix (decision authority, counterexample workflow, traceability chain).
5. The five relationship groups as the validation priority.

The first milestone is validating **Group 1 (Member, Membership, Organization)** and **Group 2 (Committee, Appointment, Role, Mandate)**, because these establish the facts that authorization and forum access depend on.

Only after the five groups are validated against real organizational evidence should bounded contexts, aggregates, and identity/tenancy decisions be derived.

**Authorization required:** Provision of at least one political party constitution and one membership association bylaws to begin evidence collection and falsification of Groups 1 and 2.