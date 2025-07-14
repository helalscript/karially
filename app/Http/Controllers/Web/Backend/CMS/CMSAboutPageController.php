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

class CMSAboutPageController extends Controller
{
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::AboutPage->value)
            ->where('section', Section::HeroSection->value)
            ->first();
        return view('backend.layouts.cms.about_page.hero_section.create', compact('data'));
    }
    public function updateHerosection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $data = CMS::where('page', Page::AboutPage->value)
                ->where('section', Section::HeroSection->value)
                ->first();

            if ($request->hasFile('image')) {
                if ($data && $data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'hero_section', time() . '_' . getFileName($request->file('image')));
            }
            // Update or create the CMS entry for the hero section
            CMS::updateOrCreate(
                [
                    'page' => Page::AboutPage->value,
                    'section' => Section::HeroSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.about_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("AboutPageController::updateHeroSection" . $e->getMessage());
            return redirect()->route('cms.about_page.hero_section.create')->with('error', 'Hero Section not Updated Successfully');
        }
    }
    public function getSpotlight()
    {
        $data = CMS::where('page', Page::AboutPage->value)
            ->where('section', Section::SpotLightSection->value)
            ->first();
        return view('backend.layouts.cms.about_page.spotlight_section.create', compact('data'));
    }
    public function updateSpotlight(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'button_text' => 'required|string|max:50',
            // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $data = CMS::where('page', Page::AboutPage->value)
                ->where('section', Section::SpotLightSection->value)
                ->first();
            if ($request->hasFile('image')) {
                if ($data && $data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'spotlight_section', time() . '_' . getFileName($request->file('image')));
            }

            // Update or create the CMS entry for the Spotlight section
            CMS::updateOrCreate(
                [
                    'page' => Page::AboutPage->value,
                    'section' => Section::SpotLightSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.about_page.spotlight_section.create')->with('success', 'Spotlight Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("CMSAboutPageController::updateSpotlight" . $e->getMessage());
            return redirect()->route('cms.about_page.spotlight_section.create')->with('error', 'Spotlight Section not Updated Successfully');
        }
    }
}
