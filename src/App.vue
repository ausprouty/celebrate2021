<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script></script>

<style>
:root {
  --color-black: #020101;
  

  --color-black-lighter: #324f23;
  --color-grey: #efefef;
  --color-grey-medium: #bbbbbb;
  --color-grey-dark: #666666;
  --color-green: #44b340;
  --color-green-dark:#297f25;
  --color-green-lighter: #9bd263;
  --color-green-lightest: #b2d292;
  --color-red: #a41121;
  --color-red-lighter: #d74787;
  --color-red-light: #d7afc1;
  --color-white: #ffffff;
  --color-yellow: #e0de78;
  --color-yellow: #edec60;
  --color-yellow-lighter: #edefc5;
}

.pointer {
  cursor: pointer;
}

a.black {
  color: var(--color-black);
  text-decoration: underline;
}
.preview {
  background-color: var(--color-white);
}
.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 60px;
}
img.nav-icon {
  width: 100%;
}
.hand {
  cursor: pointer;
}
.hidden {
  visibility: hidden;
}
html {
  -webkit-text-size-adjust: 100%;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
body {
  margin: 0;
  width: 95%;
  font-family: 'Open Sans', sans-serif;
  font-size: 16px;
  line-height: 1.5;
}
body.team {
  background-image: url('/images/background/wiggles.png');
}
div.confetti,
body.user {
  background-image: url('/images/background/confetti.png');
}
.white {
  background-color: var(--color-white);
}
img.icon {
  width: 48px;
}
div.not_authorized {
  height: 300px;
  color: var(--color-red);
  padding: 20px;
  font-size: 24px;
}
div.wrapper {
  display: block;
  width: 100%;
  overflow: hidden; /* will contain if #first is longer than #second */
}
div.icon {
  float: left; /* add this */
}
div.entry {
  overflow: hidden; /* if you don't want #second to wrap below #first */
}

#app {
  box-sizing: border-box;
  width: 90%;
  max-width: 600px;
  padding: 0px 0px 0px 0px;
  margin: 0 auto;
}
.content {
  max-width: 640px;
}
hr {
  box-sizing: content-box;
  height: 0;
  overflow: visible;
}

img {
  border-style: none;
  width: 100%;
}
h1,
h2,
h3,
h4,
h5,
h6 {
  text-align: center;
  font-family: 'Montserrat', sans-serif;
  color: var(--color-green);
}
h1 {
  font-size: 18px;
}
h2 {
  font-size: 18px;
  font-weight: 700;
}
h3 {
  font-size: 18px;
  font-weight: 700;
}
h4 {
  font-size: 16px;
  font-weight: 700;
}
h5 {
  font-size: 14px;
  font-weight: 700;
}
h6 {
  font-size: 12px;
  font-weight: 700;
}
b,
strong {
  font-weight: bolder;
}
small {
  font-size: 80%;
}
.shadow-card {
  background-color: var(--color-grey);
  margin-bottom: 12px;
  padding: 10px;
  text-align: left;
  transition: all 0.2s linear;
  width: 95%;
}
div.card-names {
  float: right;
  font-size: 18px;
  vertical-align: top;
  width: 70%;
}
.card-name {
  font-weight: bold;
  line-height: 20px;
}
.center {
  text-align: center;
}
.error {
  border: 1px solid;
}
.errorMessage {
  color: var(--color-red);
}
.eyebrow {
  font-size: 20px;
}
.-text-primary {
  color: var(--color-green);
}
.-text-base {
  color: var(--color-black);
}
.-text-error {
  color: var(--color-);
}
.-text-gray {
  color: rgba(0, 0, 0, 0.5);
}
.-shadow {
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.2), 0 1px 5px 0 rgba(0, 0, 0, 0.13);
}
.badge {
  display: inline-flex;
  height: 26px;
  width: auto;
  padding: 0 7px;
  margin: 0 5px;
  background: transparent;
  border-radius: 13px;
  font-size: 13px;
  font-weight: 400;
  line-height: 26px;
}

.italics {
  font-style: italic;
}
button,
label,
input,
optgroup,
select,
textarea {
  display: inline-flex;
  font-family: 'Open sans', sans-serif;
  font-size: 100%;
  line-height: 1.15;
  margin: 0;
}

