<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        activity('Messages')
            ->log("Accessed Messages")->causer(request()->user());
      //  $notifications = \request()->user()->notifications()->get();

        return view('messages.index')->with([
            'messages' => Message::where(['status'=>1])->orWhere(['status'=>0])->orderBy('status','desc')->get(),
            'cpage' => "messages"
        ]);
    }


    public function unreadNotifications()
    {
        return view('messages.unread')->with([
            'messages' => Message::where(['status'=>1])->orderBy('id','desc')->get(),
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
