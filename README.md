# HW12_Queues

`Set up 3 containers - beanstalkd and redis (rdb and aof).`

## Setup

1) Run command ```docker compose up -d```
2) Execute a command in a running container  ```docker-compose exec php bash```
3) Run ```php producer.php``` and ```php consumer.php```

## Results

````
root@06cbd666d4b6:/app# php producer.php
Redis AOF Producer throughput - 1000000 messages / 63.69 seconds = 15702.01 req/sec
Redis RDB Producer throughput - 1000000 messages / 60.65 seconds = 16489.11 req/sec
Beanstalkd Producer throughput - 1000000 messages / 53.71 seconds = 18617.98 req/sec

root@06cbd666d4b6:/app# php consumer.php 
Redis AOF Consumer throughput - 1000000 messages / 64.01 seconds = 15623.03 req/sec
Redis RDB Consumer throughput - 1000000 messages / 61.60 seconds = 16232.99 req/sec
Beanstalkd Consumer throughput - 1000000 messages / 107.12 seconds = 9335.09 req/sec
````