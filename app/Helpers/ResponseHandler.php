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
                    'FoodSharingMarkerUpdateSuccessMessage' => 'تم تعديل العنصر بنجاح',
                    'FoodSharingMarkerDeleteSuccessMessage' => 'تم إزالة العنصر بنجاح',
                    'FoodSharingMarkerCreationBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من إنشاء عناصر جديدة',
                    'FoodSharingMarkerViewingBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من مشاهدة العناصر',
                    'FoodSharingMarkerCreationForbiddenMessage' => 'أنت لا تملك صلاحية إنشاء هذا العنصر',
                    'FoodSharingMarkerUpdateForbiddenMessage' => 'أنت لا تملك صلاحية تعديل هذا العنصر',
                    'FoodSharingMarkerDeletionForbiddenMessage' => 'أنت لا تملك صلاحية إزالة هذا العنصر',
                    'FoodSharingMarkerNotFound' => 'هذا العنصر غير موجود',
                    'FoodSharingMarkerSuccessCollectExist' => 'شكراً لجعلك من العالم مكاناً أفضل',
                    'FoodSharingMarkerSuccessCollectNoExist' => 'نأسف لتضييع وقتك، لكن أعتبر أنه ذهب لمكانه الصحيح',
                    'FoodSharingMarkerAlreadyCollected' => 'هذا العنصر تم جمعه بالفعل',
                    'ShowAchievementForbidden' => 'أنت لا تملك صلاحية عرض هذا العنصر',
                    'AchievementNotFound' => 'هذا العنصر غير موجود',

                    'MemoryCreationSuccessMessage' => 'الذكريات لا تموت معنا',
                    'MemoryUpdateSuccessMessage' => 'تم تعديل العنصر بنجاح',
                    'MemoryDeleteSuccessMessage' => 'تم إزالة العنصر بنجاح',
                    'MemoryCreationBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من إنشاء ذكريات جديدة',
                    'MemoryViewingBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من مشاهدة الذكريات',
                    'MemoryCreationForbiddenMessage' => 'أنت لا تملك صلاحية إنشاء هذا العنصر',
                    'MemoryUpdateForbiddenMessage' => 'أنت لا تملك صلاحية تعديل هذا العنصر',
                    'MemoryDeletionForbiddenMessage' => 'أنت لا تملك صلاحية إزالة هذا العنصر',
                    'MemoryNotFound' => 'هذا العنصر غير موجود',
                ];
                //Default is English
            default:
                return [
                    'InvalidData' => 'Invalid Data',
                    'UserNotFound' => 'User Cannot be found',
                    'FoodSharingMarkerCreationSuccessMessage' => 'Thank you for your valuable contribution',
                    'FoodSharingMarkerUpdateSuccessMessage' => 'Marker updated successfully',
                    'FoodSharingMarkerDeleteSuccessMessage' => 'Marker deleted successfully',
                    'FoodSharingMarkerCreationBannedMessage' => 'Sorry, but it seems that you are banned from creating any new markers',
                    'FoodSharingMarkerViewingBannedMessage' => 'Sorry, but it seems that you are banned from viewing markers',
                    'FoodSharingMarkerCreationForbiddenMessage' => 'You aren\'t authorized to create this marker',
                    'FoodSharingMarkerUpdateForbiddenMessage' => 'You aren\'t authorized to update this marker',
                    'FoodSharingMarkerDeletionForbiddenMessage' => 'You aren\'t authorized to delete this marker',
                    'FoodSharingMarkerNotFound' => 'Food Sharing Marker Not Found',
                    'FoodSharingMarkerSuccessCollectExist' => 'Thank you for making the world a better place',
                    'FoodSharingMarkerSuccessCollectNoExist' => 'Sorry for wasting your time, but consider that it has gone to it\'s place',
                    'FoodSharingMarkerAlreadyCollected' => 'This Food Sharing Marker has been collected already',
                    'ShowAchievementForbidden' => 'You aren\'t authorized to show this achievement',
                    'AchievementNotFound' => 'This element can\'t be found',

                    'MemoryCreationCreationSuccessMessage' => 'Memories don\'t die with us',
                    'MemoryCreationUpdateSuccessMessage' => 'Memory updated Successfully',
                    'MemoryDeleteSuccessMessage' => 'Memory deleted Successfully',
                    'MemoryCreationBannedMessage' => 'Sorry, but it seems that you are banned from creating any new memories',
                    'MemoryViewingBannedMessage' => 'Sorry, but it seems that you are banned from viewing memories',
                    'MemoryCreationForbiddenMessage' => 'You aren\'t authorized to create this memory',
                    'MemoryUpdateForbiddenMessage' => 'You aren\'t authorized to update this memory',
                    'MemoryDeletionForbiddenMessage' => 'You aren\'t authorized to delete this memory',
                    'MemoryNotFound' => 'Memory Not Found',
                ];
        }
    }
}
