<?php

namespace ChuckNorris\Entity;

/**
 * Шутка
 */
class Joke
{
    /**
     * Id
     *
     * @var int
     */
    private int $id;

    /**
     * Сама шутка
     *
     * @var string
     */
    private string $joke;

    /**
     * Категории шутки
     *
     * @var Category
     */
    private Category $category;

    /**
     * @param int        $id
     * @param string     $joke
     * @param Category $category
     */
    public function __construct(int $id, string $joke, Category $category)
    {
        $this->id = $id;
        $this->joke = $joke;
        $this->category = $category;
    }

    /**
     * Возвращает id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Возвращает шутку
     *
     * @return string
     */
    public function getJoke(): string
    {
        return $this->joke;
    }

    /**
     * Возвращает категории
     *
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }
}