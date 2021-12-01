<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Helpers\ResponseHandler;
use App\Models\Memory;
use App\Traits\ControllersTraits\MemoryValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class MemoryController extends BaseController
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
            //ToDo: return wether the memory was liked or not
            $responseHandler = new ResponseHandler($request['language']);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', Memory::class);
            return $this->sendResponse(
                Memory::select(
                    [
                        'id',
                        'personName',
                        'birthDate',
                        'deathDate',
                        'lifeStory',
                        'image',
                        DB::raw('DATEDIFF(deathDate,birthDate) / 365 as age')
                    ]
                )
                    ->where('nationality', '=', $user->nationality)
                    ->paginate(8),
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
            $user = $this->userExists($request['createdBy']);
            //Validate Request
            $validated = $this->validateMemory($request, 'store');
            if ($validated->fails()) {
                return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);   ///Invalid data
            }
            $this->userIsAuthorized($user, 'create', Memory::class);
            $imagePath = $request['image']->store('memories', 'public');
            $user->memories()->create([
                'personName' => $request['personName'],
                'birthDate' => $request['birthDate'],
                'deathDate' => $request['deathDate'],
                'lifeStory' => $request['lifeStory'],
                'image' => "/storage/" . $imagePath,
                'nationality' => $user->nationality,
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
