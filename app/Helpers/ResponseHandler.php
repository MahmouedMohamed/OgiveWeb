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

                    'LikeCreationSuccessMessage' => 'تم إضافة الإعجاب',
                    'LikeDeleteSuccessMessage' => 'تم إزالة الإعجاب',
                    'LikeCreationBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من التفاعل',
                    'LikeViewingBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من متابعة التفاعلات',
                    'LikeCreationForbiddenMessage' => 'أنت لا تملك صلاحية إنشاء التفاعل',
                    'LikeDeletionForbiddenMessage' => 'أنت لا تملك صلاحية إزالة هذا التفاعل',
                    'LikeNotFound' => 'هذا العنصر غير موجود',

                    'PetCreationSuccessMessage' => 'شكراً لكونك إنسان',
                    'PetUpdateSuccessMessage' => 'تم تعديل البيانات بنجاح',
                    'PetDeleteSuccessMessage' => 'تم إزالة الحالة بنجاح',
                    'PetCreationBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من إنشاء حالة جديدة',
                    'PetViewingBannedMessage' => 'نأسف لكن يبدو أنك ممنوع من مشاهدة الحالات',
                    'PetCreationForbiddenMessage' => 'أنت لا تملك صلاحية إنشاء هذة الحالة',
                    'PetUpdateForbiddenMessage' => 'أنت لا تملك صلاحية تعديل هذة الحالة',
                    'PetDeletionForbiddenMessage' => 'أنت لا تملك صلاحية إزالة هذة الحالة',
                    'PetNotFound' => 'هذة الحالة غير موجودة',
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

                    'MemoryCreationSuccessMessage' => 'Memories Last Forever',
                    'MemoryUpdateSuccessMessage' => 'Memory updated Successfully',
                    'MemoryDeleteSuccessMessage' => 'Memory deleted Successfully',
                    'MemoryCreationBannedMessage' => 'Sorry, but it seems that you are banned from creating any new memories',
                    'MemoryViewingBannedMessage' => 'Sorry, but it seems that you are banned from viewing memories',
                    'MemoryCreationForbiddenMessage' => 'You aren\'t authorized to create this memory',
                    'MemoryUpdateForbiddenMessage' => 'You aren\'t authorized to update this memory',
                    'MemoryDeletionForbiddenMessage' => 'You aren\'t authorized to delete this memory',
                    'MemoryNotFound' => 'Memory Not Found',

                    'LikeCreationSuccessMessage' => 'Like added successfully',
                    'LikeDeleteSuccessMessage' => 'Like has been removed',
                    'LikeCreationBannedMessage' => 'Sorry, but it seems that you are banned from creating any new likes',
                    'LikeViewingBannedMessage' => 'Sorry, but it seems that you are banned from viewing likes',
                    'LikeCreationForbiddenMessage' => 'You aren\'t authorized to like this',
                    'LikeDeletionForbiddenMessage' => 'You aren\'t authorized to delete this like',
                    'LikeNotFound' => 'Like Not Found',

                    'PetCreationSuccessMessage' => 'Thank you for being a Human',
                    'PetUpdateSuccessMessage' => 'Information Updated Successfully',
                    'PetDeleteSuccessMessage' => 'The case has been deleted successfully',
                    'PetCreationBannedMessage' => 'Sorry, but it seems that you are banned from creating any new cases',
                    'PetViewingBannedMessage' => 'Sorry, but it seems that you are banned from viewing the cases',
                    'PetCreationForbiddenMessage' => 'You aren\'t authorized to create the case',
                    'PetUpdateForbiddenMessage' => 'You aren\'t authorized to update this case',
                    'PetDeletionForbiddenMessage' => 'You aren\'t authorized to delete this case',
                    'PetNotFound' => 'This case Can\'t be found',
                ];
        }
    }
}
