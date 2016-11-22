package 
{

	import flash.display.*;
	import fl.video.*;
	import flash.events.*;
	import flash.text.TextField;
	import flash.text.TextFieldAutoSize;
	import fl.data.DataProvider;
	import flash.system.*;
	import flash.utils.Timer;
	import flash.net.*;
	
	import flash.external.ExternalInterface;

	public class Main extends MovieClip
	{
		// Definizione parametri:  //////////////////////////
		
		
		public var path_contenuti:String ="";
		public var dominio:String ="";
		public var id_elemento:String="";
		public var lingua:String="";
		public var tview:int=0;
		
		
		public const cartella_data:String = "";//nel formato "data/" comprensivo di slash
		public const prefisso_slide:String = "Slide";// Le slide vanno nominate senza lo zero: Slide1, Slide2, Slide3 ecc.
		public const img_normal_basepath:String = cartella_data + "images/normal/" + prefisso_slide;
		public const img_large_basepath:String = cartella_data + "images/large/" + prefisso_slide;

		private var package_path:String = cartella_data + "contenuto.zip"; //file compresso comprendente tutto il pacchetto.
		private var pdf_path:String = cartella_data + "slide.pdf"; //pdf di tutte le slide

		public var videoname:String = "video.flv";

		public var url_track:String=  "index.php?option=com_gglms&task=updateTrack";

		/////////////////////////////////////////////////////

		private var providertile:DataProvider;
		private var providerlist:DataProvider;

		public var programmi_array:Array;//repository per i programmi in palinsesto
		
		private var media:LoadingMedia;




		public var cuePoints_array:Array;
		public var pathVideo_array:Array;


		private var id:int = 0;

		private var cue:Object;


		public var infos_array:Array;
		public var thumbs_array:Array;

		public var curr_lyt:int;
		public var stato:int = 0;

		public var suspend_data:String = "0";

		public var param:String;

		public var player:FLVPlayback = new FLVPlayback();

		public function Main()
		{
			Security.loadPolicyFile("http://ggallery.it/crossdomain.xml");
			
			path_contenuti= loaderInfo.parameters['path'] != undefined ? loaderInfo.parameters['path']:"http://www.ausindfad.it/home/contenuti_fad/01_generalelavoratori/00_videoguida/";
			dominio= loaderInfo.parameters['dominio'] != undefined ? loaderInfo.parameters['dominio']:"0";
			id_elemento = loaderInfo.parameters['id_elemento'] != undefined ? loaderInfo.parameters['id_elemento']:"0";
			stato = loaderInfo.parameters['stato'] != undefined ? loaderInfo.parameters['stato']:0;
			lingua = loaderInfo.parameters['lingua'] != undefined ? loaderInfo.parameters['lingua']:"IT";
			tview = loaderInfo.parameters['tview'] != undefined ? loaderInfo.parameters['tview']:0;
			
			debug("path contenuti: " +  path_contenuti);
			
			layout(3);

			stage.frameRate = 45;

			programmi_array = new Array  ;
			
			debug("..do create Player");
			//createPlayer();
			debug("..do contenuto");
			contenuto();
	
	
	
			lente_btn.addEventListener(MouseEvent.CLICK,lenteHandler);
			lenteall_btn.addEventListener(MouseEvent.CLICK,lenteallHandler);
			lentestd_btn.addEventListener(MouseEvent.CLICK,lentestdHandler);
			//download_btn.addEventListener(MouseEvent.CLICK,downloadHandler);
			pdf_btn.addEventListener(MouseEvent.CLICK,pdfHandler);
			html5.addEventListener(MouseEvent.CLICK,html5Handler);


		}

		public function createPlayer()
		{
			
			
			
			player.addEventListener(VideoEvent.BUFFERING_STATE_ENTERED, onPlayerNotBuffered);
			player.addEventListener(VideoProgressEvent.PROGRESS, progressHandler);
			player.addEventListener(VideoEvent.PLAYING_STATE_ENTERED, onPlayerBuffered);
			player.addEventListener(VideoEvent.READY, readyHandler);

			
			
			
			player.width = 450;
			player.height = 284;
			player.x = 0;
			player.y= 0;
			player.scaleMode= "maintainAspectRatio";
			player.bufferTime = 0;

			if (stato)
			{
				player.skin = "http://www.ausindfad.it/home/"+"components/com_gglms/flash/SkinUnderPlayStopSeekFullVol.swf";
			}
			else
			{
				player.skin = "http://www.ausindfad.it/home/"+"components/com_gglms/flash/SkinUnderPlayFullscreen.swf";
			}
			player.autoPlay = false;
			player.skinBackgroundColor = 0x003366;
			
			
			
			debug("player source: " +player.source);
			
			
			video.addChild(player);
			
			

			
			
			//start_view();
			

		}
		
		
		function onPlayerNotBuffered(event:VideoEvent)
		{
			debug("##Not buffered");
		}
		
		function progressHandler(event:VideoProgressEvent):void
		{
		   debug("##buffering...");
		}
		
		function onPlayerBuffered(event:VideoEvent)
		{
			
		   debug("##Ready");
		}
		
		function readyHandler(event:VideoEvent):void
		{
		   debug("##Playing");
		}




		
		
		
		
		
		public function start_view():void{
			debug("--> Start view con tview= "+tview + " e stato = "+ stato);
			var dt:Number = 0;
			dt= (player.totalTime/tview)-(player.bytesTotal/player.bytesLoaded);
				
			var myTimer:Timer = new Timer(100,5);
			
		if (!stato)
			{
					var curr_jumper_id:int=0;
					for (var n:int=0; n < cuePoints_array.length; n++)
					{
						if(tview > cuePoints_array[n].time)
							{
							debug("tview: "+tview + " --> cuePoints_array[i]: "+ cuePoints_array[n].time + " Indice corrente: "+n); 
							curr_jumper_id=n;
							}
					}

				video.visible=false;			
				if (dt > 0)
				{
					
					debug("imposto slide e jumper all'indice: "+ curr_jumper_id);
					noncaricato.visible=false;
					
					video.visible=true;			
					viewImage(curr_jumper_id);
					seekToCuePoint(tview-1);
					tile_mc.selectedIndex = curr_jumper_id;
					//list_mc.selectedIndex = curr_jumper_id;
					player.play();
					debug("Mi posiziono al secondo " + tview);
				}
				else
				{	
					debug("Aspetto qualche secondo e riprovo");
					noncaricato.play();

					myTimer.addEventListener(TimerEvent.TIMER_COMPLETE, riprova);
					myTimer.start();
				}
			}
			else
			{
				if (dt > 0)
				{
				debug("Inizia a vedere il corso dall'inizio");
				player.play();
				}
				else
				{	
					debug("Aspetto qualche secondo e riprovo");
					noncaricato.play();
					myTimer.addEventListener(TimerEvent.TIMER_COMPLETE, riprova);
					myTimer.start();
				}
			}
		}
		
		
		public function riprova(event:TimerEvent):void
		{
			debug("Riprovo a fare Play");
			start_view();
		}
		

		public function contenuto():void
		{
			cuePoints_array = new Array  ;
			pathVideo_array = new Array  ;
			infos_array = new Array  ;
			thumbs_array = new Array  ;

			loading_mc.visible = true;
			tile_mc.visible = false;
			//list_mc.visible = false;
			//player.visible = false;
			layout(3);

			debug("inizio a caricare l'xml");
			media = new LoadingMedia(this);

			lab_mc.text = '';

			lab_mc.selectable = false;



		}
		
		public function addCuePoints():void
		{
			for (var i:int=0; i < cuePoints_array.length; i++)
			{
				player.addASCuePoint(cuePoints_array[i]);
			}
			addingListener();

		}

		private function addingListener():void
		{
			player.addEventListener(MetadataEvent.CUE_POINT,go);
			player.addEventListener(VideoEvent.COMPLETE, setElementoComplete);
		}
		
		private function setElementoComplete(eventObject:VideoEvent):void
		{
			
			
			
			debug ("Track Complete" + dominio + url_track);

			stato=1;

			var request:URLRequest = new URLRequest(dominio + url_track);
			var variables:URLVariables=new URLVariables();
			
			variables.secondi = player.totalTime;
			variables.stato = 1;
			variables.id_elemento= id_elemento;


			debug("Variabili "+variables);
			

			request.method = URLRequestMethod.POST;
			request.data = variables;
			var loader:URLLoader=new URLLoader();
			loader.dataFormat = URLLoaderDataFormat.VARIABLES;
			try
			{
				loader.load(request);
			}
			catch (error:Error)
			{
				debug('Unable to load requested document.');
			}


		}

		



		private function go(m:MetadataEvent):void
		{
			debug("m.parameters"+m.info.parameters);
			tile_mc.selectedIndex = m.info.parameters;
			//list_mc.selectedIndex = m.info.parameters;
			slide.source = path_contenuti+img_normal_basepath + m.info.parameters + ".jpg";	
			viewImage(m.info.parameters);
			debug("i Secondi attuali sono: "+ cuePoints_array[m.info.parameters].time);
			if(!stato){
				update_track(cuePoints_array[m.info.parameters].time, 0);
			}
			
		}

		public function update_track(secondi: int, stato:int):void
		{
			debug ("Update Track " + dominio + url_track);

			var request:URLRequest = new URLRequest(dominio + url_track);
			var variables:URLVariables=new URLVariables();
			
			variables.secondi = secondi;
			variables.stato = stato;
			variables.id_elemento= id_elemento;


			debug("Variabili "+variables);
			

			request.method = URLRequestMethod.POST;
			request.data = variables;
			var loader:URLLoader=new URLLoader();
			loader.dataFormat = URLLoaderDataFormat.VARIABLES;
			try
			{
				loader.load(request);
			}
			catch (error:Error)
			{
				debug('Unable to load requested document.');
			}
		}

		public function newBox(img:String, titolo:String, time:int):void
		{
			debug('sto creando il box: i valori sono: '+ img +"," + titolo + "," + time);
			
			var _box:box=new box();
			var _durata:String;
			addChild(_box);
			
			_durata = convertitoreSecondi(time);
			
			_box.box_image.source = img;
			_box.box_titolo.text = titolo;
			_box.box_time.text = _durata;
			//_box.box_testi.htmlText = <b>" + titolo + "</b><br><i>" + descrizione + "</i>";
		
		
		
			programmi_array.push(_box);
			this.removeChild(_box);
			

		}

		function convertitoreSecondi(sec:Number):String
		{
			var h:Number = Math.floor(sec / 3600);
			var m:Number=Math.floor((sec%3600)/60);
			var s:Number=Math.floor((sec%3600)%60);
			return (h==0?"":(h<10?"0"+h.toString()+":":h.toString()+":"))+(m<10?"0"+m.toString():m.toString())+" : "+(s<10?"0"+s.toString():s.toString())+"";
			//    return(h==0?"":(h<10?"0"+h.toString()+":":h.toString()+":"))+(m<10?"0"+m.toString():m.toString())+" min";
		}

		public function populateJumperList():void
		{
			providertile = new DataProvider  ;
			providerlist = new DataProvider  ;

			for (var i:int=0; i < cuePoints_array.length; i++)
			{

				var minutes:Number = 0;
				var seconds:Number = 0;
				var labeltime:String = "";


				minutes = Math.floor(cuePoints_array[i].time.toString() / 60);
				seconds = Math.floor(cuePoints_array[i].time.toString()) % 60;
				labeltime= ((minutes < 10) ? "0" + minutes : minutes) + ":" + ((seconds < 10) ? "0" + seconds : seconds);

				//providerlist.addItem({label:labeltime +" - "+cuePoints_array[i].name.toString(), source:thumbs_array[i],data:i,time:cuePoints_array[i].time});
				
				
				/////modifica jumper con icona /// 
							
				
				newBox(path_contenuti+img_normal_basepath+[i+1]+".jpg",cuePoints_array[i].name.toString(),cuePoints_array[i].time);
				
				
					providertile.addItem({
					source:programmi_array[i], 
					data:i, 
					time: cuePoints_array[i].time
					});
				
				
				
				//// fine modifica jumper con icona ////
				
				
				
			}

			for (i=0; i < cuePoints_array.length; i++)
			{
				//providertile.addItem({label:"",source:thumbs_array[i],data:i,time:cuePoints_array[i].time});
			}
			tile_mc.allowMultipleSelection = false;
			tile_mc.columnWidth = 450;
			tile_mc.columnCount = 1;
			tile_mc.rowCount = 6;
			tile_mc.rowHeight = 60;
			tile_mc.dataProvider = providertile;
			tile_mc.visible = true;
			tile_mc.alpha = 1;
			tile_mc.selectedItem = providertile.getItemAt(0);
			tile_mc.addEventListener(Event.CHANGE,selezione);

			//list_mc.allowMultipleSelection = false;
//			list_mc.rowHeight = 20;
//			list_mc.dataProvider = providerlist;
//			list_mc.visible = true;
//			list_mc.selectedItem = providerlist.getItemAt(0);
//			list_mc.addEventListener(Event.CHANGE,selezione);


			loading_mc.visible = false;
			


			viewImage(0);
			layout(0);
			
			player.source = path_contenuti + videoname;
			createPlayer();


		}
		private function selezione(event:Event):void
		{

			if (stato)
			{
				debug("selezione, stato = 1");
				var dt:Number = 0;
				dt= (player.totalTime/event.target.selectedItem.time)-(player.bytesTotal/player.bytesLoaded);
				
				debug("dt" + dt);
				//if (dt > 0)
				{
					debug("dt>0" + "time" +  event.target.selectedItem.time ); 
					viewImage(event.target.selectedItem.data);
					seekToCuePoint(event.target.selectedItem.time);
					tile_mc.selectedIndex = event.target.selectedItem.data;
					debug("seekToCuePoint" + event.target.selectedItem.time);
					//list_mc.selectedIndex = event.target.selectedItem.data;
					//player.play();
				}
				//else
				{
					noncaricato.play();
					
					

					
					
				}
			}
			else
			{
				debug("devi prima vedere tutto il contenuto per poter navigare coi jumper");
				nojumper.play();
			}
		}






		public function viewImage(n:int):void
		{
			n = n + 1;

			if (curr_lyt != 2)
			{
				slide_grande.visible = false;
			}
			slide.source =path_contenuti + img_normal_basepath + n + ".jpg";

			debug(slide.source);
			
			slide_grande.slide.source = path_contenuti+ img_large_basepath + n + ".jpg";
		}

		private function seekToCuePoint(cueName):void
		{
			player.seekSeconds(cueName);
		}
		
		public function lenteHandler(evt:MouseEvent):void
		{
			layout(1);
		}
		public function lenteallHandler(evt:MouseEvent):void
		{
			layout(2);
		}

		public function lentestdHandler(evt:MouseEvent):void
		{
			layout(0);
		}



		public function downloadHandler(event:MouseEvent):void
		{
			var request:URLRequest=new URLRequest();
			request.url = package_path;
			navigateToURL(request,"self");
		}

		public function pdfHandler(event:MouseEvent):void
		{
			var request:URLRequest=new URLRequest();
			request.url = pdf_path;
			navigateToURL(request,"self");
		}

		public function html5Handler(event:MouseEvent):void
		{
			var request:URLRequest=new URLRequest();
			request.url = dominio+"index.php?option=com_gglms&view=elemento&tpl=html5&id="+id_elemento;
			
			//http://www.ausindfad.it/home/index.php?option=com_gglms&view=elemento&id=16&tpl=html5
			
			navigateToURL(request,"_self");
		}




		public function layout(nuovo_curr_lyt:int):void
		{
			switch (nuovo_curr_lyt)
			{
				case 0 ://vista standard tutto visibile

					curr_lyt = 0;
					tile_mc.visible = true;
					//list_mc.visible = true;
					tile_mc.x = 17;
					tile_mc.y = 341;
					//list_mc.x = 35.8;
					//list_mc.y = 365.8;


					slide.visible = true;
					slide_grande.visible = false;

					//progbar_mc.x = 107.6;


					break;

				case 1 ://zoom solo questa slide
					curr_lyt = 1;

					//slide.visible = false;
					slide_grande.visible = true;

					break;

				case 2 ://slide zoomata tutto il tempo


					curr_lyt = 2;

					trace(curr_lyt);

					//slide.visible = false;
					slide_grande.visible = true;


					break;

				case 3 ://fase di loading

					curr_lyt = 3;

					slide_grande.visible = false;

					loading_mc.visible = true;
					tile_mc.visible = false;
					//list_mc.visible = false;
					//player.visible = false;

					break;

			}
		}

		public function TimerView()
		{
			var myTimer:Timer = new Timer(1000,10);
			myTimer.addEventListener(TimerEvent.TIMER_COMPLETE, timerHandler);
			myTimer.start();
		}

		public function timerHandler(event:TimerEvent):void
		{
			trace("TEMPO SCADUTO");
			layout(5);

		}

		public function debug(msg)
		{
			ExternalInterface.call("fnc_alert",msg);
		}


	}
}