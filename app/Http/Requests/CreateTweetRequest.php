<?php

namespace App\Http\Requests;

class CreateTweetRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tweet' => 'required|max:140'
        ];
    }
}
