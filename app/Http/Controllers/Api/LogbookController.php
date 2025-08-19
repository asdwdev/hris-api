<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogbookController extends Controller
{
    public function index()
    {
        return response()->json(Logbook::orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'today_task' => 'required|string',
            'tomorrow_plan' => 'nullable|string',
            'documentation' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'constraints' => 'nullable|string',
        ]);

        $data = $request->only(['date', 'today_task', 'tomorrow_plan', 'constraints']);

        // Handle image upload
        if ($request->hasFile('documentation')) {
            $data['documentation'] = $request->file('documentation')->store('logbooks', 'public');
        }

        $logbook = Logbook::create($data);

        return response()->json($logbook, 201);
    }

    public function show(Logbook $logbook)
    {
        return response()->json($logbook);
    }

    public function update(Request $request, Logbook $logbook)
    {
        $request->validate([
            'date' => 'required|date',
            'today_task' => 'required|string',
            'tomorrow_plan' => 'nullable|string',
            'documentation' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'constraints' => 'nullable|string',
        ]);

        $data = $request->only(['date', 'today_task', 'tomorrow_plan', 'constraints']);

        if ($request->hasFile('documentation')) {
            if ($logbook->documentation) {
                Storage::disk('public')->delete($logbook->documentation);
            }
            $data['documentation'] = $request->file('documentation')->store('logbooks', 'public');
        }

        $logbook->update($data);

        return response()->json($logbook);
    }

    public function destroy(Logbook $logbook)
    {
        if ($logbook->documentation) {
            Storage::disk('public')->delete($logbook->documentation);
        }
        $logbook->delete();
        return response()->json(null, 204);
    }
}
