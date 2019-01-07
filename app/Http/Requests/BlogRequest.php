<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // 是否有权限执行该动作，return true 代表有权限
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    // 表单验证的规则
    public function rules()
    {
        return [
            'title' => 'required     | min:5       | max:255',
            'content'=>'required     | min:5'
        ];
    }

}
