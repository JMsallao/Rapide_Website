<style>
    body {
  font-family: "Poppins", sans-serif;
  font-weight: 400;
  font-size: 14px;
  color: #888;
}

body
  > header
  > div.header-inner
  > div
  > div
  > div
  > div.col-lg-2.col-12.mt-3
  > div
  > button {
  background-color: #f5d51f;
  color: #000;
}

body
  > header
  > div.header-inner
  > div
  > div
  > div
  > div.col-lg-2.col-12.mt-3
  > div
  > button:hover {
  background-color: #252000;
  color: #ffffff;
}

.pro-features {
  position: fixed;
  right: -300px;
  width: 300px;
  height: auto;
  line-height: 46px;
  font-size: 14px;
  background: #fff;
  text-align: left;
  color: #333;
  top: 50%;
  transform: translateY(-50%);
  box-shadow: -4px 0px 5px #00000036;
  color: #fff;
  z-index: 9999;
  padding: 20px 30px 30px 30px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.pro-features.active {
  right: 0;
}
.pro-features li.big-title {
  font-weight: 600;
  color: #f5d51f;
  font-size: 15px;
}
.pro-features li.title {
  font-weight: 600;
  color: #f5d51f;
  font-size: 15px;
}
.pro-features .button {
}
.pro-features .button .btn {
  width: 100%;
  text-align: center;
  margin-top: 8px;
  display: inline-block;
  float: left;
  font-size: 13px;
  width: 100%;
  text-transform: capitalize;
}
.pro-features li {
  color: #333;
  margin: 0;
  padding: 0;
  line-height: 22px;
  margin-bottom: 10px;
}
.get-pro {
  position: absolute;
  left: -80px;
  width: 80px;
  height: 45px;
  line-height: 45px;
  font-size: 14px;
  border-radius: 5px 0 0 5px;
  background: #1a76d1;
  text-align: center;
  color: #fff;
  top: 0;
  cursor: pointer;
  box-shadow: -4px 0px 5px #00000036;
}
.get-pro:hover {
}
#scrollUp {
  bottom: 15px;
  right: 15px;
  padding: 10px 20px;
  background: #f5d51f;
  color: #fff;
  font-size: 25px;
  width: 45px;
  height: 45px;
  text-align: center;
  line-height: 45px;
  padding: 0;
  border-radius: 3px;
  box-shadow: 0px 0px 10px #00000026;
}
#scrollUp:hover {
  background: #2c2d3f;
}
/* Color Plate */
.color-plate {
  position: fixed;
  display: block;
  z-index: 99998;
  padding: 20px;
  width: 245px;
  background: #fff;
  right: -245px;
  text-align: left;
  top: 30%;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  -webkit-box-shadow: -3px 0px 25px -2px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: -3px 0px 25px -2px rgba(0, 0, 0, 0.2);
  box-shadow: -3px 0px 25px -2px rgba(0, 0, 0, 0.2);
}
.color-plate.active {
  right: 0;
}
.color-plate .color-plate-icon {
  position: absolute;
  left: -48px;
  width: 48px;
  height: 45px;
  line-height: 45px;
  font-size: 21px;
  border-radius: 5px 0 0 5px;
  background: #1a76d1;
  text-align: center;
  color: #fff !important;
  top: 0;
  cursor: pointer;
  box-shadow: -4px 0px 5px #00000036;
}
.color-plate h4 {
  display: block;
  font-size: 15px;
  margin-bottom: 5px;
  font-weight: 500;
}
.color-plate p {
  font-size: 13px;
  margin-bottom: 15px;
  line-height: 20px;
}
.color-plate span {
  width: 42px;
  height: 35px;
  border-radius: 0;
  cursor: pointer;
  display: inline-block;
  margin-right: 3px;
}
.color-plate span:hover {
  cursor: pointer;
}
.color-plate span.color1 {
  background: #1a76d1;
}
.color-plate span.color2 {
  background: #2196f3;
}
.color-plate span.color3 {
  background: #32b87d;
}
.color-plate span.color4 {
  background: #fe754a;
}
.color-plate span.color5 {
  background: #f82f56;
}
.color-plate span.color6 {
  background: #01b2b7;
}
.color-plate span.color7 {
  background: #6c5ce7;
}
.color-plate span.color8 {
  background: #85ba46;
}
.color-plate span.color9 {
  background: #273c75;
}
.color-plate span.color10 {
  background: #fd7272;
}
.color-plate span.color11 {
  background: #badc58;
}
.color-plate span.color12 {
  background: #44ce6f;
}
/*=============================
	End Global CSS 
===============================*/

/*===================
	Start Header CSS 
=====================*/
.header {
  background-color: #fff;
  position: relative;
}
.header .navbar-collapse {
  padding: 0;
}
/* Topbar */
.header .topbar {
  background-color: #fff;
  border-bottom: 1px solid #eee;
}
.header .topbar {
  padding: 15px 0;
}
.header .top-link {
  float: left;
}
.header .top-link li {
  display: inline-block;
  margin-right: 15px;
}
.header .top-link li:last-child {
  margin-right: 0px;
}
.header .top-link li a {
  color: #2c2d3f;
  font-size: 14px;
  font-weight: 400;
}
.header .top-link li:hover a {
  color: #f5d51f;
}
.header .top-contact {
  float: right;
}
.header .top-contact li {
  display: inline-block;
  margin-right: 25px;
  color: #2c2d3f;
}
.header .top-contact li:last-child {
  margin-right: 0;
}
.header .top-contact li a {
  font-size: 14px;
}
.header .top-contact li a:hover {
  color: #f5d51f;
}
.header .top-contact li i {
  color: black;
  margin-right: 8px;
}
.header .header-inner {
  background: #fff;
  z-index: 999;
  width: 100%;
}
.get-quote {
  margin-top: 12px;
}
.get-quote .btn {
  color: #fff;
}
.header .logo {
  float: left;
  margin-top: 18px;
}
.header .navbar {
  background: none;
  box-shadow: none;
  border: none;
  margin: 0;
  height: 0px;
  min-height: 0px;
}
.header .nav li {
  margin-right: 15px;
  float: left;
  position: relative;
}
.header .nav li:last-child {
  margin: 0;
}
.header .nav li a {
  color: #2c2d3f;
  font-size: 14px;
  font-weight: 500;
  text-transform: capitalize;
  padding: 25px 12px;
  position: relative;
  display: inline-block;
  position: relative;
}
.header .nav li a::before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 3px;
  width: 0%;
  background: #aa9100;
  border-radius: 5px 5px 0 0;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.header .nav li.active a:before {
  opacity: 1;
  visibility: visible;
  width: 100%;
}
.header .nav li.active a {
  color: #000000;
}
.header .nav li:hover a:before {
  opacity: 1;
  width: 100%;
  visibility: visible;
}
.header .nav li:hover a {
  color: #948500;
}
.header .nav li a i {
  display: inline-block;
  margin-left: 1px;
  font-size: 13px;
}
/* Middle Header */
.header.style2 .header-inner {
  border-top: 1px solid #eee;
}
.header.style2 .logo {
  margin-top: 6px;
}
.header .middle-header {
  background: #fff;
  padding: 20px 0px;
}
.header .widget-main {
  float: right;
}
.header.style2 .get-quote {
  margin-top: 0;
}
.header .single-widget {
  position: relative;
  float: left;
  margin-right: 30px;
  padding-left: 55px;
}
.header .single-widget:last-child {
  margin: 0;
}
.header .single-widget .logo {
  margin: 0;
  padding: 0;
  margin-top: 7px;
}
.header .single-widget i {
  position: absolute;
  left: 0;
  top: 6px;
  height: 40px;
  width: 40px;
  line-height: 40px;
  color: #fff;
  background: #1a76d1;
  border-radius: 4px;
  text-align: center;
  font-size: 15px;
}
.header .single-widget h4 {
  font-size: 15px;
  font-weight: 500;
}
.header .single-widget p {
  margin-bottom: 5px;
  text-transform: capitalize;
}
.header .single-widget.btn {
  margin-left: 0;
}
/* Dropdown Menu */
.header .nav li .dropdown {
  background: #fff;
  width: 220px;
  position: absolute;
  left: -20px;
  top: 100%;
  z-index: 999;
  -webkit-box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
  -moz-box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.2);
  box-shadow: 0px 3px 5px #3333334d;
  transform-origin: 0 0 0;
  transform: scaleY(0.2);
  -webkit-transition: all 0.3s ease 0s;
  -moz-transition: all 0.3s ease 0s;
  transition: all 0.3s ease 0s;
  opacity: 0;
  visibility: hidden;
  top: 74px;
  border-left: 3px solid #fcd600;
}
.header .nav li:hover .dropdown {
  opacity: 1;
  visibility: visible;
  transform: translateY(0px);
}
.header .nav li .dropdown li {
  float: none;
  margin: 0;
  border-bottom: 1px dashed #eee;
}
.header .nav li .dropdown li:last-child {
  border: none;
}
.header .nav li .dropdown li a {
  padding: 12px 15px;
  color: #666;
  display: block;
  font-weight: 400;
  text-transform: capitalize;
  background: transparent;
  -webkit-transition: all 0.2s ease;
  -moz-transition: all 0.2s ease;
  transition: all 0.2s ease;
}
.header .nav li .dropdown li a:before {
  display: none;
}
.header .nav li .dropdown li:last-child a {
  border-bottom: 0px;
}
.header .nav li .dropdown li:hover a {
  color: #a89700;
}
.header .nav li .dropdown li a:hover {
  border-color: transparent;
}
/* Right Bar */
.header.style2 .main-menu {
  display: inline-block;
  float: left;
}
.header .right-bar {
  float: right;
}
.header .right-bar {
  padding-top: 20px;
}
.header .right-bar {
  display: inline-block;
}
.header .right-bar a {
  color: #fff;
  height: 35px;
  width: 35px;
  line-height: 35px;
  text-align: center;
  background: #a89700;
  border-radius: 4px;
  display: block;
  font-size: 12px;
}
.header .right-bar li a:hover {
  color: #fff;
  background: #27ae60;
}
.header .search-top.active .search i:before {
  content: "\eee1";
  font-size: 15px;
}
/* Search */
.header .search-form {
  position: absolute;
  right: 0;
  z-index: 9999;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  top: 74px;
  box-shadow: 0px 0px 10px #0000001c;
  border-radius: 4px;
  overflow: hidden;
  transform: scale(0);
}
.header .search-top.active .search-form {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}
.header .search-form input {
  width: 282px;
  height: 50px;
  line-height: 50px;
  padding: 0 70px 0 20px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  border-radius: 3px;
  border: none;
  background: #fff;
  color: #2c2d3f;
}
.header .search-form button {
  position: absolute;
  right: 0;
  height: 50px;
  top: 0;
  width: 50px;
  background: #1a76d1;
  border: none;
  color: #fff;
  border-radius: 0 4px 4px 0;
  border-left: 1px solid transparent;
}
.header .search-form button:hover {
  background: #fff;
  color: #a89700;
  border-color: #e6e6e6;
}
/* Header Sticky */
.header.sticky .header-inner {
  position: fixed;
  z-index: 999;
  top: 0;
  left: 0;
  bottom: initial;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  animation: fadeInDown 0.5s both 0.1s;
  box-shadow: 0px 0px 13px #00000054;
}
/*=========================
	End Header CSS
===========================*/

