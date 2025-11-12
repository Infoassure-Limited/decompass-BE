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

  <!--INTEGRATION AND IMPLEMENTATION -->

  <div class="container-fluid wow fadeInUp">
    <div class="container py-2">
      <div class="row g-5 py-2">
        <div class="col-lg-12 wow slideInUp">
          <div class="accordion">
            <div class="accordion__header accordion-active">
              <h5>Network
                Firewalls</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body accordion-active">
              <div id="network-firewall" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      A network firewall is a fundamental component of a network security infrastructure that acts as a
                      barrier
                      between a trusted internal network and untrusted external networks, such as the Internet. Its
                      primary function
                      is to monitor and control incoming and outgoing network traffic based on predetermined security
                      rules and
                      policies. By enforcing these rules, a network firewall helps protect an organization's network and
                      data from
                      unauthorized access, cyber threats, and potential security breaches.
                    </p>

                    <p class="mb-4" align="justify">
                      Network firewalls are essential security devices that help protect organizations' networks from
                      cyber threats
                      and unauthorized access. They provide an important defense against potential security breaches and
                      play a
                      crucial role in network security strategy and risk mitigation.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
                        src="/img/firewall.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Web Application
                Firewall</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="web-application" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      A Web Application Firewall (WAF) is a cyber-security solution designed to protect web applications
                      from a wide
                      range of online threats and attacks. Unlike traditional network firewalls that focus on network
                      traffic, WAF
                      specifically targets the application layer of the Open Systems Interconnection (OSI) model. It sits
                      between
                      users and the web application server, filtering and monitoring HTTP and HTTPS requests and
                      responses.
                    </p>

                    <p class="mb-4" align="justify">
                      In other words, a Web Application Firewall is a critical component of a comprehensive cyber-security
                      strategy,
                      providing an extra layer of protection for web applications against a wide range of threats and
                      attacks. WAFs
                      play a crucial role in safeguarding sensitive data, maintaining application availability, and
                      ensuring the
                      security and integrity of web applications.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
                        src="/img/web-application-firewall.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Securing Incident &
                Event Management
                (SIEM)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="securing-incident" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      Securing Incident and Event Management (SIEM) is a critical process in cyber-security that involves
                      securing
                      the tools, infrastructure, and processes used for collecting, analyzing, and responding to security
                      incidents
                      and events. SIEM is essential for detecting and mitigating potential cyber-security threats and
                      attacks,
                      helping organizations maintain a strong security posture.
                    </p>
                    <p class="mb-4" align="justify">

                      With the implementation of the SIEM solution, organizations can enhance their ability to detect and
                      respond to
                      cyber-security threats promptly, strengthen their overall security posture, and protect critical
                      assets and
                      sensitive data from potential cyber-attacks.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s" src="/img/siem.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>File Integrity Monitoring
                (FIM)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="fim" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      File Integrity Monitoring (FIM) is a cyber-security process that involves monitoring and detecting
                      unauthorized or unexpected changes to files and file systems on a computer system or network. FIM is
                      crucial
                      for maintaining the integrity and security of critical files, configuration files, system files, and
                      application files. It helps organizations detect potential security breaches, malware infections,
                      and
                      unauthorized modifications to critical files.
                    </p>
                    <p class="mb-4" align="justify">

                      This solution (FIM) is a vital cyber-security practice that helps organizations detect unauthorized
                      changes
                      and maintain the integrity of critical files and systems. By continuously monitoring and analyzing
                      file
                      changes, FIM enables early threat detection, enhanced security, and compliance with industry
                      standards and
                      regulations.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded wow zoomIn" src="/img/fim.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Multi-factor Authentication
                (2FA)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="2FA" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      Multi-factor authentication (MFA), also known as two-factor authentication (2FA), is a security
                      process that
                      requires users to provide two or more forms of identification or credentials to verify their
                      identity before
                      gaining access to a system, application, or online account. MFA is an essential security measure
                      that adds an
                      extra layer of protection beyond the traditional username and password authentication. It helps
                      mitigate the
                      risk of unauthorized access, data breaches, and identity theft.
                    </p>
                    <p class="mb-4" align="justify">

                      By implication, Multi-factor Authentication (2FA) is a critical security measure that significantly
                      strengthens user authentication by requiring multiple credentials to verify identity. By using a
                      combination
                      of factors, MFA helps protect against unauthorized access and data breaches, making it an essential
                      component
                      of a robust cyber-security strategy.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded wow zoomIn" src="/img/2fa.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Database Activity Monitoring
                (DAM)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="dam" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-7">
                    <p class="mb-4" align="justify">

                      Database Activity Monitoring (DAM) is a cyber-security technology that provides real-time
                      monitoring,
                      auditing, and analysis of database activities and events. DAM solutions are designed to track and
                      record all
                      database transactions and access attempts, allowing organizations to detect and respond to
                      suspicious or
                      unauthorized activities quickly. The primary goal of DAM is to enhance the security and compliance
                      of
                      databases by providing visibility into user activities and protecting sensitive data from potential
                      breaches.

                    </p>
                    <p class="mb-4" align="justify">

                      Database Activity Monitoring is a critical cyber-security technology for protecting databases and
                      sensitive
                      data. By providing real-time monitoring, auditing, and analysis of database activities, DAM enhances
                      security,
                      enables compliance, and helps organizations respond effectively to potential threats and security
                      incidents.
                    </p>
                  </div>
                  <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded wow zoomIn" src="/img/dam.jpg">
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="accordion__header">
              <h5>Mobile Device Management (MDM)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="dam" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">

                      MDM (Mobile Device Management): It is a software solution that allows organizations to manage and
                      secure mobile devices, such as smartphones, tablets, and laptops. MDM solutions can be used to:

                    </p>
                    <p class="mb-4" align="justify">
                    <ul>
                      <li>Enforce security policies: MDM solutions can be used to enforce security policies on mobile
                        devices such as; password policies, encryption policies, and application policies.</li>
                      <li>Remotely wipe devices: MDM solutions can be used to remotely wipe devices if they are lost or
                        stolen.</li>
                      <li>Track devices: MDM solutions can be used to track devices so that organizations can see where
                        they
                        are and who is using them.</li>
                      <li>Deploy applications: MDM solutions can be used to deploy applications to mobile devices.</li>
                      <li>Manage data: MDM solutions can be used to manage data on mobile devices, such as, by encrypting
                        data or by blocking access to certain types of data.</li>
                    </ul>
                    </p>
                    <p class="mb-4" align="justify">
                      MDM solutions are an important part of an organization's mobile security strategy. By using MDM
                      solutions, organizations can help protect their data and devices from unauthorized access, data
                      loss, and other security threats.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded wow zoomIn" src="/img/dam.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>


            <div class="accordion__header">
              <h5>Endpoint Detection and Response (EDR)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="dam" class="detail-class container-fluid py-5 wow fadeInUp">
                <div class="row g-5">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">

                      Endpoint detection and response (EDR) is a security solution that helps organizations detect and
                      respond to security threats on their endpoints. Endpoints are devices that are connected to a
                      network, such as laptops, desktops, Servers, mobile devices, etc.
                    </p>
                    <p class="mb-4" align="justify">
                      EDR solutions use a variety of techniques to detect threats, including:
                    <ul>
                      <li>Monitoring for suspicious activity: EDR solutions can monitor endpoints for suspicious activity,
                        such as, file changes and network connections.</li>
                      <li>Analysing system logs: EDR solutions can analyse system logs to look for signs of malicious
                        activity.</li>
                      <li>Using machine learning: EDR solutions can use machine learning to identify threats that are yet
                        to be known.</li>
                    </ul>
                    </p>
                    <p class="mb-4" align="justify">
                      Once a threat is detected, EDR solutions can take a variety of actions to respond, such as:
                    <ul>
                      <li>Isolating the infected endpoint: EDR solutions can isolate the infected endpoint from the
                        network to prevent the threat from spreading.</li>
                      <li>Removing the threat: EDR solutions can remove the threat from the infected endpoint.</li>
                      <li>Sending alerts: EDR solutions can send alerts to administrators so that they can act
                        appropriately.</li>
                    </ul>
                    </p>
                    <p class="mb-4" align="justify">
                      These solutions are an important part of any organization's security posture. By using such
                      solutions, organizations can help detect and respond to security threats on their endpoints, data
                      breach risk reduction, and other security incidents.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded wow zoomIn" src="/img/dam.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--INTEGRATION AND IMPLEMENTATION -->

@endsection
