<?php


namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class VisitsController extends Controller
{


    public function createElement(Request $request)
    {
        $query="INSERT INTO `visits`(`id`, `practitioner_id`, `user_id`, `attended_date`, `visit_state_id`) VALUES (NULL,".$request->doctor_id.",".$request->user_id.",".$request->date.",1)";
        $result=DB::insert($query);
        return $result;
    }

    public function updateAll(Request $request)
    {
        $dataToGet=["visits"=>["practitioner_id"=>"practitioner_id","user_id"=>"user_id","attendedDate"=>"attended_date","visitState_id"=>"visit_state_id"],
            "visit_reports"=>["visitReports_visit_id"=>"visit_id","creationDate"=>"creation_date","visitReports_comment"=>"comment","starScore"=>"star_score","visitReportState_id"=>"visit_report_state_id"],
            "visit_modifications"=>["visitModifications_user_id"=>"user_id","modificationDate"=>"modification_date","visitModifications_comment_id"=>"comment","visitModifications_visit_id"=>"visit_id","postponeDate"=>"postpone_date"]];
        $query="";
        $result=true;
        foreach ($dataToGet as $table=>$dataToGetByTable)
        {
            $query="UPDATE `".$table."` SET";
            $first=true;
            foreach ($dataToGetByTable as $requestKey=>$dbCollumn)
            {
                if(!$first)
                    $query.",";
                else
                    $first=false;
                if(!($request->input($requestKey)==0 or $request->input($requestKey)==null or $request->input($requestKey)==""))
                {
                    $query.= "`" . $dbCollumn . "`=" . $request->input($requestKey);
                }
            }
            $query.="WHERE 1;";
            $result=$result and DB::update($query);
        }
        return $result;

    }

    public function getAll()
    {
        $result=DB::table("visits")
            ->join("visit_states","visit_state_id","=","visit_states.id")
            ->select(["visits.id","practitioner_id","user_id","attended_date","visit_state_id","visit_states.name AS visit_state_name"])
            ->get();
        /*$query="SELECT visits.id,`practitioner_id`,`user_id`,`attended_date`,`visit_state_id`,visit_states.name AS visit_state_name FROM `visits` INNER JOIN visit_states ON visit_state_id=visit_states.id WHERE 1 ;";
    */
        return $result;
        }

    public function deleteAll()
    {
        $query="DELETE FROM `medicines_visit_reports` WHERE 0;DELETE FROM visit_reports WHERE 0;DELETE FROM visit_modifications WHERE 0;DELETE FROM `visits` WHERE 0;";
        $result=DB::delete($query);
        return $result;
    }

    public function updateById(Request $request)
    {
        $dataToGet=[["visits",["practitioner_id"=>"practitioner_id","user_id"=>"user_id","attendedDate"=>"attended_date","visitState_id"=>"visit_state_id"],"`id`=".$request->id],
            ["visit_reports",["visitReports_visit_id"=>"visit_id","creationDate"=>"creation_date","visitReports_comment"=>"comment","starScore"=>"star_score","visitReportState_id"=>"visit_report_state_id"],"`visit_id`=".$request->id],
            ["visit_modifications",["visitModifications_user_id"=>"user_id","modificationDate"=>"modification_date","visitModifications_comment_id"=>"comment","visitModifications_visit_id"=>"visit_id","postponeDate"=>"postpone_date"],"`visit_id`=".$request->id]];
        $query="";
        $result=true;
        foreach ($dataToGet as $dataToGetByTable)
        {
            $query="UPDATE `".$dataToGetByTable[0]."` SET";
            $first=true;
            foreach ($dataToGetByTable[1] as $requestKey=>$dbCollumn)
            {
                if(!$first)
                    $query.=",";
                else
                    $first=false;
                if(!($request->input($requestKey)==0 or $request->input($requestKey)==null or $request->input($requestKey)==""))
                {
                    $query.= "`" . $dbCollumn . "`=" . $request->input($requestKey);
                }
            }
            $query.="WHERE ".$dataToGetByTable[2].";";

        }
        return $result;
    }

    public function getById(Request $request)
    {
        /*$query="SELECT `practitioner_id`,practitioners.firstname AS'practionner_firstname',practitioners.lastname AS'practionner_lastname',
`user_id`,users.firstname AS 'user_firstname',users.lastname AS 'user_lastname' ,
`attended_date`,
`visit_state_id`,visit_states.name AS 'visit_state_name',
visit_reports.id AS 'visit_report_id',visit_reports.comment,visit_reports.star_score,
COUNT(medicines_visit_reports.medicine_id) AS 'number_medicines',COUNT(visit_modifications.id) AS 'number_modifications'
FROM `visits`
LEFT JOIN users ON visits.user_id=users.id
LEFT JOIN visit_reports ON visit_reports.visit_id=visits.id
LEFT JOIN visit_modifications ON visit_modifications.visit_id=visits.id
INNER JOIN visit_states on visit_states.id=visit_state_id
INNER JOIN visit_report_states on visit_reports.visit_report_state_id=visit_report_states.id
LEFT JOIN practitioners ON practitioners.id =practitioner_id
INNER JOIN medicines_visit_reports ON medicines_visit_reports.visit_report_id=visit_reports.id
WHERE visits.id=".$request->id."
        GROUP BY visits";*/

        $result=DB::table("visits")
            ->leftJoin("users","visits.user_id","=","users.id")
            ->leftJoin("visit_reports","visit_reports.visit_id","=","visits.id")
            ->leftJoin("visit_modifications","visit_modifications.visit_id","=","visits.id")
            ->join("visit_states","visit_states.id","=","visit_state_id")
            ->join("visit_report_states","visit_reports.visit_report_state_id","=","visit_report_states.id")
            ->leftJoin("practitioners","practitioners.id","=","visits.practitioner_id")
            ->join("medicines_visit_reports","medicines_visit_reports.visit_report_id","=","visit_reports.id")
            ->select(["practitioner_id","practitioners.firstname AS'practionner_firstname'","practitioners.lastname AS'practionner_lastname'",
                "`user_id`","users.firstname AS 'user_firstname'","users.lastname AS 'user_lastname'","attended_date",
                "visit_state_id","visit_states.name AS 'visit_state_name'",
                "visit_reports.id AS 'visit_report_id'","visit_reports.comment","visit_reports.star_score",
                "COUNT(medicines_visit_reports.medicine_id) AS 'number_medicines'","COUNT(visit_modifications.id) AS 'number_modifications'"])
            ->where("visits.id","=",$request->id)
            ->groupBy("visits.id")
            ->get();
        return $result;
    }

    public function deleteById(Request $request)
    {
        $query="DELETE FROM `medicines_visit_reports`WHERE visit_reports.visit_id=".$request->id.";
        DELETE FROM `visit_reports` WHERE `visit_id`=".$request->id.";
        DELETE FROM `visit_modifications` WHERE `visit_id`=".$request->id.";
        DELETE FROM `visits` WHERE `id`=".$request->id.";";
        $result=DB::delete($query);
        return $result;
    }

    public function postponeById(Request $request)
    {
        $user="connected user";
        $query="UPDATE `visits` SET `attended_date`='".$request->newDate."',`visit_state_id`=3 WHERE visits.id=".$request->id.";";
        $result=DB::update($query);
        $query="INSERT INTO `visit_modifications`(`id`, `user_id`, `modification_date`, `comment`, `visit_id`, `postpone_date`) VALUES (NULL ,".$user->id.",NOW(),'postpone :".$request->commentary."',".$request->id.",".$request->newDate.");";
        $result= $result and  DB::insert($query);
    }

    public function cancelById(Request $request)
    {
        $user="connected user";

        $query="UPDATE `visits` SET visit_state_id=4 WHERE visits.id=".$request->id.";";
        $result=DB::update($query);
        $query="INSERT INTO `visit_modifications`(`id`, `user_id`, `modification_date`, `comment`, `visit_id`, `postpone_date`) VALUES (NULL ,".$user->id.",NOW(),'cancel :".$request->comment."',".$request->id.",NULL);";
        $result= $result and  DB::insert($query);
        return $result;
    }

    public function updateReportById(Request $request)
    {
        $query="";
    }

    public function getReportById(Request $request)
    {
        $result=[];
        $result["visit_reports"]=DB::table("visit_reports")
            ->join("visit_report_states","=","visit_report_states.id")
            ->where("visit_id","=",$request->id)
            ->select(["id","creation_date","comment","star_score","visit_report_state_id","visit_report_states.name as 'visit_report_state_name'"])
            ->get()[0];
        $result["medicine_visit_report"]=DB::table("medicine_visit_report")
            ->join("medicines","medicine_id","=","medicines.id")
            ->join("medicine_families","medicine_families.id","=","medicines.family_id")
            ->where("medicines_visit_reports.visit_report_id","=",$result["visit_reports"]["id"])
            ->select(["medicine_id","quantity",
                "medicines.code","medicines.commercial_name","medicines.family_id as 'medicine_family_id'","medicine_families.name as 'medicine_family_name'"])
            ->get();
        return $result;
    }

    public function deleteReportById(Request $request)
    {
        $query="DELETE FROM `medicines_visit_reports` WHERE `visit_report_id`=(SELECT id FROM visit_reports WHERE visit_reports.visit_id=".$request->id.");
                DELETE FROM visit_reports where visit_reports.visit_id=".$request->id.";";
        $result=DB::delete($query);
        return $result;
    }

    public function createReportById(Request $request)
    {
        if($request->comment=="" or $request->comment==null)
            $comment="";
        else
            $comment=$request->comment;
        if($request->starScore=="" or $request->starScore==null)
            $starScore=3;
        else if($request->starScore>=5)
            $starScore=5;
        else if($request->starScore<=0)
            $starScore=0;
        else
            $starScore=$request->starScore;
        if($request->reportState=="" or $request->reportState==null or $request->reportState<=0 or $request->reportState>3)
            $reportState=1;
        else
            $reportState=$request->reportState;
        $query="INSERT INTO `visit_reports`(`id`, `visit_id`, `creation_date`, `comment`, `star_score`, `visit_report_state_id`) VALUES (NULL,".$request->id.",NOW(),'".$comment."',".$starScore.",".$reportState.");";
        $result=DB::insert($query);
        if(!($request->medicines==[] or $request->medicines==null))
        {
            $query="INSERT INTO `medicines_visit_reports`(`id`, `medicine_id`, `quantity`, `visit_report_id`) VALUES ";
            $first=true;
            foreach ($request->medicines as $medicine)
            {
                if($medicine["quatity"]!=0 and $medicine["quatity"]!=null and  $medicine["medicine_id"]!=0 and $medicine["medicine_id"]!=null)
                {
                    if($first)
                        $first=false;
                    else
                        $query.=",";
                    $query.="(NULL,".$medicine["medicine_id"].",".$medicine["quatity"].",(SELECT `id` FROM `visit_reports` WHERE `visit_id`=".$request->id."))";
                }

            }
            $query.=";";
            $result=$result and DB::insert($query);
        }
        return $result;
    }

    public function updateByDoctor(Request $request)
    {
        $dataToGet=[["visits",["practitioner_id"=>"practitioner_id","user_id"=>"user_id","attendedDate"=>"attended_date","visitState_id"=>"visit_state_id"],"`practitioner_id`=".$request->doctor_id],
            ["visit_reports",["visitReports_visit_id"=>"visit_id","creationDate"=>"creation_date","visitReports_comment"=>"comment","starScore"=>"star_score","visitReportState_id"=>"visit_report_state_id"],"visit_reports.visit_id=(SELECT id FROM visits where visits.practitioner_id=".$request->doctor_id.")"],
            ["visit_modifications",["visitModifications_user_id"=>"user_id","modificationDate"=>"modification_date","visitModifications_comment_id"=>"comment","visitModifications_visit_id"=>"visit_id","postponeDate"=>"postpone_date"],"visit_modifications.visit_id=(SELECT id FROM visits where visits.practitioner_id=".$request->doctor_id.")"]];
        $query="";
        $result=true;
        foreach ($dataToGet as $dataToGetByTable)
        {
            $query="UPDATE `".$dataToGetByTable[0]."` SET";
            $first=true;
            foreach ($dataToGetByTable[1] as $requestKey=>$dbCollumn)
            {
                if(!$first)
                    $query.=",";
                else
                    $first=false;
                if(!($request->input($requestKey)==0 or $request->input($requestKey)==null or $request->input($requestKey)==""))
                {
                    $query.= "`" . $dbCollumn . "`=" . $request->input($requestKey);
                }
            }
            $query.="WHERE ".$dataToGetByTable[2].";";
            $result= $result and DB::update($query);
    }
        return $result;
    }

    public function getByDoctor(Request $request)
    {
        return DB::table("visits")
            ->join("visit_states","visit_state_id","=","visit_states.id")
            ->where("practitioner_id","=",$request->doctor_id)
            ->select(["visits.id","practitioner_id","user_id","attended_date","visit_state_id","visit_states.name AS visit_state_name"])
            ->get();
    }

    public function deleteByDoctor(Request $request)
    {
        $query="DELETE FROM medicines_visit_reports WHERE medicines_visit_reports.visit_report_id=(SELECT id FROM visit_reports INNER JOIN visits on visits.id=visit_reports.visit_id where visits.practitioner_id=".$request->doctor_id.");
DELETE FROM visit_reports WHERE visit_reports.visit_id=(SELECT id FROM visits where visits.practitioner_id=".$request->doctor_id.");
DELETE FROM visit_modifications WHERE visit_modifications.visit_id=(SELECT id FROM visits where visits.practitioner_id=".$request->doctor_id.");
DELETE FROM `visits` WHERE `practitioner_id`=".$request->doctor_id.";";
        $result=DB::delete($query);
        return $result;
    }

    public function updateByUserByDate(Request $request)
    {
        $dataToGet=[["visits",["practitioner_id"=>"practitioner_id","user_id"=>"user_id","attendedDate"=>"attended_date","visitState_id"=>"visit_state_id"]],
            ["visit_reports",["visitReports_visit_id"=>"visit_id","creationDate"=>"creation_date","visitReports_comment"=>"comment","starScore"=>"star_score","visitReportState_id"=>"visit_report_state_id"]],
            ["visit_modifications",["visitModifications_user_id"=>"user_id","modificationDate"=>"modification_date","visitModifications_comment_id"=>"comment","visitModifications_visit_id"=>"visit_id","postponeDate"=>"postpone_date"]]];
        $role=DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",1)->select(["IF(`activated`=1,`role_id`,0) as \"role\""])[0]["role"];
        if($role==1)//VM
        {
            $dataToGet["visits"][2]="`user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%'";
            $dataToGet["visit_reports"][2]="`visit_id` IN (SELECT `id` FROM `visits` WHERE `user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
            $dataToGet["visit_modifications"][2]="`visit_id` IN (SELECT `id` FROM `visits` WHERE `user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
        }
        elseif ($role==2)//DR
        {
            $dataToGet["visits"][2]="`attended_date` LIKE '".$request->date."%' AND `id` IN (SELECT visits.id FROM `visits`
INNER JOIN practitioners on visits.practitioner_id=practitioners.id
INNER JOIN sector_districts on sector_districts.id=practitioners.district_id
WHERE sector_districts.leader_id=".$request->user_id.")";
            $dataToGet["visit_reports"][2]="`visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
