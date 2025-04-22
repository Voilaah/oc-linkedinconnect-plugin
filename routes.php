<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use RainLab\User\Models\User;
// use Auth;
// use Log;

Route::get('/linkedin/redirect', function () {
    $clientId = env('LINKEDIN_CLIENT_ID');
    $redirectUri = url('/linkedin/callback');
    $scope = 'openid profile email';

    return Redirect::to('https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
        'response_type' => 'code',
        'client_id' => $clientId,
        'redirect_uri' => $redirectUri,
        'scope' => $scope,
    ]));
});

Route::get('/linkedin/callback', function () {
    if (request()->has('error')) {
        Log::error('LinkedIn OAuth error', request()->all());
        return response('LinkedIn error: ' . request()->get('error_description'), 400);
    }

    $code = request()->input('code');
    $clientId = env('LINKEDIN_CLIENT_ID');
    $clientSecret = env('LINKEDIN_CLIENT_SECRET');
    $redirectUri = url('/linkedin/callback');

    $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirectUri,
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
    ]);

    $responseBody = json_decode($response->body(), true);
    Log::info('LinkedIn token response', $responseBody);

    $accessToken = $responseBody['access_token'] ?? null;

    if (!$accessToken) {
        return response('Failed to obtain access token.', 400);
    }

    $headers = [
        'Authorization' => "Bearer $accessToken"
    ];

    // Fetch user info via OpenID Connect endpoint
    $userInfoResponse = Http::withHeaders($headers)->get('https://api.linkedin.com/v2/userinfo');
    $userInfo = json_decode($userInfoResponse->body(), true);

    trace_log($userInfo);

    $profileResponse = Http::withHeaders($headers)
        ->get('https://api.linkedin.com/v2/me');
    $profile = json_decode($profileResponse->body(), true);

    trace_log($profile);


    $user = Auth::getUser();
    if ($user && $userInfo) {
        $user->linkedin_id = $userInfo['sub'] ?? null;
        $user->first_name = $userInfo['given_name'] ?? $user->first_name;
        $user->last_name = $userInfo['family_name'] ?? $user->last_name;
        // $user->email = $userInfo['email'] ?? $user->email;
        $user->save();
    }

    return redirect('/account')->with('message', 'LinkedIn profile synced successfully.');
});
