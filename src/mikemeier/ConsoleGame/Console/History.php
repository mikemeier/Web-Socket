<?php

namespace mikemeier\ConsoleGame\Console;

class History implements \Iterator
{
    /**
     * @var array
     */
    protected $history = array('');

    /**
     * @var int
     */
    protected $position = 0;

    public function __construct()
    {
        $this->position = 0;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function add($text)
    {
        $this->history[] = $text;
        $this->position = count($this->history);
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return isset($this->history[$this->position]) ? $this->history[$this->position] : false;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @param int $position
     * @return $this
     */
    public function addPosition($position)
    {
        $oldPosition = $this->position;
        $this->position += $position;
        if(!$this->valid()){
            $this->position = $oldPosition;
        }
        return $this;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return array
     */
    public function getAll()
    {
        return $this->history;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return isset($this->history[$this->position]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->position = 0;
    }
}