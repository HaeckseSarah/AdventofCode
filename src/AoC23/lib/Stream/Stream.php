<?php

declare(strict_types=1);

namespace HaeckseSarah\AoC23\lib\Stream;

class Stream implements StreamInterface
{
    /** @var resource */
    protected $resource;

    /** @var bool */
    protected $readable = false;

    /** @var bool */
    protected $writable = false;

    /** @var bool */
    protected $seekable = false;

    /** @var bool */
    protected $pipe = false;

    /** @var string */
    protected $uri = '';

    public function __construct($resource)
    {
        if (!is_resource($resource)) {
            throw new StreamException('$resource must be a valid PHP resource');
        }

        $this->resource = $resource;

        $meta = stream_get_meta_data($this->resource);
        $mode = isset($meta['mode']) ? $meta['mode'] : 'r';

        $this->readable = $this->modeAllowReading($mode);
        $this->writable = $this->modeAllowWriting($mode);

        $this->seekable = isset($meta['seekable']) ? (bool) $meta['seekable'] : false;
        $this->uri = isset($meta['uri']) ? $meta['uri'] : '';

        $fstats = fstat($this->resource);
        if ($fstats && isset($fstats['mode'])) {
            $this->pipe = (($fstats['mode'] & 0010000) !== 0);
        }
    }

    /**
     * check if a file mode allows read access.
     */
    protected function modeAllowReading($mode): bool
    {
        return (false !== strpos($mode, '+'))
               || (false !== strpos($mode, 'r'));
    }

    /**
     * check if a file mode allows write access.
     */
    protected function modeAllowWriting($mode): bool
    {
        return (false !== strpos($mode, '+'))
               || (false !== strpos($mode, 'w'))
               || (false !== strpos($mode, 'a'))
               || (false !== strpos($mode, 'x'))
               || (false !== strpos($mode, 'c'));
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     */
    public function __toString(): string
    {
        try {
            $this->rewind();

            return $this->getContents();
        } catch (\Exception $exception) {
            return '';
        }
    }

    /**
     * Closes the stream and any underlying resources.
     */
    public function close(): void
    {
        if (isset($this->resource)) {
            $status = ($this->pipe)
                ? (-1 !== pclose($this->resource))
                : fclose($this->resource);

            if (!$status) {
                throw new StreamException('Could not close file pointer');
            }
        }

        $this->detach();
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        if (isset($this->resource)) {
            $resource = $this->resource;
            unset($this->resource);
            $this->writable = false;
            $this->readable = false;
            $this->seekable = false;

            return $resource;
        }

        return null;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null returns the size in bytes if known, or null if unknown
     */
    public function getSize(): ?int
    {
        if (!isset($this->resource)) {
            return null;
        }

        $stats = fstat($this->resource);
        if (isset($stats['size'])) {
            return $stats['size'];
        }

        return null;
    }

    /**
     * Returns the current position of the file read/write pointer.
     *
     * @return int Position of the file pointer
     *
     * @throws StreamException on error
     */
    public function tell(): int
    {
        if (!$this->writable && !$this->readable) {
            throw new StreamException('Unable to get pointer position');
        }

        $position = ftell($this->resource);

        if (false === $position) {
            throw new StreamException('Unable to get pointer position');
        }

        return $position;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     */
    public function eof(): bool
    {
        return !isset($this->resource) || feof($this->resource);
    }

    /**
     * Returns whether or not the stream is seekable.
     */
    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical to the built-in
     *                    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *                    offset bytes SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws StreamException on failure
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new StreamException('Resource is not seekable');
        }
        
        fseek($this->resource,$offset,$whence);

        return;
    }

    /**
     * Seek to the beginning of the stream.
     *
     * @throws \RuntimeException on failure
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     */
    public function isWritable(): bool
    {
        return $this->writable;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string the string that is to be written
     *
     * @return int returns the number of bytes written to the stream
     *
     * @throws \RuntimeException on failure
     */
    public function write($string): int
    {
        if (!$this->isWritable()) {
            throw new StreamException('Resource is not writable');
        }

        $result = fwrite($this->resource, $string);

        if (false === $result) {
            throw new StreamException('Unable to write to resource');
        }

        return $result;
    }

    /**
     * Returns whether or not the stream is readable.
     */
    public function isReadable(): bool
    {
        return $this->readable;
    }

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
    public function read($length): string
    {
        if (!$this->readable) {
            throw new StreamException('Resource is not readable');
        }

        if (0 === $length) {
            return '';
        }
        if ($length < 0) {
            throw new StreamException('Length must be grater than or equal 0');
        }

        $string = fread($this->resource, $length);
        if (false === $string) {
            throw new StreamException('Unable to read from resource');
        }

        return $string;
    }

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
    public function readLine($length): string|bool
    {
        if (!$this->readable) {
            throw new StreamException('Resource is not readable');
        }

        if (0 === $length) {
            return '';
        }
        if ($length < 0) {
            throw new StreamException('Length must be grater than or equal 0');
        }

        $string = fgets($this->resource, $length);

        return $string;
    }

    /**
     * Returns the remaining contents in a string.
     *
     * @throws \RuntimeException if unable to read or an error occurs while
     *                           reading
     */
    public function getContents(): string
    {
        if (!$this->readable) {
            throw new StreamException('Resource is not readable');
        }

        $content = stream_get_contents($this->resource);

        if (false === $content) {
            throw new StreamException('Unable to read resource content');
        }

        return $content;
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * @param string $key specific metadata to retrieve
     *
     * @return array|mixed|null Returns an associative array if no key is
     *                          provided. Returns a specific key value if a key is provided and the
     *                          value is found, or null if the key is not found.
     */
    public function getMetadata($key = null)
    {
        if (!isset($this->resource)) {
            return $key ? null : [];
        }

        $meta = stream_get_meta_data($this->resource);

        return (null === $key)
            ? $meta
            : (isset($meta[$key])
                ? $meta[$key]
                : null);
    }
}
