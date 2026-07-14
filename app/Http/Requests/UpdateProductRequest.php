<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $productId = $this->route('product');

        return [
            'reference' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'reference')->ignore($productId),
            ],
            'barcode' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')->ignore($productId)->nullable(),
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'category' => [
                'required',
                'string',
                'in:alimentation,boissons,hygiene,divers',
            ],
            'purchase_price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:2',
            ],
            'selling_price' => [
                'required',
                'numeric',
                'min:0',
                'decimal:2',
                'gt:purchase_price',
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'minimum_stock' => [
                'required',
                'integer',
                'min:0',
            ],
            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:102400', // 100 Mo
            ],
            'status' => [
                'required',
                'string',
                'in:active,inactive',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'reference.required' => 'La référence est obligatoire.',
            'reference.unique' => 'Cette référence existe déjà.',
            'barcode.unique' => 'Ce code-barres existe déjà.',
            'name.required' => 'Le nom du produit est obligatoire.',
            'category.required' => 'La catégorie est obligatoire.',
            'category.in' => 'La catégorie sélectionnée n\'est pas valide.',
            'purchase_price.required' => 'Le prix d\'achat est obligatoire.',
            'purchase_price.min' => 'Le prix d\'achat doit être positif.',
            'selling_price.required' => 'Le prix de vente est obligatoire.',
            'selling_price.min' => 'Le prix de vente doit être positif.',
            'selling_price.gt' => 'Le prix de vente doit être supérieur au prix d\'achat.',
            'stock.required' => 'Le stock est obligatoire.',
            'stock.min' => 'Le stock ne peut pas être négatif.',
            'minimum_stock.required' => 'Le stock minimum est obligatoire.',
            'minimum_stock.min' => 'Le stock minimum ne peut pas être négatif.',
            'image.image' => 'Le fichier doit être une image.',
            'image.mimes' => 'L\'image doit être de type : jpg, jpeg, png, webp.',
            'image.max' => 'L\'image ne doit pas dépasser 100 Mo.',
            'status.required' => 'Le statut est obligatoire.',
            'status.in' => 'Le statut sélectionné n\'est pas valide.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        // Convertir les prix en format numérique
        if ($this->has('purchase_price')) {
            $this->merge([
                'purchase_price' => str_replace([' ', ','], ['', '.'], $this->purchase_price),
            ]);
        }

        if ($this->has('selling_price')) {
            $this->merge([
                'selling_price' => str_replace([' ', ','], ['', '.'], $this->selling_price),
            ]);
        }
    }
}