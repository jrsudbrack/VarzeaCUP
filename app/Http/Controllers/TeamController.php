<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consulta direta ao banco para pegar todos os times
        $teams = DB::select('SELECT * FROM teams');
        return response()->json($teams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',  // Setando campo sector como opcional
        ]);

        // Inserir os dados diretamente no banco
        $inserted = DB::insert('INSERT INTO teams (name, sector) VALUES (?, ?)', [
            $validated['name'],
            $validated['sector'] ?? null, // Garantir que o setor possa ser nulo
        ]);

        // Retorna uma resposta JSON com sucesso
        if ($inserted) {
            return response()->json(['message' => 'Team created successfully'], 201);
        }

        return response()->json(['message' => 'Failed to create team'], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Consulta para buscar o time pelo ID
        $team = DB::select('SELECT * FROM teams WHERE id = ?', [$id]);

        if (empty($team)) {
            return response()->json(['message' => 'Team not found'], 404);  // Caso o time não seja encontrado
        }

        return response()->json($team[0]);  // Retorna o time encontrado
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
        ]);

        // Verificar se o time existe
        $team = DB::select('SELECT * FROM teams WHERE id = ?', [$id]);

        if (empty($team)) {
            return response()->json(['message' => 'Team not found'], 404);  // Caso o time não seja encontrado
        }

        // Atualizar os dados do time no banco, incluindo o updated_at
        $updated = DB::update('UPDATE teams SET name = ?, sector = ?, updated_at = NOW() WHERE id = ?', [
            $validated['name'],
            $validated['sector'] ?? null,  // Se o setor for nulo, usa null
            $id
        ]);

        if ($updated) {
            return response()->json(['message' => 'Team updated successfully']);
        }

        return response()->json(['message' => 'Failed to update team'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Verificar se o time existe
        $team = DB::select('SELECT * FROM teams WHERE id = ?', [$id]);

        if (empty($team)) {
            return response()->json(['message' => 'Team not found'], 404);  // Caso o time não seja encontrado
        }

        // Deletar o time do banco
        $deleted = DB::delete('DELETE FROM teams WHERE id = ?', [$id]);

        if ($deleted) {
            return response()->json(['message' => 'Team deleted successfully']);
        }

        return response()->json(['message' => 'Failed to delete team'], 400);
    }
}
