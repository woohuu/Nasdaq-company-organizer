CREATE TABLE company (
  CompanyID SERIAL PRIMARY KEY,
  Symbol VARCHAR(10) NOT NULL,
  CompanyName VARCHAR(100) NOT NULL,
  LastSale DOUBLE PRECISION,
  MarketCap VARCHAR(15),
  Sector VARCHAR(40),
  Industry VARCHAR(100),
  SummaryLink VARCHAR(100) NOT NULL
);

/* use COPY command under psql to load data from companylist.txt into table company
because of no root user privillege */
yl576=> \copy company(symbol, companyname, lastsale, marketcap, sector, industry, summarylink) from '~/companylist.txt';

