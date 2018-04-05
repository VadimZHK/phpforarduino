/******************************************************************************/
/***         Generated by IBExpert 2017.4.19.2 05.04.2018 12:00:04          ***/
/******************************************************************************/

SET SQL DIALECT 3;

SET NAMES WIN1251;

SET CLIENTLIB 'C:\bin\Fb25\bin\fbclient.dll';

CONNECT '127.0.0.1:C:\_WORK\Arduino\DB\base.fdb' USER 'SYSDBA' PASSWORD 'masterkey';



/******************************************************************************/
/***                                Domains                                 ***/
/******************************************************************************/

CREATE DOMAIN DM_CHAR30 AS
VARCHAR(30);

CREATE DOMAIN DM_CHAR40 AS
VARCHAR(40);

CREATE DOMAIN DM_INT AS
INTEGER;

CREATE DOMAIN DM_NUM AS
DECIMAL(15,2);

CREATE DOMAIN "DM_NUMBER(15,4)" AS
DOUBLE PRECISION;

CREATE DOMAIN DM_TIMESTAMP AS
TIMESTAMP;



/******************************************************************************/
/***                               Generators                               ***/
/******************************************************************************/

CREATE GENERATOR GN_TEMP;


/******************************************************************************/
/***                               Exceptions                               ***/
/******************************************************************************/

CREATE EXCEPTION ERR '';



/******************************************************************************/
/***                           Stored procedures                            ***/
/******************************************************************************/



SET TERM ^ ;

CREATE OR ALTER PROCEDURE PR$GETTEPM (
    I$DT1 TIMESTAMP,
    I$DT2 TIMESTAMP)
RETURNS (
    IDPLACE INTEGER,
    TEMPERATURE NUMERIC(15,2),
    DTVV TIMESTAMP)
AS
BEGIN
  SUSPEND;
END^






SET TERM ; ^



/******************************************************************************/
/***                                 Tables                                 ***/
/******************************************************************************/



CREATE GLOBAL TEMPORARY TABLE GTT_TEMPERATURES (
    ID       DM_INT NOT NULL,
    DT       DM_TIMESTAMP NOT NULL,
    TEMPER   DM_NUM,
    IDPLACE  DM_INT
) ON COMMIT DELETE ROWS;


CREATE TABLE PLACE (
    IDPLACE    DM_INT NOT NULL,
    PLACENAME  DM_CHAR30
);


CREATE TABLE TEMPERATURES (
    IDTEMP       DM_INT NOT NULL,
    IDPLACE      DM_INT,
    TEMPERATURE  DM_NUM,
    DTVV         DM_TIMESTAMP
);


CREATE TABLE USERS (
    IDUSER      DM_INT NOT NULL,
    USERNAME    DM_CHAR30,
    LOGON       DM_CHAR30,
    "PASSWORD"  DM_CHAR40,
    ACCESS      DM_INT
);




/******************************************************************************/
/***                              Primary keys                              ***/
/******************************************************************************/

ALTER TABLE PLACE ADD CONSTRAINT PK_PLACE PRIMARY KEY (IDPLACE);
ALTER TABLE TEMPERATURES ADD CONSTRAINT PK_TEMPERATURES PRIMARY KEY (IDTEMP);
ALTER TABLE USERS ADD CONSTRAINT PK_USERS PRIMARY KEY (IDUSER);


/******************************************************************************/
/***                              Foreign keys                              ***/
/******************************************************************************/

ALTER TABLE TEMPERATURES ADD CONSTRAINT FK_TEMPERATURES_PLACE FOREIGN KEY (IDPLACE) REFERENCES PLACE (IDPLACE) ON DELETE CASCADE;


/******************************************************************************/
/***                                Indices                                 ***/
/******************************************************************************/

