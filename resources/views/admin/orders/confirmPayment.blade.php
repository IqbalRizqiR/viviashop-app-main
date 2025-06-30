@extends('frontend.layouts')

@section('content')
    <div class="container mb-5" style="margin-top: 12rem;">
        <div class="row">
            <div class="col-md-12 box-border rounded">
                <form action="{{ route('orders.confirmPayment', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <input type="hidden" value="{{ $order->id }}" name="id_order">
                    <div class="form-group mb-3">
                        <input type="file" required class="form-control" name="file_bukti" id="image">
                    </div>
                    <div class="form-item mt-4 d-none image-item">
                        <label for="">Preview Image</label>
                        <img src="" alt="" class="img-fluid img-preview">
                    </div>
                    <div class="mx-auto">
                        <button type="submit d-block text-center">Upload Bukti Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
