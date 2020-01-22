<?php
declare(strict_types=1);

namespace ReviewParser\Helper;

class Logger
{
    private const INFO_PATTERN = 'INFO: %s';
    private const ERROR_PATTERN = 'ERROR: %s';

    /**
     * @var string
     */
    private $logFile;

    /**
     * Logger constructor.
     * @param string $logFile
     */
    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
    }

    public function addInfoMessage(string $message): void
    {
        $this->addMessage(sprintf(self::INFO_PATTERN, $message));
    }

    public function addErrorMessage(string $message): void
    {
        $this->addMessage(sprintf(self::ERROR_PATTERN, $message));
    }

    private function addMessage(string $message): void
    {
        file_put_contents($this->logFile, $message.PHP_EOL, FILE_APPEND);
    }
}