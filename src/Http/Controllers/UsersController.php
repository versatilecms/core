<?php

namespace Versatile\Core\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\Session;
use Versatile\Core\Components\Filters\Users\CreatedAtFilter;
use Versatile\Core\Components\Filters\Users\RoleFilter;
use Versatile\Core\Components\Filters\Users\RolesFilter;
use Versatile\Core\Components\Actions\Handlers\ImpersonateAction;

class UsersController extends BaseController
{
    public function setup()
    {
        $this->bread->setActionsFormat('dropdown');//DataType::ACTIONS_DROPDOWN);

        $this->bread->addAction(
            ImpersonateAction::class
        );

        $this->bread->addFilters([
            RoleFilter::class,
            RolesFilter::class,
            CreatedAtFilter::class
        ]);
    }

    /**
     * Impersonate a user as an administrator
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function impersonate($userId)
    {
        // Store our current 'admin' id to switch back to
        Session::put('original_user.name', Auth::user()->name);
        Session::put('original_user.id', Auth::id());

        // Impersonate the requested user
        Auth::loginUsingId($userId);

        return redirect()->route('versatile.dashboard');
    }

    /**
     * Login as the original user and destroy session
     *
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revertImpersonate($userId)
    {
        if (Session::has('original_user.id') && $userId == Session::get('original_user.id')) {
            // Login as the original user and destroy session
            Session::forget('original_user');
            Auth::loginUsingId($userId);

            return redirect()->route('versatile.users.index');
        }
    }
}
