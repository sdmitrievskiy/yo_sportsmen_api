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

    /////////////////////////////
    //аутентификация
    /////////////////////////////
    public function auth(Request $request)
    {
        //получаем vkid от клиента
        $vkId = $request->input('vkid');
        if (!$vkId) $vkId = 1;

        $user = DB::table('users')->where('vk_id', $vkId)->first();

        //если пользователь есть, отправляем о нем инфу
        if (!$user) {
            DB::table('users')->insert(
                ['vk_id' => $vkId, 'date_joined' => date_create()->format('Y-m-d H:i:s')]
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
            ->select('sport_types.name')
            ->get();

        return response()->json($sports);
    }

    //////////////////////////////////////
    //Установить виды спорта пользователя
    //////////////////////////////////////
    //to-do: при обновлении видов спорта нужно полностью удалить связи
    //и сформировать их заново на основе json объекта
    public function setUserSportTypes(Request $request, $userId)
    {
        $sports = $request->json()->all();
        
        foreach($sports as $sport) {
            
            //получаем связь
            $relation = DB::table('sport_types_to_users')
                            ->where('sport_type_id',$sport['sport_type_id'])
                            ->where('user_id',$userId)
                            ->select('sport_type_id')
                            ->first();

            //если связи нет
            if (!$relation) { 
                //добавляем связь
                DB::table('sport_types_to_users')->insert(
                    ['user_id' => $userId, 'sport_type_id' => $sport['sport_type_id']]
                );
            }

        }

    }
}
