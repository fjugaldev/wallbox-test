<?php

namespace WallboxApp\PhpTest\Filter;

/**
 * Class Wallbox
 * @package WallboxApp\PhpTest\Filter
 */
class Wallbox
{
    /** @var string DATA */
    protected const DATA = WALLBOX_APP_DATA_FOLDER.'/users.csv';

    /** @var int */
    protected $activationLength;

    /**  @var array */
    protected $countries;

    /**
     * Wallbox constructor.
     * @param int   $activationLength
     * @param array $countries
     */
    public function __construct(int $activationLength = 0, array $countries = [])
    {
        // Initialize Wallbox filter class properties.
        $this
            ->setActivationLength($activationLength)
            ->setCountries($countries);
    }

    /**
     * Filter the data based on the passed parameters.
     *
     * @return array
     * @throws \Exception
     */
    public function filterData(): array
    {
        try {
            // Get array of users from CSV file.
            $users = array_map('str_getcsv', file(self::DATA));
            $filteredStays = $users;

            // Filter array by countries.
            if (!empty($this->getCountries()))
                $filteredStays = array_filter($users, [$this, 'filterByCountries']);

            // Filter array by activation length.
            if(!empty($this->getActivationLength()))
                $filteredStays = array_filter($filteredStays, [$this, 'filterByActivationLength']);

            // Sort results by name and lastname
            usort($filteredStays, [$this, 'sortByName']);

            // Returns filtered data.
            return array_values($filteredStays);

        } catch (\Exception $e) {
            // Throws an exception.
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * Filters data based on activation length.
     *
     * @param array $row
     * @return bool
     * @throws \Exception
     */
    protected function filterByActivationLength(array $row): bool
    {
        try {
            $diff = date_diff(date_create($row[6]),date_create($row[5]))->format('%d');

            return $diff >= $this->getActivationLength();

        } catch (\Exception $e) {
            throw new \Exception(
                "An error has occurred trying to filtering by activation length. Error: " . $e->getMessage());
        }
    }

    /**
     * Filters data based on passed countries.
     *
     * @param array $row
     * @return bool
     * @throws \Exception
     */
    protected function filterByCountries(array $row): bool
    {
        try {
            $result = array_search($row[4], array_values($this->getCountries()));

            return is_int($result) ? true : false;

        } catch (\Exception $e) {
            throw new \Exception(
                "An error has occurred trying to filtering by countries. Error: " . $e->getMessage());
        }
    }

    /**
     * Sorts data based on name and lastname.
     *
     * @param array $users1
     * @param array $users2
     * @return int
     * @throws \Exception
     */
    protected function sortByName(array $users1, array $users2): int
    {
        try {
            return $users1[1] <=> $users2[1];

        } catch (\Exception $e) {
            throw new \Exception(
                "An error has occurred trying to sort by name. Error: " . $e->getMessage());
        }
    }

    /**
     * @return int
     */
    protected function getActivationLength(): int
    {
        return $this->activationLength;
    }

    /**
     * @param mixed $activationLength
     * @return self
     */
    protected function setActivationLength(int $activationLength): self
    {
        $this->activationLength = $activationLength;

        return $this;
    }

    /**
     * @return array
     */
    protected function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param array $countries
     * @return self
     */
    protected function setCountries(array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

}
