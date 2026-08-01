# 📋 **Claude Prompt Instructions: Update Demo Election Selection Logic**

## **Task:** Modify `DemoCodeController` to Implement Priority-Based Demo Election Selection

### **Current Behavior (Bug)**
Currently, when `getElection()` falls back from middleware, it selects  the demo election with no organisation_id 
 (`where('type', 'demo', organisation_id=null)->first()`) .

### **Desired Behavior (Fix)**
Implement this priority order:

```
1️⃣ FIRST CHOICE: Demo election with user's organisation_id (if exists)
2️⃣ SECOND CHOICE: Platform-wide demo (organisation_id = NULL) if available
3️⃣ FAIL: Only throw error if NEITHER exists
```

---

## 📁 **File to Modify**
`app/Http/Controllers/Demo/DemoCodeController.php`

---

## 🔧 **Code Changes Required**

### **Replace the `getElection()` method:**

**Current (Buggy):**
```php
private function getElection(Request $request): Election
{
    return $request->attributes->get('election')
        ?? Election::withoutGlobalScopes()->where('type', 'demo')->first();
}
```

**New (Fixed):**
```php
/**
 * Get election with PRIORITY-BASED selection:
 * 
 * PRIORITY 1: Election from middleware (if valid for user's org)
 * PRIORITY 2: Demo election with user's organisation_id (if exists)
 * PRIORITY 3: Platform-wide demo (organisation_id = null)
 * 
 * Only fails if NO demo election exists at all
 */
private function getElection(Request $request): Election
{
    // Priority 1: Use election from middleware if it exists AND is valid
    $election = $request->attributes->get('election');
    $user = $this->getUser($request);
    
    if ($election) {
        // Verify the election belongs to user's organisation
        if ($user->organisation_id === $election->organisation_id) {
            Log::info('✅ Using election from middleware', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
                'election_id' => $election->id,
                'election_org_id' => $election->organisation_id,
            ]);
            return $election;
        }
        
        Log::warning('⚠️ Election from middleware has wrong org, will find correct one', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
            'election_id' => $election->id,
            'election_org_id' => $election->organisation_id,
        ]);
        // Fall through to find correct election
    }
    
    // Priority 2 & 3: Find appropriate demo election
    $query = Election::withoutGlobalScopes()->where('type', 'demo');
    
    if ($user->organisation_id !== null) {
        // 👥 USER HAS ORGANISATION - Try to find org-specific demo first
        $orgDemo = (clone $query)->where('organisation_id', $user->organisation_id)->first();
        
        if ($orgDemo) {
            Log::info('✅ Found org-specific demo election', [
                'user_id' => $user->id,
                'user_org_id' => $user->organisation_id,
                'election_id' => $orgDemo->id,
            ]);
            return $orgDemo;
        }
        
        // No org-specific demo found - fall back to platform demo
        Log::info('⚠️ No org-specific demo found, will try platform demo', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id,
        ]);
    }
    
    // Priority 3: Platform-wide demo (organisation_id = null)
    $platformDemo = (clone $query)->whereNull('organisation_id')->first();
    
    if ($platformDemo) {
        Log::info('✅ Using platform-wide demo election', [
            'user_id' => $user->id,
            'user_org_id' => $user->organisation_id ?? 'null',
            'election_id' => $platformDemo->id,
        ]);
        return $platformDemo;
    }
    
    // ❌ NO DEMO ELECTIONS EXIST AT ALL
    Log::error('❌ No demo elections found in database', [
        'user_id' => $user->id,
        'user_org_id' => $user->organisation_id,
    ]);
    
    throw new \Exception('No demo election available. Please create a demo election first.');
}
```

---

## 📝 **Add Logging for Debugging**

Add this at the beginning of the method for better traceability:

```php
private function getElection(Request $request): Election
{
    $user = $this->getUser($request);
    
    Log::info('🎯 [DemoCodeController] Selecting demo election', [
        'user_id' => $user->id,
        'user_org_id' => $user->organisation_id,
        'has_middleware_election' => $request->attributes->has('election'),
    ]);
    
    // ... rest of the method
}
```

---

## 🧪 **Test Cases to Verify**

After implementing, test these scenarios:

### **Scenario 1: User with Org + Org-Specific Demo Exists**
```
Input: 
- User org_id = 15
- Demo election with org_id = 15 exists
- Platform demo exists

Expected: Selects org-specific demo (org_id = 15)
```

### **Scenario 2: User with Org + NO Org-Specific Demo**
```
Input:
- User org_id = 15
- NO demo election with org_id = 15
- Platform demo exists (org_id = null)

Expected: Selects platform demo (org_id = null)
```

### **Scenario 3: User with Org + NO Demos At All**
```
Input:
- User org_id = 15
- NO demo elections in database at all

Expected: Throws helpful exception
```

### **Scenario 4: Default User (org_id = null)**
```
Input:
- User org_id = null
- Platform demo exists (org_id = null)

Expected: Selects platform demo
```

### **Scenario 5: Default User + NO Platform Demo**
```
Input:
- User org_id = null
- NO platform demo (org_id = null)
- But org-specific demos exist for other orgs

Expected: Throws exception (should not see other orgs' demos)
```

---

## 📊 **Decision Matrix Summary**

| User Has Org? | Org Demo Exists? | Platform Demo Exists? | Result |
|---------------|------------------|----------------------|--------|
| ✅ Yes | ✅ Yes | ✅ Yes | **Org Demo** |
| ✅ Yes | ✅ Yes | ❌ No | **Org Demo** |
| ✅ Yes | ❌ No | ✅ Yes | **Platform Demo** |
| ✅ Yes | ❌ No | ❌ No | **ERROR** |
| ❌ No | N/A | ✅ Yes | **Platform Demo** |
| ❌ No | N/A | ❌ No | **ERROR** |

---

## 🚀 **Implementation Steps**

1. Open `app/Http/Controllers/Demo/DemoCodeController.php`
2. Locate the `getElection()` method (around line 250-270)
3. Replace with the new implementation above
4. Add the logging code
5. Test all scenarios

---

## ✅ **Expected Outcome**

After changes:
- Users see the **most appropriate** demo election
- Organisation-specific demos get **priority** when they exist
- Platform demo serves as **fallback** for all users
- Clear errors when **no demo elections exist**

This maintains proper tenant isolation while providing the best user experience!