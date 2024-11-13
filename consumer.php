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
    $redisAof->lPop('queue:aof');
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Redis AOF Consumer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);

$start = microtime(true);
for ($i = 0; $i < $messageCount; $i++) {
    $redisRdb->lPop('queue:rdb');
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Redis RDB Consumer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);

$start = microtime(true);
for ($i = 0; $i < $messageCount; $i++) {
    $job = $beanstalkd->watch('default')->reserve();
    $beanstalkd->delete($job);
}
$duration = microtime(true) - $start;
$throughput = $messageCount / $duration;
printf("Beanstalkd Consumer throughput - %d messages / %.2f seconds = %.2f req/sec\n", $messageCount, $duration, $throughput);