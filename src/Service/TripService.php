<?php


namespace App\Service;

class TripService
{
    public function getMatchingTrips(array $trips): array
    {
        $tripsMatching = [];
        foreach ($trips as $trip) {
            foreach ($trip as $data) {
                if ($data) {
                    array_push($tripsMatching, $data);
                }
            }
        }

        return $tripsMatching;
    }
}
