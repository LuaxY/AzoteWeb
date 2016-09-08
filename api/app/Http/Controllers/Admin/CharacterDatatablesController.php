<?php

namespace App\Http\Controllers\Admin;

use App\Account;
use App\Character;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Post;

use App\User;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class CharacterDatatablesController extends Controller
{
    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function anyData($server)
    {
        $characters = Character::on($server.'_world')->select('Id', 'Name', 'Experience', 'Breed', 'DeletedDate')->get();

        return Datatables::of($characters)
            ->editColumn('Class', function ($character) {
                return $character->classe();
            })
            ->editColumn('Level', function ($character) {
                return $character->level();
            })
            ->addColumn('Status', function ($character) {
                if($character->isDeleted())
                {
                    $pub = '<span class="label label-danger">Deleted</span> <span class="label label-danger">'.$character->DeletedDate->format('d-m-Y H:i:s').'</span>';
                }
                else
                {
                    $pub = '<span class="label label-success">Running</span>';
                }
                return $pub;
            })
            ->make(true);
    }
    
}
