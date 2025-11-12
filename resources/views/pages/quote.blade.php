@extends('layouts.app')
@section('title', 'Free Consultation')
@section('', 'active')
@section('styles')
  <link rel="stylesheet" href="{{ URL::asset('css/datepicker.min.css') }}">
@endsection
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Book A Demo</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!-- Quote Start -->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif
        <div class="col-lg-6">
          <!-- <div class="section-title position-relative pb-3 mb-1">
            <h1 class="mb-0">Kindly fill in the form for a demo</h1>
          </div>
   -->

          @php
            $today = date('Y-m-d', strtotime('now'));
            $date = date('H', strtotime('now'));
          @endphp
          <div class="bg-dark rounded h-100 d-flex align-items-center p-5 wow zoomIn" data-wow-delay="0.9s">
            <form method="POST" action="{{ route('submit_consultation_form') }}">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <input type="text" class="form-control border-2 bg-light px-4" placeholder="First Name"
                    style="height: 55px;" name="first_name" value="{{ old('first_name') }}" required>
                  @if ($errors->has('first_name'))
                    <span class="text-danger">{{ $errors->first('first_name') }}</span>
                  @endif
                </div>
                <div class="col-md-6">
                  <input type="text" class="form-control border-2 bg-light px-4" placeholder="Last Name"
                    style="height: 55px;" name="last_name" value="{{ old('last_name') }}" required>
                  @if ($errors->has('last_name'))
                    <span class="text-danger">{{ $errors->first('last_name') }}</span>
                  @endif
                </div>
                <div class="col-md-12">
                  <input type="text" class="form-control border-2 bg-light px-4" placeholder="Company Name"
                    style="height: 55px;" name="company_name" value="{{ old('company_name') }}">
                  @if ($errors->has('company_name'))
                    <span class="text-danger">{{ $errors->first('company_name') }}</span>
                  @endif
                </div>
                <div class="col-md-12">
                  <input type="email" class="form-control border-2 bg-light px-4" placeholder="Official Email"
                    style="height: 55px;" name="company_email" value="{{ old('company_email') }}" required>
                  @if ($errors->has('company_email'))
                    <span class="text-danger">{{ $errors->first('company_email') }}</span>
                  @endif
                </div>
                <div class="col-md-12">
                  <input type="number" class="form-control border-2 bg-light px-4" placeholder="Phone Number"
                    style="height: 55px;" name="phone_no" value="{{ old('phone_no') }}" required>
                  @if ($errors->has('phone_no'))
                    <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                  @endif
                </div>
                <!-- <div class="col-12">
                  <label for="date" style="color: #ffffff">Pick Date</label>
                  <input id="date" type="text" class="form-control bg-light border-0 some-input" placeholder="Pick Date"
                    style="height: 55px;" name="date" value="{{ $today }}" required onblur="setTime()">
                </div> -->
                <!-- <div class="col-xl-12">
                  <label style="color: #ffffff">Set Time</label>
                  <select style="display: block" name="time" id="for_today" placeholder="time"
                    class="form-select bg-light border-2" style="height: 55px;" value="{{ old('time') }}"
                    onfocus="setTime()">
                    <option selected value="">Select Time of consultation...</option>
                    @if ($date < 8)
            <option>8:00 am</option>
          @endif
                    @if ($date < 9)
            <option>9:00 am</option>
          @endif
                    @if ($date < 10)
            <option>10:00 am</option>
          @endif
                    @if ($date < 11)
            <option>11:00 am</option>
          @endif
                    @if ($date < 12)
            <option>12:00 noon</option>
          @endif
                    @if ($date < 13)
            <option>1:00 pm</option>
          @endif
                    @if ($date < 14)
            <option>2:00 pm</option>
          @endif
                    @if ($date < 15)
            <option>3:00 pm</option>
          @endif
                    @if ($date < 16)
            <option>4:00 pm</option>
          @endif
                    @if ($date < 17)
            <option>5:00 pm</option>
          @endif
                  </select>
                  <select style="display: none" name="time" id="future_date" placeholder="time"
                    class="form-select bg-light border-2" style="height: 55px;" onfocus="setTime()"
                    value="{{ old('time') }}">
                    <option selected value="">Select Time of consultation...</option>
                    <option>8:00 am</option>
                    <option>9:00 am</option>
                    <option>10:00 am</option>
                    <option>11:00 am</option>
                    <option>12:00 noon</option>
                    <option>1:00 pm</option>
                    <option>2:00 pm</option>
                    <option>3:00 pm</option>
                    <option>4:00 pm</option>
                    <option>5:00 pm</option>
                  </select>
                </div> -->
                <!-- <div class="col-12">
                  <select name="subject" placeholder="subject" class="form-select bg-light border-2" style="height: 55px;"
                    required value="{{ old('subject') }}">
                    <option selected value="">Reason for demo...</option>
                    <option>Interest in PCI DSS</option>
                    <option>Enquiries on VAPT</option>
                    <option>NDPA consultation</option>
                    <option>GDPR or Data Protection</option>
                    <option>Solution Integration and Deployment</option>
                    <option>About ISO Standards</option>
                    <option>Trainings</option>
                    <option>Others</option>
                  </select>
                  <br>
                  <input type="text" class="form-control border-0 bg-light px-4"
                    placeholder="If not stated above, please specify" style="height: 55px;" name="other_subject"
                    value="{{ old('other_subject') }}">
                </div> -->
                <div class="col-12">
                  <p class="text-white">By clicking send, I acknowledge and agree to Decompass's <a
                      class="btn-default text-primary" data-bs-target="#privacyPolicy" data-bs-toggle="modal"
                      style="cursor: pointer">Privacy
                      Policy</a>
                  </p>
                  <a id="policy_notice" data-bs-target="#policyNotice" data-bs-toggle="modal"></a>
                </div>
                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
                @if ($errors->has('g-recaptcha-response'))
                  <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                @endif
                <div class="col-12">
                  <button class="btn btn-success btn-round w-100 py-3">Send</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Quote End -->
@endsection
@section('scripts')
  <script src="{{ URL::asset('js/datepicker.min.js') }}"></script>
  <script>
    const picker = datepicker('.some-input', {
      noWeekends: true,
      minDate: new Date()
    });

    function setTime() {
      console.log('clicked')
      let date = document.getElementById('date').value;
      date = new Date(date).getDate();
      let today = new Date().getDate();
      if (date > today) {
        document.getElementById('for_today').style.display = 'none';
        document.getElementById("for_today").removeAttribute("required");

        document.getElementById('future_date').style.display = 'block';
        document.getElementById("future_date").setAttribute("required", "true");
      } else {
        document.getElementById('for_today').style.display = 'block';
        document.getElementById("for_today").setAttribute("required", "true");

        document.getElementById('future_date').style.display = 'none';
        document.getElementById("future_date").removeAttribute("required");
      }
    }
  </script>
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