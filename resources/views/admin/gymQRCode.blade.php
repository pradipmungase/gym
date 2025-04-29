@extends('admin.layout.adminApp')
@section('content')
<main id="content" role="main" class="main">
<div class="content container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card card-hover-shadow text-center border-0 shadow-lg rounded-4">
        
        <!-- Card Header -->
        <div class="card-header bg-success text-white rounded-top-4">
          <h2 class="h4 mb-0">Connect to {{ auth()->user()->gym_name }}</h2>
        </div>

        <!-- Card Body -->
        <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
          
          <!-- Instruction Text -->
          <p class="text-muted mb-3">
            📄 <strong>Print this QR code</strong> and attach it on your gym wall.<br>
            📲 Members can simply scan the QR code to register themselves.<br>
            ✅ You can then review their registration and <strong>Approve</strong> or <strong>Reject</strong>.
          </p>

          <!-- QR Code -->
          <div class="mb-4">
            <img src="{{ auth()->user()->qr_code }}" alt="QR Code" class="img-fluid rounded shadow" style="max-width: 250px;">
          </div>

          <!-- Action Buttons -->
          <div class="d-flex gap-3 mb-4">
            <a href="{{ auth()->user()->qr_code }}" download class="btn btn-primary">
              <i class="bi bi-download me-1"></i> Download QR
            </a>
            <button type="button" class="btn btn-success" onclick="shareQRCode()">
              <i class="bi bi-share me-1"></i> Share QR
            </button>
            <a href="{{ route('memberRegistration', encrypt(auth()->user()->id)) }}" target="_blank" class="btn btn-info">
              <i class="bi bi-link-45deg me-1"></i> Open Link
            </a>

          </div>

          <!-- Reminder Text -->
          <div class="alert alert-soft-primary small" role="alert">
            🖨️ Don't forget to print and place it somewhere visible!
          </div>

        </div>
        <!-- End Card Body -->

      </div>
    </div>
  </div>
</div>

<script>
function shareQRCode() {
  if (navigator.share) {
    navigator.share({
      title: 'Join {{ auth()->user()->gym_name }}',
      text: 'Scan this QR code to register as a member!',
      url: '{{ auth()->user()->qr_code }}'
    }).catch(console.error);
  } else {
    alert('Sharing is not supported on this browser.');
  }
}
</script>

</main>

@endsection
