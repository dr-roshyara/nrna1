# Developer Guide: Member Lifecycle & Suspensions Domain

## 1. Domain Architecture Overview

The "Public Digit" platform handles membership management for multi-tenant NGO and electronic voting ecosystems. To enforce corporate governance rules and keep business logic clean, we explicitly isolate the **Member Suspension** sub-domain using Domain-Driven Design (DDD), Test-Driven Development (TDD), and Command Query Responsibility Segregation (CQRS).

```
[ Frontend: Vue 3 / Pinia ] 
         │  (HTTP / JSON REST API)
         ▼
[ Backend: Laravel API Controllers ]
         │
         ▼
[ Application Layer: Command Handlers ] ──(Dispatches)──► [ Domain Events ]
         │                                                    │
         ▼                                                    ▼
[ Domain Layer: Member Aggregate Root ] ──────────────► [ Projection / Read Models ]
         │
         ▼
[ Infrastructure: PostgreSQL Database ]

```

### Core Architectural Invariants

* **Tenant Isolation:** A member lifecycle modification (such as a suspension) must only ever mutate state within its own database-isolated tenant context. Cross-tenant state mutation is strictly prevented.
* **Aggregate Integrity:** State transitions (e.g., `Active` $\rightarrow$ `Suspended` $\rightarrow$ `Reinstated`) are managed exclusively through the `Member` Aggregate Root. Direct database updates bypassing domain methods are forbidden.
* **CQRS Separation:**
* **Commands:** Handled via backend application services which process invariants, trigger state mutations, persist database changes via Eloquent, and fire `MemberSuspended` or `MemberReinstated` domain events.
* **Queries:** Dedicated read projections optimize lists and tables for performance, keeping operational query times exceptionally low.



---

## 2. Test Architecture & Coverage

The reliability of this sub-domain relies on complete end-to-end test separation between backend state invariants and frontend user actions.

### Backend Testing Strategy (PHPUnit / Pest)

* **Coverage:** 26 comprehensive backend unit/feature tests with 200 clean assertions.
* **Responsibility:** Asserts domain state validation, event dispatching, multi-tenant isolation, error boundary cases (e.g., trying to suspend an already suspended member), and valid API response structures.

### Frontend Testing Strategy (Vitest / Vue Test Utils)

* **Coverage:** Validates component mounting, UI element state (e.g., rendering buttons based on permission levels), validation alerts, modal toggle mechanics, and clean asynchronous API integrations.

---

## 3. Step-by-Step Frontend Implementation Guide

When transitioning from green backend tests to a fully functioning client UI, use this step-by-step blueprint to fix typical integration errors in your frontend components (such as `Management.vue`).

### Step 3.1: Define Component State & Reactive Variables

In your `<script setup>` block, declare the necessary states to handle the suspension UI modal and tracking loading conditions.

```typescript
import { ref, reactive } from 'vue';

const isSuspensionModalOpen = ref(false);
const isSubmitting = ref(false);
const selectedMember = ref<any>(null);

const suspensionForm = reactive({
  reason: '',
  endDate: '',
  notifyMember: true
});

```

### Step 3.2: Implement API Actions

Ensure actions cleanly communicate with the backend REST endpoints. Catch HTTP exceptions robustly to bubble errors back up to the user interface.

```typescript
const openSuspensionModal = (member: any) => {
  selectedMember.value = member;
  isSuspensionModalOpen.value = true;
};

const handleSuspendMember = async () => {
  if (!selectedMember.value) return;
  
  isSubmitting.value = true;
  try {
    const response = await fetch(`/api/v1/members/${selectedMember.value.id}/suspend`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: JSON.stringify(suspensionForm)
    });

    if (!response.ok) {
      throw new Error('Failed to update member lifecycle status.');
    }

    // Refresh member list, trigger success toasts, close modal
    isSuspensionModalOpen.value = false;
    resetForm();
  } catch (error) {
    console.error('Domain Action Error:', error);
    // Bind to UI error notification state
  } finally {
    isSubmitting.value = false;
  }
};

const resetForm = () => {
  suspensionForm.reason = '';
  suspensionForm.endDate = '';
  suspensionForm.notifyMember = true;
  selectedMember.value = null;
};

```

### Step 3.3: Wire the Template Triggers

