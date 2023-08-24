<?php

namespace App\Http\Controllers\api\Ataa;

use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\StoreAtaaPrizeRequest;
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
            $this->userIsAuthorized($request->user, 'viewAny', AtaaPrize::class);

            return $this->sendResponse(
                AtaaPrize::select(
                    'id',
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
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeViewForbiddenMessage'));
        }
    }

    /**
     * Add Ataa Prize.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAtaaPrizeRequest $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', AtaaPrize::class);
            DB::beginTransaction();
            $this->preCreationPrizeChecker($request['level'], $request['action']);
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
                'from' => $request['from'] ?? Carbon::now(),
                'to' => $request['to'],
                'level' => $request['level'],
                //Has From? then compare -> less than then active, o.w wait for sql event to activate it || active
                'active' => $request['from'] ? ($request['from'] <= Carbon::now() ? 1 : 0) : 1,
            ]);
            DB::commit();

            return $this->sendResponse([], __('Ataa.PrizeCreationSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeCreateForbiddenMessage'));
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
    }

    /**
     * Activate Prize.
     *
     * @param  App\Models\Ataa\AtaaPrize  $prize
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, AtaaPrize $prize)
    {
        try {
            $this->userIsAuthorized($request->user, 'activate', $prize);
            $prize->activate();

            return $this->sendResponse([], __('Ataa.PrizeActivateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.PrizeActivateForbiddenMessage'));
        }
    }

    /**
     * Deactivate Prize.
     *
     * @param  App\Models\Ataa\AtaaPrize  $prize
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, AtaaPrize $prize)
    {
        try {
            $this->userIsAuthorized($request->user, 'deactivate', $prize);
            $prize->deactivate();

            return $this->sendResponse([], __('Ataa.PrizeDeactivateSuccessMessage'));
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
