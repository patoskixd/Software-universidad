<?php

class Middleware
{
    public static function run(array $stack): void
    {
        foreach ($stack as $layer) {
            $layer();
        }
    }
}
