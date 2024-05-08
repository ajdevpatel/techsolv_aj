"# techsolv_aj" 
=> Import techsolve_db.sql database from file 

=> Navigation to Directory: Change directory to the project folder.

=> Start buid-in server

=> when open browser http://localhost/techsolve/index.php

=> Main Features

- Contact form: Users can fill out the contact form with their name, email, subject and message.
- Email send : After successfully Submission ,email is sent to the website owner with the message details.
- Form validation : The form fields are validated to ensure that required fields are filled and that the email format is valid.
- Confirmation message : Users receive a confirmation message after successfully submitting the form.

=> New Features
- Captcha : Captcha field is use to determine if an online user is really a human and not a bot.
- Resubmit : If user already submited the form than user will be submit form after 2 hour.

=> File structure
- 'db_confing.php' = Connection of database and also change owner email for sending mail.
- 'index.php' = The main HTML file containing the contact form and he PHP script to handle form submission and email sending.