<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ElasticService;

class CreatePersonIndex extends Command
{
    protected $signature = 'elastic:create-index';
    protected $description = 'Create person index';

    public function handle()
    {
        $client = ElasticService::client();

        if ($client->indices()->exists(['index' => 'people'])->asBool()) {
            $this->info('Index already exists');
            return;
        }

        $client->indices()->create([
            'index' => 'people',
            'body' => [
                'mappings' => [
                    'properties' => [
                        'name'  => ['type' => 'text'],
                        'email' => ['type' => 'keyword'],
                        'city'  => ['type' => 'text'],
                    ]
                ]
            ]
        ]);

        $this->info('People index created');
    }
}
