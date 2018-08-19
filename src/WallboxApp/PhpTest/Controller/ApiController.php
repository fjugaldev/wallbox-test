<?php


namespace WallboxApp\PhpTest\Controller;

use WallboxApp\PhpTest\WallboxRepository;
use WallboxApp\PhpTest\Filter\Wallbox;
use WallboxApp\PhpTest\Utils\Security;
use WallboxApp\PhpTest\Utils\JsonResponse;

/**
 * Class ApiController
 * @package WallboxApp\PhpTest\Controller
 */
class ApiController extends JsonResponse
{

    /**
     * Gets the users data with the passed filter.
     * @throws \Exception
     */
    public function getUsersAction(array $request)
    {
        try {
            // Validate request method
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                // Security Validations.
                $security = new Security(getallheaders());
                if ($security->hasAuthorizationHeader()) {
                    if ($security->isValidToken()) {
                        // Gets query string params.
                        $activationLength = $request['activation_length'] ?? 0;
                        $countries        = $request['countries'] ?? [];

                        // Filter data and print JSON Response.
                        $filter = new Wallbox($activationLength, $countries);

                        // Return JSON response.
                        echo $this->jsonResponse(WallboxRepository::getFiltered($filter), 200) ;

                    } else {
                        // Token is not valid or expired.
                        echo $this->jsonResponse(["The token value is not valid or is expired."], 500);
                    }

                } else {
                    // Authorization Header is not present on the request.
                    echo $this->jsonResponse(["The token value is not present on the header request."], 500);
                }

            } else {
                // The request method is not allowed.
                echo $this->jsonResponse(["The request method '{$_SERVER['REQUEST_METHOD']}' is not allowed."], 500);
            }

        } catch (\Exception $e) {
            // Return error.
            echo $this->jsonResponse(
                ["An error has occurred trying to get the users data, Error: " . $e->getMessage()], 500);
        }
    }
}
