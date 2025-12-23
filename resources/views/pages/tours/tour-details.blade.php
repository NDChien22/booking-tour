@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/tour_details.css') }}">
@endpush
@section('content')
    <div>
        <!-- Hero section with background image -->
        <section class="tour-hero" style="background-image: url('{{ $tour->url_img }}');">
            <div class="tour-hero__overlay">
                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-3">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-white-50">Trang
                                    chủ</a></li>
                            <li class="breadcrumb-item active text-white" aria-current="page">Chi tiết tour</li>
                        </ol>
                    </nav>
                    <h1 class="display-5 fw-bold text-white mb-2">{{ $tour->title }}</h1>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-success bg-opacity-75">Thời gian: {{ $tour->duration }} ngày</span>
                        <span class="badge bg-primary bg-opacity-75">Điểm đi:
                            {{ optional($tour->city)->city_name ?? 'Đang cập nhật' }}</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="container py-4">
            <div class="row g-4">
                <!-- Left content -->
                <div class="col-lg-8">
                    <!-- Description Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3">Mô tả tour</h3>
                            <p class="mb-0 text-secondary">{{ $tour->description }}</p>
                        </div>
                    </div>

                    <!-- Itinerary Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h3 class="h5 mb-3">Lịch trình</h3>
                            @php
                                $rawItinerary = $tour->itinerary ?? '';
                                $itineraryItems = is_array($rawItinerary)
                                    ? $rawItinerary
                                    : preg_split(
                                        '/\s*-\s*|\r\n|\n|\r/',
                                        (string) $rawItinerary,
                                        -1,
                                        PREG_SPLIT_NO_EMPTY,
                                    );
                            @endphp
                            @if (!empty($itineraryItems))
                                <ul class="list-group list-group-flush">
                                    @foreach ($itineraryItems as $item)
                                        <li class="list-group-item ps-0">
                                            <span class="me-2 text-primary">•</span>
                                            {{ is_array($item) ? implode(' - ', $item) : trim($item) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted mb-0">Chưa có lịch trình.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Reviews Card -->
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h3 class="h5 mb-0">Đánh giá</h3>
                                @php
                                    $reviewsList = $reviews ?? [];
                                    $avgRating = !empty($reviewsList)
                                        ? round(collect($reviewsList)->avg('rating'), 1)
                                        : $tour->average_rating ?? null;
                                    $ratingCount = !empty($reviewsList)
                                        ? count($reviewsList)
                                        : $tour->review_count ?? 0;
                                    $filledStars = $avgRating ? (int) floor($avgRating) : 0;
                                @endphp
                                <div class="ratings small">
                                    {{ str_repeat('★', $filledStars) }}{{ str_repeat('☆', 5 - $filledStars) }}
                                    @if ($avgRating)
                                        ({{ number_format($avgRating, 1) }} / 5 từ {{ $ratingCount }} đánh giá)
                                    @else
                                        (Chưa có đánh giá)
                                    @endif
                                </div>
                            </div>

                            <div class="review-list">
                                @forelse(($reviews ?? []) as $review)
                                    <div class="review-item">
                                        <div class="review-rating">
                                            {{ str_repeat('★', (int) $review->rating) }}{{ str_repeat('☆', 5 - (int) $review->rating) }}
                                        </div>
                                        <div class="review-content">
                                            <p><strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong> -
                                                {{ \Illuminate\Support\Carbon::parse($review->created_at)->format('d/m/Y') }}
                                            </p>
                                            <p>{{ $review->comment }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted mb-0">Chưa có đánh giá.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right sidebar -->
                <div class="col-lg-4">
                    <div class="sticky-sidebar">
                        <div class="card shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted">Giá từ</span>
                                    <span class="tour-price">{{ number_format($tour->price, 0, ',', '.') }} VNĐ</span>
                                </div>
                                <a href="{{ route('tours.booking', ['tour' => $tour->tour_id]) }}"><button class="btn btn-primary w-100">
                                    Đặt tour ngay
                                </button></a>
                            </div>
                        </div>

                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h6 class="text-uppercase text-muted">Thông tin nhanh</h6>
                                <ul class="list-unstyled mb-0 small">
                                    <li class="d-flex align-items-center py-1">
                                        <span class="badge rounded-pill bg-light text-dark me-2">Thời gian</span>
                                        {{ $tour->duration }} ngày
                                    </li>
                                    <li class="d-flex align-items-center py-1">
                                        <span class="badge rounded-pill bg-light text-dark me-2">Điểm đi</span>
                                        {{ optional($tour->city)->city_name ?? 'Đang cập nhật' }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-grid mt-3">
                            <a href="javascript:history.back()" class="btn btn-secondary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-arrow-left me-2" viewBox="0 0 16 16" style="vertical-align: middle;">
                                    <path fill-rule="evenodd"
                                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z" />
                                </svg>
                               Trở lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
