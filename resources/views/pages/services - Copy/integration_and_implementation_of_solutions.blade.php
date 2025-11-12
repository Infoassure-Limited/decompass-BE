@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Integration & Implementation of Solutions</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3">We offer the following Integration & Implementation of Solutions Services</h4>
            </div>
            <div class="p-5 pt-0">
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#network-firewall"><i class="fa fa-arrow-right text-primary pt-1"></i> Network
                    Firewalls</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#web-application"><i class="fa fa-arrow-right text-primary pt-1"></i> Web Application
                    Firewall</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#securing-incident"><i class="fa fa-arrow-right text-primary pt-1"></i> Securing Incident &
                    Event Management
                    (SIEM)</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#fim"><i class="fa fa-arrow-right text-primary pt-1"></i> File Integrity Monitoring
                    (FIM)</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#2FA"><i class="fa fa-arrow-right text-primary pt-1"></i> Multi-factor Authentication
                    (2FA)</a></span>
              </div>

              <div class="d-flex justify-content-between mb-3">
                <span><a href="#dam"><i class="fa fa-arrow-right text-primary pt-1"></i> Database Activity Monitoring
                    (DAM)</a></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Services End -->

  <!--INTEGRATION AND IMPLEMENTATION -->

  {{-- <div id="implemented-integration" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-12">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Implementation and Integration</h1>
          </div>
          <p class="mb-4" align="justify">

            Cyber incidences are on the increase and the menace of cybercrime is the new reality for businesses globally
            as the threat landscape changes constantly. Corporations and business owners now have the understanding that
            it is not a matter of if you are vulnerable or if will be targeted, but a matter of when. As a result, it has
            become pertinent that security is engineered into applications, networks and IT environments for organizations
            to stand a chance of withstanding breaches, mitigating compromises and preventing service disruptions that can
            be costly to the growth and future of their organization.
          </p>

          <p class="mb-4" align="justify">
            To mitigate this, we deliver and deploy world-class security solutions and services bringing to bear our
            experience as certified professionals and implementation partners for leading OEM’s and security solution
            providers.
          </p>

          <p class="mb-4" align="justify">
            Our implementation & integration offerings include:
          <ul>
            <li>Threat Detection Platforms</li>
            <li>Data Loss Prevention Solution</li>
            <li>Network Perimeter Protection Solutions</li>
            <li>Endpoint Protection Solutions</li>
            <li>Network Security Policy Management Solutions</li>
            <li>Two Factor Authentication Systems</li>
            <li>Vulnerability Management Platforms</li>
            <li>File Integrity Monitoring Systems</li>
            <li>Wireless Intrusion Prevention Systems</li>
            <li>Database Activity Monitoring Systems</li>
            <li>Virtualisation Security</li>
          </ul>




          </p>
        </div>
      </div>
    </div>
  </div> --}}
  <div id="network-firewall" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Network Firewalls</h1>
          </div>
          <p class="mb-4" align="justify">

            A network firewall is a fundamental component of a network security infrastructure that acts as a barrier
            between a trusted internal network and untrusted external networks, such as the Internet. Its primary function
            is to monitor and control incoming and outgoing network traffic based on predetermined security rules and
            policies. By enforcing these rules, a network firewall helps protect an organization's network and data from
            unauthorized access, cyber threats, and potential security breaches.
          </p>

          <p class="mb-4" align="justify">
            Network firewalls are essential security devices that help protect organizations' networks from cyber threats
            and unauthorized access. They provide an important defense against potential security breaches and play a
            crucial role in network security strategy and risk mitigation.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s" src="/img/firewall.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="web-application" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Web Application Firewall</h1>
          </div>
          <p class="mb-4" align="justify">

            A Web Application Firewall (WAF) is a cyber-security solution designed to protect web applications from a wide
            range of online threats and attacks. Unlike traditional network firewalls that focus on network traffic, WAF
            specifically targets the application layer of the Open Systems Interconnection (OSI) model. It sits between
            users and the web application server, filtering and monitoring HTTP and HTTPS requests and responses.
          </p>

          <p class="mb-4" align="justify">
            In other words, a Web Application Firewall is a critical component of a comprehensive cyber-security strategy,
            providing an extra layer of protection for web applications against a wide range of threats and attacks. WAFs
            play a crucial role in safeguarding sensitive data, maintaining application availability, and ensuring the
            security and integrity of web applications.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="/img/web-application-firewall.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="securing-incident" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Securing Incident & Event Management (SIEM)</h1>
          </div>
          <p class="mb-4" align="justify">

            Securing Incident and Event Management (SIEM) is a critical process in cyber-security that involves securing
            the tools, infrastructure, and processes used for collecting, analyzing, and responding to security incidents
            and events. SIEM is essential for detecting and mitigating potential cyber-security threats and attacks,
            helping organizations maintain a strong security posture.
          </p>
          <p class="mb-4" align="justify">

            With the implementation of the SIEM solution, organizations can enhance their ability to detect and respond to
            cyber-security threats promptly, strengthen their overall security posture, and protect critical assets and
            sensitive data from potential cyber-attacks.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s" src="/img/siem.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="fim" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">File Integrity Monitoring (FIM)</h1>
          </div>
          <p class="mb-4" align="justify">

            File Integrity Monitoring (FIM) is a cyber-security process that involves monitoring and detecting
            unauthorized or unexpected changes to files and file systems on a computer system or network. FIM is crucial
            for maintaining the integrity and security of critical files, configuration files, system files, and
            application files. It helps organizations detect potential security breaches, malware infections, and
            unauthorized modifications to critical files.
          </p>
          <p class="mb-4" align="justify">

            This solution (FIM) is a vital cyber-security practice that helps organizations detect unauthorized changes
            and maintain the integrity of critical files and systems. By continuously monitoring and analyzing file
            changes, FIM enables early threat detection, enhanced security, and compliance with industry standards and
            regulations.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/fim.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="2FA" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Multi-factor Authentication (2FA)</h1>
          </div>
          <p class="mb-4" align="justify">

            Multi-factor authentication (MFA), also known as two-factor authentication (2FA), is a security process that
            requires users to provide two or more forms of identification or credentials to verify their identity before
            gaining access to a system, application, or online account. MFA is an essential security measure that adds an
            extra layer of protection beyond the traditional username and password authentication. It helps mitigate the
            risk of unauthorized access, data breaches, and identity theft.
          </p>
          <p class="mb-4" align="justify">

            By implication, Multi-factor Authentication (2FA) is a critical security measure that significantly
            strengthens user authentication by requiring multiple credentials to verify identity. By using a combination
            of factors, MFA helps protect against unauthorized access and data breaches, making it an essential component
            of a robust cyber-security strategy.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/2fa.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="dam" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Database Activity Monitoring (DAM)</h1>
          </div>
          <p class="mb-4" align="justify">

            Database Activity Monitoring (DAM) is a cyber-security technology that provides real-time monitoring,
            auditing, and analysis of database activities and events. DAM solutions are designed to track and record all
            database transactions and access attempts, allowing organizations to detect and respond to suspicious or
            unauthorized activities quickly. The primary goal of DAM is to enhance the security and compliance of
            databases by providing visibility into user activities and protecting sensitive data from potential breaches.

          </p>
          <p class="mb-4" align="justify">

            Database Activity Monitoring is a critical cyber-security technology for protecting databases and sensitive
            data. By providing real-time monitoring, auditing, and analysis of database activities, DAM enhances security,
            enables compliance, and helps organizations respond effectively to potential threats and security incidents.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/dam.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--INTEGRATION AND IMPLEMENTATION -->

@endsection
