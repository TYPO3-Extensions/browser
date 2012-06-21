<html><body>
	<h2>Berechnung Zentrum anhand mehrerer Koordinaten</h2>
	<p>Zentrum auf Koordinaten</p>
  
	<?php
		class SetFocus {
			private $n = array();
			private $e = array();
			private $s = array();
			private $w = array();
			private $center = array();

			public function fillBoundList($coordinates){
				if(count($this->n) == 0 && count($this->e) == 0 && count($this->s) == 0 && count($this->w) == 0){
					$this->n = $coordinates;
					$this->e = $coordinates;
					$this->s = $coordinates;
					$this->w = $coordinates;
					return;			
				}

				if(abs($this->n[1]) < abs($coordinates[1]))
					$this->n = $coordinates;
				if(abs($this->e[0]) < abs($coordinates[0]))
					$this->e = $coordinates;
				if(abs($this->s[1]) > abs($coordinates[1]))
					$this->s = $coordinates;
				if(abs($this->w[0]) > abs($coordinates[0]))
					$this->w = $coordinates;
			}

			public function centerCoor(){
				$this->center[0] = ($this->n[0] * 1 + $this->e[0] * 1 + $this->s[0] * 1 + $this->w[0] * 1) / 4;				// X coordinates
				$this->center[1] = ($this->n[1] * 1 + $this->e[1] * 1 + $this->s[1] * 1 + $this->w[1] * 1) / 4;				// Y coordinates
				return $this->center;
			}

		}

	$oxMapCenter = new SetFocus();

	$coordinates = array('9.5382032,48.89', '9.6075669,48.9659301','9.5382032,48.9899851','9.538,48.89');
	for($a = count($coordinates); $a--;)
		$oxMapCenter->fillBoundList(explode(',',$coordinates[$a]),$a);

	var_dump($oxMapCenter->centerCoor());
	
	?>
  
  <script>
	var coordinates = ['9.5382032,48.89', '9.6075669,48.9659301','9.5382032,48.9899851','9.538,48.89']				//	Beispiel Koordinatenliste
  
  	var n = e = s = w = [];																							//	Datenarray für die Ausdehnung

  	var getFocus = function(coordinates){																			//	Prüft Koordinatenpaar X,Y  Type: Array
		if(!(n.length && e.length && s.length && w.length)){														//	Wenn das Datenarray der Ausdehnung wird es mit ersten Werten befüllt.
			n = coordinates;																						//	Das garantiert, dass auch ein einzelner Punkt in der Mitte liegt
			e = coordinates;
			s = coordinates;
			w = coordinates;
			return;																									//	und bricht in diesem Fall die Bearbeitung ab
		}
		if(Math.abs(n[1]) < Math.abs(coordinates[1]))																//	Prüft Breitengrad nach nördlicher Ausdehnung				Y-Wert
			n = coordinates;																						//		nördlichster Wert hat höchsten Breitengradwert
		if(Math.abs(e[0]) < Math.abs(coordinates[0]))																//	Prüft Längengrad in östlicher Ausdehnung					X-Wert
			e = coordinates;																						//		östlichster Wert hat höchsten Längenwert
		if(Math.abs(s[1]) > Math.abs(coordinates[1]))																//	Südliche Ausdehnung											Y-Wert
			s = coordinates;																						//		südlicher Breitengrad besitzt kleinste Ausdehnung
		if(Math.abs(w[0]) > Math.abs(coordinates[0]))																//	Westliche Ausdehnung
			w = coordinates;																						//		westlicher Längengrad kleinste Ausdehnung				X-Wert
			
  	}
	for(var t = coordinates.length;t--;){
		getFocus(coordinates[t].split(','));																		//	Führt Beispiel mit Beispielkoordinaten aus
/*
	  	console.debug('N -> '+n);
	  	console.debug('E -> '+e);
	  	console.debug('S -> '+s);
	  	console.debug('W -> '+w);
	  	console.debug('----'+t+'----');
*/
	}
																													//	Berechnung nach Vektormathematik
																													//	http://de.wikipedia.org/wiki/Viereck#Schwerpunkt
																													
	var centerX = (n[0] * 1 + e[0] * 1 + s[0] * 1 + w[0] * 1) / 4;													//	X-center Summe der Ausdehnungen geteilt durch 4
	var centerY = (n[1] * 1 + e[1] * 1 + s[1] * 1 + w[1] * 1) / 4;													//	Y-center Summe der Ausdehnungen geteilt durch 4


  </script>
  
  
</body></html>