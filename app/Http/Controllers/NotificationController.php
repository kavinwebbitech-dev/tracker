<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{

    public function ViewNotification()
    {
        $notification = Notification::where('receiver_id', Auth::user()->id)->latest()->get();

        foreach ($notification as $key => $value) {
            $notifi_update = Notification::find($value->id);
            $notifi_update->status = 1;
            $notifi_update->save();
        }

        return view('staff.view', compact('notification'));
    }

    public function NotificationUpdate(Request $request)
    {
        // dd($request->all());
        $notification = Notification::find($request->id);
        $notification->status = 1;
        if ($notification->save()) {
            $url = route('staff.view.task', $notification->task_id);
            return $url;
        }
    }


    public function ViewSubNotification()
    {
        $notification = Notification::where('receiver_id', Auth::user()->id)->latest()->get();
        foreach ($notification as $key => $value) {
            $notifi_update = Notification::find($value->id);
            $notifi_update->status = 1;
            $notifi_update->save();
        }
        return view('sub_admin.view', compact('notification'));
    }

    public function NotificationSubUpdate(Request $request)
    {
        // dd($request->all());
        $notification = Notification::find($request->id);
        $notification->status = 1;
        if ($notification->save()) {
            $url = route('sub.task.status', $notification->task_id);
            return $url;
        }
    }

    public function ViewAdminNotification()
    {
        $notification = Notification::where('receiver_id', Auth::user()->id)->latest()->get();
        foreach ($notification as $key => $value) {
            $notifi_update = Notification::find($value->id);
            $notifi_update->status = 1;
            $notifi_update->save();
        }
        return view('admin.view', compact('notification'));
    }

    public function NotificationAdminUpdate(Request $request)
    {
        // dd($request->all());
        $notification = Notification::find($request->id);
        $notification->status = 1;
        if ($notification->save()) {
            $url = route('admin.task.status', $notification->task_id);
            return $url;
        }
    }

    // public function ViewAdminNotification()
    // {
    //     $notification = Notification::where('receiver_id', Auth::user()->id)->get();
    //     return view('admin.view', compact('notification'));
    // }

    public function NotificationSecondAdminUpdate(Request $request)
    {
        // dd($request->all());
        $notification = Notification::find($request->id);
        $notification->status = 1;
        if ($notification->save()) {
            $url = route('secon.admin.task.status', $notification->task_id);
            return $url;
        }
    }


}
