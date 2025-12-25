<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\ElasticService;
use App\Jobs\SendSearchNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = trim($request->get('q'));

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $cacheKey = 'elastic_search_' . md5($keyword);

        $results = Cache::remember($cacheKey, 300, function () use ($keyword) {

            try {
                //  Try ElasticSearch first
                $client = ElasticService::client();

                $response = $client->search([
                    'index' => 'people',
                    'size'  => 20,
                    'body'  => [
                        'query' => [
                            'multi_match' => [
                                'query'  => $keyword,
                                'fields' => ['name', 'city', 'email']
                            ]
                        ]
                    ]
                ]);

                return collect($response['hits']['hits'])
                    ->pluck('_source');

            } catch (\Exception $e) {

                // Fallback to MySQL if Elastic fails
                Log::error('Elastic failed, fallback to DB: ' . $e->getMessage());

                return Person::where('name', 'like', "%{$keyword}%")
                    ->orWhere('email', 'like', "%{$keyword}%")
                    ->orWhere('city', 'like', "%{$keyword}%")
                    ->limit(20)
                    ->get();
            }
        });

        //  Send admin notification via QUEUE (non-blocking)
        SendSearchNotificationJob::dispatch($keyword);

        return response()->json($results);
    }
}
