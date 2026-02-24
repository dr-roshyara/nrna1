# API Endpoints & Backend Requirements

---

## 📑 Table of Contents

1. [Implemented Endpoints](#implemented-endpoints) ✅
2. [Phase 2 Endpoints (To Implement)](#phase-2-endpoints-to-implement) 🚧
3. [Phase 3 Endpoints (Planned)](#phase-3-endpoints-planned) 📋
4. [Database Migrations](#database-migrations)
5. [Error Handling](#error-handling)
6. [Authentication & Authorization](#authentication--authorization)

---

## ✅ Implemented Endpoints

### **Member Import Endpoint**

**Endpoint**: `POST /organizations/{slug}/members/import`

**Purpose**: Import members from CSV or Excel file

**Authentication**: Required (logged-in user)

**Authorization**: User must have `manage` permission on organization

**Request Headers**:
```
X-CSRF-TOKEN: {token}
Content-Type: application/json
Accept: application/json
```

**Request Body**:
```json
{
  "headers": ["Email", "First Name", "Last Name", "Phone", "Region"],
  "rows": [
    {
      "Email": "john.doe@example.com",
      "First Name": "John",
      "Last Name": "Doe",
      "Phone": "+49123456789",
      "Region": "Bayern"
    },
    {
      "Email": "jane.smith@example.com",
      "First Name": "Jane",
      "Last Name": "Smith",
      "Phone": "+49987654321",
      "Region": "Baden"
    }
  ],
  "fileName": "members.csv"
}
```

**Response** (200 OK):
```json
{
  "success": true,
  "imported_count": 2,
  "skipped_count": 0,
  "created_count": 2,
  "updated_count": 0,
  "message": "2 members imported successfully"
}
```

**Response** (422 Unprocessable Entity):
```json
{
  "success": false,
  "errors": {
    "rows.0.email": ["Email must be unique"],
    "rows.1.phone": ["Invalid phone format"]
  },
  "message": "Validation failed"
}
```

**Response** (419 Unauthorized):
```json
{
  "message": "CSRF token mismatch"
}
```

**Backend Implementation Location**:
```
app/Http/Controllers/Organizations/MemberImportController.php
app/Http/Requests/ImportMembersRequest.php
app/Services/MemberImportService.php
```

**Steps**:
```php
1. Validate CSRF token ✅ (handled by middleware)
2. Check authorization ✅ (check user permission)
3. Validate file data (re-validate on server)
4. Check for duplicates (email uniqueness)
5. Check for existing members (by email)
6. Create or update members
7. Log import activity
8. Return import statistics
```

---

## 🚧 Phase 2 Endpoints (To Implement)

### **1. Get Organization Stats**

**Endpoint**: `GET /organizations/{slug}/api/stats`

**Purpose**: Fetch organization statistics

**Response** (200 OK):
```json
{
  "members_count": 150,
  "active_members_count": 145,
  "elections_count": 8,
  "active_elections_count": 2,
  "completed_elections": 6,
  "new_members_30d": 15,
  "exited_members_30d": 2
}
```

---

### **2. Appoint Election Officer**

**Endpoint**: `POST /organizations/{slug}/election-officer/appoint`

**Purpose**: Appoint election officer (BGB §26 compliance)

**Request**:
```json
{
  "member_id": 42,
  "deputy_member_id": 43,
  "expiration_date": "2027-12-31"
}
```

**Response** (200 OK):
```json
{
  "success": true,
  "officer": {
    "id": 789,
    "member_id": 42,
    "organization_id": 1,
    "appointed_at": "2026-02-22T10:30:00Z",
    "expires_at": "2027-12-31",
    "is_active": true
  },
  "deputy": {
    "id": 790,
    "member_id": 43,
    "appointed_at": "2026-02-22T10:30:00Z",
    "expires_at": "2027-12-31"
  },
  "message": "Election officer appointed successfully"
}
```

**Validation**:
```
- Member must exist in organization
- Member must be active
- Officer cannot already be appointed
- Deputy must be different from officer
- Expiration date must be in future
```

---

### **3. Create Election**

**Endpoint**: `POST /organizations/{slug}/elections/create`

**Purpose**: Create new election

**Request**:
```json
{
  "name": "Board Election 2024",
  "type": "board",
  "start_date": "2026-03-01",
  "end_date": "2026-03-15",
  "voting_method": "online",
  "description": "Annual board election",
  "election_officer_id": 789,
  "positions": [
    {
      "name": "President",
      "required_candidates": 1,
      "is_national_wide": true
    },
    {
      "name": "Vice President",
      "required_candidates": 1,
      "is_national_wide": true
    }
  ],
  "candidates": [
    {
      "position_id": 1,
      "member_id": 50,
      "position_order": 1
    }
  ]
}
```

**Response** (200 OK):
```json
{
  "success": true,
  "election": {
    "id": 99,
    "organization_id": 1,
    "name": "Board Election 2024",
    "type": "board",
    "start_date": "2026-03-01",
    "end_date": "2026-03-15",
    "voting_method": "online",
    "status": "scheduled",
    "election_officer_id": 789,
    "created_at": "2026-02-22T10:30:00Z"
  },
  "message": "Election created successfully"
}
```

---

### **4. Get Compliance Status**

**Endpoint**: `GET /organizations/{slug}/api/compliance`

**Purpose**: Get BGB compliance status

**Response**:
```json
{
  "has_election_officer": true,
  "election_officer": {
    "id": 789,
    "member": {
      "id": 42,
      "name": "John Officer",
      "email": "john@example.com"
    },
    "appointed_at": "2026-02-22",
    "expires_at": "2027-12-31"
  },
  "deputy_officer": {
    "id": 790,
    "member": {
      "id": 43,
      "name": "Jane Deputy",
      "email": "jane@example.com"
    }
  },
  "checklist": {
    "wahlausschreibung": false,
    "waehlverzeichnis": false,
    "briefwahl": false,
    "wahlhelfer": false,
    "protocol": false
  }
}
```

---

### **5. Get Recent Activity**

**Endpoint**: `GET /organizations/{slug}/api/activity?limit=20`

**Purpose**: Get recent organization activity

**Response**:
```json
{
  "activity": [
    {
      "id": 1,
      "type": "member_imported",
      "user_id": 5,
      "user": {
        "name": "Admin User",
        "email": "admin@example.com"
      },
      "data": {
        "count": 50,
        "file_name": "members.csv"
      },
      "created_at": "2026-02-22T10:00:00Z"
    },
    {
      "id": 2,
      "type": "officer_appointed",
      "user_id": 5,
      "data": {
        "officer_name": "John Officer",
        "deputy_name": "Jane Deputy"
      },
      "created_at": "2026-02-21T15:30:00Z"
    },
    {
      "id": 3,
      "type": "election_created",
      "user_id": 5,
      "data": {
        "election_name": "Board Election 2024",
        "election_type": "board"
      },
      "created_at": "2026-02-20T12:00:00Z"
    }
  ]
}
```

---

## 📋 Phase 3 Endpoints (Planned)

### **Member Management**
```
GET    /organizations/{slug}/api/members
GET    /organizations/{slug}/api/members/{id}
POST   /organizations/{slug}/api/members
PUT    /organizations/{slug}/api/members/{id}
DELETE /organizations/{slug}/api/members/{id}
GET    /organizations/{slug}/api/members/export
```

### **Election Management**
```
GET    /organizations/{slug}/api/elections
GET    /organizations/{slug}/api/elections/{id}
PUT    /organizations/{slug}/api/elections/{id}
DELETE /organizations/{slug}/api/elections/{id}
GET    /organizations/{slug}/api/elections/{id}/results
```

### **Document Templates**
```
GET    /organizations/{slug}/api/documents/templates
GET    /documents/templates/{type}/download
```

---

## 🔄 Database Migrations

### **Members Table**
```sql
CREATE TABLE members (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  organization_id BIGINT NOT NULL,
  user_id BIGINT,
  email VARCHAR(255) NOT NULL,
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  phone VARCHAR(255),
  region VARCHAR(255),
  status ENUM('active', 'inactive', 'exited') DEFAULT 'active',
  joined_at TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  UNIQUE KEY unique_org_email (organization_id, email),
  FOREIGN KEY (organization_id) REFERENCES organizations(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
)
```

### **Election Officers Table**
```sql
CREATE TABLE election_officers (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  organization_id BIGINT NOT NULL,
  member_id BIGINT NOT NULL,
  role ENUM('officer', 'deputy') DEFAULT 'officer',
  appointed_by_user_id BIGINT,
  appointed_at TIMESTAMP,
  expires_at TIMESTAMP,
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (organization_id) REFERENCES organizations(id),
  FOREIGN KEY (member_id) REFERENCES members(id),
  FOREIGN KEY (appointed_by_user_id) REFERENCES users(id)
)
```

### **Elections Table**
```sql
CREATE TABLE elections (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  organization_id BIGINT NOT NULL,
  election_officer_id BIGINT,
  name VARCHAR(255) NOT NULL,
  type ENUM('board', 'deputy', 'auditor', 'amendment'),
  voting_method ENUM('online', 'mail', 'mixed'),
  status ENUM('draft', 'scheduled', 'active', 'completed', 'cancelled'),
  description TEXT,
  start_date TIMESTAMP,
  end_date TIMESTAMP,
  created_at TIMESTAMP,
  updated_at TIMESTAMP,
  FOREIGN KEY (organization_id) REFERENCES organizations(id),
  FOREIGN KEY (election_officer_id) REFERENCES election_officers(id)
)
```

### **Import Logs Table**
```sql
CREATE TABLE import_logs (
  id BIGINT PRIMARY KEY AUTO_INCREMENT,
  organization_id BIGINT NOT NULL,
  user_id BIGINT,
  import_type ENUM('members', 'candidates'),
  file_name VARCHAR(255),
  total_rows INT,
  imported_count INT,
  skipped_count INT,
  error_details JSON,
  status ENUM('success', 'partial', 'failed'),
  created_at TIMESTAMP,
  FOREIGN KEY (organization_id) REFERENCES organizations(id),
  FOREIGN KEY (user_id) REFERENCES users(id)
)
```

---

## 🛡️ Error Handling

### **Common Error Responses**

#### **400 Bad Request**
```json
{
  "message": "Invalid request",
  "errors": {
    "field": ["Error message"]
  }
}
```

#### **401 Unauthorized**
```json
{
  "message": "Unauthenticated"
}
```

#### **403 Forbidden**
```json
{
  "message": "This action is not authorized",
  "error_code": "PERMISSION_DENIED"
}
```

#### **404 Not Found**
```json
{
  "message": "Resource not found"
}
```

#### **422 Unprocessable Entity**
```json
{
  "message": "The given data was invalid",
  "errors": {
    "email": ["Email already exists"],
    "phone": ["Phone format invalid"]
  }
}
```

#### **429 Too Many Requests**
```json
{
  "message": "Too many requests. Please slow down.",
  "retry_after": 60
}
```

#### **500 Internal Server Error**
```json
{
  "message": "Server error occurred",
  "error_id": "550e8400-e29b-41d4-a716-446655440000"
}
```

---

## 🔐 Authentication & Authorization

### **Authentication**

**Methods**:
- Session-based (for web)
- Sanctum tokens (for mobile/API)

**Headers**:
```
Authorization: Bearer {token}
X-CSRF-TOKEN: {token}
```

---

### **Authorization Checks**

#### **Organization Access**
```php
// User must be member of organization
$user->organizations()->where('id', $org->id)->exists()
```

#### **Permission Checks**
```php
// For management operations
$user->can('manage', $organization)

// For viewing organization
$user->can('view', $organization)

// For creating elections
$user->can('create_elections', $organization)
```

#### **Multi-Tenant Scope**
```php
// All queries must include organization scope
Member::where('organization_id', $org->id)->get()
```

---

## 📊 Rate Limiting

### **Endpoints Rate Limits**

```
Member Import:       10 per hour per organization
Election Creation:   50 per day per user
Officer Appointment: 20 per day per user
Activity API:        100 per minute
```

---

## 🔄 CSRF Protection

### **Required on All POST/PUT/DELETE**

**Token Source**:
1. Meta tag: `<meta name="csrf-token">`
2. Cookie: `XSRF-TOKEN`
3. Header: `X-CSRF-TOKEN`

**Automatic Handling** (via useCsrfRequest):
```javascript
const csrfRequest = useCsrfRequest()
await csrfRequest.post('/endpoint', data)
// Token automatically added
```

---

## 📝 Request/Response Examples

### **Member Import - Full Flow**

**1. Frontend sends file data**:
```javascript
const response = await csrfRequest.post(
  `/organizations/nrna/members/import`,
  {
    headers: ['Email', 'First Name', 'Last Name'],
    rows: [
      { Email: 'john@example.com', 'First Name': 'John', 'Last Name': 'Doe' },
      { Email: 'jane@example.com', 'First Name': 'Jane', 'Last Name': 'Smith' }
    ],
    fileName: 'members.csv'
  }
)
```

**2. Backend validation**:
```php
// Validate CSRF
// Check authorization
// Re-validate file data
// Check for duplicates
// Create members
// Log activity
// Return statistics
```

**3. Frontend receives response**:
```json
{
  "success": true,
  "imported_count": 2,
  "message": "2 members imported successfully"
}
```

**4. Frontend shows success**:
```
✓ Success!
"2 members imported successfully"
[Back to Organization]
```

---

## 🧪 Testing API

### **Using Postman/Insomnia**

1. **Get CSRF Token**:
   ```
   GET /organizations/nrna
   Extract: <meta name="csrf-token" content="{token}">
   ```

2. **Set Headers**:
   ```
   Authorization: Bearer {sanctum_token}
   X-CSRF-TOKEN: {token}
   Content-Type: application/json
   ```

3. **Send Request**:
   ```
   POST /organizations/nrna/members/import
   Body: {...}
   ```

---

## ✨ Implementation Checklist

### **Phase 2 Backend TODO**

```
🚧 Member Import Handler
  □ Validate file data
  □ Create/update members
  □ Handle duplicates
  □ Log import activity
  □ Return statistics

🚧 Officer Appointment
  □ Validate member
  □ Create officer record
  □ Set expiration
  □ Handle deputy
  □ Log activity

🚧 Election Creation
  □ Validate inputs
  □ Create election
  □ Create positions
  □ Handle candidates
  □ Log activity

🚧 Compliance Status
  □ Query officer status
  □ Check compliance items
  □ Format response

🚧 Activity Feed
  □ Query activity logs
  □ Format timeline
  □ Pagination
  □ Filtering
```

---

**Version**: 1.0.0
**Last Updated**: 2026-02-22
**Status**: Ready for Backend Implementation
