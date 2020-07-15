<?php

namespace App\Entity;

/**
 * Class UserMobicoop
 * @package App\Entity
 */

class UserMobicoop
{
    /**
     * @var int
     */
    private int $mobicoopId;

    /**
     * @var string
     */
    private string $givenName;

    /**
     * @var string
     */
    private string $familyName;

    /**
     * @var int
     */
    private int $gender;

    /**
     * @var string
     */
    private string $phone;

    /**
     * @var string
     */
    private string $avatar;

    /**
     * @var string
     */
    private string $role;


    /**
     * @return int
     */

    public function getMobicoopId(): int
    {
        return $this->mobicoopId;
    }

    /**
     * @param int $mobicoopId
     */
    public function setMobicoopId(int $mobicoopId)
    {
        $this->mobicoopId = $mobicoopId;
    }

    /**
     * @return string
     */
    public function getGivenName(): string
    {
        return $this->givenName;
    }

    /**
     * @param mixed $givenName
     */
    public function setGivenName($givenName)
    {
        $this->givenName = $givenName;
    }

    /**
     * @return mixed
     */
    public function getFamilyName()
    {
        return $this->familyName;
    }

    /**
     * @param mixed $familyName
     */
    public function setFamilyName($familyName)
    {
        $this->familyName = $familyName;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }
}
