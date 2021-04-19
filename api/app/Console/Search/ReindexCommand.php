<?php


namespace App\Console\Search;


use App\Entity\Books;
use App\Services\Search\BookIndexer;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    protected $signature = 'search:reindex';

    protected $description = 'Команда перемещения данных в elasticsearch согласно индексам';

    private $books;

    public function __construct(BookIndexer $books) {
        parent::__construct();
        $this->books = $books;
    }

    public function handle()
    {
        $this->books->clear();
        foreach (Books::orderBy('id')->cursor() as $banner) {
            $this->books->index($banner);
        }
        return true;
    }

}
