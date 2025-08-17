<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BooksControllerApi extends Controller
{
    /**
     * Lista todos os livros.
     * Rota: GET /api/books
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // O `with()` evita que o Laravel faça múltiplas consultas ao banco de dados (problema N+1)
        // e já carrega os relacionamentos de autor, editora e categoria.
        $books = Book::with(['author', 'publisher', 'category'])->get();
        return response()->json($books);
    }

    /**
     * Cria um novo livro.
     * Rota: POST /api/books
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Usa o Validator para validar os dados da requisição. Se falhar, retorna um erro JSON.
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            // Retorna um erro com o status 422 (Unprocessable Entity)
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book = Book::create($data);

        // Retorna o livro recém-criado e o status 201 (Created) para indicar sucesso.
        return response()->json($book, 201);
    }

    /**
     * Exibe um livro específico.
     * Rota: GET /api/books/{id}
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $book = Book::with(['author', 'publisher', 'category'])->find($id);

        if (!$book) {
            // Se o livro não for encontrado, retorna o status 404 (Not Found).
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }

        return response()->json($book);
    }

    /**
     * Atualiza um livro existente.
     * Rota: PUT /api/books/{id}
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except(['_method']);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return response()->json($book);
    }

    /**
     * Remove um livro.
     * Rota: DELETE /api/books/{id}
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Livro não encontrado.'], 404);
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return response()->json(['message' => 'Livro deletado com sucesso.'], 200);
    }
}
