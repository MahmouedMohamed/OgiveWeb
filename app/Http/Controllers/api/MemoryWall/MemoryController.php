<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Helpers\ResponseHandler;
use App\Models\Memory;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MemoryController extends BaseController
{
    use UserValidator;
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
            $currentPage = request()->get('page', 1);
            $this->userIsAuthorized($user, 'viewAny', Memory::class);
            return $this->sendResponse(
                Cache::remember('memories - ' . $currentPage, 60 * 60 * 24, function () use ($user) {
                    return
                        Memory::select(
                            [
                                'id',
                                'personName',
                                'birthDate',
                                'deathDate',
                                'lifeStory',
                                'image'
                            ]
                        )
                        ->where('nationality', '=', $user->nationality)
                        ->paginate(8);
                }),
                ''
            );
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['MemoryViewingBannedMessage']);
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
            //Validate Request
            $validated = $this->validateMemory($request, 'store');
            if ($validated->fails()) {
                return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);   ///Invalid data
            }
            $user = $this->userExists($request['createdBy']);
            $this->userIsAuthorized($user, 'create', Memory::class);
            $imagePath = $request['image']->store('memories', 'public');
            $user->memories()->create([
                'person_name' => $request['personName'],
                'birth' => $request['birthDate'],
                'death' => $request['deathDate'],
                'life_story' => $request['lifeStory'],
                'image' => "/storage/" . $imagePath,
            ]);
            return $this->sendResponse([], $responseHandler->words['MemoryCreationSuccessMessage']); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['MemoryCreationBannedMessage']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function show(Memory $memory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Memory $memory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Memory  $memory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Memory $memory)
    {
        //
    }
}