WHERE sector_districts.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
            $dataToGet["visit_modifications"][2]="`visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
WHERE sector_districts.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
        }
        elseif ($role==3)//RS
        {
            $dataToGet["visits"][2]="`attended_date` LIKE '".$request->date."%' AND `id` IN (SELECT visits.id FROM `visits`
INNER JOIN practitioners on visits.practitioner_id=practitioners.id
INNER JOIN sector_districts on sector_districts.id=practitioners.district_id
INNER JOIN sectors on sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id.")";
            $dataToGet["visit_reports"][2]="`visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
INNER JOIN sectors ON sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
            $dataToGet["visit_modifications"][2]="`visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
INNER JOIN sectors ON sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%')";
        }
        else
        {return false;}
        $result=true;
        $query="";
        foreach ($dataToGet as $dataToGetByTable)
        {
            $query="UPDATE `".$dataToGetByTable[0]."` SET";
            $first=true;
            foreach ($dataToGetByTable[1] as $requestKey=>$dbCollumn)
            {
                if(!$first)
                    $query.=",";
                else
                    $first=false;
                if(!($request->input($requestKey)==0 or $request->input($requestKey)==null or $request->input($requestKey)==""))
                {
                    $query.= "`" . $dbCollumn . "`=" . $request->input($requestKey);
                }
            }
            $query.="WHERE ".$dataToGetByTable[2].";";
            $result=$result and DB::update($query);
        }
        return $result;


    }

    public function getByUserByDate(Request $request)
    {
        $role=DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",1)->select(["IF(`activated`=1,`role_id`,0) as \"role\""])[0]["role"];

        if($role==1)//VM
        {
            $result=DB::table("visits")
                ->join("practitioners","practitioner_id","=","practitioners.id")
                ->join("visit_states","visit_state_id","=","visit_states.id")
                ->where("user_id","=",$request->user_id)
                ->where("attended_date","LIKE",$request->date.'%')
                ->select(["visits.id",
                    "practitioner_id","practitioners.firstname AS 'practitionner_firstname'","practitioners.lastname AS 'practitionner_lastname'",
                    "attended_date",
                    "visit_state_id","visit_states.name AS 'visit_state_name'"])
                ->get();
            /*$query="SELECT visits.id,
`practitioner_id`,practitioners.firstname AS 'practitionner_firstname',practitioners.lastname AS 'practitionner_lastname',
`attended_date`,
`visit_state_id`,visit_states.name AS 'visit_state_name'
FROM `visits`
INNER JOIN practitioners ON practitioner_id=practitioners.id
INNER JOIN visit_states ON visit_state_id=visit_states.id
WHERE user_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%';";*/

        }
        elseif ($role==2)//DR
        {        /*$query="SELECT visits.id,
`practitioner_id`,practitioners.firstname AS 'practitionner_firstname',practitioners.lastname AS 'practitionner_lastname',
`attended_date`,
`visit_state_id`,visit_states.name AS 'visit_state_name'
FROM `visits`
INNER JOIN practitioners ON practitioner_id=practitioners.id
INNER JOIN sector_districts ON sector_districts.id=practitioners.district_id
INNER JOIN visit_states ON visit_state_id=visit_states.id
WHERE sector_districts.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%';";*/
            $result=DB::table("visits")
                ->join("practitioners","practitioner_id","=","practitioners.id")
                ->join("sector_districts","sector_districts.id","=","practitioners.district_id")
                ->join("visit_states","visit_state_id","=","visit_states.id")
                ->where("sector_districts.leader_id","=",$request->user_id)
                ->where("attended_date","LIKE",$request->date.'%')
                ->select(["visits.id",
                    "practitioner_id","practitioners.firstname AS 'practitionner_firstname'","practitioners.lastname AS 'practitionner_lastname'",
                    "attended_date",
                    "visit_state_id","visit_states.name AS 'visit_state_name'"])
                ->get();

        }
        elseif ($role==3)//RS
        {
            $result=DB::table("visits")
                ->join("practitioners","practitioner_id","=","practitioners.id")
                ->join("sector_districts","sector_districts.id","=","practitioners.district_id")
                ->join("visit_states","visit_state_id","=","visit_states.id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("sectors.leader_id","=",$request->user_id)
                ->where("attended_date","LIKE",$request->date.'%')
                ->select(["visits.id",
                    "practitioner_id","practitioners.firstname AS 'practitionner_firstname'","practitioners.lastname AS 'practitionner_lastname'",
                    "attended_date",
                    "visit_state_id","visit_states.name AS 'visit_state_name'"])
                ->get();

            /*$query="SELECT visits.id,
`practitioner_id`,practitioners.firstname AS 'practitionner_firstname',practitioners.lastname AS 'practitionner_lastname',
`attended_date`,
`visit_state_id`,visit_states.name AS 'visit_state_name'
FROM `visits`
INNER JOIN practitioners ON practitioner_id=practitioners.id
INNER JOIN sector_districts ON sector_districts.id=practitioners.district_id
INNER JOIN sectors ON sectors.id=sector_districts.sector_id
INNER JOIN visit_states ON visit_state_id=visit_states.id
WHERE sectors.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%';";*/

        }
        else
        {
            return null;
        }
        return $result;


    }

    public function deleteByUserByDate(Request $request)
    {
        $role=DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",1)->select(["IF(`activated`=1,`role_id`,0) as \"role\""])[0]["role"];

        if($role==1)//VM
        {
            $query="DELETE FROM `medicines_visit_reports` WHERE `visit_report_id` IN (SELECT visit_reports.id FROM `visit_reports`
INNER JOIN visits ON visits.id=visit_id
WHERE visits.user_id==".$request->user_id." AND visits.attended_date LIKE '".$request->date."%');
DELETE FROM visit_reports WHERE `visit_id` IN (SELECT `id` FROM `visits` WHERE `user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
DELETE FROM visit_modifications WHERE `visit_id` IN (SELECT `id` FROM `visits` WHERE `user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
DELETE FROM `visits` WHERE `user_id`=".$request->user_id." AND `attended_date` LIKE '".$request->date."%';";

        }
        elseif ($role==2)//DR
        {
            $query="DELETE FROM `medicines_visit_reports` WHERE `visit_report_id` IN (SELECT visit_reports.id FROM `visit_reports`
INNER JOIN visits ON visits.id=visit_id
INNER JOIN practitioners ON visits.practitioner_id=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
WHERE sector_districts.leader_id=".$request->user_id." AND visits.attended_date LIKE '".$request->date."%');
DELETE FROM visit_reports WHERE `visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
WHERE sector_districts.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
        DELETE FROM visit_modifications WHERE `visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
