<?php

namespace App\Services;

use App\Models\Clip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DateTime;
use DateTimeZone;

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

    public function saveClip(Request $request, ?int $id = null) : JsonResponse
    {
        $slug = $request?->slug ?? $this->generateAndGetSlug(6);
        $url = $request?->url;
        $password = $request->password;

        // Convert datetime to UTC if provided
        $expiresAt = null;
        if ($request->expires_at) {
            try {
                // If the datetime is already in UTC format (from JavaScript conversion)
                if (strpos($request->expires_at, 'T') !== false || strpos($request->expires_at, ' ') !== false) {
                    // Handle ISO format or already formatted datetime
                    $dateTime = new DateTime($request->expires_at);
                    // If it's not already UTC, convert it
                    if ($dateTime->getTimezone()->getName() !== 'UTC') {
                        $dateTime->setTimezone(new DateTimeZone('UTC'));
                    }
                    $expiresAt = $dateTime->format('Y-m-d H:i:s');
                } else {
                    // Fallback for other formats
                    $expiresAt = date('Y-m-d H:i:s', strtotime($request->expires_at));
                }
            } catch (\Exception $e) {
                // Fallback to original behavior if parsing fails
                $expiresAt = date('Y-m-d H:i:s', strtotime($request->expires_at));
            }
        }

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

                    return success( 'Successfully Saved Clip', $clip->toArray(), 200);
                } else {
                    return error('Clip Not Found', 404);
                }
            }
        }

        $clip = Clip::where('url', $url)->first();

        if ($clip) {
            return success( 'Clip Already Exists, ignoring slug', $clip->toArray(), 200);
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

            return success( 'Successfully Saved Clip', $clip->toArray(), 201);
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
                return success('Successfully Retrieved Clip', $clip->toArray(), 200);
            }
            if (decrypt($clip->password) === $password) {
                return success('Successfully Retrieved Clip', $clip->toArray(), 200);
            } else {
                return error('Incorrect Password', 401);
            }
        } else {
            return error('Clip Not Found', 404);
        }
    }

    /**
     * @param int $id
     * @param string $password
     */
    public function verifyPassword(int $id, string $password) : ?string {
        $clip = Clip::find($id);

        $isVerified = false;

        if ($clip?->password) {
            $isVerified = decrypt($clip->password) === $password;
        }

        return $isVerified ? $clip->url : null;
    }

    public function deleteClip($id) : JsonResponse
    {
        $clip = Clip::find($id);

        if ($clip) {
            $clip->delete();

            return success('Successfully Deleted Clip', [], 200);
        } else {
            return error('Clip Not Found', 404);
        }
    }
}
