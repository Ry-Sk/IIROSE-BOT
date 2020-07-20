<?php


namespace Http;

class Response extends \Symfony\Component\HttpFoundation\Response
{
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
        $this->headers->set('Server', 'AdminPHP');
    }
}
