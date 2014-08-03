/* 
MySQL stored procedure:
1. Union all sensor box data reading query results
2. Tranverse the columns and rows of the results table
3. Join tranversed table and another sensor geolocation table together 
to produce a real time sensor data reading table 
*/


DELIMITER //

DROP PROCEDURE IF EXISTS sensorReading; 
CREATE PROCEDURE sensorReading () 
BEGIN 

DROP TEMPORARY TABLE IF EXISTS temp;
-- default group concatation length is 1024, increase to longer length
-- to accommodate longer string as the below
SET SESSION group_concat_max_len = 88888;
SET @resultQuery = NULL;

SELECT
  CONCAT('create temporary table temp (select * from (',
  GROUP_CONCAT(
    DISTINCT
    CONCAT('(select 
id_wasp,
max(case when `sensor`=\'BAT\' then value end) BAT,
max(case when `sensor`=\'HUMA\' then value end) HUMA,
max(case when `sensor`=\'LUM\' then value end) LUM,
max(case when `sensor`=\'MCP\' then value end) MCP,
max(case when `sensor`=\'DUST\' then value end) DUST,
max(case when `sensor`=\'TCA\' then value end) TCA,
timestamp 
from 
(select 
id_wasp,sensor,value,timestamp 
from sensorParser 
where id_wasp = \'', name, '\' order by timestamp DESC limit 6) tb1)')
    SEPARATOR '\r\nUNION\r\n'),
    ') tb2 inner join (select name,x,y from waspmote) tb3 on tb2.id_wasp = tb3.name)'
    )
INTO
  @resultQuery
FROM
  waspmote;

-- Prepare statement from variable and execute it, deallocate it afterwards
PREPARE mystmt FROM @resultQuery;
EXECUTE mystmt;
DEALLOCATE PREPARE mystmt;

-- Output results
SELECT * FROM temp;

END;
//
-- Resume default delimiter ';'
delimiter ;


/*
-- Select all sensor box name and geolocation
select name,x,y from waspmote;

-- Select all sensor reading data for sensor box "A01"
select id_wasp,sensor,value,timestamp from sensorParser where id_wasp = 'A01' order by timestamp DESC limit 6 ;

-- Tranverse columns and rows of sensor reading data
select
id_wasp,
max(case when `sensor`='BAT' then value end) BAT,
max(case when `sensor`='HUMA' then value end) HUMA,
max(case when `sensor`='LUM' then value end) LUM,
max(case when `sensor`='MCP' then value end) MCP,
max(case when `sensor`='DUST' then value end) DUST,
max(case when `sensor`='TCA' then value end) TCA,
timestamp
from
(select
id_wasp,sensor,value,timestamp
from sensorParser
where id_wasp = 'A01'
order by timestamp DESC limit 6)
tbl;

DROP TEMPORARY TABLE IF EXISTS temp;

-- Show query result from variable
SELECT @resultQuery;

-- Execute the stored procedure
call sensorReading();
*/
