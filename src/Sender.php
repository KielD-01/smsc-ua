<?php

namespace KielD01\SmscUA;

use Exception;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SmsClient
 * @package KielD01\SmscUA
 */
abstract class Sender
{
    const API_ENDPOINT = 'https://smsc.ua/sys/send.php';

    /**
     * Available locales
     *
     * @var array
     */
    const LOCALES = [
        'en' => 'en_GB',
        'gb' => 'en_GB',
        'ru' => 'ru_RU',
        'ua' => 'uk_UA',
        'uk' => 'uk_UA'
    ];

    /**
     * Default Locale
     *
     * @var string|null
     */
    protected static $locale = null;

    /**
     * Error Messages list
     *
     * @var array
     */
    private static $errorMessages = [];

    /**
     * Auth Credentials
     *
     * @var array
     */
    private static $credentials = [
        'login' => null,
        'psw' => null
    ];

    /** @var Client */
    private $http;

    /**
     * Form Params
     *
     * @var array
     */
    private $form_params = [];

    /**
     * Sets credential
     *
     * @return void
     */
    public static function setCredentials()
    {
        $args = func_get_args();
        $login = $psw = null;

        switch (count($args)) {
            case 1:
                list($login, $psw) = $args[0];
                break;
            case 2:
                list($login, $psw) = $args;
                break;
            default:
                break;
        }

        self::$credentials['login'] = $login;
        self::$credentials['psw'] = $psw;
    }

    /**
     * Returns credentials list
     *
     * @return array
     */
    public static function getCredentials()
    {
        return self::$credentials;
    }

    /**
     * Initialize HTTP Client to send a request
     *
     * @return void
     */
    protected function initializeClient()
    {
        $this->http = new Client([
            'base_uri' => self::API_ENDPOINT,
            'cookies' => true
        ]);
    }

    /**
     * Sets Form Params
     *
     * @param array $formParams
     */
    protected function setFormParams($formParams = [])
    {
        $this->form_params = array_merge(
            self::$credentials, $formParams
        );
    }

    /**
     * Returns a Response
     *
     * @return Response
     * @throws Exception
     */
    protected function getResponse()
    {
        if (!self::$locale) {
            self::setLocale();
        }

        $this->form_params = array_merge($this->form_params, [
            'fmt' => 3,
            'cost' => 3
        ]);

        return $this->parseResponse(
            $this->http->post('', [
                'form_params' => $this->form_params
            ])
        );
    }

    /**
     * Parsing and mutating JSON into a Response object/class
     *
     * @param ResponseInterface $response
     * @return Response
     */
    private function parseResponse(ResponseInterface $response)
    {
        return new Response(
            json_decode($response->getBody()->getContents(), 1), self::$errorMessages
        );
    }

    /**
     * Sets the locale
     *
     * @param string $locale
     * @throws Exception
     */
    public static function setLocale($locale = 'en')
    {
        self::$locale = mb_strtolower($locale);

        if(!array_key_exists($locale, self::LOCALES)){
            $availableLocales = implode(', ', array_keys(self::LOCALES));
            throw new Exception("Locale {$locale} does not exists within available locales. Use one from `{$availableLocales}`");
        }

        $errorsLocaleDir = self::LOCALES[$locale];
        self::$errorMessages = require_once(__DIR__ . "/Errors/{$errorsLocaleDir}/messages.php");
    }


}
