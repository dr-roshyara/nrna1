# 🔄 **PHASE INTEGRATION ARCHITECTURE**

## **1. INTEGRATION POINTS (WHERE Systems Connect)**

### **Connection Point 1: Phase 1 → Phase 2**
```
TRIGGER: All publishers seal container
LOCATION: Election model → startVoting() method
ACTION: Update phase from 'sealed' → 'voting'
WHO: Automatic when last publisher seals
```

### **Connection Point 2: Phase 2 → Phase 3**
```
TRIGGER: Voting end time reached
LOCATION: Election model → automatic transition
ACTION: Update phase from 'voting' → 'unsealing'  
WHO: Automatic based on voting_end_time
```

### **Connection Point 3: Phase 3 → Results Published**
```
TRIGGER: All publishers unseal container
LOCATION: Election model → completeAuthorization()
ACTION: Update phase from 'unsealing' → 'published'
WHO: Automatic when last publisher unseals
```

## **2. TRANSITION TRIGGERS (WHO & WHEN)**

### **Manual Triggers (Committee Actions)**
- **Start Sealing**: Committee clicks "Start Election Preparation"
- **Verify Results**: Committee clicks "Verify Results" (after voting ends)
- **Emergency Override**: Committee can force any transition

### **Automatic Triggers (System Actions)**
- **Start Voting**: When all publishers complete sealing
- **Start Unsealing**: When voting_end_time passes + results verified
- **Publish Results**: When all publishers complete unsealing

### **Time-Based Triggers**
- **Voting Start**: Based on election.voting_start_time
- **Voting End**: Based on election.voting_end_time  
- **Unsealing Deadline**: 24 hours after voting ends

## **3. INTEGRATION WORKFLOW**

```
PRE-ELECTION (Phase 1):
├─ Committee: "Start Election Preparation" 
├─ System: Creates authorization session
├─ Publishers: Seal empty container (via Vue.js)
├─ System: All sealed → phase = 'voting'
└─ TRANSITION: Voting system activated

DURING ELECTION (Phase 2):
├─ Your existing voting system runs
├─ System: Results blocked (phase = 'voting')
├─ Votes accumulate in database
├─ System: voting_end_time reached
└─ TRANSITION: phase = 'unsealing'

POST-ELECTION (Phase 3):
├─ Committee: "Verify Results"
├─ System: Creates new authorization session  
├─ Publishers: Unseal results (same Vue.js interface)
├─ System: All unsealed → phase = 'published'
└─ RESULT: Public can view results
```

## **4. DATABASE STATE MANAGEMENT**

### **Phase Tracking**
```sql
-- Current phase stored in elections.phase
UPDATE elections SET phase = 'sealed' WHERE id = ?;
UPDATE elections SET phase = 'voting' WHERE id = ?;
UPDATE elections SET phase = 'unsealing' WHERE id = ?;
UPDATE elections SET phase = 'published' WHERE id = ?;
```

### **Authorization Sessions**
```sql
-- Sealing session (Phase 1)
INSERT INTO result_authorizations (election_id, session_id, phase) VALUES (1, 'SEAL_2025_001', 'sealing');

-- Unsealing session (Phase 3)  
INSERT INTO result_authorizations (election_id, session_id, phase) VALUES (1, 'UNSEAL_2025_001', 'unsealing');
```

### **Progress Tracking**
```sql
-- Check sealing progress
SELECT COUNT(*) as agreed FROM result_authorizations 
WHERE session_id = 'SEAL_2025_001' AND agreed = true;

-- Check unsealing progress
SELECT COUNT(*) as agreed FROM result_authorizations 
WHERE session_id = 'UNSEAL_2025_001' AND agreed = true;
```

## **5. API ENDPOINTS NEEDED**

### **Committee Endpoints**
- `POST /api/election/start-preparation` - Begin sealing process
- `POST /api/election/verify-results` - Mark results as verified
- `GET /api/election/status` - Get current phase and progress

### **Publisher Endpoints**  
- `GET /publisher/authorize` - Show seal/unseal interface
- `POST /publisher/authorize` - Submit seal/unseal action
- `GET /api/authorization-progress` - Real-time progress updates

### **Public Endpoints**
- `GET /results` - View results (blocked by phase)
- `GET /api/election/phase` - Get current election phase

## **6. REAL-TIME UPDATES**

### **WebSocket Events**
- `authorization.progress` - Publisher completes action
- `phase.transition` - Phase changes
- `results.published` - Results become available

### **Cache Invalidation**
- Clear result cache when phase changes
- Update election status cache
- Refresh authorization progress data