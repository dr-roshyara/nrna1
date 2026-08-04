# Architecture Knowledge Transfer (AKT) — Part 7

**Implementation Standards**

| | |
|---|---|
| **Artifact** | Architecture Knowledge Transfer (AKT) — **Part 7 of 10 + Appendix** |
| **Part** | **Part 7 — Implementation Standards** |
| **Baseline** | `composer merge-gate` **PASS** · **266 tests / 665 assertions / 0 failures** · Architecture **149 green** · Deptrac **0** · PHPStan greenfield **max clean** · mutation baseline **MSI 50% / coverage 77% / strength 65%** (non-blocking) |
| **Version** | 1.0 |
| **Status** | **Living Architecture Reference.** *Lifecycle: **AUTHORED**, not ISSUED* |
| **Audience** | AI Architects · Principal Engineers · Architecture Review Board |
| **Authored** | 2026-08-01 · branch `feature/pb003` · HEAD `622c515d4` |
| **Prerequisite** | **Part 4** (the principles these standards enforce) · **Part 6** (where things go) |

---

## 0. Scope

**What a contributor must actually do, mechanically, to produce acceptable work.** Conventions, commands, gates, file shapes.

> ### **The standard that governs all the others: *every class, enum, port, mapper, event, migration and test answers "which authority requires this?" — no authority → do not create it.***

---

## 1. Backend coding standards

### 1.1 Layering — Laravel with discipline

| Layer | Laravel features | Enforcement |
|---|---|---|
| **Domain** | ❌ **ZERO Laravel dependencies. Pure PHP only** | **Deptrac: `…Domain: ~` — depends on NOTHING, not even `Shared`** |
| **Application** | ⚠️ **no Facades** (constructor injection) · **no Eloquent** (repository interface) · **no route binding** (explicit `findOrFail`) · **DTOs required, never arrays** · domain events allowed | **Deptrac: own Domain + `Shared` only** |
| **Infrastructure** | ✅ Facades, Eloquent, route binding, Eloquent events, SoftDeletes, traits | **Deptrac: own Domain + own Application + `Shared`** |
| **HTTP** | controllers, FormRequests, Resources | — |

**Class finality:** **Domain — all classes `final`** · **Application — Command/Handler classes `final`** · Infrastructure — optional · Controllers — may be non-final.

**Exceptions by layer:** `DomainException` (user sees the message) · `ApplicationException` (user sees it) · `RuntimeException` in Infrastructure (**logs only; 500**).

**Events:** **domain events for business logic** · **Eloquent events for infrastructure** (cache invalidation, logging). *They coexist; do not substitute one for the other.*

### 1.2 Value Objects and DTOs

```php
final readonly class Email {
    private function __construct(private string $value) {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Invalid email: {$value}");
        }
    }
    public static function fromString(string $value): self { return new self($value); }
    public function toString(): string { return $this->value; }
}
```

**Private constructor + named static factory + validation in the constructor** is the house shape. **VODP governs whether the VO is justified at all** (Part 4 §6.3): *truth → invariant → concepts needed* — **never fields-that-travel-together.**

**⭐ A live constraint from WP-7 worth generalizing:** ***the VO takes BUSINESS VALUES only — never a port, config, model or clock.*** *This is what keeps a value object a value.*

### 1.3 CQRS-light — the decision tree

```
Simple CRUD?              → YES → Eloquent + Controller (the Laravel way)
Complex business rules?   → YES → Domain + Command + Repository
Read-only?                → YES → Eloquent + Resource
Write with validation?    → YES → FormRequest + Command + Repository
```

**Repositories for AGGREGATES ONLY** (ADR-T6 + RMSP): **one repository per aggregate ROOT** · **no repo-per-table, no generic repo** · non-root reads via read models · **query convenience belongs to read models.**

> **The golden rule: *use Laravel for what Laravel is good at. Use Clean Architecture for what you need to protect. Know the difference.***

