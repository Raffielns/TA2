@extends('layouts.app')
@section('content')

<!-- Hero Section -->
<section class="position-relative text-white overflow-hidden" style="min-height: 550px;">
  <img src="{{ asset('img/bg-aboutus.png') }}" alt="Background About-Us"
    class="position-absolute top-0 start-0 w-100 h-100" style="object-fit: cover; z-index: 0;">
  <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, 0.6); z-index: 1;"></div>
  <div class="container text-center position-relative py-5" style="z-index: 2;">
    <h1 class="display-2 fw-bold mb-3">Tentang Kami</h1>
    <p class="lead fs-4">CV. Anugerah Sukses Sejahtera</p>
  </div>
</section>

<!-- Profile Section -->
<section class="py-5" style="background-color: #F9F7F7;">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6 mb-4">
        <h3 class="fw-bold" style="color: #112D4E;">Profil Perusahaan</h3>
        <p class="text-muted">CV. Anugerah Sukses Sejahtera adalah perusahaan terpercaya di bidang industri seal sejak tahun 2012, beralamat di <strong>Ketintang Barat III No.182, Surabaya, 60231</strong>. Kami berkomitmen menyediakan produk berkualitas tinggi dan layanan terbaik untuk kebutuhan industri di Indonesia.</p>
      </div>
      <div class="col-lg-6">
        <div class="ratio ratio-16x9 shadow rounded">
          <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15815.574957279482!2d112.726029!3d-7.311508!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTgnNDAuMiJTIDExMsKwNDMnMzMuNyJF!5e0!3m2!1sen!2sid!4v1715757067032!5m2!1sen!2sid" width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <small class="text-muted d-block text-center mt-2">Klik ikon pada peta untuk navigasi</small>
      </div>
    </div>
  </div>
</section>

<!-- Visi & Misi -->
<section class="py-5" style="background-color: #DBE2EF;">
  <div class="container">
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="bg-white rounded p-4 shadow h-100">
          <h4 class="fw-semibold" style="color: #3F72AF;">Visi</h4>
          <p class="text-muted">Menjadi perusahaan seal terpercaya dan inovatif yang memberikan nilai tambah berkelanjutan bagi pelanggan industri di Indonesia.</p>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="bg-white rounded p-4 shadow h-100">
          <h4 class="fw-semibold" style="color: #3F72AF;">Misi</h4>
          <ul class="text-muted">
            <li>Menyediakan produk berkualitas dengan harga kompetitif.</li>
            <li>Memberikan layanan pelanggan yang cepat dan solutif.</li>
            <li>Menjalin kemitraan jangka panjang dengan pelanggan dan supplier.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Spesifikasi Material -->
<section class="py-5" style="background-color: #F9F7F7;">
    <div class="container">
      <h3 class="fw-bold text-center mb-4" style="color: #112D4E;">Spesifikasi Material</h3>
      <div class="table-responsive shadow rounded">
        <table class="table table-bordered table-striped align-middle text-center">
          <thead class="table-primary">
            <tr>
              <th>Material</th>
              <th>Temp. Kerja</th>
              <th>Pemakaian Utama</th>
              <th>Tekanan (BAR)</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-danger fw-bold">PU Red</td>
              <td>-30 TO 105 °C</td>
              <td>Mineral oil, tekanan udara, penahan cairan untuk hidrolis</td>
              <td>400</td>
            </tr>
            <tr>
              <td class="text-warning fw-bold">PU Yellow</td>
              <td>-30 TO 105 °C</td>
              <td>Mineral oil, tekanan udara, penahan cairan untuk hidrolis</td>
              <td>400</td>
            </tr>
            <tr>
              <td>NBR</td>
              <td>-25 TO 100 °C</td>
              <td>Mineral oil, tekanan udara, tekanan air</td>
              <td>160</td>
            </tr>
            <tr>
              <td>EPDM</td>
              <td>-50 TO 130 °C</td>
              <td>Seal untuk air panas, asam & alkaline. Tidak tahan terhadap Mineral Oil</td>
              <td>160</td>
            </tr>
            <tr>
              <td>SILICONE</td>
              <td>-55 TO 210 °C</td>
              <td>Flange seals, Gasket & static seal. Tidak untuk aplikasi dinamik</td>
              <td>160</td>
            </tr>
            <tr>
              <td>POM White</td>
              <td>-60 TO 100 °C</td>
              <td>Guide Ring, untuk part bermesin</td>
              <td>-</td>
            </tr>
            <tr>
              <td>PA</td>
              <td>-30 TO 105 °C</td>
              <td>Back up, untuk part bermesin</td>
              <td>-</td>
            </tr>
            <tr>
              <td class="fw-bold">PTFE Grey</td>
              <td>-200 TO 260 °C</td>
              <td>Seal komposit elastomer. Putaran tinggi</td>
              <td>400</td>
            </tr>
            <tr>
              <td class="fw-bold">PTFE White</td>
              <td>-200 TO 260 °C</td>
              <td>Gesekan kecil, industri makanan, Guide Ring</td>
              <td>400</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Viton</td>
              <td>-28 TO 204 °C</td>
              <td>Seal fluoro carbon untuk BBM & suhu tinggi</td>
              <td>-</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-4">
        <p class="text-muted">
          Daftar material di atas adalah contoh material yang sering kami gunakan. Kami juga menyediakan material lain sesuai dengan spesifikasi teknis mesin Anda.
          Pemilihan material sangat berpengaruh terhadap performa dan umur seal. Silakan konsultasikan dengan kami untuk pilihan material terbaik sesuai kebutuhan.
        </p>
      </div>
    </div>
  </section>


@endsection
