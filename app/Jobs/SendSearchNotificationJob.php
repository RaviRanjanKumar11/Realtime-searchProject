<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendSearchNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $keyword;

    // Retry settings
    public int $tries = 3;
    public int $timeout = 30;

    public function __construct(string $keyword)
    {
        $this->keyword = $keyword;
    }

    public function handle(): void
    {
        Mail::raw(
            "Search keyword used: {$this->keyword}",
            function ($message) {
                $message->to('admin@realtimesearch.com')
                        ->subject('Search Keyword Alert');
            }
        );
    }

    // Called if job fails after retries
    public function failed(\Throwable $exception): void
    {
        Log::error(
            'Search mail job failed',
            [
                'keyword' => $this->keyword,
                'error' => $exception->getMessage()
            ]
        );
    }
}
