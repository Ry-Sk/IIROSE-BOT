<?php

namespace Bot;
use Bot\Exception\NetworkException;
use Logger\Logger;
use Swoole\Coroutine\Http\Client;
use Throwable;

class Connection
{
    private $username;
    private $password;
    /** @var Client $client */
    private $client;
    public function __construct($username,$password)
    {
        $this->username=$username;
        $this->password=$password;
    }

    /**
     * @param $room
     * @param \Closure $receive
     * @throws NetworkException
     */
    public function login($room,$receive)
    {
        try {
            $this->client = new Client('m.iirose.com', 443, true);
            $ret = $this->client->upgrade('/');
            $handle = '*' . json_encode(
                    [
                        'r' => $room,
                        'n' => $this->username,
                        'p' => md5($this->password),
                        'st' => 'n',
                        'mo' => '',
                        'cp' => microtime() . '1090',
                        'mu' => '01',
                        'nt' => '!6',
                        'mb' => '',
                        'fp' => '@' . md5($this->username)
                    ]
                );
            throw_if(!@$this->client->push($handle),new NetworkException());
            @$this->client->push('=^v#');
            @$this->client->push(')#');
            @$this->client->push('>#');

            go(function ()use($receive){
                try{
                    while (true){
                        $data=@$this->client->recv()->data;
                        if(!$data){
                            throw new NetworkException();
                        }
                        $receive($data);
                    }
                }catch (Throwable $e){
                    var_dump($e);
                }
            });
        }catch (NetworkException $e){
            $this->close();
            throw $e;
        }catch(Throwable $e){
            $this->close();
            throw new NetworkException('NetWork fail',0,$e);
        }
    }

    public function send($data)
    {
        if(isset($this->client) && $this->client){
            if(!@$this->client->push($data)){
                $this->close();
            }
            return true;
        }else{
            return false;
        }
    }

    public function alive()
    {
        if(!isset($this->client)){
            return false;
        }
        if(!$this->client){
            return false;
        }
        if(!$this->client->connected){
            return false;
        }
        return true;
    }

    public function close()
    {
        echo 'c';
        try {
            if(isset($this->client) && $this->client){
                $this->client->close();
            }
            unset($this->client);
        }catch (Throwable $e){}
    }
}