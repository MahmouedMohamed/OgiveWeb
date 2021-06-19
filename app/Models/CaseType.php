<?php

namespace App\Models;
class CaseType
{
    public $types = ['إيجاد مسكن مناسب', 'تحسين مستوي المعيشة', 'تجهيز عروس', 'ديون', 'علاج'];
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
