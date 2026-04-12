# Contribution Scoring Changelog

## v2 — Scientific Validation Updates (2026-04-11)

### Changed
- `calculateSynergy()` renamed to `calculateSkillDiversityBonus()` — removes misleading
  Shapley reference; the method is a heuristic, not a true Shapley calculation
- Effort scoring now applies diminishing returns after 20 hours:
  `effectiveHours = 20 + log(extraHours + 1) × 5`
- Combined multiplier (synergy × verification × sustainability) is now capped at 2.0×
  via `MAX_COMBINED_MULTIPLIER` — prevents unfair amplification in high-bonus scenarios

### Added
- `community_attestation` proof type (1.1×) — grassroots alternative to `institutional`
  for contributions verified by 3+ community members rather than an official body

### Rationale
Based on peer review by senior researcher identifying four risks in v1:
1. Multiplicative explosion (max 2.16× could reach with all bonuses)
2. Misleading method name implying Shapley value algorithm
3. Linear effort assumption penalising long-form projects vs. productivity research
4. Institutional proof bias disadvantaging communities without formal organisations

### Validation Needed (TODOs)
- Calibrate `MAX_COMBINED_MULTIPLIER` against production distribution
- Validate diminishing-returns curve with user satisfaction studies
- Measure fairness impact of `community_attestation` across user groups
- Adjust multiplier thresholds based on observed usage

## v1 — Initial Release (2026-04-11)

- Base formula: `(effort_units × 10 + TierBonus) × Synergy × Verification × Sustainability + OutcomeBonus`
- Three tracks: micro / standard / major
- Five proof types: self_report / photo / document / third_party / institutional
- Weekly micro-track cap: 100 points
