<?php

namespace App\Utilities;

class HttpStatusCodes
{
    // Informational 1xx
    const HTTP_CONTINUE = 100;
    const HTTP_SWITCHING_PROTOCOLS = 101;
    // ...

    // Success 2xx
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_ACCEPTED = 202;
    // ...

    // Redirection 3xx
    const HTTP_MOVED_PERMANENTLY = 301;
    const HTTP_FOUND = 302;
    // ...

    // Client Error 4xx
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    // ...

    // Server Error 5xx
    const HTTP_INTERNAL_SERVER_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;
    // ...
}