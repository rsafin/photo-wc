<?php

namespace App\Http\Controllers;

use App\Photo;
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
        $photos = Photo::all();

        foreach ($photos as $key => $photo) {
            $photos[$key] = [
                'id' => $photo->id,
                'name' => $photo->name,
                'url' => $photo->getFullUrl(),
                'owner_id' => $photo->owner_id,
                'users' => [],
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
        return response()->json($photo, 200);
    }

    public function store(Request $request)
    {
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

    public function update(Request $request, Photo $photo)
    {

    }

    public function delete(Request $request, Photo $photo)
    {

    }
}
