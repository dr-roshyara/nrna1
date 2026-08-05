---
name: public-digit-brand-colors
description: "Architectural decision on brand color system — Blue primary, Gold accent, no purple"
metadata: 
  node_type: memory
  type: reference
  originSessionId: c0a36271-a7ea-4077-a9b3-9a296fbac521
---

# Public Digit Brand Color Architecture

**Decision date:** 2026-05-29
**Decided by:** Chief Architect
**Domain:** Digital Governance Platform (elections, membership, identity, governance)

## The Decision

Public Digit is a **Digital Governance Platform**, not an election app. The color system must communicate **trust, neutrality, authority, transparency**.

| Role | Color | Hex |
|------|-------|-----|
| Primary action | Blue | `#2563eb` |
| Governance accent | Gold | `#a0742a` |
| Success | Emerald | `#059669` |
| Warning | Amber | `#d97706` |
| Danger | Red | `#dc2626` |
| Neutral | Gray | neutral scale |

## Rationale

Blue communicates institutional credibility (government, enterprise, security). Gold communicates governance and ceremony. Together they say "democratic institution."

Purple communicates creativity and innovation — suited for an AI/experimental feature line, not the core governance platform.

## Rules

- `<Button variant="primary">` = default action color (blue)
- `<Button variant="accent">` = governance/officer actions only (gold) — not common
- Purple is NOT a primary brand color
- See [[design-rules-json]] for enforcement patterns
