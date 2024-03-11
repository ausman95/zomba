 <header class="header shadow-sm body-pd " id="header">
    <div class="header__toggle">
        <i class='bx bx-menu' id="header-toggle"></i>
    </div>

    <div class="d-flex align-items-baseline">
        <div class="mx-3" >
            <a href="{{route('ministries.index')}}" title="Financial Year">
                <i class='fa fa-clock' style="font-size:1.8rem;padding-top: 1px;"></i>
                <span class="badge bg-primary badge-pill">{{count(\App\Models\FinancialYear::where(['soft_delete'=>0])->orderBy('id','desc')->get())}}</span>
            </a>
            <a href="{{route('ministries.index')}}" title="Services">
                <i class='bx bx-bell' style="font-size:1.8rem;padding-top: 1px;"></i>
                <span class="badge bg-primary badge-pill">{{count(\App\Models\Service::where(['soft_delete'=>0])->orderBy('id','desc')->get())}}</span>
            </a>
            <a href="{{route('ministries.index')}}" title="Ministries">
                <i class='fa fa-comments-dollar' style="font-size:1.8rem;padding-top: 1px;"></i>
                <span class="badge bg-primary badge-pill">{{count(\App\Models\Ministry::where(['soft_delete'=>0])->orderBy('id','desc')->get())}}</span>
            </a>
            <a href="{{route('months.index')}}" title="Months">
                <i class='bx bx-cog' style="font-size:1.8rem;padding-top: 1px;"></i>
                <span class="badge bg-primary badge-pill">{{count(\App\Models\Month::where(['soft_delete'=>0])->orderBy('id','desc')->get())}}</span>
            </a>
            <a href="{{route('messages.unread')}}"  title="Messages" >
                <i class='bx bx-envelope' style="font-size:1.8rem;padding-top: 1px;"></i>
                <span class="badge bg-primary badge-pill">
                   {{ \App\Models\Message::where(['status' => 1])->count('id') }}</span>
            </a>
        </div>
        <div class="header__img">
            <a href="{{route('settings')}}" >
                <img src="{{asset('images/avatar.png')}}" alt="avatar image">
            </a>
        </div>
      &nbsp; {{request()->user()->name}}
    </div>

</header>

