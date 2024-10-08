<?php
declare(strict_types=1);

namespace EonX\EasySecurity\SymfonySecurity\Voter;

use EonX\EasySecurity\Common\Resolver\SecurityContextResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends \Symfony\Component\Security\Core\Authorization\Voter\Voter<string, mixed>
 */
final class RoleVoter extends Voter
{
    public function __construct(
        private readonly SecurityContextResolverInterface $securityContextResolver,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $this->securityContextResolver
            ->resolveContext()
            ->getAuthorizationMatrix()
            ->isRole($attribute);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return $this->securityContextResolver
            ->resolveContext()
            ->hasRole($attribute);
    }
}
