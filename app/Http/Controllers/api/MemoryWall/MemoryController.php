<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Exceptions\MemoryNotFound;
use App\Http\Controllers\api\BaseController;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Models\MemoryWall\Memory;
use App\Traits\ControllersTraits\MemoryValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemoryController extends BaseController
{
    use UserValidator, MemoryValidator;

    public function __construct()
    {
        $this->middleware('api_auth')->except('index', 'getTopMemories');
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
            if ($request['userId']) {
                $user = $this->userExists($request['userId']);
                $this->userIsAuthorized($user, 'viewAny', Memory::class);
                return $this->sendResponse(
                    Memory::select(
                        [
                            'id',
                            'person_name',
                            'birth_date',
                            'death_date',
                            'brief',
                            'life_story',
                            'image',
                            'created_at',
                            DB::raw('CAST(DATEDIFF(death_date,birth_date) / 365 AS int) as age'),
                            DB::raw('exists(select 1 from `likes` li where li.memory_id = id and li.user_id = ' . $user->id . ' limit 1) as liked')
                        ]
                    )->withCount('likes as numberOfLikes')
                        ->where('nationality', '=', $user->nationality)
                        ->paginate(8),
                    __('MemoryWall.MemoryIndexSuccess')
                );
            }
            return $this->sendResponse(
                Memory::select(
                    [
                        'id',
                        'person_name',
                        'birth_date',
                        'death_date',
                        'brief',
                        'life_story',
                        'image',
                        'created_at',
                        DB::raw('CAST(DATEDIFF(death_date,birth_date) / 365 AS int) as age'),
                    ]
                )->withCount('likes as numberOfLikes')
                    ->paginate(8),
                __('MemoryWall.MemoryIndexSuccess')
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(
                __('MemoryWall.MemoryViewingBannedMessage')
            );
        }
    }

    /**
     * Display a listing of the top resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getTopMemories(Request $request)
    {
        //ToDo: Get Based on Nationality
        return $this->sendResponse(
            Memory::select(
                [
                    'id',
                    'person_name',
                    'birth_date',
                    'death_date',
                    'brief',
                    'life_story',
                    'image',
                    'created_at',
                    DB::raw('CAST(DATEDIFF(death_date,birth_date) / 365 AS int) as age'),
                ]
            )->withCount('likes as numberOfLikes')
                ->orderBy('numberOfLikes', 'desc')
                ->take(3)->get(),
            __('MemoryWall.MemoryIndexSuccess')
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
            $user = $this->userExists($request['createdBy']);
            //Validate Request
            $validated = $this->validateMemory($request, 'store');
            if ($validated->fails()) {
                return $this->sendError(
                    __('General.InvalidData'),
                    $validated->messages(),
                    400
                );   ///Invalid data
            }
            $this->userIsAuthorized($user, 'create', Memory::class);
            $imagePath = $request['image']->store('memories', 'public');
            $user->memories()->create([
                'id' => Str::uuid(),
                'person_name' => $request['personName'],
                'birth_date' => $request['birthDate'],
                'death_date' => $request['deathDate'],
                'brief' => $request['brief'],
                'life_story' => $request['lifeStory'],
                'image' => "/storage/" . $imagePath,
                'nationality' => $user->nationality,
            ]);
            return $this->sendResponse([], __('MemoryWall.MemoryCreationSuccessMessage')); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  String  $id
     * @return \Illuminate\Http\Response
     */
    public function show(String $id)
    {
        try {
            $memory = $this->memoryExists($id);
            return $this->sendResponse(collect([$memory])->map(function ($memory) {
                return [
                    'id' => $memory->id,
                    'person_name' => $memory->id,
                    'birth_date' => $memory->birthDate,
                    'death_date' => $memory->deathDate,
                    'brief' => $memory->brief,
                    'life_story' => $memory->lifeStory,
                    'image' => $memory->image,
                    'age' => date_diff(date_create($memory->deathDate), date_create($memory->birthDate))->y,
                ];
            })->first(), "");
        } catch (MemoryNotFound $e) {
            return $this->sendError(__('MemoryWall.MemoryNotFound'));
        }
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
        try {
            //Validate Request
            $validated = $this->validateMemory($request, 'update');
            if ($validated->fails()) {
                return $this->sendError(
                    __('General.InvalidData'),
                    $validated->messages(),
                    400
                );
            }
            $memory = $this->memoryExists($id);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'update', $memory);
            if ($request['image']) {
                Storage::delete('public/' . substr($memory->image, 9));
                $imagePath = $request['image']->store('memories', 'public');
            }
            $memory->update([
                'person_name' => $request['personName'] ?? $memory->personName,
                'birth_date' => $request['birthDate'] ?? $memory->birthDate,
                'death_date' => $request['deathDate'] ?? $memory->deathDate,
                'brief' => $request['brief'] ?? $memory->brief,
                'life_story' => $request['lifeStory'] ?? $memory->lifeStory,
                'image' => $request['image'] ? "/storage/" . $imagePath : $memory->image,
                'nationality' => $user->nationality,
            ]);
            return $this->sendResponse([], __('MemoryWall.MemoryUpdateSuccessMessage'));
        } catch (MemoryNotFound $e) {
            return $this->sendError(__('MemoryWall.MemoryNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryUpdateForbiddenMessage'));
        }
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
            $this->userIsAuthorized($user, 'delete', $memory);
            if ($memory->image)
                Storage::delete('public/' . substr($memory->image, 9));
            $memory->delete();
            return $this->sendResponse([], __('MemoryWall.MemoryDeleteSuccessMessage'));
        } catch (MemoryNotFound $e) {
            return $this->sendError(__('MemoryWall.MemoryNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryDeletionForbiddenMessage'));
        }
    }
}
