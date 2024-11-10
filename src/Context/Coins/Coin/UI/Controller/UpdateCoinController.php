<?php

namespace App\Context\Coins\Coin\UI\Controller;

use App\Context\Coins\Coin\Application\UpdateCoin\UpdateCoinCommand;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class UpdateCoinController extends AbstractController
{
    public function __construct(private readonly CommandBus $commandBus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $jsonData = json_decode($request->getContent(), true);
        if($jsonData === null) {
            $jsonData= $request->request->all();
        }
        $validationErrors = $this->validateRequest($jsonData);
        $messages=array();

        if($validationErrors->count()){
            foreach ($validationErrors as $violation) {
                $field = $violation->getPropertyPath();
                $messages[] = sprintf('Field "%s": %s', $field, $violation->getMessage());
            }
            return new JsonResponse([
                'errors' => $messages,
                'request_data' => [
                    'json_body' => $jsonData,
                ]
            ], Response::HTTP_OK);
        }else{

            $this->commandBus->dispatch(new UpdateCoinCommand($jsonData['coin_id'],
                $jsonData['quantity'], $jsonData['coin_value'], $jsonData['valid_for_change']));

            return new JsonResponse(['message' => "Coin updated"], Response::HTTP_CREATED);
        }
    }

    private function validateRequest(array $fields): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'coin_id' =>  [new Assert\NotBlank(), new Assert\Type('string'),  new Assert\Length(['min' => 1, 'max' => 255])],
                'quantity' => [new Assert\NotBlank(), new Assert\Regex([
                    'pattern' => "/^-?\d+$/",
                    'message' => 'ERROR: The value {{ value }} is not a valid interger.',
                ])],
                'valid_for_change' => [new Assert\NotBlank(),   new Assert\Regex([
                    'pattern' => "/^-?\d+$/",
                    'message' => 'ERROR: The value {{ value }} is not a valid boolean value.',
                ]), new Assert\Length(['min' => 0, 'max' => 100])],
                'coin_value' => [new Assert\NotBlank(),   new Assert\Regex([
                    'pattern' => "/^-?\d+(\.\d+)?$/",
                    'message' => 'ERROR: The value {{ value }} is not a valid coin value.',
                ]), new Assert\Length(['min' => 0, 'max' => 100])]

            ]);

        return Validation::createValidator()->validate($fields, $constraint);

    }
}
