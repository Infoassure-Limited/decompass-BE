<div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s" style="background: rgba(192, 192, 192, 0.61)">
  <div class="container py-5">
    <div class="text-center position-relative pb-3 mb-5 mx-auto">
      <h1 class="mb-0">Subscribe to our insightful resources</h1>
      <p>Let's send latest news and cyber-security trends right to your inbox</p>
    </div>
    @if (session('status'))
      <div class="alert alert-success">
        {{ session('status') }}
      </div>
    @endif
    <div class="row">
      <div class="col-lg-12">
        <form method="POST" action="{{ route('submit_subscription_form') }}">
          @csrf
          <div class="row g-3">
            <div class="col-md-9">
              <input type="email" class="form-control border-2 bg-white px-4" placeholder="Enter your email address"
                style="height: 55px;" name="email" required>
            </div>
            <div class="col-md-3">
              <button class="btn btn-success btn-round w-100 py-3" type="submit">Keep Me Updated</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>
