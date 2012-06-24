<?php
$series = array(
		'cat1' => array(
				'icon' => array( './img/test1.png', 14, 14, 0, 0),
				'data' => array(
						'point1' => array(
								'coors' => array( 9.6175669, 48.9659301 ),
								'desc'  => 'Punkt1<br />Neue Box und der Inhalt geht &uuml;ber mehrere Zeilen'
								),
						'point2' => array(
								'coors' => array( 9.555442525, 48.933978799 ),
								'desc'  => 'Punkt2<br />Neue Box und der Inhalt geht &uuml;ber mehrere Zeilen'
								)
						)
				),
		'cat2' => array(
				'icon' => array( './img/test1.png', 14, 14, 0, 0),
				'data' => array(
						'point1' => array(
								'coors' => array( 9.6175669, 48.9659301 ),
								'desc'  => 'Punkt1<br />Neue Box und der Inhalt geht &uuml;ber mehrere Zeilen'
								),
						'point2' => array(
								'coors' => array( 9.555442525, 48.933978799 ),
								'desc'  => 'Punkt2<br />Neue Box und der Inhalt geht &uuml;ber mehrere Zeilen'
								)
						)
				)
		);

var_dump( json_encode( $series ) ); 
exit;
?> 

<?php
$object['cat1'] = array( "foo" => "bar", 12 => true ) ;

$encoded_object = json_encode( $object, JSON_FORCE_OBJECT );
var_dump( $encoded_object );

$series = array("name"=>"N51",
                "data"=>array(1024,
                              array("y"=>2048,
                                    "events"=>array("mouseOver"=>'function(){$reporting.html(\'description of value\');}')
                                   ),
                              4096)
               );
var_dump( json_encode($series) ); 
?> 
