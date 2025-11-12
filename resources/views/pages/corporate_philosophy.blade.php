@extends('layouts.app')
@section('title', 'About Us')
@section('about_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Corporate Philosophy</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!--Corporate Philosophy-->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-3">
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.6s">
          <div class="bg-dark rounded">
            <span>
              <img src="img/pin.png" width="50">
              <img src="img/pin.png" width="50" align="right">
            </span>
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="text-white mb-1">Our Vision</h4>
            </div>
            <div class="p-2 pt-0 text-white">
              <p>To be the most respected Information Technology assurance and GRC firm in Nigeria and sub-Sahara Africa.
              </p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-white rounded shadow position-relative" style="z-index: 1;">
            <span>
              <img src="img/pin.png" width="50">
              <img src="img/pin.png" width="50" align="right">
            </span>
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="text-primary mb-1">Our Mission</h4>
            </div>
            <div class="p-2 pt-0">
              <p>To help in securing organizations’ assets using best practice standards and frameworks.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.9s">
          <div class="bg-dark rounded">
            <span>
              <img src="img/pin.png" width="50">
              <img src="img/pin.png" width="50" align="right">
            </span>
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="text-white mb-1">Our Core Values:</h4>
            </div>
            <div class="p-5 pt-0 text-white">

              <div class="d-flex justify-content-between mb-3">R – Reliability</div>
              <div class="d-flex justify-content-between mb-3">I – Integrity</div>
              <div class="d-flex justify-content-between mb-3">T – Tenacity</div>
              <div class="d-flex justify-content-between mb-2">E – Efficiency</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Features Start -->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-0">Our Value
          Proposition</h1>
      </div>
      @include('pages.partials.value_proposition')
    </div>
  </div>
  <!-- Features Start -->

  <!-- About End -->
@endsection
