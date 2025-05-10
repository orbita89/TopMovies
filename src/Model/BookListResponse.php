<?php

namespace App\Model;

// отвечает за ответ который получит клиент

class BookListResponse
{
    /**
     * @param BookListItem[] $items
     */
    private array $items;

    /**
     * @param BookListItem[] $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
