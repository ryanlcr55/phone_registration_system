<?php


namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class CustomResponse implements Responsable
{
    const STATUS_CODE_SUCCESS = 0x000;
    private $payload;
    private $statusCode;
    protected $message;

    public function __construct($payload = [], $statusCode = self::STATUS_CODE_SUCCESS, $message = null)
    {
        $this->payload = $payload;
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    private function isDataPaginated(): bool
    {
        return is_array($this->payload) &&
            array_key_exists('total', $this->payload) &&
            array_key_exists('current_page', $this->payload) &&
            array_key_exists('number_per_page', $this->payload);
    }

    public function toResponse($request)
    {
        $response = [
            'status_code' => sprintf('0x%03X', $this->statusCode),
            'result' => $this->payload,
            'message' => $this->message,
        ];
        if ($this->isDataPaginated()) {
            $response['data'] = $this->payload['data'];
            $response = array_merge($this->payload, $response);
        }

        return response(
            $response,
            200,
            ['X-MMRM-Core-Resp-Success' => $this->statusCode === self::STATUS_CODE_SUCCESS ? 1 : 0]
        );
    }
}
