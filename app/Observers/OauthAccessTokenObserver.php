<?php

namespace App\Observers;

use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Cache;

class OauthAccessTokenObserver
{
    /**
     * Handle the OauthAccessToken "created" event.
     *
     * @param  \App\Models\OauthAccessToken  $oauthAccessToken
     * @return void
     */
    public function created(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "updated" event.
     *
     * @param  \App\Models\OauthAccessToken  $oauthAccessToken
     * @return void
     */
    public function updated(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "deleted" event.
     *
     * @param  \App\Models\OauthAccessToken  $oauthAccessToken
     * @return void
     */
    public function deleted(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "restored" event.
     *
     * @param  \App\Models\OauthAccessToken  $oauthAccessToken
     * @return void
     */
    public function restored(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "force deleted" event.
     *
     * @param  \App\Models\OauthAccessToken  $oauthAccessToken
     * @return void
     */
    public function forceDeleted(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }
}
