<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Exception;

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
                        <a href="' . route('features.edit', $data->id) . '" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="deleteRecord(event,' . $data->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        try {
            Feature::create($validated);
            flash()->success('Feature created successfully');
            return redirect()->route('features.index');
        } catch (Exception $e) {
            Log::error('FeatureController::store'.$e->getMessage());
            flash()->error('Feature creation failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $feature = Feature::findOrFail($id);
        return view('backend.layouts.feature.edit', compact('feature'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);
        try {
            $feature = Feature::findOrFail($id);
            $feature->update($validated);
            flash()->success('Feature updated successfully');
            return redirect()->route('features.index');
        } catch (Exception $e) {
            Log::error('FeatureController::update'.$e->getMessage());
            flash()->error('Feature update failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();
        return response()->json(['success' => true, 'message' => 'Feature deleted successfully']);
    }
}
