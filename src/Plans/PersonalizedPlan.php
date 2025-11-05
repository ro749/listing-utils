<?php

namespace Ro749\ListingUtils\Plans;
use Ro749\SharedUtils\Forms\BaseForm;
class PersonalizedPlan extends Plan{
    public ?BaseForm $form = null;
    public function render(){
        return view('listing-utils::Plans.personalized-plan', [
            'plan' => $this, 
            'stack' => 'fill-plan-'.$this->id,
            'form' => $this->form
        ]);
    }
}