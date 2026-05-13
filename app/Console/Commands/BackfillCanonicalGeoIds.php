<?php

namespace App\Console\Commands;

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Services\CanonicalGeoSerializer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillCanonicalGeoIds extends Command
{
    protected $signature = 'app:backfill-canonical-geo-ids';

    protected $description = 'Backfill canonical_geo_id for existing committee rows with geo_unit_id';

    public function __construct(
        private readonly CanonicalGeoSerializer $serializer,
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        $this->info('Backfilling canonical_geo_id for committees...');

        $total = CommitteeModel::whereNotNull('geo_unit_id')
            ->whereNull('canonical_geo_id')
            ->count();

        if ($total === 0) {
            $this->info('No rows need backfilling.');
            return;
        }

        $this->info("Found {$total} rows to process.");

        $processed = 0;

        CommitteeModel::whereNotNull('geo_unit_id')
            ->whereNull('canonical_geo_id')
            ->chunkById(200, function ($rows) use (&$processed) {
                DB::transaction(function () use ($rows, &$processed) {
                    foreach ($rows as $committee) {
                        $committee->canonical_geo_id = $this->serializer->serialize(
                            $committee->geo_unit_id,
                            $committee->region_code,
                            $committee->country_code,
                        );
                        $committee->save();
                        $processed++;
                    }
                });

                $this->line("Processed {$processed} rows...");
            });

        $this->info("Done. Backfilled {$processed} committees.");
    }
}
