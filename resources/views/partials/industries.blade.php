<div class="py-5" style="background-color: #DBE2EF;">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #112D4E;">Industri yang Kami Layani</h2>
        <p class="text-muted lead">Digunakan oleh para profesional dari berbagai industri</p>
      </div>
      <div class="row text-center g-4">
        @foreach ([
          ['icon' => 'droplet', 'title' => 'Oil & Gas'],
          ['icon' => 'cpu', 'title' => 'Manufacturing'],
          ['icon' => 'truck-front', 'title' => 'Automotive'],
          ['icon' => 'prescription', 'title' => 'Pharmaceutical']
        ] as $ind)
        <div class="col-6 col-md-3">
          <div class="card border-0 shadow-sm h-100" style="background-color: #FFFFFF;">
            <div class="card-body p-4">
              <div class="mb-3" style="color: #3F72AF;"><i class="bi bi-{{ $ind['icon'] }} display-6"></i></div>
              <h6 class="fw-bold" style="color: #112D4E;">{{ $ind['title'] }}</h6>
              <p class="text-muted small">Solusi penyegelan terpercaya untuk sektor {{ strtolower($ind['title']) }}.</p>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
