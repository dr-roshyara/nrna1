# Specification for SCHEMA-CORE (given to the drafting worker)

Draft a coding schema with exactly three fields per record, each YES / NO / UNKNOWN:
- **ACT_REPORTED:** the text reports that an act actually happened, or was attempted, refused, withdrawn or reversed, as a past or present event.
- **ACT_SELF_PERFORMED:** the span's own words perform an act (a performative: by being written or issued, the text itself adopts, authorizes, withdraws, classifies, decides …).
- **NORM_STATED:** the span states normative content: something is permitted, required, prohibited, authorized, eligible, or has a normative status.

Fixed rules:
- Never infer an act from a norm ("X is authorized to begin" is NORM_STATED = YES; it is not evidence that X began).
- UNKNOWN when the span (and, where needed, its section) does not decide.
- A record whose span reads "NO SOURCE SPAN AVAILABLE" is UNKNOWN on all three.
- One record may be YES on several fields. The fields are coded independently.
