<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Feature;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\DataTables;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Package::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('price', function ($data) {
                    return '$' . number_format($data->price, 2);
                })
                ->addColumn('status', function ($data) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="changeStatus(event,' . $data->id . ')" type="checkbox" class="form-check-input" style="border-radius: 25rem;width:40px"' . $data->id . ' name="status"';
                    if ($data->status == "active") {
                        $status .= ' checked';
                    }
                    $status .= '>';
                    $status .= '</div>';
                    return $status;
                })
                ->addColumn('action', function ($data) {
                    return '<div class="action-wrapper">'
                        . '<a type="button" href="' . route('packages.edit', $data->id) . '" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"><i class="material-symbols-outlined fs-16 text-body">edit</i></a>'
                        . '<button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')"><i class="material-symbols-outlined fs-16 text-danger">delete</i></button>'
                        . '</div>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.packages.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $features = Feature::where('status', 'active')->get();
        return view('backend.layouts.packages.create', compact('features'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {
            // dd($request->all());
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'trial_period_days' => 'nullable|integer',
                'billing_cycle' => 'required|in:monthly,yearly,lifetime',
                'price' => 'required|numeric|min:0',

                //package features
                'feature_id' => 'nullable|array|exists:features,id',
                'feature_id.*' => 'required|integer',
                'access_lavel' => 'nullable|array|in:view_only,read_only,unlimited,custom',
                'access_lavel.*' => 'required|in:view_only,read_only,unlimited,custom',
                'custom_tag' => 'nullable|array',
                'custom_tag.*' => 'nullable|string',
                'custom_value' => 'nullable|array',
                'custom_value.*' => 'nullable|integer|min:1',
                'features_description' => 'nullable|array',
                'features_description.*' => 'nullable|string|min:1',
            ]);
        } catch (ValidationException $e) {
            Log::error('PackageController::store - Validation failed: ' . $e->getMessage());
            // dd($e->errors()); // Shows all validation errors as array
        }


        // dd($validatedData);
        try {
            $package = Package::create([
                'title' => $validatedData['title'],
                'slug' => Helper::makeSlug($validatedData['title'], 'packages'),
                'trial_period_days' => $validatedData['trial_period_days'] ?? null,
                'billing_cycle' => $validatedData['billing_cycle'],
                'price' => $validatedData['price'],
            ]);
            // Handle package features if provided
            if (isset($validatedData['feature_id']) && count($validatedData['feature_id']) > 0) {
                foreach ($validatedData['feature_id'] as $index => $featureId) {
                    $accessLavel = $validatedData['access_lavel'][$index] ?? null;
                    Log::info(' with access level: ' . $accessLavel);
                    // Skip if access_lavel is not set or not in enum
                    if (!in_array($accessLavel, ['view_only', 'read_only', 'unlimited', 'custom'])) {
                        continue;
                    }

                    $package->features()->attach($featureId, [
                        'access_lavel' => $accessLavel,
                        'custom_tag' => $validatedData['custom_tag'][$index] ?? null,
                        'custom_value' => $validatedData['custom_value'][$index] ?? null,
                        'description' => $validatedData['features_description'][$index] ?? null,
                    ]);
                }
            }


            return redirect()->route('packages.index')->with('success', 'Package created successfully.');
        } catch (\Exception $e) {
            Log::error('PackageController::store - ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create package: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show package details if needed
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return redirect()->back()->with('warning', 'You cant changes a pakage details its already created in system and its not editable. If you want to add new package please create new package.');
        // return view('backend.layouts.packages.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Handle package update logic here
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $package = Package::with(['subscriptions'])
                ->where('id', $id)
                ->firstOrFail();

            if ($package->subscriptions->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Package cannot be deleted as it is associated with existing subscriptions.'
                ], 400);
            }
            // Delete the package
            $package->delete();

            return response()->json([
                'success' => true,
                'message' => 'Package deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete package.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function status(Request $request, $id)
    {
        $data = Package::find($id);

        if (empty($data)) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found.'
            ], 404);
        }

        $data->status = $data->status === 'active' ? 'inactive' : 'active';
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.'
        ]);
    }
}
