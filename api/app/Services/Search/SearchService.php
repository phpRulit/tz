<?php


namespace App\Services\Search;


use App\Entity\Books;
use App\Http\Requests\SearchRequest;
use Elasticsearch\Client;
use Illuminate\Database\Query\Expression;

class SearchService
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function search(SearchRequest $request) {
        $response = $this->client->search([
            'index' => 'books',
            'body' => [
                '_source' => ['id'],
                'sort' => !$request['text'] ? [
                    ['created_at' => ['order' => 'desc']],
                ] : [],
                'query' => [
                    'bool' => [
                        'must' => array_merge(
                            [
                                [
                                    'range' => [
                                        'estimate' => [
                                            'gte' => $request['from'] ? (double)$request['from'] : 0,
                                            'lte' => $request['to'] ? (double)$request['to'] : 100
                                        ]
                                    ],
                                ],
                            ],
                            array_filter([
                                !empty($request['text']) ? ['multi_match' => [
                                    'query' => $request['text'],
                                    'fields' => [ 'title^3', 'author' ],
                                    'type' => 'best_fields',
                                ]] : false,
                            ])
                        )
                    ],
                ],
            ],
        ]);
        $ids = array_column($response['hits']['hits'], '_id');
        if ($ids) {
            $items = Books::whereIn('id', $ids)
                ->orderBy(new Expression('FIELD(id,' . implode(',', $ids) . ')'))
                ->get();
        } else {
            $items = [];
        }
        return $items;
    }

}
