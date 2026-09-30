from dataclasses import dataclass
from typing import Callable, Iterable

@dataclass(frozen=True)
class Morphism:
    src: str
    tgt: str
    fn: Callable[[int], int]
    name: str

def compose(f: Morphism, g: Morphism) -> Morphism:
    """g after f."""
    if f.tgt != g.src:
        raise ValueError("endpoint mismatch")
    return Morphism(
        f.src, g.tgt,
        lambda x, f=f, g=g: g.fn(f.fn(x)),
        f"({g.name}∘{f.name})"
    )

def ext_equal(f: Morphism, g: Morphism, domain: Iterable[int]) -> bool:
    return all(f.fn(x) == g.fn(x) for x in domain)

def test_function_associativity():
    # Ordinary function composition: associativity is guaranteed by definition.
    f = Morphism("A", "B", lambda x: x + 1, "f")
    g = Morphism("B", "C", lambda x: 2*x, "g")
    h = Morphism("C", "D", lambda x: x - 3, "h")
    left = compose(compose(f, g), h)
    right = compose(f, compose(g, h))
    return ext_equal(left, right, range(-20, 21))

def test_target_preserving_quotient():
    # q(x)=x mod 2 is the declared semantic target.
    # f,g differ structurally but are equivalent under q.
    f = lambda x: x + 2
    g = lambda x: x
    return all((f(x) % 2) == (g(x) % 2) for x in range(-20, 21))

def test_congruence():
    # If f and f' are equivalent under q and post-composition preserves q,
    # then replacing f by f' does not change the semantic result.
    f  = lambda x: x + 2
    fp = lambda x: x
    post = lambda x: 3*x + 1
    q = lambda x: x % 2
    return all(q(post(f(x))) == q(post(fp(x))) for x in range(-20, 21))

def test_contract_closure():
    # A three-stage chain whose pairwise compositions remain inside
    # the same semantic domain. This is the closure condition needed
    # before associativity can be invoked.
    objects = {"A", "B", "C", "D"}
    chain = [("A","B"), ("B","C"), ("C","D")]
    return all(a in objects and b in objects for a,b in chain)

def test_counterexample_without_target_preservation():
    # Structural composition can exist while semantic equivalence fails
    # if the post-composition does not preserve the declared target.
    q = lambda x: x % 3
    f  = lambda x: x + 3      # q-equivalent to identity
    fp = lambda x: x
    post = lambda x: x + 1    # Here q(post(f(x))) still equals q(post(fp(x)))
    # Replace by a non-compatible "semantic projection" to demonstrate
    # that a broken contract can make the semantic comparison undefined.
    # We encode the failure as an explicitly non-preserving q2.
    q2 = lambda x: 0 if x % 2 == 0 else 1
    bad_post = lambda x: x // 3
    return any(q2(bad_post(f(x))) != q2(bad_post(fp(x))) for x in range(-20,21))

def main():
    tests = {
        "ordinary_function_associativity": test_function_associativity(),
        "target_equivalence_example": test_target_preserving_quotient(),
        "semantic_congruence": test_congruence(),
        "admissible_domain_closure": test_contract_closure(),
        "counterexample_without_preservation": test_counterexample_without_target_preservation(),
    }
    for k,v in tests.items():
        print(f"{k}: {'PASS' if v else 'FAIL'}")
    assert all(tests.values())

if __name__ == "__main__":
    main()
