<?php


namespace WallboxApp\PhpTest;

use WallboxApp\PhpTest\Filter\Wallbox;

/**
 * Class for retrieving data from wallbox users
 *
 * @package WallboxApp\PhpTest
 */
class WallboxRepository {
    /** @var string DATA */
    protected const DATA = WALLBOX_APP_DATA_FOLDER.'/users.csv';

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
           return $filter->filterData(self::getUsersData());

        } catch (\Exception $e) {
            // Throw an exception.
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return array
     */
    protected static function getUsersData(): array {
        // Get array of users from CSV file.
        return array_map('str_getcsv', file(self::DATA));
    }
}