/*===========================
	Start Hero Area CSS
=============================*/
.slider .single-slider {
  height: 600px;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
}

.slider .single-slider:before {
  position: absolute;
  content: "";
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  background: #fff;
  opacity: 0.5;
}

.slider .single-slider .text {
  margin-top: 120px;
}
.slider.index2 .single-slider .text {
  margin-top: 150px;
}
.slider .single-slider h1 {
  color: #000000;
  font-size: 38px;
  font-weight: 700;
  margin: 0;
  padding: 0;
  line-height: 42px;
}
.slider .single-slider h1 span {
  color: #ffe600;
}
.slider .single-slider p {
  color: #050000;
  margin-top: 27px;
  font-weight: 400;
}
.slider .single-slider .button {
  margin-top: 30px;
}
.slider .single-slider .btn {
  color: #000000;
  background: #ffe600;
  font-weight: 500;
  display: inline-block;
  margin: 0;
  margin-right: 10px;
}
.slider .single-slider .btn:last-child {
  margin: 0;
}
.slider .single-slider .btn.primary {
  background: #2c2d3f;
  color: #fff;
}
.slider .single-slider .btn.primary:before {
  background: #ffe600;
}
.slider .owl-carousel .owl-nav {
  margin: 0;
  position: absolute;
  top: 50%;
  width: 100%;
  margin-top: -25px;
}
.slider .owl-carousel .owl-nav div {
  height: 50px;
  width: 50px;
  line-height: 50px;
  text-align: center;
  background: #ffe600;
  color: #fff;
  font-size: 26px;
  position: absolute;
  margin: 0;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  padding: 0;
  border-radius: 50%;
}
.slider .owl-carousel .owl-nav div:hover {
  background: #2c2d3f;
  color: #fff;
}
.slider .owl-carousel .owl-controls .owl-nav .owl-prev {
  left: 20px;
}
.slider .owl-carousel .owl-controls .owl-nav .owl-next {
  right: 20px;
}

/* Slider Animation */
.owl-item.active .single-slider h1 {
  animation: fadeInUp 1s both 0.6s;
}
.owl-item.active .single-slider p {
  animation: fadeInUp 1s both 1s;
}
.owl-item.active .single-slider .button {
  animation: fadeInDown 1s both 1.5s;
}
/*===========================
	End Hero Area CSS
=============================*/

/*=============================
	Start Schedule CSS
===============================*/
.schedule {
  background: #fff;
  margin: 0;
  padding: 0;
  height: 230px;
}
.schedule .schedule-inner {
  position: relative;
  transform: translateY(-50%);
  z-index: 9;
}
.schedule .single-schedule {
  position: relative;
  text-align: left;
  z-index: 3;
  border-radius: 5px;
  background: #fae310;
  -webkit-transition: all 0.3s ease-out 0s;
  -moz-transition: all 0.3s ease-out 0s;
  -ms-transition: all 0.3s ease-out 0s;
  -o-transition: all 0.3s ease-out 0s;
  transition: all 0.3s ease-out 0s;
}
.schedule .single-schedule .inner {
  overflow: hidden;
  position: relative;
  padding: 30px;
  z-index: 2;
}
.schedule .single-schedule:before {
  position: absolute;
  z-index: -1;
  content: "";
  bottom: -10px;
  left: 0;
  right: 0;
  margin: 0 auto;
  width: 80%;
  height: 90%;
  background: #f3db00;
  opacity: 0;
  filter: blur(10px);
  -webkit-transition: all 0.3s ease-out 0s;
  -moz-transition: all 0.3s ease-out 0s;
  -ms-transition: all 0.3s ease-out 0s;
  -o-transition: all 0.3s ease-out 0s;
  transition: all 0.3s ease-out 0s;
}
.schedule .single-schedule:hover:before {
  opacity: 0.8;
}
.schedule .single-schedule:hover {
  transform: translateY(-5px);
}
.schedule .single-schedule .icon i {
  position: absolute;
  font-size: 110px;
  color: #000000;
  -webkit-transition: all 0.3s ease-out 0s;
  -moz-transition: all 0.3s ease-out 0s;
  -ms-transition: all 0.3s ease-out 0s;
  -o-transition: all 0.3s ease-out 0s;
  transition: all 0.3s ease-out 0s;
  z-index: -1;
  visibility: visible;
  opacity: 0.2;
  right: -25px;
  bottom: -30px;
}
.schedule .single-schedule span {
  display: block;
  color: #bb8900;
}
.schedule .single-schedule h4 {
  font-size: 20px;
  font-weight: 600;
  display: inline-block;
  text-transform: capitalize;
  color: #000000;
  margin-top: 13px;
}
.schedule .single-schedule p {
  color: #000000;
  margin-top: 22px;
}
.schedule .single-schedule a {
  color: #000000;
  margin-top: 25px;
  font-weight: 500;
  display: inline-block;
  position: relative;
}
.schedule .single-schedule a:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 1px;
  width: 0%;
  background: #000000;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.schedule .single-schedule a:hover:before {
  width: 100%;
  width: 100%;
}
.schedule .single-schedule a i {
  margin-left: 10px;
}
.schedule .single-schedule .time-sidual {
}
.schedule .single-schedule .time-sidual {
  overflow: hidden;
  margin-top: 17px;
}
.schedule .single-schedule .time-sidual li {
  display: block;
  color: #000000;
  width: 100%;
  margin-bottom: 3px;
}
.schedule .single-schedule .time-sidual li:last-child {
  margin: 0;
}
.schedule .single-schedule .time-sidual li span {
  display: inline-block;
  float: right;
}
.schedule .single-schedule .day-head .time {
  font-weight: 400;
  float: right;
}
/*=============================
	End Schedule CSS
===============================*/

/*=============================
	Start Feautes CSS
===============================*/
.Feautes {
  padding-top: 0;
}
.Feautes.index2 {
  padding-top: 100px;
}
.Feautes.testimonial-page {
  padding-top: 100px;
}
.Feautes .single-features {
  text-align: center;
  position: relative;
  padding: 10px 20px;
}
.Feautes .single-features::before {
  position: absolute;
  content: "";
  right: -72px;
  top: 60px;
  width: 118px;
  border-bottom: 3px dotted #ffd900;
}
.Feautes .single-features.last::before {
  display: none;
}
.Feautes .single-features .signle-icon {
  position: relative;
}

.Feautes .single-features .signle-icon img {
  height: 150px;
}
/* .Feautes .single-features .signle-icon i{
	font-size:50px;
	color:#ffd900;
	position:absolute;
	left:50%;
	margin-left:-50px;
	top:0;
	height:100px;
	width:100px;
	line-height:100px;
	text-align:center;
	border:1px solid #dddddd;
	border-radius:100%;
	-webkit-transition:all 0.4s ease;
	-moz-transition:all 0.4s ease;
	transition:all 0.4s ease;
} */
.Feautes .single-features:hover .signle-icon i {
  background: #ffd900;
  color: #fff;
  border-color: transparent;
}
.Feautes .single-features h3 {
  padding-top: 128px;
  color: #2c2d3f;
  font-weight: 600;
  font-size: 21px;
}
.Feautes .single-features p {
  margin-top: 20px;
}
/*=============================
	End Feautes CSS
===============================*/

/*=======================
	Start Fun Facts CSS
=========================*/
.fun-facts {
  position: relative;
}
.fun-facts.section {
  padding: 120px 0;
}
.fun-facts {
  background: url("img/fun-bg.jpg");
  background-size: cover;
  background-repeat: no-repeat;
}
.fun-facts .single-fun {
}
.fun-facts .single-fun i {
  position: absolute;
  left: 0;
  font-size: 62px;
  color: #fff;
  height: 70px;
  width: 70px;
  line-height: 67px;
  font-size: 28px;
  text-align: center;
  padding: 0;
  margin: 0;
  border: 2px solid #fff;
  border-radius: 0px;
  top: 50%;
  margin-top: -35px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  border-radius: 50%;
}
.fun-facts .single-fun:hover i {
  color: #ffd900;
  background: #fff;
  border-color: transparent;
}
.fun-facts .single-fun .content {
  padding-left: 80px;
}
.fun-facts .single-fun span {
  color: #fff;
  font-weight: 600;
  font-size: 30px;
  position: relative;
  display: block;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s eas;
  display: block;
  margin-bottom: 7px;
}
.fun-facts .single-fun p {
  color: #fff;
  font-size: 15px;
}
/*===================
	End Fun Facts
=====================*/

