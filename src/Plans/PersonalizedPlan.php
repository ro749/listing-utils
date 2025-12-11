<?php

namespace Ro749\ListingUtils\Plans;
use Ro749\SharedUtils\Forms\BaseForm;
class PersonalizedPlan extends Plan{
    public function render($form){
        return view('listing-utils::Plans.personalized-plan', [
            'plan' => $this, 
            'stack' => 'fill-plan-'.$this->id,
            'form' => $form
        ]);
    }
}