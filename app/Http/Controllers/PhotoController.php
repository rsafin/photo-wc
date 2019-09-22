<?php

namespace App\Http\Controllers;

use App\Photo;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class PhotoController
 * @package App\Http\Controllers
 */
class PhotoController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $photos = $user->photos->merge($user->share);

        foreach ($photos as $key => $photo) {
            $photos[$key] = [
                'id' => $photo->id,
                'name' => $photo->name,
                'url' => $photo->getFullUrl(),
                'owner_id' => $photo->owner_id,
                'users' => $photo->users->map(function($item, $key) {
                    return $item->id;
                }),
            ];
        }

        return $this->jsonResponse(self::CODE_OK, $photos, 200);
    }

    /**
     * @param Photo $photo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Photo $photo)
    {
        $content = [
            'id' => $photo->id,
            'name' => $photo->name,
            'url' => $photo->getFullUrl(),
            'owner_id' => $photo->owner_id,
            'users' => $photo->users->map(function($item, $key) {
                return $item->id;
            }),
        ];
        return $this->jsonResponse(self::CODE_OK, $content, 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'image',
        ]);

        $image = $request->file('photo');

        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('images'), $filename);

        $photo = new Photo();
        $photo->url = $filename;
        $photo->owner_id = Auth::user()->id;

        $photo->save();

        $content = [
            'id' => $photo->id,
            'name' => $photo->name ?? 'Untitled',
            'url' => $photo->getFullUrl(),
        ];

        return $this->jsonResponse(self::CODE_CREATED, $content,201);
    }

    /**
     * @param Request $request
     * @param Photo $photo
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Photo $photo)
    {
        if (Auth::user()->id == $photo->owner->id)
        {
            if ($request->name) {
                $photo->name = $request->name;
            }


            if($request->photo) {
                $filename = time() . '.png';

                $image = str_replace('data:image/png;base64,', '', $request->photo);
                $image = str_replace(' ', '+', $image);
                $image = base64_decode($image);
                file_put_contents(public_path('images') . '/' . $filename, $image);

                $photo->url = $filename;
            }

            $photo->save();

            $content = [
                'id' => $photo->id,
                'name' => $photo->name,
                'url' => $photo->getFullUrl(),
            ];

            return $this->jsonResponse(self::CODE_OK, $content, 200);
        }

        throw new AuthorizationException(self::CODE_FORBIDDEN, 403);
    }

    /**
     * @param Photo $photo
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function delete(Photo $photo)
    {
        if (Auth::user()->id == $photo->owner->id)
        {
            $photo->delete();

            return $this->jsonResponse(self::CODE_DELETED, null, 200);
        }

        throw new AuthorizationException(self::CODE_FORBIDDEN, 403);
    }
}
