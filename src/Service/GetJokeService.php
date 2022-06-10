<?php

namespace ChuckNorris\Service;

use ChuckNorris\Entity\JokeRepositoryInterface;
use ChuckNorris\Service\GetJokeService\ResultSearchJokeDto;
use ChuckNorris\Service\GetJokeService\SearchJokeCriteriaDto;

/**
 * Сервис получения шутки
 */
class GetJokeService
{
    /**
     * Репозиторий для работы с шутками
     *
     * @var JokeRepositoryInterface
     */
    private JokeRepositoryInterface $jokeRepository;

    /**
     * @param JokeRepositoryInterface $jokeRepository
     */
    public function __construct(JokeRepositoryInterface $jokeRepository)
    {
        $this->jokeRepository = $jokeRepository;
    }

    public function get(SearchJokeCriteriaDto $criteriaDto): ResultSearchJokeDto
    {
        $joke = $this->jokeRepository->getRandomJokeOnCategory($criteriaDto->getCategory());

        return new ResultSearchJokeDto($joke->getJoke(), $joke->getCategory()->getName());
    }
}