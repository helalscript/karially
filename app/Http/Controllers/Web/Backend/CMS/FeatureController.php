<?php

namespace App\Http\Controllers\Web\Backend\CMS;


use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $FeatureContainer = CMS::where('page', Page::HomePage)->where('section', Section::FeatureContainer)->first();
        if ($request->ajax()) {
            $data = CMS::where('page', Page::HomePage)->where('section', Section::FeatureContainerContent)->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($data) {
                    return '<img src="' . asset($data->image) . '" class="wh-40 rounded-3" alt="user">';
                })
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
                        <a type="button" href="' . route('cms.home_page.feature.edit', $data->id) . '"
                                class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                 ><i class="material-symbols-outlined fs-16 text-body">edit</i>
                            </a>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['image', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.cms.home_page.feature_container.index", compact('FeatureContainer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("backend.layouts.cms.home_page.feature_container.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'tags' => 'required|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:255', // Each tag should be a string
        ]);

        try {
            if ($request->hasFile('image')) {
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'feature', time() . '_' . getFileName($request->file('image')));
            }
            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it
            $validatedData['page'] = Page::HomePage->value;
            $validatedData['section'] = Section::FeatureContainerContent->value;
            CMS::create($validatedData);
            return redirect()->route('cms.home_page.feature.index')->with('success', 'FeatureContainer created Successfully');
        } catch (Exception $e) {
            Log::error("FeatureController::store" . $e->getMessage());
            flash()->error('FeatureContainer created Failed');
            return redirect()->route('cms.home_page.feature.index');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = CMS::findOrFail($id);

        return view("backend.layouts.cms.home_page.feature_container.edit", compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'button_text' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'tags' => 'required|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:255', // Each tag should be a string
        ]);


        try {
            $data = CMS::findOrFail($id);
            if ($request->hasFile('image')) {
                if ($data && $data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'feature', time() . '_' . getFileName($request->file('image')));
            }

            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it

            $data->update($validatedData);
            return redirect()->route('cms.home_page.feature.index')->with('success', 'Feature updated Successfully');
        } catch (Exception $e) {
            Log::error("FeatureController::store" . $e->getMessage());
            return redirect()->route('cms.home_page.feature.index')->with('error', 'Feature updated Failed');
        }
    }

    public function destroy(string $id)
    {
        $data = CMS::findOrFail($id);
        // check here BookingRequest hotel_id === identifyHotel
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }

        if (!empty($data->image)) {
            Helper::fileDelete(public_path($data->image));
        }

        $data->delete();

        return response()->json([
            "success" => true,
            "message" => "Item deleted successfully."
        ]);
    }

    public function status(Request $request, $id)
    {
        $data = CMS::find($id);
        if (empty($data)) {
            return response()->json([
                "success" => false,
                "message" => "Item not found."
            ], 404);
        }
        if ($data->status == 'active') {
            $data->status = 'inactive';
        } else {
            $data->status = 'active';
        }

        $data->save();
        return response()->json([
            'success' => true,
            'message' => 'Item status changed successfully.'
        ]);
    }

    // update main feature container
    public function featureContainerUpdate(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
        ]);

        try {
            $data = CMS::updateOrCreate(
                [
                    'page' => Page::HomePage->value,
                    'section' => Section::FeatureContainer->value,
                ],
                $validatedData
            );

            return redirect()->route('cms.home_page.feature.index')->with('success', 'Feature container update successfully');
        } catch (Exception $e) {
            Log::error("FeatureController::featureContainerUpdate" . $e->getMessage());
            return redirect()->route('cms.home_page.feature.index')->with('error', 'Feature container update unsuccessfully');
        }
    }
}
