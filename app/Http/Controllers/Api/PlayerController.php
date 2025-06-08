<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Player;

class PlayerController extends Controller
{
    // Store new player
    public function store(Request $request)
{
    $validated = $request->validate([
        'player_ign'   => 'required|string|max:255',
        'player_role'  => 'required|string|max:100',
        'player_photo' => 'nullable|image|max:2048',
        'team_id'      => 'required|exists:teams,team_id',
        // removed status validation here
    ]);

    // Handle file upload
    if ($request->hasFile('player_photo')) {
        $path = $request->file('player_photo')->store('player_photos', 'public');
        $validated['player_photo'] = $path;
    }

    // Manually sanitize status, default to 'starter' if invalid
    $status = strtolower($request->input('status', 'starter'));
    if (!in_array($status, ['starter', 'bench'])) {
        $status = 'starter';
    }
    $validated['status'] = $status;

    $player = Player::create($validated);

    return response()->json($player, 201);
}


    // Update player status or other details
    public function update(Request $request, Player $player)
    {
        $validated = $request->validate([
            'player_ign'   => 'sometimes|string|max:255',
            'player_role'  => ['sometimes', Rule::in(['Jungle', 'Gold Lane', 'Mid Lane', 'EXP Lane', 'Roam'])],
            'player_photo' => 'nullable|image|max:2048',
            'team_id'      => 'sometimes|exists:teams,team_id',
            'status'       => ['sometimes', Rule::in(['Starter', 'Bench'])],
        ]);

        // Handle file upload
        if ($request->hasFile('player_photo')) {
            if ($player->player_photo) {
                Storage::disk('public')->delete($player->player_photo);
            }

            $path = $request->file('player_photo')->store('player_photos', 'public');
            $validated['player_photo'] = $path;
        }

        $player->update($validated);

        return response()->json($player);
    }

    // Get player details
    public function show(Player $player)
    {
        return response()->json($player);
    }

    // Delete player
    public function destroy(Player $player)
    {
        if ($player->player_photo) {
            Storage::disk('public')->delete($player->player_photo);
        }

        $player->delete();

        return response()->json(['message' => 'Player deleted successfully']);
    }

    // Optional: List all players
public function index()
{
    $players = Player::with('team')->get()->map(function ($player) {
        $player->player_photo = $player->player_photo 
            ? asset('storage/' . $player->player_photo)
            : null;
        return $player;
    });

    return response()->json($players);
}
}
