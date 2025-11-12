@extends('layouts.app')
@section('title', 'Features')
@section('feature_active', 'active')
@section('content')
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="display-3 mb-2 wow fadeInDown">Features</h1>
        </div>
    </div>
    <div class="container-fluid service py-5">
        <div class="container py-5">
            <!-- Value proposition -->
            @include('pages.partials.value_proposition')
            <!-- Value proposition -->
            <div class="row g-5 justify-content-center">

                <div class="col-md-6 col-lg-4 col-xl-4 wow fadeInUp">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><i
                                class="fas fa-bar-chart fa-5x text-secondary"></i></div>
                        <div class="service-content">
                            <h4 class="mb-4">Customizable Reporting</h4>
                            <p class="mb-4">Customizable reporting capabilities to generate compliance reports tailored to
                                the needs of
                                various stakeholders, including executives, auditors, and regulatory bodies
                            </p>
                            <!-- <a href="#" class="btn btn-light rounded-pill text-primary py-2 px-4">Read More</a> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4 wow fadeInUp">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><i
                                class="fa fa-handshake fa-5x text-secondary"></i></div>
                        <div class="service-content">
                            <h4 class="mb-4">Consulting and Advisory Services</h4>
                            <p class="mb-4">Optional consulting and advisory services to provide expert guidance on
                                compliance
                                strategies, implementation, and remediation efforts
                            </p>
                            <!-- <a href="#" class="btn btn-light rounded-pill text-primary py-2 px-4">Read More</a> -->
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl-4 wow fadeInUp">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><i
                                class="fas fa-bell fa-5x text-secondary"></i></div>
                        <div class="service-content">
                            <h4 class="mb-4">Alerts and Notifications</h4>
                            <p class="mb-4">Automated alerts and notifications to keep stakeholders informed about
                                compliance deadlines, upcoming audits, and important regulatory updates.</p>
                            <!-- <a href="#" class="btn btn-light rounded-pill text-primary py-2 px-4">Read More</a> -->
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp">
                    <div class="service-item text-center rounded p-4">
                        <div class="service-icon d-inline-block bg-light rounded p-4 mb-4"><i
                                class="fas fa-thumbs-up fa-5x text-secondary"></i></div>
                        <div class="service-content">
                            <h4 class="mb-4">Training and Education</h4>
                            <p class="mb-4">Integrated risk management functionalities to identify, assess, and mitigate
                                risks
                                associated with compliance requirements
                            </p>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div class="container-fluid py-5 wow fadeInUp">
        <div class="container py-2">
            <div class="row g-4 mb-4">
                <div class="col-lg-6 wow fadeInUp">
                    <img src="img/features.jpg" class="img-fluid w-100" alt="">
                </div>
                <div class="col-lg-6 wow fadeInUp">
                    <h4 class="mb-4">Frameworks/Services Overview</h4>
                    <div>
                        <ul>
                            <li>
                                <strong>PCI-DSS (Payment Card Industry Data Security Standard):<br></strong>
                                PCI-DSS is a set of security standards designed to ensure that companies that accept,
                                process, store, or transmit credit card information maintain a secure environment.
                                Compliance with PCI-DSS is essential for safeguarding cardholder data and preventing fraud
                            </li>
                            <li>
                                <strong>ISO Standards (ISO 27001, ISO 22301, etc.):<br></strong>
                                ISO standards provide internationally recognized frameworks for information security
                                management (ISO 27001) and business continuity management (ISO 22301). Compliance with ISO
                                standards demonstrates an organization’s commitment to implementing robust security measures
                                and maintaining continuity in the face of disruptions.
                            </li>
                            <li>
                                <strong>NDPA (Nigerian Data Protection Act):<br></strong>
                                ISO standards provide internationally recognized frameworks for information security
                                management (ISO 27001) and business continuity management (ISO 22301). Compliance with ISO
                                standards demonstrates an organization’s commitment to implementing robust security measures
                                and maintaining continuity in the face of disruptions.
                            </li>
                            <li>
                                <strong>Cyber Intelligence Center:<br></strong>
                                A Cyber Intelligence Center is a dedicated facility or team responsible for gathering,
                                analyzing, and disseminating
                                intelligence related to cybersecurity threats and vulnerabilities. It serves as a
                                centralized hub for monitoring,
                                detecting, and responding to cyber threats in real-time.
                            </li>

                        </ul>

                    </div>
                </div>
                <div class="col-lg-12 wow fadeInUp">
                    <p class="mb-4">Each framework or service offered by “Decompass” has a dedicated page providing
                        in-depth explanations, benefits, report templates, and case studies (if available). These pages
                        serve as comprehensive resources for organizations seeking to understand the requirements and
                        benefits of compliance with specific frameworks or services.</p>
                    <p class="mb-4">
                    <h4>Integration Options</h4>

                    Our services are designed to be flexible and adaptable to the unique needs of each organization.
                    Integration options are available to combine multiple services for comprehensive cybersecurity
                    solutions. Whether you need to integrate PCI-DSS compliance with ISO standards or incorporate cyber
                    threat intelligence into your existing security infrastructure, “Decompass” offers seamless
                    integration options to enhance your cybersecurity posture. Contact us to discuss how we can tailor
                    our services to meet your specific requirements and objectives.</p>

                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- About End -->
@endsection