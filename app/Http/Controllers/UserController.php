<?php

namespace App\Http\Controllers;

use App\Photo;
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
        $search = $request->get('search');

        $terms = explode(' ', $search);

        $users = User::query();

        foreach ($terms as $term) {
            $users = $users->where(function($query) use ($term) {
                $query->orWhere('first_name', 'like', "%{$term}%");
                $query->orWhere('surname', 'like', "%{$term}%");
                $query->orWhere('phone', 'like', "%{$term}%");
            });
        }

        $users = $users->get(['id', 'first_name', 'surname', 'phone']);

        return $this->jsonResponse(self::CODE_OK, $users, 200);
    }

    public function share(Request $request, User $user)
    {
        $photoId = json_decode($request->input('photos', ''), true);

        $photos = Photo::find($photoId);
        $user->share()->saveMany($photos);

        return $this->jsonResponse(self::CODE_CREATED, [], 201);
    }
}
