<?php
namespace Http\Responses;

class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        if (is_array($data)) {
            if (!isset($data['success'])) {
                $data['success']=true;
            }
            if (!isset($data['error'])) {
                $data['error']=null;
            }
        } elseif (is_null($data)) {
            $data=[
                'success'=>'true',
                'error'=>null,
            ];
        }
        parent::__construct($data, $status, $headers, $json);
    }
}
