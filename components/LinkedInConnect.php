<?php
namespace Voilaah\LinkedInConnect\Components;

use Cms\Classes\ComponentBase;
use Redirect;

/**
 * LinkedInConnect Component
 *
 * @link https://docs.octobercms.com/3.x/extend/cms-components.html
 */
class LinkedInConnect extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Linked In Connect Component',
            'description' => 'No description provided yet...'
        ];
    }

    /**
     * @link https://docs.octobercms.com/3.x/element/inspector-types.html
     */
    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        // Nothing needed on load yet
    }

    public function onConnectLinkedIn()
    {
        $clientId = env('LINKEDIN_CLIENT_ID');
        $redirectUri = url('/linkedin/callback');
        $scope = 'openid profile email';

        return Redirect::to('https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => $scope,
        ]));
    }
}
