# IV.1 · Engineering with KnowledgeOS

[P] What would it mean to build against this architecture? Honestly: mostly future work, and this
chapter will not pretend otherwise.

**What exists today.** [E] The executable layer is thin and specific: a session bootstrap that
resolves a process's lane, role, and authority from the governed record and *fails closed* — on
any unresolved fact the answer is "not authorized," never a guess; a read-only presenter of the
adopted operating model; observation-pipeline diagnostics whose own doc-comment states the
architecture's temperament ("it checks and reports — it never installs, never repairs"). None of
the formal objects — the gap function, the contract derivation, the ladder as datatype, the
decision contract — has an implementation. [P] An engineering programme that wanted them would
start from the ratified signatures in Part III and would inherit twelve open questions, two of
which (the aggregation operator, OQ-3; action semantics, OQ-4) sit directly on the implementation
path.

**What building would preserve.** [FA] The invariants are the specification: duplicates must not
raise confidence and dependency resolution precedes aggregation (the two TESTED rows are the two
an evidence store cannot skip); admission must pass a versioned in-force policy; commitment must
be an authority act distinct from acceptance; skipping statuses must be structurally impossible;
and the state store must be able to *hold* rejection, conflict, and ignorance as first-class
conditions rather than deleting them.

**A pattern worth copying regardless.** [M] The programme's own tooling demonstrates the cheapest
useful slice: govern the *authority* layer first. The bootstrap implements no epistemics at all —
only the refusal to conflate identity with authorization — and it is already the strongest
implementation-side evidence the conformance pass found for anything. [P] A team adopting
KnowledgeOS incrementally would do well to start exactly there: the boundaries are cheaper than
the calculus, and the architecture's history suggests they matter more.

**One missing statement, worth writing early.** [U — OQ-12] Nothing on the repository side states
the dependency-first ordering rule (I-6). Any implementation of evidence handling should carry
that sentence in its contract before it carries any code.
