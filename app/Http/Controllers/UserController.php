<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $request->get('search');


        $users = User::query()->get(['id', 'first_name', 'surname', 'phone']);

        return $this->jsonResponse(self::CODE_OK, $users, 200);
    }

    public function share(Request $request, User $user)
    {

    }
}