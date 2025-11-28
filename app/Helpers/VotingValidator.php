<?php

namespace App\Helpers;

class VotingValidator
{
    public static function isSelectAllRequired()
    {
        return config('app.select_all_required', 'no') === 'yes';
    }
    
    public static function validatePostSelection($post, $selectedCandidates)
    {
        $isRequired = self::isSelectAllRequired();
        
        if ($isRequired) {
            // Must select exactly required_number candidates
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount === $requiredCount;
        } else {
            // Current behavior: can select 0 to required_number
            $selectedCount = count($selectedCandidates);
            $requiredCount = $post['required_number'];
            
            return $selectedCount <= $requiredCount;
        }
    }
}
