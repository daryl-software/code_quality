<?php


class Git
{
    public static function diffLineChanged(string $diff, int $extend = 0): array
    {
        preg_match_all("/^@@ [+-](\d+),(\d+) [+-](\d+),(\d+) @@/m", $diff, $matches, PREG_SET_ORDER);
        $ranges = [];
        foreach ($matches as $match) {
            $start = min($match[1], $match[3]) - $extend;
            $end = max($match[2], $match[4]) + $extend;
            $ranges = array_merge($ranges, range($start, $start + $end));
        }
        return $ranges;
    }
}