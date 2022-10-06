<?php

namespace App\Domain\Finance\Services;
use App\Domain\Finance\Notifications\FinanceNotification;
use Illuminate\Support\Facades\Notification;

class FinanceNotificationService {

    public function notify_finance($financeInfo){

    $user   =auth()->user();
    $emails =['mathematikboy@yahoo.com'];
    Notification::route('mail', $emails)
    ->notify(new FinanceNotification($user,$financeInfo));
  }
}
