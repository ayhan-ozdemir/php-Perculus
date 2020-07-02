<?php
date_default_timezone_set('Europe/Istanbul');

class phpPerculus
{
	/**
	* @date		 : 26/06/2020
	* @author	 : Ayhan Özdemir ayhan@cumhuriyet.edu.tr ayhanozdemir.sivas@gmail.com
	* @copyright : Ayhan Özdemir
	* @license   : http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License
	
	* DESCRIPTIONS 
	* This class includes PHP-based functions that simplify the use of the PERCULUS API.
	* Perculus is a virtual classroom system developed by Advancity.
	*	
	* AÇIKLAMA
	* Bu sınıf, PERCULUS API'sinin kullanımını basitleştiren PHP tabanlı işlevler içerir.
	* Perculus, Advancity tarafından geliştirilen bir sanal sınıf sistemidir.
	*	
	* CLASS FEATURES
	* 1. Create a virtual classroom
	*    createClassroom($classroomName, $description, $startDate, $duration, $tags)
	* 2. Update the created virtual classroom information.
	*    updateClassroom($classroomID, $classroomName, $description, $startDate, $duration, $tags)
	* 3. Delete the virtual classroom.
	*    ddeleteClassroom($classroomID)
	* 4. Add participants to the virtual classroom.
	*    addParticipant($classroomID, $userID, $name, $surname, $email, $role, $mobile)
	* 5. Delete participant from the virtual classroom.
	*    deleteParticipant($classroomID, $participantID)
	* 
	* SINIF ÖZELLİKLERİ
	* 1. Sanal sınıf oluşturun.
	*    createClassroom($classroomName, $description, $startDate, $duration, $tags)
	* 2. Oluşturulan sanal sınıf bilgilerini güncelleyin.
	*    updateClassroom($classroomID, $classroomName, $description, $startDate, $duration, $tags)
	* 3. Sanal sınıfı silin.
	*    deleteClassroom($classroomID)
	* 4. Sanal sınıfa katılımcı ekleyin.
	*    addParticipant($classroomID, $userID, $name, $surname, $email, $role, $mobile)
	* 5. Sanal sınıftaki katılımcıyı silin.
	*    deleteParticipant($classroomID, $participantID)
	*
	* RESOURCES - KAYNAKLAR
	* Perculus Plus SDK: https://github.com/advancity/perculus-plus-sdk
	* Perculus Plus API: https://perculus-v3.almscloud.com/xapi/swagger/index.html
	* Perculus Plus API: https://plus.perculus.com/xapi/swagger/index.html
	*
	**/


	/*
	API_USER_NAME, API_PASSWORD and API_ACCOUNT_ID must be taken from Advancity Company.
	API_USER_NAME, API_PASSWORD ve API_ACCOUNT Advancity firmasından alınmalıdır.
	*/
	const API_USER_NAME = '****';
	const API_PASSWORD = '****';
	const API_ACCOUNT_ID = '*****';
	
	const API_TOKEN_URL = 'https://plus.perculus.com/auth/connect/token';
	const API_SESSION_URL = 'https://plus.perculus.com/xapi/session';
	const API_LANG = 'tr';
	const API_ENCODING = 'UTF-8';
	const API_TIMEOUT = 30;
	
	public $accessToken;
	public $tokenErrorCode;
	public $tokenErrorDescription;
	public $classroomID;
	public $classroomErrorCode;
	public $classroomErrorDescription;
	public $participantID;
	public $participantErrorCode;
	public $participantErrorDescription;
	
