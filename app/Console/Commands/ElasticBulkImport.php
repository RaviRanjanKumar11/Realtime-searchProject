<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Person;
use App\Services\ElasticService;

class ElasticBulkImport extends Command
{
    protected $signature = 'elastic:import';
    protected $description = 'Import people to ElasticSearch';

  public function handle()
{
    $client = ElasticService::client();

    $this->info('Starting ElasticSearch Import...');

    //  LIMIT FOR LOCAL DEMO
    $limit = 100000;

    $total = Person::where('id', '<=', $limit)->count();
    $this->info("Total records (limited): {$total}");

    $bar = $this->output->createProgressBar($total);
    $bar->start();

    Person::where('id', '<=', $limit)
        ->orderBy('id')
        ->chunk(1000, function ($people) use ($client, $bar) {

            $params = ['body' => []];

            foreach ($people as $person) {
                $params['body'][] = [
                    'index' => [
                        '_index' => 'people',
                        '_id' => $person->id
                    ]
                ];

                $params['body'][] = [
                    'name'  => $person->name,
                    'email' => $person->email,
                    'city'  => $person->city,
                ];

                $bar->advance();
            }

            $client->bulk($params);
        });

    $bar->finish();
    $this->newLine();
    $this->info('ElasticSearch import COMPLETED ');
}

}
