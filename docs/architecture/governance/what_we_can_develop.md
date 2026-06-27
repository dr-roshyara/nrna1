Now I understand.

---

## What You Just Described

```text
1. Voter casts vote
2. Vote is saved as JSON in vote table
3. Result table is created/updated AT THE SAME TIME
4. Final count comes from the result table
```

---

## What This Means

**There is no separate "tallying" phase.** Counting happens simultaneously with voting. When a vote is cast, the result is updated immediately.

This is a critical discovery:

| What I Assumed | What Is Actually True |
|----------------|----------------------|
| Tallying is a separate step after voting | Tallying is real-time, integrated with vote casting |
| Someone triggers the count | Nobody triggers it — it's automatic |
| Vote Tallying might be a bounded context | Tallying is a computational byproduct of voting |

---

## Architectural Implication

Your candidate context **"Vote Tallying"** may not be a separate context at all. It may be:

```text
Option A: A sub-process within the Voting context
          (cast vote → save vote → update result in same transaction)

Option B: An eventual consistency mechanism
          (vote saved, result updated asynchronously but automatically)

Option C: A read model
          (result table is a projection of vote data, not a separate domain)
```

---

## Critical Question

When a vote is cast and the result is updated:

| Question | Answer |
|----------|--------|
| Does the voter's identity go into the result table? | |
| Can anyone see partial results while voting is open? | |
| After voting closes, who can see the results? | |
| Can the result be recalculated from the vote table if needed? | |
| Is the result table the official record, or is the vote table the source of truth? | |

This tells me whether "result" is a separate domain concept or just a cached count.