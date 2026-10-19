#!/usr/bin/env python3
"""Builds the round-3 targeted-review packet: current claim texts (author's correction-history notes stripped),
definitions, source excerpts. Read-only on inputs; writes one file.  python3 -I make_review_packet_r3.py <repo_root> <out.md>"""
import hashlib, re, sys
root, out = sys.argv[1], sys.argv[2]
W = root + "/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/"
K = root + "/docs/knowledge_tranfer/20260816_"
CH0, CH1, CH2, GL = W+"chapter_00/CH-00-foundations.md", W+"chapter_01/CH-01-knowledge-authority-execution.md", W+"chapter_02/CH-02-rules-and-conflict.md", W+"APPENDIX-glossary.md"
txt = {p: open(p, encoding="utf-8").read() for p in (CH0, CH1, CH2, GL)}
NOTE = re.compile(r"\s*\*\((?:[^()]|\([^()]*\))*\)\*")
def strip(t):
    t = "\n".join(l for l in t.split("\n") if not l.startswith("> **Corrected"))
    t = NOTE.sub(lambda m: "" if re.search(r"[Rr]ound|orrect|Lookback|review|[Ss]coped|qualified|wording|withdrawn|first version|added|Added", m.group(0)) else m.group(0), t)
    t = t.replace("*Open (added in correction round 3):*", "*Open:*").replace(" (added batch 3, file 5; marked Hyp in round 2)", "")
    t = re.sub(r"\(\*\*Src\*\*; corrected after audit [A-Z]-\d+\)", "(**Src**)", t)
    t = re.sub(r"; (?:corrected |restated )?after audit [A-Z]-\d+(?:, [A-Z]-\d+)*", "", t)
    t = re.sub(r" \((?:corrected |restated )?after audit [A-Z]-\d+(?:, [A-Z]-\d+)*\)", "", t)
    t = re.sub(r" ?\(audit [A-Z]-\d+\)", "", t)
    for a in ("; round 3", ", round 3", "; revised in round 2", "; round 2", ", round 2", "; revised", " (round 3)"):
        t = t.replace(a, "")
    return t
def between(p, a, b, incl_b=False):
    s = txt[p]; i = s.index(a); j = s.index(b, i + len(a))
    return s[i:j + (len(b) if incl_b else 0)]
def lines_with(p, pred):
    return "\n".join(l for l in txt[p].split("\n") if pred(l))
def src(f, a, b):
    L = open(K + f, encoding="utf-8").read().split("\n")
    return "\n".join(f"{n:>4}  {L[n-1]}" for n in range(a, b + 1))
