<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SynchronizeGoogleEvents extends SynchronizeGoogleResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $calendar;



    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->calendar = $calendar;
    }

    public function getGoogleService()
    {
        return app(Google::class)
            // We access the token through the `googleAccount` relationship.
            ->connectUsing($this->calendar->googleAccount->token)
            ->service('Calendar');
    }

    public function getGoogleRequest($service, $options)
    {
        return $service->events->listEvents(
            // We provide the Google ID of the calendar from which we want the events.
            $this->calendar->google_id, $options
        );
    }

    public function syncItem($googleEvent)
    {
        // A Google event has been deleted if its status is `cancelled`.
        if ($googleEvent->status === 'cancelled') {
            return $this->calendar->events()
                ->where('google_id', $googleEvent->id)
                ->delete();
        }

        $this->calendar->events()->updateOrCreate(
            [
                'google_id' => $googleEvent->id,
            ],
            [
                'name' => $googleEvent->summary,
                'description' => $googleEvent->description,
                'allday' => $this->isAllDayEvent($googleEvent), 
                'started_at' => $this->parseDatetime($googleEvent->start), 
                'ended_at' => $this->parseDatetime($googleEvent->end), 
            ]
        );
    }

    // See next changes on GitHub to check out these helper methods.
    protected function isAllDayEvent($googleEvent) { ... }
    protected function parseDatetime($googleDatetime) { ... }

}
