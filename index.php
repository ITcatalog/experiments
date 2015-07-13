<?php
require_once( "lib/sparqllib.php" );

$db = sparql_connect( "http://fbwsvcdev.fh-brandenburg.de:8080/fuseki/testDataSet/query" );
if( !$db ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

//# bei Namespaces notwendig
$db->ns( "rdf","http://www.w3.org/1999/02/22-rdf-syntax-ns#" );
$db->ns( "rdfs","http://www.w3.org/2000/01/rdf-schema#" );
$db->ns( "itcat","http://th-brandenburg.de/ns/itcat#" );


$sparql = "
SELECT (?itServiceLabel AS ?Name)
WHERE {
?itservice	a		itcat:ITService;
	itcat:serviceCategorie		itcat:Lehrportal;
	rdfs:label					?itServiceLabel.
}
";

$result = $db->query( $sparql );
if( !$result ) { print $db->errno() . ": " . $db->error(). "\n"; exit; }

$fields = $result->field_array( $result );

print "<p>Number of rows: ".$result->num_rows( $result )." results.</p>";
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
	foreach( $fields as $field )
	{
		print "<td>$row[$field]</td>";
	}
	print "</tr>";
}
print "</table>";
