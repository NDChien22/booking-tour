@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@section('content')
    <x-form-alerts></x-form-alerts>

    <div class="top-content d-flex justify-content-center align-items-center">
        <form method="GET" action="{{ route('tours.list') }}" class="d-flex justify-content-center m-2" role="search">
            <input class="form-control me-2" type="search" name="search" placeholder="Tìm kiếm tour..." aria-label="Search">
            <button class="btn btn-success" type="submit">Tìm kiếm</button>
        </form>
    </div>

    <div class="container">
        <div class="content mt-3">
            <h2 class="text-center mb-4">Các tour nổi bật</h2>
            <div class="row row-cols-1 row-cols-md-3 g-4">
                <!-- Tour Item -->
                @forelse($tours ?? [] as $tour)
                    <x-tour-card :tour="$tour"></x-tour-card>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">Không có tour nào.</p>
                    </div>
                @endforelse
            </div>
            <!-- Link to Tour List -->
            <div class="d-flex justify-content-start mt-4 mb-4">
                <a href="{{ route('tours.list') }}" class="btn btn-outline-primary btn-lg">Xem Tất Cả Các Tour</a>
            </div>
        </div>
    </div>

@endsection
