<?php

namespace App\Domain\Election\Enum;

enum ElectionAction: string
{
    case SubmitForApproval = 'submit_for_approval';
    case AutoSubmit = 'auto_submit';
    case Approve = 'approve';
    case Reject = 'reject';
    case CompleteAdministration = 'complete_administration';
    case OpenVoting = 'open_voting';
    case LockVoting = 'lock_voting';
    case CloseVoting = 'close_voting';
    case PublishResults = 'publish_results';
}
