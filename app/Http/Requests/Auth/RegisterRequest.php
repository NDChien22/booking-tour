<?php 
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'phone_number' => ['required', 'string', 'max:20'],
            'full_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array{
        return [
            'username.required' => 'Vui lòng nhập tên đăng nhập.',
            'username.string' => 'Tên đăng nhập phải là chuỗi ký tự.',
            'username.max' => 'Tên đăng nhập không được vượt quá :max ký tự.',
            'username.unique' => 'Tên đăng nhập đã tồn tại.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.max' => 'Email không được vượt quá :max ký tự.',
            'email.unique' => 'Email đã tồn tại.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu phải là chuỗi ký tự.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            
            'phone_number.required' => 'Vui lòng nhập số điện thoại.',
            'phone_number.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone_number.max' => 'Số điện thoại không được vượt quá :max ký tự.',

            'full_name.string' => 'Họ tên phải là chuỗi ký tự.',
            'full_name.max' => 'Họ tên không được vượt quá :max ký tự.',

            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá :max ký tự.',
        ];
    }
}
?>