<?php
require_once( "lib/sparqllib.php" );

$db = sparql_connect( "http://fbwsvcdev.fh-brandenburg.de:8080/fuseki/testDataSet/query" );
if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

//# bei Namespaces notwendig
$db->ns( "rdf","http://www.w3.org/1999/02/22-rdf-syntax-ns#" );
$db->ns( "rdfs","http://www.w3.org/2000/01/rdf-schema#" );
$db->ns( "itcat","http://th-brandenburg.de/ns/itcat#" );
$db->ns( "schema","https://schema.org#" );
$db->ns( "skos","http://www.w3.org/2004/02/skos/core#" );
$db->ns( "usdlagreement","http://www.linked-usdl.org/ns/usdl-agreement#");

$sparql = "
SELECT *
WHERE {
?cat a itcat:ServiceKategorie;
skos:prefLabel ?catLabel.
}
";

$result = $db->query( $sparql );
if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }


echo '<h1>Kategorien</h1>';

while( $row = $result->fetch_array() ){
	echo '<li><a href="?cat='.urlencode($row['cat']).'">'.$row['catLabel'].'</a></li>';
}

echo '<hr>';

if(isset($_GET['cat'])){

	$category = urldecode($_GET['cat']);
	echo '<h1>Dienste</h1>';
	$sparql = '
	SELECT *
	WHERE {
		<'.$category.'> a itcat:ServiceKategorie;
		itcat:hasITService ?service.
		?service skos:prefLabel ?label.
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }


	while( $row = $result->fetch_array() ){
		echo '<li><a href="?cat='.urlencode($_GET['cat']).'&service='.urlencode($row['service']).'">'.$row['label'].'</a></li>';
	}

echo '<hr>';

}

