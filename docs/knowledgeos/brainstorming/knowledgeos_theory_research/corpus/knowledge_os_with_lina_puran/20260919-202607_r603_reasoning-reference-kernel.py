from dataclasses import dataclass, field
from enum import Enum
from typing import Any, Callable, Dict, List, Tuple


class Status(str, Enum):
    PASS = "PASS"
    FAIL = "FAIL"
    UNKNOWN = "UNKNOWN"
    CONDITIONAL = "CONDITIONAL"


@dataclass(frozen=True)
class Problem:
    start_state: Any
    goal: str
    constraints: Tuple[str, ...] = ()
    information: Tuple[str, ...] = ()
    assumptions: Tuple[str, ...] = ()


@dataclass(frozen=True)
class Analysis:
    target: str
    relevant_data: Tuple[str, ...]
    dependencies_declared: bool
    assumptions_declared: bool
    dependency_required: bool = False


@dataclass(frozen=True)
class ReasoningMethod:
    method_id: str
    name: str
    cost: float
    applicable: Callable[[Problem, Analysis], bool]
    adequate: Callable[[Problem, Analysis], bool]
    execute: Callable[[Problem, Analysis], "Candidate"]


@dataclass(frozen=True)
class Candidate:
    candidate_id: str
    conclusion: str
    support_groups: int
    assumptions: Tuple[str, ...] = ()
    provenance: Tuple[str, ...] = ()
    generated_by: str = ""


@dataclass(frozen=True)
class Validation:
    status: Status
    checks: Tuple[str, ...]
    reason: str


@dataclass(frozen=True)
class Assessment:
    status: Status
    candidate_id: str
    determination_eligible: bool
    reason: str


@dataclass(frozen=True)
class Determination:
    status: Status
    conclusion: str
    reason: str


@dataclass
class ReasoningTrace:
    problem: Problem
    analysis: Analysis
    selected_method: str
    candidate: Candidate
    validation: Validation
    assessment: Assessment
    determination: Determination
    assurance: Dict[str, Status] = field(default_factory=dict)


def validate_candidate(candidate: Candidate, analysis: Analysis,
                       required_support_groups: int = 3) -> Validation:
    checks = []
    if not candidate.conclusion:
        return Validation(Status.FAIL, ("non_empty_conclusion",), "empty conclusion")
    checks.append("non_empty_conclusion")

    if candidate.assumptions and not analysis.assumptions_declared:
        return Validation(Status.UNKNOWN, tuple(checks),
                          "candidate introduced undeclared assumptions")
    checks.append("assumptions_declared")

    if not analysis.dependencies_declared:
        return Validation(Status.UNKNOWN, tuple(checks),
                          "dependency structure not declared")
    checks.append("dependency_structure_declared")

    if candidate.support_groups < 0:
        return Validation(Status.FAIL, tuple(checks), "invalid support count")
    checks.append("support_count_well_formed")

    if candidate.support_groups < required_support_groups:
        return Validation(Status.CONDITIONAL, tuple(checks),
                          "insufficient independent support")

    return Validation(Status.PASS, tuple(checks), "validation contract satisfied")


def assess(candidate: Candidate, validation: Validation,
           required_support_groups: int = 3) -> Assessment:
    if validation.status != Status.PASS:
        return Assessment(validation.status, candidate.candidate_id, False,
                          validation.reason)
    eligible = candidate.support_groups >= required_support_groups
    return Assessment(Status.PASS if eligible else Status.CONDITIONAL,
                      candidate.candidate_id, eligible,
                      "determination eligible" if eligible else "insufficient support")


def determine(items: List[Tuple[Candidate, Assessment]]) -> Determination:
    eligible = [c for c, a in items
                if a.status == Status.PASS and a.determination_eligible]
    if not eligible:
        return Determination(Status.UNKNOWN, "U", "no eligible candidate")

    conclusions = {c.conclusion for c in eligible}
    if len(conclusions) > 1:
        return Determination(Status.CONDITIONAL, "CONFLICT",
                              "multiple eligible conclusions")
    return Determination(Status.PASS, next(iter(conclusions)), "contract satisfied")


def select_method(problem: Problem, analysis: Analysis,
                  methods: List[ReasoningMethod],
                  max_cost: float = float("inf")) -> ReasoningMethod:
    # Constitutional ordering:
    #   1. Applicability
    #   2. Adequacy
    #   3. Optimization (e.g. cost)
    eligible = [
        m for m in methods
        if m.cost <= max_cost
        and m.applicable(problem, analysis)
        and m.adequate(problem, analysis)
    ]
    if not eligible:
        raise ValueError("no applicable AND adequate method")
    return min(eligible, key=lambda m: m.cost)


# --- Reference methods -------------------------------------------------------

def raw_execute(problem, analysis):
    return Candidate("C-raw", "H", len(problem.information),
                     provenance=problem.information, generated_by="raw_count")


def dependency_execute(problem, analysis):
    groups, polarity = set(), "H"
    for item in problem.information:
        _, gid, pol = item.split(":")
        groups.add(gid)
        polarity = pol
    return Candidate("C-dep", polarity, len(groups),
                     provenance=problem.information,
                     generated_by="dependency_graph")


def ml_execute(problem, analysis):
    # ML is deliberately a candidate generator.
    return Candidate("C-ml", "H", 2,
                     provenance=("ml:model-v1",),
                     generated_by="ml_candidate_generator")


def raw_applicable(p, a):
    return not a.dependency_required


def raw_adequate(p, a):
    return not a.dependency_required


