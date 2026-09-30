import json

READER = ("independent reader (bundle TA-READER-BUNDLE-01); commissioned by the human 2026-09-26; "
          "Claude Code CLI 2.1.283; model deepseek-chat; no repository access; "
          "no other reader's records, no analysis documents, no model results seen")
M1V = ("d61bf5e84:docs/knowledgeos/KnowledgeOS_Engineering_Progression_Model.md "
       "(blob 71edaebc; sha256 b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc = manifest pin)")
M4P = "engineering/governance/ES-006-Engineering-Knowledge-Governance.md"
r = []

def rec(**kw):
    d = dict(test=None, source_id=None, source_version=None, location=None, original_wording=None,
             reconstructed_interpretation=None, competing_interpretation=None, classification=None,
             mechanism_ref=None, confidence=None, reader=READER, independence_class="INDEPENDENT",
             discovery_channel="COMPLETE_READING", operation=None, effect=None)
    d.update(kw)
    r.append(d)

def op(actor, action, obj, auth=None, declared=None, lexicon=None, kind=None, basis=None,
       just=None, amb=None, adm=None, obk=None, split="NONE", squote=None):
    return {"actor": actor, "action": action, "object": obj, "authority_context": auth,
            "source_declared_type": declared, "lexicon_candidate": lexicon, "assigned_kind": kind,
            "typing_basis": basis, "typing_justification": just, "ambiguity_reason": amb,
            "admissible_kinds": adm, "outcome_by_kind": obk, "split_basis": split, "split_quote": squote,
            "typing_recorded_before_effect": True}

def eff(ev=None, gr=None, st=None, bar=None, pr=None, oth=None):
    return {"evidential": ev, "grant": gr, "standing": st, "bar": bar, "promotion": pr, "other": oth}

