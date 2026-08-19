# C-10 — D4.3 · Condition-specific response mapping · **PROPOSED, nothing decided**

**Work item:** KOS-AIP04-DISCOVERY-001 · **Prepared by:** Governance (`b64828fe`) · 2026-08-19
**Standing:** D1 · D2 (`NOT YET ESTABLISHED`) · D3 (Option C / SPLIT) · D4.1 (proportionate enforcement · effect vocabulary) · D4 (floor `WARN`+`RECORD`) · D4.2 (condition semantics)

> ⛔ **The act names no selected mapping.** Per §14 — *"Record only the decisions actually selected by PO/ARB"* — **nothing here is recorded as decided.** The table is `PROPOSED` decision-input.
> ⚠️ **Same producer disclosure as D4.2:** Governance-authored tactical modelling; `R-34`/`P-2` binds — I must not later verify or accept it.

---

## 1 · ⭐ The result, stated first

> ## **Every condition and sub-condition proposes `NO ENFORCEMENT RESPONSE YET`.**
> **Zero enforceable mappings are justified.** This is not an evasion — it is exactly what §10's four prerequisites produce given D2.

**§10 requires all four: trigger semantically defined · authoritative enough to act on · identifiable evidence source · required authority exists.**

| Prerequisite | How many of the 9 rows satisfy it |
|---|---|
| semantically defined | **9 / 9** ✅ *(D4.2 delivered this)* |
| authoritative trigger | **1 / 9** — `SUPERSEDED` only (**BC-1**) |
| identifiable evidence source | 2 / 9 |
| **currently detectable** | **0 / 9** |

⇒ **No row satisfies all four. `SUPERSEDED` comes closest and still fails: its authority exists (BC-1), its mechanism (`CAP-03`) is not built.**

## 2 · The mapping table

**`MISSING` is subdivided M1–M5 as required. `DISPUTED` is excluded from this table and treated as an orthogonal modifier in §3.**

| CONDITION | EVIDENCE REQUIRED | AUTHORITATIVE TRIGGER? | CURRENTLY DETECTABLE? | RESPONSE | RATIONALE | OPEN DEPENDENCY |
|---|---|---|---|---|---|---|
| **M1** receipt never issued | absence of a receipt for (package version, execution) at the acknowledgement point | 🔴 **NO** — no receipt store exists | 🔴 **NO** | **NO ENFORCEMENT RESPONSE YET** | **§7 forbids enforcing on "an inference from absence alone" — and M1 *is* that inference.** Absence in an unrealized store is not evidence of absence (`C-1`) | a realized receipt store, after which absence becomes evidential |
| **M2** expected but absent | an applicability determination naming a required package **+** absence | 🔴 **NO** — applicability is **outside C-10** (D3.2), unowned | 🔴 **NO** | **NO ENFORCEMENT RESPONSE YET** | "expected" is an applicability judgement C-10 cannot make. **§4: do not silently convert to `STALE`/`MISSING` ⇒ status is `UNRESOLVED` / `NOT ATTESTED`** | applicability authority (`OQ-B`) |
| **M3** exists, not retrievable | retrieval error from the receipt store | 🟡 objective event, **but no store today** | 🔴 **NO** | **NO ENFORCEMENT RESPONSE YET** | **infrastructure condition, not a governance condition.** ⛔ Must never be reported as M1 (D4.1.4 laundering prohibition) | receipt store; **and whether M3 is inside C-10 at all** — D3 gives it lifecycle *state*, not storage availability |
| **M4** present, unbindable to the execution | ⛔ **none possible** — `INV-ATTR-1`; identity binding **outside** C-10 (D3.2) | 🔴 **NO — unownable today** | 🟡 the *inability* is detectable; the condition is not | ⛔ **`NOT ATTESTED` — mandatory (§2). NO enforcement response** | **doubly barred: §7 forbids enforcing on unverified identity, and D3.2 excludes identity binding from C-10** | identity attestation — `C-5`, itself `NO OWNER` with external attestation `NOT_ESTABLISHED` |
| **M5** knowledge itself absent | BC-1 publication record showing no published version | 🟢 **BC-1 owns it** | 🔴 **NO** — `CAP-03` not built | **NO ENFORCEMENT RESPONSE YET** | ⭐ **this is BC-1's condition, not C-10's.** Mapping an effect here would have C-10 gate on another context's state — crossing **D3.6** (*C-10 does not absorb knowledge publication*) | `CAP-03`; **and whether M5 belongs in C-10's mapping at all** |
| **STALE** | the act's version requirement **+** the receipt's named version | 🔴 **NO** — requires applicability to establish what the act *requires* | 🔴 **NO** | **NO ENFORCEMENT RESPONSE YET** | **§4 preserved: a newer version alone does NOT stale a receipt.** If applicability cannot be established ⇒ **`UNRESOLVED` / `NOT ATTESTED`**, never a silent `STALE` | applicability authority · **expiry semantics (D3.5, undecided)** · per-act vs global staleness |
| **SUPERSEDED** | BC-1 publication record naming predecessor → successor | ✅ **YES — BC-1** *(the only YES in the table)* | 🔴 **NO** — `CAP-03` not built | **NO ENFORCEMENT RESPONSE YET** | ⭐ **authority exists; mechanism does not. §10 requires both.** **§3 preserved: supersession ≠ staleness; the response depends on the act and on replacement availability AND authority** | `CAP-03` · replacement-authority semantics (§2.1) |
| **INVALIDATED** | the invalidation act **+ its authority reference** | 🔴 **NO — authority OPEN by D3** | 🔴 **NO** | **NO ENFORCEMENT RESPONSE YET** | ⛔ **No invalidation authority invented.** §5's three required specification slots are left unfilled — see §2.2 | **invalidation authority (D3)** |

