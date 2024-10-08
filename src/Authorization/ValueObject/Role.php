<?php
declare(strict_types=1);

namespace EonX\EasySecurity\Authorization\ValueObject;

use EonX\EasySecurity\Authorization\Formatter\AuthorizationMatrixFormatter;
use Stringable;

final class Role implements Stringable
{
    /**
     * @var \EonX\EasySecurity\Authorization\ValueObject\Permission[]
     */
    private readonly array $permissions;

    /**
     * @param string[]|\EonX\EasySecurity\Authorization\ValueObject\Permission[]|null $permissions
     */
    public function __construct(
        private readonly string $identifier,
        ?array $permissions = null,
        private readonly ?string $name = null,
        private ?array $metadata = null,
    ) {
        $this->permissions = AuthorizationMatrixFormatter::formatPermissions($permissions ?? []);
    }

    public function __toString(): string
    {
        return $this->identifier;
    }

    public function addMetadata(string $name, mixed $value): self
    {
        if ($this->metadata === null) {
            $this->metadata = [];
        }

        $this->metadata[$name] = $value;

        return $this;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getMetadata(?string $name = null, mixed $default = null): mixed
    {
        return $name === null ? ($this->metadata ?? []) : ($this->metadata[$name] ?? $default);
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return \EonX\EasySecurity\Authorization\ValueObject\Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function hasMetadata(string $name): bool
    {
        return \is_array($this->metadata) && isset($this->metadata[$name]);
    }

    public function removeMetadata(string $name): self
    {
        if ($this->metadata !== null) {
            unset($this->metadata[$name]);
        }

        return $this;
    }

    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
