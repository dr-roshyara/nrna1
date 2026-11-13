<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Infrastructure;

use App\Contexts\ReferenceGeography\Application\Ports\PublishedAtlasSource;
use App\Contexts\ReferenceGeography\Domain\PublishedAtlas;
use Illuminate\Database\ConnectionInterface;

/** The published atlas at the committed position, from PostgreSQL (G-4d PostgresPublishedAtlas). */
final class PostgresPublishedAtlasSource implements PublishedAtlasSource
{
    public function __construct(private readonly ConnectionInterface $db)
    {
    }

    public function current(): PublishedAtlas
    {
        return PostgresPublishedAtlas::at($this->db);
    }
}
