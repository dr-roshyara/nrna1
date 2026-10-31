# KnowledgeOS aim and kernel scope: is the proposed vision within scope? (review and recommendation)

| Field | Value |
|---|---|
| Status | **A review and a recommendation only.** It decides nothing, adopts no mission, specifies no kernel, and authorises no work |
| Asked by | the Principal, 2026-10-31 ("KnowledgeOS Aim and Kernel Scope Review") |
| Prepared by | the Governance Engineer (session `57ddce41…`), 2026-10-31, at commit `5cab9167a` |
| Read for it | Ch. 0 in full (summary: `AIM-IN-CHAPTER-0-SUMMARY-20261031.md`); `06-theory/RESEARCH-PURPOSE.md` (lines 1–140); §10.1 (`10.01-subdomains.md` L37–90); §10.2 candidate 8 (L168–172); §8.37 C2; §8.40 C3; §8.43 (lineage, L1–9); §8.44 C3 (heading); the implementation-track record I-003, I-004, I-011, I-012; the I-006 groundwork §3a–§3c |
| Not opened | the charter and the development constitution themselves, and AIP-14: cited **as Ch. 0 reports them** (marked "via Ch. 0") |

**Labels.** **[V]** verified source fact · **[I]** interpretation · **[P]** proposal · **[D]** existing recorded decision. **"KnowledgeOS"** below means the whole platform or product; **"the kernel"** means the minimal core the research is about. Keeping these two apart is the main finding.

## 1. Executive conclusion

- **[I]** The vision is **feasible within the existing aim if the analysing and recommending steps sit in applications around the kernel, and the kernel stays the governed core.** The existing theory already places them there: AI is an application, assessment methods write evidence and decide nothing, KnowledgeOS records authority and does not decide it.
- **[I]** Placed **inside the kernel**, the same steps contradict settled theory (§8.37 C2, §8.40 C3, §10.2 candidate 8) and move toward a general reasoning-and-decision engine (Model 3), which no record supports.
- **[V] A scope conflict exists already, before the vision:** Ch. 0 is about **engineering** knowledge; `RESEARCH-PURPOSE.md`, set by the Principal, aims the kernel at **"organisational knowledge"** for applications including "AI agents, robots, products, workflows". This is the real scope question, and it is the Principal's.
- **[P] Recommended:** Option A for the kernel, combined with Option B's clarification for KnowledgeOS as a product (decision support as an application capability, bounded). Option C only if the Principal wants knowledge beyond engineering, as an explicit decision, not now.

## 2. The current aim and its governance status

