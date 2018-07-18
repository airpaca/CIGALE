#!/bin/bash
#
# Publication des couches CIGALES depuis la Base de données PostgreSQL vers Geoserver.
# R. Souweine, AtmoSud, 2018
# 
# Lancer le script dans son répertoire racine ([...]/CIGALE/geoserver/)
# Le firewall de l'hote du Geoserver doit accépter les connexions depuis la machine qui execute le script.
#
# ATTENTION: Pour publier une couche PostGIS, l'utilisateur doit avoir les droits sur les tables suivantes:
# > GRANT ALL PRIVILEGES ON TABLE geometry_columns TO [user];
# > GRANT ALL PRIVILEGES ON TABLE geography_columns TO [user];

# Grab config data 
source ../config.sh

echo "Workspace ..."
curl -s -o /dev/null -w 'Dropping Ws %{http_code}\n' -X DELETE -u $gs_usr:$gs_pwd $gs_url/rest/workspaces/cigale?recurse=true
curl -s -o /dev/null -w 'Creating Ws %{http_code}\n' -X POST -u $gs_usr:$gs_pwd --header "Content-Type: text/xml" --data '<workspace><name>cigale</name><enabled>true</enabled></workspace>' $gs_url/rest/workspaces 

echo "Datastore ..."
# curl -s -o /dev/null -w 'Dropping Ds %{http_code}\n' -X DELETE -u $gs_usr:$gs_pwd $gs_url/rest/workspaces/cigale/datastores/cigale # Pour info mais pas besoin car on a supprimé le WS en mode récussif.
curl -s -o /dev/null -w 'Creating Ds %{http_code}\n' -X POST -u $gs_usr:$gs_pwd --header "Content-Type: text/xml" --data "<dataStore><name>cigale</name><connectionParameters><host>${ds_host}</host><port>${ds_port}</port><database>${ds_database}</database><user>${ds_usr}</user><passwd>${ds_pwd}</passwd><dbtype>postgis</dbtype><schema>cigale</schema><entry key=\"Expose primary keys\">true</entry></connectionParameters></dataStore>" $gs_url/rest/workspaces/cigale/datastores

echo "Tables ..."
# curl -s -o /dev/null -w 'Dropping table epci_poll %{http_code}\n' -X DELETE -u $gs_usr:$gs_pwd $gs_url/rest/workspaces/cigale/datastores/cigale/featuretypes/epci_poll?recurse=true # Pour info mais pas besoin car on a supprimé le WS en mode récussif.
curl -s -o /dev/null -w 'Publishing table epci_poll %{http_code}\n' -X POST -u $gs_usr:$gs_pwd --header "Content-Type: text/xml" --data "<featureType><name>epci_poll</name><nativeCRS>EPSG:4326</nativeCRS><srs>EPSG:4326</srs></featureType>" $gs_url/rest/workspaces/cigale/datastores/cigale/featuretypes
# curl -s -o /dev/null -w 'Dropping table comm_poll %{http_code}\n' -X DELETE -u $gs_usr:$gs_pwd $gs_url/rest/workspaces/cigale/datastores/cigale/featuretypes/comm_poll?recurse=true # Pour info mais pas besoin car on a supprimé le WS en mode récussif.
curl -s -o /dev/null -w 'Publishing table comm_poll %{http_code}\n' -X POST -u $gs_usr:$gs_pwd --header "Content-Type: text/xml" --data "<featureType><name>comm_poll</name><nativeCRS>EPSG:4326</nativeCRS><srs>EPSG:4326</srs></featureType>" $gs_url/rest/workspaces/cigale/datastores/cigale/featuretypes









