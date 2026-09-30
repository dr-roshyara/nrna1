#!/usr/bin/env python3
"""P2a (A10) — LABEL NORMALIZATION script half. Clusters every working_label (from
11-OBJECT-INDEX.jsonl and the PROPOSAL rows of 11-UNRESOLVED-CANDIDATES.jsonl) into
CANDIDATE GROUPS by several independent, explicitly-tagged signal types:

  EXACT-STRING-REUSE        the identical working_label string appears more than once
                            across index/unresolved (e.g. registered by one batch,
                            independently re-proposed with POSSIBLY by another)
  POSSIBLY-RELATION         an explicit relation_to_existing: POSSIBLY:<target> edge —
                            the strongest signal, an agent explicitly said so
  UNKNOWN-CANDIDATE-GROUP   an UNKNOWN-OBJECT-CANDIDATE row's candidate_of[] lists 2+
                            labels together — the agent explicitly considered them as
                            alternatives for one piece of evidence
  SHARED-NOTATION           two labels' notations[] share an identical string
  SHARED-ALIAS              two labels' aliases[] share an identical string
  STRING-SIMILARITY         working_label token-sets overlap heavily (Jaccard >= 0.5,
                            >=2 shared non-generic tokens) — inverted-index candidate
                            generation to stay sub-quadratic; generic tokens (used by
                            >30 labels) excluded from candidate generation but still
                            counted in the Jaccard once a pair is found via a rarer token
  CO-OCCURRENCE             two DIFFERENT labels appear together in the same
                            contribution's labels[] at least twice across the corpus

R5/R12 discipline: nothing is merged, nothing is renamed, a label may sit in more than
one group. This script only derives what a script can safely derive (R13); grouping
membership and "why" are mechanical facts here — the one agent pass (separate step)
reviews, spot-checks, and writes the narrative preface, per the protocol's own
"script, then one agent pass" instruction for P2a.

Never reads corpus source text — Phase 2 works from the ledger only (A10 header).
"""
import json
import os
import re
import subprocess
from collections import Counter, defaultdict

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")
FAM = os.path.join(CR, "20-FAMILIES")

GENERIC_TOKEN_DF_CUTOFF = 30    # tokens used by more than this many labels are excluded
                                # from STRING-SIMILARITY candidate generation (not from
                                # the Jaccard score itself once a pair is already found)
MIN_SHARED_TOKENS = 2
JACCARD_THRESHOLD = 0.5
MIN_COOCCURRENCE_COUNT = 2


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def tokenize(label):
    toks = re.split(r"[-_]", label.lower())
    return {t for t in toks if len(t) > 1 and not t.isdigit()}