<div class="l-navbar navbar-show" id="nav-bar">
    <nav class="sidenav">
        <div>
{{--            <a href="{{route('home')}}" class="sidenav__logo">--}}
{{--                <i class='bx bx-wrench sidenav__logo-icon'></i>--}}
{{--                <span class="sidenav__logo-name">SICO CIVILS</span>--}}
{{--            </a>--}}

            <div class="sidenav__list">
                <a href="{{route('home')}}" class="sidenav__link {{$cpage === 'dashboard' ? 'link-active' : ''}}"
                   title="Dashboard">
                    <i class='bx bx-grid-alt sidenav__icon'></i>
                    <span class="sidenav__name">Dashboard</span>
                </a>
{{--                @if(request()->user()->designation=='administrator')--}}
{{--                <a href="{{route('requisitions.index')}}"--}}
{{--                   class="sidenav__link {{$cpage === 'requisitions' ? 'link-active' : ''}}" title="Requisitions">--}}
{{--                    <i class='fab fa-acquisitions-incorporated sidenav__icon'></i>--}}
{{--                    <span class="sidenav__name">Requisitions</span>--}}
{{--                </a>--}}
{{--                @endif--}}
                @if(request()->user()->designation=='administrator')
                <a href="{{route('human-resources.index')}}" class="sidenav__link {{$cpage === 'human-resources' ? 'link-active' : ''}}"
                   title="Human Resources">
                    <i class='bx bxs-file sidenav__icon'></i>
                    <span class="sidenav__name">Human Resources</span>
                </a>
                <a href="{{route('finances.index')}}"
                   class="sidenav__link {{$cpage === 'finances' ? 'link-active' : ''}}" title="Finances">
                    <i class='bx bxs-file-archive sidenav__icon'></i>
                    <span class="sidenav__name">Finances</span>
                </a>
                <a href="{{route('suppliers.index')}}"
                   class="sidenav__link {{$cpage === 'suppliers' ? 'link-active' : ''}}" title="Suppliers">
                    <i class='bx bxs-file-archive sidenav__icon'></i>
                    <span class="sidenav__name">Suppliers</span>
                </a>
{{--                <a href="{{route('materials.index')}}"--}}
{{--                   class="sidenav__link {{$cpage === 'materials' ? 'link-active' : ''}}" title="Materials">--}}
{{--                    <i class='bx bx-abacus sidenav__icon'></i>--}}
{{--                    <span class="sidenav__name">Materials</span>--}}
{{--                </a>--}}
{{--                <a href="{{route('prices.index')}}"--}}
{{--                   class="sidenav__link {{$cpage === 'requisitions' ? 'link-active' : ''}}" title="Requisitions">--}}
{{--                    <i class='fab fa-acquisitions-incorporated sidenav__icon'></i>--}}
{{--                    <span class="sidenav__name">Material Prices</span>--}}
{{--                </a>--}}
                <a href="{{route('stores.index')}}"
                   class="sidenav__link {{$cpage === 'stores' ? 'link-active' : ''}}" title="Stores">
                    <i class='bx bxs-file-archive sidenav__icon'></i>
                    <span class="sidenav__name">Stores </span>
                </a>
                <a href="{{route('assets.index')}}"
                   class="sidenav__link {{$cpage === 'assets' ? 'link-active' : ''}}" title="Assets">
                    <i class='bx bxs-car sidenav__icon'></i>
                    <span class="sidenav__name">Assets & Liabilities</span>
                </a>
                @endif
{{--                <a href="{{route('zones.index')}}"--}}
{{--                   class="sidenav__link {{$cpage === 'zones' ? 'link-active' : ''}}" title="Zones">--}}
{{--                    <i class='fa fa-list-ol sidenav__icon'></i>--}}
{{--                    <span class="sidenav__name">Zones</span>--}}
{{--                </a>--}}
                <a href="{{route('churches.index')}}"
                   class="sidenav__link {{$cpage === 'churches' ? 'link-active' : ''}}" title="Churches">
                    <i class='fab fa-acquisitions-incorporated sidenav__icon'></i>
                    <span class="sidenav__name">Home Cells</span>
                </a>
                <a href="{{route('members.index')}}"
                   class="sidenav__link {{$cpage === 'members' ? 'link-active' : ''}}" title="Members">
                    <i class='fa fa-user-circle sidenav__icon'></i>
                    <span class="sidenav__name">Members</span>
                </a>
                @if(request()->user()->designation=='administrator')
                <a href="{{route('attendances.index')}}" class="sidenav__link {{$cpage === 'attendances' ? 'link-active' : ''}}"
                   title="Attendances">
                    <i class='bx bx-bar-chart-alt-2 sidenav__icon'></i>
                    <span class="sidenav__name">Church Attendances</span>
                </a>
                @endif
                    <a href="{{route('home-attendances.index')}}" class="sidenav__link {{$cpage === 'home-attendances' ? 'link-active' : ''}}"
                       title="Attendances">
                        <i class='bx bx-bar-chart-alt-2 sidenav__icon'></i>
                        <span class="sidenav__name">Home Attendances</span>
                    </a>
                <a href="{{route('announcements.index')}}" class="sidenav__link {{$cpage === 'announcements' ? 'link-active' : ''}}"
                   title="Announcements">
                    <i class='bx bx-list-ol sidenav__icon'></i>
                    <span class="sidenav__name">Announcements</span>
                </a>
                @if(request()->user()->designation!='administrator')
                    <a href="{{route('members.show',request()->user()->member_id)}}"
                       class="sidenav__link {{$cpage === 'tithe' ? 'link-active' : ''}}" title="Tithe">
                        <i class='bx bxs-file-archive sidenav__icon'></i>
                        <span class="sidenav__name">My Tithe</span>
                    </a>
                @endif
                <a href="{{route('programs.index')}}" class="sidenav__link {{$cpage === 'programs' ? 'link-active' : ''}}"
                   title="Programs">
                    <i class='fa fa-cog sidenav__icon'></i>
                    <span class="sidenav__name">Programs</span>
                </a>
                @if(request()->user()->designation=='administrator')

                <a href="{{route('users.index')}}" class="sidenav__link {{$cpage === 'users' ? 'link-active' : ''}}"
                   title="Users">
                    <i class='bx bx-user sidenav__icon'></i>
                    <span class="sidenav__name">Users</span>
                </a>
                @endif

                @if(request()->user()->designation!='administrator')
                <a href="{{route('users.show',request()->user()->id)}}"
                   class="sidenav__link {{$cpage === 'users' ? 'link-active' : ''}}" title="My Profile">
                    <i class='bx bxs-user-account sidenav__icon'></i>
                    <span class="sidenav__name">My Profile</span>
                </a>
                @endif
{{--                <a href="{{route('logout.perform')}}" class="sidenav__link {{$cpage === 'logout' ? 'link-active' : ''}}"--}}
{{--                   title="Logout">--}}
{{--                    <i class='bx bx-log-out sidenav__icon'></i>--}}
{{--                    <span class="sidenav__name">Log Out</span>--}}
{{--                </a>--}}
                <form action="{{route('logout')}}" method="POST" id="logout-form">
                    @csrf
                </form>
                <a href="#logout" class="sidenav__link" onclick="document.querySelector('#logout-form').submit()"
                   title="Logout">
                    <i class='bx bx-log-out sidenav__icon'></i>
                    <span class="sidenav__name">Log Out</span>
                </a>
            </div>
        </div>

    </nav>
</div>

