<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use DateTime;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpSpec\Exception\Exception;

require('otrsDAL.php');

class TicketController extends Controller {

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index']);
    }
    
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
     * Sync function that's run daily. Preferably in the afternoon (if connected to development server)
     * this compares the last ticket id between DB's and migrates any new tickets
     * It also calculates the points in the tickets and attributes them to players and teams.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sync(){
        try{
            $lastTicketId = Ticket::take(1)->orderBy('id','desc')->first()->id;
            syncDBs($lastTicketId); //202325
        } catch(exception $e){
            Log::error('Overall sync error: '.$e);
            exit(1);
        }
        try{
            $lastId = Storage::disk('local')->get('lastid.txt');
            $newLastId = updateChangedTickets($lastId);
            Storage::disk('local')->put('lastid.txt', $newLastId);
        } catch(exception $e){
            Log::error('error updating ticket state: '.$e);
            exit(1);
        }
        echo "sync done";
    }

    /*public function calculatePoints(){
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        echo 'working...<hr/> <img src="http://media1.giphy.com/media/4bAEIAB84zPwc/giphy.gif"/><br/>';
        try{
            $lastid = Storage::disk('local')->get('lastIdToCalculate.txt');
        } catch(exceptio $e){
            Log::error('unable to read last id. Unable to calculate points');
            exit(1);
        }*/

        /*$start = Carbon::createFromDate(2015,06,01, 'GMT');
        $end = Carbon::now();
        $tickets = Ticket::getAllTicketsBetween($start,$end);
        foreach($tickets as $ticket){
            $ticket->updateTicketPoints($ticket);
            echo "ticket updated ".$ticket->id." | ".$ticket->points."<br/>";
        }*/

        /*$chunkSize = 1000;
        $totalTickets = Ticket::where('id','>=',$lastid)->count();
        $chunks = floor($totalTickets / $chunkSize);
        for ($chunk = 0; $chunk < $chunks; $chunk++) {
            $offset = $chunk * $chunkSize;
            $tickets = Ticket::where('state','=',"closed")->skip($offset)->take($chunkSize)->get();
            foreach($tickets as $ticket){
                $ticket->updateTicketPoints($ticket);
            }
        }
        return "done";
    }*/
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $time = new DateTime('first day of this month');
        $start = Carbon::instance($time);
        $end = Carbon::now();
        $tickets = Ticket::getAllTicketsBetween($start,$end);
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
