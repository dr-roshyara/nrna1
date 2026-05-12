<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Baseline;

final readonly class StructuredViolation
{
    /**
     * @param string $rule    Rule name (e.g. 'no_framework_imports')
     * @param string $file    File path relative to project root
     * @param string $symbol  Class/import name causing the violation
     * @param string $message Human-readable description
     * @param string $hash    SHA256 of (rule + file + symbol) — stable identity
     */
    public function __construct(
        public string $rule,
        public string $file,
        public string $symbol,
        public string $message,
        public string $hash,
    ) {}

    public static function hash(string $rule, string $file, string $symbol): string
    {
        return hash('sha256', "{$rule}:{$file}:{$symbol}");
    }

    /**
     * @param array{rule: string, file: string, symbol: string, message: string, hash: string} $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            rule: $data['rule'],
            file: $data['file'],
            symbol: $data['symbol'],
            message: $data['message'],
            hash: $data['hash'],
        );
    }

    public function toArray(): array
    {
        return [
            'rule' => $this->rule,
            'file' => $this->file,
            'symbol' => $this->symbol,
            'message' => $this->message,
            'hash' => $this->hash,
        ];
    }
}
