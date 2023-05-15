<?php

namespace App\Observers;

use App\Models\OauthAccessToken;
use Illuminate\Support\Facades\Cache;

class OauthAccessTokenObserver
{
    /**
     * Handle the OauthAccessToken "created" event.
     *
     * @return void
     */
    public function created(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "updated" event.
     *
     * @return void
     */
    public function updated(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "deleted" event.
     *
     * @return void
     */
    public function deleted(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "restored" event.
     *
     * @return void
     */
    public function restored(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }

    /**
     * Handle the OauthAccessToken "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(OauthAccessToken $oauthAccessToken)
    {
        return Cache::forget('oauthAccessTokens');
    }
}
