<?php


namespace App\Service;

class TripService
{
    public function getMatchingTrips(?array $trips): array
    {
        $tripsMatching = [];
        if ($trips) {
            foreach ($trips as $trip) {
                foreach ($trip as $data) {
                    if ($data) {
                        array_push($tripsMatching, $data);
                    }
                }
            }
        }

        return $tripsMatching;
    }

    public static function createAjaxTripsArray(array $usersMobicoop, $trips): array
    {
        dd($trips);
        $newArray = [];
        $inc = 0;
        foreach ($usersMobicoop['hydra:member'] as $user) {
            foreach ($trips as $data) {
                if ($user['id'] === $data->getMobicoopId()) {
                    $newArray[$inc]['id'] = $data->getId();
                    $newArray[$inc]['givenName'] = $user['givenName'];
                    $inc++;
                }
            }
        }
        return $newArray;
    }
}
