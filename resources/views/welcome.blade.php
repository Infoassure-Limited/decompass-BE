@extends('layouts.app')
@section('title', 'Welcome')
@section('home_active', 'active')
@section('content')


  <!-- Hero Header Start -->
  <div class="hero-header overflow-hidden px-5">
    <!-- <div class="rotate-img">
            <img src="img/sty-1.png" class="img-fluid w-100" alt="">
            <div class="rotate-sty-2"></div>
        </div> -->
    <div class="row gy-5 align-items-center">
      <div class="col-lg-6 wow fadeInLeft" data-wow-delay="0.1s">
        <h1 class="display-4 text-dark mb-4 wow fadeInUp" data-wow-delay="0.3s">
          Decode Compliance.<br>
          Unleash Confidence.
        </h1>
        <p class="mb-4 wow fadeInUp" data-wow-delay="0.5s">
          Ready to take the next step towards cybersecurity compliance? Explore our services to learn more about how
          <strong>Decompass</strong> can benefit your organization. <br>Contact us today to schedule a demo or inquire
          about our solutions.
        </p>
        <a href="/request-quote" class="btn btn-primary py-2 px-4 wow fadeInUp" data-wow-delay="0.7s">Book A Demo</a>
        <!-- <a href="#" class="btn btn-primary rounded-pill py-3 px-5 wow fadeInUp" data-wow-delay="0.7s">Book A Demo</a> -->
      </div>
      <div class="col-lg-6 wow fadeInRight" data-wow-delay="0.2s">
        <img src="img/hero-img-1.png" class="img-fluid w-100 h-100" alt="">
      </div>
    </div>
  </div>
  <!-- Hero Header End -->
  <!--Partners Start-->
  <!-- <div class="container-fluid py-0 px-5 wow fadeInUp" data-wow-delay="0.1s" style="margin-top: 2rem;">
    <div>
      <div class="bg-white">
        <div class="owl-carousel vendor-carousel">
          <img src="img/partners/ndpc.jpeg" alt="">
          <img src="img/partners/pcidss.jpeg" alt="">
          <img src="img/partners/msecb.jpg" alt="">
          <img src="img/partners/tnv.jpg" alt="">
          <img src="img/partners/tuv.jpg" alt="">
          <img src="img/partners/pecb.jpeg" alt="">
        </div>
      </div>
    </div>
    <hr>
  </div> -->
  <!--Partners ends-->

  <!-- About Start -->
  <div class="container-xxl py-5">
    <div class="container">
      <div class="row g-4 align-items-end mb-4">
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
          <img src="img/about-1.png" class="img-fluid w-100" alt="">
        </div>
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
          <p class="d-inline-block border rounded text-primary fw-semi-bold py-1 px-3">About Us</p>
          <h3 class="mb-4">We Help Our Clients Stay Compliant</h3>

          <div class="border rounded p-4">
            <nav>
              <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                <button class="nav-link fw-semi-bold" id="nav-overview-tab" data-bs-toggle="tab"
                  data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview"
                  aria-selected="false">Overview</button>
                <button class="nav-link fw-semi-bold active" id="nav-vision-tab" data-bs-toggle="tab"
                  data-bs-target="#nav-vision" type="button" role="tab" aria-controls="nav-vision"
                  aria-selected="true">Vision</button>
                <button class="nav-link fw-semi-bold" id="nav-mission-tab" data-bs-toggle="tab"
                  data-bs-target="#nav-mission" type="button" role="tab" aria-controls="nav-mission"
                  aria-selected="false">Mission</button>
                <button class="nav-link fw-semi-bold" id="nav-core-value-tab" data-bs-toggle="tab"
                  data-bs-target="#nav-core-value" type="button" role="tab" aria-controls="nav-core-value"
                  aria-selected="false">Core Values</button>
              </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-vision" role="tabpanel" aria-labelledby="nav-vision-tab">
                <p>We envision a future where cybersecurity compliance is not just a checkbox exercise but an integral
                  part of business strategy, enabling organizations to thrive in a digital world securely.</p>
              </div>
              <div class="tab-pane fade" id="nav-mission" role="tabpanel" aria-labelledby="nav-mission-tab">
                <p>To empower businesses to achieve and maintain compliance with confidence, ensuring the security and
                  integrity of their data assets. </p>
              </div>
              <div class="tab-pane fade" id="nav-core-value" role="tabpanel" aria-labelledby="nav-core-value-tab">
                <ul>
                  <li>R – Reliability</li>
                  <li>I – Integrity</li>
                  <li>P – Professionalism</li>
                  <li>E – Excellence</li>
                </ul>
              </div>
              <div class="tab-pane fade" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                <p class="mb-4">Decompass App is your go-to platform for ensuring adherence to a multitude of industry
                  standards and regulatory requirements. From ISO 27001 to PCI DSS, ISO 22301 to NIST Cybersecurity
                  Framework, and beyond, our app provides a seamless and intuitive interface for managing and accessing
                  compliance across various domains.</p>
                <p class="mb-4">With a systematic and disciplined approach, Decompass empowers organizations in Nigeria,
                  Sub-Sahara Africa, and the UK to evaluate risk management, control, information technology, financial,
                  and governance processes. Our platform offers independent and objective assurance to stakeholders,
                  ensuring that organizational processes, technology, and people are aligned with established best
                  practices and regulatory standards.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="border rounded p-4 wow fadeInUp" data-wow-delay="0.1s">
                <div class="row g-4">
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-thumbs-up text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>Clients Satisfaction</h4>
                                    <span>We are </span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-users text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>Dedicated Team</h4>
                                    <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                                </div>
                                <div class="border-end d-none d-lg-block"></div>
                            </div>
                            <div class="border-bottom mt-4 d-block d-lg-none"></div>
                        </div>
                    </div>
                    <div class="col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                        <div class="h-100">
                            <div class="d-flex">
                                <div class="flex-shrink-0 btn-lg-square rounded-circle bg-primary">
                                    <i class="fa fa-phone text-white"></i>
                                </div>
                                <div class="ps-3">
                                    <h4>24/7 Available</h4>
                                    <span>Clita erat ipsum lorem sit sed stet duo justo</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
    </div>
  </div>
  <!-- About End -->


  <!-- Features Start -->
  <div class="container-fluid service py-5 bg-light">
    <div class="container py-5">
      <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
        <!-- <h4 class="mb-1 text-primary">Our Service</h4> -->
        <h1 class="display-5 mb-4">Why Businesses Use Decompass</h1>
        <!-- <p class="mb-0">Dolor sit amet consectetur, adipisicing elit. Ipsam, beatae maxime. Vel animi eveniet doloremque reiciendis soluta iste provident non rerum illum perferendis earum est architecto dolores vitae quia vero quod incidunt culpa corporis, porro doloribus. Voluptates nemo doloremque cum.
            </p> -->
      </div>
      @include('pages.partials.value_proposition')
      <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="0.5s">
        <a href="{{route('features')}}" class="btn btn-primary py-2 px-4">Read More</a>
      </div>
    </div>
  </div>
  <!-- Features end -->
  <!-- FAQ Start -->
  <div class="container-fluid service py-5">
    <div class="container py-0">
      <div class="text-center mx-auto  wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
        <!-- <h4 class="mb-1 text-primary">Our Service</h4> -->
        <h1 class="display-5">Frequently Asked Questions</h1>
        <!-- <p class="mb-0">Dolor sit amet consectetur, adipisicing elit. Ipsam, beatae maxime. Vel animi eveniet doloremque reiciendis soluta iste provident non rerum illum perferendis earum est architecto dolores vitae quia vero quod incidunt culpa corporis, porro doloribus. Voluptates nemo doloremque cum.
            </p> -->
      </div>
    </div>
  </div>
  @include('pages.partials.faq')
  <!-- Service Start -->
  <!-- <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s" style="background: #f0f0f0">
    <div class="container py-5">
      <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-0">We are your sure plug in the following services</h1>
      </div>
      <div class="row g-5">
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">

          <div
            class="service-item bg-dark rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-map-signs fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-white">Advisory Services</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'advisory-services'])"
              class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
          <div
            class="service-item bg-white rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-tasks fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-dark">Integration & Implementation of Solutions</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'integration-and-implementation-of-solutions'])" class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
          <div
            class="service-item bg-dark rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-shield-alt fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-white">Vulnerability & Penetration Testing Services</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'vulnerability-and-penetration-testing-services'])" class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.9s">
          <div
            class="service-item bg-white rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-check fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-dark">Audit & Assurance Services</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'audit-and-assurance-services'])"
              class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
          <div
            class="service-item bg-dark rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-cogs fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-white">Managed Services</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'managed-services'])"
              class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
          <div
            class="service-item bg-white rounded d-flex flex-column align-items-center justify-content-center text-center">
            <div class="">
              <i class="fa fa-users-cog fa-3x text-primary"></i>
            </div>
            <h4 class="mb-3 text-dark">Training</h4>
            <x-responsive-nav-link :href="route('services_details', ['serviceType' => 'training'])"
              class="btn btn-lg btn-dark rounded">
              <i class="bi bi-arrow-right"></i>
            </x-responsive-nav-link>
          </div>
        </div>
        {{-- <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
          <p>
            <a href="services" class="btn btn-primary btn-round">Read More</a>
          </p>
        </div> --}}
      </div>
    </div>
  </div> -->
  <!-- Service End -->

  <!-- Quote Start -->
  {{-- <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s" style="background: #f0f0f0">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h5 class="fw-bold text-primary text-uppercase">Book Free Consultation</h5>
            <h1 class="mb-0">Need A Quote? Please Feel Free to Contact Us</h1>
          </div>
          <div class="row gx-3">
            <div class="col-sm-6 wow zoomIn" data-wow-delay="0.2s">
              <h5 class="mb-4"><i class="fa fa-reply text-primary me-3"></i>Reply within 24 hours</h5>
            </div>
            <div class="col-sm-6 wow zoomIn" data-wow-delay="0.4s">
              <h5 class="mb-4"><i class="fa fa-phone-alt text-primary me-3"></i>24 hrs telephone support</h5>
            </div>
          </div>
          <div class="d-flex align-items-center mt-2 wow zoomIn" data-wow-delay="0.6s">
            <div class="bg-primary d-flex align-items-center justify-content-center rounded"
              style="width: 60px; height: 60px;">
              <i class="fa fa-phone-alt text-white"></i>
            </div>
            <div class="ps-4">
              <h5 class="mb-2">Call to ask any question</h5>
              <h4 class="text-primary mb-0">+234 815 094 7567</h4>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="bg-primary rounded h-100 d-flex align-items-center p-5 wow zoomIn" data-wow-delay="0.9s">
            <form>
              <div class="row g-3">
                <div class="col-xl-12">
                  <input type="text" class="form-control bg-light border-0" placeholder="Your Name" style="height: 55px;">
                </div>
                <div class="col-12">
                  <input type="email" class="form-control bg-light border-0" placeholder="Your Email"
                    style="height: 55px;">
                </div>
                <div class="col-12">
                  <input type="number" class="form-control bg-light border-0" placeholder="Your Phone Number"
                    style="height: 55px;">
                </div>
                <div class="col-xl-12">
                  <input type="text" class="form-control bg-light border-0" placeholder="Company Name"
                    style="height: 55px;">
                </div>
                <div class="col-12">
                  <textarea class="form-control bg-light border-0" rows="3" placeholder="Scope of work"></textarea>
                </div>
                <div class="col-12">
                  <button class="btn btn-dark w-100 py-3" type="submit">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div> --}}
  <!-- Quote End -->


@endsection