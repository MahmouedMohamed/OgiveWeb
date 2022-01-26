<?php


namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\AtaaBadgeNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Models\Ataa\AtaaBadge;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\ControllersTraits\AtaaBadgeValidator;
use App\Traits\ControllersTraits\UserValidator;
use Exception;
use Illuminate\Support\Facades\DB;

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
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', AtaaBadge::class);
            return $this->sendResponse(
                DB::table('ataa_badges')
                    ->leftJoin('user_ataa_acquired_badges', function ($join) use ($user) {
                        $join->on('ataa_badges.id', '=', 'user_ataa_acquired_badges.badge_id');
                        $join->on('user_ataa_acquired_badges.user_id', '=', DB::raw($user->id));
                    })
                    ->select(
                        'ataa_badges.id as id',
                        'name',
                        'image',
                        'description',
                        'active',
                        DB::raw('user_ataa_acquired_badges.badge_id IS NOT NULL as acquired'),
                        DB::raw('user_ataa_acquired_badges.created_at as acquiredAt'),
                    )
                    ->get(),
                'Ataa Badges Retrieved Successfully'
            );
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to view these badges.');
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
                    'image',
                    'description',
                    'active',
                )
                    ->get(),
                'Ataa Badges Retrieved Successfully'
            );
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to view these badges.');
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
                return $this->sendError('Invalid data', $validated->messages(), 400);
            $imagePath = null;
            if ($request['image']) {
                $imagePath = $request['image']->store('ataa_badges', 'public');
                $imagePath = "/storage/" . $imagePath;
            }
            AtaaBadge::create([
                'name' => $request['name'],
                'image' => $imagePath,
                'description' => $request['description'],
                'active' => $request['active'] ? $request['active'] : 1,
            ]);
            return $this->sendResponse([], 'Ataa Badge Created Successfully!');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to create a Badge.');
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
            return $this->sendResponse([], 'Badge Activated Successfully!');
        } catch (AtaaBadgeNotFound $e) {
            return $this->sendError('Badge Not Found');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to activate this badge.');
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
            return $this->sendResponse([], 'Badge Deactivated Successfully!');
        } catch (AtaaBadgeNotFound $e) {
            return $this->sendError('Badge Not Found');
        } catch (UserNotFound $e) {
            return $this->sendError('User Not Found');
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('You aren\'t authorized to deactivate this badge.');
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
