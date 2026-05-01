<?php

namespace App\Domain\Election\Enum;

enum ElectionState: string
{
    case Draft = 'draft';
    case PendingApproval = 'pending_approval';
    case Administration = 'administration';
    case Nomination = 'nomination';
    case Voting = 'voting';
    case ResultsPending = 'results_pending';
    case Results = 'results';
}
