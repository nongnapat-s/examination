<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    public function age()
    {
        return Carbon::parse($this->attributes['dob'])->age;
    }
}
