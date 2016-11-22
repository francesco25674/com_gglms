package 
{

	//--- Import required classes ---
	import flash.external.ExternalInterface;//for invoking JavaScript in HTML document
	import fl.controls.Button;//for button functionality
	import flash.events.MouseEvent;//for making things clickable
	import flash.display.MovieClip;//for handling movieclips
	import fl.transitions.*;//for fading movieclips in and out
	import fl.transitions.easing.*;//for fading movieclips in and out
	import com.pipwerks.SCORM;//for SCORM support

	import flash.external.ExternalInterface;


	public class scorm_conn
	{

		private var _fla:MovieClip;
		private var scorm:SCORM;
		private var lmsConnected:Boolean;
		private var lessonStatus:String;
		private var success:Boolean;

		private var param:String;

		public function set fla(m:MovieClip):void
		{
			_fla = m;
		}
		public function get fla():MovieClip
		{
			return _fla;
		}
		public function scorm_conn(m:MovieClip)
		{
			fla = m;
			_fla.debug('Inizializzo lo scorm');
			this.initializeTracking();
		}



		/* =================================================================================
		
		SCORM code below!
		
		   ============================================================================== */



		/*
		setCourseToComplete
		
		Accepts: None
		Returns: None
		
		When the course is completed, we need to perform several tasks:
		  1. Send completion notice to LMS
		  2. Save progress
		  3. Disconnect from LMS (in some courses this may cause the course window to close)
		*/
		public function setCourseToComplete():void
		{


			ExternalInterface.call("fnc_alert","Settato completed");

			success = scorm.set("cmi.core.lesson_status","completed");
			scorm.save();

			scorm.disconnect();

		}


		public function saveCourseStatus(stato):void
		{
			param = "Setto lo stato a: " + stato;
			ExternalInterface.call("fnc_alert",param);

			scorm.set("cmi.suspend_data", stato);
			scorm.save();
		}



		public function initializeTracking():void
		{


			scorm = new SCORM();

			lmsConnected = scorm.connect();

			if (lmsConnected)
			{

				lessonStatus = scorm.get("cmi.core.lesson_status");

				_fla.connessione_scorm = true;

				param = "lessonStatus: " + lessonStatus;
				ExternalInterface.call("fnc_alert",param);

				if ((lessonStatus == "completed" || lessonStatus == "passed"))
				{

					_fla.stato_scorm = true;
					_fla.createPlayer(1);
					//trace("Risulta che la Lesson è completed");
					param = "risulta che la lesson è completed";
					ExternalInterface.call("fnc_alert",param);
					scorm.disconnect();
				}
				else
				{

					//_fla.createPlayer(0);

					_fla.debug("Imposto lessonStatus: incomplete");
					success = scorm.set("cmi.core.lesson_status","incomplete");
					scorm.save();

					_fla.suspend_data = scorm.get("cmi.suspend_data");

					if (_fla.suspend_data == "")
					{
						_fla.debug("Inizializzo suspend data a 0");

						success = scorm.set("cmi.suspend_data","0");
						scorm.save();
					}

					_fla.suspend_data = scorm.get("cmi.suspend_data");

					_fla.debug("sono arrivato a vedere il Jumper : " + _fla.suspend_data);

					//_fla.go_scorm(suspend_data);

					//_fla.progbar_mc.visible = false;
				}
			}
			else
			{
				_fla.debug("Could not connect to LMS");
				_fla.connessione_scorm = false;
				_fla.progbar_mc.visible = false;

				trace("Could not connect to LMS");
			}
		}
	}
}