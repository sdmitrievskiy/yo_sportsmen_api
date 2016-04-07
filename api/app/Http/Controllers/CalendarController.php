<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;

class CalendarController extends Controller {
	public function addCalendar(Request $request)
	{
		//получаем googleApi от клиента
        $googleApi = $request->input('googleApi');
        $userId    = $request->input('user_id'); 
        if (!$googleApi) $googleApi = 1;

        $calendar = DB::table('calendars')->where('googleApi', $googleApi)->where('user_id', $userId)->first();

        //если календарь есть, отправляем о нем инфу
        //если нету, создаем новый
        if (!$calendar) {
            $calendarId = DB::table('calendars')->insertGetId(
                ['user_id' => $userId, 'date_created' => date_create()->format('Y-m-d H:i:s'),'googleApi' => $googleApi]
            );
            $calendar = DB::table('calendars')->where('calendar_id', $calendarId)->first();
            
        }

        return response()->json($calendar);
	}
}