{{-- @php
$systemSetting = App\Models\SystemSetting::first();
@endphp --}}
<!-- Start Sidebar Area -->
<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="{{ route('admin.dashboard') }}" class="d-block text-decoration-none position-relative">
            <img src="{{ asset($systemSetting->logo ?? 'backend/admin/assets/logo.png') }}" alt="logo-icon">
        </a>
        <button
            class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y"
            id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar style="height: calc(100vh - 100px)">
        <ul class="menu-inner">
            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">MAIN</span>
            </li>
            <!-- Dashboard Menu Item -->
            <li class="menu-item open">
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">dashboard</span>
                    <span class="title">Dashboard</span>
                </a>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">APPS</span>
            </li>
            <!-- Contact Support Message Menu Item -->
            {{-- <li class="menu-item open">
                <a href="{{ route('admin_contact_us.index') }}"
                    class="menu-link {{ request()->routeIs('admin_contact_us.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined menu-icon">content_paste</span>
                    <span class="title">Contact Support Message</span>
                </a>
            </li> --}}

            <!-- Coaches Menu Item -->
            <li class="menu-item {{ request()->routeIs('coaches.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">psychology</span>
                    <span class="title">Coaches</span>
                </a>
                <ul class="menu-sub">
                    <!-- Coaches Settings Submenu -->
                    <li class="menu-item ">
                        <a href="{{ route('coaches.index') }}"
                            class="menu-link {{ request()->routeIs('coaches.index') ? 'active' : '' }} ">
                            Coaches List
                        </a>
                    </li>

                </ul>
            </li>
            <!-- Category Menu Item -->
            <li class="menu-item {{ request()->routeIs('category.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">class</span>
                    <span class="title">Category</span>
                </a>
                <ul class="menu-sub">
                    <!-- Category Settings Submenu -->
                    <li class="menu-item ">
                        <a href="{{ route('category.index') }}"
                            class="menu-link {{ request()->routeIs('category.index') ? 'active' : '' }} ">
                            Category List
                        </a>
                    </li>

                </ul>
            </li>
            <!-- Packages Menu Item -->
            <li class="menu-item {{ request()->routeIs('packages.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">inventory_2</span>
                    <span class="title">Packages</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item ">
                        <a href="{{ route('packages.index') }}"
                            class="menu-link {{ request()->routeIs('packages.index') ? 'active' : '' }} ">
                            Package List
                        </a>
                    </li>
                </ul>
            </li>
            <!-- Packages Menu Item -->
            <li class="menu-item {{ request()->routeIs('newsletters.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">mail</span>
                    <span class="title">Newsletters</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item ">
                        <a href="{{ route('newsletters.index') }}"
                            class="menu-link {{ request()->routeIs('newsletters.index') ? 'active' : '' }} ">
                            List
                        </a>
                    </li>
                </ul>
            </li>


            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">OTHERS</span>
            </li>

            <!-- Settings Menu Item -->
            <li class="menu-item {{ request()->routeIs('profile_settings.*', 'system_settings.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">settings</span>
                    <span class="title">Settings</span>
                </a>
                <ul class="menu-sub">
                    <!-- Account Settings Submenu -->
                    <li class="menu-item ">
                        <a href="{{ route('profile_settings.index') }}"
                            class="menu-link {{ request()->routeIs('profile_settings.index') ? 'active' : '' }} ">
                            Profile Settings
                        </a>
                    </li>
                    <!-- Change Password Submenu -->
                    <li class="menu-item">
                        <a href="{{ route('profile_settings.password_change') }}"
                            class="menu-link {{ request()->routeIs('profile_settings.password_change') ? 'active' : '' }}">
                            Change Password
                        </a>
                    </li>
                    <!-- System Settings Configaration Submenu -->
                    <li class="menu-item">
                        <a href="{{ route('system_settings.index') }}"
                            class="menu-link {{ request()->routeIs('system_settings.*') ? 'active' : '' }}">
                            System Configaration
                        </a>
                    </li>

                </ul>
            </li>
            <!-- dynamic page Menu Item -->
            <li class="menu-item {{ request()->routeIs('dynamic_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">clarify</span>
                    <span class="title">Daynamic page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('dynamic_page.index') }}"
                            class="menu-link {{ request()->routeIs('dynamic_page.index') ? 'active' : '' }}">
                            Pages
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('dynamic_page.create') }}"
                            class="menu-link {{ request()->routeIs('dynamic_page.create') ? 'active' : '' }}">
                            Add New
                        </a>
                    </li>

                </ul>
            </li>

            <li class="menu-title small text-uppercase">
                <span class="menu-title-text">CMS</span>
            </li>
            <!-- Faqs Menu Item -->
            <li class="menu-item {{ request()->routeIs('faqs.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">help</span>
                    <span class="title">Faqs</span>
                </a>
                <ul class="menu-sub">
                    <!-- Account Settings Submenu -->
                    <li class="menu-item ">
                        <a href="{{ route('faqs.index') }}"
                            class="menu-link {{ request()->routeIs('faqs.index') ? 'active' : '' }} ">
                            Faqs List
                        </a>
                    </li>
                    <!-- Change Password Submenu -->
                    <li class="menu-item">
                        <a href="{{ route('faqs.create') }}"
                            class="menu-link {{ request()->routeIs('faqs.create') ? 'active' : '' }}">
                            Add New Faqs
                        </a>
                    </li>

                </ul>
            </li>
            <!-- CMS Home Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.home_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">handshake</span>
                    <span class="title">Home Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.social_link.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.social_link.*') ? 'active' : '' }}">
                            Social Link
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.trust_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.trust_section.*') ? 'active' : '' }}">
                            Trust Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.feature.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.feature.*') ? 'active' : '' }}">
                            Feature Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.why_choose_us.index') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.why_choose_us.*') ? 'active' : '' }}">
                            Why Choose Us Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.price_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.price_section.*') ? 'active' : '' }}">
                            Price Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.home_page.spotlight_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.home_page.spotlight_section.*') ? 'active' : '' }}">
                            Spotlight Section
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS About Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.about_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">assignment_ind</span>
                    <span class="title">About Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.about_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.about_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.about_page.our_mission.index') }}"
                            class="menu-link {{ request()->routeIs('cms.about_page.our_mission.*') ? 'active' : '' }}">
                            Our Mission Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.about_page.success_story.index') }}"
                            class="menu-link {{ request()->routeIs('cms.about_page.success_story.*') ? 'active' : '' }}">
                            Success Story Section
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('cms.about_page.spotlight_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.about_page.spotlight_section.*') ? 'active' : '' }}">
                            Spotlight Section
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Feature Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.feature_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">star</span>
                    <span class="title">Feature Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.feature_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.feature_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Price Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.price_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">local_atm</span>
                    <span class="title">Price Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.price_page.hero_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.price_page.hero_section.*') ? 'active' : '' }}">
                            Hero Section
                        </a>
                    </li>
                </ul>
            </li>
            <!-- CMS Login Page Menu Item -->
            <li class="menu-item {{ request()->routeIs('cms.auth_page.*') ? 'open' : '' }}">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">vpn_key</span>
                    <span class="title">Auth Page</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('cms.auth_page.login_section.create') }}"
                            class="menu-link {{ request()->routeIs('cms.auth_page.login_section.*') ? 'active' : '' }}">
                            SpotLight Section
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Logout Menu Item -->
            <li class="menu-item">
                <a class="menu-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form-asidebar').submit();">
                    <span class="material-symbols-outlined menu-icon">logout</span>
                    <span class="title">Logout</span>
                </a>
            </li>
        </ul>
        <form id="logout-form-asidebar" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </aside>
</div>
<!-- End Sidebar Area -->
