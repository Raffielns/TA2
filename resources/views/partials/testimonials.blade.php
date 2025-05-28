<div class="py-5" style="background-color: #F9F7F7;">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: #112D4E;">Testimoni Pelanggan</h2>
        <p class="text-muted lead">Dipercaya oleh pelaku industri dari berbagai bidang</p>
      </div>
      <div class="row g-4">
        @foreach ([
          ['name' => 'Michael Johnson', 'role' => 'Maintenance Manager', 'company' => 'Industrial Solutions Inc.', 'text' => 'Excellent quality and performance in high-pressure applications.'],
          ['name' => 'Sarah Williams', 'role' => 'Procurement Specialist', 'company' => 'AutoTech Manufacturing', 'text' => 'Consistent quality and reliable delivery every time.'],
          ['name' => 'David Chen', 'role' => 'Engineering Director', 'company' => 'PetroChem Solutions', 'text' => 'The seals have exceeded our expectations.']
        ] as $testi)
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100" style="background-color: #FFFFFF;">
            <div class="card-body p-4">
              <p class="card-text">"{{ $testi['text'] }}"</p>
              <div class="mt-3">
                <h6 class="mb-0" style="color: #112D4E;">{{ $testi['name'] }}</h6>
                <small class="text-muted">{{ $testi['role'] }}, {{ $testi['company'] }}</small>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </div>
