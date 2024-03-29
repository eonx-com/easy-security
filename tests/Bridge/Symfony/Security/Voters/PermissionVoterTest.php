<?php
declare(strict_types=1);

namespace EonX\EasySecurity\Tests\Bridge\Symfony\Security\Voters;

use EonX\EasySecurity\Authorization\AuthorizationMatrix;
use EonX\EasySecurity\Bridge\Symfony\Security\Voters\PermissionVoter;
use EonX\EasySecurity\Interfaces\Authorization\AuthorizationMatrixInterface;
use EonX\EasySecurity\Interfaces\SecurityContextInterface;
use EonX\EasySecurity\SecurityContext;
use EonX\EasySecurity\Tests\AbstractTestCase;
use EonX\EasySecurity\Tests\Stubs\SecurityContextResolverStub;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class PermissionVoterTest extends AbstractTestCase
{
    /**
     * @see testVoter
     */
    public static function providerTestVoter(): iterable
    {
        yield 'Abstain because permission not in matrix' => [
            new AuthorizationMatrix([], []),
            new SecurityContext(),
            'permission',
            VoterInterface::ACCESS_ABSTAIN,
        ];

        yield 'Denied because permission not on context' => [
            new AuthorizationMatrix([], ['permission']),
            new SecurityContext(),
            'permission',
            VoterInterface::ACCESS_DENIED,
        ];

        $securityContext = new SecurityContext();
        $securityContext->addPermissions(['permission']);

        yield 'Granted because permission in matrix and on context' => [
            new AuthorizationMatrix([], ['permission']),
            $securityContext,
            'permission',
            VoterInterface::ACCESS_GRANTED,
        ];
    }

    #[DataProvider('providerTestVoter')]
    public function testVoter(
        AuthorizationMatrixInterface $authorizationMatrix,
        SecurityContextInterface $securityContext,
        string $permission,
        int $expectedVote,
    ): void {
        $securityContext->setAuthorizationMatrix($authorizationMatrix);

        $voter = new PermissionVoter(new SecurityContextResolverStub($securityContext));
        $token = new NullToken();

        self::assertEquals($expectedVote, $voter->vote($token, null, [$permission]));
    }
}
