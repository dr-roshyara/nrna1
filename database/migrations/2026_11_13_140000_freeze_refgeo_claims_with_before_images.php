<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-4e (v3.11 §5.1 "stored with the claim forever"; §6 / completion §4: a stored before-image equals the
 * belief just before publication): every frozen claim carries its before-image. Staleness under the publication lock
 * reads the before-images of the claims published after p₀ from here. NOT NULL: no claim is frozen without one.
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE refgeo_claims ADD COLUMN before_image jsonb NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE refgeo_claims DROP COLUMN IF EXISTS before_image');
    }
};
