<?php

namespace App\Http\Requests;

use Illuminate\Http\Request;

class StoreImage extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:32048'
            // 'image' => 'required|image|mimes:jpeg,jpg,png,gif,svg|max:2048'
        ];
    }
}
