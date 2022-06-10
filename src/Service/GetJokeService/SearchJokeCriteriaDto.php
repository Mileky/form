<?php

namespace ChuckNorris\Service\GetJokeService;

/**
 * Критерии поиска шутки
 */
class SearchJokeCriteriaDto
{
    /**
     * Категория шутки
     *
     * @var string
     */
    private string $category;

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category - категория шутки
     *
     * @return SearchJokeCriteriaDto
     */
    public function setCategory(string $category): SearchJokeCriteriaDto
    {
        $this->category = $category;
        return $this;
    }
}