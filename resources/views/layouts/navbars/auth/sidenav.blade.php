<aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="/"
            target="_blank">
            <img src="/img/colpa-logo.jpeg" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">ColpaSoft</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'inicio' ? 'active' : '' }}" href="/">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Inicio</span>
                </a>
            </li>
            @if (!Auth::user()->hasRole(['company', 'regularUser']))
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'usuarios') == true ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-info text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Usuarios</span>
                </a>
            </li>
            @endif
            @if (!Auth::user()->hasRole(['company', 'regularUser']))
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'empresas') == true ? 'active' : '' }}" href="{{ route('empresas.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-building text-success text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Empresas</span>
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'sedes') == true ? 'active' : '' }}" href="{{ route('sedes.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-vector text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Sedes</span>
                </a>
            </li>
            @if (!Auth::user()->hasRole('company'))
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'categorias') == true ? 'active' : '' }}" href="{{ route('categorias.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Categorias</span>
                </a>
            </li>
            @endif
            @if (!Auth::user()->hasRole(['admin']))
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'activos') == true ? 'active' : '' }}" href="{{ route('activos.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-atom text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Activos</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'papelera') == true ? 'active' : '' }}" href="{{ route('activos.inactive') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-button-power text-danger text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Papelera</span>
                </a>
            </li>
            @endif
            @if (!Auth::user()->hasRole(['admin', 'regularUser']))
            <li class="nav-item">
                <a class="nav-link {{ str_contains(request()->url(), 'reportes') == true ? 'active' : '' }}" href="{{ route('reportes.index') }}">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-books text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Reportes</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
    <form role="form" method="post" action="{{ route('logout') }}" id="logout-form">
                        @csrf
        <a href="{{ route('logout') }}"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="btn btn-dark btn-sm w-100 mb-3">
            <i class="ni ni-single-02 me-sm-1"></i>
                            <span class="d-sm-inline d-none">Cerrar sesión</span>
            </a>
                    </form>
                </li>
    </div>
</aside>