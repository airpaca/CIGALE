/**
CIGALE - Création d'une table par polluant pour plus de rapidité
*/

do $$ 
declare

	_id_polluant integer;
	_nom_abrege_polluant text;

begin 

	for _id_polluant in (SELECT DISTINCT id_polluant from total.bilan_comm_v4_secten1 limit 1) loop

		select replace(nom_abrege_polluant, '.', '') into _nom_abrege_polluant from commun.tpk_polluants where id_polluant = _id_polluant;

		raise notice 'Création de la table total.bilan_comm_v4_secten1_%', _nom_abrege_polluant;

		execute '
			drop table if exists total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || ';
		
			create table total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || ' as 
			select * 
			from total.bilan_comm_v4_secten1
			where id_polluant = ' || _id_polluant || ';
		
			ALTER TABLE total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '
			  ADD CONSTRAINT "pk.total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '" PRIMARY KEY(id_polluant, an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab);

			CREATE INDEX "idx.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '.an"
			  ON total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (an);

			CREATE INDEX "idx.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '.id_polluant"
			  ON total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (id_polluant);

			CREATE INDEX "idx.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '.id_polluant.an.ss.id_secten1.code_cat"
			  ON total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (id_polluant, an, ss, id_secten1 COLLATE pg_catalog."default", code_cat_energie);
			ALTER TABLE total.bilan_comm_v4_secten1_' || _nom_abrege_polluant || ' CLUSTER ON "idx.bilan_comm_v4_secten1_' || _nom_abrege_polluant || '.id_polluant.an.ss.id_secten1.code_cat_energie";
			';



	end loop;

end $$ language plpgsql;


-- TODO: FAIR LES VACUUM, ... EN BASH
vacuum ANALYZE total.bilan_comm_v4_secten1_conso; 			
vacuum FREEZE total.bilan_comm_v4_secten1_conso; 			

vacuum ANALYZE total.bilan_comm_v4_secten1_ch4co2e; 			
vacuum FREEZE total.bilan_comm_v4_secten1_ch4co2e; 

vacuum ANALYZE total.bilan_comm_v4_secten1_co; 			
vacuum FREEZE total.bilan_comm_v4_secten1_co; 

vacuum ANALYZE total.bilan_comm_v4_secten1_co2; 			
vacuum FREEZE total.bilan_comm_v4_secten1_co2; 

vacuum ANALYZE total.bilan_comm_v4_secten1_covnm; 			
vacuum FREEZE total.bilan_comm_v4_secten1_covnm; 

vacuum ANALYZE total.bilan_comm_v4_secten1_n2oco2e; 			
vacuum FREEZE total.bilan_comm_v4_secten1_n2oco2e; 

vacuum ANALYZE total.bilan_comm_v4_secten1_nh3; 			
vacuum FREEZE total.bilan_comm_v4_secten1_nh3; 

vacuum ANALYZE total.bilan_comm_v4_secten1_nox; 			
vacuum FREEZE total.bilan_comm_v4_secten1_nox; 

vacuum ANALYZE total.bilan_comm_v4_secten1_pm10; 			
vacuum FREEZE total.bilan_comm_v4_secten1_pm10; 

vacuum ANALYZE total.bilan_comm_v4_secten1_pm25; 			
vacuum FREEZE total.bilan_comm_v4_secten1_pm25; 

vacuum ANALYZE total.bilan_comm_v4_secten1_prg1003ges; 			
vacuum FREEZE total.bilan_comm_v4_secten1_prg1003ges; 

vacuum ANALYZE total.bilan_comm_v4_secten1_so2; 			
vacuum FREEZE total.bilan_comm_v4_secten1_so2; 






