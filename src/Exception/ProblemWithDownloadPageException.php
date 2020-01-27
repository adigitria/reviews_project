<?php
declare(strict_types=1);

namespace ReviewParser\Exception;

class ProblemWithDownloadPageException extends \RuntimeException
{
    /**
     * @var string
     */
    private $url;

    public function __construct($message = '', string $url = '')
    {
        $code     = 0;
        $previous = null;
        parent::__construct($message, $code, $previous);

        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}
