<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $slugRule = Rule::unique('recipes', 'slug');
        $recipe = $this->route('recipe');

        if ($recipe) {
            $slugRule = $slugRule->ignore($recipe);
        }

        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', $slugRule],
            'category_id' => ['nullable', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'prep_time_min' => ['nullable', 'integer', 'min:0'],
            'calories' => ['nullable', 'integer', 'min:0'],
            'featured_date' => ['nullable', 'date'],
            'published' => ['boolean'],
            'ingredients' => ['array'],
            'ingredients.*.name' => ['nullable', 'string', 'max:255'],
            'ingredients.*.quantity' => ['nullable', 'string', 'max:255'],
            'ingredients.*.notes' => ['nullable', 'string', 'max:255'],
            'steps' => ['array'],
            'steps.*.instruction' => ['nullable', 'string'],
        ];
    }
}
