<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coach;
use App\Models\CoachSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Coach::with('category')->latest();
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('avatar', function ($data) {
                    return '<img src="' . asset($data->avatar) . '" class="wh-40 rounded-3" alt="user">';
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
                         <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit" onclick="window.location.href=\'' . route('coaches.edit', $data->id) . '\'">
                         <i class="material-symbols-outlined fs-16 text-body">edit</i>
                        </button>
                        <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete" onclick="deleteRecord(event,' . $data->id . ')">
                        <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                        </button>
             
                </div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make(true);
        }
        return view("backend.layouts.coaches.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        return view("backend.layouts.coaches.create", compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:10240',
            'calendly_user_id' => 'required|string',
            'calendly_link' => 'required|url',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'instagram_link' => 'nullable|url',
            'facebook_link' => 'nullable|url',
            'linkedin_link' => 'nullable|url',
        ]);

        try {
            // Store the avatar image
            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'coaches', time() . '_' . getFileName($request->file('avatar')));
            }

            $coachData = [
                'name' => $validatedData['name'],
                'avatar' => $validatedData['avatar'] ?? null,
                'category_id' => $validatedData['category_id'],
                'calendly_user_id' => $validatedData['calendly_user_id'],
                'calendly_link' => $validatedData['calendly_link'],
                'bio' => $validatedData['bio'] ?? null,
                'email' => $validatedData['email'] ?? null,
            ];
            $coach = Coach::create($coachData);
            if ($request->has('instagram_link')) {
                CoachSocial::create([
                    'coach_id' => $coach->id,
                    'title' => 'Instagram',
                    'link' => $validatedData['instagram_link'],
                ]);
            }
            if ($request->has('facebook_link')) {
                CoachSocial::create([
                    'coach_id' => $coach->id,
                    'title' => 'Facebook',
                    'link' => $validatedData['facebook_link'],
                ]);
            }
            if ($request->has('linkedin_link')) {
                CoachSocial::create([
                    'coach_id' => $coach->id,
                    'title' => 'LinkedIn',
                    'link' => $validatedData['linkedin_link'],
                ]);
            }

            return redirect()->route('coaches.index')->with('success', 'Coach and coach socials created successfully');
        } catch (\Exception $e) {
            Log::error("CoachController::store" . $e->getMessage());
            return redirect()->route('coaches.index')->with(['error' => 'Coach and coach socials not created successfully']);
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
        $data = Coach::with('category', 'socials')->findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $socials = $data->socials->pluck('link', 'title')->toArray();
        return view("backend.layouts.coaches.edit", compact('data', 'categories', 'socials'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:10240',
            'calendly_user_id' => 'required|string',
            'calendly_link' => 'required|url',
            'bio' => 'nullable|string',
            'email' => 'nullable|email',
            'instagram_link' => 'nullable|url',
            'facebook_link' => 'nullable|url',
            'linkedin_link' => 'nullable|url',
        ]);
        try {
            $coach = Coach::findOrFail($id);
            $coachData = [
                'name' => $validatedData['name'],
                'category_id' => $validatedData['category_id'],
                'calendly_user_id' => $validatedData['calendly_user_id'],
                'calendly_link' => $validatedData['calendly_link'],
                'bio' => $validatedData['bio'] ?? null,
                'email' => $validatedData['email'] ?? null,
            ];
            $coach->update($coachData);

            if ($request->hasFile('avatar')) {
                // delete the old avatar if it exists
                if ($coach->avatar) {
                    Helper::fileDelete($coach->avatar);
                    // upload the new avatar
                    $coach->avatar = Helper::fileUpload($request->file('avatar'), 'coaches', time() . '_' . getFileName($request->file('avatar')));
                    $coach->save();
                }

            }
            $socialLinks = [
                'instagram_link' => 'Instagram',
                'facebook_link' => 'Facebook',
                'linkedin_link' => 'LinkedIn'
            ];

            foreach ($socialLinks as $linkKey => $title) {
                if ($request->has($linkKey)) {
                    CoachSocial::updateOrCreate(
                        ['coach_id' => $coach->id, 'title' => $title],
                        ['link' => $validatedData[$linkKey]]
                    );
                }
            }

            return redirect()->route('coaches.index')->with('success', 'Coach updated successfully');
        } catch (\Exception $e) {
            Log::error("CoachController::update" . $e->getMessage());
            return redirect()->route('coaches.index')->with(['error' => 'Coach not updated successfully']);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $coach = Coach::findOrFail($id);
            // delete the avatar if it exists
            if ($coach->avatar) {
                Helper::fileDelete($coach->avatar);
            }
            // delete the coach socials
            CoachSocial::where('coach_id', $id)->delete();
            // delete the coach
            $coach->delete();

            return redirect()->route('coaches.index')->with('success', 'Coach deleted successfully');
        } catch (\Exception $e) {
            Log::error("CoachController::destroy" . $e->getMessage());
            return redirect()->route('coaches.index')->with(['error' => 'Coach not deleted successfully']);
        }
    }
}