### 1.4 Cross-context code rules (non-negotiable, machine-enforced)

| Rule | |
|---|---|
| **No consumer imports another context's `Domain`** | Deptrac: violation by omission |
| **No consumer imports another context's `Infrastructure`** | including hydrators |
| **The hydrator is producer-side** | never the consumer's reconstruction mechanism |
| **Consumers reconstruct LOCAL VOs from primitives** | |
| **Wire payloads carry primitives only** | strings · enum backing values · ISO-8601 · flat arrays of strings |
| **Provenance travels on the ENVELOPE** | never in the domain event |
| **Identity crosses as an opaque STRING** | `Contestation\DeterminationId` ≠ `Adjudication\DeterminationId` |
| **Idempotency at TWO seats** | inbox dedupe `(event_id, consumer_context)` + handler/aggregate level |

### 1.5 Multi-tenancy in code

`BelongsToTenant` global scope · `TenantContext` middleware · `session('current_organisation_id')` · FKs on `organisation_id`. **`withoutGlobalScopes()` is the only bypass — treat any use as a review item.**

**Enforced by `scripts/enforce-tenantid.sh`, which runs as part of `composer test`.**

---

## 2. Frontend standards

### 2.1 Inertia 2.0 — the migration rule that breaks silently

```javascript
// ❌ WRONG (Inertia 1.0 — BROKEN NOW): returns 302 + HTML
import { useCsrfRequest } from './useCsrfRequest';
await csrfRequest.post('/endpoint', data);

// ✅ RIGHT (Inertia 2.0)
import { router } from '@inertiajs/vue3';
router.post('/endpoint', data, {
  preserveState: true, preserveScroll: true,
  onSuccess: (page) => {}, onError: (errors) => {}, onFinish: () => {},
});
```

| Aspect | Raw fetch ❌ | `router` ✅ |
|---|---|---|
| CSRF tokens | manual | **automatic (meta tags)** |
| `Accept` / `X-Requested-With` | not set | automatic |
| Redirects | returns HTML — **breaks the app** | handled |
| Flash messages | unavailable | `page.props.flash` |
| Status | **302** | 200 |

**Controller side:** `redirect()->route(...)->with('success', …)` on success; `back()->withErrors([...])` on failure. ⛔ **Never check `wantsJson()`** — Inertia 2.0 handles it. **Raw fetch only for standalone API endpoints not on Inertia pages.**

**Diagnostic:** *seeing `HTTP 302 Found` with an HTML body means you are using raw fetch where `router.post()` belongs.*

### 2.2 The design system is executable

**Before editing any `.vue` file:** read the Rule Levels (**MUST/SHOULD/MAY**) in `.claude/UI_GUIDELINES.md` · check `design-system.exceptions.json` for approved deviations · use **semantic tokens** (`primary`, `success`, `danger`, `neutral`) · use **canonical components** (`<Button>`, `<Card>`) · run `npm run design-check` · **report violations before/after in your response.**

> ### **The design system is executable. It's not aspirational. Don't work around it.**

**Supporting audits:** `audit-design-tokens.sh` · `check-design-tokens.sh` · `audit-page-structure.sh` · `unify-page-structure.sh` · `component-audit.sh` · `audit-seo-translations.sh` · `structure-check.sh` · configs `design-rules.json`, `ui-components.json`, `frontend-architecture.json`.

---

## 3. Testing standards

### 3.1 ⛔ The database rule — the user's development database is sacred

| ❌ NEVER | ✅ ALWAYS |
|---|---|
| `php artisan migrate:fresh` | `php artisan test` *(uses `RefreshDatabase` — safe)* |
| `php artisan migrate:refresh` | `php artisan test --env=testing` |
| `php artisan test --seed` | `use RefreshDatabase;` inside the test class |

