# 🗳️ **NRNA Election System Architecture**
*Updated with Disconnection Handling*

---

## 🔌 **11. DISCONNECTION & SESSION RECOVERY** *(NEW SECTION)*

### **11.1 Disconnection Scenarios & Recovery**

#### **Scenario 1: Disconnection Before Agreement**
**State**: User has verified Code1 (`can_vote_now = true`) but hasn't accepted agreement yet
- **Session Validity**: 30 minutes from `code1_used_at` timestamp
- **Recovery Process**: 
  - User can return to system within 30 minutes
  - Automatically redirected to agreement page
  - No need to re-enter Code1
  - Session continues from where left off
- **After Expiry**: Must restart entire process (request new Code1)

#### **Scenario 2: Disconnection After Agreement, Before Voting**
**State**: Agreement accepted (`has_agreed_to_start_vote = true`), voting session started (`voting_started_at` set)
- **Session Validity**: 20 minutes from `voting_started_at` timestamp
- **Recovery Process**:
  - User can return and immediately access ballot
  - Session timer continues from original start time
  - Full 20-minute limit still applies from original start
  - Redirected directly to voting page
- **Data Preserved**: Agreement status, session start time
- **After Expiry**: Session terminated, must restart from Code1 generation

#### **Scenario 3: Disconnection During Ballot Completion**
**State**: User is actively selecting candidates on ballot page
- **Session Validity**: Remaining time from 20-minute limit
- **Recovery Process**:
  - Ballot selections stored in browser session storage
  - User can return and continue where left off
  - Previously selected candidates remain selected
  - Can modify selections until first submission
- **Data Preserved**: Candidate selections in browser session
- **Data Lost**: Any unsaved changes if browser closed completely
- **After Expiry**: Session terminated, all selections lost

#### **Scenario 4: Disconnection After First Submission, Before Code2**
**State**: Votes submitted temporarily (`code2_sent_at` set), Code2 email sent, waiting for confirmation
- **Session Validity**: 15 minutes from `code2_sent_at` for code entry
- **Recovery Process**:
  - User can return to Code2 entry page
  - Temporary vote data preserved in database session table
  - Can still enter Code2 to complete voting
  - Cannot return to ballot - selections are locked
- **Data Preserved**: 
  - Temporary vote selections in secure session storage
  - Code2 verification status
  - All process flags and timestamps
- **After Expiry**: 
  - Session expires, temporary vote data deleted
  - User cannot complete voting (security measure)
  - Must contact election committee for resolution

#### **Scenario 5: Disconnection During Final Submission**
**State**: Code2 verified, final vote submission in progress
- **Session Validity**: 5-minute grace period for completion
- **Recovery Process**:
  - System automatically retries final submission
  - If successful: vote saved, receipt generated
  - If failed: user can retry final submission
- **Data Preserved**: All vote data in transaction queue
- **After Expiry**: Manual intervention required by committee

### **11.2 Session State Management**

#### **Session Persistence Strategy**
- **Browser Session Storage**: Ballot selections during active voting
- **Database Session Table**: Temporary vote data between submissions
- **Code Model Flags**: Process state and timestamps
- **Server-Side Validation**: All critical state stored server-side

#### **Automatic Recovery Mechanisms**
- **Heartbeat System**: Background checks every 30 seconds during voting
- **Auto-Save**: Ballot selections saved every 2 minutes during completion
- **Connection Monitoring**: Detects disconnection and shows user status
- **Reconnection Handler**: Automatically resumes session when connection restored

#### **Session Expiry Handling**
- **Grace Periods**: Additional time for technical difficulties
- **Soft Expiry**: Warning at 5 minutes remaining
- **Hard Expiry**: Automatic session termination at time limit
- **Emergency Extension**: Committee can extend sessions for technical issues

### **11.3 Data Preservation Strategies**

#### **Client-Side Storage (Temporary)**
- **Browser Session Storage**: Active ballot selections
- **Automatic Clearing**: Cleared after successful submission or expiry
- **Security**: Encrypted data, cleared on browser close
- **Backup Frequency**: Every user interaction saved

#### **Server-Side Session Management**
- **Session Database Table**: Temporary storage between first and final submission
- **Encrypted Storage**: All temporary vote data encrypted
- **Automatic Cleanup**: Expired sessions automatically purged
- **Audit Trail**: All session state changes logged

#### **Recovery Data Structure**
```
Session Recovery Data:
├── User Authentication State
├── Process Step Completion Flags  
├── Time Stamps and Limits
├── Temporary Vote Selections
├── Code Verification Status
└── Network State Information
```

### **11.4 User Experience During Disconnection**

#### **Disconnection Detection**
- **Real-Time Monitoring**: JavaScript checks connection status
- **Visual Indicators**: Clear offline/online status display
- **User Notifications**: Immediate feedback when disconnected
- **Automatic Retry**: Attempts to reconnect automatically

#### **Reconnection Process**
- **Seamless Resume**: User returned to exact point in process
- **State Validation**: Server confirms session still valid
- **Data Integrity Check**: Verify no data corruption occurred
- **User Confirmation**: Ask user to confirm they want to continue

#### **User Interface Elements**
- **Connection Status Indicator**: Always visible during voting
- **Session Timer**: Shows remaining time, updates in real-time
- **Auto-Save Indicator**: Shows when selections are being saved
- **Recovery Messages**: Clear instructions for resuming process

### **11.5 Security Considerations for Disconnection**

#### **Session Hijacking Prevention**
- **Session Tokens**: Cryptographically secure session identifiers
- **IP Validation**: Session tied to original IP address
- **Browser Fingerprinting**: Additional validation of client identity
- **Timeout Enforcement**: Strict time limits prevent abuse

