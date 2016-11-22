package 
{

	import flash.display.Loader;
	import flash.display.MovieClip;
	import flash.net.URLLoader;
	import flash.net.URLVariables;
	import flash.net.URLRequest;
	import flash.net.URLRequestMethod;
	import flash.net.URLLoaderDataFormat;
	import flash.text.TextField;
	import flash.display.SimpleButton;
	import flash.events.*;
	import fl.controls.*;
	import flash.net.URLRequest;
	import flash.display.BitmapData;
	import flash.display.Bitmap;
	import flash.system.*;
	import fl.containers.UILoader;
	import flash.xml.*;


	public class LoadingMedia
	{
		private var _fla:MovieClip;
		private var cue:Object;
		private var formati:Object;

		private var loader:Loader;
		private var _path:String;
		private var _idPal:int;
		private var counter:int = 0;
		private var counter2:int = 0;


		private var clip:MovieClip;
		private var clip2:MovieClip;
		private var cliplarge:MovieClip;



		private var id:int;

		private var bitmap_data:BitmapData;


		public function set fla(m:MovieClip):void
		{
			_fla = m;
		}
		public function get fla():MovieClip
		{
			return _fla;
		}
		public function LoadingMedia(m:MovieClip)
		{
			fla = m;
			this.loadXML();
		}

		private function loadXML():void
		{
			var loader:URLLoader=new URLLoader();
			loader.addEventListener(Event.COMPLETE,completeHandler);

			_fla.debug("carico l'xml da  cue point" + _fla.path_contenuti+'cue_points.xml');
			
			var request:URLRequest = new URLRequest(_fla.path_contenuti+'cue_points.xml');
			try
			{
				loader.load(request);
			}
			catch (error:Error)
			{
				trace('Impossibile caricare il documento.');
			}
		}
		private function completeHandler(event:Event):void
		{
			var loader:URLLoader = URLLoader(event.target);
			var result:XML = new XML(loader.data);
			var myXML:XMLDocument=new XMLDocument();
			myXML.ignoreWhite = true;
			myXML.parseXML(result.toXMLString());
			var node:XMLNode = myXML.firstChild;
			var firstLength:int = int(node.childNodes.length);
			for (var i:int=0; i < firstLength; i++)
			{
				cue=new Object();
				var secondLength:int = int(node.childNodes[i].childNodes.length);
				for (var j:int=0; j < secondLength; j++)
				{
					if (j == 0)
					{
						cue.time = Number(node.childNodes[i].childNodes[j].firstChild.nodeValue);
					}
					if (j == 1)
					{
						cue.name = node.childNodes[i].childNodes[j].firstChild.nodeValue;
					}
				}
				trace(cue.time);

				cue.type = "actionscript";
				cue.parameters = i;

				_fla.cuePoints_array.push(cue);
				_fla.debug("carico il cue point" + i + "con tempo" + cue.time);

			}
			//_fla.createPlayer();
			_fla.addCuePoints();

			loadImages();


		}

		/*
		// Questa funzione serve escuslivamente a caricare le anteprime dei jumper, non piu a caricare le slide
		*/

		public function loadImages():void
		{
			if (counter <= _fla.cuePoints_array.length - 1)
			{
				_fla.loading_mc.loading.text = "caricamento slide " + [counter + 1] + "/" + _fla.cuePoints_array.length;
				_fla.loading_mc.progressBar.setProgress([counter+1],_fla.cuePoints_array.length);

				//if (counter == 0)
//				{
//					_fla.viewImage(counter);
//				}
				caricatore(_fla.path_contenuti+_fla.img_normal_basepath +[_fla.cuePoints_array[counter].parameters+1]+".jpg");
			}
			else
			{
				_fla.populateJumperList();
			}
			counter++;

		}

		private function caricatore(_path):void
		{
			var request:URLRequest = new URLRequest(_path);
			loader=new Loader();
			initListeners(loader.contentLoaderInfo);
			loader.load(request);

		}
		private function initListeners(dispatcher:IEventDispatcher):void
		{
			dispatcher.addEventListener(Event.COMPLETE,caricato);
		}

		private function caricato(event:Event):void
		{

			bitmap_data = new BitmapData(loader.width,loader.height,true,0xFFFFFFFF);
			bitmap_data.draw(loader);

			var thumb:Bitmap = new Bitmap(bitmap_data);
			var b:BitmapData = bitmap_data.clone();
			
			clip=new MovieClip();
			clip.addChild(thumb);
			
			_fla.thumbs_array.push(clip);


			//removeListeners(loader.contentLoaderInfo);

			loadImages();
		}
	}
}