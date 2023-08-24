<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\CreateMemoryRequest;
use App\Http\Requests\UpdateMemoryRequest;
use App\Http\Resources\MemoryPaginationResource;
use App\Http\Resources\MemoryResource;
use App\Models\MemoryWall\Memory;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MemoryController extends BaseController
{
    use UserValidator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ($request->user) {
                $this->userIsAuthorized($request->user, 'viewAny', Memory::class);

                return $this->sendResponse(
                    new MemoryPaginationResource(
                        Memory::select(
                            [
                                'id',
                                'person_name',
                                'birth_date',
                                'death_date',
                                'brief',
                                'nationality',
                                'life_story',
                                'image',
                                'created_at',
                                'created_by',
                                DB::raw('exists(select 1 from `likes` li where li.memory_id = id and li.user_id = \''.$request->user->id.'\' limit 1) as liked'),
                            ]
                        )->with('author')->withCount('likes as numberOfLikes')
                            ->where('nationality', '=', $request->user->getNationalityValue())
                            ->paginate(8)
                    ),
                    __('MemoryWall.MemoryIndexSuccess')
                );
            }

            return $this->sendResponse(
                new MemoryPaginationResource(
                    Memory::select(
                        [
                            'id',
                            'person_name',
                            'birth_date',
                            'death_date',
                            'brief',
                            'nationality',
                            'life_story',
                            'image',
                            'created_at',
                            'created_by',
                        ]
                    )->with('author')->withCount('likes as numberOfLikes')
                        ->paginate(8)
                ),
                __('MemoryWall.MemoryIndexSuccess')
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(
                __('MemoryWall.MemoryViewingBannedMessage')
            );
        }
    }

    /**
     * Display a listing of the top resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function getTopMemories(Request $request)
    {
        //ToDo: Get Based on Nationality
        return $this->sendResponse(
            MemoryResource::collection(Memory::select(
                [
                    'id',
                    'person_name',
                    'birth_date',
                    'death_date',
                    'brief',
                    'nationality',
                    'life_story',
                    'image',
                    'created_at',
                    'created_by',
                ]
            )->withCount('likes as numberOfLikes')
                ->orderBy('numberOfLikes', 'desc')
                ->take(3)->get()),
            __('MemoryWall.MemoryIndexSuccess')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateMemoryRequest $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', Memory::class);
            $imagePath = $request['image']->store('memories', 'public');
            $memory = $request->user->memories()->create([
                'person_name' => $request['personName'],
                'birth_date' => $request['birthDate'],
                'death_date' => $request['deathDate'],
                'brief' => $request['brief'],
                'life_story' => $request['lifeStory'],
                'image' => '/storage/'.$imagePath,
                'nationality' => $request->user->nationality,
            ]);

            return $this->sendResponse(MemoryResource::make($memory), __('MemoryWall.MemoryCreationSuccessMessage')); ///Thank You For Your Contribution!
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Memory $memory)
    {
        return $this->sendResponse(MemoryResource::make($memory), '');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemoryRequest $request, Memory $memory)
    {
        try {
            $this->userIsAuthorized($request->user, 'update', $memory);
            if ($request['image']) {
                Storage::delete('public/'.substr($memory->image, 9));
                $imagePath = $request['image']->store('memories', 'public');
            }
            $memory->update([
                'person_name' => $request['personName'] ?? $memory->personName,
                'birth_date' => $request['birthDate'] ?? $memory->birthDate,
                'death_date' => $request['deathDate'] ?? $memory->deathDate,
                'brief' => $request['brief'] ?? $memory->brief,
                'life_story' => $request['lifeStory'] ?? $memory->lifeStory,
                'image' => $request['image'] ? '/storage/'.$imagePath : $memory->image,
                'nationality' => $request->user->nationality,
            ]);

            return $this->sendResponse(MemoryResource::make($memory), __('MemoryWall.MemoryUpdateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryUpdateForbiddenMessage'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Memory $memory)
    {
        try {
            $this->userIsAuthorized($request->user, 'delete', $memory);
            if ($memory->image) {
                Storage::delete('public/'.substr($memory->image, 9));
            }
            $memory->delete();

            return $this->sendResponse([], __('MemoryWall.MemoryDeleteSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('MemoryWall.MemoryDeletionForbiddenMessage'));
        }
    }
}
