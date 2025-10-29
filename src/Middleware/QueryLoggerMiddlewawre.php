<?php

namespace MyApi\Middleware;

use PDOStatement;
use MyApi\App;

class QueryLoggerMiddleware extends PDOStatement
{
    protected App $app;

    protected function __construct(App $app)
    {
        $this->app = $app;
    }

    public function execute($input_parameters = null): bool
    {
        $start = microtime(true);
        $result = parent::execute($input_parameters);
        $time = microtime(true) - $start;

        if ($time > 0.5) { // 500 ms threshold
            $this->app->logger->warning('Slow SQL Query', [
                'sql' => $this->queryString,
                'time' => round($time, 3) . 's'
            ]);
        }

        return $result;
    }
}
