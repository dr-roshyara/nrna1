<?php

namespace App\Domain\Election\Security;

enum OverlaySignalCategory: string
{
    case CONTINUE_UNCHANGED              = 'continue_unchanged';
    case TRUST_ELEVATION_REQUEST         = 'trust_elevation_request';
    case REQUIRE_RE_VERIFICATION         = 'require_re_verification';
    case REQUIRE_CONSTITUTIONAL_REVIEW   = 'require_constitutional_review';
    case TRUST_EVALUATION_INCONCLUSIVE   = 'trust_evaluation_inconclusive';
    case FEDERATION_CONCERN              = 'federation_concern';
    case REGISTRAR_ATTENTION_REQUIRED    = 'registrar_attention_required';
}
