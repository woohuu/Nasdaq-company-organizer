# Nasdaq-company-organizer
Design a database to store company lists and create a web application for users to interact with the database

Project Summary
This project aims to create an organizer system to display information of over 3000 public companies listed on the Nasdaq stock exchange and also one hundred components of the Nasdaq 100 index. A database will be designed to store all company information on a server and a web interface will be built to help facilitate customers across the Internet to search and inquire company records from the database and for database owners to insert, update and delete company records.

Description
1. Creation of database
This project used the Postgresql database system. All SQL queries and psql commands that were used to create and load data into the project database are stored in the db/company.sql file. Original data was kept in the txt files under /db folder. Table schemas were designed according to BCNF (Boyce-Codd normal form) rules to reduce data redundancy and maintain data integrity and also make future table alteration easier.

2. PHP web application
The web application wrriten in PHP serves as a user interface to interact with the company database. On index.php page, search function was provided to use a variety of search conditions to retrieve company records from the database. You can search by company symbol or company keyword. This will return one or a few companies matching keyword in their company names and is considered as a targeted search. A blind search by constraining either stock price or market cap or sector or a combination of two or three of them are all supported. Search results have been tested to satisfy any randomly-specified conditions which proves the back-end logical layer and database query method.

On the admin.php page, presumably for database administrator, one can search for a company by company symbol and search result will be populated into the following form for updating. Once can also start from scratch and enter new company info into all fields of the form and insert the new company record into the database. Delete function is also provided after the symbol field is filled either by manual input or populated data from search result, then instead of clicking the "Save" button, you click "Delete" button. 

Large amount of time was devoted to develop, test and debug the logical layer that handles form submission. These pages are located in the /include folder. All possible situations have been conceived and tested to make sure every form input is dealt with the correct response. By correct response, it means either error message instructing user of an invalid input or an output of search results that satisfy all search conditions, or an action that the user want (e.g., insert/update/delete a record)

Web App Deployment
This project web app has been deployed to heroku. Please visit https://nasdaq-company-organizer.herokuapp.com/.
The web app will be rendered with CSS and JavaScript and more functions will be added in the near future.

