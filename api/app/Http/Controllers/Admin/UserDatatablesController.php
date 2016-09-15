<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Post;

use App\User;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class UserDatatablesController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData()
    {
        $users = User::select(['id', 'pseudo', 'email', 'firstname', 'lastname', 'rank', 'active', 'points', 'votes','banned', 'banReason'])->orderBy('id', 'desc')->get();;

        return Datatables::of($users)
            ->addColumn('action', function ($user) {
                $buttons;
                $buttons =
                    '
                <a href="user/'.$user->id.'/edit" class="edit btn btn-xs btn-default" data-toggle="tooltip" title="View"><i class="fa fa-search"></i></a>
                    ';

                if($user->isBanned())
                {
                    $buttons .= '<a id="unban-'.$user->id.'" class="unban pull-right btn btn-xs btn-info" data-toggle="tooltip" title="Unban"><i class="fa fa-check-circle-o"></i></a>';
                }
                else
                {
                    $buttons .= '<a id="ban-'.$user->id.'" class="ban pull-right btn btn-xs btn-danger" data-toggle="tooltip" title="Ban"><i class="fa fa-ban"></i></a>';
                }
                if(!$user->isActive()){
                    $buttons .= '<a id="active-'.$user->id.'" class="activ btn btn-xs btn-warning" data-toggle="tooltip" title="Active"><i class="fa fa-check"></i></a>';
                }
                return $buttons;
            })
            ->editColumn('active', function ($user) {
                if($user->isActive())
                {
                    $ac = '<span class="label label-success">Confirmed</span>';
                }
                else
                {
                    $ac = '<span class="label label-danger">Unconfirmed</span>';
                }
                return $ac;
            })
            ->make(true);
    }




}
