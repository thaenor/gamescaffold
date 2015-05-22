<?php namespace App\Http\Controllers;

use App\Http\Requests;
//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\App;
use App\User;
use Carbon\Carbon;
use App\Ticket;
//use App\User;
//use App\Group;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TicketController extends Controller {

    public function CalculatePoints(){
        $ticket = new Ticket();
        return $ticket->setTicketPoints();
    }

    public function CalculatePenalties(){


        /* !! WWHAT IS THIS: this snipped of code is experimental and left for latter referal.
            I'm fetching 1 ticket from last month that's resolved, then grabbing the assigned user
            Eloquent model, and THEN the respective team. For some reason the team comes as null
         */
        /*test area
        $carbon = new Carbon('first day of February 2015', 'Europe/London');
        $t = Ticket::where('created_at', '>=', $carbon )->where('state','=','Resolved')->get()->take(1);
        $player = new User();

        $player->find($t[0]->user_id);
        $teams = $player->groups;
        return var_dump($teams);
        test area*/

        //$ticket = new Ticket();
        //return $ticket->setTicketPenalties();
    }

    /**
     * This is a migration function that gets some tickets from OTRS
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function firstMigrate(){
        require('otrsDAL.php');
        addTicketsTable();
        return redirect('tickets/');
    }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//$tickets = Ticket::all();
        $carbon = new Carbon('first day of February 2015', 'Europe/London');
        $tickets = Ticket::where('created_at', '>=', $carbon )->where('state','=','open')->get();
		return view('tickets.index', compact('tickets'));
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('tickets.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function store(Request $request)
	{
		$ticket = new Ticket();

		$ticket->priority = $request->input("priority");
        $ticket->state = $request->input("state");
        $ticket->sla = $request->input("sla");
        $ticket->timeout = $request->input("timeout");

		$ticket->save();

		return redirect()->route('tickets.index')->with('message', 'Item created successfully.');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$ticket = Ticket::findOrFail($id);

		return view('tickets.show', compact('ticket'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$ticket = Ticket::findOrFail($id);

		return view('tickets.edit', compact('ticket'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @param Request $request
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$ticket = Ticket::findOrFail($id);

		$ticket->priority = $request->input("priority");
        $ticket->state = $request->input("state");
        $ticket->sla = $request->input("sla");
        $ticket->timeout = $request->input("timeout");

		$ticket->save();

		return redirect()->route('tickets.index')->with('message', 'Item updated successfully.');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ticket = Ticket::findOrFail($id);
		$ticket->delete();

		return redirect()->route('tickets.index')->with('message', 'Item deleted successfully.');
	}

}