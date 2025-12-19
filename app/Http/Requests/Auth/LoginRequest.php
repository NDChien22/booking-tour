<?php 
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest{
    public function authorize(): bool{
        return true;
    }

    public function rules(): array{
        $fieldType =  filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $rules = [
            'login_id' => ['required',
                $fieldType === 'email' ? 'email' : 'string',
                'exists:users,' . $fieldType,
            ],
            'password' => ['required', 'min:5'],
        ];

        return $rules;
    }

    public function messages(): array{
        return [
            'login_id.required' => 'Vui lòng nhập email hoặc tên đăng nhập.',
            'login_id.email' => 'Định dạng email không hợp lệ.',
            'login_id.exists' => 'Email hoặc tên đăng nhập không tồn tại.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
        ];
    }

    public function getFieldType(): string{
        return filter_var($this->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    }

    public function getCredentials(): array{
        $fieldType = $this->getFieldType();
        return [
            $fieldType => $this->login_id,
            'password' => $this->password,
        ];
    }
}

?>