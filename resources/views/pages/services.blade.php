@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Services</h1>
        {{-- <a href="/" class="h5 text-white">Home</a>
        <i class="far fa-circle text-white px-2"></i>
        <a href="services" class="h5 text-white">Services</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
        <h1 class="mb-0">We offer the following services</h1>
      </div>
      <div class="row g-5">
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3">Advisory Services</h4>
            </div>
            <div class="p-5 pt-0">
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#info-security-management"><i class="fa fa-arrow-right text-primary pt-1"></i> Information
                    Security Management System, ISO
                    27001</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#business-continuity-management"><i class="fa fa-arrow-right text-primary pt-1"></i>
                    Business Continuity Management System, ISO
                    22301</a></span>
              </div>

              <div class="d-flex justify-content-between mb-3">
                <span><a href="#it-service-management"><i class="fa fa-arrow-right text-primary pt-1"></i> IT Service
                    Management System, ISO 20000</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#pci-dss"><i class="fa fa-arrow-right text-primary pt-1"></i> Payment Card Data Security
                    Standard (PCI
                    DSS)</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#dpia"><i class="fa fa-arrow-right text-primary pt-1"></i> Data Privacy Impact Assessment
                    (DPIA)</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#qms"><i class="fa fa-arrow-right text-primary pt-1"></i> Quality Management System ISO
                    9001</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#hsm"><i class="fa fa-arrow-right text-primary pt-1"></i> Health and Safety Management
                    System, ISO
                    45001</a></span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3"><a href="#implemented-integration"> Integration & Implementation of Solutions</a></h4>
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
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.6s">
          <div
            class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
            <h4 class="mb-3"><a href="#managed-services">Managed Services</a></h4>
          </div>
        </div>
        {{-- <div class="col-lg-4 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3"><a href="#managed-services">Managed Services</a></h4>
            </div>
            <div class="p-5 pt-0">
              <div class="d-flex justify-content-between mb-3">
                <span><i class="fa fa-arrow-right text-primary pt-1"></i> Managed Compliance</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><i class="fa fa-arrow-right text-primary pt-1"></i> Managed SIEM</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><i class="fa fa-arrow-right text-primary pt-1"></i> Managed Firewall</span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><i class="fa fa-arrow-right text-primary pt-1"></i> Managed SOC (Security Operations Center)</span>
              </div>
            </div>
          </div>
        </div> --}}
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
          <div
            class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
            <h4 class="mb-3"><a href="#vulnerability-testing-services">Vulnerability & Penetration Testing Services</a>
            </h4>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">
          <div
            class="service-item bg-light rounded d-flex flex-column align-items-center justify-content-center text-center">
            <h4 class="mb-3"><a href="#training">Training</a></h4>
          </div>
        </div>
        {{-- <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.9s">
          <div
            class="position-relative bg-primary rounded h-100 d-flex flex-column align-items-center justify-content-center text-center p-5">
            <h3 class="text-white mb-3">Call Us For Quote</h3>
            <p class="text-white mb-3">Clita ipsum magna kasd rebum at ipsum amet dolor justo dolor est magna stet eirmod
            </p>
            <h2 class="text-white mb-0">+012 345 6789</h2>
          </div>
        </div> --}}
      </div>
    </div>
  </div>
  <!-- Services End -->
  <!--Advisory Services Details-->
  <div id="info-security-management" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Information Security Management Systems (ISO 27001)</h1>
          </div>
          <p class="mb-4" align="justify">

            Security breaches and cyberattacks are on the increase and cost organizations several trillions of dollar
            every year. This has made cybersecurity a top issue for IT, Security, Legal and the board room. The adoption
            of ISO 27001 in any Organization is a demonstration of top management commitment to the security of critical
            information assets within such Organization.
          </p>

          <p class="mb-4" align="justify">
            ISO 27001, the international best practice standard for Information Security Management System is the only
            internationally recognized and trusted information security management standard that can be independently
            certified to cover People, Process and Technology. The adoption of ISO 27001 also helps organization in
            demonstrating compliance to legal, regulatory and contractual requirements.
          </p>

          <p class="mb-4" align="justify">
            InfoAssure Limited has a team of certified professionals with in-depth knowledge and understanding of the ISO
            27001 requirements that will ensure the operationalization of the standards to meet business, legal,
            contractual and regulatory requirements.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/Information-Security-Management-Systems-ISO-27001_.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="business-continuity-management" class="detail-class container-fluid py-5 wow fadeInUp"
    data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Business Continuity Management Systems (ISO 22301)</h1>
          </div>
          <p class="mb-4" align="justify">

            We live in hazardous times. Each second of the day, somewhere in the world, there are triggers and events.
            Devastating effects. Events that might have consequences effect that no one can foresee- Natural disasters and
            manmade ones. Events that can harm people and business. We may not be able to stop them from happening but we
            can be prepared. <br>The Covid-19 Pandemic has once again justified the need for organization’s to take
            proactive measures in ensuring that business continues during and after a disruption. The implementation of
            the ISO 22301 standard will proactively improve Organization’s resilience during disruptions, effectively plan
            for pandemic & epidemic, plan for succession, and evaluate suppliers’ business continuity capabilities amongst
            others.
          </p>

          <p class="mb-4" align="justify">
            InfoAssure Limited has a team of certified professionals with in-depth knowledge and understanding of the ISO
            22301 requirements that will ensure the operationalization of the standards to meet business, legal,
            contractual and regulatory requirements.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/Business-Continuity-Management-Systems-ISO-22301.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="it-service-management" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">IT Service Management System (ISO 20000)</h1>
          </div>
          <p class="mb-4" align="justify">

            ISO 20000 is the international best practice standard for IT Service Management (ITSM) and ensures that
            Organization derives value from IT investments, ensure resources are effectively managed and ensures that ITSM
            processes align with the strategic objective of the business and also align with the requirements of best
            practice. <br>The adoption of ISO 20000 also ensures that companies can continuously improve the delivery of
            their IT services.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/it-service-management-information-technology.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="pci-dss" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Payment Card Industry Data Security Standard (PCI DSS)</h1>
          </div>
          <p class="mb-4" align="justify">

            The Payment Card Industry Data Security Standard (PCI DSS) is for Organizations that transmit, process or
            store cardholder data. It is important for customers to know your website is secured. The implementation and
            adoption of PCI DSS helps to reduce the risk of credit and debit card data loss. <br>A team of Qualified
            Security Assessor (QSA) will carry out the compliance assessment and a report on compliance is issued on
            provision of successful evidence.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="dpia" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Data Privacy Impact Assessment (DPIA)</h1>
          </div>
          <p class="mb-4" align="justify">

            A Data Privacy Impact Assessment (DPIA), also known as a Privacy Impact Assessment (PIA), is a systematic and
            comprehensive assessment of the potential risks and impacts on individuals' privacy resulting from the
            processing of personal data. DPIA is an essential tool for organizations to identify and mitigate privacy
            risks in their data processing activities, ensuring compliance with data protection laws and regulations.<br>
            The purpose of a DPIA is to assess the data processing activities from a privacy perspective, understand the
            potential risks, and take appropriate measures to reduce or eliminate those risks. It allows organizations to
            be proactive in protecting individuals' personal data and demonstrating accountability in their data
            processing practices. <br>
            In essence, a DPIA is a proactive and essential tool for organizations to assess and manage privacy risks
            associated with their data processing activities. It helps organizations to protect individuals' privacy
            rights, demonstrate compliance, and build trust with their customers and stakeholders.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="qms" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Quality Management System ISO 9001</h1>
          </div>
          <p class="mb-4" align="justify">

            ISO 9001 is an international standard that sets out the requirements for a Quality Management System (QMS). A
            QMS is a structured framework that helps organizations manage and improve the quality of their products,
            services, and processes. ISO 9001 provides a systematic approach to ensure that an organization consistently
            meets customer requirements, complies with applicable regulations, and strives for continuous improvement.<br>

          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="hsm" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Health and Safety Management System, ISO 45001</h1>
          </div>
          <p class="mb-4" align="justify">
            ISO 45001 is an international standard that sets the requirements for an Occupational Health and Safety
            Management System (OH&SMS). The standard provides a framework for organizations to identify and control health
            and safety risks, minimize the potential for accidents and injuries, and ensure compliance with health and
            safety regulations. ISO 45001 is designed to help organizations create a safe and healthy working environment
            for employees and other stakeholders.<br>
            ISO 45001 is a powerful tool for organizations to systematically manage occupational health and safety risks,
            protect their workforce, and create a safe and healthy work environment.

          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="regulatory-compliance" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Regulatory Compliance and Reporting</h1>
          </div>
          <p class="mb-4" align="justify">
            Regulatory Compliance and Reporting refer to the process of adhering to the laws, regulations, and standards
            that govern an organization's industry or operations and providing accurate and timely information to
            regulatory authorities. This is essential for ensuring that companies operate within legal boundaries, meet
            industry standards, and maintain transparency and accountability.


          </p>
          <p class="mb-4" align="justify">
            Regulatory Compliance and Reporting are crucial aspects of business operations, ensuring that organizations
            operate within legal and ethical boundaries and meet their reporting obligations to relevant authorities. By
            adhering to regulatory requirements and maintaining transparent and accurate reporting, companies can build
            trust with stakeholders and demonstrate their commitment to responsible business practices.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="management-system" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Manage Systems Certification Audit</h1>
          </div>
          <p class="mb-4" align="justify">
            A Management Systems Certification Audit, also known as a Certification Audit or Third-Party Audit, is an
            independent assessment conducted by an accredited certification body to evaluate an organization's management
            system's compliance with specific international standards. The purpose of this audit is to determine whether
            the organization's management system conforms to the requirements of the chosen standard(s) and if it is
            effectively implemented and maintained. The certification process provides external validation of the
            organization's commitment to meeting international best practices and standards.

          </p>
          <p class="mb-4" align="justify">
            The Management Systems Certification Audit provides numerous benefits, including increased credibility and
            market reputation, enhanced customer confidence, compliance with industry requirements, and continual
            improvement of business processes. It demonstrates the organization's commitment to quality, safety,
            environmental responsibility, information security, or other specific aspects covered by the chosen management
            system standard.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Advisory Services Details ends-->

  <!--INTEGRATION AND IMPLEMENTATION -->

  <div id="implemented-integration" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
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
        {{-- <div class="col-lg-5">
          <div class="position-relative">
            <img class="position-absolute w-100  rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/information-system-is-audit.jpg">
          </div>
        </div> --}}
      </div>
    </div>
  </div>
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
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/Information-Security-Management-Systems-ISO-27001_.jpg">
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
              src="img/Business-Continuity-Management-Systems-ISO-22301.jpg">
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
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/it-service-management-information-technology.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--INTEGRATION AND IMPLEMENTATION -->
  <!--Audit & Assurance Services-->
  <div id="information-system-audit" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Information Systems Audit</h1>
          </div>
          <p class="mb-4" align="justify">

            Information Systems (IS) Audit or Information Technology (IT) Audit aims at evaluating the effectiveness of an
            information system’s control and to establish that the integrity of processing, stored or transfer of data is
            maintained, information systems are safeguarding corporate assets and operating effectively to achieve the
            organization’s goals or objectives.
          </p>

          <p class="mb-4" align="justify">
            In carrying out an effective information systems or technology audit, we adopt a risk based IT audit approach.
            This provide reasonable assurance that risk management processes are managing IT risks effectively in relation
            to the organization’s risk appetite.
          </p>

          <p class="mb-4" align="justify">
            Some of the IT risk factors we consider before carrying out an audit include: the criticality of information
            premised on the information security principle – Confidentiality, Integrity and Availability, and also the
            Materiality, Reputational fallout, Strategic plan support, Fraud, Changes in the IT environment and Outsourced
            Risks.
          </p>
          <p class="mb-4" align="justify">
            Our team of Auditors have over a decade experience, helping organizations in the development of internal audit
            programs to evaluate information systems effectiveness and readiness per standard requirements. We also
            perform reviews of application systems, Operating Systems, Databases, Networks and Infrastructures. Other
            responsibilities includes identifying technology related risks and testing associated general IT controls, to
            ascertain controls are operating effectively. We have performed reviews on Organization’s IT platforms,
            Business Continuity, Change Management, Cyber Risk and IT Governance.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/information-system-is-audit.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="revenue-assurance" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Revenue Assurance</h1>
          </div>
          <p class="mb-4" align="justify">

            Revenue Assurance is a crucial process in telecommunications, finance, and other industries where companies
            generate revenue from their products or services. It involves the systematic identification, prevention, and
            recovery of potential revenue leakages and losses to ensure that the company's revenue stream is maximized and
            protected. The goal of Revenue Assurance is to safeguard the company's financial health and maintain
            profitability by reducing revenue leakage and optimizing revenue collection processes.
          </p>

          <p class="mb-4" align="justify">
            Revenue Assurance is a critical process that ensures companies optimize their revenue streams, reduce revenue
            leakages, and enhance overall business performance. It involves a comprehensive approach that spans multiple
            departments and relies on accurate data and robust processes to safeguard a company's financial health.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/information-system-is-audit.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="financial-statement" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Financial Statement Audit</h1>
          </div>
          <p class="mb-4" align="justify">

            A Financial Statement Audit is an independent examination of an organization's financial statements by a
            qualified external auditor. The primary objective of a financial statement audit is to provide reasonable
            assurance that the financial statements present a true and fair view of the company's financial position,
            performance, and cash flows in accordance with the applicable financial reporting framework (such as Generally
            Accepted Accounting Principles - GAAP or International Financial Reporting Standards - IFRS).
          </p>

          <p class="mb-4" align="justify">
            Financial Statement Audit is a thorough and independent examination of an organization's financial statements
            to ensure they present a true and fair view of its financial performance and position. The audit process
            involves gathering evidence, evaluating internal controls, and issuing an audit report with the auditor's
            opinion on the financial statements' reliability.
          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/information-system-is-audit.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Audit & Assurance Services-->
  <div id="managed-services" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-12">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Managed Services</h1>
          </div>
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
  <div id="vulnerability-testing-services" class="detail-class container-fluid py-5 wow fadeInUp"
    data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Vulnerability Assessment and Penetration Testing (VAPT)</h1>
          </div>
          <p class="mb-4" align="justify">

            Information systems and controls need to be tested frequently to ascertain their resilience against malicious
            attacks. When the need arises as it always does, we would be glad to avail you with our top notch, industry
            standard vulnerability assessment and penetration testing services.
          </p>

          <p class="mb-4" align="justify">
            Our methodologies are quite structured as they follow standardized processes and frameworks like Open Source
            Security Testing Methodology (OSSTMM), Open Web Application Security Project (OWASP) project, Penetration
            Testing Execution Standard (PTES), and the US National Institute of Standards and Technology (NIST) Technical
            guide to information security testing and assessment Special Publication 800-115. Following these
            methodologies coupled with our years of experience in conducting security assessments helps deliver value to
            our clients in testing their people, processes and technologies for defects and flaws.
          </p>

          <p class="mb-4" align="justify">
            We go beyond testing to proffer advisory on how to fix detected flaws and ensure that our simulated
            exploitation isn’t replicated in real life, and also advise on how to boost your enterprise security posture.
          </p>
          <p class="mb-4" align="justify">
            Our approach to a vulnerability assessment and penetration test usually takes the steps below:
          <ul>
            <li>Documentation: Contract and agreement is signed along with other relevant documents.</li>
            <li>Scoping: We agree on what assets should be tested and what shouldn’t be tested explicitly. We also agree
              on what form the test should take e.g. white box, gray box, black box.</li>
            <li>Information gathering: We use open source intelligence and information given in the scope sheet to gain
              useful insight about assets in scope for testing.</li>
            <li>Vulnerability Assessment: We assess the in scope assets for vulnerabilities and take proper note of
              critical exploitable vulnerabilities that can be exploited by a real life attacker.</li>
            <li>Controlled Exploitation: We exploit the confirmed vulnerabilities using the exact same tools a real life
              hacker would. However, proper care is taken to not interrupt business processes.</li>
            <li>Reporting: We produce reports on the exercise detailing what was exploited, how it was exploited and how
              to fix the exploited flaw.</li>
          </ul>




          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/vapt-vulnerability-assessment-and-penetration-testing.png">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="training" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Network Firewalls</h1>
          </div>
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
            <img class="position-absolute w-100 rounded wow zoomIn" data-wow-delay="0.9s"
              src="img/Information-Security-Management-Systems-ISO-27001_.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