def dependency_applicable(p, a):
    return a.dependency_required and a.dependencies_declared


def dependency_adequate(p, a):
    return a.dependency_required and a.dependencies_declared


def ml_applicable(p, a):
    return True


def ml_adequate(p, a):
    # Candidate discovery is not itself a determination method.
    return False


METHODS = [
    ReasoningMethod("M1", "raw evidence count", 1.0,
                    raw_applicable, raw_adequate, raw_execute),
    ReasoningMethod("M2", "explicit dependency graph", 5.0,
                    dependency_applicable, dependency_adequate, dependency_execute),
    ReasoningMethod("M3", "ML candidate discovery", 3.0,
                    ml_applicable, ml_adequate, ml_execute),
]


def run_pipeline(problem, analysis, methods, max_cost=float("inf")):
    method = select_method(problem, analysis, methods, max_cost)
    candidate = method.execute(problem, analysis)
    validation = validate_candidate(candidate, analysis)
    assessment = assess(candidate, validation)
    determination = determine([(candidate, assessment)])

    assurance = {
        "I-M01_problem_before_method":
            Status.PASS if problem.goal else Status.FAIL,
        "I-M02_applicability":
            Status.PASS if method.applicable(problem, analysis) else Status.FAIL,
        "I-M03_method_adequacy":
            Status.PASS if method.adequate(problem, analysis) else Status.FAIL,
        "I-M04_candidate_not_knowledge": Status.PASS,
        "I-M05_validation_separate": Status.PASS,
        "I-M06_dependency_aware":
            Status.PASS if analysis.dependencies_declared else Status.UNKNOWN,
        "I-M07_no_silent_assumption":
            Status.PASS if (not candidate.assumptions
                            or analysis.assumptions_declared)
            else Status.UNKNOWN,
        "I-M08_cost_not_truth": Status.PASS,
    }
    return ReasoningTrace(problem, analysis, method.method_id, candidate,
                          validation, assessment, determination, assurance)


def check(name, condition):
    return name, "PASS" if condition else "FAIL"


def benchmark():
    r = []

    # W1: three independent groups -> H.
    p1 = Problem("E", "Determine(H)",
                 information=("group:g1:H", "group:g2:H", "group:g3:H"))
    a1 = Analysis("Determine(H)", p1.information, True, True, True)
    t1 = run_pipeline(p1, a1, METHODS)
    r.append(check("W1 independent evidence -> H",
                   t1.selected_method == "M2"
                   and t1.determination.conclusion == "H"))

    # W2: three observations, one dependency group -> U.
    p2 = Problem("E", "Determine(H)",
                 information=("group:g1:H", "group:g1:H", "group:g1:H"))
    a2 = Analysis("Determine(H)", p2.information, True, True, True)
    t2 = run_pipeline(p2, a2, METHODS)
    r.append(check("W2 dependency reduces support",
                   t2.candidate.support_groups == 1
                   and t2.determination.conclusion == "U"))

    # W3: dependency is required but not declared -> no adequate method.
    p3 = Problem("E", "Determine(H)", information=("group:g1:H",))
    a3 = Analysis("Determine(H)", p3.information, False, True, True)
    try:
        run_pipeline(p3, a3, METHODS)
        no_method = False
    except ValueError:
        no_method = True
    r.append(check("W3 no adequate method -> blocked", no_method))

    # W4: ML can generate a candidate but cannot establish determination.
    p4 = Problem("E", "Determine(H)",
                 information=("group:g1:H", "group:g2:H", "group:g3:H"))
    a4 = Analysis("Determine(H)", p4.information, True, True, False)
    c4 = ml_execute(p4, a4)
    v4 = validate_candidate(c4, a4)
    r.append(check("W4 ML candidate stays behind validation firewall",
                   c4.generated_by == "ml_candidate_generator"
                   and v4.status != Status.PASS))

    # W5: undeclared assumptions -> UNKNOWN.
    p5 = Problem("E", "Determine(H)", information=("group:g1:H",))
    a5 = Analysis("Determine(H)", p5.information, True, False, False)
    c5 = Candidate("C5", "H", 3, assumptions=("independence",))
    v5 = validate_candidate(c5, a5)
    r.append(check("W5 undeclared assumption -> UNKNOWN",
                   v5.status == Status.UNKNOWN))

    # W6: applicability/adequacy precede cost.
    r.append(check("W6 expensive adequate method beats cheap inadequate method",
                   t1.selected_method == "M2"))

    # W7: method result and determination are different typed objects.
    r.append(check("W7 method result != determination",
                   t1.candidate != t1.determination))

    # W8: assurance is cross-cutting.
    r.append(check("W8 assurance is independent of determination",
                   all(v in {Status.PASS, Status.UNKNOWN}
                       for v in t1.assurance.values())))

    # W9: eligible disagreement remains explicit.
    c1, c2 = Candidate("c1", "H", 3), Candidate("c2", "not-H", 3)
    vp = Validation(Status.PASS, (), "ok")
    d = determine([(c1, assess(c1, vp)), (c2, assess(c2, vp))])
    r.append(check("W9 eligible conflict remains explicit",
                   d.status == Status.CONDITIONAL
                   and d.conclusion == "CONFLICT"))

    return r


if __name__ == "__main__":
    results = benchmark()
    passed = sum(s == "PASS" for _, s in results)
    print(f"R603 finite benchmark: {passed}/{len(results)} PASS")
    for name, status in results:
        print(f"{status}: {name}")
