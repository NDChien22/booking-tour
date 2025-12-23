@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/tour_booking.css') }}">
@endpush
@section('content')
    <div class="content">
        <div class="container_body">
            <h2 class="booking-title">Đặt Tour</h2>
            <form action="{{route('tours.booking.handle', ['tour' => $tour->tour_id])}}" method="POST" class="booking-form">
                @csrf
                <div class="form-group">
                    <label for="tourName" class="form-label">Tên Tour</label>
                    <input type="text" class="form-control" id="tourName" value="{{ $tour->title }}" readonly>
                </div>
                <div class="form-group">
                    <label for="tourDate" class="form-label">Ngày Đi</label>
                    <input type="date" class="form-control" id="tourDate" name="tour_date" value="{{ old('tour_date') }}" required>
                </div>
                @error('tour_date')
                    {{ $message }}
                @enderror
                <div class="form-group">
                    <label for="numberOfPeople" class="form-label">Số Lượng Người</label>
                    <input type="number" class="form-control" id="numberOfPeople" name="number_of_people" value="{{ old('number_of_people', 1) }}" min="1" max="10" required>
                </div>
                @error('number_of_people')
                    {{ $message }}
                @enderror
                <div class="form-group">
                    <label for="totalPrice" class="form-label">Tổng Tiền</label>
                    <input type="text" class="form-control total-price" id="totalPrice" value="0 VNĐ" readonly>
                </div>
                <button type="submit" class="btn btn-primary btn-submit">Xác Nhận Đặt Tour</button>
            </form>
        </div>
    </div>

    <script>
        const pricePerPerson = {{ $tour->price }};
        const numberOfPeopleInput = document.getElementById('numberOfPeople');
        const totalPriceInput = document.getElementById('totalPrice');
        const tourDateInput = document.getElementById('tourDate');

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        tourDateInput.setAttribute('min', today);
        if (!tourDateInput.value) {
            tourDateInput.value = today;
        }

        function updateTotalPrice() {
            const numberOfPeople = parseInt(numberOfPeopleInput.value) || 0;
            const totalPrice = pricePerPerson * numberOfPeople;
            totalPriceInput.value = totalPrice.toLocaleString('vi-VN') + ' VNĐ';
        }

        numberOfPeopleInput.addEventListener('input', updateTotalPrice);
        numberOfPeopleInput.addEventListener('change', updateTotalPrice);
        updateTotalPrice();
    </script>

@endsection
