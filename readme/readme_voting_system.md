# 🗳️ **NRNA Election System Architecture**
*Complete System Understanding*

---

## 📊 **1. DATABASE ARCHITECTURE**

### **1.1 User Model (Identity & Eligibility)**
- **Purpose**: Stores user identity and basic eligibility status
- **Key Fields**:
  - `is_voter`: Boolean flag (false = regular user, true = registered voter)
  - `can_vote`: Boolean flag (false = unverified, true = committee-approved)
  - `is_committee_member`: Boolean flag for administrative privileges
  - `approvedBy`: Tracks which committee member approved the voter
  - Basic user info: name, email, address, NRNA ID, etc.

### **1.2 Code Model (Anonymization & Process Control)**
- **Purpose**: Acts as anonymization layer between user identity and voting process
- **Core Concept**: Once voting starts, the system works with Code records, not User records
- **Key Fields**:
  - `user_id`: Links to user (but votes never link back to user)
  - `code1`, `code2`: Multi-step verification codes (hashed)
  - `can_vote_now`: Controls immediate access to voting session
  - `has_voted`: Final completion flag
  - `has_agreed_to_start_vote`: Legal agreement completion
  - `voting_started_at`: Session start timestamp
  - `voting_time_in_minutes`: Session duration (20 minutes)
  - `vote_submitted`: Final submission confirmation
  - `vote_show_code`: Post-election verification receipt
  - `client_ip`: Security and audit tracking
  - Time tracking: `code1_used_at`, `code2_used_at`, `vote_submitted_at`
  - Usage flags: `is_code1_usable`, `is_code2_usable`, `has_code1_sent`, `has_code2_sent`

### **1.3 Vote Model (Anonymous Ballot Storage)**
- **Purpose**: Stores actual vote data completely separated from user identity
- **Key Fields**:
  - Vote data: candidate selections, ballot choices
  - `vote_show_code`: Unique receipt code for post-election verification
  - `submitted_at`: Timestamp of final submission
- **Critical Security**: No direct reference to user_id - maintains ballot secrecy

### **1.4 Election Settings Model**
- **Purpose**: Controls system-wide election status and configurations
- **Key Settings**:
  - `election_active`: Whether voting is currently open
  - `election_completed`: Whether election has ended
  - `results_published`: Whether results are available to public
  - Start/end times, configuration parameters

---

## 👥 **2. USER ROLES & PERMISSIONS**

### **2.1 Regular Users**
- Default state when account is created
- Cannot access voting system
- Can view public information (candidates, posts, general info)

### **2.2 Registered Voters**
- Users with `is_voter = true`
- Still cannot vote until committee approval
- Can see voter-specific information

### **2.3 Verified Voters**
- Users with `is_voter = true` AND `can_vote = true`
- Approved by election committee members
- Can access the complete voting process
- Tracked by `approvedBy` field for audit

### **2.4 Committee Members**
- Users with `is_committee_member = true`
- Can approve/reject voter applications
- Access to administrative functions
- Can manage election settings (depending on permissions)

---

## 🔄 **3. MULTI-STEP VOTING PROCESS**

### **3.1 Eligibility Gate**
- **Check 1**: User must have `is_voter = true`
- **Check 2**: User must have `can_vote = true` (committee approved)
- **Result**: If both true, user can proceed to voting process

### **3.2 Step 1: Initial Access Request**
- User clicks "Vote Here" button on dashboard
- System creates or retrieves Code record for user
- Generates random 6-character `code1`
- Sends `code1` via email to user
- Sets flags: `is_code1_usable = true`, `has_code1_sent = true`
- Code expires after 30 minutes if not used

### **3.3 Step 2: Email Verification**
- User enters `code1` from email
- System verifies code against hashed version in database
- If valid: sets `can_vote_now = true`, `is_code1_usable = false`
- Records `code1_used_at` timestamp
- User advances to agreement page

### **3.4 Step 3: Legal Agreement**
- System displays legal agreement for online voting
- User must click "I Agree" to proceed
- Sets `has_agreed_to_start_vote = true`
- Records `voting_started_at = now()`
- Starts 20-minute voting session timer

### **3.5 Step 4: Active Voting Session**
- User can now access actual ballot
- Has 20 minutes to complete voting process
- Can select candidates for various positions
- System tracks session time remaining

### **3.6 Step 5: First Submission (Temporary Storage)**
- User submits their candidate selections
- Votes stored temporarily in session (not permanently saved yet)
- System generates and sends `code2` via email
- Sets flags: `is_code2_usable = true`, `has_code2_sent = true`
- User cannot go back to change selections

### **3.7 Step 6: Final Confirmation**
- User enters `code2` to confirm final submission
- System verifies `code2`
- If valid: permanently saves vote data to Vote model
- Generates unique `vote_show_code` for receipt
- Sets flags: `has_voted = true`, `vote_submitted = true`
- Records `vote_submitted_at` timestamp

### **3.8 Step 7: Receipt Generation**
- System emails `vote_show_code` to user as receipt
- This code can be used post-election to verify vote was counted
- Voting process is now complete

---

## 🔐 **4. SECURITY & ANONYMIZATION**

### **4.1 Ballot Secrecy**
- **Anonymization Layer**: Code model breaks link between user identity and vote
- **No Direct Connection**: Vote records never contain user_id
- **Receipt System**: `vote_show_code` allows verification without identity exposure
- **Temporal Separation**: Identity verification happens before vote storage

### **4.2 Multi-Factor Verification**
- **Code1**: Verifies email access and user intent
- **Code2**: Confirms final submission and prevents accidental voting
- **Time-Limited Codes**: All codes expire to prevent replay attacks
- **Hashed Storage**: All codes stored as hashes, never plain text

