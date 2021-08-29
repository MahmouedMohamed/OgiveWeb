<?php

namespace App\Helpers;

class ResponseHandler
{
    public string $language;
    public $words;
    public function __construct($language)
    {
        if ($language != null)
            $this->language = $language;
        else
            $this->language = 'En';
        $this->words = $this->getWords($this->language);
    }
    private function getWords(string $language)
    {
        switch ($language) {
            case 'Ar':
                return [
                    'WrongData' => 'خطأ في البيانات',
                    'UserNotFound' => 'المستخدم غير موجود',
                    'MarkerCreationSuccessMessage' => 'شكراً لمساهمتك المميزة',
                ];
            case 'En':
                return [
                    'WrongData' => 'Wrong Data',
                    'UserNotFound' => 'User Cannot be found',
                    'MarkerCreationSuccessMessage' => 'Thank you for your valuable contribution',
                ];
        }
    }
}
