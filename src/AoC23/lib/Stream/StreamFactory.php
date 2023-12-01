<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Stream;

use Psr\Http\Message\StreamInterface;

class StreamFactory
{
    /**
     * Create a new stream from a string.
     *
     * @param string $content
     */
    public function createStream($content = ''): StreamInterface
    {
        $resource = fopen('php://temp', 'r+');
        $stream = new Stream($resource);
        $stream->write($content);
        $stream->rewind();

        return $stream;
    }

    /**
     * Create a stream from an existing file.
     *
     * @param string $filename
     * @param string $mode
     */
    public function createStreamFromFile($filename, $mode = 'r'): StreamInterface
    {
        $resource = fopen($filename, $mode);
        $stream = new Stream($resource);

        return $stream;
    }

    /**
     * Create a new stream from an existing resource.
     *
     * @param resource $resource
     */
    public function createStreamFromResource($resource): StreamInterface
    {
        return new Stream($resource);
    }
}
