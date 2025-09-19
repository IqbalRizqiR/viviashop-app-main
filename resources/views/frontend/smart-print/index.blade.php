@extends('frontend.layouts')

@section('content')
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="text-center mx-auto mb-5" style="max-width: 700px;">
            <h1 class="display-4">Smart Print Service</h1>
            <p>Layanan cetak cerdas untuk kebutuhan dokumen Anda. Mudah, cepat, dan berkualitas tinggi.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-qrcode fa-3x text-primary"></i>
                        </div>
                        <h5 class="card-title">Scan QR Code</h5>
                        <p class="card-text">Dapatkan QR code dari mesin cetak atau minta dari admin untuk memulai proses cetak.</p>
                        <div class="mt-auto">
                            <button class="btn btn-primary" onclick="generateSession()">
                                <i class="fas fa-plus me-2"></i>Generate Session Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-upload fa-3x text-success"></i>
                        </div>
                        <h5 class="card-title">Upload Dokumen</h5>
                        <p class="card-text">Upload file PDF, DOC, atau gambar yang ingin dicetak dengan mudah dan aman.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="fas fa-print fa-3x text-info"></i>
                        </div>
                        <h5 class="card-title">Cetak Langsung</h5>
                        <p class="card-text">Pilih jenis kertas, warna, dan opsi cetak lainnya. Bayar dan cetak langsung di toko.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Cara Menggunakan Smart Print</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-4">
                                <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>1</strong>
                                </div>
                                <h6>Generate Session</h6>
                                <p class="small text-muted">Buat session baru atau scan QR code dari mesin cetak</p>
                            </div>
                            <div class="col-md-3 text-center mb-4">
                                <div class="step-number bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>2</strong>
                                </div>
                                <h6>Upload File</h6>
                                <p class="small text-muted">Upload dokumen yang ingin dicetak dari perangkat Anda</p>
                            </div>
                            <div class="col-md-3 text-center mb-4">
                                <div class="step-number bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>3</strong>
                                </div>
                                <h6>Pilih Opsi</h6>
                                <p class="small text-muted">Tentukan jenis kertas, warna, dan pengaturan cetak lainnya</p>
                            </div>
                            <div class="col-md-3 text-center mb-4">
                                <div class="step-number bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                                    <strong>4</strong>
                                </div>
                                <h6>Bayar & Cetak</h6>
                                <p class="small text-muted">Lakukan pembayaran dan dokumen akan segera dicetak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <h4>Sudah Punya Session?</h4>
                <p>Masukkan token session Anda untuk melanjutkan proses cetak</p>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="sessionToken" placeholder="Masukkan token session...">
                            <button class="btn btn-primary" onclick="accessSession()">
                                <i class="fas fa-arrow-right me-2"></i>Akses Session
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function generateSession() {
    console.log('Generating session...');
    
    fetch('/print-service/generate-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success && data.token) {
            console.log('Redirecting to:', '/print-service/' + data.token);
            window.location.href = '/print-service/' + data.token;
        } else {
            console.error('Invalid response:', data);
            alert('Gagal membuat session. Silakan coba lagi.');
        }
    })
    .catch(error => {
        console.error('Error details:', error);
        alert('Terjadi kesalahan: ' + error.message);
    });
}

function accessSession() {
    const token = document.getElementById('sessionToken').value.trim();
    if (token) {
        window.location.href = '/print-service/' + token;
    } else {
        alert('Silakan masukkan token session');
    }
}
</script>
@endsection