def main():
    idx = load_jsonl(os.path.join(CR, "11-OBJECT-INDEX.jsonl"))
    unresolved = load_jsonl(os.path.join(CR, "11-UNRESOLVED-CANDIDATES.jsonl"))
    proposals = [r for r in unresolved if r["kind"] == "PROPOSAL"]
    unknown_candidates = [r for r in unresolved if r["kind"] == "UNKNOWN-OBJECT-CANDIDATE"]
    contributions = load_jsonl(os.path.join(CR, "03-CONTRIBUTIONS.jsonl"))

    # ---- build node set, merging metadata for identical working_label strings ----
    nodes = {}   # working_label -> {sources:[...], notations:set, aliases:set, scopes:set, notes:[...]}

    def get_node(label):
        if label not in nodes:
            nodes[label] = {
                "working_label": label,
                "sources": [],       # list of {origin: OBJECT-INDEX|PROPOSAL, batch, note, scope}
                "notations": set(),
                "aliases": set(),
                "scopes": set(),
            }
        return nodes[label]

    for r in idx:
        n = get_node(r["working_label"])
        n["sources"].append({"origin": "OBJECT-INDEX", "batch": r.get("first_seen_in_batch"),
                              "note": r.get("note", ""), "scope": r.get("scope")})
        n["notations"] |= set(r.get("notations") or [])
        n["aliases"] |= set(r.get("aliases") or [])
        n["scopes"].add(r.get("scope"))

    for r in proposals:
        n = get_node(r["working_label"])
        n["sources"].append({"origin": "PROPOSAL", "batch": r.get("batch_id"),
                              "note": r.get("note", ""), "scope": r.get("scope"),
                              "relation_to_existing": r.get("relation_to_existing")})
        n["notations"] |= set(r.get("notations") or [])
        n["aliases"] |= set(r.get("aliases") or [])
        n["scopes"].add(r.get("scope"))

    print(f"Total distinct working_label nodes: {len(nodes)}")

    groups = []
    gid_counter = [0]

    def new_group(kind, members, why, extra=None):
        gid_counter[0] += 1
        g = {"group_id": f"G{gid_counter[0]:04d}", "kind": kind,
             "members": sorted(set(members)), "why_grouped": why}
        if extra:
            g["evidence"] = extra
        groups.append(g)

    # ---- 1. EXACT-STRING-REUSE ----
    label_occurrence_count = Counter()
    for r in idx:
        label_occurrence_count[r["working_label"]] += 1
    for r in proposals:
        label_occurrence_count[r["working_label"]] += 1
    for label, count in label_occurrence_count.items():
        if count > 1:
            n = nodes[label]
            new_group("EXACT-STRING-REUSE", [label],
                       f"the identical working_label '{label}' was independently "
                       f"registered/proposed {count} times across different batches "
                       f"({sorted(set(s['batch'] for s in n['sources']))})",
                       extra={"sources": n["sources"]})

    # ---- 2. POSSIBLY-RELATION ----
    # relation_to_existing is usually "POSSIBLY:<one-target>" but some agents wrote
    # multiple targets separated by ',' ';' or '|', sometimes repeating the "POSSIBLY:"
    # prefix on later parts (e.g. "POSSIBLY:a;POSSIBLY:b", "POSSIBLY:a,b", or
    # "gap-formalization|POSSIBLY:c") — split defensively and register one edge per
    # resolved target, still flagging any part that isn't a known label.
    not_found_targets = []
    malformed_relations = []
    for r in proposals:
        rel = r.get("relation_to_existing", "") or ""
        if "POSSIBLY:" not in rel:
            continue
        src = r["working_label"]
        raw_targets = re.split(r"[,;|]", rel)
        parsed_targets = []
        for part in raw_targets:
            part = part.strip()
            if part.startswith("POSSIBLY:"):
                part = part[len("POSSIBLY:"):]
            part = part.strip()
            if part:
                parsed_targets.append(part)
        if len(raw_targets) > 1 or rel.count("POSSIBLY:") > 1:
            malformed_relations.append({"from": src, "raw": rel, "parsed_targets": parsed_targets,
                                         "batch": r.get("batch_id")})
        for target in parsed_targets:
            if target not in nodes and target != src:
                not_found_targets.append({"from": src, "target": target, "batch": r.get("batch_id"),
                                           "raw_relation": rel})
            new_group("POSSIBLY-RELATION", [src, target],
                       f"explicit agent-stated uncertainty: '{src}' POSSIBLY relates to "
                       f"'{target}' (batch {r.get('batch_id')}). Note: {r.get('note', '')}",
                       extra={"source_label": src, "target_label": target,
                              "target_exists_in_index_or_proposals": target in nodes,
                              "raw_relation": rel})

    # ---- 3. UNKNOWN-CANDIDATE-GROUP (2+ candidate_of) ----
    single_candidate_flags = defaultdict(list)   # label -> [ {source_id, why_uncertain} ]
    for r in unknown_candidates:
        cands = [c for c in (r.get("candidate_of") or []) if c]
        if len(cands) >= 2:
            new_group("UNKNOWN-CANDIDATE-GROUP", cands,
                       f"an UNKNOWN-OBJECT-CANDIDATE row (batch {r.get('batch_id')}, "
                       f"source {r.get('source_id')}) named these as alternative "
                       f"candidates for one piece of evidence. why_uncertain: "
                       f"{r.get('why_uncertain', '')}",
                       extra={"source_id": r.get("source_id"), "batch": r.get("batch_id"),
                              "anchor": r.get("anchor")})
        elif len(cands) == 1:
            single_candidate_flags[cands[0]].append(
                {"source_id": r.get("source_id"), "batch": r.get("batch_id"),
                 "why_uncertain": r.get("why_uncertain", "")})

    # ---- 4. SHARED-NOTATION ----
    notation_users = defaultdict(set)
    for label, n in nodes.items():
        for notation in n["notations"]:
            notation_users[notation].add(label)
    for notation, labels in notation_users.items():
        if len(labels) >= 2:
            new_group("SHARED-NOTATION", labels,
                       f"labels share the notation '{notation}'",
                       extra={"notation": notation})

    # ---- 5. SHARED-ALIAS ----
    alias_users = defaultdict(set)
    for label, n in nodes.items():
        for alias in n["aliases"]:
            alias_users[alias].add(label)
    for alias, labels in alias_users.items():
        if len(labels) >= 2:
            new_group("SHARED-ALIAS", labels,
                       f"labels share the alias '{alias}'",
                       extra={"alias": alias})

    # ---- 6. STRING-SIMILARITY (inverted index for candidate generation) ----
    token_sets = {label: tokenize(label) for label in nodes}
    token_df = Counter()
    for toks in token_sets.values():
        for t in toks:
            token_df[t] += 1
    rare_token_index = defaultdict(set)
    for label, toks in token_sets.items():
        for t in toks:
            if token_df[t] <= GENERIC_TOKEN_DF_CUTOFF:
                rare_token_index[t].add(label)

    seen_pairs = set()
    sim_group_members = defaultdict(set)  # representative token -> connected members (loose grouping key)
    for t, labels in rare_token_index.items():
        labels = sorted(labels)
        for i in range(len(labels)):
            for j in range(i + 1, len(labels)):
                a, b = labels[i], labels[j]
                if (a, b) in seen_pairs:
                    continue
                seen_pairs.add((a, b))
                shared = token_sets[a] & token_sets[b]
                if len(shared) < MIN_SHARED_TOKENS:
                    continue
                union = token_sets[a] | token_sets[b]
                jaccard = len(shared) / len(union) if union else 0
                if jaccard >= JACCARD_THRESHOLD:
                    new_group("STRING-SIMILARITY", [a, b],
                               f"working_label token overlap Jaccard={jaccard:.2f} "
                               f"(shared tokens: {sorted(shared)})",
                               extra={"jaccard": round(jaccard, 3), "shared_tokens": sorted(shared)})

    # ---- 7. CO-OCCURRENCE (same contribution's labels[]) ----
    cooccur_count = Counter()
    for c in contributions:
        labs = [l for l in (c.get("labels") or []) if l != "UNKNOWN-OBJECT-CANDIDATE"]
        labs = sorted(set(labs))
        for i in range(len(labs)):
            for j in range(i + 1, len(labs)):
                cooccur_count[(labs[i], labs[j])] += 1
    for (a, b), count in cooccur_count.items():
        if count >= MIN_COOCCURRENCE_COUNT:
            new_group("CO-OCCURRENCE", [a, b],
                       f"labels co-occur in the same contribution's labels[] {count} "
                       f"separate times across the corpus",
                       extra={"count": count})

    print(f"Groups formed: {len(groups)}")
    kind_counts = Counter(g["kind"] for g in groups)
    for k, v in kind_counts.most_common():
        print(f"  {k}: {v}")
    print(f"POSSIBLY-relation targets not found among known labels: {len(not_found_targets)}")
    print(f"Malformed/multi-target relation_to_existing strings (parsed defensively): {len(malformed_relations)}")
    print(f"Labels flagged by exactly one UNKNOWN-OBJECT-CANDIDATE row (context only, no group): "
          f"{len(single_candidate_flags)}")

    # ---- membership index: label -> [group_id, ...] ----
    membership = defaultdict(list)
    for g in groups:
        for m in g["members"]:
            membership[m].append(g["group_id"])

    ungrouped = [label for label in nodes if not membership.get(label)]
    print(f"Labels in zero groups (will become singleton families directly in P2b): {len(ungrouped)}")

    derived = {
        "generated_by": "scripts/normalize_labels.py",
        "node_count": len(nodes),
        "group_count": len(groups),
        "group_kind_counts": dict(kind_counts),
        "nodes": {
            label: {
                "working_label": label,
                "sources": n["sources"],
                "notations": sorted(n["notations"]),
                "aliases": sorted(n["aliases"]),
                "scopes": sorted(s for s in n["scopes"] if s),
                "group_ids": membership.get(label, []),
                "single_candidate_flags": single_candidate_flags.get(label, []),
            }
            for label, n in nodes.items()
        },
        "groups": groups,
        "possibly_relation_targets_not_found": not_found_targets,
        "malformed_multi_target_relations": malformed_relations,
        "ungrouped_labels": sorted(ungrouped),
    }

    out_path = os.path.join(FAM, "_derived.json")
    with open(out_path, "w", encoding="utf-8") as fh:
        json.dump(derived, fh, ensure_ascii=False, indent=1)
    print(f"OK: {out_path} written")

    hub_labels = sorted(
        ((label, n["group_ids"]) for label, n in derived["nodes"].items() if n["group_ids"]),
        key=lambda kv: -len(kv[1])
    )[:40]

    write_markdown_draft(derived, kind_counts, not_found_targets, malformed_relations,
                          ungrouped, hub_labels)


