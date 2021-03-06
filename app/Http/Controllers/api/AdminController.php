<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Needy;
use App\Models\OfflineTransaction;
use App\Models\OnlineTransaction;
use App\Models\Pet;

class AdminController extends BaseController
{

    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function generalAdminDashboard(Request $request)
    {
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }
        //ToDo: Check privilige
        $data = array();
        $generalData = array();
        $ahedData = array();
        $breedmeData = array();
        $numberOfUsers = User::all()->count();
        $numberOfNeedies = Needy::all()->count();
        $numberOfNeediesSatisfied = Needy::all()->where('satisfied', '=', true)->count();
        $numberOfTransactions = OnlineTransaction::all()->count() + OfflineTransaction::all()->where('collected', '=', true)->count();
        $givesCollected = OnlineTransaction::all()->sum('amount') + OfflineTransaction::all()->where('collected', '=', true)->sum('amount');
        $numberOfPets = Pet::all()->count();
        array_push($generalData,[
            'NumberOfActiveUsers' => $numberOfUsers,
        ]);
        array_push($ahedData,[
            'NumberOfNeedies' => $numberOfNeedies,
            'NumberOfNeediesSatisfied' => $numberOfNeediesSatisfied,
            'NumberOfTransactions' => $numberOfTransactions,
            'NumberOfGives' => $givesCollected,
        ]);
        array_push($breedmeData,[
            'NumberOfPets' => $numberOfPets
        ]);
        array_push($data, [
            'General' => $generalData,
            'Ahed' => $ahedData,
            'BreedMe' => $breedmeData,
        ]);
        return $this->sendResponse($data, 'Data Retrieved Successfully!');
    }
    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can approve
        if (!$user->can('approve', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to approve this needy.');
        }
        $needy->approve();
        return $this->sendResponse([], 'Needy Approved Successfully!');
    }
    /**
     * Disapprove the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can disapprove
        if (!$user->can('disapprove', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to disapprove this needy.');
        }
        $needy->disapprove();
        return $this->sendResponse([], 'Needy Disapprove Successfully!');
    }
    /**
     * Approve the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function collectOfflineTransaction(Request $request)
    {
        //Check needy exists
        $offlinetransaction = OfflineTransaction::find($request['transactionId']);
        if ($offlinetransaction == null) {
            return $this->sendError('Transaction Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can approve
        if (!$user->can('collect', $offlinetransaction)) {
            return $this->sendForbidden('You aren\'t authorized to collect this transaction.');
        }
        $offlinetransaction->collect();
        return $this->sendResponse([], 'Transaction Collected Successfully!');
    }
}
