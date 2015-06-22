<?php namespace App\Http\Controllers;

use App\Group;
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
use Illuminate\Support\Facades\DB;
require('otrsDAL.php');

class TicketController extends Controller {

    /**
     * Manual migration method.
     * This is call and import, from OTRS,
     * all the tables for Tickets, users, groups
     * and their relations. This method only needs
     * to be called in case the local DB is cleaned
     */
    public function manualMigration(){
        manualMigration();
    }


    /**
     * Sync funtion that's run daily. Preferably in the afternoon (if connected to development server)
     * this compares the last ticket id between DB's and migrates any new tickets
     * It also calculates the points in the tickets and attributes them to players and teams.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sync(){
        $lastTicketId = Ticket::take(1)->orderBy('id','desc')->first()->id;
        try{
            syncDBs($lastTicketId); //202325
        } catch(exception $e){
            Log::error('Overall sync error: '.$e);
            exit(1);
        }
        try{
            $toUpdate = Ticket::getAllOpenTickets();
            foreach($toUpdate as $ticket){
                syncSingleTicket($ticket);
            }
        } catch(exception $e){
            Log::error('error updating open ticket state: '.$e);
            exit(1);
        }
        return "done";
    }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $time = new DateTime('first day of this month');
        $tickets = Ticket::where('created_at', '>=', $time )->get();
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
