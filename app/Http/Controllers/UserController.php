<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Regions;
use App\Repositories\UserRepository;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    protected $userRepository;
    protected $mailService;

    public function __construct(UserRepository $userRepository, MailService $mailService)
    {
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
    }

    public function profileForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Hồ sơ cá nhân',
            'user' => Auth::user(),
        ];

        return view('pages.users.profile', $data);
    }

    public function passwordForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Đổi mật khẩu',
            'user' => Auth::user(),
        ];

        return view('pages.users.change-password', $data);
    }
    public function tourDetailsForm(Tour $tour)
    {
        $reviews = Review::where('tour_id', $tour->tour_id)
            ->with('user')
            ->latest()
            ->get();

        $data = [
            'pageTitle' => 'Chi tiết tour',
            'tour' => $tour,
            'reviews' => $reviews,
        ];

        return view('pages.tours.tour-details', $data);
    }

    public function profileUpdate(UpdateProfileRequest $request)
    {
        $userId = $request->user()->user_id;
        $data = $request->validated();

        $updated = $this->userRepository->updateProfile($userId, $data);

        if ($updated) {
            return redirect()->back()->with('success', 'Cập nhật thông tin thành công.');
        }

        return redirect()->back()->with('error', 'Không thể cập nhật thông tin. Vui lòng thử lại.');
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = $request->user();
        $data = $request->validated();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($data['current_password'], $user->password)) {
            return redirect()->back()->with('error', 'Mật khẩu hiện tại không chính xác.');
        }

        // Cập nhật mật khẩu mới
        $hashedPassword = Hash::make($data['password']);
        $updated = $this->userRepository->changePassword($user->user_id, $hashedPassword);

        if ($updated) {
            return redirect()->route('profile')->with('success', 'Mật khẩu đã được đổi thành công.');
        }

        return redirect()->back()->with('error', 'Không thể đổi mật khẩu. Vui lòng thử lại.');
    }

    public function tourList(Request $request)
    {
        $perPage = 9;
        $regionId = $request->query('region');
        $priceRange = $request->query('price'); // low, medium, high
        $search = $request->query('search');

        $query = Tour::query()->with(['city.region']);

        // Filter by region (through city.region_id)
        if (!empty($regionId)) {
            $query->whereHas('city', function ($q) use ($regionId) {
                $q->where('region_id', $regionId);
            });
        }

        // Filter by price
        if (!empty($priceRange)) {
            if ($priceRange === 'low') {
                $query->where('price', '<', 5000000);
            } elseif ($priceRange === 'medium') {
                $query->whereBetween('price', [5000000, 10000000]);
            } elseif ($priceRange === 'high') {
                $query->where('price', '>', 10000000);
            }
        }

        // Search by keyword
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $tours = $query->latest()->paginate($perPage)->withQueryString();

        $regions = Regions::select('region_id', 'region_name')
            ->orderBy('region_name')
            ->get();

        $data = [
            'pageTitle' => 'Danh sách tour',
            'tours' => $tours,
            'regions' => $regions,
            'selectedRegion' => $regionId,
            'selectedPrice' => $priceRange,
            'search' => $search,
        ];

        return view('pages.tours.tour-lists', $data);
    }

    function tourBookingForm(Tour $tour, UserRepository $userRepository)
    {
        $data = [
            'pageTitle' => 'Đặt tour',
            'tour' => $tour,
            'user' => $userRepository->getUserById(Auth::id()),
        ];

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đặt tour.');
        }

        return view('pages.tours.tour-booking', $data);
    }

    function tourBookingHandle(Request $request, Tour $tour, UserRepository $userRepository)
    {
        $request->validate(
            [
                'tour_date' => 'required|date|after_or_equal:today',
                'number_of_people' => 'required|integer|min:1|max:100',
            ],
            [
                'tour_date.after_or_equal' => 'Ngày khởi hành phải là ngày hôm nay hoặc một ngày trong tương lai.',
                'number_of_people.max' => 'Số lượng người không được vượt quá 100.',
                'number_of_people.min' => 'Số lượng người phải ít nhất là 1.',
            ]
        );
        $user = $userRepository->getUserById(Auth::id());
        $bookingData = [
            'booking_date' => now()->toDateString(),
            'departure_date' => $request->input('tour_date'),
            'number_of_people' => $request->input('number_of_people'),
            'total_price' => $tour->price * $request->input('number_of_people'),
            'status' => 'pending',
        ];

        $booking = $userRepository->createTourBooking($user->user_id, $tour->tour_id, $bookingData);

        if ($booking) {
            // Gửi email xác nhận đặt tour
            $this->mailService->sendTourBookingConfirmationEmail($user, $tour, $booking);
            return redirect()->route('dashboard')->with('success', 'Đặt tour thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất có thể.');
        } else {
            return redirect()->back()->with('error', 'Đặt tour thất bại. Vui lòng thử lại.');
        }
    }

    function bookingHistory()
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with('tour')->orderBy('booking_date', 'desc')->get();

        $data = [
            'pageTitle' => 'Lịch sử đặt tour',
            'bookings' => $bookings,
        ];

        return view('pages.tours.tour-booking-history', $data);
    }

    function cancelBooking(Booking $booking)
    {
        $user = Auth::user();

        // Check if booking belongs to current user and can be cancelled
        if ($booking->user_id !== $user->user_id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền hủy booking này.'], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json(['success' => false, 'message' => 'Không thể hủy booking này.'], 400);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Hủy tour thành công.']);
    }

    function submitReview(Booking $booking, Request $request)
    {
        $user = Auth::user();

        // Check if booking belongs to current user
        if ($booking->user_id !== $user->user_id) {
            return response()->json(['success' => false, 'message' => 'Không có quyền đánh giá booking này.'], 403);
        }

        // Check if tour has been completed (departure date has passed)
        $departureDate = Carbon::parse($booking->departure_date);
        $today = Carbon::now();
        if (!$departureDate->isPast() && !$departureDate->isToday()) {
            return response()->json(['success' => false, 'message' => 'Chỉ có thể đánh giá tour sau khi tour kết thúc.'], 400);
        }

        // Check if already reviewed this specific booking
        $existingReview = Review::where('booking_id', $booking->booking_id)
            ->first();

        if ($existingReview) {
            return response()->json(['success' => false, 'message' => 'Bạn đã đánh giá booking này rồi.'], 400);
        }

        // Validate request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ], [
            'rating.required' => 'Vui lòng chọn xếp hạng.',
            'rating.min' => 'Xếp hạng phải từ 1 đến 5 sao.',
            'comment.required' => 'Vui lòng nhập nhận xét.',
            'comment.min' => 'Nhận xét phải có ít nhất 10 ký tự.',
            'comment.max' => 'Nhận xét không được vượt quá 500 ký tự.',
        ]);

        // Create review
        Review::create([
            'booking_id' => $booking->booking_id,
            'user_id' => $user->user_id,
            'tour_id' => $booking->tour_id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        return response()->json(['success' => true, 'message' => 'Cảm ơn bạn đã đánh giá tour!']);
    }
}
