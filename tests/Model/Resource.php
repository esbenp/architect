<?php

use Illuminate\Database\Eloquent\Model;

require_once __DIR__.'/Children.php';
require_once __DIR__.'/SharedChildren.php';

class Resource extends Model
{
    protected $table = 'resource';

    public function children()
    {
        return $this->hasMany('Children');
    }

    public function sharedChildren()
    {
        return $this->belongsToMany('SharedChildren', 'resource_shared_children', 'resource_id', 'shared_children_id');
    }
}