KIND_ORDER = ["POSSIBLY-RELATION", "UNKNOWN-CANDIDATE-GROUP", "EXACT-STRING-REUSE",
              "SHARED-ALIAS", "SHARED-NOTATION", "STRING-SIMILARITY", "CO-OCCURRENCE"]
KIND_RELIABILITY = {
    "POSSIBLY-RELATION": "STRONGEST — an extraction agent explicitly stated this uncertainty at capture time",
    "UNKNOWN-CANDIDATE-GROUP": "STRONG — an agent explicitly listed these as alternative candidates for one piece of evidence",
    "EXACT-STRING-REUSE": "STRONG — the identical label string was independently coined by different batches",
    "SHARED-ALIAS": "MODERATE — same alias string recorded by different labels",
    "SHARED-NOTATION": "MODERATE, NOISY FOR SHORT/GENERIC CODES — a shared symbol like 'C-1' or 'K_t' may be a real citation or pure corpus-wide numbering-scheme collision (see 09-ORCHESTRATOR-FLAGS.md's many logged numbering-collision findings); longer/distinctive notations are more trustworthy",
    "STRING-SIMILARITY": "WEAK-TO-MODERATE — lexical overlap only, no semantic confirmation; higher jaccard scores are more trustworthy",
    "CO-OCCURRENCE": "WEAK — two objects were both relevant to the same piece of evidence; does not imply identity or even close relation, only thematic proximity",
}


