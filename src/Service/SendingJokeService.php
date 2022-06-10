<?php

namespace ChuckNorris\Service;

use ChuckNorris\Entity\JokeRepositoryInterface;
use ChuckNorris\Service\GetJokeService\ResultSearchJokeDto;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * Отправка шутки на почту и сохранение её в файл
 */
class SendingJokeService
{
    /**
     * Почтовая программа
     *
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * Репозиторий для работы с шутками
     *
     * @var JokeRepositoryInterface
     */
    private JokeRepositoryInterface $jokeRepository;

    /**
     * @param MailerInterface         $mailer - Почтовая программа
     * @param JokeRepositoryInterface $jokeRepository - Репозиторий для работы с шутками
     */
    public function __construct(MailerInterface $mailer, JokeRepositoryInterface $jokeRepository)
    {
        $this->mailer = $mailer;
        $this->jokeRepository = $jokeRepository;
    }

    /**
     * Отправка сообщения на почту
     *
     * @param array               $params - массив с данными для отправки
     * @param ResultSearchJokeDto $joke   - шутка для отправка
     *
     * @return void
     * @throws TransportExceptionInterface
     */
    public function send(array $params, ResultSearchJokeDto $joke): void
    {
        $message = (new Email())
            ->from('mellowaxx@gmail.com')
            ->to($params['email'])
            ->subject("Случайная шутка из {$params['category']}")
            ->text($joke->getJoke());

        $this->mailer->send($message);

        $this->jokeRepository->writeInFile($joke->getJoke());
    }
}