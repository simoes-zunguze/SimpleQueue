<?php
namespace App\Http\Controllers;

use App\Events\QueueSendMessage;
use App\Models\Config;
use App\Models\Person;
use App\Models\Priority;
use App\Models\Queue;
use App\Models\Ticket;
use App\Models\TicketHistory;
use App\Models\Waiting;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Null_;
use Validator;
use DB;
use Illuminate\Support\Carbon;

use function React\Promise\Stream\first;

class TicketController extends Controller
{
    private const SNOOZED_STATUS = 3;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::all();
        $queues = Queue::all();

        $priorities = Priority::all();

        return view("ticket.index", compact("priorities", "queues"));
        // return csrf_token();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $person = NULL;
        $validator = Validator::make($request->all(), [
            'queue' => 'required|numeric|exists:queues,id'

        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()]);
        }

        // Fetch the global configurations
        $CONGIGURATIOS = Config::find(1);
        $ticket = new Ticket();
        // $ticket->person_id = $person->id;
        $ticket->ticket_code =  $request->get("queue");
        $ticket->priority_id = 1; // 1 - Normal
        $ticket->period_id = $CONGIGURATIOS->current_period;
        $ticket->status_id =  1;
        $ticket->queue_id = $request->get("queue");
        $ticket->save();

        // Register the history log
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'queue_id' => $ticket->queue_id
        ]);

        event(new QueueSendMessage($ticket->id));

        return $ticket;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->queue;
        $ticket->person;
        return $ticket;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Insert new ticket in the queue
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:32|min:2',
            'phone' => 'nullable|digits:9',
            'priority' => 'required|numeric|exists:priorities,id',
            'queue' => 'required|numeric|exists:queues,id'

        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()]);
        }

        // Fetch the global configurations
        $CONGIGURATIOS = Config::find(1);
        $ticket = NULL;

        //Is th
        if (!($ticket = Ticket::where("id", $id)->where("status_id", "!=", 5)->first())) {
            return ['errors' => ['closed' => 'The ticket does not exists or was closed']];
        }

        // Update person's details
        $person = $this->updatePerson($ticket->person_id, $request);
        $ticket->priority_id = $request->get("priority");
        $ticket->status_id = 2;
        $ticket->queue_id = $request->get('queue');
        $ticket->save();

        // Register the history log
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'queue_id' => $ticket->queue_id
        ]);

        // Send event
        return [$ticket];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function getTicket($queue_id)
    {
        $person = NULL;
        // Validate the request
        $validator = Validator::make(['queue' => $queue_id], [
            'queue' => 'required|numeric|exists:queues,id'

        ]);
        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()]);
        }

        // Fetch the global configurations
        $CONGIGURATIOS = Config::find(1);

        $ticket = new Ticket();
        // $ticket->person_id = $person->id;
        $ticket->ticket_code = $queue_id;
        $ticket->priority_id = 1;
        $ticket->period_id = $CONGIGURATIOS->current_period;
        $ticket->status_id =  1;
        $ticket->queue_id = $queue_id;
        $ticket->save();

        // Register the history log ´´´
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'queue_id' => $ticket->queue_id
        ]);
        event(new QueueSendMessage(['queue' => $ticket->queue_id]));

        return response($ticket, 201);
    }

    public function ticketList($id)
    {
        $tickets = Ticket::where("queue_id", $id)
            ->where('status_id', '=', 1)
            ->orderBy('updated_at')->take(4)->get();
        $queue_summary = $this->queueSummary($id);

        return ['tickets' =>  $tickets, 'queue_summary' => $queue_summary];
    }

    public function queueSummary($queue_id)
    {
        $waiting = DB::table('tickets')->where('queue_id', $queue_id)
            ->where('status_id', 1)
            ->get()->count();

        $closed = DB::table('tickets')->where('queue_id', $queue_id)
            ->where('status_id', 5)
            // ->whereDate('updated_at', Carbon::today())
            ->get()->count();

        $total = DB::table('ticket_histories')->where('queue_id', $queue_id)
            // ->whereDate('updated_at', Carbon::today())
            ->get()->count();

        $absent = DB::table('tickets')->where('queue_id', $queue_id)
            ->where('status_id', 4)
            // ->whereDate('updated_at', Carbon::today())
            ->get()->count();

        $current_ticket = DB::table('tickets')->where('queue_id', $queue_id)
            ->where('status_id', 2)
            ->orderBy('updated_at')->first();
        if ($current_ticket == NULL) {
            $current_ticket = "--";
        } else {
            $current_ticket =  $current_ticket->id;
        }
        return ["waiting" => $waiting, 'closed' => $closed, 'total' => $total, 'absent' => $absent, 'current_ticket' => $current_ticket];
    }

    public function redirectTicket(Request $request, $queue_id)
    {
        $validator = Validator::make(
            [
                'queue_id' => $queue_id,
                'redirect_to_queue' => $request->redirect_to_queue
            ],
            [
                'queue_id' => 'required|numeric|exists:queues,id',
                'redirect_to_queue' => 'required|numeric|exists:queues,id'
            ]
        );

        // If the queues id's are equal
        if ($queue_id == $request->redirect_to_queue) {
            return response(['errors' => 'Cant not redirect to the same queue'], 422);
        }

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()], 422);
        }

        $ticket = Ticket::where('queue_id', $queue_id)->where('status_id', 2)->orderBy('updated_at')->first();
        if ($ticket == NULL) {
            return response(['errors' => 'Call a ticket first'], 422);
        }

        $old_queue = $ticket->queue_id;
        //Redirecting the ticket to queue_id
        $ticket->queue_id = $request->redirect_to_queue;
        $ticket->status_id = 1;
        $ticket->save();
        TicketHistory::create([
            'ticket_id' => $ticket->id,
            'queue_id' => $request->redirect_to_queue
        ]);
        $ticket->queue;

        event(new QueueSendMessage(['queue' => $old_queue])); // Event to Old Queue
        event(new QueueSendMessage(['queue' => $ticket->queue_id])); // Event to Redirected Queue

        return $ticket;
    }

    public function callTicket($queue_id)
    {
        $snoozed_list = $this->callSnoozedTicket($queue_id);
        if ($snoozed_list) {
            return $snoozed_list;
        }
        $current_ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', 2)
            ->first();
        if ($current_ticket) {
            return ["errors" => "Close the ticket before call the next"];
        }

        $ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', 1)
            ->orderBy('updated_at')
            ->first();

        if ($ticket) {
            $ticket->status_id = 2;
            $ticket->save();
            event(new QueueSendMessage(['queue' => $ticket->queue_id]));

            return $ticket;
        }else{
            return response(['errors' =>  'The queue is empty'], 422);
        }
    }

    public function callSnoozedTicket($queue_id){

        $current_ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', 2)
            ->first();
        if ($current_ticket) {
            return ["errors" => "Close the ticket before call the next"];
        }

        $ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', self::SNOOZED_STATUS)
            ->orderBy('waiting_call')
            ->first();

        if ($ticket) {
            // If wating time is not over
            if (Carbon::now()->lt($ticket->waiting_call)) {
                return null;
            }

            $ticket->status_id = 2;
            $ticket->save();
            event(new QueueSendMessage(['queue' => $ticket->queue_id]));

            return $ticket;
        }else{
            return null;
        }
    }

    public function closeTicket($queue_id)
    {
        $ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', 2)
            ->first();
        if($ticket){
            $ticket->status_id = 5;
            $ticket->save();

            event(new QueueSendMessage(['queue' => $ticket->queue_id])); // Event current Queue
            return $ticket;
        }else{
            return response(['errors' =>  'Call a ticket first'], 422);
        }

    }

    public function absentTicket($queue_id)
    {
        $ticket = Ticket::where('queue_id', $queue_id)
            ->where('status_id', 2)
            ->orderBy('updated_at')
            ->first();

        if($ticket){
            $ticket->status_id = 4;
            $ticket->save();

            event(new QueueSendMessage(['queue' => $ticket->queue_id])); // Event current Queue
            return $ticket;
        }else{
            return response(['errors' => 'Call a ticket first'], 422);
        }

    }

    // Private functions
    private function createPerson($request)
    {
        $person = new Person();
        $person->name = $request->get("name");
        $person->phone = $request->get("phone");
        $person->save();

        return $person;
    }


    private function updatePerson($id, $request)
    {
        $person = NULL;
        if (($person = Person::where('phone', $request->get('phone'))->first())) {
            $person->name = $request->get("name");
        } else {
            $person = Person::find($id);
            $person->name = $request->get("name");
            $person->phone = $request->get("phone");
        }

        $person->save();

        return $person;
    }

    public function token()
    {
        return csrf_token();
    }

    public function postpone(Request $request, $id){

        $validator = Validator::make([
            'id' => $id,
            'minutes' => $request->minutes
        ],
        [
            'id' => 'required|exists:queues,id',
            'minutes' => 'required|numeric|gte:10|lte:60',
        ]
        );

        if ($validator->fails()) {
            return response(['errors' =>$validator->errors()], 422);
        }

        $ticket  = Ticket::where('queue_id', $id)->where('status_id', 2)->first();
        if (!$ticket) {
            return response(['errors' => 'Call a ticket before snooze'], 422);
        }
        $ticket->status_id = self::SNOOZED_STATUS;
        $ticket->waiting_call = Carbon::now()->addMinutes($request->minutes);
        $ticket->save();

        event(new QueueSendMessage(['queue' => $ticket->queue_id]));

        return $ticket;
    }

    public function queueSnoozedList(Request $request, $id){

        return Ticket::where('queue_id', $id)->get();
    }

    public function allSnoozedList(){

        return Ticket::where('status_id', self::SNOOZED_STATUS)->orderBy('waiting_call')->get();
    }
}
