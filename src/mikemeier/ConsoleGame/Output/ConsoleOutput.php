<?php

namespace mikemeier\ConsoleGame\Output;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutput implements OutputInterface
{
    /**
     * @var Console
     */
    protected $console;

    /**
     * @param Console $console
     */
    public function __construct(Console $console)
    {
        $this->console = $console;
    }

    /**
     * Writes a message to the output.
     *
     * @param string|array $messages The message as an array of lines or a single string
     * @param Boolean $newline  Whether to add a newline
     * @param integer $type     The type of output (one of the OUTPUT constants)
     *
     * @throws \InvalidArgumentException When unknown output type is given
     *
     * @api
     */
    public function write($messages, $newline = false, $type = self::OUTPUT_NORMAL)
    {
        $messages = is_array($messages) ? $messages : array($messages);
        foreach($messages as $message){
            $this->console->write($message);
        }
    }

    /**
     * Writes a message to the output and adds a newline at the end.
     *
     * @param string|array $messages The message as an array of lines of a single string
     * @param integer $type     The type of output (one of the OUTPUT constants)
     *
     * @throws \InvalidArgumentException When unknown output type is given
     *
     * @api
     */
    public function writeln($messages, $type = self::OUTPUT_NORMAL)
    {
        $this->write($messages);
    }

    /**
     * Sets the verbosity of the output.
     *
     * @param integer $level The level of verbosity (one of the VERBOSITY constants)
     *
     * @api
     */
    public function setVerbosity($level)
    {
        // TODO: Implement setVerbosity() method.
    }

    /**
     * Gets the current verbosity of the output.
     *
     * @return integer The current level of verbosity (one of the VERBOSITY constants)
     *
     * @api
     */
    public function getVerbosity()
    {
        // TODO: Implement getVerbosity() method.
    }

    /**
     * Sets the decorated flag.
     *
     * @param Boolean $decorated Whether to decorate the messages
     *
     * @api
     */
    public function setDecorated($decorated)
    {
        // TODO: Implement setDecorated() method.
    }

    /**
     * Gets the decorated flag.
     *
     * @return Boolean true if the output will decorate messages, false otherwise
     *
     * @api
     */
    public function isDecorated()
    {
        // TODO: Implement isDecorated() method.
    }

    /**
     * Sets output formatter.
     *
     * @param OutputFormatterInterface $formatter
     *
     * @api
     */
    public function setFormatter(OutputFormatterInterface $formatter)
    {
        // TODO: Implement setFormatter() method.
    }

    /**
     * Returns current output formatter instance.
     *
     * @return  OutputFormatterInterface
     *
     * @api
     */
    public function getFormatter()
    {
        return null;
    }
}