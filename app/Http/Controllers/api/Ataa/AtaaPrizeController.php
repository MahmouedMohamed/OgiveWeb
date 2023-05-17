<?php

namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\AtaaPrizeCreationActionNotFound;
use App\Exceptions\AtaaPrizeNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Models\Ataa\AtaaPrize;
use App\Traits\ControllersTraits\AtaaPrizeValidator;
use App\Traits\ControllersTraits\UserValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AtaaPrizeController extends BaseController
{
    use UserValidator, AtaaPrizeValidator;

    /**
     * Display a listing of the resource Acquired By User.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAcquired(Request $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'viewAny', AtaaPrize::class);

            return $this->sendResponse(
                DB::table('ataa_prizes')
                    ->leftJoin('user_ataa_acquired_prizes', function ($join) use ($request) {
                        $join->on('ataa_prizes.id', 'user_ataa_acquired_prizes.prize_id');
                        $join->where('user_ataa_acquired_prizes.user_id', $request->user->id);
                    })
                    ->select(
                        'ataa_prizes.id as id',
                        'name',
                        'arabic_name',
                        'image',
                        'required_markers_collected',
                        'required_markers_posted',
                        'from',
                        'to',
                        'active',
                        'level',
                        DB::raw('user_ataa_acquired_prizes.prize_id IS NOT NULL as acquired'),
                        DB::raw('user_ataa_acquired_prizes.created_at as acquiredAt'),
                    )
                    ->orderBy('level', 'ASC')->get(),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeViewForbiddenMessage'));
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
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', AtaaPrize::class);

            return $this->sendResponse(
                AtaaPrize::select(
                    'id as ataaPrizeId',
                    'name',
                    'arabic_name',
                    'image',
                    'required_markers_collected',
                    'required_markers_posted',
                    'from',
                    'to',
                    'active',
                    'level',
                )->orderBy('level', 'ASC')
                    ->get(),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeViewForbiddenMessage'));
        }
    }

    /**
     * Add Ataa Prize.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $admin = $this->userExists($request['userId']);
            $this->userIsAuthorized($admin, 'create', AtaaPrize::class);
            $validated = $this->validatePrize($request);
            if ($validated->fails()) {
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
            }
            $this->preCreationPrizeChecker($request);
            $imagePath = null;
            if ($request['image']) {
                $imagePath = $request['image']->store('ataa_prizes', 'public');
                $imagePath = '/storage/'.$imagePath;
            }
            AtaaPrize::create([
                'id' => Str::uuid(),
                'createdBy' => $request['userId'],
                'name' => $request['name'],
                'arabic_name' => $request['arabic_name'],
                'image' => $imagePath,
                'required_markers_collected' => $request['required_markers_collected'],
                'required_markers_posted' => $request['required_markers_posted'],
                'from' => $request['from'] ?? Carbon::now('GMT+2'),
                'to' => $request['to'],
                'level' => $request['level'],
                //Has From? then compare -> less than then active, o.w wait for sql event to activate it || active
                'active' => $request['from'] ? ($request['from'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
            ]);

            return $this->sendResponse([], __('Ataa.PrizeCreationSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeCreateForbiddenMessage'));
        } catch (AtaaPrizeCreationActionNotFound $e) {
            return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }

    /**
     * Activate Prize.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id)
    {
        try {
            $user = $this->userExists($request['userId']);
            $prize = $this->prizeExists($id);
            $this->userIsAuthorized($user, 'activate', $prize);
            $prize->activate();

            return $this->sendResponse([], __('Ataa.PrizeActivateSuccessMessage'));
        } catch (AtaaPrizeNotFound $e) {
            return $this->sendError(__('Ataa.PrizeNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeActivateForbiddenMessage'));
        }
    }

    /**
     * Deactivate Prize.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        try {
            $user = $this->userExists($request['userId']);
            $prize = $this->prizeExists($id);
            $this->userIsAuthorized($user, 'deactivate', $prize);
            $prize->deactivate();

            return $this->sendResponse([], __('Ataa.PrizeDeactivateSuccessMessage'));
        } catch (AtaaPrizeNotFound $e) {
            return $this->sendError(__('Ataa.PrizeNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeDeactivateForbiddenMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function show(AtaaPrize $ataaPrize)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AtaaPrize $ataaPrize)
    {
        return $this->sendError('Not Implemented', '', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function destroy(AtaaPrize $ataaPrize)
    {
        return $this->sendError('Not Implemented', '', 404);
    }
}
