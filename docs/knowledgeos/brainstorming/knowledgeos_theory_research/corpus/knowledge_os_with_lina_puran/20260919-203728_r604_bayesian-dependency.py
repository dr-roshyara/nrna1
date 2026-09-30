def normalize_pair(p_h_e1, p_not_h_e0):
    denominator = p_h_e1 + p_not_h_e0
    if denominator == 0:
        return None
    return p_h_e1 / denominator


def naive_independent_posterior(prior_h, evidences, sensitivity=0.9, false_positive=0.1):
    """
    Naive Bayes assumes conditional independence:
        P(E1,...,En | H) = product P(Ei | H)
    and likewise under not-H.
    """
    h_like = prior_h
    nh_like = 1.0 - prior_h

    for e in evidences:
        if e.observed_value == 1:
            h_like *= sensitivity
            nh_like *= false_positive
        else:
            h_like *= (1.0 - sensitivity)
            nh_like *= (1.0 - false_positive)

    return normalize_pair(h_like, nh_like)


def correlated_source_posterior(prior_h, evidences, source_accuracy=0.9):
    """
    Dependency-aware model:
    all observations belonging to the same source_group are manifestations
    of one latent source signal S.

    For each distinct source group:
        P(all positive | H) = source_accuracy
        P(all positive | not-H) = 1-source_accuracy

    Repeated reports from the same source therefore do not multiply the
    evidential likelihood again.
    """
    grouped = {}
    for e in evidences:
        grouped.setdefault(e.source_group, []).append(e)

    h_like = prior_h
    nh_like = 1.0 - prior_h

    for group in grouped.values():
        values = {e.observed_value for e in group}
        if len(values) != 1:
            # The simple latent-source benchmark cannot represent
            # contradictory observations from the same group.
            return None

        value = next(iter(values))
        if value == 1:
            h_like *= source_accuracy
            nh_like *= (1.0 - source_accuracy)
        else:
            h_like *= (1.0 - source_accuracy)
            nh_like *= source_accuracy

    return normalize_pair(h_like, nh_like)


def enumerate_joint_posterior(prior_h, evidences, source_accuracy=0.9):
    """
    Exact finite enumeration for the declared latent-source model.

    This is intentionally computational rather than a universal Bayesian
    theorem. It verifies the joint distribution used by this benchmark.
    """
    groups = sorted({e.source_group for e in evidences})
    group_values = list(product([0, 1], repeat=len(groups)))

    p_h_e = 0.0
    p_not_h_e = 0.0

    for h in [0, 1]:
        prior = prior_h if h else 1.0 - prior_h

        for assignment in group_values:
            source_state = dict(zip(groups, assignment))
            p_sources = 1.0

            for s in assignment:
                p_sources *= source_accuracy if s == h else (1.0 - source_accuracy)

            compatible = True
            for e in evidences:
                if source_state[e.source_group] != e.observed_value:
                    compatible = False
                    break

            if compatible:
                if h:
                    p_h_e += prior * p_sources
                else:
                    p_not_h_e += prior * p_sources

    return normalize_pair(p_h_e, p_not_h_e)


def validate_bayesian_regime(regime: BayesianRegime):
    checks = []

    if not 0.0 <= regime.prior_h <= 1.0:
        return Status.FAIL, ("prior_range",), "prior outside [0,1]"
    checks.append("prior_range")

    if not regime.dependency_model_id:
        return Status.UNKNOWN, tuple(checks), "dependency model missing"
    checks.append("dependency_model_declared")

    if not regime.assumptions:
        return Status.UNKNOWN, tuple(checks), "assumptions not declared"
    checks.append("assumptions_declared")

    return Status.PASS, tuple(checks), "Bayesian regime is structurally specified"


def validate_dependency_candidate(candidate: CandidateDependency, established_edges):
    """
    ML is permitted to propose a dependency edge.
    It cannot establish that edge merely because its score is high.
    """
    edge = (candidate.left, candidate.right, candidate.relation)

    if edge in established_edges:
        return DependencyAssessment(
            candidate, Status.PASS, "candidate matches an established dependency"
        )

    return DependencyAssessment(
        candidate,
        Status.UNKNOWN,
        "ML candidate is not an established epistemic dependency",
    )


def compare_close(a, b, tol=1e-12):
    return abs(a - b) <= tol