/*===================
	Why choose CSS
=====================*/
.why-choose .choose-left h3 {
  font-size: 24px;
  font-weight: 600;
  color: #2c2d3f;
  position: relative;
  padding-bottom: 20px;
  margin-bottom: 24px;
}
.why-choose .choose-left h3:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 2px;
  width: 50px;
  background: #ffd900;
}
.why-choose .choose-left p {
  margin-bottom: 35px;
}
.why-choose .choose-left .list {
}
.why-choose .choose-left .list li {
  color: #868686;
  margin-bottom: 12px;
}
.why-choose .choose-left .list li:last-child {
  margin-bottom: 0px;
}
.why-choose .choose-left .list li i {
  height: 15px;
  width: 15px;
  line-height: 15px;
  text-align: center;
  background: #ffd900;
  color: #fff;
  font-size: 14px;
  border-radius: 100%;
  padding-left: 2px;
  margin-right: 16px;
}
/* Start Faq CSS */
.why-choose {
  background: #fff;
}
.why-choose .choose-right {
  height: 100%;
  width: 100%;
  background-image: url("img/video-bg.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
}
.why-choose .choose-right .video {
  color: #fff;
  height: 70px;
  width: 70px;
  line-height: 70px;
  text-align: center;
  border-radius: 100%;
  position: absolute;
  left: 50%;
  top: 50%;
  margin-left: -35px;
  margin-top: -35px;
  font-size: 21px;
  background: #ffd900;
  padding-left: 4px;
}
.why-choose .choose-right .video:hover {
  transform: scale(1.1);
}
.why-choose .video-image .waves-block .waves {
  position: absolute;
  width: 200px;
  height: 200px;
  background: #fff;
  opacity: 0;
  -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
  border-radius: 100%;
  -webkit-animation: waves 3s ease-in-out infinite;
  animation: waves 3s ease-in-out infinite;
  left: 50%;
  margin-left: -100px;
  top: 50%;
  margin-top: -100px;
}
.why-choose .video-image .waves-block .wave-1 {
  -webkit-animation-delay: 0s;
  animation-delay: 0s;
}
.why-choose .video-image .waves-block .wave-2 {
  -webkit-animation-delay: 1s;
  animation-delay: 1s;
}
.why-choose .video-image .waves-block .wave-3 {
  -webkit-animation-delay: 2s;
  animation-delay: 2s;
}
/*=======================
	End Why choose CSS
=========================*/

/*===============================
	Start Call to action CSS
=================================*/
.call-action {
  background-image: url("img/call-bg.jpg");
  background-size: cover;
  background-position: center;
  position: relative;
  background-repeat: no-repeat;
}
.call-action .content {
  text-align: center;
  padding: 100px 265px;
}
.call-action .content h2 {
  color: #fff;
  font-size: 32px;
  font-weight: 600;
  line-height: 46px;
}
.call-action .content p {
  color: #fff;
  margin: 30px 0px;
  font-size: 15px;
}
.call-action .content .btn {
  background: #fff;
  margin-right: 20px;
  font-weight: 500;
  border: 1px solid #fff;
  color: #ffd900;
}
.call-action .content .btn:before {
  background: #ffd900;
}
.call-action .content .btn:hover {
  background: #ffd900;
  color: #fff;
}
.call-action .content .btn:last-child {
  margin-right: 0px;
}
.call-action .content .second {
  border: 1px solid #fff;
  color: #fff;
  background: transparent;
  color: #fff !important;
}
.call-action .content .second:before {
  background: #fff;
}
.call-action .content .second:hover {
  color: #ffd900;
  border-color: transparent;
  background: #fff;
}
.call-action .content .second i {
  margin-left: 10px;
}
/*===============================
	Start Call to action CSS
=================================*/

/*==========================
	Start Portfolio CSS
============================*/
.portfolio {
  background: #fdfdfd;
}
.portfolio .single-pf {
  position: relative;
}
.portfolio .single-pf img {
  height: 100%;
  width: 100%;
}
.portfolio .single-pf:before {
  position: absolute;
  content: "";
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  background: #ffd900;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  z-index: 1;
}
.portfolio .single-pf:hover:before {
  opacity: 0.7;
  visibility: visible;
}
.portfolio .single-pf .btn {
  color: #ffd900;
  z-index: 3;
  background: #fff;
  position: absolute;
  left: 50%;
  top: 50%;
  border-radius: 0px;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  height: 48px;
  width: 150px;
  text-align: center;
  line-height: 48px;
  padding: 0;
  font-weight: 500;
  font-size: 14px;
  margin-left: -75px;
  margin-top: -24px;
  border-radius: 4px;
}
.portfolio .single-pf:hover .btn {
  opacity: 1;
  visibility: visible;
}
.portfolio .single-pf .btn:hover {
  color: #fff;
}
.portfolio .owl-nav {
  display: none;
}
/* Slider Nav */
.pf-details .image-slider .owl-nav {
  margin: 0;
  position: absolute;
  top: 50%;
  width: 100%;
  margin-top: -25px;
}
.pf-details .image-slider .owl-carousel .owl-nav div {
  height: 50px;
  width: 50px;
  line-height: 45px;
  background: #fff;
  color: #1a76d1;
  position: absolute;
  margin: 0;
  border-radius: 100%;
  font-size: 20px;
  text-align: center;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  box-shadow: 0px 0px 10px #0000001a;
}
.pf-details .image-slider .owl-carousel .owl-nav div:hover {
  color: #fff;
  background: #ffd900;
}
.pf-details .image-slider .owl-carousel .owl-controls .owl-nav .owl-prev {
  left: 20px;
}
.pf-details .image-slider .owl-carousel .owl-controls .owl-nav .owl-next {
  right: 20px;
}
.pf-details .image-slider {
  border-radius: 8px 8px 0 0;
}
.pf-details .image-slider img {
  height: 100%;
  width: 100%;
}
.pf-details .date {
  background: #ffd900;
  display: block;
  padding: 20px;
  text-align: center;
  border-radius: 0;
  border: none;
  margin: 0;
  margin-top: -1px;
}
.pf-details .date ul li {
  font-size: 16px;
  color: #fff;
  display: inline-block;
  margin-right: 60px;
}
.pf-details .date ul li:last-child {
  margin: 0;
}
.pf-details .date ul li span {
  font-weight: 500;
  display: inline-block;
  margin-right: 5px;
}
.pf-details .body-text {
}
.pf-details .body-text h3 {
  font-size: 30px;
  font-weight: 600;
  color: #333;
  margin-top: 40px;
}
.pf-details .body-text p {
  margin-top: 20px;
}
.pf-details .body-text .share {
  margin-top: 40px;
}
.pf-details .body-text .share h4 {
  font-size: 15px;
  font-weight: 500;
  display: inline-block;
}
.pf-details .body-text .share ul {
  display: inline-block;
  margin-left: 12px;
}
.pf-details .body-text .share ul li {
  display: inline-block;
  margin-right: 10px;
}
.pf-details .body-text .share ul li:last-child {
  margin-right: 0;
}
.pf-details .body-text .share ul li a {
  height: 35px;
  width: 35px;
  line-height: 35px;
  text-align: center;
  border: 1px solid #c8c8c8;
  color: #757575;
  display: block;
  border-radius: 50%;
}
.pf-details .body-text .share ul li a:hover {
  color: #fff;
  border-color: transparent;
  background: #ffd900;
}
/*==========================
	End Portfolio CSS
============================*/

/*=========================
	Srart service CSS
===========================*/

.services .single-service {
  margin: 30px 0;
  position: relative;
  padding-left: 110px;
}

.services .single-service img {
  height: 100px;
  font-size: 45px;
  color: #ffd900;
  position: absolute;
  left: 0;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}

.services .single-service h4 {
  text-transform: capitalize;
  margin-bottom: 25px;
  color: #2c2d3f;
}
.services .single-service h4 a {
  color: #2c2d3f;
  font-size: 20px;
  font-weight: 600;
}
.services .single-service h4 a:hover {
  color: #ffd900;
}
.services .single-service p {
  color: #868686;
}
/*-- Service Details --*/
.services-details-img {
  margin-bottom: 50px;
}

.services-details-img img {
  width: 100%;
  margin-bottom: 30px;
}
.services-details-img h2 {
  font-weight: 600;
  font-size: 28px;
  margin-bottom: 16px;
}
.services-details-img P {
  margin-bottom: 20px;
}
.services-details-img blockquote {
  font-size: 15px;
  color: #4a6f8a;
  background-color: #ffd900;
  padding: 30px 75px;
  line-height: 26px;
  position: relative;
  margin-bottom: 20px;
  color: #fff;
}
.services-details-img blockquote i {
  position: absolute;
  display: inline-block;
  top: 20px;
  left: 38px;
  font-size: 32px;
}
.service-details-inner-left {
  background-image: url("img/signup-bg.jpg");
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
}
.service-details-inner-left img {
  display: none;
}
.service-details-inner {
  max-width: 580px;
  margin-left: auto;
  margin-right: auto;
}
.service-details-inner h2 {
  font-weight: 600;
  font-size: 30px;
  margin-bottom: 15px;
  border-left: 3px solid #ffd900;
  padding-left: 15px;
}
.service-details-inner p {
  margin-bottom: 15px;
}
.service-details-inner p:last-child {
  margin-bottom: 0;
}
/*=========================
	End service CSS
===========================*/

/*=============================
	Start Testimonials CSS
===============================*/
.testimonials {
  background-image: url("img/testi-bg.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
}
.testimonials .section-title h2 {
  color: #fff;
}
.testimonials .single-testimonial {
  text-align: left;
  position: relative;
  background: #fff;
  padding: 40px 30px;
  margin: 5px;
  margin-bottom: 27px;
  margin-right: 30px;
  border-radius: 5px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  margin: 0;
  margin: 30px 20px;
}
.testimonials .single-testimonial:hover {
  box-shadow: 0px 10px 10px #0000001c;
  transform: translateY(-4px);
}
.testimonials .single-testimonial img {
  position: absolute;
  left: 30px;
  bottom: -26px;
  height: 53px;
  width: 53px;
  border-radius: 100%;
}
.testimonials .single-testimonial p {
  color: #868686;
  font-size: 14px;
}
.testimonials .single-testimonial .name {
  margin-top: 22px;
  color: #2c2d3f;
  font-weight: 500;
  font-size: 15px;
}
.testimonials .owl-dots {
  position: absolute;
  left: 50%;
  bottom: -55px;
  margin-top: -47px;
  padding: 10px 25px;
  border-radius: 3px;
  margin: 0 0 0 -52px;
  margin-top: 49px;
  box-sizing: ;
}
.testimonials .owl-dots .owl-dot {
  display: inline-block;
  margin-right: 10px;
}
.testimonials .owl-dots .owl-dot:last-child {
  margin: 0px;
}
.testimonials .owl-dots .owl-dot span {
  width: 10px;
  height: 10px;
  display: block;
  border-radius: 30px;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  transition: all 0.3s ease;
  margin: 0;
  background: #fff;
  position: relative;
}
.testimonials .owl-dots .owl-dot span:hover {
  background: #fff;
}
.testimonials .owl-dots .owl-dot.active span {
  background: #fff;
  width: 20px;
}
/*=============================
	End Testimonials CSS
===============================*/

/*==========================
	Start Departments CSS
============================*/
.departments .department-tab .nav {
  margin-bottom: 30px;
  background: transform;
  position: relative;
}
.departments .department-tab .nav li {
  text-align: center;
  margin-right: 54px;
}
.departments .department-tab .nav li a i {
  font-size: 50px;
  color: #868686;
}
.departments .department-tab .nav li a:hover {
  background: transparent;
}
.departments .department-tab .nav li a.active i {
  color: #ffd900;
}
.departments .department-tab .nav li a {
  color: #fff;
  margin-top: 20px;
  border: none;
  padding: 0;
  padding-bottom: 20px;
  border-bottom: 2px solid transparent;
  padding: 0 10px 20px 10px;
}
.departments .department-tab .nav li a.active {
  border-color: #ffd900;
}
.departments .department-tab .nav li span {
  display: block;
}
.departments .department-tab .nav li .first {
  padding-top: 20px;
  font-size: 20px;
  font-weight: 500;
  color: #868686;
}
.departments .department-tab .nav li a.active .first {
  color: #2c2d3f;
}
.departments .department-tab .nav li .second {
  font-size: 14px;
  font-weight: 400;
  color: #868686;
  margin-top: 3px;
}
.departments .department-tab .tab-pane .department-left {
}
.departments .department-tab .tab-pane .department-left h3 {
  color: #2c2d3f;
  font-weight: 600;
  font-size: 26px;
  position: relative;
  padding-bottom: 15px;
  margin-bottom: 30px;
}
.departments .department-tab .tab-pane .department-left h3:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 3px;
  width: 50px;
  background: #2c2d3f;
}
.departments .department-tab .tab-pane .department-left .p1 {
  color: #ffd900;
  font-weight: 500;
  margin-bottom: 18px;
}
.departments .department-tab .tab-pane .department-left p {
  margin-bottom: 20px;
}
.departments .department-tab .tab-pane .department-left .list {
}
.departments .department-tab .tab-pane .department-left .list li {
  position: relative;
  padding-left: 30px;
  margin-bottom: 6px;
}
.departments .department-tab .tab-pane .department-left .list li:last-child {
  margin-bottom: 0px;
}
.departments .department-tab .tab-pane .department-left .list li i {
  position: absolute;
  left: 0;
  height: 15px;
  width: 15px;
  line-height: 15px;
  color: #fff;
  background: #ffd900;
  text-align: center;
  border-radius: 100%;
  font-size: 8px;
  margin-right: 20px;
  top: 4px;
}
.departments .department-tab .tab-content .tab-text h2 {
  font-size: 18px;
}
.departments .department-tab .tab-content .tab-text p {
  color: #555;
  margin-top: 10px;
}
/*==========================
	End Departments CSS
============================*/

/*=============================
	Start Pricing Table CSS
===============================*/
.pricing-table {
  background: #f9f9f9;
  position: relative;
}
.pricing-table .single-table {
  background: #fff;
  border: 1px solid #ddd;
  text-align: center;
  position: relative;
  overflow: hidden;
  margin: 15px 0;
  padding: 45px 35px 30px 35px;
}
/* Table Head */
.pricing-table .single-table .table-head img {
  text-align: center;
  height: 160px;
}
.pricing-table .single-table .img {
  height: 100px;
  font-size: 65px;
  color: #ffd900;
}
.pricing-table .single-table .title {
  font-size: 21px;
  color: #2c2d3f;
  margin-top: 30px;
  margin-bottom: 15px;
}
.pricing-table .single-table .amount {
  font-size: 36px;
  font-weight: 600;
  color: #ffd900;
}
.pricing-table .single-table .amount span {
  display: inline-block;
  font-size: 14px;
  font-weight: 400;
  color: #868686;
  margin-left: 8px;
}
/* Table List */
.pricing-table .single-table .table-list {
  padding: 10px 0;
  text-align: left;
  margin-top: 30px;
}
.pricing-table .table-list li {
  position: relative;
  color: #666;
  text-transform: capitalize;
  margin-bottom: 18px;
  padding-right: 32px;
}
.pricing-table .table-list li:last-child {
  margin-bottom: 0px;
}
.pricing-table .table-list li.cross i {
  background: #aaaaaa;
}
.pricing-table .table-list i {
  font-size: 7px;
  text-align: center;
  margin-right: 10px;
  position: absolute;
  right: 0;
  height: 16px;
  width: 16px;
  line-height: 16px;
  text-align: center;
  color: #fff;
  background: #ffd900;
  border-radius: 100%;
  padding-left: 1px;
}

/* Table Bottom */
.pricing-table .table-bottom {
  margin-top: 25px;
}
.pricing-table .btn {
  background-color: red;
  padding: 12px 25px;
  width: 100%;
  color: #fff;
}
.pricing-table .btn:before {
  background: #2c2d3f;
}
.pricing-table .btn:hover {
  color: #fff;
}
.pricing-table .btn i {
  font-size: 16px;
  margin-right: 10px;
}
/*=============================
	End Pricing Table CSS
===============================*/

/*========================
	Start Clients CSS
==========================*/
.clients {
  background-image: url("img/client-bg.jpg");
  background-size: cover;
  background-position: center;
  padding: 100px 0px;
  position: relative;
}
.clients .single-clients {
}
.clients .single-clients img {
  width: 100%;
  cursor: pointer;
  text-align: center;
  float: none;
  padding: 0 35px;
}
/*========================
	End Clients CSS
==========================*/

/*====================
	Start Team CSS
======================*/
.team {
  background-image: url("img/testi-bg.jpg");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  position: relative;
}
.team.single-page {
  background: #fff;
}
.team .section-title h2 {
  color: #fff;
}
.team .section-title p {
  color: #fff;
}
.team .single-team {
  background: #fff;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  transition: all 0.3s ease;
  margin-top: 30px;
  text-align: center;
  box-shadow: 0px 0px 10px #00000021;
  border-radius: 5px;
  overflow: hidden;
}
.team .t-head {
  position: relative;
  overflow: hidden;
}
.team .t-head::before {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #fff;
  opacity: 0;
  visibility: hidden;
  content: "";
  z-index: 2;
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  transition: all 0.3s ease;
}
.team .single-team:hover .t-head::before {
  visibility: visible;
  opacity: 0.5;
}
.team .t-head img {
  width: 100%;
  position: relative;
}
.team .t-icon a {
  position: absolute;
  left: 50%;
  top: 50%;
  width: 150px;
  height: 46px;
  line-height: 40px;
  opacity: 0;
  visibility: hidden;
  font-weight: 400;
  text-align: center;
  color: #fff;
  border-radius: 0;
  -webkit-transform: scale(0.6);
  -moz-transform: scale(0.6);
  transform: scale(0.6);
  -webkit-transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  transition: all 0.3s ease;
  z-index: 99;
  margin: -23px 0 0 -75px;
  font-size: 15px;
  background: #ffd900;
  font-size: 13px;
  line-height: 46px;
  padding: 0;
  border-radius: 4px;
}
.team .single-team:hover .t-icon a {
  -webkit-transform: scale(1);
  -moz-transform: scale(1);
  transform: scale(1);
  opacity: 1;
  visibility: visible;
}
.team .t-bottom {
  text-align: center;
  position: relative;
  padding: 0 20px;
  padding: 25px 20px;
}
.team .t-bottom p {
  color: #666;
  font-size: 13px;
  display: block;
  margin-bottom: 4px;
}
.team .t-bottom h2 {
  font-size: 18px;
  text-transform: capitalize;
  font-weight: 500;
  color: #2c2d3f;
}
.team .t-bottom h2 a:hover {
  color: #ffd900;
}
/*-- Doctor Details --*/
.doctor-details-left {
  -webkit-box-shadow: 0px 0px 10px 0px #ddd;
  box-shadow: 0px 0px 10px 0px #ddd;
  border-radius: 10px;
  overflow: hidden;
}
.doctor-details-item img {
  width: 100%;
  border-radius: 0;
}
.doctor-details-item .doctor-details-contact {
  padding: 50px;
}
.doctor-details-item .doctor-details-contact h3 {
  font-weight: 600;
  font-size: 20px;
  color: #2c2d3f;
  margin-bottom: 30px;
}
.doctor-details-item .doctor-details-contact .basic-info {
  margin: 0;
  padding: 0;
}
.doctor-details-item .doctor-details-contact .basic-info li {
  list-style-type: none;
  display: block;
  font-weight: 400;
  font-size: 15px;
  color: #2c2d3f;
  margin-bottom: 10px;
}
.doctor-details-item .doctor-details-contact .basic-info li:last-child {
  margin-bottom: 0;
}
.doctor-details-item .doctor-details-contact .basic-info li i {
  display: inline-block;
  color: #ffd900;
  margin-right: 8px;
  font-size: 16px;
  position: relative;
  top: 1px;
}
.doctor-details-area .doctor-details-left .social {
  margin-top: 25px;
}
.doctor-details-area .doctor-details-left .social li {
  display: inline-block;
  margin-right: 10px;
}
.doctor-details-area .doctor-details-left .social li:last-child {
  margin-right: 0px;
}
.doctor-details-area .doctor-details-left .social li a {
  height: 34px;
  width: 34px;
  line-height: 34px;
  text-align: center;
  border: 1px solid #c8c8c8;
  text-align: center;
  padding: 0;
  border-radius: 4px;
  display: block;
  color: #757575;
  font-size: 16px;
}
.doctor-details-area .doctor-details-left .social li a:hover {
  color: #fff;
  background: #ffd900;
  border-color: transparent;
}
.doctor-details-item .doctor-details-work h3 {
  font-weight: 600;
  font-size: 20px;
  color: #2c2d3f;
  margin-top: 30px;
}
.doctor-details-item .doctor-details-work .time-sidual {
}
.doctor-details-item .doctor-details-work .time-sidual {
  overflow: hidden;
}
.doctor-details-item .doctor-details-work .time-sidual li {
  display: block;
  color: #2c2d3f;
  width: 100%;
  margin-bottom: 10px;
}
.doctor-details-item .doctor-details-work .time-sidual li span {
  display: inline-block;
  float: right;
}
.doctor-details-item .doctor-details-work .day-head .time {
  font-weight: 400;
  float: right;
}

.doctor-details-area .doctor-details-right {
  padding-left: 60px;
  padding-top: 70px;
}
.doctor-details-item .doctor-details-biography {
}
.doctor-details-item .doctor-details-biography h3 {
  font-weight: 600;
  font-size: 24px;
  color: #ffd900;
  margin-bottom: 25px;
  margin-top: 25px;
}
.doctor-details-item .doctor-details-biography p {
  margin-bottom: 0;
}
.doctor-details-item .doctor-details-biography ul {
  margin: 0;
  padding: 0;
}
.doctor-details-item .doctor-details-biography ul li {
  list-style-type: none;
  display: block;
  margin-bottom: 10px;
}
.doctor-details-item .doctor-details-biography ul li:last-child {
  margin-bottom: 0;
}
.doctor-details-item .doctor-name .name {
  font-size: 30px;
  font-weight: 600;
}
.doctor-details-item .doctor-name .deg {
  font-size: 22px;
  margin: 10px 0 5px 0;
}
.doctor-details-item .doctor-name .degree {
  font-size: 16px;
}
/*====================
	End Team CSS
======================*/

/*=======================
	Start Blog CSS
=========================*/
.blog {
  background: #fff;
}
.blog .blog-title {
  text-align: center;
}
.blog .single-news {
  background: #fff;
  position: relative;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  box-shadow: 0px 0px 10px #00000014;
}
.blog .single-news img {
  width: 100%;
  margin: 0;
  padding: 0;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.blog .single-news .news-head {
  position: relative;
  overflow: hidden;
}
.blog .single-news .news-content {
  text-align: left;
  background: #fff;
  z-index: 99;
  position: relative;
  padding: 30px;
  left: 0;
  z-index: 0;
}
.blog .single-news .news-content:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 2px;
  width: 0%;
  background: #ffd900;
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.blog .single-news:hover .news-content:before {
  opacity: 1;
  visibility: visible;
  width: 100%;
}
.blog .single-news .news-body h2 {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 10px;
  line-height: 24px;
}
.blog .single-news .news-body h2 a {
  color: #2c2d3f;
  font-weight: 500;
}
.blog .single-news .news-body h2 a:hover {
  color: #ffd900;
}
.blog .single-news .news-content p {
  font-weight: 400;
  text-transform: capitalize;
  font-size: 13px;
  letter-spacing: 0px;
  line-height: 23px;
}
.blog .single-news .news-body .date {
  display: inline-block;
  font-size: 14px;
  margin-bottom: 5px;
  background: #ffd900;
  color: #fff;
  padding: 4px 15px;
  border-radius: 3px;
  font-size: 14px;
  margin-bottom: 10px;
}
.blog.grid .single-news {
  margin-top: 30px;
}
/* Blog Sidebar */
.main-sidebar {
  background: #fff;
  margin-top: 30px;
  background: transparent;
}
.main-sidebar .single-widget {
  margin-bottom: 30px;
  padding: 40px;
  background: #fff;
  -webkit-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  -moz-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}
.main-sidebar .single-widget .title {
  position: relative;
  font-size: 18px;
  font-weight: 600;
  text-transform: capitalize;
  margin-bottom: 30px;
  display: block;
  background: #fff;
  padding-left: 12px;
}
.main-sidebar .single-widget .title::before {
  position: absolute;
  content: "";
  left: 0;
  bottom: -1px;
  height: 100%;
  width: 3px;
  background: #ffd900;
}
.main-sidebar .single-widget:last-child {
  margin: 0;
}
.main-sidebar .search {
  position: relative;
}
.main-sidebar .search input {
  width: 100%;
  height: 45px;
  box-shadow: none;
  text-shadow: none;
  font-size: 14px;
  border: none;
  color: #222;
  background: transparent;
  padding: 0 70px 0 20px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  border-radius: 0;
  border: 1px solid #eee;
  border-radius: 5px;
}
.main-sidebar .search .button {
  position: absolute;
  right: 40px;
  top: 40px;
  height: 44px;
  width: 50px;
  line-height: 45px;
  box-shadow: none;
  text-shadow: none;
  text-align: center;
  border: none;
  font-size: 14px;
  color: #fff;
  background: #333;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  border-radius: 0 5px 5px 0;
}
.main-sidebar .search .button:hover {
  background: #ffd900;
  color: #fff;
}
/* Category List */
.main-sidebar .categor-list {
  margin-top: 15px;
}
.main-sidebar .categor-list li {
  margin-bottom: 10px;
}
.main-sidebar .categor-list li:last-child {
  margin-bottom: 0px;
}
.main-sidebar .categor-list li a {
  display: inline-block;
  color: #333;
  font-size: 14px;
}
.main-sidebar .categor-list li a:hover {
  color: #ffd900;
  padding-left: 7px;
}
.main-sidebar .categor-list li a i {
  display: inline-block;
  margin-right: 0px;
  font-size: 9px;
  transform: translateY(-1px);
  opacity: 0;
  visibility: hidden;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.main-sidebar .categor-list li a:hover i {
  margin-right: 6px;
  opacity: 1;
  visibility: visible;
}
/* Recent Posts */
.main-sidebar .single-post {
  position: relative;
  border-bottom: 1px solid #ddd;
  display: inline-block;
  padding: 17px 0;
}
.main-sidebar .single-post:last-child {
  padding-bottom: 0px;
  border: none;
}
.main-sidebar .single-post .image img {
  float: left;
  width: 80px;
  height: 80px;
  margin-right: 20px;
}
.main-sidebar .single-post .content {
  padding-left: 100px;
}
.main-sidebar .single-post .content h5 {
  line-height: 18px;
}
.main-sidebar .single-post .content h5 a {
  color: #2c2d3f;
  font-weight: 500;
  font-size: 14px;
  font-weight: 500;
  margin-top: 10px;
  display: block;
  margin-bottom: 10px;
  margin-top: 0;
}
.main-sidebar .single-post .content h5 a:hover {
  color: #ffd900;
}
.main-sidebar .single-post .content .comment li {
  color: #888;
  display: inline-block;
  margin-right: 15px;
  font-weight: 400;
  font-size: 14px;
}
.main-sidebar .single-post .content .comment li:last-child {
  margin-right: 0;
}
.main-sidebar .single-post .content .comment li i {
  display: inline-block;
  margin-right: 5px;
}
/* Blog Tags */
.main-sidebar .side-tags .tag {
  margin-top: 40px;
}
.main-sidebar .side-tags .tag li {
  display: inline-block;
  margin-right: 7px;
  margin-bottom: 20px;
}
.main-sidebar .side-tags .tag li a {
  background: #fff;
  color: #333;
  padding: 8px 14px;
  text-transform: capitalize;
  border-radius: 0;
  font-size: 13px;
  background: #f6f7fb;
  border-radius: 4px;
}
.main-sidebar .side-tags .tag a:hover {
  color: #fff;
  background: #ffd900;
  border-color: transparent;
}
/* News Single */
.news-single {
  padding: 60px 0 90px;
  background: #f8f8f8;
}
.news-single .single-main {
  margin-top: 30px;
  background: #fff;
  padding: 30px;
  -webkit-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  -moz-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}
.news-single .news-head {
}
.news-single .news-head img {
  width: 100%;
  height: 100%;
}
.news-single .news-title {
  font-size: 25px;
  margin: 20px 0;
}
.news-single .news-title a {
  color: #252525;
  font-weight: 600;
}
.news-single .news-title a:hover {
  color: #ffd900;
}
/* Blog Meta */
.news-single {
  background: #fff;
}
.news-single .meta {
  overflow: hidden;
  border-top: 1px solid #ebebeb;
  border-bottom: 1px solid #ebebeb;
  width: 100%;
  padding: 10px 0;
  margin-bottom: 15px;
}
.news-single .meta span {
  margin-right: 10px;
  display: inline-block;
}
.news-single .meta span:last-child {
  margin: 0;
}
.news-single .meta span,
.news-single .meta span a {
  color: #2c2d3f;
  font-weight: 400;
}
.news-single .meta span i {
  margin-right: 5px;
  color: #ffd900;
}
.news-single .meta-left {
  float: left;
}
.news-single .meta-left .author img {
  width: 45px;
  height: 45px;
  border-radius: 100%;
  margin-right: 12px;
}
.news-single .meta-left .author {
  float: left;
}
.news-single .meta-left span.date {
  margin-top: 10px;
}
.news-single .meta-right {
  float: right;
  margin-top: 10px;
}
.news-single .news-content {
  margin: 20px 0;
}
.news-single .news-content p {
  margin-bottom: 10px;
}
.news-single .news-content p:last-child {
  margin: 0;
}
.news-single .news-text p {
  font-size: 14px;
  margin-bottom: 20px;
}
/* Image Gallery */
.news-single .image-gallery {
  margin-bottom: 20px;
}
.news-single .image-gallery .single-image {
  overflow: hidden;
}
.news-single .image-gallery .single-image:hover img {
  -webkit-transform: scale(1.2);
  -moz-transform: scale(1.2);
  transform: scale(1.2);
}
/* Blockqoute */
.news-single blockquote {
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  padding: 30px;
  overflow: hidden;
}
.news-single blockquote::before {
  opacity: 0.9;
}
.news-single .news-text blockquote p {
  color: #fff;
  margin: 0;
  line-height: 26px;
  font-size: 15px;
  position: relative;
}
.news-single .blog-bottom {
  overflow: hidden;
}
/* Social Share */
.news-single .social-share {
  float: left;
}
.news-single .social-share li {
  float: left;
}
.news-single .social-share li span {
  padding-left: 5px;
}
.news-single .social-share li.facebook a {
  background: #5d82d1;
}
.news-single .social-share li.twitter a {
  background: #40bff5;
}
.news-single .social-share li.google-plus a {
  background: #eb5e4c;
}
.news-single .social-share li.linkedin a {
  background: #238cc8;
}
.news-single .social-share li.pinterest a {
  background: #e13138;
}
.news-single .social-share li a {
  padding: 10px 20px;
  display: block;
  color: #fff;
}
.news-single .social-share li a:hover {
  background: #2b343e;
}
/* Prev Next Button */
.news-single .prev-next {
  float: right;
}
.news-single .prev-next li {
  display: inline-block;
  padding: 0;
  margin-right: 5px;
}
.news-single .prev-next li:last-child {
  border: none;
}
.news-single .prev-next li a {
  display: block;
  width: 40px;
  height: 40px;
  line-height: 36px;
  text-align: center;
  font-size: 16px;
  border: 1px solid #c4c4c4;
  color: #555;
  border-radius: 4px;
}
.news-single .prev-next li a:hover {
  color: #fff;
  background: #ffd900;
  border-color: transparent;
}
/* Blog Comments */
.news-single .blog-comments {
  margin-top: 30px;
  background: transparent;
  -webkit-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  -moz-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 30px;
}
.news-single .blog-comments h2 {
  text-align: left;
  text-transform: capitalize;
  font-size: 18px;
  color: #252525;
  margin-bottom: 20px;
}
.news-single .blog-comments h4 span {
  float: right;
}
.news-single .single-comments {
  overflow: hidden;
  margin-bottom: 30px;
  background: #fff;
  border-bottom: 1px solid #eee;
  padding-bottom: 30px;
}
.news-single .single-comments.left .main {
  padding-left: 100px;
  position: relative;
  margin-left: 100px;
}
.news-single .single-comments.left img {
  position: absolute;
  left: 0;
  top: 0;
}
.news-single .single-comments:last-child {
  margin: 0;
  border: none;
  margin-bottom: 0;
  padding-bottom: 0;
}
.news-single .single-comments .main {
  overflow: hidden;
}
.news-single .single-comments .head {
  float: left;
  margin-right: 20px;
  text-align: center;
  width: 12%;
}
.news-single .head img {
  width: 80px;
  height: 80px;
  line-height: 80px;
  border-radius: 100%;
  border: 5px solid #f8f8f8;
}
.news-single .single-comments .body {
  float: left;
  width: 85%;
}
.news-single .single-comments.left .body {
  float: noene;
  width: 100%;
}
.news-single .single-comments .comment-list {
  margin-top: 30px;
  padding-top: 30px;
  border-top: 1px solid #e2e2e2;
  overflow: hidden;
}
.news-single .single-comments .comment-list .body {
  width: 78%;
}
.news-single .single-comments h4 {
  margin: 0 0 5px;
  font-size: 16px;
  text-align: left;
  font-weight: 500;
  color: #252525;
}
.news-single .single-comments .comment-meta {
  margin-bottom: 5px;
}
.news-single .single-comments .meta {
  font-size: 13px;
  color: #555;
  font-weight: 400;
  border: none;
  margin-right: 10px;
  padding: 0;
  margin: 0 10px 0 0;
}
.news-single .single-comments .meta:last-child {
  margin: 0;
}
.news-single .comment-meta span i {
  margin-right: 5px;
}
.news-single .comment-meta span:last-child {
  margin: 0;
}
.news-single .single-comments p {
  font-size: 13px;
}
.news-single .single-comments a {
  text-transform: capitalize;
  font-size: 13px;
  font-weight: 400;
  color: #fff;
  padding: 3px 15px;
  display: inline-block;
  margin-top: 10px;
  border-radius: 4px;
  background: #ffd900;
  color: #fff;
}
.news-single .single-comments a:hover {
  background: #2c2d3f;
  color: #fff;
}
.news-single .single-comments a i {
  margin-right: 5px;
}
.news-single .comment-list {
  padding-left: 50px;
}
.news-single .single-comments.login {
  text-align: center;
}
.news-single .single-comments.login i {
  font-size: 20px;
}
.news-single .single-comments.login a {
  text-align: center;
}
.news-single .single-comments.login a:hover {
  color: #353535;
}
.news-single .comments-form {
  margin-top: 30px;
  -webkit-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  -moz-box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  padding: 30px;
}
.news-single .comments-form h2 {
  text-align: left;
  font-size: 18px;
  color: #353535;
  margin-bottom: 20px;
  text-transform: capitalize;
}
.news-single .form {
}
.news-single .form-group {
  position: relative;
  display: block;
  margin: 0 0 20px;
}
.news-single .form-group i {
  position: absolute;
  left: 12px;
  top: 17px;
  z-index: 1;
  color: #ffd900;
}
.news-single .form-group input {
  width: 100%;
  height: 50px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  font-weight: 400;
  border-radius: 0px;
  padding-left: 34px;
  padding-right: 20px;
  border: none;
  line-height: 50px;
  font-weight: 400;
  font-size: 14px;
  color: #2c2d3f;
}
.news-single .form-group textarea {
  border: 1px solid #ddd;
  width: 100%;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  box-shadow: none;
  border-radius: 0px;
  border: none;
  height: 190px;
  padding: 15px 15px 15px 35px;
  resize: none;
  font-weight: 400;
  font-size: 14px;
  color: #2c2d3f;
}
.news-single .form-group input,
.news-single .form-group textarea {
  border: 1px solid transparent;
  border: 1px solid #eee;
  border-radius: 5px;
}
.news-single .form-group.message i {
  top: 22px;
}
.news-single .form-group .button {
  padding: 10px 30px;
  font-size: 14px;
  text-transform: uppercase;
  display: block;
  border: 0px solid;
  color: #fff;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  padding: 15px 30px;
}
.news-single .form-group .button:hover {
  background: #353535;
}
.news-single .form-group .button i {
  position: relative;
  display: inline-block;
  color: #fff;
  margin-right: 10px;
  padding: 0px;
}
.news-single .form-group.button {
  margin: 0;
  text-align: left;
}
.news-single .form-group.button .btn {
  background: #fff;
  background: #ffd900;
  color: #fff;
}
.news-single .form-group.button .btn:hover {
  color: #fff;
}
.news-single .form-group.button .btn i {
  color: #fff;
  position: relative;
  top: 0;
  left: 0;
  margin-right: 10px;
  -webkit-transition: all 0.3s ease 0s;
  -moz-transition: all 0.3s ease 0s;
  transition: all 0.3s ease 0s;
}
/*===================
	End Blog CSS
=====================*/

/*==========================
	Start Appointment CSS
============================*/
.appointment {
  background: #fff;
  padding-top: 100px;
}
.appointment.single-page {
  background: #fff;
  padding-top: 100px 0;
  padding: 0;
  padding: 100px 0;
}
.appointment.single-page .appointment-inner {
  padding: 40px;
  box-shadow: 0px 0px 10px #00000024;
  border-radius: 5px;
}
.appointment.single-page .title {
}
.appointment.single-page .title h3 {
  font-size: 25px;
  display: block;
  margin-bottom: 10px;
  font-weight: 600;
}
.appointment.single-page .title p {
}
.appointment .form {
  margin-top: 30px;
}
.appointment .form .form-group {
}
.appointment .form input {
  width: 100%;
  height: 50px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 0px 18px;
  color: #555;
  font-size: 14px;
  font-weight: 400;
  border-radius: 0;
  border-radius: 4px;
}
.appointment .form textarea {
  width: 100%;
  height: 200px;
  padding: 18px;
  border: 1px solid #eee;
  text-transform: capitalize;
  resize: none;
  border-radius: 4px;
}
.appointment .form-group .nice-select {
  width: 100%;
  height: 50px;
  line-height: 50px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 0px 18px;
  color: #999;
  font-size: 14px;
  font-weight: 400;
  border-radius: 4px;
  font-weight: 400;
}
.appointment .form-group .nice-select::after {
  right: 20px;
  color: #757575;
}
.appointment .form-group .list {
  border-radius: 4px;
}
.appointment .form-group .list li {
  color: #757575;
  border-radius: 0;
}
.appointment .form-group .list li.selected {
  color: #757575;
  font-weight: 400;
}
.appointment .form-group .list li:hover {
  color: #fff;
  background: #ffd900;
}
.appointment .appointment-image {
}
.appointment.single-page .button .btn {
  width: 100%;
}
.appointment .button .btn {
  font-weight: 500;
}
.appointment .button .btn:hover {
  color: #fff;
}
.appointment .form p {
  margin-top: 10px;
  color: #868686;
}
.appointment.single-page .work-hour {
  background: #ffd900;
  padding: 40px;
  box-shadow: 0px 0px 10px #00000024;
  border-radius: 5px;
}
.appointment.single-page .work-hour h3 {
  font-size: 25px;
  display: block;
  font-weight: 600;
  margin-bottom: 20px;
  color: #fff;
}
.appointment.single-page .time-sidual {
  margin-top: 15px;
}
.appointment.single-page .time-sidual {
  overflow: hidden;
}
.appointment.single-page .time-sidual li {
  display: block;
  color: #fff;
  width: 100%;
  margin-bottom: 10px;
}
.appointment.single-page .time-sidual li span {
  display: inline-block;
  float: right;
}
.appointment.single-page .day-head .time {
  font-weight: 400;
  float: right;
}
/*==========================
	End Appointment CSS
============================*/

/*====================
   Start Login CSS
======================*/
.login .inner {
  box-shadow: 0px 0px 10px #00000024;
  border-radius: 5px;
  overflow: hidden;
}
.login .login-left {
  background-image: url(img/signup-bg.jpg);
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
}
.login .login-form {
  padding: 50px 40px;
}
.login .login-form h2 {
  position: relative;
  font-size: 32px;
  color: #333;
  font-weight: 600;
  line-height: 27px;
  text-transform: capitalize;
  margin-bottom: 12px;
  padding-bottom: 20px;
  text-align: left;
}
.login .login-form h2:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 2px;
  width: 50px;
  background: #ffd900;
}
.login .login-form p {
  font-size: 14px;
  color: #333;
  font-weight: 400;
  text-align: left;
  margin-bottom: 50px;
}
.login .login-form p a {
  display: inline-block;
  margin-left: 5px;
  color: #ffd900;
}
.login .login-form p a:hover {
  color: #2c2d3f;
}
.login .form {
  margin-top: 30px;
}
.login .form .form-group {
  margin-bottom: 22px;
}
.login .form .form-group input {
  width: 100%;
  height: 50px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 0px 18px;
  color: #555;
  font-size: 14px;
  font-weight: 400;
  border-radius: 4px;
}
.login .form .form-group.login-btn {
  margin: 0;
}
.login .form button {
  border: none;
}
.login .form .btn {
  display: inline-block;
  margin-right: 10px;
  color: #fff;
  line-height: 20px;
  width: 100%;
}
.login .form .btn:hover {
  background: #ffd900;
  color: #fff;
}
.login .login-form .checkbox {
  text-align: left;
  margin: 0;
  margin-top: 20px;
  display: inline-block;
}
.login .login-form .checkbox label {
  font-size: 14px;
  font-weight: 400;
  color: #333;
  position: relative;
  padding-left: 20px;
}
.login .login-form .checkbox label:hover {
  cursor: pointer;
}
.login .login-form .checkbox label input {
  display: none;
}
.login .login-form .checkbox label::before {
  position: absolute;
  content: "";
  left: 0;
  top: 5px;
  width: 15px;
  height: 15px;
  border: 1px solid #ffd900;
  border-radius: 100%;
}
.login .login-form .checkbox label::after {
  position: relative;
  content: "";
  width: 7px;
  height: 7px;
  left: -16px;
  top: -15px;
  opacity: 0;
  visibility: hidden;
  transform: scale(0);
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  display: block;
  font-size: 9px;
  background: #ffd900;
  border-radius: 100%;
}
.login .login-form .checkbox label.checked::after {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}
.login .login-form .lost-pass {
  display: inline-block;
  margin-left: 25px;
  color: #333;
  font-size: 14px;
  font-weight: 400;
}
.login .login-form .lost-pass:hover {
  color: #ffd900;
}
/*====================
   End Login CSS
======================*/

/*=========================
   Start Register CSS
===========================*/
.register .inner {
  box-shadow: 0px 0px 10px #00000024;
  border-radius: 5px;
  overflow: hidden;
}
.register .register-left {
  background-image: url(img/signup-bg.jpg);
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  width: 100%;
  height: 100%;
}
.register .register-form {
  padding: 50px 40px;
}
.register .register-form h2 {
  position: relative;
  font-size: 32px;
  color: #333;
  font-weight: 600;
  line-height: 27px;
  text-transform: capitalize;
  margin-bottom: 12px;
  padding-bottom: 20px;
  text-align: left;
}
.register .register-form h2:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 2px;
  width: 50px;
  background: #ffd900;
}
.register .register-form p {
  font-size: 14px;
  color: #333;
  font-weight: 400;
  text-align: left;
  margin-bottom: 50px;
}
.register .register-form p a {
  display: inline-block;
  margin-left: 5px;
  color: #ffd900;
}
.register .register-form p a:hover {
  color: #2c2d3f;
}
.register .form {
  margin-top: 30px;
}
.register .form .form-group {
  margin-bottom: 22px;
}
.register .form .form-group input {
  width: 100%;
  height: 50px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 0px 18px;
  color: #555;
  font-size: 14px;
  font-weight: 400;
  border-radius: 4px;
}
.register .form .form-group.login-btn {
  margin: 0;
}
.register .form button {
  border: none;
}
.register .form .btn {
  display: inline-block;
  margin-right: 10px;
  color: #fff;
  line-height: 20px;
  width: 100%;
}
.register .form .btn:hover {
  background: #ffd900;
  color: #fff;
}
.register .register-form .checkbox {
  text-align: left;
  margin: 0;
  margin-top: 20px;
  display: inline-block;
}
.register .register-form .checkbox label {
  font-size: 14px;
  font-weight: 400;
  color: #333;
  position: relative;
  padding-left: 20px;
}
.register .register-form .checkbox label:hover {
  cursor: pointer;
}
.register .register-form .checkbox label input {
  display: none;
}
.register .register-form .checkbox label::before {
  position: absolute;
  content: "";
  left: 0;
  top: 5px;
  width: 15px;
  height: 15px;
  border: 1px solid #ffd900;
  border-radius: 100%;
}
.register .register-form .checkbox label::after {
  position: relative;
  content: "";
  width: 7px;
  height: 7px;
  left: -16px;
  top: -15px;
  opacity: 0;
  visibility: hidden;
  transform: scale(0);
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  display: block;
  font-size: 9px;
  background: #ffd900;
  border-radius: 100%;
}
.register .register-form .checkbox label.checked::after {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}
.register .register-form .terms {
  display: inline-block;
  margin-left: 5px;
  color: #1a76d1;
}
.register .register-form .terms:hover {
  color: #2c2d3f;
}
/*=========================
   End Register CSS
===========================*/

/*=====================
   Start Faq CSS
=======================*/
.faq-head h2 {
  margin-bottom: 35px;
  font-weight: 600;
  font-size: 25px;
}
.faq-wrap {
  margin-bottom: 50px;
}
.faq-wrap:last-child {
  margin-bottom: 30px;
}
.accordion {
  padding-left: 0;
  margin: 0;
  padding: 0;
}
.accordion p {
  font-size: 15px;
  display: none;
  padding: 20px 45px 15px 20px;
  margin-bottom: 0;
}
.accordion a {
  font-size: 16px;
  width: 100%;
  display: block;
  cursor: pointer;
  font-weight: 400;
  padding: 15px 0 15px 18px;
  border-radius: 0;
  background: #fff;
  color: #333;
  border: 1px solid #eee;
}
.accordion a:hover {
  color: #fff !important;
  background: #1a76d1 !important;
}
.accordion a:after {
  position: absolute;
  right: 20px;
  content: "+";
  top: 16px;
  color: #232323;
  font-size: 25px;
  font-weight: 700;
}
.accordion a:hover:after {
  color: #fff !important;
}
.accordion li {
  position: relative;
  list-style-type: none;
  margin-bottom: 30px;
}
.accordion li:first-child {
  border-top: 0;
}
.accordion li:last-child {
  margin-bottom: 0;
}
.accordion li a.active {
  color: #ffffff;
  background-color: #ffd900;
  border: 1px solid #ffd900;
}
.accordion li a.active:after {
  content: "-";
  font-size: 25px;
  color: #ffffff;
}
/*=====================
   End Faq CSS
=======================*/

/*=========================
   Start Contact Us CSS
===========================*/
.contact-us .inner {
  box-shadow: 0px 0px 10px #00000024;
  border-radius: 5px;
  overflow: hidden;
}
.contact-us .contact-us-left {
  width: 100%;
  height: 100%;
}
.contact-us .contact-us-form {
  padding: 50px 40px;
}
.contact-us .contact-us-form h2 {
  position: relative;
  font-size: 32px;
  color: #333;
  font-weight: 600;
  line-height: 27px;
  text-transform: capitalize;
  margin-bottom: 12px;
  padding-bottom: 20px;
  text-align: left;
}
.contact-us .contact-us-form h2:before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0;
  height: 2px;
  width: 50px;
  background: #ffd900;
}
.contact-us .contact-us-form p {
  font-size: 14px;
  color: #333;
  font-weight: 400;
  text-align: left;
  margin-bottom: 50px;
}
.contact-us .form {
  margin-top: 30px;
}
.contact-us .form .form-group {
  margin-bottom: 22px;
}
.contact-us .form .form-group input {
  width: 100%;
  height: 50px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 0px 18px;
  color: #555;
  font-size: 14px;
  font-weight: 400;
  border-radius: 4px;
}
.contact-us .form .form-group textarea {
  width: 100%;
  height: 100px;
  border: 1px solid #eee;
  text-transform: capitalize;
  padding: 18px;
  color: #555;
  font-size: 14px;
  font-weight: 400;
  border-radius: 4px;
}
.contact-us .form .form-group.login-btn {
  margin: 0;
}
.contact-us .form button {
  border: none;
}
.contact-us .form .btn {
  display: inline-block;
  margin-right: 10px;
  color: #fff;
  line-height: 20px;
  width: 100%;
}
.contact-us .form .btn:hover {
  background: #ffd900;
  color: #fff;
}
.contact-us .contact-us-form .checkbox {
  text-align: left;
  margin: 0;
  margin-top: 20px;
  display: inline-block;
}
.contact-us .contact-us-form .checkbox label {
  font-size: 14px;
  font-weight: 400;
  color: #333;
  position: relative;
  padding-left: 20px;
}
.contact-us .contact-us-form .checkbox label:hover {
  cursor: pointer;
}
.contact-us .contact-us-form .checkbox label input {
  display: none;
}
.contact-us .contact-us-form .checkbox label::before {
  position: absolute;
  content: "";
  left: 0;
  top: 5px;
  width: 15px;
  height: 15px;
  border: 1px solid #ffd900;
  border-radius: 100%;
}
.contact-us .contact-us-form .checkbox label::after {
  position: relative;
  content: "";
  width: 7px;
  height: 7px;
  left: -16px;
  top: -15px;
  opacity: 0;
  visibility: hidden;
  transform: scale(0);
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  display: block;
  font-size: 9px;
  background: #ffd900;
  border-radius: 100%;
}
.contact-us .contact-us-form .checkbox label.checked::after {
  opacity: 1;
  visibility: visible;
  transform: scale(1);
}
.contact-us .contact-info {
  margin-top: 50px;
}
.contact-us .single-info {
  background: #ffd900;
  padding: 40px 60px;
  height: 150px;
  border-radius: 10px;
  position: relative;
  -webkit-transition: all 0.3s ease-out 0s;
  -moz-transition: all 0.3s ease-out 0s;
  -ms-transition: all 0.3s ease-out 0s;
  -o-transition: all 0.3s ease-out 0s;
  transition: all 0.3s ease-out 0s;
}
.contact-us .single-info:before {
  position: absolute;
  z-index: -1;
  content: "";
  bottom: -10px;
  left: 0;
  right: 0;
  margin: 0 auto;
  width: 80%;
  height: 90%;
  background: #ffd900;
  opacity: 0;
  filter: blur(10px);
  -webkit-transition: all 0.3s ease-out 0s;
  -moz-transition: all 0.3s ease-out 0s;
  -ms-transition: all 0.3s ease-out 0s;
  -o-transition: all 0.3s ease-out 0s;
  transition: all 0.3s ease-out 0s;
}
.contact-us .single-info:hover:before {
  opacity: 0.8;
}
.contact-us .single-info:hover {
  transform: translateY(-5px);
}
.contact-us .single-info i {
  font-size: 42px;
  color: #fff;
  position: absolute;
  left: 40px;
}
.contact-us .single-info .content {
  margin-left: 45px;
}
.contact-us .single-info .content h3 {
  color: #fff;
  font-size: 18px;
  font-weight: 600;
}
.contact-us .single-info .content p {
  color: #fff;
  margin-top: 5px;
}
/* Google Map */
.contact-us #myMap {
  height: 100%;
  width: 100%;
}
/*=========================
   End Contact Us CSS
===========================*/

