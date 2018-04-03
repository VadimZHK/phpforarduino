/******************************************************************************/
/***          Generated by IBExpert 2017.4.19.2 03.04.2018 9:15:44          ***/
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

CREATE DOMAIN "DM_NUMBER(15,4)" AS
DOUBLE PRECISION;

CREATE DOMAIN DM_TIMESTAMP AS
TIMESTAMP;



/******************************************************************************/
/***                               Generators                               ***/
/******************************************************************************/

CREATE GENERATOR GN_TEMP;


/******************************************************************************/
/***                                 Tables                                 ***/
/******************************************************************************/



CREATE TABLE PLACE (
    IDPLACE    DM_INT NOT NULL,
    PLACENAME  DM_CHAR30
);


CREATE TABLE TEMPERATURE (
    IDTEMP       DM_INT NOT NULL,
    IDPLACE      DM_INT,
    TEMPERATURE  "DM_NUMBER(15,4)",
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
ALTER TABLE TEMPERATURE ADD CONSTRAINT PK_TEMPERATURE PRIMARY KEY (IDTEMP);
ALTER TABLE USERS ADD CONSTRAINT PK_USERS PRIMARY KEY (IDUSER);


/******************************************************************************/
/***                              Foreign keys                              ***/
/******************************************************************************/

ALTER TABLE TEMPERATURE ADD CONSTRAINT FK_TEMPERATURE_PLACE FOREIGN KEY (IDPLACE) REFERENCES PLACE (IDPLACE) ON DELETE CASCADE;


/******************************************************************************/
/***                                Indices                                 ***/
/******************************************************************************/

CREATE INDEX "TEMPERATURE_DT-PLACE" ON TEMPERATURE (DTVV, IDPLACE);
CREATE INDEX TEMPERATURE_PLACE_DT ON TEMPERATURE (IDPLACE, DTVV);


/******************************************************************************/
/***                                Triggers                                ***/
/******************************************************************************/



SET TERM ^ ;



/******************************************************************************/
/***                          Triggers for tables                           ***/
/******************************************************************************/



/* Trigger: TEMPERATURE_BI0 */
CREATE OR ALTER TRIGGER TEMPERATURE_BI0 FOR TEMPERATURE
ACTIVE BEFORE INSERT POSITION 0
AS
begin
  if (new.idtemp is null) then new.idtemp = gen_id(gn_temp,1);
  if (new.dtvv is null) then  new.dtvv = current_timestamp;
end
^

SET TERM ; ^



/******************************************************************************/
/***                          Fields descriptions                           ***/
/******************************************************************************/

DESCRIBE FIELD "PASSWORD" TABLE USERS
'MD5';
