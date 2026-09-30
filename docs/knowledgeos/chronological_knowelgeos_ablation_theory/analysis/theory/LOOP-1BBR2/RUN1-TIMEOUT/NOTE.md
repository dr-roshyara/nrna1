# 1bb-r2 run 1: an infrastructure failure (disclosed)
- **Worker:** exit 124. It was killed by `timeout 2400` before writing SPEC-r2.* (the transcript shows reads only). **No r2 content exists.**
- **Reviewer:** verdict "FAIL", **solely because the input was missing** (B-0). This is **not a verdict on r2 content**.
- **Independence flag:** the reviewer attempted to read `../A/SPEC-r2.md`, outside its folder. The file did not exist, so nothing was read. Disclosed.
- **Action:** the **same frozen task** (`76411541f`, task text unchanged) is re-executed with a longer timeout and a **fresh** reviewer folder. This is not a new repair round: the "no r3" rule applies to a reviewed-content FAIL of r2.
