@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Advisory Services</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  <div class="container-fluid wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-2">
      <div class="row g-5">
        <div class="col-lg-12 wow slideInUp" data-wow-delay="0.3s">
          <div class="bg-light rounded">
            <div class="border-bottom py-4 px-5 mb-4">
              <h4 class="mb-3">We offer the following Advisory Services</h4>
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
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#pims"><i class="fa fa-arrow-right text-primary pt-1"></i> Privacy Information Management
                    System (ISO 27701) - PIMS</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#ndpa"><i class="fa fa-arrow-right text-primary pt-1"></i> NDPA - Nigeria Data Protection
                    Act</a></span>
              </div>
              <div class="d-flex justify-content-between mb-3">
                <span><a href="#gdpr"><i class="fa fa-arrow-right text-primary pt-1"></i> General Data Protection
                    Regulations - GDPR</a></span>
              </div>
            </div>
          </div>
        </div>
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
              src="/img/Information-Security-Management-Systems-ISO-27001_.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="business-continuity-management" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
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
              src="/img/Business-Continuity-Management-Systems-ISO-22301.jpg">
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
              src="/img/it-service-management-information-technology.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/certificacao-pci.png">
          </div>
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s"
              src="/img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/dpia1.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/qms.jpg">
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
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/hsm.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="pims" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">

        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/hsm.jpg">
          </div>
        </div>
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">Privacy Information Management System (ISO 27701) - PIMS</h1>
          </div>
          <p class="mb-4" align="justify">
            ISO 27701 is an international standard that provides guidelines for implementing, maintaining, and continually
            improving a Privacy Information Management System (PIMS) as an extension to ISO 27001, which is a well-known
            standard for information security management. PIMS focuses specifically on privacy management that aligns with
            the principles of the General Data Protection Regulation (GDPR) and other data protection regulations
            including the Nigeria Data Protection Act (NDPA). It addresses the protection of privacy as potentially
            affected by the processing of PII while giving organizations a competitive edge in the global market with
            increased customer satisfaction.

          </p>
          <p class="mb-4" align="justify">
            InfoAssure Limited has competent and experienced ISO 27701 experts with a track record of supporting
            organizations to gain international recognition and attain compliance with appropriate controls against data
            breaches through the diligent adoption of this global best practice standard.

          </p>
        </div>
      </div>
    </div>
  </div>
  <div id="ndpa" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">NDPA - Nigeria Data Protection Act</h1>
          </div>
          <p class="mb-4" align="justify">
            The Nigeria Data Protection Act (NDPA) is a legal framework for the protection of personal information
            established by NDPC for the regulation of the processing of personally identifiable information (PII) of data
            subjects and related matters. All organizations that collect, store, and process personal data have been
            mandated to safeguard this PII in accordance to the NDPA requirements.

          </p>
          <p class="mb-4" align="justify">
            InfoAssure Limited is a licensed Data Protection Compliance Organization (DPCO) in active partnership with the
            Nigeria Data Protection Commission. Our seasoned data privacy and protection consultants have enabled several
            organizations to implement robust technical and organizational security measures, data audit filing, data
            protection impact assessment, and privacy by design to ensure the data privacy and protection of Nigerian
            citizens.

          </p>
        </div>
        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/hsm.jpg">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="gdpr" class="detail-class container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-5">
      <div class="row g-5">

        <div class="col-lg-5" style="min-height: 300px">
          <div class="position-relative">
            <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/hsm.jpg">
          </div>
        </div>
        <div class="col-lg-7">
          <div class="section-title position-relative pb-3 mb-5">
            <h1 class="mb-0">General Data Protection Regulations - GDPR</h1>
          </div>
          <p class="mb-4" align="justify">
            The General Data Protection Regulation (GDPR) is a comprehensive data privacy and protection regulation that
            played a significant role in inspiring the creation of similar data protection regulations in other parts of
            the world. GDPR basically is specific to the collection, processing, and transfer of Personal Identifiable
            Information (PII) of European citizens and non-citizens based in the EU. Its primary purpose is to provide
            individuals with greater control over their personal data, ensure the security of PII, and harmonize data
            protection laws across the EU member states.

          </p>
          <p class="mb-4" align="justify">
            At InfoAssure Limited, we have a team of highly skilled Certified Data Protection Officers (CDPO) who have the
            expertise knowledge, and wealth of experience in implementing the requirements of the GDPR (173 Recitals and
            99 Articles) across organizations who are data controllers or processors.

          </p>
        </div>
      </div>
    </div>
  </div>
  <!--Advisory Services Details ends-->


@endsection
