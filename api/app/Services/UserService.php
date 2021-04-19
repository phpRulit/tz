<?php


namespace App\Services;


use App\Entity\Books;
use App\Entity\UserVoice;
use App\Services\Search\BookIndexer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    private $indexer;

    public function __construct(BookIndexer $indexer) {
        $this->indexer = $indexer;
    }

    public function create (Request $request)
    {
        $user = Auth::user();
        $book = Books::create([
            'user_id' => $user->id,
            'title' => $request['title'],
            'author' => $request['author'],
            'description' => json_encode([
                'name_user' => $user->name,
                'description' => $request['description']
            ]),
        ]);
        $this->indexer->index($book);
        return $book;
    }

    public function update (Books $book, Request $request)
    {
        $user = Auth::user();
        $book->update([
            'title' => $request['title'],
            'author' => $request['author'],
            'description' => json_encode([
                'name_user' => $user->name,
                'description' => $request['description']
            ]),
        ]);
        return $book;
    }

    public function updatePhoto(Books $book, Request $request)
    {
        return DB::transaction(function () use ($book, $request) {
            if ($book->img) {
                Storage::delete($book->img);
            }
            $path = 'books/'.$book->id . '/';
            $nameFile = 'X7' . $book->id .'2434ava';
            Storage::putFileAs(
                $path,
                $request->file('file'),
                $nameFile,
                ['visibility' => 'public']
            );
            if (!$book->img) {
                $book->update([
                    'img' => $nameFile,
                ]);
            }
            return $book;
        });
    }

    public function destroy (Books $book) :void
    {
        $book->delete();
        $this->indexer->remove($book);
    }

    public function toVote (Books $book, Request $request)
    {
        return DB::transaction(function () use ($book, $request) {
            $this->indexer->remove($book);
            $sum_voices =  $book->sum_voices + (int)$request['voice'];
            $count_voices =  $book->count_voices + 1;
            $estimate = $this->voteScore($sum_voices, $count_voices);
            $book->update([
                'sum_voices' => $sum_voices,
                'count_voices' => $count_voices,
                'estimate' => $estimate
            ]);
            if (Auth::check()) {
                $userAuth = Auth::user();
                $voices = $userAuth->voices()->first();
                !is_null($voices)
                    ? $voices->update(['book_id' => json_encode(array_merge(json_decode($voices->book_id), [$book->id]))])
                    : UserVoice::create(['user_id' => $userAuth->id, 'book_id' => json_encode([$book->id])]);
            }
            $this->indexer->index($book);
            return $book;
        });
    }

    /**
     * Расчет рейтинга на основе шкалы голосов
     *
     * @param $sumVotes //Сумма всех голосов
     * @param $totalVotes //Кол-во голосов
     * @param array $votesRange Диапазон возможных значений голосов
     * @return float|int
     */
    private function voteScore($sumVotes, $totalVotes, $votesRange = [1, 2, 3, 4, 5])
    {
        if ($sumVotes > 0 && $totalVotes > 0) {
            $z = 1.64485;
            $vMin = min($votesRange);
            $vWidth = floatval(max($votesRange) - $vMin);

            $phat = ($sumVotes - $totalVotes * $vMin) / $vWidth / floatval($totalVotes);

            $rating = ($phat + $z * $z / (2 * $totalVotes) - $z * sqrt(($phat * (1 - $phat) + $z * $z / (4 * $totalVotes)) / $totalVotes)) / (1 + $z * $z / $totalVotes);

            return round($rating * $vWidth + $vMin, 6);
        }

        return 0;
    }

}
