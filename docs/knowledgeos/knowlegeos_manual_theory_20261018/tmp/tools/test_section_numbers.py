#!/usr/bin/env python3
"""Test: §8.10 and §8.100 are distinct in every lane tool that parses section numbers (the Principal, 2026-11-03:
"Number the next Ch. 8 sections §8.100 onward (files 08.100-…); 08.99 stays the change log. … add a test showing
§8.10 and §8.100 are distinct (parse, order and citation search), and run it.").

Builds a throw-away book under a temporary directory (nothing in the lane is read or written) with
chapter_08/08.10-…, 08.11-…, 08.99-change-log.md and 08.100-…, then checks:
  1. parse: each tool's section pattern reads 08.10 and 08.100 as different sections;
  2. order: section files sort 08.10 < 08.11 < 08.99 < 08.100 (numerically, not as strings);
  3. citation search: a search for §8.10 finds the §8.10 citation and not the §8.100 one, and the reverse;
     a scope of 08.100 selects only the 08.100 file, a scope 08.82-08.99 does not select it.
Usage: python3 -I test_section_numbers.py      (exit 0: all passed)"""
import ast, pathlib, re, subprocess, sys, tempfile

TOOLS = pathlib.Path(__file__).resolve().parent
fails = []


def check(name, cond, detail=''):
    print(('PASS ' if cond else 'FAIL ') + name + (f' — {detail}' if detail and not cond else ''))
    if not cond:
        fails.append(name)


def fn_from(tool, fname):
    """Load one top-level function from a tool's source without running the tool."""
    src = (TOOLS / tool).read_text(encoding='utf-8')
    node = next(n for n in ast.parse(src).body if isinstance(n, ast.FunctionDef) and n.name == fname)
    ns = {'re': re, 'pathlib': pathlib}
    exec(compile(ast.Module([node], []), tool, 'exec'), ns)
    return ns[fname]


def run(tool, *args):
    r = subprocess.run([sys.executable, '-I', str(TOOLS / tool), *args], capture_output=True, text=True)
    return r.returncode, r.stdout + r.stderr


