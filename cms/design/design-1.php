
<?php 
	echo $output;
?>


<?php
global $anzahlOffenerDIV, $templateIsClosed, $templateIsClosed;


if(!$templateIsClosed) {
	for($i=1; $i<=$anzahlOffenerDIV; $i++) echo '					</div>
';

	echo '
				</section>
';
	$templateIsClosed=1;
}

