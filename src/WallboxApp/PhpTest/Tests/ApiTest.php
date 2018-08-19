<?php
declare(strict_types=1);

namespace WallboxApp\PhpTest\Tests;

use PHPUnit\Framework\TestCase;
use WallboxApp\PhpTest\Utils\Security;
use WallboxApp\PhpTest\Filter\Wallbox;
use WallboxApp\PhpTest\WallboxRepository;
use WallboxApp\PhpTest\Controller\ApiController;

define('WALLBOX_APP_DATA_FOLDER', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'resources/data'));
/**
 * Class ApiTest
 * @package WallboxApp\PhpTest\Tests
 */
final class ApiTest extends TestCase
{
    /** @var string TEST_DATA */
    protected const TEST_DATA = './resources/data/users.csv';

    /** @var array $users */
    protected $users = null;

    /**
     * @return array
     */
    public function getUsers(): array
    {
        if (!isset($this->users)) {
            $this->setUsers(array_map('str_getcsv', file(self::TEST_DATA)));
        }

        return $this->users;
    }

    /**
     * @param array $users
     * @return self
     */
    public function setUsers(array $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function testRequestApi(): void
    {
        // Get cURL resource
        $curl = curl_init();
        //Set headers
        $token = base64_encode((string) time());
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "Authorization: {$token}",
        ));
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost:8080/?activation_length=27&countries[]=PT&countries[]=CN',
            CURLOPT_USERAGENT => 'Tests with PHPUnit'
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        // Parse results
        $result = json_decode($response, true);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals(false, $result['error']);
        $this->assertEquals(200, $result['code']);
        $this->assertInternalType('array',$result['data']);

        // Get cURL resource
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://localhost:8080/?activation_length=27&countries[]=PT&countries[]=CN',
            CURLOPT_USERAGENT => 'Tests with PHPUnit'
        ));
        // Send the request & save response to $resp
        $response = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        // Parse results
        $result = json_decode($response, true);

        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('code', $result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals(true, $result['error']);
        $this->assertEquals(500, $result['code']);
        $this->assertInternalType('array',$result['data']);
    }

//    public function testGetUsersAction(): void {}

//    public function testGetFiltered(): void
//    {
//        // Prepare data.
//        $activationLength = 20;
//        $countries = [
//            "CN",
//            "PT"
//        ];
//
//        $filter = new Wallbox($activationLength, $countries);
//        $result = WallboxRepository::getFiltered($filter);
//
//        $this->assertInternalType('array',$result);
//    }

    public function testFilterData(): void
    {
        // Prepare data.
        $activationLength = 20;
        $countries = [
            "CN",
            "PT"
        ];

        $filter = new Wallbox($activationLength, $countries);
        $result = $filter->filterData($this->getUsers());

        $this->assertInternalType('array',$result);
    }


    public function testHasAuthorizationHeader(): void
    {
        // Prepare data.
        $header = [
            "Authorization" => 'MTUzNDY4MTI0Ng==',
        ];
        $fakeHeader = [
            "Autorization" => 'MTUzNDY4MTI0Ng==',
        ];

        // With real header.
        $securityTest1 = new Security($header);
        $this->assertEquals(true,$securityTest1->hasAuthorizationHeader());

        // With fake header.
        $securityTest2 = new Security($fakeHeader);
        $this->assertEquals(false, $securityTest2->hasAuthorizationHeader());
    }

    public function testIsValidToken(): void
    {
        // Prepare data.
        $header = [
            "Authorization" => base64_encode((string) time()),
        ];
        $fakeHeader = [
            "Authorization" => 'x$5543fg4433s',
        ];

        $expiredHeader = [
            "Authorization" => 'MTUzNDYxOTU2OA==',
        ];

        // With real header.
        $securityTest1 = new Security($header);
        $this->assertEquals(true,$securityTest1->isValidToken());

        // With fake header.
        $securityTest2 = new Security($fakeHeader);
        $this->assertEquals(false, $securityTest2->isValidToken());

        // With expired header.
        $securityTest3 = new Security($expiredHeader);
        $this->assertEquals(false, $securityTest3->isValidToken());
    }
}
