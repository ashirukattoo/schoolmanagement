<?php 

	namespace Config;
	/**
	 * For Validation of Administration
	 */
	class Administration {
		public $employee = [
			'empFname' = 'required | min_length[3] | alpha',
			'empMname' = 'required',
			'empSurname' = 'required',
			'empSex' = 'required',
			'empEmail' = 'required',
			'empPassword' = 'required',
			'empRole' = 'required',
			'empPosition' = 'required',
			'empHired' = 'required',
			'empStatus' = 'required',
			'empFname' = 'required',
			'empFname' = 'required',
			'empFname' = 'required'
		];		
		public function FunctionName($value=''){
			// code...
		}
	}