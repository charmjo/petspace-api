<?php
    // app/Enums/Status.php
    namespace App\Enums;

    enum PetHealthHistory: string
    {
        case ALLERGIES = 'allergy';
        case WEIGHT = 'weight';
        case SPECIAL_CONDITION = 'special conditions';
    }