button,
input {
  overflow: visible;
}
button,
select {
  text-transform: none;
}
button,
[type='button'],
[type='reset'],
[type='submit'] {
  -webkit-appearance: none;
}
button::-moz-focus-inner,
[type='button']::-moz-focus-inner,
[type='reset']::-moz-focus-inner,
[type='submit']::-moz-focus-inner {
  border-style: none;
  padding: 0;
}
button:-moz-focusring,
[type='button']:-moz-focusring,
[type='reset']:-moz-focusring,
[type='submit']:-moz-focusring {
  outline: 2px solid var(--color-green);
}
button {
  margin-bottom: 20px;
  background-color: var(--color-green);
  color: var(--color-white);
  font-weight: bold;
  border-radius: 6px;
  text-align: center;
  padding: 10px;
  height: 45px;
  cursor: pointer;
}
.button.red {
  background-color: var(--color-red);
}
.button.yellow {
  background-color: var(--color-yellow);
}
.button.grey {
  background-color: var(--color-grey-dark);
}
.button.right {
  margin-left: 40px;
}
img.member {
  height: 60px;
  width: auto;
}
img.sortable {
  height: 30px;
  width: 30px;
}
img.screen-shot {
  border-style: solid;
  padding: 10px;
}
label {
  color: rgba(0, 0, 0, 0.9);
  font-weight: 700;
}
p.rtl {
  direction: rtl;
}
input,
textarea {
  box-sizing: border-box;
  border: solid 1px rgba(0, 0, 0, 0.4);
}
textarea {
  width: 100%;
  overflow: auto;
  font-size: 12px;
}
[type='checkbox'],
[type='radio'] {
  box-sizing: border-box;
  padding: 0;
}
[type='number']::-webkit-inner-spin-button,
[type='number']::-webkit-outer-spin-button {
  height: auto;
}
[type='search'] {
  -webkit-appearance: textfield;
  outline-offset: -2px;
}
[type='search']::-webkit-search-decoration {
  -webkit-appearance: none;
}
[type='text'],
[type='number'],
[type='search'],
[type='password'] {
  height: 36px;
  width: 100%;
  padding: 0 10px;
}
[type='number'] {
  width: 80%;
  margin-left: 10px;
}
[type='textarea'] {
  height: 72px;
}

[type='text']:focus,
[type='number']:focus,
[type='search']:focus,
[type='password']:focus {
  border-color: var(--color-green);
}
::-webkit-file-upload-button {
  -webkit-appearance: button;
  font: inherit;
}
[hidden] {
  display: none;
}
select {
  width: 100%;
  height: 36px;
  padding: 0 24px 0 10px;
  vertical-align: middle;
  background: var(--color-white)
    url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E")
    no-repeat right 12px center;
  background-size: 8px 10px;
  border: solid 1px rgba(0, 0, 0, 0.4);
  border-radius: 0;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}
select:focus {
  border-color: var(--color-green);
  outline: 0;
}
select:focus::ms-value {
  color: var(--color-black);
  background: var(--color-white);
}
select::ms-expand {
  opacity: 0;
}
.app-card {
  padding: 10px;
  width: 95%;
  background-color: var(--color-grey);
  margin-bottom: 12px;
  transition: all 0.2s linear;
}
.app-card:hover {
  transform: scale(1.01);
  box-shadow: 0 3px 12px 0 rgba(0, 0, 0, 0.2), 0 1px 15px 0 rgba(0, 0, 0, 0.19);
}
.app-card > .title {
  margin: 0;
}
li {
  text-align: left;
}
div.bible {
  text-align: left;
}
.left {
  text-align: left;
}
.menu-card {
  margin-left: 12px;
  padding: 10px;
  width: 50%;
  background-color: var(--color-red);
  text-align: left;
  color: white;
  margin-bottom: 5px;
  transition: all 0.2s linear;
}
img.app-img-header {
  width: 100%;
}
.app-link {
  color: var(--color-black);

  text-decoration: none;
  font-weight: 100;
}
img.help-icon {
  height: 30px;
  width: 30px;
  padding-left: 30px;
  padding-right: 30px;
}
label,
.bold {
  font-weight: bold;
}
</style>
