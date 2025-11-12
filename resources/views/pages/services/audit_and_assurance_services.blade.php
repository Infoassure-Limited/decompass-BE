@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 mt-lg-5 text-center">
        <h1 class="display-4 text-white animated zoomIn">Audit & Assurance Services</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
        {{-- <i class="far fa-circle text-white px-2"></i>
        <a href="services" class="h5 text-white">Services</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  <div class="container-fluid wow fadeInUp">
    <div class="container py-2">
      <div class="row g-5 py-2">
        <div class="col-lg-12 wow slideInUp">
          <div class="accordion">
            <div class="accordion__header accordion-active">
              <h5>Information
                Systems Audit</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body accordion-active">
              <div id="information-system-audit" class="detail-class container-fluid py-5 wow fadeInUp">
                <div>
                  <div class="row g-5">
                    <div class="col-lg-7">
                      <p class="mb-4" align="justify">

                        Information Systems (IS) Audit or Information Technology (IT) Audit aims at evaluating the
                        effectiveness of an
                        information system’s control and to establish that the integrity of processing, stored or transfer
                        of data is
                        maintained, information systems are safeguarding corporate assets and operating effectively to
                        achieve the
                        organization’s goals or objectives.
                      </p>

                      <p class="mb-4" align="justify">
                        In carrying out an effective information systems or technology audit, we adopt a risk based IT
                        audit approach.
                        This provide reasonable assurance that risk management processes are managing IT risks effectively
                        in relation
                        to the organization’s risk appetite.
                      </p>

                      <p class="mb-4" align="justify">
                        Some of the IT risk factors we consider before carrying out an audit include: the criticality of
                        information
                        premised on the information security principle – Confidentiality, Integrity and Availability, and
                        also the
                        Materiality, Reputational fallout, Strategic plan support, Fraud, Changes in the IT environment
                        and Outsourced
                        Risks.
                      </p>
                      <p class="mb-4" align="justify">
                        Our team of Auditors have over a decade experience, helping organizations in the development of
                        internal audit
                        programs to evaluate information systems effectiveness and readiness per standard requirements. We
                        also
                        perform reviews of application systems, Operating Systems, Databases, Networks and
                        Infrastructures. Other
                        responsibilities includes identifying technology related risks and testing associated general IT
                        controls, to
                        ascertain controls are operating effectively. We have performed reviews on Organization’s IT
                        platforms,
                        Business Continuity, Change Management, Cyber Risk and IT Governance.
                      </p>
                    </div>
                    <div class="col-lg-5" style="min-height: 500px">
                      <div class="position-relative">
                        <img class="position-absolute w-100 rounded wow zoomIn"
                          src="/img/information-system-is-audit.jpg">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Revenue
                Assurance</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="revenue-assurance" class="detail-class container-fluid py-5 wow fadeInUp">
                <div>
                  <div class="row g-5">
                    <div class="col-lg-7">
                      <p class="mb-4" align="justify">

                        Revenue Assurance is a crucial process in telecommunications, finance, and other industries where
                        companies
                        generate revenue from their products or services. It involves the systematic identification,
                        prevention, and
                        recovery of potential revenue leakages and losses to ensure that the company's revenue stream is
                        maximized and
                        protected. The goal of Revenue Assurance is to safeguard the company's financial health and
                        maintain
                        profitability by reducing revenue leakage and optimizing revenue collection processes.
                      </p>

                      <p class="mb-4" align="justify">
                        Revenue Assurance is a critical process that ensures companies optimize their revenue streams,
                        reduce revenue
                        leakages, and enhance overall business performance. It involves a comprehensive approach that
                        spans multiple
                        departments and relies on accurate data and robust processes to safeguard a company's financial
                        health.
                      </p>
                    </div>
                    <div class="col-lg-5" style="min-height: 500px">
                      <div class="position-relative">
                        <img class="position-absolute w-100 rounded wow zoomIn" src="/img/revenue-assurance.jpg">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Financial
                Statement Audit</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="financial-statement" class="detail-class container-fluid py-5 wow fadeInUp">
                <div>
                  <div class="row g-5">
                    <div class="col-lg-7">
                      <p class="mb-4" align="justify">

                        A Financial Statement Audit is an independent examination of an organization's financial
                        statements by a
                        qualified external auditor. The primary objective of a financial statement audit is to provide
                        reasonable
                        assurance that the financial statements present a true and fair view of the company's financial
                        position,
                        performance, and cash flows in accordance with the applicable financial reporting framework (such
                        as Generally
                        Accepted Accounting Principles - GAAP or International Financial Reporting Standards - IFRS).
                      </p>

                      <p class="mb-4" align="justify">
                        Financial Statement Audit is a thorough and independent examination of an organization's financial
                        statements
                        to ensure they present a true and fair view of its financial performance and position. The audit
                        process
                        involves gathering evidence, evaluating internal controls, and issuing an audit report with the
                        auditor's
                        opinion on the financial statements' reliability.
                      </p>
                    </div>
                    <div class="col-lg-5" style="min-height: 500px">
                      <div class="position-relative">
                        <img class="position-absolute w-100 rounded wow zoomIn" src="/img/financial-statement.jpg">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Regulatory
                Compliance and Reporting</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="regulatory-compliance" class="detail-class container-fluid py-5 wow fadeInUp">
                <div>
                  <div class="row g-5">
                    <div class="col-lg-7">
                      <p class="mb-4" align="justify">
                        Regulatory Compliance and Reporting refer to the process of adhering to the laws, regulations, and
                        standards
                        that govern an organization's industry or operations and providing accurate and timely information
                        to
                        regulatory authorities. This is essential for ensuring that companies operate within legal
                        boundaries, meet
                        industry standards, and maintain transparency and accountability.


                      </p>
                      <p class="mb-4" align="justify">
                        Regulatory Compliance and Reporting are crucial aspects of business operations, ensuring that
                        organizations
                        operate within legal and ethical boundaries and meet their reporting obligations to relevant
                        authorities. By
                        adhering to regulatory requirements and maintaining transparent and accurate reporting, companies
                        can build
                        trust with stakeholders and demonstrate their commitment to responsible business practices.
                      </p>
                    </div>
                    <div class="col-lg-5" style="min-height: 500px">
                      <div class="position-relative">
                        <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/regulatory-compliance.jpg">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Management Systems
                Certification Audit</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="management-system" class="detail-class container-fluid py-5 wow fadeInUp">
                <div>
                  <div class="row g-5">
                    <div class="col-lg-7">
                      <p class="mb-4" align="justify">
                        A Management Systems Certification Audit, also known as a Certification Audit or Third-Party
                        Audit, is an
                        independent assessment conducted by an accredited certification body to evaluate an organization's
                        management
                        system's compliance with specific international standards. The purpose of this audit is to
                        determine whether
                        the organization's management system conforms to the requirements of the chosen standard(s) and if
                        it is
                        effectively implemented and maintained. The certification process provides external validation of
                        the
                        organization's commitment to meeting international best practices and standards.

                      </p>
                      <p class="mb-4" align="justify">
                        The Management Systems Certification Audit provides numerous benefits, including increased
                        credibility and
                        market reputation, enhanced customer confidence, compliance with industry requirements, and
                        continual
                        improvement of business processes. It demonstrates the organization's commitment to quality,
                        safety,
                        environmental responsibility, information security, or other specific aspects covered by the
                        chosen management
                        system standard.
                      </p>
                    </div>
                    <div class="col-lg-5" style="min-height: 500px">
                      <div class="position-relative">
                        <img class="w-100  rounded wow zoomIn" data-wow-delay="0.3s" src="/img/certification-audit.jpg">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Audit & Assurance Services-->
@endsection
