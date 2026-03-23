<?php declare(strict_types=1);

namespace App\GraphQL\Mutations\Auth;

use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final readonly class Logout
{
    /** @param array{} $args */
    public function __invoke(null $_, array $args, GraphQLContext $context): bool
    {
        $context->user()->token()->revoke();

        return true;
    }
}
