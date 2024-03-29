<?php
declare(strict_types=1);

namespace EonX\EasySecurity\Tests\Bridge\Symfony\Security;

use EonX\EasySecurity\Bridge\Symfony\Factories\AuthenticationFailureResponseFactory;
use EonX\EasySecurity\Bridge\Symfony\Security\SecurityContextAuthenticator;
use EonX\EasySecurity\Interfaces\SecurityContextResolverInterface;
use EonX\EasySecurity\Tests\AbstractTestCase;
use EonX\EasySecurity\Tests\Bridge\Symfony\Stubs\CustomAuthenticationException;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

final class SecurityContextAuthenticatorTest extends AbstractTestCase
{
    /**
     * @see testAuthenticateThrowsCorrectException
     */
    public static function provideExceptions(): iterable
    {
        yield 'Library exception 1' => [
            'thrownException' => new RuntimeException(),
            'expectedExceptionClass' => AuthenticationException::class,
        ];

        yield 'Library exception 2' => [
            'thrownException' => new LogicException(),
            'expectedExceptionClass' => AuthenticationException::class,
        ];

        yield 'Custom exception' => [
            'thrownException' => new CustomAuthenticationException(),
            'expectedExceptionClass' => CustomAuthenticationException::class,
        ];
    }

    /**
     * @psalm-param class-string<\Throwable> $expectedExceptionClass
     */
    #[DataProvider('provideExceptions')]
    public function testAuthenticateThrowsCorrectException(
        Throwable $thrownException,
        string $expectedExceptionClass,
    ): void {
        $this->expectException($expectedExceptionClass);

        $securityContextResolver = $this->prophesize(SecurityContextResolverInterface::class);
        $securityContextResolver->resolveContext()
            ->willThrow($thrownException);
        /** @var \EonX\EasySecurity\Interfaces\SecurityContextResolverInterface $securityContextResolverReveal */
        $securityContextResolverReveal = $securityContextResolver->reveal();
        $authenticator = new SecurityContextAuthenticator(
            $securityContextResolverReveal,
            new AuthenticationFailureResponseFactory()
        );

        $authenticator->authenticate(new Request());
    }
}
