# ITcat Test Application

Demo IT-Service-Catalog auf Basis eines annotieren HTML-Dokuments.

## Technologien 
- Apache2 Web Server
- Apache Jena Fuseki Service (Triple Store)
- [sparqllib](http://graphite.ecs.soton.ac.uk/sparqllib/) (PHP Library für Sparql Queries)
- [RDF Translator](http://rdf-translator.appspot.com/) (RDF Parser)




## Änderungen gegenüber [ITcat_v020](https://github.com/ITcatalog/ITcat/blob/eb2095a1415503db473e17a36e992edda301d09d/itcat_v020.ttl)

- itcat:ServiceKategorie (*Beispiel Lehrportal hasITService Moodle*) // besser: ServiceCategorie
- itcat:hasITService (owl:inverseOf hasServiceCategorie)
- [schema:isRelatedTo](http://schema.org/isRelatedTo) (*Beispiel Moodle isRelatedTo IDM*)
- [usdlagreement:refersTo](http://www.linked-usdl.org/ns/usdl-agreement#refersTo) (*Beispiel Moodle refersTo Computer*, Computer a SupportedDevice, SupportedDevice a ServiceProperty*)

Data Properties "Service"
- [dcterms:subject](http://purl.org/dc/terms/subject)
- [dcterms:description](http://purl.org/dc/terms/description)
- [schema:url](https://schema.org/url)




Diskussion:
- Verwendung von skos:prefLabel anstelle von rdfs:label sinnvoll?
