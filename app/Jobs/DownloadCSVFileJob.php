<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Storage;


class DownloadCSVFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileName;
    public $url;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileName, $url)
    {
        $this->fileName = $fileName;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->downUsingLaravel();
    }



    function downUsingLaravel()
    {
        $response = Http::withOptions([
            [
                'debug' => true,
                'stream' => true,
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/109.0',
                    'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                    'Accept-Language' => 'en-US,en;q=0.5',
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Connection' => 'keep-alive',
                    'Upgrade-Insecure-Requests' => '1',
                    'Sec-Fetch-Dest' => 'document',
                    'Sec-Fetch-Mode' => 'navigate',
                    'Sec-Fetch-Site' => 'none',
                    'Sec-Fetch-User' => '?1',
                    'Sec-GPC' => '1',
                    'Pragma' => 'no-cache',
                    'Cache-Control' => 'no-cache',
                ]
            ]
        ])->get($this->url);

        $fullPath = storage_path('app') . DIRECTORY_SEPARATOR . $this->fileName;
        $body = $response->toPsrResponse()->getBody();
        Log::debug('Stream size = ' . $body->getSize());
        while (!$body->eof()) {
            $byte =  $body->read(500 * 1024);
            Log::debug(substr($byte, -20));
            Log::debug('memory ' . getMemoryUsage());
            Storage::append($this->fileName, $byte);
        }

        $body->close();

        ReadCSVFile::dispatch($fullPath);
    }
    function downUsingGuzzle() // Not working
    {
        $client = new Client();
        $response = $client->request('GET', $this->url, [
            'stream' => true,
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:109.0) Gecko/20100101 Firefox/109.0',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
                'Accept-Language' => 'en-US,en;q=0.5',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Connection' => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
                'Sec-Fetch-Dest' => 'document',
                'Sec-Fetch-Mode' => 'navigate',
                'Sec-Fetch-Site' => 'none',
                'Sec-Fetch-User' => '?1',
                'Sec-GPC' => '1',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'no-cache',
            ]
        ]);

        $body = $response->getBody();
        $i = 1;
        Log::debug('Stream size = ' . $body->getSize());
        while (!$body->eof()) {
            $byte =  $body->read(2000 * 1024);
            Log::debug($i . '=' . substr($byte, -20));
            Log::debug('memory ' . getMemoryUsage());
            $i++;
        }

        Log::debug('after while loop ------------------------->>>>>>END');
        $body->close();

        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK

        Log::debug('code & reason', [$code, $reason]);
    }
}
