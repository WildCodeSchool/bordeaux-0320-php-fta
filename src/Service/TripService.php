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
        $newArray = [];
        $inc = 0;
        foreach ($usersMobicoop as $user) {
            foreach ($trips as $data) {
                if ($user->id === $data->getBeneficiary()->getMobicoopId()) {
                    $newArray[$inc]['id'] = $data->getId();
                    $newArray[$inc]['departureName'] = $data->getDeparture()->getName();
                    $newArray[$inc]['arrivalName'] = $data->getArrival()->getName();
                    $newArray[$inc]['date'] = $data->getDate()->format('d-m-Y');
                    $newArray[$inc]['hours'] = $data->getDate()->format('H:i');
                    $newArray[$inc]['beneficiaryFullName'] =
                        $data->getBeneficiary()->getGivenName() . ' ' . $data->getBeneficiary()->getFamilyName();
                    if ($data->getVolunteer()) {
                        $newArray[$inc]['volunteerFullName'] =
                            $data->getVolunteer()->getGivenName() . ' ' . $data->getVolunteer()->getFamilyName();
                    }

                    $inc++;
                }
            }
        }
        return $newArray;
    }
}
