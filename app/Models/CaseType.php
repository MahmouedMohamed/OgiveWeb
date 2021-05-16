<?php

namespace App\Models;
class CaseType
{
    public $types = ['Finding Living', 'Upgrade Standard of Living', 'Bride Preparation', 'Debt', 'Cure'];
    public function toString()
    {
        $s = "";
        for ($index = 0; $index < count($this->types); $index++) {
            $s .= $this->types[$index];
            if ($index + 1 < count($this->types))
                $s .= ",";
        }
        return $s;
    }
}
