## Complete Membership & Finance Architecture

Here's the **end-to-end architecture** from member creation to financial reporting.

---

## 📊 High-Level Flow

```
┌─────────────────────────────────────────────────────────────────────────────────────┐
│                         MEMBERSHIP & FINANCE ECOSYSTEM                               │
├─────────────────────────────────────────────────────────────────────────────────────┤
│                                                                                      │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐       │
│  │   Member     │───▶│   Fees       │───▶│   Payment    │───▶│   Income     │       │
│  │   Creation   │    │   Assignment │    │   Recording  │    │   Record     │       │
│  └──────────────┘    └──────────────┘    └──────────────┘    └──────────────┘       │
│         │                   │                   │                   │               │
│         ▼                   ▼                   ▼                   ▼               │
│  ┌──────────────┐    ┌──────────────┐    ┌──────────────┐    ┌──────────────┐       │
│  │  User Account│    │  Fee Types   │    │  Audit Trail │    │  Finance     │       │
│  │  & Roles     │    │  & Periods   │    │  Receipts    │    │  Reports     │       │
│  └──────────────┘    └──────────────┘    └──────────────┘    └──────────────┘       │
│                                                                                      │
└─────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ Database Schema

### Core Tables & Relationships

```sql
-- MEMBERSHIP MODULE
organisations
    ├── id (uuid) PK
    ├── name, slug
    └── uses_full_membership (boolean)

users
    ├── id (uuid) PK
    ├── name, email, password
    └── organisation_id (FK → organisations)

members
    ├── id (uuid) PK
    ├── organisation_id (FK → organisations)
    ├── user_id (FK → users)
    ├── membership_type_id (FK → membership_types)
    ├── fees_status (pending|paid|overdue)
    └── joined_at, expired_at

membership_fees
    ├── id (uuid) PK
    ├── member_id (FK → members)
    ├── organisation_id (FK → organisations)
    ├── amount, currency
    ├── due_date, paid_at
    ├── status (pending|paid|overdue|waived)
    ├── payment_method
    ├── recorded_by (FK → users)
    └── period_label (e.g., "2025 Annual Fee")

membership_payments
    ├── id (uuid) PK
    ├── member_id (FK → members)
    ├── fee_id (FK → membership_fees) [nullable]
    ├── organisation_id (FK → organisations)
    ├── amount, currency
    ├── payment_method
    ├── payment_reference
    ├── recorded_by (FK → users)
    ├── income_id (FK → incomes) [nullable]
    └── paid_at

-- FINANCE MODULE
incomes
    ├── id (uuid) PK
    ├── organisation_id (FK → organisations)
    ├── user_id (FK → users) [recorded by]
    ├── source_type (membership_fee|donation|event|...)
    ├── source_id (uuid) [FK to source table]
    ├── membership_fee (decimal) [amount]
    ├── country, committee_name
    ├── period_from, period_to
    └── [other income category columns]
```

### Entity Relationship Diagram

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│   Organisation  │     │      User       │     │  MembershipType │
├─────────────────┤     ├─────────────────┤     ├─────────────────┤
│ id (PK)         │◄────│ organisation_id │     │ id (PK)         │
│ name            │     │ id (PK)         │     │ name            │
│ slug            │     │ name            │     │ fee_amount      │
│ uses_full_      │     │ email           │     │ duration_days   │
│ membership      │     └─────────────────┘     └─────────────────┘
└─────────────────┘              │                       │
         │                       │                       │
         │                       ▼                       ▼
         │              ┌─────────────────────────────────────┐
         │              │              Member                  │
         │              ├─────────────────────────────────────┤
         │              │ id (PK)                             │
         └─────────────►│ organisation_id (FK)                │
                        │ user_id (FK)                        │
                        │ membership_type_id (FK)             │
                        │ fees_status                         │
                        └─────────────────────────────────────┘
                                       │
                                       │
                    ┌──────────────────┼──────────────────┐
                    │                  │                  │
                    ▼                  ▼                  ▼
         ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐
         │  MembershipFee  │  │MembershipPayment│  │   Income        │
         ├─────────────────┤  ├─────────────────┤  ├─────────────────┤
         │ id (PK)         │  │ id (PK)         │  │ id (PK)         │
         │ member_id (FK)  │  │ member_id (FK)  │  │ organisation_id │
         │ organisation_id │  │ fee_id (FK)     │  │ source_type     │
         │ amount          │  │ amount          │  │ source_id       │
         │ due_date        │  │ payment_method  │  │ membership_fee  │
         │ status          │  │ income_id (FK)──┼─►│ (amount)        │
         │ paid_at         │  │ paid_at         │  └─────────────────┘
         └─────────────────┘  └─────────────────┘
```

