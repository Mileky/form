<?php

namespace ChuckNorris\Service;

use ChuckNorris\Entity\CategoryRepositoryInterface;
use ChuckNorris\Service\GetCategoryService\ResultCategoryDto;

/**
 * Сервис получения категорий
 */
class GetCategoryService
{
    /**
     * Репозиторий для работы с категориями
     *
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @param CategoryRepositoryInterface $categoryRepository - Репозиторий для работы с категориями
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Получение категорий
     *
     * @return ResultCategoryDto[]
     */
    public function get(): array
    {
        $entities = $this->categoryRepository->getCategories();

        $dtoCollection = [];

        foreach ($entities as $entity) {
            $dtoCollection[] = new ResultCategoryDto($entity->getName());
        }

        return $dtoCollection;

    }
}