/*========================
	Start Error 404 CSS
==========================*/
.error-page {
  text-align: center;
  background: #fff;
  border-top: 1px solid #eee;
}
.error-page .error-inner {
  display: inline-block;
}
.error-page .error-inner h1 {
  font-size: 140px;
  text-shadow: 3px 5px 2px #3333;
  color: #ffd900;
  font-weight: 700;
}
.error-page .error-inner h1 span {
  display: block;
  font-size: 25px;
  color: #333;
  font-weight: 600;
  text-shadow: none;
}
.error-page .error-inner p {
  padding: 20px 15px;
}
.error-page .search-form {
  width: 100%;
  position: relative;
}
.error-page .search-form input {
  width: 400px;
  height: 50px;
  padding: 0px 78px 0 30px;
  border: none;
  background: #f6f6f6;
  border-radius: 5px;
  display: inline-block;
  margin-right: 10px;
  font-weight: 400;
  font-size: 14px;
}
.error-page .search-form input:hover {
  padding-left: 35px;
}
.error-page .search-form .btn {
  width: 80px;
  height: 50px;
  border-radius: 5px;
  cursor: pointer;
  background: #ffd900;
  display: inline-block;
  position: relative;
  top: -2px;
}
.error-page .search-form .btn i {
  font-size: 16px;
}
/*========================
	End Error 404 CSS
==========================*/