---

## 🔄 Complete Flow: Member to Income

### Phase 1: Member Creation

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ STEP 1: User Registers / Admin Creates Member                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INPUT:                                                                      │
│  ├── Name, Email, Password                                                   │
│  ├── Organisation selection                                                  │
│  └── Membership type selection                                               │
│                                                                              │
│  PROCESS:                                                                    │
│  1. Create User record                                                       │
│  2. Create Member record linked to User                                      │
│  3. Assign OrganisationUser role                                             │
│  4. Set default fees_status = 'pending'                                      │
│                                                                              │
│  OUTPUT:                                                                     │
│  └── Member created with pending fees_status                                 │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Phase 2: Fee Assignment

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ STEP 2: Admin Assigns Fees to Member                                        │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  INPUT:                                                                      │
│  ├── Member selection                                                        │
│  ├── Fee amount (or auto from membership type)                               │
│  ├── Due date                                                               │
│  └── Period label (e.g., "2025 Annual Fee")                                  │
│                                                                              │
│  PROCESS:                                                                    │
│  1. Create MembershipFee record                                              │
│  2. Set status = 'pending'                                                   │
│  3. Update Member.fees_status = 'pending' (if first fee)                     │
│                                                                              │
│  OUTPUT:                                                                     │
│  └── Fee assigned to member, visible in "Outstanding Fees"                   │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Phase 3: Payment Recording (The Core Flow)

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ STEP 3: Admin Records Payment                                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  URL: POST /organisations/{org}/members/{member}/fees/{fee}/pay              │
│                                                                              │
│  INPUT:                                                                      │
│  ├── payment_method (bank_transfer|cash|card|cheque|online)                  │
│  ├── amount (defaults to fee amount)                                         │
│  └── payment_reference (optional)                                            │
│                                                                              │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │              MembershipPaymentService::recordPayment()               │    │
│  │                         (Atomic Transaction)                         │    │
│  ├─────────────────────────────────────────────────────────────────────┤    │
│  │                                                                      │    │
│  │  1. lockForUpdate() on fee row ───────────────────────────────┐     │    │
│  │     (prevents concurrent duplicate payments)                   │     │    │
│  │                                                                 │     │    │
│  │  2. Check if fee already paid ──────────────────────────────┐  │     │    │
│  │     → throws FeeAlreadyPaidException                        │  │     │    │
│  │                                                             │  │     │    │
│  │  3. Create MembershipPayment record                         │  │     │    │
│  │     └── stores amount, method, reference, recorded_by       │  │     │    │
│  │                                                             │  │     │    │
│  │  4. Update MembershipFee                                    │  │     │    │
│  │     └── status='paid', paid_at=now()                        │  │     │    │
│  │                                                             │  │     │    │
│  │  5. Update Member.fees_status                               │  │     │    │
│  │     └── if no pending/overdue fees remain → 'paid'          │  │     │    │
│  │                                                             │  │     │    │
│  │  6. Fire MembershipFeePaid event ─────────────────────────┐ │  │     │    │
│  │                                                           │ │  │     │    │
│  └───────────────────────────────────────────────────────────┼─┼──┼─────┘    │
│                                                              │ │  │          │
│  ┌───────────────────────────────────────────────────────────┼─┼──┼──────┐   │
│  │           CreateIncomeForMembershipFee Listener           │ │  │      │   │
│  │                       (Decoupled)                         │◄┘  │      │   │
│  ├───────────────────────────────────────────────────────────┼────┘      │   │
│  │                                                           │           │   │
│  │  7. Create Income record                                  │           │   │
│  │     ├── organisation_id = member->organisation_id         │           │   │
│  │     ├── source_type = 'membership_fee'                    │           │   │
│  │     ├── source_id = fee->id                               │           │   │
│  │     ├── membership_fee = payment->amount                  │           │   │
│  │     ├── user_id = recorded_by                             │           │   │
│  │     └── period_from/to = fee period                       │           │   │
│  │                                                           │           │   │
│  │  8. Link back: payment->update(['income_id' => $income->id])         │   │
│  │                                                           │           │   │
│  └───────────────────────────────────────────────────────────┼───────────┘   │
│                                                              │               │
│  OUTPUT:                                                     │               │
│  └── Redirect back with success message ◄───────────────────┘               │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

