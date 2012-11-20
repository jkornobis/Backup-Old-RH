<?php
require_once "../Artichow/BarPlot.class.php";
$ax1= $_GET['ax1'];
$ax2= $_GET['ax2'];
$ax3= $_GET['ax3'];
$ax4= $_GET['ax4'];
$ax5= $_GET['ax5'];
$ax6= $_GET['ax6'];

$graph = new Graph(819, 400);
$graph->setAntiAliasing(TRUE);

$x = array(
    $ax1, $ax2, $ax3, $ax4, $ax5, $ax6
);

$plot = new BarPlot($x);

$plot->setSpace(0, 0, 0, 0);
$plot->setPadding(45, 4, 10, 30);

/*$plot->title->set("Zoé and friends");
$plot->title->setFont(new TuffyBold(11));
$plot->title->border->show();
$plot->title->setBackgroundColor(new Color(255, 255, 255, 25));
$plot->title->setPadding(4, 4, 4, 4);
$plot->title->move(-20, 25);*/

$plot->yAxis->title->set("Pourcents");
$plot->yAxis->title->setFont(new TuffyBold(17));
$plot->yAxis->title->move(-10, 0);
$plot->yAxis->setTitleAlignment(Label::TOP);

/*$plot->xAxis->title->set("Axe des X");
$plot->xAxis->title->setFont(new TuffyBold(10));
$plot->xAxis->setTitleAlignment(Label::RIGHT);*/

$plot->setBackgroundGradient(
    new LinearGradient(
	new Color(255, 255, 153, 75),
        new Color(200, 200, 255, 75), 
        0
    )
);

$plot->barBorder->setColor(new Color(100, 100, 100, 25));

$plot->setBarGradient(
    new LinearGradient(
        new Color(50, 150, 255, 0),
        new Color(255, 255, 153, 30),
        0
    )
);

$y = array(
    'Axe 1',
    'Axe 2',
    'Axe 3',
    'Axe 4',
    'Axe 5',
    'Axe 6'
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
