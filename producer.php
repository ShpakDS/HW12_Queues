<?php

require __DIR__ . '/vendor/autoload.php';

use Pheanstalk\Pheanstalk;

$redisAof = new Redis();
$redisAof->connect('172.22.0.5', 6379);

$redisRdb = new Redis();
$redisRdb->connect('172.22.0.6', 6379);

$beanstalkd = Pheanstalk::create('172.22.0.7');

$messageCount = 1000000;

$start = microtime(true);
for ($i = 0; $i < $messageCount; $i++) {
    $message = "Message AOF #$i";
    $redisAof->rPush('queue:aof', $message);
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Redis AOF Producer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);

$start = microtime(true);
for ($i = 0; $i < $messageCount; $i++) {
    $message = "Message RDB #$i";
    $redisRdb->rPush('queue:rdb', $message);
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Redis RDB Producer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);

$start = microtime(true);
for ($i = 0; $i < $messageCount; $i++) {
    $message = "Message Beanstalkd #$i";
    $beanstalkd->useTube('default')->put($message);
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Beanstalkd Producer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);