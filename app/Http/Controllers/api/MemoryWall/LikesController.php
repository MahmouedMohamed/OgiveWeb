<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Http\Requests\CreateLikeRequest;
use App\Http\Resources\LikePaginationResource;
use App\Models\MemoryWall\Like;
use App\Models\MemoryWall\Memory;
use App\Traits\ControllersTraits\MemoryValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;

class LikesController extends BaseController
{
    use UserValidator, MemoryValidator;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', Like::class);
            return $this->sendResponse(
                new LikePaginationResource(
                    $request->user->likes()->with('memory')
                        ->paginate(8)
                ),
                ''
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeViewingBannedMessage'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateLikeRequest  $request
     * @param  \App\Models\MemoryWall\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function store(CreateLikeRequest $request, Memory $memory)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', Like::class);
            $like = Like::where('user_id', '=', $request->user->id)
                ->where('memory_id', '=', $memory->id)
                ->first();
            if (!$like) {
                $request->user->likes()->create([
                    'memory_id' => $memory->id
                ]);
            } else {
                $like->update(['type' => $request->type]);
            }
            return $this->sendResponse(
                [],
                __('MemoryWall.LikeCreationSuccessMessage'),
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MemoryWall\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, String $id)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  \App\Models\MemoryWall\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Memory $memory)
    {
        try {
            $like = Like::where('user_id', '=', $request->user->id)
                ->where('memory_id', '=', $memory->id);
            if ($like->first() != null) {
                $this->userIsAuthorized($request->user, 'delete', $like->first());
                $like->delete();
            }
            return $this->sendResponse(
                [],
                __('MemoryWall.LikeDeleteSuccessMessage'),
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeDeletionForbiddenMessage'),);
        }
    }
}
