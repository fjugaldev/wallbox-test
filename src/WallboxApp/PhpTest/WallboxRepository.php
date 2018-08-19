<?php


namespace WallboxApp\PhpTest;

use WallboxApp\PhpTest\Filter\Wallbox;

/**
 * Class for retrieving data from wallbox users
 *
 * @package WallboxApp\PhpTest
 */
class WallboxRepository {
    /**
     * Returns all the stays that matches the filter
     *
     * @param Wallbox $filter
     * @return array
     * @throws \Exception
     */
    public static function getFiltered(Wallbox $filter)
    {
        try {
            // Filter data based on the Wallbox filter class.
           return $filter->filterData();

        } catch (\Exception $e) {
            // Throw an exception.
            throw new \Exception($e->getMessage());
        }
    }
}
