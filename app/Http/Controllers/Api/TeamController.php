<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    // Get all teams
    public function index()
    {
        $teams = Team::all();
        return response()->json($teams);
    }

    // Get a single team by ID
    public function show($id)
    {
        $team = Team::findOrFail($id);
        return response()->json($team);
    }

    // Store a new team
public function store(Request $request)
{
    try {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'team_logo' => 'nullable|image|max:2048',
        ]);

        $team = new Team();
        $team->team_name = $request->team_name;
        $team->short_name = $request->short_name;
        $team->address = $request->address;

        if ($request->hasFile('team_logo')) {
            $path = $request->file('team_logo')->store('team_logos', 'public');
            $team->team_logo = $path;
        }

        $team->save();

        return response()->json(['message' => 'Team created successfully', 'team' => $team], 201);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to create team',
            'error' => $e->getMessage()
        ], 500);
    }
}



    // Update existing team
    public function update(Request $request, $id)
    {
        $request->validate([
            'team_name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'team_logo' => 'nullable|image|max:2048',
        ]);

        $team = Team::findOrFail($id);

        $team->team_name = $request->team_name;
        $team->short_name = $request->short_name;
        $team->address = $request->address;

        if ($request->hasFile('team_logo')) {
            // Delete old logo if exists
            if ($team->team_logo && Storage::disk('public')->exists($team->team_logo)) {
                Storage::disk('public')->delete($team->team_logo);
            }

            $path = $request->file('team_logo')->store('team_logos', 'public');
            $team->team_logo = $path;
        }

        $team->save();

        return response()->json([
            'message' => 'Team updated successfully',
            'team' => $team
        ]);
    }

    // Delete a team
    public function destroy($id)
    {
        $team = Team::findOrFail($id);

        // Delete logo file if exists
        if ($team->team_logo && Storage::disk('public')->exists($team->team_logo)) {
            Storage::disk('public')->delete($team->team_logo);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }

    // Delete a team and all its players
public function destroyWithPlayers($team_id)
{
    \Log::info("DELETE /api/teams/{$team_id}/with-players initiated");
    
    try {
        $team = Team::find($team_id);
        
        if (!$team) {
            \Log::warning("Team {$team_id} not found");
            return response()->json(['message' => 'Team not found'], 404);
        }

        \Log::info("Found team: {$team->team_name}");
        \Log::info("Deleting ".$team->players()->count()." players");
        
        // Delete players
        $playerDeletions = $team->players()->delete();
        \Log::info("Deleted {$playerDeletions} players");
        
        // Delete team
        $team->delete();
        \Log::info("Team deleted successfully");

        return response()->json([
            'success' => true,
            'message' => 'Team and players deleted successfully'
        ]);
        
    } catch (\Exception $e) {
        \Log::error("Deletion failed: ".$e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Deletion failed',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
