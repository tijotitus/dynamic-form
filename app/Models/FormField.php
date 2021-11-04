<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;

    public function FormFieldOptions()
    {
        return $this->hasMany(FormFieldOption::class);
    }
}
