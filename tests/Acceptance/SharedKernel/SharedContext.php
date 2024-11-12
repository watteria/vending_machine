<?php

namespace App\Tests\Acceptance\SharedKernel;

use App\Context\Coins\Coin\Domain\Coin;
use App\Context\Coins\Coin\Domain\ValueObject\CoinId;
use App\Context\Coins\Coin\Domain\ValueObject\CoinQuantity;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValidForChange;
use App\Context\Coins\Coin\Domain\ValueObject\CoinValue;
use App\Context\Customers\Customer\Domain\Customer;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerId;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerInsertedMoney;
use App\Context\Customers\Customer\Domain\ValueObject\CustomerStatus;
use App\Context\Items\Item\Domain\Item;
use App\Context\Items\Item\Domain\ValueObject\ItemId;
use App\Context\Items\Item\Domain\ValueObject\ItemPrice;
use App\Context\Items\Item\Domain\ValueObject\ItemProductName;
use App\Context\Items\Item\Domain\ValueObject\ItemQuantity;
use App\Tests\Unit\Coins\Coin\Domain\CoinMother;
use App\Tests\Unit\Items\Item\Domain\ItemMother;
use App\Tests\Unit\SharedKernel\Domain\Mothers\UuidMother;
use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Assert;
use SebastianBergmann\Diff\Differ;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Context\Items\Item\Infrastructure\Persistence\Doctrine\Fixture\ItemFixture;

final class SharedContext implements Context
{
    private ?Response $response; 
    private KernelBrowser $client;
    private EntityManagerInterface $entityManager;
    private array $itemData;


    public function __construct(KernelInterface $kernel, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $client = $kernel->getContainer()->get('test.client');

        if (!$client instanceof KernelBrowser) {
            throw new \RuntimeException('The test client could not be initialized as KernelBrowser.');
        }

        $this->client = $client;

        $this->client->setServerParameter('CONTENT_TYPE', 'application/json');
        $this->client->setServerParameter('HTTP_ACCEPT', 'application/json');
    }




    /**
     * Helper method to decode JSON response
     */
    private function getDecodedResponse(): array
    {
        return json_decode($this->response->getContent(), true);
    }


    /**
     * @Given I have the following JSON itemData:
     */
    public function iHaveTheFollowingJsonItemdata(string $json)
    {
        $decodedData = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON provided: " . json_last_error_msg());
        }

