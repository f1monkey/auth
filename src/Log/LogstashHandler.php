<?php
declare(strict_types=1);

namespace App\Log;

use Monolog\Handler\SocketHandler;

/**
 * Class LogstashHandler
 *
 * @package App\Log
 */
class LogstashHandler extends SocketHandler
{
    /**
     * {@inheritdoc}
     */
    protected function generateDataStream(array $record): string
    {
        $content = $record['formatted'];

        return $this->buildHeader($content) . $content;
    }

    /**
     * Builds the header of the API Call
     *
     * @param string $content
     *
     * @return string
     */
    private function buildHeader(string $content): string
    {
        $header = "PUT / HTTP/1.1\r\n";
        $header .= "Content-Type: application/json\r\n";
        $header .= "Content-Length: " . strlen($content) . "\r\n";
        $header .= "\r\n";

        return $header;
    }
}