<?php


namespace App\Services\Search;


use App\Entity\Books;
use Elasticsearch\Client;

class BookIndexer
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function clear()
    {
        $this->client->deleteByQuery([
            'index' => 'books',
            'body' => [
                'query' => [
                    'match_all' => new \stdClass(),
                ],
            ],
        ]);
    }

    public function index(Books $book)
    {
        $this->client->index([
            //служебные записи elasticserch
            'index' => 'books',
            'id' => $book->id,
            'body' => [
                //указываем поля необходимые для поиска
                'id' => $book->id,
                'title' => $book->title,
                'author' => $book->author,
                'estimate' => $book->estimate ? $book->estimate : 0.0,
                'created_at' => substr($book->created_at, 0, 10),
            ],
        ]);
    }

    public function remove(Books $book)
    {
        $this->client->delete([
            'index' => 'books',
            'id' => $book->id,
        ]);
    }

}