**`RefreshDatabase` wraps each test in a transaction and rolls back — it does NOT affect the development database.** Test-specific setup goes **inside** `setUp()` after `parent::setUp()`, so the transaction wraps it.

### 3.2 The five test suites

| Suite | Path | Purpose |
|---|---|---|
| **Unit** | `tests/Unit` | domain logic |
| **Replay** | `tests/Replay` | projection rebuild (ADR-T9) |
| **Feature** | `tests/Feature` | HTTP / integration |
| **Architecture** | `tests/Architecture` | ⭐ **constitutional rules — 149 green** |
| **GreenfieldCore** | `tests/Unit/Contexts/{Contestation,Adjudication,…}` | the greenfield contexts |

### 3.3 Architecture tests verify PROPERTIES, not class names

`GreenfieldCoreArchitectureTest` scans for forbidden linkage tokens — **`user_id` · `voter_id` · `voterId` · `voting_code` · `votingCode`** — across Contestation + Adjudication + Election (*Election joined at PB-004 Step 4C once it became a complete Hexagonal context*).

**And the guard-hosting rule (ADR-MP-03):** **the suite hosting an invariant's guard is the suite owned by the invariant's OWNER.** ⛔ **A preserver must never host an owner's guarantee** — *live violation: **AD-M1***.

### 3.4 RED-first discipline

**Phase 10:** failing tests expressing **business invariants, policies, transitions, rules and architectural constraints** — **not implementation detail.** **Phase 11 GREEN: the minimum satisfying RED** — no speculative abstraction, **no anticipating later work packages.**

**Report at the RED boundary before writing production code.** *WP-1 recorded "10 keystone failures"; WP-2 "25 tests / 25 expected errors"; 7A "11/11 keystones".* **Confirm they fail FOR THE EXPECTED REASONS.**

**⚠️ And a RED variant that needs explicit authorization:** *amending an existing PASSING test is a **different act** from adding failing ones.* **7C's RED includes amending `AuditCleanupTest`, and that is gated on decision C-3** — see Part 8.

### 3.5 Tenant testing (RULE 9)

**Unit tests MUST test tenant isolation logic** · **always test with multiple `TenantId` values** · **mock repositories MUST enforce tenant boundaries** · integration tests use actual tenant connections · **test tenant switching explicitly.**

### 3.6 Mutation testing — measured, non-blocking

**Baseline (validated, F-7D-2): MSI 50% · Mutation Code Coverage 77% · Test Strength 65%.** Run via `composer quality-gate` (Infection). **Non-blocking tier — an A-3 ratchet policy.** *Measurement is the deliverable; a threshold is not asserted.*

---

## 4. The gates — exact commands

### 4.1 `composer merge-gate` — the one that matters

```bash
composer merge-gate
```

Runs, in order:

```
1. phpunit --testsuite=Architecture                     # constitutional fitness
2. deptrac analyse --no-progress                        # boundaries + hexagonal layers
3. phpstan analyse -c phpstan-greenfield.neon           # max level, greenfield scope
   --memory-limit=1G
4. phpunit --testsuite=GreenfieldCore                   # widened regression
→ "MERGE GATE: PASS (Architecture fitness / Deptrac / greenfield PHPStan / widened regression)"
```

### 4.2 The rest

| Command | Does |
|---|---|
| `composer test` | `artisan test` **+ `bash scripts/enforce-tenantid.sh`** |
| `composer quality-gate` | coverage + Infection mutation (**non-blocking**) |
| `npm run design-check` | the executable design system |
| `npm run knowledge-lint` / `:strict` | knowledge cards + links **under `docs/knowledge/` ONLY** |
| `npm run knowledge-graph` | regenerate the graph |
| `npm run docs:placement` / `:verify` | **resolve** a documentation location |
| `npm run lint` / `lint:fix` | ESLint over `resources/js` |
| `npm run type-check` | `vue-tsc --noEmit` |
| `npm run test` / `test:coverage` | Vitest |
| `npm run verify` | `scripts/verify.sh` |
| `npm run migration-progress` | migration progress |
| `php scripts/link-check.php` | repo-wide link integrity + the confidence model |
| `bash scripts/check-domain-purity.sh` | domain-layer purity |

