<?php

namespace KielD01\SmscUA;

/**
 * Class Response
 * @package KielD01\SmscUA
 * @property integer id
 * @property integer cnt
 * @property float|double cost
 * @property float balance
 * @property integer|null error_code
 * @property string|null error
 */
class Response
{

    /**
     * Error code
     *
     * @var integer|null
     */
    private $error_code = null;

    /**
     * Error message
     *
     * @var null
     */
    private $error = null;

    /**
     * Errors Messages
     *
     * @var array
     */
    private $errorMessages = [];

    /**
     * Response constructor.
     * @param array $response
     * @param array $errorsMessages
     */
    public function __construct($response = [], $errorsMessages = [])
    {
        $this->errorMessages = $errorsMessages;

        foreach ($response as $name => $value) {
            switch ($name) {
                case 'cost':
                case 'balance':
                    $this->{$name} = floatval($value);
                    break;
                default:
                    $this->{$name} = $value;
                    break;
            }
        }

        if (array_key_exists('error_code', $response)) {
            $this->error = $this->errorMessages[$this->error_code];
        }
    }

    /**
     * Sets the variable
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'cost':
            case 'balance':
                $this->{$name} = floatval($value);
                break;
            case'error_code':
                $this->{$name} = floatval($value);
                return;
            case 'error':
                $this->{$name} = $this->errorMessages[$this->error_code];
                break;
            default:
                $this->errorMessages = $value;
                break;
        }
    }

    /**
     * Check, if there are any errors
     *
     * @return mixed
     */
    public function hasErrors()
    {
        return $this->error_code !== null;
    }

    /**
     * Get ID of the message
     *
     * @return int
     */
    public function getID(){
        return $this->id;
    }

    /**
     * Returns chunks count for the message
     *
     * @return int
     */
    public function getChunksCount() {
        return $this->cnt;
    }

    /**
     * Returns message cost
     *
     * @return float
     */
    public function getMessageCost(){
        return $this->cost;
    }

    /**
     * Returns current balance
     *
     * @return float
     */
    public function getBalance(){
        return $this->balance;
    }
}
