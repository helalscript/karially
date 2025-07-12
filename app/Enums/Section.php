<?php

namespace App\Enums;

enum Section: string
{
    // Home page section 
    case Banner = 'banner';
    case HowItWork = 'how_it_work';
    case HowItWorkContainer = 'how_it_work_container';
    case SocialLinkContainer = 'social_link_container';
    
    case HeroSection = 'hero_section';
    case TrustSection = 'trust_section';
    case FeatureSection = 'feature_section';
    case WhyChooseUs = 'why_choose_us';
    case WhyChooseUsContainer = 'why_choose_us_container';
    case PrireSection = 'price_section';
    case SpotLightSection = 'spotlight_section';


}


