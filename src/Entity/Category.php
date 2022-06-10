<?php

namespace ChuckNorris\Entity;

/**
 * Категория
 */
class Category
{
    /**
     * Категория
     *
     * @var string
     */
    private string $name;

    /**
     * @param string $name - название категории
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}