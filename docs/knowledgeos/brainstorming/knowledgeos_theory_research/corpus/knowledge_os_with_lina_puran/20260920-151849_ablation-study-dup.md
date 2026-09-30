## Ablation study

An **ablation study** is a controlled ML experiment that tests how much each component of a system contributes to its performance: start with the full model, remove or simplify one component, and measure what changes. [en.wikipedia](https://en.wikipedia.org/wiki/Ablation_(artificial_intelligence))

For example, suppose a vision model uses:
- data augmentation,
- an attention module,
- a special auxiliary loss.

You would evaluate the full model first, then run variants such as “without augmentation,” “without attention,” and “without the auxiliary loss,” keeping the dataset, evaluation metric, training budget, and other conditions fixed. If accuracy drops substantially after removing attention, that is evidence that the attention module is valuable; if it barely changes, the module may be redundant or not worth its complexity. [theorempath](https://theorempath.com/topics/ablation-study-design)

A typical result table looks like this:

| Model variant | Accuracy | Interpretation |
|---|---:|---|
| Full model | 92.0% | Baseline |
| Without attention | 88.1% | Attention contributes materially |
| Without augmentation | 90.9% | Augmentation helps modestly |
| Without auxiliary loss | 92.0% | No observed added value |

The key idea is **causal isolation**: change one design choice at a time, rather than merely comparing two very different architectures. Strong studies also repeat runs across random seeds and report variation, because a small difference could be training noise rather than a genuine contribution. [theorempath](https://theorempath.com/topics/ablation-study-design)

In your own words, what component of an ML or RAG pipeline would you ablate first, and what metric would you use to judge its effect?