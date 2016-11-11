<?php

namespace understeam\fcm;

use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

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
    private $_httpClient;

    /**
     * Returns FCM client
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
     * Creates FCM client
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
     * Returns Guzzle client
     * @return \GuzzleHttp\ClientInterface
     */
    public function getHttpClient()
    {
        if (!isset($this->_httpClient)) {
            $this->_httpClient = Yii::createObject($this->guzzleClass, [$this->guzzleConfig]);
        }
        return $this->_httpClient;
    }

    /**
     * Creates Notification object
     * @param string $title Notification title
     * @param string $body Notification body text
     * @return Notification
     */
    public function createNotification($title, $body)
    {
        return new Notification($title, $body);
    }

    /**
     * Creates Message object
     * @param string[]|string $deviceTokens tokens of recipient devices
     * @return Message
     */
    public function createMessage($deviceTokens = [])
    {
        $message = new Message();
        if (is_string($deviceTokens)) {
            $deviceTokens = [$deviceTokens];
        }
        if (!is_array($deviceTokens)) {
            throw new InvalidParamException("\$deviceTokens must be string or array");
        }
        foreach ($deviceTokens as $token) {
            $message->addRecipient($this->createDevice($token));
        }
        return $message;
    }

    /**
     * Creates Device object
     * @param string $token
     * @return Device
     */
    public function createDevice($token)
    {
        return new Device($token);
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
