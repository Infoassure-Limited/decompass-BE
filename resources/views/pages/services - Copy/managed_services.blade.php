@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Managed Services</h1>
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


  <div id="managed-services" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-12">
          {{-- <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Managed Services</h1>
          </div> --}}
          <p class="mb-4" align="justify">

            Our Parent company offer Managed Security Service aimed at providing organizations with a cost efficient model
            of achieving Information Security monitoring.<br>
            Through our Managed Services solutions offerings, we have demonstrated a distinctive ability to enhance the
            Information Security capabilities of our customers, both on premise and in the cloud.
          </p>

          <p class="mb-4" align="justify">
            Our managed services include:
          <ul>
            <li>Managed Compliance - Managed Compliance is a strategic approach that helps organizations streamline their
              compliance efforts, enhance their compliance posture, and ensure ongoing adherence to regulatory and
              industry standards. It provides peace of mind and allows businesses to navigate the complexities of
              compliance more efficiently. </li>
            <li>Managed SIEM (Securing Incident & Event Management) - Managed SIEM is a valuable service that allows
              organizations to enhance their cyber-security capabilities by leveraging the expertise of a dedicated team
              of security professionals. It enables real-time monitoring, threat detection, and incident response, helping
              organizations improve their security posture and defend against evolving cyber threats.</li>
            <li>Managed Firewall - Managed Firewall is a valuable cyber-security service that enables organizations to
              maintain a robust security posture by outsourcing the management of their firewall infrastructure to
              specialized Managed Security Services Providers (MSSPs). It provides continuous monitoring, proactive threat
              detection, and incident response, helping organizations safeguard their networks from potential cyber
              threats and security breaches.</li>
            <li>Managed SOC (Security Operations Center) - Managed SOC is a valuable cyber-security service that allows
              organizations to strengthen their security defenses by outsourcing their security monitoring and incident
              response to a specialized MSSP. The Managed SOC provides continuous monitoring, proactive threat detection,
              and incident response, helping organizations protect their valuable assets and data from evolving cyber
              threats.</li>
          </ul>




          </p>
        </div>
        {{-- <div class="col-lg-5">
          <div class="position-relative">
            <img class="position-absolute w-100  rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/information-system-is-audit.jpg">
          </div>
        </div> --}}
      </div>
    </div>
  </div>
@endsection
