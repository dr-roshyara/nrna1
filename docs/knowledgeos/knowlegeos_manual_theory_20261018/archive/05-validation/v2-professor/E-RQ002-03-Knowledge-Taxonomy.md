# V-2 Professor extraction — RQ-002 Deliverable 9, Knowledge Taxonomy

```yaml
source_path: docs/implementation/RQ-002_Knowledge_Taxonomy.md
sha256: 90d2b7f1235adfd462628b9cf6bb3e5d2faffd4f29aae27fcee710eb168a1534
lines_total: 141
lines_read: 141
extractor: Professor (Session B), blind to the reviewer; B-08 not consulted
```

## Items

> Q1 L3-3: "NOT a folder structure, NOT templates, NOT an ontology of documents, NOT design."
- Q1 · CLM · Kind: semantic foundation, not design.

> Q2 L4-4: "COMPLETE — presented for ARB review."
- Q2 · CLM · Status; Reference Architecture blocked until ARB rules.

> Q3 L5-5: "candidates are validated **or falsified**, not assumed."
- Q3 · ASM · Method: closed evidence base; candidate types tested.

> Q4 L11-11: "is a FAMILY of knowledge types sharing one constitutional core, not a homogeneous domain."
- Q4 · CLM · Headline: project knowledge is a family of types with one core.

> Q5 L11-11: "Seven types survive the evidence; five candidates are falsified or merged."
- Q5 · CLM · Counts: 7 survive, 5 falsified or merged.

> Q6 L11-11: "while all obey the nine invariants of the Architectural Synthesis"
- Q6 · REL · All types obey the Synthesis's nine invariants.

> Q7 L11-11: "Uniform treatment of knowledge is therefore an architectural error the evidence forbids; per-type governance is mandatory."
- Q7 · CLM · Per-type governance is mandatory.

> Q8 L18-18: "Knowledge encoded so that a machine can check it"
- Q8 · DEF · Type 1 Executable.

> Q9 L19-19: "Developers, in the act of building (producer = consumer, by construction)."
- Q9 · CLM · Executable producer = consumer.

> Q10 L20-20: "the highest-trust stratum for every consumer type"
- Q10 · CLM · Executable has the highest trust.

> Q11 L21-21: "The passing run. Authority is earned per execution, not assigned."
- Q11 · CLM · Executable authority = the passing run.

> Q12 L22-22: "EXECUTION — the only mechanism empirically shown to keep an artifact true"
- Q12 · CLM · Executable validation = execution.

> Q13 L23-23: "Changes with its referent in the same gesture"
- Q13 · CLM · Executable lifecycle.

> Q14 L24-24: "Deleted with the code it guards; history preserved by version control."
- Q14 · CLM · Executable retirement.

> Q15 L25-25: "Self-enforcing — a stale executable fails."
- Q15 · CLM · Executable freshness.

> Q16 L26-26: "Live-source search (agentic search; repository legibility = scent for this type — D)."
- Q16 · CLM · Executable discoverability.

> Q17 L27-27: "already implemented — the merge gate, fitness suites, greenfield PHPStan are this type in production."
- Q17 · CLM · Platform already implements Executable.

> Q18 L28-28: "B (trust stratification, code-proximate survival) · D (execution-grounding) · PK-P4."
- Q18 · CLM · Evidence pointers for Executable.

> Q19 L31-31: "A recorded choice with context, alternatives, rationale, consequences"
- Q19 · DEF · Type 2 Decision.

> Q20 L32-32: "the capture window is decision time or never"
- Q20 · CLM · Decision producer and capture window.

> Q21 L33-33: "rationale is exactly what agents cannot derive from code"
- Q21 · CLM · Decision consumers include agents.

> Q22 L34-34: "Canonical = accepted ∧ unsuperseded ∧ owned ∧ **bound to code**"
- Q22 · MATH · Decision canonicity as a conjunction of four conditions.

> Q23 L35-35: "the hardest type to validate"
- Q23 · CLM · Decision validation = enforcement-binding + review.

