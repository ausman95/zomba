<?php

namespace App\Http\Controllers;

use App\Models\Labourer;
use App\Models\Member;
use App\Models\Message;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    protected $smsService;


    public function index()
    {
        activity('Messages')
            ->log("Accessed Messages")->causer(request()->user());
        //  $notifications = \request()->user()->notifications()->get();

        return view('messages.index')->with([
            'messages' => Message::orderBy('id','desc')->get(),
            'cpage' => "messages"
        ]);
    }
    public function create()
    {
        // Set the current page for view reference
        $cpage = 'messages';

        // Pass the $cpage variable to the view using compact
        return view('messages.create', compact('cpage'));
    }
    public function store(Request $request, ReceiptController $receiptController)
    {
        // Validate the incoming request
        $request->validate([
            'message' => 'required|string|max:160', // Validate message with a max length of 160 characters
        ]);

        // Retrieve labourers' phone numbers from the database
        $mobileNumbers = Member::pluck('phone_number')->toArray(); // Assuming Labourer model has the 'phone_number' field
        // Prepare data for storing each SMS record
        $message = $request->input('message');

        $data = [
            'created_by' => $request->user()->id, // Track the user who sends the SMS
            'body' => $message,
            'status' => 'sent', // Default to 'sent', update if failed
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Send SMS to each valid number and log the SMS message in the database
        foreach ($mobileNumbers as $mobileNumber) {
            // Trim any spaces from the mobile number
            $mobileNumber = trim($mobileNumber);

            // Skip numbers that don't start with +2659, 09, 9, or 2659
            if (!preg_match('/^(\\+2659|2659|09|9)/', $mobileNumber)) {
                continue; // Skip invalid number and move to next
            }

            // Remove leading +265, 265, or 0
            if (preg_match('/^(\\+265|265|0)/', $mobileNumber)) {
                $mobileNumber = preg_replace('/^(\\+265|265|0)/', '', $mobileNumber);
            }

            // Ensure the number starts with 9 and is exactly 9 digits long
            if (!preg_match('/^9[0-9]{8}$/', $mobileNumber)) {
                continue; // Skip numbers that are not 9 digits starting with 9
            }

            // Send SMS using the SMS service
            if ($receiptController->sendSms($mobileNumber, $message)) {
                // Log the message in the Message table after successful sending
                Message::create($data);
            } else {
                // Handle failed SMS sending, mark as 'failed' in the status
                Message::create(array_merge($data, ['phone_number' => $mobileNumber, 'status' => 'failed']));
            }
        }

        // Redirect or return with a success message
        return redirect()->route('messages.unread')->with('success-notification', 'Bulk SMS sent successfully!');
    }


    public function unreadNotifications()
    {
        return view('messages.unread')->with([
            'messages' => Message::orderBy('id','desc')->get(),
            'cpage' => "messages"
        ]);
    }


    public function readNotification($notification_id)
    {
        activity('Messages')
            ->log("Read a Message")->causer(request()->user());
        DB::table('messages')
            ->where(['id'=>$notification_id])
            ->update(['status' => 0]);
        return view('messages.unread')->with([
            'messages' => Message::where(['status'=>1])->orderBy('id','desc')->get(),
            'cpage' => "messages"
        ]);
    }


    public function markAllAsRead()
    {
        activity('Messages')
            ->log("Marked all messages as read")->causer(request()->user());
        DB::table('messages')
            ->where(['status'=>1])
            ->update(['status' => 0]);

        return back()->with(['success-notification' => "All notifications marked as read."]);
    }
    public function destroy(Message $message)
    {
        try{
            $message->delete();
            activity('Messages')
                ->log("Deleted a message")->causer(request()->user());
            return redirect()->route('messages.index')->with([
                'success-notification'=>"Message successfully Deleted"
            ]);

        }catch (\Exception $exception){
            return redirect()->route('messages.index')->with([
                'error-notification'=>"Something went Wrong ".$exception.getMessage()
            ]);
        }
    }
}
