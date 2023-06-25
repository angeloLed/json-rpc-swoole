<?php

namespace App\Lib\Exceptions;

abstract class AbstractRpcResponseError extends \Exception
{
    public $code = -32603;
}
