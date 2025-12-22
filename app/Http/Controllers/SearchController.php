<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Services\ElasticService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
            // 🔥 ElasticSearch FIRST
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

            // ❗ Fallback to MySQL (VERY IMPRESSIVE)
            Log::error('Elastic failed, fallback to DB: ' . $e->getMessage());

            return Person::where('name', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('city', 'like', "%{$keyword}%")
                ->limit(20)
                ->get();
        }
    });

    // ✅ Admin notification (safe)
    try {
        Mail::raw(
            "Search keyword used: {$keyword}",
            function ($message) {
                $message->to('admin@realtimesearch.com')
                        ->subject('Search Keyword Alert');
            }
        );
    } catch (\Exception $e) {
        Log::error('Search email failed: ' . $e->getMessage());
    }

    return response()->json($results);
}
}