WHERE sector_districts.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
        DELETE FROM `visits` WHERE `attended_date` LIKE '".$request->date."%' AND `id` IN (SELECT visits.id FROM `visits`
INNER JOIN practitioners on visits.practitioner_id=practitioners.id
INNER JOIN sector_districts on sector_districts.id=practitioners.district_id
WHERE sector_districts.leader_id=".$request->user_id.");";

        }
        elseif ($role==3)//RS
        {
            $query="DELETE FROM `medicines_visit_reports` WHERE `visit_report_id` IN (SELECT visit_reports.id FROM `visit_reports`
INNER JOIN visits ON visits.id=visit_id
INNER JOIN practitioners ON visits.practitioner_id=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
INNER JOIN sectors on sector_districts.sector_id=sectors.id
WHERE sectors.leader_id=".$request->user_id." AND visits.attended_date LIKE '".$request->date."%');
DELETE FROM visit_reports WHERE `visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
INNER JOIN sectors ON sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
        DELETE FROM visit_modifications WHERE `visit_id` IN (SELECT `id` FROM `visits`
INNER JOIN practitioners ON `practitioner_id`=practitioners.id
INNER JOIN sector_districts ON practitioners.district_id=sector_districts.id
INNER JOIN sectors ON sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id." AND `attended_date` LIKE '".$request->date."%');
        DELETE FROM `visits` WHERE `attended_date` LIKE '".$request->date."%' AND `id` IN (SELECT visits.id FROM `visits`
INNER JOIN practitioners on visits.practitioner_id=practitioners.id
INNER JOIN sector_districts on sector_districts.id=practitioners.district_id
INNER JOIN sectors on sectors.id=sector_districts.sector_id
WHERE sectors.leader_id=".$request->user_id.");";

        }
        else
        {
            return false;
        }
        $result=DB::delete($query);
        return $result;
    }

    public function countByYearByUser(Request $request)
    {
        $role=DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",1)->select(["IF(`activated`=1,`role_id`,0) as \"role\""])[0]["role"];
        if($role==1)//VM
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("visits.user_id","=",$request->user_id)
                ->where("YEAR(visits.attended_date)","=",$request->year)
                ->select(["COUNT(visits.id) as 'number_visits'"])
                ->get();

        }
        elseif($role==2)//DR
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("sector_districts.leader_id","=",$request->user_id)
                ->where("YEAR(visits.attended_date)","=",$request->year)
                ->select(["COUNT(visits.id) as 'number_visits'"])
                ->get();
        }
        elseif($role==3)//DR
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("sectors.leader_id","=",$request->user_id)
                ->where("YEAR(visits.attended_date)","=",$request->year)
                ->select(["COUNT(visits.id) as 'number_visits'"])
                ->get();
        }
        else
        {
            return 0;
        }
    }

    public function countByMonthYearByUser(Request $request)
    {

    }

    public function countByDateByUser(Request $request)
    {

    }

    public function countBySectorDateByUser(Request $request)
    {

    }

    public function countByUserGroupByYear(Request $request)
    {
        $role=DB::table("applications_users")->where("user_id","=",$request->user_id)->where("application_id","=",1)->select(["IF(`activated`=1,`role_id`,0) as \"role\""])[0]["role"];
        if($role==1)//VM
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("visits.user_id","=",$request->user_id)
                ->groupBy("YEAR(visits.attended_date)")
                ->select(["YEAR(visits.attended_date) as 'year'","COUNT(visits.id) as 'number_visits'"])
                ->get();

        }
        elseif($role==2)//DR
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("sector_districts.leader_id","=",$request->user_id)
                ->groupBy("YEAR(visits.attended_date)")
                ->select(["YEAR(visits.attended_date) as 'year'","COUNT(visits.id) as 'number_visits'"])
                ->get();
        }
        elseif($role==3)//DR
        {
            return DB::table("visits")
                ->join("users","users.id","=","visits.user_id")
                ->join("sector_districts","sector_districts.id","=","users.district_id")
                ->join("sectors","sectors.id","=","sector_districts.sector_id")
                ->where("sectors.leader_id","=",$request->user_id)
                ->groupBy("YEAR(visits.attended_date)")
                ->select(["YEAR(visits.attended_date) as 'year'","COUNT(visits.id) as 'number_visits'"])
                ->get();
        }
        else
        {
            return 0;
        }
    }

    public function countByUserGroupByMonthYear(Request $request)
    {

    }

    public function countByUserGroupByDate(Request $request)
    {

    }


}
