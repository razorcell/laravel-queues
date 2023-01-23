<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\MyTools;


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
        $this->downUsingLaravelHttp();
    }

    function downUsingLaravelHttp()
    {
        $fullPath = storage_path('app') . DIRECTORY_SEPARATOR . $this->fileName;

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

        // Get Guzzle response stream handle
        $body = $response->toPsrResponse()->getBody();

        Log::debug('Stream size = ' . $body->getSize());
        
        // Read from the stream chunks of 500 KBytes, and write to file
        while (!$body->eof()) {
            $byte =  $body->read(500 * 1024);
            Storage::append($this->fileName, $byte);

            Log::debug(substr($byte, -20));
            Log::debug('memory ' . MyTools::getMemoryUsage());
        }

        // Close the stream handle
        $body->close();

        // Dispatch Reading the CSV Job
        ReadCSVFile::dispatch($fullPath);
    }
}
