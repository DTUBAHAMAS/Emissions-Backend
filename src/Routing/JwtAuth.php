<?php

namespace App\Routing;

use Cake\Chronos\Chronos;
use Lcobucci\Clock\SystemClock;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Token\Plain;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\Constraint\LooseValidAt;
use Lcobucci\JWT\Validation\ConstraintViolation;
use Lcobucci\JWT\ValidationData;
use Symfony\Component\Uid\Uuid;

/**
 * JwtAuth
 */
final class JwtAuth
{
    public function __construct(
        private Configuration $configuration,
        private string $issuer,
        private int $lifetime
    )
    {
    }

    /**
     * Get JWT max lifetime.
     *
     * @return int The lifetime in seconds
     */
    public function getLifetime(): int
    {
        return $this->lifetime;
    }

    /**
     * Create JSON web token.
     *
     * @param array<string> $claims The claims
     *
     * @return string The JWT
     */
    public function createJwt(array $claims): string
    {
        $now = Chronos::now();

        $builder = $this->configuration->builder()
            ->issuedBy($this->issuer)
            ->identifiedBy(Uuid::v4())
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now)
            ->expiresAt($now->addSeconds($this->lifetime));

        foreach ($claims as $name => $value) {

            $builder = $builder->withClaim($name, $value);
        }

        return $builder->getToken(
            $this->configuration->signer(),
            $this->configuration->signingKey()
        )->toString();
    }

    /**
     * Parse token.
     *
     * @param string $token The JWT
     *
     * @throws ConstraintViolation
     *
     * @return Plain The parsed token
     */
    private function createParsedToken(string $token): Plain
    {
        $token = $this->configuration->parser()->parse($token);

        if (!$token instanceof Plain) {
            throw new ConstraintViolation('You should pass a plain token');
        }

        return $token;
    }

    /**
     * Validate the access token.
     *
     * @param string $accessToken The JWT
     *
     * @return Plain|null The token, if valid
     */
    public function validateToken(string $accessToken): Plain|null
    {
        $token = $this->createParsedToken($accessToken);

        $constraints = $this->configuration->validationConstraints();

        $constraints[] = new SignedWith(
            $this->configuration->signer(),
            $this->configuration->verificationKey(),
        );

        $constraints[] = new IssuedBy($this->issuer);

        $constraints[] = new LooseValidAt(new SystemClock(Chronos::now()->getTimezone()));

        if (!$this->configuration->validator()->validate($token, ...$constraints)) {
            return null;
        }

        $userId = $token->claims()->get('uid');
        if (!$userId) {
            return null;
        }

        return $token;
    }
}