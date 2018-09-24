<?php

namespace Versatile\Core\Policies;

use Versatile\Core\Contracts\UserInterface;
use Versatile\Core\Facades\Versatile;
use Versatile\Core\Models\DataType;

class MenuItemPolicy extends BasePolicy
{
    protected static $datatypes = null;
    protected static $permissions = null;

    /**
     * Check if user has an associated permission.
     *
     * @param UserInterface   $user
     * @param object $model
     * @param string $action
     *
     * @return bool
     */
    protected function checkPermission(UserInterface $user, $model, $action)
    {
        if (self::$permissions == null) {
            self::$permissions = Versatile::model('Permission')->all();
        }

        if (self::$datatypes == null) {
            self::$datatypes = DataType::all()->keyBy('slug');
        }

        $regex = str_replace('/', '\/', preg_quote(route('versatile.dashboard')));
        $slug = preg_replace('/'.$regex.'/', '', $model->link(true));
        $slug = str_replace('/', '', $slug);

        if ($str = self::$datatypes->get($slug)) {
            $slug = $str->name;
        }

        if ($slug == '') {
            $slug = 'admin';
        }

        // If permission doesn't exist, we can't check it!
        if (!self::$permissions->contains('key', $action.'_'.$slug)) {
            return true;
        }

        return $user->hasPermission($action.'_'.$slug);
    }
}
