<?php

namespace App\Services;

use App\Models\Clip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClipService
{
    private function generateAndGetSlug(int $length): string
    {
        $slug = substr(
            str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'),
            0,
            $length
        );
        $clip = Clip::where('slug', $slug)->first();

        if ($clip) {
            return $this->generateAndGetSlug($length);
        } else {
            return $slug;
        }
    }

    public function saveClip(Request $request, $id)
    {
        $slug = $request?->slug ?? $this->generateAndGetSlug(6);
        $url = $request?->url;
        $password = $request->password;

        // parse timestamp from dd/mm/yyyy-hh:mm:ss format
        $expiresAt = $request->expires_at ? date('Y-m-d H:i:s', strtotime($request->expires_at)) : null;
        if($id) {
            $clip = Clip::find($id);
            $uc = Clip::where('url', $url)->first();

            if ($uc && $uc->id !== $id) {
                return error( 'Url already exists', 409);
            }

            $sclip = Clip::where('slug', $slug)->first();

            if ($sclip && $sclip->id !== $id) {
                return error('Slug Already Exists', 409);
            } else {

                if ($clip) {
                    $clip->slug = $slug;
                    $clip->url = $url;
                    $clip->password = $password ? encrypt($password) : null;
                    $clip->expires_at = $expiresAt;
                    $clip->save();

                    return success( 'Successfully Saved Clip', $clip, 200);
                } else {
                    return error('Clip Not Found', 404);
                }
            }
        }

        $clip = Clip::where('url', $url)->first();

        if ($clip) {
            return success( 'Clip Already Exists, ignoring slug', $clip, 200);
        }

        $clip = Clip::where('slug', $slug)->first();

        if ($clip) {
            return error('Slug Already Exists', 409);
        } else {
            $clip = new Clip();
            $clip->slug = $slug;
            $clip->url = $url;
            $clip->password = $password ? encrypt($password) : null;
            $clip->expires_at = $expiresAt;
            $clip->save();

            return success( 'Successfully Saved Clip', $clip, 201);
        }
    }
    /**
     * @param string $slug
     * @param string $password
     * @param bool $onlyUrl
     */
    public function getClip($slug, $password) : JsonResponse
    {
        $clip = Clip::where('slug', $slug)->first();

        if ($clip) {
            if ($clip->expires_at && $clip->expires_at < now()) {
                return error('Clip Expired', 410);
            }
            if ($clip->password && !$password) {
                return error('Password Missing', 404);
            }
            if(!$clip->password) {
                return success('Successfully Retrieved Clip',$clip, 200);
            }
            if (decrypt($clip->password) === $password) {
                return success('Successfully Retrieved Clip',$clip, 200);
            } else {
                return error('Incorrect Password', 401);
            }
        } else {
            return error('Clip Not Found', 404);
        }
    }

    /// param int $id
    /// param string $password
    public function verifyPassword($id, $password) : mixed {
        $clip = Clip::find($id);

        $isVerified = false;

        if ($clip?->password) {
            $isVerified = decrypt($clip->password) === $password;
        }

        return $isVerified ? $clip->url : null;
    }

    public function deleteClip($id)
    {
        $clip = Clip::find($id);

        if ($clip) {
            $clip->delete();

            return success([], 'Successfully Deleted Clip', 200);
        } else {
            return error('Clip Not Found', 404);
        }
    }
}
