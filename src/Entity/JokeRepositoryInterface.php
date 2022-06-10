<?php

namespace ChuckNorris\Entity;

/**
 * Репозиторий для работы с шутками
 */
interface JokeRepositoryInterface
{
    /**
     * Получение случайной шутки из конкретной категории
     *
     * @param string $category - категория
     *
     * @return Joke
     */
    public function getRandomJokeOnCategory(string $category): Joke;

    /**
     * Запись в файл шутки
     *
     * @param string $getJoke
     *
     * @return mixed
     */
    public function writeInFile(string $getJoke);
}