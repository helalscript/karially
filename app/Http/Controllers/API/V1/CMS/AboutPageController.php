<?php

namespace App\Http\Controllers\API\V1\CMS;


use App\Enums\Page;
use App\Enums\Section;
use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CMS\GetFeatureSectionResource;
use App\Models\CMS;
use App\Models\DynamicPage;
use App\Models\Faq;
use App\Models\SystemSetting;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class AboutPageController extends Controller
{
    public function getHeroSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description', 'image',)
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
            $faqs = Faq::select('id', 'question', 'answer')
                ->where('status', 'active')
                ->get();
            $data = CMS::select('id', 'title', 'description', 'button_text')
                ->where([
                    ['page', Page::HomePage],
                    ['section', Section::FaqContainer],
                    ['status', 'active']
                ])
                ->first();
            $data['faqs'] = $faqs;
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getFeatureSection - " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getSuccessStorySection()
    {
        try {
            $faqs = Faq::select('id', 'question', 'answer')
                ->where('status', 'active')
                ->get();
            $data = CMS::select('id', 'title', 'description', 'button_text')
                ->where([
                    ['page', Page::HomePage],
                    ['section', Section::FaqContainer],
                    ['status', 'active']
                ])
                ->first();
            $data['faqs'] = $faqs;
            return Helper::jsonResponse(true, 'Data fetched successfully', 200, $data);
        } catch (Exception $e) {
            Log::error("HomePageController::getFeatureSection - " . $e->getMessage());
            return Helper::jsonErrorResponse('Something went wrong', 500);
        }
    }
    public function getSpotlightSection()
    {
        try {
            $data = CMS::select('id', 'title', 'description', 'button_text', 'image')
                ->where([
                    ['page', Page::HomePage],
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
}
