<style>
    .btn-shortcut {
        width: 6rem !important;
        font-size: 0.6rem !important;
        font-weight: bold !important;
        padding: 0.2rem;
    }

    .shortcut-dropdown{
        min-width: 6rem;
        font-size: 0.7rem;
    }
</style>
<div class="app-header header">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="header-brand" href="{{ url('/' . ($page = 'index')) }}">
                <img src="{{ URL::asset('assets/images/brand/getucon-logo.png') }}" class="header-brand-img desktop-lgo" alt="getucon Logo">
                <img src="{{ URL::asset('assets/images/brand/getucon-logo.png') }}" class="header-brand-img dark-logo" alt="getucon Logo">
                <img src="{{ URL::asset('assets/images/brand/getucon-logo.png') }}" class="header-brand-img mobile-logo" alt="getucon Logo">
                <img src="{{ URL::asset('assets/images/brand/getucon-logo.png') }}" class="header-brand-img darkmobile-logo" alt="getucon Logo">
            </a>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="{{ url('/' . ($page = '#')) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-left header-icon mt-1">
                        <line x1="17" y1="10" x2="3" y2="10"></line>
                        <line x1="21" y1="6" x2="3" y2="6"></line>
                        <line x1="21" y1="14" x2="3" y2="14"></line>
                        <line x1="17" y1="18" x2="3" y2="18"></line>
                    </svg>
                </a>
            </div>
            <div class="d-flex order-lg-2 ml-auto">
                <a href="{{ url('/' . ($page = '#')) }}" data-toggle="search" class="nav-link nav-link-lg d-md-none navsearch">
                    <svg class="header-icon search-icon" x="1008" y="1248" viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet" focusable="false">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" />
                    </svg>
                </a>
                <div class="dropdown header-fullscreen">
                    <a class="nav-link icon full-screen-link p-0" id="fullscreen-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10 4L8 4 8 8 4 8 4 10 10 10zM8 20L10 20 10 14 4 14 4 16 8 16zM20 14L14 14 14 20 16 20 16 16 20 16zM20 8L16 8 16 4 14 4 14 10 20 10z" />
                        </svg>
                    </a>
                </div>
                <div class="dropdown profile-dropdown">
                    <a href="{{ url('/' . ($page = '#')) }}" class="nav-link pr-0 leading-none" data-toggle="dropdown">
                        <span>
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->first_name }} {{ auth()->user()->surname }}&background=5E72E4&color=fff&size=40" alt="img" class="avatar avatar-md brround" id="noclick">
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow animated">
                        <div class="text-center">
                            <a href="{{ url('/' . ($page = 'dashboard')) }}"
                                class="dropdown-item text-center user pb-0 font-weight-bold">{{ auth()->user()->first_name }}
                                {{ auth()->user()->surname }}</a>
                            <div class="dropdown-divider"></div>
                        </div>

                        <a class="dropdown-item d-flex" href="{{ url('resetPassword') . '/' . auth()->id() }}">
                            <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" height="24"
                                viewBox="0 0 24 24" width="24">
                                <path d="M0 0h24v24H0V0z" fill="none" />
                                <path
                                    d="M4 4h16v12H5.17L4 17.17V4m0-2c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4zm2 10h12v2H6v-2zm0-3h12v2H6V9zm0-3h12v2H6V6z" />
                            </svg>
                            <div class="">{{ trans('words.reset_password') }}</div>
                        </a>

                        <form action="{{ url('/logout') }}" method="post">
                            @csrf
                            <button type="submit" class="dropdown-item d-flex" href="">
                                <svg class="header-icon mr-3" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24">
                                    <g>
                                        <rect fill="none" height="24" width="24" />
                                    </g>
                                    <g>
                                        <path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z" />
                                    </g>
                                </svg>
                                <div class="">{{ trans('words.signout') }}</div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@role([1,2,3])
    <div class="row calculate-section">
        <div class="col-lg-7 col-md-7 mt-4 d-none d-sm-block flex-100">
            <div style="display: flex;align-items:flex-start;">
                <div class="mr-5 p-1 mt-3">
                    <h6>Shortcuts</h6>
                </div>
                <div style="display: flex;flex-direction: column">
                    <div class="btn-group btn-group-sm" role="group">
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/add-ticket') }}">New Ticket</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/add-todo') }}">New Own To-do</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/add-package') }}">New Order</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/add-organization') }}">New Organization</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/add-user') }}">New User</a>
                        <div class="dropdown">
                            <button class="btn btn-gray dropdown-toggle mr-1 mt-1 btn-shortcut" type="button"
                                id="new-contract-dropdown-btn" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                New Contract
                            </button>
                            <div class="dropdown-menu shortcut-dropdown" aria-labelledby="new-contract-dropdown-btn">
                                <a class="dropdown-item" href="{{ url('/add-contract/getucon-de') }}" target="_blank">New Contract getucon GmbH</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-gray dropdown-toggle mr-1 mt-1 btn-shortcut" type="button"
                                id="new-asset-dropdown-btn" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                New Asset
                            </button>
                            <div class="dropdown-menu shortcut-dropdown" aria-labelledby="new-asset-dropdown-btn">
                                <a class="dropdown-item" href="{{ url('/assets/create/getucon-de') }}" target="_blank">New Asset getucon GmbH</a>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group btn-group-sm" role="group">
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/tickets') }}">All Tickets</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/todos') }}">Own To-do's</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/package-tracking') }}">Orders</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/organizations') }}">Organizations</a>
                        <a class="btn btn-gray btn-shortcut mr-1 mt-1" target="_blank" href="{{ url('/users') }}">Users</a>
                        <div class="dropdown">
                            <button class="btn btn-gray dropdown-toggle mr-1 mt-1 btn-shortcut" type="button" id="contracts-dropdown-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Contracts</button>
                            <div class="dropdown-menu shortcut-dropdown" aria-labelledby="contracts-dropdown-btn">
                                <a class="dropdown-item" href="{{ url('/contracts/getucon-de') }}" target="_blank">Contracts getucon GmbH</a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-gray dropdown-toggle mr-1 mt-1 btn-shortcut" type="button" id="assets-dropdown-btn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Assets</button>
                            <div class="dropdown-menu shortcut-dropdown" aria-labelledby="assets-dropdown-btn">
                                <a class="dropdown-item" href="{{ url('/assets/getucon-de') }}" target="_blank">Assets getucon GmbH</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @role([1])
            <div class="col-md-4 col-lg-4 flex-100 mt-5 ml-4">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group row border-bottom">

                            <div class="col-md-3 col-lg-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <label class="form-label"> Hourly Rate </label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control form-control-sm" data-type="currency"
                                            id="global-hour-price">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <label class="form-label">Discount</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control form-control-sm" data-type="currency4"
                                            id="global-price-discount">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3 col-lg-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <label class="form-label">Final</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control form-control-sm" data-type="currency" id="global-price-result">
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3 col-lg-3">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <label class="form-label">15-Minutes Rate</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <input type="text" class="form-control form-control-sm" data-type="currency"
                                            id="global-fifteen-price">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endif