#### **Data Integrity Protection**
- **Checksums**: Verify data integrity after reconnection
- **Audit Logging**: All disconnection/reconnection events logged
- **Suspicious Activity Detection**: Multiple disconnections trigger alerts
- **Emergency Procedures**: Committee intervention for unusual patterns

#### **Anti-Tampering Measures**
- **Server-Side Validation**: All critical state stored server-side
- **Encrypted Transmission**: All data encrypted in transit
- **Tamper Detection**: Detect if client-side data modified
- **Session Invalidation**: Compromise detection terminates session

### **11.6 Committee Override Procedures**

#### **Manual Intervention Scenarios**
- **Technical Failures**: System-wide connectivity issues
- **Individual Emergencies**: Voter's technical difficulties
- **Session Corruption**: Data integrity issues
- **Extended Outages**: Power failures, network outages

#### **Committee Powers**
- **Session Extension**: Extend time limits for technical issues
- **Manual Completion**: Complete voting on behalf of user (with verification)
- **Session Reset**: Allow user to restart voting process
- **Emergency Procedures**: Handle exceptional circumstances

#### **Audit Requirements**
- **Justification Documentation**: Reason for intervention
- **Multiple Approvals**: Require multiple committee members
- **Complete Logging**: All manual actions fully audited
- **Post-Election Review**: All interventions reviewed

### **11.7 Network Resilience Features**

#### **Connection Quality Monitoring**
- **Bandwidth Detection**: Adjust interface based on connection speed
- **Latency Monitoring**: Warn users of poor connection quality
- **Stability Assessment**: Track connection drops and reconnections
- **Quality Recommendations**: Suggest optimal voting conditions

#### **Adaptive Interface**
- **Low-Bandwidth Mode**: Simplified interface for slow connections
- **Progressive Enhancement**: Core functionality works with any connection
- **Graceful Degradation**: Advanced features disabled on poor connections
- **Mobile Optimization**: Optimized for mobile network conditions

#### **Backup Communication Channels**
- **Multiple Email Routes**: Backup email servers for code delivery
- **SMS Fallback**: Text message codes if email fails
- **Phone Support**: Voice verification for critical failures
- **Alternative Access Points**: Multiple server locations

---

## 📊 **Updated Section 3: MULTI-STEP VOTING PROCESS** *(Revised)*

### **3.X Recovery Points in Voting Process**

Each step now includes recovery mechanisms:

#### **Step 1: Initial Access Request**
- **Recovery Window**: 30 minutes from code generation
- **Disconnection Handling**: Can restart from dashboard
- **Data Preserved**: Code1 generation timestamp

#### **Step 2: Email Verification** 
- **Recovery Window**: 30 minutes from code1 generation
- **Disconnection Handling**: Can re-enter code1 if still valid
- **Data Preserved**: Code1 verification status

#### **Step 3: Legal Agreement**
- **Recovery Window**: 30 minutes from code1 verification
- **Disconnection Handling**: Automatically redirected to agreement
- **Data Preserved**: Code verification, session eligibility

#### **Step 4: Active Voting Session**
- **Recovery Window**: Remaining time from 20-minute limit
- **Disconnection Handling**: Auto-save ballot selections
- **Data Preserved**: Candidate selections, session timer

#### **Step 5: First Submission**
- **Recovery Window**: 15 minutes from first submission
- **Disconnection Handling**: Return to Code2 entry page
- **Data Preserved**: Temporary vote data, code2 status

#### **Step 6: Final Confirmation**
- **Recovery Window**: 5 minutes grace period
- **Disconnection Handling**: Automatic retry mechanisms
- **Data Preserved**: Final vote data in transaction queue

---

## 🚨 **Updated Section 9: SECURITY PRINCIPLES** *(Enhanced)*

### **9.4 Disconnection Security** *(NEW)*

#### **Session Security During Disconnection**
- **Immediate Lockdown**: Sensitive operations paused during disconnection
- **State Verification**: All resumed sessions validated against server state
- **Tampering Detection**: Client-side data integrity checked on reconnection
- **Timeout Enforcement**: Strict time limits maintained regardless of disconnections

#### **Recovery Attack Prevention**
- **Session Token Rotation**: New tokens issued after each disconnection
- **IP Address Validation**: Sessions tied to original IP address
- **Multiple Disconnection Limits**: Excessive disconnections trigger security review
- **Audit Trail Enhancement**: All disconnection events logged for security analysis

---

## 🎯 **Updated Section 10: SYSTEM STATES & TRANSITIONS** *(Enhanced)*

### **10.4 Disconnection State Transitions** *(NEW)*

#### **Normal Flow with Disconnection Recovery**
1. **Active Session** → Disconnection → **Session Suspended**
2. **Session Suspended** → Reconnection → **Session Validated**
3. **Session Validated** → Resume → **Active Session**
4. **Session Suspended** → Timeout → **Session Expired**

#### **Security State Transitions**
1. **Trusted Session** → Suspicious Activity → **Session Under Review**
2. **Session Under Review** → Validation → **Trusted Session**
3. **Session Under Review** → Security Violation → **Session Terminated**

---

## 💡 **DISCONNECTION BEST PRACTICES**

### **For Voters**
- **Stable Connection**: Use reliable internet connection for voting
- **Device Preparation**: Ensure device is charged and stable
- **Browser Requirements**: Use supported browsers with JavaScript enabled
- **Backup Planning**: Note session recovery procedures before starting

### **For System Administrators**
- **Monitoring**: Real-time monitoring of disconnection patterns
- **Capacity Planning**: Ensure system can handle reconnection spikes
- **Support Procedures**: Clear escalation paths for technical issues
- **Testing**: Regular testing of disconnection recovery mechanisms

This enhanced architecture ensures **robust handling of network disconnections** while maintaining **security, integrity, and user experience** throughout the voting process.