<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersController extends Controller
{
//    public function createElement(Request $request){
//        $result=DB::table("users")
//            ->insert(["code"=>$request->code,"leader_id"=>$request->leader_id,"district_id"=>$request->district_id,"postal_code"=>$request->postal_code,"firstname"=>$request->firstname,"lastname"=>$request->lastname,"login"=>$request->login,"password"=>$request->password,"address"=>$request->address,"city"=>$request->city,"phone"=>$request->phone,"entry_date"=>$request->entry_date,"mail_address"=>$request->mail]);
//        if($result)
//        {
//            $id=DB::table("users")->where("login","=",$request->login)->select(["id"])->get()[0]["id"];
//            if($request->role<=3)
//            {
//                $result=$result and DB::table("applications_users")
//                    ->insert([["user_id"=>$id,"application_id"=>1,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1],["user_id"=>$id,"application_id"=>2,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1]]);
//
//            }
//            elseif ($request->role<=5)
//            {
//                $result=$result and DB::table("applications_users")
//                    ->insert(["user_id"=>$id,"application_id"=>3,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1]);
//            }
//            else
//            {
//                $result=$result and DB::table("applications_users")
//                    ->insert([["user_id"=>$id,"application_id"=>1,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1],["user_id"=>$id,"application_id"=>2,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1],["user_id"=>$id,"application_id"=>3,"role_id"=>$request->role,"creation_date"=>"NOW()","activated"=>1]]);
//            }
//            return $result;
//        }
//        else
//        {
//            return false;
//        }
//
//
//    }
//
//    public function updateAll(Request $request){
//
//    }
//
//    public function getAll(){
//        return DB::table("users")->select()->get();
//    }
//
//    public function updateByRole(Request $request){
//        $querry="";
//    }
//
//    public function getByRole(Request $request){
//        $request->role_id;
//        DB::table("users")
//            ->leftJoin("","","=","")
//
//    }

//
//    public function updateById(Request $request){
//        $querry="";
//    }

//    public function getById(Request $request)
//    {
//        return DB::table("users")
//            ->leftJoin("users as leader", "users.leader_id", "=", "leader.id")
//            ->join("sector_districts", "users.district_id", "=", "sector_districts.id")
//            ->join("sectors", "", "=", "")
//            ->leftJoin("users as district_leader", "district_leader.id", "=", "sector_districts.leader_id")
//            ->leftJoin("users as sector_leader", "sector_leader.id", "=", "sectors.leader_id")
//            ->leftJoin("applications_users as 'aur_visits'", "aur_visits.user_id", "=", "users.id")
//            ->leftJoin("applications_users as 'aur_fees'", "aur_fees.user_id", "=", "users.id")
//            ->leftJoin("applications_users as 'aur_repay'", "aur_repay.user_id", "=", "users.id")
//            ->leftJoin("roles as 'r_visits'", "r_visits.id", "=", "IF(aur_visits.activated=1,aur_visits.role_id,0)")
//            ->leftJoin("roles as 'r_fees'", "r_fees.id", "=", "IF(aur_fees.activated=1,aur_fees.role_id,0)")
//            ->leftJoin("roles as 'r_repay'", "r_repay.id", "=", "IF(aur_repay.activated=1,aur_repay.role_id,0)")
//            ->where("users.id", "=", $request->user_id)
//            ->where("aur_visits", "=", 1)
//            ->where("aur_fees", "=", 2)
//            ->where("aur_repay", "=", 3)
//            ->select(["users.`code`",
//                "users.`leader_id`", "leader.firstname AS 'leader_firstname'", "leader.firstname AS 'leader_firstname'",
//                "users.`district_id`", "sector_districts.name as 'district_name'",
//                "district_leader.id as 'district_leader_id'", "district_leader.firstname as 'district_leader_firstname'", "district_leader.lastname as 'district_leader_lastname'", "district_leader.mail_address AS 'district_leader_mail'",
//                "sectors.id as 'sector_id'", "sectors.name AS 'sector_name'",
//                "sector_leader.id as 'sector_leader_id'", "sector_leader.firstname as 'sector_leader_firstname'", "sector_leader.lastname as 'sector_leader_lastname'", "sector_leader.mail_address AS 'sector_leader_mail'",
//                "users.postalCode", "users.firstname as 'user_firstname'", "users.lastname as 'lastname'", "users.login", "users.password", "users.address", "users.city", "users.`phone`", "users.`release_date`", "users.`entry_date`", "users.`token`", "users.`timespan`", "users.`mail_address` as 'user_mail'",
//                "IF(aur_visits.activated=1,aur_visits.role_id,0) as 'visits_role_id'", "r_visits.name as 'visits_role_name'", "IF(aur_fees.activated=1,aur_fees.role_id,0) as 'fees_role_id'", "r_fees.name as 'fees_role_name'", "IF(aur_repay.activated=1,aur_repay.role_id,0) as 'repay_role_id'", "r_repay.name as 'repay_role_name'"])
//            ->get()[0];
//    }

    public function getById(User $user)
    {
        if($user->leader_id == null)
        {
            $user->leader_id = 0;
        }
        if($user->phone == null)
        {
            $user->phone = 0;
        }
        if($user->release_date == null)
        {
            $user->release_date = 0;
        }
        if($user->token == null)
        {
            $user->token = 0;
        }
        if($user->timespan == null)
        {
            $user->timespan = 0;
        }
        return $user;
    }

    public function getAll()
    {
        $accountants = User::select("*")->where("role_id", "=", 4)->orWhere("role_id", "=", 5)->get();

        foreach($accountants as $user)
        {
            if($user->leader_id == null)
            {
                $user->leader_id = 0;
            }
            if($user->phone == null)
            {
                $user->phone = 0;
            }
            if($user->release_date == null)
            {
                $user->release_date = 0;
            }
            if($user->token == null)
            {
                $user->token = 0;
            }
            if($user->timespan == null)
            {
                $user->timespan = 0;
            }
        }
        return $accountants;
    }

    public function update(Request $request, $id)
    {
        $options = [
            'cost' => 11,
        ];

        $user = User::find($id);
        $user->password_hash($request->input("password"), PASSWORD_BCRYPT, $options);
        $user->save();
    }

//    public function enable(Request $request){
//        return DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",$request->appli_id)->update(["activated"=>1]);
//    }
//
//    public function disable(Request $request){
//        return DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",$request->appli_id)->update(["activated"=>0]);
//    }
//
//    public function login(Request $request){
//        $result=DB::table("logs")->insert(["id"=>"NULL","user_id"=>$request->user_id,"login_date"=>"NOW()","logout_date"=>"NULL","application_id"=>$request->appli_id]);
//    }
//
//    public function logout(Request $request){
//        $result=DB::table("logs")
//            ->where("user_id","=",$request->user_id)
//            ->where("application_id","=",$request->appli_id)
//            ->whereNull("logout_date")
//            ->update(["logout_date"=>"NOW()"]);
//    }
//
//    public function updateLastLoginByUser(Request $request){
//        return DB::table("logs")
//            ->update(["user_id"=>$request->newUser_id,"login_date"=>$request->newLoginDate,"logout_date"=>$request->newLogoutDate,"application_id"=>$request->newAppli_id])
//            ->where("user_id","=",$request->user_id)
//            ->where("application_id","=",$request->appli_id)
//            ->where("login_date","=","max(login_date)");
//    }
//
//    public function getLastLoginByUser(Request $request){
//        return DB::table("logs")->where("user_id","=",$request->user_id)->where("application_id","=",$request->appli_id)->orderBy("login_date","desc")->limit(1)->select()->get()[0];
//    }
//
//    public function getAverageTimeByUser(Request $request){
//        return DB::table("logs")
//            ->where("application_id","=",$request->appli_id)
//            ->where("user_id","=",$request->user_id)
//            ->select(["AVG(TIMEDIFF(logs.login_date,logs.logout_date)) AS 'average_time'"])
//            ->get()[0]["average_time"];
//    }
}
