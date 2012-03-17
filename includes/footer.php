<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
<?php
## Notify
if (isset($notify)) {
	foreach ($notify as $note) {
		echo '$.jGrowl("'.$note.'", { 
			header: \'Notify\',
			theme: \'notify\'
		});';
	}
}
## Warnings
if (isset($warning)) {
	foreach ($warning as $note) {
		echo '$.jGrowl("'.$note.'", { 
			header: \'Warning\',
			theme: \'warning\',
			sticky: true
		});';
	}
}
## Errors
if (isset($error)) {
	foreach ($error as $note) {
		echo '$.jGrowl("'.$note.'", { 
			header: \'Error\',
			theme: \'error\',
			sticky: true
		});';
	}
}
?>
	});
</script>