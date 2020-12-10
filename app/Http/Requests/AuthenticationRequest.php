<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case "PUT":
                return [
                    'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|between:8,20',
                    'password_repeat' => 'required|same:password'
                ];
                break;
            case "POST":
                return [];
                break;
            case "GET":
                return [];
                break;
            case "DELETE":
                return [];
                break;
            default:
                return [];
                break;
        }
    }


    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',
            'email.required' => '用户邮箱不能为空。',
            'email.unique' => '邮箱已被占用。',
            'password.required' => '请输入密码。',
            'password.between' => '必须介于 8 - 20 个字符之间。',
            'password_repeat.required' => '请输入密码确认。',
            'password.same' => '两次密码输入不一致。',
        ];
    }
}
