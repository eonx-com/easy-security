<?php
declare(strict_types=1);

namespace EonX\EasySecurity\Authorization\ValueObject;

use Stringable;

final readonly class Permission implements Stringable
{
    public function __construct(
        private string $identifier,
    ) {
    }

    public function __toString(): string
    {
        return $this->identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
