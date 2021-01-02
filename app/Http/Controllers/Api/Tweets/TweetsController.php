<?php

namespace App\Http\Controllers\Api\Tweets;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\CreateTweetRequest;
use App\Http\Resources\TweetCollection;
use App\Http\Resources\TweetResource;
use App\Models\Tweet;
use Illuminate\Http\Request;

class TweetsController extends ApiController
{

    /**
     * @api {Get} / Get Tweets
     * @apiGroup Tweets
     * @apiName Get Tweets
     * @apiDescription Get tweets of followers users
     * @apiParam {integer} page required The page number from 1.
     * @apiParam {integer} limit  number of items from 1 to 500.
     * @apiSuccessExample Success-Response:
        {"data":[{"id":7,"tweet":"this is my first tweet","user_id":2,"created_at":"2021-01-01T22:30:30.000000Z"}]}
     */
    public function index(Request $request)
    {
        $page =  intval($request->input('page', 1));
        $limit = intval($request->input('limit', 10));

        if($limit <= 0){
            $limit = 5;
        }
        if($limit > 500){
            $limit = 500;
        }
        $skip = (($page - 1) * $limit);

        $followers = auth()->user()->following()->pluck('following_id');

        $tweets = Tweet::whereIn('user_id', $followers)->take($limit)->skip($skip)->get();

        return new TweetCollection($tweets);
    }

     /**
     * @api {Post} / Create Tweet
     * @apiGroup Tweets
     * @apiName Create Tweet
     * @apiDescription Create tweets
     * @apiParam {text} tweet required The text of tweet max 140 character.
     * @apiSuccessExample Success-Response:
        {"data":{"message":"Tweed Added successfully","data":{"id":10,"tweet":"this is my first tweet","user_id":1,"created_at":"2021-01-01T22:34:02.000000Z"}}}     */
    public function store(CreateTweetRequest $request)
    {
        $tweet = Tweet::create(['tweet' => $request->tweet, 'user_id' => auth()->id()]);

        return $this->respondCreated(new TweetResource($tweet), trans('messages.tweet_created'));
    }
}