def write_markdown_draft(derived, kind_counts, not_found_targets, malformed_relations, ungrouped, hub_labels):
    lines = []
    lines.append("# 20-FAMILIES/_LABEL-NORMALIZATION — P2a candidate groups (DRAFT)\n")
    lines.append(
        "**Mechanically generated by `scripts/normalize_labels.py`.** Per protocol R5/R12: "
        "**grouping here is NEVER an identity claim.** Nothing is merged, nothing is renamed, "
        "and a label may sit in more than one group. P3 (reconciliation) is where "
        "relationship + basis are actually decided, pair by pair, against the full evidence "
        "in `03-CONTRIBUTIONS.jsonl`.\n"
    )
    lines.append(
        f"**This is the script half of P2a's \"script, then one agent pass.\"** An agent "
        "review pass follows (see the preface this file will carry once reviewed) to spot-"
        "check, flag noise, and surface cross-signal patterns — it will edit this file "
        "in place, not replace it.\n"
    )
    lines.append(f"- Distinct working_label nodes: {derived['node_count']}")
    lines.append(f"- Candidate groups formed: {derived['group_count']}")
    lines.append(f"- Labels in zero groups (become singleton families in P2b): {len(ungrouped)}")
    lines.append(f"- POSSIBLY-relation targets that resolved to no known label (orphan references, "
                  f"listed in full below): {len(not_found_targets)}")
    lines.append(f"- `relation_to_existing` strings carrying more than one target, parsed "
                  f"defensively (listed in full below): {len(malformed_relations)}\n")

    lines.append("## Most cross-referenced labels (top 40 by group membership count)\n")
    lines.append(
        "Mechanically ranked by how many candidate groups (of any signal type) each label "
        "belongs to. High membership does not itself mean anything is the same object — it "
        "means this label has accumulated the most cross-cutting evidence and is highest "
        "priority for the agent review pass below, and later for P3. See `_derived.json` → "
        "`nodes.<label>.group_ids` for the exact group list per label.\n"
    )
    lines.append("| working_label | # groups |")
    lines.append("|---|---|")
    for label, gids in hub_labels:
        lines.append(f"| `{label}` | {len(gids)} |")
    lines.append("")

    lines.append("## Group counts by signal type (strongest first)\n")
    lines.append("| signal type | count | reliability note |")
    lines.append("|---|---|---|")
    for kind in KIND_ORDER:
        lines.append(f"| {kind} | {kind_counts.get(kind, 0)} | {KIND_RELIABILITY[kind]} |")
    lines.append("")

    groups_by_kind = defaultdict(list)
    for g in derived["groups"]:
        groups_by_kind[g["kind"]].append(g)

    for kind in KIND_ORDER:
        glist = groups_by_kind.get(kind, [])
        if not glist:
            continue
        lines.append(f"## {kind} ({len(glist)} groups)\n")
        lines.append(f"_{KIND_RELIABILITY[kind]}_\n")
        for g in glist:
            members_str = " · ".join(f"`{m}`" for m in g["members"])
            lines.append(f"- **{g['group_id']}** [{members_str}] — {g['why_grouped']}")
        lines.append("")

    lines.append("## Orphan POSSIBLY-relation targets (resolved to no known label)\n")
    lines.append(
        "These are genuine findings, not errors to silently fix: an extraction agent named a "
        "target label that was never itself registered under that exact string anywhere in "
        "`11-OBJECT-INDEX.jsonl` or `11-UNRESOLVED-CANDIDATES.jsonl`. Either the concept was "
        "never given its own working_label, or it exists under a different spelling that "
        "STRING-SIMILARITY above did not catch. Left for P3/human review — never guessed here.\n"
    )
    for x in not_found_targets:
        lines.append(f"- `{x['from']}` (batch {x['batch']}) → target `{x['target']}` "
                      f"(raw: `{x['raw_relation']}`)")
    lines.append("")

    lines.append("## Multi-target `relation_to_existing` strings (parsed defensively)\n")
    lines.append(
        "Some agents packed more than one candidate target into one `relation_to_existing` "
        "value (comma/semicolon/pipe-separated, sometimes repeating the `POSSIBLY:` prefix). "
        "Each was split into its own POSSIBLY-RELATION edge above; the raw strings are kept "
        "here for auditability.\n"
    )
    for x in malformed_relations:
        lines.append(f"- `{x['from']}` (batch {x['batch']}): raw=`{x['raw']}` → "
                      f"parsed={x['parsed_targets']}")
    lines.append("")

    lines.append(f"## Ungrouped labels ({len(ungrouped)})\n")
    lines.append(
        "No signal connected these to any other label. They proceed directly to P2b as "
        f"singleton families. Full list in `_derived.json` (`ungrouped_labels`); not "
        "reproduced here for length.\n"
    )

    out_path = os.path.join(FAM, "_LABEL-NORMALIZATION.md")
    with open(out_path, "w", encoding="utf-8") as fh:
        fh.write("\n".join(lines) + "\n")
    print(f"OK: {out_path} written (draft, pending agent review pass)")


if __name__ == "__main__":
    main()
