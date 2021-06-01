<?php 
	
class Cmis_Reports_model extends CI_Model
{


	public function __construct() {
        header('Access-Control-Allow-Origin: *');
    	header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    	parent::__construct();
	}

	

	public function reports($payload)
	{
		if($payload != null)
		{

			switch ($payload->method) {

				case 'f5_regional_pi_r1_p1':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){

							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							$totalCarryOver = 0;
							$totalRcv = 0;
							$totalInvestigation = 0;
							$totalRcv2 = 0;
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){

									$payload->filter = ""; $payload->table = "F5T1"; $payload->field_office = $value1->NAME;
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->filter = ""; $payload->table = "F5T2_RCV"; $payload->field_office = $value1->NAME;
									$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalRcv += $rcv->count;

									$totalRcv2 = $totalRcv;
									$totalInvestigation = $totalCarryOver + $totalRcv;
								};
							}
							

							

							$data[] = array('REGION' => $value->VALUE_,
											'ID' => $value->ID,
											'carryOver' => $totalCarryOver,
											'totalRcv' => $totalRcv,
											'totalRcv2' => $totalRcv2,
											'totalInvestigation' => $totalInvestigation,

										);

							#var_dump();
							#break;
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;


				case 'f5_regional_pi_r1_p2':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){

							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							$totalGrant = 0;
							$totalDenial = 0;
							$totalManifest = 0;
							$totalTransfer = 0;
							$totalActed = 0;
							$totalRecall = 0;
							$totalWarrant = 0;
							$totalNotActed = 0;

							$totalCarryOver = 0;
							$totalRcv = 0;
							$totalInvestigation = 0;
							$totalRcv2 = 0;

							$totalActive = 0;
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){


									$payload->filter = ""; $payload->table = "F5T1"; $payload->field_office = $value1->NAME; $payload->filter_val="";$payload->filter_field="";
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->filter = ""; $payload->table = "F5T2_RCV"; $payload->field_office = $value1->NAME;$payload->filter_val="";$payload->filter_field="";
									$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalRcv += $rcv->count;

									$totalRcv2 = $totalRcv;
									$totalInvestigation = $totalCarryOver + $totalRcv;


									#echo $this->Cmis_F5T2_model->fetchF5T2_RCV_ByYM($payload);
									$payload->table = "F5T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "Grant"; $payload->field_office = $value1->NAME;
									$grant_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalGrant += $grant_upon->count;

									$payload->table = "F5T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "Denial"; $payload->field_office = $value1->NAME;
									$denial_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDenial += $denial_upon->count;

									$payload->table = "F5T2_ACTED"; $payload->filter_field = "manifest_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$manifest_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalManifest += $manifest_upon->count;

									$payload->table = "F5T2_ACTED"; $payload->filter_field = "transfer_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$transfer_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTransfer += $transfer_upon->count;

									$payload->table = "F5T2_NOTACTED"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Recall"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRecall += $upon->count;

									$payload->table = "F5T2_NOTACTED"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Warrant"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalWarrant += $upon->count;

									
									$totalActed = $totalGrant + $totalDenial + $totalManifest + $totalTransfer;
									$totalNotActed = $totalRecall + $totalWarrant;
									$totalActive = $totalInvestigation - ($totalActed + $totalNotActed);
									#($act_upon);

								};
							}
							

							

							$data[] = array('REGION' => $value->VALUE_,
											'ID' => $value->ID,
											'totalGrant' => $totalGrant,
											'totalDenial' => $totalDenial,
											'totalManifest' => $totalManifest,
											'totalTransfer' => $totalTransfer,
											'totalActed' => $totalActed,
											'totalRecall' => $totalRecall,
											'totalWarrant' => $totalWarrant,
											'totalNotActed' => $totalNotActed,
											'totalActive' => $totalActive,
											'totalInvestigation' => $totalInvestigation

										);

							#var_dump();
							#break;
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_pi_r2':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){

							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							$totalCarryOver = 0;
							$totalPSIR = 0;
							$totalManifest = 0;
							$totalSubmitted = 0;
							$totalbeActed = 0;

							$totalGrant = 0;
							$totalDenial = 0;
							$totalDismiss = 0;
							$totalWithdraw = 0;
							$totalReinv = 0;
							$totalOther = 0;
							$totalWarrant = 0;
							$totalRecall = 0;
							$totalDisposed = 0;
							$totalNotActed = 0;
							$totalPending = 0;
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){


									$payload->filter = ""; $payload->table = "F5T3"; $payload->field_office = $value1->NAME; $payload->filter_val="";$payload->filter_field="";
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->table = "F5T2_ACTED"; $payload->filter_field = "psir_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalPSIR += $upon->count;

									$payload->table = "F5T2_ACTED"; $payload->filter_field = "manifest_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalManifest += $upon->count;

									$totalSubmitted = $totalPSIR + $totalManifest;
									$totalbeActed = $totalSubmitted + $totalCarryOver;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Grant"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalGrant += $upon->count;

									
									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Den"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDenial += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Dismi"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDismiss += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "With"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalWithdraw += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Reinv"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalReinv += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Other"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Warrant"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalWarrant += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Recall"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRecall += $upon->count;

									$totalDisposed = $totalGrant + $totalDenial + $totalDismiss + $totalWithdraw + $totalReinv + $totalOther;
									$totalNotActed = $totalWarrant + $totalRecall;

									$totalPending = $totalbeActed - $totalDisposed;
								};
							}
							

							

							$data[] = array('REGION' => $value->VALUE_,
											'ID' => $value->ID,
											'totalCarryOver' => $totalCarryOver,
											'totalPSIR' => $totalPSIR,
											'totalManifest' => $totalManifest,
											'totalSubmitted' => $totalSubmitted,
											'totalbeActed' => $totalbeActed,
											'totalGrant' => $totalGrant,
											'totalDenial' => $totalDenial,
											'totalDismiss' => $totalDismiss,
											'totalWithdraw' => $totalWithdraw,
											'totalReinv' => $totalReinv,
											'totalOther' => $totalOther,
											'totalDisposed' => $totalDisposed,
											'totalWarrant' => $totalWarrant,
											'totalRecall' => $totalRecall,
											'totalNotActed' => $totalNotActed,
											'totalPending' => $totalPending,

										);

							#var_dump();
							#break;
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;


				case 'f5_regional_pi_r3':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){

							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							$totalCarryOver = 0;
							$totalRcv = 0;
							$totalInvestigation = 0;
							$totalCmpltd = 0;
							$totalActive = 0;
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){

									$payload->filter = ""; $payload->table = "F5T5"; $payload->field_office = $value1->NAME;
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->filter = ""; $payload->table = "F5T6_RCV"; $payload->field_office = $value1->NAME;
									$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalRcv += $rcv->count;

									$totalInvestigation = $totalCarryOver + $totalRcv;

									$payload->filter = ""; $payload->table = "F5T6_CMPLTD"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCmpltd += $upon->count;
									$totalActive = $totalInvestigation - $totalCmpltd;
								};
							}
							

							

							$data[] = array('REGION' => $value->VALUE_,
											'ID' => $value->ID,
											'carryOver' => $totalCarryOver,
											'totalRcv' => $totalRcv,
											'totalInvestigation' => $totalInvestigation,
											'totalCmpltd' => $totalCmpltd,
											'totalActive' => $totalActive,

										);

							#var_dump();
							#break;
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_ps_r1_p1':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){

							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							$totalCarryOver = 0;
							$totalRcv = 0;
							$totalInvestigation = 0;
							$totalRcv2 = 0;
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){

									$payload->filter = ""; $payload->table = "F5T7"; $payload->field_office = $value1->NAME;
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->filter = ""; $payload->table = "F5T8"; $payload->field_office = $value1->NAME;
									$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalRcv += $rcv->count;

									$totalRcv2 = $totalRcv;
									$totalInvestigation = $totalCarryOver + $totalRcv;
								};
							}
							

							

							$data[] = array('REGION' => $value->VALUE_,
											'ID' => $value->ID,
											'carryOver' => $totalCarryOver,
											'totalRcv' => $totalRcv,
											'totalRcv2' => $totalRcv2,
											'totalInvestigation' => $totalInvestigation,

										);

							#var_dump();
							#break;
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_ps_r1_p2':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							
							if($fieldOffice->status == 'SUCCESS'){
								$totalCarryOver = 0;
								$totalFullTerm = 0;
								$totalEarlyTerm = 0;
								$totalDiedTerm = 0;

								$totalAbs = 0;
								$totalComm = 0;
								$totalVio = 0;
								$totalOther = 0;
								$totalRevoc = 0;


								$totalInvestigation = 0;
								$totalRcv = 0;
								$totalRcv2 = 0;
								$totalTerm = 0;

								$totalTransfer = 0;
								$totalOthers = 0;
								$totalDropped = 0;
								$totalActive = 0;

								foreach ($fieldOffice->payload as $key1 => $value1){

									$payload->filter_val = ""; $payload->filter_field = ""; $payload->filter = ""; $payload->table = "F5T7"; $payload->field_office = $value1->NAME;
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->filter_val = ""; $payload->filter_field = ""; $payload->filter = ""; $payload->table = "F5T8"; $payload->field_office = $value1->NAME;
									$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalRcv += $rcv->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalFullTerm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalEarlyTerm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDiedTerm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalAbs += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalComm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalVio += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTransfer += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOthers += $upon->count;

									$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

									$totalRcv2 = $totalRcv;
									$totalInvestigation = $totalCarryOver + $totalRcv;
									$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

									$totalDropped = $totalTerm + $totalRevoc + $totalTransfer + $totalOthers;
									$totalActive = $totalInvestigation - $totalDropped;

								}
									$data[] = array('REGION' => $value->VALUE_,
											'totalInvestigation' => $totalInvestigation,
											'totalFullTerm' => $totalFullTerm,
											'totalEarlyTerm' => $totalEarlyTerm,
											'totalDiedTerm' => $totalDiedTerm,
											'totalTerm' => $totalTerm,
											'totalAbs' => $totalAbs,
											'totalComm' => $totalComm,
											'totalVio' => $totalVio,
											'totalOther' => $totalOther,
											'totalRevoc' => $totalRevoc,
											'totalTransfer' => $totalTransfer,
											'totalOthers' => $totalOthers,
											'totalDropped' => $totalDropped,
											'totalActive' => $totalActive,

										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_ps_r1_p3':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							
							if($fieldOffice->status == 'SUCCESS'){
								
								$totalFullTerm = 0;
								$totalEarlyTerm = 0;
								$totalDiedTerm = 0;

								$totalAbs = 0;
								$totalComm = 0;
								$totalVio = 0;
								$totalOther = 0;
								$totalRevoc = 0;


								
								$totalTerm = 0;

								$totalTransfer = 0;
								$totalOthers = 0;
								$totalDropped = 0;
								$totalExt = 0;
								foreach ($fieldOffice->payload as $key1 => $value1){

									
							
									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalFullTerm += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalEarlyTerm += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDiedTerm += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalAbs += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalComm += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalVio += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTransfer += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalExt += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOthers += $upon->count;

									$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

									
									$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

									$totalDropped = $totalTerm + $totalRevoc + $totalTransfer + $totalOthers;
									

								}
									$data[] = array('REGION' => $value->VALUE_,
											'totalFullTerm' => $totalFullTerm,
											'totalEarlyTerm' => $totalEarlyTerm,
											'totalDiedTerm' => $totalDiedTerm,
											'totalTerm' => $totalTerm,
											'totalAbs' => $totalAbs,
											'totalComm' => $totalComm,
											'totalVio' => $totalVio,
											'totalOther' => $totalOther,
											'totalRevoc' => $totalRevoc,
											'totalExt' => $totalExt,
											'totalTransfer' => $totalTransfer,
											'totalOthers' => $totalOthers,
											'totalDropped' => $totalDropped,
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

					
				case 'f5_regional_ps_r2_p1':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
							// (A)
							$totalCarryTerm = 0;
							$totalCarryRevoc = 0;
							$totalCarryExt = 0;
							$totalCarryOth = 0;
							$totalCarryTrans = 0;
							$totalCarryOver = 0;
							// (B)
							$totalTerm = 0;
							$totalRevoc = 0;
							$totalExt = 0;
							$totalOther = 0;
							$totalTrans = 0;
							$totalSubmitted = 0;
							// (C)
							$totalCasesBeActed = 0;


							if($fieldOffice->status == 'SUCCESS'){
								
								
								foreach ($fieldOffice->payload as $key1 => $value1){

									// (A) CARRY OVER
									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Termina"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryTerm += $upon->count;

									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Revoc"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryRevoc += $upon->count;

									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryExt += $upon->count;

									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryTrans += $upon->count;

									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryOth += $upon->count;

									$totalCarryOver = 	$totalCarryTerm + $totalCarryRevoc + $totalCarryExt + $totalCarryExt + $totalCarryOth;
									
									// (B) SUBMITTED TO COURT
									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Term"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTerm += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revoc"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRevoc += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalExt += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTrans += $upon->count;

									$totalSubmitted = $totalTerm + $totalRevoc + $totalExt + $totalOther + $totalTrans;


									$totalCasesBeActed = $totalCarryOver + $totalSubmitted;

								}
									$data[] = array('REGION' => $value->VALUE_,
													'totalCarryTerm' => $totalCarryTerm,
													'totalCarryRevoc' => $totalCarryRevoc, 
													'totalCarryExt' => $totalCarryExt,
													'totalCarryTrans' => $totalCarryTrans,
													'totalCarryOth' => $totalCarryOth,
													'totalCarryOver'=> $totalCarryOver,
													'totalTerm'=> $totalTerm,
													'totalRevoc'=> $totalRevoc,
													'totalExt'=> $totalExt,
													'totalOther'=> $totalOther,
													'totalTrans'=> $totalTrans,
													'totalSubmitted'=> $totalSubmitted,
													'totalCasesBeActed'=> $totalCasesBeActed,
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_ps_r2_p2':

						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
							// (A)
							
							$totalCarryOver = 0;
							// (B)
							
							$totalSubmitted = 0;
							// (C)
							$totalCasesBeActed = 0;

							$totalFullTerm = 0;
							$totalEarlyTerm = 0;
							$totalDiedTerm = 0;
							$totalTerm = 0;

							$totalAbs = 0;
							$totalComm = 0;
							$totalVio = 0;
							$totalOther = 0;
							$totalRevoc = 0;
							
							$totalExt = 0;
							$totalTransfer = 0;
							$totalOthers = 0;

							$totalDisposed = 0;
							$totalPending = 0;

							if($fieldOffice->status == 'SUCCESS'){
								
								
								foreach ($fieldOffice->payload as $key1 => $value1){

									// (A) CARRY OVER
									$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryOver += $upon->count;

									
									// (B) SUBMITTED TO COURT
									$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalSubmitted += $upon->count;

									//( C)
									$totalCasesBeActed = $totalCarryOver + $totalSubmitted;

									// (D) DISPOSED

									// (D - TERMINATION)
									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalFullTerm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalEarlyTerm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDiedTerm += $upon->count;

									$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

									// (D - REVOC)
									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalAbs += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalComm += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalVio += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

									// (D - OTHER)
									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Exten"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalExt += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTransfer += $upon->count;

									$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOthers += $upon->count;


									$totalDisposed = $totalTerm + $totalRevoc + $totalExt + $totalTransfer + $totalOthers;
									
									// (E)
									$totalPending = $totalCasesBeActed - $totalDisposed;

								}
									$data[] = array('REGION' => $value->VALUE_,
													'totalCasesBeActed'=> $totalCasesBeActed,
													'totalFullTerm'=> $totalFullTerm,
													'totalEarlyTerm'=> $totalEarlyTerm,
													'totalDiedTerm'=> $totalDiedTerm,
													'totalTerm'=> $totalTerm,
													'totalAbs'=> $totalAbs,
													'totalComm'=> $totalComm,
													'totalVio'=> $totalVio,
													'totalOther'=> $totalOther,
													'totalRevoc'=> $totalRevoc,
													'totalExt'=> $totalExt,
													'totalTransfer'=> $totalTransfer,
													'totalOthers'=> $totalOthers,
													'totalDisposed'=> $totalDisposed,
													'totalPending'=> $totalPending,
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_regional_ps_r3':

						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
							// (A)
							
							$totalCarryOver = 0;
							// (B)
							$totalRcv = 0;
							// (C)
							$totalCasesHandled = 0;
							// (D)
							$totalCompltd = 0;
							// (E)
							$totalCourtesy = 0;

							if($fieldOffice->status == 'SUCCESS'){
								
								
								foreach ($fieldOffice->payload as $key1 => $value1){

									// (A) CARRY OVER
									$payload->table = "F5T12"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryOver += $upon->count;

									
									// (B) COURTESY REFERRALS RECEIVED	
									$payload->table = "F5T13_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRcv += $upon->count;

									//( C)
									$totalCasesHandled = $totalCarryOver + $totalRcv;

									// (D) DISPOSED
									$payload->table = "F5T13_TERM"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCompltd += $upon->count;
									
									// (E)
									$totalCourtesy = $totalCasesHandled - $totalCompltd;

								}
									$data[] = array('REGION' => $value->VALUE_,
													'totalCarryOver'=> $totalCarryOver,
													'totalRcv'=> $totalRcv,
													'totalCasesHandled'=> $totalCasesHandled,
													'totalCompltd'=> $totalCompltd,
													'totalCourtesy'=> $totalCourtesy,
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f21_regional_ppi_r1_p1':

						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
							
							// (A)
							$totalCarryOver = 0;
							
							// (B)
							$totalRcv = 0;

							// (C)
							$totalCasesHandled = 0;


							if($fieldOffice->status == 'SUCCESS'){
								
								
								foreach ($fieldOffice->payload as $key1 => $value1){

									// (A) CARRY OVER
									$payload->table = "F21T1"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryOver += $upon->count;

									
									// (B) COURTESY REFERRALS RECEIVED	
									$payload->table = "F21T2_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRcv += $upon->count;

									//( C)
									$totalCasesHandled = $totalCarryOver + $totalRcv;


								}
									$data[] = array('REGION' => $value->VALUE_,
													'totalCarryOver'=> $totalCarryOver,
													'totalRcv'=> $totalRcv,
													'totalCasesHandled'=> $totalCasesHandled
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;


				case 'f21_regional_ppi_r1_p3':

						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						foreach ($array['payload'] as $key => $value){
							$payload->REGION = $value->ID;
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
							// (A)
							$totalCarryOver = 0;
							
							// (B)
							$totalRcv = 0;

							// (C)
							$totalCasesHandled = 0;


							// (D1)
							$totalInvestigationReport = 0;
							// (D2)
							$totalTransfer = 0;
							
							$totalOther = 0;

							$totalActed = 0;

							$totalActive = 0;

							if($fieldOffice->status == 'SUCCESS'){
								
								
								foreach ($fieldOffice->payload as $key1 => $value1){

									// (A) CARRY OVER
									$payload->table = "F21T1"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalCarryOver += $upon->count;

									
									// (B) COURTESY REFERRALS RECEIVED	
									$payload->table = "F21T2_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRcv += $upon->count;

									//( C)
									$totalCasesHandled = $totalCarryOver + $totalRcv;


									// (D1)
									$payload->table = "F21T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "o"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalInvestigationReport += $upon->count;

									// (D2)
									$payload->table = "F21T2_ACTED"; $payload->filter_field = "transfer_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalTransfer += $upon->count;

									
									$totalActed = $totalInvestigationReport  + $totalTransfer;

									$totalActive = $totalCasesHandled - $totalActed;

								}
									$data[] = array('REGION' => $value->VALUE_,
													'totalInvestigationReport'=> $totalInvestigationReport,
													'totalTransfer'=> $totalTransfer,
													'totalOther'=> $totalOther,
													'totalActed'=> $totalActed,
													'totalActive' => $totalActive,
										);
							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				//FIELDS
				//@breakpoint
				case 'f5_field_office_pi_f1_p1':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						

						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

						
						if($fieldOffice->status == 'SUCCESS'){

							foreach ($fieldOffice->payload as $key1 => $value1){
								$totalCarryOver = 0;
								$totalRcv = 0;
								$totalInvestigation = 0;

								$payload->filter = ""; $payload->table = "F5T1"; $payload->field_office = $value1->NAME;
								$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalCarryOver += $carryOver->count;


								$payload->filter = ""; $payload->table = "F5T2_RCV"; $payload->field_office = $value1->NAME;
								$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalRcv += $rcv->count;

								$totalInvestigation = $totalCarryOver + $totalRcv;

								$data[] = array('FIELD' => $value1->NAME,
										'carryOver' => $totalCarryOver,
										'totalRcv' => $totalRcv,
										'totalInvestigation' => $totalInvestigation,

									);
							};
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;
				case 'f5_field_office_pi_f1_p2':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						

						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

						
						if($fieldOffice->status == 'SUCCESS'){

							foreach ($fieldOffice->payload as $key1 => $value1){
								$totalGrant = 0;
								$totalDenial = 0;
								$totalManifest = 0;
								$totalTransfer = 0;
								$totalActed = 0;
								$totalRecall = 0;
								$totalWarrant = 0;
								$totalNotActed = 0;

								$totalCarryOver = 0;
								$totalRcv = 0;
								$totalInvestigation = 0;
								$totalRcv2 = 0;

								$totalActive = 0;

								$payload->filter = ""; $payload->table = "F5T1"; $payload->field_office = $value1->NAME; $payload->filter_val="";$payload->filter_field="";
								$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalCarryOver += $carryOver->count;


								$payload->filter = ""; $payload->table = "F5T2_RCV"; $payload->field_office = $value1->NAME;$payload->filter_val="";$payload->filter_field="";
								$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalRcv += $rcv->count;

								$totalRcv2 = $totalRcv;
								$totalInvestigation = $totalCarryOver + $totalRcv;


								#echo $this->Cmis_F5T2_model->fetchF5T2_RCV_ByYM($payload);
								$payload->table = "F5T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "Grant"; $payload->field_office = $value1->NAME;
								$grant_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalGrant += $grant_upon->count;

								$payload->table = "F5T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "Denial"; $payload->field_office = $value1->NAME;
								$denial_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalDenial += $denial_upon->count;

								$payload->table = "F5T2_ACTED"; $payload->filter_field = "manifest_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
								$manifest_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalManifest += $manifest_upon->count;

								$payload->table = "F5T2_ACTED"; $payload->filter_field = "transfer_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
								$transfer_upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTransfer += $transfer_upon->count;

								$payload->table = "F5T2_NOTACTED"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Recall"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalRecall += $upon->count;

								$payload->table = "F5T2_NOTACTED"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Warrant"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalWarrant += $upon->count;

								
								$totalActed = $totalGrant + $totalDenial + $totalManifest + $totalTransfer;
								$totalNotActed = $totalRecall + $totalWarrant;
								$totalActive = $totalInvestigation - ($totalActed + $totalNotActed);
								#($act_upon);
								$data[] = array('FIELD' => $value1->NAME,
										'totalGrant' => $totalGrant,
										'totalDenial' => $totalDenial,
										'totalManifest' => $totalManifest,
										'totalTransfer' => $totalTransfer,
										'totalActed' => $totalActed,
										'totalRecall' => $totalRecall,
										'totalWarrant' => $totalWarrant,
										'totalNotActed' => $totalNotActed,
										'totalActive' => $totalActive,
										'totalInvestigation' => $totalInvestigation);
							};
						}
						

						

						

									

						#var_dump();
						#break;
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_field_office_pi_f2':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
						

							
							$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							
							if($fieldOffice->status == 'SUCCESS'){

								foreach ($fieldOffice->payload as $key1 => $value1){
									$totalCarryOver = 0;
									$totalPSIR = 0;
									$totalManifest = 0;
									$totalSubmitted = 0;
									$totalbeActed = 0;

									$totalGrant = 0;
									$totalDenial = 0;
									$totalDismiss = 0;
									$totalWithdraw = 0;
									$totalReinv = 0;
									$totalOther = 0;
									$totalWarrant = 0;
									$totalRecall = 0;
									$totalDisposed = 0;
									$totalNotActed = 0;
									$totalPending = 0;


									$payload->filter = ""; $payload->table = "F5T3"; $payload->field_office = $value1->NAME; $payload->filter_val="";$payload->filter_field="";
									$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
									$totalCarryOver += $carryOver->count;


									$payload->table = "F5T2_ACTED"; $payload->filter_field = "psir_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalPSIR += $upon->count;

									$payload->table = "F5T2_ACTED"; $payload->filter_field = "manifest_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalManifest += $upon->count;

									$totalSubmitted = $totalPSIR + $totalManifest;
									$totalbeActed = $totalSubmitted + $totalCarryOver;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Grant"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalGrant += $upon->count;

									
									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Den"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDenial += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Dismi"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalDismiss += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "With"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalWithdraw += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Reinv"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalReinv += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Other"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalOther += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Warrant"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalWarrant += $upon->count;

									$payload->table = "F5T4"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Recall"; $payload->field_office = $value1->NAME;
									$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
									$totalRecall += $upon->count;

									$totalDisposed = $totalGrant + $totalDenial + $totalDismiss + $totalWithdraw + $totalReinv + $totalOther;
									$totalNotActed = $totalWarrant + $totalRecall;

									$totalPending = $totalbeActed - $totalDisposed;

									$data[] = array('FIELD' => $value1->NAME,
											'totalCarryOver' => $totalCarryOver,
											'totalPSIR' => $totalPSIR,
											'totalManifest' => $totalManifest,
											'totalSubmitted' => $totalSubmitted,
											'totalbeActed' => $totalbeActed,
											'totalGrant' => $totalGrant,
											'totalDenial' => $totalDenial,
											'totalDismiss' => $totalDismiss,
											'totalWithdraw' => $totalWithdraw,
											'totalReinv' => $totalReinv,
											'totalOther' => $totalOther,
											'totalDisposed' => $totalDisposed,
											'totalWarrant' => $totalWarrant,
											'totalRecall' => $totalRecall,
											'totalNotActed' => $totalNotActed,
											'totalPending' => $totalPending,

										);
								};
							}
							

							

							

							#var_dump();
							#break;
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;
				case 'f5_field_office_pi_f3':
						//var_dump($payload);
						$array = (array)json_decode($this->Pis_model->fetchAllRegion2());
						#var_dump($array);
						$data = array();
							
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
						if($fieldOffice->status == 'SUCCESS'){
							foreach ($fieldOffice->payload as $key1 => $value1){

							$totalCarryOver = 0;
							$totalRcv = 0;
							$totalInvestigation = 0;
							$totalCmpltd = 0;
							$totalActive = 0;


							$payload->filter = ""; $payload->table = "F5T5"; $payload->field_office = $value1->NAME;
							$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
							$totalCarryOver += $carryOver->count;


							$payload->filter = ""; $payload->table = "F5T6_RCV"; $payload->field_office = $value1->NAME;
							$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
							$totalRcv += $rcv->count;

							$totalInvestigation = $totalCarryOver + $totalRcv;

							$payload->filter = ""; $payload->table = "F5T6_CMPLTD"; $payload->field_office = $value1->NAME;
							$upon = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
							$totalCmpltd += $upon->count;
							$totalActive = $totalInvestigation - $totalCmpltd;

							$data[] = array('FIELD' => $value1->NAME,
											'carryOver' => $totalCarryOver,
											'totalRcv' => $totalRcv,
											'totalInvestigation' => $totalInvestigation,
											'totalCmpltd' => $totalCmpltd,
											'totalActive' => $totalActive,

										);
							};
						}
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;
				case 'f5_field_office_ps_f1_p1':
						//var_dump($payload);
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

						
						if($fieldOffice->status == 'SUCCESS'){

							foreach ($fieldOffice->payload as $key1 => $value1){
								$totalCarryOver = 0;
								$totalRcv = 0;
								$totalInvestigation = 0;
								$totalRcv2 = 0;

								$payload->filter = ""; $payload->table = "F5T7"; $payload->field_office = $value1->NAME;
								$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalCarryOver += $carryOver->count;


								$payload->filter = ""; $payload->table = "F5T8"; $payload->field_office = $value1->NAME;
								$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalRcv += $rcv->count;

								$totalRcv2 = $totalRcv;
								$totalInvestigation = $totalCarryOver + $totalRcv;
							
								$data[] = array('FIELD' => $value1->NAME,
										'carryOver' => $totalCarryOver,
										'totalRcv' => $totalRcv,
										'totalRcv2' => $totalRcv2,
										'totalInvestigation' => $totalInvestigation,

									);
							};
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;
			case 'f5_field_office_ps_f1_p2':
						//var_dump($payload);
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

						
						if($fieldOffice->status == 'SUCCESS'){

							foreach ($fieldOffice->payload as $key1 => $value1){
								$totalCarryOver = 0;
								$totalFullTerm = 0;
								$totalEarlyTerm = 0;
								$totalDiedTerm = 0;

								$totalAbs = 0;
								$totalComm = 0;
								$totalVio = 0;
								$totalOther = 0;
								$totalRevoc = 0;


								$totalInvestigation = 0;
								$totalRcv = 0;
								$totalRcv2 = 0;
								$totalTerm = 0;

								$totalTransfer = 0;
								$totalOthers = 0;
								$totalDropped = 0;
								$totalActive = 0;

								$payload->filter_val = ""; $payload->filter_field = ""; $payload->filter = ""; $payload->table = "F5T7"; $payload->field_office = $value1->NAME;
								$carryOver = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalCarryOver += $carryOver->count;


								$payload->filter_val = ""; $payload->filter_field = ""; $payload->filter = ""; $payload->table = "F5T8"; $payload->field_office = $value1->NAME;
								$rcv = json_decode($this->Cmis_F5PCS_model->callProcedure1($payload));
								$totalRcv += $rcv->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalFullTerm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalEarlyTerm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalDiedTerm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalAbs += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalComm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalVio += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOther += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTransfer += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOthers += $upon->count;

								$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

								$totalRcv2 = $totalRcv;
								$totalInvestigation = $totalCarryOver + $totalRcv;
								$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

								$totalDropped = $totalTerm + $totalRevoc + $totalTransfer + $totalOthers;
								$totalActive = $totalInvestigation - $totalDropped;

								$data[] = array('FIELD' => $value1->NAME,
										'totalInvestigation' => $totalInvestigation,
										'totalFullTerm' => $totalFullTerm,
										'totalEarlyTerm' => $totalEarlyTerm,
										'totalDiedTerm' => $totalDiedTerm,
										'totalTerm' => $totalTerm,
										'totalAbs' => $totalAbs,
										'totalComm' => $totalComm,
										'totalVio' => $totalVio,
										'totalOther' => $totalOther,
										'totalRevoc' => $totalRevoc,
										'totalTransfer' => $totalTransfer,
										'totalOthers' => $totalOthers,
										'totalDropped' => $totalDropped,
										'totalActive' => $totalActive,

									);
							};
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

			case 'f5_field_office_ps_f1_p3':
						//var_dump($payload);
						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

							
						if($fieldOffice->status == 'SUCCESS'){
								
								
							foreach ($fieldOffice->payload as $key1 => $value1){
								$totalFullTerm = 0;
								$totalEarlyTerm = 0;
								$totalDiedTerm = 0;

								$totalAbs = 0;
								$totalComm = 0;
								$totalVio = 0;
								$totalOther = 0;
								$totalRevoc = 0;


								
								$totalTerm = 0;

								$totalTransfer = 0;
								$totalOthers = 0;
								$totalDropped = 0;
								$totalExt = 0;
								
						
								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalFullTerm += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalEarlyTerm += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalDiedTerm += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalAbs += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalComm += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalVio += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOther += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTransfer += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalExt += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOthers += $upon->count;

								$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

								
								$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

								$totalDropped = $totalTerm + $totalRevoc + $totalTransfer + $totalOthers;
								
								$data[] = array('FIELD' => $value1->NAME,
										'totalFullTerm' => $totalFullTerm,
										'totalEarlyTerm' => $totalEarlyTerm,
										'totalDiedTerm' => $totalDiedTerm,
										'totalTerm' => $totalTerm,
										'totalAbs' => $totalAbs,
										'totalComm' => $totalComm,
										'totalVio' => $totalVio,
										'totalOther' => $totalOther,
										'totalRevoc' => $totalRevoc,
										'totalExt' => $totalExt,
										'totalTransfer' => $totalTransfer,
										'totalOthers' => $totalOthers,
										'totalDropped' => $totalDropped,
									);

							}
						}
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;


				case 'f5_field_office_ps_f2_p1':
						//var_dump($payload);
						
						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));

						
						if($fieldOffice->status == 'SUCCESS'){
							
							foreach ($fieldOffice->payload as $key1 => $value1){
								// (A)
								$totalCarryTerm = 0;
								$totalCarryRevoc = 0;
								$totalCarryExt = 0;
								$totalCarryOth = 0;
								$totalCarryTrans = 0;
								$totalCarryOver = 0;
								// (B)
								$totalTerm = 0;
								$totalRevoc = 0;
								$totalExt = 0;
								$totalOther = 0;
								$totalTrans = 0;
								$totalSubmitted = 0;
								// (C)
								$totalCasesBeActed = 0;


								// (A) CARRY OVER
								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Termina"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryTerm += $upon->count;

								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Revoc"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryRevoc += $upon->count;

								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryExt += $upon->count;

								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryTrans += $upon->count;

								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryOth += $upon->count;

								$totalCarryOver = 	$totalCarryTerm + $totalCarryRevoc + $totalCarryExt + $totalCarryExt + $totalCarryOth;
								
								// (B) SUBMITTED TO COURT
								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Term"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTerm += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revoc"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalRevoc += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Extension"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalExt += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOther += $upon->count;

								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTrans += $upon->count;

								$totalSubmitted = $totalTerm + $totalRevoc + $totalExt + $totalOther + $totalTrans;


								$totalCasesBeActed = $totalCarryOver + $totalSubmitted;
								

								$data[] = array('FIELD' => $value1->NAME,
													'totalCarryTerm' => $totalCarryTerm,
													'totalCarryRevoc' => $totalCarryRevoc, 
													'totalCarryExt' => $totalCarryExt,
													'totalCarryTrans' => $totalCarryTrans,
													'totalCarryOth' => $totalCarryOth,
													'totalCarryOver'=> $totalCarryOver,
													'totalTerm'=> $totalTerm,
													'totalRevoc'=> $totalRevoc,
													'totalExt'=> $totalExt,
													'totalOther'=> $totalOther,
													'totalTrans'=> $totalTrans,
													'totalSubmitted'=> $totalSubmitted,
													'totalCasesBeActed'=> $totalCasesBeActed,
										);
							}
						}
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_field_office_ps_f2_p2':

						
						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
						

						if($fieldOffice->status == 'SUCCESS'){
							
							
							foreach ($fieldOffice->payload as $key1 => $value1){

								// (A)
								$totalCarryOver = 0;
								// (B)
								
								$totalSubmitted = 0;
								// (C)
								$totalCasesBeActed = 0;

								$totalFullTerm = 0;
								$totalEarlyTerm = 0;
								$totalDiedTerm = 0;
								$totalTerm = 0;

								$totalAbs = 0;
								$totalComm = 0;
								$totalVio = 0;
								$totalOther = 0;
								$totalRevoc = 0;
								
								$totalExt = 0;
								$totalTransfer = 0;
								$totalOthers = 0;

								$totalDisposed = 0;
								$totalPending = 0;

								// (A) CARRY OVER
								$payload->table = "F5T10"; $payload->filter_field = "submitted_decision"; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryOver += $upon->count;

								
								// (B) SUBMITTED TO COURT
								$payload->table = "F5T9"; $payload->filter_field = "disposed_decision"; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalSubmitted += $upon->count;

								//( C)
								$totalCasesBeActed = $totalCarryOver + $totalSubmitted;

								// (D) DISPOSED

								// (D - TERMINATION)
								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Full Term"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalFullTerm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Early"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalEarlyTerm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Died"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalDiedTerm += $upon->count;

								$totalTerm = $totalFullTerm + $totalEarlyTerm + $totalDiedTerm;

								// (D - REVOC)
								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Abs"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalAbs += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Comm"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalComm += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Vio"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalVio += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Revocation - Other"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOther += $upon->count;

								$totalRevoc = $totalAbs + $totalComm + $totalVio + $totalOther;

								// (D - OTHER)
								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Exten"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalExt += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Transfer"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalTransfer += $upon->count;

								$payload->table = "F5T11"; $payload->filter_field = "disposed_decision"; $payload->filter_val = "Others"; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalOthers += $upon->count;


								$totalDisposed = $totalTerm + $totalRevoc + $totalExt + $totalTransfer + $totalOthers;
								
								// (E)
								$totalPending = $totalCasesBeActed - $totalDisposed;

								$data[] = array('FIELD' => $value1->NAME,
												'totalCasesBeActed'=> $totalCasesBeActed,
												'totalFullTerm'=> $totalFullTerm,
												'totalEarlyTerm'=> $totalEarlyTerm,
												'totalDiedTerm'=> $totalDiedTerm,
												'totalTerm'=> $totalTerm,
												'totalAbs'=> $totalAbs,
												'totalComm'=> $totalComm,
												'totalVio'=> $totalVio,
												'totalOther'=> $totalOther,
												'totalRevoc'=> $totalRevoc,
												'totalExt'=> $totalExt,
												'totalTransfer'=> $totalTransfer,
												'totalOthers'=> $totalOthers,
												'totalDisposed'=> $totalDisposed,
												'totalPending'=> $totalPending,
									);
							}
						}
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f5_field_office_ps_f3':

						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
						
						if($fieldOffice->status == 'SUCCESS'){
							
							
							foreach ($fieldOffice->payload as $key1 => $value1){

								// (A)
								$totalCarryOver = 0;
								// (B)
								$totalRcv = 0;
								// (C)
								$totalCasesHandled = 0;
								// (D)
								$totalCompltd = 0;
								// (E)
								$totalCourtesy = 0;


								// (A) CARRY OVER
								$payload->table = "F5T12"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryOver += $upon->count;

								
								// (B) COURTESY REFERRALS RECEIVED	
								$payload->table = "F5T13_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalRcv += $upon->count;

								//( C)
								$totalCasesHandled = $totalCarryOver + $totalRcv;

								// (D) DISPOSED
								$payload->table = "F5T13_TERM"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCompltd += $upon->count;
								
								// (E)
								$totalCourtesy = $totalCasesHandled - $totalCompltd;

								$data[] = array('FIELD' => $value1->NAME,
												'totalCarryOver'=> $totalCarryOver,
												'totalRcv'=> $totalRcv,
												'totalCasesHandled'=> $totalCasesHandled,
												'totalCompltd'=> $totalCompltd,
												'totalCourtesy'=> $totalCourtesy,
									);
							}
						}
						
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f21_field_office_ppi_f1_p1':

						
						$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
						
						// (A)


						if($fieldOffice->status == 'SUCCESS'){
							
							foreach ($fieldOffice->payload as $key1 => $value1){

								$totalCarryOver = 0;
								
								// (B)
								$totalRcv = 0;

								// (C)
								$totalCasesHandled = 0;
								
								// (A) CARRY OVER
								$payload->table = "F21T1"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalCarryOver += $upon->count;

								
								// (B) COURTESY REFERRALS RECEIVED	
								$payload->table = "F21T2_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
								$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
								$totalRcv += $upon->count;

								//( C)
								$totalCasesHandled = $totalCarryOver + $totalRcv;

								$data[] = array('FIELD' => $value1->NAME,
												'totalCarryOver'=> $totalCarryOver,
												'totalRcv'=> $totalRcv,
												'totalCasesHandled'=> $totalCasesHandled
									);

							}
						}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;

				case 'f21_field_office_ppi_f1_p3':

						
					$fieldOffice = json_decode($this->Pis_model->fetchFieldOfficeByRegion($payload));
					// (A)

					if($fieldOffice->status == 'SUCCESS'){
						
						
						foreach ($fieldOffice->payload as $key1 => $value1){

							$totalCarryOver = 0;
							
							// (B)
							$totalRcv = 0;

							// (C)
							$totalCasesHandled = 0;


							// (D1)
							$totalInvestigationReport = 0;
							// (D2)
							$totalTransfer = 0;
							
							$totalOther = 0;

							$totalActed = 0;

							$totalActive = 0;

							// (A) CARRY OVER
							$payload->table = "F21T1"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
							$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
							$totalCarryOver += $upon->count;

							
							// (B) COURTESY REFERRALS RECEIVED	
							$payload->table = "F21T2_RCV"; $payload->filter_field = ""; $payload->filter_val = ""; $payload->field_office = $value1->NAME;
							$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
							$totalRcv += $upon->count;

							//( C)
							$totalCasesHandled = $totalCarryOver + $totalRcv;


							// (D1)
							$payload->table = "F21T2_ACTED"; $payload->filter_field = "ppo_recommendation"; $payload->filter_val = "o"; $payload->field_office = $value1->NAME;
							$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
							$totalInvestigationReport += $upon->count;

							// (D2)
							$payload->table = "F21T2_ACTED"; $payload->filter_field = "transfer_date"; $payload->filter_val = "20"; $payload->field_office = $value1->NAME;
							$upon = json_decode($this->Cmis_F21PCS_model->callProcedure1($payload));
							$totalTransfer += $upon->count;

							
							$totalActed = $totalInvestigationReport  + $totalTransfer;

							$totalActive = $totalCasesHandled - $totalActed;

							$data[] = array('FIELD' => $value1->NAME,
											'totalInvestigationReport'=> $totalInvestigationReport,
											'totalTransfer'=> $totalTransfer,
											'totalOther'=> $totalOther,
											'totalActed'=> $totalActed,
											'totalActive' => $totalActive,
								);
						}
					}
						$response = array('status' => 'SUCCESS',
								  'message' => 'SUCCESS',
								  'payload' => $data);
						echo json_encode($response);
					break;
			
			}
		}
	}


}