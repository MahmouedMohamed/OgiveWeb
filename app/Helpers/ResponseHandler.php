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
                    'InvalidData' => 'خطأ في البيانات',
                    'UserNotFound' => 'المستخدم غير موجود',
                    'FoodSharingMarkerCreationSuccessMessage' => 'شكراً لمساهمتك المميزة',
                    'FoodSharingMarkerNotFound' => 'هذا العنصر غير موجود',
                    'FoodSharingMarkerSuccessCollectExist' => 'شكراً لجعلك من العالم مكاناً أفضل',
                    'FoodSharingMarkerSuccessCollectNoExist' => 'نأسف لتضييع وقتك، لكن أعتبر أنه ذهب لمكانه الصحيح',
                    'FoodSharingMarkerAlreadyCollected' => 'هذا العنصر تم جمعه بالفعل',
                    'ShowAchievementForbidden' => 'أنت لا تملك صلاحية عرض هذا التعامل',
                    'AchievementNotFound' => 'هذا العنصر غير موجود',
                ];
                //Default is English
            default:
                return [
                    'InvalidData' => 'Invalid Data',
                    'UserNotFound' => 'User Cannot be found',
                    'FoodSharingMarkerCreationSuccessMessage' => 'Thank you for your valuable contribution',
                    'FoodSharingMarkerNotFound' => 'Food Sharing Marker Not Found',
                    'FoodSharingMarkerSuccessCollectExist' => 'Thank you for making the world a better place',
                    'FoodSharingMarkerSuccessCollectNoExist' => 'Sorry for wasting your time, but consider that it has gone to it\'s place',
                    'FoodSharingMarkerAlreadyCollected'=> 'This Food Sharing Marker has been collected already',
                    'ShowAchievementForbidden' => 'You aren\'t authorized to show this achivement',
                    'AchievementNotFound' => 'This element can\'t be found',
                ];
        }
    }
}
