<?php

namespace App\Domain\Election\Enum;

enum ElectionAction: string
{
    case SubmitForApproval = 'submit_for_approval';
    case AutoSubmit = 'auto_submit';
    case Approve = 'approve';
    case Reject = 'reject';
    case BeginSetup = 'begin_setup';
    case ReviseAndResubmit = 'revise_and_resubmit';
    case CompleteAdministration = 'complete_administration';
    case CompleteNomination = 'complete_nomination';
    case ApplyCandidacy = 'apply_candidacy';
    case OpenVoting = 'open_voting';
    case CloseVoting = 'close_voting';
    case PublishResults = 'publish_results';
    case Archive = 'archive';
    case Suspend = 'suspend';
    case Resume = 'resume';
}
