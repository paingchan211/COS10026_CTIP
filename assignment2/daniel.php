<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Malaysian Sign Language</title>
    <meta charset="utf-8">
    <meta name="description" content="daniel">
    <meta name="keywords" content="daniel">
    <meta name="author" content="Daniel Sie, Zwe Htet Zaw, Paing Chan, Sherlyn Kok, Michael Wong">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
      rel="icon"
      href="images/love-you-gesture-svgrepo-com.svg"
      type="images/svg"
    >
    <link rel="stylesheet" href="styles/style.css" >
    <link
      rel="stylesheet"
      type="text/css"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    >
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <header>
      <?php include "header.php" ?>
    </header>

    <article id="profile-page">
      <!--Picture and credentials section-->
      <section>
        <div id="title">Student Profile Page</div>

        <br />

        <!--padding in between sections-->
        <div id="pic">
          <span id="cpt">
            <span
              ><p>
                Hi! My name is Daniel. I'm currently enrolled in Swinburne Sarawak and is learning Computer Sciece.
              </p></span
            >
          </span>
          <img src="images/daniel.jpeg" alt="Picture of Daniel" id="pfp" />
        </div>

        <div id="credentials">
          <p id="name">Daniel Sie Chuan Feng</p>
          <p id="student_id">102788159</p>
          <p id="course">Bachelor of Computer Science</p>
        </div>
      </section>

      <br />

      <!--Information section-->
      <section>
        <div id="table">
          <table id="info_t">
            <tr class="odd daniel">
              <th>Age</th>
              <td>19</td>
            </tr>

            <tr class="even">
              <th>Gender</th>
              <td>Male</td>
            </tr>

            <tr class="odd daniel">
              <th>Nationality</th>
              <td>Malaysian</td>
            </tr>

            <tr class="even">
              <th>Description of Hometown</th>
              <td>
                I was born and raised in Kuching, Sarawak.
              </td>
            </tr>

            <tr class="odd daniel">
              <th>A Great Achievement</th>
              <td>
                Finished a booking app using Python and also a functionable website during my foundation time. As the finished product were better than I had imagined, I was really satisfied and feel proud about myself.
              </td>
            </tr>

            <tr class="even">
              <th>Some things I enjoy!</th>
              <td>
                I enjoy playing video games and hanging out with friends when I have free time.
              </td>
            </tr>
          </table>
        </div>
      </section>

      <br /><br />

      <!--Email section-->
      <section>
        <div id="email">
          <button id="prof-button">
            <a href="mailto:102788159@students.swinburne.edu.my">
              Contact Me
            </a>
          </button>
        </div>
      </section>
    </article>

    <?php include 'footer.php'?>
  </body>
</html>
