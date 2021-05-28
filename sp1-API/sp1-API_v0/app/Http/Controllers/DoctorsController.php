<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;


class DoctorsController extends Controller
{
    public function createElement(Request $request)
    {
        $dataToGet=["practitioner",["lastname"=>"lastname","firstname"=>"firstname","address"=>"address","phone"=>"tel","notorietyCoeff"=>"notoriety_coeff","complementarySpeciality"=>"complementary_speciality","district_id"=>"district_id"]];

        $collumn="(`id`";
        $values=") VALUES (NULL";
        $query="INSERT INTO `".$dataToGet[0]."`";
        foreach ($dataToGet[1] as $requestKey=>$dbCollumn)
        {
            $query.=",";
            $collumn.=',`'.$dbCollumn.'`';
            if(!($request->input($requestKey)==0 or $request->input($requestKey)==null or $request->input($requestKey)==""))
            {
                $query.='"'.$request->input($requestKey).'"';
            }
            else
                $query.="NULL";
        }
        $query.=$collumn.$values.");";

    }

    public function updateAll(Request $request)
    {
        $dataToGet=["practitioner",["lastname"=>"lastname","firstname"=>"firstname","address"=>"address","phone"=>"tel","notorietyCoeff"=>"notoriety_coeff","complementarySpeciality"=>"complementary_speciality","district_id"=>"district_id"],"1"];
        $query="";
        $query.="UPDATE `".$dataToGet[0]."` SET";
        $first=true;
        foreach ($dataToGet[1] as $requestKey=>$dbCollumn)
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
        $query.="WHERE ".$dataToGet[2].";";
    }

    public function getAll()
    {
        $query="SELECT practitioners.id,`lastname`,`firstname`,`address`,`tel`,`notoriety_coeff`,`complementary_speciality`,`district_id`,sector_districts.name as \"district_name\", sectors.id as \"sector_id\",sectors.name as \"sector_name\" FROM `practitioners`
INNER JOIN sector_districts ON district_id=sector_districts.id
INNER JOIN sectors on sectors.id=sector_districts.sector_id
WHERE 1;";
    }

    public function deleteAll(Request $request)
    {
        $query="DELETE FROM activities_practitioners WHERE 0;DELETE FROM `practitioners` WHERE 0;";
    }

    public function updateById(Request $request)
    {
        $dataToGet=["practitioner",["lastname"=>"lastname","firstname"=>"firstname","address"=>"address","phone"=>"tel","notorietyCoeff"=>"notoriety_coeff","complementarySpeciality"=>"complementary_speciality","district_id"=>"district_id"],"`id`=".$request->id];
        $query="";
            $query.="UPDATE `".$dataToGet[0]."` SET";
            $first=true;
            foreach ($dataToGet[1] as $requestKey=>$dbCollumn)
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
            $query.="WHERE ".$dataToGet[2].";";

    }

    public function getById(Request $request)
    {
        $query="SELECT practitioners.id,`lastname`,`firstname`,`address`,`tel`,`notoriety_coeff`,`complementary_speciality`,`district_id`,sector_districts.name as \"district_name\", sectors.id as \"sector_id\",sectors.name as \"sector_name\" FROM `practitioners`
INNER JOIN sector_districts ON district_id=sector_districts.id
INNER JOIN sectors on sectors.id=sector_districts.sector_id
WHERE practitioners.id=".$request->id.";";
    }

    public function deleteById(Request $request)
    {
        $query="DELETE FROM activities_practitioners WHERE activities_practitioners.practitioner_id=".$request->id."; SELECT * FROM `practitioners` WHERE `id`=".$request->id.";";
    }

    public function updateByUser(Request $request)
    {

    }

    public function getByUser(Request $request)
    {

    }

    public function deleteByUser(Request $request)
    {

    }


}
