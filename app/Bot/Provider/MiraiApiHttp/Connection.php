<?php

namespace Bot\Provider\MiraiApiHttp;

use Bot\Exception\NetworkException;
use Models\Bot;
use Swoole\Coroutine\Http\Client;
use Throwable;

class Connection
{
    /** @var Client $client */
    private $client;

    /**
     * @param $room
     * @param \Closure $receive
     * @throws NetworkException
     */
    public function login($receive)
    {
        try {
            $this->client = new Client('127.0.0.1', Bot::$instance->uid, false);
            $ret = $this->client->upgrade('/all?sessionKey='.urlencode(MiraiApiHttpProvider::$instance->session));
            go(function () use ($receive) {
                while (true) {
                    $data=@$this->client->recv()->data;
                    if (!$data && $data === '') {
                        throw new NetworkException();
                    }
                    $receive($data);
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
        try {
            if (isset($this->client) && $this->client) {
                $this->client->close();
            }
            unset($this->client);
        } catch (Throwable $e) {
        }
    }
}
