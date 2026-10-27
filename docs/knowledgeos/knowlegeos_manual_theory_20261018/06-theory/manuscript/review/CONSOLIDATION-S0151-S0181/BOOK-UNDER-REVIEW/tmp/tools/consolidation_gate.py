"""Consolidation gate (Rulebook v1.4 §12): once LIMIT sources have been read in index order (full/skim)
since the last settled consolidation or milestone review, the next full/skim source is refused until
that consolidation is settled.

A consolidation or milestone review counts as settled when its review folder carries a SETTLEMENT.md
(a stage-1 file, SETTLEMENT-STAGE1.md, does not count). The folder name gives the last source it covers:
  CONSOLIDATION-Snnnn-Smmmm   covers up to Smmmm
  WHOLE-BOOK-Smmmm            covers up to Smmmm
Folders without a source range (e.g. CONSOLIDATION-20261019) are ignored."""
import re, pathlib

LIMIT = 25
FOLDER = re.compile(r'(?:CONSOLIDATION-S\d{4}-|WHOLE-BOOK-)(S\d{4})$')


def last_settled(review_dir, order):
    """(source id, index position) of the furthest source covered by a settled consolidation or
    milestone review, or (None, -1)."""
    best = (None, -1)
    for d in pathlib.Path(review_dir).iterdir():
        m = FOLDER.fullmatch(d.name)
        if m and d.is_dir() and (d / 'SETTLEMENT.md').is_file() and m.group(1) in order:
            if order[m.group(1)] > best[1]:
                best = (m.group(1), order[m.group(1)])
    return best


def read_since(rows, last_restart, boundary, order):
    """Distinct sources read full/skim after the last RESTART whose index position lies after the boundary."""
    return sorted({r[1] for r in rows[last_restart + 1:]
                   if len(r) > 5 and r[5] in ('full', 'skim') and r[1] in order and order[r[1]] > boundary},
                  key=order.get)


def check(rows, last_restart, review_dir, order, limit=LIMIT):
    """(due, message). due is True when a consolidation must be settled before the next full/skim source."""
    sid, pos = last_settled(review_dir, order)
    since = read_since(rows, last_restart, pos, order)
    msg = f'{len(since)} of {limit} read in order since the last settled consolidation ({sid or "none"})'
    return len(since) >= limit, msg
