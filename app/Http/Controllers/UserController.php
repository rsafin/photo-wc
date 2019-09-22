<?php

namespace App\Http\Controllers;

use App\Photo;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * //TODO: don't share for yourself
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function share(Request $request, User $user)
    {
        $photosId = $request->input('photos');

        /**
         * @var User $user
         */
        $me = Auth::user();
        $shared = $user->share;

        $sharedIds = array_values($shared->map(function($item, $key){
            return $item->id;
        })->all());


        $idForAdd = array_diff($photosId, $sharedIds);

        $photos = $me->photos()->find($idForAdd);
        $user->share()->saveMany($photos);


        $sharedIds = array_values($user->share()->get()->map(function($item, $key){
            return $item->id;
        })->all());

        $content = [
            'existing_photos' => $sharedIds,
        ];

        return $this->jsonResponse(self::CODE_CREATED, $content, 201);
    }
}
