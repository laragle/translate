<?php

namespace Laragle\Translate\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class TranslationController extends Controller
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
                    'scope' => 'push-translations'
                ]
            ]);
        } catch (RequestException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true)['message'];
            throw new AuthorizationException($message);
        }

        return json_decode((string) $response->getBody(), true)['access_token'];
    }

    public function update(Request $request, Filesystem $file)
    {
        if (config('app.env') === 'production') {
            return;
        }

        $translations = Lang::getLoader()->load($request->locale, $request->group);
        
        $keys = explode('.', $request->key);
        $is_valid_key = true;

        collect($keys)->each(function ($key) use (&$is_valid_key){
            if ($key == '*' || is_numeric($key)) {
                $is_valid_key = false;
            }
        });

        if ($is_valid_key) {
            array_set($translations, $request->key, $request->value);
        } else {
            $child_key = collect($keys)->filter(function ($item, $key) {
                return $key > 0;
            })->implode('.');

            $translations[$keys[0]][$child_key] = $request->value;
        }

        $path = resource_path('lang') . '/' . $request->locale;

        if (! $file->exists($path)) {
            $file->makeDirectory($path);
        }

        $output = "<?php\n\nreturn " . var_export($translations, true) . ";\n";

        $file->put($path . '/' . $request->group . '.php', $output);
    }

    public function delete()
    {
        Artisan::call('laragle:pull-translations');
    }
}