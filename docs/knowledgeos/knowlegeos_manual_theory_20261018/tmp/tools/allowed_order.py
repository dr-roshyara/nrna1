"""Allowed-list order (the Principal, 2026-10-25, after the S0155 incident): a source not on the corpus allowed list is
never started and never "the next source in order"; reading order skips it. The index is not edited.

  load_allowed(path)            -> set of repository-relative paths on CORPUS-ALLOWED.tsv (parsed, exact match)
  next_allowed(index, pos, ok)  -> (source id of the first allowed entry after position pos, [skipped source ids])"""
import csv


def load_allowed(tsv_path):
    with open(tsv_path, encoding='utf-8') as fh:
        return {r['path'] for r in csv.DictReader(fh, delimiter='\t') if r.get('path')}


def next_allowed(index, pos, allowed):
    skipped = []
    for r in index[pos + 1:]:
        if r['path'] in allowed:
            return r['source_id'], skipped
        skipped.append(r['source_id'])
    return None, skipped