| Element | Content | Status |
|---|---|---|
| Problem | "Organizations that build software lose the knowledge that explains their systems" (Ch. 0 §0.1 L3) | **[V]** Src, literature-reported; the solution is Hyp (§0.1 L18) |
| Core requirement | knowledge "valid at the moment it is used … and the user must be able to see whether it is" (§0.1 L22) | **[V]** the book's own Int |
| What KnowledgeOS promises | "current standing is right and shown with its reasons, together with an honest display of how well-founded it is (refuted, not attested, or supported by recorded evidence), without a guarantee that it is well-founded" (§10.1 L82) | **[D]** T10-01 (b), 2026-10-29, `e73b8e1cb`; contested added by T9-17 (b) |
| What it does not promise | well-foundedness (option (c) not chosen, §10.1 L46); "engineering truth" (§0.2 L14, DevConst via Ch. 0) | **[D]** (b); **[V]** PROPOSED · INERT source |
| Type of knowledge | engineering knowledge (§0.1 L3; §0.2 L8, L14) **vs** "organisational knowledge" (RESEARCH-PURPOSE L14) | **[V] conflict, preserved** |
| Users | "a human or an AI" (§0.1 L22); applications "AI agents, robots, products, workflows" run on the kernel (RESEARCH-PURPOSE L14, L19) | **[V]** |
| Authority | "Knowledge feeds authority; it never holds it"; "Decisions belong to humans. The AI provides analysis and recommendations; it never approves, ratifies, authorizes or promotes" (§0.6 L5–6); "KnowledgeOS records authority and does not decide it" (§8.40 C3); "Capability is not authority" (RESEARCH-PURPOSE L78) | **[V]** Src (DevConst via Ch. 0); §8.40 C3 settled, S0 |
| Mission | serve PublicDigit now; a reusable engineering-knowledge platform for organisations that build software as the stated longer-term aim | **Principal input, not adopted** (I-006 groundwork §3c, `5cab9167a`). Ch. 0's mission is "Awaiting Human Decision" (TODO T0-02) |
| Relation to PublicDigit | AIP-14 makes KnowledgeOS a supporting subdomain of the election product; Product Primacy: the platform serves the product | **[V] via Ch. 0** (§0.2 L25, L50, L61; AIP-14 itself not opened) |
| The kernel | the research **goal**: "the minimal, trusted core … that every later application … runs on" (RESEARCH-PURPOSE L14); "No coherent KnowledgeOS Kernel can be established" is a permitted answer (L41). All of Ch. 10 is S0 | **[V]** no kernel specification is adopted or ratified (Ch. 0; Ch. 10 rows at S0, 10.00 L7). The corpus's Constitution is "pending HPA ratification" (§8.43 L8) |
| EKS / PKS | PKS outside KnowledgeOS, which can write it (input, §3a); "EKS is part of KnowledgeOS" (input, §3b); Ch. 0 records an unlocated ruling "EKS … retired" (§0.2 L23); EKS is a supporting tool, the kernel is built new | inputs **not decisions**; **[D]** I-003 item 4 (EKS a supporting tool, "not the starting point of the kernel") |

## 3. The proposed kernel capabilities against the current aim

| # | Capability | Classification | Evidence |
|---|---|---|---|
| 1 | Analysing input documents | **Application-level** (for the kernel); within KnowledgeOS's aim as evidence production | assessment methods "write evidence entries into candidate 2 and decide no classes" (§10.2 L170); AI and robots are applications, not the core (RESEARCH-PURPOSE L19) |
| 2 | Analysing AI-generated outputs | **Application-level**; an AI output is at most a proposal or an evidence entry | "an AI may propose, analyse, derive and challenge, never adopt or ratify" (RESEARCH-PURPOSE L78); §0.6 L6 |
| 3 | Identifying candidate knowledge facts | **Application-level** for extraction; **potentially within aim** for the kernel's part: registering a candidate and admitting it by a recorded act | lifecycle and admission are a supporting area (§10.1 row 6); standing changes only by act (§0.4 L6; §0.6 L12) |
| 4 | Preserving claims, sources and status | **Within existing aim** (core) | claims with provenance and status (§0.3 L3–10; I-3); current standing is core under (b) (§10.1 row 1) |
| 5 | Evaluating evidence; exposing contradictions and gaps | **Split:** *showing* the evidence class and contradictions is **within aim** (core under (b); I-4); *assessing* evidence is **application-level** (supporting); gaps are a supporting area | §10.1 L86–88 (core: showing; supporting: assessment methods); §10.1 row 7 |
| 6 | Recommending decisions | **Application-level, consistent with the aim**; **scope expansion if put in the kernel** | "The AI provides analysis and recommendations; it never approves" (§0.6 L6); a verdict engine re-entering the kernel is recorded as a drift the book does not follow (§8.37 C2); "KnowledgeOS records authority and does not decide it" (§8.40 C3) |
| 7 | Supporting applications across engineering contexts | **Potentially within aim**, unvalidated | the stated longer-term aim (§3c input); the kernel/application boundary is a research deliverable (RESEARCH-PURPOSE, deliverable 3) |
| 8 | Supporting arbitrary non-engineering domains | **Scope expansion** relative to Ch. 0 and the Mission C input; **undetermined** relative to RESEARCH-PURPOSE's "organisational knowledge" | §0.2 L14 vs RESEARCH-PURPOSE L14 (the conflict of §2) |

