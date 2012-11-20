<?php
require_once('../Artichow/BarPlot.class.php');

$ax1= $_GET['ax1'];
$ax2= $_GET['ax2'];
$ax3= $_GET['ax3'];
$ax4= $_GET['ax4'];
$ax5= $_GET['ax5'];
$ax6= $_GET['ax6'];
$ax7= $_GET['ax7'];
$ax8= $_GET['ax8'];
$ax9= $_GET['ax9'];
$ax10= $_GET['ax10'];
$ax11= $_GET['ax11'];
$ax12= $_GET['ax12'];

$graph = new Graph(900, 300);
$graph->setAntiAliasing(TRUE);

$x = array(
    $ax1, $ax2, $ax3, $ax4, $ax5, $ax6, $ax7, $ax8, $ax9, $ax10, $ax11, $ax12
);

$plot = new BarPlot($x);

$plot->setSpace(0, 0, 10, 0);
$plot->setPadding(25, 10, 10, 25);

$plot->xAxis->title->set("");
$plot->xAxis->title->setFont(new TuffyBold(12));
$plot->xAxis->setTitleAlignment(Label::RIGHT);

$plot->setBackgroundGradient(
    new LinearGradient(
				new Color(255, 255, 100, 100),
        new Color(50, 150, 255, 90), 
        0
    )
);

$plot->barBorder->setColor(new Color(50, 50, 50, 0));

$plot->setBarGradient(
    new LinearGradient(
        new Color(50, 150, 255, 0),
        new Color(100, 100, 255, 100),
        0
    )
);

$y = array(
    'Janvier',
    'Février',
		'Mars',
		'Avril',
		'Mai',
		'Juin',
		'Juillet',
		'Aout',
		'Septembre',
    'Octobre',
    'Novembre',
    'Décembre'
);

$plot->xAxis->setLabelText($y);
$plot->xAxis->label->setFont(new TuffyBold(12));


/*
$graph->shadow->setSize(2);
$graph->shadow->setPosition(Shadow::LEFT_TOP);
$graph->shadow->smooth(TRUE);
$graph->shadow->setColor(new Color(200, 200, 200));
*/
$graph->add($plot);
$graph->draw();
?>