**Husky is installed (`npm run prepare`).** **Deptrac runs via the `qossmic/deptrac-shim` PHAR (deptrac 1.0.2)** — *the canonical Composer package is unresolvable against this dependency graph; same tool, same gate.*

### 4.3 ⚠️ What the gates do NOT cover — state this whenever you cite them

> **"Deptrac 0 / 149 green / PHPStan max clean" does NOT cover the defect class *"a business value was invented."***
>
> **AP-1** (`max(1,$days)`) and **AP-2** (a MAD key in a retention config) **passed every automated gate.**
>
> **Coverage arithmetic (WP-7 guard commission): of 11 constraints, 5 executably enforced, 7 manual** *(3 cheaply automatable, 4 accepted manual with reasons)*. **One named hole: a PSR `ClockInterface` would enter the EPW value object undetected.**
>
> **Structural gates scan ONLY `app/Contexts/{Contestation,Adjudication,Election,Shared}`.** *`app/Console/Commands/` and `app/Helpers/` have zero structural coverage.*

**And per R-53: a recorded gate result is a CLAIM, not a guarantee, unless it was reproduced.** *Always cite a gate figure **with its ruling**.*

---

## 5. Document conventions

### 5.1 Plans (ES-004.2)

```
docs/plans/YYYYMMDD-HHMM-<what_is_it_about>-plan.md
```

**Contents:** Objective · Background · Scope · Design decisions · Task checklist · Progress · Risks · Open questions · Next actions. **A superseding plan cites the superseded plan's filename in its traceability.** ⛔ **Never randomly-generated filenames.** *Plans are living documents — update them rather than creating new ones.*

**Work-package plans live at `.claude/plans/WP-n-<slug>.md`** and their **Status line is the authoritative slice state** (Part 1 §7.5).

**EEP §4 minimum plan contents:** Objective · **Governing decision** · Scope · Files affected · Risks · **Verification strategy** (*HOW*) · **Evidence** (*WHAT KIND* — the exact artifacts are recorded in the report) · **Out of scope.**

### 5.2 Session logs

```
.claude/sessions/YYYY-MM-DD.md     ⛔ APPEND-ONLY (ES-004.2 / AIP-11)
```

**Sections:** Summary · Completed · Decisions · Problems · Next Steps. **Append to the current day's log; never create a second log for the same day.**

### 5.3 Developer guides — part of the Definition of Done

**Every implementation step ships a guide.** `developer_guide/<area>/` for existing areas; **new guides go under `./docs`** per the legacy ruling.

**One file per step, numbered for reading order** (`00_index.md`, `01_step_….md`, …), with `00_index.md` mapping the steps.

**Each guide covers:** purpose · where it fits (layer/namespace) · key files · design decisions (**with ADR/D-refs**) · how it works (with code) · how to use/extend · testing · pitfalls · **a Traceability line.**

⛔ **Ground every snippet in the COMMITTED CODE — no invented APIs.** **Honour the invariants in the docs too** — *no voter↔vote linkage in examples (ADR-T11).* **Match the house voice of the area's existing guides.**

> **The ADR records the DECISION; the guide is the developer HOW-TO.** *Even when an ADR captures the design, still write the guide.*

### 5.4 Verification reports

```
engineering/verification/reports/YYYY-MM-DD-<subject>-<kind>.md
```

**Kinds observed in use:** `-commission` · `-verification` · `-report` · `-record` · `-package` · `-review` · `-finding` · `-assessment` · `-red-report` / `-green-report` / `-acceptance-record` / `-authorization-record` / `-ep01-approval-record`.