        $this->itemData = $decodedData ?? [];
    }


    /**
     * @Given the database contains this item:
     */
    public function theDatabaseContainsThisItem(string $json)
    {
        $data = json_decode($json, true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON provided");
        }
        $this->entityManager->createQuery('DELETE FROM App\Context\Items\Item\Domain\Item')->execute();
        $item = new Item(
            new ItemId($data['item_id']),
            new ItemProductName($data['product_name']),
            new ItemQuantity($data['quantity']),
            new ItemPrice($data['price'])
        );
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }


    /**
     * @Given the database contains multiple items
     */
    public function theDatabaseContainsMultipleItems()
    {
        $loader = new Loader();

        $this->entityManager->createQuery('DELETE FROM App\Context\Items\Item\Domain\Item')->execute();
        $numberOfItems = 5;
        for ($i = 0; $i < $numberOfItems; $i++) {
            $item = ItemMother::create();
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();
    }



    /**
     * @Given the database contains this coins:
     */
    public function theDatabaseContainsThisCoins(string $json)
    {
        $data = json_decode($json, true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON provided");
        }

        $this->entityManager->createQuery('DELETE FROM App\Context\Coins\Coin\Domain\Coin')->execute();

        foreach ($data as $data_item) {
            $coin = new Coin(
                new CoinId($data_item['coin_id']),
                new CoinQuantity($data_item['quantity']),
                new CoinValue($data_item['coin_value']),
                new CoinValidForChange((bool)$data_item['valid_for_change'])
            );

            $this->entityManager->persist($coin);
        }


        $this->entityManager->flush();
    }


    /**
     * @Given the database contains multiple coins
     */
    public function theDatabaseContainsMultipleCoins()
    {
        $loader = new Loader();
        $this->entityManager->createQuery('DELETE FROM App\Context\Coins\Coin\Domain\Coin')->execute();
        $numberOfCoins = 5; //
        for ($i = 0; $i < $numberOfCoins; $i++) {
            $item = CoinMother::create();
            $this->entityManager->persist($item);
        }

        $this->entityManager->flush();
    }

    /**
     * @Given the database contains this customer:
     */
    public function theDatabaseContainsThisCustomer(string $json)
    {
        $data = json_decode($json, true);
        if ($data === null) {
            throw new \InvalidArgumentException("Invalid JSON provided");
        }
        $this->entityManager->createQuery('DELETE FROM App\Context\Customers\Customer\Domain\Customer')->execute();

        if(!isset($data['customer_id'])){
            $data['customer_id']=UuidMother::create();
        }
        $customer = new Customer(
            new CustomerId($data['customer_id']),
            new ItemId($data['id_product']),
            new CustomerInsertedMoney($data['inserted_money']),
            new CustomerStatus($data['status']),
            $data['remaining_machine_coins']
        );

        $this->entityManager->persist($customer);
        $this->entityManager->flush();

    }

    /**
     * @When I send a GET request to :url
     */
    public function iSendAGetRequestTo($url)
    {
        $this->client->request('GET', $url);
        $this->response = $this->client->getResponse();
    }


    /**
     * @When I send a GET request to :arg1 with query parameters in JSON:
     */
    public function iSendAGetRequestToWithJsonQueryParametersInJson($url, PyStringNode $jsonParams)
    {

        $queryParamsArray = json_decode($jsonParams->getRaw(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON format in feature file: " . json_last_error_msg());
        }


        $fullUrl = $url . '?' . http_build_query($queryParamsArray);


        $this->client->request('GET', $fullUrl);
        $this->response = $this->client->getResponse();
    }

    /**
     * @When I send a POST request to :url
     */
    public function iSendAPostRequestTo($url)
    {
        if (!isset($this->itemData)) {
            throw new \Exception("itemData no está inicializado.");
        }
        $this->client->request('POST', $url, [], [], [], json_encode($this->itemData));
        $this->response = $this->client->getResponse();
    }

    /**
     * @When I send a DELETE request to :url
     */
    public function iSendADeleteRequestTo($url)
    {
        $this->client->request('DELETE', $url);
        $this->response = $this->client->getResponse();
    }

    /**
     * @Then the status code should be :statusCode
     */
    public function theStatusCodeShouldBe($statusCode)
    {
        if ($this->response === null) {
            throw new \Exception("No se ha realizado ninguna peticion");
        }
        Assert::assertEquals($statusCode, $this->response->getStatusCode());
    }

    /**
    * @Then the response body should contain the message :message
    */
    public function theResponseBodyShouldContainTheMessage($message)
    {
        $responseData = $this->getDecodedResponse();

        if (isset($responseData['message'])) {
            Assert::assertStringContainsString($message, $responseData['message']);
        } elseif (isset($responseData['errors'])) {
            foreach ($responseData['errors'] as $error) {
                if (str_contains($error, $message)) {
                    return; // Pass if at least one error contains the expected message
                }
            }
        }  elseif (isset($responseData['status'])) {
            Assert::assertStringContainsString($message, $responseData['status']);
        }else {
            throw new \Exception('Neither message nor errors found in the response->');
        }
    }

    /**
     * @Then the response body should contain a list of :arg1
     */
    public function theResponseBodyShouldContainAListOf($arg1)
    {

        $responseData = $this->getDecodedResponse();
        Assert::assertIsArray($responseData, "The response  is not an array");

        $expectedKey = $arg1."_id";
        foreach ($responseData as $element) {
            Assert::assertArrayHasKey($expectedKey, $element, "Each element in 'response' should contain the key '$expectedKey'");
        }
    }

    /**
     * @Then the response body should contain one of the following status:
     */
    public function theResponseBodyShouldContainOneOfTheFollowingStatus(PyStringNode $jsonMessages)
    {
        $expectedMessagesData = json_decode($jsonMessages->getRaw(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON provided in the feature file: " . json_last_error_msg());
        }

        if (!isset($expectedMessagesData['status']) || !is_array($expectedMessagesData['status'])) {
            throw new \InvalidArgumentException("The JSON should contain an array under the 'status' key.");
        }

        $responseData = $this->getDecodedResponse();

        if (isset($responseData['status'])) {
            Assert::assertTrue(
                in_array($responseData['status'], $expectedMessagesData['status'], true),
                sprintf(
                    "Expected one of the status values: %s, but got: %s",
                    implode(', ', $expectedMessagesData['status']),
                    $responseData['status']
                )
            );
        } else {
            throw new \Exception('The response does not contain a status.');
        }
    }

    /**
     * @Then the response JSON should contain exactly:
     */

    public function theResponseJsonShouldContainExactly(string $expectedJson)
    {
        $expected = json_decode($expectedJson, true);
        $actual = json_decode($this->response->getContent(), true);

        if (count($expected) !== count($actual)) {
            throw new \Exception("The response JSON does not match the expected JSON exactly.");
        }

        foreach ($expected as $expectedItem) {
            if (!in_array($expectedItem, $actual, true)) {
                throw new \Exception("The response JSON does not contain the expected items.");
            }
        }

        foreach ($actual as $actualItem) {
            if (!in_array($actualItem, $expected, true)) {
                throw new \Exception("The response JSON contains extra items not expected.");
            }
        }
    }


    /**
     * @Then each :arg1 in the response should contain the fields:
     */
    public function eachItemInTheResponseShouldContainFields($arg1, PyStringNode $fieldsJson)
    {
        $responseJson = $this->response->getContent();
        $responseData = json_decode($responseJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("La respuesta no es un JSON válido: " . json_last_error_msg());
        }

        $expectedFields = json_decode($fieldsJson->getRaw(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Los campos esperados no son un JSON válido: " . json_last_error_msg());
        }

        $items = $responseData[$arg1] ?? $responseData;

        if (!is_array($items)) {
            throw new \Exception("La respuesta no contiene una lista de '$arg1'.");
        }

        foreach ($items as $index => $item) {
            foreach ($expectedFields as $field => $value) {
                if (!array_key_exists($field, $item)) {
                    throw new \Exception("El campo esperado '$field' no se encontró en el elemento en el índice $index.");
                }
            }
        }
    }

    /**
    * @Then the response body should contain :arg1: :expected
    */
    public function theResponseBodyShouldContainExpected($arg1, $expected)
    {
        $responseData = $this->getDecodedResponse();
        Assert::assertArrayHasKey($arg1, $responseData, "The response does not contain the key '$arg1'");

        if ($expected === 'exists') {
            return;
        }

        $expectedValue = filter_var($expected, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($expectedValue === null && strtolower($expected) !== 'true' && strtolower($expected) !== 'false') {
            throw new \InvalidArgumentException("Expected value should be 'true', 'false', or 'exists'");
        }
    }

    /**
     * @Then the response JSON should be equal to:
     */
    public function theResponseJsonShouldBeEqualTo(PyStringNode $expectedJson)
    {

        $expectedData = json_decode($expectedJson->getRaw(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException("Invalid JSON in feature file: " . json_last_error_msg());
        }



        $actualData = $this->getDecodedResponse();
        Assert::assertEquals($expectedData, $actualData, "The response JSON does not match the expected JSON");
    }

}
