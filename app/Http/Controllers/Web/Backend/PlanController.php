<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Plan::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $data->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px" name="status"';
                    if ($data->status == "active") {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '</div>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="window.location.href=\'' . route('plans.edit', $data->id) . '\'">
                        <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </button>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
                </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('backend.layouts.plan.index');
    }

    public function create()
    {
        return view('backend.layouts.plan.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
        ]);
        $validatedData['status'] = 'active';
        try {
            Plan::create($validatedData);
            flash()->success('Plan created Successfully');
            return redirect()->route('plans.index');
        } catch (Exception $e) {
            Log::error('PlanController::store ' . $e->getMessage());
            flash()->error('Plan creation failed');
            return redirect()->route('plans.index');
        }
    }

    public function edit(string $id)
    {
        $data = Plan::findOrFail($id);
        return view('backend.layouts.plan.edit', compact('data'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
        ]);
        try {
            $data = Plan::findOrFail($id);
            $data->update($validatedData);
            flash()->success('Plan updated Successfully');
            return redirect()->route('plans.index');
        } catch (Exception $e) {
            Log::error('PlanController::update ' . $e->getMessage());
            flash()->error('Plan update failed');
            return redirect()->route('plans.index');
        }
    }

    public function destroy(string $id)
    {
        $data = Plan::findOrFail($id);
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.'
        ]);
    }

    public function status(Request $request, $id)
    {
        $data = Plan::find($id);
        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.'
            ], 404);
        }
        $data->status = $data->status == 'active' ? 'inactive' : 'active';
        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.'
        ]);
    }
}
