<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response as illuminateResponse;

class ApiController extends Controller
{
    protected $statusCode = illuminateResponse::HTTP_OK;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondNotFound($message = 'Not Found!')
    {
        return $this->setStatusCode(illuminateResponse::HTTP_NOT_FOUND)->respondWithError($message);
    }

    public function respondValidationError($message = 'Validation Error Check Your Parameter')
    {
        return $this->setStatusCode(illuminateResponse::HTTP_UNPROCESSABLE_ENTITY)->respondWithError($message);
    }

    public function respondBadRequest($message = 'Something Went Wrong With Request')
    {
        return $this->setStatusCode(illuminateResponse::HTTP_BAD_REQUEST)->respondWithError($message);
    }

    public function respondUnAuthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(illuminateResponse::HTTP_UNAUTHORIZED)->respondWithError($message);
    }

    protected function respondAccessDenied($message = 'Access denied')
    {
        return $this->setStatusCode(illuminateResponse::HTTP_FORBIDDEN)->respondWithError($message);
    }

    protected function respondCreated($data ,$message)
    {
        return $this->setStatusCode(illuminateResponse::HTTP_CREATED)->respond([
            'message' => $message,
            'data' => $data
        ]);
    }

    public function respondWithError($message)
    {
        return $this->respond([
            'message' => $message,
            'status_code' => $this->getStatusCode()
        ]);
    }

    public function respondWithSuccess($message = null, $data = null)
    {
        return $this->setStatusCode(illuminateResponse::HTTP_OK)->respond([
            'message' => $message,
            'data' => $data,
            'status_code' => illuminateResponse::HTTP_OK
        ]);
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json(['data' => $data], $this->getStatusCode(), $headers);
    }

    protected function respondWithPagination(LengthAwarePaginator $paginatedResult, $data)
    {
        $data = array_merge($data, [

            'paginator' => [
                'totalCount' => $paginatedResult->total(),
                'totalPages' => ceil($paginatedResult->total() / $paginatedResult->perPage()),
                'currentPage' => $paginatedResult->currentPage(),
                'limit' => $paginatedResult->perPage()
            ]
        ]);

        return $this->respond($data);
    }

    /**
     * this will return the translation message key name
     * or it will return model as a string
     * @return string
     */
    public function messageKeyName()
    {
        if (isset($this->message))
            return $this->message;

        return 'model';
    }
}