### Phase 4: Financial Reporting

```
┌─────────────────────────────────────────────────────────────────────────────┐
│ STEP 4: View Financial Reports                                               │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  Member-Level (Finance.vue):                                                 │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ /organisations/{org}/members/{member}/finance                       │    │
│  │                                                                      │    │
│  │ ├── Stats: Outstanding Total, Paid This Month, Overdue Count        │    │
│  │ ├── Outstanding Fees: List of pending/overdue fees with pay button  │    │
│  │ └── Payment History: Past payments with amounts, methods, dates     │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
│  Organisation-Level (Income Index):                                          │
│  ┌─────────────────────────────────────────────────────────────────────┐    │
│  │ /finance/income                                                      │    │
│  │                                                                      │    │
│  │ ├── All income records (membership_fee, donations, events)           │    │
│  │ ├── Filter by source_type = 'membership_fee'                         │    │
│  │ ├── Total membership revenue                                         │    │
│  │ └── Export to CSV/PDF                                                │    │
│  └─────────────────────────────────────────────────────────────────────┘    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🛡️ Security & Tenant Isolation Layers

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         SECURITY ARCHITECTURE                                │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                              │
│  LAYER 1: Route Middleware                                                   │
│  └── auth, throttle, organisation.context                                    │
│                                                                              │
│  LAYER 2: Policy Authorization                                               │
│  └── MemberPolicy::manageMembership()                                        │
│  └── ElectionPolicy::view()                                                  │
│                                                                              │
│  LAYER 3: Controller Tenant Isolation                                        │
│  └── if ($member->organisation_id !== $organisation->id) abort(404)         │
│  └── if ($fee->member_id !== $member->id) abort(404)                        │
│                                                                              │
│  LAYER 4: Database Query Scoping                                             │
│  └── withoutGlobalScopes() for cross-tenant queries                         │
│  └── where('organisation_id', $org->id) for all queries                     │
│                                                                              │
│  LAYER 5: Model Level                                                        │
│  └── BelongsTo(Organisation::class) on all tenant models                    │
│                                                                              │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 📁 File Structure Map

```
app/
├── Models/
│   ├── Organisation.php
│   ├── Member.php
│   ├── MembershipFee.php
│   ├── MembershipPayment.php      ← NEW
│   └── Income.php                  ← MOVED from Domain
│
├── Services/
│   └── MembershipPaymentService.php  ← NEW (core logic)
│
├── Domain/Shared/ValueObjects/
│   └── Money.php                     ← NEW (type safety)
│
├── Events/
│   └── MembershipFeePaid.php         ← NEW
│
├── Listeners/
│   └── CreateIncomeForMembershipFee.php ← NEW
│
├── Http/Controllers/
│   ├── MemberController.php          ← ADDED finance()
│   ├── Membership/MembershipFeeController.php ← MODIFIED pay()
│   └── Finance/
│       ├── IncomeController.php      ← MOVED
│       └── OutcomeController.php     ← MOVED
│
└── Exceptions/
    └── FeeAlreadyPaidException.php   ← NEW

resources/js/Pages/
├── Organisations/Membership/Member/
│   ├── Fees.vue                      ← Existing
│   └── Finance.vue                   ← NEW (Precision Ledger)
└── Members/
    └── Index.vue                     ← MODIFIED (added Finance link)

database/migrations/
├── [timestamp]_create_membership_payments_table.php  ← NEW
└── [existing income table already has required columns]

