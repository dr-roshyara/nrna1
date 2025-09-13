# 📝 **Election System Implementation TODOs**

## **Phase 1: Database Schema Updates**

### **1.1 Update Users Table**
- [ ] Add migration to add `is_voter` boolean column (default: false)
- [ ] Add migration to add `is_committee_member` boolean column (default: false)
- [ ] Update User model to include these fields in `$fillable`
- [ ] Add User model relationships:
  ```php
  public function voter()
  public function electionCommitteeMember()
  ```

### **1.2 Update Voters Table**
- [ ] Add migration to add `can_vote` boolean column (default: false)
- [ ] Add migration to add `voting_region` string column (nullable)
- [ ] Add migration to add `approved_at` timestamp column (nullable)
- [ ] Add migration to add `approved_by` foreign key to election_committee_members (nullable)
- [ ] Add migration to add `is_active` boolean column (default: true)
- [ ] Update Voter model relationships:
  ```php
  public function approvedBy()
  public function user()
  public function approvals()
  ```

### **1.3 Create Election Committee Members Table**
- [ ] Create migration for `election_committee_members` table:
  ```php
  - id (Primary Key)
  - user_id (Foreign Key to users)
  - role (enum: 'chief_commissioner', 'secretary', 'commissioner')
  - appointed_at (timestamp)
  - is_active (boolean, default: true)
  - permissions (json)
  - created_at, updated_at
  ```
- [ ] Create ElectionCommitteeMember model
- [ ] Add relationships to ElectionCommitteeMember model:
  ```php
  public function user()
  public function approvedVoters()
  public function voterApprovals()
  ```

### **1.4 Create Voter Approvals Table**
- [ ] Create migration for `voter_approvals` table:
  ```php
  - id (Primary Key)
  - voter_id (Foreign Key to voters)
  - committee_member_id (Foreign Key to election_committee_members)
  - action (enum: 'approved', 'rejected', 'suspended', 'reactivated')
  - reason (text, nullable)
  - previous_status (boolean)
  - new_status (boolean)
  - ip_address (string)
  - created_at, updated_at
  ```
- [ ] Create VoterApproval model
- [ ] Add relationships to VoterApproval model

---

## **Phase 2: Model Setup & Relationships**

### **2.1 Update Existing Models**
- [ ] Update User model with new fields and relationships
- [ ] Update Voter model with new fields and relationships
- [ ] Add model scopes for filtering (active voters, approved voters, etc.)

### **2.2 Create New Models**
- [ ] Create ElectionCommitteeMember model with relationships
- [ ] Create VoterApproval model with relationships
- [ ] Add necessary model factories for testing
- [ ] Add model observers if needed (for automatic logging)

---

## **Phase 3: Permissions & Roles System**

### **3.1 Define Permission System**
- [ ] Create permissions configuration file
- [ ] Define committee roles and their permissions:
  ```php
  'chief_commissioner' => ['view_voters', 'approve_voters', 'suspend_voters', 'view_approval_logs', 'manage_committee']
  'secretary' => ['view_voters', 'approve_voters', 'view_approval_logs']
  'commissioner' => ['view_voters', 'approve_voters']
  ```
- [ ] Create helper methods in ElectionCommitteeMember model for permission checking

### **3.2 Middleware Setup**
- [ ] Create `CommitteeAuth` middleware to check committee member access
- [ ] Create `VoterEligibility` middleware to check voter access to ballot
- [ ] Update route groups with appropriate middleware

---

## **Phase 4: Controller Logic**

### **4.1 Committee Management Controller**
- [ ] Create ElectionCommitteeController
- [ ] Add methods:
  ```php
  - index() // List committee members
  - store() // Add new committee member
  - update() // Update committee member role/permissions
  - destroy() // Remove committee member
  ```

### **4.2 Voter Approval Controller**
- [ ] Create VoterApprovalController
- [ ] Add methods:
  ```php
  - index() // List voters pending approval
  - show() // Show voter details
  - approve() // Approve single voter
  - reject() // Reject single voter
  - suspend() // Suspend voter
  - batchApprove() // Approve multiple voters
  - approvalHistory() // Show approval audit trail
  ```

