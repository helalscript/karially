<?php

namespace App\Http\Controllers\Web\Backend\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Models\CMS;
use Exception;

use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;


class CMSFeaturePageController extends Controller
{
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::FeaturePage->value)
            ->where('section', Section::HeroSection->value)
            ->first();
        return view('backend.layouts.cms.feature_page.hero_section.create', compact('data'));
    }
    public function updateHerosection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'tags' => 'nullable|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:50', // Each tag should be a string
        ]);

        try {
            $data = CMS::where('page', Page::FeaturePage->value)
                ->where('section', Section::HeroSection->value)
                ->first();

            if ($request->hasFile('image')) {
                if ($data && $data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'hero_section', time() . '_' . getFileName($request->file('image')));
            }
            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it

            // Update or create the CMS entry for the hero section
            CMS::updateOrCreate(
                [
                    'page' => Page::FeaturePage->value,
                    'section' => Section::HeroSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.feature_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("CMSFeaturePageController::updateHeroSection" . $e->getMessage());
            return redirect()->route('cms.feature_page.hero_section.create')->with('error', 'Hero Section not Updated Successfully');
        }
    }
}
