# Validate Specification Findings

Full-file grep for the required term list (`validate|warrant|claim|hypothesis|evidence|verdict|
precondition|postcondition|failure|error|reject|invalid|exception|contradiction|determinist|state|
mutation|side.effect`) — every hit accounted for below; none silently dropped.

## Classification of every `Validate`/`Verdict`-relevant hit

| Location | Content | Concerns Validate's own precondition/postcondition/failure contract? | Classification |
|---|---|---|---|
| Lines 48, 64 (ablation tables) | "`Validate` \| none" (no property fails when ablated) | No — this is a *system*-level ablation-study result (what happens to the *whole system* when `Validate` is removed), not `Validate`'s own operational contract | Not applicable to the operator-level contract question |
| Lines 73–76 | "removing them drives the guard to zero... a system that cannot validate never validates wrongly" | No — describes a *vacuity* artifact of the ablation-testing methodology itself, not a precondition on `Validate`'s own execution | Not applicable |
| Line 110–112 | "Removing `Challenge` produces a system that issues verdicts it was never able to attack" | Indirectly — concerns the *consequence* of `Challenge`'s absence on the *quality* of verdicts produced, not `Validate`'s own preconditions | Contextual, not a direct contract statement |
| Line 157 | V7: "nothing can produce a `Verdict` at all, so `Validate`'s atom is inert... vacuous... rejected as evidence" | No — a reachability fact tied to `Qualify`'s absence (already established, MD-030/033), explicitly self-rejected by the source as non-evidentiary | Not applicable — and the source itself disclaims it |
| Lines 196–201 (V0 vs. V6 causal-criticism table) | "Is a `Verdict` reachable without `Challenge`? V0: yes. V6: no." | **Yes, conditionally** — this is the one place in the file that states something precondition-*shaped*: under `V6` specifically, producing a `Verdict` requires (via the surviving-defeater requirement) that a `Challenge` has been issued and survived | **PARTIALLY SPECIFIED, AND ONLY FOR V6** — not for the baseline rule already admitted via `06` |

## Direct answers to the required questions

**Does this file add a precondition to the currently-admitted baseline (`V0`) `Validate` rule?**
**No.** The baseline rule (input availability only, per `06`) is unchanged by anything in this file;
the file's own failure-rate table (line 38 onward) is itself computed *using* the baseline
operator set, not V6.

**Does this file add a precondition to `V6` specifically?** **Yes** — a `Defeater` (surviving a
`Challenge`) is required, which is a real precondition-shaped constraint, though stated as a
consequence of V6's own derivation-rule change (`03`), not as a separately-declared precondition
predicate in Hoare-logic style.

**Does this file add a postcondition beyond "produces a `Verdict`"?** **No** — no statement anywhere
in the file describes a property the produced `Verdict` must satisfy beyond being reachable.

**Does this file add operator-level failure/error/rejection/invalid-input semantics (what `Validate`
does or returns given insufficient or invalid input)?** **No.** The file's own extensive "failure"
language refers to a specific, different technical meaning throughout — *experimental property
failure* (whether properties P1–P10 pass or fail under ablation, a methodology-internal concept) —
never an operational failure mode of the `Validate` operator itself (e.g., an error value, an
exception, a rejection outcome).

## Net effect on MD-033's own findings

**MD-033's precondition/postcondition/failure-semantics gap for the currently-admitted (`V0`)
`Validate` rule is NOT closed by this file.** The one genuinely new, precondition-shaped fact this
file supplies (V6's own `Defeater` requirement) applies to a variant that is not the design actually
in use throughout this file's own experiments, and is not (per MD-031/033) part of the currently-
admissible evidence in any case.
