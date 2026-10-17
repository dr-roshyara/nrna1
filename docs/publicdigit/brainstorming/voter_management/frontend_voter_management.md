Yes. I think this is an important addition, and I would make it part of the **first usable version**, but keep it deliberately simple.

The key is to distinguish **configuration of the admission requirement** from **execution of the verification**.

## 1. Election Committee needs an Admission Policy page

I would add a section to the election administration/settings page:

### Voter Admission

```text
┌──────────────────────────────────────────────────────────────┐
│ Voter Admission                                             │
│                                                              │
│ Choose which verification requirements apply to voters.      │
│                                                              │
│ Identity verification                                       │
│ Election Chief / Deputy must verify the voter's identity.   │
│                                                              │
│                         [ OFF ]                              │
│                                                              │
│ When enabled, every voter must pass identity verification   │
│ before becoming entitled to vote.                            │
│                                                              │
│                              [Save Admission Policy]         │
└──────────────────────────────────────────────────────────────┘
```

When the Chief/Deputy turns it **ON**:

```text
OFF
 ↓
Identity Verification Required
```

the policy becomes:

```json
{
  "requires": [
    "OFFICER_IDENTITY_VERIFICATION"
  ]
}
```

From that moment:

```text
ALL registered voters
        │
        ▼
Identity verification required
        │
        ▼
Chief / Deputy verifies
        │
        ▼
Voting entitlement
```

This is much better than having the administrator manually configure every voter.

---

# 2. But the button should represent a policy, not a "gate"

I would label the UI:

> **Identity verification required**

rather than:

> **Gate 1 ON**

The user understands the business meaning immediately.

Internally:

```text
VoterAdmissionPolicy
    requires:
        OfficerIdentityVerification
```

This also leaves room for later:

```text
☐ Identity verification
☐ Email verification
☐ Phone verification
☐ eID verification
```

without redesigning the page.

---

# 3. Then the voter management page becomes the operational screen

Your existing page:

`/organisations/.../elections/.../voters/manage`

should remain the place where the Committee **executes** the policy.

You want at least:

```text
[ Verify Identity ]   [ Suspend Identity ]
```

I agree with this direction.

But I would make one semantic adjustment:

### Button 1

**Verify Identity**

Meaning:

> Chief/Deputy confirms that this person is the registered voter.

### Button 2

**Suspend Identity**

Meaning:

> Suspend the voter's current identity admission, causing re-verification to be required.

I would **not call the second button simply "Suspend"**, because later there could be other kinds of suspension.

---

# 4. Keep the legacy button initially

Yes.

This is a very good incremental strategy.

Do **not** immediately redesign the entire voter-management interface.

Keep the existing legacy Verify button/functionality and introduce the new admission semantics behind it.

Conceptually:

```text
Existing UI
     │
     ▼
[Verify]
     │
     ▼
RecordOfficerIdentityVerification
     │
     ▼
Admission Evidence
     │
     ▼
Voting Entitlement
```

Then later we can rename/refine the UI once the domain model is proven.

This reduces implementation risk.

---

# 5. Important: the policy OFF state

This is where the architecture becomes especially useful.

### Policy OFF

```text
VoterAdmissionPolicy
requires = []
```

Then:

```text
registered voter
       ↓
admission satisfied
       ↓
voting entitlement
```

No verification required.

### Policy ON

```text
VoterAdmissionPolicy
requires = [
    OfficerIdentityVerification
]
```

Then:

```text
registered voter
       ↓
identity verification?
       │
   ┌───┴────┐
   │        │
  NO       YES
   │        │
   ▼        ▼
BLOCKED   ENTITLED
```

This gives you exactly the behavior you're describing.

---

# 6. What happens when the Committee switches it ON?

This is important.

Suppose there are already:

```text
5 voters
```

and the Chief switches:

```text
Identity verification required = ON
```

The system should **not modify all five membership records**.

Instead:

```text
ElectionMembership
    remains registered
```

and:

```text
VoterAdmissionPolicy
    now requires identity verification
```

Therefore the five voters automatically become:

```text
Admission = PENDING
Voting entitlement = FALSE
```

until verified.

That's much cleaner DDD.

---

# 7. What should the voter management page show?

I'd add an **Admission** column.

For example:

| Voter | Membership | Admission | Actions |
|---|---|---|---|
| Ram Sharma | Active | ⚠ Identity verification required | Verify Identity |
| Sita Rai | Active | ✓ Identity verified | Suspend Identity |
| Hari Thapa | Active | ⚠ Identity verification required | Verify Identity |
| Mina Gurung | Suspended | ⚠ Re-verification required | Verify Identity |

This makes the domain state visible to the Committee.

---

# 8. After suspension

Your second button becomes particularly useful.

Suppose:

```text
Sita
Identity verified
Can vote
```

Committee selects:

**Suspend Identity**

Then:

```text
Sita
   │
   ▼
Suspended
   │
   ├── remains in voter list
   ├── cannot vote
   └── requires identity re-verification
```

The UI should show:

```text
Admission:
⚠ Re-verification required
```

Then Chief/Deputy clicks:

**Verify Identity**

and:

```text
Suspended/blocked
       ↓
identity reverified
       ↓
admission satisfied
```

The suspension status itself still needs to be handled according to your existing two-person suspension workflow. The verification action does **not** itself approve the suspension.

That's an important separation.

---

# 9. I would slightly change the existing plan

The current Claude plan already has most of this:

- policy toggle
- affected-voter count
- admission column
- Verify action
- revoke action
- suspension invalidation. Eingefügter Text

But your clarification gives us a cleaner **product workflow**:

```text
                    ELECTION SETTINGS
                           │
                           ▼
                 VOTER ADMISSION POLICY
                           │
                    [Identity verification]
                           │
                     ON / OFF
                           │
                           ▼
                    ┌───────────────┐
                    │ Policy locked │
                    │ at ReadyFor   │
                    │ Voting        │
                    └───────────────┘


                    VOTER MANAGEMENT
                           │
             ┌─────────────┴──────────────┐
             ▼                            ▼
       Verify Identity             Suspend Identity
             │                            │
             ▼                            ▼
       Admission evidence          Revalidation required
             │                            │
             └─────────────┬──────────────┘
                           ▼
                  Voting Entitlement
```

That is, in my opinion, the cleanest **v1 user experience**.

---

# 10. One important UX rule

When the policy is **OFF**, don't show "Verify Identity" as if it were mandatory.

You could show:

```text
Admission
Not required
```

and perhaps retain the legacy verification action for administrative use.

When the policy is **ON**:

```text
Admission
Identity verification required
```

and the action becomes prominent.

This avoids confusing the Committee.

---

# 11. My recommended v1

I would therefore modify Claude's plan to explicitly include:

### Election Settings

**Voter Admission**

```text
Identity verification required
[ OFF / ON ]

Description:
Every registered voter must be personally verified by
the Election Chief or Deputy before voting.

Affected voters: 5

[Save]
```

After `ReadyForVoting`:

```text
Identity verification required
[ ON — LOCKED ]

The admission policy cannot be changed once the election
is ready for voting.
```

### Voter Management

Keep the **legacy verification button** initially, but connect it to the new domain command.

Add:

```text
[Verify Identity]
[Suspend Identity]
```

with the second action respecting the existing **two-person suspension process**.

### Domain

```text
Election
  │
  └── VoterAdmissionPolicy
          │
          └── OfficerIdentityVerification

Voter
  │
  ├── Membership
  └── AdmissionEvidence

Election Committee
  │
  ├── Chief
  └── Deputy
          │
          └── VerificationAuthority

VotingEntitlement
  │
  ├── Membership
  ├── Admission
  ├── Suspension
  └── Election lifecycle
```

**This is the architecture I would use.** It gives the Committee a very simple mental model—**turn identity verification ON, and every voter must pass it**—while the domain remains flexible enough for future vendor requirements such as email, WhatsApp, eID, or combinations of requirements.

And I would still keep the **legacy UI button initially**, exactly as you suggested, to minimize the first implementation's risk.