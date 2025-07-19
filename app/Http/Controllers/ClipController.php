<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Services\ClipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/**
 * @group Clip Management
 *
 * APIs for managing URL clips (shortened URLs)
 */
class ClipController extends Controller
{
    public function __construct(protected ClipService $clipService)
    {
    }

    /**
     * Get all clips
     *
     * Retrieve all clips belonging to the authenticated user.
     *
     * @authenticated
     *
     * @response 200 {
     *     "message": "Successfully Retrieved Clips",
     *     "success": true,
     *     "data": [
     *         {
     *             "id": 1,
     *             "url": "https://example.com/very-long-url",
     *             "slug": "abc123",
     *             "password": null,
     *             "expires_at": null,
     *             "created_at": "2023-01-01T00:00:00.000000Z",
     *             "updated_at": "2023-01-01T00:00:00.000000Z"
     *         }
     *     ]
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     */
    public function index(): JsonResponse
    {
        $clips = Clip::all();

        return success('Successfully Retrieved Clips', $clips->toArray(), 200);
    }

    /**
     * Create a new clip
     *
     * Create a new shortened URL clip.
     *
     * @authenticated
     *
     * @bodyParam url string required The URL to shorten. Example: https://example.com/very-long-url
     * @bodyParam slug string optional Custom slug for the shortened URL. Example: my-custom-slug
     * @bodyParam password string optional Password to protect the clip. Example: secret123
     * @bodyParam expires_at string optional Expiration date for the clip (ISO 8601 format). Example: 2023-12-31T23:59:59Z
     *
     * @response 201 {
     *     "message": "Successfully Created Clip",
     *     "success": true,
     *     "data": {
     *         "id": 1,
     *         "url": "https://example.com/very-long-url",
     *         "slug": "abc123",
     *         "password": null,
     *         "expires_at": null,
     *         "created_at": "2023-01-01T00:00:00.000000Z",
     *         "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     * }
     *
     * @response 400 {
     *     "message": "The url field is required.",
     *     "success": false
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     */
    public function saveClip(Request $request): JsonResponse
    {
        $validator = validator([
            'url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first(), 400);
        }
        try {
            return $this->clipService->saveClip($request);
        } catch (\Exception $e) {
            return error('Server error', 500);
        }
    }

    /**
     * Update a clip
     *
     * Update an existing clip by its ID.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the clip to update. Example: 1
     * @bodyParam url string required The URL to shorten. Example: https://example.com/updated-url
     * @bodyParam slug string optional Custom slug for the shortened URL. Example: updated-slug
     * @bodyParam password string optional Password to protect the clip. Example: newsecret123
     * @bodyParam expires_at string optional Expiration date for the clip (ISO 8601 format). Example: 2023-12-31T23:59:59Z
     *
     * @response 200 {
     *     "message": "Successfully Updated Clip",
     *     "success": true,
     *     "data": {
     *         "id": 1,
     *         "url": "https://example.com/updated-url",
     *         "slug": "updated-slug",
     *         "password": null,
     *         "expires_at": null,
     *         "created_at": "2023-01-01T00:00:00.000000Z",
     *         "updated_at": "2023-01-01T12:00:00.000000Z"
     *     }
     * }
     *
     * @response 400 {
     *     "message": "The url field is required.",
     *     "success": false
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     *
     * @response 404 {
     *     "message": "Clip not found",
     *     "success": false
     * }
     */
    public function editClip(Request $request, int $id): JsonResponse
    {
        $validator = validator([
            'url' => 'required|string',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first(), 400);
        }
        try {
            return $this->clipService->saveClip($request, $id);
        } catch (\Exception $e) {
            return error('Server error', 500);
        }
    }

    /**
     * Get a clip by slug
     *
     * Retrieve a specific clip by its slug. If the clip is password protected,
     * the password must be provided in the request.
     *
     * @authenticated
     *
     * @urlParam slug string required The slug of the clip to retrieve. Example: abc123
     * @bodyParam password string optional Password for protected clips. Example: secret123
     *
     * @response 200 {
     *     "message": "Successfully Retrieved Clip",
     *     "success": true,
     *     "data": {
     *         "id": 1,
     *         "url": "https://example.com/very-long-url",
     *         "slug": "abc123",
     *         "password": null,
     *         "expires_at": null,
     *         "created_at": "2023-01-01T00:00:00.000000Z",
     *         "updated_at": "2023-01-01T00:00:00.000000Z"
     *     }
     * }
     *
     * @response 400 {
     *     "message": "The slug field is required.",
     *     "success": false
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     *
     * @response 404 {
     *     "message": "Clip not found",
     *     "success": false
     * }
     *
     * @response 403 {
     *     "message": "Incorrect password",
     *     "success": false
     * }
     */
    public function getClip($slug): JsonResponse
    {
        $validator = validator([
            'slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first(), 400);
        }
        try {
            return $this->clipService->getClip($slug, request()->password ?? null);
        } catch (\Exception $e) {
            dd($e);
            return error('Server error ' . $e->getMessage(), 500);
        }
    }

    /**
     * Verify password for a clip
     *
     * Verify the password for a password-protected clip and redirect to the original URL.
     * This is used for web-based access to protected clips.
     *
     * @unauthenticated
     *
     * @urlParam id integer required The ID of the clip. Example: 1
     * @bodyParam password string required The password for the clip. Example: secret123
     *
     * @response 302 {
     *     "description": "Redirects to the original URL if password is correct"
     * }
     *
     * @response 422 {
     *     "description": "Validation error or incorrect password"
     * }
     */
    public function verifyPassword(Request $request, $id): RedirectResponse
    {
        $id = $request->id;
        $password = $request->password;

        $validator = validator([
            $id => 'required|int',
            $password => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        try {
            $url = $this->clipService->verifyPassword($id, $password);
            if ($url) {
                return redirect($url);
            } else {
                return redirect()->back()->with('error', 'Incorrect Password');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Server error');
        }
    }

    /**
     * Delete a clip
     *
     * Delete a clip by its ID.
     *
     * @authenticated
     *
     * @urlParam id integer required The ID of the clip to delete. Example: 1
     *
     * @response 200 {
     *     "message": "Successfully Deleted Clip",
     *     "success": true,
     *     "data": []
     * }
     *
     * @response 400 {
     *     "message": "The id field is required.",
     *     "success": false
     * }
     *
     * @response 401 {
     *     "message": "Unauthenticated"
     * }
     *
     * @response 404 {
     *     "message": "Clip not found",
     *     "success": false
     * }
     */
    public function deleteClip($id): JsonResponse
    {
        $validator = validator([
            'id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return error($validator->errors()->first(), 400);
        }

        try {
            return $this->clipService->deleteClip($id);
        } catch (\Exception $e) {
            return error('Server error', 500);
        }
    }
}
