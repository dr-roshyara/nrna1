<?php

namespace App\Domain\Election\Security;

/**
 * TrustEvaluationState
 *
 * Constitutional evidence evaluation states. The evaluator NEVER decides authority.
 * It returns evidence state: what evidence was sufficient, insufficient, inconclusive, or requiring review.
 * ONLY the resolver interprets these states and derives capability decisions.
 *
 * This separation prevents the evaluator from becoming a shadow resolver.
 *
 * CONSTITUTIONAL LAW:
 * - SUFFICIENT_EVIDENCE means facts support participation — resolver MAY grant
 * - INSUFFICIENT_EVIDENCE means facts do not support participation — resolver MAY deny
 * - REVIEW_REQUIRED means evidence is ambiguous or policy requires manual review — resolver defers
 * - INCONCLUSIVE means evidence quality is too poor to evaluate — resolver cannot decide
 */
enum TrustEvaluationState: string
{
    case SUFFICIENT_EVIDENCE   = 'sufficient_evidence';
    case INSUFFICIENT_EVIDENCE = 'insufficient_evidence';
    case REVIEW_REQUIRED       = 'review_required';
    case INCONCLUSIVE          = 'inconclusive';
}