> Q24 L36-36: "IMMUTABLE — regime 1 of PK-P5; a dated decision stays true as a historical fact."
- Q24 · CLM · Decision lifecycle immutable.

> Q25 L37-37: "Supersession by link with reason; the only mainstream form with built-in retirement semantics"
- Q25 · CLM · Decision retirement.

> Q26 L38-38: "Does not stale as history; its *canonicity* stales"
- Q26 · CLM · Decision freshness: canonicity stales, content does not.

> Q27 L39-39: "Must surface when the governed code is touched"
- Q27 · CLM · Decision discoverability.

> Q28 L40-40: "the platform is an existence proof of this type done per the evidence."
- Q28 · CLM · Platform implements Decision type.

> Q29 L41-41: "highest-demand/lowest-capture finding"
- Q29 · CLM · Evidence pointers for Decision.

> Q30 L44-44: "Ubiquitous-language terms with meaning fixed inside one bounded context; context boundaries themselves."
- Q30 · DEF · Type 3 Definitional.

> Q31 L45-45: "The whole team, jointly and continuously"
- Q31 · CLM · Definitional producer.

> Q32 L46-46: "rarely via glossary"
- Q32 · CLM · Definitional consumers learn via code and conversation.

> Q33 L47-47: "a glossary is derivative, never authoritative"
- Q33 · CLM · Definitional authority = conversation + code identifiers.

> Q34 L48-48: "CONVERSATIONAL — drift is heard as awkwardness; term drift is a *boundary signal*, not mere decay"
- Q34 · CLM · Definitional validation conversational; drift signals a boundary.

> Q35 L49-49: "**rots when the owning conversation stops** (~2 years after owner departure)"
- Q35 · CLM · Definitional lifecycle.

> Q36 L50-50: "Terms retire when their context retires"
- Q36 · CLM · Definitional retirement.

> Q37 L51-51: "Freshness = conversation cadence; unmeasurable from the artifact alone."
- Q37 · CLM · Definitional freshness.

> Q38 L52-52: "In the code (identifiers) and in people"
- Q38 · CLM · Definitional discoverability.

> Q39 L53-53: "the conversational-validation assumption is challenged when engineers are AI"
- Q39 · CLM · Platform partially implements; AI challenges conversational validation.

> Q40 L54-54: "smallest-unit answer (term-bound-to-context)"
- Q40 · CLM · Evidence pointers for Definitional.

> Q41 L57-57: "Prose describing current state: guides, READMEs, views, runbooks/procedures (operational subtype)."
- Q41 · DEF · Type 4 Descriptive.

> Q42 L58-58: "Survives ONLY where these are the same population (docs-as-code)"
- Q42 · CLM · Descriptive survival condition.

> Q43 L59-59: "ownerless descriptive content is definitionally non-authoritative"
- Q43 · CLM · Descriptive authority = owner + freshness + review.

> Q44 L60-60: "automation *relocates* staleness if the source model is unmaintained"
- Q44 · CLM · Descriptive validation caveat.

> Q45 L61-61: "EVOLVES/REGENERATES — regime 2 of PK-P5; materially stale within 30–90 days without coupling"
- Q45 · CLM · Descriptive lifecycle.

> Q46 L62-62: "Staged ROT disposal: merge+redirect / update-or-retire / archive — never silent abandonment."
- Q46 · CLM · Descriptive retirement.

> Q47 L63-63: ""last edited" is a proxy, not validity"
- Q47 · CLM · Descriptive freshness (owner + verified stamp).

> Q48 L64-64: "the type where foraging theory bites hardest"
- Q48 · CLM · Descriptive discoverability depends on scent.

> Q49 L65-65: "runbooks validate by USE"
- Q49 · CLM · Operational subtype validated by use.

> Q50 L66-66: "the wiki anti-pattern is structurally avoided"
- Q50 · CLM · Platform implements Descriptive as guides.

> Q51 L67-67: "Google GooWiki 90%"
- Q51 · CLM · Evidence pointers for Descriptive.

