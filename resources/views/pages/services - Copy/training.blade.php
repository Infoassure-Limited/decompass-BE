@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Training</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
        {{-- <i class="far fa-circle text-white px-2"></i>
        <a href="services" class="h5 text-white">Services</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  {{-- <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-0">We offer the following services</h1>
      </div>
      <div class="row g-5">
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3">Audit & Assurance Services</h4>
            </div>
            <div class="p-5 pt-0">
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#information-system-audit"><i class="fa fa-arrow-right text-primary pt-1"></i> Information
                    Systems Audit</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#revenue-assurance"><i class="fa fa-arrow-right text-primary pt-1"></i> Revenue
                    Assurance</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#financial-statement"><i class="fa fa-arrow-right text-primary pt-1"></i> Financial
                    Statement Audit</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#regulatory-compliance"><i class="fa fa-arrow-right text-primary pt-1"></i> Regulatory
                    Compliance and Reporting</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#management-system"><i class="fa fa-arrow-right text-primary pt-1"></i> Management Systems
                    Certification Audit</a></span>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div> --}}
  <!-- Services End -->
  <div id="training" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <p class="mb-4" align="justify">

            Basically, for the training, we carry out a series of trainings on virtually all the cyber security standards
            and certification programs such as the ISO family; both for lead implementer (LI) and lead auditor (LA).
          </p>

          <p class="mb-4" align="justify">
            We also carry out training for other certification programs like the General Data Protection Regulation and
            the Nigeria Data Protection Act (GDPR/ NDPA) formerly known as NDPR.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s" src="/img/training.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
