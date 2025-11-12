@extends('layouts.app')
@section('title', 'Training Form')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h3 class="display-4 text-white animated zoomIn">Training Pre-Registration form</h3>
      </div>
    </div>
  </div>
  <div class="container-fluid py-5 wow fadeInUp">
    <div class="container py-5">
      <h3>Kindly Fill the form below</h3>
      <div class="row g-5">
        <div class="col-lg-12">

          @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif
          <form method="POST" action="{{ route('submit_training_form') }}">
            @csrf
            <div class="row g-3">
              <div class="col-md-12">
                <input type="text" class="form-control border-2 bg-light px-4" placeholder="Full Name"
                  style="height: 55px;" name="name" value="{{ old('name') }}" required>
                @if ($errors->has('name'))
                  <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
              <div class="col-md-12">
                <input type="email" class="form-control border-2 bg-light px-4" placeholder="Email Address"
                  style="height: 55px;" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
              <div class="col-md-12">
                <input type="number" class="form-control border-2 bg-light px-4" placeholder="Phone Number"
                  style="height: 55px;" name="phone_no" value="{{ old('phone_no') }}" required>
                @if ($errors->has('phone_no'))
                  <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                @endif
              </div>
              <div class="col-md-12">
                <fieldset class="form-control border-2 bg-light px-4">
                  <legend>Pick Course(s) of Interest</legend>
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO/IEC 27001 - INFORMATION SECURITY MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO/IEC 27001 -
                  INFORMATION SECURITY MANAGEMENT SYSTEM LEAD IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 22301 - BUSINESS CONTINUITY MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO 22301 - BUSINESS
                  CONTINUITY MANAGEMENT SYSTEM LEAD IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="ISO 27017 - CLOUD SECURITY CONTROL"> ISO 27017
                  -
                  CLOUD SECURITY CONTROL<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27001 INFORMATION SECURITY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 27001 INFORMATION SECURITY
                  MANAGEMENT SYSTEM LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 22301 BUSINESS CONTINUITY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 22301 BUSINESS CONTINUITY
                  MANAGEMENT SYSTEM LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 9001 QUALITY MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO 9001 QUALITY MANAGEMENT SYSTEM LEAD
                  IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27701 PRIVACY INFORMATION MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO 27701 PRIVACY
                  INFORMATION
                  MANAGEMENT SYSTEM LEAD IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="NDPA - NIGERIA DATA PROTECTION ACT"> NDPA -
                  NIGERIA DATA PROTECTION ACT<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="LEAD CYBERSECURITY MANAGER"> LEAD
                  CYBERSECURITY
                  MANAGER<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27701 PRIVACY INFORMATION MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 27701 PRIVACY INFORMATION
                  MANAGEMENT SYSTEM LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="GENERAL DATA PROTECTION REGULATION"> GENERAL
                  DATA PROTECTION REGULATION<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 20000 IT SERVICE MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 20000 IT SERVICE MANAGEMENT SYSTEM
                  LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27001 INFORMATION SECURITY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 27001 INFORMATION SECURITY
                  MANAGEMENT SYSTEM LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 9001 QUALITY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 9001 QUALITY MANAGEMENT SYSTEM LEAD
                  AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27035 - INFORMATION SECURITY INCIDENT MANAGEMENT"> ISO 27035 - INFORMATION SECURITY
                  INCIDENT
                  MANAGEMENT<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 27005 - INFORMATION SECURITY RISK MANAGEMENT"> ISO 27005 - INFORMATION SECURITY RISK
                  MANAGEMENT<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 45001 - HEALTH AND SAFETY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 45001 - HEALTH AND SAFETY
                  MANAGEMENT SYSTEM LEAD AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="ITIL FOUNDATION v4"> ITIL FOUNDATION
                  v4<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 9001 - QUALITY MANAGEMENT SYSTEM LEAD AUDITOR"> ISO 9001 - QUALITY MANAGEMENT SYSTEM LEAD
                  AUDITOR<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 45001 - HEALTH AND SAFETY MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO 45001 - HEALTH AND
                  SAFETY
                  MANAGEMENT SYSTEM LEAD IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]"
                    value="ISO 20000 - IT SERVICE MANAGEMENT SYSTEM LEAD IMPLEMENTER"> ISO 20000 - IT SERVICE MANAGEMENT
                  SYSTEM LEAD IMPLEMENTER<br /><br />
                  <input type="checkbox" name="course_of_interest[]" value="GDPR - GENERAL DATA PROTECTION REGULATION">
                  GDPR - GENERAL DATA PROTECTION REGULATION<br /><br />
                </fieldset>
              </div>
              <div class="col-12">
                <p>By clicking submit, I acknowledge and agree to InfoAssure's <a id="privacy_button"
                    class="btn-default text-primary" data-bs-target="#privacyPolicy" data-bs-toggle="modal"
                    style="cursor: pointer">Privacy
                    Policy</a></p>
                <a id="policy_notice" data-bs-target="#policyNotice" data-bs-toggle="modal"></a>
              </div>
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
              @if ($errors->has('g-recaptcha-response'))
                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
              @endif

              <div class="col-12">
                <button class="btn btn-success btn-round w-100 py-3" type="submit">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection
@section('scripts')
  <script>
    function getCookie(cname) {
      let name = cname + "=";
      let decodedCookie = decodeURIComponent(document.cookie);
      let ca = decodedCookie.split(';');
      for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    function checkCookie() {
      let notice = getCookie("privacy_notice");
      if (notice === "") {
        document.getElementById('policy_notice').click()
      }
    }

    checkCookie();
  </script>
@endsection
