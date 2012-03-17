<script type="text/javascript" src="<?php echo STATIK ?>js/mootools/lib/motools1.2dev.js"></script>
<script type="text/javascript" src="<?php echo STATIK ?>js/fancyuploader/Swiff.Base.js"></script>
<script type="text/javascript" src="<?php echo STATIK ?>js/fancyuploader/Swiff.Uploader.js"></script>
<script type="text/javascript" src="<?php echo STATIK ?>js/fancyuploader/FancyUpload.js"></script>


<script type="text/javascript">
	//<![CDATA[

	/**
	 * Sample Data
	 */

	window.addEvent('load', function()
	{
		/**
		 * We take the first input with this class we can find ...
		 */
		var input = $('photoupload-filedata-1');

		/**
		 * Simple and easy
		 * 
		 * swf: the path to the swf
		 * container: the object is embedded in this container (default: document.body)
		 * 
		 * NOTE: container is only used for the first uploader u create, all others depend
		 * on the same swf in that container, so the container option for the other uploaders
		 * will be ignored.
		 * 
		 */
		var uplooad = new FancyUpload(input, {
			swf: '<?php echo STATIK ?>js/fancyuploader/Swiff.Uploader.swf',
			queueList: 'photoupload-queue',
			container: $E('h1')
		});

		/**
		 * We create the clear-queue link on-demand, since we don't know if the user has flash/javascript.
		 * 
		 * You can also create the complete xhtml structure thats needed for the queue here, to be sure
		 * that its only in the document when the user has flash enabled.
		 */
		$('photoupload-status').adopt(new Element('a', {
			href: 'javascript:void(null);',
			events: {
				click: uplooad.clearList.bind(uplooad, [false])
			}
		}).setHTML('Clear Completed'));


		/**
		 * Second, this one uses the already injected swf ambed to upload files.
		 */
		var uplooad2 = new FancyUpload($('photoupload2-filedata-1'), {
			swf: '<?php echo STATIK ?>js/fancyuploader/Swiff.Uploader.swf',
			queueList: 'photoupload-queue-2'
		});
	});

	//]]>
</script>

	<style type="text/css">
	
	.photosactu {
		width:550px;
		margin:auto;
		text-align:center;
	}

		/**
		 * Thats the basic css needed for the upload bars
		 */

		.photoupload-queue
		{
			list-style:				none;
		}
		.photoupload-queue li
		{
			background:				url("<?php echo STATIK ?>illus/picture_go.png") no-repeat 0 5px;
			padding:				5px 0 5px 22px;
		}

		.photoupload-queue .queue-file
		{
			font-weight:			bold;
		}

		.photoupload-queue .queue-size
		{
			color:					#aaa;
			margin-left:			1em;
			font-size:				0.9em;
		}

		.photoupload-queue .queue-loader
		{
			position:				relative;
			margin:					3px 15px;
			font-size:				0.9em;
			background-color:		#ddd;
			color:					#fff;
			border:					1px inset #ddd;
		}
		.photoupload-queue .queue-subloader
		{
			text-align:				center;
			position:				absolute;
			background-color:		#81B466;
			height:					100%;
			width:					0%;
			left:					0;
			top:					0;
		}

		.photoupload-queue .input-delete
		{
			width:					16px;
			height:					16px;
			background:				url("<?php echo STATIK ?>illus/delete.png") no-repeat 0 0;
			text-decoration:		none;
			border:					none;
			float:					right;
		}
	</style>