/*===========================
	Start Mail Success CSS
=============================*/
.mail-seccess {
  text-align: center;
  background: #fff;
  border-top: 1px solid #eee;
}
.mail-seccess .success-inner {
  display: inline-block;
}
.mail-seccess .success-inner h1 {
  font-size: 100px;
  text-shadow: 3px 5px 2px #3333;
  color: #1a76d1;
  font-weight: 700;
}
.mail-seccess .success-inner h1 span {
  display: block;
  font-size: 25px;
  color: #333;
  font-weight: 600;
  text-shadow: none;
  margin-top: 20px;
}
.mail-seccess .success-inner p {
  padding: 20px 15px;
}
.mail-seccess .success-inner .btn {
  color: #fff;
}
/*===========================
	End Mail Success CSS
=============================*/

/*=========================
	Start Newsletter CSS
===========================*/
.newsletter {
  background: #edf2ff;
}
.newsletter .subscribe-text {
}
.newsletter .subscribe-text h6 {
  font-size: 22px;
  margin-bottom: 10px;
  color: #2c2d3f;
}
.newsletter .subscribe-text p {
}
.newsletter .subscribe-form {
  position: relative;
}
.newsletter .common-input {
  height: 60px;
  width: 300px;
  border: none;
  color: #333;
  box-shadow: none;
  text-shadow: none;
  border-radius: 5px;
  padding: 0px 25px;
  font-weight: 500;
  font-size: 14px;
  background: #fff;
  font-weight: 400;
}
.newsletter .btn {
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  display: inline-block;
  height: 60px;
  line-height: 60px;
  padding: 0;
  width: 180px;
  position: relative;
  top: -2px;
  border-radius: 5px;
  margin-left: 10px;
  font-size: 13px;
  font-weight: 500;
  box-shadow: 0px 5px 15px rgba(188, 199, 255, 0.75);
}
.newsletter .btn:before {
  border-radius: 5px;
}
.newsletter .button:hover {
  box-shadow: none;
}
/*=========================
	End Newsletter CSS
===========================*/

