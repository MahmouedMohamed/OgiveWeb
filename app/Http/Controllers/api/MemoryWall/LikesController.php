<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Exceptions\MemoryNotFound;
use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Models\MemoryWall\Like;
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
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', Like::class);
            return $this->sendResponse(
                $user->likes()->with('memory')->select(
                    [
                        'user_id',
                        'memory_id'
                    ]
                )
                    ->paginate(8),
                ''
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeViewingBannedMessage'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $memory = $this->memoryExists($request['memoryId']);
            $this->userIsAuthorized($user, 'create', Like::class);
            $like = Like::where('user_id', '=', $user->id)
                ->where('memory_id', '=', $memory->id)
                ->first();
            if (!$like) {
                $user->likes()->create([
                    'memory_id' => $memory->id
                ]);
            }
            return $this->sendResponse(
                [],
                __('MemoryWall.LikeCreationSuccessMessage'),
            ); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (MemoryNotFound $e) {
            return $this->sendError(__('MemoryWall.MemoryNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
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
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, String $id)
    {
        try {
            $memory = $this->memoryExists($id);
            $user = $this->userExists($request['userId']);
            $like = Like::where('user_id', '=', $user->id)
                ->where('memory_id', '=', $memory->id);
            if ($like->first() != null) {
                $this->userIsAuthorized($user, 'delete', $like->first());
                $like->delete();
            }
            return $this->sendResponse(
                [],
                __('MemoryWall.LikeDeleteSuccessMessage'),
            );
        } catch (MemoryNotFound $e) {
            return $this->sendError(
                __('MemoryWall.MemoryNotFound'),
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'),);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.LikeDeletionForbiddenMessage'),);
        }
    }
}
