<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Rules\ISBN;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\JsonReponse
     */
    public function index()
    {
        $perPage = request('perPage', 10);

        return BookResource::collection(Book::with('author')->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonReponse
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'title' => ['required', 'unique:books', 'string', 'max:255'],
            'isbn' => ['required', 'unique:books', 'string', new ISBN],
            'author_id' => ['required', 'exists:authors,id'],
            'publication_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
        ]);

        try {
            Book::create($data);
            return response()->json([
                'message' => 'Livre enregistré avec succès',
                'status' => true
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Oups une erreur est survenue, veuillez réessayer plus tard'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\JsonReponse
     */
    public function show($id)
    {
        $book = Book::find($id)?->load('author');
        if (!$book) {
            $message = 'Le livre que vous chercher à afficher n\'existe pas';
            $status = false;
            return response()->json(compact('message', 'status'), 404);
        }

        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\JsonReponse
     */
    public function update(Request $request, $id)
    {
        $originalBook = Book::find($id);
        if (!$originalBook) {
            $message = 'Le livre que vous chercher à modifier n\'existe pas';
            $status = false;
            return response()->json(compact('message', 'status'), 404);
        }

        $data = $this->validate($request, [
            'title' => ['required', 'unique:books,id,' . $id . ',id', 'string', 'max:255'],
            'isbn' => ['required', 'unique:books,id,' . $id . ',id', 'string', new ISBN],
            'author_id' => ['required', 'exists:authors,id'],
            'publication_year' => ['required', 'integer', 'min:1800', 'max:' . date('Y')],
        ]);

        try {
            $bookCopy = $originalBook;
            if ($bookCopy->update($data)) {
                return response()->json([
                    'message' => 'Mise à jour des informations du livre avec succès',
                    'status' => true,
                    'data' => $originalBook?->load('author'),
                ]);
            } else {
                return response()->json([
                    'message' => 'Une erreur est survenue lors de la tentative de mise à jour des informations du livre',
                    'status' => false
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Oups une erreur est survenue, veuillez réessayer plus tard'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\JsonReponse
     */
    public function destroy($id)
    {
        $message = 'Le livre que vous chercher à supprimer n\'existe pas';
        $status = false;
        try {

            $book = Book::find($id);
            if (!$book) {
                return response()->json(compact('message', 'status'), 404);
            }

            if ($book->delete()) {
                $message = 'Livre supprimé avec succès';
                $status = true;
            } else {
                $message = 'Une erreur est survenue lors de la tentative de suppression du livre';
                $status = false;
            }

            return response()->json(compact('message', 'status'));
        } catch (\Throwable $th) {
            return response()->json([
                'message' => env('APP_DEBUG') ? $th->getMessage() : 'Oups une erreur est survenue, veuillez réessayer plus tard'
            ], 500);
        }
    }
}
