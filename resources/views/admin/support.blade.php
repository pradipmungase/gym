@extends('admin.layout.adminApp')
@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <h1 class="page-header-title">Hello, <span class="user_name">{{ Auth::user()->owner_name }}</span>
                        </h1>
                        <p class="page-header-text">Need help? Our support team is here for you 24/7.</p>
                    </div>
                </div>
            </div>

            <!-- Support Info Cards -->
            <div class="row g-4">
                <!-- WhatsApp Support -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-whatsapp fs-1 text-success mb-3"></i>
                            <h5 class="card-title">WhatsApp Support</h5>
                            <p class="card-text">Chat with us anytime on WhatsApp.</p>
                            <a href="https://wa.me/1234567890" target="_blank" class="btn btn-outline-success">Chat Now</a>
                        </div>
                    </div>
                </div>

                <!-- Phone Support -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-telephone-fill fs-1 text-primary mb-3"></i>
                            <h5 class="card-title">Call Support</h5>
                            <p class="card-text">Speak to our experts directly.</p>
                            <a href="tel:+1234567890" class="btn btn-outline-primary">Call Us</a>
                        </div>
                    </div>
                </div>

                <!-- Email Support -->
                <div class="col-md-4">
                    <div class="card border-0 shadow h-100 text-center">
                        <div class="card-body">
                            <i class="bi bi-envelope-fill fs-1 text-danger mb-3"></i>
                            <h5 class="card-title">Email Support</h5>
                            <p class="card-text">Drop us a line and we’ll get back soon.</p>
                            <a href="mailto:support@example.com" class="btn btn-outline-danger">Email Us</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section (Optional) -->
            <div class="row mt-5">
                <div class="col">
                    <h4>Frequently Asked Questions</h4>
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq1">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse1" aria-expanded="true">
                                    How do I reset my password?
                                </button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Go to the login page and click "Forgot Password" to reset it using your email.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faq2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse2">
                                    How can I update my profile details?
                                </button>
                            </h2>
                            <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Go to your profile settings and click "Edit" to update your information.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- End Content -->
    </main>
@endsection
