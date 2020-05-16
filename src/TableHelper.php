<?php

namespace App;

class TableHelper {
    const SORT_KEY = 'sort';
    const DIR_KEY = 'dir';

    public static function sort(string $sortKey, string $label, array $data): string
    {

        $sort = $data[self::SORT_KEY] ?? null;
        $direction = $data [self::DIR_KEY] ?? null;
        $icon ="";
        //show sort in active title
        if($sort === $sortKey){
            $icon =  $direction === 'asc' ? "^" : 'v';
        }
        //show direction in url and initialize sort in active title only
        $url = URLHelper::withParams($data, [
            'sort' => $sortKey, 
            'dir' => $direction === 'asc' && $sort === $sortKey ? "desc" : 'asc'
        ]);
        return <<<HTML
        <a href="?$url">$label $icon</a>
        HTML;
    }
}