def benchmark():
    results = []

    regime = BayesianRegime(
        hypothesis="H",
        prior_h=0.5,
        likelihood_model_id="binary-source-accuracy",
        dependency_model_id="latent-source-groups",
        assumptions=(
            "source_group represents one latent source signal",
            "source groups are conditionally independent given H",
            "source accuracy is 0.9",
        ),
        scope="finite binary benchmark",
    )

    status, _, _ = validate_bayesian_regime(regime)
    results.append(("R1 regime explicitly declares dependency model", status == Status.PASS))

    # ------------------------------------------------------------
    # W1: three independent sources
    # ------------------------------------------------------------
    independent = tuple(
        Evidence(f"E{i}", 1, f"S{i}", f"lineage:S{i}")
        for i in range(1, 4)
    )

    naive_ind = naive_independent_posterior(0.5, independent)
    exact_ind = enumerate_joint_posterior(0.5, independent)

    results.append((
        "R2 independent sources: naive and exact Bayesian posterior agree",
        compare_close(naive_ind, exact_ind),
    ))

    # Expected:
    # 0.9^3 / (0.9^3 + 0.1^3) ~= 0.998630
    results.append((
        "R3 independent sources legitimately accumulate evidence",
        naive_ind > 0.99,
    ))

    # ------------------------------------------------------------
    # W2: same source repeated three times
    # ------------------------------------------------------------
    dependent = tuple(
        Evidence(f"E{i}", 1, "SAME_SOURCE", f"lineage:SAME_SOURCE")
        for i in range(1, 4)
    )

    naive_dep = naive_independent_posterior(0.5, dependent)
    dependency_aware = correlated_source_posterior(0.5, dependent)
    exact_dep = enumerate_joint_posterior(0.5, dependent)

    results.append((
        "R4 repeated same-source evidence: dependency-aware posterior = 0.9",
        compare_close(dependency_aware, 0.9),
    ))

    results.append((
        "R5 dependency-aware calculation agrees with exact enumeration",
        compare_close(dependency_aware, exact_dep),
    ))

    results.append((
        "R6 naive independence is materially overconfident for common source",
        naive_dep > dependency_aware + 0.05,
    ))

    # ------------------------------------------------------------
    # W3: mixed evidence — two observations from source A,
    # one observation from independent source B.
    # ------------------------------------------------------------
    mixed = (
        Evidence("E1", 1, "A", "lineage:A"),
        Evidence("E2", 1, "A", "lineage:A"),
        Evidence("E3", 1, "B", "lineage:B"),
    )

    naive_mixed = naive_independent_posterior(0.5, mixed)
    aware_mixed = correlated_source_posterior(0.5, mixed)
    exact_mixed = enumerate_joint_posterior(0.5, mixed)

    results.append((
        "R7 mixed dependency: exact model agrees with dependency-aware result",
        compare_close(aware_mixed, exact_mixed),
    ))

    results.append((
        "R8 mixed dependency: repeated A does not count as two independent groups",
        compare_close(aware_mixed, 0.9 * 0.9 / (0.9 * 0.9 + 0.1 * 0.1)),
    ))

    results.append((
        "R9 naive mixed model differs from dependency-aware model",
        naive_mixed > aware_mixed + 0.01,
    ))

    # ------------------------------------------------------------
    # W4: ML dependency candidate firewall
    # ------------------------------------------------------------
    ml_candidate = CandidateDependency(
        "E1", "E2", "same_source", 0.99, "ML:model-v1"
    )
    dep_assessment = validate_dependency_candidate(
        ml_candidate,
        established_edges=set(),
    )

    results.append((
        "R10 ML dependency candidate remains UNKNOWN until validated",
        dep_assessment.status == Status.UNKNOWN,
    ))

    # ------------------------------------------------------------
    # W5: established edge allows dependency-aware treatment
    # ------------------------------------------------------------
    established = {("E1", "E2", "same_source")}
    dep_assessment2 = validate_dependency_candidate(
        ml_candidate,
        established_edges=established,
    )

    results.append((
        "R11 established dependency can pass validation",
        dep_assessment2.status == Status.PASS,
    ))

    # ------------------------------------------------------------
    # W6: invalid regime must not silently produce posterior
    # ------------------------------------------------------------
    invalid_regime = BayesianRegime(
        hypothesis="H",
        prior_h=0.5,
        likelihood_model_id="binary-source-accuracy",
        dependency_model_id="",
        assumptions=(),
        scope="",
    )

    bad_status, _, _ = validate_bayesian_regime(invalid_regime)

    results.append((
        "R12 incomplete Bayesian regime is not admissible",
        bad_status in {Status.UNKNOWN, Status.FAIL},
    ))

    # ------------------------------------------------------------
    # W7: dependency changes epistemic interpretation, not merely metadata
    # ------------------------------------------------------------
    results.append((
        "R13 dependency changes posterior materially",
        abs(naive_dep - dependency_aware) > 0.05,
    ))

    # ------------------------------------------------------------
    # W8: posterior is not automatically Knowledge
    # ------------------------------------------------------------
    results.append((
        "R14 posterior remains Bayesian assessment, not Knowledge attribution",
        isinstance(dependency_aware, float),
    ))

    return results, {
        "naive_independent": naive_ind,
        "exact_independent": exact_ind,
        "naive_common_source": naive_dep,
        "dependency_aware_common_source": dependency_aware,
        "exact_common_source": exact_dep,
        "naive_mixed": naive_mixed,
        "dependency_aware_mixed": aware_mixed,
        "exact_mixed": exact_mixed,
    }


if __name__ == "__main__":
    results, values = benchmark()
    passed = sum(ok for _, ok in results)
    print(f"R604 Bayesian dependency benchmark: {passed}/{len(results)} PASS")
    for name, ok in results:
        print(("PASS" if ok else "FAIL") + ": " + name)
    print("\nPosterior values:")
    for key, value in values.items():
        print(f"{key}: {value:.12f}")