/*===============================
	Start Doctor Calendar CSS
=================================*/
.doctor-calendar-area {
  position: relative;
  z-index: 1;
}
.doctor-calendar-table {
  background-color: #ffffff;
  -webkit-box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.2);
  box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.2);
}
.doctor-calendar-table table {
  margin-bottom: 0;
}
.doctor-calendar-table table thead tr th {
  vertical-align: middle;
  text-align: center;
  background-color: #1a76d1;
  border: none;
  color: #ffffff;
  text-transform: uppercase;
  white-space: nowrap;
  font-size: 16px;
  font-weight: 500;
  padding-top: 17px;
  padding-bottom: 15px;
}
.doctor-calendar-table table tbody tr td {
  vertical-align: middle;
  text-align: center;
  border: 1px solid #eeeeee;
  border-top: none;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  white-space: nowrap;
  padding-top: 25px;
  padding-right: 25px;
  padding-left: 25px;
  padding-bottom: 25px;
}
.doctor-calendar-table table tbody tr td:first-child {
  border-left: none;
}
.doctor-calendar-table table tbody tr td:last-child {
  border-right: none;
}
.doctor-calendar-table table tbody tr td h3 {
  margin-bottom: 0;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  font-size: 16px;
  font-weight: 600;
}
.doctor-calendar-table table tbody tr td span {
  display: block;
  color: #7d7d7d;
  font-size: 14.5px;
  margin-top: 5px;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}
