<html>
<body>
<h2>Berechnung Zentrum anhand mehrerer Koordinaten</h2>
<p>Zentrum auf Koordinaten</p>

<?php
class SetFocus 
{
  private $n = array();
  private $e = array();
  private $s = array();
  private $w = array();
  private $center = array();

  public function fillBoundList( $coordinates )
  {
    if
    ( 
          count( $this->n ) == 0 
      &&  count( $this->e ) == 0 
      &&  count( $this->s ) == 0 
      &&  count( $this->w ) == 0
    )
    {
      $this->n = $coordinates;
      $this->e = $coordinates;
      $this->s = $coordinates;
      $this->w = $coordinates;
      var_dump( __METHOD__, __LINE__, $this->n, $this->e, $this->s, $this->w );
      return;			
    }

    if( abs( $this->n[1] ) < abs( $coordinates[1] ) )
      $this->n = $coordinates;
    if( abs( $this->e[0] ) < abs( $coordinates[0] ) )
      $this->e = $coordinates;
    if( abs( $this->s[1] ) > abs( $coordinates[1] ) )
      $this->s = $coordinates;
    if( abs( $this->w[0] ) > abs( $coordinates[0] ) )
      $this->w = $coordinates;
    var_dump( __METHOD__, __LINE__, $this->n, $this->e, $this->s, $this->w );
  }

  public function centerCoor( )
  {
    $this->center[0] = ( $this->n[0] * 1 + $this->e[0] * 1 + $this->s[0] * 1 + $this->w[0] * 1 ) / 4;				// X coordinates
    $this->center[1] = ( $this->n[1] * 1 + $this->e[1] * 1 + $this->s[1] * 1 + $this->w[1] * 1 ) / 4;				// Y coordinates
    return $this->center;
  }
}

$oxMapCenter = new SetFocus( );

$coordinates = array( '9.6175669,48.9659301', '9.555442525,48.933978799', '9.538,48.89', '9.6075669,48.9459301' );
for( $a = count( $coordinates ); $a--; )
  $oxMapCenter->fillBoundList( explode( ',' , $coordinates[$a] ), $a );

var_dump( __METHOD__, __LINE__, $oxMapCenter->centerCoor( ) );

?>
  
</body>
</html>