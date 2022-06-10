<?php

namespace ChuckNorris\Service\GetJokeService;

/**
 * Результат поиска шутки
 */
class ResultSearchJokeDto
{
    /**
     * Текст шутки
     *
     * @var string
     */
    private string $joke;

    /**
     * Категория шутки
     *
     * @var string
     */
    private string $category;

    /**
     * @param string $joke - шутка
     * @param string  $category - категории
     */
    public function __construct(string $joke, string $category)
    {
        $this->joke = $joke;
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getJoke(): string
    {
        return $this->joke;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
}