<?php

namespace App\Service;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Queue;


class RabbitMQPublisher{
    public function publish(array $data, string $key, string $exchange = 'patients_events'){
        $connection = Queue::connection('rabbitmq');
        $channel = $connection->getChannel();

        $message = new AMQPMessage(
            json_encode(['patient_id' => $data['id'], 'description' => '']),
            ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
        );

        $channel->basic_publish($message, $exchange, $key);
    }
}