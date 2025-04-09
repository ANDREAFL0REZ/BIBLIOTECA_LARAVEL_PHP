<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index()
    {
        return response()->json(Book::all(), 200);
    }

    public function store(Request $request)
    {
        // Validar que la solicitud no esté vacía
        if (!$request->all()) {
            return response()->json(['message' => 'No se puede crear un libro con datos vacíos'], 400);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
        ]);

        $book = Book::create($request->all());
        return response()->json($book, 201);
    }

    public function show($id)
    {
        $book = Book::find($id);
        return $book ? response()->json($book, 200) : response()->json(['message' => 'Libro no encontrado'], 404);
    }

    public function update(Request $request, $id)
    {
        $book = Book::find($id);
        if (!$book) return response()->json(['message' => 'Libro no encontrado'], 404);

        // Validar que la solicitud no esté vacía
        if (!$request->all()) {
            return response()->json(['message' => 'No se puede actualizar un libro con datos vacíos'], 400);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'author' => 'sometimes|required|string|max:255',
        ]);

        $book->update($request->all());
        return response()->json($book, 200);
    }

    public function destroy($id)
    {
        $book = Book::find($id);
        if (!$book) return response()->json(['message' => 'Libro no encontrado'], 404);

        $book->delete();
        return response()->json(['message' => 'Libro eliminado'], 200);
    }
}