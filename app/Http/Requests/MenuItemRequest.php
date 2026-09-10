<?php

namespace App\Http\Requests;

use App\Models\MenuItem;
use App\Rules\IniAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MenuItemRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // dd($this->all());
        return [
            'module_id' => ['required','integer',Rule::in([1, 2]),],
            'restaurant_id'  => ['required', 'numeric'],
            'name'           => ['required', 'string', 'max:255'],
            'categories.*'   => 'nullable',
            'unit_price'     => ['required', 'numeric', new IniAmount()],
            'discount_price' => ['nullable', 'numeric', new IniAmount()],
            'status'         => 'required|numeric',
            'description'    => 'nullable|string|max:1000',
            'image'          => 'image|mimes:jpeg,png,jpg|max:4096',
            'max_cart_quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes()
    {
        return [
            'module_id' => 'module',
            'name'      => trans('validation.attributes.name'),
            'image'     => trans('validation.attributes.image'),
            'status'    => trans('validation.attributes.status'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->menuItemNameUniqueCheck()) {
                $validator->errors()->add(
                    'name',
                    'The menu item name already exists.'
                );
            }

            if ($this->priceValidationCheck()) {
                $validator->errors()->add(
                    'discount_price',
                    'The discount price is greater than the unit price.'
                );
            }
        });
    }

    private function menuItemNameUniqueCheck()
    {
        $restaurant_id = auth()->user()->restaurant->id ?? 0;
        $id = $this->menu_item;

        $queryArray['name'] = request('name');
        $queryArray['restaurant_id'] = $restaurant_id;

        $menu_items = MenuItem::where($queryArray)
            ->where('id', '!=', $id)
            ->first();

        return !blank($menu_items);
    }

    private function priceValidationCheck()
    {
        return request('unit_price') < request('discount_price');
    }
}