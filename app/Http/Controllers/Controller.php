<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    public const CODE_OK = '200 OK';
    public const CODE_CREATED = '201 Created';
    public const CODE_DELETED = '204 Deleted';
    public const CODE_FORBIDDEN = '403 Forbidden';
    public const CODE_NOT_FOUND = '404 NOT FOUND';
    public const CODE_UNPROCESSABLE_ENTITY = 'Code: 422 Unprocessable entity';

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $code
     * @param array|object $content
     * @param int $status
     *
     * @return JsonResponse
     */
    protected function jsonResponse(string $code, $content, int $status): JsonResponse
    {
        $response = [
            'Code' => $code,
        ];

        if ($content) {
            $response['Content'] = $content;
        }

        return response()->json($response, $status);
    }
}
