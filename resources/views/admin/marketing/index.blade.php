<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bootstrap Dark Mode Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
  <div class="container mt-5">
    <h4 class="mb-4">Enter Details</h4>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error')) 
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('marketing.index') }}" method="post">
      @csrf
      <div class="mb-3">
        <label for="whatsappNumber" class="form-label">WhatsApp Number</label>
        <input type="text" value="7028143227" required class="form-control bg-dark text-white border-secondary" id="whatsappNumber" name="whatsappNumber" placeholder="Enter WhatsApp number">
      </div>
      <div class="mb-3">
        <label for="scoreCode" class="form-label">Score Code</label>
        <input type="text" required class="form-control bg-dark text-white border-secondary" id="scoreCode" name="scoreCode" placeholder="Enter Score Code">
      </div>
      <button type="submit" class="btn btn-light">Submit</button>
    </form>
  </div>
</body>
</html>
