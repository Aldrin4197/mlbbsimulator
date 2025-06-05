<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;  // Assuming you have a Team model

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }

    public function store(Request $request)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'team_logo' => 'nullable|image|max:2048', // Optional image
        ]);

        $team = new Team();
        $team->team_name = $request->team_name;

        if ($request->hasFile('team_logo')) {
            $path = $request->file('team_logo')->store('team_logos', 'public');
            $team->team_logo = $path;
        }

        $team->save();

        return response()->json(['message' => 'Team created successfully', 'team' => $team]);
    }

    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }
}
