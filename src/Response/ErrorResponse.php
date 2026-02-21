<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace App\Response;

use JsonSerializable;
use Override;

final readonly class ErrorResponse implements JsonSerializable
{
    /**
     * @param array<string, array<string>|int|string> $details
     */
    public function __construct(
        private string $message,
        private array $details = [],
    ) {
    }

    /**
     * @return array{message: string, details?: array<string, mixed>}
     */
    #[Override]
    public function jsonSerialize(): array
    {
        $result = ['message' => $this->message];

        if ([] !== $this->details) {
            $result['details'] = $this->details;
        }

        return $result;
    }
}
