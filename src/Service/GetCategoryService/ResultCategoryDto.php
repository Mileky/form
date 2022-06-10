<?php

namespace ChuckNorris\Service\GetCategoryService;

/**
 * ДТО результата получения категорий
 */
class ResultCategoryDto
{
    private string $category;

    /**
     * @param string $category
     */
    public function __construct(string $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }
}