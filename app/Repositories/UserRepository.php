<?php 
namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    public function getUserById(int $id): ?User
    {
        return $this->model->find($id);
    }

    public function getUsersByRole(string $role): Collection
    {
        return $this->model->where('role', $role)->get();
    }

    public function emailExists(string $email): bool
    {
        return $this->model->where('email', $email)->exists();
    }

    public function usernameExists(string $username): bool
    {
        return $this->model->where('username', $username)->exists();
    }

    public function updateProfile(int $id, array $data): bool
    {
        $allowedFields = ['email', 'phone_number', 'full_name', 'address'];
        $filtered = array_intersect_key($data, array_flip($allowedFields));

        return $this->update($id, $filtered);
    }

    public function changePassword(int $id, string $hashedPassword): bool
    {
        return $this->update($id, ['password' => $hashedPassword]);
    }

    public function createTourBooking(int $userId, int $tourId, array $bookingData)
    {
        $user = $this->model->find($userId);
        if (!$user) {
            return null;
        }

        return $user->bookings()->create(array_merge($bookingData, ['tour_id' => $tourId]));
    }
}
?>