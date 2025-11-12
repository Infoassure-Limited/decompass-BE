@extends('layouts.app')
@section('title', 'About Us')
@section('about_active', 'active')
@section('content')
  <div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
      <h3 class="display-3 mb-2 wow fadeInDown">Overview</h1>
    </div>
  </div>
  <!-- About Start -->
  <div class="container-fluid py-5 wow fadeInUp">
    <div class="container py-2">
      <div class="row g-4 mb-4">
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.3s">
          <h3 class="mb-4">We Help Our Clients Stay Compliant</h3>
          <div>
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
          <div class="border rounded p-4">
            <nav>
              <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                <!-- <button class="nav-link fw-semi-bold active" id="nav-overview-tab" data-bs-toggle="tab"
                  data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview"
                  aria-selected="false">Overview</button> -->
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
              <!-- <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                <p class="mb-4">Decompass App is your go-to platform for ensuring adherence to a multitude of industry
                  standards and regulatory requirements. From ISO 27001 to PCI DSS, ISO 22301 to NIST Cybersecurity
                  Framework, and beyond, our app provides a seamless and intuitive interface for managing and accessing
                  compliance across various domains.</p>
                <p class="mb-4">With a systematic and disciplined approach, Decompass empowers organizations in Nigeria,
                  Sub-Sahara Africa, and the UK to evaluate risk management, control, information technology, financial,
                  and governance processes. Our platform offers independent and objective assurance to stakeholders,
                  ensuring that organizational processes, technology, and people are aligned with established best
                  practices and regulatory standards.</p>
              </div> -->
            </div>
          </div>
        </div>
        <div class="col-lg-6 wow fadeInUp" data-wow-delay="0.1s">
          <img src="img/about-1.png" class="img-fluid w-100" alt="">
        </div>
      </div>
    </div>
  </div>

  <!-- About End -->
@endsection