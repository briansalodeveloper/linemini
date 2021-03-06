<aside class="main-sidebar elevation-4 bg-white">
    <a href="{{ route('dashboard.index') }}" class="brand-link">
        <img src="{{ _vers('images/logo/coop_hp.png') }}" alt="Coop service" class="brand-image">
        <span class="brand-text font-weight-bold text-blue">コープやまぐち</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @can('manageDashboard')
                    <li class="nav-item">
                        <a href="{{ route('dashboard.index') }}" class="nav-link{{ _isRoute('dashboard.index') ? ' active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt half"></i>
                            <p>{{ __('words.NumberOfRegisteredPeople') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageContents')
                    <li class="nav-item{{
                        _isRoute(config('const.routeMenuGroup.notice'))
                        || _isRoute(config('const.routeMenuGroup.recipe'))
                        || _isRoute(config('const.routeMenuGroup.productInformation'))
                        || _isRoute(config('const.routeMenuGroup.column')) ? ' menu-open' : '' }}">

                        <a href="#" class="nav-link{{
                            _isRoute(config('const.routeMenuGroup.notice'))
                            || _isRoute(config('const.routeMenuGroup.recipe'))
                            || _isRoute(config('const.routeMenuGroup.productInformation'))
                            || _isRoute(config('const.routeMenuGroup.column')) ? ' active' : '' }}">

                            <i class="nav-icon fas fa-edit"></i>
                            <p>{{ __('words.Content') }}<i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('notice.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.notice')) }}">
                                    <i class="nav-icon fas fa-bullhorn"></i>
                                    <p>{{ __('words.Deals') }}</p>
                                </a>
                            </li>
                        <li class="nav-item">
                                <a href="{{ route('recipe.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.recipe')) }}">
                                    <i class="nav-icon fas fa-utensils"></i>
                                    <p>{{ __('words.Recipe') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('productInformation.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.productInformation')) }}">
                                    <i class="nav-icon fas fa-basket-shopping"></i>
                                    <p>{{ __('words.ProductInformation') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('column.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.column')) }}">
                                    <i class="nav-icon fas fa-file-pen"></i>
                                    <p>{{ __('words.Column') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('manageFlyer')
                    <li class="nav-item">
                        <a href="{{ route('flyer.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.flyer')) }}">
                            <i class="nav-icon far fa-newspaper"></i>
                            <p>{{ __('words.Flyer') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageCoupon')
                    <li class="nav-item">
                        <a href="{{ route('coupon.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.coupon')) }}">
                            <i class="nav-icon fas fa-ticket-simple"></i>
                            <p>{{ __('words.Coupon') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageStamp')
                <li class="nav-item">      
                    <a href="{{ route('stamp.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.stamp')) }}">
                        <i class="nav-icon fas fa-stamp"></i>
                        <p>{{ __('words.Stamp') }}</p>
                    </a>
                </li>
                @endcan
                @can('manageMessage')
                    <li class="nav-item">
                        <a href="{{ route('message.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.message')) }}">
                            <i class="nav-icon fas fa-envelope"></i>
                            <p>{{ __('words.Message') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageUser')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.user')) }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>{{ __('words.LineAppStatusChange') }}</p>
                        </a>
                    </li> 
                @endcan
                @can('manageUser')
                    <li class="nav-item">
                        <a href="{{ route('user.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.user')) }}" onclick="event.preventDefault();window.open('{{ route('user.index') }}', '_blank');">
                            <i class="nav-icon fas fa-user"></i>
                            <p>{{ __('words.LineAppStatusChange') }} (can close)</p>
                        </a>
                    </li> 
                @endcan
                @can('manageBatchLog')
                    <li class="nav-item">
                        <a href="{{ route('batchLog.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.batchLog')) }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>{{ __('words.BatchLog') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageAdmin')
                    <li class="nav-item">
                        <a href="{{ route('admin.index') }}" class="nav-link {{ _isRoute(config('const.routeMenuGroup.admin')) }}">
                            <i class="nav-icon fas fa-user-tie"></i>
                            <p>{{ __('words.Administrator') }}</p>
                        </a>
                    </li>
                @endcan
                @can('manageQuestionnaire')
                    {{-- TODO: Questionnaire --}}
                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-list-check"></i>
                            <p>{{ __('words.Questionnaire') }}</p>
                        </a>
                    </li> --}}
                @endcan
            </ul>
        </nav>
    </div>
</aside>
