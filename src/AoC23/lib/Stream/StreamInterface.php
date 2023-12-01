<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Stream;

interface StreamInterface extends \Psr\Http\Message\StreamInterface
{
    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if underlying stream
     *                    call returns fewer bytes.
     *
     * @return string returns the data read from the stream, or an empty string
     *                if no bytes are available
     *
     * @throws \RuntimeException if an error occurs
     */
    public function readLine($length): string|bool;
}
