<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Http\Requests\VideoRequest;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VideoRepository extends Repository
{
    public static function model()
    {
        return Video::class;
    }

    // store
    public static function videoStoreByRequest(VideoRequest $request): Video
    {
        DB::beginTransaction();

        try {
            $thumbnail = null;

            // store video file if uploaded
            if ($request->hasFile('video')) {
                $thumbnail = MediaRepository::videoStoreByRequest($request->video, 'videos', 'video', 'video');

                if (! $thumbnail || ! isset($thumbnail->id)) {
                    // if MediaRepository failed for some reason, stop here
                    throw new \RuntimeException('Failed to store uploaded video media.');
                }
            }

            // shop
            $shop = generaleSetting('shop');
            $shopId = $shop?->id;
            $user = $shop?->user;
            if ($user && $user->hasRole('root') && ! $request->for_shop) {
                $shopId = null;
            }

            // base data to save
            $data = [
                'title'        => $request->title,
                'shop_id'      => $shopId,
                'product_link' => $request->product_link,
                'status'       => true,
            ];

            // If a new media was uploaded, set it (override). If not, preserve existing below.
            if ($thumbnail) {
                $data['media_id'] = $thumbnail->id;
            }
            if ($request->id) {
                // Try to find existing video
                $video = Video::find($request->id);

                if ($video) {
                    // If no new thumbnail uploaded, preserve old media_id explicitly
                    if (! isset($data['media_id'])) {
                        $data['media_id'] = $video->media_id;
                    }

                    $video->update($data);

                    DB::commit();

                    // return fresh model with media relation loaded
                    return Video::with('media')->find($video->id);
                }

                // if id provided but not found, fall through to create
                Log::warning("Video id {$request->id} not found — creating new record instead.");
            }

            // create new record
            $video = Video::create($data);

            DB::commit();

            return Video::with('media')->find($video->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            // log full error for debugging
            Log::error('Video store/update failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            // rethrow or return null depending on your app conventions
            throw $e;
        }
    }
}
