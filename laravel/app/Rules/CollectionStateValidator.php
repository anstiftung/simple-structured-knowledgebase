<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CollectionStateValidator implements ValidationRule
{
    private $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // prevents depublishing featured collections
        if ($this->collection->featured && $value == false) {
            $fail('Die Sammlung wird auf der Startseite angezeigt und kann nicht auf unveröffentlicht geschaltet werden.');
        }
    }
}
