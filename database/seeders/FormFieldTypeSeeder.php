<?php

namespace Database\Seeders;

use App\Models\FormFieldType;
use Illuminate\Database\Seeder;

class FormFieldTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $field_types = [
            'Text',
            'Number',
            'Select'
        ];

        foreach ($field_types as $field_type) {
            $model_field_type = new FormFieldType();
            $model_field_type->name = $field_type;
            $model_field_type->save();
        }
    }
}
