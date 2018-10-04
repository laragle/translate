<?php

namespace Laragle\Translate\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Auth\Access\AuthorizationException;

class AuthController extends Controller
{
    public function token()
    {
        $client = new Client([
            'base_uri' => config('laragle.translate.api_url')
        ]);

        try {
            $response = $client->post('oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('laragle.translate.app_id'),
                    'client_secret' => config('laragle.translate.app_secret'),
                    'scope' => 'import-translations'
                ]
            ]);
        } catch (RequestException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true)['message'];
            throw new AuthorizationException($message);
        }

        return json_decode((string) $response->getBody(), true)['access_token'];
    }
}