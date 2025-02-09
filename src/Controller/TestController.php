<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/v1')]
class TestController extends AbstractController
{

    public const USERS_DATA = [
        [
            'id'    => '1',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '2',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '3',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '4',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '5',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '6',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
        [
            'id'    => '7',
            'email' => 'vt231_dmyu@student.ztu.edu.ua',
            'name'  => 'Makar'
        ],
    ];

    #[Route('/users', name: 'app_collection_users', methods: ['GET'])]
    #[IsGranted("ROLE_ADMIN")]
    public function getCollection(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::USERS_DATA
        ], Response::HTTP_OK);
    }

    #[Route('/users/{id}', name: 'app_item_users', methods: ['GET'])]
    public function getItem(string $id): JsonResponse
    {
        $userData = $this->findUserById($id);

        return new JsonResponse([
            'data' => $userData
        ], Response::HTTP_OK);
    }

    #[Route('/users', name: 'app_create_users', methods: ['POST'])]
    #[IsGranted("ROLE_ADMIN")]
    public function createItem(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['email'], $requestData['name'])) {
            throw new UnprocessableEntityHttpException("name and email are required");
        }

        // TODO check by regex

        $countOfUsers = count(self::USERS_DATA);

        $newUser = [
            'id'    => $countOfUsers + 1,
            'name'  => $requestData['name'],
            'email' => $requestData['email']
        ];

        // TODO add new user to collection

        return new JsonResponse([
            'data' => $newUser
        ], Response::HTTP_CREATED);
    }

    #[Route('/users/{id}', name: 'app_delete_users', methods: ['DELETE'])]
    #[IsGranted("ROLE_ADMIN")]
    public function deleteItem(string $id): JsonResponse
    {
        $this->findUserById($id);

        // TODO remove user from collection

        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

    #[Route('/users/{id}', name: 'app_update_users', methods: ['PATCH'])]
    #[IsGranted("ROLE_ADMIN")]
    public function updateItem(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        if (!isset($requestData['name'])) {
            throw new UnprocessableEntityHttpException("name is required");
        }

        $userData = $this->findUserById($id);

        // TODO update user name

        $userData['name'] = $requestData['name'];

        return new JsonResponse(['data' => $userData], Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return string[]
     */
    public function findUserById(string $id): array
    {
        $userData = null;

        foreach (self::USERS_DATA as $user) {
            if (!isset($user['id'])) {
                continue;
            }

            if ($user['id'] == $id) {
                $userData = $user;

                break;
            }

        }

        if (!$userData) {
            throw new NotFoundHttpException("User with id " . $id . " not found");
        }

        return $userData;
    }

}
