"""Minimal, dependency-free reader for the two flat EKP vocab files."""
import re
def load_ordered(path, section):
    out, cur = {}, None
    for line in open(path, encoding="utf-8"):
        if re.match(rf"^{section}:\s*$", line): cur = "IN"; continue
        if cur == "IN":
            m = re.match(r"^  ([a-z_]+):\s*$", line)
            if m: key = m.group(1); out[key] = {}; continue
            m = re.match(r"^    ([a-z_]+):\s*(.+)$", line)
            if m and out:
                k = list(out)[-1]
                v = m.group(2).strip()
                out[k][m.group(1)] = int(v) if v.isdigit() else v
    return out
