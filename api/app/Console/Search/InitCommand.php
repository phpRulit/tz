<?php


namespace App\Console\Search;


use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Console\Command;

class InitCommand extends Command
{
    protected $signature = 'search:init';

    protected $description = 'Команда создания индексов для elasticsearch';

    private $client;

    public function __construct(Client $client)
    {
        parent::__construct();
        $this->client = $client;
    }

    public function handle()
    {
        $this->initBooks();
    }

    private function initBooks()
    {
        try {
            $this->client->indices()->delete([
                'index' => 'books'
            ]);
        } catch (Missing404Exception $e) {
        }

        $this->client->indices()->create([
            'index' => 'books',
            'body' => [
                'mappings' => [
                    '_source' => [
                        'enabled' => true,
                    ],
                    'properties' => [
                        'id' => [
                            'type' => 'integer',
                        ],
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'default'
                        ],
                        'author' => [
                            'type' => 'text',
                            'analyzer' => 'default'
                        ],
                        'estimate' => [
                            'type' => 'float',
                        ],
                        'created_at' => [
                            'type' => 'date',
                        ],
                    ],
                ],
                'settings' => [
                    'analysis' => [
                        'char_filter' => [
                            'replace' => [
                                'type' => 'mapping',
                                'mappings' => [
                                    '&=> and '
                                ],
                            ],
                        ],
                        'filter' => [
                            'word_delimiter' => [
                                'type' => 'word_delimiter',
                                'split_on_numerics' => false,
                                'split_on_case_change' => true,
                                'generate_word_parts' => true,
                                'generate_number_parts' => true,
                                'catenate_all' => true,
                                'preserve_original' => true,
                                'catenate_numbers' => true,
                            ],
                        ],
                        //Анализаторы поиска
                        'analyzer' => [
                            'default' => [
                                'type' => 'custom',
                                'char_filter' => [
                                    'html_strip',
                                    'replace',
                                ],
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'lowercase',
                                    'word_delimiter',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

}
