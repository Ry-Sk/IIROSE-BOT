<?php

namespace Bot\Provider\IIROSE;

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
    public function __construct($username, $password)
    {
        $this->username=$username;
        $this->password=$password;
    }

    /**
     * @param $room
     * @param \Closure $receive
     * @throws NetworkException
     */
    public function login($room, $receive)
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
            if(!@$this->client->push($handle)){
                Logger::warn('连接建立失败');
                $this->close();
                return;
            }
            @$this->client->push('=^v#');
            @$this->client->push(')#');
            @$this->client->push('>#');
            Logger::info('连接建立完成');
            go(function () use ($receive) {
                while (true) {
                    if (isset($this->client) && $this->client) {
                        $data=@$this->client->recv()->data;
                        if (!$data && $data !== '') {
                            \Co::sleep(0.1);
                            continue;
                        }
                        $receive($data);
                    }else{
                        \Co::sleep(0.1);
                    }
                }
            });
        } catch (NetworkException $e) {
            $this->close();
            throw $e;
        } catch (Throwable $e) {
            $this->close();
            throw new NetworkException('NetWork fail', 0, $e);
        }
    }

    public function send($data)
    {
        if (isset($this->client) && $this->client) {
            if (!@$this->client->push($data)) {
                $this->close();
            }
            return true;
        } else {
            return false;
        }
    }

    public function alive()
    {
        if (!isset($this->client)) {
            return false;
        }
        if (!$this->client) {
            return false;
        }
        if (!$this->client->connected) {
            return false;
        }
        return true;
    }

    public function close()
    {
        if (isset($this->client) && $this->client) {
            $this->client->close();
        }
        $this->client=null;
    }
}
