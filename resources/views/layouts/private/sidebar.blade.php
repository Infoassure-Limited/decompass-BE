<aside
  class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark"
  id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
      aria-hidden="true" id="iconSidenav"></i>
    <x-responsive-nav-link class="navbar-brand" href="#">
      <img src="/img/logo-alt.png" width="100">
    </x-responsive-nav-link>
  </div>
  <hr class="horizontal light mt-0 mb-2">
  <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white active bg-gradient-primary" :href="route('dashboard')">
          <i class="fa fa-home" aria-hidden="true"></i>
          <span class="nav-link-text ms-1">Dashboard</span>
        </x-responsive-nav-link>
      </li>
      <li class="nav-item">
        <x-responsive-nav-link data-bs-toggle="collapse" href="#resourcesDropdown"
          class="nav-link text-white active collapsed" aria-controls="resourcesDropdown" role="button"
          aria-expanded="false">
          <i class="fa fa-list" aria-hidden="true"></i>
          <span class="nav-link-text ms-2 ps-1">Resources</span>
        </x-responsive-nav-link>
        <div class="collapse" id="resourcesDropdown" style="">
          <ul class="nav ">
            <li class="nav-item  active ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'cyber_security_article'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Cybersecurity Articles<b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>

            <li class="nav-item ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'video_tutorial'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Video Tutorials <b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>
            <li class="nav-item ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'webinars'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Webinars <b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>
            <li class="nav-item ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'case_studies'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Case Studies <b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>
            <li class="nav-item ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'industry_reports_and_survey'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Industry Reports & Surveys <b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>
            <li class="nav-item ">
              <x-responsive-nav-link class="nav-link text-white" :href="route('resource_index', ['type' => 'podcast_and_interviews'])">
                <i class="fa fa-circle"></i>
                <span class="sidenav-normal  ms-2  ps-1"> Podcasts & Interviews <b class="caret"></b></span>
              </x-responsive-nav-link>
            </li>
          </ul>
        </div>
      </li>
      {{-- <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white active bg-gradient-primary" :href="route('register')">
          <i class="fa fa-users" aria-hidden="true"></i>
          <span class="nav-link-text ms-1">Register Users</span>
        </x-responsive-nav-link>
      </li> --}}
      {{-- <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white " href="../pages/tables.html">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">table_view</i>
          </div>
          <span class="nav-link-text ms-1">Tables</span>
        </x-responsive-nav-link>
      </li>
      <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white " href="../pages/billing.html">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">receipt_long</i>
          </div>
          <span class="nav-link-text ms-1">Billing</span>
        </x-responsive-nav-link>
      </li>
      <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white " href="../pages/virtual-reality.html">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">view_in_ar</i>
          </div>
          <span class="nav-link-text ms-1">Virtual Reality</span>
        </x-responsive-nav-link>
      </li>
      <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white " href="../pages/rtl.html">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">format_textdirection_r_to_l</i>
          </div>
          <span class="nav-link-text ms-1">RTL</span>
        </x-responsive-nav-link>
      </li>
      <li class="nav-item">
        <x-responsive-nav-link class="nav-link text-white " href="../pages/notifications.html">
          <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
            <i class="material-icons opacity-10">notifications</i>
          </div>
          <span class="nav-link-text ms-1">Notifications</span>
        </x-responsive-nav-link>
      </li> --}}
    </ul>
  </div>
  <div class="sidenav-footer position-absolute w-100 bottom-0 ">
    <div class="mx-3">
      <!-- Authentication -->
      <form method="POST" action="{{ route('logout') }}">
        @csrf



        <x-responsive-nav-link class="btn bg-gradient-primary w-100" href="route('logout')"
          onclick="event.preventDefault(); this.closest('form').submit();" type="button">Logout</x-responsive-nav-link>
      </form>
    </div>
  </div>
</aside>
