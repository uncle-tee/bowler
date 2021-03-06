<?php

namespace Vinelab\Bowler;

use Vinelab\Bowler\Exceptions\InvalidSubscriberBindingException;

/**
 * @author Ali Issa <ali@vinelab.com>
 * @author Kinane Domloje <kinane@vinelab.com>
 */
class RegisterQueues
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * Registrator::queue.
     *
     * @param string $queue
     * @param string $className
     * @param array  $options
     */
    public function queue($queue, $className, $options = [])
    {
        $handler = new Handler();
        $handler->queueName = $queue;
        $handler->className = $className;
        $handler->options = $options;

        array_push($this->handlers, $handler);
    }

    /**
     * Registrator::subscriber.
     * Default out-of-box Publisher/Subscriber setup.
     *
     * @param string $queue
     * @param string $className
     * @param array $bindingKeys
     * @param string $exchangeName
     * @param string $exchangeType
     * @throws InvalidSubscriberBindingException
     */
    public function subscriber($queue, $className, array $bindingKeys, $exchangeName = 'pub-sub', $exchangeType = 'topic')
    {
        if (empty($bindingKeys)) {
            throw new InvalidSubscriberBindingException('Missing bindingKeys for Subscriber queue: '.$queue.'.');
        }

        // Default pub/sub setup
        // We only need the bindingKeys to enable key based pub/sub
        $options = [
            'exchangeName' => $exchangeName,
            'exchangeType' => $exchangeType,
            'bindingKeys' => $bindingKeys,
            'passive' => false,
            'durable' => true,
            'autoDelete' => false,
        ];

        $this->queue($queue, $className, $options);
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}
