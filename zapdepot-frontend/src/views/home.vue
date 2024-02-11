<template>
  <nav>
    <input type="checkbox" id="check">
    <label for="check" class="checkbtn">
      <i class="fas fa-bars"></i>
    </label>
    <div class="container">
      <div class="row justify-content-between align-items-center">
        <div class="">
          <img class="logo p-0" src="@/assets/logo/listerpro.png" style="width: 200px" />
        </div>
        <div>
          <ul class="m-0">
            <li>
              <router-link :to="{ name: 'Home' }" class="nav-link">
                <a class="active btn">{{ user ? 'Go To Dashboard' : 'Log In' }}</a>
              </router-link>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <hr class="m-0">
  </nav>
  <main>
    <div class="container">
      <div class="main">
        <div class="main_title">
          <span>Today is a great day to automate your</span>
        </div>
      </div>
      <h1 role="marquee"
        aria-label="Today is a great day to automate your sales outreach, leads , marketing campaigns , customer support, team handoffs, app data, tickets and incidents , employee onboarding, social posts, or projects "
        class="css-zp9oku-HeroCentered__heading-HeroCentered__animatedHeading">
        <span class="data">Lead Flow</span>
        <span class="data">Work</span>
        <span class="data">Business</span>
      </h1>
      <div class="my-5 datas">

                <div class="sub color2"><span>Zapdepot empowers you to automate your work across 10+ apps—so you can move forward, faster.</span></div>

                <div class="sub color1"><span>Do what you do best, let Zapdepot do the rest</span></div>
                <div class="sub color2"><span>It all starts with an app Imagine all the apps you use for work talking to each other.</span></div><div class="sub color2"><span>Zapdepot connects 10+ apps so the conversation flows—automatically.</span></div>

                <div class="sub color1"><span>Next, choose a trigger Your trigger is what kicks off the automated process between your apps.</span></div><div class="sub color1"><span> In our example, the trigger is a lead coming in through Gmail.</span></div>
                <div class="sub color2"><span>This is where the action happens After the trigger step comes the action, or what you want to happen automatically.</span></div><div class="sub color2"><span> In our example, the action is fetching an email.</span></div>
                <div class="sub color1"><span>Zapdepot is an automation platform that connects your work apps and does repetitive tasks for you.</span></div>
            </div>
    </div>
  </main>
</template>
<script>
import { mapGetters, mapState } from "vuex";
export default {
  name: "Home Page",
  components: {
  },
  computed: {
    ...mapState('auth', ['user']),
    ...mapGetters({
      getDashData: "integration/getDashData"
    }),
  },
  mounted: async function() {
    const spans = document.querySelectorAll('h1 span');
    await spans.forEach((span) => {
      span.classList.remove('css-c94oj3-HeroCentered__rotatingHeadingText');
      span.classList.remove('css-595s3g-HeroCentered__rotatingHeadingText-HeroCentered__currentRotatingHeadingText');
      span.classList.remove('css-axvd06-HeroCentered__rotatingHeadingText-HeroCentered__previousRotatingHeadingText');
      span.classList.add('data');
    });
    const rotatingTexts = document.querySelectorAll('.data');
    let index = 0;
    function rotateText() {
      rotatingTexts.forEach((text, i) => {
        if (i === index) {
          text.classList.remove('css-c94oj3-HeroCentered__rotatingHeadingText');
          text.classList.remove('data');
          text.classList.add('css-595s3g-HeroCentered__rotatingHeadingText-HeroCentered__currentRotatingHeadingText');

        } else {
          text.classList.remove('css-595s3g-HeroCentered__rotatingHeadingText-HeroCentered__currentRotatingHeadingText');
          text.classList.add('css-c94oj3-HeroCentered__rotatingHeadingText');
          text.classList.remove('css-axvd06-HeroCentered__rotatingHeadingText-HeroCentered__previousRotatingHeadingText');
        }
      });

      const previousIndex = (index === 0) ? rotatingTexts.length - 1 : index - 1;
      rotatingTexts[previousIndex].classList.add('css-axvd06-HeroCentered__rotatingHeadingText-HeroCentered__previousRotatingHeadingText');
      index = (index + 1) % rotatingTexts.length;
    }

    rotateText(); 

    setInterval(rotateText, 3000);
  }
}
</script>
<style scoped>
h1 {
  font-size: 70px;
  font-weight: 600;
  text-align: center;
  line-height: 0.9em;
  letter-spacing: 1px;
  color: #201515;
  background-color: inherit;
  position: relative;
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
  -webkit-align-items: center;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding-bottom: 1em;
  overflow: hidden;
}