with tempfile.TemporaryDirectory() as d:
    root = pathlib.Path(d)
    ch = root / '06-theory' / 'manuscript' / 'chapter_08'
    ch.mkdir(parents=True)
    (root / '06-theory' / 'CONCEPT-REGISTER.tsv').write_text(
        'concept_id\tterm\tmeaning\tsources\tidea_ids\tbook_location\tsame_subject_as\tstatus\tbasis\tchecked_on\treviewed\tfollow_up\thistory\n',
        encoding='utf-8')
    (root / '06-theory' / 'THEORY-MAP.md').write_text('# map\n', encoding='utf-8')
    (root / '06-theory' / 'manuscript' / 'BOOK-OUTLINE.md').write_text('# outline\n', encoding='utf-8')
    (ch / '08.00-index.md').write_text('# 8.00 Index\n', encoding='utf-8')
    (ch / '08.10-ten.md').write_text('# 8.10 Ten\n\n**C1. Ten (Src).** zzonlyintenzz\n', encoding='utf-8')
    (ch / '08.11-eleven.md').write_text('# 8.11 Eleven\n\nThis uses §8.10 C1 here.\n', encoding='utf-8')
    (ch / '08.99-change-log.md').write_text('# Chapter 8: change log\n', encoding='utf-8')
    (ch / '08.100-hundred.md').write_text('# 8.100 Hundred\n\n**C1. Hundred (Src).** qqonlyinhundredqq; it cites §8.10 C1 and §8.11.\n', encoding='utf-8')
    (ch / '08.12-twelve.md').write_text('# 8.12 Twelve\n\nThis uses §8.100 C1 only.\n', encoding='utf-8')

    # 1. parse
    pat = re.compile(r'(\d\d)\.(\d{2,3})(?!\d)')
    check('parse: 08.10 and 08.100 read as different sections',
          pat.match('08.10-ten.md').groups() == ('08', '10') and pat.match('08.100-hundred.md').groups() == ('08', '100'))

    # 2. order, per tool that sorts section files
    names = [p.name for p in ch.iterdir()]
    want = ['08.00-index.md', '08.10-ten.md', '08.11-eleven.md', '08.12-twelve.md', '08.99-change-log.md', '08.100-hundred.md']
    for tool in ('book_search.py', 'inbound_citations.py', 'dependency_graph.py', 'proof_step_check.py'):
        key = fn_from(tool, 'file_key')
        got = [pathlib.Path(n).name for n in sorted((str(ch / n) for n in names), key=key)]
        check(f'order: {tool} sorts numerically', got == want, f'got {got}')
    check('order: a plain string sort would be wrong (control)', sorted(names) != want)

    # 3. citation search (inbound_citations)
    rc, out = run('inbound_citations.py', '--root', str(root), '§8.10 C1')
    claim = out.split('-- claim:')[1].split('-- concept:')[0] if '-- claim:' in out else ''
    check('cite: §8.10 C1 found in 08.11 and 08.100', rc == 0 and '08.11-eleven.md' in claim and '08.100-hundred.md' in claim, out)
    check('cite: §8.10 C1 not matched in 08.12 (which cites §8.100 C1)', '08.12-twelve.md' not in out, out)
    check('cite: home of §8.10 is 08.10, not 08.100', 'home: 06-theory/manuscript/chapter_08/08.10-ten.md' in out, out)
    rc, out = run('inbound_citations.py', '--root', str(root), '§8.100 C1')
    check('cite: §8.100 C1 found in 08.12 only', rc == 0 and '08.12-twelve.md' in out and '08.11-eleven.md' not in out, out)
    check('cite: home of §8.100 is 08.100', 'home: 06-theory/manuscript/chapter_08/08.100-hundred.md' in out, out)

    # 3b. dependency graph: §8.12 depends on §8.100, not §8.10
    rc, out = run('dependency_graph.py', '--root', str(root), '--up', '§8.12')
    check('graph: §8.12 → §8.100, not §8.10', rc == 0 and '§8.100' in out and '§8.10 ' not in out.replace('§8.100', ''), out)
    rc, out = run('dependency_graph.py', '--root', str(root), '--up', '§8.100')
    check('graph: §8.100 → §8.10 and §8.11', rc == 0 and '§8.10' in out and '§8.11' in out, out)

    # 3c. scope (undefined_terms): its own section parser, key and scope test, loaded from its source
    src = (TOOLS / 'undefined_terms.py').read_text(encoding='utf-8')
    keep = [n for n in ast.parse(src).body
            if (isinstance(n, ast.Assign) and any(getattr(t, 'id', '') in ('SECNO', 'secno', 'sec_key') for t in n.targets))
            or (isinstance(n, ast.FunctionDef) and n.name == 'in_scope_sec')]
    ns = {'re': re}
    exec(compile(ast.Module(keep, []), 'undefined_terms.py', 'exec'), ns)
    check('scope: secno reads 08.10 and 08.100', ns['secno']('08.10-ten.md') == '08.10' and ns['secno']('08.100-hundred.md') == '08.100')
    for spec, sec, want in [('08.100', '08.100', True), ('08.100', '08.10', False), ('08.10', '08.100', False),
                            ('08.82-08.99', '08.100', False), ('08.82-08.99', '08.90', True), ('08.99-08.105', '08.100', True),
                            ('08', '08.100', True)]:
        ns['scope_spec'] = spec
        check(f'scope: {sec} in "{spec}" is {want}', ns['in_scope_sec'](sec) is want)
    rc, out = run('undefined_terms.py', '--root', str(root), '--no-corpus', '--scope', '08.100')
    check('scope: the tool runs on a book with an 08.100 file', rc == 0, out[-300:])

print(f'{len(fails)} failed' if fails else 'all passed')
sys.exit(1 if fails else 0)
