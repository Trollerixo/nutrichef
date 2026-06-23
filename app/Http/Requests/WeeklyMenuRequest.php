<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WeeklyMenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $mealTypes = ['desayuno', 'almuerzo', 'cena', 'postre', 'piqueo'];

        return [
            'title' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'slots' => ['required', 'array', 'min:1'],
            'slots.*.slot_date' => ['required', 'date'],
            'slots.*.meal_type' => ['required', Rule::in($mealTypes)],
            'slots.*.recipe_id' => ['required', 'exists:recipes,id'],
        ];
    }
}
