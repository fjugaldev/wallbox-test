<?php

namespace WallboxApp\PhpTest\Utils;

/**
 * Class JsonResponse
 * @package WallboxApp\PhpTest\Utils
 */
class JsonResponse
{
    /**
     * Generates a JSON Response.
     *
     * @param array|null $data
     * @param int $code
     * @return string
     */
    protected function jsonResponse(array $data = null, int $code = 200): string
    {
        header_remove();
        http_response_code($code);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');

        $status = array(
            200 => '200 OK',
            400 => '400 Bad Request',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
        );

        header('Status: '.$status[$code]);

        return json_encode([
            'error' => $code > 300,
            'code'  => $code,
            'data'  => $data,
        ]);
    }
}
