<?php


namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\AtaaBadgeNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Models\Ataa\AtaaBadge;
use Illuminate\Http\Request;
use App\Traits\ControllersTraits\AtaaBadgeValidator;
use App\Traits\ControllersTraits\UserValidator;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtaaBadgeController extends BaseController
{
    use UserValidator, AtaaBadgeValidator;
    /**
     * Display a listing of the resource Acquired By User.
     *
     * @param  \Illuminate\Http\Request  $request
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', AtaaBadge::class);
            return $this->sendResponse(
                AtaaBadge::select(
                    'id as ataaBadgeId',
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $admin = $this->userExists($request['userId']);
            $this->userIsAuthorized($admin, 'create', AtaaBadge::class);
            $validated = $this->validateBadge($request);
            if ($validated->fails())
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
            $imagePath = null;
            if ($request['image']) {
                $imagePath = $request['image']->store('ataa_badges', 'public');
                $imagePath = "/storage/" . $imagePath;
            }
            AtaaBadge::create([
                'id' => Str::uuid(),
                'name' => $request['name'],
                'arabic_name' => $request['arabic_name'],
                'image' => $imagePath,
                'description' => $request['description'],
                'active' => $request['active'] ? $request['active'] : 1,
            ]);
            return $this->sendResponse([], __('Ataa.BadgeCreationSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeCreateForbiddenMessage'));
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }

    /**
     * Activate Badge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id)
    {
        try {
            $user = $this->userExists($request['userId']);
            $badge = $this->badgeExists($id);
            $this->userIsAuthorized($user, 'activate', $badge);
            $badge->activate();
            return $this->sendResponse([], __('Ataa.BadgeActivateSuccessMessage'));
        } catch (AtaaBadgeNotFound $e) {
            return $this->sendError(__('Ataa.BadgeNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.BadgeActivateForbiddenMessage'));
        }
    }
    /**
     * Deactivate Badge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        try {
            $user = $this->userExists($request['userId']);
            $badge = $this->badgeExists($id);
            $this->userIsAuthorized($user, 'deactivate', $badge);
            $badge->deactivate();
            return $this->sendResponse([], __('Ataa.BadgeDeactivateSuccessMessage'));
        } catch (AtaaBadgeNotFound $e) {
            return $this->sendError(__('Ataa.BadgeNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
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
     * @param  \Illuminate\Http\Request  $request
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