	public function __construct()
	{
		// Get a token from perculus system - Perculus sisteminen jeton al 
		$params = array('username' => self::API_USER_NAME,
				'password' => self::API_PASSWORD,
				'account_id' => self::API_ACCOUNT_ID,
				'client_id' => 'api',
				'grant_type' => 'password');
		$paramString = http_build_query($params);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_TOKEN_URL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, '');
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramString);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("application/x-www-form-urlencoded"));
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response, true);
		if ( isset($json['error']) )
		{
			//Connection failed - Bağlantı başarısız
			$this->accessToken = '';
			$this->tokenErrorCode = $json['error'];
			$this->tokenErrorDescription = $json['error_description'];
		}
		else
		{
			//Connection successful. - Bağlantı başarılı
			$this->accessToken = $json['access_token'];
			$this->tokenErrorCode = '';
			$this->tokenErrorDescription = '';
		}
	}
	
	public function createClassroom($classroomName, $description, $startDate, $duration, $tags)
	{
		/* 
		Create a virtual classroom - Sanal sınıf oluştur.
		Virtual classroom creation model - Sanal sınıf oluşturma modeli (JSON format)
		{
  			"session_id"		: "string",
  			"name"			: "string",
  			"description"		: "string",
  			"tags"			: "string",
  			"start_date"		: "2020-01-18T21:20:46.430Z",
  			"duration"		: 0,
  			"lang"			: "string",
  			"options"		: 
			{
    				"syscheck_on_startup"	: true,
    				"allow_rating"		: true,
    				"preparation_time"	: 0,
    				"chat"			: 
				{
      					"offMessageModule"		: false,
      					"offGeneralMsging"		: false,
      					"offGeneralMsgLimitForUser"	: false,
      					"offSpecialMsging"		: false,
      					"offSpecialMsgToAdmin"		: false,
      					"offSpecialMsgToUser"		: false,
      					"offNewMsgSound"		: false,
      					"offClearForReplay"		: false,
      					"onNewMsgSoundInAll"		: false,
      					"onNewMsgNotifyInAll"		: false
    				},
				"duration"		: 
				{
					"allowExtendTime"		: true,
					"useRemainingTime"		: false
    				}
  			}
		}
		*/
		$params = array ('name' => $classroomName,
				 'description' => $description,
				 'tags' => $tags,
				 'start_date' => $startDate,
				 'duration' => $duration,
				 'lang' => self::API_LANG);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_SESSION_URL);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, self::API_ENCODING);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
							     "Content-Type: application/json",
							     "Authorization: Bearer ".$this->accessToken));
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response, true);
		if ( isset($json['code']) )
		{
			//Creating virtual classroom failed - Sanal sınıf oluşturma işlemi başarısız
			$this->classroomID = '';
			$this->classroomErrorCode = $json['code'];
			$this->classroomErrorDescription = $json['details'];
		}
		else
		{
			//Creating virtual classroom successful - Sanal sınıf oluşturma işlemi başarılı
			$this->classroomID = $json['session_id'];
			$this->classroomErrorCode = '';
			$this->classroomErrorDescription = '';
		}
	} //endFunction classroomCreate

	public function updateClassroom($classroomID, $classroomName, $description, $startDate, $duration, $tags)
	{
		/* 
		Update virtual classroom information - Sanal sınıf bilgilerini güncelle
		Virtual classroom update model - Sanal sınıf güncelleme modeli (JSON format)
		{
  			"session_id"		: "string",
  			"name"			: "string",
  			"description"		: "string",
  			"tags"			: "string",
  			"start_date"		: "2020-01-18T21:20:46.430Z",
  			"duration"		: 0,
  			"lang"			: "string",
  			"options"		: 
			{
				"syscheck_on_startup"	: true,
				"allow_rating"		: true,
				"preparation_time"	: 0,
				"chat"			: 
				{
					"offMessageModule"		: false,
					"offGeneralMsging"		: false,
					"offGeneralMsgLimitForUser"	: false,
					"offSpecialMsging"		: false,
					"offSpecialMsgToAdmin"		: false,
					"offSpecialMsgToUser"		: false,
					"offNewMsgSound"		: false,
					"offClearForReplay"		: false,
					"onNewMsgSoundInAll"		: false,
					"onNewMsgNotifyInAll"		: false
				},
    				"duration"		: 
				{
	      				"allowExtendTime"	: true,
	     				"useRemainingTime"	: false
	    			}
	  		}
		}
		*/
		$params = array ('name' => $classroomName,
				 'description' => $description,
				 'tags' => $tags,
				 'start_date' => $startDate,
				 'duration' => $duration,
				 'lang' => self::API_LANG);
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_SESSION_URL.'/'.$classroomID);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, self::API_ENCODING);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
							     "Content-Type: application/json",
							     "Authorization: Bearer ".$this->accessToken));
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response, true);
		if ( isset($json['code']) )
		{
			//Updating virtual classroom failed - Sanal sınıf güncelleme işlemi başarısız
			$this->classroomID = '';
			$this->classroomErrorCode = $json['code'];
			$this->classroomErrorDescription = $json['details'];
		}
		else
		{
			//Updating virtual classroom successful - Sanal sınıf güncelleme işlemi başarılı
			$this->classroomID = $json['session_id'];
			$this->classroomErrorCode = '';
			$this->classroomErrorDescription = '';
		}
	} //endFunction classroomUpdate

	public function deleteClassroom($classroomID)
	{
		// Delete a virtual classroom - Sanal sınıfı sil.
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_SESSION_URL.'/'.$classroomID);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, self::API_ENCODING);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
							     "Content-Type: application/json",
							     "Authorization: Bearer ".$this->accessToken));
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response, true);
		if ( isset($json['code']) )
		{
			//Deleting virtual classroom failed - Sanal sınıf silme işlemi başarısız
			$this->classroomID = '';
			$this->classroomErrorCode = $json['code'];
			$this->classroomErrorDescription = $json['details'];
		}
		else
		{
			//Deleting virtual classroom successful - Sanal sınıf silme işlemi başarılı
			$this->classroomID = $json['session_id'];
			$this->classroomErrorCode = '';
			$this->classroomErrorDescription = '';
		}
	} //endFunction classroomDelete

	public function addParticipant($classroomID, $userID, $name, $surname, $email, $role, $mobile)
	{
		/* 
		Add a participant - Katılımcı ekle.
		Add participant data model - Katılımcı ekleme veri modeli (JSON format)
		{
    			"user_id"		: "string",
	    		"name"			: "string",
   			"surname"		: "string",
	    		"email"			: "string",
	    		"role"			: "string",
	    		"mobile"		: "string",
	    		"avatar"		: "string"
  		}

		Descriptions for role field - Rol alanı için açıklamalar
		a: Admin - Yönetici
		e: Teacher - Eğitmen
		e-: Restricted Teacher (Can't see shared files) - Kısıtlı Eğitmen (Paylaşılan dosyaları göremez)
		u: Standard Participant - Standart Katılımcı (öğrenci ya da yetkisiz kullanıcı denebilir)
		*/
		$params = array ('user_id'=>$userID,
						 'name'=>$name,
						 'surname'=>$surname,
						 'email'=>$email,
						 'role'=>$role,
						 'mobile'=>$mobile,
						 'avatar'=>'testAdmin'
						 );
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_SESSION_URL.'/'.$classroomID."/attendee" );
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, self::API_ENCODING);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($params));
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
													 "Content-Type: application/json",
													 "Authorization: Bearer ".$this->accessToken));
		$response = curl_exec($curl);
		curl_close($curl);
		$json = json_decode($response, true);
		if ( isset($json['code']) )
		{
			//Adding participants failed - Katılımcı ekleme başarısız
			$this->ParticipantID = '';
			$this->participantErrorCode = $json['code'];
			$this->participantErrorDescription = $json['detail'];
		}
		else
		{
			//Adding participants successful - Katılımcı ekleme başarılı
			$this->participantID = str_replace('"','',$response);
			$this->participantErrorCode = '';
			$this->participantErrorDescription = '';
		}
	} //endFunction addParticipant
	
	public function deleteParticipant($classroomID, $participantID)
	{
		// Delete a participant - Katılımcı sil.
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, self::API_SESSION_URL.'/'.$classroomID."/attendee/".$participantID);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		curl_setopt($curl, CURLOPT_POSTREDIR, 3);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, self::API_TIMEOUT);
		curl_setopt($curl, CURLOPT_ENCODING, self::API_ENCODING);
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($curl, CURLOPT_HEADER, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Accept: application/json",
													 "Content-Type: application/json",
													 "Authorization: Bearer ".$this->accessToken));
		$response = curl_exec($curl);
		print_r ($response);
		curl_close($curl);
		$json = json_decode($response, true);
		echo "<br><br>".$json."<br><br>";
		if ( isset($json['code']) )
		{
			//Adding participants failed - Katılımcı ekleme başarısız
			$this->ParticipantID = '';
			$this->participantErrorCode = $json['code'];
			$this->participantErrorDescription = $json['details'];
		}
		else
		{
			//Adding participants successful - Katılımcı ekleme başarılı
			$this->participantID = $participantID;
			$this->participantErrorCode = '';
			$this->participantErrorDescription = '';
		}
	} //endFunction deleteParticipant

} //endClass phpPerculus
?>
