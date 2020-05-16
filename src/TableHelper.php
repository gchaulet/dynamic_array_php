<?php

namespace App;

class TableHelper {
    const SORT_KEY = 'sort';
    const DIR_KEY = 'dir';

    public static function sort(string $sortKey, string $label, array $data): string
    {
        $sort = $data[self::SORT_KEY] ?? null;
        $direction = $data [self::DIR_KEY] ?? null;
        $url = URLHelper::withParams($data, ['sort' => $sortKey, 'dir' => 'asc']);
        return <<<HTML
        <a href="?$url">$label</a>
        HTML;
    }
}