# ---------------- F-A2e ----------------
rec(test="F-A2e", source_id="F0018", source_version=M1V,
    location="F0018 section 3 ('The kinds that emerge — four, plus one non-progression'), blockquote 'The distinction that carries everything', third sentence",
    original_wording="No ARB ruling can make an observation `Replicated`.",
    reconstructed_interpretation="A ruling — a governance act by the named authority (ARB) — is stated to be unable to change an observation's evidential position (P-4 maturity). The source states the locality of governance acts over the evidential component in its own words.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="\"GOVERNANCE-ACT progression\" over \"EVIDENTIAL progression\" (F0018 section 3, source's own kind names); instance P-4 maturity",
    confidence="HIGH",
    operation=op("ARB (source wording: 'No ARB ruling')", "make an observation `Replicated` (a ruling attempting to confer maturity)",
                 "an observation (P-4 maturity)", "ARB — the Decision Authority, a named role", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The source-described action is a ruling: a decision taken by a named authority (ARB). F0018's own taxonomy assigns 'a decision by a named role' to the GOVERNANCE-ACT kind (section 3 kinds table). It is not an observation, not execution of work, not a composite act. The kind is taken from the action; the effect was read only afterwards."),
    effect=eff(oth="the source states the transition does NOT occur: 'No ARB ruling can make an observation `Replicated`'"))

rec(test="F-A2e", source_id="F0018", source_version=M1V,
    location="F0018 section 2 table, row P-4 maturity (columns 'Invariant protected' and 'Owner')",
    original_wording="**P-4 maturity** | **epistemic confidence** in an observation | a claim never outruns its evidence | ⭐ **the evidence itself** — *governance only records it* | ⛔ optional | ⭐⭐ **PURELY EVIDENTIAL** | ✅ ⭐ **declared ⊥ ADR status** | ⛔ **no — a measurement scale**",
    reconstructed_interpretation="The source assigns the P-4 (purely evidential) progression to the evidence itself as owner and limits governance to recording it. A governance act is thus stated to leave the evidential position unchanged.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-4 maturity (source's own mechanism id); \"the evidence itself — governance only records it\"",
    confidence="MEDIUM",
    operation=op("governance", "record (the evidential progression) — source: 'governance only records it'", "P-4 maturity / the evidence",
                 "governance as owner-contrast; owner of P-4 is 'the evidence itself'", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The source-described action is an act of recording performed by governance in its capacity as the authority that maintains the record. It is an administrative act of governance, not an observation of the phenomenon, not the execution of work, not a composite. The actor is recorded as context only (section 3.0 c.3); the typing rests on the nature of the act, not on the actor's identity."),
    effect=eff(oth="'the evidence itself — governance only records it'"))

rec(test="F-A2e", source_id="F0018", source_version=M1V,
    location="F0018 section 6 ('The success criterion — why multiple progression mechanisms exist'), following the four-engine table",
    original_wording="A single unified lifecycle would force one engine onto all four — requiring either that evidence be grantable by decree, or that decisions wait on evidence that may never come. The repository's two declared orthogonalities and GEP-F1 are three prior refusals of exactly that fusion.",
    reconstructed_interpretation="'Evidence grantable by decree' is named as one of the two things a unified lifecycle would require, and the repository is stated to refuse that fusion. A decree (a governance act) is thus stated not to be able to move the evidential position.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="\"GOVERNANCE-ACT progression\" (a 'decree') against the evidential engine (F0018 section 6)",
    confidence="MEDIUM",
    operation=op("a decree (source wording)", "grant evidence by decree", "evidence", "decree = an act of authority", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "A decree is a decision issued by authority — the paradigm of a governance act, and the engine the source sets against the evidential engine. Not an observation, execution or composite. Typed from the action."),
    effect=eff(oth="the source states the repository refuses the fusion that would require 'evidence be grantable by decree'"))

# ---------------- F-A3g ----------------
rec(test="F-A3g", source_id="F0018", source_version=M1V,
    location="F0018 section 3, blockquote 'The distinction that carries everything', first two sentences",
    original_wording="a GOVERNANCE-ACT progression can be granted. An EVIDENTIAL progression can only be earned.",
    reconstructed_interpretation="The source attributes the grant to the governance-act kind and attributes earning to the evidential kind. An evidential operation therefore does not confer the grant; the grant is conferred by a governance act.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="\"GOVERNANCE-ACT progression\" / \"EVIDENTIAL progression\" (F0018 section 3, source's own kind names)",
    confidence="MEDIUM",
    operation=op("evidence (source wording: 'An EVIDENTIAL progression')", "earn an EVIDENTIAL progression", "an EVIDENTIAL progression",
                 "contrasted in the same passage with the grant, which a GOVERNANCE-ACT progression can receive", None, "EVID", "EVID", "ACTION_SEMANTICS",
                 "The source-described action is the earning of a progression by accumulating evidence — an evidential operation. It is not a decision, not the execution of work, not a composite; the kind is taken from the action, not from the effect."),
    effect=eff(ev="'An EVIDENTIAL progression can only be earned'", oth="'a GOVERNANCE-ACT progression can be granted' — the source attributes the grant to the governance-act kind, not to the evidential operation"))

# ---------------- F-A4 ----------------
rec(test="F-A4", source_id="F0018", source_version=M1V,
    location="F0018 section 5 ('Independence and interactions'), row 'P-3 / P-6 consume P-10'",
    original_wording="⭐⭐ **P-3 / P-6 consume P-10** | **promotion = evidential standing crossing a bar + a governance act recognizing it** | ⭐ **this is the platform's central mechanism, stated at last:** ***evidence EARNS; governance GRANTS; promotion requires BOTH***",
    reconstructed_interpretation="The source decomposes promotion into two conjuncts moved by two different engines and explicitly requires both. It does not model promotion as one atomic transition; the composite is a conjunction (and, per section 3, a 'trajectory'). The alternative reading — that promotion is one atomic act whose effect spans the evidential position and the grant — is not admissible here, because the source names the two engines separately and assigns each its component.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-3 ES-006.1 promotion ladder and P-6 capability (F0018 section 2 kind column: P-3 'EVIDENTIAL + governance gate'; P-6 'COMPOSITE')",
    confidence="MEDIUM",
    operation=op("evidence and governance (two engines, source wording)", "promote: evidential standing crossing a bar + a governance act recognizing it", "promotion",
                 "governance act recognizing it", "P-3 kind = 'EVIDENTIAL + governance gate'; P-6 kind = 'COMPOSITE' (F0018 section 2, source's own Kind column)", "COMP", "COMP", "SOURCE_DECLARED"),
    effect=eff(ev="'evidential standing crossing a bar'", bar="'crossing a bar'", pr="'promotion = evidential standing crossing a bar + a governance act recognizing it'",
               oth="'evidence EARNS; governance GRANTS; promotion requires BOTH'"))

rec(test="F-A4", source_id="F0018", source_version=M1V,
    location="F0018 section 2 table, row P-6 capability (columns 'Kind' and 'A lifecycle?')",
    original_wording="**P-6 capability** | ⭐ **crosses kinds** — see §5 | one invariant per capability | DA / platform | optional | ⭐⭐ **COMPOSITE** | ⛔ **no — a trajectory across P-3, P-7, P-10** | ⛔ **a composite, not one lifecycle**",
    reconstructed_interpretation="The source types P-6 as COMPOSITE and describes it as a trajectory across other mechanisms, explicitly denying that it is one lifecycle. A trajectory across mechanisms is a sequence, not one atomic transition.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-6 capability (F0018 section 2, source's own mechanism id and kind)",
    confidence="MEDIUM",
    operation=op("the capability progression (P-6)", "cross kinds — a trajectory across P-3, P-7, P-10", "a capability",
                 None, "⭐⭐ COMPOSITE (F0018 section 2, source's own Kind column for P-6)", None, "COMP", "SOURCE_DECLARED"),
    effect=eff(oth="'⛔ no — a trajectory across P-3, P-7, P-10'; '⛔ a composite, not one lifecycle'"))

# ---------------- F-A5e ----------------
rec(test="F-A5e", source_id="F0018", source_version=M1V,
    location="F0018 section 5, row 'P-7 produces P-10'",
    original_wording="**P-7 produces P-10** | work produces evidence | healthy",
    reconstructed_interpretation="Reading 1 (primary): 'produces' states a causal relation between two progressions — the work occasions the observations that constitute evidence — rather than an operation whose direct effect is a change of the evidential position. The source separately types the work progression as concerning work only ('a lifecycle … of work, not of knowledge', P-7) and describes the evidential progression as moved by accumulating evidence (section 3). Under this reading the passage does not state that a WORK operation moves the evidential position.",
    competing_interpretation="Reading 2 (competing, adverse): the source states in its own words that the work progression P-7 produces the evidential progression P-10 ('work produces evidence'). Read as a stated effect of a WORK-typed operation on the evidential component, this violates A5e, and section 3.1 requires only that the effect be stated.",
    competing_classification="DIRECT_COUNTEREXAMPLE",
    classification="AMBIGUOUS",
    mechanism_ref="P-7 EEP (source kind: WORK-EXECUTION) producing P-10 evidence n (source kind: PURELY EVIDENTIAL)",
    confidence="MEDIUM",
    operation=op("the executing team / work (P-7 EEP)", "produce evidence (P-10)", "evidence (P-10)",
                 None, "P-7 typed by the source as WORK-EXECUTION; P-10 as PURELY EVIDENTIAL (F0018 section 2, source's own Kind column)", "WORK", "WORK", "SOURCE_DECLARED"),
    effect=eff(ev="'P-7 produces P-10'; 'work produces evidence'", oth="'healthy'"))

rec(test="F-A5e", source_id="F0018", source_version=M1V,
    location="F0018 section 3, kinds table, row WORK-EXECUTION progression",
    original_wording="⭐ **WORK-EXECUTION progression** | **time and effort; has a clock** | ⛔ no — work concludes | the executing team | P-7 · P-8",
    reconstructed_interpretation="The row states what moves a work-execution progression (time and effort), what reverses it (nothing — work concludes), and who owns it. Nothing in the entry concerns the evidential component; the described effects are confined to the work progression.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="\"WORK-EXECUTION progression\" (F0018 section 3, source's own kind name); instances P-7, P-8",
    confidence="MEDIUM",
    operation=op("the executing team", "advance a work-execution progression by time and effort; work concludes", "the work progression",
                 None, "⭐ WORK-EXECUTION progression (F0018 section 3, source's own kind name)", None, "WORK", "SOURCE_DECLARED"),
    effect=eff(oth="'⛔ no — work concludes'; moved by 'time and effort; has a clock'"))

# ---------------- F-A5g ----------------
rec(test="F-A5g", source_id="F0018", source_version=M1V,
    location="F0018 section 2 table, row P-7 EEP (columns 'Kind', 'Independent?', 'A lifecycle?')",
    original_wording="**P-7 EEP** | the **work's** stage | no stage skipped; human approval | DA | ⭐ **mandatory once begun** | ⭐ **WORK-EXECUTION** *(temporal)* | ✅ — GEP-F1 separates it from artifact lifecycles | ✅ yes — **of work, not of knowledge**",
    reconstructed_interpretation="The source states that the work progression's lifecycle is 'of work, not of knowledge', and that GEP-F1 separates it from artifact lifecycles. The grant (the artifact's standing/qualification) is therefore not moved by the work progression.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-7 EEP (source kind: WORK-EXECUTION, temporal); GEP-F1",
    confidence="MEDIUM",
    operation=op("the executing team / DA", "advance the work's stage", "the work (the work's stage)",
                 "DA", "⭐ WORK-EXECUTION *(temporal)* (F0018 section 2, source's own Kind column for P-7)", None, "WORK", "SOURCE_DECLARED"),
    effect=eff(oth="'✅ yes — of work, not of knowledge'; 'GEP-F1 separates it from artifact lifecycles'"))

rec(test="F-A5g", source_id="F0018", source_version=M1V,
    location="F0018 section 1, closing note (citing Reference Model section 6)",
    original_wording="Reference Model §6 — **\"two lifecycles, never conflated (GEP-F1)\"**: the GOVERNANCE-ARTIFACT lifecycle (driven by Human Decision Events) vs the GOVERNED-WORK lifecycle (governed by the active artifact).",
    reconstructed_interpretation="The source states that the artifact lifecycle (driven by human decision events) and the work lifecycle (governed by the artifact) are two and are never conflated. Work therefore does not move the artifact's governance-derived position — the grant.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="GEP-F1 (Reference Model section 6, cited as existing canon in F0018 section 1)",
    confidence="MEDIUM",
    operation=op("the executing team", "advance the GOVERNED-WORK lifecycle", "the governed work",
                 "the active artifact governs the work lifecycle", "\"the GOVERNED-WORK lifecycle (governed by the active artifact)\" — quoted from the source", None, "WORK", "SOURCE_DECLARED"),
    effect=eff(oth="'two lifecycles, never conflated (GEP-F1)'; the work lifecycle is distinct from the GOVERNANCE-ARTIFACT lifecycle"))

# ---------------- F-A6 ----------------
rec(test="F-A6", source_id=M4P, source_version="M-4 ES-006, all 7 revisions d63202b8c … 668cc7b22 (ES-006.1 paragraph and ladder line verified byte-identical in all seven)",
    location="ES-006.1 — The Promotion Ladder (present unchanged in every revision)",
    original_wording="**ES-006.1 — The Promotion Ladder** *(ARB, refined 2026-07-11)*: Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability. Qualification sits between research and engineering — nothing is promoted because it is a good idea; everything is promoted because operational evidence demonstrated necessity (the burden-of-proof rule, R-37, applied to promotion).",
    reconstructed_interpretation="The bar for promotion is stated as a demonstration of necessity by operational evidence. Under this formulation a promotion is not produced by a change of rule; it requires an evidential event. This is the state of the bar across the entire recorded revision history: no revision altered it.",
    classification="DIRECT_SUPPORT",
    a6_level="META_LEVEL",
    mechanism_ref="ES-006.1 promotion ladder / the bar (source's own rule name); F0018 P-3 and P-10",
    confidence="HIGH",
    operation=op("the promoting authority (DA) under the ARB-refined ladder rule", "promote an item from one ladder stage to the next", "a knowledge item",
                 "ARB, refined 2026-07-11", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The described action is an act of promotion — admitting an item to a higher ladder stage, operated as a governance decision under the ladder rule. It is not an observation, not the execution of work, not a composite. The evidential condition that must hold is the rule's condition, not the action's kind (section 3.0 c.5: the effect is never a typing criterion)."),
    effect=eff(bar="promotion occurs 'because operational evidence demonstrated necessity'; the bar is an evidential demonstration",
               pr="'everything is promoted because operational evidence demonstrated necessity'"))

rec(test="F-A6", source_id=M4P, source_version="M-4 ES-006, all 7 revisions d63202b8c … 668cc7b22 (ES-006.2 paragraph verified unchanged in all seven)",
    location="ES-006.2 — The Knowledge Research Freeze",
    original_wording="**ES-006.2 — The Knowledge Research Freeze** *(ARB 2026-07-11 — a milestone, not a rule)*. The RQ-002 knowledge theory (General Knowledge Constitution + Project Knowledge Strategic Model, `docs/implementation/`) is complete-enough-to-be-falsified and frozen: no theoretical refinement; changes only from pilot/operational evidence.",
    reconstructed_interpretation="A change to the frozen canon is expressly conditioned on pilot/operational evidence. The source thus states that canon changes require an evidential event, which is the A6 property applied to the canon rather than the bar.",
    classification="DIRECT_SUPPORT",
    a6_level="META_LEVEL",
    mechanism_ref="ES-006.2 knowledge research freeze (source's own rule name); the RQ-002 canon",
    confidence="MEDIUM",
    operation=op("ARB", "change the frozen theory (permitted only from pilot/operational evidence)", "the RQ-002 knowledge theory (the canon)",
                 "ARB 2026-07-11", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The described action is a change to a frozen body of theory — a canon change operated by the ARB. It is not an observation, execution or composite; the kind is taken from the action (an act of governance over the canon)."),
    effect=eff(oth="'no theoretical refinement; changes only from pilot/operational evidence'"))

rec(test="F-A6", source_id=M4P, source_version="M-4 ES-006, all 7 revisions d63202b8c … 668cc7b22 (ES-006.3 paragraph verified unchanged in all seven)",
    location="ES-006.3 — Harvest Discipline, final sentence",
    original_wording="Research dossiers are input-only: never architecture until promoted through ES-006.1.",
    reconstructed_interpretation="An existing object (a research dossier) cannot acquire the higher qualification ('architecture') by any route other than promotion through the ES-006.1 ladder — that is, without the evidential demonstration the ladder requires.",
    classification="DIRECT_SUPPORT",
    a6_level="OBJECT_LEVEL",
    mechanism_ref="ES-006.3 harvest discipline; ES-006.1 promotion ladder",
    confidence="MEDIUM",
    operation=op("the promoting authority under ES-006.1", "admit a research dossier to architecture (promote it)", "research dossiers",
                 "ES-006.1 promotion ladder", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The described action is an act of admission — promotion of an item to the architecture tier, gated by the standard's own rule. A governance act of promotion, not an observation, execution or composite."),
    effect=eff(bar="'never architecture until promoted through ES-006.1'", oth="'input-only'"))

rec(test="F-A6", source_id=M4P, source_version="M-4 ES-006, revision 5 → revision 6 (8d1df4b1d → 43682264d)",
    location="ES-006.4 — The Harvest Question: the standing question and the 'Reveal, not produce' gloss, as changed between revisions 5 and 6",
    original_wording="'Did this work produce reusable engineering knowledge?' → 'Did this work REVEAL reusable engineering knowledge?'; '*Reveal, not produce* (ARB): the project **reveals** knowledge · the engineer **captures** it · the platform **governs** it · engineering **promotes** it'",
    reconstructed_interpretation="Reading 1 (primary): a meta-level amendment to the canon's text was made by the ARB, and the source states no re-qualification of any already-existing or pending object as a consequence. A canon change occurred without any stated effect on qualification.",
    competing_interpretation="Reading 2 (competing): the source is silent about already-existing and pending objects; under section 3.1's non-inference rules ('one source's silence ≠ another source's claim') the passage does not bear on the property, rather than supporting it.",
    competing_classification="NOT_BEARING",
    classification="AMBIGUOUS",
    a6_level="META_LEVEL",
    mechanism_ref="ES-006.4 harvest question (source's own rule name); the canon's text",
    confidence="LOW",
    operation=op("ARB", "amend the wording of the standing harvest question (produce → REVEAL)", "ES-006.4, the canon's own text",
                 "ARB", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The described action is an amendment of a rule text by the ARB — a governance act on the canon. Not an observation, execution or composite; typed from the action."),
    effect=eff(oth="the amendment changes the question's wording; the source states no effect on the qualification of any already-existing or pending object"))

rec(test="F-A6", source_id=M4P, source_version="M-4 ES-006, revision 6 → revision 7 (43682264d → 668cc7b22)",
    location="ES-006, 'Registered (pointers)' table — the DDD Tactical Governance Principles row, added in revision 7",
    original_wording="DDD Tactical Governance Principles (methodology module — ADOPTED via explicit DA early-promotion exception R-39) | `../knowledge/methodology/DDD_Tactical_Governance_Principles.md` — projects bind (PublicDigit: `docs/architecture/governance/DDD_PRINCIPLES.md`), never fork",
    reconstructed_interpretation="Reading 1 (primary): the row records a governance act — an explicit Decision Authority exception — that adopted an already-existing module outside the ES-006.1 ladder. The row states no change to the bar or rule itself, and no transition of the constrained component (the bar) is described, so the procedural counterexample form (section 3.0 g.5) is not satisfied.",
    competing_interpretation="Reading 2 (competing, adverse): an 'early-promotion exception' is a rule-level act by which an already-existing object (the module) was carried to ADOPTED without any stated new evidential event. That is the effect the A6 countermodel describes — an item brought across the bar with no evidential event.",
    competing_classification="DIRECT_COUNTEREXAMPLE",
    classification="AMBIGUOUS",
    a6_level="NEITHER_OR_UNCLEAR",
    mechanism_ref="ES-006.1 promotion ladder / the bar; the registered pointer 'methodology module'",
    confidence="MEDIUM",
    operation=op("the Decision Authority (DA)", "adopt the module via an explicit early-promotion exception", "the DDD Tactical Governance Principles methodology module",
                 "explicit DA early-promotion exception R-39", None, "GOV", "GOV", "ACTION_SEMANTICS",
                 "The described action is an act of adoption — a Decision Authority decision conferring adopted status on a module, taken by exception. It is a governance act, not an observation, not the execution of work, not a composite. The kind is taken from the action; the effect was read only afterwards."),
    effect=eff(st="ADOPTED", oth="adopted 'via explicit DA early-promotion exception R-39'; the source states no evidential event"))

# ---------------- F-A3m ----------------
rec(test="F-A3m", source_id="F0018", source_version=M1V,
    location="F0018 section 3, kinds table, row EVIDENTIAL progression",
    original_wording="⭐ **EVIDENTIAL progression** | ⭐ **accumulating evidence — nobody can decree it** | ⛔ **only by refutation, never by decision** | *the evidence itself; governance merely recognizes* | P-4 · P-10",
    reconstructed_interpretation="Reading 1 (primary): the passage states that an evidential progression accumulates, and that its reversal is a matter for refutation rather than decision. Under the reading that A3m concerns loss of qualification through additional supporting evidence, and given the source's separate statement that P-10 is a 'monotonic counter', the reversibility clause is a property of the progression rather than the stated effect of one operation.",
    competing_interpretation="Reading 2 (competing, adverse): the clause states an effect. Refutation — an EVIDREF operation, one of the two kinds A3m's kind pair fixes (EVID/EVIDREF) — reverses an evidential progression (P-4, P-10). That is the loss of an evidential state by an evidential operation; if A3m forbids loss of evidential position by EVID/EVIDREF, the passage violates it.",
    competing_classification="DIRECT_COUNTEREXAMPLE",
    classification="AMBIGUOUS",
    mechanism_ref="\"EVIDENTIAL progression\" (F0018 section 3, source's own kind name); instances P-4 maturity and P-10 evidence n",
    confidence="MEDIUM",
    operation=op("the evidence itself (source wording)", "accumulate evidence; reverse the evidential progression by refutation", "the EVIDENTIAL progression (P-4 · P-10)",
                 "nobody can decree it; 'governance merely recognizes'", "⭐ EVIDENTIAL progression (F0018 section 3, source's own kind name)", None, "EVID", "SOURCE_DECLARED"),
    effect=eff(ev="'accumulating evidence'; '⛔ only by refutation, never by decision'", oth="'nobody can decree it'; 'the evidence itself; governance merely recognizes'"))

rec(test="F-A3m", source_id="F0018", source_version=M1V,
    location="F0018 section 2 table, row P-10 evidence n (columns 'Kind' and 'A lifecycle?')",
    original_wording="**P-10 evidence n** | **how much evidence exists** | detection ≠ prevention; *\"never add these two lines\"* | the producing track | ⛔ cannot be decreed | ⭐⭐ **PURELY EVIDENTIAL** — *monotonic counter* | ✅ — feeds P-3/P-4/P-6 | ⛔ **no — accumulation, not states**",
    reconstructed_interpretation="The source describes the evidential position (P-10) as accumulation by a monotonic counter — quantity increases and does not decrease. That supports the property that an evidential operation does not reduce the evidential position.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-10 evidence n (source's own mechanism id; kind: PURELY EVIDENTIAL — monotonic counter)",
    confidence="MEDIUM",
    operation=op("the producing track", "accumulate evidence (the count n)", "evidence n",
                 "'cannot be decreed'", "⭐⭐ **PURELY EVIDENTIAL** — *monotonic counter* (F0018 section 2, source's own Kind column)", None, "EVID", "SOURCE_DECLARED"),
    effect=eff(ev="'how much evidence exists'; 'monotonic counter'; '⛔ no — accumulation, not states'", oth="'⛔ cannot be decreed'"))

# ---------------- Q-H6 (interpretive) ----------------
rec(test="Q-H6", source_id="F0018", source_version=M1V,
    location="F0018 section 2 table, row P-3 ES-006.1 (columns 'What changes?', 'Kind', 'A lifecycle?')",
    original_wording="**P-3 ES-006.1** | knowledge's **reusability standing** | ⭐ *never promote from a single occurrence* | **DA** | ⛔ optional *(most knowledge never climbs)* | ⭐ **EVIDENTIAL + governance gate** | ⚠️ consumes P-10 | ⚠️ *a **ladder** — monotonic, no return edges except demotion-by-decision*",
    reconstructed_interpretation="Both. The source describes promotion as a ladder — a standing the knowledge holds ('knowledge's reusability standing'; 'monotonic, no return edges except demotion-by-decision') — and at the same time as gated by a governance act ('EVIDENTIAL + governance gate'; owner DA). Material note: the M-3 item (statuses.yaml) required by Q-H6 was not released, so this reading rests on M-1 alone; no verdict is claimed and the interpretive questions take no verdict under section 5.0.",
    classification="DIRECT_SUPPORT",
    mechanism_ref="P-3 ES-006.1 promotion ladder (source's own mechanism id); H-6",
    confidence="MEDIUM")

with open("/tmp/ta-reader-01/out/records.jsonl", "w", encoding="utf-8") as f:
    for d in r:
        f.write(json.dumps(d, ensure_ascii=False) + "\n")
print("records:", len(r))
