/* create table company */
CREATE TABLE company (
  companyid SERIAL PRIMARY KEY,
  symbol VARCHAR(10) NOT NULL,
  companyname VARCHAR(100) NOT NULL,
  lastsale DOUBLE PRECISION,
  marketcap VARCHAR(15),
  sector VARCHAR(40),
  industry VARCHAR(100),
  summarylink VARCHAR(100) NOT NULL
);

/* use COPY command under psql to load data from companylist.txt into table company because of no root user privillege */
yl576=> \copy company(symbol, companyname, lastsale, marketcap, sector, industry, summarylink) from '~/companylist.txt';

/* drop column industry due to similarity with sector and it have very ambiguous classfication.
It's supposed to a sub-catagory of sector but there are many instances same industry being associated with two sectors */
ALTER TABLE company DROP COLUMN industry;

/* add a unique constraint to table company (column symbol)
and the symbol column will be referenced by table nasdaq100component */
ALTER TABLE company ADD CONSTRAINT symbol_unique UNIQUE (symbol);

/* create table nasdaq100 which represents 100 companies included in the nasdaq 100 index (the well-known nasdaq index) */

CREATE TABLE nasdaq100 (
componentid SERIAL PRIMARY KEY,
symbol VARCHAR(10) REFERENCES company (symbol),
weight DOUBLE PRECISION NOT NULL
);

/* use COPY command under psql to load data from nasdaq100components.txt */
yl576=> \copy nasdaq100(symbol, weight) from '~/nasdaq100components.txt';


/* create table sector and insert values into the table */
CREATE TABLE sector (
sectorid SERIAL PRIMARY KEY,
sectorname VARCHAR(40) NOT NULL
);

INSERT INTO sector (sectorname)
VALUES ('Basic Industries');

INSERT INTO sector (sectorname)
VALUES ('Capital Goods');

INSERT INTO sector (sectorname)
VALUES ('Consumer Durables');

INSERT INTO sector (sectorname)
VALUES ('Consumer Non-Durables');

INSERT INTO sector (sectorname)
VALUES ('Consumer Services');

INSERT INTO sector (sectorname)
VALUES ('Energy');

INSERT INTO sector (sectorname)
VALUES ('Finance');

INSERT INTO sector (sectorname)
VALUES ('Health Care');

INSERT INTO sector (sectorname)
VALUES ('Miscellaneous');

INSERT INTO sector (sectorname)
VALUES ('Public Utilities');

INSERT INTO sector (sectorname)
VALUES ('Technology');

INSERT INTO sector (sectorname)
VALUES ('Transportation');

/* create table internet_performer which represents a small selection of companies in the large cap internet industry */
CREATE TABLE internet_performer (
symbol VARCHAR(10) REFERENCES company (symbol),
one_year_return DOUBLE PRECISION
);

/* load data from large_cap_internet_performance.txt */
yl576=> \copy internet_performer from '~/large_cap_internet_performance.txt';

/* table normalization, replace sector column in table company with sectorid by inner join with sector table
Note: there are many ETFs which are not companies being included in the original data and 
they don't have a value in the sector field (being empty).
By doing inner join it also helps discard these rows. 
Should have removed them before loading data into table company  */
CREATE TABLE company_new AS
SELECT company.companyid, company.symbol, company.companyname, 
company.lastsale, company.marketcap, sector.sectorid, company.summarylink
FROM company
INNER JOIN sector ON
company.sector = sector.sectorname
ORDER BY company.companyid;


