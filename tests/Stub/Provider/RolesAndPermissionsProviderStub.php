<?php
declare(strict_types=1);

namespace EonX\EasySecurity\Tests\Stub\Provider;

use EonX\EasySecurity\Authorization\Provider\PermissionsProviderInterface;
use EonX\EasySecurity\Authorization\Provider\RolesProviderInterface;

final class RolesAndPermissionsProviderStub implements RolesProviderInterface, PermissionsProviderInterface
{
    /**
     * @var string[]|\EonX\EasySecurity\Authorization\ValueObject\Permission[]
     */
    private readonly array $permissions;

    /**
     * @var string[]|\EonX\EasySecurity\Authorization\ValueObject\Role[]
     */
    private readonly array $roles;

    /**
     * @param string[]|\EonX\EasySecurity\Authorization\ValueObject\Role[]|null $roles
     * @param string[]|\EonX\EasySecurity\Authorization\ValueObject\Permission[]|null $permissions
     */
    public function __construct(?array $roles = null, ?array $permissions = null)
    {
        $this->roles = $roles ?? [];
        $this->permissions = $permissions ?? [];
    }

    /**
     * @return string[]|\EonX\EasySecurity\Authorization\ValueObject\Permission[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @return string[]|\EonX\EasySecurity\Authorization\ValueObject\Role[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }
}
