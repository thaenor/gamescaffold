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
    public function fetchTicketJson($startTime, $endTime){
        $carbonStart = $this->validateTime($startTime);
        $carbonEnd = $this->validateTime($endTime);
        $tickets = Ticket::where('created_at', '>=', $carbonStart )->where('created_at', '<=', $carbonEnd )->where('state','=','open')->get();
        return $tickets->toJson();
    }

    public function fetchTicketJsonDefault(){
        $start = new Carbon('first day of February 2015', 'Europe/London');
        $end = new Carbon('last day of February 2015', 'Europe/London');
        $tickets = Ticket::where('created_at', '>=', $start )->where('created_at', '<=', $end )->where('state','=','open')->get();
        return $tickets->toJson();
    }

    public function validateTime($date){
        return $ValidDate = Carbon::createFromFormat('m-d-Y', $date);
    }

}
