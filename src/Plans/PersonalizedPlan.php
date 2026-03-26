<?php

namespace Ro749\ListingUtils\Plans;
use Ro749\SharedUtils\Forms\BaseForm;
class PersonalizedPlan extends Plan{
    public function render($form,$personal_plan = null){
        return view('listing-utils::Plans.personalized-plan', [
            'plan' => $this, 
            'stack' => 'fill-plan-'.$this->id,
            'form' => $form,
            'personal_plan' => $personal_plan
        ]);
    }
}