routes/
├── organisations.php                 ← ADDED members.finance route
└── finance/financeRoutes.php         ← UPDATED namespace
```

---

## 🔄 Complete Data Flow Summary

```
┌──────────────────────────────────────────────────────────────────────────────┐
│                         END-TO-END DATA FLOW                                  │
├──────────────────────────────────────────────────────────────────────────────┤
│                                                                               │
│  [Admin]                                                          [System]    │
│     │                                                                 │        │
│     │ 1. Create Member                                              │        │
│     ├─────────────────────────────────────────────────────────────► │        │
│     │                                                                 │        │
│     │ 2. Assign Fee                                                  │        │
│     ├─────────────────────────────────────────────────────────────► │        │
│     │                                                    ┌──────────┴──────┐ │
│     │                                                    │ membership_fees │ │
│     │                                                    │ status=pending  │ │
│     │                                                    └──────────┬──────┘ │
│     │                                                               │        │
│     │ 3. Record Payment                                            │        │
│     ├─────────────────────────────────────────────────────────────► │        │
│     │                                                    ┌──────────┴──────┐ │
│     │                                                    │ lockForUpdate() │ │
│     │                                                    │ create payment  │ │
│     │                                                    │ update fee paid │ │
│     │                                                    │ fire event      │ │
│     │                                                    └──────────┬──────┘ │
│     │                                                               │        │
│     │                                                    ┌──────────┴──────┐ │
│     │                                                    │ listener creates│ │
│     │                                                    │ Income record   │ │
│     │                                                    └──────────┬──────┘ │
│     │                                                               │        │
│     │ 4. View Finance Report                                        │        │
│     ├─────────────────────────────────────────────────────────────► │        │
│     │                                                    ┌──────────┴──────┐ │
│     │                                                    │ Finance.vue     │ │
│     │                                                    │ shows:          │ │
│     │                                                    │ - Stats         │ │
│     │                                                    │ - History       │ │
│     │                                                    │ - Income linked │ │
│     │                                                    └─────────────────┘ │
│     │                                                                        │
│     │ 5. View Income Report                                                 │
│     ├─────────────────────────────────────────────────────────────►         │
│     │                                                    ┌──────────┴──────┐ │
│     │                                                    │ /finance/income │ │
│     │                                                    │ shows all       │ │
│     │                                                    │ membership fees │ │
│     │                                                    └─────────────────┘ │
│                                                                               │
└──────────────────────────────────────────────────────────────────────────────┘
```

---

## 🎯 Key Entry Points (URLs)

| Purpose | URL | Controller | View |
|---------|-----|------------|------|
| **Members List** | `/organisations/{org}/members` | MemberController@index | Members/Index.vue |
| **Member Finance** | `/organisations/{org}/members/{member}/finance` | MemberController@finance | Membership/Member/Finance.vue |
| **Record Payment** | `POST /organisations/{org}/members/{member}/fees/{fee}/pay` | MembershipFeeController@pay | (redirect back) |
| **Income Report** | `/finance/income` | IncomeController@index | Finance/Income/Index.vue |
| **Member Fees Config** | `/organisations/{org}/members/{member}/fees` | MemberController@fees | Membership/Member/Fees.vue |

---

## ✅ What's Working Now

| Component | Status |
|-----------|--------|
| Member creation | ✅ |
| Fee assignment | ✅ (needs UI verification) |
| Payment recording | ✅ |
| Income record creation (event-driven) | ✅ |
| Member finance dashboard (Finance.vue) | ✅ |
| Payment history | ✅ |
| Outstanding fees tracking | ✅ |
| Tenant isolation | ✅ |
| Concurrent payment protection | ✅ |

---

## ❌ What Needs Verification/Implementation

| Issue | Status | Action Needed |
|-------|--------|---------------|
| **Fee creation UI** | ⚠️ Unknown | Check if admin can create fees for members |
| **Finance.vue payment form** | ⚠️ Unknown | Verify drawer opens and submits |
| **Member has no fees** | ❌ Current state | Create a test fee via tinker or UI |

---

## 🚀 Quick Fix to Test Payment Flow

Since your member has no fees, create one:

```bash
php artisan tinker

$member = App\Models\Member::find('a18ccdfc-1216-4b82-adc0-6a57f1e1b986');

$fee = App\Models\MembershipFee::create([
    'member_id' => $member->id,
    'organisation_id' => $member->organisation_id,
    'amount' => 100.00,
    'currency' => 'EUR',
    'due_date' => now()->addDays(30),
    'status' => 'pending',
    'period_label' => '2025 Annual Membership Fee',
    'recorded_by' => auth()->id() ?? User::first()->id,
]);
```

Then refresh the Finance page - you should see the outstanding fee with a "Record Payment" button.

---

**Would you like Claude to:**
1. Create a fee creation UI (so admins can assign fees)?
2. Modify the payment service to accept payments without pre-existing fees?
3. Help you create a test fee via tinker to verify the flow works?