### **4.3 Update Existing Controllers**
- [ ] Update VoterController to include approval status
- [ ] Update VoteController to check voter eligibility
- [ ] Add voter eligibility checks to ballot access

---

## **Phase 5: Routes Setup**

### **5.1 Committee Routes**
- [ ] Add committee management routes:
  ```php
  Route::group(['middleware' => ['auth', 'committee']], function() {
      Route::resource('committee', ElectionCommitteeController::class);
      Route::get('voters/pending', [VoterApprovalController::class, 'index']);
      Route::post('voters/{voter}/approve', [VoterApprovalController::class, 'approve']);
      Route::post('voters/{voter}/reject', [VoterApprovalController::class, 'reject']);
      Route::post('voters/batch-approve', [VoterApprovalController::class, 'batchApprove']);
  });
  ```

### **5.2 Voter Access Routes**
- [ ] Update voting routes with eligibility middleware:
  ```php
  Route::group(['middleware' => ['auth', 'voter-eligible']], function() {
      Route::get('/vote/create', [VoteController::class, 'create']);
      // ... other voting routes
  });
  ```

---

## **Phase 6: Frontend Components**

### **6.1 Committee Dashboard**
- [ ] Create CommitteeDashboard.vue component
- [ ] Add voter approval queue interface
- [ ] Add batch approval functionality
- [ ] Add search and filter capabilities

### **6.2 Voter Management Interface**
- [ ] Create VoterList.vue component with approval actions
- [ ] Create VoterDetail.vue component for detailed review
- [ ] Create ApprovalHistory.vue component for audit trail
- [ ] Add status indicators (pending, approved, rejected, suspended)

### **6.3 Update Existing Components**
- [ ] Update voting components to show eligibility status
- [ ] Add voter status display in user profile
- [ ] Update navigation to include committee links for authorized users

---

## **Phase 7: Business Logic & Validation**

### **7.1 Validation Rules**
- [ ] Create form requests for committee member creation
- [ ] Create validation for voter approval actions
- [ ] Add business rule validations (can't approve self, etc.)

### **7.2 Service Classes**
- [ ] Create VoterApprovalService for approval logic
- [ ] Create CommitteePermissionService for permission checking
- [ ] Create AuditLogService for tracking all actions

---

## **Phase 8: Testing & Security**

### **8.1 Database Seeders**
- [ ] Create seeder for committee members
- [ ] Create seeder for test voters
- [ ] Update existing seeders to include new fields

### **8.2 Security Implementation**
- [ ] Add IP logging for all approval actions
- [ ] Add rate limiting for approval actions
- [ ] Add CSRF protection for sensitive operations
- [ ] Add audit logging for all committee actions

### **8.3 Testing**
- [ ] Write feature tests for approval workflow
- [ ] Write unit tests for permission system
- [ ] Write tests for voter eligibility checks
- [ ] Test batch operations

---

## **Phase 9: UI/UX Implementation**

### **9.1 Committee Interface**
- [ ] Design committee dashboard layout
- [ ] Add approval action buttons with confirmations
- [ ] Implement real-time status updates
- [ ] Add notification system for approvals

### **9.2 Voter Status Interface**
- [ ] Show voter status in user dashboard
- [ ] Display approval timeline
- [ ] Add appeal process if needed

---

## **Phase 10: Documentation & Deployment**

### **10.1 Documentation**
- [ ] Document API endpoints
- [ ] Create user manual for committee members
- [ ] Document permission system
- [ ] Create troubleshooting guide

### **10.2 Final Setup**
- [ ] Run all migrations in sequence
- [ ] Seed initial committee members
- [ ] Test complete workflow end-to-end
- [ ] Deploy to staging environment

---

## **🚀 Quick Start Priority Order:**

1. **Database Setup** (Phase 1 - Most Critical)
2. **Model Relationships** (Phase 2)
3. **Basic Controller Logic** (Phase 4.2)
4. **Simple Frontend** (Phase 6.1 basic version)
5. **Test Complete Workflow**
6. **Add Security & Validation**
7. **Polish UI/UX**

This gives you a clear roadmap to implement the election committee approval system step by step!