**⛔ The naming boundary is load-bearing** (Part 1 §5.3): a **PACKAGE** is prepared *before* and holds **blank** templates; a **RECORD** is written *after* and holds rulings. ***Do not name a package a record.***

**Standard header:** date · prepared-by (role) · authority · rulings · evidence source (**cited, not restated**) · **Repository Integrity Gate: PASS/FAIL**.

**Standard practices inside:** **state the review SCOPE before findings** · **report by constitutional category first, severity second** · **record limits without qualifying the decision** · **name the guard and show it satisfied** · **ask the honest-failure-direction question** · **negative claims state what was checked, over what scope, with what instrument.**

### 5.5 Knowledge cards (`docs/knowledge/`)

**Required frontmatter:** `knowledge_id` (stable, `PREFIX-ID`) · `title` · `knowledge_type` · `bounded_context` · `status` (**lifecycle**) · `authority` (**trust/source — INDEPENDENT of status**) · `owner`. **Recommended:** `audience`, `tags`. **Typed relationships** (`implements`, `requires`, `depends_on`, `derived_from`) become **edges in the knowledge graph**.

**⚠️ The template states the trap in its own comments:** ***"A bounded context is not a domain. For WHERE the document belongs: `php scripts/doc-placement.php --list`."***

**AI rule:** AI-generated knowledge enters under `docs/knowledge/ai/` as **`authority: generated`** and is ⛔ **NEVER authoritative without human review.**

### 5.6 ADR conventions

**One architectural question per ADR** · **class it** (ADR-S/UL/PL/PC/T/IM/AIP) · **ER-06 ordering** (UL → PL → contract → implementation) · **record rejected alternatives** · **superseding is explicit and forward-only** — *the superseded ADR remains valid history, never edited* · **status annotations are permitted; decision text is not touched** · **ADR-AIP files live in `engineering/architecture/adr/`, never interleaved with domain ADRs.**

### 5.7 Commit conventions

**Observed shape:** `type(scope): summary` — `docs(verification):` · `docs(pks):` · `docs(governance):` · `docs(<area>):`.

**The Blueprint's binding governance rule for the greenfield Core:** ⛔ **no PR may be merged unless every changed class can be traced to Blueprint section(s), the applicable ADR(s), and a Traceability Matrix row.** *If an implementation cannot be traced: **STOP and request an architecture review — do not introduce a new design decision.***

**Commit the developer guide WITH the step** (or as a paired `docs(<area>): …` commit).

### 5.8 Closure synchronization (ES-004.3 / R-41)

**At every slice closure, run the ROLE-BASED checklist:**

| Role | Artifact | Rule |
|---|---|---|
| **Runtime** | plan status + `CONTEXT.md` | states the **PRESENT** state |
| **Historical** | session log | ⛔ **append-only** |
| **Reference** | developer guide | current |
| **Decision** | ADR status annotations + acceptance record | **decision text never rewritten** |

⛔ **Synchronization touches only the MUTABLE portion. History is never rewritten to manufacture consistency.**

---

## 6. Link integrity, migrations and the confidence model

### 6.1 The confidence bar

| Confidence | Basis | Action |
|---|---|---|
| **100** | git rename record · documented migration · exact existing target | **auto-apply** |
| **99** | **exactly one** file in the repo carries that basename | **auto-apply** |
| **75** | several candidates | ⛔ **AMBIGUOUS — evidence, never a repair** |
| **0** | no candidate | ⛔ **MISSING — evidence, never a repair** |

**Only ≥99 auto-applies. Every rule requires the destination to EXIST before rewriting. NEVER invent a target.**

### 6.2 The declarative migration registry

**`docs/knowledge/schema/repository-migrations.yaml`** (id · from · to · kind · source ADR), read by **`scripts/link-check.php`**. ⭐ **Adding a future migration is a REGISTRY ENTRY, not a code change.**

