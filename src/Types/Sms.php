<?php

namespace KielD01\SmscUA\Types;

use KielD01\SmscUA\Sender;

class Sms extends Sender
{

    /** @var null|string */
    private $mes = null;

    /** @var null|string[] */
    private $phones = null;

    /** @var int */
    const FMT = 3;

    private $id;

    private $viber = 0;

    public function __construct()
    {
        $this->initializeClient();
    }

    public function setMessage($message = null)
    {
        $this->mes = $message;
        return $this;
    }

    public function setPhones($phones = null)
    {
        $this->phones = is_array($phones) ?
            $phones :
            (
            preg_match('/[,|;|\|]/', $phones) && is_string($phones) ?
                preg_split('/[,|;|\|]/', $phones) :
                (array)$phones
            );

        return $this;
    }

    public function isViber($isViber = false)
    {
        $this->viber = is_bool($isViber) ? (int)$isViber : strlen($isViber) > 0;
    }

    public function send()
    {
        $this->setFormParams([
            'mes' => $this->mes,
            'phones' => implode(',', $this->phones)
        ]);
        return $this->getResponse();
    }

}
