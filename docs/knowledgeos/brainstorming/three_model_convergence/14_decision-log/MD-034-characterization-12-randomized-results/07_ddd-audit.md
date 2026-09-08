# DDD Audit

## Does this file introduce genuine domain concepts, or only experimental/type/capability labels?

**Only experimental/type/capability labels — consistent with every prior audit in this sequence
(MD-029, MD-031, MD-033).** `Claim`, `Evidence`, `Hypothesis`, `Verdict`, `Defeater` are used
throughout `12` exactly as carrier-kind labels feeding a statistical/ablation experiment (e.g., line
7: "1–20 entities/observations/claims... 1–5 competing hypotheses" — a generator-parameter
description, not a domain model). No source text in this file gives any of these concepts identity,
lifecycle, or behavior beyond their role in the reach/derivation apparatus already characterized.

## Does `V6` introduce a new domain concept (`Defeater`-gated validation), or merely a new type rule?

**A new type rule over existing carriers, not a new domain concept.** `V6`'s own definition ("a
`Verdict` requires a surviving-defeater step") is stated entirely in terms of carriers already
established (`Claim`/`Hypothesis`/`Evidence`/`Defeater`/`Verdict`) and one atom
(`warrant-assessment`, implicitly relabeled `evidential-support` per MD-030's own reading of the
executable `V6`) — it changes which inputs satisfy a rule, not what kind of object any carrier is.

## Aggregate / command / bounded-context reading

**Not established, unchanged from every prior finding in this sequence.** The "Causal /
model-criticism check" section's own framing ("Applied to the kernel," line 194) uses "kernel" in
this research programme's own operator-set sense (per `04`'s "C0"/"C0+Qualify" usage), not in any
DDD aggregate sense — no source text in this file uses DDD vocabulary at all.

## No promotion performed

No carrier, atom, or variant label in this file is treated as an entity, value object, domain event,
policy, domain service, or invariant beyond what the source's own explicit taxonomy (operator / atom
/ carrier / capability, established in `03`/`04`, MD-033 `07`) already supports.
