<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;  

    protected $guarded = ['id'];

    protected $with = ['ancestors'];

    public function getParentKeyName()
    {
        return 'parent_id';
    }
}
