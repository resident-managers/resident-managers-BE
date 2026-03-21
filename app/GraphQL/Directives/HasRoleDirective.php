<?php declare(strict_types=1);

namespace App\GraphQL\Directives;

use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\AuthorizationException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldMiddleware;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

final class HasRoleDirective extends BaseDirective implements FieldMiddleware
{
    public static function definition(): string
    {
        return /** @lang GraphQL */ <<<'GRAPHQL'
        """Yêu cầu người dùng có role cụ thể để truy cập field này."""
        directive @hasRole(
            """Tên role yêu cầu (vd: "admin", "user")."""
            name: String!
        ) on FIELD_DEFINITION
        GRAPHQL;
    }

    public function handleField(FieldValue $fieldValue): void
    {
        $requiredRole = $this->directiveArgValue('name');

        $fieldValue->wrapResolver(fn (callable $resolver): Closure =>
            function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver, $requiredRole) {
                $user = $context->user();

                if (!$user || !$user->hasRole($requiredRole)) {
                    throw new AuthorizationException(
                        'Bạn không có quyền thực hiện thao tác này.'
                    );
                }

                return $resolver($root, $args, $context, $resolveInfo);
            }
        );
    }
}
