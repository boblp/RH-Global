	<chart_data>
		<row>
			<?php echo implode("\n",$series); ?>
		</row>
		<row>
			<?php echo implode("\n",$sap); ?>
		</row>
		<row>
			<?php echo implode("\n",$sar); ?>
		</row>
		<row>
			<?php echo implode("\n",$sna); ?>
		</row>
	</chart_data>
	<chart_transition type='scale' delay='0' duration='0.40' order='category' />
	<chart_type>stacked column</chart_type>
	<chart_value alpha='90' font='arial' bold='true' size='10' position='right' prefix='' suffix='' decimals='0' separator='' as_percentage='false' />
	<legend_transition type='slide_down' delay='0' duration='3' />
	<axis_category orientation="vertical_up" />
	<series_color>
		<color>648521</color>
		<color>BFBB21</color>
		<color>DB401E</color>
	</series_color>