.doctor-calendar-table table tbody tr td span.time {
  display: inline-block;
  background-color: #dff5e8;
  color: #1a76d1;
  width: 65px;
  height: 65px;
  border-radius: 50%;
  line-height: 65px;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  font-weight: 500;
  font-size: 16px;
}
.doctor-calendar-table table tbody tr td:hover {
  background-color: #ffd900;
  border-color: #ffd900;
}
.doctor-calendar-table table tbody tr td:hover h3 {
  color: #ffffff;
}
.doctor-calendar-table table tbody tr td:hover span {
  color: #ffffff;
}
.doctor-calendar-table table tbody tr td:hover span.time {
  background-color: #ffffff;
  color: #1a76d1;
}
.doctor-calendar-table table tbody tr:last-child td {
  border-bottom: none;
}
/*===============================
	End Doctor Calendar CSS
=================================*/

/*=========================
	Start About Us CSS
===========================*/
.about-area {
  position: relative;
  z-index: 1;
}
.about-image {
  width: 100%;
  height: 100%;
  background-image: url(img/about-img.jpg);
  background-position: center center;
  background-size: cover;
  background-repeat: no-repeat;
}
.about-image img {
  display: none;
}
.about-content {
  max-width: 555px;
  padding-top: 60px;
  padding-bottom: 60px;
  padding-left: 50px;
}
.about-content span {
  display: block;
  margin-bottom: 5px;
  color: #1a76d1;
  font-size: 17px;
}
.about-content h2 {
  margin-bottom: 0;
  line-height: 1.3;
  font-size: 40px;
  font-weight: 600;
}
.about-content p {
  margin-top: 10px;
  margin-bottom: 0;
}
.about-content ul {
  padding-left: 0;
  list-style-type: none;
  margin-top: 25px;
  margin-bottom: 0;
}
.about-content ul li {
  margin-bottom: 16px;
  position: relative;
  padding-left: 34px;
}
.about-content ul li i {
  width: 25px;
  height: 25px;
  line-height: 25px;
  text-align: center;
  border-radius: 100%;
  background-color: #1a76d12b;
  color: #ffd900;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  display: inline-block;
  font-size: 11px;
  position: absolute;
  left: 0;
  top: -2px;
}
.about-content ul li:hover i {
  background-color: #ffd900;
  color: #ffffff;
}
.about-content ul li:last-child {
  margin-bottom: 0;
}
.about-content .btn {
  margin-top: 30px;
}
/* Start Our Vision Area CSS */
.our-vision-area {
  position: relative;
  z-index: 1;
  padding-bottom: 70px;
}
.single-vision-box {
  margin-bottom: 30px;
  background-color: #ffffff;
  -webkit-box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.2);
  box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.2);
  padding: 25px 20px;
  position: relative;
  z-index: 1;
  -webkit-transition: 0.5s;
  transition: 0.5s;
  overflow: hidden;
}
.single-vision-box .icon {
  margin-bottom: 20px;
  text-align: center;
  width: 60px;
  height: 60px;
  line-height: 60px;
  border-radius: 100%;
  background-color: #ffd900;
  color: #ffffff;
  font-size: 25px;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}