if(isset($_GET['service'])){
	$service = urldecode($_GET['service']);

	$sparql = '
	SELECT *
	WHERE {
		<'.$service.'> ?property ?value
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

	$fields = $result->field_array( $result );

	echo '<h1>Service-Steckbrief</h1>';
	print "<table class='example_table'>";
	print "<tr>";
	foreach( $fields as $field )
	{
		print "<th>$field</th>";
	}
	print "</tr>";
	while( $row = $result->fetch_array() ){
		print "<tr>";
		foreach( $fields as $field ){

			$value = $row[$field];
			if(filter_var($value, FILTER_VALIDATE_URL)){
				if (strpos($value, '#') !== FALSE){

					$x = explode( '#', $value );
					$label = $x[1];
					if (strpos($value, 'http://th-brandenburg.de/ns/itcat#') !== FALSE){
						$x = explode( 'http://th-brandenburg.de/ns/itcat#', $value );
						$label = $x[1];
					}
					else{
						#$label = $value;
					}

					echo '<td><a href="?general='.urlencode($value).'">'.$label.'</a></td>';
				}
				else{
					echo '<td><a href="'.$value.'" target="_blank">'.$value.'</a></td>';
				}
			}
			else{
				print "<td>$value</td>";
			}
		}
		print "</tr>";
	}
	print "</table>";

	echo '<hr>';
}

if(isset($_GET['general'])){
	$general = urldecode($_GET['general']);

	$sparql = '
	SELECT *
	WHERE {
		<'.$general.'> ?property ?value
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

	$fields = $result->field_array( $result );


	echo '<h1>Steckbrief</h1>';
	print "<table class='example_table'>";
	print "<tr>";
	foreach( $fields as $field )
	{
		print "<th>$field</th>";
	}
	print "</tr>";
	while( $row = $result->fetch_array() )
	{
		print "<tr>";
		foreach( $fields as $field ){
			$value = $row[$field];

			if(filter_var($value, FILTER_VALIDATE_URL)){
				if (strpos($value, '#') !== FALSE){

					if (strpos($value, 'http://th-brandenburg.de/ns/itcat#') !== FALSE){
						$x = explode( 'http://th-brandenburg.de/ns/itcat#', $value );
						$label = $x[1];
					}
					else{
						$label = $value;
					}

					echo '<td><a href="?general='.urlencode($value).'">'.$label.'</a></td>';
				}
				else{
					echo '<td><a href="'.$value.'" target="_blank">'.$value.'</a></td>';
				}
			}
			else{
				print "<td>$value</td>";
			}
		}
		print "</tr>";
	}
	print "</table>";

	echo '<hr>';
}


echo '<h1>Fragen</h1>';

echo '<ul>';

	echo '<li><a href="?question=provider">Welche Provider gibt es?</a></li>';
	echo '<li><a href="?question=active">Wie ist der Status der Dienste?</a></li>';
	echo '<li>Welche Dienste stehen mir zur Verfügung als <a href="?group=Studenten">Student</a>, <a href="?group=Mitarbeiter">Mitarbeiter</a>, <a href="?group=Extern">Extern</a></li>';
	echo '<li><a href="?device=Smartphones">Welche Dienste können mit einem Smartphone genutzt werden?</a></li>';


echo '</ul>';
echo '<hr>';

if(isset($_GET['question'])){
	switch ($_GET['question']){

		case 'provider':
			$sparql = '
			SELECT *
			WHERE {
			?provider a <http://th-brandenburg.de/ns/itcat#Provider>;
			skos:prefLabel ?provLabel.
			}
			';

			$result = $db->query( $sparql );
			if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

			echo '<ul>';
			while( $row = $result->fetch_array() ){
				echo '<li><a href="?question='.$_GET['question'].'&provider='.urlencode($row['provider']).'">'.$row['provLabel'].'</a></li>';
			}
			echo '</ul>';

			if(isset($_GET['provider'])){
				$provider = urldecode($_GET['provider']);
				echo '<h1>Dienste</h1>';
				$sparql = '
				SELECT *
				WHERE {
					?service schema:provider <'.$provider.'>;
					skos:prefLabel ?label.
				}
				';
				$result = $db->query( $sparql );
				if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
				while( $row = $result->fetch_array() ){
					echo '<li><a href="?service='.urlencode($row['service']).'">'.$row['label'].'</a></li>';
				}

			echo '<hr>';

			}
			break;


		case 'active':

			$sparql = '
			SELECT *
			WHERE {
			  ?service a schema:Service.
			  ?service itcat:Status ?status.
			  ?service skos:prefLabel ?label.
			}
			';

			$result = $db->query( $sparql );
			if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

			echo '<ul>';
			while( $row = $result->fetch_array() ){
				if($row['status'] == 'in Betrieb'){
					echo '<li><a href="?question='.$_GET['question'].'&service='.urlencode($row['service']).'"  style="color:green;">'.$row['label'].'</a></li>';
				}
				else{
					echo '<li><a href="?question='.$_GET['question'].'&service='.urlencode($row['service']).'" style="color:orange;">'.$row['label'].'</a></li>';
				}

			}
			echo '</ul>';

			if(isset($_GET['provider'])){
				$provider = urldecode($_GET['provider']);
				echo '<h1>Dienste</h1>';
				$sparql = '
				SELECT *
				WHERE {
					?service schema:provider <'.$provider.'>;
					skos:prefLabel ?label.
				}
				';
				$result = $db->query( $sparql );
				if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }
				while( $row = $result->fetch_array() ){
					echo '<li><a href="?service='.urlencode($row['service']).'">'.$row['label'].'</a></li>';
				}

				echo '<hr>';

			}
			break;
	}
}


if(isset($_GET['group'])){

	$group = urldecode($_GET['group']);
	echo '<h1>Dienste</h1>';
	$sparql = '
	SELECT *
	WHERE {
		?service a schema:Service;
  		schema:user <http://th-brandenburg.de/ns/itcat#'.$group.'>.
		?service skos:prefLabel ?label.
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }


	while( $row = $result->fetch_array() ){
		echo '<li><a href="?service='.urlencode($row['service']).'">'.$row['label'].'</a></li>';
	}

	echo '<hr>';

}


if(isset($_GET['device'])){

	$device = urldecode($_GET['device']);
	echo '<h1>Dienste</h1>';
	$sparql = '
	SELECT *
	WHERE {
		?service a schema:Service;
  		usdlagreement:refersTo <http://th-brandenburg.de/ns/itcat#'.$device.'>.
		?service skos:prefLabel ?label.
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }


	while( $row = $result->fetch_array() ){
		echo '<li><a href="?service='.urlencode($row['service']).'">'.$row['label'].'</a></li>';
	}

	echo '<hr>';

}


echo '<form method="GET" action="">';
	echo '<input type="search" name="search" value="" placeholder="Search">';
	echo '<input type="submit" value="Search">';
echo '</form>';


if(isset($_GET['search'])){
	$searchTerm = $_GET['search'];

	echo '<h1>Ergebnis</h1>';

	$sparql = '
	SELECT *
	WHERE {
  		?s skos:prefLabel ?label.
  		FILTER regex(lcase(str(?label)), lcase("'.$searchTerm.'")) .
	}
	';

	$result = $db->query( $sparql );
	if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

	echo "<p>Anzahl: ".$result->num_rows( $result )." results.</p>";

	while( $row = $result->fetch_array() ){
		echo '<li><a href="?general='.urlencode($row['s']).'">'.$row['label'].'</a></li>';
	}

	echo '<hr>';


}

if(isset($sparql)){
	echo '<hr style="margin-top:150px;"><div style=""><pre>
		'. htmlspecialchars($sparql).'
	</pre></div>';
}