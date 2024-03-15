<?php

namespace backend\components;

use yii\base\Component;
use yii\base\Event;
use yii\helpers\Console;

/**
 * Компонент очереди noty сообщений
 */
final class NotyComponent extends Component
{
    /** @var string Event after add message */
    public const EVENT_AFTER_ADD = 'afterAdd';

    /** @var string Простые, совсем неважные сообщения */
    public const SIMPLE  = 'simple';
    /** @var string Критические ошибки */
    public const ERROR   = 'error';
    /** @var string Предупреждения */
    public const WARNING = 'warning';
    /** @var string Информация */
    public const INFO    = 'info';
    /** @var string Сообщения об успешном выполнении */
    public const SUCCESS = 'success';


    /**
     * @var array
     */
    private $messages = [];


    /**
     * @return array Все сообщения
     */
    public function getAllMessages() : array
    {
        return $this->messages;
    }

    /**
     * @return array Простые, совсем неважные сообщения
     */
    public function getSimple() : array
    {
        return $this->messages[self::SIMPLE] ?? [];
    }

    /**
     * @return array Критические ошибки
     */
    public function getError() : array
    {
        return $this->messages[self::ERROR] ?? [];
    }

    /**
     * @return array Предупреждения
     */
    public function getWarning() : array
    {
        return $this->messages[self::WARNING] ?? [];
    }

    /**
     * @return array Информация
     */
    public function getInfo() : array
    {
        return $this->messages[self::INFO] ?? [];
    }

    /**
     * @return array Сообщения об успешном выполнении
     */
    public function getSuccess() : array
    {
        return $this->messages[self::SUCCESS] ?? [];
    }

    /**
     * @return string
     */
    public function getSuccessToString() : string
    {
        $string = '';
        if (isset($this->messages[self::SUCCESS])) {
            foreach ($this->messages[self::SUCCESS] as $message) {
                $string .= $message . PHP_EOL;
            }
        }
        return $string;
    }

    /**
     * Add simple message
     * @param string $message
     */
    public function simple(string $message) : void
    {
        $this->messages[self::SIMPLE][] = $message;
        $this->createEvent(self::SIMPLE, $this->getSimple());
    }

    /**
     * Add error message
     * @param string $message
     */
    public function error(string $message) : void
    {
        $this->messages[self::ERROR][] = $message;
        $this->createEvent(self::ERROR, $this->getError());
    }

    /**
     * Add warning message
     * @param string $message
     */
    public function warning(string $message) : void
    {
        $this->messages[self::WARNING][] = $message;
        $this->createEvent(self::WARNING, $this->getWarning());
    }

    /**
     * Add info message
     * @param string $message
     */
    public function info(string $message) : void
    {
        $this->messages[self::INFO][] = $message;
        $this->createEvent(self::INFO, $this->getInfo());
    }

    /**
     * Add success message
     * @param string $message
     */
    public function success(string $message) : void
    {
        $this->messages[self::SUCCESS][] = $message;
        $this->createEvent(self::SUCCESS, $this->getSuccess());
    }

    /**
     * Create and trigger event
     * @param $type
     * @param $message
     */
    private function createEvent($type, $message) : void
    {
        $this->trigger(self::EVENT_AFTER_ADD, new Event([
            'sender' => (object) [
                'type' => $type,
                'message' => $message
            ]
        ]));
    }

    /**
     * Stdout in Console application
     */
    public function stdout() : void
    {
        foreach ($this->messages as $type => $messages) {
            foreach ($messages as $message) {
                Console::output($type . ': ' . $message);
            }
        }
        $this->clear();
    }

    /**
     * Clear all messages
     */
    public function clear() : void
    {
        $this->messages = [];
    }
}
