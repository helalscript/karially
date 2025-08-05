<?php

namespace App\Http\Controllers\API\V1\CMS;

use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CMS\GetFeatureSectionResource;
use App\Models\CMS;
use Exception;
use Illuminate\Support\Facades\Log;

class AboutPageController extends Controller
{
    public function getHeroSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description', 'image', )
                ->where('page', Page::AboutPage)
                ->where('section', Section::HeroSection)
                ->where('status', 'active')
                ->first();
            return Helper::jsonResponse(true, 'Data fatced successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHeroSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    public function getOurMissionSection()
    {
        try {
            $section = CMS::select('id', 'sub_title', 'title', 'image', 'description')
                ->where([
                    ['page', Page::AboutPage],
                    ['section', Section::OurMissionContainer],
                    ['status', 'active']
                ])
                ->first();

            $contents = CMS::select('id', 'title', 'description')
                ->where([
                    ['page', Page::AboutPage],
                    ['section', Section::OurMissionContainerContent],
                    ['status', 'active']
                ])
                ->get();

            // Use Laravel Collection to pass data
            $data = collect([
                'id' => $section->id,
                'section_title' => $section->sub_title,
                'title' => $section->title,
                'image' => $section->image,
                'description' => $section->description,
                'contents' => $contents,
            ]);

            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getFeatureSection - " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getSuccessStorySection()
    {
        try {
            $section = CMS::select('id', 'title', 'description')
                ->where([
                    ['page', Page::AboutPage],
                    ['section', Section::SuccessStoryContainer],
                    ['status', 'active']
                ])
                ->first();

            $contents = CMS::select('id', 'title', 'description', 'image')
                ->where([
                    ['page', Page::AboutPage],
                    ['section', Section::SuccessStoryContainerContent],
                    ['status', 'active']
                ])
                ->get();

            // Use Laravel Collection to pass data
            $data = collect([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description,
                'contents' => $contents,
            ]);

            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getFeatureSection - " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getSpotlightSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description', 'button_text')
                ->where([
                    ['page', Page::AboutPage],
                    ['section', Section::SpotLightSection],
                    ['status', 'active']
                ])
                ->first();
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getFeatureSection - " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }

    public function getFeaturePageHeroSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description', 'tags')
                ->where('page', Page::FeaturePage)
                ->where('section', Section::HeroSection)
                ->where('status', 'active')
                ->first();
            $data['tags'] = json_decode($data->tags);
            return Helper::jsonResponse(true, 'Data fatced successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHeroSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getFeaturePageFeatureSection()
    {
        try {
            $section = CMS::select('id', 'title', 'description')
                ->where('page', Page::FeaturePage)
                ->where('section', Section::FeatureContainer)
                ->where('status', 'active')
                ->first();
            $contents = CMS::select('id', 'title', 'description', 'image', 'button_text', 'tags')
                ->where([
                    ['page', Page::HomePage],
                    ['section', Section::FeatureContainerContent],
                    ['status', 'active']
                ])
                ->get();

            // Use Laravel Collection to pass data
            $data = collect([
                'id' => $section->id,
                'title' => $section->title,
                'description' => $section->description,
                'contents' => $contents,
            ]);

            return Helper::jsonResponse(true, 'Data fetched successfully', 200, new GetFeatureSectionResource($data));
            // return Helper::jsonResponse(true, 'Data fatced successfully', 200, $data ?? []);
        } catch (Exception $e) {
            Log::error("HomePageController::getHeroSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getPricePageHeroSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description')
                ->where('page', Page::PricePage)
                ->where('section', Section::HeroSection)
                ->where('status', 'active')
                ->first();
            return Helper::jsonResponse(true, 'Data fatced successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getHeroSection" . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }




}