### **4.3 IP-Based Security**
- **Rate Limiting**: Maximum votes per IP address (configurable)
- **Audit Logging**: All actions logged with IP address and timestamp
- **User Agent Tracking**: Browser fingerprinting for additional security
- **Suspicious Activity Detection**: Multiple failed attempts trigger alerts

### **4.4 Session Management**
- **Time Limits**: 20-minute voting sessions prevent coercion
- **Session Expiry**: Automatic cleanup of expired sessions
- **One-Time Process**: Once voted, cannot vote again
- **State Validation**: Each step validates previous steps completed

---

## 📧 **5. EMAIL NOTIFICATION SYSTEM**

### **5.1 Code1 Email (Access Verification)**
- Sent when user requests ballot access
- Contains 6-character verification code
- Instructions in Nepali and English
- Security warnings and help information

### **5.2 Code2 Email (Final Confirmation)**
- Sent after first vote submission
- Contains confirmation code for final submission
- Warning that this finalizes the vote
- Cannot change selections after this point

### **5.3 Receipt Email (Vote Confirmation)**
- Sent after successful vote submission
- Contains `vote_show_code` for verification
- Instructions for post-election verification
- Thank you message

### **5.4 Administrative Emails**
- Voter approval/rejection notifications
- Committee member alerts
- System status notifications

---

## 🎛️ **6. ADMINISTRATIVE CONTROLS**

### **6.1 Election Management**
- **Start Election**: Activate voting system
- **End Election**: Close voting, begin result processing
- **Publish Results**: Make results available to users
- **Emergency Controls**: Suspend system if needed

### **6.2 Voter Management**
- **Approve Voters**: Committee members can verify and approve voters
- **Reject Applications**: Deny voting access with reasons
- **Bulk Operations**: Handle multiple approvals efficiently
- **Audit Trail**: Track all approval actions

### **6.3 System Monitoring**
- **Real-Time Statistics**: Vote counts, turnout percentages
- **Session Monitoring**: Active voting sessions
- **Error Tracking**: Failed operations and system issues
- **Security Alerts**: Suspicious activities and rate limit violations

---

## 💻 **7. FRONTEND ARCHITECTURE**

### **7.1 Dynamic Dashboard**
- **Voting Button State**: Changes based on user eligibility and voting status
- **Session Timer**: Shows remaining time during active voting
- **Status Messages**: Clear feedback on current state
- **Multi-Language Support**: Nepali and English throughout

### **7.2 Voting Flow Pages**
- **Code Entry Page**: Input verification codes
- **Agreement Page**: Legal consent for online voting
- **Ballot Page**: Candidate selection interface
- **Confirmation Page**: Review selections before final submission
- **Receipt Page**: Show completion and receipt code

### **7.3 Error Handling**
- **Eligibility Errors**: Clear messages for access denied
- **Process Errors**: Guidance for recovery from failures
- **Timeout Handling**: Graceful handling of session expiry
- **Network Issues**: Retry mechanisms and offline detection

---

## 📊 **8. RESULT MANAGEMENT**

### **8.1 Result Calculation**
- **Anonymous Processing**: Results calculated from Vote model only
- **No Identity Links**: Impossible to trace votes back to voters
- **Real-Time Counting**: Results available immediately after election end
- **Verification Support**: `vote_show_code` allows individual verification

### **8.2 Publication Control**
- **Staged Release**: Results not visible until officially published
- **Access Control**: Only authorized users can view results initially
- **Public Release**: Full results available after publication flag set
- **Audit Support**: Complete audit trail of result processing

---

## 🚨 **9. SECURITY PRINCIPLES**

### **9.1 Defense in Depth**
- **Multiple Validation Layers**: User, Code, and Vote model checks
- **Time-Based Controls**: Session limits and code expiry
- **IP-Based Limits**: Prevent abuse from single source
- **Email Verification**: Confirm user controls email account

### **9.2 Audit and Transparency**
- **Complete Logging**: Every action logged with timestamp and IP
- **Immutable Records**: Vote data cannot be modified after submission
- **Public Verification**: Voters can verify their vote was counted
- **Committee Oversight**: All administrative actions tracked

### **9.3 Privacy Protection**
- **Ballot Secrecy**: No way to connect vote to voter identity
- **Data Minimization**: Only necessary data collected and stored
- **Secure Transmission**: All communications encrypted
- **Access Controls**: Role-based access to sensitive functions

---

## 🎯 **10. SYSTEM STATES & TRANSITIONS**

### **10.1 User States**
1. **Unregistered** → Register → **Registered User**
2. **Registered User** → Apply for Voting → **Voter Applicant**  
3. **Voter Applicant** → Committee Approval → **Verified Voter**
4. **Verified Voter** → Complete Voting → **Voted User**

### **10.2 Voting Process States**
1. **Eligible** → Request Access → **Code1 Sent**
2. **Code1 Sent** → Verify Code → **Access Granted**
3. **Access Granted** → Accept Agreement → **Session Active**
4. **Session Active** → Submit Votes → **Confirmation Pending**
5. **Confirmation Pending** → Verify Code2 → **Vote Completed**

### **10.3 Election States**
1. **Preparation** → Start Election → **Active Voting**
2. **Active Voting** → End Election → **Processing Results**
3. **Processing Results** → Publish → **Results Available**

---

This architecture ensures **secure, anonymous, auditable, and user-friendly electronic voting** while maintaining the highest standards of election integrity and voter privacy.