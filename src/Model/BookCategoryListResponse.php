<?php

namespace App\Model;

// отвечает за ответ который получит клиент

class BookCategoryListResponse
{

    /**
     * @param  BookCategory[]  $items
     */
    private array $items;


    /**
     * @param  BookCategory[]  $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}
