<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use DB;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /////////////////////////////////////////////////////////////////////////////
    //аутентификация
    //////////////////////////////////////////////////////////////////////////////
    public function auth(Request $request)
    {
        //получаем vkid от клиента
        $vkId = $request->input('vkid');
        if (!$vkId) $vkId = 1;

        $user = DB::table('users')->where('vk_id', $vkId)->first();

        //если пользователь есть, отправляем о нем инфу
        if (!$user) {
            DB::table('users')->insert(
                ['vk_id' => $vkId, 'date_joined' => date_create()->format('Y-m-d H:i:s'),'last_update' => date_create()->format('Y-m-d H:i:s')]
            );
            $user = DB::table('users')->where('vk_id', $vkId)->first();
            
        }

        return response()->json($user);
    }

    ///////////////////////////////////
    //Получить виды спорта пользователя
    ///////////////////////////////////
    public function getUserSportTypes($userId)
    {
        //херачим запрос
        $sports = DB::table('users')
            ->where('users.user_id',$userId)
            ->join('sport_types_to_users', 'users.user_id', '=', 'sport_types_to_users.user_id')
            ->join('sport_types', 'sport_types_to_users.sport_type_id', '=', 'sport_types.sport_type_id')
            ->select('sport_types.sport_type_name')
            ->get();

        return response()->json($sports);
    }

    //////////////////////////////////////
    //Установить виды спорта пользователя
    //////////////////////////////////////
    public function setUserSportTypes(Request $request, $userId)
    {
        $sports = $request->json()->all();
        
        //удаляем текущие связи
        DB::table('sport_types_to_users')->where('user_id', $userId)->delete();

        //добавляем новые связи
        foreach($sports as $sport) {

            DB::table('sport_types_to_users')->insert(
                    ['user_id' => $userId, 'sport_type_id' => $sport['sport_type_id']]
            );
        }

    }

    //////////////////////////////////////
    //Обновить данные пользователя
    //////////////////////////////////////    
    public function updateUser(Request $request, $userId)
    {
        $data = $request->json()->all();
        //обновляем vk_id
        if ( isset($data['vk_id']) ) {
            DB::table('users')->where('user_id', $userId)
                              ->update(['vk_id'       => $data['vk_id'],
                                         'last_update' => date_create()->format('Y-m-d H:i:s') 
                                ]);
        }

        //обновляем google_account
        if ( isset($data['google_account']) ) {
            DB::table('users')->where('user_id', $userId)
                              ->update(['google_account' => $data['google_account'],
                                        'last_update'    => date_create()->format('Y-m-d H:i:s') 
                                ]);
        }

        //получаем пользователя и отправляем объект
        $user = DB::table('users')->where('user_id', $userId)->first(); 
        return response()->json($user);
    }
}
