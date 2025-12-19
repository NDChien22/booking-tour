<div class="col-md-4 mb-4">
    <div class="tour-card">
        <img src="{{ $tour->url_img }}" alt="{{ $tour->title }}" class="img-fluid">
        <div class="tour-details">
            <h3>{{ $tour->title }}</h3>
            <p>Thời gian: {{ $tour->duration }}</p>
            <p>Giá: {{ number_format($tour->price, 0, ',', '.') }} VNĐ</p>
            <a href="{{ route('tours.show', $tour) }}" class="btn btn-primary">Xem Chi Tiết</a>
        </div>
    </div>
</div>
