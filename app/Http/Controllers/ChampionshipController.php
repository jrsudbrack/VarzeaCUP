<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChampionshipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Consultar todos os campeonatos
        $championships = DB::select('SELECT * FROM championships');
        return response()->json($championships);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        // Inserir no banco
        $id = DB::table('championships')->insertGetId([
            'name' => $validated['name'],
            'year' => $validated['year'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Championship created successfully', 'id' => $id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Consultar campeonato específico
        $championship = DB::select('SELECT * FROM championships WHERE id = ?', [$id]);

        if (empty($championship)) {
            return response()->json(['message' => 'Championship not found'], 404);
        }

        return response()->json($championship);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        // Consultar campeonato para garantir que existe
        $championship = DB::select('SELECT * FROM championships WHERE id = ?', [$id]);

        if (empty($championship)) {
            return response()->json(['message' => 'Championship not found'], 404);
        }

        // Atualizar campeonato
        $updated = DB::update('UPDATE championships SET name = ?, year = ?, updated_at = NOW() WHERE id = ?', [
            $validated['name'],
            $validated['year'],
            $id
        ]);

        if ($updated) {
            return response()->json(['message' => 'Championship updated successfully']);
        }

        return response()->json(['message' => 'Failed to update championship'], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Consultar campeonato para garantir que existe
        $championship = DB::select('SELECT * FROM championships WHERE id = ?', [$id]);

        if (empty($championship)) {
            return response()->json(['message' => 'Championship not found'], 404);
        }

        // Deletar campeonato
        $deleted = DB::delete('DELETE FROM championships WHERE id = ?', [$id]);

        if ($deleted) {
            return response()->json(['message' => 'Championship deleted successfully']);
        }

        return response()->json(['message' => 'Failed to delete championship'], 400);
    }

    public function getClassification()
    {
        $classification = DB::table('teams')
            ->leftJoin('matches as home_matches', 'teams.id', '=', 'home_matches.home_team_id')
            ->leftJoin('matches as away_matches', 'teams.id', '=', 'away_matches.away_team_id')
            ->select(
                'teams.id',
                'teams.name',
                DB::raw('COUNT(DISTINCT home_matches.id) + COUNT(DISTINCT away_matches.id) as matches_played'),
                DB::raw('SUM(CASE WHEN home_matches.home_team_score > home_matches.away_team_score THEN 1 WHEN away_matches.away_team_score > away_matches.home_team_score THEN 1 ELSE 0 END) as wins'),
                DB::raw('SUM(CASE WHEN home_matches.home_team_score = home_matches.away_team_score THEN 1 WHEN away_matches.home_team_score = away_matches.away_team_score THEN 1 ELSE 0 END) as draws'),
                DB::raw('SUM(CASE WHEN home_matches.home_team_score < home_matches.away_team_score THEN 1 WHEN away_matches.away_team_score < away_matches.home_team_score THEN 1 ELSE 0 END) as losses'),
                DB::raw('SUM(CASE WHEN teams.id = home_matches.home_team_id THEN home_matches.home_team_score WHEN teams.id = away_matches.away_team_id THEN away_matches.away_team_score ELSE 0 END) as points')
            )
            ->groupBy('teams.id', 'teams.name')
            ->orderByDesc('points')
            ->get();

        return response()->json($classification);
    }
}
