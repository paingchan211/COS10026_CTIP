<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Malaysian Sign Language</title>
    <meta charset="utf-8">
    <meta name="description" content="sherlyn">
    <meta name="keywords" content="sherlyn">
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
                Hi! My name is Sherlyn and I'm currently on my second year in Swinburne, having completed my foundation in the first and doing my degree in the second.
              </p></span
            >
          </span>
          <img src="images/sherlyn.jpeg" alt="Picture of Paing" id="pfp" />
        </div>

        <div id="credentials">
          <p id="name">Sherlyn Kok Jia Wen</p>
          <p id="student_id">102788463</p>
          <p id="course">Bachelor of Computer Science</p>
        </div>
      </section>

      <br />

      <!--Information section-->
      <section>
        <div id="table">
          <table id="info_t">
            <tr class="odd sherlyn">
              <th>Age</th>
              <td>19</td>
            </tr>

            <tr class="even">
              <th>Gender</th>
              <td>Female</td>
            </tr>

            <tr class="odd sherlyn">
              <th>Nationality</th>
              <td>Malaysian</td>
            </tr>

            <tr class="even">
              <th>Description of Hometown</th>
              <td>
                I was born and raised in Kuching which is the capital city of Sarawak, one of the states in East Malaysia.
              </td>
            </tr>

            <tr class="odd sherlyn">
              <th>A Great Achievement</th>
              <td>
                A feat that I'm proud of is my ability to overcome hardships no matter how long it takes and also how I step out of my comfort zone sometimes.              </td>
            </tr>

            <tr class="even">
              <th>Some things I enjoy!</th>
              <td>
                I enjoy spending quality time with the people I love, shopping, watching various shows, playing video games as well as some occasional reading.
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
            <a href="mailto:102788463@students.swinburne.edu.my">
              Contact Me
            </a>
          </button>
        </div>
      </section>
    </article>

    <?php include 'footer.php'?>
  </body>
</html>
