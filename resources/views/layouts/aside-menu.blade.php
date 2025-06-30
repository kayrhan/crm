<style>
    .contracts-slide .sub-side-menu__item {
        padding-left: 40px !important;
    }

    .contracts-slide .sub-slide-menu .sub-slide-item {
        padding-left: 55px !important;
    }

    .contracts-slide .sub-slide-menu .sub-side-menu__item {
        padding-left: 55px !important;
    }

    .custom-slide {
        line-height: 20px !important;
    }

    .custom-slide > a {
        line-height: 20px !important;
    }

    .custom-slide > a span {
        line-height: 20px !important;
    }

    .app-sidebar3 {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

</style>

<aside class="app-sidebar" style="overflow-y: auto;">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ url('/tickets') }}">
            <img src="{{ URL::asset('assets/images/brand/getucon-logo.png') }}" class="header-brand-img desktop-lgo" alt="getucon Logo" id="noclick">
        </a>
    </div>
    <div class="app-sidebar__user">

        @if (url('/') == 'https://dev.getucon.com' || url('/') == 'http://dev.getucon.com' || url('/') == 'dev.getucon.com')
            <h6 style="color:white; background: red;" class="display-4 mb-2 font-weight-bold text-center">DEV</h6>
        @endif
        <div class="dropdown user-pro-body text-center">
            <div class="user-pic">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->first_name }} {{ auth()->user()->surname }}&background=5E72E4&color=fff&size=64"
                    alt="user-img" class="avatar-xl rounded-circle mb-1" id="noclick">
            </div>
            <div class="user-info">
                <h5 class=" mb-1">{{ auth()->user()->first_name }} {{ auth()->user()->surname }} <i
                        class="ion-checkmark-circled  text-success fs-12"></i></h5>
            </div>
            @unless(in_array(auth()->user()->role_id, [6, 8]))
            <div class="user-info">
                {{auth()->user()->role->name}}
            </div>
            @endunless
        </div>
    </div>

    {{-- Sidebar --}}
    <ul class="side-menu app-sidebar3">
        <li class="side-item side-item-category mt-4">{{ trans('words.main') }}</li>

        {{-- Own Todos --}}
        @if ((auth()->user()->org_id == 8 or auth()->user()->org_id == 7 or auth()->user()->org_id == 3) && auth()->user()->role_id != 7)
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/todos') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.owns_to_do') }}'s</span>
                </a>
            </li>
        @endif

        {{-- Dashboard --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/dashboard') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v2h-4V5h4M9 5v6H5V5h4m10 8v6h-4v-6h4M9 17v2H5v-2h4M21 3h-8v6h8V3zM11 3H3v10h8V3zm10 8h-8v10h8V11zm-10 4H3v6h8v-6z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.dashboard') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a href="{{ url('/dashboard') }}" class="slide-item">Dashboard</a></li>
                    <li><a href="{{ url('/dashboard/proofed') }}" class="slide-item">Done Tickets</a></li>
                </ul>
            </li>
        @endif

        {{-- Admin --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <circle cx="11" cy="8" opacity=".3" r="2"></circle>
                        <path d="M5 18h4.99L9 17l.93-.94C7.55 16.33 5.2 17.37 5 18z" opacity=".3"></path>
                        <path
                            d="M11 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm-1 12H5c.2-.63 2.55-1.67 4.93-1.94h.03l.46-.45L12 14.06c-.39-.04-.68-.06-1-.06-2.67 0-8 1.34-8 4v2h9l-2-2zm10.6-5.5l-5.13 5.17-2.07-2.08L12 17l3.47 3.5L22 13.91z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{ trans('words.admin') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a href="{{ url('/vip-organizations') }}" class="slide-item">VIP Organizations</a></li>
                </ul>
            </li>
        @endif

        {{-- Organitations --}}
        @if (in_array('VIEW_ORGANIZATIONS', auth()->user()->Permissions))
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/organizations') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M6.5 10h-2v7h2v-7zm6 0h-2v7h2v-7zm8.5 9H2v2h19v-2zm-2.5-9h-2v7h2v-7zm-7-6.74L16.71 6H6.29l5.21-2.74m0-2.26L2 6v2h19V6l-9.5-5z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.organizations') }}</span>
                </a>
            </li>
        @endif

        {{-- External Partners --}}
        @if (in_array('VIEW_ORGANIZATIONS', auth()->user()->Permissions))
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/external-partners') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v14H5V5h14m0-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3zm0-4c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm6 10H6v-1.53c0-2.5 3.97-3.58 6-3.58s6 1.08 6 3.58V18zm-9.69-2h7.38c-.69-.56-2.38-1.12-3.69-1.12s-3.01.56-3.69 1.12z" />
                    </svg>
                    <span class="side-menu__label">External Partners</span>
                </a>
            </li>
        @endif

        {{-- Freelencers --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/freelancers') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v14H5V5h14m0-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3zm0-4c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm6 10H6v-1.53c0-2.5 3.97-3.58 6-3.58s6 1.08 6 3.58V18zm-9.69-2h7.38c-.69-.56-2.38-1.12-3.69-1.12s-3.01.56-3.69 1.12z" />
                    </svg>
                    <span class="side-menu__label">Freelancers</span>
                </a>
            </li>
        @endif

        {{-- Users --}}
        @if (in_array('VIEW_USERS', auth()->user()->Permissions))
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/users') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M12 16c-2.69 0-5.77 1.28-6 2h12c-.2-.71-3.3-2-6-2z" opacity=".3" />
                        <circle cx="12" cy="8" opacity=".3" r="2" />
                        <path
                            d="M12 14c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4zm-6 4c.22-.72 3.31-2 6-2 2.7 0 5.8 1.29 6 2H6zm6-6c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.users') }}</span>
                </a>
            </li>
        @endif

        {{-- Roles --}}
        @if (in_array('VIEW_ROLES', auth()->user()->Permissions))
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/roles') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M19 5v14H5V5h14m0-2H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 9c-1.65 0-3-1.35-3-3s1.35-3 3-3 3 1.35 3 3-1.35 3-3 3zm0-4c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm6 10H6v-1.53c0-2.5 3.97-3.58 6-3.58s6 1.08 6 3.58V18zm-9.69-2h7.38c-.69-.56-2.38-1.12-3.69-1.12s-3.01.56-3.69 1.12z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.roles') }}</span>
                </a>
            </li>
        @endif

        {{-- Calendar --}}
        @if (auth()->user()->org_id == 7 or auth()->user()->org_id == 8 or auth()->user()->org_id == 3)
            <li class="slide">
                <a class="side-menu__item" href="/calendar">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                    <span class="side-menu__label">Calendar</span>
                </a>
            </li>
        @endif

        {{-- Tickets --}}
        @if (in_array('VIEW_TICKETS', auth()->user()->Permissions))
            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 3)
                <li class="slide">
                    <a class="side-menu__item" data-toggle="slide" href="{{ url('/') }}">
                        <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                            width="24">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                        </svg>
                        <span class="side-menu__label">{{ trans('words.tickets') }}</span><i
                            class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li><a href="{{ url('/') }}"
                                class="slide-item">{{ trans('words.all_tickets') }}</a></li>
                        <li><a href="{{ url('/add-ticket') }}" class="slide-item">New Ticket</a></li>
                    </ul>
                </li>
            @else
                <li class="slide">
                    <a class="side-menu__item" href="{{ url('/tickets') }}">
                        <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                            width="24">
                            <path d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                        </svg>
                        <span class="side-menu__label">{{ trans('words.tickets') }}</span>
                    </a>
                </li>
            @endif
        @endif

        {{-- Attachments --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/ticket-attachment') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path
                            d="M12.5 23c3.04 0 5.5-2.46 5.5-5.5V6h-1.5v11.5c0 2.21-1.79 4-4 4s-4-1.79-4-4V5c0-1.38 1.12-2.5 2.5-2.5s2.5 1.12 2.5 2.5v10.5c0 .55-.45 1-1 1s-1-.45-1-1V6H10v9.5c0 1.38 1.12 2.5 2.5 2.5s2.5-1.12 2.5-2.5V5c0-2.21-1.79-4-4-4S7 2.79 7 5v12.5c0 3.04 2.46 5.5 5.5 5.5z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.attachments') }}</span>
                </a>
            </li>
        @endif

        {{-- Package Tracking --}}
        @if ((auth()->user()->org_id == 7 || auth()->user()->org_id == 8 || auth()->user()->org_id == 3) && auth()->user()->role_id != 7)
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/package-tracking') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0zm18.31 6l-2.76 5z" fill="none"></path>
                        <path
                            d="M11 9h2V6h3V4h-3V1h-2v3H8v2h3v3zm-4 9c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zm10 0c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2zm-9.83-3.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.86-7.01L19.42 4h-.01l-1.1 2-2.76 5H8.53l-.13-.27L6.16 6l-.95-2-.94-2H1v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.13 0-.25-.11-.25-.25z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{ trans('words.package_tracking') }}</span>
                </a>
            </li>
        @endif

        {{-- Post Box --}}
        @if ((auth()->user()->org_id == 7 or auth()->user()->org_id == 8 or auth()->user()->org_id == 3) && auth()->user()->role_id != 7)
            <li class="slide">
                <a class="side-menu__item" href="/post-box">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path d="M12 15.36l-8-5.02V18h16l-.01-7.63z" opacity=".3"></path>
                        <path
                            d="M21.99 8c0-.72-.37-1.35-.94-1.7L12 1 2.95 6.3C2.38 6.65 2 7.28 2 8v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2l-.01-10zM12 3.32L19.99 8v.01L12 13 4 8l8-4.68zM4 18v-7.66l8 5.02 7.99-4.99L20 18H4z">
                        </path>
                    </svg>
                    <span class="side-menu__label">Post-box</span>
                </a>
            </li>
        @endif

        {{-- Accounting --}}
        @if(in_array(auth()->id(), [5, 86, 119, 158, 161, 199, 201, 202]))
            <li class="slide">
                <a class="side-menu__item" id="accountingBtn" style="cursor:pointer;">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                         width="24">
                        <path d="M-74 29h48v48h-48V29zM0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M13 12h7v1.5h-7zm0-2.5h7V11h-7zm0 5h7V16h-7zM21 4H3c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 15h-9V6h9v13z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{ trans('words.accounting') }}</span>
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        @endif


        @role([1,2])
            <li class="slide">
                <a class="side-menu__item" href="/offers">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path
                            d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{ trans('words.offers') }}</span>
                </a>
            </li>
        @endif

        {{-- Contracts --}}
        @role([1,2])
            <li class="slide custom-slide">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0)">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0z" fill="none" />
                        <path
                            d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('words.contracts') }}</span>
                    <i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu contracts-slide">
                    <li class="sub-slide">
                        <a class="sub-side-menu__item" data-toggle="sub-slide" href="javascript:void(0)" style="line-height: 20px !important;">
                            <span class="sub-side-menu__label">Contract getucon GmbH</span>
                            <i class="sub-angle fe fe-chevron-down"></i>
                        </a>

                        <ul class="sub-slide-menu">
                            <li>
                                <a href="{{ url('/contracts/getucon-de') }}" class="sub-slide-item">All
                                    Contracts</a>
                            </li>
                            <li>
                                <a class="sub-side-menu__item collapsed" data-toggle="collapse"
                                    data-target="#dsgvo-sub-slide" href="javascript:void(0)">
                                    <span class="sub-side-menu__label">DSGVO</span>
                                    <i class="sub-angle fe fe-chevron-down"></i>
                                </a>
                                <ul class="collapse" id="dsgvo-sub-slide">
                                    <li class="pl-4">
                                        <a href="{{ url('/contracts/file/getucon/dsgvo/pdf') }}"
                                            class="sub-slide-item" target="_blank">Pdf</a>
                                    </li>
                                    <li class="pl-4">
                                        <a href="{{ url('/contracts/file/getucon/dsgvo/doc') }}"
                                            class="sub-slide-item">Doc</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="sub-side-menu__item collapsed mb-3" data-toggle="collapse"
                                    data-target="#backup-sub-slide" href="javascript:void(0)" style="line-height: 20px !important;">
                                    <span class="sub-side-menu__label">Backup Disclaimer</span>
                                    <i class="sub-angle fe fe-chevron-down" style="margin-top: -10px;"></i>
                                </a>
                                <ul class="collapse" id="backup-sub-slide">
                                    <li class="pl-4">
                                        <a href="{{ url('/contracts/file/getucon/backup/pdf') }}"
                                            class="sub-slide-item" target="_blank">Pdf</a>
                                    </li>
                                    <li class="pl-4">
                                        <a href="{{ url('/contracts/file/getucon/backup/doc') }}"
                                            class="sub-slide-item">Doc</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="{{ url('/contracts/getucon-de/2') }}"
                                    class="sub-slide-item">Service-Support-Maintenance </a>
                            </li>
                            <li>
                                <a href="{{ url('/contracts/getucon-de/1') }}" class="sub-slide-item">DataCenter</a>
                            </li>
                            <li>
                                <a href="{{ url('/contracts/getucon-de/5') }}" class="sub-slide-item">Leasing
                                    Firewall</a>
                            </li>
                            <li>
                                <a href="{{ url('/contracts/getucon-de/3') }}"
                                    class="sub-slide-item">Non-Contract</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        @endif

        {{-- Service Monitor --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" href="/services">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path
                            d="M19 12h-2v3h-3v2h5v-5zM7 9h3V7H5v5h2V9zm14-6H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16.01H3V4.99h18v14.02z">
                        </path>

                    </svg>
                    <span class="side-menu__label">Service Monitor</span>
                </a>
            </li>
        @endif

        {{-- Warehouse --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/stocks') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M-74 29h48v48h-48V29zM0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M13 12h7v1.5h-7zm0-2.5h7V11h-7zm0 5h7V16h-7zM21 4H3c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 15h-9V6h9v13z">
                        </path>
                    </svg>
                    <span class="side-menu__label">Warehouse</span><i class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li><a href="{{ url('/stocks') }}" class="slide-item">Stocks</a></li>
                    <li><a href="{{ url('/offices') }}" class="slide-item">Offices</a></li>
                </ul>
            </li>
        @endif

        {{-- Projects --}}
        {{-- <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="{{ url('') }}">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                    width="24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path
                        d="M16.66 4.52l2.83 2.83-2.83 2.83-2.83-2.83 2.83-2.83M9 5v4H5V5h4m10 10v4h-4v-4h4M9 15v4H5v-4h4m7.66-13.31L11 7.34 16.66 13l5.66-5.66-5.66-5.65zM11 3H3v8h8V3zm10 10h-8v8h8v-8zm-10 0H3v8h8v-8z" />
                </svg>
                <span class="side-menu__label">{{ trans('words.projects') }}</span><i
                    class="angle fa fa-angle-right"></i></a>
            <ul class="slide-menu">
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.all') }}
                        {{ trans('words.projects') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.new') }}
                        {{ trans('words.project') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.book_effort') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.milestones') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.project') }}
                        {{ trans('words.tickets') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.controlling') }}</a></li>
                <li><a href="{{ url('') }}" class="slide-item">{{ trans('words.reporting') }}</a></li>
            </ul>
        </li> --}}

        {{-- Asset Management --}}
        @role([1,2,3])
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0)">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                        width="24">
                        <path d="M-74 29h48v48h-48V29zM0 0h24v24H0V0zm0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M13 12h7v1.5h-7zm0-2.5h7V11h-7zm0 5h7V16h-7zM21 4H3c-1.1 0-2 .9-2 2v13c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 15h-9V6h9v13z">
                        </path>
                    </svg>
                    <span class="side-menu__label">Assets</span><i class="angle fa fa-angle-right"></i>
                </a>
                <ul class="slide-menu">
                    <li><a href="{{ url('/assets/getucon-de') }}" target="_blank" class="slide-item">Assets getucon GmbH</a></li>
                </ul>
            </li>
        @endif

        {{-- Reports --}}
        @if (in_array('VIEW_REPORTS', auth()->user()->Permissions))
            <li class="slide">
                <a class="side-menu__item" href="{{ url('/reports') }}">
                    <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                         width="24">
                        <path d="M0 0h24v24H0V0z" fill="none"></path>
                        <path
                            d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zM7 10h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z">
                        </path>
                    </svg>
                    <span class="side-menu__label">{{ trans('words.reports') }}</span>
                </a>
            </li>
        @endif

        {{-- Service Report --}}
        @role([1,2,3])
        <li class="slide">
            <a class="side-menu__item" href="/document-templates">
                <svg class="side-menu__icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                     width="24">
                    <path d="M0 0h24v24H0z" fill="none" />
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 6h-4c0 1.62-1.38 3-3 3s-3-1.38-3-3H5V5h14v4zm-4 7h6v3c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2v-3h6c0 1.66 1.34 3 3 3s3-1.34 3-3z"></path>
                </svg>
                <span class="side-menu__label">{{ trans('words.document_templates') }}</span>
            </a>
        </li>
        @endif
    </ul>
</aside>