## 4. Models 1, 2 and 3

- **[I] The existing aim fits Model 1 for the kernel,** with **decision recommendations allowed outside it**: the sources let AI and applications recommend (§0.6 L6) and keep the kernel to standing, its reasons and the evidence display (§10.1 under (b)). Call it "Model 1 kernel, Model 2 product".
- **[I] The Principal's vision, as written, is Model 2 placed inside the kernel** ("The KnowledgeOS Kernel … analyses input documents and AI outputs … and can recommend decisions"). Two phrases pull it toward **Model 3**: "input documents and AI outputs" without a domain, and the earlier Guru–Shishya application.
- **[I] A reusable engineering platform does not imply Model 3.** Reuse is across organisations that build software; Model 3 is across subject matter. Ch. 0's own boundary is subject matter: "KnowledgeOS governs engineering knowledge, not engineering truth" (§0.2 L14).
- **[I] Is "engineering vs general knowledge" a material boundary?** For the **kernel**, perhaps not: its candidate primitives (identity, lifecycle, authority, evidence, relationships, policy, history; RESEARCH-PURPOSE) are not engineering-specific, which is why RESEARCH-PURPOSE could write "organisational". For **KnowledgeOS as a product**, yes: the analysis, the evidence methods and the recommendations are domain-specific, and the problem the sources evidence is the engineering one (§0.1). **[U]** Whether the kernel is domain-neutral is a research question, not yet answered.

## 5. Commercial ambition versus knowledge-domain scope

| # | Activity | Assessment | Evidence |
|---|---|---|---|
| 1 | Using KnowledgeOS internally for PublicDigit | **Supported by existing decisions** | AIP-14 (via Ch. 0); T10-01 (b) **[D]**; Mission C input (now) |
| 2 | Validating it as a reusable platform for software organisations | **Consistent with the aim, unvalidated** | Mission C input (later); "unproven until shown" |
| 3 | Selling it as a platform in its own right | **A commercial proposal that does not itself change the aim**, but it reopens a shut gate: the charter's product hypothesis is "proposed, unapproved, its gate shut" (CON-0028, invalid; §0.2 L49) | Ch. 0; a sponsor decision is needed |
| 4 | Specialised AI applications on top | **Consistent with the aim** (applications run on the kernel; AI is an application), **unvalidated** | RESEARCH-PURPOSE L14, L19; §10.2 candidate 8 |
| 5 | Consulting, implementation, training, support | **Commercial proposal; no aim change** | none needed |
| 6 | Knowledge outside software engineering | **Appears to require a change of aim or scope** (Ch. 0; Mission C input); **conflicts** with RESEARCH-PURPOSE's wording | §0.2 L14; RESEARCH-PURPOSE L14 |

## 6. Scope risks and unresolved questions

1. **[V] Engineering vs organisational knowledge:** Ch. 0 and the Mission C input say engineering; RESEARCH-PURPOSE says organisational. Unresolved.
2. **[I] A kernel that recommends would decide classes or standing in effect.** The book rejects this: assessment decides no classes (§10.2 L170); a passing check is not acceptance (§8.36 C1); an engine that "recognises authority" conflicts with A1 under one reading (§8.44 C3).
3. **[V] Nothing is validated in use** (§0.7 L30; §0.8 L27); all of Ch. 10 is S0.
4. **[U] The kernel's existence** is itself an open research deliverable (RESEARCH-PURPOSE L41).
5. **[U] EKS:** "part of KnowledgeOS" (input) vs "retired" (unlocated ruling); likely a naming clash, not checked.
6. **[I] "Identifies knowledge facts"** risks treating extraction as establishing fact; in the book an extracted claim has no standing until a recorded act, and evidence never confers standing (T9-20 **[D]**: no guard; standing from a recorded act).

## 7. Recommended option, with reasons

