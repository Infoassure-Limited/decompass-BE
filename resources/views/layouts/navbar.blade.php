<nav
  class="navbar navbar-expand-xl navbar-expand-lg navbar-expand-md .navbar-expand-sm bg-white navbar-light px-4 px-lg-5 py-3 py-lg-0">
  <a href="/">
    <img src="img/logo.png" alt="Logo" width="200">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="fa fa-bars"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav ms-auto py-0">
      <a href="/" class="nav-item nav-link @yield('home_active')">Home</a>
      <a href="{{route('about_us')}}" class="nav-item nav-link @yield('about_active')">Overview</a>
      <a href="{{route('features')}}" class="nav-item nav-link @yield('feature_active')">Features</a>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Resources</a>
        <div class="dropdown-menu m-0">
          <a href="{{route('faq')}}" class="dropdown-item">FAQ</a>
          <!-- <a href="pricing.html" class="dropdown-item">Pricing</a>
          <a href="blog.html" class="dropdown-item">Blog</a>
          <a href="testimonial.html" class="dropdown-item">Testimonial</a>
          <a href="404.html" class="dropdown-item">404 Page</a> -->
        </div>
      </div>
      <a href="/contact" class="nav-item nav-link">Contact Us</a>
    </div>
    <a href="https://app.decompass.com" class="btn btn-light border text-primary py-1 px-3 me-4">Log In</a>
    <a href="/request-quote" class="hide-mobile btn btn-secondary border text-white py-1 px-3">Get Started</a>
  </div>
</nav>
<!-- <nav class="navbar navbar-expand-lg navbar-dark px-5 py-3 py-lg-0">
  <a href="/" class="navbar-brand p-0 alt-img">
    <img src="{{ URL::asset('img/logo-alt.png') }}" width="100" style="padding-top: 10px">
  </a>
  <a href="/" class="navbar-brand p-0 main-img">
    <img src="{{ URL::asset('img/logo.png') }}" width="100" style="padding-top: 10px">
  </a>
  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
    <span class="fa fa-bars"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarCollapse">
    <div class="navbar-nav ms-auto py-0">
      <a href="/" class="nav-item nav-link @yield('home_active')">Home</a>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" @yield('service_active')>Company</a>
        <div class="dropdown-menu m-0">
          <x-responsive-nav-link class="dropdown-item" :href="route('about_us')">Overview</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('corporate_philosophy')">Corporate Philosophy</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" href="#">Team</x-responsive-nav-link>
        </div>
      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" @yield('service_active')>Services</a>
        <div class="dropdown-menu m-0">
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'advisory-services'])">Advisory Services</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'integration-and-implementation-of-solutions'])">Integration & Implementation of
            Solutions</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'vulnerability-and-penetration-testing-services'])">Vulnerability & Penetration Testing
            Services</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'audit-and-assurance-services'])">Audit & Assurance
            Services</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'managed-services'])">Managed Services</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('services_details', ['serviceType' => 'training'])">Training</x-responsive-nav-link>
        </div>
      </div>
      <div class="nav-item dropdown">
        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" @yield('resource_active')>Resources</a>
        <div class="dropdown-menu m-0">
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'cyber_security_article'])">Cybersecurity Articles</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'video_tutorial'])">Video Tutorials</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'webinars'])">Webinars</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'case_studies'])">Case Studies</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'industry_reports_and_survey'])">Industry Reports &
            Surveys</x-responsive-nav-link>
          <x-responsive-nav-link class="dropdown-item" :href="route('public_resource_index', ['type' => 'podcast_and_interviews'])">Podcasts & Interviews</x-responsive-nav-link>
        </div>
      </div>
      <a href="/contact" class="nav-item nav-link @yield('contact_active')">Contact Us</a>
    </div>
    <a href="/request-quote" class="btn btn-success btn-round py-2 px-4 ms-3">Book Free Consultation</a>
  </div>
</nav> -->