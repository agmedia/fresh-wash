<div class="menu-header">
    <a href="/" class="menu-logo text-center">
        <span class="font-800 font-16">Locker</span>
    </a>

    <p class="text-center mt-2 mb-0 opacity-70 font-13">
        @auth
            {{ auth()->user()->first_name ?? auth()->user()->name }}
        @else
            Gost
        @endauth
    </p>
</div>

<div class="divider divider-margins mt-3 mb-3"></div>

<div class="list-group list-custom-small list-menu">
    {{-- PWA / App --}}
    @auth
        <a id="nav-app" href="/app" class="close-menu">
            <i class="fa fa-home color-highlight"></i>
            <span>Dashboard</span>
            <i class="fa fa-angle-right"></i>
        </a>

        <a id="nav-orders" href="/app/narudzbe" class="close-menu">
            <i class="fa fa-receipt color-blue-dark"></i>
            <span>Moje narudžbe</span>
            <i class="fa fa-angle-right"></i>
        </a>

        <a id="nav-profile" href="/app/profil" class="close-menu">
            <i class="fa fa-user color-yellow-dark"></i>
            <span>Profil</span>
            <i class="fa fa-angle-right"></i>
        </a>
    @endauth

    {{-- Info web --}}
    <a id="nav-info" href="/info" class="close-menu">
        <i class="fa fa-file color-gray-dark"></i>
        <span>Info</span>
        <i class="fa fa-angle-right"></i>
    </a>

    <a id="nav-locations" href="/info/lokacije" class="close-menu">
        <i class="fa fa-location-dot color-green-light"></i>
        <span>Lokacije</span>
        <i class="fa fa-angle-right"></i>
    </a>

    <a id="nav-pricing" href="/info/cjenik" class="close-menu">
        <i class="fa fa-euro-sign color-yellow-dark"></i>
        <span>Cjenik</span>
        <i class="fa fa-angle-right"></i>
    </a>

    <a id="nav-faq" href="/info/faq" class="close-menu">
        <i class="fa fa-circle-info color-blue-dark"></i>
        <span>FAQ</span>
        <i class="fa fa-angle-right"></i>
    </a>

    <a id="nav-contact" href="/info/kontakt" class="close-menu">
        <i class="fa fa-envelope color-green-light"></i>
        <span>Kontakt</span>
        <i class="fa fa-angle-right"></i>
    </a>

    <a id="nav-blog" href="/info/blog" class="close-menu">
        <i class="fa fa-newspaper color-yellow-dark"></i>
        <span>Blog</span>
        <i class="fa fa-angle-right"></i>
    </a>

    {{-- Auth --}}
    @guest
        <a id="nav-login" href="/login" class="close-menu">
            <i class="fa fa-right-to-bracket color-highlight"></i>
            <span>Prijava</span>
            <i class="fa fa-angle-right"></i>
        </a>

        <a id="nav-register" href="/register" class="close-menu">
            <i class="fa fa-user-plus color-highlight"></i>
            <span>Registracija</span>
            <i class="fa fa-angle-right"></i>
        </a>
    @endguest

    @auth
        <form method="POST" action="/logout" class="m-0 p-0">
            @csrf
            <button type="submit" class="list-group-item close-menu border-0 bg-transparent w-100 text-start">
                <i class="fa fa-arrow-right-from-bracket color-red-light"></i>
                <span>Odjava</span>
                <i class="fa fa-angle-right"></i>
            </button>
        </form>
    @endauth

    @auth
        @can('access-admin')
            <a id="nav-admin" href="/admin" class="close-menu">
                <i class="fa fa-shield-halved color-gray-dark"></i>
                <span>Admin</span>
                <i class="fa fa-angle-right"></i>
            </a>
        @endcan
    @endauth
</div>

<div class="divider divider-margins mt-3 mb-3"></div>

<div class="content px-3">
    <a href="#" data-menu="menu-colors" class="btn btn-full rounded-s font-13 font-700 bg-highlight">
        Boje i tema
    </a>
</div>