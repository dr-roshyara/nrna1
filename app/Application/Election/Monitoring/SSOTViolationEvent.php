<?php

namespace App\Application\Election\Monitoring;

final readonly class SSOTViolationEvent implements \JsonSerializable
{
    public function __construct(
        public string $violationType,
        public string $layer,
        public string $context,
        public \DateTimeImmutable $timestamp,
    ) {}

    public static function make(string $violationType, string $layer, string $context): self
    {
        return new self($violationType, $layer, $context, new \DateTimeImmutable());
    }

    public function jsonSerialize(): array
    {
        return [
            'violation_type' => $this->violationType,
            'layer'          => $this->layer,
            'context'        => $this->context,
            'timestamp'      => $this->timestamp->format(\DateTimeInterface::ATOM),
        ];
    }
}
