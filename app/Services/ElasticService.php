<?php

namespace App\Services;

use Elastic\Elasticsearch\ClientBuilder;

class ElasticService
{
    public static function client()
    {
        return ClientBuilder::create()
            ->setHosts(['localhost:9200'])
            ->build();
    }
}
