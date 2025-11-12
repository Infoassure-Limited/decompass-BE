@extends('layouts.app')
@section('title', 'Contact Us')
@section('contact_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header">
    <div class="container py-5">
      <div class="row py-5">
        <div class="col-lg-7 pt-lg-5 mt-lg-5">
          <h1 class="display-4 text-white animated zoomIn">Contact Us Today</h1>
          <h4 class="text-white">We are here to serve!</h4>
          <!-- <div class="hide-on-mobile py-5">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.79309558564!2d3.3697934999999997!3d6.5477889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8d983008f58f%3A0x587d6c09d1a2d643!2s28%20OBANIKORO%20St%2C%20Pedro%20102216%2C%20Lagos!5e0!3m2!1sen!2sng!4v1689687546794!5m2!1sen!2sng"
              height="450" style="width: 80%; border:5px solid #ffffff; border-radius: 5px;" allowfullscreen=""
              loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          </div> -->
          <div class="container-fluid py-5 wow fadeInUp text-white" data-wow-delay="0.1s">
            <div class="container py-5">
              <div class="row g-5 mb-5 py-5">
                <div class="col-lg-12">
                  <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.4s">
                    <div class="d-flex rounded" style="width: 60px; height: 60px;">
                      <i class="bi bi-envelope fa-3x"></i>
                    </div>
                    <div class="ps-4">
                      <h5 class="mb-2 text-white">Send us a message through</h5>
                      <p class="mb-0"><a href="mailto:info@decompass.com" class="text-white">info@decompass.com</a></p>
                    </div>
                  </div>
                  {{-- <p>Thank you for your interest in our cybersecurity services. If you have any questions, feedback,
                    or
                    require
                    assistance, please don't hesitate to get in touch with us. We are here to help!</p> --}}
                  <!-- <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.1s">
                                    <div class="text-dark d-flex align-items-center justify-content-center rounded"
                                      style="width: 60px; height: 60px;">
                                      <i class="bi bi-telephone fa-3x"></i>
                                    </div>
                                    <div class="ps-4">
                                      <h5 class="mb-2">Call to ask any question</h5>
                                      <h5 class="text-primary mb-0"><a href="tel:+234 815 094 7567">+234 815 094 7567</a></h5>
                                    </div>
                                  </div> -->
                  <br>
                  <!-- <div class="d-flex align-items-center wow fadeIn" data-wow-delay="0.8s">
                    <div class="d-flex align-items-center justify-content-center rounded"
                      style="width: 60px; height: 60px;">
                      <i class="fa fa-map-marker-alt fa-3x"></i>
                    </div>
                    <div class="ps-4">
                      <h5 class="mb-2 text-white">Addresses</h5>
                      <p class="mb-4"><strong>Nigeria Address:</strong> 360 Herbert Macaulay Way, Yaba,<br> Lagos,
                        Nigeria.</p>
                      <p class="mb-4"><strong>UK Address:</strong>C/O Aacsl Accountants Limited, 1st Floor, North Westgate
                        House,
                        <br>Harlow, Essex, United Kingdom, CM20 1YS
                      </p>
                    </div>
                  </div> -->
                  <p>&nbsp;</p>
                  <!-- <p>Follow us on social media to stay updated with the latest cybersecurity news, tips, and trends.</p>
                    <div class="d-flex mt-4">
                      <a class="btn btn-primary btn-square me-2"
                        href="https://web.facebook.com/Decompass-100880868949546/?ref=pages_you_manage" target="_blank"><i
                          class="fab fa-facebook-f fw-normal"></i></a>
                      <a class="btn btn-primary btn-square me-2"
                        href="https://www.linkedin.com/company/Decompass-limited/posts/?feedView=all" target="_blank"><i
                          class="fab fa-linkedin-in fw-normal"></i></a>
                      <a class="btn btn-primary btn-square" href="https://www.instagram.com/Decompassltd/" target="_blank"><i
                          class="fab fa-instagram fw-normal"></i></a>
                    </div> -->
                </div>
              </div>

              <div class="row g-5 mb-5  show-on-mobile">
                <!-- <div class="col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.79309558564!2d3.3697934999999997!3d6.5477889!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b8d983008f58f%3A0x587d6c09d1a2d643!2s28%20OBANIKORO%20St%2C%20Pedro%20102216%2C%20Lagos!5e0!3m2!1sen!2sng!4v1689687546794!5m2!1sen!2sng"
                    height="550" style="width: 100%; border:5px solid #152cb1; border-radius: 5px;" allowfullscreen=""
                    loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div> -->
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5 pt-lg-5 mt-lg-5 py-5">

          @if (session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif
          <form method="POST" action="{{ route('submit_contact_form') }}">
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
              <div class="col-md-6">
                <input type="email" class="form-control border-2 bg-light px-4" placeholder="Company Email"
                  style="height: 55px;" name="company_email" value="{{ old('company_email') }}" required>
                @if ($errors->has('company_email'))
                  <span class="text-danger">{{ $errors->first('company_email') }}</span>
                @endif
              </div>
              <div class="col-md-6">
                <input type="number" class="form-control border-2 bg-light px-4" placeholder="Phone Number"
                  style="height: 55px;" name="phone_no" value="{{ old('phone_no') }}" required>
                @if ($errors->has('phone_no'))
                  <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                @endif
              </div>
              <div class="col-md-6">
                <select name="job_function" id="" placeholder="Choose a job function"
                  class="form-select bg-light border-2" style="height: 55px;" value="{{ old('job_function') }}">
                  <option selected value="">Choose a job function...</option>
                  <option>Administration & Operations</option>
                  <option>Business & Strategy</option>
                  <option>Creative & Communication</option>
                  <option>People & Services</option>
                  <option>Technical & Specialized</option>
                  <option>Others</option>
                </select>
              </div>
              <div class="col-md-6">
                <select name="job_level" id="" placeholder="Job Level" class="form-select bg-light border-2"
                  style="height: 55px;" value="{{ old('job_level') }}">
                  <option selected value="">Job Level...</option>
                  <option>Entry Level</option>
                  <option>Mid-Level</option>
                  <option>Senior Level</option>
                  <option>Managerial Level</option>
                  <option>Director/Chief Level</option>
                  <option>Others</option>
                </select>
              </div>
              <div class="col-12">
                <select name="country" id="" placeholder="Select Country" class="form-select bg-light border-2"
                  style="height: 55px;" value="{{ old('country') }}" required>
                  <option value="">Select Country...</option>
                  <option value="Australia">Australia</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling Islands)">Cocos (Keeling Islands)</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Cote D'Ivoire (Ivory Coast)">Cote D'Ivoire (Ivory Coast)</option>
                  <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="Dominica">Dominica</option>
                  <option value="East Timor">East Timor</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="Metropolitan"> Metropolitan</option>
                  <option value="France">France</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guinea-Bissau">Guinea-Bissau</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard and McDonald Islands">Heard and McDonald Islands</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran">Iran</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Korea (North)">Korea (North)</option>
                  <option value="Korea (South)">Korea (South)</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Laos">Laos</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libya">Libya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macau">Macau</option>
                  <option value="Macedonia">Macedonia</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia">Micronesia</option>
                  <option value="Moldova">Moldova</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Nigeria" selected>Nigeria</option>
                  <option value="Niger">Niger</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Reunion">Reunion</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="S. Georgia and S. Sandwich Isls.">S. Georgia and S. Sandwich Isls.</option>
                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovak Republic">Slovak Republic</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="St. Helena">St. Helena</option>
                  <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syria">Syria</option>
                  <option value="Taiwan">Taiwan</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania">Tanzania</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="US Minor Outlying Islands">US Minor Outlying Islands</option>
                  <option value="Uganda">Uganda</option>

                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>

                  <option value="United Kingdom (GB)">United Kingdom (GB)</option>
                  <option value="United States">United States</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Vatican City State">Vatican City State</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                  <option value="Virgin Islands (US)">Virgin Islands (US)</option>
                  <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Yugoslavia">Yugoslavia</option>
                  <option value="Zaire">Zaire</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
                </select>
              </div>
              <div class="col-12">
                <select name="subject" id="" placeholder="subject" class="form-select bg-light border-2"
                  style="height: 55px;" value="{{ old('subject') }}" required>
                  <option selected value="">Select Subject of interest...</option>
                  <option>Interest in PCI DSS</option>
                  <option>Enquiries on VAPT</option>
                  <option>NDPA</option>
                  <option>GDPR or Data Protection</option>
                  <option>Solution Integration and Deployment</option>
                  <option>About ISO Standards</option>
                  <option>Trainings</option>
                  <option>Others</option>
                </select>
                <br>
                <input type="text" class="form-control border-0 bg-light px-4"
                  placeholder="If your subject of interest is not stated above, please specify" style="height: 55px;"
                  name="other_subject" value="{{ old('other_subject') }}">
              </div>
              <div class="col-12">
                <textarea class="form-control border-2 bg-light px-4 py-3" rows="4"
                  placeholder="Type your Message Here..." name="message" required>{{ old('message') }}</textarea>
              </div>
              <div class="col-12 text-white">
                <input type="checkbox" value="1" class="form-checkbox" name="subscribe" checked>&nbsp;&nbsp; I
                want
                to receive news,
                feature updates
                and offers
                from Decompass
              </div>
              <div class="col-12 text-white">
                <p>By clicking send, I acknowledge and agree to Decompass's <a id="privacy_button"
                    class="btn-default text-white" data-bs-target="#privacyPolicy" data-bs-toggle="modal"
                    style="cursor: pointer">Privacy
                    Policy</a></p>
                <a id="policy_notice" data-bs-target="#policyNotice" data-bs-toggle="modal"></a>
              </div>
              <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.key') }}"></div>
              @if ($errors->has('g-recaptcha-response'))
                <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
              @endif

              <div class="col-12">
                <button class="btn btn-success btn-round w-100 py-3" type="submit">Send</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Contact Details Start -->
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