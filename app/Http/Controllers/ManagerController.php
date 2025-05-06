<?php

namespace App\Http\Controllers;
use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'link' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string|max:50',
        ]);

        $manager = Manager::create($validated);

        return response()->json([
            'message' => 'Менеджер успешно создан',
            'manager' => $manager
        ], 201);
    }
}
