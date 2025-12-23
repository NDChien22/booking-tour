@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/tour_list.css') }}">
@endpush
@section('content')
    <div class="content">
        <div class="container_body">
            <h1 class="text-center mb-4">Danh Sách Tour</h1>
            <form method="GET" action="{{ route('tours.list') }}" class="filters-card mb-4">
                <div class="row g-3 align-items-end">
                    <!-- Lọc theo khu vực -->
                    <div class="col-md-4">
                        <label for="region" class="form-label">Khu vực</label>
                        <select name="region" id="region" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach ($regions ?? [] as $region)
                                <option value="{{ $region->region_id }}"
                                    {{ (string) ($selectedRegion ?? '') === (string) $region->region_id ? 'selected' : '' }}>
                                    {{ $region->region_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Lọc theo giá -->
                    <div class="col-md-3">
                        <label for="price" class="form-label">Khoảng giá</label>
                        <select name="price" id="price" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="low" {{ ($selectedPrice ?? '') === 'low' ? 'selected' : '' }}>Dưới 5 triệu
                            </option>
                            <option value="medium" {{ ($selectedPrice ?? '') === 'medium' ? 'selected' : '' }}>5 - 10 triệu
                            </option>
                            <option value="high" {{ ($selectedPrice ?? '') === 'high' ? 'selected' : '' }}>Trên 10 triệu
                            </option>
                        </select>
                    </div>

                    <!-- Tìm kiếm -->
                    <div class="col-md-3">
                        <label for="search" class="form-label">Từ khóa</label>
                        <input type="text" name="search" id="search" class="form-control" value="{{ $search ?? '' }}"
                            placeholder="Tìm kiếm tour...">
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                        @if (request()->filled('region') || request()->filled('price') || request()->filled('search'))
                            <a href="{{ route('tours.list') }}" class="btn btn-outline-secondary w-100 mt-2">Hiển thị tất
                                cả</a>
                        @endif
                    </div>
                </div>
            </form>
            <div class="row">
                <!-- Tour Item -->
                @forelse($tours ?? [] as $tour)
                    <x-tour-card :tour="$tour"></x-tour-card>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Không có tour nào.</p>
                    </div>
                @endforelse
            </div>

            @if (isset($tours) && $tours->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $tours->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection
