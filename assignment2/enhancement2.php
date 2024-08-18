<!DOCTYPE html>
<html lang="en">

<head>
  <title>Malaysian Sign Language</title>
  <meta charset="utf-8" />
  <meta name="description" content="" />
  <meta name="keywords" content="" />
  <meta name="author" content="Daniel Sie, Zwe Htet Zaw, Paing Chan" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="icon" href="images/love-you-gesture-svgrepo-com.svg" type="images/svg" />
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="styles/style.css" />
</head>

<body>
  <header>
    <?php include "header.php" ?>
  </header>

  <br />

  <article id="enhancements_page">
    <section>
      <!--introduction section, only affects the top of the page-->

      <div id="top">
        <p id="title">Enhancements</p>
        <br />
        <p id="desc">
          This page is where we present all the enhancements throughout the
          project
        </p>
      </div>
    </section>
    <section>
      <div class="sect">
        <figure>
          <img src="images/login.gif" alt="Login GIF" title="Login GIF" class="enh1" />
        </figure>

        <p class="enhc_name">Login/Logout</p>
        <p class="enhc_desc">
          Allow users to login and logout after registering. It reads through the database and find the user id entered.
          Then, it will decrypt the password and check whether the user entered the correct password. If it was correct,
          it will let the user login. When the user is logged in, it will change the login button and make it so when
          clicked, it will show the option to logout.
          <br />
          <br />
          Pages applied: login.php
          <br />
          Source:
          -
        </p>
      </div>

      <br />

    </section>
    <section>
      <div class="sect">
        <figure>
          <img src="images/user-management-module.gif" alt="User Management Module GIF"
            title="User Management Module GIF" class="enh1" />
        </figure>

        <p class="enhc_name">User Management Module</p>
        <p class="enhc_desc">
          The enhancement goes beyond the basic requirements by adding security, access control, and comprehensive user
          management.
          Implementation involves modifying the database, adding authentication logic, managing sessions, and creating
          user management interfaces with CRUD functionalities.
          This provides a secure and flexible system for managing users and public enquiries, ensuring only authorized
          personnel can access sensitive data. <br />
          <br />
          Pages applied: admin/index.php
          <br />
          Source:
          https://www.youtube.com/watch?v=72U5Af8KUpA&ab_channel=StepbyStep
        </p>
      </div>

      <div class="sect">
        <figure>
          <img src="images/view-page-search-bar.gif" alt="View Pages Search Bar GIF" title="View Pages Search Bar GIF"
            id="enh2" />
        </figure>

        <p class="enhc_name">Search Bar</p>
        <p class="enhc_desc">
          Allows the admin to search for specific information easily. This feature utilizes advanced PHP and MySQL
          functions, which is not included in the syllabus.
          The developer must take note of important fields in the table being displayed and write their code accordingly
          to ensure functionality.
          <br />
          <br />
          Pages applied: viewenquiries.php, viewvolunteers.php
          <br />
          Source:
          https://youtu.be/yp5pYIg4WHc?feature=shared
        </p>
      </div>

      <br />

    </section>
    <section>
      <div class="sect">
        <figure>
          <img src="images/user-sorting.gif" alt="User Sorting GIF" title="User Sorting GIF" class="enh1" />
        </figure>

        <p class="enhc_name">Sorting Users</p>
        <p class="enhc_desc">
          Add sorting controls in the HTML to allow the user to select sorting criteria and order.
          Update the SQL query to include the sorting parameters, ensuring they are validated to prevent SQL injection.
          Handle sorting parameters in the PHP code to dynamically sort the user data as per the selected criteria.
          This enhancement allows administrators to easily manage and view users in a sorted and organized manner,
          providing a better user management experience. <br />
          <br />
          Pages applied: admin/index.php
          <br />
          Source:
          https://www.mysqltutorial.org/mysql-basics/mysql-order-by/#:~:text=Use%20the%20ORDER%20BY%20clause,the%20FROM%20and%20SELECT%20clauses.
        </p>
      </div>

      <br />

    </section>
  </article>
</body>

</html>