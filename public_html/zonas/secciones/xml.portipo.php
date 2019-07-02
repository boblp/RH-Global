	<chart_data>
		<row>
			<null/>
			<string>Aptos</string>
			<string>Aptos CR</string>
			<string>No Aptos</string>
		</row>
		<row>
			<string></string>
			<number><?php echo $vars["ca"]; ?></number>
			<number><?php echo $vars["cr"]; ?></number>
			<number><?php echo $vars["na"]; ?></number>
		</row>
	</chart_data>
	<chart_transition type='dissolve' delay='.5' duration='0.75' order='category' />
	<chart_type>3d pie</chart_type>
	<chart_value color='000000' alpha='65' font='arial' bold='true' size='10' position='inside' prefix='' suffix='' decimals='0' separator='' as_percentage='true' />
	<legend_transition type='slide_down' delay='0' duration='3' />
	
	<series_color>
		<color>648521</color>
		<color>BFBB21</color>
		<color>DB401E</color>
	</series_color>
	<series_explode>
		<number>0</number>
		<number>0</number>
		<number>50</number>
	</series_explode>
	<?php 
	if(($vars["ca"]==0 && $vars["cr"]==0 && $vars["na"]==0) || ($vars["ca"]<0 && $vars["cr"]<0 && $vars["na"]<0)) {
		$nodata=true;
	}
	?>