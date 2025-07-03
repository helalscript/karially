<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Feature;
use Illuminate\Http\Request;
use Exception;

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
                    $status .= '<input onclick="changeStatus(event,' . $data->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px"' . $data->id . '" name="status"';
                    if ($data->status == "active") {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '</div>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">
                        <a href="' . route('plans.edit', $data->id) . '" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" onclick="deleteRecord(event,' . $data->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>
                    </div>';
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('backend.layouts.plan.index');
    }

    public function create()
    {
        $features = Feature::all();
        return view('backend.layouts.plan.create', compact('features'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'features' => 'array'
        ]);
        try {
            $validated['slug'] = str()->slug($validated['title']);
            $plan = Plan::create($validated);
            if ($request->has('features')) {
                $plan->features()->sync($request->features);
            }
            flash()->success('Plan created successfully');
            return redirect()->route('plans.index');
        } catch (Exception $e) {
            Log::error('PlanController::store'.$e->getMessage());
            flash()->error('Plan creation failed');
            return redirect()->back();
        }
    }

    public function edit($id)
    {
        $plan = Plan::with('features')->findOrFail($id);
        $features = Feature::all();
        return view('backend.layouts.plan.edit', compact('plan','features'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'features' => 'array'
        ]);
        try {
            $validated['slug'] = str()->slug($validated['title']);
            $plan = Plan::findOrFail($id);
            $plan->update($validated);
            $plan->features()->sync($request->features ?? []);
            flash()->success('Plan updated successfully');
            return redirect()->route('plans.index');
        } catch (Exception $e) {
            Log::error('PlanController::update'.$e->getMessage());
            flash()->error('Plan update failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->features()->detach();
        $plan->delete();
        return response()->json(['success' => true, 'message' => 'Plan deleted successfully']);
    }

    public function status(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->status = $plan->status == 'active' ? 'inactive' : 'active';
        $plan->save();
        return response()->json(['success' => true, 'message' => 'Status updated']);
    }
}
