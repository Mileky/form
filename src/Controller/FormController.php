<?php

namespace ChuckNorris\Controller;

use ChuckNorris\Service\GetCategoryService;
use ChuckNorris\Service\GetJokeService;
use ChuckNorris\Service\SendingJokeService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Обработка главной формы
 */
class FormController extends AbstractController
{
    /**
     * Сервис для получения категорий шуток
     *
     * @var GetCategoryService
     */
    private GetCategoryService $categoryService;

    /**
     * Сервис получения шутки
     *
     * @var GetJokeService
     */
    private GetJokeService $getJokeService;

    /**
     * Сервис отправки сообщения на почту
     *
     * @var SendingJokeService
     */
    private SendingJokeService $sendingJokeService;

    /**
     * Сервис валидации
     *
     * @var ValidatorInterface
     */
    private ValidatorInterface $validator;

    /**
     * @param GetCategoryService $categoryService    - Сервис для получения категорий шуток
     * @param GetJokeService     $getJokeService     - Сервис получения шутки
     * @param SendingJokeService $sendingJokeService - Сервис отправки сообщения на почту
     * @param ValidatorInterface $validator          - Сервис валидации
     */
    public function __construct(
        GetCategoryService $categoryService,
        GetJokeService $getJokeService,
        SendingJokeService $sendingJokeService,
        ValidatorInterface $validator
    )
    {
        $this->categoryService = $categoryService;
        $this->getJokeService = $getJokeService;
        $this->sendingJokeService = $sendingJokeService;
        $this->validator = $validator;
    }

    /**
     * Обработка запроса
     *
     * @param Request $request - запрос
     *
     * @return Response
     */
    public function main(Request $request): Response
    {
        try {
            $resultSendJoke = [];

            if ('POST' === $request->getMethod()) {
                $resultSendJoke = $this->sendJoke($request);
            }

            $categories = $this->categoryService->get();

            $template = 'form.twig';

            $viewData = [
                'categories' => $categories
            ];

            $context = array_merge($viewData, $resultSendJoke);

            $httpCode = isset($resultSendJoke['errors']) ? 400 : 200;
        } catch (Throwable $e) {
            $httpCode = 500;
            $template = 'errors.twig';
            $context = ['errors' => [$e->getMessage()]];
        }

        $response = $this->render($template, $context);

        $response->setStatusCode($httpCode);

        return $response;
    }

    /**
     * Обработка отправки шутки на почту
     *
     * @param Request $request
     *
     * @return array
     * @throws TransportExceptionInterface
     */
    private function sendJoke(Request $request): array
    {
        $result = [];

        $queryParams = $request->request->all();

        $validationResult = $this->validateParams($queryParams);

        if (0 === count($validationResult)) {

            $joke = $this->getJokeService->get(
                (new GetJokeService\SearchJokeCriteriaDto())->setCategory($queryParams['category'])
            );

            $this->sendingJokeService->send($queryParams, $joke);

            $result['message'] = 'Сообщение успешно отправлено';
        } else {
            $result = ['errors' => $validationResult];
        }

        return $result;
    }

    /**
     * Валидация данных пришедших от клиента
     *
     * @param array $queryParams
     *
     * @return array
     * @throws Exception
     */
    private function validateParams(array $queryParams): array
    {
        $constraints = [
            new Assert\Type(['type' => 'array', 'message' => 'Данные должны быть массивом']),
            new Assert\Collection([
                'allowExtraFields'     => false,
                'allowMissingFields'   => false,
                'missingFieldsMessage' => 'Отсутствует обязательное поле: {{ field }}',
                'extraFieldsMessage'   => 'Есть лишние поля: {{ field }}',
                'fields'               => [
                    'email'    => [
                        new Assert\Type(['type' => 'string', 'message' => 'Данные о почте должны быть строкой']),
                        new Assert\Email(['message' => 'Некорректные данные об электронной почте'])
                    ],
                    'category' => [
                        new Assert\Type(['type' => 'string', 'message' => 'Данные о категории должны быть строкой'])
                    ]
                ]
            ])
        ];

        $err = $this->validator->validate($queryParams, $constraints);

        return array_map(static function ($v) {
            return $v->getMessage();
        }, $err->getIterator()->getArrayCopy());
    }
}