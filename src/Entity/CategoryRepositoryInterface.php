<?php

namespace ChuckNorris\Entity;

/**
 * Репозиторий для работы с категориями
 */
interface CategoryRepositoryInterface
{
    /**
     * Получение категорий
     *
     * @return Category[]
     */
    public function getCategories(): array;
}