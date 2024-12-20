<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;

class ModelPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function before($user,$ability)
    {
        if ($user->super_admin){
            return true;
        }
    }

    public function __call(string $name, array $arguments)
    {
        $class_name=str_replace('Policy','',class_basename($this));
        $class_name=Str::plural(Str::lower($class_name));

        if ($name == 'view-any'){
            $name='view';
        }
        $ability=$class_name . '.'. Str::kebab($name);

        $user= $arguments[0];

        if (isset($arguments[1])){
            $model=$arguments[1];
            if ($model->store_id !== $user->store_id){
                return false;
            }
        }
        return $user->hasAbility($ability);
    }
}
