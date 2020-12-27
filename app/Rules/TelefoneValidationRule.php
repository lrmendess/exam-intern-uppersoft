<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TelefoneValidationRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_string($value))
        {
            return false;
        }

        // Reference: https://gist.github.com/boliveirasilva/c927811ff4a7d43a0e0c
        // Modified to require the use of DDD
        return preg_match('/^(?:(?:\+|00)?(55)(\s*)?)?(?:\(?([1-9][0-9])\)?(\s*)?)(?:((?:9(\s*)?\d|[2-9])\d{3})\-?(\d{4}))$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Número de telefone inválido ou mal formatado.';
    }
}
