<?php

namespace App\Context\Items\Item\UI\Controller;

use App\Context\Items\Item\Application\CreateItem\CreateItemCommand;
use App\SharedKernel\Domain\Bus\Command\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Ramsey\Uuid\Uuid;

class CreateItemsController extends AbstractController
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
                'request_data' =>   $jsonData
            ], Response::HTTP_OK);
        }else{
            $item_id=Uuid::uuid4()->toString();
            $this->commandBus->dispatch(new CreateItemCommand($item_id, $jsonData['product_name'],
                $jsonData['quantity'], $jsonData['price']));

            return new JsonResponse(['message' => "Item created"], Response::HTTP_CREATED);
        }
    }

    private function validateRequest(array $fields): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection(
            [
                'product_name' => [new Assert\NotBlank([
                    'message' => 'ERROR: The field "product_name" cannot be empty.',
                ]), new Assert\Type([
                    'type' => 'string',
                    'message' => 'ERROR: The field "product_name" must be a string.',
                ]), new Assert\Length([
                    'min' => 1,
                    'max' => 255,
                    'minMessage' => 'ERROR: The field "product_name" must be at least {{ limit }} characters long.',
                    'maxMessage' => 'ERROR: The field "product_name" cannot be longer than {{ limit }} characters.',
                ])],
                'quantity' => [new Assert\NotBlank([
                    'message' => 'ERROR: The field "quantity" cannot be empty.',
                ]), new Assert\Regex([
                    'pattern' => "/^-?\d+$/",
                    'message' => 'ERROR: The value {{ value }} is not a valid integer for the field "quantity".',
                ]), new Assert\Length([
                    'min' => 0,
                    'max' => 100,
                    'maxMessage' => 'ERROR: The field "quantity" cannot exceed {{ limit }} characters.',
                ])],
                'price' => [new Assert\NotBlank([
                    'message' => 'ERROR: The field "price" cannot be empty.',
                ]), new Assert\Regex([
                    'pattern' => "/^-?\d+(\.\d+)?$/",
                    'message' => 'ERROR: The value {{ value }} is not a valid price for the field "price".',
                ]), new Assert\Length([
                    'min' => 0,
                    'max' => 100,
                    'maxMessage' => 'ERROR: The field "price" cannot exceed {{ limit }} characters.',
                ])]
            ]
        );

        return Validation::createValidator()->validate($fields, $constraint);

    }
}