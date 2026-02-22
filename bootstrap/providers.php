<?php

return [
    App\Providers\AppServiceProvider::class,
	/*
	 * Other Service Providers
	 */
	App\GraphQL\GraphQLServiceProvider::class,
	\Nuwave\Lighthouse\WhereConditions\WhereConditionsServiceProvider::class,
];
