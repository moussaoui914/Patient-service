<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;

class RabbitMQSetupBinding extends Command {
    protected $signature = 'rabbitmq:setup-bindings';
    protected $description = 'Declare the notification_service_queue and bind it to the auth_events topic exchange';

    public $connection;
    public function handle() {

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST', 'rabbit'),
            (int) env('RABBITMQ_PORT', 5672),
            env('RABBITMQ_LOGIN', 'guest'),
            env('RABBITMQ_PASSWORD', 'guest'),
            env('RABBITMQ_VHOST', '/'),
        );

        $channel = $connection->channel();

        $exchange = 'patients_events';
        $queue = 'patients_events_queue';
        
        $channel->exchange_declare($exchange, AMQPExchangeType::TOPIC, false, true, false);
        $this->info('patients_evants exchange have been created if not already');
        
        $channel->queue_declare($queue, false, true, false, false);
        $this->info('patients_evants_queue have been created if not already');

        $routingKeys = ['patient.created', 'patient.deleted'];

        foreach($routingKeys as $key){
            $channel->queue_bind($queue, $exchange,  $key);
            $this->info("bound routing key: {$key} to the {$queue}");
        }

        $channel->close();
        $connection->close();

        $this->info('RabbitMQ setup completed');
        return self::SUCCESS;
    }
}
