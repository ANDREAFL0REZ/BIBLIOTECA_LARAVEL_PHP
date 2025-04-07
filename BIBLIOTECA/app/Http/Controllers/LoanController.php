<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index()
    {
        return response()->json(Loan::all(), 200);
    }

    public function store(Request $request)
    {
        // Validar que la solicitud no esté vacía
        if (!$request->all()) {
            return response()->json(['message' => 'No se puede crear un préstamo con datos vacíos'], 400);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Verificar si el usuario existe
        $user = User::find($request->user_id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Verificar si el libro existe
        $book = Book::find($request->book_id);
        if (!$book) {
            return response()->json(['message' => 'Libro no encontrado'], 404);
        }

        // Verificar si el libro está disponible
        if (!$book->available) {
            return response()->json(['message' => 'Libro no disponible'], 400);
        }

        // Crear el préstamo
        $loan = Loan::create([
            'user_id' => $request->user_id,
            'book_id' => $request->book_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'returned' => false
        ]);

        // Marcar el libro como no disponible
        $book->update(['available' => false]);

        return response()->json($loan, 201);
    }

    public function update(Request $request, $id)
    {
        if (!$id) {
            return response()->json(['message' => 'ID no proporcionado'], 400);
        }

        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Préstamo no encontrado'], 404);
        }

        // Validar que la solicitud no esté vacía
        if (!$request->all()) {
            return response()->json(['message' => 'No se puede actualizar un préstamo con datos vacíos'], 400);
        }

        // Verificar si el libro relacionado con el préstamo existe
        $book = Book::find($loan->book_id);
        if (!$book) {
            return response()->json(['message' => 'Libro no encontrado'], 404);
        }

        // Marcar el préstamo como devuelto y hacer el libro disponible otra vez
        $loan->update(['returned' => true]);
        $book->update(['available' => true]);

        return response()->json(['message' => 'Préstamo devuelto con éxito', 'loan' => $loan], 200);
    }

    public function destroy($id)
    {
        if (!$id) {
            return response()->json(['message' => 'ID no proporcionado'], 400);
        }

        $loan = Loan::find($id);
        if (!$loan) {
            return response()->json(['message' => 'Préstamo no encontrado'], 404);
        }

        $loan->delete();
        return response()->json(['message' => 'Préstamo eliminado con éxito'], 200);
    }
}
