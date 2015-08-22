<?php

use Illuminate\Database\Eloquent\Model;

require_once __DIR__.'/NestedChildren.php';

class Children extends Model
{
    protected $table = 'children';

    public function nestedChildren()
    {
        return $this->hasMany('NestedChildren');
    }
}
