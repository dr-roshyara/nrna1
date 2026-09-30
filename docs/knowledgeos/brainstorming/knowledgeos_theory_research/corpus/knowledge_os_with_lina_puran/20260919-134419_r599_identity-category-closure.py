
from dataclasses import dataclass
from typing import Callable, Iterable, Any

@dataclass(frozen=True)
class SemanticMap:
    name: str
    src: str
    tgt: str
    fn: Callable[[Any], Any]

def compose(f: SemanticMap, g: SemanticMap) -> SemanticMap:
    if f.tgt != g.src:
        raise ValueError("not composable")
    return SemanticMap(
        f"({g.name}∘{f.name})", f.src, g.tgt,
        lambda x, f=f, g=g: g.fn(f.fn(x))
    )

def identity(obj: str) -> SemanticMap:
    return SemanticMap(f"id_{obj}", obj, obj, lambda x: x)

def equivalent(f: SemanticMap, g: SemanticMap,
                domain: Iterable[Any],
                target: Callable[[Any], Any]) -> bool:
    return all(target(f.fn(x)) == target(g.fn(x)) for x in domain)

def equivalence_properties(maps, domain, target):
    # Reflexive
    reflexive = all(equivalent(f, f, domain, target) for f in maps)

    # Symmetric
    symmetric = all(
        (not equivalent(f, g, domain, target))
        or equivalent(g, f, domain, target)
        for f in maps for g in maps
    )

    # Transitive
    transitive = True
    for f in maps:
        for g in maps:
            if equivalent(f, g, domain, target):
                for h in maps:
                    if equivalent(g, h, domain, target):
                        transitive &= equivalent(f, h, domain, target)

    return reflexive, symmetric, transitive

def identity_laws():
    f = SemanticMap("f", "A", "B", lambda x: x + 1)
    id_a = identity("A")
    id_b = identity("B")

    left = compose(id_a, f)       # f after id_A
    right = compose(f, id_b)      # id_B after f

    return (
        equivalent(left, f, range(-20,21), lambda x: x),
        equivalent(right, f, range(-20,21), lambda x: x),
    )

def counterexample_bad_identity():
    f = SemanticMap("f", "A", "B", lambda x: x + 1)
    bad_id_b = SemanticMap("bad_id_B", "B", "B", lambda x: x + 1)
    result = compose(f, bad_id_b)
    return any(result.fn(x) != f.fn(x) for x in range(-20,21))

def associativity_under_identity():
    f = SemanticMap("f", "A", "B", lambda x: x + 1)
    g = SemanticMap("g", "B", "C", lambda x: 2*x)
    h = SemanticMap("h", "C", "D", lambda x: x - 3)
    left = compose(compose(f,g),h)
    right = compose(f,compose(g,h))
    return equivalent(left, right, range(-20,21), lambda x: x)

def main():
    f = SemanticMap("f", "A", "B", lambda x: x+1)
    f2 = SemanticMap("f2", "A", "B", lambda x: x+1)
    f3 = SemanticMap("f3", "A", "B", lambda x: x+3)
    maps = [f,f2,f3]
    domain = range(-20,21)
    target = lambda x: x % 2

    r,s,t = equivalence_properties(maps, domain, target)
    l,rid = identity_laws()

    tests = {
        "semantic_equivalence_reflexive": r,
        "semantic_equivalence_symmetric": s,
        "semantic_equivalence_transitive": t,
        "left_identity": l,
        "right_identity": rid,
        "bad_identity_counterexample": counterexample_bad_identity(),
        "associativity_with_identity": associativity_under_identity(),
    }
    for k,v in tests.items():
        print(f"{k}: {'PASS' if v else 'FAIL'}")
    assert all(tests.values())

if __name__ == "__main__":
    main()
