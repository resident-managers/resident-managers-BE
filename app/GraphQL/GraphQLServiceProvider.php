<?php
namespace App\GraphQL;

use App\Enums\HouseholdRelationship;
use App\Enums\InsuranceType;
use App\Enums\ResidenceType;
use App\Enums\SocialInsuranceStatus;
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
		$typeRegistry->register(new LaravelEnumType(HouseholdRelationship::class));
		$typeRegistry->register(new LaravelEnumType(ResidenceType::class));
		$typeRegistry->register(new LaravelEnumType(InsuranceType::class));
		$typeRegistry->register(new LaravelEnumType(SocialInsuranceStatus::class));
	}
}