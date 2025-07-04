<?php

namespace App\Http\Controllers\Web\Backend;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\CoachSocial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CoachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'avatar' => 'required|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'calendly_user_id' => 'required|string',
            'calendly_link' => 'required|url',
            'bio' => 'nullable|string',
            'social_title' => 'nullable|array|min:1',
            'social_title.*' => 'nullable|string',
            'social_logo' => 'nullable|array|min:1',
            'social_logo.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'social_link' => 'nullable|array|min:1',
            'social_link.*' => 'nullable|url',
        ]);
        try {
            // Store the avatar image
            if ($request->hasFile('avatar')) {
                $validatedData['avatar'] = Helper::fileUpload($request->file('avatar'), 'coaches', time() . '_' . getFileName($request->file('avatar')));
            }

            $coachData = [
                'name' => $validatedData['name'],
                'avatar' => $validatedData['image'] ?? null,
                'category_id' => $validatedData['category_id'],
                'email' => $validatedData['email'],
                'calendly_user_id' => $validatedData['calendly_user_id'],
                'calendly_link' => $validatedData['calendly_link'],
                'bio' => $validatedData['bio'] ?? null,
            ];
            $coach = Coach::create($coachData);

            if (isset($validatedData['social_title'])) {
                foreach ($validatedData['social_title'] as $index => $title) {

                    $logo = Helper::fileUpload($validatedData['social_logo'][$index] ?? null, 'social_logos', time() . '_' . getFileName($validatedData['social_logo'][$index] ?? null));

                    $link = $validatedData['social_link'][$index] ?? null;

                    CoachSocial::create([
                        'coach_id' => $coach->id,
                        'title' => $title,
                        'logo' => $logo ?? null,
                        'link' => $link,
                    ]);
                }
            }
            // flash()->success('Coach and coach socials created successfully');
            return redirect()->route('coaches.index')->with('success', 'Coach and coach socials created successfully');
        } catch (\Exception $e) {
            Log::error("CoachController::store" . $e->getMessage());
            // flash()->error('Coach and coach socials not created successfully');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
