<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-1 px-3">
    <div class="nav-item d-xl-none ps-3 d-flex align-items-center">
      <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
        <div class="sidenav-toggler-inner">
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
          <i class="sidenav-toggler-line"></i>
        </div>
      </a>
    </div>
    {{-- <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
      </ol>
      <h6 class="font-weight-bolder mb-0">{{ $title }}</h6>
    </nav> --}}
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        {{-- <div class="input-group input-group-outline">
          <label class="form-label">Type here...</label>
          <input type="text" class="form-control">
        </div> --}}
      </div>
      <ul class="navbar-nav  justify-content-end">
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa fa-user cursor-pointer"></i>
            <span class="d-sm-inline d-none">{{ Auth::user()->name }}</span>
          </a>
          <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li class="mb-2">
              {{-- <a class="dropdown-item border-radius-md" :href="route('profile.edit')">
                {{ __('Profile') }}
              </a> --}}
              <x-responsive-nav-link class="dropdown-item border-radius-md" :href="route('profile.edit')">
                {{ __('Profile') }}
              </x-responsive-nav-link>
            </li>
            <li class="mb-2">
              <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link class="dropdown-item border-radius-md" :href="route('logout')"
                  onclick="event.preventDefault();
                                        this.closest('form').submit();">
                  {{ __('Log Out') }}
                </x-responsive-nav-link>
              </form>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
