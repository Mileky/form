<?php

namespace ChuckNorris\Repository;

use ChuckNorris\Entity\Category;
use ChuckNorris\Entity\Joke;
use ChuckNorris\Entity\JokeRepositoryInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientInterface;
use RuntimeException;

/**
 * Репозиторий для работы с шутками через API
 */
class JokeRepository implements JokeRepositoryInterface
{
    /**
     * Http клиент
     *
     * @var ClientInterface
     */
    private ClientInterface $client;

    /**
     * @param ClientInterface $client - http-клиент
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @inheritDoc
     *
     * @throws \JsonException
     */
    public function getRandomJokeOnCategory(string $category): Joke
    {
        try {
            $response = $this->client->request(
                'GET',
                'jokes/random',
                ['query' => "limitTo=$category"]);
        } catch (GuzzleException $e) {
            throw new RuntimeException('При запросе к API произошла ошибка ' . $e);
        }

        $dataForCreate = json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $a = 0;
        return $this->createEntity($dataForCreate['value']);
    }

    /**
     * Создание сущности Шутка на основе данных полученных от API
     *
     * @param array $dataForCreate - данные для создания
     *
     * @return Joke
     */
    private function createEntity(array $dataForCreate): Joke
    {
        return new Joke(
            $dataForCreate['id'],
            $dataForCreate['joke'],
            new Category(current($dataForCreate['categories']))
        );
    }

    public function writeInFile(string $joke): void
    {
        file_put_contents(__DIR__ . '/../../data/joke.txt', $joke . PHP_EOL, FILE_APPEND);
    }


}