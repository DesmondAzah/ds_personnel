<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Client\Request;
use App\Services\PersonnelService;

class PersonnelController extends Controller
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

    //
    /**
     * PROCESS a request to add a new personnel
     * @param PersonnelService $personnelService
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function addPersonnel( PersonnelService $personnelService) {
        $personnel = request()->all();
        return $personnelService->addPersonnel($personnel);
    }

    /**
     * PROCESS a request to add a personnel details
     * @param PersonnelService $personnelService
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function addPersonnelDetail( PersonnelService $personnelService) {
        $personnelDetails = request()->all();
        return $personnelService->addPersonnelDetails($personnelDetails);
    }

    /**
     * PROCESS a request to update a personnel
     * @param PersonnelService $personnelService
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function updatePersonnel($personnel, PersonnelService $personnelService) {
        $request = request()->all();
        return $personnelService->updatePersonnel($request, $personnel);
    }
      /**
     * PROCESS a request to update a personnel's details
     * @param PersonnelService $personnelService
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function updatePersonnelDetails($id, PersonnelService $personnelService) {
        $request = request()->all();
        return $personnelService->updatePersonnelDetails($request, $id);
    }

    public function deletePersonnel($personnel, PersonnelService $personnelService) {
        return $personnelService->deletePersonnel($personnel);
    }
     /**
     * PROCESS a customer order
     * @param PersonnelService $personnelService
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function getPersonnelList( PersonnelService $personnelService) {
        $request = request()->all();
        return $personnelService->getPersonnelList($request);
    }
    public function getAllPersonnel( PersonnelService $personnelService) {
        return $personnelService->getAllPersonnelDetails();
    }
    public function getPersonnel($personnel, PersonnelService $personnelService) {
        return $personnelService->getPersonnel($personnel);
    }

    public function getPersonnelRequest($id , PersonnelService $personnelService){
        return $personnelService->getPersonnelRequest($id);
    }

    public function getPersonnelDetails($personnel, PersonnelService $personnelService) {
        return $personnelService->getPersonnelDetails($personnel);
    }
}