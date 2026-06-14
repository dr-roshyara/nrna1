# ARN-001 – Capability Resolution and Transition Validation Consistency Review

## Als

Domain Architect / Backend Entwickler

## möchte ich

die Beziehung zwischen CapabilityResolver und Transition Validation für `open_voting` analysieren,

## um

sicherzustellen, dass die Benutzeroberfläche keine Aktionen als ausführbar darstellt, die später durch die Domänenlogik abgelehnt werden.

---

## Akzeptanzkriterien

### Szenario 1 – Regelinventur

**ANGENOMMEN**

* CapabilityResolver bewertet `open_voting`

**WENN**

* alle Capability-Regeln dokumentiert werden

**DANN**

* existiert eine vollständige Liste aller Capability-Voraussetzungen

---

### Szenario 2 – Transition Validation Inventur

**ANGENOMMEN**

* `Election::validateOpenVoting()` wird analysiert

**WENN**

* alle Validierungsregeln dokumentiert werden

**DANN**

* existiert eine vollständige Liste aller Transition-Voraussetzungen

---

### Szenario 3 – Konsistenzanalyse

**ANGENOMMEN**

* beide Regelmengen liegen vor

**WENN**

* sie verglichen werden

**DANN**

* werden Unterschiede dokumentiert

**UND**

* jede Abweichung erhält eine fachliche Begründung

---

### Szenario 4 – Architekturentscheidung

**ANGENOMMEN**

* Unterschiede wurden identifiziert

**WENN**

* die Analyse abgeschlossen wird

**DANN**

* wird entschieden, ob

  * die Capability-Schicht erweitert wird,
  * die Transition-Validierung angepasst wird,
  * oder die Abweichung bewusst bestehen bleibt.

---

## Beteiligte Fremdsysteme

Keine

---

## Technische Zusatzinformationen

Betroffene Artefakte:

* `ElectionConstitution::RULES['open_voting']`
* `LifecycleCapabilityBaselinePolicy`
* `CapabilityResolver`
* `Election::whyCannotOpenVoting()`
* `Election::validateOpenVoting()`

Referenzen:

* ARN-001
* ARG-02 Language Governance
* Architecture Baseline v1
* `architecture/ARCHITECTURE_REVIEW_NOTES.md`

---

## Definition of Ready

* ARN-001 dokumentiert ✅
* Architekturbaseline verabschiedet ✅
* Governance Transparency Initiative abgeschlossen ✅

---

## Definition of Done

* Regelinventur dokumentiert
* Unterschiede identifiziert
* Architekturentscheidung getroffen
* ADR oder Jira-Kommentar erstellt
* Keine Frontend-Workarounds eingeführt
