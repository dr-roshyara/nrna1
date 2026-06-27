<?php

namespace App\Application\Election\Capabilities;

final readonly class CapabilityTrace
{
    /** @param CapabilityTraceEntry[] $entries */
    public function __construct(
        public array $entries = [],
    ) {}

    public static function empty(): self
    {
        return new self([]);
    }

    public function add(CapabilityTraceEntry $entry): self
    {
        return new self([...$this->entries, $entry]);
    }

    public function count(): int
    {
        return count($this->entries);
    }

    public function hasDenials(): bool
    {
        foreach ($this->entries as $entry) {
            if ($entry->isDenied()) {
                return true;
            }
        }
        return false;
    }

    public function denials(): array
    {
        $denials = [];
        foreach ($this->entries as $entry) {
            if ($entry->isDenied()) {
                $denials[] = $entry;
            }
        }
        return $denials;
    }

    public function firstDenial(): ?CapabilityTraceEntry
    {
        foreach ($this->entries as $entry) {
            if ($entry->isDenied()) {
                return $entry;
            }
        }
        return null;
    }

    public function hasTerminalOutcome(): bool
    {
        foreach ($this->entries as $entry) {
            if ($entry->isTerminal()) {
                return true;
            }
        }
        return false;
    }

    public function firstTerminal(): ?CapabilityTraceEntry
    {
        foreach ($this->entries as $entry) {
            if ($entry->isTerminal()) {
                return $entry;
            }
        }
        return null;
    }

    public function policyNames(): array
    {
        $names = [];
        foreach ($this->entries as $entry) {
            $names[] = $entry->policyName;
        }
        return $names;
    }
}
