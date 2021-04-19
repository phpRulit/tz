<?php


namespace App\Http\Controllers;


use App\Entity\Books;
use App\Http\Requests\SearchRequest;
use App\Services\Search\SearchService;
use Illuminate\Http\JsonResponse;

class OpenController extends Controller
{
    private $search;

    public function __construct(SearchService $search)
    {
        $this->search = $search;
    }

    public function search(SearchRequest $request) :JsonResponse
    {
        return response()->json($this->search->search($request));
    }

    public function getPrompts() :JsonResponse
    {
        $arr1 = Books::pluck('author')->unique()->toArray();
        $arr2 = Books::pluck('title')->unique()->toArray();
        return response()->json(array_merge($arr1, $arr2));
    }
}
