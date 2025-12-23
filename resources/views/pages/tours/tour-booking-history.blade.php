@extends('layout.pages-layout')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Page Title')
@push('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/tour_history.css') }}">
@endpush
@section('content')
    <div class="content d-flex flex-column ">
        <div class="container-fluid history-container">
            <h2>Lịch Sử Đặt Tour</h2>
            <x-form-alerts></x-form-alerts>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Tên Tour</th>
                        <th scope="col">Ngày Đặt</th>
                        <th scope="col">Ngày Đi</th>
                        <th scope="col">Số Lượng Người</th>
                        <th scope="col">Tổng Tiền</th>
                        <th scope="col">Đánh Giá</th>
                        <th scope="col">Hủy Tour</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Sort bookings: non-cancelled first, cancelled at bottom
                        $sortedBookings = collect($bookings ?? [])
                            ->sort(function ($a, $b) {
                                if ($a->status === 'cancelled' && $b->status !== 'cancelled') {
                                    return 1;
                                }
                                if ($a->status !== 'cancelled' && $b->status === 'cancelled') {
                                    return -1;
                                }
                                return 0;
                            })
                            ->values();
                    @endphp
                    @forelse($sortedBookings as $booking)
                        <tr>
                            <td><a href="{{ route('tours.show', $booking->tour) }}">{{ $booking->tour->title }}</a                            php artisan migrate:fresh></td>
                            <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($booking->departure_date)->format('d/m/Y') }}</td>
                            <td>{{ $booking->number_of_people }}</td>
                            <td>{{ number_format($booking->total_price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @php
                                    $departure = \Carbon\Carbon::parse($booking->departure_date);
                                    $today = \Carbon\Carbon::today();
                                    $effectiveStatus =
                                        $departure->isSameDay($today) || $departure->isPast()
                                            ? 'completed'
                                            : $booking->status;

                                    // Check if already reviewed this booking
                                    $hasReview = false;
                                    if ($effectiveStatus === 'completed' && Auth::check()) {
                                        $hasReview = \App\Models\Review::where(
                                            'booking_id',
                                            $booking->booking_id,
                                        )->exists();
                                    }
                                @endphp
                                @if ($effectiveStatus === 'completed')
                                    @if ($hasReview)
                                        <span class="badge bg-success">Đã đánh giá</span>
                                    @else
                                        <button class="btn btn-rate btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#ratingModal" data-booking-id="{{ $booking->booking_id }}"
                                            data-tour-id="{{ $booking->tour_id }}">Đánh Giá</button>
                                    @endif
                                @else
                                    <span
                                        class="badge bg-{{ $booking->status === 'pending' ? 'warning' : ($booking->status === 'confirmed' ? 'info' : ($booking->status === 'cancelled' ? 'danger' : 'secondary')) }}">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($booking->status === 'pending' && $departure->isFuture())
                                    <button class="btn btn-cancel btn-sm"
                                        data-booking-id="{{ $booking->booking_id }}">Hủy</button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Bạn chưa có lịch sử đặt tour nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal đánh giá -->
    <div class="modal fade" id="ratingModal" tabindex="-1" aria-labelledby="ratingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ratingModalLabel">Đánh Giá Tour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="ratingForm">
                        <div class="mb-3">
                            <label for="rating" class="form-label">Xếp Hạng</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="">Chọn xếp hạng</option>
                                <option value="1">1 sao</option>
                                <option value="2">2 sao</option>
                                <option value="3">3 sao</option>
                                <option value="4">4 sao</option>
                                <option value="5">5 sao</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Nhận Xét</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi Đánh Giá</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButtons = document.querySelectorAll('.btn-cancel');
            const ratingModal = document.getElementById('ratingModal');
            const ratingForm = document.getElementById('ratingForm');
            let currentBookingId = null;
            let currentTourId = null;

            // Handle rating modal button click
            document.querySelectorAll('.btn-rate').forEach(button => {
                button.addEventListener('click', function() {
                    currentBookingId = this.dataset.bookingId;
                    currentTourId = this.dataset.tourId;
                    // Reset form
                    ratingForm.reset();
                });
            });

            // Handle rating form submit
            ratingForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const rating = document.getElementById('rating').value;
                const comment = document.getElementById('comment').value;

                if (!rating || !comment.trim()) {
                    alert('Vui lòng điền đầy đủ thông tin');
                    return;
                }

                fetch(`/bookings/${currentBookingId}/review`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.content || '',
                        },
                        body: JSON.stringify({
                            rating,
                            comment
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Cảm ơn bạn đã đánh giá!');
                            bootstrap.Modal.getInstance(ratingModal).hide();
                            location.reload();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Lỗi: ' + error.message);
                    });
            });

            cancelButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    const bookingId = this.dataset.bookingId;
                    const row = this.closest('tr');

                    // Confirm cancellation
                    if (!confirm('Bạn có chắc chắn muốn hủy tour này?')) {
                        return;
                    }

                    // Send cancel request
                    fetch(`/bookings/${bookingId}/cancel`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')?.content || '',
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the status and button cell
                                const statusCell = row.querySelector('td:nth-child(6)');
                                const cancelCell = row.querySelector('td:nth-child(7)');

                                // Update status display
                                statusCell.innerHTML =
                                    '<span class="badge bg-danger">Đã hủy</span>';

                                // Replace button with dash
                                cancelCell.innerHTML = '<span class="text-muted">-</span>';

                                // Move the cancelled row to the bottom
                                const tbody = row.parentElement;
                                tbody.appendChild(row);

                                // Show success message
                                alert(data.message);
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Có lỗi xảy ra. Vui lòng thử lại.');
                        });
                });
            });
        });
    </script>

@endsection
