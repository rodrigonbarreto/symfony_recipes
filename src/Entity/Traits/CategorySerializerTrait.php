<?php


namespace App\Entity\Traits;


trait CategorySerializerTrait {
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}