<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final readonly class Login
{
	/** @param array{} $args
	 * @throws ValidationException
	 */
    public function __invoke(null $_, array $args)
    {
        DB::beginTransaction();
	    try {
		    $user = User::where('email', $args['email'])->first();

		    if (!$user || !Hash::check($args['password'], $user->password)) {
			    throw ValidationException::withMessages([
				    'email' => ['Thông tin đăng nhập không chính xác.'],
			    ]);
		    }

		    $token = $user->createToken('Personal Access Token')->accessToken;

		    DB::commit();

		    return [
			    'access_token' => $token,
			    'user' => $user,
		    ];
	    } catch (\Exception $e) {
			DB::rollBack();
			throw $e;
	    }
    }
}
