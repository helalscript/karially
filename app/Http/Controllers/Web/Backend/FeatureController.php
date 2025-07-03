<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class FeatureController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Feature::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="window.location.href=\'' . route('features.edit', $data->id) . '\'">
                        <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </button>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
                </div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.layouts.feature.index');
    }

    public function create()
    {
        return view('backend.layouts.feature.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        try {
            Feature::create($validatedData);
            flash()->success('Feature created Successfully');
            return redirect()->route('features.index');
        } catch (Exception $e) {
            Log::error('FeatureController::store ' . $e->getMessage());
            flash()->error('Feature creation failed');
            return redirect()->route('features.index');
        }
    }

    public function edit(string $id)
    {
        $data = Feature::findOrFail($id);
        return view('backend.layouts.feature.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        try {
            $data = Feature::findOrFail($id);
            $data->update($validatedData);
            flash()->success('Feature updated Successfully');
            return redirect()->route('features.index');
        } catch (Exception $e) {
            Log::error('FeatureController::update ' . $e->getMessage());
            flash()->error('Feature update failed');
            return redirect()->route('features.index');
        }
    }

    public function destroy(string $id)
    {
        $data = Feature::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }
}
