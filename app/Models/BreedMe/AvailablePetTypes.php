<?php

namespace App\Models\BreedMe;

class AvailablePetTypes
{
    public $types = ['Dog', 'Cat', 'Bird', 'Fish'];

    public function toString()
    {
        $s = '';
        for ($index = 0; $index < count($this->types); $index++) {
            $s .= $this->types[$index];
            if ($index + 1 < count($this->types)) {
                $s .= ',';
            }
        }

        return $s;
    }
}
