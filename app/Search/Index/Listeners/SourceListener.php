<?php

namespace App\Search\Index\Listeners;

use App\Search\Index\Interfaces\SourceInterface;
use JsonStreamingParser\Listener\ListenerInterface;

class SourceListener implements ListenerInterface
{
    protected $stack;
    protected $key;
    protected $level;
    protected $counter;
    protected $batchStack;
    protected $batchSize = 3;
    protected $total = 0;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback = null)
    {
        $this->callback = $callback;
    }

    public function startDocument(): void
    {
        $this->stack = [];
        $this->level = 0;
        // Key is an array so that we can can remember keys per level to avoid
        // it being reset when processing child keys.
        $this->key = [];
    }

    public function endDocument(): void
    {
        if (\is_callable($this->callback) && !empty($this->batchStack)) {
            \call_user_func($this->callback, $this->batchStack);
            $this->counter = 0;
            $this->batchStack = [];
        }
    }

    public function startObject(): void
    {
        ++$this->level;
        $this->stack[] = [];

        // Reset the stack when entering the second level
        if ($this->level === 1) {

            $this->stack = [];
            $this->key[$this->level] = null;
        }
    }

    public function endObject(): void
    {
        --$this->level;
        $obj = array_pop($this->stack);
        if (empty($this->stack)) {
            $this->counter++;
            if($obj) {
                $this->batchStack[] = $obj;
                $this->total++;
            }
        } else {
            $this->value($obj);
        }
        if ($this->level === 1 && \is_callable($this->callback) && $this->counter == $this->batchSize && !empty($this->batchStack)) {
            \call_user_func($this->callback, $this->batchStack);
            $this->counter = 0;
            $this->batchStack = [];
        }
    }

    public function startArray(): void
    {
        $this->startObject();
    }

    public function endArray(): void
    {
        $this->endObject();
    }

    public function key(string $key): void
    {
        $this->key[$this->level] = $key;
    }

    /**
     * @param mixed $value Value may be a string, integer, boolean, null.
     */
    public function value($value): void
    {
        $obj = array_pop($this->stack);
        if (!empty($this->key[$this->level])) {
            $obj[$this->key[$this->level]] = $value;
            $this->key[$this->level] = null;
        } else {
            $obj[] = $value;
        }
        $this->stack[] = $obj;
    }

    public function whitespace(string $whitespace): void
    {
    }

    /**
     * @param int $batchSize
     */
    public function setBatchSize(int $batchSize): void
    {
        $this->batchSize = $batchSize;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
}