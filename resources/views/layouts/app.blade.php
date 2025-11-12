<!DOCTYPE html>
<html lang="en">

<head>

  <!-- Start cookieyes banner -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <meta charset="utf-8">
  <title>@yield('title') | {{ env('APP_NAME') }}</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta
    content="Decompass, Compass, Compliance, IT Governance, Risk & Compliance (IT GRC), Information & Cyber Security, Audit & Assurance firm, ISO 27001, ISO 22301, ISO 20000, PCI DSS, COBIT, NIST, ITIL, TOGAF"
    name="keywords">
  <meta
    content="Decompass is a cybersecurity compliance tool designed to assist organizations in achieving and maintaining compliance with various frameworks such as ISO 27001, PCI-DSS, and more. It provides comprehensive assessments, personalized compliance roadmaps, and automated monitoring to streamline the compliance process. @yield('title')"
    name="description">
  <meta property="og:image" content="{{ URL::asset('img/favicon.ico') }}">

  <meta property="og:image:width" content="20">

  <meta property="og:image:height" content="20">
  <!-- Favicon -->
  <link href="{{ URL::asset('img/favicon.ico') }}" rel="icon">
  <link rel="icon" href="{{ URL::asset('img/favicon.ico') }}" sizes="32x32" />
  <link rel="icon" href="{{ URL::asset('img/favicon.ico') }}" sizes="192x192" />

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&family=Rubik:wght@400;500&display=swap"
    rel="stylesheet">

  <!-- Icon Font Stylesheet -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

  <!-- ibraries Stylesheet -->
  <link href="{{ URL::asset('lib/animate/animate.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
  <link href="{{ URL::asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">


  <!-- Customized Bootstrap Stylesheet -->
  <link href="{{ URL::asset('css/bootstrap.min.css') }}" rel="stylesheet">

  <!-- Template Stylesheet -->
  <link href="{{ URL::asset('css/style.css') }}" rel="stylesheet">
  @yield('styles')
</head>

