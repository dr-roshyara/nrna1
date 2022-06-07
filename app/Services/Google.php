<?php
namespace App\Services;
use App\Models\Calendar;
use App\Models\GoogleAccount;
class Google
{

    protected $client;

    function __construct()
    {
        $_wd_credential    = storage_path('credentials/');
        $tokenPath         = $_wd_credential."google_token.json";
        $json_file_name     =$_wd_credential.'client_secret_roshyara_local.json';
        //start here
         $this->google_login();
        // $this->client =$this->getClient($json_file_name,$tokenPath);
    }
    public function google_login(){

         $client = new \Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));
        $client->setRedirectUri(config('services.google.redirect_uri'));
        $client->setScopes(config('services.google.scopes'));
        $client->setApprovalPrompt(config('services.google.approval_prompt'));
        $client->setAccessType(config('services.google.access_type'));
        $client->setIncludeGrantedScopes(config('services.google.include_granted_scopes'));
        $this->client = $client;
    }
    public function getClient($credential_file, $tokenPath)
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Calendar API PHP Quickstart');
        $client->setScopes(config('services.google.scopes'));
        $client->setAuthConfig($credential_file);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(config('services.google.redirect_uri'));
        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        // $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
}


    public function connectUsing($token)
    {
        $this->client->setAccessToken($token);

        return $this;
    }

    public function connectWithSynchronizable($synchronizable)
    {
        $token = $this->getTokenFromSynchronizable($synchronizable);

        return $this->connectUsing($token);
    }

    protected function getTokenFromSynchronizable($synchronizable)
    {
        switch (true) {
            case $synchronizable instanceof GoogleAccount:
                return $synchronizable->token;

            case $synchronizable instanceof Calendar:
                return $synchronizable->googleAccount->token;

            default:
                throw new \Exception("Invalid Synchronizable");
        }
    }

    public function revokeToken($token = null)
    {
        $token = $token ?? $this->client->getAccessToken();

        return $this->client->revokeToken($token);
    }

    public function service($service)
    {
        $classname = "Google_Service_$service";

        return new $classname($this->client);
    }

    public function __call($method, $args)
    {
        if (! method_exists($this->client, $method)) {
            throw new \Exception("Call to undefined method '{$method}'");
        }

        return call_user_func_array([$this->client, $method], $args);
    }
}

