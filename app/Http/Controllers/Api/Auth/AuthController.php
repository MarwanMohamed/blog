<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Http\Helpers\UploadImage;
use App\Models\User;
use Hash;

class AuthController extends ApiController
{
    /**
     * @api {post} /login Login
     * @apiGroup Auth
     * @apiName Make Login
     * @apiDescription Make Login
     * @apiParam {email} email required The email
     * @apiParam {password} password required The password
     * @apiSuccessExample Success-Response:
       {"data":{"id":1,"name":"Admin","email":"admin@admin.com","image":"http:\/\/localhost:8000\/uploads\/users\/IMG_1769.JPG-2501609361065.JPG"},"token":"1|NR4U1brRQlCoOkpKEsER1iX13qbYQuVRqE4y4QAx"}
     * @apiErrorExample Error-Response:
        {"data":{"message":"These credentials do not match our records.","status_code":401}}
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken("local_token")->plainTextToken;

            return (new UserResource($user))->additional(['token' => $token]);
        }

        return $this->setStatusCode(401)->respondWithError(trans('messages.login_invalid'));
    }

    /**
     * @api {post} /signup Signup
     * @apiGroup Auth
     * @apiName Make Register
     * @apiDescription Make Register
     * @apiParam {string} name required The name
     * @apiParam {email} email required The email
     * @apiParam {password} password required The password
     * @apiParam {password} password_confirmation required The password confirmation
     * @apiParam {image} image required The image
     * @apiSuccessExample Success-Response:
       {"data":{"id":1,"name":"Admin","email":"admin@admin.com","image":"http:\/\/localhost:8000\/uploads\/users\/IMG_1769.JPG-2501609361065.JPG"},"token":"1|NR4U1brRQlCoOkpKEsER1iX13qbYQuVRqE4y4QAx"}
    */
    public function signup(RegisterRequest $request)
    {
        if ($request->image) {
            $image = UploadImage::upload($request->image, 'users');
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'image' => $image ?? null
        ]);

        $token = $user->createToken("local_token")->plainTextToken;
        return (new UserResource($user))->additional(['token' => $token]);
    }
}
