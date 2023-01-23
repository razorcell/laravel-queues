<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Jobs\CalculateDataJob;
use Illuminate\Support\Facades\Log;
use App\Jobs\DownloadCSVFileJob;


class JobButton extends Component
{
    public function render()
    {
        return view('livewire.job-button');
    }

    public function runJob()
    {
        // Log::info("Job button clicked");
        // for ($x = 1; $x <= 2; $x++) {
        //     CalculateDataJob::dispatch($x);
        // }
        // $downloadFileJob = new DownloadCSVFileJob('myData.csv', 'https://raw.githubusercontent.com/amitkaps/multidim/master/data/pincode.csv');

        Log::info("dispatching downloadFileJob");
        // $downloadFileJob::dispatch();
        DownloadCSVFileJob::dispatch('myData.csv', 'https://raw.githubusercontent.com/amitkaps/multidim/master/data/pincode.csv');
        // DownloadCSVFileJob::dispatch('veryLarge.csv', 'https://github.com/Schlumberger/hackathon/raw/master/backend/dataset/data-large.csv');
    }
}