> Q52 L70-70: "claims produced by learning, **input-only until promoted**"
- Q52 · DEF · Type 5 Experiential/Research.

> Q53 L71-71: "historically captured under mandate at the worst moment"
- Q53 · CLM · Experiential producer.

> Q54 L72-72: "via the promotion chain, never directly as authority"
- Q54 · CLM · Experiential consumers.

> Q55 L73-73: "an experiential claim gains authority only by surviving evaluation and being promoted"
- Q55 · CLM · Experiential authority none intrinsically.

> Q56 L74-74: "CLAIM EVALUATION at intake — the gate almost no system implements"
- Q56 · CLM · Experiential validation.

> Q57 L75-75: "falsified claims retained as still-informative"
- Q57 · CLM · Experiential lifecycle append-only; negative knowledge kept.

> Q58 L76-76: "Never edited; superseded by newer findings; archives remain retrievable."
- Q58 · CLM · Experiential retirement.

> Q59 L77-77: "born-stale risk at intake (58.4%) dominates aging"
- Q59 · CLM · Experiential freshness two-sided.

> Q60 L78-78: "requires indexing by *future task*, not by past project"
- Q60 · CLM · Experiential discoverability.

> Q61 L79-79: "with the claim-evaluation gate the literature says is missing"
- Q61 · CLM · Platform implements Experiential with the gate.

> Q62 L80-80: "A (NKM-14 knowledge claim; LLIS audits)"
- Q62 · CLM · Evidence pointers for Experiential.

> Q63 L83-83: "Records of what instruments measured"
- Q63 · DEF · Type 6 Qualification/Evidence.

> Q64 L84-84: "Instruments (measuring runs), never authors; recorded as produced, never asserted (I-9)."
- Q64 · CLM · Qualification producer.

> Q65 L85-85: "dashboards (derived-only)"
- Q65 · CLM · Qualification consumers.

> Q66 L86-86: "authority is never editorial"
- Q66 · CLM · Qualification authority = instrument + configuration.

> Q67 L87-87: "The run itself IS the validation; re-validation = re-run."
- Q67 · CLM · Qualification validation.

> Q68 L88-88: "IMMUTABLE record with verdict-history semantics"
- Q68 · CLM · Qualification lifecycle.

> Q69 L89-89: "the old measurement stays true of its moment"
- Q69 · CLM · Qualification retirement.

> Q70 L90-90: "Timestamped by construction; staleness = referent drift, detected by re-running."
- Q70 · CLM · Qualification freshness.

> Q71 L91-91: "provenance chains make lineage traversable"
- Q71 · CLM · Qualification discoverability.

> Q72 L92-92: "the platform's most mature type"
- Q72 · CLM · Platform fully implements Qualification.

> Q73 L93-93: "C (PROV, provenance-as-substrate)"
- Q73 · CLM · Evidence pointers for Qualification.

> Q74 L96-96: "Mental models, skills, judgment — the actual operational knowledge store of every team"
- Q74 · DEF · Type 7 Tacit.

> Q75 L97-97: "Every practitioner, involuntarily, by doing."
- Q75 · CLM · Tacit producer.

> Q76 L98-98: "Colleagues via interruption (the empirically preferred channel)"
- Q76 · CLM · Tacit consumers.

> Q77 L99-99: "Expertise recognition — social, unrecorded, real."
- Q77 · CLM · Tacit authority.

> Q78 L100-100: "USE and peer conversation; not artifact-checkable."
- Q78 · CLM · Tacit validation.

> Q79 L101-101: "**lost abruptly at departure** (fat-tailed: 3–5x expected loss)"
- Q79 · CLM · Tacit lifecycle.

> Q80 L102-102: "None possible — it leaves with the person"
- Q80 · CLM · Tacit retirement.

> Q81 L103-103: "the proxy is concentration risk (truck factor)"
- Q81 · CLM · Tacit freshness.

> Q82 L104-104: "succession mechanics are the only measured mitigation (~15–25%)"
- Q82 · CLM · Tacit discoverability and mitigation.

