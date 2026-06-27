# Round 6F.1 — Authority Discovery

**Date:** 2026-06-03  
**Objective:** Discover how Authority behaves across five domains; derive patterns, not definitions  
**Method:** Matrix investigation of Q1-Q9; populate from concrete domain examples  
**Output:** AuthorityDiscoveryFindings.md (after all nine questions)

---

## Q1: What Is Authority?

(Not: "Define authority." But: "What does authority DO in this domain?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Someone (Committee) approves or rejects a status change | Membership Committee votes on applicant → "eligible" or "not eligible" |
| **Election** | Someone (Observer) certifies or disputes a result | Two observers recount votes; if they agree, election is certified |
| **Appeal** | Someone (Authority) reconsiders and can overturn prior decision | Voter appeals denial; Appeal Authority reviews and says "reverse the denial" |
| **Fraud** | Someone (Investigator) detects and invalidates prior legitimacy | Fraud detector finds duplicate votes; Fraud Authority says "void both votes" |
| **Governance** | Someone (Rules-maker) defines what eligibility means | Governance says "current members only" or "suspended members cannot vote" |

**Patterns Emerging:**
- In all domains, an Authority makes a **binding decision**
- Decision has **recognition by others** (voters accept it, observers report it, governance enforces it)
- Decision can be **written down** (recorded in evidence)
- Decision can be **challenged** (but not easily reversed without higher authority)

**Conflicts Observed:**
- Membership Authority says "eligible"; Election Authority says "check signature"; which takes precedence?
- Appeal Authority overturns Membership Authority; does Appeal Authority rank higher, or is it a different kind?
- Governance says "rules"; Membership says "decisions." Are these both Authority? Or is Governance just constraint?

**Unknowns:**
- Does Authority require **consent** to be real? (If Appeal Authority overturns and voter doesn't accept, is it still Authority?)
- Can Authority be **impersonal**? (Can "the rules" be Authority, or must it be a person/committee?)
- Can Authority be **provisional**? (Does Authority last forever, or can it expire?)

---

## Q2: Who Can Become an Authority?

(Not: "What types exist?" But: "What must something do to become Authority?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | A designated role (Committee) becomes Authority over membership decisions | "Membership Committee has authority to approve or reject." |
| **Election** | A designated role (Observers) becomes Authority over result legitimacy | "Two independent observers certify the count." |
| **Appeal** | A role defined by Governance (Appeal Committee) becomes Authority to reconsider | "Appeals are heard by a body appointed by Governance." |
| **Fraud** | A role (Investigator) with special access becomes Authority over fraud detection | "Fraud Authority has access to device fingerprints, IP logs, voting times." |
| **Governance** | A role or entity (Council, Constitution) becomes Authority to set rules | "Governance makes decisions about who can vote and how." |

**Patterns Emerging:**
- Authority is always **designated** or **recognized** (not self-claimed and uncontested)
- Authority requires **legitimacy through origin** (Governance appoints, Constitution defines, prior Authority delegates)
- Authority can be **individual** (one fraud investigator) or **collective** (committee of observers)
- Authority requires **scope clarity** ("This Authority decides X; not Y")

**Conflicts Observed:**
- Can Authority be **self-designating**? (Can a group just declare themselves Authority?)
- Can Authority exist without **Governance recognition**? (Can regional committee self-authorize?)
- Can **time-limited** Authority exist? (Authority until 2026? After one vote? Forever?)

**Unknowns:**
- What makes designation "legitimate" vs. "claimed"?
- Can Authority be inherited (old Authority appoints successor)?
- Can Authority be **distributed** (shared among multiple entities)?

---

## Q3: Can Authority Be Delegated?

(Not: "Is delegation possible?" But: "What happens when it is?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Membership Committee delegates approval to Regional Committee; both have authority | "Governance says 'Membership Committee decides'; Committee says 'Regional sub-committees decide.'" |
| **Election** | Central Governance may delegate observation authority to Regional Observers | "Each region has its own observers; central observers oversee regions." |
| **Appeal** | Governance-appointed Appeal Authority can delegate specific appeals to sub-panels | "Complex appeals go to full Appeal Committee; simple ones to subcommittee." |
| **Fraud** | Central Fraud Authority delegates investigation to Regional Fraud Teams | "National audit finds pattern; regional teams investigate local instances." |
| **Governance** | Governance can delegate decision-making to subordinate Governance bodies | "Central Governance defines rules; Regional Governance implements them." |

**Patterns Emerging:**
- Delegation **preserves authority at origin** (delegator retains oversight)
- Delegated authority can be **revoked** (delegator can take back delegation)
- Delegation creates **chain of authority** (A → B → C; who is ultimately responsible?)
- Delegation requires **explicit scope** (what is delegated; what is retained)

**Conflicts Observed:**
- When delegate contradicts delegator, which Authority wins?
- Is delegated Authority "same" as original, or "weaker"?
- Can delegate re-delegate? (A → B → C → D?)
- What if delegate disappears? Is Authority orphaned?

**Unknowns:**
- How many levels of delegation are allowed?
- Can delegation be **partial** (delegate some decisions, not others)?
- Does delegation **transfer responsibility**?

---

## Q4: Who Revokes Authority?

(Not: "Can Authority be revoked?" But: "What process exists?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Governance can revoke Committee authority; Committee can be restructured | "Governance dissolves current Committee; appoints new one." |
| **Election** | Governance can revoke Observer authority if misconduct found | "Observer removed from role; vote potentially re-certified." |
| **Appeal** | Governance or Constitution can revoke Appeal Authority or restructure process | "Governance changes appeal rules; old Authority structure invalid." |
| **Fraud** | Governance or higher Authority can remove Fraud Authority if it's abusing power | "Fraud Authority making false accusations; Governance removes it." |
| **Governance** | Constitution or higher Authority can revoke Governance decisions | "Constitutional override of Governance; rare but possible." |

**Patterns Emerging:**
- Authority can be revoked by **higher Authority** (delegator, Governance, Constitution)
- Authority can be revoked by **structural change** (Committee dissolved, rules changed)
- Revocation can be **temporary** (suspension) or **permanent** (removal)
- Revocation can affect **retroactively** (prior decisions now void) or **prospectively** (only future decisions affected)

**Conflicts Observed:**
- What happens to decisions made by revoked Authority?
- Can Authority revoke itself? (Authority says "I'm no longer Authority")
- If Authority is revoked mid-process, does current work continue?
- Who decides whether revocation was justified?

**Unknowns:**
- Is there a **maximum authority** that cannot be revoked?
- Does revocation require **due process**?
- Can revoked Authority **appeal**?

---

## Q5: Is Authority Temporal?

(Not: "Does time matter?" But: "When does Authority start/end?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Authority exists for duration of election cycle; can change when new Committee elected | "This Committee has authority for 2026; next election in 2027 brings new Committee." |
| **Election** | Observer authority exists only during counting period; expires after certification | "Observers have authority while counting; once certified, authority is done." |
| **Appeal** | Appeal Authority exists during appeal window; after deadline, Authority is gone | "Appeals accepted for 30 days post-certification; after that, decision is final." |
| **Fraud** | Fraud Authority exists throughout election and post-election period; can revoke votes | "Fraud investigation continues after election; can invalidate even certified results." |
| **Governance** | Governance authority exists until superseded by higher authority or constitutional change | "Governance makes rules; rules last until changed by Governance or Constitution." |

**Patterns Emerging:**
- Authority has **start time** (when designated or recognized)
- Authority has **scope in time** (duration of authority)
- Authority can **expire** (end time, often tied to event completion)
- Authority can be **renewed** (re-designated for next cycle)

**Conflicts Observed:**
- Does **temporal Authority** mean the decision is also temporal? (Decision valid only while Authority exists?)
- Can Authority be **retroactively revoked**? (Authority existed at T1; revoked at T3; decision at T2 now invalid?)
- Is there **maximum temporal scope**? (Authority can't last forever?)

**Unknowns:**
- What determines Authority duration? (Event completion? Cycle end? Explicit expiration?)
- Can Authority **extend beyond its temporal scope**? (Old decision by expired Authority still binding?)

---

## Q6: Who Verifies Authorities?

(Not: "How is Authority checked?" But: "Who has meta-authority?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Governance verifies Membership Committee is following rules | "Governance audits: Did Committee only approve eligible members?" |
| **Election** | Governance or Central Authority verifies Observers are honest | "Central Authority spot-checks: Did Observers count correctly?" |
| **Appeal** | Governance verifies Appeal Authority is fair | "Governance reviews: Did Appeal Authority follow due process?" |
| **Fraud** | Governance or Constitutional Court verifies Fraud Authority isn't abusing power | "Court review: Did Fraud Authority prove fraud, or just suspect it?" |
| **Governance** | Constitution or higher Authority (if exists) verifies Governance isn't exceeding powers | "Constitutional Court: Is Governance decision within constitutional bounds?" |

**Patterns Emerging:**
- Authority is **verified by higher Authority** (delegator, Governance, Constitution)
- Verification is **post-hoc** (after decision, then check if proper)
- Verification can be **triggered** (complaint filed) or **routine** (regular audit)
- Verification asks: "Did Authority follow **rules**?" not "Did Authority make **correct decision**?"

**Conflicts Observed:**
- Can Authority be verified **during** decision-making, or only after?
- What if **verification Authority** is corrupt?
- Does verification Authority have **final say**?
- Can Verification Authority **override** the original Authority?

**Unknowns:**
- Is there a **chain of verification**? (A verifies B; who verifies A?)
- How does **verification handle disagreement**?

---

## Q7: Can Authorities Disagree?

(Not: "Is conflict possible?" But: "What happens when it occurs?")

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Membership Authority says "eligible"; HR Authority says "not eligible" | Stress test 2B: both authorities claim truth; Governance rule decides ("HR is authority of record") |
| **Election** | Observer A says "63 votes"; Observer B says "61 votes" | Stress test 1B: disagreement triggers recount; process resolves conflict |
| **Appeal** | Original Authority says "decision final"; Appeal Authority says "decision reversed" | Stress test 5: Appeal Authority has explicit power to overturn; new Authority wins |
| **Fraud** | Fraud Authority says "fraud detected"; Original Authority says "no fraud" | Stress test 3: Fraud Authority can retroactively invalidate even certified results |
| **Governance** | Regional Governance says "rule X"; Central Governance says "rule Y" | Stress test 4: precedence rule decides ("Central Governance wins") |

**Patterns Emerging:**
- Disagreement is **normal and expected** (not a failure)
- Disagreement is **resolved by precedence** (not by democratic vote of authorities)
- Precedence is **defined in advance** (Governance rule states "HR wins" or "Appeal Authority wins")
- Disagreement can create **new decisions** (recount, reinvestigation, re-hearing)

**Conflicts Observed:**
- What if **precedence rule itself is unclear**?
- Can two **equal authorities** both be right?
- Does disagreement **suspend legitimacy** until resolved?
- Can disagreement be **appealed**?

**Unknowns:**
- Is there a **symmetric disagreement** (neither authority has precedence)?
- Can authorities **disagree on what their precedence is**?

---

## Q8: Can Legitimacy Exist Without Authority?

(Testing if Authority is necessary for Legitimacy)

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Status change (eligible/ineligible) requires some decision authority | Without Membership Authority, how is eligibility decided? Silent majority? Vote? |
| **Election** | Result certification requires some authority to say "this is correct" | Without Observer Authority, who certifies? Whoever counted? Self-certification? |
| **Appeal** | Reversal of prior decision requires some authority that can overturn | Without Appeal Authority, can voter challenge? Is challenge binding? |
| **Fraud** | Detection and invalidation requires some authority to say "this is fraud" | Without Fraud Authority, how is fraud proven? Who decides? |
| **Governance** | Rules require some authority to enforce them | Without Governance Authority, are rules just suggestions? |

**Observation:**
- In all cases, legitimacy seems to **require some Authority** to establish it
- Without designated Authority, legitimacy is **unclear or absent**
- Even "default" decisions (no vote = approved?) require an **implicit Authority** saying "that's how it works"

**Candidate Hypothesis:**
- Authority is **necessary** for Legitimacy
- Legitimacy cannot exist without **some** Authority claiming it

---

## Q9: Can Authority Exist Without Legitimacy?

(Testing if Authority automatically implies Legitimacy)

| Domain | Observation | Concrete Example |
|--------|-------------|------------------|
| **Membership** | Corrupt Committee can **claim authority** but lack legitimacy (members reject it) | Committee rules "applicant X is approved"; members say "you don't have real authority" |
| **Election** | Biased Observers can **claim authority** but lack legitimacy (voters distrust count) | Observers certify results; voters say "we don't believe you" |
| **Appeal** | Appeal Authority can **claim authority** but lack legitimacy if process is unfair | Appeal Authority reverses decision; but if process was rigged, reversal lacks legitimacy |
| **Fraud** | Fraud Authority can **claim authority** but lack legitimacy if accusations are false | Fraud Authority says "duplicate votes"; but evidence fabricated = claims are illegitimate |
| **Governance** | Governance can **claim authority** but lack legitimacy if constitution violated | Governance makes rule; but if unconstitutional, rule lacks legitimacy |

**Observation:**
- Authority can be **claimed** without being **legitimate**
- Legitimacy requires **acceptance or recognition** beyond mere claim
- Authority without legitimacy is **contested** or **empty**

**Candidate Hypothesis:**
- Authority is **not sufficient** for Legitimacy
- Legitimacy requires Authority **PLUS** something else (recognition, consent, due process, constitutionality)

---

## Summary So Far

After Q1-Q9, patterns and conflicts are emerging but no conclusions yet.

**This document is incomplete.** Continue to next phase:

→ **AuthorityDiscoveryFindings.md** (after reading this, deriving patterns, listing conflicts, stating unknowns, proposing candidate hypotheses)

→ **Round 6F.2 Authority Stress Test** (attempt to break each candidate hypothesis)
