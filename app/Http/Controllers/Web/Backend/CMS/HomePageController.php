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

class HomePageController extends Controller
{
    public function getHeroSection()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::HeroSection->value)
            ->first();
        return view('backend.layouts.cms.home_page.hero_section.create', compact('data'));
    }
    public function updateHerosection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'button_text' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $data = CMS::where('page', Page::HomePage->value)
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
                    'page' => Page::HomePage->value,
                    'section' => Section::HeroSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.home_page.hero_section.create')->with('success', 'Hero Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateHeroSection" . $e->getMessage());
            return redirect()->route('cms.home_page.hero_section.create')->with('error', 'Hero Section not Updated Successfully');
        }
    }

    public function getTrustSection()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::TrustSection->value)
            ->first();
        return view('backend.layouts.cms.home_page.trust_section.create', compact('data'));
    }
    public function updateTrustSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'sub_title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'button_text' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'tags' => 'nullable|array', // Assuming tags is an array of strings
            'tags.*' => 'string|max:50', // Each tag should be a string
        ]);
        // dd($validatedData);
        try {
            $data = CMS::where('page', Page::HomePage->value)
                ->where('section', Section::TrustSection->value)
                ->first();
            if ($request->hasFile('image')) {
                if ($data && $data->image && file_exists(public_path($data->image))) {
                    Helper::fileDelete(public_path($data->image));
                }
                $validatedData['image'] = Helper::fileUpload($request->file('image'), 'trust_section', time() . '_' . getFileName($request->file('image')));
            }
            $validatedData['tags'] = $request->input('tags') ? json_encode($request->input('tags')) : null; // Ensure tags is an array, even if empty, and json encode it
            // dd($validatedData);
            // Update or create the CMS entry for the trust section
            CMS::updateOrCreate(
                [
                    'page' => Page::HomePage->value,
                    'section' => Section::TrustSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.home_page.trust_section.create')->with('success', 'Trust Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateTrustSection" . $e->getMessage());
            return redirect()->route('cms.home_page.trust_section.create')->with('error', 'Trust Section not Updated Successfully');
        }

    }

    public function getSpotlight()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::SpotLightSection->value)
            ->first();
        return view('backend.layouts.cms.home_page.spotlight_section.create', compact('data'));
    }
    public function updateSpotlight(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'button_text' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        try {
            $data = CMS::where('page', Page::HomePage->value)
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
                    'page' => Page::HomePage->value,
                    'section' => Section::SpotLightSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.home_page.spotlight_section.create')->with('success', 'Spotlight Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updateSpotlight" . $e->getMessage());
            return redirect()->route('cms.home_page.spotlight_section.create')->with('error', 'Spotlight Section not Updated Successfully');
        }
    }
    public function getPriceSection()
    {
        $data = CMS::where('page', Page::HomePage->value)
            ->where('section', Section::PriceSection->value)
            ->first();
        return view('backend.layouts.cms.home_page.price_section.create', compact('data'));
    }
    public function updatePriceSection(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ]);

        try {
            // Update or create the CMS entry for the price section
            CMS::updateOrCreate(
                [
                    'page' => Page::HomePage->value,
                    'section' => Section::PriceSection->value,
                ],
                $validatedData
            );
            return redirect()->route('cms.home_page.price_section.create')->with('success', 'Price Section Updated Successfully');
        } catch (Exception $e) {
            Log::error("HomePageController::updatePriceSection" . $e->getMessage());
            return redirect()->route('cms.home_page.price_section.create')->with('error', 'Price Section not Updated Successfully');
        }
    }
}
