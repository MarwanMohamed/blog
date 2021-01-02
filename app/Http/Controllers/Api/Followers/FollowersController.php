<?php

namespace App\Http\Controllers\Api\Followers;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\FollowersRequest;

class FollowersController extends ApiController
{
    /**
     * @api {post} /follow Follow User
     * @apiGroup Follower
     * @apiName Make Follow
     * @apiDescription Make Follow to User
     * @apiParam {integer} user_id required The user that you will follow
     * @apiSuccessExample Success-Response:
       {"data":{"message":"Followed Successfully","data":null,"status_code":200}}
     * @apiErrorExample Error-Response:
       {"data":{"message":"You have already followed this user","status_code":400}}
    */
    public function follow(FollowersRequest $request)
    {
        $auth = auth()->user();
        $theUserId = $request->user_id;

        if ($auth->id == $theUserId)
            return $this->setStatusCode(400)->respondWithError(trans('messages.invalid_user'));

        if ($auth->following->contains($theUserId))
            return $this->setStatusCode(400)->respondWithError(trans('messages.already_followed'));

        $auth->following()->attach($theUserId);

        return $this->respondWithSuccess(trans('messages.follwed_success'));
    }
}