Expose action buttons and a structured modal layout inside the component template. Make sure to map elements exactly to test wrappers using semantic `data-testid` properties.

```html
<!-- Member Row Actions Menu -->
<button 
  @click="openSuspensionModal(member)"
  class="btn btn-warning"
  data-testid="suspend-member-btn"
>
  Suspend Member
</button>

<!-- Suspension Workflow Modal -->
<div v-if="isSuspensionModalOpen" class="modal-overlay" data-testid="suspension-modal">
  <div class="modal-content">
    <h3>Suspend Member: {{ selectedMember?.name }}</h3>
    
    <form @submit.prevent="handleSuspendMember">
      <div class="form-group">
        <label for="reason">Suspension Reason</label>
        <textarea 
          id="reason" 
          v-model="suspensionForm.reason" 
          required
          data-testid="suspension-reason-input"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="endDate">End Date (Optional)</label>
        <input 
          type="date" 
          id="endDate" 
          v-model="suspensionForm.endDate"
          data-testid="suspension-date-input"
        />
      </div>

      <div class="modal-actions">
        <button type="button" @click="isSuspensionModalOpen = false" :disabled="isSubmitting">Cancel</button>
        <button type="submit" class="btn-danger" :disabled="isSubmitting" data-testid="confirm-suspension-btn">
          {{ isSubmitting ? 'Processing...' : 'Confirm Suspension' }}
        </button>
      </div>
    </form>
  </div>
</div>

```

---

## 4. Troubleshooting & Debugging Playbook

### 4.1 "Element not found" / "Cannot call click on undefined" (Vitest)

* **Root Cause:** The test wrapper is executing a query selector (e.g., `find('[data-testid="suspend-member-btn"]')`) before the component conditional rendering evaluates to true, or the specific attribute is missing in the markup.
* **Resolution Check:**
1. Verify `data-testid` names match perfectly between `Management.spec.ts` and `Management.vue`.
2. If the button relies on a user permission flag (e.g., `v-if="user.canSuspend"`), confirm that your test wrapper passes the matching mocked user object or prop during initialization.



### 4.2 "Method not allowed (405)" or "Not Found (404)" on API Calls

* **Root Cause:** A route mismatch in the frontend action block or an incomplete backend router specification.
* **Resolution Check:**
1. Run `php artisan route:list --path=api` on the backend to confirm the exact URI pattern and HTTP verb (e.g., `POST api/v1/members/{member}/suspend`).
2. Double-check your dynamic frontend URL template strings to guarantee no literal `undefined` or `null` values are leaking into route strings (e.g., `/api/v1/members/undefined/suspend`).



### 4.3 Multi-Tenancy Boundary Violations (PostgreSQL Level)

* **Root Cause:** A database query is pulling or updating records across tenants because the current tenant context global scope isn't correctly resolved before running domain operations.
* **Resolution Check:**
1. Inspect local test execution logs or active container connections.
2. Verify that your application controller or event payload resolves the tenant context early:
```php
// Ensure current tenant scope is initialized
TenantManager::setTenant($request->tenant_id);

```





```

### 4.4 Async Discrepancy In Component Updates (Vitest)
* **Root Cause:** Dom state testing changes immediately after checking an asynchronous state change, causing assertions to fire prematurely before the virtual DOM re-renders.
* **Resolution Check:**
  * Use `await wrapper.setValue()` or call `await nextTick()` right before firing assertions on structural template changes:
    ```typescript
await button.trigger('click');
await nextTick();
expect(wrapper.find('[data-testid="suspension-modal"]').exists()).toBe(true);

```

---

## 5. Development Workflow Recommendations

When continuing feature additions within the lifecycle sub-domain, preserve high quality and speed with these simple rules:

1. **Backend First:** Always verify that backend business invariants are fully covered in Pest/PHPUnit tests before writing any frontend code.
2. **Mock API Handlers in Tests:** When checking component UI in Vitest, explicitly isolate your UI tests from live servers by cleanly mocking service modules or network layers (e.g., using MSW or direct wrapper method spy overrides).
3. **Verify Local Infrastructure Configuration:** Ensure your isolated developer setup runs database migrations cleanly inside Podman using matching runtime environments to guarantee consistency before pushing code changes up to your Git pipelines.