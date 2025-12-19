<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Models\Tour;
use App\Models\Regions;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        $data = [
            'pageTitle' => 'Chi tiết tour',
            'tour' => $tour,
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
}