.css-axvd06-HeroCentered__rotatingHeadingText-HeroCentered__previousRotatingHeadingText {
  color: #ff4f00 !important;
  position: absolute !important;
  bottom: -1em !important;
  z-index: 1 !important;
  opacity: 0 !important;
  -webkit-transform: rotate(10deg) !important;
  -moz-transform: rotate(10deg) !important;
  -ms-transform: rotate(10deg) !important;
  transform: rotate(10deg) !important;
  transform-origin: left !important;
  -webkit-transition: none !important;
  transition: none !important;
  bottom: 1em !important;
  opacity: 0 !important;
  display: none !important;
  -webkit-transform: rotate(-10deg) !important;
  -moz-transform: rotate(-10deg) !important;
  -ms-transform: rotate(-10deg) !important;
  transform: rotate(-10deg) !important;
  -webkit-transition: all 500ms cubic-bezier(0.65, 0, 0.35, 1) !important;
  transition: all 500ms cubic-bezier(0.65, 0, 0.35, 1) !important;
}

.css-595s3g-HeroCentered__rotatingHeadingText-HeroCentered__currentRotatingHeadingText {
  color: #ff4f00 !important;
  position: absolute !important;
  bottom: -1em !important;
  display: block !important;
  opacity: 0 !important;
  -webkit-transform: rotate(10deg) !important;
  -moz-transform: rotate(10deg) !important;
  -ms-transform: rotate(10deg) !important;
  transform: rotate(10deg) !important;
  transform-origin: left !important;
  -webkit-transition: none !important;
  transition: none !important;
  bottom: 0.1em !important;
  opacity: 1 !important;
  -webkit-transform: none !important;
  -moz-transform: none !important;
  -ms-transform: none !important;
  transform: none !important;
  -webkit-transition: all 500ms cubic-bezier(0.65, 0, 0.35, 1) !important;
  transition: all 500ms cubic-bezier(0.65, 0, 0.35, 1) !important;
}

.css-c94oj3-HeroCentered__rotatingHeadingText {
  color: #ff4f00 !important;
  position: absolute !important;
  bottom: -1em !important;
  opacity: 0 !important;
  -webkit-transform: rotate(10deg) !important;
  -moz-transform: rotate(10deg) !important;
  -ms-transform: rotate(10deg) !important;
  transform: rotate(10deg) !important;
  transform-origin: left !important;
  -webkit-transition: none !important;
  transition: none !important;
}

.main {
  font-size: 70px;
  font-weight: 600;
  text-align: center;
  line-height: 0.9em;
  letter-spacing: 1px;
  color: #201515;
  font-family: 'Degular', sans-serif;
  background-color: inherit;
  position: relative;
  display: flex;
  flex-direction: column;
  align-items: center;
  padding-bottom: 1em;
  overflow: hidden;
  padding: 96px 20px 48px;
}

.datas span{
    font-size: 22px;
    font-weight: 600;
}
.datas  .color1{
    color:black;
    text-align: left;
}
.datas  .color2{
    color:black;
}
.datas .sub{
    margin: 20px 0px;
}
* {
  text-decoration: none;
  list-style: none;
  box-sizing: border-box;
}

body {
  font-family: montserrat;
}

nav {
  background: #ffffff;
  height: 80px;
  width: 100%;
}

label.logo {
  color: white;
  font-size: 35px;
  line-height: 80px;
  padding: 0 100px;
  font-weight: bold;
}

nav ul {
  float: right;
  margin-right: 20px;
}

nav ul li {
  display: inline-block;
  line-height: 80px;
  margin: 0 5px;
}

nav ul li a {
  color: white;
  font-size: 17px;
  padding: 7px 13px;
  border-radius: 3px;
  text-transform: uppercase;
}

.btn {
  background-color: #007bff;
}

a:hover .btn {
  background-color: #4CAF50;
  color: white;
}

.checkbtn {
  font-size: 30px;
  color: white;
  float: right;
  line-height: 80px;
  margin-right: 40px;
  cursor: pointer;
  display: none;
}

#check {
  display: none;
}

@media (max-width: 952px) {
  label.logo {
    font-size: 30px;
  }

  nav ul li a {
    font-size: 16px;
  }
}

@media (max-width: 858px) {
  .checkbtn {
    display: block;
  }

  ul {
    position: fixed;
    width: 100%;
    height: 100vh;
    background: #2c3e50;
    top: 80px;
    left: -100%;
    text-align: center;
    transition: all .5s;
  }

  nav ul li {
    display: block;
    margin: 50px 0;
    line-height: 30px;
  }

  nav ul li a {
    font-size: 20px;
  }

  a:hover,
  a.active {
    background: none;
    color: #0082e6;
  }

  #check:checked~ul {
    left: 0;
  }
}</style>