CREATE INDEX GTT_TEMPERATURES_IDX1 ON GTT_TEMPERATURES (ID);
CREATE INDEX GTT_TEMPERATURES_IDX2 ON GTT_TEMPERATURES (DT, IDPLACE);
CREATE INDEX GTT_TEMPERATURES_IDX3 ON GTT_TEMPERATURES (IDPLACE, DT);
CREATE INDEX TEMPERATURES_DT_PLACE_IDX ON TEMPERATURES (DTVV, IDPLACE);
CREATE INDEX TEMPERATURES_PLACE_DT_IDX ON TEMPERATURES (IDPLACE, DTVV);


/******************************************************************************/
/***                                Triggers                                ***/
/******************************************************************************/



SET TERM ^ ;



/******************************************************************************/
/***                          Triggers for tables                           ***/
/******************************************************************************/



/* Trigger: TEMPERATURES_BI0 */
CREATE OR ALTER TRIGGER TEMPERATURES_BI0 FOR TEMPERATURES
ACTIVE BEFORE INSERT POSITION 0
AS
begin
  if (new.idtemp is null) then new.idtemp = gen_id(gn_temp,1);
  if (new.dtvv is null) then  new.dtvv = current_timestamp;
end
^

SET TERM ; ^



/******************************************************************************/
/***                           Stored procedures                            ***/
/******************************************************************************/



SET TERM ^ ;

CREATE OR ALTER PROCEDURE PR$GETTEPM (
    I$DT1 TIMESTAMP,
    I$DT2 TIMESTAMP)
RETURNS (
    IDPLACE INTEGER,
    TEMPERATURE NUMERIC(15,2),
    DTVV TIMESTAMP)
AS
declare variable V$PERIOD double precision;
declare variable V$DT timestamp;
declare variable V$I integer;
declare variable V$DELTA integer;
begin

  V$PERIOD = I$DT2 - I$DT1;
  V$I = 0;

  --0.0208 - 30 min  30.0 * 1.0 / 34.0 / 60.0
  --0.0417 - 1 hour   1.0 / 24.0
  --0.25 - 6 - hours  1.0 / 4.0
  --0.5   12 - hours  1.0 / 2.0
  --1 - day

  V$DT = I$DT1;
  if (V$PERIOD > 120) then
    V$DELTA = 6; --day
  else
  if (V$PERIOD > 1) then
    V$DELTA = 1; --day
  else
  if (V$PERIOD > 0.5) then
    V$DELTA = 30; --minutes
  else
    V$DELTA = 5; --minutes

  for select  IDPLACE, TEMPERATURE, DTVV
        from TEMPERATURES T
      where T.DTVV between :I$DT1 and :I$DT2
      order by T.DTVV
      into :IDPLACE, :TEMPERATURE, :DTVV
  do
  begin
    if (DTVV > V$DT) then
    begin
      if (V$DELTA in (1,6)) then
        V$DT = dateadd(hour, V$DELTA, V$DT);
      else
        V$DT = dateadd(minute, V$DELTA, V$DT);
      V$I = V$I + 1;
    end
    insert into GTT_TEMPERATURES (ID,
            DT,
            TEMPER, IDPLACE)
    values (:V$I,
            iif(:V$DELTA in (1,6), dateadd(hour, -:V$DELTA, :V$DT), dateadd(minute, -:V$DELTA, :V$DT)),
            :TEMPERATURE,:IDPLACE);
  end

     for select min(DT), min(TEMPER), -1
        from GTT_TEMPERATURES
        union
         select max(DT), max(TEMPER), -2
        from GTT_TEMPERATURES
      into :DTVV, :TEMPERATURE, :IDPLACE do
      suspend;

--  exception err 'Первый этап';

  for

      select DT, avg(TEMPER), IDPLACE
        from GTT_TEMPERATURES
      group by DT, IDPLACE
      into :DTVV, :TEMPERATURE, :IDPLACE
  do
    suspend;

end^



SET TERM ; ^



/******************************************************************************/
/***                          Fields descriptions                           ***/
/******************************************************************************/

DESCRIBE FIELD "PASSWORD" TABLE USERS
'MD5';
