# Independent review packet: §1.14.1 formal model and candidate repair M1

**You are not the author.** Do not take the author's candidate as the answer to confirm; your job is to falsify. Work read-only. Create, change or delete no file anywhere. Report in your final message.

## Material

| File | What it is |
|---|---|
| `CH-01-baseline-v1.md` | Chapter 1 **baseline v1**, sha256 `4d65261d18f6fdb1af007732ca46e443d8c1a057fe1f3e0746a6fc852f391367`. **§1.14.1** is the subject; §1.13 holds the authority concepts it relies on (C1, AM-1…AM-5, delegation §1.13.3) |
| `M1-candidate-model.md` | the author's **candidate** repaired model (PROPOSED) |
| `M1-claimed-theorems.md` | the author's claimed theorems with proofs: **claims to falsify** |
| `lead-encodings-SUSPECT/` | the author's Python encodings of v1 (`model2.py`, `suite.py`, `props.py`) and of M1 (`m1.py`). **Treat them as suspect:** an earlier review found that encodings by this author hid defects through unstated assumptions. Do not use them as evidence that anything is correct |

**Authorized background sources** (read only the cited lines; read nothing else under `docs/knowledgeos/`; never read `.env*` files or `docs/knowledgeos/chronological-read/_batch_input*`). They are in `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledge_tranfer/`:
- `20260816_1332_authority_model_business_semantics.md` (AMS): L38–51, L57–135, L183–226, L278–298;
- `20260816_1023_invariant_aggregate_matrix.md` (IM): L125–150, L205–227;
- `20260816_1029_invariant_allocation.md` (IA): L85–105;
- `20260816_1044_domain_events.md` (DE): L95–110, L360–392.

## What to do

1. **Reconstruct the v1 model of §1.14.1 independently** from the baseline text: every symbol, domain and codomain, quantifier, and parameter. Classify each definition: explicitly stated · derived · the author's assumption · unresolved interpretation.
2. **Write the v1 headline claim (Property 1, totality) with explicit quantifiers and domain.** Decide whether it is total, partial, undefined for some inputs, or false for some permitted input, with a witness for each failing case.
3. **For v1, investigate each of these reported defects separately.** Confirm or refute each with a derivation or a concrete counterexample:
   - (a) monotonicity of the operator under each value of υ, and the theorem cited;
   - (b) grant issuance;
   - (c) delegation (recover what the sources actually rule, versus what the book adds);
   - (d) totality of the headline;
   - (e) expiry, including the boundary instant; does any source decide inclusive vs exclusive?
   - (f) time domains: act time, record position, effective time, the evaluation instant, the record state. Which comparisons are made, and are any conflated?
4. **Attack the candidate M1.**
   - Do its changes resolve the defects they target?
   - Do they change the intended meaning of v1 anywhere, without saying so?
   - Do they introduce a new unsupported claim?
   - Is each element labelled with the right status ([SRC] / [DER] / [ASM] / [UNR] / [PROP])?
5. **Attack the claimed theorems** (M1-T1 … T4, Property 2′, Property 4).
   - Look for circularity, hidden premises, wrong quantifiers, an infinite or ill-defined key set, non-monotone terms hidden inside "constants", and a misuse of the fixed-point theorem.
   - Search for counterexamples involving issuance, delegation, expiry, consumption, backdated or late-recorded entries, the stage-2 υ suppression, and the "own view" reading under ρ = at-the-time.
6. **Identify assumptions the author's encodings carry** that the text does not, and say which cases they would hide.
7. **Separate three kinds of issue:** mathematical defects · terminology issues · policy choices that the Principal must decide. Do not decide policy.

## Report format

- verdict per item 1–7 (SOUND · SOUND WITH MINOR ISSUES · DEFECTIVE);
- numbered findings: location · severity · type (mathematical / terminology / policy / fidelity) · derivation or counterexample · suggested action;
- the policy questions you see, with alternatives, but no recommendation required;
- limitations: say what you did not check. You are a session of the same model family as the author, not an external expert.
