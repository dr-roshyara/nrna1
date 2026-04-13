The voter verification (video call + IP/fingerprint capture) happens **after** the member is added as a voter.

## Workflow Order

```
1. Member added to voter list
   ↓
2. Admin contacts voter for video call
   ↓
3. Admin opens Voter Verification Modal
   ↓
4. During call, admin captures:
   - Voter's current IP address
   - Device fingerprint (optional)
   ↓
5. Admin saves verification
   ↓
6. Voter can now access ballot (if mode requires it)
```

## Rationale

| Order | Reason |
|-------|--------|
| Voter added first | Establishes eligibility in the system |
| Verification second | Identity confirmation before voting access |
| Voting last | Only after both steps complete |

## In the UI

- **Voters page**: Shows list of all eligible voters
- **Verify button**: Available for each voter (regardless of verification status)
- **Verification badge**: Appears after admin completes verification

The member must be in the voter list before the "Verify" button becomes available for them.