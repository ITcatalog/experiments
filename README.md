# ITcat Test Application

SPARQL Queries auf Basis von [sparqllib](http://graphite.ecs.soton.ac.uk/sparqllib/)




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
