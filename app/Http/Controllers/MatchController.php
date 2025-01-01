<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Busca todos os matches com informações relacionadas aos times e campeonato
    $matches = DB::table('matches')
        ->join('teams as home_team', 'matches.home_team_id', '=', 'home_team.id')
        ->join('teams as away_team', 'matches.away_team_id', '=', 'away_team.id')
        ->join('championships', 'matches.championship_id', '=', 'championships.id')
        ->select(
            'matches.id',
            'matches.match_date',
            'matches.round',
            'matches.home_team_score',
            'matches.away_team_score',
            'home_team.name as home_team_name',
            'away_team.name as away_team_name',
            'home_team.id as home_team_id',
            'away_team.id as away_team_id',
            'championships.id as championship_id',
            'championships.name as championship_name',
            'championships.year as championship_year'
        )
        ->get();

    // Estrutura a resposta com os dados dos times e campeonato dentro de cada partida
    $formattedMatches = $matches->map(function ($match) {
        return [
            'id' => $match->id,
            'match_date' => $match->match_date,
            'round' => $match->round,
            'home_team_score' => $match->home_team_score,
            'away_team_score' => $match->away_team_score,
            'home_team' => [
                'id' => $match->home_team_id,
                'name' => $match->home_team_name
            ],
            'away_team' => [
                'id' => $match->away_team_id,
                'name' => $match->away_team_name
            ],
            'championship' => [
                'id' => $match->championship_id,
                'name' => $match->championship_name,
                'year' => $match->championship_year
            ]
        ];
    });

    // Retorna a resposta com as partidas e seus dados completos
    return response()->json($formattedMatches);
}




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'championship_id' => 'required|integer|exists:championships,id',
            'home_team_id' => 'required|integer|exists:teams,id',
            'away_team_id' => 'required|integer|exists:teams,id',
            'match_date' => 'required|date',
            'round' => 'required|integer',
        ]);

        $match = DB::table('matches')->insert([
            'championship_id' => $request->championship_id,
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'home_team_score' => 0,
            'away_team_score' => 0,
            'match_date' => $request->match_date,
            'round' => $request->round,
        ]);

        return response()->json(['message' => 'Match created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $match = DB::select('SELECT * FROM matches WHERE id = ?', [$id]);

        if (empty($match)) {
            return response()->json(['message' => 'Match not found'], 404);
        }

        return response()->json($match[0]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
        {
            // Validação dos dados recebidos
            $request->validate([
                'championship_id' => 'required|exists:championships,id',
                'home_team_id' => 'required|exists:teams,id',
                'away_team_id' => 'required|exists:teams,id',
                'match_date' => 'required|date',
                'round' => 'required|integer',
                'home_team_score' => 'nullable|integer',
                'away_team_score' => 'nullable|integer',
            ]);

            // Atualizando os dados da partida
            $updated = DB::table('matches')
                ->where('id', $id)
                ->update([
                    'championship_id' => $request->championship_id,
                    'home_team_id' => $request->home_team_id,
                    'away_team_id' => $request->away_team_id,
                    'match_date' => $request->match_date,
                    'round' => $request->round,
                    'home_team_score' => $request->home_team_score,
                    'away_team_score' => $request->away_team_score,
                    'updated_at' => now(),
                ]);

            // Verificando se houve alguma atualização
            if ($updated) {
                return response()->json(['message' => 'Match updated successfully']);
            }

            return response()->json(['message' => 'Match not found or no changes made'], 404);
        }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = DB::table('matches')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['message' => 'Match deleted successfully']);
        }

        return response()->json(['message' => 'Match not found'], 404);
    }
}
