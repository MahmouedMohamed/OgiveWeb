<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Exceptions\MemoryNotFound;
use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Helpers\ResponseHandler;
use App\Models\Memory;
use App\Traits\ControllersTraits\MemoryValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemoryController extends BaseController
{
    use UserValidator, MemoryValidator;

    public function __construct() {
        $this->middleware('api_auth')->except('index','getTopMemories');
    }

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
            if ($request['userId']) {
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
                            'created_at',
                            DB::raw('CAST(DATEDIFF(deathDate,birthDate) / 365 AS int) as age'),
                            DB::raw('exists(select 1 from `likes` li where li.memoryId = id and li.userId = ' . $user->id . ' limit 1) as liked')
                        ]
                    )->withCount('likes')
                        ->where('nationality', '=', $user->nationality)
                        ->paginate(8),
                    ''
                );
            }
            return $this->sendResponse(
                Memory::select(
                    [
                        'id',
                        'personName',
                        'birthDate',
                        'deathDate',
                        'lifeStory',
                        'image',
                        'created_at',
                        DB::raw('CAST(DATEDIFF(deathDate,birthDate) / 365 AS int) as age'),
                    ]
                )->withCount('likes')
                    ->paginate(8),
                ''
            );
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['MemoryViewingBannedMessage']);
        }
    }
    
    public function getTopMemories(Request $request){
        $responseHandler = new ResponseHandler($request['language']);
        return $this->sendResponse(
            Memory::select(
                [
                    'id',
                    'personName',
                    'birthDate',
                    'deathDate',
                    'lifeStory',
                    'image',
                    'created_at',
                    DB::raw('CAST(DATEDIFF(deathDate,birthDate) / 365 AS int) as age'),
                ]
            )->withCount('likes')
            ->orderBy('likes_count', 'desc')
                ->take(3)->get(),
            ''
        );
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
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $responseHandler = new ResponseHandler($request['language']);
            $memory = $this->memoryExists($id);
            return $this->sendResponse(collect([$memory])->map(function ($memory) {
                return [
                    'id' => $memory->id,
                    'personName' => $memory->id,
                    'birthDate' => $memory->birthDate,
                    'deathDate' => $memory->deathDate,
                    'lifeStory' => $memory->lifeStory,
                    'image' => $memory->image,
                    'age' => date_diff(date_create($memory->deathDate), date_create($memory->birthDate))->y,
                ];
            })->first(), "");
        } catch (MemoryNotFound $e) {
            return $this->sendError($responseHandler->words['MemoryNotFound']);
        }
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
        try {
            $responseHandler = new ResponseHandler($request['language']);
            //Validate Request
            $validated = $this->validateMemory($request, 'update');
            if ($validated->fails()) {
                return $this->sendError($responseHandler->words['InvalidData'], $validated->messages(), 400);
            }
            $memory = $this->memoryExists($id);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'update', $memory);
            if ($request['image']) {
                Storage::delete('public/' . substr($memory->image, 9));
                $imagePath = $request['image']->store('memories', 'public');
            }
            $memory->update([
                'personName' => $request['personName'] ?? $memory->personName,
                'birthDate' => $request['birthDate'] ?? $memory->birthDate,
                'deathDate' => $request['deathDate'] ?? $memory->deathDate,
                'lifeStory' => $request['lifeStory'] ?? $memory->lifeStory,
                'image' => $request['image'] ? "/storage/" . $imagePath : $memory->image,
                'nationality' => $user->nationality,
            ]);
            return $this->sendResponse([], $responseHandler->words['MemoryUpdateSuccessMessage']);
        } catch (MemoryNotFound $e) {
            return $this->sendError($responseHandler->words['MemoryNotFound']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['MemoryUpdateForbiddenMessage']);
        }
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
            $this->userIsAuthorized($user, 'delete', $memory);
            if ($memory->image)
                Storage::delete('public/' . substr($memory->image, 9));
            $memory->delete();
            return $this->sendResponse([], $responseHandler->words['MemoryDeleteSuccessMessage']);  ///Needy Updated Successfully!
        } catch (MemoryNotFound $e) {
            return $this->sendError($responseHandler->words['MemoryNotFound']);
        } catch (UserNotFound $e) {
            return $this->sendError($responseHandler->words['UserNotFound']);
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($responseHandler->words['MemoryDeletionForbiddenMessage']);
        }
    }
}
