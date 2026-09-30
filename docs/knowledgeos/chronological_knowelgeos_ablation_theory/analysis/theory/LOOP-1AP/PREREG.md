# 1ap: pre-registration (frozen before either subagent runs)

- **Question:** AUTHORIZE-IMPL's formal guard is {a} (F-LOG-0156), but 1ao found a hidden status variable (D10). Is force determined by the issuer directly, or mediated by the ruling's status?
- **Sources:** register rows R-70, R-81, R-86, R-89, R-95. All are already-read range; no new corpus read.

| Reconciled outcome | Consequence |
|---|---|
| M_issuer FALSIFIED and M_status SURVIVES | AUTHORIZE-IMPL's guard is **status-mediated**. Authority's role becomes **capability to change status** (a transition of the ruling LTS), not a direct guard of the act. The guard family is simplified: a is replaced by ruling status, which the bisimulation already marks as behaviour-relevant |
| both survive (confounded) | a designed observation is needed: the same issuer with different status, or the same status with different issuers |
| M_status FALSIFIED | status is not sufficient; M_issuer or M_adopter (or both) is needed |

- **Circularity guard:** an in_force value derived from status is **not** usable to test M_status; the reviewer checks this.
- **Sealed main-analyst expectation (bias check):**
  - M_issuer FALSIFIED (R-81..85, Chief-prepared: not in force before R-86 and in force after; or R-89 vs R-95);
  - M_status SURVIVES;
  - M_chain SURVIVES, with status changes by the DA;
  - risk: most in_force values are derived rather than stated.

  Moderate confidence.
