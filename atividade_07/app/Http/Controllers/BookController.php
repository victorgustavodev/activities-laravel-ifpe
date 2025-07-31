<?php
namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    // Formulário com input de ID
    public function createWithId()
    {
        return view('books.create-id');
    }
    // Cria um livro com id
    public function storeWithId(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id'    => 'required|exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'image_url'    => 'nullable|image|max:2048', // imagem agora é opcional
        ]);

        $data = $request->all();

        if ($request->hasFile('image_url')) {
            $imagePath         = $request->file('image_url')->store('books', 'public');
            $data['image_url'] = $imagePath;
        } else {
            $data['image_url'] = 'defaults/default-book.png';
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }
    // Formulário com input select
    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors    = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // Cria um livro com input-select
    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id'    => 'required|exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'image_url'    => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Trata o upload da imagem
        if ($request->hasFile('image_url')) {
            $path              = $request->file('image_url')->store('books', 'public');
            $data['image_url'] = $path; // Salva só o caminho relativo
        } else {
            // Se não enviar imagem, salva o caminho padrão
            $data['image_url'] = 'defaults/default-book.png';
        }

        Book::create($data);
        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function index()
    {
        // Carregar os livros com autores usando eager loading e paginação
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));

    }

    public function update(Request $request, Book $book){
        $request->validate([
            'title'        => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id'    => 'required|exists:authors,id',
            'category_id'  => 'required|exists:categories,id',
            'image_url'    => 'nullable|image',
        ]);
    
        $data = $request->all();
    
        // Remover imagem se o checkbox foi marcado
        if ($request->has('remove_image') && $book->image_url) {
            if (Storage::disk('public')->exists($book->image_url)) {
                Storage::disk('public')->delete($book->image_url);
            }
            $data['image_url'] = null; // ou 'defaults/default-book.png' se quiser uma imagem padrão
        }
    
        // Se uma nova imagem foi enviada, faz o upload e atualiza o caminho
        if ($request->hasFile('image_url')) {
            $imagePath = $request->file('image_url')->store('books', 'public');
            $data['image_url'] = $imagePath;
        } elseif (!$request->has('remove_image')) {
            // Mantém a imagem antiga se não foi enviada uma nova e não marcou para remover
            $data['image_url'] = $book->image_url;
        }
    
        $book->update($data);
    
        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function show(Book $book){
        // Carregando autor, editora e categoria do livro com eager loading
        $book->load(['author', 'publisher', 'category']);

        // Carregar todos os usuários para o formulário de empréstimo
        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }

    public function edit(Book $book){
        $publishers = Publisher::all();
        $authors    = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function destroy(Book $book){
    // Se houver imagem e ela não for a padrão, deleta do storage
    if ($book->image_url && Storage::disk('public')->exists($book->image_url)) {
        Storage::disk('public')->delete($book->image_url);
    }

    $book->delete();

    return redirect()->route('books.index')->with('success', 'Livro deletado com sucesso.');
}
}