**On record:** `architecture/` → `architecture_legacy/` · repository-root AI-generated developer docs → `developer_guide/` *(repository-root file only · EXACT filename · relocated file must exist · **no other candidate may exist**)*.

> ***A DOCUMENTED MIGRATION is evidence; a FILENAME HEURISTIC is not.***

### 6.3 Closure wording

⛔ **Never** *"all broken links were fixed."* ✅ ***"All deterministic link repairs have been completed. Remaining unresolved references have been classified and transferred to backlog or governance."***

---

## 7. The documentation generation workflow

```
1. CLASSIFY the artifact      Scope · Steward · Maturity · Domain
2. RESOLVE the location       php scripts/doc-placement.php --scope=… [--maturity=…] [--domain=…]
                              exit 2 → record PENDING, ESCALATE. Never invent.
3. WRITE it                   correct kind (package vs record), correct header,
                              evidence CITED not restated
4. VALIDATE                   npm run knowledge-lint   (⚠️ docs/knowledge/ ONLY)
                              record the baseline BEFORE and AFTER
5. LINK-CHECK                 php scripts/link-check.php   (repo-wide; ≥99 only)
6. SYNCHRONIZE at closure     ES-004.3 role-based checklist
7. COMMIT                     docs(<area>): … , with the code step or paired
```

**⚠️ Step 4's baseline discipline is not optional.** *Recorded instance: `knowledge-lint` baseline was **9 errors / 0 warnings**; a first pass took it to **10** (a new `docs/knowledge/` doc needs a knowledge card). **The linter caught it; it was fixed.*** **Without a recorded before-figure you cannot tell your own regression from a pre-existing one.**

---

## 8. Standing prohibitions — the short list

| ⛔ | |
|---|---|
| **Never** `migrate:fresh` / `migrate:refresh` / `test --seed` on the development database | |
| **Never** raw `fetch` for form submissions on an Inertia page | |
| **Never** import another context's `Domain` or `Infrastructure` | |
| **Never** put Laravel in the Domain layer | |
| **Never** pass arrays into the Application layer | |
| **Never** create a repository per table, or a generic repository | |
| **Never** hard-code a documentation root | |
| **Never** invent a link target, a destination, a severity value, or a ruling number | |
| **Never** restate a rule that has a canonical home — **reference it** | |
| **Never** write rule text, governance prose or historical commentary into executable tooling | |
| **Never** rewrite a session log, a decision text, or history | |
| **Never** create a class, test or migration with no answering authority | |
| **Never** report a gate figure without its ruling / date | |

---

## Traceability

**Primary sources (read at authoring):** `composer.json` (**`test` · `merge-gate` · `quality-gate` scripts verbatim**) · `package.json` (npm scripts) · `phpunit.xml` (the five suites) · `deptrac.yaml` · `phpstan-greenfield.neon` · `scripts/` inventory (25 files) · `tests/Architecture/GreenfieldCoreArchitectureTest.php` · `docs/knowledge/_meta/knowledge-card.template.md` · `CLAUDE.md` (project + `.claude/`: layering rules, Inertia 2.0 rules, database testing, developer-guide DoD, RULE 9) · `.claude/UI_GUIDELINES.md` (referenced) · `engineering/governance/ES-004-Documentation.md` · `Engineering_Execution_Protocol.md` §4 · `.claude/IMPLEMENTATION_PROTOCOL.md` (phases 6, 10, 11) · `docs/implementation/PushB_Architecture_Blueprint.md` (the traceability merge rule) · `docs/publicdigit/WP-7C_Engineering_Readiness.md` (the VO constraint, the C-3-gated amendment) · `docs/adr/README.md` (ADR classes, ER-06) · `.claude/MEMORY.md` (confidence bar, migration registry, lint baseline discipline) · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (**R-41 · R-53**) · `docs/implementation/PROGRAM_STATUS.md` (mutation baseline).

**Supersedes:** nothing. **Depends on:** Parts 1–6.