### 2.1 · `SUPERSEDED` — the three replacement cases §3 requires kept separate

| Case | Note |
|---|---|
| replacement **available and authoritative** | the only case that could ever justify an enforcing effect — **and still needs `CAP-03`** |
| replacement **unavailable** | ⚠️ enforcing here would block work with **no remediation path** — the D4 §12 system-wide-consequence hazard in miniature |
| replacement exists, **applicability unresolved** | ⭐ **collapses into the applicability gap** — indistinguishable from `STALE`'s blocker, and equally unowned |

### 2.2 · `INVALIDATED` — the three slots §5 requires, unfilled but shaped by a real instance

§5 requires the eventual mapping to specify **what use is prevented · what historical evidence remains · whether a replacement receipt may be established.** ⛔ Not decided. **But this work item has already produced one worked instance of the shape:**

> **`ca6039a8`** — use prevented: **independent assurance** · evidence remaining: **the substantive findings F-1…F-7, intact** · replacement: **a fresh independent verification** (`S5`, still outstanding).

⭐ **It demonstrates the property the eventual mapping must preserve: invalidation is PURPOSE-SCOPED, not global — and `INVALIDATED` ≠ `DELETED` ≠ `FALSE`, exactly as §5 requires.** *(`OBSERVED`, one instance — evidence, not a standard.)*

## 3 · `DISPUTED` — orthogonal modifier, not a lifecycle row

**Deliberately excluded from §2's table.** A receipt may be **`VALID` + `DISPUTED`** · **`SUPERSEDED` + `DISPUTED`** · **`INVALIDATED` + `DISPUTED`** · **`MISSING` + `DISPUTED`**.

| | |
|---|---|
| **Evidence required** | the assertion **+ its basis** |
| **Authoritative trigger?** | 🔴 **NO** — who may raise is `OPEN`; **adjudication is outside C-10 (D3.2)** |
| **Currently detectable?** | 🔴 **NO** |
| **Response** | **NO ENFORCEMENT RESPONSE YET** |

**The effect, defined without deciding who adjudicates — as §6 requires (`PROPOSED`):**

> **A dispute suspends the receipt's use FOR THE DISPUTED CLAIM ONLY.** Its undisputed claims remain usable, and its status as historical evidence is unaffected.

This is definable without an adjudicator because it names *what is suspended*, not *who resolves it* — and it mirrors §2.2's purpose-scoping.

> ### ⚠️ **The effect cannot be adopted alone — it creates a denial-of-service surface**
> D4.2 §5 recorded the asymmetry: **raising a dispute needs far less evidence than resolving one.** If a dispute suspends use, then **an unqualified right to raise lets any party suspend any receipt indefinitely**, since resolution requires an adjudicator that does not exist. ⇒ **The effect and the "who may raise" gate must be decided together.** Adopting the effect while the raiser is `OPEN` would be unsafe.

**⭐ `MISSING` + `DISPUTED` is the sharpest combination:** disputing M1 means **disputing an inference from absence** — which §7 already bars enforcement on. **A dispute cannot make an unenforceable condition enforceable.**

## 4 · The floor, and what it is not

**D4's floor — `INFO` / `WARN` + `RECORD` — continues to apply as the STANDING BASELINE, not as a per-condition mapping.** Nothing in §2 or §3 raises it, and ⛔ **no global `BLOCK` or `HALT` is proposed** (§8, §12).

⚠️ **Proportionality note (D4.1's principle applied):** because **`M1` is effectively universal** (D2 ⇒ zero receipts), a `WARN` on every governed act would be **noise, not notification** — degrading the floor's value. **`INFO` + `RECORD` is the proportionate baseline until a receipt store exists and absence becomes meaningful.** ⛔ Proposed, not decided.

## 5 · ✅ The category-deciding flag is NOT reached by this act

**As promised at D4.1.5 and confirmed at D4.3 of the register:** the category-deciding moment arrives when any condition is mapped to `BLOCK` or `HALT + ESCALATE` (canonical §5.3 pt 13/23 — *control-plane if it gates START*).

> **No row proposes `BLOCK` or `HALT`. C-10 gates nothing. C-10 remains advisory. Its category remains undecided** — as §11 requires.

⚠️ **The flag stays live for whichever future act first maps an enforcing effect.**

## 6 · Not decided here

⛔ C-10 existence · category · bounded context · ownership / stewardship · **dispute adjudication ownership** · **applicability authority** · **identity attestation** · implementation technology · build order · invalidation authority · expiry semantics · D3.5's lifecycle correction · **and every mapping above, which is `PROPOSED` only.**
**D2 = `NOT YET ESTABLISHED` · D3 = Option C / SPLIT · D4.1 = proportionate enforcement · D4 floor unchanged.**

**Traceability:** PO/ARB act 2026-08-19 (D4.3) · **D1 · D2 · D3** (D3.2, D3.5, D3.6) · **D4.1** (D4.1.3, D4.1.4, D4.1.5) · **D4** (§2, §3, §12) · **D4.2** (§0, §1 M1–M5, §2, §3, §5, §7) · canonical §5.1, §5.3 pt 13/23 · `INV-ATTR-1`/`INV-ATTR-2` · `C-1` (*absence never implies ownership*) · `CAP-01`/`CAP-03` · BC-1 · `ca6039a8` reclassification (provenance disposition §B) · `S5` outstanding · `ES-006.1` · `R-34`/`P-2`
