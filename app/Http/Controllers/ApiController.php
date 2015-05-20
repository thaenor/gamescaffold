<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Ticket;
use App\Group;
use Illuminate\Http\Request;

class ApiController extends Controller {

    /**
     * Returns JSON with all group information
     * @return mixed
     */
    public function fetchGroupJson(){
        $groups = Group::all();
        return $groups->toJson();
    }

    /**
     * Returns JSON with all ticket information
     * @return mixed
     */
    public function fetchTicketJson(){
        $carbon = new Carbon('first day of February 2015', 'Europe/London');
        $tickets = Ticket::where('created_at', '>=', $carbon )->where('state','=','open')->get();
        return $tickets->toJson();
    }

}
