<?php 

namespace App\Services;
use App\Models\Personnel;
use App\Models\PersonnelDetail;
use Illuminate\Http\Client\Request;
use Exception;
use App\Traits\ApiResponseHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PersonnelService extends Service {

    use ApiResponseHelper;
    public function __construct() {
        parent::__construct();
    }

    public function getPersonnelDetails($personnel) {
        try{
            $personnelObj = Personnel::find($personnel);
            if(!$personnelObj) {
                return $this->errorResponse("Personnel not found", Response::HTTP_NOT_FOUND);
            }
            $personnel_details = PersonnelDetail::where('personnel_id', $personnel)->first();
            if(!$personnel_details) {
                return $this->errorResponse("Personnel details not found", Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($personnel_details, "Personnel details found", Response::HTTP_OK);
        } catch(Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getPersonnel($personnel) {
        try{
            $personnel =  Personnel::Select('personnel.id', 'personnel.full_name', 'personnel.emp_status', 'personnel.created_by','personnel_details.username', 'personnel_details.phone_number', 'personnel_details.pn_extension', 'personnel_details.picture_url', 'personnel_details.email')
                                    ->join('personnel_details','personnel_details.personnel_id','=',  'personnel.id' )
                                    ->where('personnel.id', $personnel)
                                    ->get();
            if(!$personnel) {
                return $this->errorResponse('Personnel not found', Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($personnel, 'Personnel found', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getPersonnelRequest($personnel) {
        try{
            $personnel =  Personnel::Select('personnel.id', 'personnel.full_name', 'personnel.emp_status', 'personnel.created_by','personnel_details.username', 'personnel_details.phone_number', 'personnel_details.pn_extension', 'personnel_details.picture_url', 'personnel_details.email')
                                    ->join('personnel_details','personnel_details.personnel_id','=',  'personnel.id' )
                                    ->where('personnel.id', $personnel)
                                    ->get();
            if(!$personnel) {
                return $this->errorResponse('Personnel not found', Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($personnel, 'Personnel found', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }
    
    public function addPersonnel($personnel) {
        try {
            Validator::make($personnel,$this->personnelValidationRule())->validate();
            $personnelInfo = Personnel::create($personnel);
            $personnelInfo->save();
            return $this->successResponse($personnelInfo, 'Personnel successfully added.', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function addPersonnelDetails($personnel_details) {
        try {
            Validator::make($personnel_details,$this->personnelDetailsValidator())->validate();
            $personnelObj = Personnel::find($personnel_details['personnel_id']);
            if(!$personnelObj) 
                return $this->errorResponse("Personnel not found", Response::HTTP_NOT_FOUND);
            $personnelDetails = PersonnelDetail::where('personnel_id', $personnel_details['personnel_id'])->first();
            if($personnelDetails) {
                return $this->errorResponse("Personnel details already exists", Response::HTTP_BAD_REQUEST);
            }
            $personnel_details = PersonnelDetail::create($personnel_details);
            $personnel_details->save();
            return $this->successResponse($personnel_details, 'Personnel details successfully added.', Response::HTTP_OK);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function updatePersonnel( $request, $personnel) {
        try{
            $personnel = Personnel::find($personnel);
            if(!$personnel) {
                return $this->errorResponse("Personnel not found", Response::HTTP_NOT_FOUND);
            }
            $personnel->fill($request);
            if($personnel->isClean()) {
                return $this->errorResponse("No changes to update", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $request['dt_updated'] = date('Y-m-d H:i:s');
            $personnel->update();
            return $this->successResponse($personnel, "Personnel updated successfully", Response::HTTP_OK);
        } catch (Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function updatePersonnelDetails( $request, $id) {
        try{
            $personnel = PersonnelDetail::find($id);
            if(!$personnel) {
                return $this->errorResponse("Personnel details not found", Response::HTTP_NOT_FOUND);
            }
            $request['dt_updated'] = date('Y-m-d H:i:s');
            $personnel->fill($request);
            if($personnel->isClean()) {
                return $this->errorResponse("No changes to update", Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $personnel->update();
            return $this->successResponse($personnel, "Personnel details updated successfully", Response::HTTP_OK);
        } catch (Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function deletePersonnel($personnel_id) {
        try{
            $personnel = Personnel::find($personnel_id);
            if(!empty($personnel)) {
                $personnel->emp_status = false;
                $personnel->update();
                return $this->successResponse(null, 'Personnel deleted successfully.', Response::HTTP_OK);
            }
            return $this->errorResponse('Personnel not found.', Response::HTTP_NOT_FOUND);
        }
        catch(Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getPersonnelList($request) {
        try{
            $page = !empty($request['page']) ? $request['page'] : 1;
            $limit = !empty($request['limit']) ? $request['limit'] : 100;
            $personnel_list =  Personnel::Select('personnel.id', 'personnel.full_name', 'personnel.emp_status', 'personnel.created_by')
                                    ->join('personnel_details', 'personnel.id', '=', 'personnel_details.personnel_id')
                                    ->orderBy('personnel.id', 'desc')
                                    ->paginate($limit);
        if(!empty($personnel_list)){
             return $this->successResponse($personnel_list, 'Personnel List', Response::HTTP_OK);
        } else{
            return $this->errorResponse('Personnel List is empty', Response::HTTP_NOT_FOUND);
        }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);

        }
    }

    public function getAllPersonnelDetails(){
        try{
            $personnel_list =  Personnel::Select('personnel.id', 'personnel.full_name', 'personnel.emp_status', 'personnel_details.phone_number','personnel_details.picture_url', 'personnel_details.email')
                                    ->join('personnel_details', 'personnel.id', '=', 'personnel_details.personnel_id')
                                    ->orderBy('personnel.id', 'desc')
                                    ->get();
        if(!empty($personnel_list)){
             return $personnel_list;
        } else{
            return $this->errorResponse('Personnel List is empty', Response::HTTP_NOT_FOUND);
        }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);

        }
    }

    public function findPersonnel ($request)
    {
        try{
        $personnel_list =  Personnel::Select('personnel.id', 'personnel.full_name', 'personnel.emp_status', 'personnel.created_by')
                                    ->join('personnel_details', 'personnel.id', '=', 'personnel_details.personnel_id')
                                    ->where('personnel_details.username', 'like', '%' . $request->input('username') . '%')
                                    ->where('personnel_details.phone_number', 'like', '%' . $request->input('phone_number') . '%')
                                    ->where('personnel_details.email', 'like', '%' . $request->input('email') . '%')
                                    ->where('personnel.ext_emp_id', 'like', '%' . $request->input('ext_emp_id') . '%')
                                    ->where('personnel.full_name', 'like', '%' . $request->input('full_name') . '%')
                                    ->where('personnel.emp_status', 'like', '%' . $request->input('emp_status') . '%')
                                    ->orderBy('personnel.id', 'desc')
                                    ->get();
        if(!empty($personnel_list)){
                return $this->successResponse($personnel_list, 'Personnel List', Response::HTTP_OK);
            } else{
                return $this->errorResponse('Personnel List is empty', Response::HTTP_NOT_FOUND);
            }
            } catch (Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
    
            }
    }

    public function setUpPersonnelToHat($request) {
        try{
            Validator::make($request,$this->personalHatValidationRule())->validate();
            $personnel_id = $request['personnel_id'];
            $personnel = Personnel::find($personnel_id);
            if(!$personnel) {
                return $this->errorResponse("Personnel not found", Response::HTTP_NOT_FOUND);
            }
            $personnel->hat_id = $request['hat_id'];
            $personnel->update();
            return $this->successResponse($personnel, "Personnel updated successfully", Response::HTTP_OK);
        } catch (Exception $e){
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function personnelValidationRule () {
        return [
            'ext_emp_id' => 'required',
            'full_name' => 'required',
            'emp_status' => 'required',
            'created_by' => 'required'
        ];
    }

    public function personnelDetailsValidator () {
        return [
            'personnel_id' => 'required',
            'username' => 'required',
            'phone_number' => 'required',
            'pn_extension' => '',
            'picture_url' => '',
            'email' => 'required'
        ];
    }

    private function personalHatValidationRule () {
        return [
            'personnel_id' => 'required',
            'hat_lr_id' => 'required'
        ];
    }

}