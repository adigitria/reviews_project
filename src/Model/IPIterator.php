<?php
declare(strict_types=1);

namespace ReviewParser\Model;

use \Iterator;

class IPIterator implements Iterator
{
    private $ipList;

    /**
     * IPIterator constructor.
     *
     * @param array $ipList
     */
    public function __construct(array $ipList = [])
    {
        $this->ipList = $ipList;
    }

    public function getIp()
    {
        return $this->key();
    }

    public function getPort()
    {
        return $this->current();
    }

    public function current()
    {
        return current($this->ipList);
    }

    public function next()
    {
        return next($this->ipList);
    }

    public function key()
    {
        return key($this->ipList);
    }

    public function valid(): bool
    {
        if (key($this->ipList) === null) {
            $this->rewind();
        }

        return key($this->ipList) !== null;
    }

    public function rewind()
    {
        return reset($this->ipList);
    }

    public function count()
    {
        return count($this->ipList);
    }

    public function removePrev(): void
    {
        prev($this->ipList);
        unset($this->ipList[$this->key()]);
        $this->next();
    }
}
