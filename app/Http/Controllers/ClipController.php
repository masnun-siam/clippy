<?php

namespace App\Http\Controllers;

use App\Models\Clip;
use App\Services\ClipService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ClipController extends Controller
{
    public function __construct(protected ClipService $clipService)
    {
    }

    public function index(): JsonResponse
    {
        $clips = Clip::all();

        return success('Successfully Retrieved Clips', $clips ?? [], 200);
    }

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
     * @param mixed $slug
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
    @param int $id
    **/
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
     * @param mixed $id
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
