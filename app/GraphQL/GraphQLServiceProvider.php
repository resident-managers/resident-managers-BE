<?php
namespace App\GraphQL;

use App\Enums\HouseholdRelationship;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\TypeRegistry;
use Nuwave\Lighthouse\Schema\Types\LaravelEnumType;

class GraphQLServiceProvider extends ServiceProvider
{
	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Bootstrap services.
	 *
	 * @param TypeRegistry $typeRegistry
	 * @return void
	 * @throws DefinitionException
	 */
	public function boot(TypeRegistry $typeRegistry): void
	{
		$typeRegistry->register(
			new LaravelEnumType(HouseholdRelationship::class)
		);
	}
}