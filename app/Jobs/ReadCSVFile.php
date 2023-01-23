<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\MyTools;

class ReadCSVFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fullPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fullPath)
    {
        $this->fullPath = $fullPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("Reading file: " . $this->fullPath);

        // Get a pointer to an output stream for the CSV file
        $file_handle = fopen($this->fullPath, 'r');

        $count = 0;
        // Read the fie line by line
        foreach (MyTools::get_all_lines($file_handle) as $line) {
            $count += 1;
            if ($count % 1000 == 0) echo $count . ". " . $line;
        }

        fclose($file_handle);

        Log::info("Finished Reading file: " . $this->fullPath . " : MaxRAM = " . MyTools::getMemoryUsage());
    }
}
