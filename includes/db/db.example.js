//Example file for db.js
export const mysql = require('mysql');

export const connection = mysql.createConnection({
    host: 'localhost',
    user: 'your_username',
    password: 'your_password',
    database: 'your_database'
});