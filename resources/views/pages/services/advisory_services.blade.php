@extends('layouts.app')
@section('title', 'Our Services')
@section('service_active', 'active')
@section('content')
  <div class="container-fluid bg-primary py-5 bg-header" style="margin-bottom: 50px;">
    <div class="row py-5">
      <div class="col-12 pt-lg-5 py-2 mt-lg-5 py-2 text-center">
        <h1 class="display-4 text-white animated zoomIn">Advisory Services</h1>
        {{-- <a href="/" class="h5 text-white">Home</a> --}}
      </div>
    </div>
  </div>

  <!-- Services Start -->
  <div class="container-fluid ">
    <div class="container py-2">
      <div class="row g-5 py-2">
        <div class="col-lg-12 slideInUp">
          <div class="accordion">
            <div class="accordion__header accordion-active">
              <h5>Information
                Security Management System, ISO
                27001</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body accordion-active">
              <div id="info-security-management" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    {{-- <div class="section-title position-relative pb-3 mb-5">
                      <h1 class="mb-0">Information Security Management Systems (ISO 27001)</h1>
                    </div> --}}
                    <p class="mb-4" align="justify">

                      Security breaches and cyberattacks are on the increase and cost organizations several trillions of
                      dollar
                      every year. This has made cybersecurity a top issue for IT, Security, Legal and the board room.
                      The adoption
                      of ISO 27001 in any Organization is a demonstration of top management commitment to the security
                      of critical
                      information assets within such Organization.
                    </p>

                    <p class="mb-4" align="justify">
                      ISO 27001, the international best practice standard for Information Security Management System is
                      the only
                      internationally recognized and trusted information security management standard that can be
                      independently
                      certified to cover People, Process and Technology. The adoption of ISO 27001 also helps
                      organization in
                      demonstrating compliance to legal, regulatory and contractual requirements.
                    </p>

                    <p class="mb-4" align="justify">
                      InfoAssure Limited has a team of certified professionals with in-depth knowledge and understanding
                      of the ISO
                      27001 requirements that will ensure the operationalization of the standards to meet business,
                      legal,
                      contractual and regulatory requirements.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded zoomIn"
                        src="/img/Information-Security-Management-Systems-ISO-27001_.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Business Continuity Management System, ISO
                22301</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="business-continuity-management" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">
                      At InfoAssure Ltd, we take pride in our extensive experience and proven competence in providing
                      Business Continuity Management services, in compliance with ISO 22301 standards. With a track record
                      of successful engagements, with organizations within and outside Nigeria, we are your trusted
                      partner in ensuring the resilience and continuity of your business operations.
                    </p>
                    <h5>Our Experience</h5>
                    <p class="mb-4" align="justify">
                      We have successfully collaborated with organizations, both within and organizations that have
                      expansions outside Nigeria, addressing their unique challenges and helping them achieve ISO 22301
                      certification. Our reach extends across borders, reflecting our ability to adapt to diverse
                      industries and regulatory environments.
                    </p>
                    <h5>Proven Track Record</h5>
                    <p class="mb-4" align="justify">
                      Over the years, we have assisted numerous organizations in implementing ISO 22301, helping them
                      fortify their resilience to disruptions and ensure uninterrupted business operations. Our portfolio
                      includes a diverse range of businesses, from small enterprises to multinational corporations. These
                      successes underscore our capability to tailor Business Continuity Management solutions to meet
                      specific needs, ensuring that organizations of all sizes can navigate disruptions with confidence.
                    </p>
                    <h5>Our Competence</h5>
                    <p class="mb-4" align="justify">
                      Our team, comprises dedicated professionals with deep expertise in Business Continuity Management
                      and ISO 22301 standards. They possess a profound understanding of the intricacies of business
                      continuity planning, risk assessment, and recovery strategies. We stay at the forefront of industry
                      developments to offer you the most up-to-date and effective solutions

                    </p>
                    <h5>Commitment to Ongoing Improvement</h5>
                    <p class="mb-4" align="justify">
                      ISO 22301 is more than just certification; it's a commitment to continuous improvement. We work
                      alongside you, to foster a culture of resilience and preparedness, helping your organization adapt
                      and thrive in an ever-evolving business landscape.

                    </p>
                    <h5>Why Choose Us?</h5>
                    <p class="mb-4" align="justify">
                      When you partner with InfoAssure ltd, you are choosing a trusted ally with a proven record of
                      helping organizations within and outside Nigeria achieve ISO 22301 certification. We are dedicated
                      to your success and will work tirelessly to ensure your business remains resilient in the face of
                      adversity

                    </p>
                    <p class="mb-4" align="justify">
                      <a href="{{ route('contact_us') }}">Contact Us</a> today to learn more about how we can help you
                      safeguard your business operations and
                      ensure your readiness for whatever challenges the future may hold.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded zoomIn"
                        src="/img/Business-Continuity-Management-Systems-ISO-22301.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>IT Service
                Management System, ISO 20000</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="it-service-management" class="detail-class container-fluid py-5 ">
                <div class="row g-5">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">

                      ISO 20000 is the international best practice standard for IT Service Management (ITSM) and ensures
                      that
                      Organization derives value from IT investments, ensure resources are effectively managed and ensures
                      that ITSM
                      processes align with the strategic objective of the business and also align with the requirements of
                      best
                      practice. <br>The adoption of ISO 20000 also ensures that companies can continuously improve the
                      delivery of
                      their IT services.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="position-absolute w-100 rounded zoomIn"
                        src="/img/it-service-management-information-technology.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Payment Card Data Security
                Standard (PCI
                DSS)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="pci-dss" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    {{-- <p class="mb-4" align="justify">

                      The Payment Card Industry Data Security Standard (PCI DSS) is for Organizations that transmit,
                      process or
                      store cardholder data. It is important for customers to know your website is secured. The
                      implementation and
                      adoption of PCI DSS helps to reduce the risk of credit and debit card data loss. <br>A team of
                      Qualified
                      Security Assessor (QSA) will carry out the compliance assessment and a report on compliance is
                      issued on
                      provision of successful evidence.
                    </p> --}}
                    <p class="mb-4" align="justify">
                      PCI DSS certification which is Payment Card Industry Data Security Standard. It is a set of security
                      standards designed to protect cardholder data. The PCI DSS is administered by the Payment Card
                      Industry Security Standards Council (PCI SSC).
                    </p>
                    <p class="mb-4" align="justify">
                      The PCI DSS applies to all organizations that process, store, or transmit payment card data. This
                      includes merchants, payment processors, and service providers.</p>
                    <p class="mb-4" align="justify">
                      The PCI DSS has 12 requirements that organizations must meet in order to be compliant. These
                      requirements cover a wide range of security controls, such as:
                    <ul>
                      <li>Installing and maintaining a firewall</li>
                      <li>Implementing strong access controls</li>
                      <li>Encrypting data in transit and at rest</li>
                      <li>Monitoring for security incidents</li>
                      <li>Conducting regular security assessments</li>
                    </ul>
                    </p>
                    <p class="mb-4" align="justify">
                      Organizations that are required to comply with the PCI DSS must undergo a regular assessment, to
                      verify their compliance. These assessments can be conducted by a Qualified Security Assessor (QSA).
                    </p>
                    <p class="mb-4" align="justify">
                      The PCI DSS is an important security standard for organizations that process, store, or transmit
                      payment card data. By complying with the PCI DSS, organizations can help to protect cardholder data
                      and reduce their risk of data breaches. ROC (Report of compliance), AOC (Attestation of compliance)
                      and SAQ (Self-assessment questionnaire) are all we issue depending on the PCI level of businesses to
                      merchants or service providers after an Assessment by the QSA.
                    </p>

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/certificacao-pci.png">
                    </div>
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/PCI_SSC_Mission_Strategic_Pillars_v2.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>


            <div class="accordion__header">
              <h5>Approved Scanning Vendor (ASV) </h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="dpia" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">

                      It's an organization that has been certified by the Payment Card Industry Security Standards Council
                      (PCI SSC) to conduct external vulnerability scans for organizations that are required to comply with
                      the Payment Card Industry Data Security Standard (PCI DSS).
                    </p>
                    <p class="mb-4" align="justify">

                      ASV scans are required to be conducted at least once every quarter, specifically to identify and
                      report on vulnerabilities that could be exploited by attackers, to gain access to an organization's
                      payment card data. The results of ASV scans are used by organizations to prioritize remediation
                      efforts and to ensure that they are in compliance with the PCI DSS.
                    </p>
                    <p class="mb-4" align="justify">

                      Any organization obligated to adhere to the standard, should contemplate arranging external
                      vulnerability scans through InfoAssure Limited, which maintains a well-established partnership with
                      one of the approved vendors. This will help to protect your payment card data and to demonstrate
                      your compliance with the Regulatory body.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/dpia1.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Data Privacy Impact Assessment
                (DPIA)</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="dpia" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">

                      A Data Privacy Impact Assessment (DPIA), also known as a Privacy Impact Assessment (PIA), is a
                      systematic and
                      comprehensive assessment of the potential risks and impacts on individuals' privacy resulting from
                      the
                      processing of personal data. DPIA is an essential tool for organizations to identify and mitigate
                      privacy
                      risks in their data processing activities, ensuring compliance with data protection laws and
                      regulations.<br>
                      The purpose of a DPIA is to assess the data processing activities from a privacy perspective,
                      understand the
                      potential risks, and take appropriate measures to reduce or eliminate those risks. It allows
                      organizations to
                      be proactive in protecting individuals' personal data and demonstrating accountability in their data
                      processing practices. <br>
                      In essence, a DPIA is a proactive and essential tool for organizations to assess and manage privacy
                      risks
                      associated with their data processing activities. It helps organizations to protect individuals'
                      privacy
                      rights, demonstrate compliance, and build trust with their customers and stakeholders.
                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/dpia1.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Quality Management System ISO
                9001</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="qms" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <h5>Elevating Quality Standards Globally</h5>
                    <p class="mb-4" align="justify">
                      At InfoAssure Ltd, we are dedicated to helping organizations worldwide achieve ISO 9001
                      certification and establish a culture of quality excellence. With a strong global footprint and a
                      demonstrated track record, we are your trusted partner for implementing and optimizing ISO 9001
                      standards.
                    </p>
                    <h5>Our Experience</h5>
                    <p class="mb-4" align="justify">
                      While we have a deep-rooted presence within Nigeria, our reach extends far beyond borders. We have
                      successfully partnered with organizations, spanning diverse industries and regulatory environments.
                      Our experience equips us with a comprehensive understanding of global best practices in quality
                      management.
                    </p>
                    <h5>Proven Success Stories</h5>
                    <p class="mb-4" align="justify">
                      Our portfolio includes a multitude of success stories from organizations that have harnessed the
                      power of ISO 9001 to enhance their operations. These success stories reflect the tangible benefits
                      our clients have realized, from streamlined processes to enhanced customer satisfaction, on a global
                      scale.
                    </p>
                    <h5>Our Competence</h5>
                    <p class="mb-4" align="justify">
                      Global-Quality Experts <br>

                      Our team comprises seasoned experts in quality management and ISO 9001 standards, capable of
                      providing guidance and solutions tailored to international contexts. We stay at the forefront of
                      industry developments to ensure your organization receives cutting-edge advice.


                    </p>
                    <h5>Commitment to Ongoing Improvement</h5>
                    <p class="mb-4" align="justify">
                      ISO 22301 is more than just certification; it's a commitment to continuous improvement. We work
                      alongside you, to foster a culture of resilience and preparedness, helping your organization adapt
                      and thrive in an ever-evolving business landscape.

                    </p>
                    <h5>Why Choose InfoAssure Ltd?</h5>
                    <p class="mb-4" align="justify">
                      When you choose InfoAssure Ltd, you're selecting a partner deeply rooted in Nigeria, with a proven
                      track record of excellence in ISO 9001 Quality Management System implementation. Our experience,
                      coupled with our commitment to competence and customization, positions us as the ideal choice to
                      elevate your organization's quality standards in Nigeria and beyond.

                    </p>
                    <p class="mb-4" align="justify">
                      <a href="{{ route('contact_us') }}">Contact Us</a> today to embark on your journey to ISO 9001
                      certification and quality leadership, whether
                      you're based in Nigeria or abroad.

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/qms.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>


            <div class="accordion__header">
              <h5>Health and Safety Management
                System, ISO
                45001</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="hsm" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">
                      ISO 45001 is an international standard that sets the requirements for an Occupational Health and
                      Safety
                      Management System (OH&SMS). The standard provides a framework for organizations to identify and
                      control health
                      and safety risks, minimize the potential for accidents and injuries, and ensure compliance with
                      health and
                      safety regulations. ISO 45001 is designed to help organizations create a safe and healthy working
                      environment
                      for employees and other stakeholders.<br>
                      ISO 45001 is a powerful tool for organizations to systematically manage occupational health and
                      safety risks,
                      protect their workforce, and create a safe and healthy work environment.

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/hsm.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>Privacy Information
                Management
                System (ISO 27701) - PIMS</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="pims" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">
                      ISO 27701 is an international standard that provides guidelines for implementing, maintaining, and
                      continually
                      improving a Privacy Information Management System (PIMS) as an extension to ISO 27001, which is a
                      well-known
                      standard for information security management. PIMS focuses specifically on privacy management that
                      aligns with
                      the principles of the General Data Protection Regulation (GDPR) and other data protection
                      regulations
                      including the Nigeria Data Protection Act (NDPA). It addresses the protection of privacy as
                      potentially
                      affected by the processing of PII while giving organizations a competitive edge in the global market
                      with
                      increased customer satisfaction.

                    </p>
                    <p class="mb-4" align="justify">
                      InfoAssure Limited has competent and experienced ISO 27701 experts with a track record of supporting
                      organizations to gain international recognition and attain compliance with appropriate controls
                      against data
                      breaches through the diligent adoption of this global best practice standard.

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/hsm.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>

            <div class="accordion__header">
              <h5>NDPA - Nigeria Data
                Protection
                Act</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="ndpa" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">
                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">
                      The Nigeria Data Protection Act (NDPA) is a legal framework for the protection of personal
                      information
                      established by NDPC for the regulation of the processing of personally identifiable information
                      (PII) of data
                      subjects and related matters. All organizations that collect, store, and process personal data have
                      been
                      mandated to safeguard this PII in accordance to the NDPA requirements.

                    </p>
                    <p class="mb-4" align="justify">
                      InfoAssure Limited is a licensed Data Protection Compliance Organization (DPCO) in active
                      partnership with the
                      Nigeria Data Protection Commission. Our seasoned data privacy and protection consultants have
                      enabled several
                      organizations to implement robust technical and organizational security measures, data audit filing,
                      data
                      protection impact assessment, and privacy by design to ensure the data privacy and protection of
                      Nigerian
                      citizens.

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/hsm.jpg">
                    </div>
                  </div> --}}
                </div>
              </div>
            </div>


            <div class="accordion__header">
              <h5>General Data Protection
                Regulations - GDPR</h5>
              <span class="accordion__toggle"></span>
            </div>
            <div class="accordion__body">
              <div id="gdpr" class="detail-class container-fluid py-5 ">
                <div class="row g-5 py-2">


                  <div class="col-lg-12">
                    <p class="mb-4" align="justify">
                      The General Data Protection Regulation (GDPR) is a comprehensive data privacy and protection
                      regulation that
                      played a significant role in inspiring the creation of similar data protection regulations in other
                      parts of
                      the world. GDPR basically is specific to the collection, processing, and transfer of Personal
                      Identifiable
                      Information (PII) of European citizens and non-citizens based in the EU. Its primary purpose is to
                      provide
                      individuals with greater control over their personal data, ensure the security of PII, and harmonize
                      data
                      protection laws across the EU member states.

                    </p>
                    <p class="mb-4" align="justify">
                      At InfoAssure Limited, we have a team of highly skilled Certified Data Protection Officers (CDPO)
                      who have the
                      expertise knowledge, and wealth of experience in implementing the requirements of the GDPR (173
                      Recitals and
                      99 Articles) across organizations who are data controllers or processors.

                    </p>
                  </div>
                  {{-- <div class="col-lg-5 py-2" style="min-height: 500px">
                    <div class="position-relative">
                      <img class="w-100  rounded zoomIn" src="/img/hsm.jpg">
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
  <!-- Services End -->
  <!--Advisory Services Details-->









  <!--Advisory Services Details ends-->


@endsection
