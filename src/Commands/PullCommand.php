<?php

namespace Laragle\Translate\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PullCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laragle:pull-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull translations from https://translate.laragle.com';

    protected $files;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
            $this->error($message);
            $this->error('Kindly check the following environment variables in your .env file.');
            $this->error('LARAGLE_TRANSLATE_APP_ID');
            $this->error('LARAGLE_TRANSLATE_APP_SECRET');
            return;
        }

        $access_token = json_decode((string) $response->getBody(), true)['access_token'];

        try {
            $response = $client->get('client/'.config('laragle.translate.app_id').'/language/translations/export', [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$access_token
                ]
            ]);

            collect(json_decode((string) $response->getBody(), true))->each(function ($item) {
                $locale = $item['locale'];

                collect($item['translations'])->each(function ($item, $key) use ($locale) {
                    $filtered = collect($item)->filter(function ($item) {
                        return !is_null($item['value']);
                    });

                    if ($filtered->count()) {
                        $translations = $filtered->map(function ($item) {
                            $temp = [];
                            array_set($temp, $item['key'], $item['value']);
                            return $temp;
                        })->collapse()->all();
                        
                        $path = resource_path('lang') . '/' . $locale;

                        if (! $this->files->exists($path)) {
                            $this->files->makeDirectory($path);
                        }

                        $output = "<?php\n\nreturn " . var_export($translations, true) . ";\n";
                        $this->files->put($path . '/' . $key . '.php', $output);
                    }
                });                
            });

            $this->info('Success!');
        } catch (RequestException $e) {
            $message = json_decode($e->getResponse()->getBody()->getContents(), true)['message'];
            $this->error($message);
        }
    }
}