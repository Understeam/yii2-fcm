<?php

namespace understeam\fcm;

use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use Yii;
use yii\base\Component;

/**
 * Client class dispatches paragraph1\phpFCM entities
 * @author Anatoly Rugalev <anatoly.rugalev@gmail.com>
 */
class Client extends Component
{

    /**
     * @var string Server API Key. Read how to obtain an api key here: https://firebase.google.com/docs/server/setup#prerequisites
     */
    public $apiKey;

    /**
     * @var string Proxy API Url
     */
    public $proxyApiUrl;

    /**
     * @var array configuration of Guzzle client (see http://docs.guzzlephp.org/en/latest/request-options.html)
     */
    public $guzzleConfig = [];

    /**
     * @var string Guzzle Client class name
     */
    public $guzzleClass = '\GuzzleHttp\Client';

    private $_fcm;

    /**
     * @return \paragraph1\phpFCM\Client
     */
    public function getFcm()
    {
        if (!isset($this->_fcm)) {
            $this->_fcm = $this->createFcm();
        }
        return $this->_fcm;
    }

    /**
     * @return \paragraph1\phpFCM\Client
     */
    protected function createFcm()
    {
        $client = new \paragraph1\phpFCM\Client();
        $client->setApiKey($this->apiKey);
        $client->setProxyApiUrl($this->proxyApiUrl);
        $client->injectHttpClient($this->getHttpClient());
        return $client;
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        return Yii::createObject($this->guzzleClass, [$this->guzzleConfig]);
    }

    public function createNotification($title, $body)
    {
        return new Notification($title, $body);
    }

    public function createMessage()
    {
        return new Message();
    }

    /**
     * @param Message $message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function send(Message $message)
    {
        return $this->getFcm()->send($message);
    }
}
