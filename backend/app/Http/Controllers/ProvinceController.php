<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Congregation;
use App\Models\Payment;
use App\Models\Province;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;

class ProvinceController extends Controller
{ 
        Private $status = 200;
       
        public function Provincestore(Request $request)
        {
           
            $validator    =  Validator::make($request->all(), 
            [
                "congregation" => 'required',
                "province"   => "required",
                "address1"  => "required",
                "state"  => "required",
                "postcode"=> "required",
                "city"  => "required",
                "country"  => "required",
                "mobile"  => "required",
                "email"  => "required",
            ]
           );
                if($validator->fails()) {
                    return response()->json(["status" => "failed", 
                                    "validation_errors" => $validator->errors()]);
                }
                 $ProvinceArray['params'] = array(
                                "congregation" => $request->congregation,
                                "province" => $request->province,
                                "address1" => $request->address1,
                                "state" => $request->state,
                                "address2" => $request->address2,
                                "postcode"   => $request->postcode, 
                                "city"   => $request->city, 
                                "country"   => $request->country, 
                                "mobile"   => $request->mobile, 
                                "email"   => $request->email, 
                         );
    
                $Province  = Province::create($ProvinceArray['params']);
    
                if(!is_null($Province)){ 
    
                    return response()->json(["status" => $this->status, "success" => true, 
                            "message" => "Province created successfully", "data" => $Province]);
                }    
                else {
                    return response()->json(["status" => "failed", "success" => false,
                                "message" => "Whoops! failed to create."]);
            }      
        }
    
        // list value
    
        public function ProvinceList() {
    
            $ProvinceAll = DB::table('provinces as pr')
            ->select('pr.*','co.congregation')
            ->leftjoin('congregation as co','co.id','pr.congregation')
            ->get();
    
            if(count($ProvinceAll) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($ProvinceAll), "data" => $ProvinceAll]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false, "message" => "Whoops! no record found"]);
            }
        }

        public function ProvinceDelete($id){

            $Congregationdel =Province::find($id);
            $Congregationdel->delete();
            return response()->json(
                ["status" => $this->status, "success" => true, 
                "message" => " Province deleted  successfully"]);
        }
        public function ProvinceCongregation(){

            $Congregation =Congregation::all();
            if(count($Congregation) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($Congregation), "data" => $Congregation]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false, "message" => "Whoops! no record found"]);
            }
        }

        public function ProvinceEdit($id){
           
            $Congregationedit = Province::where('id',$id)->get();
            if(count($Congregationedit) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($Congregationedit), "data" => $Congregationedit]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false, "message" => "Whoops! no record found"]);
            }

        }

        public function Provinceget($id){
         
            // $Provinceget = Province::where('id',$id)->get();
            
            $Provinceget = DB::table('provinces as pr')
            ->select('pr.*','co.congregation')
            ->leftjoin('congregation as co','co.id','pr.congregation')
            ->where('co.id',$id)
            ->get();
          
            if(count($Provinceget) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($Provinceget), "data" => $Provinceget]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false, "message" => "Whoops! no record found"]);
            }

        }
        public function Provinceupdate($id,Request $request){
           
            $Congregationupdate = Province::where('id',$id)
            ->update([
                "congregation" => $request->congregation,
                "province" => $request->province,
                "address1" => $request->address1,
                "state" => $request->state,
                "address2" => $request->address2,
                "postcode"   => $request->postcode, 
                "city"   => $request->city, 
                "country"   => $request->country, 
                "mobile"   => $request->mobile, 
                "email"   => $request->email, 
            ]);

            return response()->json(
                ["status" => $this->status, "success" => true, 
                "message" => " Congregation updated  successfully"]);
        }

        public function GetBalance($value){
            $Balancefilter =Payment::where('clienttype',$value)->get();
            $balance=[];
            $total=[];
            $year =[];
            $Paid=[];
          foreach ($Balancefilter as $key => $value) {
            $balance[]= $value->balance;
            $total[]=$value->total;
            // $createddate[] =Carbon::parse($value->created_at)->format('m d Y');
             $year[] =$value->financialyear;
            $Paid[]= $value->paid;

          }
          $balances =array_sum($balance);
          $totalval = array_sum($total);
         $paidval = array_sum($Paid);

         $perbal =  round(($balances * 100) / $totalval ,2);
         $perpaid = round(($paidval * 100) / $totalval,2);
         
    

        // dd($balances,$totalval,$paidval);
  
            if(count($Balancefilter) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($Balancefilter), "data" => [
                                "balance" =>$balances ?? '0',
                                "total" => $totalval ?? '0',
                                "paid" => $paidval ?? '0',
                                "year" =>$year ?? '0' ,
                                "balPer" =>$perbal ?? '0' ,
                                "paidPer" =>$perpaid ?? '0'
                                ]
                        
                        
                        ]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false,  "count" => count($Balancefilter), "data" => [
                    "balance" =>$balances ?? '0',
                    "total" => $totalval ?? '0',
                    "paid" => $paidval ?? '0',
                    "balPer" =>$perbal ?? '0' ,
                    "paidPer" =>$perpaid ?? '0'
                    ]
            
            ]);
            }
        }

        public function GetFinancialyear(){
          
        $years = DB::table('payments')->select('financialyear')->groupby('financialyear')->get();
     $finnacialyear =[];
         foreach ($years as $key => $value) {
            $finnacialyear[]=$value->financialyear;
         }
        //  dd($finnacialyear);
            if(count($finnacialyear) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($finnacialyear), "data" => $finnacialyear]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false, "message" => "Whoops! no record found"]);
            } 
        }
        public function financialyear(Request $request){
           
            $getBalance = Payment::where('clienttype',$request->type)->where('financialyear',$request->year)->get();
            $balance=[];
            $total=[];
            $Paid=[];
          foreach ($getBalance as $key => $value) {
            $balance[]= $value->balance;
            $total[]=$value->total;
            $Paid[]= $value->paid;
            
          }
          $balances =array_sum($balance);
          $totalval = array_sum($total);
         $paidval = array_sum($Paid);
    
         $perbal =  round(($balances * 100) / $totalval ,2);
         $perpaid = round(($paidval * 100) / $totalval,2);

            if(count($getBalance) > 0) {
                return response()->json(["status" => $this->status, "success" => true, 
                            "count" => count($getBalance), "data" => [
                                "balance" =>$balances ?? '0',
                                "total" => $totalval ?? '0',
                                "paid" => $paidval ?? '0',
                                "balPer" =>$perbal ?? '0' ,
                                "paidPer" =>$perpaid ?? '0'
                               
                                ]
            ]);
            }
            else {
                return response()->json(["status" => "failed",
                "success" => false,  "count" => count($getBalance), "data" => [
                    "balance" =>$balances ?? '0',
                    "total" => $totalval ?? '0',
                    "paid" => $paidval ?? '0',
                    "balPer" =>$perbal ?? '0' ,
                     "paidPer" =>$perpaid ?? '0'
                    ]
            
            ]);
            }

        }
}

