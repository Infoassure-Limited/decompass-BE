<?php

namespace App\Http\Controllers;

use App\Models\EmailMessage;
use App\Models\EmailReply;
use Illuminate\Http\Request;

class AppMailingsController extends Controller
{
    //
    public function inbox()
    {
        $user_email = $this->getUser()->email;
        $emails = EmailMessage::where(function ($query) use ($user_email) {
            return $query->where('recipients', 'LIKE', '%' . $user_email . '%')
                ->orWhere(function ($p) use ($user_email) {
                    return $p->where('sender', $user_email)
                        ->where('has_reply', 1);
                });
        })
            ->where(function ($query) use ($user_email) {
                return $query->where('recipient_delete', 'NOT LIKE', '%' . $user_email . '%')
                    ->orWhere('recipient_delete', '=', NULL);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $type = 'inbox';
        return response()->json(compact('emails', 'type'), 200);
    }
    public function sent()
    {
        $user_email = $this->getUser()->email;
        $emails = EmailMessage::where('sender', $user_email)
            ->orderBy('created_at', 'DESC')
            ->paginate(10);
        $type = 'sent';
        return response()->json(compact('emails', 'type'), 200);
    }

    public function messageDetails(Request $request, EmailMessage $message)
    {
        $user_email = $this->getUser()->email;
        $message_details = $message->with([
            'replies' => function ($q) {
                return $q->orderBy('id', 'ASC');
            }
        ])->find($message->id);
        //record that this user has read the message;
        $read_by = ($message_details->read_by != NULL) ? $message_details->read_by : [];
        $read_by[] = $user_email;
        $message_details->read_by = array_unique($read_by);
        $message_details->save();
        return $this->render(compact('message_details'));
    }

    public function compose(Request $request)
    {
        $user = $this->getUser();
        $message = new EmailMessage();
        $recipients = $request->new_recipients;
        $message->sender_name = $user->name;
        $message->sender = $user->email;
        $message->recipients = $recipients;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->save();
        return response()->json(compact('message'), 200);
    }

    public function reply(Request $request, EmailMessage $message)
    {
        $user = $this->getUser();
        $reply = new EmailReply();
        $reply->sender_name = $user->name;
        $reply->sender = $user->email;
        $reply->email_message_id = $message->id;
        $reply->message = $request->message;
        $reply->save();
        $message->has_reply = 1;
        $message->save();
        $message_details = $message->with([
            'replies' => function ($q) {
                $q->orderBy('created_at');
            }
        ])->find($message->id);
        return response()->json(compact('message_details'), 200);
    }

    public function destroy(Request $request, EmailMessage $message)
    {
        $message->delete();
        return response()->json([], 204);
    }
}