P = []
P.append("# Review packet RP-R3 — targeted independent review (read-only)\n")
P.append("**Built** by `tools/make_review_packet_r3.py` from the current manuscript. Inputs, sha256:\n" + "\n".join(f"- `{p.split('manuscript/')[1]}` {hashlib.sha256(txt[p].encode()).hexdigest()}" for p in (CH0, CH1, CH2, GL)))
P.append("""
## Your task

You are a fresh, read-only reviewer: formal logic, mathematics, DDD and research method. **Do not create, modify or delete any file.** Do not decide the four reserved questions listed below; evaluate whether the text keeps them open.

**You may read only:** this packet; and, to check context around the excerpts, the background source files whose excerpts appear in Part D (all in `docs/knowledge_tranfer/`, names beginning `20260816_0841`, `0905`, `0922`, `1023`, `1029`, `1211`, `1332`). **Do not read** the manuscript files themselves, any review or disposition record, the idea register, the KnowledgeOS research corpus, sealed material, `.env` or credential files, transcripts, or anything else. If you need more, say so in your report.

**The excerpts in Parts B and C are the current manuscript text with the author's editorial correction-history notes removed** (notes of the form "*(Round …)*" and blockquotes beginning "Corrected …"). Nothing else was changed.

**Reserved questions (keep open, do not decide):**
- **R1** the ending policy P (which grant endings carry over to claims, per kind);
- **R2** whether premise P-E is adopted, narrowed or kept conditional;
- **R3** the reading of I-2's "reasoned" part ((a), (b) or (c));
- **R4** whether adopting a rule as mandatory authorizes blocking (OQ-24).

### Areas and what to do

- **A — Authority formula (Part B.1–B.3).**
  - Reconstruct the definitions of validly-made and currently-authoritative as premises → conclusion.
  - Test the formula against:
    - multiple covering grants (cited and uncited);
    - joint grants;
    - **each** ending kind (expiry, consumption, revocation, supersession, annulment, suspension);
    - claims that state no validity period;
    - records that cite no grant.
  - Check whether time of act, act window, binding period and time of use are distinct and consistent.
  - Check whether every open question (R1, record time) is stated as open rather than settled.
  - Check whether any comparison involves incomparable quantities.
  - Check whether the source readings (V3, AB) are stated accurately against the excerpts in Part D.
- **B — Enforcement (Part B.4–B.6).**
  - Reconstruct the argument and premise P-E.
  - Check that the conclusion follows **only** given P-E and is not presented as adopted.
  - Attempt counterexamples to the three conditions for an admissible block, using your **own** cases. Do not reuse the examples given in the text.
  - Check the treatment of mandatory rules, candidate rules, authorized freezes, configuration-driven escalation and authority gates (fail-closed).
  - Check the delimitation of "normative effects".
- **C — I-2 "reasoned" (Part B.7, B.8 and A.2).**
  - Check that explicit / reasoned / visible are separated correctly.
  - Check that readings (a), (b), (c) are stated fairly, with consequences and evidence.
  - Check that no passage or glossary row states one reading as settled.
  - Check that "valid", "well-founded", "verifiable" and "quality obligation" are used consistently across Parts A–C.
- **Consistency.** The glossary rows (Part C) and the dependent passages (Part B.6, B.8) must agree with the main claims.

**For each area A, B and C, give:**
- a verdict: SOUND / SOUND WITH MINOR ISSUES / DEFECTIVE / CANNOT JUDGE;
- the exact claim reviewed (quote, with part and line);
- the premises;
- the counterexample(s) attempted and their outcome;
- evidence;
- remaining uncertainty;
- whether correction is required;
- whether a reserved question is affected.

**A failed counterexample attempt does not prove a claim; say what is and is not established.**

**Report** (final message, about 1,200–1,800 words):
1. Scope actually read.
2. Verdicts with reasoning.
3. Confirmed defects / interpretive points / unresolved questions.
4. Effects on the reserved questions (alternatives and consequences only).
5. Recommended corrections (one sentence each).
6. Limitations: you are a fresh session of the same model family as the author, not an external expert.
7. Recommendation: CORRECT, PAUSE, or eligible for the next approval step.
""")
P.append("## Part A — Definitions and axioms\n\n### A.1 Authority axiom (Ch. 1 §1.6)\n\n" + strip(between(CH1, "**Development (Ded).** These are not separate rules", "4. **Standing:**", True)) + " …\n")
P.append("### A.2 Invariants and I-2 (Ch. 0 §0.5–§0.6)\n\n| # | Invariant | Kind | Note |\n|---|---|---|---|\n" + strip(lines_with(CH0, lambda l: l.startswith("| I-"))) + "\n\n" + strip(lines_with(CH0, lambda l: "> status transitions" in l or l.startswith("This makes I-2"))) + "\n")
P.append("### A.3 Kinds of empty answer (Ch. 2 §2.8.8)\n\n" + strip(lines_with(CH2, lambda l: re.match(r"\| E\d \|", l) is not None)) + "\n")
P.append("### A.4 Grant endings and delegation (Ch. 1 §1.13.2–§1.13.3)\n\n" + strip(lines_with(CH1, lambda l: l.startswith(("2. **Endings now exist", "3. **A new kind of ending", "5. **Record integrity")))) + "\n\n" + strip(between(CH1, "### 1.13.3", "- **\"Authority of a rule\"")) + "\n")
P.append("## Part B — Claims under review (current text, notes removed)\n\n### B.1 Authority formula (Ch. 1 §1.14.1)\n\n" + strip(between(CH1, "*Premise made explicit (Int).*", "### 1.14.2")))
P.append("### B.2 Two times (Ch. 1 §1.16.2)\n\n" + strip(between(CH1, "**Development (Ded).** Authority statements need two time indices", "### 1.16.3")))
P.append("### B.3 Stored derived values (Ch. 1 §1.16.8)\n\n" + strip(lines_with(CH1, lambda l: l.startswith("**Time-triggered conditions"))) + "\n")
P.append("### B.4 Enforcement (Ch. 1 §1.14.5)\n\n" + strip(between(CH1, "**Classification:** NEW IDEA. **It does not follow", "**Two kinds of gate")))
P.append("### B.5 Rules on blocking in Chapter 2 (§2.9–§2.10)\n\n" + strip(lines_with(CH2, lambda l: l.startswith(("> **A rule may be MANDATORY", "**A third orthogonal property", "`scripts/design-check.sh` states", "- **Machine-enforced checks**")))) + "\n")
P.append("### B.6 Dependent passages (Ch. 1 §1.17.3, §1.17.5)\n\n" + strip(lines_with(CH1, lambda l: l.startswith(("- **refusal to proceed**", "**Hardness and its owner")))) + "\n")
P.append("### B.7 I-2 and validity vs well-foundedness (Ch. 1 §1.17.2)\n\n" + strip(between(CH1, "### 1.17.2", "### 1.17.3")))
P.append("### B.8 Rule lifecycle and exceptions (Ch. 2 §2.10–§2.11)\n\n" + strip(lines_with(CH2, lambda l: l.startswith(("- **Configuration rules are edited in place", "**The rule lifecycle (Src, ruled RM-3", "- By Ch. 1 §1.12, an exception is valid only if"))))+"\n")
terms = ("validity (period)", "effective (rule)", "authority (valid act)", "enforcement consequence", "current authority", "authority gate", "conformance gate", "valid (act)", "well-founded (decision)", "verifiable", "quality obligation", "ending policy P", "P-E (premise)", "binding strength", "standing")
P.append("## Part C — Glossary rows\n\n| Term | Meaning | Not to be confused with | Where |\n|---|---|---|---|\n" + lines_with(GL, lambda l: any(l.startswith(f"| **{t}") for t in terms)) + "\n")
P.append("## Part D — Source excerpts (line numbers are the source's)\n")
for f, a, b, name in (("0841_target_architecture_v3.md", 297, 323, "V3 §10"), ("0922_aggregate_boundaries.md", 94, 111, "AB §5"),
                      ("1332_authority_model_business_semantics.md", 196, 206, "AMS rule–grant linkage"), ("1332_authority_model_business_semantics.md", 276, 292, "AMS rulings AM-1…AM-5"),
                      ("0905_architecture_conformance.md", 14, 38, "CF §0"), ("1211_rule_model_business_semantics.md", 176, 191, "RS binding strength vs enforcement"),
                      ("1211_rule_model_business_semantics.md", 262, 276, "RS rule lifecycle (RM-3)"), ("1029_invariant_allocation.md", 42, 60, "IA ownership test"),
                      ("1029_invariant_allocation.md", 169, 175, "IA open questions"), ("1023_invariant_aggregate_matrix.md", 207, 227, "IM worksheet INV-EXC-004")):
    P.append(f"### {name} — `docs/knowledge_tranfer/20260816_{f}` L{a}–{b}\n\n```\n{src(f, a, b)}\n```\n")
open(out, "w", encoding="utf-8").write("\n".join(P))