**[P] Option A for the kernel, with Option B's clarification for the product.**
- The kernel governs: claims, sources, standing by recorded act, the evidence display of promise (b), contradictions kept visible.
- Analysis of documents and AI outputs, candidate extraction, evidence assessment and **decision recommendations** are **application capabilities** built on the kernel. A recommendation is a proposal or an evidence entry: it never changes standing; a human act does.
- Option C (a wider domain) only by an explicit Principal decision, and not needed for the commercial plan as long as the domain is software engineering.

**Why:** it delivers the whole commercial flow the Principal described; it keeps every settled boundary (§0.6, §8.37 C2, §8.40 C3, §10.2, T10-01 (b), T9-20); it keeps the kernel small enough to specify and test; and it leaves the domain question open rather than deciding it by drift.

| | |
|---|---|
| **Already authorised** | the research toward the kernel (RESEARCH-PURPOSE); T10-01 (b); the evidence kinds (T9-19); EKS as a supporting tool (I-003) |
| **Can be explored without changing the aim** | recommendation and analysis as applications; reuse across software organisations; commercial services |
| **Needs a Principal decision** | the domain (engineering or organisational); the kernel/application split for recommendations as a direction for the research; reopening the product gate; the validation tests below |
| **Must stay deferred or prohibited until authorised** | any kernel specification; recommendations or analysis inside the kernel; non-engineering domains; any implementation (stage 1 not authorised) |

## 8. Proposed validation tests (none has been run)

| Test | Task | KnowledgeOS must show | Success | Failure would mean | Unvalidated assumptions |
|---|---|---|---|---|---|
| **V1 Standing at use** | one real PublicDigit change: its ADRs, standards, and an AI assistant's proposal | for each claim the AI relies on: current or not, why (the act), and its evidence class | a human reviewer agrees with every shown standing; every item traces to a recorded act or entry | standing cannot be traced, or is shown wrongly | the record holds the acts; the AI's claims can be matched to recorded items |
| **V2 Superseded knowledge** | a rule superseded in the records; an AI output that still uses the old rule | "not current" at the point of use, with the superseding act | caught in every planted case | missed cases: the core promise fails | supersession is recorded as an act |
| **V3 Recommendation stays a proposal** | an application recommends a decision from governed knowledge | the recommendation cites its entries and changes no standing until a human acts | no standing change without a recorded act; every recommendation traceable | the application needs the kernel to decide or recommend: evidence that Model 2 must sit in the kernel | applications can read standing and evidence through an interface |
| **V4 Reuse (later)** | V1 on a second software project | the same behaviour without kernel changes | no kernel change needed | the kernel is PublicDigit-specific | a second project is available |

## 9. Decisions required from the Principal (one at a time)

1. **The domain:** engineering knowledge (Ch. 0, Mission C input), or organisational knowledge (RESEARCH-PURPOSE). *Recommended:* engineering for KnowledgeOS now; the research may keep asking whether the kernel itself is domain-neutral.
2. **The kernel/application split:** analysis and recommendations as applications on the kernel, not in it. *Recommended:* yes.
3. **Later:** whether to reopen the product gate (selling the platform); whether and when to run V1–V3 (a separate authorisation; at the stage-1 point at the earliest).

## 10. Actions that must not be taken without further authorisation

- No change to Ch. 0, RESEARCH-PURPOSE or any research record; no new mission adopted.
- No kernel specification; no kernel/application split written into the theory (the Professor's work, by the Principal's routing).
- No Lead DDD Architect or Implementation Engineer work outside their gates; no tests V1–V4 run.
- No change to EKS code.

## Plain answer

**[I]** The proposed kernel is a feasible way to deliver the aim **if the kernel stays the governed core and the analysing and recommending happen in applications around it**. Put everything inside the kernel, and you combine governed knowledge, general reasoning and decision-making into something too broad to define or test, and it would contradict what the research has already settled. **Uncertainty:** nothing is validated in use, all of the design is at S0, the domain question (engineering or all organisational knowledge) is unresolved in your own documents, and I did not open AIP-14 or the charter themselves. The decision is the Principal's.
