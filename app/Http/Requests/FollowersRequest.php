<?php

namespace App\Http\Requests;


class FollowersRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|min:0|exists:users,id'
        ];
    }
}
