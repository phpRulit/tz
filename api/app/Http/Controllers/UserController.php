<?php


namespace App\Http\Controllers;

use App\Entity\Books;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index() :JsonResponse
    {
        return response()->json(Auth::user()->books()->orderByDesc('id')->get());
    }

    public function store(Request $request) :JsonResponse
    {
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'author' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()]);
        }
        try {
            $book = $this->service->create($request);
        } catch (\DomainException $e) {
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'item' => $book,
            'message' => 'Книга добавлена...'
        ]);
    }


    public function update(Books $book, Request $request) :JsonResponse
    {
        $this->checkAccess($book);
        $v = Validator::make($request->all(), [
            'title' => 'required|string|max:100',
            'author' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
        ]);
        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()]);
        }
        try {
            $book = $this->service->update($book, $request);
        } catch (\DomainException $e) {
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'item' => $book,
            'message' => 'Изменения сохранены...'
        ]);
    }

    public function updatePhoto(Books $book, Request $request)
    {
        $this->checkAccess($book);
        try {
            $book = $this->service->updatePhoto($book, $request);
        } catch (\DomainException $e) {
            return response()->json([
                'messageError' => 'Ошибка, попробуйте еще раз...'
            ]);
        }
        return response()->json([
            'item' => $book,
            'message' => 'Изображение сохранено...'
        ]);
    }


    public function destroy($book_id) :JsonResponse
    {
        $book = Books::findOrFail($book_id);
        if ($book) {
            $this->checkAccess($book);
            try {
                $this->service->destroy($book);
            } catch (\DomainException $e) {
                return response()->json([
                    'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
                ]);
            }
            return response()->json([
                'message' => 'Книга удалена...'
            ]);
        }
        return response()->json([
            'messageError' => 'Уже удалена...'
        ]);
    }

    public function getVoices()  :JsonResponse
    {
        return response()->json(Auth::user()->voices()->first());
    }

    public function toVote(Books $book, Request $request) :JsonResponse
    {
        try {
            $book = $this->service->toVote($book, $request);
        } catch (\DomainException $e) {
            return response()->json([
                'messageError' => 'Ошибка!!! Попробуйте ещё раз...'
            ]);
        }
        return response()->json([
            'item' => $book,
            'message' => 'Ваш голос учтён. Спасибо, что проголосовали...'
        ]);
    }

    private function checkAccess(Books $book)
    {
        if (!Gate::allows('can-manage', $book)) {
            abort(403);
        }
    }
}
