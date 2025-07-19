<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('c_m_s')->insert([
            ['page' => 'home_page', 'section' => 'hero_section', 'title' => 'You shouldn\'t have to figure out your career on your own.', 'sub_title' => '10k+ people using Karially ai', 'image' => 'uploads/hero_section/1752312356-1752312356-gradiant-1.png', 'background_image' => NULL, 'description' => 'Karially gives you structure, strategy, and support so you always know what to do next and why it matters.', 'sub_description' => NULL, 'button_text' => 'Start a Free Trial', 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-12 08:58:43'), 'updated_at' => Carbon::parse('2025-07-17 05:33:06')],
            [
                'page' => 'home_page',
                'section' => 'trust_section',
                'title' => 'Need clarity on your next career move?  Get guidance from real experts',
                'sub_title' => 'We are trusted by',
                'image' => 'uploads/trust_section/1752730533-1752730533-a6cf9f7d5276aa133a5eaf94d30a3b04917a9c60.png',
                'background_image' => NULL,
                'description' => 'Talk through your career questions with a coach who understands your goals.',
                'sub_description' => NULL,
                'button_text' => 'Start today',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Software Engineers",
                    "Project Managers",
                    "Product Mangers",
                    "Customer success managers",
                    "Business",
                    "Analyst"
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-12 09:30:09'),
                'updated_at' => Carbon::parse('2025-07-17 05:35:33')
            ],
            ['page' => 'home_page', 'section' => 'faq_container', 'title' => 'FAQs', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Find detailed answers to the most commonly asked questions about creating and optimizing resumes with Resumate AI.', 'sub_description' => NULL, 'button_text' => 'Contact us', 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 02:58:37'), 'updated_at' => Carbon::parse('2025-07-17 06:04:36')],
            ['page' => 'home_page', 'section' => 'feature_container', 'title' => 'Our features', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Elevate your profile with smart resumes for faster results & more interviews.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 05:03:23'), 'updated_at' => Carbon::parse('2025-07-17 05:36:00')],
            ['page' => 'home_page', 'section' => 'why_choose_us', 'title' => 'Why choose us', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Built to help you stand out: Smarter resumes, faster results, more interviews.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 05:40:03'), 'updated_at' => Carbon::parse('2025-07-17 05:59:47')],
            ['page' => 'home_page', 'section' => 'spotlight_section', 'title' => 'Your perfect resume is a click away', 'sub_title' => NULL, 'image' => 'uploads/spotlight_section/1752732238-1752732238-minimalist-mixed-with-photo.png', 'background_image' => NULL, 'description' => 'Ditch blank pages and formatting woes. Resumate_ai helps you craft standout resumes.', 'sub_description' => NULL, 'button_text' => 'Start for free', 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 06:29:11'), 'updated_at' => Carbon::parse('2025-07-17 06:03:59')],
            ['page' => 'home_page', 'section' => 'price_section', 'title' => 'Simple Pricing', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Choose from our three plans – Basic, Standard and  Premium or contact us for more details about our custom plans.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 06:39:25'), 'updated_at' => Carbon::parse('2025-07-17 06:02:29')],
            ['page' => 'about_page', 'section' => 'hero_section', 'title' => 'You shou dn\'t have to figure out your career on your own.', 'sub_title' => NULL, 'image' => 'uploads/hero_section/1752732635-1752732635-image-8.png', 'background_image' => NULL, 'description' => 'Karially gives you structure, strategy, and support so you always know what to do next and why it matters.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 10:46:47'), 'updated_at' => Carbon::parse('2025-07-17 06:10:35')],
            ['page' => 'about_page', 'section' => 'spotlight_section', 'title' => 'Ready to land your dream job?', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Build a standout resume in minutes and take the first step toward your next big opportunity. Start now!', 'sub_description' => NULL, 'button_text' => 'Start for free', 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-14 11:04:39'), 'updated_at' => Carbon::parse('2025-07-17 06:14:02')],
            ['page' => 'about_page', 'section' => 'our_mission_container', 'title' => 'Saepe quibusdam volu', 'sub_title' => 'Fugiat et placeat', 'image' => 'uploads/our_mission/1752732718-1752732718-image-9.png', 'background_image' => NULL, 'description' => 'Et aliquid eligendi', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-15 03:55:54'), 'updated_at' => Carbon::parse('2025-07-17 06:11:58')],
            ['page' => 'home_page', 'section' => 'social_link_container', 'title' => 'facebook', 'sub_title' => NULL, 'image' => 'uploads/social_link/1752553307-1752553307-facebook-social-media-icon-3d-46.png', 'background_image' => NULL, 'description' => NULL, 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => 'https://www.facebook.com/husoaib12', 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-15 04:21:47'), 'updated_at' => Carbon::parse('2025-07-15 04:21:47')],
            ['page' => 'about_page', 'section' => 'success_story_container', 'title' => 'Your success, our goal', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'At ResuMate, we empower job seekers and simplify career growth to turn opportunities into achievements.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-15 06:10:05'), 'updated_at' => Carbon::parse('2025-07-17 06:12:15')],
            [
                'page' => 'feature_page',
                'section' => 'hero_section',
                'title' => 'Explore resources to  elevate your dream career',
                'sub_title' => NULL,
                'image' => NULL,
                'background_image' => NULL,
                'description' => 'Simplify resumes & boost job search confidence with Resumate AI.',
                'sub_description' => NULL,
                'button_text' => NULL,
                'link_url' => NULL,
                'tags' => json_encode([
                    "ATS-friendly",
                    "Real-time suggestions",
                    "Time-saving tool",
                    "Job-specific resumes"
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-15 06:24:19'),
                'updated_at' => Carbon::parse('2025-07-17 06:15:06')
            ],
            ['page' => 'price_page', 'section' => 'hero_section', 'title' => 'Real tools for real career growth.', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Whether you’re just getting started or ready for 1-on-1 guidance, Karially gives you the structure, clarity, and momentum to move your career forward — without the fluff.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-15 06:30:50'), 'updated_at' => Carbon::parse('2025-07-17 06:15:29')],
            ['page' => 'home_page', 'section' => 'login_section', 'title' => 'Joing in to Karially', 'sub_title' => NULL, 'image' => 'uploads/login_section/1752733083-1752733083-screenshot-2025-07-17-121740.png', 'background_image' => NULL, 'description' => 'Your smart companion for career growth and productivity.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-15 06:58:04'), 'updated_at' => Carbon::parse('2025-07-17 06:18:20')],
            [
                'page' => 'home_page',
                'section' => 'feature_container_content',
                'title' => 'Using an AI-powered resume writing',
                'sub_title' => NULL,
                'image' => 'uploads/feature/1752730746-1752730746-358ab53789a6b8dd2cb0c6be8d4273ffcf2eda58.png',
                'background_image' => NULL,
                'description' => 'Utilizes AI technology to create customized resumes that align with specific industry requirements and job positions.',
                'sub_description' => NULL,
                'button_text' => 'Try for free',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Generates resumes tailored to your desired job title and industry.",
                    "Uses AI to craft concise, impactful summaries.",
                    "Saves time by automating content creation without sacrificing quality."
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-17 05:39:06'),
                'updated_at' => Carbon::parse('2025-07-17 05:39:06')
            ],
            [
                'page' => 'home_page',
                'section' => 'feature_container_content',
                'title' => 'AI-Powered Cover Letter Writing',
                'sub_title' => NULL,
                'image' => 'uploads/feature/1752730915-1752730915-ee83f4661a85bdedd08b389cd364b67b71116e35.png',
                'background_image' => NULL,
                'description' => 'Craft personalized cover letters that highlight your strengths and align with the job you’re targeting',
                'sub_description' => NULL,
                'button_text' => 'Customize for free',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Uses AI to tailor your message based on your resume and job description.",
                    "Ensures tone, structure, and keywords resonate with recruiters.",
                    "Saves time while keeping your application professional and impactful."
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-17 05:41:55'),
                'updated_at' => Carbon::parse('2025-07-17 05:41:55')
            ],
            [
                'page' => 'home_page',
                'section' => 'feature_container_content',
                'title' => 'AI-Powered Career Planning',
                'sub_title' => NULL,
                'image' => 'uploads/feature/1752731801-1752731801-d5c43a6be219a3487844ff52a7867b6489a0ddf2.png',
                'background_image' => NULL,
                'description' => 'Discover your ideal career path with a step-by-step roadmap tailored to your goals.',
                'sub_description' => NULL,
                'button_text' => 'Try for free',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Analyzes your skills, experience, and aspirations using AI.",
                    "Recommends roles, certifications, and weekly action steps.",
                    "Keeps you on track with clear milestones and progress tracking."
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-17 05:56:41'),
                'updated_at' => Carbon::parse('2025-07-17 05:56:41')
            ],
            [
                'page' => 'home_page',
                'section' => 'feature_container_content',
                'title' => 'Weekly Goal Creation Made Simple',
                'sub_title' => NULL,
                'image' => 'uploads/feature/1752731881-1752731881-b3a0c67b716b7e9e83191720b18e87d64d7716c0.png',
                'background_image' => NULL,
                'description' => 'Turn your career roadmap into achievable weekly tasks with ease.',
                'sub_description' => NULL,
                'button_text' => 'Try for free',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Breaks down long-term goals into manageable, AI-suggested actions.",
                    "Helps you prioritize and track progress every week.",
                    "Stay motivated and aligned with your growth plan."
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-17 05:58:01'),
                'updated_at' => Carbon::parse('2025-07-17 05:58:01')
            ],
            [
                'page' => 'home_page',
                'section' => 'feature_container_content',
                'title' => '1:1 Expert Coaching Calls',
                'sub_title' => NULL,
                'image' => 'uploads/feature/1752731970-1752731970-883846b7a456da1210bc92d338a9e67d9be0526e.png',
                'background_image' => NULL,
                'description' => 'Get personalized guidance from career coaches when you need it most.',
                'sub_description' => NULL,
                'button_text' => 'Try for free',
                'link_url' => NULL,
                'tags' => json_encode([
                    "Book monthly sessions to review progress, refine your strategy,",
                    "gain insights tailored to your goals",
                    "Exclusively available to premium members via Calendly."
                ]),
                'status' => 'active',
                'created_at' => Carbon::parse('2025-07-17 05:59:30'),
                'updated_at' => Carbon::parse('2025-07-17 05:59:30')
            ],
            ['page' => 'home_page', 'section' => 'why_choose_us_container', 'title' => 'Save time', 'sub_title' => NULL, 'image' => 'uploads/why_choose_us/1752732053-1752732053-frame-61.png', 'background_image' => NULL, 'description' => 'Create standout, impressive professional resumes in under 5 minutes.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:00:53'), 'updated_at' => Carbon::parse('2025-07-17 06:00:53')],
            ['page' => 'home_page', 'section' => 'why_choose_us_container', 'title' => 'Increase success', 'sub_title' => NULL, 'image' => 'uploads/why_choose_us/1752732108-1752732108-frame-61-2.png', 'background_image' => NULL, 'description' => 'Boost your ATS success with optimized designs, tailored keywords, and engaging visuals.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:01:48'), 'updated_at' => Carbon::parse('2025-07-17 06:01:48')],
            ['page' => 'home_page', 'section' => 'why_choose_us_container', 'title' => 'Personalized to you', 'sub_title' => NULL, 'image' => 'uploads/why_choose_us/1752732130-1752732130-frame-61-1.png', 'background_image' => NULL, 'description' => 'Tailored resumes that highlight your goals, skills, and achievements to help you shine.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:02:10'), 'updated_at' => Carbon::parse('2025-07-17 06:02:10')],
            ['page' => 'about_page', 'section' => 'our_mission_container_content', 'title' => '2024', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Launched', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:11:31'), 'updated_at' => Carbon::parse('2025-07-17 06:11:31')],
            ['page' => 'about_page', 'section' => 'our_mission_container_content', 'title' => '$2.1M', 'sub_title' => NULL, 'image' => NULL, 'background_image' => NULL, 'description' => 'Pre-seed round', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:11:46'), 'updated_at' => Carbon::parse('2025-07-17 06:11:46')],
            ['page' => 'about_page', 'section' => 'success_story_container_content', 'title' => 'Empowering job seekers', 'sub_title' => NULL, 'image' => 'uploads/success_story/1752732762-1752732762-image-10.png', 'background_image' => NULL, 'description' => 'We prioritize creating tools that give job seekers a competitive edge, helping them craft standout resumes that showcase their unique strengths and talents.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:12:42'), 'updated_at' => Carbon::parse('2025-07-17 06:12:42')],
            ['page' => 'about_page', 'section' => 'success_story_container_content', 'title' => 'Simplifying career growth', 'sub_title' => NULL, 'image' => 'uploads/success_story/1752732783-1752732783-image-11.png', 'background_image' => NULL, 'description' => 'We believe the job search process should be easy and stress-free. Our AI-powered platform simplifies resume creation and career planning so users can focus on their goals.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:13:03'), 'updated_at' => Carbon::parse('2025-07-17 06:13:03')],
            ['page' => 'about_page', 'section' => 'success_story_container_content', 'title' => 'Driving real results', 'sub_title' => NULL, 'image' => 'uploads/success_story/1752732798-1752732798-image-12.png', 'background_image' => NULL, 'description' => 'We’re committed to building ATS-friendly resumes and actionable tools that connect users with real opportunities, ensuring they land interviews and achieve their career aspirations.', 'sub_description' => NULL, 'button_text' => NULL, 'link_url' => NULL, 'tags' => NULL, 'status' => 'active', 'created_at' => Carbon::parse('2025-07-17 06:13:18'), 'updated_at' => Carbon::parse('2025-07-17 06:13:18')],
        ]);
    }
}


