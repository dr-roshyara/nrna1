# Election Settings — Developer Guide

**Area:** the election settings an election's Chief or Deputy manage (`ElectionSettingsController`, `UpdateElectionSettingsRequest`, `Election` model guards).

```
Election settings
├── Voter admission policy   → developer_guide/election/voter_admission/   (PBDIGIT-213)
└── Security policy          → this folder                                 (PBDIGIT-244)
```

The security policy reuses the admission policy's lifecycle-lock *pattern*; it is a separate concern, not part of voter admission.

## Steps

| File | Story | What it covers |
|---|---|---|
| [01_security_policy_lifecycle_lock.md](./01_security_policy_lifecycle_lock.md) | PBDIGIT-244 | The security policy is immutable from ReadyForVoting (I9): application-layer refusal + model guard; interim four-field boundary, Q3 open |