.single-vision-box h3 {
  -webkit-transition: 0.5s;
  transition: 0.5s;
  margin-bottom: 0;
  position: relative;
  font-size: 20px;
  font-weight: 700;
}
.single-vision-box p {
  -webkit-transition: 0.5s;
  transition: 0.5s;
  margin-top: 12px;
  margin-bottom: 0;
}
.single-vision-box::before {
  width: 0;
  height: 100%;
  z-index: -1;
  content: "";
  position: absolute;
  left: 0;
  top: 0;
  background-color: #ffd900;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}
.single-vision-box::after {
  content: "";
  position: absolute;
  width: 100px;
  height: 100px;
  border: 10px solid #ffffff;
  left: -80px;
  bottom: -80px;
  border-radius: 50%;
  z-index: -1;
  opacity: 0.15;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}
.single-vision-box:hover {
  -webkit-transform: translateY(-8px);
  transform: translateY(-8px);
}
.single-vision-box:hover::before {
  width: 100%;
}
.single-vision-box:hover .icon {
  background-color: #fff;
  color: #ffd900;
}
.single-vision-box:hover h3 {
  color: #ffffff;
}
.single-vision-box:hover p {
  color: #ffffff;
}
.single-vision-box:hover::after {
  left: -50px;
  bottom: -50px;
}
/* End Our Vision CSS */

/* Our Mission Area CSS */
.our-mission-area {
  position: relative;
  z-index: 1;
}
.our-mission-image {
  width: 100%;
  height: 100%;
  background-image: url(img/mission-img.jpg);
  background-position: center center;
  background-size: cover;
  background-repeat: no-repeat;
}
.our-mission-image img {
  display: none;
}
.our-mission-content {
  max-width: 555px;
  margin-left: auto;
  padding-top: 60px;
  padding-bottom: 60px;
  padding-right: 50px;
}
.our-mission-content .sub-title {
  display: block;
  margin-bottom: 5px;
  color: #ffd900;
  font-size: 17px;
}
.our-mission-content h2 {
  margin-bottom: 0;
  line-height: 1.3;
  font-size: 40px;
  font-weight: 600;
}
.our-mission-content p {
  margin-top: 10px;
  margin-bottom: 0;
}
.our-mission-content ul {
  display: -ms-flexbox;
  display: -webkit-box;
  display: flex;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  padding-left: 0;
  list-style-type: none;
  margin-right: -15px;
  margin-left: -15px;
  margin-bottom: 0;
  margin-top: 0;
}
.our-mission-content ul li {
  -ms-flex: 0 0 50%;
  -webkit-box-flex: 0;
  flex: 0 0 50%;
  max-width: 50%;
  color: #7d7d7d;
  font-size: 14.5px;
  line-height: 1.7;
  padding-left: 15px;
  padding-right: 15px;
  padding-top: 25px;
}
.our-mission-content ul li .icon {
  margin-bottom: 13px;
  -webkit-box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.3);
  box-shadow: 0 10px 55px 5px rgba(137, 173, 255, 0.3);
  width: 50px;
  height: 50px;
  line-height: 50px;
  text-align: center;
  border-radius: 50%;
  color: #ffd900;
  font-size: 20px;
  -webkit-transition: 0.5s;
  transition: 0.5s;
}
.our-mission-content ul li span {
  display: block;
  color: #121521;
  text-transform: uppercase;
  margin-bottom: 5px;
  font-weight: 600;
  font-size: 17px;
}
.our-mission-content ul li:hover .icon {
  background-color: #ffd900;
  color: #ffffff;
  border-radius: 100%;
}
/* End Our Mission CSS */

/*=========================
	End About Us CSS
===========================*/

/*=========================
	Start Footer CSS
===========================*/
.footer {
  position: relative;
}
.footer .footer-top {
  padding: 100px 0px;
  position: relative;
  background: #bea200;
  color: black;
}
.footer .footer-top:before {
  position: absolute;
  content: "";
  left: 0;
  top: 0;
  height: 100%;
  width: 100%;
  background: #000;
  opacity: 0.1;
}
.footer .single-footer {
}
.footer .single-footer .social {
  margin-top: 25px;
}
.footer .single-footer .social li {
  display: inline-block;
  margin-right: 10px;
}
.footer .single-footer .social li:last-child {
  margin-right: 0px;
}
.footer .single-footer .social li a {
  height: 34px;
  width: 34px;
  line-height: 34px;
  text-align: center;
  border: 1px solid #fff;
  text-align: center;
  padding: 0;
  border-radius: 100%;
  display: block;
  color: #fff;
  font-size: 16px;
}
.footer .single-footer .social li a:hover {
  color: #c5a804d0;
  background: #fff;
  border-color: transparent;
}
.footer .single-footer .social li a i {
}
.footer .single-footer.f-link li a i {
  margin-right: 10px;
}
.footer .single-footer.f-link li {
  display: block;
  margin-bottom: 12px;
}
.footer .single-footer.f-link li:last-child {
  margin: 0;
}
.footer .single-footer.f-link li a {
  display: block;
  color: #fff;
  text-transform: capitalize;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  font-weight: 400;
}
.footer .single-footer.f-link li a:hover {
  padding-left: 8px;
}
.footer .single-footer h2 {
  color: #fff;
  font-size: 20px;
  font-weight: 600;
  text-transform: capitalize;
  margin-bottom: 40px;
  padding-bottom: 20px;
  text-transform: capitalize;
  position: relative;
}
.footer .single-footer h2::before {
  position: absolute;
  content: "";
  left: 0;
  bottom: 0px;
  height: 3px;
  width: 50px;
  background: #fff;
}
.footer .single-footer .time-sidual {
  margin-top: 15px;
}
.footer .single-footer .time-sidual {
  overflow: hidden;
}
.footer .single-footer .time-sidual li {
  display: block;
  color: #fff;
  width: 100%;
  margin-bottom: 5px;
}
.footer .single-footer .time-sidual li span {
  display: inline-block;
  float: right;
}
.footer .single-footer .day-head .time {
  font-weight: 400;
  float: right;
}
.footer .single-footer p {
  color: #fff;
}
.footer .single-footer .newsletter-inner {
  margin-top: 20px;
  position: relative;
}
.footer .single-footer .newsletter-inner input {
  background: transparent;
  border: 1px solid #fff;
  height: 50px;
  line-height: 42px;
  width: 100%;
  margin-right: 15px;
  color: #fff;
  padding-left: 18px;
  padding-right: 70px;
  display: inline-block;
  float: left;
  border-radius: 0px;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  font-weight: 400;
  border-radius: 5px;
}
.footer .single-footer .newsletter-inner input:hover {
  padding-left: 22px;
}
.footer input::-webkit-input-placeholder {
  opacity: 1;
  color: #fff !important;
}

.footer input::-moz-placeholder {
  opacity: 1;
  color: #fff !important;
}

.footer input::-ms-input-placeholder {
  opacity: 1;
  color: #fff !important;
}
.footer input::input-placeholder {
  opacity: 1;
  color: #fff !important;
}
.footer .single-footer .newsletter-inner .button {
  position: absolute;
  right: 0;
  top: 0;
  height: 50px;
  line-height: 50px;
  width: 50px;
  background: #fff;
  border-left: 1px solid #fff;
  text-shadow: none;
  box-shadow: none;
  display: inline-block;
  border-radius: 0px;
  border: none;
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
  border-radius: 0 5px 5px 0;
  color: #ffd900;
  font-size: 25px;
}
.footer .single-footer .newsletter-inner .button i {
  -webkit-transition: all 0.4s ease;
  -moz-transition: all 0.4s ease;
  transition: all 0.4s ease;
}
.footer .single-footer .newsletter-inner .button:hover i {
  color: #2c2d3f;
}
.footer .copyright {
  background: #ffd900;
  padding: 25px 0px 25px 0px;
  text-align: center;
}
.footer .copyright .copyright-content p {
  color: #030303;
}
.footer .copyright .copyright-content p a {
  color: #c57600;
  font-weight: 400;
  text-decoration: underline;
  display: inline-block;
  margin-left: 4px;
}
/*=========================
	End Footer CSS
===========================*/

</style>