<!-- The overlay -->
<div id="mob-nav" class="overlay">
    <!-- Overlay content -->
    <div class="mob-nav-header">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between align-items-baseline">
                <div class="text-white font-weight-bold" style="font-size: 14pt;">
                    <img src="{{asset('images/logo.png')}}" alt="" class="mob-nav-logo rounded">
                </div>
                <div>
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mob-nav-links">
        <a class="text-white" href="{{route('site.index')}}">
            <i class="fa fa-home"></i>&nbsp;Home
        </a>
        <a class="text-white" href="{{route('site.products')}}">
            <i class="fa fa-box"></i>&nbsp;Products & Services
        </a>
        <a class="text-white" href="{{route('site.projects')}}">
            <i class="fa fa-project-diagram"></i>&nbsp;Projects
        </a>
        <a class="text-white" href="{{route('site.blog')}}">
            <i class="fa fa-newspaper"></i>&nbsp;Blog
        </a>
        <a class="text-white" href="{{route('site.contact')}}">
            <i class="fa fa-phone"></i>&nbsp;Contact Us
        </a>
        <a class="text-white" href="{{route('site.about')}}">
            <i class="fa fa-info-circle"></i>&nbsp;About Us
        </a>
    </div>
</div>
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm  pt-3 border-bottom">
    <div class="container">
        <a class="navbar-brand" href="{{route('site.index')}}">
            <img src="{{asset('images/logo.png')}}" alt="logo" class="rounded"
                 style="width: 4em;height: auto;margin-top: -.5em;">
        </a>
        <button class="navbar-toggler border rounded" type="button" id="sm-nav-toggler" aria-expanded="false"
                aria-label="Toggle navigation">
            <i class="fa fa-bars text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.index')}}">
                        <i class="fa fa-home"></i>&nbsp;Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.products')}}">
                        <i class="fa fa-box"></i>&nbsp;Products & Services
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.projects')}}">
                        <i class="fa fa-project-diagram"></i>&nbsp;Projects
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.blog')}}">
                        <i class="fa fa-newspaper"></i>&nbsp;Blog
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.contact')}}">
                        <i class='fa fa-phone'></i>&nbsp;Contact Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('site.about')}}">
                        <i class="fa fa-info-circle"></i>&nbsp;About Us
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