<body>
  <!-- Spinner Start -->
  <div id="spinner"
    class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
      <!-- <span class="sr-only">Loading...</span> -->
    </div>
  </div>
  <!-- Spinner End -->

  <!-- Navbar & Carousel Start -->
  <div class="container-fluid fixed-top px-0 wow fadeIn">
    @include('layouts.navbar')
  </div>
  <!-- Navbar & Carousel End -->

  <div>
    @yield('content')
  </div>
  <!-- Footer Start -->
  <div class="container-fluid bg-dark text-light mt-0">
    <div class="container">
      <div class="row py-5">
        <div class="row gx-5">
          <div class="col-lg-4 col-md-4 pt-0 pt-lg-5 mb-5">
            <div class="section-title section-title-sm position-relative pb-3 mb-4">
              <h3 class="text-light mb-0">Quick Links</h3>
            </div>
            <div class="link-animated d-flex flex-column justify-content-start">
              <a class="text-light mb-2" href="/">Home</a>
              <a class="text-light mb-2" href="{{route('about_us')}}">Overview</a>
              <a class="text-light mb-2" href="{{route('features')}}">Features</a>
              <a class="text-light" href="/contact">Contact
                Us</a>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 pt-0 pt-lg-5 mb-5">
            <div class="section-title section-title-sm position-relative pb-3 mb-4">
              <h3 class="text-light mb-0">Resources</h3>
            </div>
            <div class="link-animated d-flex flex-column justify-content-start">
              <a href="{{route('faq')}}" class="text-light mb-2">FAQ</a>
              <!-- <a class="text-light mb-2"
                href="{{ route('public_resource_index', ['type' => 'cyber_security_article']) }}">Cybersecurity
                Articles</a>
              <a class="text-light mb-2" href="{{ route('public_resource_index', ['type' => 'video_tutorial']) }}">Video
                Tutorials</a>
              <a class="text-light mb-2"
                href="{{ route('public_resource_index', ['type' => 'webinars']) }}">Webinars</a>
              <a class="text-light mb-2" href="{{ route('public_resource_index', ['type' => 'case_studies']) }}">Case
                Studies</a>
              <a class="text-light mb-2"
                href="{{ route('public_resource_index', ['type' => 'industry_reports_and_survey']) }}">Industry
                Reports
                &
                Surveys</a>
              <a class="text-light mb-2"
                href="{{ route('public_resource_index', ['type' => 'podcast_and_interviews']) }}">Podcasts &
                Interviews</a> -->
            </div>
          </div>
          <div class="col-lg-4 col-md-4 pt-5 mb-5">
            <div class="section-title section-title-sm position-relative pb-3 mb-4">
              <h3 class="text-light mb-0">Get In Touch</h3>
            </div>
            <!-- <div class="d-flex mb-2">
              <p class="mb-0"><strong>Nigeria Address:</strong> 360 Herbert Macaulay Way, Yaba,<br> Lagos,
                Nigeria.</p>
            </div>
            <div class="d-flex mb-2">
              <p class="mb-0"><strong>UK Address:</strong>C/O Aacsl Accountants Limited, 1st Floor, North Westgate
                House,
                <br>Harlow, Essex, United Kingdom, CM20 1YS
              </p>
            </div> -->
            <div class="d-flex mb-2">
              <i class="fa fa-envelope-open me-2"></i>
              <p class="mb-0"><a href="mailto:info@decompass.com">info@decompass.com</a></p>
            </div>
            <!-- <div class="d-flex mb-2">
              <i class="fa fa-phone me-2"></i>
              <p class="mb-0"><a href="tel:+234 815 094 7567">+234 815 094 7567</a></p>
            </div> -->
            <!-- <div class="d-flex mt-4">
              <a class="btn btn-primary btn-square me-2"
                href="https://web.facebook.com/InfoAssure-100880868949546/?ref=pages_you_manage" target="_blank"><i
                  class="fab fa-facebook-f fw-normal"></i></a>
              <a class="btn btn-primary btn-square me-2"
                href="https://www.linkedin.com/company/infoassure-limited/posts/?feedView=all" target="_blank"><i
                  class="fab fa-linkedin-in fw-normal"></i></a>
              <a class="btn btn-primary btn-square" href="https://www.instagram.com/infoassureltd/" target="_blank"><i
                  class="fab fa-instagram fw-normal"></i></a>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="container-fluid text-white" style="background: #061429;">
    <div class="container text-center">
      <div class="row justify-content-end">
        <div class="col-lg-12">
          <div class="d-flex align-items-center justify-content-center" style="height: 75px;">
            <p class="mb-0">
              <a class="btn btn-default text-white" data-bs-target="#IMSPolicy" data-bs-toggle="modal">IMS
                Policy</a>
              <!-- <span class="text-primary">|</span>
              <a class="btn btn-default text-white" data-bs-target="#ISMSPolicy" data-bs-toggle="modal">ISMS
                Policy</a> -->
              <span class="text-primary">|</span>
              <a id="privacy_policy_show" class="btn btn-default text-white" data-bs-target="#privacyPolicy"
                data-bs-toggle="modal">Privacy
                Policy</a>
              <span class="text-primary">|</span>
              <a href="#" class="btn btn-default text-white">&copy; Compass</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Footer End -->
  <div id="privacyPolicy" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Privacy Policy</h4>
          <button type="button" class="close btn btn-default btn-lg text-danger"
            data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @include('pages.partials.privacy_policy')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="policyNotice" class="modal fade privacyNotice" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Privacy Notice</h4>
          {{-- <button type="button" class="close btn btn-default btn-lg text-danger"
            data-bs-dismiss="modal">&times;</button> --}}
        </div>
        <div class="modal-body">
          @include('pages.partials.privacy_notice')
        </div>
        <div class="modal-footer">
          <button id="policy_notice_close" type="button" class="btn btn-default" data-bs-dismiss="modal"
            onclick="setCookie('privacy_notice', 'shown', 1)">OK</button>
        </div>
      </div>

    </div>
  </div>
  <div id="ISMSPolicy" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">ISMS Policy</h4>
          <button type="button" class="close btn btn-default btn-lg text-danger"
            data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @include('pages.partials.ISMS_policy')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>
  <div id="IMSPolicy" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">IMS Policy</h4>
          <button type="button" class="close btn btn-default btn-lg text-danger"
            data-bs-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          @include('pages.partials.IMS_policy')
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Back to Top -->
  <!-- <a href="#" class="btn btn-outline-light btn-round back-to-top"><i class="bi bi-arrow-up"></i>
    BACK TO TOP</a> -->


  <!-- JavaScript Libraries -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ URL::asset('lib/wow/wow.min.js') }}"></script>
  <script src="{{ URL::asset('lib/easing/easing.min.js') }}"></script>
  <script src="{{ URL::asset('lib/waypoints/waypoints.min.js') }}"></script>
  <script src="{{ URL::asset('lib/counterup/counterup.min.js') }}"></script>
  <script src="{{ URL::asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
  <!-- <script src="{{ URL::asset('js/accordion.js') }}"></script> -->

  <!-- Template Javascript -->
  <script src="{{ URL::asset('js/main.js') }}"></script>

  <script src="https://unpkg.com/bootstrap-multiselect@0.9.13/dist/js/bootstrap-multiselect.js"></script>
  <!--Start of Tawk.to Script-->
  <script type="text/javascript">
    var Tawk_API = Tawk_API || {},
      Tawk_LoadStart = new Date();
    (function () {
      var s1 = document.createElement("script"),
        s0 = document.getElementsByTagName("script")[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/64ec62b2a91e863a5c1034b1/1h8tm3339';
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    })();
  </script>

  <script type="text/javascript">
    function setCookie(cname, cvalue, exdays) {
      console.log(cname);
      const d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      let expires = "expires=" + d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
  </script>
  <!--End of Tawk.to Script-->
  @yield('scripts')
</body>

</html>