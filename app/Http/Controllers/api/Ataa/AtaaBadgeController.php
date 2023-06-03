<?php

namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\StoreAtaaBadgeRequest;
use App\Models\Ataa\AtaaBadge;
use App\Traits\ControllersTraits\UserValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtaaBadgeController extends BaseController
{
    use UserValidator;

    /**
     * Display a listing of the resource Acquired By User.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAcquired(Request $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', AtaaBadge::class);

            return $this->sendResponse(
                DB::table('ataa_badges')
                    ->leftJoin('user_ataa_acquired_badges', function ($join) use ($request) {
                        $join->on('ataa_badges.id', '=', 'user_ataa_acquired_badges.badge_id');
                        $join->where('user_ataa_acquired_badges.user_id', '=', $request->user->id);
                    })
                    ->select(
                        'ataa_badges.id as id',
                        'name',
                        'arabic_name',
                        'image',
                        'description',
                        'active',
                        DB::raw('user_ataa_acquired_badges.badge_id IS NOT NULL as acquired'),
                        DB::raw('user_ataa_acquired_badges.created_at as acquiredAt'),
                    )
                    ->get(),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeViewForbiddenMessage'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', AtaaBadge::class);

            return $this->sendResponse(
                AtaaBadge::select(
                    'id',
                    'name',
                    'arabic_name',
                    'image',
                    'description',
                    'active',
                )
                    ->get(),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeViewForbiddenMessage'));
        }
    }

    /**
     * Add Ataa Badge.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAtaaBadgeRequest $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', AtaaBadge::class);
            $imagePath = null;
            if ($request['image']) {
                $imagePath = $request['image']->store('ataa_badges', 'public');
                $imagePath = '/storage/'.$imagePath;
            }
            $badge = AtaaBadge::create([
                'id' => Str::uuid(),
                'name' => $request['name'],
                'arabic_name' => $request['arabic_name'],
                'image' => $imagePath,
                'description' => $request['description'],
                'active' => $request['active'] ? $request['active'] : 1,
            ]);

            return $this->sendResponse($badge, __('Ataa.BadgeCreationSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeCreateForbiddenMessage'));
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }

    /**
     * Activate Badge.
     *
     * @param  App\Models\Ataa\AtaaBadge  $badge
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, AtaaBadge $badge)
    {
        try {
            $this->userIsAuthorized($request->user, 'activate', $badge);
            $badge->activate();

            return $this->sendResponse([], __('Ataa.BadgeActivateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeActivateForbiddenMessage'));
        }
    }

    /**
     * Deactivate Badge.
     *
     * @param  App\Models\Ataa\AtaaBadge  $badge
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, AtaaBadge $badge)
    {
        try {
            $this->userIsAuthorized($request->user, 'deactivate', $badge);
            $badge->deactivate();

            return $this->sendResponse([], __('Ataa.BadgeDeactivateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeDeactivateForbiddenMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function show(AtaaBadge $ataaBadge)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AtaaBadge $ataaBadge)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function destroy(AtaaBadge $ataaBadge)
    {
        return $this->sendError('Not Implemented', '', 404);
    }
}
