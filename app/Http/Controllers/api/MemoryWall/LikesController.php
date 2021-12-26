<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Exceptions\MemoryNotFound;
use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Helpers\ResponseHandler;
use App\Models\Like;
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
            $responseHandler = new ResponseHandler($request['language']);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', Like::class);
            return $this->sendResponse(
                $user->likes()->with('memory')->select(
                    [
                        'userId',
                        'memoryId'
                    ]
                )
                    ->paginate(8),
                ''
            );
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['LikeViewingBannedMessage']);
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
            $responseHandler = new ResponseHandler($request['language']);
            $user = $this->userExists($request['userId']);
            $memory = $this->memoryExists($request['memoryId']);
            $this->userIsAuthorized($user, 'create', Like::class);
            $like = Like::where('userId', '=', $user->id)
                ->where('memoryId', '=', $memory->id)
                ->first();
            if (!$like) {
                $user->likes()->create([
                    'memoryId' => $memory->id
                ]);
            }
            return $this->sendResponse([], $responseHandler->words['LikeCreationSuccessMessage']); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (MemoryNotFound $e) {
            return $this->sendError($responseHandler->words['MemoryNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['LikeCreationBannedMessage']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);
            $memory = $this->memoryExists($id);
            $user = $this->userExists($request['userId']);
            $like = Like::where('userId', '=', $user->id)
                ->where('memoryId', '=', $memory->id);
            if ($like->first() != null) {
                $this->userIsAuthorized($user, 'delete', $like->first());
                $like->delete();
            }
            return $this->sendResponse([], $responseHandler->words['LikeDeleteSuccessMessage']);  ///Needy Updated Successfully!
        } catch (MemoryNotFound $e) {
            return $this->sendError($responseHandler->words['MemoryNotFound']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['LikeDeletionForbiddenMessage']);
        }
    }
}