> Q83 L105-105: "deliberately UNGOVERNED as artifact (PK-P9)"
- Q83 · CLM · Platform deliberately leaves tacit ungoverned.

> Q84 L105-105: "the platform's session logs + reconstruction discipline are a partial *rationale* capture channel at near-zero gesture cost"
- Q84 · CLM · Session logs as a partial rationale channel.

> Q85 L106-106: "B (NKM-12; LaToza; Ko; Rigby; Avelino)"
- Q85 · CLM · Evidence pointers for Tacit.

> Q86 L112-112: "**Architecture Knowledge** | ❌ FALSIFIED as a distinct type"
- Q86 · REV · Architecture falsified as a type: it names a content domain, decomposes into Decision + Descriptive + Executable.

> Q87 L113-113: "**Business Rule Knowledge** | ⚠ MERGED"
- Q87 · REV · Business rules merged (encoded → Executable; otherwise Definitional/Descriptive; why → Decision).

> Q88 L114-114: "It is environment, not artifact class"
- Q88 · REV · Organizational knowledge falsified as an artifact-governable type.

> Q89 L115-115: "**Historical Knowledge** | ❌ FALSIFIED as a type — **it is a STATUS**"
- Q89 · REV · Historical is a status.

> Q90 L115-115: "Treating it as a type would re-break status-at-discovery (PK-P2)."
- Q90 · CLM · Reason for Q89.

> Q91 L116-116: "classifying it now would violate the no-unsupported-concepts constraint"
- Q91 · REV · Runtime knowledge: insufficient evidence, open question.

> Q92 L117-117: "**Requirements** | ⚠ MERGED (per evidence available)"
- Q92 · REV · Requirements merged into Decision + Definitional + Executable.

> Q93 L131-131: "no two rows share a full profile — the family thesis holds."
- Q93 · CLM · [labelled INTERPRETATION in the source] Family thesis.

> Q94 L131-131: "The Engineering Platform already implements or partially implements six of seven rows **for engineering knowledge**"
- Q94 · CLM · Platform covers six of seven types for engineering knowledge.

> Q95 L135-135: "Runtime/telemetry knowledge: kinship with Qualification knowledge needs first-party evidence"
- Q95 · CLM · Open question 1.

> Q96 L136-136: "Definitional validation when engineers are AI: does conversational validation have an agent-compatible analogue?"
- Q96 · CLM · Open question 2.

> Q97 L137-137: "Boundary artifacts (IDDs: part decision, part description) — which regime governs which section?"
- Q97 · CLM · Open question 3.

> Q98 L141-141: "the one-domain-or-family question answered: **a family with a constitutional core**"
- Q98 · CLM · Success criterion answered.

> Q99 L141-141: "4 falsified/merged, 1 insufficient-evidence"
- Q99 · CLM · Count of falsified/merged candidates (inconsistent with Q5 and the table, see Uncertainties).

## Line accounting (NOT-EXTRACTED)

| Lines | Reason |
|---|---|
| 1 | FRONTMATTER (title) |
| 2, 6–8, 10, 12–14, 16, 29, 42, 55, 68, 81, 94, 107, 109, 118, 120, 130, 132, 134, 138–140 | BOILERPLATE (blank lines, separators) |
| 9, 15, 17, 30, 43, 56, 69, 82, 95, 108, 119, 133 | NAVIGATION (section and type headings) |
| 110–111, 121–122 | BOILERPLATE (table headers) |
| 123–129 | REPETITION-OF: Q8–Q85 (the one-page matrix restates the per-type fields) |

## Uncertainties noticed

- [?] Counting inconsistency: L11 says "five candidates are falsified or merged"; the table (L112–117) has five falsified or merged plus one insufficient-evidence; L141 says "4 falsified/merged, 1 insufficient-evidence".
- [?] L11 refers to "the nine invariants of the Architectural Synthesis"; the later Constitutional Model has eleven. Revision across documents [I].
